<?php

namespace App\Entity;

use App\Repository\NftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NftRepository::class)]
class Nft
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["nft" , "nftBySlug"])]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'nfts' ,cascade: ['persist'])]
    #[Groups("nft")]
    private Collection $subCategory;

    #[Groups(["nft" , "nftBySlug"])]
    #[ORM\ManyToOne(inversedBy: 'nfts' ,cascade: ['persist', 'remove'])]
    private ?Image $image = null;


    #[ORM\ManyToMany(targetEntity: Transaction::class, mappedBy: 'nfts')]
    private Collection $transactions;

    #[Groups(["nft" , "nftBySlug"])]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'nfts' ,cascade: ["persist"])]
    private Collection $users;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["nft" , "nftBySlug"])]
    private ?\DateTimeInterface $createdAt = null;


    public function __construct()
    {
        $this->subCategory = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return "tetetette";
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
