<?
	include 'php/database.php';
	include('php/wordcloud.class.php');
	$database = connectToDB();
	$connection = $database;

	$totalSql = "SELECT COUNT(*) FROM Document";
	$totalResult = mysqli_query($GLOBALS["___mysqli_ston"], $totalSql);
	$totalDocs = mysqli_result($totalResult, 0);
	logString($totalDocs);

	// Get total document counts per year
	$docDistSql = "SELECT YEAR(creation) doc_year, COUNT(*) doc_total FROM Document GROUP BY doc_year ORDER BY doc_year";
	$docDistResult = mysqli_query($GLOBALS["___mysqli_ston"], $docDistSql);
	$docDistDocs = array();
	while($docDistRow = $docDistResult->fetch_assoc()) {
	    $docDistDocs[$docDistRow['doc_year']] = $docDistRow['doc_total'];		
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>Stephen F. Austin - Digital Collection</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

<!-- JQuery and JQuery UI -->
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.3.min.js"></script>

<!-- Maps API 
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCld462mkpAZrPllmHK8eJGXenW5Kus7g0&sensor=false"></script>-->

<!-- Word Cloud -->
<link rel="stylesheet" type="text/css" href="jqcloud/jqcloud.css" />
<script type="text/javascript" src="jqcloud/jqcloud-1.0.0.js"></script>

<!-- Google AJAX API -->
<script type="text/javascript" src="https://www.google.com/jsapi?key=AIzaSyCld462mkpAZrPllmHK8eJGXenW5Kus7g0"></script>

<!-- Custom JavaScript -->
<script type="text/javascript">
	// Global data
	var basicData = null;
	var cloudData = null;
	var cityData = null;
	var sortKey = 'date';
	var totalDocsCount = <?=$totalDocs?>;
	var totalDocDistribution = <?=json_encode($docDistDocs)?>;

	/*  Changes the content to match the current query

		The content is immediately changed to a loading screen,
		then an asychronous data request is sent to the server.  When the data
		is received, the current view is reloaded.

		This function returns false so that it cancels the form submission
	*/
	function queryChanged() {
		// Invalidate all data that was for the old query parameters
		basicData = null;
		cloudData = null;
		cityData = null;

		// Request data for the current view
		requestData();

		// Don't navigate away from this page
		return false;
	}

	// Start data requests for the current view if necessary.
	function requestData() {
		console.log("RequestData called");
		var waitingForData = false;
		// document.getElementById('content').innerHTML = "Loading...";

		// Build the GET params
		var getParams = '?query=';
		getParams += encodeURIComponent(document.getElementById('query').value);
		// getParams += '&location=';
		// getParams += encodeURIComponent(document.getElementById('location').value);
		getParams += '&fromYear=';
		getParams += encodeURIComponent(document.getElementById('fromYear').value);
		getParams += '&toYear=';
		getParams += encodeURIComponent(document.getElementById('toYear').value);
		getParams += '&fromPersonId=';
		getParams += encodeURIComponent(document.getElementById('fromPersonId').value);
		getParams += '&toPersonId=';
		getParams += encodeURIComponent(document.getElementById('toPersonId').value);
		getParams += '&fromPlaceId=';
		getParams += encodeURIComponent(document.getElementById('fromPlaceId').value);
		getParams += '&toPlaceId=';
		getParams += encodeURIComponent(document.getElementById('toPlaceId').value);
		getParams += '&sort=';
		getParams += encodeURIComponent(sortKey);


		var humanQueryString = "";
		if(document.getElementById('query').value) {
			humanQueryString = "text: <b>"+document.getElementById('query').value+"</b>";
		}
		if(document.getElementById('fromYear').value) {
			humanQueryString += " starting: <b>"+document.getElementById('fromYear').value+"</b>";
		}
		if(document.getElementById('toYear').value) {
			humanQueryString += " ending: <b>"+document.getElementById('toYear').value+"</b>";
		}
		if(document.getElementById('fromPersonId').value) {
			var personId = document.getElementById('fromPersonId').value;
			humanQueryString += " sender: <b>"+personIdToNames[personId]+"</b>";
		}
		if(document.getElementById('toPersonId').value) {
			var personId = document.getElementById('toPersonId').value;
			humanQueryString += " recipient: <b>"+personIdToNames[personId]+"</b>";
		}
		if(document.getElementById('fromPlaceId').value) {
			var placeId = document.getElementById('fromPlaceId').value;
			humanQueryString += " sent from: <b>"+placeIdToNames[placeId]+"</b>";
		}
		if(document.getElementById('toPlaceId').value) {
			var placeId = document.getElementById('toPlaceId').value;
			humanQueryString += " sent to: <b>"+placeIdToNames[placeId]+"</b>";
		}
		document.getElementById('humanQuery').innerHTML = humanQueryString;


		// Request new basic search data
		if (true) {
			var url = 'data/search.php';
			$.getJSON(url + getParams, function(json) {
				$(document).trigger("basicDataLoaded", [json]);
			});
			waitingForData = true;
		}

		// Request new cloud data only if it's being listened for
		if (true) {
			var url = 'data/cloud.php';
			$.getJSON(url + getParams, function(json) {
				$(document).trigger("cloudDataLoaded", [json]);
			});
			waitingForData = true;
		}

		// Request new city data only if it's being listened for
		if (true) {
			var url = 'data/cities.php';
			$.getJSON(url + getParams, function(json) {
				$(document).trigger("cityDataLoaded", [json]);
			});
			waitingForData = true;
		}

		// Request new network
		if (true) {
			var url = 'data/network.php';
			$.getJSON(url + getParams, function(json) {
				$(document).trigger("networkDataLoaded", [json]);
			});
			waitingForData = true;
		}
		return waitingForData;
	}
</script>
</head>
<body onload="queryChanged()">
<div id="wrapper" class="shadow">
	
<div id = "header">
	<?php include('header.php'); ?>
</div>

<div id="form">
	<h1>Search the Austin Papers</h1>
	<form onsubmit='return queryChanged()'>
		<?php include('php/advancedForm.php'); ?>
	</form>
	<p>Query for <span id="humanQuery">no query terms</span></p>
	<p>Showing <span id="resultsCount">0</span> results out of <span id="totalDocsCount">?</span> total documents.</p>
</div>

		<h2>View search results by:</h2>



<div id="tabs">
	<ul style="display:block">
		<li><a href="#tab-documents">Documents</a></li>
		<li><a href="#tab-timeline">Timeline</a></li>
		<li><a href="#tab-geographic">Geography</a></li>
		<li><a href="#tab-clouds">Word Clouds</a></li>
	</ul>
	<div id="tab-documents">
		<!-- Documents Tab Content -->
		<form id='sortForm'>
			<input type='radio' name='sort' id='sort_similarity' value='similarity'>Relevance</input>
			<input type='radio' name='sort' id='sort_date' value='date'>Date</input>
			<input type='radio' name='sort' id='sort_sentiment' value='sentiment'>Sentiment</input>
		</form>

		<div id="documentsList">Loading...</div>

		<script>
			function updateDocuments() {
				var docList = $('#documentsList')
				var NatLangString;
				docList.empty();
				$('#resultsCount').text(basicData.length);
				$('#totalDocsCount').text(totalDocsCount);
				
				
				
				for (var i=0; i<basicData.length; i++) {
					var doc = basicData[i];
					var row = document.createElement("p");
					row.innerHTML = "" + (parseInt(i) + 1) + ". ";
					var sentiment = document.createElement("span");
					sentiment.innerHTML = doc['sentimentScore'];
					row.appendChild(sentiment);
					var a = document.createElement("a");
					a.href = "document.php?id=" + doc['id'] + ".xml";
					a.innerHTML = doc['title'];
					row.appendChild(a)
					row.innerHTML += ":";
					var summary = document.createElement("p");
					summary.innerHTML = doc['summary'];
					row.appendChild(summary);
					row.appendChild(document.createElement("br"));
					
					docList.append(row);
				}
				$('#sort_' + sortKey).prop('checked',true);
			}
			$(document).on("basicDataLoaded", function(e, data) {
				// Invoked when new basic data is downloaded
				if (data != null && data != basicData) {
					basicData = data;
					updateDocuments();
				}
				else alert("?");
			});
			// Define behavior for when the sort options are clicked
			$('input:radio[name=sort]').on('click', function(e) {
				sortKey = $('input:radio[name=sort]:checked').val();
				queryChanged();
			});
		</script>
	</div>
	<div id="tab-geographic" style="padding:0">
		<!-- Geographic Tab Content -->
		<div id="map" style="width:100%; height:500px;"></div>
		<script>
			var mapIsSetup = false;
			var map;
			var markers = [];
			var lines = [];
			var infoWindows = [];
			
			// Load Maps API package
			google.load("maps", "3", {other_params:'sensor=false'});

			// Performs initial map setup
			function setupMap() {
				var mapOptions = {
					center: new google.maps.LatLng(34, -94),
					zoom: 4,
					mapTypeId: google.maps.MapTypeId.TERRAIN
				};
				map = new google.maps.Map(document.getElementById("map"), mapOptions);
				if (map.getProjection() != null) {
					redrawAllCurves();
				}
				else {
					google.maps.event.addListener(map, 'projection_changed', redrawAllCurves);
				}
				redrawMarkers();
				google.maps.event.addListener(map, 'zoom_changed', redrawMarkers);
			}
			// Invoked when new city data is downloaded
			$(document).on("cityDataLoaded", function(e, data) {
				if (data != null && data != cityData) {
					cityData = data;
					mapNeedRerender = true;
					if ($("#tab-geographic")[0].style.display != "none") {
						redrawAllCurves();
						redrawMarkers();
					}
				}
			});
			// Invoked when a tab is clicked
			$("#tabs").on("tabsactivate", function(event, ui) {
				if (ui.newPanel[0].id == "tab-geographic") {
					if (mapIsSetup !== true) {
						setupMap();
					}
					else if (mapNeedsRerender) {
						redrawAllCurves();
						redrawMarkers();
					}
				}
			});
			//This function handles a click on a particular city within the Google Map
			function cityClicked(markerIndex)
			{
				//Closes all info windows
				for (var i=0; i<infoWindows.length; i++) {
					infoWindows[i].close();
				}

				//Opens the info window for the selected marker
				infoWindows[markerIndex].open(map, markers[markerIndex]);
			}

			//Changes the query's location filter to the given city
			//id must be a key in the NormalizedPlace table
			function searchCity(id) {
				var getParams = '?query=';
				getParams += encodeURIComponent(document.getElementById('query').value);
				getParams += '&location=';
				getParams += encodeURIComponent(id);
				getParams += '&sort=';
				getParams += encodeURIComponent(sortKey);
				window.location = "results.php" + getParams;
			}

			//Given an r, g, b, returns a string representing a color in html
			//Borrowed from the internet
			function rgbToHtml(r, g, b)
			{
				var decColor = r + 256 * g + 65536 * b;
				var str = decColor.toString(16);
				while (str.length < 6) {
					str = '0' + str;
				}
				return "#" + str;
			}

			// Draws a Bezier curve on a Google Map
			//
			//  First, the start and end points are converted to screen coordinates.
			//  Then, the curvyness is used to generate a third point.  This point is
			//  equidistant from the endpoints and, if you were travelling from start
			//  to end, this point would be off to your left.  From these three points,
			//  we generate a Bezier curve.  Then this curve is coverted back into
			//  Lat/Long coordinates and plotted on the map.
			//  Each segment of this curve will transition from Red to Blue.
			//
			//@param map The Google Map on which to draw.
			//@param startLatLng The latitude/longitude of the curve's start point
			//@param endLatLng The latitude/longitude of the curve's end point
			//@param curvyness Determines how far the third point of the Bezier curve
			//  is from the straight line between the endpoints.
			//
			//Reference http://en.wikipedia.org/wiki/B%C3%A9zier_curve
			function drawCurve(map, startLatLng, endLatLng, curvyness) {
				var projection = map.getProjection();
				var p0 = projection.fromLatLngToPoint(startLatLng);
				var p2 = projection.fromLatLngToPoint(endLatLng);
				var xDiff = p2.x-p0.x;
				var yDiff = p2.y-p0.y;
				var dist = Math.sqrt(Math.pow(xDiff, 2) + Math.pow(yDiff, 2));
				dist *= curvyness;
				var angle = Math.atan2(yDiff, xDiff) + Math.PI/2;
				var mid = new google.maps.Point((p0.x+p2.x)/2, (p0.y+p2.y)/2);
				var p1 = new google.maps.Point(mid.x + dist * Math.cos(angle),
					mid.y + dist*Math.sin(angle));

				var SEGMENTS = 10;
				var prevLatLng;
				var currentLatLng;
				var first = true;
				for (var i = 0; i <= SEGMENTS; i += 1) {
					var t = i / SEGMENTS;
					var x = Math.pow(1 - t, 2) * p0.x + 2 * (1 - t) * t * p1.x
						+ Math.pow(t, 2) * p2.x;
					var y = Math.pow(1 - t, 2) * p0.y + 2 * (1 - t) * t * p1.y
						+ Math.pow(t, 2) * p2.y;
					currentLatLng = projection.fromPointToLatLng(new google.maps.Point(x, y));
					if (!first) {
						var r = Math.floor(255*t);
						var color = rgbToHtml(r, 0, 255 - r);
						var coords = [prevLatLng, currentLatLng];
						var line = new google.maps.Polyline({
						  path: coords,
						  strokeColor: color,
						  strokeOpacity: 0.1 + 0.4 * Math.abs(t - 0.5),
						  strokeWeight: 5
						});
						line.setMap(map);
						lines.push(line);
					}
					prevLatLng = currentLatLng;
					first = false;
				}
			}
			function redrawAllCurves() {
				// Clear existing
				for (var i=0; i<lines.length; i++)
					lines[i].setMap(null);
				lines = [];
				
				for (var i=0; i<basicData.length; i++) {
					doc = basicData[i];
					if (doc['srcLat'] != null && doc['dstLat'] != null) {
						var curvyness = 0.1 + Math.random() * 0.2;
						drawCurve(map,
							new google.maps.LatLng(doc['srcLat'], doc['srcLng']), 
							new google.maps.LatLng(doc['dstLat'], doc['dstLng']),
							curvyness);
					}
				}
			}
			function redrawMarkers() {
				console.log("RedrawMarkers called.");
				if (cityData == null) {
					console.log("Aborting.");
					console.log(cityData);
					return;
				}
				//Clear all existing markers.
				for (var i=0; i<markers.length; i++) {
					markers[i].setMap(null);
				}
				markers = [];
				infoWindows = [];

				//Determine how many markers we want to draw at this zoom level
				var maxMarkers;
				if (map.getZoom() > 7) {
					//If we are zoomed in really close, show them all
					maxMarkers = cityData.length;
				}
				else {
					//Otherwise just show the first few (which are the ones with
					//the most traffic)
					maxMarkers = Math.min(cityData.length,
						Math.ceil(Math.pow(map.getZoom(), 1.2)));
				}

				//Add the markers
				for (i = 0; i < maxMarkers; i++) {
					var city = cityData[i];
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(city['lat'], city['lng']),
						map: map,
						title: city['name']
					});
					var onClickFunction = function() {
						cityClicked(arguments.callee.markerIndex);
					};
					onClickFunction.markerIndex = i;
					google.maps.event.addListener(marker, 'click', onClickFunction);
					markers.push(marker);

					var contentHtml = "<a style='color:#0000FF;text-decoration:underline;'"
						+ " onClick='searchCity(" + city['id'] + ")'> "
						+ city['name'] + "</a><br />"
						+ "<b>" + (parseInt(city['incoming']) + parseInt(city['outgoing']))
						+ " Letters</b><br />"
						+ city['incoming'] + " Incoming Letters<br />"
						+ city['outgoing'] + " Outgoing Letters<br />";

					var infowindow = new google.maps.InfoWindow({
						content: contentHtml
					});
					infoWindows.push(infowindow);
				}
				mapNeedsRerender = false;
			}
		</script>
	</div>
	<div id="tab-clouds">
		<!-- Clouds Tab Content -->
		<h2>People</h2>
		<div id="personCloud" style="width: 550px; height: 350px; border: 1px solid #ccc;"></div>
		<h2>Places</h2>
		<div id="placeCloud" style="width: 550px; height: 350px; border: 1px solid #ccc;"></div>
		<h2>Words</h2>
		<div id="wordCloud" style="width: 550px; height: 350px; border: 1px solid #ccc;"></div>
		<script>
			var cloudsNeedRerender = false;
			function updateClouds() {
				$("#wordCloud").empty();
				$("#personCloud").empty();
				$("#placeCloud").empty();
				
				$("#wordCloud").jQCloud(cloudData[0]);
				$("#personCloud").jQCloud(cloudData[2]);
				$("#placeCloud").jQCloud(cloudData[1]);
				cloudsNeedRerender = false;
			}
			// Invoked when new cloud data is downloaded
			$(document).on("cloudDataLoaded", function(e, data) {
				if (data != null && data != cloudData) {
					cloudData = data;
					cloudsNeedRerender = true;
					if ($("#tab-clouds")[0].style.display != "none") {
						updateClouds();
					}
				}
			});
			// Invoked when a tab is clicked
			$("#tabs").on("tabsactivate", function(event, ui) {
				if (cloudsNeedRerender && ui.newPanel[0].id == "tab-clouds") {
					updateClouds();
				}
			});
		</script>
	</div>
	<div id="tab-timeline">
		<!-- Timeline Tab Content -->
		<div id="timeChart" style="width:100%; height:500px;"></div>
		<script>
			var timeChart;
			var timeChartNeedsUpdate = true;
			google.load("visualization", "1", {packages:["corechart"], callback:function(){
				timeChart = new google.visualization.ColumnChart(document.getElementById('timeChart'));
			}});
			
			function updateChart() {
				if (basicData != null) {
					var years = {};
					var positive = {};
					var neutral = {};
					var negative = {};
					for (var i=0; i<basicData.length; i++) {
						var year = basicData[i].date.substring(0, 4);
						if (years[year] === undefined)
							years[year] = 1;
						else
							years[year]++;
						
						if (positive[year] === undefined) {
							positive[year] = 0;
							neutral[year] = 0;
							negative[year] = 0;
						}
						
						if (basicData[i].sentimentScore < -2.0) {
							negative[year]++;
						} else if (basicData[i].sentimentScore > 2.0) {
							positive[year]++;
						} else {
							neutral[year]++;
						}
					}
					
					var minYear = 2000;
					var maxYear = 1000;
					for (var key in totalDocDistribution) {
						// Find years with at least 15 documents
						if (parseInt(totalDocDistribution[key]) >= 15) {
							var intYear = parseInt(key);
							if (intYear < minYear)
								minYear = intYear;
							if (parseInt(key) > maxYear)
								maxYear = intYear;
						}
					}
					var chartData = [['Year', 'Negative','Neutral','Positive']];
					for (var i=minYear; i<=maxYear; i++) {
						var yearStr = i.toString();
						var value = 0;
						if (years[yearStr] !== undefined)
							value = years[yearStr];
						var total = 0;
						if (totalDocDistribution[yearStr] !== undefined) {
							total = parseInt(totalDocDistribution[yearStr]);
							
						}
						chartData.push([yearStr, negative[yearStr] / total * 100,neutral[yearStr] / total * 100,positive[yearStr] / total * 100]);
					}
				}

				// Define data and options, and draw the chart
				var data = google.visualization.arrayToDataTable(chartData);
				var options = {
					isStacked: true,
					title: 'Search Results, Distributed by Year',
					//isStacked: true,
					hAxis: {
						title: "Year"
					},
					vAxis: {
						title: "Percentage out of all documents",
						minValue: 0,
					//	logScale: true
					},
					series: [{color: '#b70000'},{color: 'gray'},{color: '#006600'}]
				};
				timeChart.draw(data, options);
				
				// Handle event when user clicks on a chart entity
				google.visualization.events.addListener(timeChart, 'select', function() {
					console.log(timeChart.getSelection());
					var sel = timeChart.getSelection()[0];
					if ('row' in sel && 'column' in sel) {
						// Set the "from year"/"to year" search fields to the year clicked
						var year = chartData[sel.row+1][0];
						document.getElementById('fromYear').value = year;
						document.getElementById('toYear').value = year;
						queryChanged();
						$("#tabs").tabs("option", "active", 0);
					}
				});
			}
			// Invoked when new basic data is downloaded
			$(document).on("basicDataLoaded", function(e, data) {
				if (data != null) {
					timeChartNeedsUpdate = true;
					if ($("#tab-timeline")[0].style.display != "none") {
						updateChart();
					}
				}
			});
			// Invoked when a tab is clicked
			$("#tabs").on("tabsactivate", function(event, ui) {
				if (ui.newPanel[0].id == "tab-timeline" && timeChartNeedsUpdate) {
					updateChart();
				}
			});
		</script>
	</div>
</div>

<script>
	$(function() {
		// Enable jQueryUI tabs
		$( "#tabs" ).tabs({
			beforeLoad: function( event, ui ) {
				ui.jqXHR.error(function() {
					ui.panel.html("This tab couldn't be loaded. Please try again later.");
				});
			}
		});
	});
</script>

<div id="footer">
	<?php include('footer.php'); ?>
</div>
</div> <!--End wrapper div-->
</body>
</html>
