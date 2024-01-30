<?php

//all files under src has a namespace starting
//with a name defined in composer.json file

namespace App\Controller;

//controllers have actions (methods) with one controller
//to group similar requests together
//revolve around CRUD (create, read, update, delete)
//example: a blog post has a controller with methods to
//create, delete, list, and update posts

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController {

    private array $messages = [

        ['message' => 'Hello', 'created' => '2023/12/12'],
        ['message' => 'Hi', 'created' => '2024/01/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12']
  ];

    //you can define the route above the action
    //import the class (annotation)
    //you can put a parameter limiting the array

    #[Route('/', name: 'app_index')]
    public function index(
        EntityManagerInterface $entityManager,
        MicroPostRepository $posts,
        CommentRepository $comments,
    ): Response {


        return $this->render('hello/index.html.twig',
        [
            'messages' => $this->messages,
            'limit' => 3
        ]
        );
    }

    //a route parameter needs to be specified by regular expressions
    //example: <\d+> means a number
    //so if someone types a string in the url instead of a number
    //they would get a 404 error which is better than crashing
    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]

    public function showOne($id): Response {

        //this method renders the array in twig template
        return $this->render(
            'hello/show_one.html.twig',
            ['message' => $this->messages[$id]]
        );
//        return new Response($this->messages[$id]);

    }
}

//NOTES

//every controller returns a response with a response class
//and then the Response class needs to be imported from
//the namespace (HttpFoundation)

//implode function turns an array into a string
//use array_slice to only slice array 0 to 3
//the slice is now being passed to the template

//ADDING user to userProfile, must have EntityManager

//        $user = new User();
//        $user->setEmail('email2@email.com');
//        $user->setPassword('1234');

//        $profile = new UserProfile();
//        $profile->setUser($user);
//
//        $entityManager->persist($profile);
//        $entityManager->flush();

// NOTES
//to remove user with profile id of 1
//this will remove the user and also the profile associated with the user
//because cascade effect in the userProfile repo

//        $profile = $profiles->find(1);
//        $entityManager->remove($profile);
//        $entityManager->flush();
