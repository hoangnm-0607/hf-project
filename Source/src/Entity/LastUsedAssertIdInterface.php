<?php

declare(strict_types=1);

namespace App\Entity;

interface LastUsedAssertIdInterface
{
    public function getLastAssetId(): ?int;

    public function setLastAssetId(int $assetId): self;
}
