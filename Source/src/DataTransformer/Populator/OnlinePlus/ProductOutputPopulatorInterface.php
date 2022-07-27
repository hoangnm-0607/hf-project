<?php


namespace App\DataTransformer\Populator\OnlinePlus;


use App\Dto\OnlinePlus\ProductOutputDto;
use App\Entity\OnlineProduct;

interface ProductOutputPopulatorInterface
{
    public function populate(OnlineProduct $source, ProductOutputDto $target): ProductOutputDto;
}
