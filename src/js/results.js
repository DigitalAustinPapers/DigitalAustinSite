/*
  Search results page javascript
 */

// Global data
var basicData = null,
    chartData = null,
    cityData = null,
    sortKey = 'date',
    positiveThreshold = 2.0,
    negativeThreshold = -2.0,
    resultsListItems = null;
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
    $('.search-results').removeClass('hidden');

    // Don't navigate away from this page
    return false;
}

// Find form input selections on search screen and return search string for URI
function stringifyUrlQuery() {
    var getParams = '?query=';
    getParams += encodeURIComponent(document.getElementById('query').value);
    if (encodeURIComponent(document.getElementById('fromYear').value)) {
        getParams += '&fromYear=';
        getParams += encodeURIComponent(document.getElementById('fromYear').value);
    }
    if (encodeURIComponent(document.getElementById('toYear').value)) {
        getParams += '&toYear=';
        getParams += encodeURIComponent(document.getElementById('toYear').value);
    }
    if (encodeURIComponent(document.getElementById('fromPersonId').value)) {
        getParams += '&fromPersonId=';
        getParams += encodeURIComponent(document.getElementById('fromPersonId').value);
    }
    if (encodeURIComponent(document.getElementById('toPersonId').value)) {
        getParams += '&toPersonId=';
        getParams += encodeURIComponent(document.getElementById('toPersonId').value);
    }
    if (encodeURIComponent(document.getElementById('fromPlaceId').value)) {
        getParams += '&fromPlaceId=';
        getParams += encodeURIComponent(document.getElementById('fromPlaceId').value);
    }
    if (encodeURIComponent(document.getElementById('toPlaceId').value)) {
        getParams += '&toPlaceId=';
        getParams += encodeURIComponent(document.getElementById('toPlaceId').value);
    }
    if (encodeURIComponent(document.getElementById('sentiment').value)) {
        getParams += '&sentiment=';
        getParams += encodeURIComponent(document.getElementById('sentiment').value);
    }
    getParams += '&sort=';
    getParams += encodeURIComponent(sortKey);

    return getParams;
}

// Start data requests for the current view if necessary.
function requestData() {
    // Build the GET params
    var getParams = stringifyUrlQuery(),
        humanQueryString = "";

    if (history.pushState) {
        // Update browser URL
        window.history.pushState({path:getParams},'', getParams);
    }

    // Google analytics event tracking
    ga('send', 'event', 'search', 'submit', getParams);

    // Create the search summary HTML
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
        if(humanQueryString == "") {
            $('#resultsFor').hide();
        } else {
            $('#resultsFor').show();
        }
        humanQueryString += " with <b>"+document.getElementById('sentiment').value+"</b> sentiment scores";
    }
    if(humanQueryString == "") {
        humanQueryString = "all results.";
    }

    // Reveal results summary
    document.getElementById('humanQuery').innerHTML = humanQueryString;

    // Request new basic search data
    $.getJSON('search_results.php' + getParams, function (json) {
        $(document).trigger("basicDataLoaded", [json]);
    });

    // Request new chart data only if it's being listened for
    $.getJSON('data/cloud.php' + getParams, function (json) {
        $(document).trigger("chartDataLoaded", [json]);
    });

    // Request new city data only if it's being listened for
    $.getJSON('data/cities.php' + getParams, function (json) {
        $(document).trigger("cityDataLoaded", [json]);
    });

    // Request new network
    $.getJSON('data/network.php' + getParams, function (json) {
        $(document).trigger("networkDataLoaded", [json]);
    });
}

/*
  Document tab
 */

var $paging = $(".pagination").paging(0, pagingOpts);

// Paging options are first declared in paging.js and overridden here
$paging.setOptions({onSelect: function(page) {
    updatePage(this.slice);
    updateSentiment();
    showPager(this.pages);
}});

