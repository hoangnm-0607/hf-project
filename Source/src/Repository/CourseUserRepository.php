<?php


namespace App\Repository;


use Pimcore\Model\DataObject\CourseUser;

class CourseUserRepository
{
    public function getCourseUserByUserId(string $userId): ?CourseUser
    {
        return CourseUser::getByUserId($userId, ['limit' => 1, 'unpublished' => true]);
    }
}
