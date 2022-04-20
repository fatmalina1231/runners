// code from Google Maps API docs
// https://developers.google.com/maps/documentation/javascript/adding-a-google-map
function initMap() {
    var mapDiv = document.getElementById('map');
    var position = { lat: Number(mapDiv.dataset.lat), lng: Number(mapDiv.dataset.lng) };
    var map = new google.maps.Map(mapDiv, {
        zoom: 8,    
        center: position
    });
    var marker = new google.maps.Marker({
        position: position,
        map: map,
        icon: mapDiv.dataset.iconOption
    });
}