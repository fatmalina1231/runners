<?php

namespace App\Controller;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Service\UserStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserStatsController extends AbstractController
{
    /**
     * @Route("/user-stats/{id}", name="fluffy_user_stats")
     * @param User $user
     * @param Request $request
     * @param UserStatsService $userStatsService
     * @return Response
     */
    public function userStats(User $user, Request $request, UserStatsService $userStatsService): Response
    {
        $lastConnexion = $user->getLastConnexion();
        $lastVisited = $user->getLastVisited();
        $nbPageViews = $user->getNbPageViews();
        $pageViewsToday = $userStatsService->getPageViewsPerPeriod($user, (new DateTime())->modify('midnight'), (new DateTime())->modify('23:59:59'));
        $pageViewYear = $userStatsService->getPageViewsPerPeriod($user, (new DateTime())->modify('- 1 year'), (new DateTime())->modify('23:59:59'));
        $avgUtilisation = $userStatsService->getAvgUtilisation($user);
        $mostRouteViewed = $userStatsService->getMostRouteViewed($user);
        $statsSession = $userStatsService->getBySession($user);

        return $this->render('user-stats.html.twig', [
            'user' => $user,
            'last_connexion' => $lastConnexion,
            'last_visited' => $lastVisited,
            'nb_page_views' => $nbPageViews,
            'page_views_today' => $pageViewsToday,
            'page_views_year' => $pageViewYear,
            'avg_utilisation' => $avgUtilisation,
            'most_route_viewed' => $mostRouteViewed,
            'statsSession' => $statsSession
        ]);
    }

    /**
     * @Route("/remove-user-stats/{id}", name="fluffy_remove_user_stats")
     * @param User $user
     * @param UserStatsService $userStatsService
     * @return RedirectResponse
     */
    public function removeUserStats(Request $request, User $user, UserStatsService $userStatsService, EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator): RedirectResponse
    {
        $user->setLastConnexion(null);
        $user->setLastVisited(null);
        $user->setNbPageViews(0);
        $userStatsService->removeUserStatsLines($user);

        $entityManager->persist($user);
        $entityManager->flush();

        return new RedirectResponse($adminUrlGenerator->setRoute('fluffy_user_stats', ['id' => $user->getId()])->generateUrl());
    }
}