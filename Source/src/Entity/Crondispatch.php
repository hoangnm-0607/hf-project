<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CrondispatchRepository;

/**
 * Crondispatch
 *
 * @ORM\Table(name="crondispatch")
 * @ORM\Entity(repositoryClass=CrondispatchRepository::class)
 */
class Crondispatch
{
    /**
     * @var string
     *
     * @ORM\Column(name="jobname", type="string", length=100, nullable=false)
     * @ORM\Id
     */
    private $jobname;

    /**
     * @var string
     *
     * @ORM\Column(name="parameters", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $parameters;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    private $active = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $timestamp = 'CURRENT_TIMESTAMP';

    public function getJobname(): ?string
    {
        return $this->jobname;
    }

    public function setJobname(string $jobname): self
    {
        $this->jobname = $jobname;

        return $this;
    }

    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    public function setParameters(string $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }


}
