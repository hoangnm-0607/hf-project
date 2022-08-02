<?php

namespace App\Command\File;

use App\Repository\Asset\AssetRepository;
use App\Service\FolderService;
use Carbon\Carbon;
use Exception;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;

class RemoveEndUserBulkFilesCommand extends Command
{
    private array $locales;
    private AssetRepository $assetRepository;

    public function __construct(AssetRepository $assetRepository, array $locales, string $name = null)
    {
        $this->locales = $locales;
        $this->assetRepository = $assetRepository;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:cleanup:enduser:tempfiles')
            ->setDescription('Delete all temporary files after a day automatically to avoid unconfirmed files to be store to long')
        ;
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Removing files ...');

        $bulkUploadPath = '/'.FolderService::TEMP_FILES_FOLDER . '/' . FolderService::END_USER_BULK_UPLOAD_TEMP_FILES;
        $assetListing = new Asset\Listing;
        $assetListing->setCondition('type = "text" AND  creationDate <= ?', Carbon::now()->subSeconds(24)->timestamp);
        $assetListing->addConditionParam("path LIKE ?", $bulkUploadPath . '%')->getConditionVariableTypes();
        $removedCount = 0;

        foreach ($assetListing as $asset) {
            $io->writeln(sprintf('Removing %s ...', $asset->getFullPath()));
            ++$removedCount;
            $asset->delete();
        }

        $io->success(sprintf('Removed %s file(s).', $removedCount));
        $io->success('DONE');

        return Command::SUCCESS;
    }
}
