<?php


namespace App\EventListener;


use Pimcore\Event\Model\DataObjectDeleteInfoEvent;
use Pimcore\Model\DataObject\Course;
use Symfony\Contracts\Translation\TranslatorInterface;

class CourseDeletionListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function checkDeletion(DataObjectDeleteInfoEvent $event)
    {
        if (($object = $event->getElement()) && $object instanceof Course && ($singleEvents = $object->getSingleEvents())) {
            $publishedEvents = [];
            foreach ($singleEvents as $singleEvent) {
                if ($singleEvent->getPublished()) {
                    $publishedEvents[] = $singleEvent;
                }
            }
            if ( ! empty($publishedEvents)) {
                $deletable = false;
                $reason    = $this->translator->trans('admin.object.message.deleteFailed.publishedEvents', [], 'admin');
            }


            $event->setDeletionAllowed($deletable ?? true);

            if (isset($reason)) {
                $event->setReason($reason);
            }
        }
    }

}
