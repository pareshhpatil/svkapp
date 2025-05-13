<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Google Maps API Example</title>

  <!-- Google Maps API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXjOQcwVqCL9dC7pFJZ501AupJzzHBSXY&libraries=places&callback=initMap" 
    async defer></script>

  <style>
    html, body, #app, #map {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
<div id="app">
  <div id="map"></div>
</div>

<script>
  let map, directionsService, directionsRenderer, cabMarker;
  let intervalId;
  const office = { lat: 15.2993, lng: 74.1240 };
  const employees = [
    { name: "Priya", coords: { lat: 15.3050, lng: 74.1300 } },
    { name: "Ravi", coords: { lat: 15.3100, lng: 74.1350 } }
  ];
  const driverName = "John (Cab Driver)";

  function initMap() {
    // Initialize map
    map = new google.maps.Map(document.getElementById("map"), {
      center: office,
      zoom: 14
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
      map: map,
      suppressMarkers: true
    });

    addOfficeMarker();
    addEmployeeMarkers();
    drawRoute();
    updateCabLocation();

    // Update cab position every 10 seconds
    intervalId = setInterval(updateCabLocation, 1000);
  }

  function addOfficeMarker() {
    const officeMarker = new google.maps.Marker({
      position: office,
      map: map,
      title: "Office"
    });
    const infowindow = new google.maps.InfoWindow({
      content: "Office"
    });
    officeMarker.addListener("click", () => infowindow.open(map, officeMarker));
  }

  function addEmployeeMarkers() {
    employees.forEach(emp => {
      const marker = new google.maps.Marker({
        position: emp.coords,
        map: map,
        title: emp.name
      });
      const infowindow = new google.maps.InfoWindow({
        content: emp.name
      });
      marker.addListener("click", () => infowindow.open(map, marker));
    });
  }

  function drawRoute() {
    const waypoints = employees.map(emp => ({
      location: emp.coords,
      stopover: true
    }));

    const request = {
      origin: office,
      destination: employees[employees.length - 1].coords,
      waypoints: waypoints,
      travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, (result, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsRenderer.setDirections(result);
      } else {
        console.error("Directions request failed due to " + status);
      }
    });
  }

  function updateCabLocation() {
    // For testing: move cab position randomly or near office
    const position = {
      lat: 15.3020 + (Math.random() * 0.01 - 0.005),
      lng: 74.1260 + (Math.random() * 0.01 - 0.005)
    };

    if (!cabMarker) {
      cabMarker = new google.maps.Marker({
        position: position,
        map: map,
        icon: {
          url: 'https://cdn-icons-png.flaticon.com/512/61/61231.png',
          scaledSize: new google.maps.Size(32, 32)
        },
        title: driverName
      });
      const infowindow = new google.maps.InfoWindow({
        content: `Driver: ${driverName}`
      });
      cabMarker.addListener("click", () => infowindow.open(map, cabMarker));
    } else {
      cabMarker.setPosition(position);
    }
  }
</script>
</body>
</html>
