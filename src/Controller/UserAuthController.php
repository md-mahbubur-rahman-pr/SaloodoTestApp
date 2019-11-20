<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Member;
use App\Entity\Users;
use App\Enums\UserRoleEnum;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAuthController extends AbstractController
{
    public function authentication(Request $request, JWTTokenManagerInterface $JWTManager, UserInterface $user)
    {
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }


        return new JsonResponse(['accessToken' => $JWTManager->create($user)], 200);
    }

    public function register(Request $request,  UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $user = new Users();
        $email = (\array_key_exists('email', $data)) ? $data['email'] : null;
        $username = (\array_key_exists('username', $data)) ? $data['username'] : null;
        $user->setUsername($username);
        $user->setEmail($email) ;
        $user->setPassword((\array_key_exists('password', $data)) ? $data['password'] : null);

        $errors = $validator->validate($user, ['groups' => 'registration']);

        if (!empty($errors)) {
            return new Response($errors);
        }

        $user->updatePassword($encoder->encodePassword($user, $data['password']));

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => sprintf('User %s successfully created', $user->getUsername())], 201);
    }
}
