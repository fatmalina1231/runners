<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartsjsController extends AbstractController
{
    /**
     * @Route("/chartsjs", name="app_chartsjs")
     */
    public function index(UserRepository $userRepository,ChartBuilderInterface $chartBuilder): Response
    {

        $repo = $userRepository->findAll();
        //créations des données du chart
        $labels = [];
        $datasets = [];

        foreach($repo as $data){
            $labels[] = $data->getCreatedAt()->format('d-m-Y');
            $datasets[] =$data->getRoles();
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Mes premières données',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $datasets,

                ]
            ],
        ]);


       
        /*$chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);*/

        return $this->render('chartsjs/index.html.twig', [
            'controller_name' => 'ChartsjsController',
            'users'=>$repo,
            'chart' => $chart,
        ]);
    }
}
