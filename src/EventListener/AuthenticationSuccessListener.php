<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event) {
        $data = $event->getData();
        $user = $event->getUser();

        $data['user'] = array(
            'roles' => $user->getRoles(),
            'username' => $user->getUserIdentifier()
        );

        $event->setData($data);
    }
}