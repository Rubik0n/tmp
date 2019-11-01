<?php

namespace App\Controller;

use App\Entity\Coupons;
use App\Form\CouponsType;
use App\Repository\CouponsRepository;

use App\Repository\PatientsRepository;
use App\Repository\DoctorsRepository;
use App\Controller\DoctorsController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/coupons")
 */
class CouponsController extends AbstractController
{
    /**
     * @Route("/", name="coupons_index", methods={"GET"})
     */
    public function index(CouponsRepository $couponsRepository): Response
    {
        return $this->render('coupons/index.html.twig', [
            'coupons' => $couponsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="coupons_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $coupon = new Coupons();
        $form = $this->createForm(CouponsType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coupon);
            $entityManager->flush();

            return $this->redirectToRoute('coupons_index');
        }

        return $this->render('coupons/new.html.twig', [
            'coupon' => $coupon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="coupons_list", methods={"GET"})
     */
    public function list(CouponsRepository $couponsRepository,
                         DoctorsRepository $doctorsRepository,
                         PatientsRepository $patientsRepository): Response
    {
        #$entityManager = $this->getDoctrine()->getManager();
        #$entityCouponsRepository = $entityManager->getRepository(Coupons::class);
        #$entityDoctorsRepository = $entityManager->getRepository(Doctors::class);
        #$entityPatientsRepository = $entityManager->getRepository(Patients::class);

        return $this->render('coupons/list.html.twig', [
            'coupons' => $couponsRepository->findAll(),
            'doctors' => $doctorsRepository->findAll(),
            'patients' => $patientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="coupons_show", methods={"GET"})
     */
    public function show(Coupons $coupon): Response
    {
        return $this->render('coupons/show.html.twig', [
            'coupon' => $coupon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="coupons_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Coupons $coupon): Response
    {
        $form = $this->createForm(CouponsType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('coupons_index');
        }

        return $this->render('coupons/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coupons_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Coupons $coupon): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coupon->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($coupon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('coupons_index');
    }
}
