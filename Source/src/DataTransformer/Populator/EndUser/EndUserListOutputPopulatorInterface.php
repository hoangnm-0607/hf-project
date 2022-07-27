<?php

namespace App\DataTransformer\Populator\EndUser;

use App\Dto\EndUser\EndUserListOutputDto;
use App\Entity\EndUser;

interface EndUserListOutputPopulatorInterface
{
    public function populate(EndUser $source, EndUserListOutputDto $target): EndUserListOutputDto;
}
