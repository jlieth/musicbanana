<?php

// https://github.com/aleksblendwerk/pingcrm-symfony/blob/main/src/Controller/Traits/BuildInertiaDefaultPropsTrait.php

declare(strict_types=1);

namespace App\Controller\Traits;

use ArrayObject;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

trait DefaultProps
{
    /**
     * @return array<string, mixed>
     */
    protected function buildDefaultProps(Request $request, ?User $user): array
    {
        $flashSuccessMessage = null;
        $flashErrorMessage = null;

        $props = [];
        $props["user"] = null;

        if ($user !== null) {
            $props["user"] = [
                "id" => $user->getId(),
                "name" => $user->getName()
            ];
        }

        // @phpstan-ignore-next-line
        if ($request->hasSession()) {
            /** @var Session $session */
            $session = $request->getSession();

            if ($session->getFlashBag()->has("success")) {
                $flashSuccessMessages = $session->getFlashBag()->get("success");
                $flashSuccessMessage = reset($flashSuccessMessages);
            }

            if ($session->getFlashBag()->has("error")) {
                $flashErrorMessages = $session->getFlashBag()->get("error");
                $flashErrorMessage = reset($flashErrorMessages);
            }
        }
        $props["flash"] = [
            "success" => $flashSuccessMessage,
            "error" => $flashErrorMessage
        ];

        return $props;
    }
}
