let lastLat = null;
let lastLng = null;

function sendLocation(lat, lng) {

    fetch('/tracking/location', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': self.csrfToken
        },
        body: JSON.stringify({
            latitud: lat,
            longitud: lng
        })
    });
}

function getLocation() {

    if (!navigator.geolocation) return;

    navigator.geolocation.getCurrentPosition((pos) => {

        let lat = pos.coords.latitude;
        let lng = pos.coords.longitude;

        if (lat !== lastLat || lng !== lastLng) {
            sendLocation(lat, lng);
            lastLat = lat;
            lastLng = lng;
        }

    });
}

setInterval(getLocation, 180000); // 3 minutos
getLocation();
