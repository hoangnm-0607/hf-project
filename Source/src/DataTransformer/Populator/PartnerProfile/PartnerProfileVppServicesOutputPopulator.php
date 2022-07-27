<?php

namespace App\DataTransformer\Populator\PartnerProfile;

use App\Dto\PartnerProfileVppOutputDto;
use App\Entity\PartnerProfile;

class PartnerProfileVppServicesOutputPopulator
{

    public function populate(PartnerProfile $source, PartnerProfileVppOutputDto $target)
    {
        $contractServices = [];
        $this->setFitnessServices($source, $target, $contractServices);
        $this->setWellnessServices($source, $target, $contractServices);
        $this->setServices($source, $target, $contractServices);

        $target->contractServices = $contractServices;

        return $target;
    }

    private function setFitnessServices(PartnerProfile $source, PartnerProfileVppOutputDto$target, array &$contractServices): void
    {
        $definition = $source->getClass()->getFieldDefinition("FitnessServicesInclusive");
        $servicesInclusive = $source->getFitnessServicesInclusive() ?? [];
        $servicesSurcharge = $source->getFitnessServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getFitnessServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getFitnessServicesContractSurcharge() ?? [];


        $target->fitnessServices = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge, $contractServices);
    }

    private function setWellnessServices(PartnerProfile $source, PartnerProfileVppOutputDto$target, array &$contractServices)
    {
        $definition = $source->getClass()->getFieldDefinition("WellnessServicesInclusive");
        $servicesInclusive = $source->getWellnessServicesInclusive() ?? [];
        $servicesSurcharge = $source->getWellnessServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getWellnessServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getWellnessServicesContractSurcharge() ?? [];


        $target->wellnessServices = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge, $contractServices);

    }

    private function setServices(PartnerProfile $source, PartnerProfileVppOutputDto$target, array &$contractServices)
    {
        $definition = $source->getClass()->getFieldDefinition("ServicesInclusive");
        $servicesInclusive = $source->getServicesInclusive() ?? [];
        $servicesSurcharge = $source->getServicesSurcharge() ?? [];
        $servicesContractInclusive = $source->getServicesContractInclusive() ?? [];
        $servicesContractSurcharge = $source->getServicesContractSurcharge() ?? [];


        $target->services = $this->getServiceOptions($definition->getOptions(), $servicesInclusive, $servicesSurcharge, $servicesContractInclusive, $servicesContractSurcharge, $contractServices);

    }

    private function getServiceOptions(array $options, array $servicesIncluded, array $servicesSurcharge, array $contractServicesIncluded, array $contractServicesSurcharge, array &$contractServices): array {
        $services = [];

        foreach ($options as $element) {

            if (in_array($element['key'], $contractServicesIncluded)) {
                $contractServices[$element['key']] = 'inclusive';
            }
            elseif (in_array($element['key'], $contractServicesSurcharge)) {
                $contractServices[$element['key']] = 'surcharge';
            }
            elseif (in_array($element['key'], $servicesIncluded)) {
                $services[$element['key']] = 'inclusive';
            }
            elseif (in_array($element['key'], $servicesSurcharge)) {
                $services[$element['key']] = 'surcharge';
            }
            else {
                $services[$element['key']] = 'not_included';
            }
        }
        return $services;
    }

}
