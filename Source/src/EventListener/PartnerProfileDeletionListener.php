<?php

namespace App\EventListener;

use Exception;
use Pimcore\Event\Model\DataObjectDeleteInfoEvent;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\Element\ValidationException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartnerProfileDeletionListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    /**
     * @throws Exception
     */
    public function deleteProfileOwnedAssetFolder(DataObjectEvent $event): void
    {
        if ( ($partnerProfile = $event->getElement())
             && $partnerProfile instanceof PartnerProfile
             && $assetFolder = $partnerProfile->getAssetFolder() ) {
            $assetFolder->delete();
        }
    }

    /**
     * @throws ValidationException
     */
    public function checkDeletion(DataObjectDeleteInfoEvent $event): void
    {
        if ( ($partnerProfile = $event->getElement())
            && $partnerProfile instanceof PartnerProfile
            && !empty($partnerProfile->getPartnerID())) {
            $event->setDeletionAllowed(false);
            throw new ValidationException($this->translator->trans('admin.object.partnerProfile.message.cantDelete', [], 'admin'));
        }
    }
}
