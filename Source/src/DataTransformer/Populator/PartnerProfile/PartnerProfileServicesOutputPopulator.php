<?php

namespace App\DataTransformer\Populator\PartnerProfile;

use App\Dto\PartnerProfileDto;
use App\Dto\PartnerProfileVppOutputDto;
use App\Entity\PartnerProfile;

class PartnerProfileServicesOutputPopulator
{

    public function populate(PartnerProfile $source, PartnerProfileDto $target)
    {
        $this->setFitnessServices($source, $target);
        $this->setWellnessServices($source, $target);
        $this->setServices($source, $target);


        return $target;
    }

    private function setFitnessServices(PartnerProfile $source, PartnerProfileDto$target): void
    {
        $definition = $source->getClass()->getFieldDefinition("FitnessServicesInclusive");
        $servicesInclusive = $source->getFitnessServicesInclusive() ?? [];
        $servicesSurcharge = $source->getFitnessServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getFitnessServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getFitnessServicesContractSurcharge() ?? [];


        $target->fitnessServices = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge);
    }

    private function setWellnessServices(PartnerProfile $source, PartnerProfileDto$target)
    {
        $definition = $source->getClass()->getFieldDefinition("WellnessServicesInclusive");
        $servicesInclusive = $source->getWellnessServicesInclusive() ?? [];
        $servicesSurcharge = $source->getWellnessServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getWellnessServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getWellnessServicesContractSurcharge() ?? [];


        $target->wellnessServices = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge);

    }

    private function setServices(PartnerProfile $source, PartnerProfileDto$target)
    {
        $definition = $source->getClass()->getFieldDefinition("ServicesInclusive");
        $servicesInclusive = $source->getServicesInclusive() ?? [];
        $servicesSurcharge = $source->getServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getServicesContractSurcharge() ?? [];


        $target->services = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge);

    }

    private function getServiceOptions(array $options, array $servicesIncluded, array $servicesSurcharge, array $contractServicesIncluded, array $contractServicesSurcharge): array {
        $services = [];

        foreach ($options as $element) {
            if (in_array($element['key'], $contractServicesIncluded)) {
                $services[$element['key']] = 'inclusive';
            }
            elseif (in_array($element['key'], $contractServicesSurcharge)) {
                $services[$element['key']] = 'surcharge';
            }
            elseif (in_array($element['key'], $servicesIncluded)) {
                $services[$element['key']] = 'inclusive';
            }
            elseif (in_array($element['key'], $servicesSurcharge)) {
                $services[$element['key']] = 'surcharge';
            }
        }
        return $services;
    }
}
