<?php

namespace App\Controller;

use App\Entity\Dev;
use App\Form\DevType;
use App\Repository\DevRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dev")
 */
class DevController extends AbstractController
{
    /**
     * @Route("/", name="app_dev_index", methods={"GET"})
     */
    public function index(DevRepository $devRepository): Response
    {
        return $this->render('dev/index.html.twig', [
            'devs' => $devRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_dev_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DevRepository $devRepository): Response
    {
        $dev = new Dev();
        $form = $this->createForm(DevType::class, $dev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devRepository->add($dev);
            return $this->redirectToRoute('app_dev_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dev/new.html.twig', [
            'dev' => $dev,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_dev_show", methods={"GET"})
     */
    public function show(Dev $dev): Response
    {
        return $this->render('dev/show.html.twig', [
            'dev' => $dev,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_dev_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Dev $dev, DevRepository $devRepository): Response
    {
        $form = $this->createForm(DevType::class, $dev);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $devRepository->add($dev);
            return $this->redirectToRoute('app_dev_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dev/edit.html.twig', [
            'dev' => $dev,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_dev_delete", methods={"POST"})
     */
    public function delete(Request $request, Dev $dev, DevRepository $devRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dev->getId(), $request->request->get('_token'))) {
            $devRepository->remove($dev);
        }

        return $this->redirectToRoute('app_dev_index', [], Response::HTTP_SEE_OTHER);
    }
}
