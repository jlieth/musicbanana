<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Artist;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends BaseController
{
    #[Route("/music/{artistName}", name: "music_overview", methods: ["GET"], options: ["expose" => true])]
    public function overview(String $artistName): Response
    {
        $artistRepository = $this->em->getRepository(Artist::class);
        $artistName = rawurldecode($artistName);
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
}
