<?php

namespace App\DataTransformer\Populator\EndUser;

use App\Dto\EndUser\EndUserOutputDto;
use App\Entity\EndUser;

interface EndUserOutputPopulatorInterface
{
    public function populate(EndUser $source, EndUserOutputDto $target): EndUserOutputDto;
}
