<?php

namespace App\Command;

use App\Entity\PartnerProfile;
use App\Service\FolderService;
use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Data\StructuredTable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PartnerProfileImageImportCommand extends AbstractCommand
{
    private FolderService $folderService;

    public function __construct(FolderService $folderService, string $name = null)
    {
        parent::__construct($name);
        $this->folderService = $folderService;
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:import:image-openinghours')
            ->setDescription('Configures the studioimage and the opening hours')
            ->setHelp('Configures the studioimage and the opening hours by the given json und image folder')
            ->addArgument('openinghours', InputArgument::OPTIONAL, 'A json file containing the opening hours')
            ->addArgument('images', InputArgument::OPTIONAL, 'The folder which contains the images to use. NO other files are allowed and the filename needs to us the cas partner id as name, followed by a usual suffix');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonFileName = $input->getArgument('openinghours');
        $imageFolderName = $input->getArgument('images');
        if (isset($jsonFileName)) {
            $openingHoursJson = json_decode(file_get_contents($jsonFileName), true);

            $weekdayMap = [
                'Mo' => 'monday',
                'Di' => 'tuesday',
                'Mi' => 'wednesday',
                'Do' => 'thursday',
                'Fr' => 'friday',
                'Sa' => 'saturday',
                'So' => 'sunday',
            ];

            foreach ($openingHoursJson as $partner) {
                if (count($partner['hours']) > 0 && ($partnerProfile = PartnerProfile::getByPartnerID($partner['partnerId'], 1))) {
                    $data = [];
                    foreach ($partner['hours'] as $weekday => $timeRanges) {
                        $data[$weekdayMap[$weekday]]['open'] = !empty($timeRanges);
                        for ($i = 1; $i <= 3; $i++) {
                            if (isset($timeRanges[$i-1])) {
                                $data[$weekdayMap[$weekday]]['time_from'.$i] = substr($timeRanges[$i-1],0,2) . ':' . substr($timeRanges[$i-1],2,2);
                                $data[$weekdayMap[$weekday]]['time_to'.$i] = substr($timeRanges[$i-1],7,2) . ':' . substr($timeRanges[$i-1],9,2);
                            } else {
                                $data[$weekdayMap[$weekday]]['time_from'.$i] = null;
                                $data[$weekdayMap[$weekday]]['time_to'.$i] = null;
                            }
                        }
                    }
                    $openingHours = new StructuredTable($data);

                    $output->writeln('HOURS: Updating openinghours for partner with CAS ID ' . $partner['partnerId']);
                    $partnerProfile->setOpeningTimes($openingHours);
                    $partnerProfile->save();
                }
                else {
                    $output->writeln('HOURS: Partner or hours not found with CAS ID ' . $partner['partnerId']);
                }
            }
        }

        if (isset($imageFolderName)) {
            $files = scandir($imageFolderName);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $fileParts = explode(".", $file);
                    if ($partnerProfile = PartnerProfile::getByPartnerID($fileParts[0], 1)) {
                       $folder = $this->folderService->getOrCreateGalleryAssetFolderForPartnerProfile($partnerProfile);
                       $image = new Image();
                       $image->setParent($folder);
                       $image->setFilename('studioimage_'. time() . '.' . $fileParts[1]);
                       $image->setData(file_get_contents($imageFolderName.'/'. $file));
                       $image->save();
                       $partnerProfile->setStudioImage($image);
                       $partnerProfile->save();
                       $output->writeln('IMAGE: Uploaded and assigned Studio image for partner with CAS ID ' . $partner['partnerId']);
                    }
                    else {
                        $output->writeln('IMAGE: Partner not found with CAS ID ' . $partner['partnerId']);
                    }
                }
            }
        }

        return 0;
    }
}
