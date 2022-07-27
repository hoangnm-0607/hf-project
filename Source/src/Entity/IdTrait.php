<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;

trait IdTrait
{
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ApiProperty(writable: false)]
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
