var geoLocator = (function () {
    "use strict";
    var markersArray = [];
    var img = "<img id='map_logo' src='http://www.absoluteorange.com/images/bg/logo.png' title='Absolute Orange' height='35' />"
    var mypolylineOptions = {
        strokeColor: '#FF7D27'
    },
    directionsRendererOptions = {
        suppressMarkers : true,
        polylineOptions : mypolylineOptions
    };
    var directionsDisplay = new google.maps.DirectionsRenderer(directionsRendererOptions);
    var directionsService = new google.maps.DirectionsService();
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow({
        width:150,
        height:270
    });
    var myLocation = new google.maps.LatLng(51.479069,-0.211469);
    var myOptions = {
        zoom: 17,
        center: myLocation,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById('map-canvas'), myOptions);
    var contentString = [img, "<p>This is Absolute Orange's location : <a href='javascript:void(0)' onclick='geoLocator.getCurrentPos();' title='find your location'>What's yours?</a></p>"].join("");
    map.setCenter(myLocation);
    infowindow.setContent(contentString);
    infowindow.setPosition(myLocation);
    infowindow.open(map);

    function addMarker(marker_position, marker_title) {
        var marker = new google.maps.Marker ({
            position: marker_position,
            map: map,
            title: marker_title
        });
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.open(map, marker);
        });
        markersArray.push(marker);
    }
    
    function deleteMarkers () {
      for (var i= 0, len=markersArray.length; i<len; i++) {
        if (i > 0) {
            markersArray[i].setMap(null);
        }
      }
      markersArray.length = 1;
    }

    function getCurrentPos () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition(), onError());
        }
    }

    function googleGears () {
        var geo = google.gears.factory.create('beta.geolocation');
        geo.getCurrentPosition(showPosition(), onError());
    }

    function geoYahoo () {
        yqlgeo.get('visitor', normalizeYqlResponse());
    }

    function codeAddress () {
        var address = document.getElementById("address").value;
        if (geocoder) {
            geocoder.geocode( {'address': address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    displayPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                } else {
                    giveFeedback('error', '', status);
                }
            });
        }
    }

    function showPosition (position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        displayPosition(lat, lng);
    }

    function displayPosition (lat, lng) {
        var latlng = new google.maps.LatLng(lat, lng);
        var latlngbounds = extendLatlngBounds(latlng);
        deleteMarkers();
        addMarker(latlng, "Your location");
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
        displayDirections(latlng);
    }

    function displayDirections(latlng) {
        var request = {
            origin: latlng,
            destination: myLocation,
            travelMode: google.maps.DirectionsTravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == 'ZERO_RESULTS' ) {
                contentString = giveFeedback('relocation','', '');
            }
            else if (status == google.maps.DirectionsStatus.OK) {
                var distance = response.routes[0].legs[0].distance.value/1000;
                directionsDisplay.setDirections(response);
                if (distance <= '10') {
                  contentString  = giveFeedback('feasible', distance, '');
                } else {
                  contentString = giveFeedback('not feasible', distance, '');
                }
            }
            infowindow.setContent(contentString);
            infowindow.setPosition(latlng);
            infowindow.open(map);
            directionsDisplay.setMap(map);
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        });
    }

    function extendLatlngBounds (latlng) {
        var latlngbounds = new google.maps.LatLngBounds();
        var latlng_array = [];
        latlng_array.push(latlng);
        latlng_array.push(myLocation);
        for ( var i = 0; i < latlng_array.length; i++ ){
            latlngbounds.extend(latlng_array[i]);
        }
        return latlngbounds;
    }

    function onError (error) {
      if (error.message == 'User denied Geolocation') {
         contentString = [img, "<p>Error: User denied Geolocation.  You will need to allow the browser to find your location or update your browser.  Now trying Google gears to find your location.</p>"].join("");
         timer = setTimeout("if (google.gears) { geoLocator.googleGears(); } else { geoLocator.onError('Google gears'); }", 5000);
      } else if (error == 'Google gears') {
         contentString = [img, "<p>Error: Google gears is not supported.<br />Now trying Yahoo Geo to find your<br />location using your IP address.</p>"].join("");
         timer = setTimeout("geoLocator.geoYahoo();", 5000);
      }
      map.setCenter(myLocation);
      infowindow.setContent(contentString);
      infowindow.setPosition(myLocation);
      infowindow.open(map);
    }

    function normalizeYqlResponse (response)  {
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
        handleGeolocationQuery(position);
    }

    function handleGeolocationQuery (position){
        showPosition(position);
    }

    function giveFeedback (message, distance, status) {
         if (message == 'feasible') {
            contentString ="" + img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is a feasible journey for<br />Absolute Orange.</p>"
         } else if (message == 'relocation') {
            contentString ="" + img +"<p>Your location is miles away from Absolute Orange.<br />Absolute Orange would have to relocate.</p>"
         } else if (message == 'error') {
            contentString ="" + img +"<p>Geo Yahoo was unsuccessful for<br />the following reason: " +status+".</p>";
         } else if (message == 'not feasible') {
             contentString ="" + img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is might not be a feasible<br />journey for Absolute Orange.</p>";
         }
         return contentString;
    }

    return {
        addMarker: addMarker,
        getCurrentPos: getCurrentPos,
        codeAddress: codeAddress
    };
})();

geoLocator.addMarker(geoLocator.myLocation, "Absolute Orange's location");
