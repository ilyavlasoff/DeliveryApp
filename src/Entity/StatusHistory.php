<?php

namespace App\Entity;

use App\Repository\StatusHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusHistoryRepository::class)
 * @ORM\Table(name="status_history")
 */
class StatusHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status_comment", type="string", length=50, nullable=true)
     */
    private $statusComment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_set_date", type="datetime", nullable=false)
     */
    private $statusSetDate;

    /**
     * @var Delivery
     *
     * @ORM\ManyToOne(targetEntity="Delivery", inversedBy="statuses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_id", referencedColumnName="id")
     * })
     */
    private $delivery;

    /**
     * @var StatusCodes
     *
     * @ORM\ManyToOne(targetEntity="StatusCodes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_code", referencedColumnName="scode")
     * })
     */
    private $statusCode;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStatusComment(): ?string
    {
        return $this->statusComment;
    }

    public function setStatusComment(?string $statusComment): self
    {
        $this->statusComment = $statusComment;

        return $this;
    }

    public function getStatusSetDate(): ?\DateTimeInterface
    {
        return $this->statusSetDate;
    }

    public function setStatusSetDate(\DateTimeInterface $statusSetDate): self
    {
        $this->statusSetDate = $statusSetDate;

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

    public function getStatusCode(): ?StatusCodes
    {
        return $this->statusCode;
    }

    public function setStatusCode(?StatusCodes $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
