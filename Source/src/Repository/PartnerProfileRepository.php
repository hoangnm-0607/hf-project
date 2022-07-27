<?php


namespace App\Repository;


use App\Entity\PartnerProfile;
use Carbon\Carbon;
use Pimcore\Model\DataObject\PartnerProfile as DataObjectPartnerProfile;

class PartnerProfileRepository
{
    public function getOnePartnerProfileById(int $id): ?PartnerProfile
    {
        return PartnerProfile::getById($id);
    }

    public function getOnePartnerProfileByCasPublicId(string $id): ?PartnerProfile
    {
        return PartnerProfile::getByCASPublicID($id, ['limit' => 1,'unpublished' => true]);
    }

    public function getTerminatedVisiblePartnerProfiles(): DataObjectPartnerProfile\Listing {
        $listing = new DataObjectPartnerProfile\Listing;
        $listing->setCondition('StudioVisibility != "Nein" AND TerminationDate < ?', Carbon::today()->timestamp);

        return $listing;
    }

    public function get3MonthTerminatedPublishedPartnerProfiles(): DataObjectPartnerProfile\Listing {
        $listing = new DataObjectPartnerProfile\Listing;
        $listing->setCondition('TerminationDate < ?', Carbon::today()->subMonth(3)->timestamp);

        return $listing;
    }

    public function getLastModifiedAndPublishedPartner(): ?PartnerProfile
    {
        $listing = new DataObjectPartnerProfile\Listing;
        $listing->setUnpublished(false)
            ->setOrderKey('o_modificationDate')
            ->setOrder('DESC')
            ->setLimit(1);
        return $listing->current() ?: null;
    }
}
