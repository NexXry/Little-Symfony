<?php

namespace App\Entity;

use App\Repository\KeyWordsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KeyWordsRepository::class)]
class KeyWords
{
    #[ORM\Id]
    #[Groups(['product:read'])]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['product:read'])]
    #[ORM\Column(type: 'string', length: 255,unique:true)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'KeyWords')]
    private $products;

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
            $product->addKeyWord($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeKeyWord($this);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;    
    }

}
