<?php

declare(strict_types=1);

namespace App\Controller;

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
        return $this->renderWithInertia("Index");
    }
}
