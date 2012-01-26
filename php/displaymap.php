<?php
	echo "
		var latlng = new google.maps.LatLng(30.30, -97.70);
		var myOptions = {
		  zoom: 8,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.TERRAIN
		};
		var map = new google.maps.Map(document.getElementById('map_canvas'),
			myOptions);
		
		var myLatLng;
		var bounds = new google.maps.LatLngBounds();\n";
	
	$destinations = $place;
	$origins = $place2;
	//$places[] = "San Felipe de Ausitn, Texas";
	//$places[] = "Cincinnati, Ohio";
	//$places[] = "Saltillo, Coahuila";
	//$places = unserialize(stripslashes($_POST['places']) );
	//$places = str_replace("*", "'", $places);
	
	foreach($origins as $key => $currOrigin){
		echo "myLatLng = new google.maps.LatLng(31.294, -92.459);\n";
		echo "var marker = new google.maps.Marker({\n";
		echo "title: 'ALEXANDRIA, LOUISIANA',\n";
		echo "position: myLatLng,\n"; 

		echo "map: map,\n";
		echo "icon: 'pics/gmap_blue_icon.png'\n";
		echo "});\n";

		echo "bounds.extend(myLatLng)\n";
		echo "map.fitBounds(bounds)\n";
	}
	
	
	/*
if(
!strcmp($currOrigin, "Alexandria, Louisiana")
){
// ALEXANDRIA, LOUISIANA
echo "myLatLng = new google.maps.LatLng(31.294, -92.459);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ALEXANDRIA, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Austin's Colony, District of Colorado, Texas") ||
!strcmp($currOrigin, "Austin's Colony, Texas") ||
!strcmp($currOrigin, "Austin's Texas") ||
!strcmp($currOrigin, "Austin, Texas") ||
!strcmp($currOrigin, "San Feilpe de Austin, Texas") ||
!strcmp($currOrigin, "San Felipe de Austin, Bexar, Texas") ||
!strcmp($currOrigin, "San Felipe de Austin, Texas") ||
!strcmp($currOrigin, "Municipality of Austin, Texas") ||
!strcmp($currOrigin, "Province of Tais") ||
!strcmp($currOrigin, "Texas")
){
// SAN FELIPE, TEXAS
echo "myLatLng = new google.maps.LatLng(29.793, -96.091);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN FELIPE, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Bexar, Texas") ||
!strcmp($currOrigin, "San Antonio, Texas")
){
// SAN ANTONIO, TEXAS
echo "myLatLng = new google.maps.LatLng(29.472, -98.537);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN ANTONIO, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Saltillo, Coahuila") ||
!strcmp($currOrigin, "Saltillo, Texas") ||
!strcmp($currOrigin, "Satillo, Coahuila") ||
!strcmp($currOrigin, "Leona Vicario, Coahuila") ||
!strcmp($currOrigin, "Leaona Vicario, Coahuila")
){
// SALTILLO, COAHUILA, MEXICO
echo "myLatLng = new google.maps.LatLng(25.4167, -101.0);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SALTILLO, COAHUILA, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Little Rock, Arkansas")
){
// LITTLE ROCK, ARKANSAS
echo "myLatLng = new google.maps.LatLng(34.748, -92.276);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LITTLE ROCK, ARKANSAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Gonzales") ||
!strcmp($currOrigin, "Gonzales, Texas") ||
!strcmp($currOrigin, "Gonzalez, Texas")
){
// GONZALES, TEXAS
echo "myLatLng = new google.maps.LatLng(29.510, -97.452);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GONZALES, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Galveston Bay on Board Schooner Angelia, Texas") ||
!strcmp($currOrigin, "Galveston Bay, Texas")
){
// GALVESTON, TEXAS
echo "myLatLng = new google.maps.LatLng(29.297, -94.796);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GALVESTON, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Goliad") ||
!strcmp($currOrigin, "Goliad, Texas")
){
// GOLIAD, TEXAS
echo "myLatLng = new google.maps.LatLng(28.672, -97.389);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GOLIAD, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Vera Cruz, Mexico") ||
!strcmp($currOrigin, "Vera Cruz, Vera Cruz") ||
!strcmp($currOrigin, "Vera Cruz, Veracruz") ||
!strcmp($currOrigin, "Veracruz, Veracruz")
){
// VERACRUZ, MEXICO
echo "myLatLng = new google.maps.LatLng(19.202, -96.137);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'VERACRUZ, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Victoria, Texas") ||
!strcmp($currOrigin, "Guadalupe Victoria") ||
!strcmp($currOrigin, "Guadalupe, Texas") ||
!strcmp($currOrigin, "(DeLeon's) Guadalupe River, Texas")
){
// VICTORIA, TEXAS
echo "myLatLng = new google.maps.LatLng(28.805, -97.013);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'VICTORIA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Philadelphia") ||
!strcmp($currOrigin, "Philadelphia, Pennsylvania") ||
!strcmp($currOrigin, "Philadelphia, Pennsylvannia")
){
// PHILADELPHIA, PENNSYLVANIA
echo "myLatLng = new google.maps.LatLng(40.118, -75.015);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'PHILADELPHIA, PENNSYLVANIA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Potosi, Missouri") ||
!strcmp($currOrigin, "Potosi, Mossouri") ||
!strcmp($currOrigin, "Mine a Breton, Missouri") ||
!strcmp($currOrigin, "Mine a Burton, Missouri")
){
// POTOSI, MISSOURI
echo "myLatLng = new google.maps.LatLng(37.938, -90.785);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'POTOSI, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monterey, Nuevo Leon") ||
!strcmp($currOrigin, "Monterrey, Nuevo Leon") ||
!strcmp($currOrigin, "Monterey, Coahuila")
){
// MONTERREY, MEXICO
echo "myLatLng = new google.maps.LatLng(25.6667, -100.317);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONTERREY, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monticello, Miss")
){
// MONTICELLO, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.557, -90.111);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONTICELLO, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Near Russellville, Logan Country, Kentucky")
){
// RUSSELLVILLE, KENTUCKY
echo "myLatLng = new google.maps.LatLng(36.853, -86.883);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'RUSSELLVILLE, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "New London, Missouri")
){
// NEW LONDON, MISSOURI
echo "myLatLng = new google.maps.LatLng(39.586, -91.398);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NEW LONDON, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Northumberland, Pennsylvania")
){
// NORTHUMBERLAND, PENNSYLVANIA
echo "myLatLng = new google.maps.LatLng(40.894, -76.798);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NORTHUMBERLAND, PENNSYLVANIA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Parish of Lampazzos, State of Leon")
){
// LAMPAZOS DE NARANJO, NUEVO LEON, MEXICO
echo "myLatLng = new google.maps.LatLng(27.0167, -100.517);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LAMPAZOS DE NARANJO, NUEVO LEON, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monroe, Louisiana")
){
// MONROE, LOUISIANA
echo "myLatLng = new google.maps.LatLng(32.522, -92.103);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONROE, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "New Madrid, Missouri") ||
!strcmp($currOrigin, "New Madrid, Missourri")
){
// NEW MADRID, MISSOURI
echo "myLatLng = new google.maps.LatLng(36.590, -89.549);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NEW MADRID, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Montgomery, Alabama")
){
// MONTGOMERY, ALABAMA
echo "myLatLng = new google.maps.LatLng(32.399, -86.326);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONTGOMERY, ALABAMA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monclova, Coahuila") ||
!strcmp($currOrigin, "Moncolva, Coahuila")
){
// MONVCLOVA, COAHUILA, MEXICO
echo "myLatLng = new google.maps.LatLng(26.9, -101.417);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONVCLOVA, COAHUILA, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Woodville") ||
!strcmp($currOrigin, "Woodville, ") ||
!strcmp($currOrigin, "Woodville, Wilkerson County, Mississippi") ||
!strcmp($currOrigin, "Wilkenson County")
){
// WOODVILLE, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.176, -91.353);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WOODVILLE, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Prison of the Acordada, City of Mexico") ||
!strcmp($currOrigin, "Prison of the Acordada, Mexico") ||
!strcmp($currOrigin, "City of Mexico") ||
!strcmp($currOrigin, "En Inquisicion, Mexico") ||
!strcmp($currOrigin, "Exinquisition, Mexico") ||
!strcmp($currOrigin, "Mexico")
){
// MEXICO CITY, MEXICO
echo "myLatLng = new google.maps.LatLng(19.24, -99.09);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MEXICO CITY, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "New Orleans, Louisiana")
){
// NEW ORLEANS, LOUISIANA
echo "myLatLng = new google.maps.LatLng(29.956, -90.079);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NEW ORLEANS, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Matamoras, ") ||
!strcmp($currOrigin, "Matamoras, Tamaulipas") ||
!strcmp($currOrigin, "Matamoras, Tamaulips") ||
!strcmp($currOrigin, "Matamoras, Texxas") ||
!strcmp($currOrigin, "Matamoros, Tamaulipas") ||
!strcmp($currOrigin, "Matamoros, Texas") ||
!strcmp($currOrigin, "Matamoros,Tamaulipas")
){
// MATAMOROS, MEXICO
echo "myLatLng = new google.maps.LatLng(25.7699, -97.5253);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MATAMOROS, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Nachitoches, Louisiana") ||
!strcmp($currOrigin, "Nachitoches, Louisiana/ Aysh Bayou, Texas") ||
!strcmp($currOrigin, "Nachitoches,Louisiana") ||
!strcmp($currOrigin, "Natchitoches, Louisiana") ||
!strcmp($currOrigin, "Natchitoches,Louisiana")
){
// NATCHITOCHES, LOUISIANA
echo "myLatLng = new google.maps.LatLng(31.761, -93.088);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NATCHITOCHES, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Nacodoches, Texas") ||
!strcmp($currOrigin, "Nacogdoches, Texas") ||
!strcmp($currOrigin, "Nacogdoches, Texas and Saltillo, Coahuila")
){
// NACOGDOCHES, TEXAS
echo "myLatLng = new google.maps.LatLng(31.609, -94.651);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NACOGDOCHES, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Nashville, Tennessee")
){
// NASHVILLE, TENNESSEE
echo "myLatLng = new google.maps.LatLng(36.166, -86.780);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NASHVILLE, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Natchez, Mississippi") ||
!strcmp($currOrigin, "Natchez,Mississippi")
){
// NATCHEZ, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.542, -91.365);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NATCHEZ, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Augusta, Maine")
){
// AUGUSTA, MAINE
echo "myLatLng = new google.maps.LatLng(44.354, -69.731);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'AUGUSTA, MAINE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Matagorda, Texas")
){
// MATAGORDA, TEXAS
echo "myLatLng = new google.maps.LatLng(28.696, -95.971);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MATAGORDA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Memphis, Tennessee")
){
// MEMPHIS, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.068, -89.958);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MEMPHIS, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

/*
else if(
!strcmp($currOrigin, "Detroit, Michigan")
){
// DETROIT, MICHIGAN
echo "myLatLng = new google.maps.LatLng(42.346, -83.061);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'DETROIT, MICHIGAN',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// HAVANA, CUBA
echo "myLatLng = new google.maps.LatLng(42.346, -83.061);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HAVANA, CUBA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// Havana,
echo "myLatLng = new google.maps.LatLng(42.346, -83.061);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'Havana,',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Lower Settlement Brazaos, Texas") ||
!strcmp($currOrigin, "Lower Settlement Brazos, Texas") ||
!strcmp($currOrigin, "Lower Settlement, Texas") ||
!strcmp($currOrigin, "Brasoria, Texas") ||
!strcmp($currOrigin, "Brazoria, Texas") ||
!strcmp($currOrigin, "Brasos, Texas") ||
!strcmp($currOrigin, "Bravo, Texas") ||
!strcmp($currOrigin, "Brazos, Texas") ||
!strcmp($currOrigin, "Peach Point, Texas") ||
!strcmp($currOrigin, "Schooner Gerneral Santa Anna, Brazoria, Texas")
){
// BRAZORIA, TEXAS
echo "myLatLng = new google.maps.LatLng(29.043, -95.567);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BRAZORIA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "La Vaca, Texas") ||
!strcmp($currOrigin, "Labaca River, Texas") ||
!strcmp($currOrigin, "Labaca Station, Texas") ||
!strcmp($currOrigin, "Labacca, Texas") ||
!strcmp($currOrigin, "LaVaca, Texas") ||
!strcmp($currOrigin, "Rio La Vaca, Texas") ||
!strcmp($currOrigin, "Rio Labaca, Texas") ||
!strcmp($currOrigin, "Lavaca River, Texas") ||
!strcmp($currOrigin, "Station at Labaca, Texas")
){
// VANDERBILT, TEXAS
echo "myLatLng = new google.maps.LatLng(29.010, -96.684);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'VANDERBILT, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Perry County, Missouri")
){
// PERRYVILLE, MISSOURI
echo "myLatLng = new google.maps.LatLng(37.557, -89.843);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'PERRYVILLE, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// JESUP, LOUISIANA
echo "myLatLng = new google.maps.LatLng(37.557, -89.843);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'JESUP, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Contonement, Jesup")
){
// Cantonment, Jessup, Louisiana
echo "myLatLng = new google.maps.LatLng(31.6129491, -93.4029513);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'Cantonment, Jessup, Louisiana',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Charleston, SC")
){
// CHARLESTON, SOUTH CAROLINA
echo "myLatLng = new google.maps.LatLng(32.779, -79.940);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CHARLESTON, SOUTH CAROLINA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Chillicothe, Ohio")
){
// CHILLICOTHE, OHIO
echo "myLatLng = new google.maps.LatLng(39.328, -82.983);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CHILLICOTHE, OHIO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Cincinnati, Ohio")
){
// CINCINNATI, OHIO
echo "myLatLng = new google.maps.LatLng(39.109, -84.501);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CINCINNATI, OHIO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// JEFFERSON, MISSOURI
echo "myLatLng = new google.maps.LatLng(39.109, -84.501);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'JEFFERSON, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// City of Jefferson,Missouri
echo "myLatLng = new google.maps.LatLng(39.109, -84.501);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'City of Jefferson,Missouri',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Clinton") ||
!strcmp($currOrigin, "Clinton, Louisiana")
){
// CLINTON, LOUISIANA
echo "myLatLng = new google.maps.LatLng(30.710, -90.897);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CLINTON, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// VELASCO, TEXAS
echo "myLatLng = new google.maps.LatLng(30.710, -90.897);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'VELASCO, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// Velasco, Texas
echo "myLatLng = new google.maps.LatLng(30.710, -90.897);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'Velasco, Texas',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "New York, New York") ||
!strcmp($currOrigin, "New York, New York.")
){
// NEW YORK, NEW YORK
echo "myLatLng = new google.maps.LatLng(40.752, -73.995);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NEW YORK, NEW YORK',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Col. Groces, Texas") ||
!strcmp($currOrigin, "Bernard, Texas") ||
!strcmp($currOrigin, "Bernardo, Texas")
){
// BERNARDO, TEXAS [4 miles south of Hempstead, Texas on FM 1887]
echo "myLatLng = new google.maps.LatLng(29.741, -96.382);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BERNARDO, TEXAS [4 miles south of Hempstead, Texas on FM 1887]',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Pinckney Ville, ") ||
!strcmp($currOrigin, "Pinckneyville, Mississippi")
){
// PINCKNEYVILLE, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.176, -91.353);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'PINCKNEYVILLE, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Tuscumbia, Alabama") ||
!strcmp($currOrigin, "Tuscumbia, Franklin County, North Alabama") ||
!strcmp($currOrigin, "Alabama") ||
!strcmp($currOrigin, "Franklin County, Alabama")
){
// TUSCUMBIA, ALABAMA
echo "myLatLng = new google.maps.LatLng(34.725, -87.698);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'TUSCUMBIA, ALABAMA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Winchester, ") ||
!strcmp($currOrigin, "Winchester, Kentucky")
){
// WINCHESTER, KENTUCKY
echo "myLatLng = new google.maps.LatLng(37.980, -84.169);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WINCHESTER, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Lexington, Kentucky")
){
// LEXINGTON, KENTUCKY
echo "myLatLng = new google.maps.LatLng(38.002, -84.480);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LEXINGTON, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// LINCOLN, TENNESSEE
echo "myLatLng = new google.maps.LatLng(38.002, -84.480);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LINCOLN, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
){
// Lincoln City, Tennessee
echo "myLatLng = new google.maps.LatLng(38.002, -84.480);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'Lincoln City, Tennessee',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Lincoln County, Missouri")
){
// LINCOLN COUNTY, MISSOURI [COULD USE TROY, MO, IF NECESSARY]
echo "myLatLng = new google.maps.LatLng(38.981, -91.007);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LINCOLN COUNTY, MISSOURI [COULD USE TROY, MO, IF NECESSARY]',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Louisville, Kentucky.")
){
// LOUISVILLE, KENTUCKY
echo "myLatLng = new google.maps.LatLng(38.247, -85.777);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LOUISVILLE, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "McNeels Landing, Texas") ||
!strcmp($currOrigin, "Bells") ||
!strcmp($currOrigin, "Bells Landing, Texas") ||
!strcmp($currOrigin, "Bells [House], Texas") ||
!strcmp($currOrigin, "Bells,")
){
// MARION, TEXAS
echo "myLatLng = new google.maps.LatLng(29.574, -98.151);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MARION, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Ais Bayou, Texas")
){
// AYISH BAYOU, TEXAS
echo "myLatLng = new google.maps.LatLng(31.529, -94.111);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'AYISH BAYOU, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Anahuac, Texas")
){
// ANAHUAC, TEXAS
echo "myLatLng = new google.maps.LatLng(29.754, -94.566);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ANAHUAC, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Attacapas,Louisiana")
){
// SAINT MARTINVILLE, LOUISIANA
echo "myLatLng = new google.maps.LatLng(30.140, -91.823);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAINT MARTINVILLE, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Attica, Seneca County, Ohio")
){
// ATTICA, OHIO
echo "myLatLng = new google.maps.LatLng(41.073, -82.891);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ATTICA, OHIO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Ayish Bayou, Texas") ||
!strcmp($currOrigin, "Ayish District, Texas") ||
!strcmp($currOrigin, "Aysh Bayou, Texas") ||
!strcmp($currOrigin, "Headquarters, Sprowls plantation")
){
// SAN AUGUSTINE COUNTY, TEXAS
echo "myLatLng = new google.maps.LatLng(31.529, -94.111);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN AUGUSTINE COUNTY, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Bahia, Texas")
){
// GOLIAD, TEXAS
echo "myLatLng = new google.maps.LatLng(28.672, -97.389);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GOLIAD, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Bardstown, Kentucky")
){
// BARDSTOWN, KENTUCKY
echo "myLatLng = new google.maps.LatLng(37.814, -85.466);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BARDSTOWN, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Bonneville, Missouri")
){
// BOONVILLE, MISSOURI
echo "myLatLng = new google.maps.LatLng(38.965, -92.743);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BOONVILLE, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Brownsville, Tennessee")
){
// BROWNSVILLE, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.597, -89.271);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BROWNSVILLE, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Buffalo Bayou, Texas")
){
// HOUSTON, TEXAS
echo "myLatLng = new google.maps.LatLng(29.720, -95.222);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HOUSTON, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Camargo") ||
!strcmp($currOrigin, "Comargo")
){
// CAMARGO, SONORA, MEXICO
echo "myLatLng = new google.maps.LatLng(27.6667, -105.167);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CAMARGO, SONORA, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Caney Creek, Texas") ||
!strcmp($currOrigin, "Caney,") ||
!strcmp($currOrigin, "Cany")
){
// CANEY CREEK, TEXAS [Google brings up location of the river, north of Houston about halfway to Huntsville] 
echo "myLatLng = new google.maps.LatLng(29.3496912, -96.2085735);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CANEY CREEK, TEXAS [Google brings up location of the river, north of Houston about halfway to Huntsville] ',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Cedar Lake, Texas")
){
// CEDAR LAKE, TEXAS
echo "myLatLng = new google.maps.LatLng(28.964, -95.948);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CEDAR LAKE, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Cerralvo, Mexico")
){
// CERRALVO MUNICIPALITY, NUEVO LEON, MEXICO
echo "myLatLng = new google.maps.LatLng(25.9843, -99.7092);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'CERRALVO MUNICIPALITY, NUEVO LEON, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Columbia, Tennessee")
){
// COLUMBIA, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.610, -87.049);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'COLUMBIA, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Danville, Kentucky")
){
// DANVILLE, KENTUCKY
echo "myLatLng = new google.maps.LatLng(37.640, -84.782);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'DANVILLE, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Davis Point, ") ||
!strcmp($currOrigin, "Davis Point, Galveston Bay, Texas") ||
!strcmp($currOrigin, "Hall's Bayou") ||
!strcmp($currOrigin, "Halls Bayou, Texas")
){
// GALVESTON, TEXAS
echo "myLatLng = new google.maps.LatLng(29.297, s-94.796);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GALVESTON, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Demascus, Henry County, Ohio")
){
// DAMASCUS, OHIO
echo "myLatLng = new google.maps.LatLng(40.898, -80.958);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'DAMASCUS, OHIO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "District of Atascosito, Texas") ||
!strcmp($currOrigin, "Atascaceto District, Texas") ||
!strcmp($currOrigin, "Atascosito District, Texas") ||
!strcmp($currOrigin, "Atascosito, Texas")
){
// ATASCOCITA, TEXAS
echo "myLatLng = new google.maps.LatLng(29.992, -95.182);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ATASCOCITA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "District of San Jacinto, Texas") ||
!strcmp($currOrigin, "San Jacinto Bay, Texas") ||
!strcmp($currOrigin, "San Jacinto, Texas")
){
// SAN JACINTO, TEXAS
echo "myLatLng = new google.maps.LatLng(30.521868, -95.295218);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN JACINTO, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "District of Tenaha, Texas") ||
!strcmp($currOrigin, "District of Teneha, Texas")
){
// TENAHA, TEXAS
echo "myLatLng = new google.maps.LatLng(31.940, -94.255);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'TENAHA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "District of Victoria, Texas")
){
// VICTORIA, TEXAS
echo "myLatLng = new google.maps.LatLng(28.805, -97.013);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'VICTORIA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Dover, New Hampshire")
){
// DOVER, NEW HAMPSHIRE
echo "myLatLng = new google.maps.LatLng(43.190, -70.894);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'DOVER, NEW HAMPSHIRE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Eatenton, Putnam County, Georgia")
){
// EATONTON, GEORGIA
echo "myLatLng = new google.maps.LatLng(33.327, -83.346);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'EATONTON, GEORGIA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Essextown, New York")
){
// ESSEX, NEW YORK
echo "myLatLng = new google.maps.LatLng(44.262, -73.374);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ESSEX, NEW YORK',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Mouth of Brazos, Texas") ||
!strcmp($currOrigin, "Mouth of the Brazos River, Texas")
){
// QUINTANA, TEXAS
echo "myLatLng = new google.maps.LatLng(28.973, -95.340);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'QUINTANA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Frankfort, Kentucky")
){
// FRANKFORT, KENTUCKY
echo "myLatLng = new google.maps.LatLng(38.200, -84.948);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'FRANKFORT, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Franklin, Louisiana")
){
// FRANKLIN, LOUISIANA
echo "myLatLng = new google.maps.LatLng(29.796, -91.507);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'FRANKLIN, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Fredericktown, Maddison Country, Missouri") ||
!strcmp($currOrigin, "Fredericktown, Missouri")
){
// FREDERICKTOWN, MISSOURI
echo "myLatLng = new google.maps.LatLng(37.560, -90.293);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'FREDERICKTOWN, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Gallatin County, Kentucky")
){
// WARSAW, KENTUCKY
echo "myLatLng = new google.maps.LatLng(38.785, -84.903);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WARSAW, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Gallatin, Tennessee")
){
// GALLATIN, TENNESSEE
echo "myLatLng = new google.maps.LatLng(36.390, -86.454);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GALLATIN, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Harrisburg, Buffalo Bayou, Texas") ||
!strcmp($currOrigin, "Harrisburg, Texas") ||
!strcmp($currOrigin, "Harrisburgh, Texas")
){
// HARRISBURG, TEXAS [EAST PART OF HOUSTON]
echo "myLatLng = new google.maps.LatLng(30.926, -94.005);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HARRISBURG, TEXAS [EAST PART OF HOUSTON]',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Hempstead County Arkansas") ||
!strcmp($currOrigin, "Hemstead County, Territory of Arkansas")
){
// HOPE, ARKANSAS
echo "myLatLng = new google.maps.LatLng(33.662, -93.589);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HOPE, ARKANSAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Henderson County, Tennessee")
){
// LEXINGTON, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.659, -88.401);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LEXINGTON, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Herculaneum, Missouri")
){
// HERCULANEUM, MISSOURI
echo "myLatLng = new google.maps.LatLng(38.266, -90.384);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HERCULANEUM, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Home")
){
// OYSTER CREEK, TEXAS
echo "myLatLng = new google.maps.LatLng(28.973, -95.340);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'OYSTER CREEK, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Home of Thomas M Duke,") ||
!strcmp($currOrigin, "Duke's Place, Texas")
){
// MATAGORDA, TEXAS
echo "myLatLng = new google.maps.LatLng(28.696, -95.971);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MATAGORDA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Hopkinsville, Kentucky")
){
// HOPKINSVILLE, KENTUCKY
echo "myLatLng = new google.maps.LatLng(36.868, -87.480);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'HOPKINSVILLE, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Jackson, Louisiana")
){
// JACKSON, LOUISIANA
echo "myLatLng = new google.maps.LatLng(30.778, -91.210);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'JACKSON, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Jefferson Barracks near St Louis Missouri") ||
!strcmp($currOrigin, "St. Louis, Missouri")
){
// ST. LOUIS, MISSOURI
echo "myLatLng = new google.maps.LatLng(38.632, -90.191);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ST. LOUIS, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Jessamine County, Kentucky")
){
// NICHOLASVILLE, KENTUCKY
echo "myLatLng = new google.maps.LatLng(37.878, -84.578);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NICHOLASVILLE, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Lafayette County, ")
){
// FAYETTEVILLE, ARKANSAS
echo "myLatLng = new google.maps.LatLng(36.067, -94.155);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'FAYETTEVILLE, ARKANSAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Laredo, Tamaulipas") ||
!strcmp($currOrigin, "Rio Grande") ||
!strcmp($currOrigin, "Rio Grande, Texas")
){
// NUEVO LAREDO, MEXICO
echo "myLatLng = new google.maps.LatLng(27.5, -99.5167);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NUEVO LAREDO, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Milford, Pennsylvania")
){
// MILFORD, PENNSYLVANIA
echo "myLatLng = new google.maps.LatLng(41.326, -74.802);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MILFORD, PENNSYLVANIA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Mill Creek,") ||
!strcmp($currOrigin, "Mill Creek, Texas")
){
// WASHINGTON COUNTY, TEXAS [COULD USE BRENHAM, TX, IF NECESSARY]
echo "myLatLng = new google.maps.LatLng(30.162, -96.396);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WASHINGTON COUNTY, TEXAS [COULD USE BRENHAM, TX, IF NECESSARY]',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Miller City, Arkansas")
){
// MILLER, MISSOURI
echo "myLatLng = new google.maps.LatLng(37.231, -93.850);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MILLER, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Miller County, Arkansas Territory")
){
// TEXARKANA, TEXAS
echo "myLatLng = new google.maps.LatLng(33.424, -94.102);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'TEXARKANA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monroe County, Mississippi")
){
// ABERDEEN, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(33.827, -88.555);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ABERDEEN, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Monroe, Mississippi")
){
// MONROE, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.405, -90.844);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'MONROE, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Port Gibson")
){
// PORT GIBSON, MISSISSIPPI
echo "myLatLng = new google.maps.LatLng(31.958, -90.982);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'PORT GIBSON, MISSISSIPPI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Randolph, Tennessee")
){
// RANDOLPH, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.561, -89.838);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'RANDOLPH, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Rapides Parish, Louisiana")
){
// ALEXANDRIA, LOUISIANA
echo "myLatLng = new google.maps.LatLng(31.294, -92.459);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ALEXANDRIA, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Red River Rapide, Louisiana")
){
// RAPIDES, LOUISIANA
echo "myLatLng = new google.maps.LatLng(31.318, -92.683);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'RAPIDES, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Reynosa, Tamaulipas")
){
// REYNOSA, TAMAULIPAS, MEXICO
echo "myLatLng = new google.maps.LatLng(26.0833, -98.2833 );\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'REYNOSA, TAMAULIPAS, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Ripley, Brown County, Ohio")
){
// RIPLEY, OHIO
echo "myLatLng = new google.maps.LatLng(38.762, -83.813);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'RIPLEY, OHIO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Rushville, Illinois") ||
!strcmp($currOrigin, "Rushville, Schuyler Co, Illinois")
){
// RUSHVILLE, ILLINOIS
echo "myLatLng = new google.maps.LatLng(40.122, -90.561);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'RUSHVILLE, ILLINOIS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "San Carlos, Coahuila")
){
// SAN CARLOS, COAHUILA, MEXICO
echo "myLatLng = new google.maps.LatLng(26.4166667, -101.3333333);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN CARLOS, COAHUILA, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "San Carlos, Tamaulipas")
){
// SAN CARLOS, TAMULIPAS, MEXICO
echo "myLatLng = new google.maps.LatLng(26.1, -98.4166667);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN CARLOS, TAMULIPAS, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "San Luis Potosi, Mexico")
){
// SAN LUIS POTOSI, MEXICO
echo "myLatLng = new google.maps.LatLng(19.283333, -99.65);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SAN LUIS POTOSI, MEXICO',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Scotia, Pope County, Arkansas Territory")
){
// SCOTIA, ARKANSAS
echo "myLatLng = new google.maps.LatLng(35.3311955, -93.2924011);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SCOTIA, ARKANSAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Shelbyville, Tennessee")
){
// SHELBYVILLE, TENNESSEE
echo "myLatLng = new google.maps.LatLng(35.476, -86.467);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SHELBYVILLE, TENNESSEE',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "St Albans, Vermont")
){
// ST. ALBANS, VERMONT
echo "myLatLng = new google.maps.LatLng(44.815, -73.077);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ST. ALBANS, VERMONT',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "St Genevieve, Missouri")
){
// STE. GENEVIEVE, MISSOURI
echo "myLatLng = new google.maps.LatLng(37.912590, -90.151554);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'STE. GENEVIEVE, MISSOURI',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "St. Mary's Parish, Louisiana")
){
// ST. MARY PARISH, LOUISIANA
echo "myLatLng = new google.maps.LatLng(29.64528, -91.39389);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'ST. MARY PARISH, LOUISIANA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Washington, Hempstead County, Arkansas Territory")
){
// WASHINGTON, ARKANSAS
echo "myLatLng = new google.maps.LatLng(33.750, -93.668);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WASHINGTON, ARKANSAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Washington, KY")
){
// WASHINGTON, KENTUCKY
echo "myLatLng = new google.maps.LatLng(38.616, -83.806);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'WASHINGTON, KENTUCKY',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Wigginsville, Clarke Co, Alabama")
){
// GROVE HILL, ALABAMA
echo "myLatLng = new google.maps.LatLng(31.706, -87.780);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'GROVE HILL, ALABAMA',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Sabine, ") ||
!strcmp($currOrigin, "Sabine, Texas")
){
// SABINE RIVER, TEXAS
echo "myLatLng = new google.maps.LatLng(29.895, -93.956);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'SABINE RIVER, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Groces Retreat, Texas")
){
// NAVASOTA, TEXAS
echo "myLatLng = new google.maps.LatLng(30.389, -96.085);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NAVASOTA, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Trinity Bay, Texas")
){
// BEACH CITY, TEXAS
echo "myLatLng = new google.maps.LatLng(29.724, -94.919);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'BEACH CITY, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Trinity Atoscosito, Texas") ||
!strcmp($currOrigin, "Trinity River, Texas") ||
!strcmp($currOrigin, "Trinity, Taskasito, ") ||
!strcmp($currOrigin, "Trinity, Texas")
){
// LIVINGSTON, TEXAS
echo "myLatLng = new google.maps.LatLng(30.705, -94.938);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LIVINGSTON, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Pine Bluff Trinity, Texas") ||
!strcmp($currOrigin, "Pine Bluff, Texas")
){
// LIBERTY, TEXAS
echo "myLatLng = new google.maps.LatLng(30.038, -94.746);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'LIBERTY, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Colorado District, Texas") ||
!strcmp($currOrigin, "Colorado River, Texas") ||
!strcmp($currOrigin, "Colorado, Texas")
){
// COLUMBUS, TEXAS
echo "myLatLng = new google.maps.LatLng(29.708, -96.547);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'COLUMBUS, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Coles Settlement, Texas") ||
!strcmp($currOrigin, "Coles' Settlement, Texas")
){
// INDEPENDENCE, TEXAS
echo "myLatLng = new google.maps.LatLng(30.162, -96.396);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'INDEPENDENCE, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

else if(
!strcmp($currOrigin, "Navidad, Texas")
){
// NAVIDAD RIVER, TEXAS
echo "myLatLng = new google.maps.LatLng(29.0655358, -96.7691462);\n";
echo "var marker = new google.maps.Marker({\n";
echo "title: 'NAVIDAD RIVER, TEXAS',\n";
echo "position: myLatLng,\n"; 
echo "map: map,\n";
echo "icon: 'pics/gmap_blue_icon.png'\n";
echo "});\n";
echo "bounds.extend(myLatLng)\n";
echo "map.fitBounds(bounds)\n";
}

	}
	
	foreach ($destinations as $key => $currDestination) {

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

		else if(
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

		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
		!strcmp($currDestination, "Brazoria, Texas") ||
		!strcmp($currDestination, "Lower Settlement, Texas")
		){
			// Brazoria, TX
			echo "myLatLng = new google.maps.LatLng(29.023642, -95.586683);\n";
			echo "var marker = new google.maps.Marker({\n";
			echo "title: 'Brazoria, TX',\n";
			echo "position: myLatLng,\n"; 
			echo "map: map\n";
			echo "});\n";
			echo "bounds.extend(myLatLng)\n";
			echo "map.fitBounds(bounds)\n";
		}
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
		
		else if(
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
	
*/
?>
