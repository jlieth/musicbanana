<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    #[Route("/user/{name}", name: "user_overview", methods: ["GET"], options: ["expose" => true])]
    public function overview(String $name): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        /** @var \App\Repository\UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);
        $profileUserName = rawurldecode($name);
        $profileUser = $userRepository->findOneBy(["name" => $profileUserName]);

        if ($profileUser === null) {
            throw $this->createNotFoundException("User not found");
        }

        $publicOnly = ($user !== $profileUser);

        // get recent tracks
        $recentTracks = $this
            ->getListenQueryBuilder()
            ->filterByUser($profileUser)
            ->all()
            ->page(1);

        // get top artists
        $topArtists = $this
            ->getChartsQueryBuilder()
            ->filterByUser($profileUser)
            ->artists()
            ->page(1);

        // get top albums
        $topAlbums = $this
            ->getChartsQueryBuilder()
            ->filterByUser($profileUser)
            ->albums()
            ->page(1);

        // filter out private listens
        if ($publicOnly) {
            $recentTracks->public();
            $topArtists->public();
            $topAlbums->public();
        }

        $props = [
            "profileUser" => $profileUser->getName(),
            "recentTracks" => $recentTracks->fetchAllAssociative(),
            "topArtists" => $topArtists->fetchAllAssociative(),
            "topAlbums" => $topAlbums->fetchAllAssociative(),
        ];
        return $this->renderWithInertia("User/Overview", $props);
    }
}
