<?php


namespace App\Service;


use Pimcore\Model\DataObject\AbstractObject;

class DataObjectService
{
    /**
     * Takes an object and a classname and delivers the first parent object of this type.
     */
    public function getRecursiveParent(AbstractObject $object, string $className): ?AbstractObject {
        $parent = $object->getParent();
        if ($parent && !$parent instanceof $className) {
            $parent = $this->getRecursiveParent($parent, $className);
        }
        return $parent;
    }
}
