<?php

namespace App\Entity;

use App\Repository\ShoesSizesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ShoesSizesRepository::class)]
class ShoesSizes
{
    #[ORM\Id]
    #[Groups(['product:read'])]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['product:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'shoesSizes')]
    private $theProduct;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->theProduct = new ArrayCollection();
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


    public function __toString()
    {
        return $this->name;    
    }

    /**
     * @return Collection<int, Product>
     */
    public function getTheProduct(): Collection
    {
        return $this->theProduct;
    }

    public function addTheProduct(Product $theProduct): self
    {
        if (!$this->theProduct->contains($theProduct)) {
            $this->theProduct[] = $theProduct;
            $theProduct->addShoesSize($this);
        }

        return $this;
    }

    public function removeTheProduct(Product $theProduct): self
    {
        if ($this->theProduct->removeElement($theProduct)) {
            $theProduct->removeShoesSize($this);
        }

        return $this;
    }
}
