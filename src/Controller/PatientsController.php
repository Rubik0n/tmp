<?php

namespace App\Controller;

use App\Entity\Patients;
use App\Entity\Users;
use App\Form\PatientsType;
use App\Repository\PatientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/patients")
 */
class PatientsController extends AbstractController
{
    /**
     * @Route("/", name="patients_index", methods={"GET"})
     */
    public function index(PatientsRepository $patientsRepository): Response
    {
        return $this->render('patients/index.html.twig', [
            'patients' => $patientsRepository->findAll(),
        ]);
    }


    /**
     * @Route("/list", name="patients_list", methods={"GET"})
     */
    public function list(PatientsRepository $patientsRepository): Response
    {
        return $this->render('patients/list.html.twig', [
            'patients' => $patientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="patients_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $patient = new Patients();
        $form = $this->createForm(PatientsType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $login = $form["login"]->getData();
            $entityRepository = $this->getDoctrine()->getRepository(Users::class);
            $user = $entityRepository->findOneBy([
                'login' => $login
            ]);
            if (!$user)
            {
                $patient->setPassword(sha1($form["password"]->getData()));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($patient);
                $entityManager->flush();

                return $this->redirectToRoute('patients_index');
            }


            return $this->redirectToRoute('patients_new');
        }

        return $this->render('patients/new.html.twig', [
            'patient' => $patient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="patients_show", methods={"GET"})
     */
    public function show(Patients $patient): Response
    {
        return $this->render('patients/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="patients_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Patients $patient): Response
    {
        $form = $this->createForm(PatientsType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('patients_index');
        }

        return $this->render('patients/edit.html.twig', [
            'patient' => $patient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="patients_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Patients $patient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($patient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('patients_index');
    }
}
