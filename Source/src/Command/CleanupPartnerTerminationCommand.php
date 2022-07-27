<?php /** @noinspection PhpInternalEntityUsedInspection */

namespace App\Command;

use App\Repository\PartnerProfileRepository;
use App\Service\CognitoDynamoDbManagementService;
use Exception;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pimcore\Log\Simple;

class CleanupPartnerTerminationCommand extends AbstractCommand
{
    private CognitoDynamoDbManagementService $cognitoDynamoDbManagementService;
    private PartnerProfileRepository $partnerProfileRepository;

    public function __construct(CognitoDynamoDbManagementService $cognitoDynamoDbManagementService,
                                PartnerProfileRepository $partnerProfileRepository, string $name = null)
    {
        parent::__construct($name);
        $this->cognitoDynamoDbManagementService = $cognitoDynamoDbManagementService;
        $this->partnerProfileRepository = $partnerProfileRepository;
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:cleanup:partner:terminated')
            ->setDescription('Disables the visibility and/or published status for canceled partner contracts')
            ->setHelp('This job disables the visibility directly after the termination date and 3 month later the published state for a partner.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $partnerProfileListing = $this->partnerProfileRepository->getTerminatedVisiblePartnerProfiles();
        foreach ($partnerProfileListing as $partnerProfile) {
            Simple::log('partnertermination', 'Disabled visibility for partner ' . $partnerProfile->getId() . ' as termination date was reached');
            $partnerProfile->setStudioVisibility('Nein');
            $partnerProfile->save();
        }

        $partnerProfileListing = $this->partnerProfileRepository->get3MonthTerminatedPublishedPartnerProfiles();
        foreach ($partnerProfileListing as $partnerProfile) {
            $partnerProfile->setPublished(false);
            $partnerProfile->save();
            Simple::log('partnertermination', 'Disabled published state for partner ' . $partnerProfile->getId() . ' as termination date was reached for 3 months');
            if ($partnerProfile->getCASPublicID()) {
                $deactivatedUsers = $this->cognitoDynamoDbManagementService->deactivateUsersByPublicId($partnerProfile->getCASPublicID());
                Simple::log('partnertermination', 'Deactivated ' . implode(',', $deactivatedUsers) . ' Cognito users with publicId ' . $partnerProfile->getCASPublicID());
            }
        }

        return 0;
    }
}
