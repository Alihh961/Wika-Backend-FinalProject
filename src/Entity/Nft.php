<?php

namespace App\Entity;

use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NftRepository::class)]
class Nft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $quantityAvailable = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'nft')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'nfts')]
    private Collection $subCategory;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?Image $image = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?Video $video = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?Audio $audio = null;

    #[ORM\ManyToOne(inversedBy: 'nfts')]
    private ?Eth $price = null;

    #[ORM\ManyToMany(targetEntity: Transaction::class, mappedBy: 'nfts')]
    private Collection $transactions;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->subCategory = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantityAvailable(): ?int
    {
        return $this->quantityAvailable;
    }

    public function setQuantityAvailable(int $quantityAvailable): static
    {
        $this->quantityAvailable = $quantityAvailable;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addNft($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeNft($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getSubCategory(): Collection
    {
        return $this->subCategory;
    }

    public function addSubCategory(SubCategory $subCategory): static
    {
        if (!$this->subCategory->contains($subCategory)) {
            $this->subCategory->add($subCategory);
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): static
    {
        $this->subCategory->removeElement($subCategory);

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getAudio(): ?Audio
    {
        return $this->audio;
    }

    public function setAudio(?Audio $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getPrice(): ?Eth
    {
        return $this->price;
    }

    public function setPrice(?Eth $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->addNft($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            $transaction->removeNft($this);
        }

        return $this;
    }

    public function __toString():string{
        return $this->getName();
    }

}