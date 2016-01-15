function geoLocator() {}

geoLocator.initialize = function () {
    "use strict";
    this.markersArray = [];
    this.img = "<img id='map_logo' src='http://www.absoluteorange.com/images/bg/logo.png' title='Absolute Orange' height='35' />"

    var mypolylineOptions = {
        strokeColor: '#FF7D27'
    },
    directionsRendererOptions = {
        suppressMarkers : true,
        polylineOptions : mypolylineOptions
    };
    this.directionsDisplay = new google.maps.DirectionsRenderer(directionsRendererOptions),
    this.directionsService = new google.maps.DirectionsService(),
    this.geocoder = new google.maps.Geocoder(),
    this.infowindow = new google.maps.InfoWindow({
        width:150,
        height:270
    }),
    this.myLocation = new google.maps.LatLng(51.479069,-0.211469),
    this.myOptions = {
        zoom: 17,
        center: this.myLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    },
    this.map = new google.maps.Map(document.getElementById('map-canvas'), this.myOptions),
    this.contentString = "" + this.img + "<p>This is Absolute Orange's location : <a href='javascript:void(0)' onclick='geoLocator.geolocation();' title='find your location'>What's yours?</a></p>"

    this.map.setCenter(this.myLocation);
    this.infowindow.setContent(this.contentString);
    this.infowindow.setPosition(this.myLocation);
    this.infowindow.open(this.map);
    geoLocator.addMarker(this.myLocation, "Absolute Orange's location");
};

geoLocator.addMarker = function (marker_position, marker_title) {
    var marker = new google.maps.Marker ({
        position: marker_position,
        map: this.map,
        title: marker_title
    });
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
    });
    geoLocator.markersArray.push(marker);
};

geoLocator.deleteMarkers = function () {
  for (var i= 0, len=geoLocator.markersArray.length; i<len; i++) {
    if (i > 0) {
        geoLocator.markersArray[i].setMap(null);
    }
  }
  geoLocator.markersArray.length = 1;
}

geoLocator.geolocation = function () {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(geoLocator.showPosition, geoLocator.onError);
    }
};

geoLocator.google_gears = function() {
    var geo = google.gears.factory.create('beta.geolocation');
    geo.getCurrentPosition(geoLocator.showPosition, geoLocator.onError);
};

geoLocator.geo_yahoo = function () {
    yqlgeo.get('visitor', geoLocator.normalize_yql_response);
};

geoLocator.codeAddress = function () {
    var address = document.getElementById("address").value;
    if (this.geocoder) {
        this.geocoder.geocode( {'address': address}, function(results, status) {
	        if (status === google.maps.GeocoderStatus.OK) {
	        	geoLocator.displayPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
	        } else {
	            geoLocator.feedback('error', '', status);
	        }
        });
    }
};

geoLocator.showPosition = function (position) {
  var lat = position.coords.latitude;
  var lng = position.coords.longitude;
  geoLocator.displayPosition(lat, lng);
};

geoLocator.displayPosition = function (lat, lng) {
  var latlng = new google.maps.LatLng(lat, lng);
  geoLocator.deleteMarkers();
  geoLocator.addMarker(latlng, "Your location");
  var latlng_array = [];
  latlng_array.push(latlng);
  latlng_array.push(this.myLocation);
  var latlngbounds = new google.maps.LatLngBounds();
  for ( var i = 0; i < latlng_array.length; i++ ){
    latlngbounds.extend(latlng_array[i]);
  }
  this.map.setCenter(latlngbounds.getCenter());
  this.map.fitBounds(latlngbounds);
  //distance
  var request = {
      origin: latlng,
      destination: this.myLocation,
      travelMode: google.maps.DirectionsTravelMode.DRIVING
  };
  var thisObj = this;
  this.directionsService.route(request, function(response, status) {
    if (status == 'ZERO_RESULTS' ) {
        contentString = geoLocator.feedback('relocation','', '');
    }
    else if (status == google.maps.DirectionsStatus.OK) {
      var distance = response.routes[0].legs[0].distance.value/1000;
      thisObj.directionsDisplay.setDirections(response);
      if (distance <= '10') {
          thisObj.contentString  = geoLocator.feedback('feasible', distance, '');
      }  else {
          thisObj.contentString = geoLocator.feedback('not feasible', distance, '');
      }
    }
    thisObj.infowindow.setContent(thisObj.contentString);
    thisObj.infowindow.setPosition(latlng);
    thisObj.infowindow.open(thisObj.map);
    thisObj.directionsDisplay.setMap(thisObj.map);
    document.body.scrollTop = document.documentElement.scrollTop = 0;
  });
};

geoLocator.onError = function(error) {
  if (error.message == 'User denied Geolocation') {
     this.contentString = "" + this.img +"<p>Error: User denied Geolocation.  You will need to allow the browser to find your location or update your browser.  Now trying Google gears to find your location.</p>";
     timer = setTimeout("if (google.gears) { geoLocator.google_gears(); } else { geoLocator.onError('Google gears'); }", 5000);
  } else if (error == 'Google gears') {
     this.contentString = "" + this.img +"<p>Error: Google gears is not supported.<br />Now trying Yahoo Geo to find your<br />location using your IP address.</p>";
     timer = setTimeout("geoLocator.geo_yahoo();", 5000);
  }
  this.map.setCenter(this.myLocation);
  this.infowindow.setContent(this.contentString);
  this.infowindow.setPosition(this.myLocation);
  this.infowindow.open(this.map);
};

geoLocator.normalize_yql_response = function (response)  {
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
    geoLocator.handle_geolocation_query(position);
};

geoLocator.handle_geolocation_query = function (position){
    geoLocator.showPosition(position);
};

geoLocator.feedback = function(message, distance, status) {
     if (message == 'feasible') {
        contentString ="" + this.img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is a feasible journey for<br />Absolute Orange.</p>"
     } else if (message == 'relocation') {
        contentString ="" + this.img +"<p>Your location is miles away from Absolute Orange.<br />Absolute Orange would have to relocate.</p>"
     } else if (message == 'error') {
        contentString ="" + this.img +"<p>Geo Yahoo was unsuccessful for<br />the following reason: " +status+".</p>";
     } else if (message == 'not feasible') {
         contentString ="" + this.img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is might not be a feasible<br />journey for Absolute Orange.</p>";
     }
     return contentString;
};