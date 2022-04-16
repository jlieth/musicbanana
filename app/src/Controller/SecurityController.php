<?php

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route("/login", name: "app_login", options: ["expose" => true])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute("target_path");
        // }

        $context = [
            // get the login error if there is one
            "error" => $authenticationUtils->getLastAuthenticationError(),
            // last username entered by the user
            "last_username" => $authenticationUtils->getLastUsername(),
        ];

        return $this->render("login.html.twig", $context);
    }

    #[Route("/logout", name: "app_logout")]
    public function logout(): void
    {
        throw new LogicException("This method can be blank - it will be intercepted by the logout key on your firewall.");
    }
}
