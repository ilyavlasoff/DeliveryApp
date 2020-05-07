<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 * @ORM\Table(name="delivery")
 */
class Delivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_city", type="string", length=30, nullable=false)
     */
    private $depCity;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_country", type="string", length=30, nullable=false)
     */
    private $depCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_street", type="string", length=40, nullable=false)
     */
    private $depStreet;

    /**
     * @var int
     *
     * @ORM\Column(name="dep_building", type="string", length=5, nullable=false)
     */
    private $depBuilding;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dep_flat", type="integer", nullable=true)
     */
    private $depFlat;

    /**
     * @var string
     *
     * @ORM\Column(name="dep_postcode", type="string", length=20, nullable=false)
     */
    private $depPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_city", type="string", length=30, nullable=false)
     */
    private $destCity;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_country", type="string", length=30, nullable=false)
     */
    private $destCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_street", type="string", length=40, nullable=false)
     */
    private $destStreet;

    /**
     * @var int
     *
     * @ORM\Column(name="dest_house", type="string", length=5, nullable=false)
     */
    private $destHouse;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dest_flat", type="integer", nullable=true)
     */
    private $destFlat;

    /**
     * @var string
     *
     * @ORM\Column(name="dest_postcode", type="string", length=20, nullable=false)
     */
    private $destPostcode;

    /**
     * @var float
     *
     * @ORM\Column(name="route_length", type="float", precision=10, scale=0, nullable=false)
     */
    private $routeLength;

    /**
     * @var DeliveryType
     *
     * @ORM\ManyToOne(targetEntity="DeliveryType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var Vendor
     *
     * @ORM\ManyToOne(targetEntity="Vendor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vendor_id", referencedColumnName="id")
     * })
     */
    private $vendor;

    /**
     * @var Receiver
     *
     * @ORM\ManyToOne(targetEntity="Receiver", inversedBy="deliveries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     * })
     */
    private $receiver;

    /**
     * @ORM\OneToMany(targetEntity="Arrival", mappedBy="delivery")
     */
    private $arrivals;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="delivery")
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="StatusHistory", mappedBy="delivery")
     */
    private $statuses;

    /**
     * @ORM\OneToOne(targetEntity="Payments", mappedBy="delivery")
     */
    private $payment;

    public function __construct()
    {
        $this->arrivals = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->statuses = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDepCity(): ?string
    {
        return $this->depCity;
    }

    public function setDepCity(string $depCity): self
    {
        $this->depCity = $depCity;

        return $this;
    }

    public function getDepCountry(): ?string
    {
        return $this->depCountry;
    }

    public function setDepCountry(string $depCountry): self
    {
        $this->depCountry = $depCountry;

        return $this;
    }

    public function getDepStreet(): ?string
    {
        return $this->depStreet;
    }

    public function setDepStreet(string $depStreet): self
    {
        $this->depStreet = $depStreet;

        return $this;
    }

    public function getDepBuilding(): ?string
    {
        return $this->depBuilding;
    }

    public function setDepBuilding(string $depBuilding): self
    {
        $this->depBuilding = $depBuilding;

        return $this;
    }

    public function getDepFlat(): ?int
    {
        return $this->depFlat;
    }

    public function setDepFlat(?int $depFlat): self
    {
        $this->depFlat = $depFlat;

        return $this;
    }

    public function getDepPostcode(): ?string
    {
        return $this->depPostcode;
    }

    public function setDepPostcode(string $depPostcode): self
    {
        $this->depPostcode = $depPostcode;

        return $this;
    }

    public function getDestCity(): ?string
    {
        return $this->destCity;
    }

    public function setDestCity(string $destCity): self
    {
        $this->destCity = $destCity;

        return $this;
    }

    public function getDestCountry(): ?string
    {
        return $this->destCountry;
    }

    public function setDestCountry(string $destCountry): self
    {
        $this->destCountry = $destCountry;

        return $this;
    }

    public function getDestStreet(): ?string
    {
        return $this->destStreet;
    }

    public function setDestStreet(string $destStreet): self
    {
        $this->destStreet = $destStreet;

        return $this;
    }

    public function getDestHouse(): ?string
    {
        return $this->destHouse;
    }

    public function setDestHouse(string $destHouse): self
    {
        $this->destHouse = $destHouse;

        return $this;
    }

    public function getDestFlat(): ?int
    {
        return $this->destFlat;
    }

    public function setDestFlat(?int $destFlat): self
    {
        $this->destFlat = $destFlat;

        return $this;
    }

    public function getDestPostcode(): ?string
    {
        return $this->destPostcode;
    }

    public function setDestPostcode(string $destPostcode): self
    {
        $this->destPostcode = $destPostcode;

        return $this;
    }

    public function getRouteLength(): ?float
    {
        return $this->routeLength;
    }

    public function setRouteLength(float $routeLength): self
    {
        $this->routeLength = $routeLength;

        return $this;
    }

    public function getType(): ?DeliveryType
    {
        return $this->type;
    }

    public function setType(?DeliveryType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getReceiver(): ?Receiver
    {
        return $this->receiver;
    }

    public function setReceiver(?Receiver $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @return Collection|Arrival[]
     */
    public function getArrivals(): Collection
    {
        return $this->arrivals;
    }

    public function addArrival(Arrival $arrival): self
    {
        if (!$this->arrivals->contains($arrival)) {
            $this->arrivals[] = $arrival;
            $arrival->setDelivery($this);
        }

        return $this;
    }

    public function removeArrival(Arrival $arrival): self
    {
        if ($this->arrivals->contains($arrival)) {
            $this->arrivals->removeElement($arrival);
            // set the owning side to null (unless already changed)
            if ($arrival->getDelivery() === $this) {
                $arrival->setDelivery(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setDelivery($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getDelivery() === $this) {
                $item->setDelivery(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|StatusHistory[]
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(StatusHistory $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setDelivery($this);
        }

        return $this;
    }

    public function removeStatus(StatusHistory $status): self
    {
        if ($this->statuses->contains($status)) {
            $this->statuses->removeElement($status);
            // set the owning side to null (unless already changed)
            if ($status->getDelivery() === $this) {
                $status->setDelivery(null);
            }
        }

        return $this;
    }

    public function getPayment(): ?Payments
    {
        return $this->payment;
    }

    public function setPayment(?Payments $payment): self
    {
        $this->payment = $payment;

        // set (or unset) the owning side of the relation if necessary
        $newDelivery = null === $payment ? null : $this;
        if ($payment->getDelivery() !== $newDelivery) {
            $payment->setDelivery($newDelivery);
        }

        return $this;
    }
}
