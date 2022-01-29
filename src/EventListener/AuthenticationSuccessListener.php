<?php

namespace App\EventListener;

use App\Repository\ModeratorRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    private ModeratorRepository $userRepository;

    public function __construct(ModeratorRepository $moderatorRepository)
    {
        $this->userRepository = $moderatorRepository;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event) {
        $data = $event->getData();
        $user = $event->getUser();

        $data['user'] = array(
            'roles' => ($this->userRepository->findOneByUsername($event->getUser()->getUserIdentifier()))->getRoles(),
            'username' => $user->getUserIdentifier()
        );

        $event->setData($data);
    }
}