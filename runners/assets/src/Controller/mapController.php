<?php

// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class mapController extends Controller
{
    /**
     * @Route("/home/map")
     */


    public function showIndex()
    {
        return $this->render("map/index.html.twig");
    }

    /**
     * @Route("/get")
     */

    public function getLocation()
    {
        if (isset($_GET["submit"])) {
            $user_address = urlencode($_GET["address"]);
            $usable_address = str_replace(' ', '+', $user_address);

            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $usable_address . '&key=AIzaSyDGDxBUUL2IfxGnkjWFi9KEja4_sbOAWyY');
            $response = json_decode($geocode, true);

            if ($response['status'] == 'OK') {
                $lat = $response['results'][0]['geometry']['location']['lat'];
                $long = $response['results'][0]['geometry']['location']['lng'];

//                $lat_string = (string)$lat;
//                $long_string = (string)$long;

                if ($lat && $long) {
                    return new Response(
                        $this->renderView("map/index.html.twig", array(
                            'lat' => $lat,
                            'long' => $long
                        ))
                    );
                } else {
                    return new Response($response['status']);
                }
            } else {
                echo "Error: {$response['status']}";
                return new Response(
                    "Error 1"
                );
            }
        } else {
            return new Response(
                "Error 2"
            );
        }
    }
}