<?php

namespace App\Entity;

use App\Repository\ArrivalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArrivalRepository::class)
 * @ORM\Table(name="arrival")
 */
class Arrival
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
     * @ORM\Column(name="arrival_date", type="datetime", nullable=true)
     */
    private $arrivalDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="departure_date", type="datetime", nullable=true)
     */
    private $departureDate;

    /**
     * @var int
     *
     * @ORM\Column(name="storage", type="integer", nullable=false)
     */
    private $storage;

    /**
     * @var int
     *
     * @ORM\Column(name="shelf", type="integer", nullable=false)
     */
    private $shelf;

    /**
     * @var int
     *
     * @ORM\Column(name="place", type="integer", nullable=false)
     */
    private $place;

    /**
     * @var Delivery
     *
     * @ORM\ManyToOne(targetEntity="Delivery", inversedBy="arrivals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_id", referencedColumnName="id")
     * })
     */
    private $delivery;

    /**
     * @var Warehouse
     *
     * @ORM\ManyToOne(targetEntity="Warehouse", inversedBy="arrivals")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="warehouse_id", referencedColumnName="id")
     * })
     */
    private $warehouse;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?\DateTimeInterface $arrivalDate): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?\DateTimeInterface $departureDate): self
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getShelf(): ?int
    {
        return $this->shelf;
    }

    public function setShelf(int $shelf): self
    {
        $this->shelf = $shelf;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }
}
