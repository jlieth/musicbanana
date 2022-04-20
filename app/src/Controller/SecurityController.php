<?php

namespace App\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    #[Route("/login", name: "login", methods: ["GET"], options: ["expose" => true])]
    #[Route("/login", name: "login_attempt", methods: ["POST"], options: ["expose" => true])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();
        $error = $authenticationUtils->getLastAuthenticationError();
        $props = [
            "errors" => $error ? $error->getMessageKey() : null
        ];

        // redirect after successful login attempt
        if ($request->getMethod() === "POST" && $user !== null) {
            return $this->redirectToRoute("index");
        }

        return $this->renderWithInertia("Login", $props);
    }

    #[Route("/logout", name: "app_logout")]
    public function logout(): void
    {
        throw new LogicException("This method can be blank - it will be intercepted by the logout key on your firewall.");
    }
}
