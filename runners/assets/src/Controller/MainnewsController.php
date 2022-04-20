<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainnewsController extends AbstractController
{
    /**
     * @Route("/news", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('main/newsindex.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
