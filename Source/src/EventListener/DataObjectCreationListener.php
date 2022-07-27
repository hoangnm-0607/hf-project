<?php

namespace App\EventListener;

use App\Exception\DataObjectException;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\CourseCategory;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\DataObject\PartnerCategory;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\SingleEvent;
use Pimcore\Model\WebsiteSetting;
use Symfony\Contracts\Translation\TranslatorInterface;

class DataObjectCreationListener
{
    const PARTNER_ROOT_FOLDER = 'PartnerRootFolder';
    const PARTNER_CATEGORIES_ROOT_FOLDER = 'PartnerCategoriesRootFolder';
    const COURSE_CATEGORIES_ROOT_FOLDER = 'CourseCategoriesRootFolder';
    const COURSE_USERS_ROOT_FOLDER = 'UserRootFolder';
    const COMPANY_ROOT_FOLDER = 'CompanyRootFolder';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Checks if new objects are created at the adequate places.
     * Throws an exception otherwise.
     *
     * @throws DataObjectException
     * @throws Exception
     */
    public function checkCreatability(DataObjectEvent $event): void
    {
        if($object = $event->getElement()) {
            $parent = $object->getParent();

            if ($object instanceof Course) {
                $this->checkForCoursesFolder($parent);
            } else if ($object instanceof SingleEvent) {
                $this->checkForParentCourse($parent);
            } else if ($object instanceof Booking) {
                $this->checkForParentEvent($parent);
            } else if ($object instanceof PartnerProfile) {
                $this->checkForPartnerRootFolder($parent);
            } else if ($object instanceof Company) {
                $this->checkForCompanyRootFolder($parent);
            } else if ($object instanceof PartnerCategory) {
                $this->checkForPartnerCategoryRootFolder($parent);
            } else if ($object instanceof CourseCategory) {
                $this->checkForCourseCategoryRootFolder($parent);
            } else if ($object instanceof CourseUser) {
                $this->checkForCourseUserRootFolder($parent);
            }
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     */
    private function checkForCoursesFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent->getKey() !== 'Courses') {
            throw new DataObjectException(
                $this->translator->trans('admin.object.message.createFailed.courseUnderFolder', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     */
    private function checkForParentCourse(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Course) {
            throw new DataObjectException(
                $this->translator->trans('admin.object.message.createFailed.eventsUnderCourses', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     */
    private function checkForParentEvent(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof SingleEvent) {
            throw new DataObjectException(
                $this->translator->trans('admin.object.message.createFailed.bookingsUnderEvents', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     * @throws Exception
     */
    private function checkForPartnerRootFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent !== WebsiteSetting::getByName(self::PARTNER_ROOT_FOLDER)->getData()) {
            throw new DataObjectException(
                $this->translator->trans('admin.object.message.createFailed.partnersUnderRootFolder', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     * @throws Exception
     */
    private function checkForCompanyRootFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent !== WebsiteSetting::getByName(self::COMPANY_ROOT_FOLDER)->getData()) {
            throw new DataObjectException(
                $this->translator->trans('admin.object.message.createFailed.companyUnderRootFolder', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     * @throws Exception
     */
    private function checkForPartnerCategoryRootFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent !== WebsiteSetting::getByName(self::PARTNER_CATEGORIES_ROOT_FOLDER)->getData()) {
            throw new DataObjectException(
                $this->translator->trans(
                    'admin.object.message.createFailed.partnerCategoriesUnderRootFolder', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     * @throws Exception
     */
    private function checkForCourseCategoryRootFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent !== WebsiteSetting::getByName(self::COURSE_CATEGORIES_ROOT_FOLDER)->getData()) {
            throw new DataObjectException(
                $this->translator->trans(
                    'admin.object.message.createFailed.courseCategoriesUnderRootFolder', [], 'admin')
            );
        }
    }

    /**
     * @param AbstractObject|null $parent
     *
     * @throws DataObjectException
     * @throws Exception
     */
    private function checkForCourseUserRootFolder(?AbstractObject $parent): void
    {
        if ( ! $parent instanceof Folder || $parent !== WebsiteSetting::getByName(self::COURSE_USERS_ROOT_FOLDER)->getData()) {
            throw new DataObjectException(
                $this->translator->trans(
                    'admin.object.message.createFailed.courseUserssUnderRootFolder', [], 'admin')
            );
        }
    }
}
