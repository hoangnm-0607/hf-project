<?php

declare(strict_types=1);

namespace App\Entity;

trait LastUsedAssertIdTrait
{
    private ?int $lastAssetId = null;

    public function getLastAssetId(): ?int
    {
        return $this->lastAssetId;
    }

    public function setLastAssetId(int $assetId): self
    {
        $this->lastAssetId = $assetId;

        return $this;
    }
}
