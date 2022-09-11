<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\{Album, Artist, Track};
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends BaseController
{
    #[Route("/music/{artistName}", name: "music_overview", methods: ["GET"], options: ["expose" => true])]
    public function overview(String $artistName): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);
        $artist = $artistRepository->findOneBy(["name" => $artistName]);

        if ($artist === null) {
            throw $this->createNotFoundException("Artist not found");
        }

        // get top albums
        $topAlbums = $this
            ->getChartsQueryBuilder()
            ->filterByAlbumArtist($artist)
            ->public()
            ->albums()
            ->page(1);

        // get top tracks
        $topTracks = $this
            ->getChartsQueryBuilder()
            ->filterByArtist($artist)
            ->public()
            ->tracks()
            ->page(1);

        $props = [
            "artist" => [
                "id" => $artist->getId(),
                "name" => $artist->getName(),
                "mbid" => $artist->getMbid(),
            ],
            "topAlbums" => $topAlbums->fetchAllAssociative(),
            "topTracks" => $topTracks->fetchAllAssociative(),
        ];

        return $this->renderWithInertia("Music/Overview", $props);
    }

    #[Route("/music/{artistName}/{albumTitle}", name: "music_album", methods: ["GET"], options: ["expose" => true])]
    public function album(String $artistName, String $albumTitle): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);
        $albumRepository = $this->em->getRepository(Album::class);

        $artist = $artistRepository->findOneBy(["name" => $artistName]);
        if ($artist === null) {
            throw $this->createNotFoundException("Artist not found");
        }

        $album = $albumRepository->findOneBy(["artist" => $artist, "title" => $albumTitle]);
        if ($album === null) {
            throw $this->createNotFoundException("Album not found");
        }

        // get tracklist
        $trackList = $this
            ->getChartsQueryBuilder()
            ->filterByAlbum($album)
            ->public()
            ->trackList()
            ->fetchAllAssociative();

        $maxCount = 0;
        foreach ($trackList as $track) {
            $count = $track["count"];
            $maxCount = max($maxCount, $count);
        }

        $props = [
            "artist" => [
                "id" => $artist->getId(),
                "name" => $artist->getName(),
                "mbid" => $artist->getMbid(),
            ],
            "album" => [
                "id" => $album->getId(),
                "title" => $album->getTitle(),
                "mbid" => $album->getMbid(),
                "artistName" => $album->getArtist()->getName(),
            ],
            "trackList" => $trackList,
            "maxCount" => $maxCount,
        ];

        return $this->renderWithInertia("Music/Album", $props);
    }

    #[Route("/music/{artistName}/_/{trackTitle}", name: "music_track", methods: ["GET"], options: ["expose" => true])]
    public function track(String $artistName, String $trackTitle): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);
        $trackRepository = $this->em->getRepository(Track::class);

        $artist = $artistRepository->findOneBy(["name" => $artistName]);
        if ($artist === null) {
            throw $this->createNotFoundException("Artist not found");
        }

        $result = $trackRepository->findBy(["artist" => $artist, "title" => $trackTitle]);
        if (count($result) === 0) {
            throw $this->createNotFoundException("Track not found");
        }

        $tracks = [];
        foreach ($result as $track) {
            $artist = $track->getArtist();
            $album = $track->getAlbum();

            $tracks[] = [
                "artist_name" => $artist->getName(),
                "track_title" => $track->getTitle(),
                "album_title" => $album ? $album->getTitle() : null,
                "length" => $track->getLength(),
                "tracknumber" => $track->getTracknumber(),
            ];
        }

        $props = [
            "artist" => [
                "id" => $artist->getId(),
                "name" => $artist->getName(),
                "mbid" => $artist->getMbid(),
            ],
            "trackTitle" => $trackTitle,
            "tracks" => $tracks,
        ];

        return $this->renderWithInertia("Music/Track", $props);
    }
}
