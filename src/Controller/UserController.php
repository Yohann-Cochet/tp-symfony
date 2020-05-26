<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        // Retourne tous les objets de la classe USER
        $users = $this -> getDoctrine() -> getRepository(User::class) -> findAll();

        dump($users);

        return $this->render('user/index.html.twig',
            [
                'users' => $users
            ]
        );
    }

    /**
     * @Route("/add-user", name="add_user")
     */
    public function addUser(Request $request)
    {
        $form = $this -> createForm(UserType::class, new User());
        $form -> handleRequest($request);

        if ($form -> isSubmitted() and $form -> isValid())
        {
            $user = $form -> getData();
            $entityManager = $this -> getDoctrine() -> getManager();
            $entityManager -> persist($user);
            $entityManager -> flush();

            return $this -> redirectToRoute('default');
        }
        else
        {
            return $this -> render('user/addUser.html.twig',
            [
                'form' => $form -> createView(),
            ]);
        }
    }    

    /**
     * @Route("/delete/{user}", name="delete_user")
     */
    public function deleteUser(User $user)
    {
        $entityManager = $this -> getDoctrine() -> getManager();
        $entityManager -> remove($user);
        $entityManager -> flush();

        return $this -> redirectToRoute('default');
        
    }
}