function updateDocuments() {
    var $docList = $('.search-results-list'),
        $resultsTabs = $('.search-results__tabs'),
        $progressBar = $('.documents-tab .searching-progress'),
        $description = $('.documents-tab__description'),
        $sorter = $('.documents-tab .search-results__sort'),
        $resultsSummary = $('.search-results__results-summary'),
        resultsCount = basicData['json'].length;

    // Remove existing sorter, results, and pager. show progress bar
    $docList.hide();
    $progressBar.show();
    $sorter.hide();

    // Fill in search summary
    $('#resultsCount').text(addCommas(resultsCount));
    $('#totalDocsCount').text(addCommas(totalDocsCount));
    $resultsSummary.slideDown();

    if(resultsCount === 1) {
        document.getElementById('resultsPlural').innerHTML = "result";
    }

  // Apply alert class with CSS transition and remove after 2 seconds
  $resultsSummary.addClass('search-results__results-summary--changed').delay(2000).queue(function() {
          $(this).removeClass('search-results__results-summary--changed')
          .dequeue();
  });

    // Set number of results in pager
    $paging.setNumber(resultsCount).setPage();

    $('#sort_' + sortKey).prop('checked',true);

    $progressBar.hide();

    if(resultsCount > 0) {
        $resultsTabs.show();
        $docList.show();
        $sorter.show();
        $description.show();
    } else {
        $resultsTabs.hide();
        $docList.hide();
        $sorter.hide();
        $description.hide();
    }
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

    $('.search-results-list__item-sentiment').each(function() {

        var $sentimentElement = $(this),
            $score = $sentimentElement.attr('data-sentiment');

        if ($score < negativeThreshold) {
            $sentimentElement.find('.search-results-list__item-sentiment-score')
                .addClass('sentiment-negative')
                .text('Negative');
        } else if($score > positiveThreshold) {
            $sentimentElement.find('.search-results-list__item-sentiment-score')
                .addClass('sentiment-positive')
                .text('Positive');
        } else {
            $sentimentElement.find('.search-results-list__item-sentiment-score')
                .addClass('sentiment-neutral')
                .text('Neutral');
        }
    });

    // Initialize bootstrap tooltip API for hover tooltips
    $(function () {
        $('.documents-tab').find('.fa-info-circle, .fa-question-circle').tooltip({
            'container': 'body',
            'placement': 'auto right',
            'html': true,
            'template': '<div class="tooltip" role="tooltip">' +
            '<div class="tooltip-arrow"></div>' +
            '<div class="tooltip-inner"></div>' +
            '</div>'
        })
    });
}

// Document tab paging
function updatePage(pageSlice) {
    /* Updates the current page when a page button is clicked
     * @param {array} pageSlice This is an array with 2 values:
     *     The start and end values to slice the page
     */

    var $listId = $('#documentsList'),
        newPage = basicData['html'].slice(pageSlice[0], pageSlice[1]);

    $listId.empty()
        .append(newPage)
        .first().attr('start', pageSlice[0]+1);
}

/*
 Timeline tab
 */

var timeChart;
var timeChartNeedsUpdate = true;

