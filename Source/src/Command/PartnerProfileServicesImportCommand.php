<?php

namespace App\Command;


use App\Entity\PartnerProfile;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PartnerProfileServicesImportCommand extends AbstractCommand
{
    private Client $elasticClient;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->elasticClient = ClientBuilder::create()->setHosts([$_ENV['AES_SEARCH_ENDPOINT']])->build();
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:import:partner-services')
            ->setDescription('Imports/Updates partner services')
            ->setHelp('Imports a csv file containing the services for the cas ids')
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


        $file_to_read = fopen($fileName, 'r');

        $fitnessServices = [
            5 => "Cardiobereich",
            6 => "Kraftbereich",
            7 => "Frauenbereich",
            8 => "Zirkeltraining",
            9 => "Elektronisches Zirkeltraining",
            10 => "Vibrationstraining",
            11 => "EMS",
            12 => "Kurse",
            13 => "Präventions- / Rehakurse",
            14 => "Funktionelles Training",
            15 => "TRX/-Schlingentraining",
            16 => "Stretch Geräte",
        ];

        $wellnessServices = [
            17 => "Finnische Sauna",
            18 => "Kräutersauna",
            19 => "Damensauna",
            20 => "Dampfbad",
            21 => "Hamam",
            22 => "Infrarotkabine",
            23 => "Pool",
            24 => "Whirlpool",
            25 => "Ruheraum",
            26 => "Solarium",
            27 => "Salzgrotte",
        ];

        $services = [
            28 => "Personal Training",
            29 => "Online-Training",
            30 => "Ernährungsberatung",
            31 => "Schließfächer",
            32 => "Materialverleih",
            33 => "Café / Bar / Lounge",
            34 => "Getränkestation",
            35 => "Shop",
            36 => "Parkplätze",
        ];

        if($file_to_read !== FALSE){

            $row = 0;
            while(($data = fgetcsv($file_to_read, 0, ',', '"')) !== FALSE){
                $row++;

                if (!is_numeric($data[0])) {
                    $output->writeln('Notice;Skipping row as no cas partner id could be found in row ' . $row);
                    continue;
                }

                $partnerProfile = PartnerProfile::getByPartnerID($data[0], 1);
                if ($partnerProfile == null ) {
                    $output->writeln('Error;Skipping row as partner could not be found with id;' . $data[0]);
                    continue;
                }

                if ($partnerProfile->getFitnessServicesContractInclusive()
                    || $partnerProfile->getFitnessServicesContractSurcharge()
                    || $partnerProfile->getFitnessServicesInclusive()
                    || $partnerProfile->getFitnessServicesSurcharge()
                    || $partnerProfile->getWellnessServicesContractInclusive()
                    || $partnerProfile->getWellnessServicesContractSurcharge()
                    || $partnerProfile->getWellnessServicesInclusive()
                    || $partnerProfile->getWellnessServicesSurcharge()
                    || $partnerProfile->getServicesContractInclusive()
                    || $partnerProfile->getServicesContractSurcharge()
                    || $partnerProfile->getServicesInclusive()
                    || $partnerProfile->getServicesSurcharge()
                ) {
                    $inclusive = $this->implode_all([
                        $partnerProfile->getFitnessServicesContractInclusive(),
                        $partnerProfile->getFitnessServicesInclusive(),
                        $partnerProfile->getWellnessServicesContractInclusive(),
                        $partnerProfile->getWellnessServicesInclusive(),
                        $partnerProfile->getServicesContractInclusive(),
                        $partnerProfile->getServicesInclusive(),
                    ]);

                    $surcharge = $this->implode_all([
                        $partnerProfile->getFitnessServicesContractSurcharge(),
                        $partnerProfile->getFitnessServicesSurcharge(),
                        $partnerProfile->getWellnessServicesContractSurcharge(),
                        $partnerProfile->getWellnessServicesSurcharge(),
                        $partnerProfile->getServicesContractSurcharge(),
                        $partnerProfile->getServicesSurcharge(),
                    ]);
                    $output->writeln('Error;Skipping row as has services;' . $data[0] . ';' . $inclusive . ';' . $surcharge);
                    continue;
                }

                $fitnessServicesIncludes = [];
                $fitnessServicesSurcharge = [];
                for ($i = 5; $i <= 16; $i++) {
                    if ($data[$i] == 'Kostenlos') {
                        $fitnessServicesIncludes[] = $fitnessServices[$i];
                    }
                    elseif ($data[$i] == 'Aufpreis') {
                        $fitnessServicesSurcharge[] = $fitnessServices[$i];
                    }
                }
                $partnerProfile->setFitnessServicesContractInclusive($fitnessServicesIncludes);
                $partnerProfile->setFitnessServicesContractSurcharge($fitnessServicesSurcharge);

                $wellnessServicesIncludes = [];
                $wellnessServicesSurcharge = [];
                for ($i = 17; $i <= 27; $i++) {
                    if ($data[$i] == 'Kostenlos') {
                        $wellnessServicesIncludes[] = $wellnessServices[$i];
                    }
                    elseif ($data[$i] == 'Aufpreis') {
                        $wellnessServicesSurcharge[] = $wellnessServices[$i];
                    }
                }
                $partnerProfile->setWellnessServicesContractInclusive($wellnessServicesIncludes);
                $partnerProfile->setWellnessServicesContractSurcharge($wellnessServicesSurcharge);

                $servicesIncludes = [];
                $servicesSurcharge = [];
                for ($i = 28; $i <= 36; $i++) {
                    if ($data[$i] == 'Kostenlos') {
                        $servicesIncludes[] = $services[$i];
                    }
                    elseif ($data[$i] == 'Aufpreis') {
                        $servicesSurcharge[] = $services[$i];
                    }
                }
                $partnerProfile->setServicesContractInclusive($servicesIncludes);
                $partnerProfile->setServicesContractSurcharge($servicesSurcharge);



                if ($execute) {
                    if ($row % 5 == 0) {
                        $this->waitElasticSearchHealthy();
                    }

                    try {
                        $partnerProfile->save();
                        $output->writeln('Notice;Done partner with id;' . $data[0]);

                    } catch (Exception $e) {
                        $output->writeln('Error;Failed to save partner;' . $data[0] . ';' . $e->getMessage());
                    }
                }
            }

            fclose($file_to_read);
        }


        return 0;
    }

    private function waitElasticSearchHealthy(): void
    {
        $isHealthy = false;
        while (!$isHealthy) {
            $pools = $this->elasticClient->cat()->threadPool(['thread_pool_patterns' => 'write']);
            foreach ($pools as $pool) {
                if ($pool['name'] == 'write') {
                    if ($pool['queue'] <= 3) {
                        $isHealthy = true;
                    }
                    else {
                        sleep(1);
                    }
                }
            }
        }
    }

    private function implode_all(array $arrays): string
    {
        $results = [];
        foreach ($arrays as $array) {
            if (null === $array) {
                continue;
            }
            $results[] = implode(',', $array);
        }
        return implode(',', $results);;
    }

}
