document.addEventListener("DOMContentLoaded", function (event) {
    var select = document.getElementsByTagName("select")[0];
    select.onchange = function () {
        if (select.value == "address")
            document.getElementById("input").innerHTML =
                'Address <input type="text" name="address">';
        else if (select.value == "geocode") {
            document.getElementById("input").innerHTML =
                'Latitude <input type="text" name="lat"> Longitude <input type="text" name="lng">';
        }
    }
});