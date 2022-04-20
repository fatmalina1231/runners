var map, marker, infoWindow;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 65.0121, lng: 25.4651},
        zoom: 6
    });


    if (lat != null) {
        function geocodeAddress(geocoder, resultsMap) {
            geocoder = new google.maps.Geocoder();
            var latitude = Number(document.getElementById('lat').textContent);
            var longitude = Number(document.getElementById('long').textContent);

            var pos = {
                lat: latitude,
                lng: longitude
            };

            geocoder.geocode({'location': pos}, function (results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });

            var icon = document.getElementById('markers').value;
            getMarker(icon);
        }

        geocodeAddress();

    }
}

function getMarker(icon) {
    var lat = Number(document.getElementById('lat').textContent);
    var long = Number(document.getElementById('long').textContent);
    var pos = {
        lat: lat,
        lng: long
    };

    map = new google.maps.Map(document.getElementById('map'), {
        center: pos,
        zoom: 10
    });

    if (icon == 1) {
        marker = new google.maps.Marker({
            map: map,
            position: pos,
        });
        infowindow = new google.maps.InfoWindow({
            content: '<p>Coordinates:' + marker.getPosition() + '</p>'
        });

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
    } else if (icon == 2) {
        var fillColor, strokeColor;


        document.getElementById('colors').disabled = false;
        if (document.getElementById('colors').value === 'Blue') {
            fillColor = 'blue';
            strokeColor = 'black';
        } else if (document.getElementById('colors').value === 'Purple') {
            fillColor = 'purple';
            strokeColor = 'black';
        }

        var marker = new google.maps.Marker({
            map: map,
            position: pos,
            icon: {
                path: 'M -2,0 0,-2 2,0 0,2 z',
                strokeColor: strokeColor,
                fillColor: fillColor,
                fillOpacity: .8,
                scale: 10,
            }
        });
        var infowindow = new google.maps.InfoWindow({
            content: '<p>Coordinates:' + marker.getPosition() + '</p>'
        });

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });

    }
}