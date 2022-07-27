<?php


namespace App\DataTransformer\Populator\PartnerCategory;


use App\Dto\PartnerCategoryDto;
use App\Entity\PartnerCategory;

interface PartnerCategoryOutputPopulatorInterface
{
    public function populate(PartnerCategory $source, PartnerCategoryDto $target): PartnerCategoryDto;
}
