<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $descrip;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;


    #[ORM\ManyToOne(targetEntity: CategoryProdcut::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $Category;

    #[ORM\ManyToMany(targetEntity: KeyWords::class, inversedBy: 'products')]
    private $KeyWords;

    #[ORM\ManyToMany(targetEntity: TshirtSizes::class, inversedBy: 'theProduct')]
    private $tshirtSizes;

    #[ORM\ManyToMany(targetEntity: ShoesSizes::class, inversedBy: 'theProduct')]
    private $shoesSizes;

    public function __construct()
    {
        $this->Sizes = new ArrayCollection();
        $this->KeyWords = new ArrayCollection();
        $this->tshirtSizes = new ArrayCollection();
        $this->shoesSizes = new ArrayCollection();
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

    public function getDescrip(): ?string
    {
        return $this->descrip;
    }

    public function setDescrip(string $descrip): self
    {
        $this->descrip = $descrip;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }



    public function getCategory(): ?CategoryProdcut
    {
        return $this->Category;
    }

    public function setCategory(?CategoryProdcut $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @return Collection<int, KeyWords>
     */
    public function getKeyWords(): Collection
    {
        return $this->KeyWords;
    }

    public function addKeyWord(KeyWords $keyWord): self
    {
        if (!$this->KeyWords->contains($keyWord)) {
            $this->KeyWords[] = $keyWord;
        }

        return $this;
    }

    public function removeKeyWord(KeyWords $keyWord): self
    {
        $this->KeyWords->removeElement($keyWord);

        return $this;
    }
    public function __toString()
    {
        return $this->name;    
    }

    /**
     * @return Collection<int, TshirtSizes>
     */
    public function getTshirtSizes(): Collection
    {
        return $this->tshirtSizes;
    }

    public function addTshirtSize(TshirtSizes $tshirtSize): self
    {
        if (!$this->tshirtSizes->contains($tshirtSize)) {
            $this->tshirtSizes[] = $tshirtSize;
        }

        return $this;
    }

    public function removeTshirtSize(TshirtSizes $tshirtSize): self
    {
        $this->tshirtSizes->removeElement($tshirtSize);

        return $this;
    }

    /**
     * @return Collection<int, ShoesSizes>
     */
    public function getShoesSizes(): Collection
    {
        return $this->shoesSizes;
    }

    public function addShoesSize(ShoesSizes $shoesSize): self
    {
        if (!$this->shoesSizes->contains($shoesSize)) {
            $this->shoesSizes[] = $shoesSize;
        }

        return $this;
    }

    public function removeShoesSize(ShoesSizes $shoesSize): self
    {
        $this->shoesSizes->removeElement($shoesSize);

        return $this;
    }

}
