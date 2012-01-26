<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }
</style>
<script type="text/javascript"
    src="http://maps.googleapis.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
  function initialize() {
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"),
        myOptions);
		
	var marker = new google.maps.Marker({  
	  position: latlng,  
	  map: map,  
	  title: 'My workplace',  
	  clickable: false,  
	  icon: 'http://google-maps-icons.googlecode.com/files/factory.png'  
	});
	
	<?php
		$currDestination = "asdf";
	
		if(
			!strcmp($currDestination, "San Feilpe de Austin, Texas") ||
			!strcmp($currDestination, "San Felipe de Ausitn, Texas") ||
			!strcmp($currDestination, "San Felipe de Austin, Province of Texas") ||
			!strcmp($currDestination, "San Felipe de Austin, Rio Brazos, Province of Texas") ||
			!strcmp($currDestination, "San Felipe de Austin, Texas") ||
			!strcmp($currDestination, "San Felipe de Austin, Texas by way of Alexandria, Louisiana") ||
			!strcmp($currDestination, "San Felipe de Austin, Texas via New Orleans") ||
			!strcmp($currDestination, "San Felipe de Austin, Texas, via Natchitoches, Louisiana") ||
			!strcmp($currDestination, "Ausin's Settlement, Texas") ||
			!strcmp($currDestination, "Austin Settlement, Texas") ||
			!strcmp($currDestination, "Austin's Colony, Brazos, Texas") ||
			!strcmp($currDestination, "Austin's Colony, Province of Texas") ||
			!strcmp($currDestination, "Austin's Colony, Texas") ||
			!strcmp($currDestination, "Austin's Grant, Texas") ||
			!strcmp($currDestination, "Sn Felipe de Austin, Texas, via Natchitoches, Louisiana") ||
			!strcmp($currDestination, "Austin, Texas") ||
			!strcmp($currDestination, "Texas") ||
			!strcmp($currDestination, "Texas via New Orleans") ||
			!strcmp($currDestination, "Department of Texas, United Mexican States") ||
			!strcmp($currDestination, "Province of Texas via Natchitoches, Louisiana") ||
			!strcmp($currDestination, "Province of Texas by way of New Orleans") ||
			!strcmp($currDestination, "Province of Texas") ||
			!strcmp($currDestination, "Rio de los Brazos en la Provincia de Texas") ||
			!strcmp($currDestination, "On the Brazos, Texas") ||
			!strcmp($currDestination, "Brazos, Texas") ||
			!strcmp($currDestination, "Colorado") ||
			!strcmp($currDestination, "Nacogdoches, Texas; San Felipe de Austin, Texas; La Bahia, Texas; San Antonio, Texas.") ||
			!strcmp($currDestination, "Natchitoches, Louisiana, San Felipe de Austin, Texas")
		){
			// San Felipe, TX
			echo "var marker = new google.maps.Marker({\n";
			echo "title: 'San Felipe, TX',\n";
			echo "position: new google.maps.LatLng(29.8019, -96.1014),\n"; 
			echo "map: map\n";
			echo "});\n";
		}

		if(
			!strcmp($currDestination, "San Antonio, Texas") ||
			!strcmp($currDestination, "Bexar, Texas")
		){
			// San Antonio, TX
			echo "var marker = new google.maps.Marker({\n";
			echo "title: 'San Antonio, TX',\n";
			echo "position: new google.maps.LatLng(29.53, -98.47),\n"; 
			echo "map: map\n";
			echo "});\n";
		}

		if(
		!strcmp($currDestination, "Saltillo, Coahuila") ||
		!strcmp($currDestination, "Saltillo,Coahuila") ||
		!strcmp($currDestination, "Leona Vicario") ||
		!strcmp($currDestination, "Leona Vicario or Saltillo, Coahuila") ||
		!strcmp($currDestination, "Leona Vicario, Coahuila")
		){
			// Saltillo, Coahuila, Mexico
			echo "var marker = new google.maps.Marker({\n";
			echo "title: 'Saltillo, Coahuila, Mexico',\n";
			echo "position: new google.maps.LatLng(25.4, -101.0),\n"; 
			echo "map: map\n";
			echo "});\n";
		}
		
		echo "var marker = new google.maps.Marker({\n";
		echo "title: 'Saltillo, Coahuila, Mexico',\n";
		echo "position: new google.maps.LatLng(25.4, -101.0),\n"; 
		echo "map: map\n";
		echo "});\n";
	?>
  }

</script>
</head>
<body onload="initialize()">
  <div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>