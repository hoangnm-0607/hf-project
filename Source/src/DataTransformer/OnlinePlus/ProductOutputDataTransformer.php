<?php


namespace App\DataTransformer\OnlinePlus;


use App\DataTransformer\OutputDataTransformerInterface;
use App\Dto\OnlinePlus\ProductOutputDto;
use App\Entity\OnlineProduct;
use Exception;

class ProductOutputDataTransformer implements OutputDataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    /**
     * @param OnlineProduct $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []): ProductOutputDto
    {
        $target = new ProductOutputDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === ProductOutputDto::class && $data instanceof OnlineProduct;
    }

}
