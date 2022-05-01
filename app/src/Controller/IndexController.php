<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\{Album, Artist, Listen, Track};
use Doctrine\Orm\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseController
{
    #[Route("/foo", name: "indexfoo")]
    public function indexfoo(): Response
    {
        return $this->render("index.html.twig", [
            "controller_name" => "IndexController",
        ]);
    }

    #[Route("/", name: "index", methods: ["GET"], options: ["expose" => true])]
    public function index(): Response
    {
        /** @var array<EntityRepository> $repos */
        $repos = [
            "listens" => $this->em->getRepository(Listen::class),
            "artists" => $this->em->getRepository(Artist::class),
            "albums" => $this->em->getRepository(Album::class),
            "tracks" => $this->em->getRepository(Track::class),
        ];

        $counts = [
            "listens" => $repos["listens"]->count([]),
            "artists" => $repos["artists"]->count([]),
            "albums" => $repos["albums"]->count([]),
            "tracks" => $repos["tracks"]->count([]),
        ];
        $props = ["counts" => $counts];
        return $this->renderWithInertia("Index", $props);
    }
}
