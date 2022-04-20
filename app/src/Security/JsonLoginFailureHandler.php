<?php

// https://github.com/aleksblendwerk/pingcrm-symfony/blob/main/src/Security/JsonLoginFailureHandler.php

declare(strict_types=1);

namespace App\Security;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class JsonLoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    protected InertiaInterface $inertia;
    protected RouterInterface $router;

    public function __construct(InertiaInterface $inertia, RouterInterface $router)
    {
        $this->inertia = $inertia;
        $this->router = $router;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            /** @var Session $session */
            $session = $request->getSession();
            $session->set(Security::AUTHENTICATION_ERROR, $exception);
            $session->getFlashBag()->add("error", $exception->getMessageKey());
        }

        return new RedirectResponse($this->router->generate("login"));
    }
}
