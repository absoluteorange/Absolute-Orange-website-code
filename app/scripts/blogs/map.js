function google_map () {

};

google_map.initialize = function (instancename) {
    this.instancename = instancename;
    this.markersArray = [];
    var mypolylineOptions = {
      strokeColor: '#FF7D27'
    }
    var directionsRendererOptions = {
      suppressMarkers : true,
      polylineOptions : mypolylineOptions
    }
    directionsDisplay = new google.maps.DirectionsRenderer(directionsRendererOptions);
    directionsService = new google.maps.DirectionsService();
    geocoder = new google.maps.Geocoder();
    infowindow = new google.maps.InfoWindow({
        width:150,
        height:270
    });
    //myLocation = new google.maps.LatLng(51.96291,-0.226071);
    myLocation = new google.maps.LatLng(51.479069,-0.211469);
    myOptions = {
      zoom: 17,
      center: myLocation,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
    contentString ="<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>This is Absolute Orange's location : <a href='javascript:void(0)' onclick='google_map.geolocation();' title='find your location'>What's yours?</a></p>"
    map.setCenter(myLocation);
    infowindow.setContent(contentString);
    infowindow.setPosition(myLocation);
    infowindow.open(map);
    google_map.addMarker(myLocation, "Absolute Orange's location");
};

google_map.addMarker = function (marker_position, marker_title) {
    var marker = new google.maps.Marker({
        position: marker_position,
        map: map,
        title: marker_title
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map, marker);
    });
    google_map.markersArray.push(marker);
};

google_map.deleteMarkers = function () {
  for (var i=0; i<google_map.markersArray.length; i++) {
    if (i > 0) {
        google_map.markersArray[i].setMap(null);
    }
  }
  google_map.markersArray.length = 1;
}

google_map.geolocation = function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(google_map.showPosition, google_map.onError);
    }
};

google_map.google_gears = function() {
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(google_map.showPosition, google_map.onError);
};

google_map.geo_yahoo = function () {
    yqlgeo.get('visitor', google_map.normalize_yql_response);
};

google_map.codeAddress = function () {
    var address = document.getElementById("address").value;
    if (geocoder) {
        geocoder.geocode( {'address': address}, function(results, status) {
	        if (status == google.maps.GeocoderStatus.OK) {
	        	google_map.displayPosition(results[0].geometry.location.jb, results[0].geometry.location.kb);
	        } else {
	            google_map.feedback('error', '', status);
	        }
        });
    }
};

google_map.showPosition = function (position) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  google_map.displayPosition(lat, lng);
};

google_map.displayPosition = function (lat, lng) {
  var latlng = new google.maps.LatLng(lat, lng);
  google_map.deleteMarkers();
  google_map.addMarker(latlng, "Your location");
  var latlng_array = new Array();
  latlng_array.push(latlng);
  latlng_array.push(myLocation);
  var latlngbounds = new google.maps.LatLngBounds();
  for ( var i = 0; i < latlng_array.length; i++ ){
    latlngbounds.extend(latlng_array[i]);
  }
  map.setCenter(latlngbounds.getCenter());
  map.fitBounds(latlngbounds);
  //distance
  var request = {
      origin: latlng,
      destination: myLocation,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == 'ZERO_RESULTS' ) {
        contentString = google_map.feedback('relocation','', '');
    }
    else if (status == google.maps.DirectionsStatus.OK) {
      var distance = response.routes[0].legs[0].distance.value/1000;
      directionsDisplay.setDirections(response);
      if (distance <= '10') {
         contentString  = google_map.feedback('feasible', distance, '');
      }  else {
         contentString = google_map.feedback('not feasible', distance, '');
      }
    }
    infowindow.setContent(contentString);
    infowindow.setPosition(latlng);
    infowindow.open(map);
    directionsDisplay.setMap(map);
  });
};

google_map.onError = function(error) {
  if (error.message == 'User denied Geolocation') {
     contentString = "<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>Error: User denied Geolocation.  You will need to allow the browser to find your location or update your browser.  Now trying Google gears to find your location.</p>";
     timer = setTimeout("if (google.gears) { google_map.google_gears(); } else { google_map.onError('Google gears'); }", 5000);
  } else if (error == 'Google gears') {
     contentString = "<p><img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' />Error: Google gears is not supported.<br />Now trying Yahoo Geo to find your<br />location using your IP address.</p>";
     timer = setTimeout("google_map.geo_yahoo();", 5000);
  }
  map.setCenter(myLocation);
  infowindow.setContent(contentString);
  infowindow.setPosition(myLocation);
  infowindow.open(map);  
};

google_map.normalize_yql_response = function (response)  {
    if (response.error)  {
        var error = {code : 0};
        onError(error);
        return;
    }
    var position = {
        coords : {
        latitude: response.place.centroid.latitude,
        longitude: response.place.centroid.longitude
    },
    address :  {
        city: response.place.locality2.content,
        region: response.place.admin1.content,
        country: response.place.country.content
    }};
    google_map.handle_geolocation_query(position);
};

google_map.handle_geolocation_query = function (position){
    google_map.showPosition(position);
};

google_map.feedback = function(message, distance, status) {
     if (message == 'feasible') {
        contentString ="<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is a feasible journey for<br />Absolute Orange.</p>"
     } else if (message == 'relocation') {
        contentString ="<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>Your location is miles away from Absolute Orange.<br />Absolute Orange would have to relocate.</p>"
     } else if (message == 'error') {
        contentString ="<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>Geo Yahoo was unsuccessful for<br />the following reason: " +status+".</p>";
     } else if (message == 'not feasible') {
         contentString ="<img id='map_logo' src='http://www.absoluteorange.com/images/logo.png' title='Absolute Orange' height='35' /><p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is might not be a feasible<br />journey for Absolute Orange.</p>";
     }
     return contentString;
};