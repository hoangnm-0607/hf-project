<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\Dto\OpeningHoursDto;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;

class PartnerProfileOpeningHoursPopulator
{

    public function populate(PartnerProfile $source, $target)
    {
        $openingTimesTable = $source->getOpeningTimes();

        if($openingTimesTable && false === $openingTimesTable->isEmpty()) {
            $openingHours = [];
            $openingTimes = $openingTimesTable->getData();
            foreach ($openingTimes as $weekday => $openingTime) {
                $openingTimeDto = new OpeningHoursDto();
                $openingTimeDto->opened = $openingTime['open'];
                $openingTimeDto->weekday = $weekday;

                for ($i = 1; $i <= 3; $i++) {
                    if ($openingTime['time_from' . $i] && $openingTime['time_to' . $i]) {
                        $openingTimeDto->times[] = [
                            'from' => $openingTime['time_from' . $i],
                            'to'  => $openingTime['time_to' . $i],
                        ];
                    }
                }

                $openingHours[] = $openingTimeDto;
            }
        }

        $target->openingHours = $openingHours ?? [];
        return $target;
    }
}
