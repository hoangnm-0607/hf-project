<?php


namespace App\Command;


use Carbon\Carbon;
use Exception;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupTempVppAssetsCommand extends Command
{
    public function configure(): void
    {
        $this
            ->setName('hanse:cleanup:assets')
            ->setDescription('Removes unused temporary assets')
            ->setHelp('Deletes all assets in logo/video/gallery subfolder which are older than 24h and are not used somewhere.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $assetList = new Asset\Listing();
        $assetList->setCondition(
            'id NOT IN (SELECT targetid FROM dependencies WHERE targettype=\'asset\') AND
            type = \'image\' AND
            (
                path like \'/Partner/%/Gallery/\' OR
                path like \'/Partner/%/Logo/\' OR
                path like \'/Partner/%/Video/\'
            ) AND
            modificationdate <= ?', [Carbon::now()->subHours(24)->timestamp]
        );

        foreach ($assetList as $asset) {
            $asset->delete();
        }

        return Command::SUCCESS;
    }

}
