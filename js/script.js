// Wait for the page to load
document.addEventListener("DOMContentLoaded", function () {
  // Create the map
  var map = L.map("map").setView([44.439729, 26.051674], 14);

  // Add the tile layer (OpenStreetMap)
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  // Define the parking spots
  var parkingSpots = [
    {
      name: "Politehnica",
      location: [44.439729, 26.051674],
      parkingLot: 150,
    },
    {
      name: "Afi",
      location: [44.43177, 26.053951],
      parkingLot: 300,
    },
    {
      name: "Regie",
      location: [44.445703, 26.053933],
      parkingLot: 200,
    },
    {
      name: "Grozavesti",
      location: [44.44256, 26.059576],
      parkingLot: 100,
    },
    {
      name: "Orhideea",
      location: [44.444136, 26.061827],
      parkingLot: 20,
    },
  ];

  // Create an array to store the markers
  var markers = [];

  // Function to add event listeners to markers
  function addMarkerListeners(marker, parkingSpot) {
    marker.addEventListener("click", function () {
      var clickedParkingMessage = document.getElementById("clickedParkingMessage");
      var bookButton = document.getElementById("bookButton");
      var bookingPage = "Booking.php?name=" + parkingSpot.name;

      clickedParkingMessage.textContent = "You clicked the parking: " + parkingSpot.name;
      bookButton.style.display = "block";
      bookButton.textContent = "Book: " + parkingSpot.name;
      bookButton.href = bookingPage;

      console.log("You clicked the parking: " + parkingSpot.name);
    });
  }

  // Function to create markers for parking spots
  function createMarkers(filteredSpots) {
    // Clear existing markers from the map
    markers.forEach(function (marker) {
      map.removeLayer(marker);
    });
    markers = [];

    // Add a marker for each parking spot
    filteredSpots.forEach(function (parkingSpot) {
      var marker = L.marker(parkingSpot.location).addTo(map);
      marker.bindPopup(
        `<strong>${parkingSpot.name}</strong><br>Parking lot: ${parkingSpot.parkingLot}`
      );

      // Add event listeners to the marker
      addMarkerListeners(marker._icon, parkingSpot);

      markers.push(marker);
    });
  }

  // Search functionality
  var searchInput = document.getElementById("searchInput");
  var searchButton = document.getElementById("searchButton");

  searchButton.addEventListener("click", function () {
    var query = searchInput.value.trim().toLowerCase();

    // Filter parking spots based on the search query
    var filteredSpots = parkingSpots.filter(function (parkingSpot) {
      var spotName = parkingSpot.name.toLowerCase();
      return spotName.includes(query);
    });

    // Get the user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        var userLocation = [position.coords.latitude, position.coords.longitude];

        // Calculate distances to the user's location and sort spots by distance
        filteredSpots.forEach(function (parkingSpot) {
          parkingSpot.distance = getDistance(parkingSpot.location, userLocation);
        });
        filteredSpots.sort(function (a, b) {
          return a.distance - b.distance;
        });

        // Add markers for the filtered parking spots
        createMarkers(filteredSpots);
      });
    } else {
      console.log("Geolocation is not supported by this browser.");
    }
  });

  // Function to calculate distance between two coordinates using Haversine formula
  function getDistance(coord1, coord2) {
    var lat1 = coord1[0];
    var lon1 = coord1[1];
    var lat2 = coord2[0];
    var lon2 = coord2[1];

    var R = 6371; // Radius of the Earth in kilometers
    var dLat = degToRad(lat2 - lat1);
    var dLon = degToRad(lon2 - lon1);
    var a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(degToRad(lat1)) *
        Math.cos(degToRad(lat2)) *
        Math.sin(dLon / 2) *
        Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var distance = R * c;

    return distance;
  }

  function degToRad(deg) {
    return (deg * Math.PI) / 180;
  }
});
