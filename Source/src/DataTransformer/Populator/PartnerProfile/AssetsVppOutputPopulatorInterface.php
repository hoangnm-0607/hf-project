<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\Dto\VPP\Assets\AssetsVppOutputDto;
use App\Entity\PartnerProfile;

interface AssetsVppOutputPopulatorInterface
{
    public function populate(PartnerProfile $source, AssetsVppOutputDto $target): AssetsVppOutputDto;
}
