function queryChanged(){return basicData=null,chartData=null,cityData=null,resultsListItems=null,requestData(),$(".search-results").removeClass("hidden"),!1}function stringifyUrlQuery(){var t="?query=";return t+=encodeURIComponent(document.getElementById("query").value),encodeURIComponent(document.getElementById("fromYear").value)&&(t+="&fromYear=",t+=encodeURIComponent(document.getElementById("fromYear").value)),encodeURIComponent(document.getElementById("toYear").value)&&(t+="&toYear=",t+=encodeURIComponent(document.getElementById("toYear").value)),encodeURIComponent(document.getElementById("fromPersonId").value)&&(t+="&fromPersonId=",t+=encodeURIComponent(document.getElementById("fromPersonId").value)),encodeURIComponent(document.getElementById("toPersonId").value)&&(t+="&toPersonId=",t+=encodeURIComponent(document.getElementById("toPersonId").value)),encodeURIComponent(document.getElementById("fromPlaceId").value)&&(t+="&fromPlaceId=",t+=encodeURIComponent(document.getElementById("fromPlaceId").value)),encodeURIComponent(document.getElementById("toPlaceId").value)&&(t+="&toPlaceId=",t+=encodeURIComponent(document.getElementById("toPlaceId").value)),encodeURIComponent(document.getElementById("sentiment").value)&&(t+="&sentiment=",t+=encodeURIComponent(document.getElementById("sentiment").value)),t+="&sort=",t+=encodeURIComponent(sortKey)}function requestData(){var t=stringifyUrlQuery(),e="";if(console.log("RequestData called"),window.history.pushState({path:t},"",location.origin+location.pathname+t),ga("send","event","search","submit",t),document.getElementById("query").value&&(e="text: <b>"+document.getElementById("query").value+"</b>"),document.getElementById("fromYear").value&&(e+=" starting: <b>"+document.getElementById("fromYear").value+"</b>"),document.getElementById("toYear").value&&(e+=" ending: <b>"+document.getElementById("toYear").value+"</b>"),document.getElementById("fromPersonId").value){var a=document.getElementById("fromPersonId").value;e+=" sender: <b>"+personIdToNames[a]+"</b>"}if(document.getElementById("toPersonId").value){var a=document.getElementById("toPersonId").value;e+=" recipient: <b>"+personIdToNames[a]+"</b>"}if(document.getElementById("fromPlaceId").value){var r=document.getElementById("fromPlaceId").value;e+=" sent from: <b>"+placeIdToNames[r]+"</b>"}if(document.getElementById("toPlaceId").value){var r=document.getElementById("toPlaceId").value;e+=" sent to: <b>"+placeIdToNames[r]+"</b>"}document.getElementById("sentiment").value&&(e+=" with <b>"+document.getElementById("sentiment").value+"</b> sentiment scores"),""==e&&(e="all results."),document.getElementById("humanQuery").innerHTML=e,$.getJSON("search_results.php"+t,function(t){$(document).trigger("basicDataLoaded",[t])}),$.getJSON("data/cloud.php"+t,function(t){$(document).trigger("chartDataLoaded",[t])}),$.getJSON("data/cities.php"+t,function(t){$(document).trigger("cityDataLoaded",[t])}),$.getJSON("data/network.php"+t,function(t){$(document).trigger("networkDataLoaded",[t])})}function updateDocuments(){var t=$("search-results-list"),e=$(".documents-tab .searching-progress"),a=$(".documents-tab .search-results__sort"),r=$(".search-results__results-summary"),n=basicData.json.length;t.hide(),e.show(),a.hide(),$("#resultsCount").text(addCommas(n)),$("#totalDocsCount").text(addCommas(totalDocsCount)),r.slideDown(),1===n&&(document.getElementById("resultsPlural").innerHTML="result"),r.addClass("search-results__results-summary--changed").delay(2e3).queue(function(){$(this).removeClass("search-results__results-summary--changed").dequeue()}),$paging.setNumber(n).setPage(),$("#sort_"+sortKey).prop("checked",!0),e.hide(),t.show(),a.show()}function updateSentiment(){$(".search-result-list__item-sentiment-score").each(function(){var t=$(this),e=t.attr("data-sentiment");negativeThreshold>e?t.addClass("sentiment-negative"):e>positiveThreshold?t.addClass("sentiment-positive"):t.addClass("sentiment-neutral")})}function updatePage(t){var e=$("#documentsList"),a=basicData.html.slice(t[0],t[1]);e.empty().append(a).first().attr("start",t[0]+1)}function setupMap(){$(".geographic-chart-tab .searching-progress").show();var t={center:new google.maps.LatLng(34,-94),zoom:4,mapTypeId:google.maps.MapTypeId.TERRAIN};map=new google.maps.Map(document.getElementById("map"),t),null!=map.getProjection()?redrawAllCurves():google.maps.event.addListener(map,"projection_changed",redrawAllCurves),redrawMarkers(),google.maps.event.addListener(map,"zoom_changed",redrawMarkers),$(".geographic-chart-tab .searching-progress").hide()}function cityClicked(t){for(var e=0;e<infoWindows.length;e++)infoWindows[e].close();infoWindows[t].open(map,markers[t])}function searchCity(t,e){var a=stringifyUrlQuery();"from"===e?a.includes("&fromPlaceId")?a=a.replace(/fromPlaceId=[0-9]*/,"fromPlaceId="+t):a+="&fromPlaceId="+t:"to"===e&&(a.includes("&toPlaceId")?a=a.replace(/toPlaceId=[0-9]*/,"toPlaceId="+t):a+="&toPlaceId="+t),window.location="search"+a}function rgbToHtml(t,e,a){for(var r=t+256*e+65536*a,n=r.toString(16);n.length<6;)n="0"+n;return"#"+n}function drawCurve(t,e,a,r){var n=t.getProjection(),o=n.fromLatLngToPoint(e),s=n.fromLatLngToPoint(a),i=s.x-o.x,l=s.y-o.y,d=Math.sqrt(Math.pow(i,2)+Math.pow(l,2));d*=r;for(var c,m,u=Math.atan2(l,i)+Math.PI/2,p=new google.maps.Point((o.x+s.x)/2,(o.y+s.y)/2),g=new google.maps.Point(p.x+d*Math.cos(u),p.y+d*Math.sin(u)),h=10,f=!0,y=0;h>=y;y+=1){var v=y/h,I=Math.pow(1-v,2)*o.x+2*(1-v)*v*g.x+Math.pow(v,2)*s.x,w=Math.pow(1-v,2)*o.y+2*(1-v)*v*g.y+Math.pow(v,2)*s.y;if(m=n.fromPointToLatLng(new google.maps.Point(I,w)),!f){var b=Math.floor(255*v);color=rgbToHtml(b,0,255-b),coords=[c,m],line=new google.maps.Polyline({path:coords,strokeColor:color,strokeOpacity:.1+.4*Math.abs(v-.5),strokeWeight:5}),line.setMap(t),lines.push(line)}c=m,f=!1}}function redrawAllCurves(){for(var t=0;t<lines.length;t++)lines[t].setMap(null);lines=[];for(var t=0;t<basicData.json.length;t++)if(doc=basicData.json[t],null!=doc.srcLat&&null!=doc.dstLat){var e=.1+.2*Math.random();drawCurve(map,new google.maps.LatLng(doc.srcLat,doc.srcLng),new google.maps.LatLng(doc.dstLat,doc.dstLng),e)}}function redrawMarkers(){if(console.log("RedrawMarkers called."),null==cityData)return console.log("Aborting."),void console.log(cityData);for(var t=0;t<markers.length;t++)markers[t].setMap(null);markers=[],infoWindows=[];var e;for(e=map.getZoom()>7?cityData.length:Math.min(cityData.length,Math.ceil(Math.pow(map.getZoom(),1.2))),t=0;e>t;t++){var a=cityData[t],r=new google.maps.Marker({position:new google.maps.LatLng(a.lat,a.lng),map:map,title:a.name}),n=function(){cityClicked(arguments.callee.markerIndex)};n.markerIndex=t,google.maps.event.addListener(r,"click",n),markers.push(r);var o="<span class='geography__city-name'>"+a.name+"</span><br /><b>"+(parseInt(a.incoming)+parseInt(a.outgoing))+" Letters</b><br /><a class='geography__city-link' onClick='searchCity("+a.id+", &apos;to&apos;)'>"+a.incoming+" Incoming Letters</a><br /><a class='geography__city-link' onClick='searchCity("+a.id+", &apos;from&apos;)'>"+a.outgoing+" Outgoing Letters</a>",s=new google.maps.InfoWindow({content:o});infoWindows.push(s)}mapNeedsRerender=!1}function updateWordCharts(){$(".word-chart-tab .searching-progress").show(),$(".word-chart").removeClass("hidden"),$(".word-chart__outer-svg").remove(),wordChart(chartData[2],"#personChart"),wordChart(chartData[1],"#placeChart"),wordChart(chartData[0],"#wordChart"),$(".word-chart-tab .searching-progress").hide(),chartsNeedRerender=!1}function wordChart(t,e){var a=t,r={top:0,right:0,bottom:0,left:0},n=d3.select(e),o=parseInt(n.style("width"))-parseInt(n.style("padding-left"))-parseInt(n.style("padding-right")),o=o-r.left-r.right,s=20,i=5,l=150,d=(s+i)*a.length-r.top-r.bottom,c=d3.scale.linear().domain([0,d3.max(a,function(t){return parseInt(t.weight,10)})]),m=(d3.scale.ordinal().domain(a.map(function(t){return t.text})).rangeBands([d,0],.1),d3.select(e).append("svg").attr("class","word-chart__outer-svg").attr("width",o+r.left+r.right).attr("height",d+r.top+r.bottom).append("g").attr("class","word-chart__inner-g").attr("width",o).attr("transform","translate("+[r.left,r.top]+")")),u=m.append("g").attr("class","word-chart__label-container").attr("width",l);u.selectAll("text").data(a).enter().append("a").attr("class","word-chart__label-link").attr("xlink:href",function(t){return t.link}).append("text").attr("class","word-chart__label-text").attr("transform",function(t,e){return"translate(0,"+e*(s+i)+")"}).attr("dy","1em").text(function(t){return t.text.length<20?t.text:t.text.slice(0,20)+"..."}),l=d3.select(e).select(".word-chart__label-container").node().getBBox().width,c.range([25,o-l]);var p=m.append("g").attr("class","word-chart__bar-container"),g=p.selectAll("g").data(a).enter().append("g").attr("transform",function(t,e){return"translate("+l+","+e*(s+i)+")"});d3.select(m.node().parentNode).style("height",d+r.top+r.bottom+"px"),g.append("rect").attr("class","word-chart__bar").attr("width",function(t){return c(t.weight)}).attr("height",s-1),g.append("text").attr("class","word-chart__item-weight").attr("y",s/2).attr("dy",".35em").text(function(t){return addCommas(t.weight)}).attr("x",function(t){return c(t.weight)-this.getBBox().width-2})}function timelineData(){if(null!=basicData){for(var t={},e={},a={},r={},n=0;n<basicData.json.length;n++){var o=basicData.json[n].date.substring(0,4);void 0===t[o]?t[o]=1:t[o]++,void 0===e[o]&&(e[o]=0,a[o]=0,r[o]=0),basicData.json[n].sentimentScore<negativeThreshold?r[o]++:basicData.json[n].sentimentScore>positiveThreshold?e[o]++:a[o]++}var s=2e3,i=1e3;for(var l in totalDocDistribution)if(parseInt(totalDocDistribution[l])>=15){var d=parseInt(l);s>d&&(s=d),parseInt(l)>i&&(i=d)}for(var c=[["Year","Negative","Neutral","Positive"]],n=s;i>=n;n++){var m=n.toString(),u=0;void 0!==t[m]&&(u=t[m]);var p=0;void 0!==totalDocDistribution[m]&&(p=parseInt(totalDocDistribution[m])),c.push([m,Math.floor(r[m]/p*1e3)/10,Math.floor(a[m]/p*1e3)/10,Math.floor(e[m]/p*1e3)/10])}}return c}function updateTimeChart(){$(".time-chart__outer-svg").remove(),$(".time-chart-tab .searching-progress").show();var t=timelineData(),e=t.shift(),a=t.map(function(t){var a={};return a[e[0]]=t[0],a[e[1]]=t[1],a[e[2]]=t[2],a[e[3]]=t[3],a}),r={top:20,right:100,bottom:100,left:60},n=d3.select(".time-chart"),o=parseInt(n.style("width"))-parseInt(n.style("padding-left"))-parseInt(n.style("padding-right")),o=o-r.left-r.right,s=500-r.top-r.bottom,i=d3.scale.ordinal().rangeRoundBands([0,o],.1),l=d3.scale.linear().range([s,0]),d=d3.scale.ordinal().range(["#d9534f","#727272","#5cb85c"]),c=d3.svg.axis().scale(i).orient("bottom"),m=d3.svg.axis().scale(l).orient("left").tickValues([0,25,50,75,100]),u=(d3.select(".time-chart").append("div").attr("class","tooltip").style("opacity",0),d3.select(".time-chart").append("svg").attr("class","time-chart__outer-svg").attr("width",o+r.left+r.right).attr("height",s+r.top+r.bottom).append("g").attr("class","time-chart__inner-g").attr("transform","translate("+r.left+","+r.top+")"));i.domain(a.map(function(t){return t.Year})),l.domain([0,100]),d.domain(e.filter(function(t){return"Year"!==t})),a.forEach(function(t){var e=0;t.sentiment=d.domain().map(function(a){return{name:a,year:t.Year,y0:e,y1:e+=+t[a]}}),t.total=t.sentiment[t.sentiment.length-1].y1}),u.append("g").attr("class","x axis").attr("transform","translate(0,"+s+")").call(c).selectAll("text").filter(function(t){return"string"==typeof t}).style("cursor","pointer").on("click",function(t){document.location.href="search?query=&fromYear="+t+"&toYear="+t}).style("text-anchor","end").attr("dx","-.8em").attr("dy",".15em").attr("transform","rotate(-65)"),u.append("text").attr("class","x label").attr("text-anchor","end").attr("x",o/2).attr("y",s+r.bottom-15).attr("dy",".71em").text("Year"),u.append("g").attr("class","y axis").call(m),u.append("text").attr("class","y label").attr("transform","rotate(-90)").attr("y","-60").attr("x","-60").attr("dy",".71em").style("text-anchor","end").text("Percentage out of all documents");var p=u.selectAll(".year").data(a).enter().append("g").attr("class",function(t){return"time-chart__year "+t.Year}).attr("transform",function(t){return"translate("+i(t.Year)+",0)"});p.selectAll("rect").data(function(t){return t.sentiment}).enter().append("a").attr("class",function(t){return"time-chart__bar--"+t.name.toLowerCase()}).attr("id",function(t){return t.name.toLowerCase()+"-"+t.year}).attr("xlink:href",function(t){return"search?query=&fromYear="+t.year+"&toYear="+t.year+"&sentiment="+t.name.toLowerCase()}).attr("data-toggle","tooltip").attr("data-placement","right").attr("title",function(t){var e=parseFloat(t.y1-t.y0).toFixed(1);return t.year+"</br>"+t.name+": "+(e%1===0?parseInt(e):e)}).append("rect").attr("class",function(t){return"time-chart__bar--"+t.name.toLowerCase()}).attr("width",i.rangeBand()).attr("y",function(t){return l(t.y1)}).attr("height",function(t){return l(t.y0)-l(t.y1)}).style("fill",function(t){return d(t.name)});var g=u.selectAll(".legend").data(d.domain().slice().reverse()).enter().append("g").attr("class","legend").attr("transform",function(t,e){return"translate(0,"+20*e+")"});g.append("rect").attr("x",o+r.right-14).attr("width",18).attr("height",18).style("fill",d),g.append("text").attr("x",o+r.right-20).attr("y",9).attr("dy",".35em").style("text-anchor","end").text(function(t){return t}),timeChartNeedsUpdate=!1,$(".time-chart-tab .searching-progress").hide(),$(".time-chart-tab__description").removeClass("hidden"),$(function(){$('[data-toggle="tooltip"]').tooltip({container:"body",html:!0,template:'<div class="tooltip timechart__tooltip" role="tooltip"><div class="tooltip-arrow timechart__tooltip-arrow"></div><div class="tooltip-inner timechart__tooltip-inner"></div></div>'})})}function addCommas(t){t+="",x=t.split("."),x1=x[0],x2=x.length>1?"."+x[1]:"";for(var e=/(\d+)(\d{3})/;e.test(x1);)x1=x1.replace(e,"$1,$2");return x1+x2}var basicData=null,chartData=null,cityData=null,sortKey="date",positiveThreshold=2,negativeThreshold=-2,resultsListItems=null,$paging=$(".pagination").paging(0,pagingOpts);$paging.setOptions({perpage:20}),$paging.setOptions({onSelect:function(t){updatePage(this.slice),updateSentiment(),showPager(this.pages)}}),$(document).on("basicDataLoaded",function(t,e){null!=e&&e!=basicData?(basicData=e,updateDocuments()):alert("?")}),$("input:radio[name=sort]").on("click",function(t){sortKey=$("input:radio[name=sort]:checked").val(),queryChanged()});var mapIsSetup=!1,map,markers=[],lines=[],infoWindows=[];google.load("maps","3",{other_params:"sensor=false"}),$(document).on("cityDataLoaded",function(t,e){null!=e&&e!=cityData&&(cityData=e,mapNeedRerender=!0,"none"!=$("#tab-geographic")[0].style.display&&(redrawAllCurves(),redrawMarkers()))});var chartsNeedRerender=!1;$(document).on("chartDataLoaded",function(t,e){null!=e&&e!=chartData&&(chartData=e,chartsNeedRerender=!0,"none"!=$("#tab-charts").css("display")&&updateWordCharts())});var timeChart,timeChartNeedsUpdate=!0;$(document).on("basicDataLoaded",function(t,e){null!=e&&(timeChartNeedsUpdate=!0,"none"!=$("#tab-timeline").css("display")&&updateTimeChart())}),$('a[data-toggle="tab"]').on("shown.bs.tab",function(t){switch(newTabId=$(t.target).attr("href"),newTabId){case"#tab-content":break;case"#tab-timeline":timeChartNeedsUpdate&&updateTimeChart();break;case"#tab-geographic":mapIsSetup!==!0?setupMap():mapNeedsRerender&&(redrawAllCurves(),redrawMarkers());break;case"#tab-charts":chartsNeedRerender&&updateWordCharts()}});