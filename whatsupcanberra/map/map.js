var locations = [];
var map,
    marker,
    infowindow;
    
var styleArray = [
  {
    featureType: "all",
    stylers: [
      { saturation: -80 }
    ]
  },{
    featureType: "road.arterial",
    elementType: "geometry",
    stylers: [
      { hue: "#00ffee" },
      { saturation: 50 }
    ]
  },{
    featureType: "poi.business",
    elementType: "labels",
    stylers: [
      { visibility: "off" }
    ]
  }
];

function initialize() {
    infowindow = new google.maps.InfoWindow();
    
    var myOptions = {
        zoom: 4,
        center: new google.maps.LatLng(0, 0),
        mapTypeControl: true,
        navigationControl: true,
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    set_current_location();
    //set_markers(new google.maps.LatLngBounds(), map);

  // Create an array of styles.
  var styles = [
    {
      stylers: [
        { hue: "#00ffe6" },
        { saturation: -20 }
      ]
    },{
      featureType: "road",
      elementType: "geometry",
      stylers: [
        { lightness: 100 },
        { visibility: "simplified" }
      ]
    },{
      featureType: "road",
      elementType: "labels",
      stylers: [
        { visibility: "off" }
      ]
    }
  ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.
  var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

  // Create a map object, and include the MapTypeId to add
  // to the map type control.
  var mapOptions = {
    zoom: 11,
    center: new google.maps.LatLng(55.6468, 37.581),
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    }
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
    mapOptions);

  //Associate the styled map with the MapTypeId and set it to display.
  map.mapTypes.set('map_style', styledMap);
  map.setMapTypeId('map_style');
}


// add new location to the locations array
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