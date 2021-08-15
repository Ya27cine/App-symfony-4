<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;



class UserPasswordSubscriber implements EventSubscriberInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->passwordEncoder = $userPasswordEncoderInterface;
    }

    public function cyrptPassword(ViewEvent $event)
    {
        $obj_entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if($obj_entity instanceof User && $method == Request::METHOD_POST){

            $crypt_pass = $this->passwordEncoder->encodePassword( $obj_entity, $obj_entity->getPassword());

            $obj_entity->setPassword(  $crypt_pass  );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => ['cyrptPassword', EventPriorities::PRE_WRITE],
        ];
    }
}
