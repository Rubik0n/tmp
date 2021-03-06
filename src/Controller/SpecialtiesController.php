<?php

namespace App\Controller;

use App\Entity\Specialties;
use App\Form\Specialties1Type;
use App\Repository\SpecialtiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/specialties")
 */
class SpecialtiesController extends AbstractController
{
    /**
     * @Route("/", name="specialties_index", methods={"GET"})
     */
    public function index(SpecialtiesRepository $specialtiesRepository): Response
    {
        return $this->render('specialties/index.html.twig', [
            'specialties' => $specialtiesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="specialties_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $specialty = new Specialties();
        $form = $this->createForm(Specialties1Type::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($specialty);
            $entityManager->flush();

            return $this->redirectToRoute('specialties_index');
        }

        return $this->render('specialties/new.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="specialties_show", methods={"GET"})
     */
    public function show(Specialties $specialty): Response
    {
        return $this->render('specialties/show.html.twig', [
            'specialty' => $specialty,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="specialties_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Specialties $specialty): Response
    {
        $form = $this->createForm(Specialties1Type::class, $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('specialties_index');
        }

        return $this->render('specialties/edit.html.twig', [
            'specialty' => $specialty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="specialties_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Specialties $specialty): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialty->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($specialty);
            $entityManager->flush();
        }

        return $this->redirectToRoute('specialties_index');
    }
}
