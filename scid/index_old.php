<?php
//Set which Study Area needs to be looked up
if (!isset($_POST['transect'])) $transect_id = 0; else $transect_id = $_POST['transect'];
$latitude = 36.304600;
$longitude = -121.900400;

//Database Connection (open)
require ("includes/db_connection.php");

if ($transect_id > 0) {
include 'includes/db_connection.php';


$result = mysql_query("SELECT area_name, latitude, longitude, image_directory FROM study_area WHERE study_area = $transect_id");
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
$area = mysql_fetch_row($result);
$latitude = $area[1];
$longitude = $area[2];
$directory = $area[3];
}

//generate random number for URLs
$rand = rand(100, 1000000);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SCID - Sanctuary Characterization Image Display - Ifame - CSUMB</title>
<link rel="shortcut icon" href="images/scid_layout/ifame_logo.ico" type="image/x-icon" /> 

<!-- Drop down menu expansion in IE -->
<SCRIPT src="includes/jquery.js" type="text/javascript"></SCRIPT>
<script src="includes/jquery.selecteSizer.js" type="text/JavaScript"></script>
<SCRIPT type="text/javascript">
var j = jQuery.noConflict();
		
	j("document").ready(function(){			
		if(j.browser.msie){
			j("select").selecteSizer();
		}
	});
   	
</SCRIPT>
<!-- END Drop down menu expansion in IE -->


<style type="text/css">
<!--
#body {
	height:500px;
	padding-bottom:10px;
	padding-top: 10px;
}
#map_control {
	width:150px;
	float:left;
	padding-left:10px;
	padding-top: 50px;
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#01a9cd;
	
	/*
	height:500px;
	padding-top: 0px;
	margin-top: 2px;
	width:800px;
	margin-left:auto;
	margin-right:auto;*/
}
#map_canvas {
	float:left;
	height:500px;
	/*
	margin-right:10px;
	margin-top:5px;
	margin-left:auto;
	margin-right:auto;
	 margin-top: 10px; */
}
#right_side {
	width:140px;
	padding-top: 50px;
	float:right;
	padding-right:20px;
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:18px;
	color:#01a9cd;
}
#right_side a{
	color:#01a9cd;
}
#right_side a:hover {
	color:#FFF;
}


#note {
	font-family:Arial, Helvetica, sans-serif;
	font-size:10px;
	color:#01a9cd;
}

/*
fieldset { padding-left:10px; }
legend {
	font-family:georgia, sans-serif; 
	font-size:12px; font-weight:bold; 
	border:1px solid #fff; 
	margin-bottom:5px; 
	padding:3px; width:150px; 
	background:#fff url(form.gif) repeat-x center left;
}
*/
select {
    width: 140px; /* Or whatever width you want. */
	margin-bottom:3px;
}

.main{
	background-color: #000;
	text-align:left;
	margin-top:2px;
}
#top_div {
	background-image:url(images/scid_layout/scid_bg.jpg);
	background-repeat:no-repeat;
	width:1024px;
	
	margin-left: auto;
	margin-right: auto;
}
#header {
	background-image:url(images/scid_layout/scid_header.png);
	background-repeat:no-repeat;
	height: 68px;
	padding-bottom: 0px;
}
#sub_header {
	background-image:url(images/scid_layout/scid_sub_header.png);
	background-repeat:no-repeat;
	height: 25px;
	padding-bottom: 5px;
}

#footer {
	font-family:Arial, Helvetica, sans-serif;
	margin-left:auto;
	margin-right:auto;
	text-align:center;
	width:700px;
	color:#FFF;
	font-size: 12px;
}
#top_nav{
	text-align:center;
	padding-bottom: 8px;
	
}
#top_nav a{
	font-size: 20px;
	color:#FF6;
	padding-right: 15px;
	padding-bottom: 5px;
}
#top_nav a:hover {
	color:#FFF;
}
img {
	margin: 5px;
	border-color:#FFF;
}
#text {
	/*color:#6688cc; color of google navigation blue*/
	font-family:Arial, Helvetica, sans-serif;
	color:#000;
	font-size:18px;
	padding-left:20px;
	padding-top:20px;
}
-->
</style>

  <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAD10LEVugLpJVUzTtXmRjXxS3NbpQRjPoDiqDCW4U0-dPkyMr_RRIAQAvoAM_l5TjEZno901621QnpQ" type="text/javascript"></script>

    <script language="javascript"  type="text/javascript">
	var map;
	var geoXml;
	var loc_area; // URL: what study area was picked
	var latitude;
	var longitude;
	var img_dir; //URL: directory of the images
	var media; // URL: What type of media points to display
	var locationed = 0; //for GControl
	var boundary;
	var instruments;
	 
