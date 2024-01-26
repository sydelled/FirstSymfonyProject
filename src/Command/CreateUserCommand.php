<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Creates a new user account.',
)]
class CreateUserCommand extends Command
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private UserRepository $users;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $users,
        UserPasswordHasherInterface $userPasswordHasher

    )
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        //$this->userRepository = $users;

    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User e-mail')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = new User();
        $user->setEmail($email);
        $user->setPassword (
        //setting hashed password with UserPasswordHasherInterface constructor
            $this->userPasswordHasher->hashPassword(
                $user,
                '123456789'
            )
        );

        //$this->users->add($user, true);

        //adding post to repository
        //using entityManager to add data to entity
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('User %s account was created!', $email));

        return Command::SUCCESS;
    }
}
