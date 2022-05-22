<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['product:read']],
    collectionOperations: [
    'get'=>[
        "method"=>"GET",
    ]
],itemOperations: ['get'=>['method'=>'GET']]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product:read'])]
    private $id;

    #[Groups(['product:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[Groups(['product:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $descrip;

    #[Groups(['product:read'])]
    #[ORM\ManyToOne(targetEntity: CategoryProdcut::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $Category;

    #[Groups(['product:read'])]
    #[ORM\ManyToMany(targetEntity: KeyWords::class, inversedBy: 'products')]
    private $KeyWords;

    #[Groups(['product:read'])]
    #[ORM\ManyToMany(targetEntity: TshirtSizes::class, inversedBy: 'theProduct')]
    private $tshirtSizes;

    #[Groups(['product:read'])]
    #[ORM\ManyToMany(targetEntity: ShoesSizes::class, inversedBy: 'theProduct')]
    private $shoesSizes;

    #[Groups(['product:read'])]
    #[ORM\OneToMany(mappedBy: 'Product', targetEntity: Images::class,cascade: ['persist'])]
    private $images;

    public function __construct()
    {
        $this->Sizes = new ArrayCollection();
        $this->KeyWords = new ArrayCollection();
        $this->tshirtSizes = new ArrayCollection();
        $this->shoesSizes = new ArrayCollection();
        $this->images = new ArrayCollection();
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

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }
}
