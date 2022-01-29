<?php

namespace App\Service;

use App\Entity\Moderator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private TokenStorageInterface $tokenStorage;

    private UserInterface $user;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser($tokenId) {
        $token = $this->tokenStorage->getToken($tokenId);

        if ($token instanceof TokenInterface) {
            $this->user = $token->getUser();

            return $this->user;
        }
        else return null;
    }

    public function isCanDeleteCurrentPost($post) {
        return true;
    }
}