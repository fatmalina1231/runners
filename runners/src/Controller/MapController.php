<?php
namespace App\Controller;

class MapController
{
    public function geocode($address)
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "cafile"           => "cacert.pem",
                "verify_peer"      => true,
                "verify_peer_name" => true,
            ),
        );
        $address = str_replace(" ", "+", $address);
        $geocode = file_get_contents(
            "https://maps.googleapis.com/maps/api/geocode/"
                ."json?address={$address}&key=AIzaSyBmYmVyLcaa0vx15Vm1N3HFk355LTZ7Mxo", 
                false, 
                stream_context_create($arrContextOptions));
        $geocode = json_decode($geocode);
        return $geocode->results[0]->geometry->location;
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function vincentyGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    public function getDirections($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        $arrContextOptions=array(
            "ssl"=>array(
                "cafile"           => "cacert.pem",
                "verify_peer"      => true,
                "verify_peer_name" => true,
            ),
        );
        $directions = file_get_contents(
            "https://maps.googleapis.com/maps/api/directions/"
                ."json?origin={$latitudeFrom},{$longitudeFrom}"
                ."&destination={$latitudeTo},{$longitudeTo}"
                ."&key=AIzaSyAlS9XOm1eCsv9EfKzoS0PTEpp6UJKrC10",
            false, 
            stream_context_create($arrContextOptions));
        $directions = json_decode($directions);
        return array("steps"    => $directions->routes[0]->legs[0]->steps, 
                     "distance" => $directions->routes[0]->legs[0]->distance->text);

    }
}