// Convert basicData so it's usable by D3
function timelineData() {
    if (basicData != null) {
        var years = {},
            positive = {},
            neutral = {},
            negative = {};

        for (var i=0; i<basicData['json'].length; i++) {
            var year = basicData['json'][i].date.substring(0, 4);

            if (years[year] === undefined) {
                years[year] = 1;
            } else {
                years[year]++;
            }

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

        var minYear = 2000,
            maxYear = 1000;

        for (var key in totalDocDistribution) {
            // Find years with at least 15 documents
            if (parseInt(totalDocDistribution[key]) >= 15) {
                var intYear = parseInt(key);
                if (intYear < minYear) {
                    minYear = intYear;
                }
                if (parseInt(key) > maxYear) {
                    maxYear = intYear;
                }
            }
        }

        var chartData = {};

        chartData['sentiment'] = [];
        chartData['distribution'] = [];

        for (var i=minYear; i<=maxYear; i++) {
            var yearStr = i.toString(),
                value = 0;

            if (years[yearStr] !== undefined) {
                value = years[yearStr];
            }

            var yearTotal = Math.floor(negative[yearStr] + neutral[yearStr] + positive[yearStr]);

            if(!isNaN(yearTotal)) {
                chartData['distribution'].push({
                    year: yearStr,
                    total: Math.floor((negative[yearStr] + neutral[yearStr] + positive[yearStr]) / basicData['json'].length * 1000) / 10
                });
            } else {
                chartData['distribution'].push({
                        year: yearStr,
                        total: 0
                    });
            }

            var total = 0;
            if (totalDocDistribution[yearStr] !== undefined) {
                total = parseInt(totalDocDistribution[yearStr]);
            }

            chartData['sentiment'].push({
                year: yearStr,
                negative: negative[yearStr],
                neutral: neutral[yearStr],
                positive: positive[yearStr],
                total: total
            });
        }
    }
    return chartData;
}

function updateTimeChart(isPercentageDomain) {
    /* Updates the timeline chart
     * @param {bool} isPercentageDomain Whether to plot the chart as a percentage of search results (true)
     *                                  or real number (false)
     */

    $('.time-chart__outer-svg').attr('class', 'time-chart__outer-svg--hidden');
    $('.time-chart-tab .searching-progress').show();

    var dataSet = timelineData();

    // Assign dataset from function call
    var data = dataSet['sentiment'];

    if(isPercentageDomain) {
        // Assign dataset for line graph
        var dataLine = dataSet['distribution'];

        // Convert data to percentages
        data.forEach(function(d) {
            d.negative = Math.floor(d.negative / d.total * 1000)/10;
            d.neutral = Math.floor(d.neutral / d.total * 1000)/10;
            d.positive = Math.floor(d.positive / d.total * 1000)/10;
        })
    }

    // Determine if we're creating mobile version based on window width
    var mobile = $(window).width() < 768;

    // Set chart variables
    var margin = {top: 60, right: 100, bottom: 100, left: 50},
        container = d3.select('.time-chart'),
        width = parseInt(container.style('width'))
            - parseInt(container.style('padding-left'))
            - parseInt(container.style('padding-right')),
        height = mobile ? 750 : 500;

    // mobile margin adjustments
    if(mobile) {
        margin.bottom = 125;
        margin.right = 50;
    }

    // adjust for margins
    width = width - margin.left - margin.right;
    height = height - margin.top - margin.bottom;

    // Set scales and ranges
    if (mobile) {
        var x = d3.scale.linear()
            .range([0, width]);

        var y = d3.scale.ordinal()
            .rangeRoundBands([0, height], .1);

    } else {
        var x = d3.scale.ordinal()
            .rangeRoundBands([0, width],.1);

        var y = d3.scale.linear()
            .range([height, 0]);
    }

    var color = d3.scale.ordinal()
        .range(["#d9534f", "#727272", "#5cb85c"]);

    // Set axes
    if(mobile) {
        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom")
            .outerTickSize(0)
            .innerTickSize(-height + 5)
            .ticks(5);

        var yAxis = d3.svg.axis()
            .scale(y)
            .outerTickSize(0)
            .orient("left");

    } else {
        var xAxis = d3.svg.axis()
            .scale(x)
            .outerTickSize(0)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left")
            .outerTickSize(0)
            .innerTickSize(-width + 5)
            .ticks(5);
    }

    // Create svg outer and inner elements
    var svg = d3.select(".time-chart").append("svg")
        .attr("class", "time-chart__outer-svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("class", "time-chart__inner-g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Assign scale domains
    if(mobile) {
        x.domain([0, d3.max(data, function(d) {
            return d.positive + d.neutral + d.negative;
        })]);
        y.domain(data.map(function(d) { return d.year; }));
    } else {
        x.domain(data.map(function(d) { return d.year; }));
        if(isPercentageDomain) {
            y.domain([0, d3.max([
                d3.max(data, function(d) {
                return d.positive + d.neutral + d.negative;
            }),
            d3.max(dataLine, function(d) {
                return d.total;
            })])])
        } else {
            y.domain([0, d3.max(data, function(d) {
                return d.positive + d.neutral + d.negative;
            })]);
        }
    }
    color.domain(d3.keys(data[0]).filter(function(d) { return d !== "year" && d !== "total"; }));

    // Bind colors and coordinates to each year/sentiment
    data.forEach(function(d) {
        var xy0 = 0;
        d.sentiment = color.domain().map(function(name) {
            return {
                name: name,
                year: d['year'],
                xy0: xy0,
                xy1: xy0 += +d[name]
            };
        });
        d.total = d.sentiment[d.sentiment.length -1].xy1;
    });

    if(mobile) {
        // add x axis
        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        // add x axis label
        svg.append("text")
            .attr("class", "x label")
            .attr("text-anchor", "middle")
            .attr("x", width/2)
            .attr("y", height + margin.bottom - 90)
            .attr("dy", ".71em")
            .text(function() {
                return isPercentageDomain ? "% out of all documents" : "Number of documents";
            });

        // add y axis labels with links to search
        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
            .selectAll("text")
            .filter(function(d) { return typeof(d) == "string"; })
            .style("cursor", "pointer")
            .on("click", function(d) {
                document.location.href = "search?query=&fromYear="+ d + "&toYear=" + d;
            });
    } else {
        // add x axis labels with links to search
        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis)
            .selectAll("text")
            .filter(function(d) { return typeof(d) == "string"; })
            .style("cursor", "pointer")
            .on("click", function(d) {
                document.location.href = "search?query=&fromYear="+ d + "&toYear=" + d;
            })
            .style("text-anchor", "end")
            .attr("dx", "-.8em")
            .attr("dy", ".15em")
            .attr("transform", "rotate(-65)")
            .attr("data-toggle", "tooltip")
            .attr("title", function(d) {
                return d + "</br>" + totalDocDistribution[d] + " letters";
            });

        // add x axis label
        svg.append("text")
            .attr("class", "x label")
            .attr("text-anchor", "end")
            .attr("x", width/2)
            .attr("y", height + margin.bottom - 30)
            .attr("dy", ".71em")
            .text("Year");

        // add y axis
        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis);

        // add y axis label
        svg.append("text")
            .attr("class", "y label")
            .attr("transform", "rotate(-90)")
            .attr("y", "-50")
            .attr("x", function() { return -height / 2; })
            .attr("dy", ".71em")
            .style("text-anchor", "middle")
            .text(function() {
                return isPercentageDomain ? "% out of all documents" : "Number of documents";
            });
    }

    // add scope title
    svg.append("text")
        .attr("class", "time-chart__domain-title")
        .attr("text-anchor", "middle")
        .attr("x", width/2)
        .attr("y", -50)
        .attr("dy", ".71em")
        .text(function() {
            return isPercentageDomain ? "Viewing search results percentage out of all documents" : "Viewing number of documents per year";
        });

    // add scope toggle
    svg.append("text")
        .attr("class", "time-chart__domain-toggle")
        .attr("text-anchor", "middle")
        .attr("x", width/2)
        .attr("y", -30)
        .attr("dy", ".71em")
        .text(function() {
            return isPercentageDomain ? "(See number of documents per year)" : "(See search results percentage out of all documents)" ;
        })
        .style("cursor", "pointer")
        .on("click", function() {
            return isPercentageDomain ? updateTimeChart(false) : updateTimeChart(true);
        });

    // add g element for each year's bars
    var year = svg.selectAll(".year")
        .data(data)
        .enter().append("g")
        .attr("class", function(d) { return "time-chart__year " + d.year; })
        .attr("transform", function(d) {
            if(mobile) {
                return "translate(0," + y(d.year) + ")";
            } else {
                return "translate(" + x(d.year) + ",0)";
            }
        });

    // add rect elements with sentiment bars and links to new searches
    year.selectAll("rect")
        .data(function(d) { return d.sentiment; })
        .enter()
        .append("a")
        .attr("class", function(d) { return "time-chart__bar--" + d.name.toLowerCase(); })
        .attr("id", function(d) { return d.name.toLowerCase() + '-' + d.year; })
        .attr("xlink:href", function(d) {
            return "search?query=&fromYear="+ d.year + "&toYear=" + d.year + "&sentiment=" + d.name.toLowerCase();
        })
        .attr("data-toggle", "tooltip")
        .attr("title", function(d) {
            var sentValue = parseFloat(d.xy1 - d.xy0).toFixed(1);
            return d.year + "</br>" + d.name + ": " + (sentValue % 1 === 0 ? parseInt(sentValue) : sentValue) +
                (isPercentageDomain ? "%" : "");
        })
        .append("rect")
        .attr("class", function(d) { return "time-chart__bar--" + d.name.toLowerCase(); })
        .attr("width", function(d) {
            return mobile ? (isNaN(d.xy1) ? null : x(d.xy1) - x(d.xy0)) : x.rangeBand();
        })
        .attr("y", function(d) {
            return mobile ? null : (isNaN(d.xy1) ? null : y(d.xy1));
        })
        .attr("height", function(d) {
            return mobile ? y.rangeBand() : (isNaN(d.xy1) ? null : y(d.xy0) - y(d.xy1));
        })
        .attr("x", function(d) {
            return mobile ? (isNaN(d.xy0) ? null : x(d.xy0)) : null; })
        .style("fill", function(d) { return color(d.name); });

    // Determine height and width of bars
    var halfBar;
    if(mobile) {
        halfBar = d3.select('.time-chart__year').select('rect').node().getBBox().height / 2;
    } else {
        halfBar = d3.select('.time-chart__year').select('rect').node().getBBox().width / 2;
    }

    if(mobile) {
        var line = d3.svg.line()
            .x(function(d) { return x(d.total); })
            .y(function(d) { return y(d.year) + halfBar; });
    } else {
        var line = d3.svg.line()
            .x(function(d) { return x(d.year) + halfBar; })
            .y(function(d) { return y(d.total); });
    }

    if(isPercentageDomain) {
        svg.append("path")
            .datum(dataLine)
            .attr("class", "time-chart__line")
            .attr("d", line);

        if(mobile) {
            svg.selectAll("dot")
                .data(dataLine)
                .enter().append("circle")
                .attr("class", "time-chart__dot")
                .attr("r", 3.5)
                .attr("cx", function(d) { return x(d.total); })
                .attr("cy", function(d) { return y(d.year) + halfBar; })
                .attr("data-toggle", "tooltip")
                .attr("data-placement", "right")
                .attr("title", function(d) {
                    return d.year + "</br>" + d.total + (d.total == 0 ? "results" : "% of results");
                });
        } else {
            svg.selectAll("dot")
                .data(dataLine)
                .enter().append("circle")
                .attr("class", "time-chart__dot")
                .attr("r", 3.5)
                .attr("onmouseover", "evt.target.setAttribute('r', '7');")
                .attr("onmouseout", "evt.target.setAttribute('r', '3');")
                .attr("cx", function(d) { return x(d.year) + halfBar; })
                .attr("cy", function(d) { return y(d.total); })
                .attr("data-toggle", "tooltip")
                .attr("data-placement", "top")
                .attr("title", function(d) {
                    return d.year + "</br>" + d.total + (d.total == 0 ? "results" : "% of results");
                })
        }
    }

    // create legend element
    var legend = svg.selectAll(".legend")
        .data(color.domain().slice().reverse())
        .enter().append("a")
        .attr("xlink:href", function(d) {
            return "search?query=&sentiment=" + d.toLowerCase();
        })
        .append("g")
        .attr("class", "legend")
        .attr("transform", function(d, i) {
            return mobile ? "translate(-50," + ((height + 60)+ i * 20) + ")" : "translate(0," + i * 20 + ")";
        });

    // add legend colors
    legend.append("rect")
        .attr("x", width + 2)
        .attr("width", 18)
        .attr("height", 18)
        .style("fill", color);

    // add legend text
    legend.append("text")
        .attr("x", width + 22)
        .attr("y", 9)
        .attr("dy", ".35em")
        .style("text-anchor", "start")
        .text(function(d) { return d; });

    // Legend for distribution over time line
    if(isPercentageDomain) {
        svg.append("line")
            .attr("class", "legend time-chart__line")
            .attr("x1", function(d) {
                return mobile ? width / 2 : width + 2;
            })
            .attr("x2", function(d) {
                return mobile ? width / 2 + 18 : width + 20;
            })
            .attr("y1", function(d) {
                return mobile ? height + 90 : "100";
            })
            .attr("y2", function(d) {
                return mobile ? height + 90 : "100";
            });

        svg.append("circle")
            .attr("class", "legend time-chart__dot")
            .attr("r", 3.5)
            .attr("cx", function(d) {
                return mobile ? width / 2 + 9 : (width + 2 + width + 20) / 2;
            })
            .attr("cy", function(d) {
                return mobile ? height + 90 : "100";
            });

        svg.append("text")
            .attr("class", "legend")
            .attr("x", function(d) {
                return mobile ? width / 2 + 20 : width + 22;
            })
            .attr("y", function(d) {
                return mobile ? height + 90 : "100";
            })
            .attr("dy", ".35em")
            .style("text-anchor", "start")
            .text("% results");

        svg.append("text")
            .attr("class", "legend")
            .attr("x", function(d) {
                return mobile ? width / 2 + 20 : width + 22;
            })
            .attr("y", function(d) {
                return mobile ? height + 110 : "120";
            })
            .attr("dy", ".35em")
            .style("text-anchor", "start")
            .text("over time");
    }



    timeChartNeedsUpdate = false;

    $('.time-chart-tab .searching-progress').hide();
    $('.time-chart-tab__description').show();
    $('.time-chart__outer-svg--hidden').remove();

    // Initialize bootstrap tooltip API for hover tooltips
    $(function () {
        $('a[class^=time-chart__bar]').tooltip({
            'container': 'body',
            'placement': 'auto right',
            'html': true,
            'template': '<div class="tooltip timechart__tooltip" role="tooltip">' +
            '<div class="tooltip-arrow timechart__tooltip-arrow"></div>' +
            '<div class="tooltip-inner timechart__tooltip-inner"></div>' +
            '</div>'
        })
    });

    // Initialize bootstrap tooltip API for hover tooltips
    $(function () {
        $('#timeChart').find('.time-chart__dot').tooltip({
            'container': 'body',
            'html': true,
            'template': '<div class="tooltip timechart__tooltip" role="tooltip">' +
            '<div class="tooltip-arrow timechart__tooltip-arrow"></div>' +
            '<div class="tooltip-inner timechart__tooltip-inner"></div>' +
            '</div>'
        })
    });

    // Initialize bootstrap tooltip API for hover tooltips
    $(function () {
        $('.x.axis').find('text').tooltip({
            'container': 'body',
            'placement': 'auto top',
            'html': true,
            'template': '<div class="tooltip timechart__tooltip" role="tooltip">' +
            '<div class="tooltip-arrow timechart__tooltip-arrow"></div>' +
            '<div class="tooltip-inner timechart__tooltip-inner"></div>' +
            '</div>'
        })
    });
}

