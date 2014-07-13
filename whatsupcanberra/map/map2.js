	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	var map;

function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();
	var chicago = new google.maps.LatLng(41.850033, -87.6500523);
	var mapOptions = {
  	center: new google.maps.LatLng(-35.3075,149.1244),
  	zoom: 11,
  	
  	styles: [{featureType:'water',elementType:'all',	
        stylers:[{hue:'#e9ebed'},{saturation:-78},{lightness:67},{visibility:'simplified'}]	
        },
        {
        featureType:'landscape',elementType:'all',
        stylers:[{hue:'#ffffff'},{saturation:-100},{lightness:100},{visibility:'simplified'}]
        },
        {
        featureType:'road',elementType:'geometry',					
        stylers:[{hue:'#bbc0c4'},{saturation:-93},{lightness:31},{visibility:'simplified'}]
        },
        { 
        featureType:'poi',elementType:'all',
        stylers:[{hue:'#ffffff'},{saturation:-100},{lightness:100},	{visibility:'off'}]
        },
        {
        featureType:'road.local',elementType:'geometry',
        stylers:[{hue:'#e9ebed'},{saturation:-90},{lightness:-8},{visibility:'simplified'}]	
        },
        {
        featureType:'transit',elementType:'all',
        stylers:[{hue:'#e9ebed'},{saturation:10},{lightness:69},{visibility:'on'}]
        },
        {
        featureType:'administrative.locality',elementType:'all',
        stylers:[{hue:'#2c2e33'},{saturation:7},{lightness:19},{visibility:'on'}]
        },
        {
        featureType:'road',elementType:'labels',
        stylers:[{hue:'#bbc0c4'},{saturation:-93},{lightness:31},{visibility:'on'}]
        },
        {
        featureType:'road.arterial',elementType:'labels',
        stylers:[{hue:'#bbc0c4'},{saturation:-93},{lightness:-2},{visibility:'simplified'}]	
        }			]
  };
  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  directionsDisplay.setMap(map);
}

function calcRoute() {
  var start = document.getElementById('start').value;
  var end = document.getElementById('end').value;
  var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.TravelMode.DRIVING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}
function add_location(description, lastitude, longtitude) {
    locations.push([description, lastitude, longtitude]);
    console.log('#locations: ' + locations.length);
    console.log(locations);  
}

// Set all the markers in the location arrays and bound/frame the map
function set_markers(bounds, map) {
    console.log('#locations: ' + locations.length);
    console.log(bounds);
    
    for (var i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });
        bounds.extend(marker.position);
        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
    map.fitBounds(bounds);
}

// Get current location based on the IP Address
function set_current_location() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            /*
            var pos = new google.maps.LatLng(position.coords.latitude,
                                             position.coords.longitude);
            var myLat = position.coords.latitude;
            var myLong = position.coords.longitude;
            */
            add_location('My location', 
                        position.coords.latitude, 
                        position.coords.longitude);
           	set_markers(new google.maps.LatLngBounds(), map);
        }, function error(err) {
            console.log('error: ' + err.message);
            set_markers(new google.maps.LatLngBounds(), map);            
        });
    } else {
        alert("Geolocation is not supported by this browser.");
        //set_markers(new google.maps.LatLngBounds(), map);
    }
}

google.maps.event.addDomListener(window, 'load', initialize);
