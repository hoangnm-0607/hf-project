<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\PartnerProfileVppInputDto;
use App\Entity\PartnerCategory;
use App\Entity\PartnerProfile;
use App\Entity\ServicePackage;
use App\Exception\UnknownServiceStatus;
use App\Service\PartnerProfileService;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Model\DataObject\Data\GeoCoordinates;
use Pimcore\Model\DataObject\Data\StructuredTable;

class PartnerProfileVppInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param PartnerProfileVppInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);

        if (isset($object->studioName)) {
            $partner->setName($object->studioName);
        }
        if (isset($object->email)) {
            $partner->setEmail($object->email);
        }
        if (isset($object->coordLat)) {
            if (null === $partner->getGeoData()) {
                $partner->setGeoData(new GeoCoordinates());
            }
            $partner->getGeoData()->setLatitude($object->coordLat);
        }
        if (isset($object->coordLong)) {
            if (null === $partner->getGeoData()) {
                $partner->setGeoData(new GeoCoordinates());
            }
            $partner->getGeoData()->setLongitude($object->coordLong);
        }
        if (isset($object->phonenumber)) {
            $partner->setTelephone($object->phonenumber);
        }
        if (isset($object->website)) {
            $partner->setWebsite($object->website);
        }
        if (isset($object->fitnessServices)) {
            $this->applyFitnessServices($object->fitnessServices, $partner);
        }
        if (isset($object->wellnessServices)) {
            $this->applyWellnessServices($object->wellnessServices, $partner);
        }
        if (isset($object->services)) {
            $this->applyServices($object->services, $partner);
        }
        if (isset($object->description['de'])) {
            $partner->setShortDescription($object->description['de'], 'de');
        }
        if (isset($object->description['en'])) {
            $partner->setShortDescription($object->description['en'], 'en');
        }
        if (isset($object->holidays['de'])) {
            $partner->setHolidays($object->holidays['de'], 'de');
        }
        if (isset($object->holidays['en'])) {
            $partner->setHolidays($object->holidays['en'], 'en');
        }
        if (isset($object->notes['de'])) {
            $partner->setNotesInformations($object->notes['de'], 'de');
        }
        if (isset($object->notes['en'])) {
            $partner->setNotesInformations($object->notes['en'], 'en');
        }
        if (isset($object->twogether)) {
            $servicePackages = $partner->getServicePackages() ?? [];
            $servicePackages = array_values(array_filter($servicePackages, function ($servicePackage) {
                if ($servicePackage->getName() != ServicePackage::TWOGETHER_SERVICE_PACKAGE) {
                    return true;
                }
                return false;
            }));
            if ($object->twogether) {
                $servicePackages[] = ServicePackage::getByName(ServicePackage::TWOGETHER_SERVICE_PACKAGE, 1);
            }

            $partner->setServicePackages($servicePackages);
        }
        if (isset($object->checkInCard)) {
            $partner->setCheckInCard($object->checkInCard ?: false);
        }
        if (isset($object->checkInApp)) {
            $partner->setCheckInApp($object->checkInApp ?: false);
        }
        if (isset($object->checkinInformation['de'])) {
            $partner->setCheckInInformation($object->checkinInformation['de'], 'de');
        }
        if (isset($object->checkinInformation['en'])) {
            $partner->setCheckInInformation($object->checkinInformation['en'], 'en');
        }
        if (isset($object->hansefitCard)) {
            $partner->setHansefitCard($object->hansefitCard ? 'Ja' : 'Nein');
        }
        if (isset($object->tags)) {
            $partner->setTags($object->tags);
        }
        if (isset($object->categoryPrimary)) {
            $primaryCategory = new PartnerCategory();
            $primaryCategory->setId($object->categoryPrimary);
            $primaryCategory->setPublished(true);
            $partner->setPartnerCategoryPrimary($primaryCategory);
        }
        if (isset($object->categories)) {
            $secondaryCategories = [];
            foreach ($object->categories as $category) {
                $secondaryCategory = new PartnerCategory();
                $secondaryCategory->setId($category);
                $secondaryCategory->setPublished(true);
                $secondaryCategories[] = $secondaryCategory;
            }
            $partner->setPartnerCategorySecondary($secondaryCategories);
        }
        if (isset($object->showOpeningTimes)) {
            $partner->setShowOpeningTimes($object->showOpeningTimes ? 'Ja' : 'Nein');
        }
        if (isset($object->openingHours)) {
            $data = [];
            foreach ($object->openingHours as $row) {
                $structuredRow = [
                    'open' => $row->opened ? '1' : '0',
                ];
                for ($i = 1; $i <= 3; $i++) {
                    if (isset($row->times[$i-1])) {
                        $structuredRow['time_from'.$i] = $row->times[$i-1]['from'];
                        $structuredRow['time_to'.$i] = $row->times[$i-1]['to'];
                    } else {
                        $structuredRow['time_from'.$i] = null;
                        $structuredRow['time_to'.$i] = null;
                    }
                }
                $data[$row->weekday] = $structuredRow;
            }
            $openingHours = new StructuredTable($data);
            $partner->setOpeningTimes($openingHours);
        }
        $partner->save();

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof PartnerProfileVppInputDto) ||
                ($context['input']['class'] ?? null) === PartnerProfileVppInputDto::class
            );
    }

    /**
     * @throws UnknownServiceStatus
     */
    private function applyFitnessServices(array $services, PartnerProfile $partner): void {
        $options = $this->getServiceOptions($services);

        $partner->setFitnessServicesInclusive($options['inclusive']);
        $partner->setFitnessServicesSurcharge($options['surcharge']);
    }

    /**
     * @throws UnknownServiceStatus
     */
    private function applyWellnessServices(array $services, PartnerProfile $partner): void {
        $options = $this->getServiceOptions($services);
        $partner->setWellnessServicesInclusive($options['inclusive']);
        $partner->setWellnessServicesSurcharge($options['surcharge']);
    }

    /**
     * @throws UnknownServiceStatus
     */
    private function applyServices(array $services, PartnerProfile $partner): void {
        $options = $this->getServiceOptions($services);
        $partner->setServicesInclusive($options['inclusive']);
        $partner->setServicesSurcharge($options['surcharge']);
    }

    /**
     * @throws UnknownServiceStatus
     */
    #[ArrayShape(["inclusive" => "array", "surcharge" => "array"])] private function getServiceOptions(array $services): array {
        $inclusive = [];
        $surcharge = [];

        foreach ($services as $name => $status) {
            if ($status == 'inclusive') {
                $inclusive[] = $name;
            }
            elseif ($status == 'surcharge') {
                $surcharge[] = $name;
            }
            elseif ($status != 'not_included') {
                throw new UnknownServiceStatus("Got unknown service/equipment status with value $status");
            }
        }

        return ["inclusive" => $inclusive, "surcharge" => $surcharge];
    }

}
