<?php

namespace App\Service\CAS;

use App\DataTransformer\Populator\CAS\CASPopulatorResolver;
use App\Entity\PartnerCategory;
use App\Message\CasPartnerUpdate;
use App\Traits\SentryClientTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\ServicePackage;
use Sentry\Severity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CasCommunicationService
{
    use SentryClientTrait;

    private CASPopulatorResolver $populatorResolver;
    private MessageBusInterface $bus;
    private HttpClientInterface $httpClient;
    private string $casApiPlatformUri;


    public function __construct(CASPopulatorResolver $populatorResolver, MessageBusInterface $bus, HttpClientInterface $httpClient, string $casApiPlatformUri)
    {
        $this->populatorResolver = $populatorResolver;
        $this->bus = $bus;
        $this->httpClient = $httpClient;
        $this->casApiPlatformUri = $casApiPlatformUri;
    }

    public function createCasDataForNewCompany(Company $company): ?array
    {
        $casUri  = $this->casApiPlatformUri.'/companies';

        try {
            $response = $this->httpClient->request(Request::METHOD_POST, $casUri, [
                'json' => $this->populatorResolver->populate($company),
            ]);

            $responseCode = $response->getStatusCode();
        } catch (\Throwable $e) {
            $this->sentryClient->captureException($e);

            return null;
        }

        if (201 !== $responseCode) {
            $this->sentryClient->captureMessage(sprintf('Wrong response status code: %s', $responseCode), Severity::error());

            return null;
        }

        return $response->toArray();
    }

    public function createCasDataForNewEndUser(EndUser $endUser): ?array
    {
        $casUri  = $this->casApiPlatformUri.'/customers';

        try {
            $response = $this->httpClient->request(Request::METHOD_POST, $casUri, [
                'json' => $this->populatorResolver->populate($endUser),
            ]);

            $responseCode = $response->getStatusCode();
        } catch (\Throwable $e) {
            $this->sentryClient->captureException($e);

            return null;
        }

        if (201 !== $responseCode) {
            $this->sentryClient->captureMessage(sprintf('Wrong response status code: %s', $responseCode), Severity::error());

            return null;
        }

        return $response->toArray();
    }

    /**
     * @throws GuzzleException
     */
    public function getCasDataForNewPartner(PartnerProfile $partnerProfile): ?array
    {
        $casUri  = $this->casApiPlatformUri.'/partners';
        try {
            $response = $this->httpClient->request('POST', $casUri, [
                'json' => $this->populatorResolver->populate($partnerProfile),
            ]);

            if (201 !== $response->getStatusCode()) {
                return null;
            } else {
                $casData = $response->getContent(false);
            }
        } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            return null;
        }

        return json_decode($casData, true) ?: null;
    }

    public function syncPartnerToCas(PartnerProfile $partnerProfile): void
    {
        $servicePackagesListing = new ServicePackage\Listing;
        $servicePackageCasIds = [];
        foreach ($servicePackagesListing as $package) {
            $servicePackageCasIds[] = $package->getCasId();
        }

        $message = new CasPartnerUpdate();
        $message->setPartnerId($partnerProfile->getPartnerID());
        $message->setPimcoreId($partnerProfile->getId());
        $message->setName($partnerProfile->getKey());
        $message->setDisplayName($partnerProfile->getName() ?? '');

        $street = $partnerProfile->getCountry() == 'LU' ? ($partnerProfile->getNumber() ?? '') . ' ' . ($partnerProfile->getStreet() ?? '') : ($partnerProfile->getStreet() ?? '') . ' ' . ($partnerProfile->getNumber() ?? '');
        $message->setStreet($street);

        $message->setPostalCode($partnerProfile->getZip() ?? '');
        $message->setCity($partnerProfile->getCity() ?? '');
        $message->setCountry($partnerProfile->getCountry() ?? '');
        $message->setLatitude($partnerProfile->getGeoData()?->getLatitude());
        $message->setLongitude($partnerProfile->getGeoData()?->getLongitude());
        $message->setPhone($partnerProfile->getTelephone() ?? '');
        $message->setEmail($partnerProfile->getEmail() ?? '');
        $message->setHomepage($partnerProfile->getWebsite() ?? '');

        //can't directly get the values for the category, load the Category
        $partnerCategory = PartnerCategory::getById($partnerProfile->getPartnerCategoryPrimary()?->getId());
        $message->setCategoryId($partnerCategory->getCASPartnerId());
        $message->setNote($partnerProfile->getNotesInformations('de') ?? '');

        $currentServicePackageCasIds = array_map(function (ServicePackage $package) {
            return $package->getCasId();
        }, $partnerProfile->getServicePackages());

        $message->setPackagesAdd($currentServicePackageCasIds);
        $message->setPackagesDelete(array_diff($servicePackageCasIds,$currentServicePackageCasIds));

        $message->setLocked(!$partnerProfile->isPublished() || (($terminationDate = $partnerProfile->getTerminationDate()) && $terminationDate->lt(Carbon::today())));
        $message->setVisibleOnMap($partnerProfile->getStudioVisibility()  != 'Nein');

        $this->bus->dispatch($message);
    }
}
