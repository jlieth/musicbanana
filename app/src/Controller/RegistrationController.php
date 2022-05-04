<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends BaseController
{
    #[Route("/register", name: "register", methods: ["GET"], options: ["expose" => true])]
    #[Route("/register", name: "register_attempt", methods: ["POST"], options: ["expose" => true])]
    public function registerGet(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($request->getMethod() === "POST") {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->submit($request->request->all());

            foreach ($form->getErrors(true) as $error) {
                $this->addFlash("error", $error->getMessage());
            }

            if ($form->isValid()) {
                // encode the plain password
                $plaintext = $form->get("plainPassword")->getData();
                $password = $userPasswordHasher->hashPassword($user, $plaintext);

                $user->setPassword($password);
                $this->em->persist($user);
                $this->em->flush();

                $msg = "Your account has been created. You can log in now.";
                $this->addFlash("success", $msg);

                return $this->redirectToRoute("login");
            }
        }

        $token = $this->tokenManager->getToken("registration_form")->getValue();
        $props = ["token" => $token];

        return $this->renderWithInertia("Register", $props);
    }
}
