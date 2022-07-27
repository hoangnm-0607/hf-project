<?php

namespace App\Command;


use Pimcore\Model\DataObject\Localizedfield;
use Pimcore\Model\DataObject\PartnerProfile;
use Exception;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartnerProfileNoteTranslationCommand extends AbstractCommand
{

    public function configure(): void
    {
        $this
            ->setName('hanse:translate:partner:note')
            ->setDescription('Translates the the german note to the english note')
            ->addArgument('key', InputArgument::REQUIRED, 'Deepl API key');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {

        Localizedfield::setGetFallbackValues(false);
        $partnerListing = new PartnerProfile\Listing;
        $partnerListing->setUnpublished(true);
        $client  = HttpClient::create();
        $url = 'https://api-free.deepl.com/v2/translate';
        $apiKey = $input->getArgument('key');

        $counter = 0;
        $maxPartners = $partnerListing->count();
        foreach ($partnerListing as $partner) {
            $counter++;
            if (!empty($partner->getNotesInformations('de')) && empty($partner->getNotesInformations('en'))) {

                $translated = '';
                try {
                    $response = $client->request('GET', $url, [
                        'query' => [
                            'auth_key' => $apiKey,
                            'source_lang' => 'de',
                            'target_lang' => 'en',
                            'text' => $partner->getNotesInformations('de'),
                        ],
                    ]);


                    if (200 !== $response->getStatusCode()) {
                        $output->writeln('Request failed for ' . $partner->getId());
                    } else {
                        $json = $response->getContent(false);
                        $decoded = json_decode($json,true);
                        if (isset($decoded['translations']) && isset($decoded['translations'][0]) && isset($decoded['translations'][0]['text'])) {
                            $translated = $decoded['translations'][0]['text'];
                        }
                    }
                } catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
                    $output->writeln('Request failed for ' . $partner->getId());
                }


                $partner->setNotesInformations($translated, 'en');
                $partner->save();
                $output->writeln('translated to: ' . $translated);
            }
            $output->writeln('Done ' . $counter. '/' .  $maxPartners);
        }

        return 0;
    }

}
