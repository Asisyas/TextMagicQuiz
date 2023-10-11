<?php

namespace App\Controller;

use App\Form\AuthenticateUserType;
use App\Shared\Transfer\AuthenticationTransfer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    #[Route('/authentication', name: 'app_authentication')]
    public function index(Request $request): Response
    {
        $authenticationTransfer = new AuthenticationTransfer();
        $form = $this->createForm(AuthenticateUserType::class, $authenticationTransfer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('username', $authenticationTransfer->username);
            $request->getSession()->save();

            return new RedirectResponse($this->generateUrl('app_quiz'));
        }

        return $this->render('authentication/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
