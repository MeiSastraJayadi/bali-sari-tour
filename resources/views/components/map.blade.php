<div>
    <div id="map_instructions" 
     class="w-full md:w-3/4 mb-3 px-4 py-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm">
  🗺️ <b>Petunjuk:</b> Klik peta untuk memilih titik awal dan titik tujuan.  
  Anda juga bisa mencari nama jalan di kolom pencarian di atas.
</div>
    
    <div id="map" class="w-full h-96 rounded-lg mb-4"></div>
  
    <div class="grid grid-cols-2 gap-4 w-full md:w-96">
      <div>
        <label class="text-sm font-medium text-gray-600">Start Lat</label>
        <input id="start_lat" name="start_lat" type="text"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"
               readonly>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-600">Start Lng</label>
        <input id="start_lng" name="start_lng" type="text"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"
               readonly>
      </div>
  
      <div>
        <label class="text-sm font-medium text-gray-600">End Lat</label>
        <input id="end_lat" name="end_lat" type="text"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"
               readonly>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-600">End Lng</label>
        <input id="end_lng" name="end_lng" type="text"
               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"
               readonly>
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
  
        // === FUNGSI MENGHITUNG JARAK (km)
        function calculateDistance() {
          if (startMarker && endMarker) {
            const distance = map.distance(startMarker.getLatLng(), endMarker.getLatLng()) / 1000;
            document.getElementById('distance').textContent = `Jarak: ${distance.toFixed(2)} km`;
          }
        }
  
        // === EVENT KLIK PETA
        map.on('click', function (e) {
          const lat = e.latlng.lat.toFixed(6);
          const lng = e.latlng.lng.toFixed(6);
  
          if (clickCount === 0) {
            if (startMarker) map.removeLayer(startMarker);
            startMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup('Start').openPopup();
  
            document.getElementById('start_lat').value = lat;
            document.getElementById('start_lng').value = lng;
  
            startMarker.on('dragend', function (event) {
              const newLat = event.target.getLatLng().lat.toFixed(6);
              const newLng = event.target.getLatLng().lng.toFixed(6);
              document.getElementById('start_lat').value = newLat;
              document.getElementById('start_lng').value = newLng;
              if (routeLine) {
                routeLine.setLatLngs([startMarker.getLatLng(), endMarker.getLatLng()]);
                calculateDistance();
              }
            });
  
            clickCount = 1;
  
          } else if (clickCount === 1) {
            if (endMarker) map.removeLayer(endMarker);
            endMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup('Destination').openPopup();
  
            document.getElementById('end_lat').value = lat;
            document.getElementById('end_lng').value = lng;
  
            if (routeLine) map.removeLayer(routeLine);
            routeLine = L.polyline([
              [startMarker.getLatLng().lat, startMarker.getLatLng().lng],
              [lat, lng]
            ], { color: 'blue', weight: 3 }).addTo(map);
  
            calculateDistance();
  
            endMarker.on('dragend', function (event) {
              const newLat = event.target.getLatLng().lat.toFixed(6);
              const newLng = event.target.getLatLng().lng.toFixed(6);
              document.getElementById('end_lat').value = newLat;
              document.getElementById('end_lng').value = newLng;
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
            document.getElementById('end_lat').value = '';
            document.getElementById('end_lng').value = '';
            document.getElementById('distance').textContent = '';
            clickCount = 0;
          }
        });
  
        // === TAMBAHKAN FITUR SEARCH
        const geocoder = L.Control.geocoder({
          defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
          const { lat, lng } = e.geocode.center;
  
          if (clickCount === 0) {
            // set sebagai start
            if (startMarker) map.removeLayer(startMarker);
            startMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup('Start').openPopup();
            document.getElementById('start_lat').value = lat.toFixed(6);
            document.getElementById('start_lng').value = lng.toFixed(6);
            clickCount = 1;
          } else if (clickCount === 1) {
            // set sebagai end
            if (endMarker) map.removeLayer(endMarker);
            endMarker = L.marker([lat, lng], { draggable: true }).addTo(map).bindPopup('Destination').openPopup();
            document.getElementById('end_lat').value = lat.toFixed(6);
            document.getElementById('end_lng').value = lng.toFixed(6);
  
            if (routeLine) map.removeLayer(routeLine);
            routeLine = L.polyline([
              [startMarker.getLatLng().lat, startMarker.getLatLng().lng],
              [lat, lng]
            ], { color: 'blue', weight: 3 }).addTo(map);
  
            calculateDistance();
            clickCount = 2;
          } else {
            // reset kalau sudah dua titik
            map.removeLayer(startMarker);
            map.removeLayer(endMarker);
            map.removeLayer(routeLine);
            document.getElementById('start_lat').value = '';
            document.getElementById('start_lng').value = '';
            document.getElementById('end_lat').value = '';
            document.getElementById('end_lng').value = '';
            document.getElementById('distance').textContent = '';
            clickCount = 0;
          }
  
          map.setView([lat, lng], 15);
        })
        .addTo(map);
      });
    </script>
  