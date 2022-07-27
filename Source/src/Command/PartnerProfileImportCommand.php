<?php

namespace App\Command;


use App\Entity\PartnerProfile;
use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Data\GeoCoordinates;
use Pimcore\Model\DataObject\PartnerCategory;
use Pimcore\Model\DataObject\ServicePackage;
use Pimcore\Model\Element\Service;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PartnerProfileImportCommand extends AbstractCommand
{
    private FolderService $folderService;
    private CasCommunicationService $casCommunicationService;
    private Client $elasticClient;

    public function __construct(FolderService $folderService, CasCommunicationService $casCommunicationService,
                                string $name = null)
    {
        parent::__construct($name);
        $this->folderService = $folderService;
        $this->casCommunicationService = $casCommunicationService;

        $this->elasticClient = ClientBuilder::create()->setHosts([$_ENV['AES_SEARCH_ENDPOINT']])->build();
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:import:partner')
            ->setDescription('Imports/Updates partners')
            ->setHelp('Imports a csv file containing the base partner informations')
            ->addArgument('filename', InputArgument::REQUIRED, 'A CSV file')
            ->addArgument('execute', InputArgument::REQUIRED, 'true or false');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('filename');
        $execute = $input->getArgument('execute') == 'true';

        $partnerCategoriesById = [];
        \Pimcore\Model\DataObject\PartnerProfile::setHideUnpublished(false);
        $categoryListing = new PartnerCategory\Listing;
        foreach ($categoryListing as $category) {
            $partnerCategoriesById[$category->getCASPartnerId()] = $category;
        }

        $mainCategories = [
          '1' => \App\Entity\PartnerCategory::getByPath('/Partner Categories/Fitnessstudio'),
          '32' => \App\Entity\PartnerCategory::getByPath('/Partner Categories/Tennis'),
          '32768' => \App\Entity\PartnerCategory::getByPath('/Partner Categories/Wasserski - Wakeboarding'),
          '65536' => \App\Entity\PartnerCategory::getByPath('/Partner Categories/Outdoor'),
        ];

        $subCategory = \App\Entity\PartnerCategory::getByPath('/Partner Categories/Kanu - Kajak - Standup Padeling');

        $servicePackagesById = [];
        foreach (new ServicePackage\Listing as $package) {
            $servicePackagesById[$package->getCasId()] = $package;
        }

        $partnerFolder = $this->folderService->getOrCreatePartnerFolder();


        $file_to_read = fopen($fileName, 'r');

        if($file_to_read !== FALSE){

            $row = 0;
            while(($data = fgetcsv($file_to_read, 0, ',', '"')) !== FALSE){
                $row++;

                if (!is_numeric($data[0])) {
                    $output->writeln('Notice;Skipping row as no cas partner id could be found in row ' . $row);
                    continue;
                }

                $partnerProfile = PartnerProfile::getByPartnerID($data[0], 1);
                $isNewPartner = true;
                if ($partnerProfile == null ) {
                    $partnerProfile = new PartnerProfile();
                    $output->writeln('Notice;Creating new partner for partner;' . $data[0]);
                }
                else {
                    $output->writeln('Notice;Loaded existing partner for partner;' . $data[0]);
                    $isNewPartner = false;
                }

                $partnerProfile->setPartnerID($data[0]);
                $partnerProfile->setCASPublicID($data[1]);


                $key = str_replace("/", "-", trim($data[2]));
                if ($isNewPartner && Service::pathExists('/Partner/' . $key, 'object')) {
                        $i = 2;
                        while (Service::pathExists('/Partner/' . $key . ' ' . $i, 'object')) {
                            $i++;
                            $output->writeln('Notice;Renamed partner as key already exists;' . $data[0]);
                        }
                        $key = $key . ' ' . $i;
                }
                $partnerProfile->setKey($key);


                $partnerProfile->setName(trim($data[3]));
                $partnerProfile->setGeoData(new GeoCoordinates($data[4], $data[5]));
                $partnerProfile->setStreet(trim($data[6]));
                $partnerProfile->setNumber(trim($data[7]));
                $partnerProfile->setZip(trim($data[8]));
                $partnerProfile->setCity(trim($data[9]));
                $partnerProfile->setCountry(trim($data[10]));

                $partnerProfile->setTelephone(trim($data[11]));
                $partnerProfile->setEmail(trim($data[12]));
                $partnerProfile->setWebsite(trim($data[13]));

                $categoryIds = $this->getCasCategoryIds($data[14]);
                $existingCategories = [];
                foreach ($categoryIds as $id => $name) {
                    if (!isset($partnerCategoriesById[$id])) {
                        $output->writeln('Error;Unknown partner category with id ' . $id . ' for partner;' . $data[0]);
                    }
                    else {
                        $existingCategories[$id] = $name;
                    }
                }
                if (sizeof($existingCategories) == 1) {
                    $key = array_key_first($existingCategories);

                    if (isset($mainCategories[$key])) {
                        $partnerProfile->setPartnerCategoryPrimary($mainCategories[$key]);
                    }
                    else {
                        $partnerProfile->setPartnerCategoryPrimary($partnerCategoriesById[$key]);
                    }
                }
                else {
                    $this->setCategories($existingCategories, $partnerProfile, $mainCategories, $subCategory, $partnerCategoriesById);
                }

                $partnerProfile->setHansefitCard($data[15]);
                $partnerProfile->setCheckInCard($data[16] == 'Ja');
                $partnerProfile->setCheckInApp($data[17] == 'Ja');

                if ($data[18]) {
                    $servicePackages = $this->getValidServicePackages(explode(',', $data[18]), $servicePackagesById, $output, $data[0]);
                    $partnerProfile->setServicePackages($servicePackages);
                }
                $partnerProfile->setStartCode(str_replace('-', '', $data[19]));
                $partnerProfile->setConfigCheckInApp($data[20]);

                $partnerProfile->setStudioVisibility($data[21]);

                $partnerProfile->setShowOpeningTimes('Nein');

                if ($data[22] == 'A') {
                    $partnerProfile->setPublished(true);
                }
                elseif ($data[22] != 'L') {
                    $output->writeln('Error;Unsupported status for partner;' . $data[0]);
                    continue;
                }

                if ($data[23] != '') {
                    $partnerProfile->setNotesInformations($data[23],'de');
                }

                $partnerProfile->setParentId($partnerFolder->getId());

                if ($execute) {
                    if ($row % 10 == 0) {
                        $this->waitElasticSearchHealthy();
                    }

                    try {
                        $partnerProfile->save();

                        if ($isNewPartner) {
                            $this->casCommunicationService->syncPartnerToCas($partnerProfile);
                        }

                    } catch (Exception $e) {
                        $output->writeln('Error;Failed to save partner;' . $data[0] . ';' . $e->getMessage());
                    }
                }
            }

            fclose($file_to_read);
        }


        return 0;
    }

    private function getValidServicePackages(array $servicePackageIds, array $servicePackagesById, OutputInterface $output, string $partnerId)
    {
        $servicePackages = [];
        $containsHansefit = false;
        foreach ($servicePackageIds as $servicePackageId) {
            if (!isset($servicePackagesById[$servicePackageId])) {
                $output->writeln('Warning;Unknown service package with id ' . $servicePackageId . ' for partner;' . $partnerId);
            } else {
                if (in_array($servicePackageId, [33, 34, 35])) {
                    $containsHansefit = true;
                }
                $servicePackages[] = $servicePackagesById[$servicePackageId];
            }
        }

        if (!$containsHansefit) {
            $output->writeln('Error;No valid service packages left for partner;' . $partnerId);
        }

        return $servicePackages;
    }

    private function getCasCategoryIds (int $typeId): array
    {
        $bitmask = [
            'Fitnessstudio',
            'Schwimmbad',
            'Physiotherapie',
            'Damenstudio',
            'Golfplatz',
            'Racket',
            'Wellness',
            'Klettern / Bouldern',
            'Yoga / Pilates',
            'Salzgrotte',
            'Crossfit / Kursstudio',
            'Kältetherapie',
            'EMS',
            'Kampfsport / Selbstverteidigung',
            'Tanzstudio',
            'Wassersport',
            'Outdoor',
        ];

        $categories = [];

        foreach ($bitmask as $cnt => $categoryName) {
            if (($typeId & 1) == 1) {
                $categories[pow(2, $cnt)] = $categoryName;
            }

            $typeId = $typeId >> 1;
        }

        return $categories;
    }

    private function setCategories(array $categories, PartnerProfile $partnerProfile, array $mainCategories, PartnerCategory $subCategory, array $partnerCategoriesById): void
    {
        $primaryCategory = null;
        $secondaryCategories = [];
        if (isset($categories['1'])) {
            $primaryCategory = $mainCategories['1'];
            unset($categories['1']);
        }
        elseif (str_starts_with($partnerProfile->getName(), '*') && isset($categories['2'])) {
            $primaryCategory = $partnerCategoriesById['2'];
            unset($categories['2']);
        }
        elseif (mb_stripos($partnerProfile->getName(), 'SUP') || mb_stripos($partnerProfile->getName(), 'Stand Up Paddling')) {
            $primaryCategory = $subCategory;
            unset($categories['32768']);
        }
        else {
            foreach ($categories as $key => $category) {
                $primaryCategory = $mainCategories[$key] ?? $partnerCategoriesById[$key];
                unset($categories[$key]);
                break;
            }
        }

        foreach ($categories as $key => $category) {
            if (isset($mainCategories[$key])) {
                $secondaryCategories[] = $mainCategories[$key];
                unset($categories[$key]);
            }
            else {
                $secondaryCategories[] = $partnerCategoriesById[$key];
            }
        }

        foreach ($secondaryCategories as $key => $category) {
            if ($category->getId() == $primaryCategory->getId()) {
                unset($secondaryCategories[$key]);
            }
        }

        $partnerProfile->setPartnerCategoryPrimary($primaryCategory);
        $partnerProfile->setPartnerCategorySecondary(array_values($secondaryCategories));
    }

    private function waitElasticSearchHealthy(): void
    {
        $isHealthy = false;
        while (!$isHealthy) {
            $pools = $this->elasticClient->cat()->threadPool(['thread_pool_patterns' => 'write']);
            foreach ($pools as $pool) {
                if ($pool['name'] == 'write') {
                    if ($pool['queue'] <= 5) {
                        $isHealthy = true;
                    }
                    else {
                        sleep(1);
                    }
                }
            }
        }
    }
}