var url = "area_results.php?area="; // The server-side script
function handleHttpResponse() {
  if (http.readyState == 4) {
    // Split the comma delimited response into an array
    results = http.responseText.split(",");
    //document.getElementById('city').value = results[0];
 	 // document.getElementById('state').value = results[1];
//location = (results[0]);
latitude = (results[1]);
longitude = (results[2]);
img_dir = (results[3]);
	  
  //document.all.name.innerHTML = (results[0]);
  locationed = (results[0]);
  //document.all.latitude.innerHTML = (results[1]);
  //document.all.longitude.innerHTML = ( results[3] );

new_kml();	

  }
       
}
/*
function updateCityState() {
  var zipValue = document.getElementById("zip").value;
  http.open("GET", url + escape(zipValue), true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);
}
*/
function update_area() {
  var area = document.getElementById("transect").value;
  //alert("hello " + area);
  http.open("GET", url + escape(area), true);
  http.onreadystatechange = handleHttpResponse;
  http.send(null);
}

function getHTTPObject() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
var http = getHTTPObject(); // We create the HTTP Object

//Start test overlay
/*
function MyPane() {}
MyPane.prototype = new GControl;
MyPane.prototype.initialize = function(map) {
	var me = this;

  
  
  me.panel = document.createElement("div");
  me.panel.style.width = "320px";
  me.panel.style.height = "30px";
  me.panel.style.border = "1px solid gray";
  me.panel.style.background = "#FFFFFF";
  //me.panel.style.opacity = "50";
  me.panel.innerHTML = "<span id=\"text\" >" + locationed +"</span>";
  map.getContainer().appendChild(me.panel);
  return me.panel;
};

MyPane.prototype.getDefaultPosition = function() {
  return new GControlPosition(
      G_ANCHOR_TOP_LEFT, new GSize(80, 10));
      //Should be _ and not &#95;
};

MyPane.prototype.getPanel = function() {
  return me.panel;
}
*/

//END test overlay

function disable()
{
document.getElementById("media").disabled=true;
document.getElementById("instrument").disabled=true;
}
function enable()
{
document.getElementById("media").disabled=false;
document.getElementById("instrument").disabled=false;
}



    function initialize_map() {
      if (GBrowserIsCompatible()) {
		  var rnumber = Math.floor(Math.random()*111111);
		  geoXml = new GGeoXml("http://sep.csumb.edu/ifame/scid/testkml.php?boundary=0&area=0&instr=0&dir=lobos&media=1");
		  
        map = new GMap2(document.getElementById("map_canvas"));
		map.setMapType(G_HYBRID_MAP);
		//map.removeMapType(G_SATELLITE_MAP);
        map.setCenter(new GLatLng(36.6644, -121.972<?php /*echo $latitude . "," . $longitude;*/ ?>), 7);
		map.setUIToDefault();
		disable(); //disables media drop down menu
		//var mapControl = new GMapTypeControl();
		//map.addControll(new GOverviewMapControl());
		var bottomRight = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(12,12));
		 map.addControl(new GOverviewMapControl(), bottomRight);
       // document.all.name.innerHTML = ("Your Location will Appear Here");
		//map.addControl(new GLargeMapControl());
        //map.addControl(new GLargeMapControl());
		
		
			//var mgrOptions = { borderPadding: 50, maxZoom: 15, trackMarkers: true };
			//var mgr = new MarkerManager(map, mgrOptions);
		map.addOverlay(geoXml);
		      // ==== Create a KML Overlay ====
      }
   }
	
