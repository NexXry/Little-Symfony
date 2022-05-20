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

    #[ORM\ManyToMany(targetEntity: Sizes::class, inversedBy: 'products')]
    private $Sizes;

    #[ORM\ManyToOne(targetEntity: CategoryProdcut::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $Category;

    #[ORM\ManyToMany(targetEntity: KeyWords::class, inversedBy: 'products')]
    private $KeyWords;

    public function __construct()
    {
        $this->Sizes = new ArrayCollection();
        $this->KeyWords = new ArrayCollection();
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

    /**
     * @return Collection<int, Sizes>
     */
    public function getSizes(): Collection
    {
        return $this->Sizes;
    }

    public function addSize(Sizes $size): self
    {
        if (!$this->Sizes->contains($size)) {
            $this->Sizes[] = $size;
        }

        return $this;
    }

    public function removeSize(Sizes $size): self
    {
        $this->Sizes->removeElement($size);

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
}
