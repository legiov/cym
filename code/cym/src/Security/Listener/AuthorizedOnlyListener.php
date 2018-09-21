<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 20:44
 */

namespace App\Security\Listener;


use App\Security\Annotation\AuthorizedOnly;
use App\Security\JWTTokenValidator;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthorizedOnlyListener implements EventSubscriberInterface
{
//region SECTION: Fields
    /**
     * @var Reader
     */
    private $annotationReader;
    /**
     * @var JWTTokenValidator
     */
    private $jwtTokenValidator;
    /**
     * @var RequestStack
     */
    private $requestStack;
//endregion Fields


//region SECTION: Constructor
    /**
     * AuthorizedOnlyListener constructor.
     *
     * @param Reader            $annotationReader
     * @param JWTTokenValidator $JWTTokenValidator
     * @param RequestStack      $requestStack
     */
    public function __construct(Reader $annotationReader, JWTTokenValidator $JWTTokenValidator, RequestStack $requestStack)
    {
        $this->annotationReader = $annotationReader;

        $this->jwtTokenValidator = $JWTTokenValidator;


        $this->requestStack = $requestStack;
    }
//endregion Constructor

//region SECTION: Public
    public function onKernelController(FilterControllerEvent $event)
    {
        if(!$event->isMasterRequest()) {
            return;
        }

        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }
        $controllerObject = $controller[0];
        $action = $controller[1];

        $refController = new \ReflectionObject($controllerObject);

        if ($refController->hasMethod($action)) {
            $action = $refController->getMethod($action);

            $propertyAnnotation = $this->annotationReader->getMethodAnnotation($action, AuthorizedOnly::class);
            if (null !== $propertyAnnotation) {
                $argumentsRelolver = new ArgumentResolver();
                $request           = $this->requestStack->getCurrentRequest();
                $arguments         = [];
                if (null !== $request) {
                    $arguments = $argumentsRelolver->getArguments($request, $controller);
                }

                if (false === $this->jwtTokenValidator->validateToken($propertyAnnotation->yourSelf, $arguments[0] ?? null)) {
                    throw new HttpException(Response::HTTP_FORBIDDEN, 'Access Denied');
                }
            }
        }

    }
//endregion Public

//region SECTION: Getters/Setters
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
//endregion Getters/Setters
}