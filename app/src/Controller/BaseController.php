<?php

// https://github.com/aleksblendwerk/pingcrm-symfony/blob/main/src/Controller/AbstractInertiaController.php

declare(strict_types=1);

namespace App\Controller;

use RuntimeException;
use App\Controller\Traits\DefaultProps;
use App\Entity\User;
use App\QueryBuilder\ListenQueryBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    use DefaultProps;

    protected EntityManagerInterface $em;
    protected RequestStack $requestStack;
    protected CsrfTokenManagerInterface $tokenManager;
    protected Connection $connection;
    protected InertiaInterface $inertia;
    protected ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $requestStack,
        CsrfTokenManagerInterface $tokenManager,
        Connection $connection,
    ) {
        $this->em = $entityManager;
        $this->requestStack = $requestStack;
        $this->tokenManager = $tokenManager;
        $this->connection = $connection;
    }

    /**
     * @required
     */
    public function setInertia(InertiaInterface $inertia): void
    {
        $this->inertia = $inertia;
    }

    /**
     * @required
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    protected function getListenQueryBuilder(): ListenQueryBuilder
    {
        return new ListenQueryBuilder($this->connection);
    }

    /**
     * @param array<string, mixed> $props
     * @param array<string, mixed> $viewData
     * @param array<string, mixed> $context
     */
    protected function renderWithInertia(
        string $component,
        array $props = [],
        array $viewData = [],
        array $context = []
    ): Response {
        /** @var ?User $currentUser */
        $currentUser = $this->getUser();

        $request = $this->requestStack->getCurrentRequest();

        if ($request === null) {
            throw new RuntimeException('There is no current request.');
        }

        $defaultProps = $this->buildDefaultProps($request, $currentUser);

        return $this->inertia->render($component, array_merge($defaultProps, $props), $viewData, $context);
    }
}
