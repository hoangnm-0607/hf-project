<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;

interface OutputDataTransformerInterface extends DataTransformerInterface
{
    public function __construct(iterable $populators);
}
