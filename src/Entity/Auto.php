<?php

namespace App\Entity;

use App\Repository\AutoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AutoRepository::class)
 * @ORM\Table(name="auto")
 */
class Auto
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=8, nullable=false, options={"fixed"=true})
     */
    private $number;

    /**
     * @var string|null
     *
     * @ORM\Column(name="model", type="string", length=30, nullable=true)
     */
    private $model;

    /**
     * @var string
     *
     * @ORM\Column(name="required_drive_cat", type="string", length=3, nullable=false)
     */
    private $requiredDriveCat;

    /**
     * @var float
     *
     * @ORM\Column(name="capacity", type="float", nullable=false)
     */
    private $capacity;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_functional", type="boolean", nullable=false)
     */
    private $isFunctional;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRequiredDriveCat(): ?string
    {
        return $this->requiredDriveCat;
    }

    public function setRequiredDriveCat(string $requiredDriveCat): self
    {
        $this->requiredDriveCat = $requiredDriveCat;

        return $this;
    }

    public function getCapacity(): ?float
    {
        return $this->capacity;
    }

    public function setCapacity(float $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getIsFunctional(): ?bool
    {
        return $this->isFunctional;
    }

    public function setIsFunctional(bool $isFunctional): self
    {
        $this->isFunctional = $isFunctional;

        return $this;
    }
}
