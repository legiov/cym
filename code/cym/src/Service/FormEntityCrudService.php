<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 15:06
 */

namespace App\Service;


use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormEntityCrudService
{
//region SECTION: Fields
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var null|Request
     */
    private $request;

//endregion Fields

//region SECTION: Constructor
    /**
     * EntityCrud constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface   $formFactory
     * @param RequestStack           $requestStack
     */
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->formFactory   = $formFactory;
        $this->request       = $requestStack->getCurrentRequest();
    }
//endregion Constructor

//region SECTION: Public
    /**
     *
     * @param string          $typeClass
     * @param EntityInterface $entity
     *
     * @return View
     */
    public function createEntity(string $typeClass, EntityInterface $entity): View
    {
        return $this->saveEntity($typeClass, $entity, false);
    }

    public function updateEntity(string $typeClass, EntityInterface $entity, bool $isPatch = false): View
    {
        return $this->saveEntity($typeClass, $entity, $isPatch);
    }

    /**
     * @param string $entityClass
     * @param string $id
     *
     * @return EntityInterface
     * @throws NotFoundHttpException
     */
    public function readEntity($entityClass, string $id): EntityInterface
    {
        return $this->findEntity($entityClass, $id);
    }

    /**
     *
     * @param string          $typeClass
     * @param EntityInterface $entity
     * @param bool            $isPatch если нужно пропатчить сущность
     *
     * @return View
     */
    public function saveEntity(string $typeClass, EntityInterface $entity, bool $isPatch): View
    {
        $isNew = null === $entity->getId();
        $form  = $this->formFactory->create($typeClass, $entity);

        $form->submit($this->request->request->all(), $isPatch ? false : true);

        if (false === $form->isValid()) {
            return View::create($form);
        }

        if ($isNew) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();

        $httpCode = $isNew ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT;

        return View::create(
            $isNew ? $entity : null,
            $httpCode
        );
    }

    /**
     * @param string $class
     *
     * @return EntityInterface[]
     */
    public function readAll(string $class): array
    {
        return $this->entityManager->getRepository($class)->findAll();
    }

    /**
     * @param EntityInterface $entity
     *
     * @return View
     */
    public function deleteEntity(EntityInterface $entity):View
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
//endregion Public

//region SECTION: Private
    /**
     * @param string $entityClass
     * @param string $id
     *
     * @return EntityInterface
     * @throws NotFoundHttpException
     */
    private function findEntity(string $entityClass, string $id): EntityInterface
    {
        /** @var EntityInterface $entity */
        $entity = $this->entityManager->getRepository($entityClass)->find($id);

        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }
//endregion Private
}