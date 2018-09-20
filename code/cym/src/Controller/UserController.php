<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use App\Security\Annotation\AuthorizedOnly;

/**
 * Class UserController
 * @Rest\RouteResource("Users", pluralize=false)
 *
 * @package App\Controller
 */
class UserController extends AbstractRestCrudController
{
//region SECTION: Protected
    /**
     * @return string
     */
    protected function getTypeClass(): string
    {
        return ProfileType::class;
    }

    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return User::class;
    }
//endregion Protected

//region SECTION: Public
    public function postAction(): View
    {
        return $this->view(
            [
                'status' => 'Forbidden',
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    public function deleteAction(string $id): View
    {
        return $this->view(
            [
                'status' => 'Forbidden',
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @param string $id
     * @AuthorizedOnly(yourSelf=true)
     * @return View
     */
    public function putAction(string $id):View
    {
        return parent::putAction($id);
    }

    /**
     * @param string $id
     * @AuthorizedOnly(yourSelf=true)
     * @return View
     */
    public function patchAction(string $id):View
    {
        return parent::patchAction($id);
    }

    /**
     * @param string $id
     * @AuthorizedOnly(yourSelf=true)
     * @return View
     */
    public function getAction(string $id):View
    {
        return parent::getAction($id);
    }

    /**
     * @return View
     * @AuthorizedOnly()
     */
    public function cgetAction(): View
    {
        return parent::cgetAction();
    }


//endregion Public


}
