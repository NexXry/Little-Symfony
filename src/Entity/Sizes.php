<?php

namespace App\Entity;

use App\Repository\SizesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SizesRepository::class)]
class Sizes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'Sizes')]
    private $products;

    #[ORM\ManyToOne(targetEntity: CategoryProdcut::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addSize($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeSize($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;    
    }

    public function getCategory(): ?CategoryProdcut
    {
        return $this->category;
    }

    public function setCategory(?CategoryProdcut $category): self
    {
        $this->category = $category;

        return $this;
    }

}
