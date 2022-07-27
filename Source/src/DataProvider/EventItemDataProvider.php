<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\SingleEvent;
use App\Exception\InvalidPartnerException;
use App\Entity\PartnerProfile;
use App\Exception\ObjectNotFoundException;
use App\Exception\TokenIdMismatchException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\PartnerProfileService;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class EventItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(RequestStack $requestStack, InMemoryUserValidator $inMemoryUserValidator,
                                PartnerProfileService $partnerProfileService)
    {
        $this->requestStack = $requestStack;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @throws TokenIdMismatchException
     * @throws Exception
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?SingleEvent
    {
        $eventId = $this->requestStack->getCurrentRequest()->attributes->get('eventId');
        $casPublicId = $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId();
        $partnerProfile = PartnerProfile::getByCASPublicID($casPublicId, 1);

        $event = SingleEvent::getById($eventId);
        if (!$event) {
            throw new ObjectNotFoundException('Event with ID ' . $eventId . ' not found');
        }

        if ($operationName == 'patch_cancel_event') {
            $this->partnerProfileService->checkIfChangesAreAllowed($partnerProfile);
        }

        if(!str_starts_with($event->getFullPath(), $partnerProfile->getFullPath())) {
            throw new InvalidPartnerException('partnerId does not match eventId');
        }

        return $event;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === SingleEvent::class && in_array($operationName, [
                'get_event', 'get_bookings_download', 'patch_cancel_event'
            ]);
    }
}
