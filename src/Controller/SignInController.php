<?php

namespace App\Controller;


use App\Entity\Admin;
use App\Entity\Doctors;
use App\Entity\Patients;
use App\Entity\Users;
use App\Form\SignInType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping as ORM;

class SignInController extends AbstractController
{
    /**
     * @Route("/sign/in", name="sign_in", methods={"GET","POST"})
     */
    public function index(Request $request)
    {

        $form = $this->createForm(SignInType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $login = $form["login"]->getData();
            $password = $form["password"]->getData();


            $entityRepository = $this->getDoctrine()->getRepository(Users::class);
            $user = $entityRepository->findOneBy([
                'login' => $login,
                'password' => sha1($password)
            ]);


            if ($user instanceof Admin){
                $response = new RedirectResponse($this->generateUrl('admin'));

                $response->headers->setCookie(new Cookie('login', $login, time() + 60));
                $response->headers->setCookie(new Cookie('password', sha1($password), time() + 60));

                return $response;
            }

            if ($user instanceof Doctors){

//                $response = new RedirectResponse($this->generateUrl('current_doctor'));
//                $response->headers->setCookie(new Cookie('login', $login, time() + 60));
//                $response->headers->setCookie(new Cookie('password', sha1($password), time() + 60));
//                return $response;
//

                return $this->redirectToRoute('current_doctor',['id' => $user->getId()]);
            }

            if ($user instanceof Patients){
//                $response = new RedirectResponse($this->generateUrl('patients_list'));
//                $response->headers->setCookie(new Cookie('login', $login, time() + 60));
//                $response->headers->setCookie(new Cookie('password', sha1($password), time() + 60));
//                return $response;
                return $this->redirectToRoute('current_patient',['id' => $user->getId()]);
            }

        }

        return $this->render('sign_in/index.html.twig',
            array( 'form' => $form->createView() ));
    }

    /**
     * @Route("/sign_out", name="sign_out")
     */
    public function signOut(Request $request){

        $response = new RedirectResponse($this->generateUrl('main'));
        $response->headers->clearCookie('login');
        $response->headers->clearCookie('password');

        return $response;
    }
}
