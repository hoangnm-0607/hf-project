<?php


namespace App\Factory;


use Exception;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\WebsiteSetting;

class CourseUserFactory
{
    private const USER_ROOT_FOLDER = 'UserRootFolder';

    /**
     * @throws Exception
     */
    public function createNewCourseUser(array $user ): ?CourseUser
    {
        if($userFolder = WebsiteSetting::getByName(self::USER_ROOT_FOLDER)->getData()) {
            $courseUser = new CourseUser();

            $courseUser->setParent($userFolder);
            if (isset($user['firstname']) && isset($user['lastname'])) {
                $courseUser->setKey($user['lastname'] . $user['firstname'] . '_' . uniqid());
            }
            else {
                $courseUser->setKey($user['userId'] . '_' . uniqid());
            }
            $courseUser->setUserId($user['userId']);
            if (isset($user['lastname'])) {
                $courseUser->setLastname($user['lastname']);
            }
            if (isset($user['lastname'])) {
                $courseUser->setFirstname($user['firstname']);
            }
            if (isset($user['lastname'])) {
                $courseUser->setCompany($user['company']);
            }
            $courseUser->setPublished(true);

            $courseUser->save();

            return $courseUser;
        }
        return null;
    }
}
