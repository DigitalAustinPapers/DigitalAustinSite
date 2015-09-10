/*
  Results page javascript
 */

// Global data
var basicData = null;
var chartData = null;
var cityData = null;
var sortKey = 'date';
var positiveThreshold = 2.0;
var negativeThreshold = -2.0;
var resultsListItems = null;
// placeIdToNames and personIdToNames are declared in the search template

/*  Changes the content to match the current query

 The content is immediately changed to a loading screen,
 then an asychronous data request is sent to the server.  When the data
 is received, the current view is reloaded.

 This function returns false so that it cancels the form submission
 */
function queryChanged() {
    // Invalidate all data that was for the old query parameters
    basicData = null;
    chartData = null;
    cityData = null;
    resultsListItems = null;

    // Request data for the current view
    requestData();

    // reveal results div
    jQuery('.search-results').removeClass('hidden');

    // Don't navigate away from this page
    return false;
}

function queryUrlForYearAndSentiment(year, sentiment) {
    // Build the GET params
    var getParams = '?query=';
    getParams += encodeURIComponent(document.getElementById('query').value);
    // getParams += '&location=';
    // getParams += encodeURIComponent(document.getElementById('location').value);
    getParams += '&fromYear=';
    getParams += year;
    getParams += '&toYear=';
    getParams += year;
    getParams += '&fromPersonId=';
    getParams += encodeURIComponent(document.getElementById('fromPersonId').value);
    getParams += '&toPersonId=';
    getParams += encodeURIComponent(document.getElementById('toPersonId').value);
    getParams += '&fromPlaceId=';
    getParams += encodeURIComponent(document.getElementById('fromPlaceId').value);
    getParams += '&toPlaceId=';
    getParams += encodeURIComponent(document.getElementById('toPlaceId').value);
    getParams += '&sentiment=';
    getParams += sentiment;
    getParams += '&sort=';
    getParams += encodeURIComponent(sortKey);

    return 'data/search.php' + getParams;
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
    getParams += '&sentiment=';
    getParams += encodeURIComponent(document.getElementById('sentiment').value);
    getParams += '&sort=';
    getParams += encodeURIComponent(sortKey);

    window.history.pushState({path:getParams},'', location.origin + location.pathname + getParams);

    // Google analytics event tracking
    ga('send', 'event', 'search', 'submit', getParams);


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
    if(document.getElementById('sentiment').value) {
        humanQueryString += " with <b>"+document.getElementById('sentiment').value+"</b> sentiment scores";
    }
    if(humanQueryString == "") {
        humanQueryString = "all results.";
    }
        document.getElementById('humanQuery').innerHTML = humanQueryString;


    // Request new basic search data
    if (true) {
        var url = 'search_results.php';
        $.getJSON(url + getParams, function(json) {
            $(document).trigger("basicDataLoaded", [json]);
        });
        waitingForData = true;
    }

    // Request new chart data only if it's being listened for
    if (true) {
        var url = 'data/cloud.php';
        $.getJSON(url + getParams, function(json) {
            $(document).trigger("chartDataLoaded", [json]);
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

/*
  Document tab
 */

function updateDocuments() {
    var docList = $('#documentsList');
    var NatLangString;
    var resultsCount = basicData['json'].length;
    $('#resultsCount').text(resultsCount);
    $('#totalDocsCount').text(totalDocsCount);
    $('.search-results__results-summary').removeClass('invisible');

    if(resultsCount === 1) {
      document.getElementById('resultsPlural').innerHTML = "result";
    }

  // Apply alert class with CSS transition and remove after 2 seconds
  $('.search-results__results-summary')
      .addClass('search-results__results-summary--changed').delay(2000).queue(function() {
        $(this).removeClass('search-results__results-summary--changed')
        .dequeue();
  });

    $(".pagination").paging(resultsCount, pagingOpts);

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

// Update class based on sentiment. Do that here instead of in template
// because thresholds are defined in the javascript.
function updateSentiment() {
    $('.search-result-list__item-sentiment-score').each(function() {
        var $sentimentElement = $(this);
        var $score = $sentimentElement.attr('data-sentiment');

        if ($score < negativeThreshold) {
            $sentimentElement.addClass('sentiment-negative');
        } else if($score > positiveThreshold) {
            $sentimentElement.addClass('sentiment-positive');
        } else {
            $sentimentElement.addClass('sentiment-neutral');
        }
    });
}

/*
 * Document tab paging
 */

// Paging options are declared in paging.js and overridden here
pagingOpts['perpage'] = 20;
pagingOpts['onSelect'] = function(page) {
  updatePage(this.slice);
  updateSentiment();
  showPager(this.pages);
};

function updatePage(pageSlice) {
    /* Updates the current page when a page button is clicked
     * @param {array} pageSlice This is an array with 2 values:
     *     The start and end values to slice the page
     */

    var listId = $('#documentsList');
    //var newList = $(resultsListItems).filter('li');

    var newPage = basicData['html'].slice(pageSlice[0], pageSlice[1]);
    listId.empty()
        .append(newPage)
        .first().attr('start', pageSlice[0]+1);
}

/*
  Geographic tab
 */

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
    window.location = "search" + getParams;
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

    for (var i=0; i<basicData['json'].length; i++) {
        doc = basicData['json'][i];
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

/*
  Chart tab
 */

var chartsNeedRerender = false;
function updateCharts() {
  $('svg').remove();

  wordChart(chartData[2], "#personChart");
  wordChart(chartData[1], "#placeChart");
  wordChart(chartData[0], "#wordChart");

  chartsNeedRerender = false;
}
// Invoked when new chart data is downloaded
$(document).on("chartDataLoaded", function(e, data) {
    if (data != null && data != chartData) {
        chartData = data;
        chartsNeedRerender = true;
        if ($("#tab-charts").css('display') != "none") {
            updateCharts();
        }
    }
});

function wordChart(dataset, divId) {
  // Chart defaults
  var dataSet = dataset,
    margin = {top: 0, right: 0, bottom: 0, left: 0},
    container = d3.select(divId),
    width = parseInt(container.style('width'))
        - parseInt(container.style('padding-left'))
        - parseInt(container.style('padding-right')),
    width = width - margin.left - margin.right,
    barHeight = 20,
    barPadding = 5,
    labelSpace = 125, // initial value
    height = (barHeight + barPadding) * dataSet.length
        - margin.top - margin.bottom;

  // Create the x scale
  var xScale = d3.scale.linear()
      .domain([0, d3.max(dataSet, function (d) {
        return parseInt(d.weight, 10);
      })]);

  // Create the y scale
  var yScale = d3.scale.ordinal()
      .domain(dataSet.map(function (d) {
        return d.text;
      }))
      .rangeBands([height, 0], .1);

  // Create and append the outer svg and inner g for margins
  var chart = d3.select(divId)
      .append("svg")
      .attr("class", "word-chart__outer-svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("class", "word-chart__inner-g")
      .attr("width", width)
      .attr("transform", "translate(" + [margin.left, margin.top] + ")");

  // Create and append the container for labels
  var labelContainer = chart.append("g")
      .attr("class", "word-chart__label-container");

  // Add labels to the label container
  labelContainer.selectAll("text")
      .data(dataSet)
      .enter()
      .append("a")
      .attr("class", "word-chart__label-link")
      .attr("xlink:href", function(d) {
        return d.link;
      })
      .append("text")
      .attr("class", "word-chart__label-text")
      .attr("transform", function (d, i) {
        return "translate(0," +
            (i * (barHeight + barPadding) ) + ")";
      })
      .attr("dy", "1em")
      .text(function (d) {
        return d.text;
      });

  // Update labelSpace to widest label
  labelSpace = d3.select(divId).select(".word-chart__label-container").node().getBBox().width;

  // Set x scale range after determining labelSpace
  xScale.range([25, width - labelSpace]);

  // Create and append a container for bars
  var barContainer = chart.append("g")
      .attr("class", "word-chart__bar-container");

  // Create g elements for each bar and append inside bar container
  var bars = barContainer.selectAll("g")
      .data(dataSet)
      .enter()
      .append("g")
      .attr("transform", function (d, i) {
        return "translate(" + labelSpace + "," +
            (i * (barHeight + barPadding) ) + ")";
      });

  // Adjust height of chart parent element
  d3.select(chart.node().parentNode)
      .style("height", (height + margin.top + margin.bottom) + "px");

  // Create and append the bars inside the bar container
  bars.append("rect")
      .attr("class", "word-chart__bar")
      .attr("width", function (d) {
        return xScale(d.weight);
      })
      .attr("height", barHeight - 1);

  // Create and append the bar value labels inside the bar container
  bars.append("text")
      .attr("class", "word-chart__item-weight")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .text(function (d) {
        return d.weight;
      })
      .attr("x", function (d) {
        return xScale(d.weight) - this.getBBox().width - 2;
      });

  function resizeChart() {
    width = parseInt(d3.select(divId).style('width'));
    width = width - margin.left - margin.right;

    console.log(width);

    xScale.range([0, width - labelSpace]);
    d3.select(chart.node().parentNode)
      .style('width', (width + margin.left + margin.right) + 'px');

    bars.attr("width", function(d) { return xScale(d.weight); })
        .attr("transform", function (d, i) {
          return "translate(" + (margin.left + labelSpace) + "," +
              (i * (barHeight + barPadding) ) + ")";
        });
  }

}

/*
  Timeline tab
 */

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
        for (var i=0; i<basicData['json'].length; i++) {
            var year = basicData['json'][i].date.substring(0, 4);
            if (years[year] === undefined)
                years[year] = 1;
            else
                years[year]++;

            if (positive[year] === undefined) {
                positive[year] = 0;
                neutral[year] = 0;
                negative[year] = 0;
            }

            if (basicData['json'][i].sentimentScore < negativeThreshold) {
                negative[year]++;
            } else if (basicData['json'][i].sentimentScore > positiveThreshold) {
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
            chartData.push([yearStr,
                Math.floor(negative[yearStr] / total * 1000)/10,
                Math.floor(neutral[yearStr] / total * 1000)/10,
                Math.floor(positive[yearStr] / total * 1000)/10]);
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
            minValue: 0
            //	logScale: true
        },
        series: [{color: '#b70000'},{color: 'gray'},{color: '#006600'}]
    };
    timeChart.draw(data, options);

    // Handle event when user clicks on a chart entity
    google.visualization.events.addListener(timeChart, 'select', function() {
        console.log(timeChart.getSelection()[0]);
        var sel = timeChart.getSelection()[0];
        if ('row' in sel && 'column' in sel) {
            // Set the "from year"/"to year" search fields to the year clicked
            var year = chartData[sel.row+1][0];
            if(sel.column == 1) {
                document.getElementById('sentiment').value = 'negative';
            }
            if(sel.column == 2) {
                document.getElementById('sentiment').value = 'neutral';
            }
            if(sel.column == 3) {
                document.getElementById('sentiment').value = 'positive';
            }
            document.getElementById('fromYear').value = year;
            document.getElementById('toYear').value = year;
            queryChanged();
            $('.search-results__tabs a[href="#tab-documents"]').tab("show");
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

// A single event listener for tabs using bootstrap's tabs js
$('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
    newTabId = $(e.target).attr('href');
    switch(newTabId) {
        case '#tab-content':
            break;
        case '#tab-timeline':
            if (timeChartNeedsUpdate) {
                updateChart();
            }
            break;
        case '#tab-geographic':
            if (mapIsSetup !== true) {
                setupMap();
            }
            else if (mapNeedsRerender) {
                redrawAllCurves();
                redrawMarkers();
            }
            break;
        case '#tab-charts':
            if (chartsNeedRerender) {
                updateCharts();
            }
            break;
    }
});