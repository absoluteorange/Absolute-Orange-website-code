define(['lib/dom-ready', 'lib/signals'], function (domReady, Signals) {
    var GeoLocator = function () {
        var allowGeoLocatorSignal = new Signals.Signal()
        var denyGeoLocatorSignal = new Signals.Signal()
        var subscribe=function(event, callback){
            switch(event){
                case 'allow':
                    allowGeoLocatorSignal.add(callback);
                    break;
                case 'deny':
                    denyGeoLocatorSignal.add(callback);
                    break;
            }
        };
        var markersArray = [];
        var img = "<div id='map_logo' class='icon-fish'></div>"
        var mypolylineOptions = {
            strokeColor: '#FF7D27'
        };
        var directionsRendererOptions = {
            suppressMarkers : true,
            polylineOptions : mypolylineOptions
        };
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
        var contentString = [img, "<p>This is Absolute Orange's location : <span class='callToAction'>>> </span><a href='javascript:void(0)' onclick='GeoLocator.getCurrentPos();' title='find your location' id='findLocation'>Click to find yours</a></p>"].join("");
        function init () {
            var marker = addMarker(myLocation, "Absolute Orange's location");
            infowindow.setContent(contentString);
            infowindow.setPosition(myLocation);
            infowindow.open(map, marker);
        }

        function addMarker(marker_position, marker_title) {
            var marker = new google.maps.Marker ({
                position: marker_position,
                map: map,
                title: marker_title
            });
            var latlngbounds = extendLatlngBounds(marker_position); 
            map.setCenter(latlngbounds.getCenter());
            map.fitBounds(latlngbounds);
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
            markersArray.push(marker);
            return marker;
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
            displayLoadingMessage();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, onError);
            }
        }

        function displayLoadingMessage () {
            var contentString = "" + img +"<p>Loading your location ...</p>";
            infowindow.setContent(contentString);
        }

        function codeAddress () {
            displayLoadingMessage();
            var address = document.getElementById("address").value;
            if (typeof geocoder === 'undefined') {
                var geocoder = new google.maps.Geocoder();
            }
            geocoder.geocode( {'address': address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    displayPosition(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                } else {
                    giveFeedback('error', '', status);
                }
            });
        }

        function showPosition (position) {
            allowGeoLocatorSignal.dispatch($(this));
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            displayPosition(lat, lng);
        }

        function displayPosition (lat, lng) {
            var latlng = new google.maps.LatLng(lat, lng);
            deleteMarkers();
            var marker = addMarker(latlng, "Your location");
            infowindow.close();
            displayDirections(latlng, marker);
        }

        function displayDirections(latlng, marker) {
            var request = {
                origin: latlng,
                destination: myLocation,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            };
            if (typeof directionsService === 'undefined') {
                var directionsService = new google.maps.DirectionsService();
            }
            directionsService.route(request, function(response, status) {
                if (status == 'ZERO_RESULTS' ) {
                    contentString = giveFeedback('relocation','', '');
                }
                else if (status == google.maps.DirectionsStatus.OK) {
                    var distance = response.routes[0].legs[0].distance.value/1000;
                    if (typeof directionsDisplay === 'undefined') {
                        var directionsDisplay = new google.maps.DirectionsRenderer(directionsRendererOptions);
                    }
                    directionsDisplay.setDirections(response);
                    if (distance <= 10) {
                      contentString  = giveFeedback('feasible', distance, '');
                    } else if (distance <= 80) {
                      contentString = giveFeedback('not feasible', distance, '');
                    } else {
                      contentString = giveFeedback('relocation', distance, '');
                    }
                }
                infowindow.setContent(contentString);
                infowindow.setPosition(latlng);
                infowindow.open(map, marker);
                directionsDisplay.setMap(map);
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
            denyGeoLocatorSignal.dispatch($(this));
            if (error.message == 'User denied Geolocation') {
                contentString = [img, "<p>Error: User denied Geolocation.  You will need to allow the browser to find your location or update your browser.</p>"].join("");
            }
            infowindow.setContent(contentString);
            infowindow.setPosition(myLocation);
            infowindow.open(map);
        }

        function handleGeolocationQuery (position){
            showPosition(position);
        }

        function giveFeedback (message, distance, status) {
             if (message == 'feasible') {
                contentString ="" + img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is a feasible journey for<br />Absolute Orange.</p>"
             } else if (message == 'relocation') {
                contentString ="" + img +"<p>Your location is "+distance+" miles away from Absolute Orange.<br />Absolute Orange would have to relocate.</p>"
             } else if (message == 'not feasible') {
                 contentString ="" + img +"<p>Your location is approximately "+distance+" km's from<br />Absolute Orange.  This is might not be a feasible<br />journey for Absolute Orange.</p>";
             }
             return contentString;
        }
        return {
            subscribe: subscribe,
            myLocation: myLocation,
            init: init,
            addMarker: addMarker,
            getCurrentPos: getCurrentPos,
            codeAddress: codeAddress
        }
    };
    var lab = document.getElementById('lab');
    if ((typeof google !== 'undefined') && (typeof google.maps !== 'undefined') && (lab !== null)) {
        return new GeoLocator();
    }
});
