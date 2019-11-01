<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Admin;
use App\Entity\Doctors;
use App\Entity\Patients;
use Symfony\Component\HttpFoundation\Cookie;


/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin")
     */
    public function index(Request $request)
    {
        $login = $request->cookies->get('login');
        $password = $request->cookies->get('password');

        if (!$login){

            return $this->redirectToRoute('sign_in');
        }

        $entityRepository = $this->getDoctrine()->getRepository(Users::class);
        $user = $entityRepository->findOneBy([
            'login' => $login
        ]);

        if (!$user || !$password == $user->getPassword())
            return $this->redirectToRoute('sign_in');


        //$response = new RedirectResponse($this->generateUrl('doctors_index'));
       //dump($request);

        $entities = $this->getDoctrine()->getRepository(Users::class)->findAll();



        return $this->render('admin/index.html.twig', [
            'entities' => $entities
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request){

        //dump($_REQUEST['login']);die;


        $login = $_POST['login'];
        $password = $_POST['password'];


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

            return new RedirectResponse($this->generateUrl('doctors_index'));
//            return $this->redirectToRoute('current_doctor',[
//                'id' => $user->getId(),
//                'who' => 'doctor'
//            ]);
        }

        if ($user instanceof Patients){
            return $this->redirectToRoute('current_patient',['id' => $user->getId()]);
        }

        return $this->redirectToRoute('main');
    }
}
