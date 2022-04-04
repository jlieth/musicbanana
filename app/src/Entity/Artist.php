<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 1024, unique: true)]
    private $name;

    #[ORM\Column(type: "string", length: 36)]
    private $mbid;

    #[ORM\OneToMany(mappedBy: "artist", targetEntity: Album::class, orphanRemoval: true)]
    private $albums;

    #[ORM\OneToMany(mappedBy: "artist", targetEntity: Track::class, orphanRemoval: true)]
    private $tracks;

    #[ORM\OneToMany(mappedBy: "artist", targetEntity: Listen::class)]
    private $listens;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->tracks = new ArrayCollection();
        $this->listens = new ArrayCollection();
    }

    public function __toString(): string {
        return "Artist(name={$this->name})";
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

    public function getMbid(): ?string
    {
        return $this->mbid;
    }

    public function setMbid(string $mbid): self
    {
        $this->mbid = $mbid;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setArtist($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->removeElement($album)) {
            // set the owning side to null (unless already changed)
            if ($album->getArtist() === $this) {
                $album->setArtist(null);
            }
        }

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
            $track->setArtist($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->removeElement($track)) {
            // set the owning side to null (unless already changed)
            if ($track->getArtist() === $this) {
                $track->setArtist(null);
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
            $listen->setArtist($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->removeElement($listen)) {
            // set the owning side to null (unless already changed)
            if ($listen->getArtist() === $this) {
                $listen->setArtist(null);
            }
        }

        return $this;
    }
}
