<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request)
    {
//        if (!$_REQUEST){
//            $error = false;
//        }
//        else{
//            $error = $_REQUEST['error'];
//        }
//
//        dump($error);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController'
//            'error' => $error
        ]);
    }
}
