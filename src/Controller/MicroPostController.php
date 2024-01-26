<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {

        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComment(),
        ]);
    }

    #[Route('/micro-post/{post}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        //by using the param converter, it automatically converts
        //the MicroPost $posts above by the primary key
        //dd($posts);

        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(
        EntityManagerInterface $entityManager,
        Request $request,
        MicroPostRepository $posts
    ): Response
    {

        //create MicroPost instance
        $microPost= new MicroPost();
        $form = $this->createForm(MicroPostType::class, $microPost);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //if form has been submitted and validated
            //form gets data
            $post = $form->getData();
            $post->setCreated(new DateTime());

            //adding post to repository
            //using entityManager to add data to entity
            $entityManager->persist($microPost);
            $entityManager->flush();

            //flash message - only displays once
            $this->addFlash('success', 'Your post has been added!');
            //redirect
            return $this->redirectToRoute('app_micro_post');
        }

            return $this->render('micro_post/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/micro-post/{post}/edit', name: 'app_micro_post_edit')]
    public function edit(
        MicroPost $post,
        EntityManagerInterface $entityManager,
        Request $request,
        MicroPostRepository $posts
    ): Response
    {

        //fetch data from database
        //call createForm method
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //if form has been submitted and validated
            //form gets data
            $post = $form->getData();

            //adding post to repository
            //using entityManager to add data to entity
            $entityManager->persist($post);
            $entityManager->flush();

            //flash message - only displays once
            $this->addFlash('success', 'Your post has been updated!');
            //redirect
            return $this->redirectToRoute('app_micro_post_show', ['post' => $post->getId()]);
        }

        return $this->render('micro_post/edit.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route('/micro-post/{post}/comment', name: 'app_micro_post_comment')]
    public function addComment(
        MicroPost $post,
        EntityManagerInterface $entityManager,
        Request $request,
        CommentRepository $comments
    ): Response
    {

        //fetch data from database
        //call createForm method and put in FormType

        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //if form has been submitted and validated
            //form gets data
            $comment = $form->getData();
            $comment->setPost($post);

            //adding post to repository
            //using entityManager to add data to entity
            $entityManager->persist($comment);
            $entityManager->flush();

            //flash message - only displays once
            $this->addFlash('success', 'Your comment has been updated!');
            //redirect
            return $this->redirectToRoute('app_micro_post_show',
            ['post' => $post->getId()]);
        }

        return $this->render('micro_post/comment.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }
}


    //NOTES

//  how to add, update, and delete from entity
//  #[Route('/micro-post', name: 'app_micro_post')]
//    public function update(EntityManagerInterface $entityManager, MicroPostRepository $posts): Response
//    {
//          $mircoPost = $posts->find(3);
//          //$mircoPost->setTitle('C00l');
//
////        $mircoPost->setText("I come from the controller!.");
////        $mircoPost->setCreated(new DateTime());
//
//         $entityManager->remove($mircoPost, true);
//
//        // Persisting the entity, not the repository
//        //$entityManager->persist($mircoPost);
//
//        // Flushing changes to the database
//        $entityManager->flush();
//
//        return $this->render('micro_post/index.html.twig', [
//            'controller_name' => 'MicroPostController',
//        ]);
//    }

//below code dd() can fetch records
        //findAll fetches all records
        //dd($posts->find(3));
        //dd($posts -> findAll($posts));
        //to find by title you use findOneBy which accepts an array
        //you can also use findBy which can find all records
        //with the same title
        //dd($posts->findOneBy(['title'=> 'Welcome to US!']));