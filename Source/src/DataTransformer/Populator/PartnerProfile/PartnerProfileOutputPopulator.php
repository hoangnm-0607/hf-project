<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use App\Service\I18NService;
use App\Service\TranslatorService;

class PartnerProfileOutputPopulator
{
    private I18NService $i18NService;

    public function __construct(I18NService $i18NService)
    {
        $this->i18NService = $i18NService;
    }

    public function populate(PartnerProfile $source, $target): PartnerProfileDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target->description        = $source->getShortDescription($language);
        $target->checkinInformation = $source->getCheckInInformation($language);
        $target->holidays           = $source->getHolidays($language);
        $target->notes              = $source->getNotesInformations($language);
        $target->studioVisibility   = $source->getStudioVisibility() != 'Nein';
        $target->published          = $source->isPublished();

        if ($servicePackages = $source->getServicePackages()) {
            foreach ($servicePackages as $servicePackage) {
                $target->servicePackages[$servicePackage->getCasId()] = $servicePackage->getName();
            }
        }


        return $target;
    }
}
