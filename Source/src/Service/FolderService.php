<?php


namespace App\Service;


use Exception;
use Pimcore\Model\Asset\Folder as AssetFolder;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\DataObject\Folder as DataObjectFolder;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\Document\Folder;
use Pimcore\Model\Document\Link;

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
    public const TEMP_FILES_FOLDER    = 'Temporary Files';
    public const END_USER_BULK_UPLOAD_TEMP_FILES = 'EndUser Bulk Uploads';
    public const CCP_DOCUMENT_Folder = 'Company (CCP)';
    public const EMAIL_TEMPLATES_FOLDER = 'E-Mail Templates';
    public const COMPANY_EMAIL_FOLDER = 'Company Emails';
    public const RESEND_LETTER = 'Resend Letter';

    public static string $companyActivationLetter = '/'.self::CCP_DOCUMENT_Folder.'/Activation Letter/Company Activation Letters/%s/%s/%s';
    public static string $activationLetter = '/'.self::CCP_DOCUMENT_Folder.'/Activation Letter/%s/%s';

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
    public function createDocumentFolder($parent, $key): Folder
    {
        $folder = new Folder();
        $folder->setKey($key);
        $folder->setParent($parent);
        $folder->save();

        return $folder;
    }

    /**
     * @throws Exception
     */
    public function createDocumentFolderForCompany(Company $company): void
    {
        $companyId = (string) $company->getId();

        if (!$ccpFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_Folder)) {
            $ccpFolder = $this->createDocumentFolder( Folder::getByPath('/'), self::CCP_DOCUMENT_Folder);
        }
        if (!$emailFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_Folder . '/' . self::EMAIL_TEMPLATES_FOLDER)) {
            $emailFolder = $this->createDocumentFolder( $ccpFolder, self::EMAIL_TEMPLATES_FOLDER);
        }
        if (!$companyEmailFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_Folder . '/' . self::EMAIL_TEMPLATES_FOLDER . '/' . self::COMPANY_EMAIL_FOLDER)) {
            $companyEmailFolder = $this->createDocumentFolder( $emailFolder, self::COMPANY_EMAIL_FOLDER);
        }
//        if (!$companyFolder  = Folder::getByPath('/' . self::CCP_DOCUMENT_Folder . '/' . self::EMAIL_TEMPLATES_FOLDER . '/' . self::COMPANY_EMAIL_FOLDER . '/' . $companyId)) {
//            $companyFolder = $this->createDocumentFolder($companyEmailFolder, $companyId);
//            foreach ($this->locales as $locale) {
//                $link = new Link();
//                $link->setKey($locale);
//                $link->setParent($companyFolder);
//                $link->setProperty("language", 'text', $locale);
//                $link->save();
//
//            }
//        }

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
    public function getOrCreateAssetsFolderForCompany(Company $company, string $language, ?string $documentFolderName = null): AssetFolder
    {
        $companyId = (string) $company->getId();

        if (null === $documentFolderName) {
            $folder = AssetFolder::getByPath('/' . self::COMPANIES_FOLDER . '/' . $companyId . '/' . self::DOCUMENTS . '/' . $language);
        } else {
            $folder = AssetFolder::getByPath('/' . self::COMPANIES_FOLDER . '/' . $companyId . '/' . self::DOCUMENTS . '/' . $language. '/' .$documentFolderName);
        }

        if ($folder instanceof AssetFolder) {
            return $folder;
        }

        $this->getOrCreateAssetFolder('/', self::COMPANIES_FOLDER);
        $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER, $companyId);
        $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER.'/'.$companyId, self::DOCUMENTS);

        $result = $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER.'/'.$companyId.'/'.self::DOCUMENTS, $language);

        if (null === $documentFolderName) {
            return $result;
        }

        return $this->getOrCreateAssetFolder('/'.self::COMPANIES_FOLDER.'/'.$companyId.'/'.self::DOCUMENTS.'/'.$language, $documentFolderName);
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
