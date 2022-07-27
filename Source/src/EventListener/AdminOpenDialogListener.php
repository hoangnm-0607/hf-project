<?php

namespace App\EventListener;

use App\Entity\PartnerProfile;
use Pimcore\Event\Model\ResolveElementEvent;


class AdminOpenDialogListener
{


    /**
     * Checks if new objects are created at the adequate places.
     * Throws an exception otherwise.
     *
     */
    public function resolveElement(ResolveElementEvent $event): void
    {
        $id  = strtolower($event->getId());
        if ($event->getType() == "object" && (str_starts_with($id, 'p') || str_starts_with($id, 's'))) {
            $partnerProfile = PartnerProfile::getByPartnerID(substr($id, 1), 1);
            if ($partnerProfile) {
                $id = $partnerProfile->getId();
                $event->setId($id);
            }
        }
    }
}
