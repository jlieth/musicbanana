<?php

// https://github.com/aleksblendwerk/pingcrm-symfony/blob/main/src/Controller/Traits/BuildInertiaDefaultPropsTrait.php

declare(strict_types=1);

namespace App\Controller\Traits;

use ArrayObject;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

trait BuildInertiaDefaultPropsTrait
{
    /**
     * @return array<string, mixed>
     */
    protected function buildDefaultProps(Request $request, ?User $user): array
    {
        $flashSuccessMessage = null;
        $flashErrorMessage = null;

        // @phpstan-ignore-next-line
        if ($request->hasSession()) {
            /** @var Session $session */
            $session = $request->getSession();

            if ($session->getFlashBag()->has('success')) {
                $flashSuccessMessages = $session->getFlashBag()->get('success');
                $flashSuccessMessage = reset($flashSuccessMessages);
            }

            if ($session->getFlashBag()->has('success')) {
                $flashErrorMessages = $session->getFlashBag()->get('error');
                $flashErrorMessage = reset($flashErrorMessages);
            }
        }

        return [
            'errors' => new ArrayObject(),
            'auth' => [
                'user' => $user !== null
                    ? [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                        'role' => null // TODO: not sure what the vue app expects here yet...
                    ]
                    : null
            ],
            'flash' => [
                'success' => $flashSuccessMessage,
                'error' => $flashErrorMessage
            ]
        ];
    }
}
