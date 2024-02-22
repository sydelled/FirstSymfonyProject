<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LikeController extends AbstractController
{
    #[Route('/like{id}', name: 'app_like')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function like(
        MicroPost $post,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        /** @var $currentUser User */
        $currentUser=$this->getUser();
        $post->addLikedBy($currentUser);
        $entityManager->persist($post);
        $entityManager->flush();

        //redirect to last page user used
        //using headers property from request
        //fetching it with get method
        $referer = $request->headers->get('referer');
        //if referer is not available the user will be redirected to app_micro_post
        $redirectUrl = $referer ? $referer : $this->generateUrl('app_micro_post');

        return $this->redirect($redirectUrl);

    }
    #[Route('/unlike{id}', name: 'app_unlike')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function unlike(
        MicroPost $post,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        /** @var $currentUser User */
        $currentUser=$this->getUser();
        $post->removeLikedBy($currentUser);
        $entityManager->persist($post);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        //if referer is not available the user will be redirected to app_micro_post
        $redirectUrl = $referer ? $referer : $this->generateUrl('app_micro_post');

        return $this->redirect($redirectUrl);

    }

}
