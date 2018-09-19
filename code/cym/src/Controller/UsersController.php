<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 0:50
 */

namespace App\Controller;

use App\Entity\User\User;
use FOS\RestBundle\Controller\Annotations as FosRest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends FOSRestController
{
    /**
     * Lists all Articles.
     * @FOSRest\Get("/users")
     *
     * @return View
     */
    public function getUserAction(): View
    {
        $repository = $this->getDoctrine()->getRepository(User::class);


        $users = $repository->findAll();

        return View::create($users, Response::HTTP_OK , []);
    }
}