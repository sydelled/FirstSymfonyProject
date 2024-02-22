<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile{id}', name: 'app_profile')]
    public function show(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'controller_name' => 'ProfileController',
            //pass user object to template
            'user' => $user
        ]);
    }
    #[Route('/profile/{id}/follows', name: 'app_profile_follows')]
    public function follows(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/{id}/followers', name: 'app_profile_followers')]
    public function followers(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user
        ]);
    }
}
