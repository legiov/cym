<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 17:05
 */

namespace App\Controller;


use App\Form\LoginType;
use App\Security\Interfaces\AuthenticationServiceInterface;
use App\Security\Interfaces\JWTTokenProviderInterface;
use App\Security\Token\UserToken;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class AuthenticationController
 * @Route("/auth")
 *
 * @package App\Controller
 */
class AuthenticationController extends Controller
{
//region SECTION: Fields
    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;
    /**
     * @var JWTTokenProviderInterface
     */
    private $JWTTokenProvider;

//endregion Fields

//region SECTION: Constructor
    /**
     * AuthenticationController constructor.
     *
     * @param AuthenticationServiceInterface $authenticationService
     * @param JWTTokenProviderInterface      $JWTTokenProvider
     */
    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        JWTTokenProviderInterface $JWTTokenProvider
    ) {
        $this->authenticationService = $authenticationService;
        $this->JWTTokenProvider      = $JWTTokenProvider;
    }
//endregion Constructor


//region SECTION: Public
    /**
     *
     * @param Request $request
     * @Rest\Route("/login", methods={"POST"})
     *
     * @return JsonResponse
     */
    public function authenticateAction(Request $request)
    {
        $form = $this->createForm(LoginType::class);

        $form->submit(
            $request->request->all()
        );

        /** @var UserToken $token */
        $token = $form->getData();
        try {
            $this->authenticationService->authenticate($token);
        } catch (AuthenticationException $e) {
            return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
        }
        if ($token->isAuthenticated()) {
            $jwtTokens = $this->JWTTokenProvider->getTokens($token->getUser());


            return new JsonResponse($jwtTokens, Response::HTTP_OK);
        }

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);


    }


//endregion Public
}