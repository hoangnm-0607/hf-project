<?php

namespace App\Service;

use App\Factory\CourseUserFactory;
use App\Repository\CourseUserRepository;
use Exception;
use Pimcore\Model\DataObject\CourseUser;

class CourseUserService
{
    private CourseUserRepository $courseUserRepository;
    private CourseUserFactory $courseUserFactory;

    public function __construct(CourseUserRepository $courseUserRepository, CourseUserFactory $courseUserFactory)
    {
        $this->courseUserRepository = $courseUserRepository;
        $this->courseUserFactory = $courseUserFactory;
    }

    /**
     * @throws Exception
     */
    public function getAndUpdateOrCreateCourseUser(array $user): ?CourseUser
    {
        if ($user['userId'] && $courseUser = $this->courseUserRepository->getCourseUserByUserId($user['userId'])) {
            if (isset($user['firstname']) && isset($user['lastname']) && isset($user['company']) && array_diff(
                [
                    $user['firstname'],
                    $user['lastname'],
                    $user['company'],
                ],
                [
                    $courseUser->getFirstname(),
                    $courseUser->getLastname(),
                    $courseUser->getCompany(),
                ]
            )) {
                $courseUser->setKey($user['lastname'] . $user['firstname'] . '_' . uniqid());

                $courseUser->setFirstname($user['firstname']);
                $courseUser->setLastname($user['lastname']);
                $courseUser->setCompany( $user['company']);
                $courseUser->save();
            }

        } else {
            $courseUser = $this->courseUserFactory->createNewCourseUser($user);
        }
        return $courseUser;
    }

}
