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
        $name = rawurldecode($name);
        $userRepository = $this->em->getRepository(User::class);
        $profileUser = $userRepository->findOneBy(["name" => $name]);

        if ($profileUser === null) {
            throw $this->createNotFoundException("User not found");
        }

        $props = [
            "profileUser" => $profileUser->getName()
        ];
        return $this->renderWithInertia("User/Overview", $props);
    }
}
