<?php

namespace App\Controller;

use App\Entity\BaseUser;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/register", "register")
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request) : Response
    {
        $baseUser = new BaseUser();

        $form = $this->createForm(RegistrationFormType::class, $baseUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $baseUser->setPlainPassword($form->get('plainPassword')->getData());
            $baseUser->setRoles(["ROLE_SEMANTIC_USER"]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($baseUser);
            $entityManager->flush();

            return $this->redirectToRoute("home");
        }

        return $this->render('user/registration.html.twig',
            [
                'registrationForm' => $form->createView()
            ]
        );
    }
}