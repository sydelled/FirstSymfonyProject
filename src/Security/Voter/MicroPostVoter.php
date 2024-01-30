<?php

namespace App\Security\Voter;

use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MicroPostVoter extends Voter
{

    private Security $security;  // Add this property

    public function __construct(Security $security)
    {
        $this->security = $security; // injected security service to the property
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [MicroPost::EDIT, MicroPost::VIEW])
            && $subject instanceof MicroPost;
    }

    /**
     * @param MicroPost $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user  */
        $user = $token->getUser();

        //checks if user is authenticated
        $isAuth = $user instanceof UserInterface;
        if($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }
        return match ($attribute) {
            MicroPost::EDIT => $isAuth && (
                    ($subject->getAuthor()->getId() === $user->getId()) ||
                    $this->security->isGranted('ROLE_EDITOR')
                ),
            MicroPost::VIEW => true,
            default => throw new \LogicException('This code should not be reached!')
        };

    }
}

//OLD CODE BELOW////

// ... (check conditions and return true to grant permission) ...
//        switch ($attribute) {
//            case MicroPost::EDIT:
//                // logic to determine if the user can EDIT
//                // return true or false
//                return $isAuth && (
//                    ($subject->getAuthor()->getId() === $user->getId()) ||
//                    $this->security->isGranted('ROLE_EDITOR')
//                    );
//                break;
//
//            case MicroPost::VIEW:
//                // logic to determine if the user can VIEW
//                // return true or false
//                return true;
//        }