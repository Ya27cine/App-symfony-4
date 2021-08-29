<?php

namespace  App\Controller;

use App\Entity\User;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ResetPasswordAction
{
    private $validator;
    private $userPasswordEncoderInterface;
    private $entityManagerInterface;
    private $jWTTokenManagerInterface;


    public function __construct(ValidatorInterface $validator, 
    UserPasswordEncoderInterface $userPasswordEncoderInterface, 
    EntityManagerInterface $entityManagerInterface,
    JWTTokenManagerInterface $jWTTokenManagerInterface)
    {
        $this->validator = $validator;
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->jWTTokenManagerInterface  = $jWTTokenManagerInterface;
    }
    public function __invoke(User $data)
    {
        //var_dump(
        //    $data->getNewPassword(), $data->getNewRetypedPassword(), $data->getOldPassword()
      //  die();
       

      $this->validator->validate($data);
      $data->setPassword(
                $this->userPasswordEncoderInterface->encodePassword($data, $data->getNewPassword() )
      );

        // save in db
      $this->entityManagerInterface->flush();

      //gene new jwt  token :
      $token = $this->jWTTokenManagerInterface->create($data);

      return new JsonResponse(["token" => $token ]);
    }
}
?>