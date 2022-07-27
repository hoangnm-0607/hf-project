<?php


namespace App\Service;


use App\Repository\SingleEventRepository;
use Exception;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\OnlineProduct;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\SingleEvent;
use Pimcore\Model\DataObject\Voucher\Listing;

class ArchiveService
{
    private DataObjectService $dataObjectService;
    private FolderService $folderService;
    private SingleEventRepository $singleEventRepository;

    public function __construct(DataObjectService $dataObjectService, FolderService $folderService, SingleEventRepository $singleEventRepository)
    {
        $this->dataObjectService = $dataObjectService;
        $this->folderService = $folderService;
        $this->singleEventRepository = $singleEventRepository;
    }

    /**
     * @param Course|SingleEvent $object
     *
     * @return bool
     * @throws Exception
     */
    public function archiveCourseOrEvent(Course|SingleEvent $object): bool
    {
        /** @var PartnerProfile $parentPartnerProfile */
        if (!$parentPartnerProfile = $this->dataObjectService->getRecursiveParent($object, PartnerProfile::class)) {
            return false;
        }

        $parentCourse = $object instanceof Course ? $object : $this->dataObjectService->getRecursiveParent($object, Course::class);

        $subFolder    = $this->folderService->getOrCreateArchiveSubFolder($parentPartnerProfile, $parentCourse);

        $object->setParent($subFolder);
        $object->save();

        if ($object instanceof SingleEvent) {
            $object->setPublished(false);
            $object->save();

            $parentCourse->setSingleEvents(
                array_filter(
                    $this->singleEventRepository->getAllSingleEventsByCourse($parentCourse),
                    function ($event) use ($object) {
                        return $event !== $object;
                    }
                )
            );
            // unpublish if no events are left
            if(!$parentCourse->getSingleEvents()) {
                $parentCourse->setPublished(false);
            }
            $parentCourse->save();
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function archiveVouchers(Listing $vouchers): void
    {
        foreach ($vouchers as $voucher) {
            /** @var OnlineProduct $parentProduct */
            if(($parentProduct = $this->dataObjectService->getRecursiveParent($voucher,OnlineProduct::class))
               && ($archiveSubFolder = $this->folderService->getOrCreateVoucherArchiveSubFolder($parentProduct->getKey()))) {
                    $voucher->setParent($archiveSubFolder);
                    $voucher->setPublished(false);
                    $voucher->save();
            }
        }
    }
}
