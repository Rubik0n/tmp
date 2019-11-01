<?php

namespace App\Controller;



use App\Entity\Doctors;
use App\Entity\Users;
use App\Form\DoctorsType;
use App\Repository\DoctorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctors")
 */
class DoctorsController extends AbstractController
{
    /**
     * @Route("/", name="doctors_index", methods={"GET"})
     */
    public function index(DoctorsRepository $doctorsRepository): Response
    {

        return $this->render('doctors/index.html.twig', [
            'doctors' => $doctorsRepository->findAll()
        ]);
    }



    /**
     * @Route("/new", name="doctors_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $doctor = new Doctors();
        $form = $this->createForm(DoctorsType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $login = $form["login"]->getData();
            $entityRepository = $this->getDoctrine()->getRepository(Users::class);
            $user = $entityRepository->findOneBy([
                'login' => $login
            ]);

            if (!$user){
                $doctor->setPassword(sha1($form["password"]->getData()));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($doctor);
                $entityManager->flush();

                return $this->redirectToRoute('doctors_index');
            }

            $this->addFlash(
                'error',
                'This login already exists!'
            );
            return $this->redirectToRoute('doctors_new');
        }

        return $this->render('doctors/new.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="doctors_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Doctors $doctor): Response
    {
        return $this->render('doctors/show.html.twig', [
            'doctor' => $doctor,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="doctors_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Doctors $doctor): Response
    {
        $form = $this->createForm(DoctorsType::class, $doctor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('doctors_index');
        }

        return $this->render('doctors/edit.html.twig', [
            'doctor' => $doctor,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="doctors_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Doctors $doctor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$doctor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($doctor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('doctors_index');
    }

    /**
     * @Route("/list", name="doctors_list", methods={"GET"})
     */
    public function list(DoctorsRepository $doctorsRepository): Response
    {
        #$entityManager = $this->getDoctrine()->getManager();
        #$entityDoctorsRepository = $entityManager->getRepository(Doctors::class);

        return $this->render('doctors/list.html.twig', [
            #'doctors' => $entityDoctorsRepository->findAll()
            'doctors' => $doctorsRepository->findAll()
        ]);
    }

    /**
     * @Route("/doctor/curr", name="current_doctor", requirements={"id"="\d+"})
     */
    public function currentDoctor(Request $request):Response
    {
        $id = $_REQUEST['id'];

        $entityManager = $this->getDoctrine()->getManager();
        $entityRepository = $entityManager->getRepository(Doctors::class);

        $doctor = $entityRepository->findOneBy([
            'id'=> $id
        ]);

        return $this->render('doctors/current_doctor.html.twig', [
            'doctor' => $doctor
        ]);
    }

}
