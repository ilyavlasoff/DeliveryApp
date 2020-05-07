<?php

namespace App\Entity;

use App\Repository\WorkShiftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkShiftRepository::class)
 * @ORM\Table(name="work_shift")
 */
class WorkShift
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     *
     */
    private $endTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false)
     */
    private $active;

    /**
     * @var Courier
     *
     * @ORM\ManyToOne(targetEntity="Courier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="courier_id", referencedColumnName="id")
     * })
     */
    private $courier;

    /**
     * @var Auto
     *
     * @ORM\ManyToOne(targetEntity="Auto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auto_num", referencedColumnName="number")
     * })
     */
    private $autoNum;

    /**
     * @ORM\OneToMany(targetEntity="Carry", mappedBy="workshift")
     */
    private $carries;

    public function __construct()
    {
        $this->carries = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    public function setCourier(?Courier $courier): self
    {
        $this->courier = $courier;

        return $this;
    }

    public function getAutoNum(): ?Auto
    {
        return $this->autoNum;
    }

    public function setAutoNum(?Auto $autoNum): self
    {
        $this->autoNum = $autoNum;

        return $this;
    }

    /**
     * @return Collection|Carry[]
     */
    public function getCarries(): Collection
    {
        return $this->carries;
    }

    public function addCarry(Carry $carry): self
    {
        if (!$this->carries->contains($carry)) {
            $this->carries[] = $carry;
            $carry->setWorkshift($this);
        }

        return $this;
    }

    public function removeCarry(Carry $carry): self
    {
        if ($this->carries->contains($carry)) {
            $this->carries->removeElement($carry);
            // set the owning side to null (unless already changed)
            if ($carry->getWorkshift() === $this) {
                $carry->setWorkshift(null);
            }
        }

        return $this;
    }
}
