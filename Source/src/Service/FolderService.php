<?php


namespace App\Service;


use Exception;
use Pimcore\Model\Asset\Folder as AssetFolder;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\DataObject\Folder as DataObjectFolder;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\Document;
use Pimcore\Model\Document\Folder;

class FolderService
{
    public const PARTNERFOLDER        = 'Partner';
    public const COURSESFOLDER        = 'Courses';
    public const ARCHIVEFOLDER        = 'Archive';
    public const LOGOFOLDER           = 'Logo';
    public const GALLERYFOLDER        = 'Gallery';
    public const VIDEOFOLDER          = 'Video';
    public const ENDUSERSFOLDER       = 'End Users';
    public const CUSTOM_FIELDS_FOLDER = 'Custom Fields';
    public const DOCUMENTS            = 'Documents';
    public const COMPANIES_FOLDER     = 'Companies';
    public const TEMP_FILES_FOLDER    = 'Temporary_Files';
    public const END_USER_BULK_UPLOAD_TEMP_FILES = 'EndUser_Bulk_Uploads';
    public const RESEND_LETTER = 'Resend Letter';

    public const CCP_DOCUMENT_FOLDER = 'Company (CCP)';

    public const EMAIL_TEMPLATES_FOLDER = 'E-Mail Templates';
    public const COMPANY_EMAIL_FOLDER = 'Company Emails';

    public const FAQ_FOLDER = 'FAQ';
    public const COMPANY_FAQ_FOLDER = 'Company FAQ';

    public const ACTIVATION_LETTER_FOLDER = 'Activation Letters';
    public const COMPANY_ACTIVATION_LETTER_FOLDER = 'Company Activation Letters';

    public const MARKETING_MATERIAL_FOLDER = 'Marketing Material';
    public const COMPANY_MARKETING_MATERIAL_FOLDER = 'Company Marketing Material';

    public const TEMPLATE_TYPE_EMAIL = 'emailTemplates';
    public const TEMPLATE_TYPE_FAQ = 'faqTemplates';
    public const TEMPLATE_TYPE_ACTIVATION_LETTER = 'activationLetterTemplates';
    public const TEMPLATE_TYPE_MARKETING_MATERIAL = 'marketingMaterialTemplates';

    public static string $companyActivationLetter = '/'.self::CCP_DOCUMENT_FOLDER.'/'.self::ACTIVATION_LETTER_FOLDER.'/'.self::COMPANY_ACTIVATION_LETTER_FOLDER.'/%s/%s/%s';
    public static string $activationLetter = '/'.self::CCP_DOCUMENT_FOLDER.'/'.self::ACTIVATION_LETTER_FOLDER.'/%s/%s';

