<?php


namespace App\DataTransformer;


use App\Dto\DatedPartnerProfileDto;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;

class DatedPartnerProfileOutputDataTransformer implements OutputDataTransformerInterface
{

    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }


    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return in_array($to, [DatedPartnerProfileDto::class, PartnerProfileDto::class])  && $data instanceof PartnerProfile;
    }

    /**
     * @param PartnerProfile $object
     */
    public function transform($object, string $to, array $context = []): PartnerProfileDto
    {
        // We're cheating here a bit and gonna deliver a PartnerProfileDto instead of a DatedPartnerProfileDto.
        // We're doing this, to have a schema description that fits the customized, timestamped output.
        $target = new PartnerProfileDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }
}
