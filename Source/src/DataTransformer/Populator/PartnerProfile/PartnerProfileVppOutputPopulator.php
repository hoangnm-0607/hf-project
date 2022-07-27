<?php

namespace App\DataTransformer\Populator\PartnerProfile;

use App\Dto\PartnerProfileVppOutputDto;
use App\Entity\PartnerProfile;
use App\Entity\ServicePackage;
use App\Service\PartnerProfileService;
use Carbon\Carbon;

class PartnerProfileVppOutputPopulator
{

    private PartnerProfileService $partnerService;

    public function __construct(PartnerProfileService $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function populate(PartnerProfile $source, PartnerProfileVppOutputDto $target): PartnerProfileVppOutputDto
    {
        $target->casPublicId    = $source->getCASPublicID() ?? '';
        $target->description['de'] = $source->getShortDescription('de');
        $target->description['en'] = $source->getShortDescription('en');

        $target->notes['de']       = $source->getNotesInformations('de');
        $target->notes['en']       = $source->getNotesInformations('en');

        $target->holidays['de']    = $source->getHolidays('de') ?? '';
        $target->holidays['en']    = $source->getHolidays('en') ?? '';

        $target->checkinInformation['de']    = $source->getCheckInInformation('de') ?? '';
        $target->checkinInformation['en']    = $source->getCheckInInformation('en') ?? '';

        $target->readonly = ($terminationDate = $source->getTerminationDate()) && $terminationDate->lt(Carbon::today());

        $target->twogether         = $this->hasServicePackage($source, ServicePackage::TWOGETHER_SERVICE_PACKAGE);
        $target->showCourseManager = $this->hasServicePackage($source, ServicePackage::ONLINE_COURSE_SERVICE_PACKAGE);

        $target->completionPercentage = $this->partnerService->getProfileCompletionPercentage($source);

        return $target;
    }

    public function hasServicePackage(PartnerProfile $source, $packageName): bool {
        if ($source->getServicePackages()) {
            foreach ($source->getServicePackages() as $servicePackage) {
                if ($servicePackage->getName() == $packageName) {
                    return true;
                }
            }
        }
        return false;
    }
}
