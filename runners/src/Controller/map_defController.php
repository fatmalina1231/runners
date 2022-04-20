<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Controller\MapController;

class map_defController extends Controller
{
    /**
    * @Route("/map", name="index")
    */
    public function index()
    {
        return $this->render('map/index.html.twig');
    }

    /**
    * @Route("/geocode")
    */
    public function geocode(Request $request)
    {
        $address = $request->query->get('address');
        if (!$address) 
            return $this->render('map/geocode_form.html.twig');
        else {
            $geocode = MapController::geocode($address);
            return $this->render('map/geocode.html.twig', 
                array("lat" => $geocode->lat, "lng" => $geocode->lng));
        }
    }

    /**
    * @Route("/place_marker")
    */
    public function place_marker(Request $request,  SessionInterface $session)
    {
        $type = $request->query->get('type');
        $iconOption = $session->get('iconOption');
        if (!$type) 
            return $this->render('map/place_marker_form.html.twig');
        else if ($type == "address") {
            $address = $request->query->get('address');
            $geocode = MapController::geocode($address);
            return $this->render('map/place_marker.html.twig', 
                array("lat"        => $geocode->lat, 
                      "lng"        => $geocode->lng,
                      "API_KEY"    => "AIzaSyA2utCgFATmbG4fvzdHpFXvXErUYSJodwc",
                      "iconOption" => $iconOption));
        } else if ($type == "geocode")
            return $this->render('map/place_marker.html.twig', 
                array("lat"        => $request->query->get('lat'),
                      "lng"        => $request->query->get('lng'),
                      "API_KEY"    => "AIzaSyA2utCgFATmbG4fvzdHpFXvXErUYSJodwc",
                      "iconOption" => $iconOption));
    }

    /**
    * @Route("/custom_marker")
    */
    public function custom_marker(Request $request, SessionInterface $session) {
        $icon = $request->request->get('icon');
        $color = $request->request->get('color');
        if ($icon === null) 
            return $this->render('map/custom_marker.html.twig');
        else {
            $iconOption = $icon ? "img/{$color}/{$icon}"
                : "http://maps.google.com/mapfiles/ms/icons/{$color}-dot.png";
            $session->set('iconOption', $iconOption);
            return $this->redirectToRoute("index");
        }
    }

    /**
    * @Route("/distance")
    */
    public function distance(Request $request, SessionInterface $session) {
        $iconOption = $session->get('iconOption');
        $lat1 = $request->query->get('lat1');
        $lng1 = $request->query->get('lng1');
        $lat2 = $request->query->get('lat2');
        $lng2 = $request->query->get('lng2');
        if ($lat1 === null || $lat2 === null) {
            return $this->render('map/distance.html.twig', 
                array("API_KEY"    => "AIzaSyA2utCgFATmbG4fvzdHpFXvXErUYSJodwc",
                      "iconOption" => $iconOption));
        } else {
            return $this->render('map/distance_result.html.twig', 
                array("result" => MapController::vincentyGreatCircleDistance(
                    $lat1, $lng1, $lat2, $lng2)));
        }
    }

    /**
    * @Route("/directions")
    */
    public function directions(Request $request, SessionInterface $session) {
        $iconOption = $session->get('iconOption');
        $lat1 = $request->query->get('lat1');
        $lng1 = $request->query->get('lng1');
        $lat2 = $request->query->get('lat2');
        $lng2 = $request->query->get('lng2');
        if ($lat1 === null || $lat2 === null) {
            return $this->render('map/directions.html.twig', 
                array("API_KEY"    => "AIzaSyA2utCgFATmbG4fvzdHpFXvXErUYSJodwc",
                      "iconOption" => $iconOption));
        } else {
            return $this->render('map/directions_result.html.twig', 
                array("directions" => MapController::getDirections(
                    $lat1, $lng1, $lat2, $lng2)));
        }
    }

}