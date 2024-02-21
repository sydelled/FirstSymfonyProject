<?php

namespace App\Controller;


use App\Entity\User;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsProfileController extends AbstractController
{
    #[Route('/settings/profile', name: 'app_settings_profile')]
    public function profile(Request $request): Response
    {
        /**@var User $user*/
        $user = $this->getUser();

        if ($user === null) {
            // Redirect to login route when user is null
            return $this->redirectToRoute('app_login');
        }

        $userProfile = $user->getUserProfile() ?? new UserProfile();

        $form = $this->createForm(
            UserProfileType::class, $userProfile
        );
        $form->handleRequest($request);;
        if ($form->isSubmitted() && $form->isValid()){
            $userProfile=$form->getData();
            //save this
            //add flash message
            //redirect
        }
        return $this->render('settings_profile/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
