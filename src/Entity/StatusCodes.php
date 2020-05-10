<?php

namespace App\Entity;

use App\Repository\StatusCodesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusCodesRepository::class)
 * @ORM\Table(name="status_codes")
 */
class StatusCodes
{
    /**
     * @var string
     *
     * @ORM\Column(name="scode", type="string", length=5, nullable=false)
     * @ORM\Id
     */
    private $scode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="title", type="string", length=30, nullable=true)
     */
    private $title;

    public function getScode(): ?string
    {
        return $this->scode;
    }

    public function setScode(string $code): self
    {
        $this->scode = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
