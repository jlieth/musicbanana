<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
#[ORM\UniqueConstraint(name: "artist_album_tracktitle_tracknumber", columns: ["artist_id", "album_id", "title", "tracknumber"])]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 1024)]
    private $title;

    #[ORM\Column(type: "string", length: 36)]
    private $mbid;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: "tracks")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $artist;

    #[ORM\Column(type: "integer")]
    private $length;

    #[ORM\ManyToOne(targetEntity: Album::class, inversedBy: "tracks")]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $album;

    #[ORM\Column(type: "integer", nullable: true)]
    private $tracknumber;

    #[ORM\OneToMany(mappedBy: "track", targetEntity: Listen::class)]
    private $listens;

    public function __construct()
    {
        $this->listens = new ArrayCollection();
    }

    public function __toString(): string {
        return "Track(title={$this->title}, artist={$this->artist}, album={$this->album})";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getMbid(): ?string
    {
        return $this->mbid;
    }

    public function setMbid(string $mbid): self
    {
        $this->mbid = $mbid;

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
            $listen->setTrack($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->removeElement($listen)) {
            // set the owning side to null (unless already changed)
            if ($listen->getTrack() === $this) {
                $listen->setTrack(null);
            }
        }

        return $this;
    }
}
