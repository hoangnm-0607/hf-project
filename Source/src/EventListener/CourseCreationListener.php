<?php


namespace App\EventListener;


use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Course;

class CourseCreationListener
{
    /**
     * @throws Exception
     */
    public function setCourseId(DataObjectEvent $event)
    {
        if (($object = $event->getElement()) && $object instanceof Course && !$object->getCourseID()) {
            // simplified course id
            $courseId = $object->getId();
            $object->setCourseID($courseId);
            // for some reason the default value wasn't set automatically anymore, so here we'll set it manually.
            // this part needs to be adjusted, if different course types are rolled out
            $object->setCourseType('OnlineCourse');
            $object->save();
        }
    }

}
