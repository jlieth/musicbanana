<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\UniqueConstraint(name: "artist_albumtitle", columns: ["artist_id", "title"])]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 1024)]
    private $title;

    #[ORM\Column(type: "string", length: 36, nullable: true)]
    private $mbid;

    #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: "albums")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private $artist;

    #[ORM\OneToMany(mappedBy: "album", targetEntity: Track::class)]
    private $tracks;

    #[ORM\OneToMany(mappedBy: "album", targetEntity: Listen::class)]
    private $listens;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->listens = new ArrayCollection();
    }

    public function __toString(): string {
        return "Album(title={$this->title}, artist={$this->artist})";
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

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setAlbum($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getAlbum() === $this) {
                $track->setAlbum(null);
            }
        }

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
            $listen->setAlbum($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->removeElement($listen)) {
            // set the owning side to null (unless already changed)
            if ($listen->getAlbum() === $this) {
                $listen->setAlbum(null);
            }
        }

        return $this;
    }
}
