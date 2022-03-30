<?php

namespace App\Entity;

use App\Repository\ListenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListenRepository::class)]
#[ORM\UniqueConstraint(name: "profile_timestamp", columns: ["profile_id", "timestamp"])]
class Listen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "datetimetz")]
    private $date;

    #[ORM\ManyToOne(targetEntity: Profile::class, inversedBy: "listens")]
    #[ORM\JoinColumn(nullable: false)]
    private $profile;

    #[ORM\Column(type: "integer")]
    private $timestamp;

    #[ORM\Column(type: "string", length: 1024)]
    private $artistName;

    #[ORM\Column(type: "string", length: 1024, nullable: true)]
    private $albumArtistName;

    #[ORM\Column(type: "string", length: 1024, nullable: true)]
    private $albumTitle;

    #[ORM\Column(type: "string", length: 1024)]
    private $trackTitle;

    #[ORM\Column(type: "integer")]
    private $length;

    #[ORM\Column(type: "integer", nullable: true)]
    private $tracknumber;

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

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getArtistName(): ?string
    {
        return $this->artistName;
    }

    public function setArtistName(string $artistName): self
    {
        $this->artistName = $artistName;

        return $this;
    }

    public function getAlbumArtistName(): ?string
    {
        return $this->albumArtistName;
    }

    public function setAlbumArtistName(?string $albumArtistName): self
    {
        $this->albumArtistName = $albumArtistName;

        return $this;
    }

    public function getAlbumTitle(): ?string
    {
        return $this->albumTitle;
    }

    public function setAlbumTitle(string $albumTitle): self
    {
        $this->albumTitle = $albumTitle;

        return $this;
    }

    public function getTrackTitle(): ?string
    {
        return $this->trackTitle;
    }

    public function setTrackTitle(string $trackTitle): self
    {
        $this->trackTitle = $trackTitle;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getTracknumber(): ?int
    {
        return $this->tracknumber;
    }

    public function setTracknumber(?int $tracknumber): self
    {
        $this->tracknumber = $tracknumber;

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
