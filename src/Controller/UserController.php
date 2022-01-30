<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\UserRepository;
// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    // public function listAction()
    public function listAction(UserRepository $userRepository)
    {
        // return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()]);
        // return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository('User::class')->findAll()]);
        // return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
        return $this->render('user/list.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    // public function createAction(Request $request)
    // public function createAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    public function createAction(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            // $user->setPassword($password);
            //  ----RAJOUT NICOLAS code origine-------------
            $Password = $form->get('password')->getData();
            // $user->setPassword($passwordEncoder->encodePassword($user, $Password));
            $user->setPassword($passwordHasher->hashPassword($user, $Password));
            //  ----SUITE RAJOUT NICOLAS-------------
            $roleUser = $form->get('roles')->getData();
            $user->setRoles($roleUser);
            // dd($user);
            //  ----FIN RAJOUT NICOLAS-------------
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    // public function editAction(User $user, Request $request)
    // public function editAction(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $passwordHasher)

    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        // if ($form->isValid()) {
        if ($form->isSubmitted() && $form->isValid()) {
            // $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            // $user->setPassword($password);
            $Password = $form->get('password')->getData();
            // $user->setPassword($passwordEncoder->encodePassword($user, $Password));
            $user->setPassword($passwordHasher->hashPassword($user, $Password));
            //  ----SUITE RAJOUT NICOLAS-------------
            $roleUser = $form->get('roles')->getData();
            $user->setRoles($roleUser);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
