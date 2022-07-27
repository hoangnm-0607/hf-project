<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\Entity\PartnerProfile;

class PartnerProfileCategoryOutputPopulator
{

    public function populate(PartnerProfile $source, $target)
    {
        if ($categoryPrimary = $source->getPartnerCategoryPrimary()) {
            $target->categoryPrimary = $categoryPrimary->getId();
        }

        $target->categories = [];
        if($moreCategories = $source->getPartnerCategorySecondary()) {
            foreach ($moreCategories as $category) {
                $target->categories[] = $category->getId();
            }
        }

        return $target;
    }
}
