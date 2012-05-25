<?
include 'php/database.php';
$database = connectToDB();
?>
<!DOCTYPE html>
<html>
<head>
<title>Stephen F. Austin - Digital Collection</title>
<link rel="stylesheet" type="text/css" href="header.css" />
<link rel="stylesheet" type="text/css" href="footer.css" />

<link rel="stylesheet" type="text/css" href="results.css" />

<!-- Google Maps API -->
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCld462mkpAZrPllmHK8eJGXenW5Kus7g0&sensor=false">
</script>

<script type="text/javascript">

//This function handles a click on a particular city within the Google Map
//id must be a key in the NormalizedPlace table
function cityClicked(id)
{
    //TODO
    //window.location = "" + id;
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

var map;
var docArray;
var sortKey='similarity';
function redrawAllCurves()
{
    for (i in docArray) {
        doc = docArray[i];
        if (doc['srcLat'] != null && doc['dstLat'] != null) {
            var curvyness = 0.1 + Math.random() * 0.2;
            drawCurve(map,
                new google.maps.LatLng(doc['srcLat'], doc['srcLng']), 
                new google.maps.LatLng(doc['dstLat'], doc['dstLng']),
                curvyness);
        }
    }
}

function changeSort() {
    sortKey = $('input:radio[name=sort]:checked').val();
    changeQuery();
}

//If the user has selected the basic view, this function will generate
//the content.  In this case, the content is simply a list of the matching
//documents in the order they are returned from the server.
//
//A 'hidden' parameter to this function is the global docArray, which
//is the most recent data received from the server.
function basicContent() {
    var doc;
    var i;
    var newContent = "";
    newContent += "\
    <form id='sortForm' >\
    <input type='radio' name='sort' id='sort_similarity' \
        onclick='changeSort()'\
        value='similarity'>Similarity</input>\
    <input type='radio' name='sort' id='sort_date'\
        onclick='changeSort()'\
        value='date'>Date</input>\
    </form>";
    newContent += "<p>Showing " + docArray.length + " results</p>";
    for (i in docArray) {
        doc = docArray[i];
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
//A 'hidden' parameter to this function is the global docArray, which
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
}

//Generates the content of the word cloud view
//
//A 'hidden' parameter to this function is the global docArray, which
//is the most recent data received from the server.
function cloudContent() {
    //TODO
    var newContent = "This view is not currently available";
    document.getElementById('content').innerHTML = newContent;
}

//This function is used as a callback for our XMLHttpRequest object
//that is pulling data from the server.
function stateChanged() {
    if (this.readyState == 4) {
        //Complete response received
        if (this.status == 200) {
            //Query was successful
            docArray = JSON.parse(this.responseText);
            contentGenerators[currentView]();
        }
    }
}
var views = {"basic" : 0, "geographic" : 1, "cloud" : 2};
var contentGenerators = [basicContent, geographicContent, cloudContent];
var currentView = views.basic;
function load() {
    changeQuery();
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
function changeQuery() {
    document.getElementById('content').innerHTML = "Loading...";
    var dataRequest = new XMLHttpRequest();
    var url = 'search.php';
    var getParams = '?query=';
    getParams += encodeURIComponent(document.getElementById('query').value);
    getParams += '&location=';
    getParams += encodeURIComponent(document.getElementById('location').value);
    getParams += '&sort=';
    getParams += encodeURIComponent(sortKey);
    dataRequest.open("GET", url + getParams, true);
    dataRequest.onreadystatechange = stateChanged;
    dataRequest.send();
    return false;
}

/* Given the index value of the chosen view, the content div is updated
    to show the desired content.  This is done asynchronously, so the
    content div is immediately changed to say "Loading" and some a process
    is kicked off to fill in the content appropriately.

    newView is one of views.basic, views.geographic, etc.
*/
function changeView(newView) {
    //Reset the height to auto (undoing the static height chosen for
    //  geographic view)
    $('#content').height('auto');

    getTabElement(currentView).removeClass("selected");
    getTabElement(newView).addClass("selected");
    currentView = newView;
    contentGenerators[currentView]();
}

</script>
</head>
<body onload="load()">
<div id="wrapper" class="shadow">
	
<div id = "header">
	<?php include('header.php'); ?>
</div>

<div style='background:white;'>
    <h1>Query</h1>
    <form onsubmit='return changeQuery()'>
    <input id='query' type='text' value='<? print htmlentities($_GET['query'])?>' />
    <br />
    Location:
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
    <input type='submit' value='Search' />
    </form>
    <br />
</div>
<div id="tabs">
<h1>Views</h1>
<ul>
	<li id="Tab0" class="selected">
        <a href="#" onclick="changeView(views.basic);">
            Basic
        </a>
    </li>
	<li id="Tab1">
        <a href="#" onclick="changeView(views.geographic);">
            Geographic
        </a>
    </li>
	<li id="Tab2">
        <a href="#" onclick="changeView(views.cloud);">
            Word Cloud
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
