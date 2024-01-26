<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ){

    }


    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('text@text.com');
        $user1->setPassword (
            //setting hashed password with UserPasswordHasherInterface constructor
            $this->userPasswordHasher->hashPassword(
                $user1,
                '123456789'
            )
        );
        //need to call manager object to persist user
        $manager->persist($user1);


        $user2 = new User();
        $user2->setEmail('john@text.com');
        $user2->setPassword (
        //setting hashed password with UserPasswordHasherInterface constructor
            $this->userPasswordHasher->hashPassword(
                $user2,
                '123456789'
            )
        );
        //need to call manager object to persist user
        //this puts the data in the database
        $manager->persist($user2);


       $microPost1 = new MicroPost();
        $microPost1 ->setTitle('Welcome to Poland');
        $microPost1 ->setText("This is my first symfony project.");
        $microPost1 -> setCreated(new DateTime());
        $microPost1->setAuthor($user1);

       //the below code tells doctrine that you want
        //this microPost1 save
        $manager->persist($$microPost1);

        $microPost2 = new MicroPost();
        $microPost2 ->setTitle('Welcome to US!');
        $microPost2 ->setText("I am from the US!.");
        $microPost2 -> setCreated(new DateTime());
        $microPost2->setAuthor($user2);

        //the below code tells doctrine that you want
        //this microPost1 save
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3 ->setTitle('Welcome to Germany!');
        $microPost3 ->setText("I would like to go to Germany!.");
        $microPost3 -> setCreated(new DateTime());
        $microPost3->setAuthor($user2);

        //the below code tells doctrine that you want
        //this microPost1 save
        $manager->persist($microPost3);

        //the below code makes sure the query is executed
        $manager->flush();
    }
}
