<?php


namespace App\Service;


use Pimcore\Model\DataObject\ClassDefinition\Layout\DynamicTextLabelInterface;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\SingleEvent;
use Pimcore\Tool\Admin;

class DynamicTextService implements DynamicTextLabelInterface
{

    public function renderLayoutText($data, $object, $params): string
    {
        $text              = '';
        $dataObjectService = new DataObjectService();

        $language = ($user = Admin::getCurrentUser()) ? $user->getLanguage() : 'de';

        if($object instanceof PartnerProfile) {
            $text = $object->getName();

        } else if ($object instanceof Course) {
            /** @var PartnerProfile $parentPartnerProfile */
            if($parentPartnerProfile = $dataObjectService->getRecursiveParent($object, PartnerProfile::class)) {
                $text = $parentPartnerProfile->getName()
                        . ' - ' . $object->getCourseName($language);
            }

        } else if ($object instanceof SingleEvent) {
            /** @var PartnerProfile $parentPartnerProfile */
            $parentPartnerProfile = $dataObjectService->getRecursiveParent($object, PartnerProfile::class);
            /** @var Course $parentCourse */
            if (($parentCourse = $object->getParentCourse())
               && $courseDate  = $object->getCourseDate()) {
                    $text      = $parentPartnerProfile->getName()
                                  . ' - ' . $parentCourse->getCourseName($language)
                                  . ' - ' . $courseDate->format('d.m.Y')
                                  . ' ' . $object->getCourseStartTime();
                }
        }

        return $text ? '<h1>'.$text.'</h1>' : '';
    }

}