function new_kml(){
	enable();
	//update_area();
	map.removeOverlay(geoXml);
	//map.removeControl(MyPane());
	//alert("hello " + img_dir);
	//var rnumber = Math.floor(Math.random()*111111);
	loc_area = document.getElementById("transect").value;
	media = document.getElementById("media").value;
	boundary = document.getElementById("transect").value;
	//boundary = 2;
	instruments = document.getElementById("instrument").value;
	//instruments = 0;
	//alert("hello " + media);
	
	geoXml = new GGeoXml("http://sep.csumb.edu/ifame/scid/testkml.php?boundary=" + boundary + "&area=" + loc_area + "&instr=" + instruments + "&dir=" + img_dir + "&media=" + media + "");
	 map.addOverlay(geoXml);
	// map.addControl(new MyPane()); //Test overlay
	// alert("hello " + latitude);
	 if(latitude == 0){
	     window.setTimeout(function() {
           map.panTo(new GLatLng(36.6644, -121.972));
         }, 1000);
		 map.setZoom(7)
	 }
	 else {
		 window.setTimeout(function() {
           map.panTo(new GLatLng(latitude, longitude));
         }, 1000);
		 map.setZoom(10)
	 }
	 
	//map.setZoom(10);
	 //alert("hello " + geoXml);
}

</script>
</head>
<body onLoad="initialize_map()"  onunload="GUnload()" class="main">

<div id="top_div">

	<div id="header">
    	
	</div>
    <div id="sub_header">
    	
	</div>
    

    
    <div id="body">    
      <div id="map_control">
      
      
      To browse the<br />
      Map, select a<br />
      Study Area:
      <br /><br />
		<form name="map_choice" action="post" >

            <select name="transect" id="transect" onchange="update_area();">
            <option value="0" selected="selected">All Study Areas</option>
			<?php
            $area_menu = mysql_query("
                        SELECT study_area, area_name
                        FROM study_area
                        ORDER BY area_name ASC
                                      ");
                    while($show_area = mysql_fetch_array($area_menu))
                    
                            {	
                            echo '<option value="' . $show_area['study_area'] . '">' . $show_area['area_name'] . '</option>';
                            }
            ?>
            </select>
		<br />
        
        
        AND/OR
        <br />
        
        
        <select name="instrument" id="instrument" onchange="update_area();">
            <option value="0" selected="selected">All Instruments</option>
			<?php 
            $instrument_menu = mysql_query("
                        SELECT id, type
                        FROM instruments
                        ORDER BY type ASC
                                      ");
                    while($show_instrument = mysql_fetch_array($instrument_menu))
                    
                            {	
                            echo '<option value="' . $show_instrument['id'] . '">' . $show_instrument['type'] . '</option>';
                            }
            ?>
            </select>
        <br />
        AND/OR
        <br />

            <select name="media" id="media" onchange="new_kml();">
            <option value="3" selected="selected">Media</option>
            <option value="1">Pictures</option>
            <option value="0">Videos</option>
            <option value="2">Both</option>
            <option value="3">None</option>
			</select>
            
		</form>
        <br />
        Click a <font color="#FF0000">Video</font> or <font color="#FFFF00">Photo</font> point to view the media and related data
        <br /><br />
        <img src="images/scid_layout/legend.png" alt="Map Legend" />
        
	</div> 


 <div id="map_canvas" style="width: 700px; height: 500px"></div>
 
 <div id="right_side"><a href="about.php">About SCID</a> <p /> <a href="field_ops.php">Field Ops</a> <p /> 
 <a href="http://sep.csumb.edu/ifame" target="_blank"><img src="images/scid_layout/ifame_logo_link.jpg" border="1" bordercolor="#FFFFFF" alt="Institute for Applied Marine Ecology -  Ifame" /></a> <p />
 <a href="http://www.sanctuarysimon.org" target="_blank"><img src="images/scid_layout/nms_logo_link.jpg" border="1" bordercolor="#FFFFFF" alt="National Marine Sanctuary" /></a></div>
 
  </div>
    </div>
    
       <div id="footer">
    	SCID is the result of a partnership between the Institute for Applied Marine Ecology (IfAME) at 
        California State University Monterey Bay and the SIMoN Program at the Monterey Bay National Marine Sanctuary.
        <br />
        <span id="note">Background image courtesy IMPACT 2008</span>
       </div>
    
</div>


<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6090545-7");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>
<?php
mysql_close($connection);
?>