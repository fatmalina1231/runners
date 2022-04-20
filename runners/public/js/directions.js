
function initMap() {
    var mapDiv = document.getElementById('map');
    var map = new google.maps.Map(mapDiv, {
        zoom: 8,
        center: { lat: 50, lng: 40 }
    });

    map.addListener('click', function (e) {
        var lat1 = document.getElementById("lat1");
        var lng1 = document.getElementById("lng1");
        var lat2 = document.getElementById("lat2");
        var lng2 = document.getElementById("lng2");
        if (lat1.value == "")
        {
            var marker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                icon: mapDiv.dataset.iconOption
            });
            lat1.value = e.latLng.lat();
            lng1.value = e.latLng.lng();
        } else if (lat2.value == "") {
            var marker = new google.maps.Marker({
                position: e.latLng,
                map: map,
                icon: mapDiv.dataset.iconOption
            });
            lat2.value = e.latLng.lat();
            lng2.value = e.latLng.lng();
        }
    });
}