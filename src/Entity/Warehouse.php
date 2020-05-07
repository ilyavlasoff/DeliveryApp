<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarehouseRepository::class)
 * @ORM\Table(name="warehouse")
 */
class Warehouse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=30, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=60, nullable=false)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50, nullable=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="building", type="string", length=10, nullable=false)
     */
    private $building;

    /**
     * @var float
     *
     * @ORM\Column(name="max_contain", type="float", precision=10, scale=0, nullable=false)
     */
    private $maxContain;

    /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="warehouse")
     */
    private $employees;

    /**
     * @ORM\OneToMany(targetEntity="Arrival", mappedBy="warehouse")
     */
    private $arrivals;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->arrivals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(string $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getMaxContain(): ?float
    {
        return $this->maxContain;
    }

    public function setMaxContain(float $maxContain): self
    {
        $this->maxContain = $maxContain;

        return $this;
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setWarehouse($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getWarehouse() === $this) {
                $employee->setWarehouse(null);
            }
        }

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
            $arrival->setWarehouse($this);
        }

        return $this;
    }

    public function removeArrival(Arrival $arrival): self
    {
        if ($this->arrivals->contains($arrival)) {
            $this->arrivals->removeElement($arrival);
            // set the owning side to null (unless already changed)
            if ($arrival->getWarehouse() === $this) {
                $arrival->setWarehouse(null);
            }
        }

        return $this;
    }
}
