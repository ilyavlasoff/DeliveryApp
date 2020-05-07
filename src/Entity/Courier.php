<?php

namespace App\Entity;

use App\Repository\CourierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourierRepository::class)
 * @ORM\Table(name="courier")
 */
class Courier
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
     * @ORM\Column(name="drive_cat", type="json", nullable=false)
     */
    private $driveCat = [];

    /**
     * @var Employee
     *
     * @ORM\OneToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="emp_id", referencedColumnName="id")
     * })
     */
    private $empId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriveCat(): ?array
    {
        return $this->driveCat;
    }

    public function setDriveCat(array $driveCat): self
    {
        $this->driveCat = $driveCat;

        return $this;
    }

    public function getEmpId(): ?Employee
    {
        return $this->empId;
    }

    public function setEmpId(?Employee $empId): self
    {
        $this->empId = $empId;

        return $this;
    }

}
