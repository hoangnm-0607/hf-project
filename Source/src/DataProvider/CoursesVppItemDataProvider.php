<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Exception\InvalidPartnerException;
use App\Exception\ObjectNotFoundException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\PartnerProfileService;
use Symfony\Component\HttpFoundation\RequestStack;

class CoursesVppItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
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
     * @throws InvalidPartnerException
     * @throws \Exception
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []) : Courses
    {
        $casPublicId = $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId();
        $partnerProfile = PartnerProfile::getByCASPublicID($casPublicId, 1);

        if ($operationName != 'get_course') {
            $this->partnerProfileService->checkIfChangesAreAllowed($partnerProfile);
        }

        $courseId = $this->requestStack->getCurrentRequest()->attributes->get('courseId');
        $course = Courses::getById($courseId);
        if (!$course) {
            throw new ObjectNotFoundException('Courses with ID ' . $courseId . ' not found');
        }

        if(!str_starts_with($course->getFullPath(), $partnerProfile->getFullPath())) {
            throw new InvalidPartnerException('partnerId does not match courseId');
        }

        return $course;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Courses::class && in_array($operationName, [
            'get_course', 'update_course','archive_course', 'delete_course'
            ]);
    }
}
