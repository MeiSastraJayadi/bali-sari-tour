<div>
  <div id="map_instructions" 
    class="w-full md:w-3/4 mb-3 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
    🗺️ <b>Petunjuk:</b> Klik peta untuk memilih titik awal dan titik tujuan.  
    Anda juga bisa mencari nama jalan di kolom pencarian di atas.
  </div>
  <div class="mb-4">
    <button id="resetMap" class="px-4 py-2 bg-[#333] text-white rounded hover:opacity-90 hover:cursor-pointer">Reset Semua Titik</button>
  </div>  

  <div id="map" class="w-full h-70 rounded-lg mb-4"></div>

  <div class="grid grid-cols-2 gap-4 w-full md:w-96">
    <div>
      <input id="start_lat" name="start_lat" type="hidden" readonly>
      <input id="start_lng" name="start_lng" type="hidden" readonly>
      <input id="alamatInput" name="alamatInput" type="hidden" readonly>
    </div>

    <div>
      <input id="end_lat" name="end_lat" type="hidden" readonly>
      <input id="end_lng" name="end_lng" type="hidden" readonly>
      <input type="hidden" id="jarak" name="jarak">
      <input type="hidden" id="destinasiInput" name="destinasiInput">
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
  let destMarkers = [];
  let routeLine = null;
  let isStartSet = false;

  async function getLocationName(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`;
    try {
      const res = await fetch(url);
      const data = await res.json();
      return (data && data.display_name) ? data.display_name : `Koordinat: (${lat}, ${lng})`;
    } catch (error) {
      console.error('Gagal mengambil nama lokasi:', error);
      return `Koordinat: (${lat}, ${lng})`;
    }
  }

  function distance(lat1, lng1, lat2, lng2) {
    return map.distance([lat1, lng1], [lat2, lng2]);
  }

  function getShortestRoute() {
    if (!startMarker || destMarkers.length === 0) return [startMarker.getLatLng(), ...destMarkers.map(m => m.getLatLng())];

    let remaining = [...destMarkers];
    let route = [startMarker.getLatLng()];
    let current = startMarker.getLatLng();

    while (remaining.length > 0) {
      let nearestIndex = 0;
      let nearestDist = distance(current.lat, current.lng, remaining[0].getLatLng().lat, remaining[0].getLatLng().lng);
      for (let i = 1; i < remaining.length; i++) {
        const d = distance(current.lat, current.lng, remaining[i].getLatLng().lat, remaining[i].getLatLng().lng);
        if (d < nearestDist) {
          nearestDist = d;
          nearestIndex = i;
        }
      }
      current = remaining[nearestIndex].getLatLng();
      route.push(current);
      remaining.splice(nearestIndex, 1);
    }

    return route;
  }

  function updateDestinasiInput() {
    const values = destMarkers.map(marker => {
      const name = marker.getPopup().getContent();
      return name.split(',')[0].trim();
    });
    document.getElementById('destinasiInput').value = values.join(',');
  }

  function updateRouteLine() {
    const route = getShortestRoute();
    if (routeLine) map.removeLayer(routeLine);
    routeLine = L.polyline(route, { color: 'blue', weight: 3 }).addTo(map);

    // Hitung total jarak
    let total = 0;
    for (let i = 0; i < route.length - 1; i++) {
      total += distance(route[i].lat, route[i].lng, route[i + 1].lat, route[i + 1].lng);
    }
    const km = total / 1000;
    document.getElementById('distance').textContent = `Total Jarak : ${km.toFixed(2)} km`;
    document.getElementById('jarak').value = km.toFixed(2);

    // Update destinasiInput
    const values = destMarkers.map(marker => {
      const name = marker.getPopup().getContent();
      return name.split(',')[0].trim();
    });
    document.getElementById('destinasiInput').value = values.join(',');

    // Cari destinasi terjauh dari startMarker
    if (startMarker && destMarkers.length > 0) {
      let farthest = destMarkers[0];
      let maxDist = distance(
        startMarker.getLatLng().lat, startMarker.getLatLng().lng,
        farthest.getLatLng().lat, farthest.getLatLng().lng
      );

      destMarkers.forEach(marker => {
        const d = distance(
          startMarker.getLatLng().lat, startMarker.getLatLng().lng,
          marker.getLatLng().lat, marker.getLatLng().lng
        );
        if (d > maxDist) {
          maxDist = d;
          farthest = marker;
        }
      });

      document.getElementById('end_lat').value = farthest.getLatLng().lat.toFixed(6);
      document.getElementById('end_lng').value = farthest.getLatLng().lng.toFixed(6);
    }
  }

  map.on('click', async function (e) {
    const lat = e.latlng.lat.toFixed(6);
    const lng = e.latlng.lng.toFixed(6);
    const name = await getLocationName(lat, lng);

    if (!isStartSet) {
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
        updateRouteLine();
      });

      isStartSet = true;
    } else {
      const destMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();
      destMarkers.push(destMarker);

      destMarker.on('dragend', updateRouteLine);

      updateRouteLine();
    }
  });

  document.getElementById('resetMap').addEventListener('click', function () {
    if (startMarker) map.removeLayer(startMarker);
    destMarkers.forEach(m => map.removeLayer(m));
    destMarkers = [];
    if (routeLine) map.removeLayer(routeLine);
    routeLine = null;

    document.getElementById('start_lat').value = '';
    document.getElementById('start_lng').value = '';
    document.getElementById('alamatInput').value = '';
    document.getElementById('end_lat').value = '';
    document.getElementById('end_lng').value = '';
    document.getElementById('destinasiInput').value = '';
    document.getElementById('jarak').value = '';
    document.getElementById('distance').textContent = '';

    isStartSet = false;
  });

  // Geocoder
  L.Control.geocoder({ defaultMarkGeocode: false })
    .on('markgeocode', async function(e) {
      const { lat, lng } = e.geocode.center;
      const name = e.geocode.name || await getLocationName(lat, lng);

      if (!isStartSet) {
        startMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();
        document.getElementById('start_lat').value = lat.toFixed(6);
        document.getElementById('start_lng').value = lng.toFixed(6);
        document.getElementById('alamatInput').value = name;
        isStartSet = true;
      } else {
        const destMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup(name).openPopup();
        destMarkers.push(destMarker);
        destMarker.on('dragend', updateRouteLine);
      }

      updateRouteLine();
      map.setView([lat, lng], 15);
    })
    .addTo(map);

});
</script>
