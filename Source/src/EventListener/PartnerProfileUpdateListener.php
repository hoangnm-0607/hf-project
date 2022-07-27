<?php

namespace App\EventListener;

use App\Entity\ServicePackage;
use App\Exception\ObjectPublishingException;
use App\Repository\CourseRepository;
use App\Service\ApiGatewayService;
use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use App\Service\GeoCodeService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Course\Listing;
use Pimcore\Model\DataObject\Data\GeoCoordinates;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\Element\ValidationException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartnerProfileUpdateListener
{
    private FolderService $folderService;
    private TranslatorInterface $translator;
    private GeoCodeService $geoCodeService;
    private RequestStack $requestStack;
    private CasCommunicationService $casCommunicationService;
    private ApiGatewayService $apiGatewayService;
    private ContainerBagInterface $params;

    public function __construct(
        FolderService $folderService,
        TranslatorInterface $translator,
        GeoCodeService $geoCodeService,
        RequestStack $requestStack,
        CasCommunicationService $casCommunicationService,
        ApiGatewayService $apiGatewayService,
        ContainerBagInterface $params
    ) {
        $this->folderService = $folderService;
        $this->translator = $translator;
        $this->geoCodeService = $geoCodeService;
        $this->requestStack = $requestStack;
        $this->casCommunicationService = $casCommunicationService;
        $this->apiGatewayService = $apiGatewayService;
        $this->params = $params;
    }

    /**
     * @throws Exception
     */
    public function checkForCasPublicId(DataObjectEvent $event): void
    {
        if (($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && $partnerProfile->getCASPublicID()
            && ! $partnerProfile->getAssetFolder()) {
            $folder = $this->folderService->getOrCreateAssetFolderForPartnerProfile($partnerProfile);
            $partnerProfile->setAssetFolder($folder);
        }
    }

    /**
     * @throws Exception
     */
    public function unpublishCoursesWhenProfileUnpublished(DataObjectEvent $event): void
    {
        if (($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && false === $partnerProfile->isPublished())
        {
            $courses = new Listing();
            $courses->setCondition('partnerProfile__id = ' . $partnerProfile->getId());

            foreach ($courses as $course) {
                if ($course->getPublished()) {
                    $course->setPublished(false);
                    try {
                        $course->save();
                    } catch (Exception $e) {
                        throw new ObjectPublishingException(
                            $this->translator->trans('admin.object.partnerProfile.message.cantUnpublish', [], 'admin') . '<br>' . $e->getMessage()
                        );
                    }
                }
            }
        }

    }


    public function geocodeAddress(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && ! $partnerProfile->getGeoData()
            && ($partnerProfile->getStreet() && $partnerProfile->getNumber() && $partnerProfile->getCity())) {

                $address = $this->geoCodeService->buildAddressQuery($partnerProfile);

                if ($coordinates = $this->geoCodeService->geoCodeAddress($address)) {
                    $partnerProfile->setGeoData(new GeoCoordinates($coordinates['latitude'], $coordinates['longitude']));
                }
            }
    }

    private function isCallFromAutoSave(DataObjectEvent $event): bool
    {
        $arguments = $event->getArguments();
        if(is_array($arguments) && isset($arguments['isAutoSave'])) {
            return true;
        }
        return false;
    }

    /**
     * @throws ValidationException
     */
    public function checkUpdateAllowed(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && ($currentReQuest = $this->requestStack->getCurrentRequest())
            && ($inputBag = json_decode($currentReQuest->request->get('data')))) {
            if (isset($inputBag->TerminationDate)
                && ($terminationDate = $partnerProfile->getTerminationDate())
                && $terminationDate = $terminationDate->clone()->setTime(23,59,59)) {
                $terminationTimestamp = $terminationDate->timestamp;
                $this->checkTerminationDate($partnerProfile, $terminationTimestamp);
            }
            if (isset($inputBag->ServicePackages)) {
                $this->checkForFutureEvents($partnerProfile, $inputBag);
            }
            if(isset($inputBag->FitnessServicesInclusive) && count($inputBag->FitnessServicesInclusive) > 0
                || isset($inputBag->FitnessServicesSurcharge) && count($inputBag->FitnessServicesSurcharge) > 0) {
                $this->checkForCategoriesMatchServices($partnerProfile);
            }
            if(isset($inputBag->FitnessServicesInclusive) && count($inputBag->FitnessServicesInclusive) > 0
                || isset($inputBag->FitnessServicesSurcharge) && count($inputBag->FitnessServicesSurcharge) > 0
                || isset($inputBag->WellnessServicesInclusive) && count($inputBag->WellnessServicesInclusive) > 0
                || isset($inputBag->WellnessServicesSurcharge) && count($inputBag->WellnessServicesSurcharge) > 0
                || isset($inputBag->ServicesInclusive) && count($inputBag->ServicesInclusive) > 0
                || isset($inputBag->ServicesSurcharge) && count($inputBag->ServicesSurcharge) > 0
                || isset($inputBag->FitnessServicesContractInclusive) && count($inputBag->FitnessServicesContractInclusive) > 0
                || isset($inputBag->FitnessServicesContractSurcharge) && count($inputBag->FitnessServicesContractSurcharge) > 0
                || isset($inputBag->WellnessServicesContractInclusive) && count($inputBag->WellnessServicesContractInclusive) > 0
                || isset($inputBag->WellnessServicesContractSurcharge) && count($inputBag->WellnessServicesContractSurcharge) > 0
                || isset($inputBag->ServicesContractInclusive) && count($inputBag->ServicesContractInclusive) > 0
                || isset($inputBag->ServicesContractSurcharge) && count($inputBag->ServicesContractSurcharge) > 0
            ) {
                $this->checkForSingleServiceUsage($partnerProfile);
            }
        }
    }

    /**
     * @throws ValidationException
     * FitnessServices are only allowed for Category Fitnessstudio/Damenstudio
     */
    private function checkForSingleServiceUsage(PartnerProfile $partnerProfile): void
    {
        $this->checkForDuplicates(array_merge($partnerProfile->getFitnessServicesInclusive() ?? [], $partnerProfile->getFitnessServicesSurcharge() ?? [], $partnerProfile->getFitnessServicesContractInclusive() ?? [], $partnerProfile->getFitnessServicesContractSurcharge() ?? []));
        $this->checkForDuplicates(array_merge($partnerProfile->getWellnessServicesInclusive() ?? [], $partnerProfile->getWellnessServicesSurcharge() ?? [], $partnerProfile->getWellnessServicesContractInclusive() ?? [], $partnerProfile->getWellnessServicesContractSurcharge() ?? []));
        $this->checkForDuplicates(array_merge($partnerProfile->getServicesInclusive() ?? [], $partnerProfile->getServicesSurcharge() ?? [], $partnerProfile->getServicesContractInclusive() ?? [], $partnerProfile->getServicesContractSurcharge() ?? []));
    }

    /**
     * @throws ValidationException
     */
    private function checkForDuplicates(array $array): void
    {
        $duplicatesCounts = array_count_values($array);
        $duplicates = array_filter($duplicatesCounts, function($v) { return $v > 1; });
        if (!empty($duplicates)) {
            throw new ValidationException($this->translator->trans('admin.object.partnerProfile.message.duplicateService', ['{{service}}' => implode(',', array_keys($duplicates))], 'admin'));
        }
    }


    /**
     * @throws ValidationException
     * FitnessServices are only allowed for Category Fitnessstudio/Damenstudio
     */
    private function checkForCategoriesMatchServices(PartnerProfile $partnerProfile): void
    {
        if (in_array($partnerProfile->getPartnerCategoryPrimary()?->getName('de'), ['Fitnessstudio', 'Damenstudio'])) {
            return;
        }
        if ($secondaryCategories = $partnerProfile->getPartnerCategorySecondary()) {
            foreach ($secondaryCategories as $category) {
                if (in_array($category->getName('de'), ['Fitnessstudio', 'Damenstudio'])) {
                    return;
                }
            }
        }

        throw new ValidationException($this->translator->trans('admin.object.partnerProfile.message.cantFitnessService', [], 'admin'));
    }

    /**
     * @throws ValidationException
     * When a PartnerProfile is updated, we'll check if the terminationDate field was set/modified.
     * If so, we're checking the date of all corresponding events
     */
    private function checkTerminationDate(PartnerProfile $partnerProfile, int $terminationTimestamp): void
    {
        $courseRepository = new CourseRepository();
        $courses = $courseRepository->getAllCoursesOfPartner($partnerProfile);

        foreach ($courses as $course) {
            foreach($course->getSingleEvents() as $event) {
                if(!$event->getCancelled() && $event->isPublished() && $event->getStartTimestamp() > $terminationTimestamp) {
                    throw new ValidationException('At least one of the partner\'s event dates lies on or beyond the termination date. <br><strong>Event id: #' . $event->getId(). '</strong>');
                }
            }
        }
    }

    /**
     * @throws ValidationException
     * When a PartnerProfile is updated and Online-Kurs is removed.
     * If so, we're checking the date of all corresponding events
     */
    private function checkForFutureEvents(PartnerProfile $partnerProfile, $inputBag): void
    {
        $oldPartnerProfile = PartnerProfile::getById($partnerProfile->getId(), true);
        $existedBefore = false;
        foreach ($oldPartnerProfile->getServicePackages() as $oldPackage) {
            if ($oldPackage->getName() == ServicePackage::ONLINE_COURSE_SERVICE_PACKAGE) {
                $existedBefore = true;
                break;
            }
        }
        if ($existedBefore) {
            $isGone = true;
            foreach ($inputBag->ServicePackages as $newPackage) {
                if ($newPackage->Name == ServicePackage::ONLINE_COURSE_SERVICE_PACKAGE) {
                    $isGone = false;
                    break;
                }
            }
            if ($isGone) {
                $courseRepository = new CourseRepository();
                $courses = $courseRepository->getAllCoursesOfPartner($partnerProfile);

                foreach ($courses as $course) {
                    foreach($course->getSingleEvents() as $event) {
                        if(!$event->getCancelled() && $event->isPublished() && $event->getStartTimestamp() > time()) {
                            throw new ValidationException('At least one of the partner\'s event dates lies in the future, but '. ServicePackage::ONLINE_COURSE_SERVICE_PACKAGE . ' is not assigned. <br><strong>Event id: #' . $event->getId(). '</strong>');
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws ValidationException
     * @throws GuzzleException
     */
    public function getCasDataOnPublish(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && $this->requestStack->getCurrentRequest()?->get('task') === 'publish'
            && empty($partnerProfile->getCASPublicID())
            && $partnerProfile->isPublished()) {


            if ($casData = $this->casCommunicationService->getCasDataForNewPartner($partnerProfile)) {
                $partnerProfile->setPartnerID($casData['partnerId']);
                $partnerProfile->setCASPublicID($casData['publicId']);
                $partnerProfile->setStartCode(str_replace('-','', $casData['startCode']));
                $partnerProfile->setConfigCheckInApp($casData['appConfigKey']);
                $partnerProfile->save();
            } else {
                $partnerProfile->setPublished(false);
                $partnerProfile->save();
                throw new ValidationException($this->translator->trans('admin.object.partnerProfile.message.cantCasCreate', [], 'admin'));
            }

        }

    }

    public function syncToCas(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile
            && !empty($partnerProfile->getPartnerID())) {
            $this->casCommunicationService->syncPartnerToCas($partnerProfile, true);
        }

    }

    public function flushApiGateway(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ($partnerProfile = $event->getObject())
            && $partnerProfile instanceof PartnerProfile) {
            $this->apiGatewayService->flushStage($this->params->get('api_gateway.studio_list_id'), ApiGatewayService::STAGE_PROD);
        }

    }
}
