<?php

namespace App\Entity;

use App\Repository\ListenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListenRepository::class)]
#[ORM\UniqueConstraint(name: "profile_date", columns: ["profile_id", "date"])]
class Listen
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "datetimetz")]
    private $date;

    #[ORM\ManyToOne(targetEntity: Profile::class, inversedBy: "listens")]
    #[ORM\JoinColumn(nullable: false)]
    private $profile;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: "listens")]
    #[ORM\JoinColumn(nullable: false)]
    private $artist;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: "listens")]
    #[ORM\JoinColumn(nullable: true)]
    private $album;

    #[ORM\ManyToOne(targetEntity: Track::class, inversedBy: "listens")]
    #[ORM\JoinColumn(nullable: false)]
    private $track;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): self
    {
        $this->track = $track;

        return $this;
    }
}
