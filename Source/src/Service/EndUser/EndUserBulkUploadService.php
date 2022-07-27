<?php

declare(strict_types=1);

namespace App\Service\EndUser;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Dto\EndUser\EndUserBulkUploadFileDto;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Message\EndUserBulkCreateMessage;
use App\Service\Company\CompanyService;
use App\Service\File\UserIterator;
use App\Service\File\UserRow;
use App\Service\FolderService;
use App\Traits\I18NServiceTrait;
use App\Traits\SymfonyDenormalizerTrait;
use App\Traits\ValidatorTrait;
use Carbon\Carbon;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\EndUser\Listing;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndUserBulkUploadService
{
    use SymfonyDenormalizerTrait;
    use ValidatorTrait;
    use I18NServiceTrait;

    public const TEMP_END_USER_FILE_PATH = '/'.FolderService::TEMP_FILES_FOLDER.'/'.FolderService::END_USER_BULK_UPLOAD_TEMP_FILES;
    public const USER_LIST_HEADER = ['First Name', 'Last Name', 'Status', 'Locked', 'Date of Birth', 'Gender', 'Registration Date'];

    private Filesystem $filesystem;
    private CompanyService $companyService;
    private FolderService $folderService;
    private EndUserManager $endUserManager;

    public function __construct(Filesystem $filesystem, CompanyService $companyService, FolderService $folderService, EndUserManager $endUserManager)
    {
        $this->filesystem = $filesystem;
        $this->folderService = $folderService;
        $this->companyService = $companyService;
        $this->endUserManager = $endUserManager;
    }

    public function prepareEndUserTable(Listing $users): array
    {
        $result = [];

        $language = $this->i18NService->getLanguageFromRequest();

        $header = self::USER_LIST_HEADER;

        $firstUser = $users->current();
        $companyCustomFields = [];

        if ($firstUser instanceof EndUser) {
            $company = $firstUser->getCompany();
            $companyCustomFields = $company instanceof Company ? $this->companyService->prepareCustomFields($company) : [];
            foreach ($companyCustomFields as $field) {
                $header[] = $field->name[$language] ?? $field->key;
            }
        }

        $result[] = $header;

        /** @var EndUser $endUser */
        foreach ($users as $endUser) {
            $userCustomFields = $this->endUserManager->prepareCustomFields($endUser);
            $data = [
                $endUser->getFirstname(),
                $endUser->getLastname(),
                $endUser->getStatus(),
                $endUser->getUserLocked(),
                $endUser->getDateOfBirth()?->format('Y-m-d'),
                $endUser->getGender(),
                $endUser->getRegistrationDate(),
            ];

            foreach ($companyCustomFields as $field) {
                $fieldName = $field->name[$language] ?? $field->key;
                $data[] = $userCustomFields[$fieldName] ?? null;
            }

            $result[] = $data;
        }

        return $result;
    }

    public function createUsersListCsvFile(Listing $users): string
    {
        $fileName = $this->filesystem->tempnam('users-list', 'users-list');

        $usersTable = $this->prepareEndUserTable($users);

        $handle = fopen($fileName, 'w');
        foreach ($usersTable as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return $fileName;
    }

    public function createUsersForCompanyFromFile(EndUserBulkCreateMessage $message): void
    {
        $companyId = $message->getCompanyId();
        $fileId = $message->getConfirmationId();

        $company = $this->companyService->findOneOrThrowException($companyId);

        $file = $this->findFile($companyId, $fileId);

        $data = json_decode($file->getData(), true);

        if (!empty($data['errors']) || empty($data['success'])) {
            return;
        }

        $dtos = $this->symfonyNormalizer->denormalize($data['success'], EndUserInputDto::class.'[]');

        $errors = [];
        $createdUsers = 0;

        /** @var EndUserInputDto $dto */
        foreach ($dtos as $line => $dto) {
            $dto->companyId = $companyId;

            try {
                $this->validator->validate($dto, ['groups' => ['Default', 'end_user.create']]);
            } catch (ValidationException $e) {
                $errors['line'.$line] =  $e->getMessage();

                continue;
            }

            $endUser = $this->endUserManager->createEndUserForCompanyFromDto($company, $dto);

            try {
                $endUser->save();
                ++$createdUsers;
            } catch (\Throwable $e) {
                $errors['line'.$line] =  $e->getMessage();
            }
        }

        $uploadResult = [
            'errorsCount' => count($errors),
            'createdUsers' => $createdUsers,
        ];

        if (!empty($errors) && null !== $message->getUserEmail()) {
            //@todo send email
        }

        $this->saveFile($companyId, $uploadResult, '', $fileId, '-result');

        $file->delete();
    }

    public function removeEndUserUploadedFile(int $companyId, string $fileId): void
    {
        $this->findFile($companyId, $fileId)->delete();
    }

    public function analyzeFile(UploadedFile $file, int $companyId, string $owner): EndUserBulkUploadFileDto
    {
        try {
            $usersFile = new UserIterator($file->getPathname());
        } catch (\Throwable) {
            $response = new EndUserBulkUploadFileDto();
            $response->errors = 'Bad File Format';
            $response->code = JsonResponse::HTTP_BAD_REQUEST;

            return $response;
        }

        $errors = [];
        $successful = [];

        $names = [];
        $privateEmails = [];
        $businessEmails = [];
        $headerRow = new UserRow([]);

        foreach ($usersFile as $rowNum => $row) {
            if (0 === $rowNum) {
                $headerRow = new UserRow($row);

                continue;
            }

            $row = new UserRow($row, $headerRow);

            $dto = $this->getDtoFromRow($row, $companyId);

            $lineNumber = sprintf('line%s', $rowNum);

            try {
                $this->validator->validate($dto, ['groups' => ['Default', 'end_user.create']]);
            } catch (ValidationException $e) {
                $errors[$lineNumber] = ['message' => $e->getMessage(), 'line' => null];

                continue;
            }

            $name = $row->getFullNameWithBirthDate();
            if (null !== $line = $this->findDuplicate($name, $names)) {
                $errors[$lineNumber] = [
                    'message' => sprintf('duplicate name and birth day detected with line %d', $line),
                    'line' => $line,
                ];

                continue;
            }

            $names[$rowNum] = $name;

            $line = $this->findDuplicate($dto->privateEmail, $privateEmails);
            if (null === $line) {
                $line = $this->findDuplicate($dto->privateEmail, $businessEmails);
            }

            if (null === $line) {
                $line = $this->findDuplicate($dto->businessEmail, $businessEmails);
            }

            if (null === $line) {
                $line = $this->findDuplicate($dto->businessEmail, $privateEmails);
            }

            if (null !== $line) {
                $errors[$lineNumber] = [
                    'message' => sprintf('duplicate email detected with line %d', $line),
                    'line' => $line,
                ];

                continue;
            }

            $privateEmails[$rowNum] = $dto->privateEmail;
            $businessEmails[$rowNum] = $dto->businessEmail;

            $successful[$lineNumber] = $row->toArray();
        }

        $response = new EndUserBulkUploadFileDto();

        if (empty($successful) && empty($errors)) {
            $response->errors = 'File is empty';
            $response->code = JsonResponse::HTTP_BAD_REQUEST;

            return $response;
        }

        $data = [
            'errors' => $errors,
            'success' => $successful,
        ];

        /** @var Asset $asset */
        /** @var string $fileId */
        [$fileId, $asset]= $this->saveFile($companyId, $data, $owner);

        $this->updateBulkUploadDtoFromAsset($response, $asset, $data, $fileId);

        $this->filesystem->remove($file->getPathname());

        return $response;
    }

    public function updateBulkUploadDtoFromAsset(EndUserBulkUploadFileDto $dto, Asset $asset, array $data, ?string $fileId = null): void
    {
        $modificationDate = $asset->getModificationDate() ?? $asset->getCreationDate();
        $modificationDateString = Carbon::createFromTimestamp($modificationDate);

        $errors = $data['errors'] ?? [];

        if (\count($errors) > 0) {
            $dto->errors = sprintf('%d line(s) with errors.', \count($errors));
            $dto->code = JsonResponse::HTTP_CREATED;
        }

        if (null === $fileId) {
            $fileId = substr($asset->getFilename(), 0, strpos($asset->getFilename(), '.json'));
        }
        $dto->success = $data['success'] ?? [];
        $dto->errorsArray = $errors;
        $dto->confirmationId = $fileId;
        $dto->filePath = $asset->getFullPath();
        $dto->lastModifiedAtTS = $modificationDate;
        $dto->version = $asset->getVersionCount();
        $dto->owner = $asset->getMetadata('owner');
        $dto->lastModifiedAt = $modificationDateString->format('Y-m-d H:i:s');
    }

    public function findFile(int $companyId, string $filename): Asset
    {
        $file = Asset::getByPath(sprintf('%s/%s/%s.json', self::TEMP_END_USER_FILE_PATH, $companyId, $filename));

        if (!$file instanceof Asset) {
            throw new NotFoundHttpException(sprintf('File with path %s/%s.json not found!', $companyId, $filename));
        }

        return $file;
    }

    public function isFileContainErrors(int $companyId, string $filename): bool
    {
        $file = $this->findFile($companyId, $filename);
        $data = json_decode($file->getData(), true);

        return !empty($data['errors']);
    }

    private function getDtoFromRow(UserRow $row, int $companyId): EndUserInputDto
    {
        $dto = new EndUserInputDto();
        $dto->firstName = $row->getFirstName();
        $dto->lastName = $row->getLastName();
        $dto->privateEmail = $row->getPrivateEmail();
        $dto->businessEmail = $row->getBusinessEmail();
        $dto->dateOfBirth = $row->getDateOfBirth();
        $dto->phoneNumber = $row->getPhoneNumber();
        $dto->gender = $row->getGender();
        $dto->companyId = $companyId;
        $dto->customFields = $row->getCustomFields();

        return $dto;
    }

    private function findDuplicate(?string $value, array $array): ?int
    {
        $key = (null === $value || empty($array)) ? false : array_search($value, $array, true);

        return false === $key ? null : (int) $key;
    }

    private function saveFile(int $companyId, array $data, string $owner, ?string $fileId = null, string $prefixFileName = ''): array
    {
        $dataString = json_encode($data);

        if (null === $fileId) {
            $fileId = md5($dataString);
        }

        $newAsset = Asset::getByPath(sprintf('%s/%s/%s.json', self::TEMP_END_USER_FILE_PATH, $companyId, $fileId.$prefixFileName));

        if (!$newAsset instanceof Asset) {
            $folder = $this->folderService->getOrCreateAssetFolderForEndUserBulkUploadsFiles($companyId);

            $newAsset = new Asset();
            $newAsset
                ->setFilename(sprintf('%s.json', $fileId.$prefixFileName))
                ->setData($dataString)
                ->setParent($folder)
                ->setMetadata([
                        [
                            'name' => 'owner',
                            'type' => 'string',
                            'data' => $owner,
                        ],
                    ]
                )
            ;

            $newAsset->save(["versionNote" => $fileId.$prefixFileName]);
        }

        return [$fileId, $newAsset];
    }
}
