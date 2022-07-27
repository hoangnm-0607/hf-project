<?php


namespace App\EventListener;


use Pimcore\Bundle\AdminBundle\Security\User\UserLoader;
use Pimcore\Model\DataObject\Course;
use Symfony\Component\EventDispatcher\GenericEvent;

class ExclusiveCourseListener
{
    protected UserLoader $userLoader;

    public function __construct(UserLoader $userLoader)
    {
        $this->userLoader = $userLoader;
    }

    public function checkPermissions(GenericEvent $event): void
    {
        $object = $event->getArgument("object");

        if(($object instanceof Course) && $object->getExclusiveCourse()) {
            //data element that is send to Pimcore backend UI
            $data = $event->getArgument("data");

            //get current user
            $user = $this->userLoader->getUser();

            //check if allowed and if not change permission
            if(!$user || !$user->isAllowed("allow_exclusive_publishing")) {

                $data['userPermissions']['save'] = true;
                $data['userPermissions']['publish'] = false;
                $data['userPermissions']['unpublish'] = false;
                $data['userPermissions']['delete'] = true;
                $data['userPermissions']['create'] = false;
                $data['userPermissions']['rename'] = true;

            }

            $event->setArgument("data", $data);
        }

    }

}
