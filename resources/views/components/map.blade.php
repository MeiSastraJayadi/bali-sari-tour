<div>
  <div id="map_instructions" 
    class="w-full md:w-3/4 mb-3 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
    🗺️ <b>Petunjuk:</b> Klik peta untuk memilih titik awal dan titik tujuan.  
    Anda juga bisa mencari nama jalan di kolom pencarian di atas.
  </div>

  <div id="map" class="w-full h-90 rounded-lg mb-4"></div>

  <div class="grid grid-cols-2 gap-4 w-full md:w-96">
    <div>
      <input id="start_lat" name="start_lat" type="hidden" readonly>
      <input id="start_lng" name="start_lng" type="hidden" readonly>
    </div>

    <div>
      <input id="end_lat" name="end_lat" type="hidden" readonly>
      <input id="end_lng" name="end_lng" type="hidden" readonly>
      <input type="hidden" id="jarak" name="jarak">
    </div>
  </div>

  <p id="distance" class="mt-3 text-gray-700 font-medium"></p>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const map = L.map('map').setView([-8.6912, 115.1650], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  let startMarker = null;
  let endMarker = null;
  let routeLine = null;
  let clickCount = 0;

  function calculateDistance() {
    if (startMarker && endMarker) {
      const distance = map.distance(startMarker.getLatLng(), endMarker.getLatLng()) / 1000;
      document.getElementById('distance').textContent = `Jarak: ${distance.toFixed(2)} km`;
      document.getElementById('jarak').value = distance.toFixed(2);
    }
  }

  // 🔹 Fungsi untuk mendapatkan nama lokasi atau fallback ke koordinat
  async function getLocationName(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;
    try {
      const res = await fetch(url);
      const data = await res.json();
      if (data && data.display_name) {
        return data.display_name;
      } else {
        return `Koordinat: (${lat}, ${lng})`; // 🔹 fallback jika tidak ditemukan
      }
    } catch (error) {
      console.error('Gagal mengambil nama lokasi:', error);
      return `Koordinat: (${lat}, ${lng})`; // 🔹 fallback jika error
    }
  }

  map.on('click', async function (e) {
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);
    const name = await getLocationName(lat, lng);

    if (clickCount === 0) {
      if (startMarker) map.removeLayer(startMarker);
      startMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();

      document.getElementById('start_lat').value = lat;
      document.getElementById('start_lng').value = lng;
      document.getElementById('alamatInput').value = name;

      startMarker.on('dragend', async function (event) {
        const newLat = event.target.getLatLng().lat.toFixed(6);
        const newLng = event.target.getLatLng().lng.toFixed(6);
        const newName = await getLocationName(newLat, newLng);
        document.getElementById('start_lat').value = newLat;
        document.getElementById('start_lng').value = newLng;
        document.getElementById('alamatInput').value = newName;
        startMarker.bindPopup(newName);
        if (routeLine && endMarker) {
          routeLine.setLatLngs([startMarker.getLatLng(), endMarker.getLatLng()]);
          calculateDistance();
        }
      });

      clickCount = 1;

    } else if (clickCount === 1) {
      if (endMarker) map.removeLayer(endMarker);
      endMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();

      document.getElementById('end_lat').value = lat;
      document.getElementById('end_lng').value = lng;
      document.getElementById('destinasiInput').value = name;

      if (routeLine) map.removeLayer(routeLine);
      routeLine = L.polyline([
        [startMarker.getLatLng().lat, startMarker.getLatLng().lng],
        [lat, lng]
      ], { color: 'blue', weight: 3 }).addTo(map);

      calculateDistance();

      endMarker.on('dragend', async function (event) {
        const newLat = event.target.getLatLng().lat.toFixed(6);
        const newLng = event.target.getLatLng().lng.toFixed(6);
        const newName = await getLocationName(newLat, newLng);
        document.getElementById('end_lat').value = newLat;
        document.getElementById('end_lng').value = newLng;
        document.getElementById('destinasiInput').value = newName;
        endMarker.bindPopup(newName);
        routeLine.setLatLngs([startMarker.getLatLng(), endMarker.getLatLng()]);
        calculateDistance();
      });

      clickCount = 2;

    } else {
      map.removeLayer(startMarker);
      map.removeLayer(endMarker);
      map.removeLayer(routeLine);
      document.getElementById('start_lat').value = '';
      document.getElementById('start_lng').value = '';
      document.getElementById('alamatInput').value = '';
      document.getElementById('end_lat').value = '';
      document.getElementById('end_lng').value = '';
      document.getElementById('destinasiInput').value = '';
      document.getElementById('distance').textContent = '';
      clickCount = 0;
    }
  });

  // 🔹 Pencarian nama lokasi
  const geocoder = L.Control.geocoder({
    defaultMarkGeocode: false
  })
  .on('markgeocode', async function(e) {
    const { lat, lng } = e.geocode.center;
    const name = e.geocode.name || await getLocationName(lat, lng);

    if (clickCount === 0) {
      if (startMarker) map.removeLayer(startMarker);
      startMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();
      document.getElementById('start_lat').value = lat.toFixed(6);
      document.getElementById('start_lng').value = lng.toFixed(6);
      document.getElementById('alamatInput').value = name;
      clickCount = 1;
    } else if (clickCount === 1) {
      if (endMarker) map.removeLayer(endMarker);
      endMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();
      document.getElementById('end_lat').value = lat.toFixed(6);
      document.getElementById('end_lng').value = lng.toFixed(6);
      document.getElementById('destinasiInput').value = name;

      if (routeLine) map.removeLayer(routeLine);
      routeLine = L.polyline([
        [startMarker.getLatLng().lat, startMarker.getLatLng().lng],
        [lat, lng]
      ], { color: 'blue', weight: 3 }).addTo(map);

      calculateDistance();
      clickCount = 2;
    } else {
      map.removeLayer(startMarker);
      map.removeLayer(endMarker);
      map.removeLayer(routeLine);
      document.getElementById('start_lat').value = '';
      document.getElementById('start_lng').value = '';
      document.getElementById('alamatInput').value = '';
      document.getElementById('end_lat').value = '';
      document.getElementById('end_lng').value = '';
      document.getElementById('destinasiInput').value = '';
      document.getElementById('distance').textContent = '';
      clickCount = 0;
    }

    map.setView([lat, lng], 15);
  })
  .addTo(map);
});
</script>
