<?php

namespace App\Entity;

use App\Repository\CarryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarryRepository::class)
 * @ORM\Table(name="carry")
 */
class Carry
{
    /**
     * @var WorkShift
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="WorkShift", inversedBy="carries")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="workshift_id", referencedColumnName="id")
     * })
     */
    private $workshift;

    /**
     * @var Delivery
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Delivery")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_id", referencedColumnName="id")
     * })
     */
    private $delivery;

    /**
     * @var Warehouse
     *
     * @ORM\ManyToOne(targetEntity="Warehouse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="from_warehouse", referencedColumnName="id", nullable=true)
     * })
     */
    private $fromWarehouse;

    /**
     * @var Warehouse
     *
     * @ORM\ManyToOne(targetEntity="Warehouse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_warehouse", referencedColumnName="id", nullable=true)
     * })
     */
    private $toWarehouse;

    public function getWorkshift(): ?WorkShift
    {
        return $this->workshift;
    }

    public function setWorkshift(?WorkShift $workshift): self
    {
        $this->workshift = $workshift;

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

    public function getFromWarehouse(): ?Warehouse
    {
        return $this->fromWarehouse;
    }

    public function setFromWarehouse(?Warehouse $fromWarehouse): self
    {
        $this->fromWarehouse = $fromWarehouse;

        return $this;
    }

    public function getToWarehouse(): ?Warehouse
    {
        return $this->toWarehouse;
    }

    public function setToWarehouse(?Warehouse $toWarehouse): self
    {
        $this->toWarehouse = $toWarehouse;

        return $this;
    }
}