    private array $locales;


    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateAssetFolderForPartnerProfile(PartnerProfile $partnerProfile): ?AssetFolder
    {
        return ($casPublicId = $partnerProfile->getCASPublicID()) ? $this->getOrCreateAssetFolder('/' . self::PARTNERFOLDER, $casPublicId) : null;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateLogoAssetFolderForPartnerProfile(PartnerProfile $partnerProfile): AssetFolder
    {
        return $this->getOrCreateAssetSubFolderForPartnerProfile($partnerProfile, self::LOGOFOLDER);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateGalleryAssetFolderForPartnerProfile(PartnerProfile $partnerProfile): AssetFolder
    {
        return $this->getOrCreateAssetSubFolderForPartnerProfile($partnerProfile, self::GALLERYFOLDER);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateVideoAssetFolderForPartnerProfile(PartnerProfile $partnerProfile): AssetFolder
    {
        return $this->getOrCreateAssetSubFolderForPartnerProfile($partnerProfile, self::VIDEOFOLDER);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateAssetSubFolderForPartnerProfile(PartnerProfile $partnerProfile, string $folderName): ?AssetFolder
    {
        $partnerFolder = $this->getOrCreateAssetFolderForPartnerProfile($partnerProfile);
        if ($partnerFolder == null) {
            return null;
        }

        $path   = $partnerFolder->getCurrentFullPath();
        $folder = AssetFolder::getByPath($path . '/' . $folderName);

        if ( !$folder ) {
            $rootFolder = AssetFolder::getByPath($path);
            $folder     = new AssetFolder();
            $folder->setKey($folderName);
            $folder->setParent($rootFolder);
            $folder->save();
        }

        return $folder;
    }

    /**
     * @throws Exception
     */
    public function createDataObjectFolder($parent, $key): DataObjectFolder
    {
        $dataObjectFolder = new DataObjectFolder();
        $dataObjectFolder->setKey($key);
        $dataObjectFolder->setParent($parent);
        $dataObjectFolder->save();

        return $dataObjectFolder;
    }

    /**
     * @throws Exception
     */
    public function createDocumentFolder($parent, $key, $language = null): Folder
    {
        $folder = new Folder();
        $folder->setKey($key);
        $folder->setParent($parent);
        if ($language) {
            $folder->setProperty("language", 'text', $language);
        }
        $folder->save();

        return $folder;
    }

    /**
     * @throws Exception
     */
    public function createDocumentFolderForCompany(Company $company, ?string $subFolder = null): Folder
    {
        $companyId = (string) $company->getId();

        if (!$ccpFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_FOLDER)) {
            $ccpFolder = $this->createDocumentFolder( Document::getById(1), self::CCP_DOCUMENT_FOLDER);
        }


        switch ($subFolder) {
            case self::TEMPLATE_TYPE_EMAIL:
                return $this->createDocumentSubFolderForCompany(
                    $ccpFolder,
                    self::EMAIL_TEMPLATES_FOLDER,
                    self::COMPANY_EMAIL_FOLDER,
                    $companyId
                );
            case self::TEMPLATE_TYPE_FAQ:
                return $this->createDocumentSubFolderForCompany(
                    $ccpFolder,
                    self::FAQ_FOLDER,
                    self::COMPANY_FAQ_FOLDER,
                    $companyId
                );
            case self::TEMPLATE_TYPE_MARKETING_MATERIAL:
                return $this->createDocumentSubFolderForCompany(
                    $ccpFolder,
                    self::MARKETING_MATERIAL_FOLDER,
                    self::COMPANY_MARKETING_MATERIAL_FOLDER,
                    $companyId
                );
            case self::TEMPLATE_TYPE_ACTIVATION_LETTER:
                return $this->createDocumentSubFolderForCompany(
                    $ccpFolder,
                    self::ACTIVATION_LETTER_FOLDER,
                    self::COMPANY_ACTIVATION_LETTER_FOLDER,
                    $companyId
                );
            default:
                return $ccpFolder;
        }
    }

    /**
     * @throws Exception
     */
    public function createDocumentSubFolderForCompany(Folder $ccpFolder, string $firstLevelFolder, string $secondLevelFolder, int $companyId): Folder
    {
        if (!$documentTypeFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_FOLDER . '/' . $firstLevelFolder)) {
            $documentTypeFolder = $this->createDocumentFolder( $ccpFolder, $firstLevelFolder);
        }
        if (!$companiesFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_FOLDER . '/' . $firstLevelFolder . '/' . $secondLevelFolder)) {
            $companiesFolder = $this->createDocumentFolder( $documentTypeFolder, $secondLevelFolder);
        }
        if (!$companyFolder = Folder::getByPath('/' . self::CCP_DOCUMENT_FOLDER . '/' . $firstLevelFolder . '/' . $secondLevelFolder . '/' . $companyId)) {
            $companyFolder = $this->createDocumentFolder($companiesFolder, $companyId);
            foreach ($this->locales as $locale) {
                $this->createDocumentFolder($companyFolder, $locale, $locale);
            }
        }
        return $companyFolder;
    }

    public function getCompanyEmailTemplatesPath(?int $companyId = null) {
        if (null !== $companyId) {
            return '/' . self::CCP_DOCUMENT_FOLDER . '/' . self::EMAIL_TEMPLATES_FOLDER . '/' . self::COMPANY_EMAIL_FOLDER . '/' . $companyId;
        }
        else {
            return '/' . self::CCP_DOCUMENT_FOLDER . '/' . self::EMAIL_TEMPLATES_FOLDER;
        }
    }

    public function getCoursesFolderByPartnerProfile(PartnerProfile $profile): ?DataObjectFolder
    {
        return DataObjectFolder::getByPath($profile->getFullPath() . '/' . self::COURSESFOLDER);
    }

    public function getArchiveFolderByPartnerProfile(PartnerProfile $profile): ?DataObjectFolder
    {
        return DataObjectFolder::getByPath($profile->getFullPath() . '/' . self::ARCHIVEFOLDER);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateCustomFieldsSubFolderForCompany(Company $company): ?DataObjectFolder
    {
        if(!$folder = DataObjectFolder::getByPath($company->getFullPath() . '/' . self::CUSTOM_FIELDS_FOLDER)) {
            $folder = new DataObjectFolder();
            $folder->setKey(self::CUSTOM_FIELDS_FOLDER);
            $folder->setParent($company);
            $folder->save();
        }
        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateArchiveSubFolder(PartnerProfile $partnerProfile, Course $course): ?DataObjectFolder
    {
        $archiveFolder = $this->getArchiveFolderByPartnerProfile($partnerProfile);

        $folderName = $course->getKey() . ' ('. $course->getId() . ')';
        if(!$folder = DataObjectFolder::getByPath($archiveFolder->getFullPath() . '/' . $folderName)) {
            $folder = new DataObjectFolder();
            $folder->setKey($folderName);
            $folder->setParent(DataObjectFolder::getByPath($archiveFolder->getFullPath()));
            $folder->save();
        }
        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateAssetFolderForEndUser(EndUser $endUser): ?AssetFolder
    {
        $assetFolder = $endUser->getAssetFolder();

        if (!$assetFolder) {
            $this->getOrCreateAssetFolder('/', self::ENDUSERSFOLDER);
            $assetFolder = $this->getOrCreateAssetFolder('/' . self::ENDUSERSFOLDER, $endUser->getId());
        }
        return $assetFolder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateAssetsFolderForCompany(Company $company, string $language): AssetFolder
    {
        $companyId = (string) $company->getId();

        $folder = AssetFolder::getByPath('/' . self::COMPANIES_FOLDER . '/' . $companyId . '/' . self::DOCUMENTS . '/' . $language);

        if ($folder instanceof AssetFolder) {
            return $folder;
        }

        $this->getOrCreateAssetFolder('/', self::COMPANIES_FOLDER);
        $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER, $companyId);
        $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER.'/'.$companyId, self::DOCUMENTS);

        return $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER.'/'.$companyId.'/'.self::DOCUMENTS, $language);

    }

    /**
     * @throws Exception
     */
    private function getOrCreateAssetFolder(string $path, string $folderKey): AssetFolder
    {
        $folder = AssetFolder::getByPath($path . '/' . $folderKey);

        if ( ! $folder) {
            $rootFolder = AssetFolder::getByPath($path);
            $folder     = new AssetFolder();
            $folder->setKey($folderKey);
            $folder->setParent($rootFolder);
            $folder->save();
        }

        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreatePartnerFolder(): DataObjectFolder
    {
        if(!$folder = DataObjectFolder::getByPath('/' . self::PARTNERFOLDER)) {
            $folder = new DataObjectFolder();
            $folder->setKey(self::PARTNERFOLDER);
            $folder->setParent(DataObjectFolder::getByPath('/'));
            $folder->save();
        }
        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateEndUsersFolder(): DataObjectFolder
    {
        if(!$folder = DataObjectFolder::getByPath('/' . self::ENDUSERSFOLDER)) {
            $folder = new DataObjectFolder();
            $folder->setKey(self::ENDUSERSFOLDER);
            $folder->setParent(DataObjectFolder::getByPath('/'));
            $folder->save();
        }
        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateEndUsersFolderForCompany(Company $company): DataObjectFolder
    {
        $companyName = $company->getKey();

        return $this->getOrCreateEndUsersFolderForFolderName($companyName);
    }

    public function getOrCreateAssetFolderForEndUserBulkUploadsFiles(int $companyId): AssetFolder
    {
        $this->getOrCreateAssetFolder('/', self::TEMP_FILES_FOLDER);
        $this->getOrCreateAssetFolder('/'.self::TEMP_FILES_FOLDER, self::END_USER_BULK_UPLOAD_TEMP_FILES);

        return $this->getOrCreateAssetFolder('/'.self::TEMP_FILES_FOLDER.'/'.self::END_USER_BULK_UPLOAD_TEMP_FILES, (string) $companyId);
    }

    /**
     * @throws Exception
     */
    public function getOrCreateEndUsersFolderForFolderName(string $subFolderName): DataObjectFolder
    {
        $endUserFolder = $this->getOrCreateEndUsersFolder();

        if(!$folder = DataObjectFolder::getByPath($endUserFolder->getFullPath().'/'.$subFolderName)) {
            $folder = new DataObjectFolder();
            $folder->setKey($subFolderName);
            $folder->setParent(DataObjectFolder::getByPath($endUserFolder->getFullPath()));
            $folder->save();
        }

        return $folder;
    }

    /**
     * @throws Exception
     */
    public function getOrCreateVoucherArchiveSubFolder(string $productKey): DataObjectFolder
    {
        $archiveFolder = $this->getOrCreateVoucherArchiveFolder();

        if(!$folder = DataObjectFolder::getByPath($archiveFolder->getFullPath() . '/' . $productKey)) {
            $folder = new DataObjectFolder();
            $folder->setKey($productKey);
            $folder->setParent(DataObjectFolder::getByPath($archiveFolder->getFullPath()));
            $folder->save();
        }
        return $folder;
    }

    /**
     * @throws Exception
     */
    private function getOrCreateVoucherArchiveFolder(): DataObjectFolder
    {
        if(!$folder = DataObjectFolder::getByPath('/Online+/Products/Archive')) {
            $folder = new DataObjectFolder();
            $folder->setKey('Archive');
            $folder->setParent(DataObjectFolder::getByPath('/Online+/Products'));
            $folder->save();
        }
        return $folder;
    }
}
