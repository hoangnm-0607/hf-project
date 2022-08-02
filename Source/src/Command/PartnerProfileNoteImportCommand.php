<?php

namespace App\Command;


use App\Entity\PartnerProfile;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Model\DataObject\Localizedfield;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartnerProfileNoteImportCommand extends AbstractCommand
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
            ->setName('hanse:import:partner-note')
            ->setDescription('Imports/Updates partner german note')
            ->setHelp('Imports a csv file containing the note for the cas ids')
            ->addArgument('filename', InputArgument::REQUIRED, 'A CSV file')
            ->addOption('execute', null, InputOption::VALUE_NONE, 'execute')
            ->addOption('translate', null, InputOption::VALUE_NONE, 'auto translate english note')
            ->addOption('key', 'k', InputOption::VALUE_OPTIONAL, 'Deepl API key');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument('filename');
        $execute = $input->getOption('execute');
        $translate = $input->getOption('translate');
        $apiKey = $input->getOption('key');

        if ($translate && !$apiKey) {
            $output->writeln('Translate mode needs deepl key');
            return 0;
        }


        $file_to_read = fopen($fileName, 'r');

        if($file_to_read !== FALSE){

            Localizedfield::setGetFallbackValues(false);
            $client  = HttpClient::create();
            $url = 'https://api-free.deepl.com/v2/translate';

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


                $partnerProfile->setNotesInformations($data[1], 'de');
                if ($translate) {
                    try {
                        $translated = '';
                        $response = $client->request('GET', $url, [
                            'query' => [
                                'auth_key' => $apiKey,
                                'source_lang' => 'de',
                                'target_lang' => 'en',
                                'text' =>$data[1],
                            ],
                        ]);


                        if (200 !== $response->getStatusCode()) {
                            $output->writeln('Error;Request failed for;' . $data[0]);
                        } else {
                            $json = $response->getContent(false);
                            $decoded = json_decode($json,true);
                            if (isset($decoded['translations']) && isset($decoded['translations'][0]) && isset($decoded['translations'][0]['text'])) {
                                $translated = $decoded['translations'][0]['text'];
                                $partnerProfile->setNotesInformations($translated, 'en');
                            }
                        }
                    } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
                        $output->writeln('Error;Request failed for;' . $data[0]);

                    }
                }

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
}
