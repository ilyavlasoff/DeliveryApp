<?php

namespace App\Entity;

use App\Repository\ItemCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemCategoryRepository::class)
 * @ORM\Table(name="item_category")
 */
class ItemCategory
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
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="fire_danger", type="boolean", nullable=false)
     */
    private $fireDanger;

    /**
     * @var bool
     *
     * @ORM\Column(name="toxic", type="boolean", nullable=false)
     */
    private $toxic;

    /**
     * @var bool
     *
     * @ORM\Column(name="explosive", type="boolean", nullable=false)
     */
    private $explosive;

    /**
     * @var ItemCategory
     *
     * @ORM\ManyToOne(targetEntity="ItemCategory", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id_cat", referencedColumnName="id", nullable=true)
     * })
     */
    private $parentIdCat;

    /**
     * @ORM\OneToMany(targetEntity="ItemCategory", mappedBy="parentIdCat")
     */
    private $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFireDanger(): ?bool
    {
        return $this->fireDanger;
    }

    public function setFireDanger(bool $fireDanger): self
    {
        $this->fireDanger = $fireDanger;

        return $this;
    }

    public function getToxic(): ?bool
    {
        return $this->toxic;
    }

    public function setToxic(bool $toxic): self
    {
        $this->toxic = $toxic;

        return $this;
    }

    public function getExplosive(): ?bool
    {
        return $this->explosive;
    }

    public function setExplosive(bool $explosive): self
    {
        $this->explosive = $explosive;

        return $this;
    }

    public function getParentIdCat(): ?self
    {
        return $this->parentIdCat;
    }

    public function setParentIdCat(?self $parentIdCat): self
    {
        $this->parentIdCat = $parentIdCat;

        return $this;
    }

    /**
     * @return Collection|ItemCategory[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(ItemCategory $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParentIdCat($this);
        }

        return $this;
    }

    public function removeChild(ItemCategory $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParentIdCat() === $this) {
                $child->setParentIdCat(null);
            }
        }

        return $this;
    }

}
