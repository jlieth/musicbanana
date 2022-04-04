<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\UniqueConstraint(name: "user_profilename_unique", columns: ["name", "user_id"])]
#[UniqueEntity(fields: ["user_id", "name"], message: "This user already has a profile with this name")]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "profiles")]
    #[ORM\JoinColumn(name: "user_id", nullable: false, onDelete: "CASCADE")]
    private $user;

    #[ORM\Column(type: "boolean")]
    private $isPublic;

    #[ORM\Column(type: "datetimetz")]
    private $created;

    #[ORM\OneToMany(mappedBy: "profile", targetEntity: Listen::class)]
    private $listens;

    public function __construct()
    {
        $this->created = new DateTime("now", new DateTimeZone("UTC"));
        $this->listens = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @return Collection|Listen[]
     */
    public function getListens(): Collection
    {
        return $this->listens;
    }

    public function addListen(Listen $listen): self
    {
        if (!$this->listens->contains($listen)) {
            $this->listens[] = $listen;
            $listen->setProfile($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->removeElement($listen)) {
            // set the owning side to null (unless already changed)
            if ($listen->getProfile() === $this) {
                $listen->setProfile(null);
            }
        }

        return $this;
    }
}
