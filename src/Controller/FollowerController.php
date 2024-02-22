<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowerController extends AbstractController
{
    #[Route('/follow{id}', name: 'app_follow')]
    public function follow(
        User $userToFollow,
        ManagerRegistry $doctrine,
        Request $request
    ): Response
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        if ($userToFollow->getId() !== $currentUser->getId()){
            $currentUser->follow($userToFollow);
            //need to insert new record to table
            $doctrine->getManager()->flush();
        }
        $referer = $request->headers->get('referer');
        //if referer is not available the user will be redirected to app_micro_post
        $redirectUrl = $referer ? $referer : $this->generateUrl('app_micro_post');

        return $this->redirect($redirectUrl);
    }
    #[Route('/unfollow{id}', name: 'app_unfollow')]
    public function unfollow(User $userToUnfollow,
                             ManagerRegistry $doctrine,
                             Request $request
    ): Response
    {
        /**@var User $currentUser */
        $currentUser = $this->getUser();
        if ($userToUnfollow->getId() !== $currentUser->getId()) {
            $currentUser->unfollow($userToUnfollow);
            //need to insert new record to table
            $doctrine->getManager()->flush();
        }
        $referer = $request->headers->get('referer');
        //if referer is not available the user will be redirected to app_micro_post
        $redirectUrl = $referer ? $referer : $this->generateUrl('app_micro_post');

        return $this->redirect($redirectUrl);
    }
}
