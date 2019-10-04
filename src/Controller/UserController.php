<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaseUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @Route("/api", name="api_")
 */
class UserController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * UserController constructor.
     *
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator
    )
    {
        $this->em         = $em;
        $this->serializer = $serializer;
        $this->validator  = $validator;
    }

    /**
     * @Rest\Post("/base_user")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postBaseUser(Request $request): JsonResponse
    {
        $baseUser = new BaseUser();

        $baseUser->setLogin($request->request->get("username"));
        $baseUser->setPlainPassword($request->request->get("password"));

        $baseUser->setRoles([BaseUser::ROLE_SEMANTIC_USER]);

        // Validation
        $errors = $this->validator->validate($baseUser);
        if (count($errors) > 0) {
            $errorsStr = (string) $errors;
            return new JsonResponse(json_encode($errorsStr), Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($baseUser);
        $this->em->flush();

        $userToResp = clone $baseUser;
        $userToResp->setPlainPassword("");

        return new JsonResponse($this->serializer->serialize($userToResp, JsonEncoder::FORMAT),
            Response::HTTP_CREATED, [],
            true);
    }

    /**
     * @Rest\Put("/base_user")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function putBaseUser(Request $request): JsonResponse
    {
        $baseUserId = $request->request->get("userId");

        if (!is_null($baseUserId)) {
            $baseUser = $this->em->find("App:BaseUser", $baseUserId);

            if ($baseUser instanceof BaseUser) {
                $baseUser->setLogin($request->request->get("username"));
                $baseUser->setPlainPassword($request->request->get("password"));

                // Validation
                $errors = $this->validator->validate($baseUser);
                if (count($errors) > 0) {
                    $errorsStr = (string) $errors;

                    return new JsonResponse(json_encode($errorsStr), Response::HTTP_BAD_REQUEST, [], true);
                }

                $this->em->flush();

                $userToResp = clone $baseUser;
                $userToResp->setPlainPassword("");

                return new JsonResponse($this->serializer->serialize($userToResp, JsonEncoder::FORMAT),
                    Response::HTTP_OK, [],
                    true);
            } else {
                return new JsonResponse("Can't find user with identifier: $baseUserId.",
                    Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("User identifier is null.", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Rest\Delete("/base_user")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteBaseUser(Request $request): JsonResponse
    {
        $baseUserId = $request->request->get("userId");

        if (!is_null($baseUserId)) {
            $baseUser = $this->em->find("App:BaseUser", $baseUserId);

            if ($baseUser instanceof BaseUser) {

                $this->em->remove($baseUser);
                $this->em->flush();

                return new JsonResponse("Delete success.", Response::HTTP_OK);
            } else {
                return new JsonResponse("Can't find user with identifier: $baseUserId.",
                    Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("User identifier is null.", Response::HTTP_BAD_REQUEST);
        }
    }
}