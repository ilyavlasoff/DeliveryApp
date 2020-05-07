<?php

namespace App\Entity;

use App\Repository\VendorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VendorRepository::class)
 * @ORM\Table(name="vendor")
 */
class Vendor
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
     * @ORM\Column(name="ogrn", type="string", length=13, nullable=false, options={"fixed"=true})
     */
    private $ogrn;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cor_acc", type="string", length=20, nullable=true, options={"fixed"=true})
     */
    private $corAcc;

    /**
     * @var string
     *
     * @ORM\Column(name="bik", type="string", length=9, nullable=false, options={"fixed"=true})
     */
    private $bik;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_city", type="string", length=100, nullable=false)
     */
    private $bankCity;

    /**
     * @var string
     *
     * @ORM\Column(name="kpp", type="string", length=9, nullable=false, options={"fixed"=true})
     */
    private $kpp;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="string", length=12, nullable=false, options={"fixed"=true})
     */
    private $inn;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOgrn(): ?string
    {
        return $this->ogrn;
    }

    public function setOgrn(string $ogrn): self
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCorAcc(): ?string
    {
        return $this->corAcc;
    }

    public function setCorAcc(?string $corAcc): self
    {
        $this->corAcc = $corAcc;

        return $this;
    }

    public function getBik(): ?string
    {
        return $this->bik;
    }

    public function setBik(string $bik): self
    {
        $this->bik = $bik;

        return $this;
    }

    public function getBankCity(): ?string
    {
        return $this->bankCity;
    }

    public function setBankCity(string $bankCity): self
    {
        $this->bankCity = $bankCity;

        return $this;
    }

    public function getKpp(): ?string
    {
        return $this->kpp;
    }

    public function setKpp(string $kpp): self
    {
        $this->kpp = $kpp;

        return $this;
    }

    public function getInn(): ?string
    {
        return $this->inn;
    }

    public function setInn(string $inn): self
    {
        $this->inn = $inn;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
