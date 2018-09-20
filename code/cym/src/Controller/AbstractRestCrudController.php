<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 15:53
 */

namespace App\Controller;


use App\Service\FormEntityCrudService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;

abstract class AbstractRestCrudController extends FOSRestController implements ClassResourceInterface
{
    //region SECTION: Fields
    /**
     * @var FormEntityCrudService
     */
    private $formEntityCrudService;
//endregion Fields

//region SECTION: Constructor
    /**
     * UserType constructor.
     *
     * @param FormEntityCrudService  $formEntityCrudService
     */
    public function __construct(FormEntityCrudService $formEntityCrudService)
    {
        $this->formEntityCrudService = $formEntityCrudService;
    }

    /**
     * Return class of Type for operated Entity
     * @return string
     */
    abstract protected function getTypeClass(): string;

    /**
     * Return Class of Entity witch operated
     * @return string
     */
    abstract protected function getEntityClass(): string;

    /**
     * @return View
     */
    public function postAction(): View
    {
        $class = $this->getEntityClass();

        return $this->formEntityCrudService->createEntity($this->getTypeClass(), new $class);
    }


    /**
     * @param string  $id
     *
     * @return View
     */
    public function putAction(string $id): View
    {
        $user = $this->formEntityCrudService->readEntity($this->getEntityClass(), $id);

        return $this->formEntityCrudService->updateEntity($this->getTypeClass(), $user, false);
    }

    /**
     * @param string $id
     *
     * @return View
     */
    public function patchAction(string $id): View
    {
        $user = $this->formEntityCrudService->readEntity($this->getEntityClass(), $id);

        return $this->formEntityCrudService->updateEntity($this->getTypeClass(), $user, true);
    }

    /**
     * @return View
     */
    public function cgetAction(): View
    {
        $users = $this->formEntityCrudService->readAll($this->getEntityClass());

        return $this->view(
            $users
        );
    }

    /**
     * @param string $id
     *
     * @return View
     */
    public function deleteAction(string $id):View
    {
        $entity = $this->formEntityCrudService->readEntity($this->getEntityClass(), $id);

        return $this->formEntityCrudService->deleteEntity($entity);
    }
//endregion Public

//region SECTION: Getters/Setters
    /**
     * @param string $id
     *
     * @return View
     */
    public function getAction(string $id): View
    {
        $user = $this->formEntityCrudService->readEntity($this->getEntityClass(), $id);

        return $this->view(
            $user
        );
    }
}