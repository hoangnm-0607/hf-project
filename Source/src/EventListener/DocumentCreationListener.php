<?php

namespace App\EventListener;

use Pimcore\Event\Model\DocumentEvent;
use Pimcore\Model\Document\Folder;
use Pimcore\Model\Document\Page;
use Pimcore\Model\Element\Service;

class DocumentCreationListener
{
    private array $locales;

    public function __construct(array $locales)
    {
        $this->locales = $locales;
    }


    public function createLanguageDocument(DocumentEvent $event): void
    {
        if (($document = $event->getDocument()) && $document instanceof Page) {
            if (($parentKey = $document->getParent()?->getKey()) && in_array($parentKey, $this->locales)) {
                $documentPath = $document->getPath();
                $documentPath = substr($documentPath, 0, - strlen($parentKey) - 1);

                foreach ($this->locales as $locale) {
                    if ($locale == $parentKey) {
                        continue;
                    }

                    if (($newParent = Folder::getByPath($documentPath . $locale)) && !Service::pathExists($newParent->getCurrentFullPath() . '/'. $document->getKey(), 'document')) {
                        $newDocument = new Page();
                        $newDocument->setKey($document->getKey());
                        $newDocument->setParent($newParent);

                        $newDocument->setController($document->getController());
                        $newDocument->setTemplate($document->getTemplate());
                        $newDocument->setContentMasterDocument($document);

                        $newDocument->setProperties($document->getProperties());
                        $newDocument->setProperty('language', 'text', $locale, false, true);
                        $newDocument->save();

                    }
                }
            }
        }
    }

}
