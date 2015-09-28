function queryChanged(){return basicData=null,chartData=null,cityData=null,resultsListItems=null,requestData(),jQuery(".search-results").removeClass("hidden"),!1}function queryUrlForYearAndSentiment(t,e){var a="?query=";return a+=encodeURIComponent(document.getElementById("query").value),a+="&fromYear=",a+=t,a+="&toYear=",a+=t,a+="&fromPersonId=",a+=encodeURIComponent(document.getElementById("fromPersonId").value),a+="&toPersonId=",a+=encodeURIComponent(document.getElementById("toPersonId").value),a+="&fromPlaceId=",a+=encodeURIComponent(document.getElementById("fromPlaceId").value),a+="&toPlaceId=",a+=encodeURIComponent(document.getElementById("toPlaceId").value),a+="&sentiment=",a+=e,a+="&sort=",a+=encodeURIComponent(sortKey),"data/search.php"+a}function requestData(){console.log("RequestData called");var t=!1,e="?query=";e+=encodeURIComponent(document.getElementById("query").value),e+="&fromYear=",e+=encodeURIComponent(document.getElementById("fromYear").value),e+="&toYear=",e+=encodeURIComponent(document.getElementById("toYear").value),e+="&fromPersonId=",e+=encodeURIComponent(document.getElementById("fromPersonId").value),e+="&toPersonId=",e+=encodeURIComponent(document.getElementById("toPersonId").value),e+="&fromPlaceId=",e+=encodeURIComponent(document.getElementById("fromPlaceId").value),e+="&toPlaceId=",e+=encodeURIComponent(document.getElementById("toPlaceId").value),e+="&sentiment=",e+=encodeURIComponent(document.getElementById("sentiment").value),e+="&sort=",e+=encodeURIComponent(sortKey),window.history.pushState({path:e},"",location.origin+location.pathname+e),ga("send","event","search","submit",e);var a="";if(document.getElementById("query").value&&(a="text: <b>"+document.getElementById("query").value+"</b>"),document.getElementById("fromYear").value&&(a+=" starting: <b>"+document.getElementById("fromYear").value+"</b>"),document.getElementById("toYear").value&&(a+=" ending: <b>"+document.getElementById("toYear").value+"</b>"),document.getElementById("fromPersonId").value){var n=document.getElementById("fromPersonId").value;a+=" sender: <b>"+personIdToNames[n]+"</b>"}if(document.getElementById("toPersonId").value){var n=document.getElementById("toPersonId").value;a+=" recipient: <b>"+personIdToNames[n]+"</b>"}if(document.getElementById("fromPlaceId").value){var r=document.getElementById("fromPlaceId").value;a+=" sent from: <b>"+placeIdToNames[r]+"</b>"}if(document.getElementById("toPlaceId").value){var r=document.getElementById("toPlaceId").value;a+=" sent to: <b>"+placeIdToNames[r]+"</b>"}document.getElementById("sentiment").value&&(a+=" with <b>"+document.getElementById("sentiment").value+"</b> sentiment scores"),""==a&&(a="all results."),document.getElementById("humanQuery").innerHTML=a;var o="search_results.php";$.getJSON(o+e,function(t){$(document).trigger("basicDataLoaded",[t])}),t=!0;var o="data/cloud.php";$.getJSON(o+e,function(t){$(document).trigger("chartDataLoaded",[t])}),t=!0;var o="data/cities.php";$.getJSON(o+e,function(t){$(document).trigger("cityDataLoaded",[t])}),t=!0;var o="data/network.php";return $.getJSON(o+e,function(t){$(document).trigger("networkDataLoaded",[t])}),t=!0}function updateDocuments(){var t=($("#documentsList"),basicData.json.length);$("#resultsCount").text(t),$("#totalDocsCount").text(totalDocsCount),$(".search-results__results-summary").removeClass("invisible"),1===t&&(document.getElementById("resultsPlural").innerHTML="result"),$(".search-results__results-summary").addClass("search-results__results-summary--changed").delay(2e3).queue(function(){$(this).removeClass("search-results__results-summary--changed").dequeue()}),paging.setNumber(t).setPage(),$("#sort_"+sortKey).prop("checked",!0)}function updateSentiment(){$(".search-result-list__item-sentiment-score").each(function(){var t=$(this),e=t.attr("data-sentiment");negativeThreshold>e?t.addClass("sentiment-negative"):e>positiveThreshold?t.addClass("sentiment-positive"):t.addClass("sentiment-neutral")})}function updatePage(t){var e=$("#documentsList"),a=basicData.html.slice(t[0],t[1]);e.empty().append(a).first().attr("start",t[0]+1)}function setupMap(){var t={center:new google.maps.LatLng(34,-94),zoom:4,mapTypeId:google.maps.MapTypeId.TERRAIN};map=new google.maps.Map(document.getElementById("map"),t),null!=map.getProjection()?redrawAllCurves():google.maps.event.addListener(map,"projection_changed",redrawAllCurves),redrawMarkers(),google.maps.event.addListener(map,"zoom_changed",redrawMarkers)}function cityClicked(t){for(var e=0;e<infoWindows.length;e++)infoWindows[e].close();infoWindows[t].open(map,markers[t])}function searchCity(t){var e="?query=";e+=encodeURIComponent(document.getElementById("query").value),e+="&location=",e+=encodeURIComponent(t),e+="&sort=",e+=encodeURIComponent(sortKey),window.location="search"+e}function rgbToHtml(t,e,a){for(var n=t+256*e+65536*a,r=n.toString(16);r.length<6;)r="0"+r;return"#"+r}function drawCurve(t,e,a,n){var r=t.getProjection(),o=r.fromLatLngToPoint(e),s=r.fromLatLngToPoint(a),l=s.x-o.x,d=s.y-o.y,i=Math.sqrt(Math.pow(l,2)+Math.pow(d,2));i*=n;for(var c,m,u=Math.atan2(d,l)+Math.PI/2,p=new google.maps.Point((o.x+s.x)/2,(o.y+s.y)/2),g=new google.maps.Point(p.x+i*Math.cos(u),p.y+i*Math.sin(u)),h=10,f=!0,y=0;h>=y;y+=1){var v=y/h,I=Math.pow(1-v,2)*o.x+2*(1-v)*v*g.x+Math.pow(v,2)*s.x,b=Math.pow(1-v,2)*o.y+2*(1-v)*v*g.y+Math.pow(v,2)*s.y;if(m=r.fromPointToLatLng(new google.maps.Point(I,b)),!f){var w=Math.floor(255*v),C=rgbToHtml(w,0,255-w),D=[c,m],_=new google.maps.Polyline({path:D,strokeColor:C,strokeOpacity:.1+.4*Math.abs(v-.5),strokeWeight:5});_.setMap(t),lines.push(_)}c=m,f=!1}}function redrawAllCurves(){for(var t=0;t<lines.length;t++)lines[t].setMap(null);lines=[];for(var t=0;t<basicData.json.length;t++)if(doc=basicData.json[t],null!=doc.srcLat&&null!=doc.dstLat){var e=.1+.2*Math.random();drawCurve(map,new google.maps.LatLng(doc.srcLat,doc.srcLng),new google.maps.LatLng(doc.dstLat,doc.dstLng),e)}}function redrawMarkers(){if(console.log("RedrawMarkers called."),null==cityData)return console.log("Aborting."),void console.log(cityData);for(var t=0;t<markers.length;t++)markers[t].setMap(null);markers=[],infoWindows=[];var e;for(e=map.getZoom()>7?cityData.length:Math.min(cityData.length,Math.ceil(Math.pow(map.getZoom(),1.2))),t=0;e>t;t++){var a=cityData[t],n=new google.maps.Marker({position:new google.maps.LatLng(a.lat,a.lng),map:map,title:a.name}),r=function(){cityClicked(arguments.callee.markerIndex)};r.markerIndex=t,google.maps.event.addListener(n,"click",r),markers.push(n);var o="<a style='color:#0000FF;text-decoration:underline;' onClick='searchCity("+a.id+")'> "+a.name+"</a><br /><b>"+(parseInt(a.incoming)+parseInt(a.outgoing))+" Letters</b><br />"+a.incoming+" Incoming Letters<br />"+a.outgoing+" Outgoing Letters<br />",s=new google.maps.InfoWindow({content:o});infoWindows.push(s)}mapNeedsRerender=!1}function updateWordCharts(){$(".word-chart__outer-svg").remove(),wordChart(chartData[2],"#personChart"),wordChart(chartData[1],"#placeChart"),wordChart(chartData[0],"#wordChart"),chartsNeedRerender=!1}function wordChart(t,e){var a=t,n={top:0,right:0,bottom:0,left:0},r=d3.select(e),o=parseInt(r.style("width"))-parseInt(r.style("padding-left"))-parseInt(r.style("padding-right")),o=o-n.left-n.right,s=20,l=5,d=125,i=(s+l)*a.length-n.top-n.bottom,c=d3.scale.linear().domain([0,d3.max(a,function(t){return parseInt(t.weight,10)})]),m=(d3.scale.ordinal().domain(a.map(function(t){return t.text})).rangeBands([i,0],.1),d3.select(e).append("svg").attr("class","word-chart__outer-svg").attr("width",o+n.left+n.right).attr("height",i+n.top+n.bottom).append("g").attr("class","word-chart__inner-g").attr("width",o).attr("transform","translate("+[n.left,n.top]+")")),u=m.append("g").attr("class","word-chart__label-container");u.selectAll("text").data(a).enter().append("a").attr("class","word-chart__label-link").attr("xlink:href",function(t){return t.link}).append("text").attr("class","word-chart__label-text").attr("transform",function(t,e){return"translate(0,"+e*(s+l)+")"}).attr("dy","1em").text(function(t){return t.text}),d=d3.select(e).select(".word-chart__label-container").node().getBBox().width,c.range([25,o-d]);var p=m.append("g").attr("class","word-chart__bar-container"),g=p.selectAll("g").data(a).enter().append("g").attr("transform",function(t,e){return"translate("+d+","+e*(s+l)+")"});d3.select(m.node().parentNode).style("height",i+n.top+n.bottom+"px"),g.append("rect").attr("class","word-chart__bar").attr("width",function(t){return c(t.weight)}).attr("height",s-1),g.append("text").attr("class","word-chart__item-weight").attr("y",s/2).attr("dy",".35em").text(function(t){return t.weight}).attr("x",function(t){return c(t.weight)-this.getBBox().width-2})}function timelineData(){if(null!=basicData){for(var t={},e={},a={},n={},r=0;r<basicData.json.length;r++){var o=basicData.json[r].date.substring(0,4);void 0===t[o]?t[o]=1:t[o]++,void 0===e[o]&&(e[o]=0,a[o]=0,n[o]=0),basicData.json[r].sentimentScore<negativeThreshold?n[o]++:basicData.json[r].sentimentScore>positiveThreshold?e[o]++:a[o]++}var s=2e3,l=1e3;for(var d in totalDocDistribution)if(parseInt(totalDocDistribution[d])>=15){var i=parseInt(d);s>i&&(s=i),parseInt(d)>l&&(l=i)}for(var c=[["Year","Negative","Neutral","Positive"]],r=s;l>=r;r++){var m=r.toString(),u=0;void 0!==t[m]&&(u=t[m]);var p=0;void 0!==totalDocDistribution[m]&&(p=parseInt(totalDocDistribution[m])),c.push([m,Math.floor(n[m]/p*1e3)/10,Math.floor(a[m]/p*1e3)/10,Math.floor(e[m]/p*1e3)/10])}}return c}function updateTimeChart(){$(".time-chart__outer-svg").remove();var t=timelineData(),e=t.shift(),a=t.map(function(t){var a={};return a[e[0]]=t[0],a[e[1]]=t[1],a[e[2]]=t[2],a[e[3]]=t[3],a}),n={top:20,right:100,bottom:100,left:60},r=d3.select(".time-chart"),o=parseInt(r.style("width"))-parseInt(r.style("padding-left"))-parseInt(r.style("padding-right")),o=o-n.left-n.right,s=500-n.top-n.bottom,l=d3.scale.ordinal().rangeRoundBands([0,o],.1),d=d3.scale.linear().range([s,0]),i=d3.scale.ordinal().range(["#d9534f","#727272","#5cb85c"]),c=d3.svg.axis().scale(l).orient("bottom"),m=d3.svg.axis().scale(d).orient("left").tickValues([0,25,50,75,100]),u=(d3.select(".time-chart").append("div").attr("class","tooltip").style("opacity",0),d3.select(".time-chart").append("svg").attr("class","time-chart__outer-svg").attr("width",o+n.left+n.right).attr("height",s+n.top+n.bottom).append("g").attr("class","time-chart__inner-g").attr("transform","translate("+n.left+","+n.top+")"));l.domain(a.map(function(t){return t.Year})),d.domain([0,100]),i.domain(e.filter(function(t){return"Year"!==t})),a.forEach(function(t){var e=0;t.sentiment=i.domain().map(function(a){return{name:a,year:t.Year,y0:e,y1:e+=+t[a]}}),t.total=t.sentiment[t.sentiment.length-1].y1}),u.append("g").attr("class","x axis").attr("transform","translate(0,"+s+")").call(c).selectAll("text").style("text-anchor","end").attr("dx","-.8em").attr("dy",".15em").attr("transform","rotate(-65)"),u.append("text").attr("class","x label").attr("text-anchor","end").attr("x",o/2).attr("y",s+n.bottom-15).attr("dy",".71em").text("Year"),u.append("g").attr("class","y axis").call(m),u.append("text").attr("class","y label").attr("transform","rotate(-90)").attr("y","-60").attr("x","-60").attr("dy",".71em").style("text-anchor","end").text("Percentage out of all documents");var p=u.selectAll(".year").data(a).enter().append("g").attr("class",function(t){return"time-chart__year "+t.Year}).attr("transform",function(t){return"translate("+l(t.Year)+",0)"});p.selectAll("rect").data(function(t){return t.sentiment}).enter().append("a").attr("class",function(t){return"time-chart__bar--"+t.name.toLowerCase()}).attr("xlink:href",function(t){return"search?query=&fromYear="+t.year+"&toYear="+t.year+"&sentiment="+t.name.toLowerCase()}).attr("data-toggle","tooltip").attr("data-placement","right").attr("title",function(t){var e=parseFloat(t.y1-t.y0).toFixed(1);return t.year+"</br>"+t.name+": "+(e%1===0?parseInt(e):e)}).append("rect").attr("class",function(t){return"time-chart__bar--"+t.name.toLowerCase()}).attr("width",l.rangeBand()).attr("y",function(t){return d(t.y1)}).attr("height",function(t){return d(t.y0)-d(t.y1)}).style("fill",function(t){return i(t.name)});var g=u.selectAll(".legend").data(i.domain().slice().reverse()).enter().append("g").attr("class","legend").attr("transform",function(t,e){return"translate(0,"+20*e+")"});g.append("rect").attr("x",o+n.right-18).attr("width",18).attr("height",18).style("fill",i),g.append("text").attr("x",o+n.right-24).attr("y",9).attr("dy",".35em").style("text-anchor","end").text(function(t){return t}),$(function(){$('[data-toggle="tooltip"]').tooltip({container:"body",html:!0,template:'<div class="tooltip timechart__tooltip" role="tooltip"><div class="tooltip-arrow timechart__tooltip-arrow"></div><div class="tooltip-inner timechart__tooltip-inner"></div></div>'})})}var basicData=null,chartData=null,cityData=null,sortKey="date",positiveThreshold=2,negativeThreshold=-2,resultsListItems=null,paging=$(".pagination").paging(0,pagingOpts);paging.setOptions({perpage:20}),paging.setOptions({onSelect:function(t){updatePage(this.slice),updateSentiment(),showPager(this.pages)}}),$(document).on("basicDataLoaded",function(t,e){null!=e&&e!=basicData?(basicData=e,updateDocuments()):alert("?")}),$("input:radio[name=sort]").on("click",function(t){sortKey=$("input:radio[name=sort]:checked").val(),queryChanged()});var mapIsSetup=!1,map,markers=[],lines=[],infoWindows=[];google.load("maps","3",{other_params:"sensor=false"}),$(document).on("cityDataLoaded",function(t,e){null!=e&&e!=cityData&&(cityData=e,mapNeedRerender=!0,"none"!=$("#tab-geographic")[0].style.display&&(redrawAllCurves(),redrawMarkers()))});var chartsNeedRerender=!1;$(document).on("chartDataLoaded",function(t,e){null!=e&&e!=chartData&&(chartData=e,chartsNeedRerender=!0,"none"!=$("#tab-charts").css("display")&&updateWordCharts())});var timeChart,timeChartNeedsUpdate=!0;$(document).on("basicDataLoaded",function(t,e){null!=e&&(timeChartNeedsUpdate=!0,"none"!=$("#tab-timeline").css("display")&&updateTimeChart())}),$('a[data-toggle="tab"]').on("shown.bs.tab",function(t){switch(newTabId=$(t.target).attr("href"),newTabId){case"#tab-content":break;case"#tab-timeline":timeChartNeedsUpdate&&updateTimeChart();break;case"#tab-geographic":mapIsSetup!==!0?setupMap():mapNeedsRerender&&(redrawAllCurves(),redrawMarkers());break;case"#tab-charts":chartsNeedRerender&&updateWordCharts()}});