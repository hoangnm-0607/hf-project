<?php

declare(strict_types=1);

namespace App\Repository\Asset;

use Pimcore\Model\Asset;

class AssetRepository
{
    public function findAllWithParent(Asset $parent): Asset\Listing
    {
        $listing = new Asset\Listing();
        $listing->setCondition('parentId = ?', [$parent->getId()]);

        return $listing;
    }
}
