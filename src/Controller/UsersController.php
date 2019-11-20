<?php

namespace App\Controller;

use ApiPlatform\Core\JsonLd\Action\ContextAction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UsersController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(Request $request, NormalizerInterface $decorated)
    {
        $member =  $this->getUser();

        $memberArray = $decorated->normalize($member, "jsonld");
        unset($memberArray["password"]);
        return new JsonResponse($memberArray, Response::HTTP_OK);
    }
}
