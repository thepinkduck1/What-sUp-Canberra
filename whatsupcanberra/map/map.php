<?php
	$address = $_POST["address"];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css' />
    <link type="image/png" rel="icon" href="../favicon.png" />
    <title>What'sUp Canberra Directions</title>
    		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <style>
      html, body, #map-canvas {
        height: 98%;
        margin: 0px;
      }
    img.button{
		cursor: pointer;
	}
      
      body{
      	margin-bottom: 20px;
      	overflow: hidden;
      }
      
      #directions-panel {
        height: 100%;
        float: left;
        width: 390px;
        overflow: auto;
        padding-left: 5px;
      }

      #map-canvas {
        margin-left: 400px;
      }

      #control {
        background: #fff;
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;
      }
      
      .header{
		width: 100%;
		height: 50px;
		background-image:url('../images/Landscape_Blur.jpg');
		background-size: cover;
		font-size: 30px;
		font-weight: lighter;
		color: white;
		vertical-align: center;
		line-height: 50px;
		border-bottom: 2px solid gray;
		padding-left: 10px;
		font-family: "Lato", sans-serif;
		}
		
		.header p{
			margin-left: 35px;
			padding-left: 5px;
			vertical-align: center;
		}
		
		.header img {
			float: right;
			border: none;
		    margin-top: 0;
		    margin-right: 20px;
		    vertical-align: center;
		    transition-duration: 0.3s;
		}
		
		.header img:hover {
			-webkit-transform: scale(0.9);
			-moz-transform:    scale(0.9);
			-o-transform:      scale(0.9);
		 	-ms-transform:     scale(0.9);
		 	transition-duration: 0.3s;
		}
		
		.header img:active {
			-webkit-transform: scale(0.8);
			-moz-transform:    scale(0.8);
			-o-transform:      scale(0.8);
		 	-ms-transform:     scale(0.8);
		}

      @media print {
        #map-canvas {
          height: 500px;
          margin: 0;
        }

        #directions-panel {
          float: none;
          width: auto;
        }
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var link;
var begin;
function initialize() {
  directionsDisplay = new google.maps.DirectionsRenderer();
   var mapOptions = {
                    zoom: 11,
                    center: new google.maps.LatLng(-35.3075, 149.1244),


                    styles: [	{		featureType:'water',		stylers:[{color:'#46bcec'},{visibility:'on'}]	},{		featureType:'landscape',		stylers:[{color:'#f2f2f2'}]	},{		featureType:'road',		stylers:[{saturation:200},{lightness:45}]	},{		featureType:'road.highway',		stylers:[{visibility:'simplified'}]	},{		featureType:'road.arterial',		elementType:'labels.icon',		stylers:[{visibility:'off'}]	},{		featureType:'administrative',		elementType:'labels.text.fill',		stylers:[{color:'#444444'}]	},{		featureType:'transit',		stylers:[{visibility:'off'}]	},{		featureType:'poi',		stylers:[{visibility:'off'}]	}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById('map-canvas');

                // Create the Google Map using out element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(document.getElementById('directions-panel'));

}
function displayLocation (position) {
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	link = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude;
	$.getJSON(link, function(json) {
		begin = json.results[0].formatted_address;
		var request = {
					origin: begin,
					destination: "<?php echo $address;?>",
					travelMode: google.maps.TravelMode.DRIVING
				};
				directionsService.route(request, function(result, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(result);
					}
				});
});
}
navigator.geolocation.getCurrentPosition(displayLocation);

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
  	<div class="header">What'sUp Canberra - Directions<a href="../"><img class="button" src="../images/home-50.png" alt=Home /></a></div>
    <div id="directions-panel"></div>
    <div id="map-canvas"></div>
  </body>
</html>