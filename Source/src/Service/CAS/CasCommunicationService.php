<?php

namespace App\Service\CAS;

use App\DataTransformer\Populator\CAS\CASPartnerProfilePopulator;
use App\Message\CasPartnerUpdate;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\ServicePackage;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CasCommunicationService
{
    private CASPartnerProfilePopulator $CASPartnerProfilePopulator;
    private string $casSystemApiUser;
    private string $casSystemApiPassword;
    private MessageBusInterface $bus;

    public function __construct(CASPartnerProfilePopulator $CASPartnerProfilePopulator, MessageBusInterface $bus,
                                string $casSystemApiUser, string $casSystemApiPassword)
    {
        $this->CASPartnerProfilePopulator = $CASPartnerProfilePopulator;
        $this->casSystemApiUser = $casSystemApiUser;
        $this->casSystemApiPassword = $casSystemApiPassword;
        $this->bus = $bus;
    }

    /**
     * @throws GuzzleException
     */
    public function getCasDataForNewPartner(PartnerProfile $partnerProfile): ?array
    {
        $casUri  = $_ENV['CAS_API_PLATFORM_URI'] . '/partners';
        $client  = HttpClient::create();
        $client  = HttpClient::create(["verify_peer"=>false,"verify_host"=>false]);

        try {
            $response = $client->request('POST', $casUri, [
                'json' => $this->CASPartnerProfilePopulator->populate($partnerProfile),
                'auth_basic' => [$this->casSystemApiUser, $this->casSystemApiPassword],
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
        $message->setCategoryId($partnerProfile->getPartnerCategoryPrimary()?->getCASPartnerId());

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
