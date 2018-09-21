<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 17:05
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\LoginType;
use App\Security\Interfaces\AuthenticationServiceInterface;
use App\Security\Interfaces\JWTTokenProviderInterface;
use App\Security\Interfaces\JWTTokenValidatorInterface;
use App\Security\SecurityManager;
use App\Security\Token\UserToken;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var JWTTokenValidatorInterface
     */
    private $tokenValidator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var SecurityManager
     */
    private $securityManager;

//endregion Fields

//region SECTION: Constructor
    /**
     * AuthenticationController constructor.
     *
     * @param AuthenticationServiceInterface $authenticationService
     * @param JWTTokenProviderInterface      $JWTTokenProvider
     * @param JWTTokenValidatorInterface     $tokenValidator
     * @param EntityManagerInterface         $entityManager
     * @param SecurityManager                $securityManager
     */
    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        JWTTokenProviderInterface $JWTTokenProvider,
        JWTTokenValidatorInterface $tokenValidator,
        EntityManagerInterface $entityManager,
        SecurityManager $securityManager
    ) {
        $this->authenticationService = $authenticationService;
        $this->JWTTokenProvider      = $JWTTokenProvider;
        $this->tokenValidator = $tokenValidator;
        $this->entityManager = $entityManager;

        $this->securityManager = $securityManager;
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
    public function authenticateAction(Request $request): JsonResponse
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

            $this->saveRefreshToken($jwtTokens);
            return new JsonResponse($jwtTokens, Response::HTTP_OK);
        }

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);


    }

    /**
     *
     * @Rest\Route("/refresh-token", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function refreshAction(): JsonResponse
    {

        if($this->tokenValidator->validateRefreshToken()) {
            $payload = $this->securityManager->getPayload();
            $jwtTokens = null;
            if(isset($payload->user_id)) {
                $user = $this->entityManager->getRepository(User::class)->find($payload->user_id);
                if($user) {
                    $jwtTokens = $this->JWTTokenProvider->getTokens($user);

                    $this->saveRefreshToken($jwtTokens);
                }
            }

            if(null !== $jwtTokens) {
                return new JsonResponse($jwtTokens, Response::HTTP_OK);
            }

        }

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }

//endregion Public
    private function saveRefreshToken(array $jwtTokens): void
    {
        if(isset($jwtTokens[JWTTokenProviderInterface::REFRESH_TOKEN])) {
            /** @var User $user */
            $user = $this->securityManager->getUser();
            if(null !== $user) {
                $user->setRefreshToken($jwtTokens[JWTTokenProviderInterface::REFRESH_TOKEN]);
                $this->entityManager->flush();
            }
        }
    }
}