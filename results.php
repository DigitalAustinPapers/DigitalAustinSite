<?
include 'php/database.php';
include('php/wordcloud.class.php');
$database = connectToDB();
?>
<!DOCTYPE html>
<html>
<head>
<title>Stephen F. Austin - Digital Collection</title>
<link rel="stylesheet" type="text/css" href="header.css" />
<link rel="stylesheet" type="text/css" href="footer.css" />
<link rel="stylesheet" type="text/css" href="results.css" />

<script src="jquery.js"></script>

<!-- Word Cloud -->
<link rel="stylesheet" type="text/css" href="jqcloud/jqcloud.css" />
<script type="text/javascript" src="jqcloud/jqcloud-1.0.0.js"></script>

<!-- Google Maps API -->
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCld462mkpAZrPllmHK8eJGXenW5Kus7g0&sensor=false">
</script>

<script type="text/javascript">

var map;
var basicData;
var cloudData;
var cityData;
var sortKey='date';
var markers = [];
var infoWindows = [];

//This function handles a click on a particular city within the Google Map
function cityClicked(markerIndex)
{
    //Closes all info windows
    var i;
    for (i in infoWindows) {
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
        }
        prevLatLng = currentLatLng;
        first = false;
    }
}

function redrawAllCurves()
{
    for (i in basicData) {
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

function redrawMarkers()
{
    if (cityData == null) {
        return;
    }
    //Clear all existing markers.
    var i;
    for (i in markers) {
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
}

function changeSort() {
    sortKey = $('input:radio[name=sort]:checked').val();
    queryChanged();
}

//If the user has selected the basic view, this function will generate
//the content.  In this case, the content is simply a list of the matching
//documents in the order they are returned from the server.
//
//A 'hidden' parameter to this function is the global basicData, which
//is the most recent data received from the server.
function basicContent() {
    var doc;
    var i;
    var newContent = "";
    newContent += "\
    <form id='sortForm' >\
    <input type='radio' name='sort' id='sort_similarity' \
        onclick='changeSort()'\
        value='similarity'>Relevance</input>\
    <input type='radio' name='sort' id='sort_date'\
        onclick='changeSort()'\
        value='date'>Date</input>\
    </form>";

    var minYear = 9999, maxYear = -9999, year;
    for (i in basicData) {
        doc = basicData[i];
        var year = parseInt(new Date(doc['date']).getFullYear());
        if (year < minYear) {
            minYear = year;
        }
        if (year > maxYear) {
            maxYear = year;
        }
    }

    newContent += "<p>Showing " + basicData.length + " results";
    if (basicData.length > 0) {
        newContent += " between " + minYear + " and " + maxYear;
    }
    newContent += "</p>";
    for (i in basicData) {
        doc = basicData[i];
        newContent += "<p>" + (parseInt(i) + 1)
        + ". <a href='document.php?id="
        + doc['id'] + ".xml'>" + doc['title'] + "</a>:<p>"
        + doc['summary'] + "</p></p><br>";
    }
    document.getElementById('content').innerHTML = newContent;
    $('#sort_' + sortKey).prop('checked',true);
}

//Generates the content of the geographic view, which is a Google Map
//
//A 'hidden' parameter to this function is the global basicData, which
//is the most recent data received from the server.
function geographicContent() {
    $('#content').height(500);
    var myOptions = {
        center: new google.maps.LatLng(34, -94),
        zoom: 4,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    map = new google.maps.Map(document.getElementById("content"), myOptions);
    if (map.getProjection() != null) {
        redrawAllCurves();
    }
    else {
        google.maps.event.addListener(map, 'projection_changed', redrawAllCurves);
    }
    redrawMarkers();
    google.maps.event.addListener(map, 'zoom_changed', redrawMarkers);
}

//Generates the content of the word cloud view
//
//A 'hidden' parameter to this function is the global cloudData, which
//is the most recent data received from the server.
function cloudContent() {
    if (cloudData != null) {
        document.getElementById('content').innerHTML =
            '<h2>People</h2>'
          + '<div id="personCloud" style="width: 550px; height: 350px;'
          + '    border: 1px solid #ccc;">'
          + '</div>'
          + '<h2>Places</h2>'
          + '<div id="placeCloud" style="width: 550px; height: 350px;'
          + '    border: 1px solid #ccc;">'
          + '</div>'
          + '<h2>Words</h2>'
          + '<div id="wordCloud" style="width: 550px; height: 350px;'
          + '    border: 1px solid #ccc;">'
          + '</div>';
        $("#wordCloud").jQCloud(cloudData[0]);
        $("#personCloud").jQCloud(cloudData[2]);
        $("#placeCloud").jQCloud(cloudData[1]);
    }
}

function correlationContent() {
    var newContent = "";
    newContent += "This view is not currently available.";
    document.getElementById('content').innerHTML = newContent;
}

function sentimentContent() {
    var newContent = "";
    newContent += "This view is not currently available.";
    document.getElementById('content').innerHTML = newContent;
}

//This function is used as a callback for our XMLHttpRequest object
//that is pulling data from the server.
function basicRequestStateChanged() {
    if (this.readyState == 4) {
        //Complete response received
        if (this.status == 200) {
            //Query was successful
            basicData = JSON.parse(this.responseText);
            contentGenerators[currentView]();
        }
    }
}

//This function is used as a callback for our XMLHttpRequest object
//that is pulling data from the server for the word cloud data.
function cloudRequestStateChanged() {
    if (this.readyState == 4) {
        //Complete response received
        if (this.status == 200) {
            //Query was successful
            cloudData = JSON.parse(this.responseText);
            contentGenerators[currentView]();
        }
    }
}

function cityRequestStateChanged() {
    if (this.readyState == 4) {
        //Complete response received
        if (this.status == 200) {
            //Query was successful
            cityData = JSON.parse(this.responseText);
            contentGenerators[currentView]();
        }
    }
}


var views = {"basic" : 0, "geographic" : 1, "cloud" : 2, "correlation" : 3,
    "sentiment" : 4};
var contentGenerators = [basicContent, geographicContent, cloudContent, correlationContent, sentimentContent];
var currentView = views.basic;
function load() {
    queryChanged();
}

/* Given the index value of a tab, returns the element representing
    this tab.

    tabIndex is one of views.basic, views.geographic, etc.
*/
function getTabElement(tabIndex)
{
    var tabIdSelector = "#Tab" + tabIndex;
    return $(tabIdSelector);
}

/*  Changes the content to match the current query

    The content is immediately changed to a loading screen,
    then an asychronous data request is sent to the server.  When the data
    is received, the current view is reloaded.

    This function returns false so that it cancels the form submission
*/
function queryChanged() {
    //Invalidate all data that was for the old query parameters
    basicData = null;
    cloudData = null;
    cityData = null;

    //Request data for the current view
    requestData();

    //Don't navigate away from this page
    return false;
}

//Start data requests for the current view if necessary.
function requestData() {
    var waitingForData = false;
    document.getElementById('content').innerHTML = "Loading...";

    //Build the GET params
    var getParams = '?query=';
    getParams += encodeURIComponent(document.getElementById('query').value);
    getParams += '&location=';
    getParams += encodeURIComponent(document.getElementById('location').value);
    getParams += '&fromYear=';
    getParams += encodeURIComponent(document.getElementById('fromYear').value);
    getParams += '&toYear=';
    getParams += encodeURIComponent(document.getElementById('toYear').value);
    getParams += '&fromPersonId=';
    getParams += encodeURIComponent(document.getElementById('fromPersonId').value);
    getParams += '&toPersonId=';
    getParams += encodeURIComponent(document.getElementById('toPersonId').value);
    getParams += '&sort=';
    getParams += encodeURIComponent(sortKey);

    var url;

    //Request the data for the basic and geographic views
    if (basicData == null) {
        var dataRequest = new XMLHttpRequest();
        url = 'data/search.php';
        dataRequest.open("GET", url + getParams, true);
        dataRequest.onreadystatechange = basicRequestStateChanged;
        dataRequest.send();
        waitingForData = true;
    }

    //Request the data for the clouds
    if (cloudData == null && currentView == views.cloud) {
        var cloudRequest = new XMLHttpRequest();
        //Text cloud
        url = 'data/cloud.php';
        cloudRequest.open("GET", url + getParams, true);
        cloudRequest.onreadystatechange = cloudRequestStateChanged;
        cloudRequest.send();

        waitingForData = true;
    }

    if (cityData == null && currentView == views.geographic) {
        var cityRequest = new XMLHttpRequest();
        //Text cloud
        url = 'data/cities.php';
        cityRequest.open("GET", url + getParams, true);
        cityRequest.onreadystatechange = cityRequestStateChanged;
        cityRequest.send();

        waitingForData = true;
    }

    return waitingForData;
}

/* Given the index value of the chosen view, the content div is updated
    to show the desired content.  This is done asynchronously, so the
    content div is immediately changed to say "Loading" and some a process
    is kicked off to fill in the content appropriately.

    newView is one of views.basic, views.geographic, etc.
*/
function tabChanged(newView) {
    //Reset the height to auto (undoing the static height chosen for
    //  geographic view)
    $('#content').height('auto');

    getTabElement(currentView).removeClass("selected");
    getTabElement(newView).addClass("selected");
    currentView = newView;
    var waitingForData = requestData();
    if (!waitingForData) {
        contentGenerators[currentView]();
    }
}

</script>
</head>
<body onload="load()">
<div id="wrapper" class="shadow">
	
<div id = "header">
	<?php include('header.php'); ?>
</div>

<div style='background:white;'>
    <h1>Search the Austin Papers</h1>
    <form onsubmit='return queryChanged()'>
    Search for: 
    <input id='query' type='text' value='<? print htmlentities($_GET['query'])?>' />
    <br />
    in:
    <select id='location'>
    <option value=-1>--All Locations--</option>
    <?
    $placeSql = 'SELECT name, id FROM NormalizedPlace';
    $result = mysql_query($placeSql);
    while ($row = mysql_fetch_array($result))
    {
        if ($row['id'] == $_GET['location'])
        {
            print "<option value={$row['id']} selected>{$row['name']}</option>";
        }
        else
        {
            print "<option value={$row['id']}>{$row['name']}</option>";
        }
    }
    ?>
    </select>

    <? if (array_key_exists('fromYear', $_GET)) {
    	$fromYear = $_GET['fromYear'];
    } else {
        $fromYear = '';
    } ?>
    <input type='hidden' name='fromYear' id='fromYear' value='<? print htmlentities($fromYear)?>' />

    <? if (array_key_exists('toYear', $_GET)) {
    	$toYear = $_GET['toYear'];
    } else {
        $toYear = '';
    } ?>
    <input type='hidden' name='toYear' id='toYear' value='<? print htmlentities($toYear)?>' />

    
    <? if (array_key_exists('fromPersonId', $_GET)) {
    	$fromPersonId = $_GET['fromPersonId'];
    } else {
        $fromPersonId = '';
    } ?>
    <input type='hidden' name='fromPersonId' id='fromPersonId' value='<? print htmlentities($fromPersonId)?>' />

    <? if (array_key_exists('toPersonId', $_GET)) {
    	$toPersonId = $_GET['toPersonId'];
    } else {
        $toPersonId = '';
    } ?>
    <input type='hidden' name='toPersonId' id='toPersonId' value='<? print htmlentities($toPersonId)?>' />

    
    <input type='submit' value='Search' />
    </form>
    <br />
</div>
<div id="tabs">
<h2>View search results by:</h2>
<ul>
	<li id="Tab0" class="selected">
        <a href="#" onclick="tabChanged(views.basic);">
            Documents
        </a>
    </li>
	<li id="Tab1">
        <a href="#" onclick="tabChanged(views.geographic);">
            Geography
        </a>
    </li>
	<li id="Tab2">
        <a href="#" onclick="tabChanged(views.cloud);">
            Word Clouds
        </a>
    </li>
	<li id="Tab3">
        <a href="#" onclick="tabChanged(views.correlation);">
            Word Correlation
        </a>
    </li>
	<li id="Tab4">
        <a href="#" onclick="tabChanged(views.sentiment);">
            Sentiment Analysis
        </a>
    </li>
</ul>
</div>

<!-- The contents of this div are generated by javascript -->
<div id="content" style='min-height:200px;'>Loading...</div>
<div id = "footer">
	<?php include('footer.php'); ?>
</div>
</div> <!--End wrapper div-->
</body>
</html>
