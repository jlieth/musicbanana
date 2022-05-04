<?php

// https://github.com/aleksblendwerk/pingcrm-symfony/blob/main/src/Controller/Traits/BuildInertiaDefaultPropsTrait.php

declare(strict_types=1);

namespace App\Controller\Traits;

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
        $props = ["user" => null];

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
                $success = $session->getFlashBag()->get("success");
            }

            if ($session->getFlashBag()->has("error")) {
                $error = $session->getFlashBag()->get("error");
            }

            if ($session->getFlashBag()->has("notice")) {
                $notice = $session->getFlashBag()->get("notice");
            }
        }

        $props["flash"] = [
            "success" => $success ?? [],
            "error" => $error ?? [],
            "notice" => $notice ?? [],
        ];

        return $props;
    }
}
