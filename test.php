<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="header.css" />
	<link rel="stylesheet" type="text/css" href="footer.css" />
	<link rel="stylesheet" type="text/css" href="idea.css" />

<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  <!-- html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 20% } -->
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
		
	/*
	var marker = new google.maps.Marker({  
	  position: latlng,  
	  map: map,  
	  title: 'My workplace',  
	  clickable: false,  
	  icon: 'http://google-maps-icons.googlecode.com/files/factory.png'  
	});
	*/
	
	var myLatLng;
	var bounds = new google.maps.LatLngBounds();
	
	<?php
		$places = unserialize(stripslashes($_POST['places']) );
		$places = str_replace("*", "'", $places);
		
		foreach ($places as $key => $currDestination) {
	
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
				echo "myLatLng = new google.maps.LatLng(29.8019, -96.1014);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'San Felipe, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}

			if(
				!strcmp($currDestination, "San Antonio, Texas") ||
				!strcmp($currDestination, "Bexar, Texas")
			){
				// San Antonio, TX
				echo "myLatLng = new google.maps.LatLng(29.53, -98.47);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'San Antonio, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}

			if(
			!strcmp($currDestination, "Saltillo, Coahuila") ||
			!strcmp($currDestination, "Saltillo,Coahuila") ||
			!strcmp($currDestination, "Leona Vicario") ||
			!strcmp($currDestination, "Leona Vicario or Saltillo, Coahuila") ||
			!strcmp($currDestination, "Leona Vicario, Coahuila")
			){
				// Saltillo, Coahuila, Mexico
				echo "myLatLng = new google.maps.LatLng(25.4, -101.0);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Saltillo, Coahuila, Mexico',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Nacogdoches, Texas")
			){
				// Nacogdoches, Texas
				echo "var myLatLng = new google.maps.LatLng(31.61, -94.63);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Nacogdoches, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Nachitoches, Louisiana") ||
			!strcmp($currDestination, "Natchitoces, Louisiana") ||
			!strcmp($currDestination, "Natchitoches, Louisiana")
			){
				// Natchitoches, LA
				echo "myLatLng = new google.maps.LatLng(31.760, -93.086);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Natchitoches, LA',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Philadelphia, Pennslyvania") ||
			!strcmp($currDestination, "Philadelphia, Pennsylvania") ||
			!strcmp($currDestination, "Philadelphia, Pennsylvannia")
			){
				// Philadelphia, PA
				echo "myLatLng = new google.maps.LatLng(39.88, -75.25);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Philadelphia, PA',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Cincinnati, Ohio")
			){
				// Cincinnati, OH
				echo "myLatLng = new google.maps.LatLng(39.05, -84.67);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Cincinnati, OH',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Natchez, Mississippi")
			){
				// Natchez, MS
				echo "myLatLng = new google.maps.LatLng(31.62, -91.25);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Natchez, MS',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "New Orleans, Louisiana")
			){
				// New Orleans, LA
				echo "myLatLng = new google.maps.LatLng(29.98, -90.25);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'New Orleans, LA',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Pittsburg, Pennsylvania")
			){
				// Pittsburg, PA
				echo "myLatLng = new google.maps.LatLng(40.50, -80.22);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Pittsburg, PA',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "San Jacinto, Texas")
			){
				// San Jacinto, TX
				echo "myLatLng = new google.maps.LatLng(30.598679, -95.141631);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'San Jacinto, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Mine a Burton, Missouri") ||
			!strcmp($currDestination, "Mine a Burton, Wanshington County, Missouri") ||
			!strcmp($currDestination, "Potosi, Missouri") ||
			!strcmp($currDestination, "Potosi, Washington County, Missouri") ||
			!strcmp($currDestination, "Potosi, Washington County, State of Missouri")
			){
				// Potosi, MO
				echo "myLatLng = new google.maps.LatLng(37.94, -90.77);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Potosi, MO',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Matamoras, Tamaulipas") ||
			!strcmp($currDestination, "Matamoros, Texas")
			){
				// Matamoros, Mexico
				echo "myLatLng = new google.maps.LatLng(25.7703, -97.5269);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Matamoros, Mexico',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Matagorda") ||
			!strcmp($currDestination, "Matagorda, Texas")
			){
				// Matagorda, TX
				echo "myLatLng = new google.maps.LatLng(28.695229, -95.967832);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Matagorda, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "San Martinville, Attakapas, Louisiana")
			){
				// St Martinville, LA
				echo "myLatLng = new google.maps.LatLng(30.13, -91.82);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'St Martinville, LA',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Brazoria, Texas") ||
			!strcmp($currDestination, "Lower Settlement, Texas")
			){
				// Brazoria, TX
				echo "myLatLng = new google.maps.LatLng(29.023642,-95.586683);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Brazoria, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Ayish Bayou, Texas")
			){
				// Ayish Bayou, TX
				echo "myLatLng = new google.maps.LatLng(31.126, -94.062);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Ayish Bayou, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Hazelwood, Missouri")
			){
				// Hazelwood, MO
				echo "myLatLng = new google.maps.LatLng(38.78, -90.37);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Hazelwood, MO',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Velasco, Texas")
			){
				// Velasco, TX
				echo "myLatLng = new google.maps.LatLng(28.9619144, -95.360495);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Velasco, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Pleasant Byou, Texas")
			){
				// Pleasant Bayou, TX
				echo "myLatLng = new google.maps.LatLng(29.2655169, -95.2324323);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Pleasant Bayou, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Chocolate Bayou, Texas")
			){
				// Chocolate Bayou, TX
				echo "myLatLng = new google.maps.LatLng(29.315, -95.254);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Chocolate Bayou, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Buffalo Bayou, Texas")
			){
				// Houston, TX
				echo "myLatLng = new google.maps.LatLng(29.97, -95.35);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Houston, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Peach Point, Texas")
			){
				// Freeport, TX
				echo "myLatLng = new google.maps.LatLng(28.95, -95.36);\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Freeport, TX',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
			
			if(
			!strcmp($currDestination, "Mexico")
			){
				// Mexico City, Mexico
				echo "myLatLng = new google.maps.LatLng(19.24, -99.09 ;\n";
				echo "var marker = new google.maps.Marker({\n";
				echo "title: 'Mexico City, Mexico',\n";
				echo "position: myLatLng,\n"; 
				echo "map: map\n";
				echo "});\n";
				echo "bounds.extend(myLatLng)\n";
				echo "map.fitBounds(bounds)\n";
			}
		}
	?>
  }

</script>
</head>
<body onload="initialize()">
	<div id = "wrapper" class="shadow">
		<div id = "header">
			<?php include('header.php'); ?>
		</div>
		<div id="map_canvas" style="width:1000px; height:500px"></div>
		<div id = "footer">
			<?php include('footer.php'); ?>
		</div>
	</div>
</body>
</html>