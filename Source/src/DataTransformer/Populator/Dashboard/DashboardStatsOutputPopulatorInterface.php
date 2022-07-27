<?php


namespace App\DataTransformer\Populator\Dashboard;


use App\Dto\VPP\Dashboard\DashboardStatsDto;
use App\Entity\PartnerProfile;

interface DashboardStatsOutputPopulatorInterface
{
    public function populate(PartnerProfile $source, DashboardStatsDto $target): DashboardStatsDto;
}