// Invoked when new basic data is downloaded
$(document).on("basicDataLoaded", function(e, data) {
    if (data != null) {
        timeChartNeedsUpdate = true;
        if ($("#tab-timeline").css('display') != "none") {
            updateTimeChart(false);
        }
    }
});

$('#tab-timeline').on('click', '.time-chart-tab__toggle-domain', function(e) {
    timeChartNeedsUpdate = true;
    if($(this).hasClass('time-chart-tab__toggle-domain--results')) {
        $(this).addClass('time-chart-tab__toggle-domain--all')
            .removeClass('time-chart-tab__toggle-domain--results')
            .html('View percentage out of all documents');
        updateTimeChart(true);
    } else if($(this).hasClass('time-chart-tab__toggle-domain--all')) {
        $(this).addClass('time-chart-tab__toggle-domain--results')
            .removeClass('time-chart-tab__toggle-domain--all')
            .html('View percentage out of search results');
        updateTimeChart(false);
    }
});

/*
  Geographic tab
 */

var mapIsSetup = false,
    map,
    markers = [],
    lines = [],
    infoWindows = [];

// Load Maps API package
google.load("maps", "3", {other_params:'sensor=false'});

// Performs initial map setup
function setupMap() {
    $('.geographic-chart-tab .searching-progress').show();
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
    $('.geographic-chart-tab .searching-progress').hide();
    $('.geographic-chart-tab__description').show();
}
// Invoked when new city data is downloaded
$(document).on("cityDataLoaded", function(e, data) {
    if (data != null && data != cityData) {
        cityData = data;
        mapNeedRerender = true;
        if ($("#tab-geographic").css('display') != "none") {
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

// Changes the query's location filter to the given city
// id must be a key in the NormalizedPlace table
// direction must be string 'to' or 'from'
function searchCity(id, direction) {

    var getParams = stringifyUrlQuery();

    if (direction === 'from') {
        if (getParams.includes('&fromPlaceId')) {
            getParams = getParams.replace(/fromPlaceId=[0-9]*/, 'fromPlaceId=' + id);
        } else {
            getParams += "&fromPlaceId=" + id;
        }
    } else if (direction === 'to') {
        if (getParams.includes('&toPlaceId')) {
            getParams = getParams.replace(/toPlaceId=[0-9]*/, 'toPlaceId=' + id);
        } else {
            getParams += "&toPlaceId=" + id;
        }
    }

    window.location = "search" + getParams;
}

//Given an r, g, b, returns a string representing a color in html
//Borrowed from the internet
function rgbToHtml(r, g, b) {
    var decColor = r + 256 * g + 65536 * b,
        str = decColor.toString(16);

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
    var projection = map.getProjection(),
        p0 = projection.fromLatLngToPoint(startLatLng),
        p2 = projection.fromLatLngToPoint(endLatLng),
        xDiff = p2.x-p0.x,
        yDiff = p2.y-p0.y,
        dist = Math.sqrt(Math.pow(xDiff, 2) + Math.pow(yDiff, 2));

    dist *= curvyness;

    var angle = Math.atan2(yDiff, xDiff) + Math.PI/2,
        mid = new google.maps.Point((p0.x+p2.x)/2, (p0.y+p2.y)/2),
        p1 = new google.maps.Point(mid.x + dist * Math.cos(angle),
            mid.y + dist*Math.sin(angle)),
        SEGMENTS = 10,
        prevLatLng,
        currentLatLng,
        first = true;

    for (var i = 0; i <= SEGMENTS; i += 1) {
        var t = i / SEGMENTS,
            x = Math.pow(1 - t, 2) * p0.x + 2 * (1 - t) * t * p1.x
                + Math.pow(t, 2) * p2.x,
            y = Math.pow(1 - t, 2) * p0.y + 2 * (1 - t) * t * p1.y
                + Math.pow(t, 2) * p2.y;

        currentLatLng = projection.fromPointToLatLng(new google.maps.Point(x, y));

        if (!first) {
            var r = Math.floor(255*t);
                color = rgbToHtml(r, 0, 255 - r),
                coords = [prevLatLng, currentLatLng],
                line = new google.maps.Polyline({
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
    for (var i=0; i<lines.length; i++) {
        lines[i].setMap(null);
    }

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
    } else {
        //Otherwise just show the first few (which are the ones with
        //the most traffic)
        maxMarkers = Math.min(cityData.length,
            Math.ceil(Math.pow(map.getZoom(), 1.2)));
    }

    //Add the markers
    for (i = 0; i < maxMarkers; i++) {
        var city = cityData[i],
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(city['lat'], city['lng']),
                map: map,
                title: city['name']
            }),
            onClickFunction = function() {
            cityClicked(arguments.callee.markerIndex);
        };

        onClickFunction.markerIndex = i;
        google.maps.event.addListener(marker, 'click', onClickFunction);
        markers.push(marker);

        var contentHtml = "<span class='geography__city-name'>" + city['name'] + "</span><br />"
            + "<b>" + (parseInt(city['incoming']) + parseInt(city['outgoing'])) + " Letters</b><br />"
            + "<a class='geography__city-link' id='to-city-" + city['id'] + "' onClick='searchCity(" + city['id'] + ", &apos;to&apos;)'>" + city['incoming'] + " Incoming Letters</a><br />"
            + "<a class='geography__city-link' id='from-city-" + city['id'] + "' onClick='searchCity(" + city['id'] + ", &apos;from&apos;)'>" + city['outgoing'] + " Outgoing Letters</a>";

        var infowindow = new google.maps.InfoWindow({
            content: contentHtml
        });
        infoWindows.push(infowindow);
    }
    mapNeedsRerender = false;
}

/*
  Word Counts tab
 */

var chartsNeedRerender = false;
function updateWordCharts() {
    $('.word-chart-tab .searching-progress').show();
    $('.word-chart').removeClass("hidden");
    $('.word-chart__outer-svg').remove();

    wordChart(chartData[2], "#personChart");
    wordChart(chartData[1], "#placeChart");
    wordChart(chartData[0], "#wordChart");

    $('.word-chart-tab .searching-progress').hide();
    $('.word-chart-tab__description').show();

    chartsNeedRerender = false;
}
// Invoked when new chart data is downloaded
$(document).on("chartDataLoaded", function(e, data) {
    if (data != null && data != chartData) {
        chartData = data;
        chartsNeedRerender = true;
        if ($("#tab-charts").css('display') != "none") {
            updateWordCharts();
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
        labelSpace = 150, // initial value
        truncateLength = 20, // max label letters before truncation
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
        .attr("class", "word-chart__label-container")
        .attr("width", labelSpace);

    // Add labels to the label container
    labelContainer.selectAll("text")
        .data(dataSet)
        .enter()
        .append("a")
        .attr("class", "word-chart__label-link")
        .attr("xlink:href", function(d) {
            return d.link;
        })
        .attr("data-toggle", function(d) {
            if (d.text.length > truncateLength) {
                return "tooltip";
            }
        })
        .attr("data-placement", function(d) {
            if (d.text.length > truncateLength) {
                return "right";
            }
        })
        .attr("title", function(d) {
            return d.text;
        })
        .attr("data-ga-event", function(d) {
            return d.text;
        })
        .append("text")
        .attr("class", "word-chart__label-text")
        .attr("transform", function (d, i) {
            return "translate(0," +
                (i * (barHeight + barPadding) ) + ")";
        })
        .attr("dy", "1em")
        .text(function (d) {
            return d.text.length <= truncateLength ? d.text : d.text.slice(0, truncateLength) + '...';
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
            return addCommas(d.weight);
        })
        .attr("x", function (d) {
            return xScale(d.weight) - this.getBBox().width - 2;
        });

    // Initialize bootstrap tooltip API for hover tooltips
    $(function () {
        $('.word-chart-tab').find('[data-toggle="tooltip"]').tooltip({
            'container': 'body',
            'html': true,
            'template': '<div class="tooltip timechart__tooltip" role="tooltip">' +
            '<div class="tooltip-arrow timechart__tooltip-arrow"></div>' +
            '<div class="tooltip-inner timechart__tooltip-inner"></div>' +
            '</div>'
        })
    });
}

/*
    Miscellaneous
 */

// A single event listener for tabs using bootstrap's tabs js
$('a[data-toggle="tab"]').on("shown.bs.tab", function(e) {
    newTabId = $(e.target).attr('href');
    switch(newTabId) {
        case '#tab-content':
            break;
        case '#tab-timeline':
            if (timeChartNeedsUpdate) {
                    updateTimeChart(false);
            }
            break;
        case '#tab-geographic':
            if (mapIsSetup !== true) {
                setupMap();
            } else if (mapNeedsRerender) {
                redrawAllCurves();
                redrawMarkers();
            }
            break;
        case '#tab-charts':
            if (chartsNeedRerender) {
                updateWordCharts();
            }
            break;
    }
});

// Add commas to number from http://www.mredkj.com/javascript/nfbasic.html
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

// Vertical center modals on search results page.
// From http://www.minimit.com/articles/solutions-tutorials/vertical-center-bootstrap-3-modals
var modalVerticalCenterClass = ".modal";
function centerModals($element) {
    var $modals;
    if ($element.length) {
        $modals = $element;
    } else {
        $modals = $(modalVerticalCenterClass + ':visible');
    }
    $modals.each( function(i) {
        var $clone = $(this).clone().css('display', 'block').appendTo('body');
        var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
        top = top > 0 ? top : 0;
        $clone.remove();
        $(this).find('.modal-content').css("margin-top", top);
    });
}
$(modalVerticalCenterClass).on('show.bs.modal', function(e) {
    centerModals($(this));
});
$(window).on('resize', centerModals);