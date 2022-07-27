<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Courses;
use App\Exception\AlreadyPublishedException;
use App\Exception\UnexpectedChildException;
use Exception;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\SingleEvent;

class CoursesVppDeleteDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Courses &&
            isset($context['item_operation_name']) && $context['item_operation_name'] == 'delete_course';
    }

    /**
     * @inheritDoc
     */
    public function persist($data, array $context = [])
    {

    }

    /**
     * @inheritDoc
     * @param Courses $data
     * @throws Exception
     */
    public function remove($data, array $context = [])
    {
        if (!$data->isPublished()) {
            foreach ($data->getChildren(
                [
                    DataObject\AbstractObject::OBJECT_TYPE_VARIANT,
                    DataObject\AbstractObject::OBJECT_TYPE_FOLDER,
                    DataObject\AbstractObject::OBJECT_TYPE_OBJECT
                ],
                true
            ) as $child) {
                if ($child instanceof SingleEvent) {
                    $child->delete();
                }
                else {
                    throw new UnexpectedChildException(
                        "Found unexpected child of type ". $data->getClassName() . "in Course " . $data->getId()
                    );
                }
            }
            $data->delete();
        }
        else {
            throw new AlreadyPublishedException("Can't delete already published Course");
        }
    }
}
