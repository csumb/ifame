  <?php
//Database Connection (open)
include 'db_connection.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link type="text/css" rel="stylesheet" href="floatbox/floatbox.css" />
<script type="text/javascript" src="floatbox/floatbox.js?framed"></script>
<script type="text/javascript" src="floatbox/options.js?framed"></script>


<script type="text/javascript">
fbPageOptions = {
  framed: true
};
</script>

<!--<script type="text/javascript" src="http://sep.csumb.edu/ifame/testing/infobox.js"></script>-->
<title>SCID - Shelf Characterization and Image Display - Explore. Locate. Identify.</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<link rel="shortcut icon" href="scid_images/ifame_logo.ico" type="image/x-icon" /> 


<!-- <link rel="stylesheet" href="css/style.css" type="text/css" media="all" /> -->
<style>
 /* html { height: 100% }
  body { height: 100%; margin: 0px; padding: 0px }
  
*/

html {
  /*height: auto;*/
  min-height:600px;
  font:Arial, Helvetica, sans-serif;
}

body {
 /* height: auto; */
  margin: 0;
  padding: 0;
  font:Arial, Helvetica, sans-serif;
}

 select { width: 250px }

#infopic {
		font:Arial, Helvetica, sans-serif;
	
	
}

#infovideo {
	font:Arial, Helvetica, sans-serif;
	
	
}

#map_canvas {
   height: 100%;
   z-index:1;
  position: absolute;
  bottom:-50;
  left:0;
  right:0;
  top:-50;
}

@media print {
  #map_canvas {
    height: 950px;
  }
}
</style>

<!-- <script type="text/javascript" src="js/7-x.js"></script> -->

<script type="text/javascript">


<?php

//-----------------adding labels to the map via infobox


$justLabels = mysql_query("Select study_area From study_area ORDER BY study_area ASC");
									   
					 echo 'var allAreas =[';	
					 $area_rows = mysql_num_rows($justLabels);
					 $quickCount = 1;			   
		   while($labels = mysql_fetch_array($justLabels)){
			   
					echo $labels['study_area']; 
					if($quickCount < $area_rows) {echo ', ';}
					$quickCount++;
			}		   
			echo '];' . "\n" ;	


		$boundary_result = mysql_query("Select study_area, area_name, latitude, longitude, image_directory, 
										GREATEST(bb_lat1, bb_lat2, bb_lat3, bb_lat4, bb_lat5) AS bb_lat,
										GREATEST(bb_long1, bb_long2, bb_long3, bb_long4, bb_long5) AS bb_long
		                                
									    From study_area
										ORDER BY study_area ASC
									   ");
								
									   
//-------------------------------------Area location title, img_dir, latitude, longitude --------------------------------------------------------						   
		   while($bbLabel = mysql_fetch_array($boundary_result)){
				
				 echo 'var areaLabel' . $bbLabel['study_area'] . '=[';
					echo '\'' . $bbLabel['area_name'] . '\', ' . $bbLabel['latitude'] . ', ' . $bbLabel['longitude'] . ', \'' . $bbLabel['image_directory'] . '\'';
				 echo '];' . "\n" ;
			}		   
			echo "\n" ;						   



//------------------------------------------------ create transect line arrays----------------------------------------------------------------

$tranStudyAreas = mysql_query("Select study_area From study_area ORDER BY study_area ASC");
	 while($showTrans = mysql_fetch_array($tranStudyAreas)){
		echo 'var transect' . $showTrans['study_area'] . '=[';
		
			$transects = mysql_query("SELECT transect_id, instrument_id FROM transect where study_area = " . $showTrans['study_area']);
				$transectsRows = mysql_num_rows($transects);
				$transectsTotal = 1;
				while($showTransect = mysql_fetch_array($transects)){
					echo '[' . $showTransect['instrument_id'] .', [';
					
				$tranCords = mysql_query("SELECT latitude, longitude FROM transect_coordinates WHERE transect_id =" . $showTransect['transect_id'] . " ORDER BY transect_coordinate_id");
					$transRows = mysql_num_rows($tranCords);
					$transTotal = 1;
					while($showTranCords = mysql_fetch_array($tranCords)){
						echo '' . $showTranCords['latitude'] . ', ' . $showTranCords['longitude'] . '';
						 if($transTotal < $transRows) {echo ', ';}
						  $transTotal++;	
					}
					
					
					echo ']]';
					if($transectsTotal < $transectsRows) {echo ', ';}
					$transectsTotal++;	
				}
			
	 echo '];' . "\n \n" ;
	 }
	



//-----------------Create marker arrays
          $area_menu = mysql_query("
                        SELECT study_area, area_name
                        FROM study_area
                        ORDER BY area_name ASC
                                      ");

                    while($show_area = mysql_fetch_array($area_menu))
                    
                            {	
                            echo 'var mark' . $show_area['study_area'] . '=[';
							
							
								          $area_markers = mysql_query("
SELECT media_points.title, media_points.subtitle, media_points.latitude, media_points.longitude, media_points.media_link, 
study_area.image_directory, media_points.media_type, transect.instrument_id
FROM media_points
INNER JOIN study_area
ON media_points.study_area_id = study_area.study_area
INNER JOIN transect
ON media_points.transect_id = transect.transect_id
Where study_area_id = $show_area[study_area]
ORDER BY latitude DESC
														  ");
										$num_rows = mysql_num_rows($area_markers);
										$total = 1;
										while($area_info = mysql_fetch_array($area_markers))
										
												{
													
													if($area_info['media_type'] == 1){
													
	                                         echo '[\'' . $area_info['media_type'] . '\', ' . $area_info['latitude'] . ', ' . $area_info['longitude'] . ', ' . $total . ', ';
								 echo '\'<div id=\"infopic\">' . $area_info['title'] .'<br />' . $area_info['subtitle'] .'<br /><img src="' . 'http://sep.csumb.edu/ifame/scid/images/';
									echo $area_info['image_directory'] . '/' . $area_info['media_link'] . '" width="320px" height="240px"></img></div>\', ' . $area_info['media_type'] . ', ' . $area_info['instrument_id'] . ']' ;
										
										
													}else {
														
											 echo '[\'' . $area_info['title'] . '\', ' . $area_info['latitude'] . ', ' . $area_info['longitude'] . ', ' . $total . ', ';
											 echo '\'<div id=\"infovideo\">';
											 
											 
											 
											 
											 echo $area_info['title'] .'<br />' . $area_info['subtitle'] .'<br /><iframe title="YouTube video player" width="280" height="240" src="http://www.youtube.com/embed/' . $area_info['media_link'] . '?rel=0" frameborder="0" allowfullscreen></iframe>';
											 
											 //echo '<img src="' . 'http://sep.csumb.edu/ifame/scid/images/';
											 //echo $area_info['image_directory'] . '/' . $area_info['media_link'] . '" width="320px" height="240px" />';
											 
											 
											 /*
											 <object width="320" height="240"><param name="movie" value="http://www.youtube.com/v/' . $area_info['media_link'] . '&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $area_info['media_link'] . '&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="240"></embed></object>
											 
											 */
											 
											 
											 
											 
											 echo '</div>\', ' . $area_info['media_type'] . ', ' . $area_info['instrument_id'] . ']' ;
														
													}
														
													
													if($total < $num_rows) {echo ', ';}
													$total++;
												}
							
							
							echo '];' . "\n \n" ;
                            }
							//echo 'var dude=[12,13]';
							
						
?>  
  
	var areainfo = []; //holds area from drop down menu
 	var markers = []; //markers to be placed on the map
    var iterator = 0; //keeps track of z-index for markers
	var locLatLng; // stores position for markers
	var loc; //marker variables
	var marker; //actual markers
	var map; 
	var infowindow = null; //create infoWindow
	var green_play = new google.maps.MarkerImage("http://www.google.com/mapfiles/dd-start.png", null, null, new google.maps.Point(20, 34));
	var markerNum; //holds the array ID
    var ibLabel; //infobox label

	var scidKML = null; //kml url
	var areaZoom = 0;
	var instrumentZoom = 0 ;
	var loc_area; // URL: what study area was picked
	var latitude = 36.815729; //monterey 36.815729, -121.783281
	var longitude = -121.783281; //do not set to null.
	var img_dir; //URL: directory of the images
	var media; // URL: What type of media points to display
	var locationed = 0; //for GControl
	var boundary;
	var instruments;
	var url = "area_results.php?area="; // The server-side script

	//var top.formWindow = self; //fb test
	var studyAreaLocation; //location in update_area()
	var currentArea = 0; //actually area id.
	var mediaId = 3; //current media selected. //3 is no media selected.
	var instrumentId; //current instrument selected.
	var addOn; //for loop in transect lines
	var route = new google.maps.MVCArray(); //Polyline array
	var lines = [];
	
	var browserType = ''; //keep track of the browser i.e. mozilla
	var browserVersion; //i.e. mozilla 5.0

  
//Help button "?" to map
function HomeControl(controlDiv, map) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
 
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
   controlUI.id = 'questions';
  //controlUI.style.backgroundColor = 'white';
  //controlUI.style.backgroundImage = 'url(bg_1x1t.png)';
  //controlUI.style.backgroundRepeat = 'repeat';
  controlUI.style.padding = '1px';
  controlUI.style.marginLeft = '18px';
  //controlUI.style.borderStyle = 'solid';
  //controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'SCID Instructions';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.backgroundImage = 'url(scid_images/map_question.png)';
  controlText.style.backgroundRepeat = 'no-repeat';
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.style.height = '40px';
  controlText.style.width = '40px';
  controlText.innerHTML = '';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
	   fb.start( 'http://sep.csumb.edu/ifame/testing/instructions.html', 'width:450px height:450px autoStart:once controlsPos:tr' );
	  //fb.start( 'http://omahanightlife.com/images/1/media/2009/05/07/pineapple.jpg' );
    //map.setCenter(chicago)
  });
    google.maps.event.addDomListener(controlUI, 'DOMNodeInserted', function() {
	   fb.start( 'http://sep.csumb.edu/ifame/testing/instructions.html', 'width:450px height:450px autoStart:once controlsPos:tr' );
	  //fb.start( 'http://omahanightlife.com/images/1/media/2009/05/07/pineapple.jpg' );
    //map.setCenter(chicago)
  });
  
  
}

//Legend button "List Style Button" to map
function Legend(controlDiv, map) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
  //controlUI.style.backgroundColor = 'white';
  //controlUI.style.backgroundImage = 'url(bg_1x1t.png)';
  //controlUI.style.backgroundRepeat = 'repeat';
  controlUI.style.padding = '1px';
  controlUI.style.marginLeft = '18px';
  //controlUI.style.borderStyle = 'solid';
  //controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Map Legend';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.backgroundImage = 'url(scid_images/map_legend.png)';
  controlText.style.backgroundRepeat = 'no-repeat';
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.style.height = '40px';
  controlText.style.width = '40px';
  controlText.innerHTML = '';
  controlUI.appendChild(controlText);


  google.maps.event.addDomListener(controlUI, 'click', function() {
	// alert("listener");
	 fb.start( '<p><img src="scid_images/legend.png" /></p>', 'data-fb-options="modal:false overlayOpacity:0 disableScroll:true width:auto scrolling:no enableDragResize:false cornerRadius:4 padding:10 controlsPos:tr caption:`Legend` boxLeft:+100% boxTop:200 outsideClickCloses:false colorTheme:white, backgroundColor:#6784c7' );
  });
}

//ajax menu start
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
	
	function getInstrument(areaId) {		
		var strURL="findStudyArea.php?study_area="+areaId;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('instrumentNav').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
			//update_area(); //get new kml
			//load media for all instruments.
			
			getMedia(document.getElementById("transect").value,document.getElementById("instrument").value);
		}	
		//alert("instrumentId" + instrumentId);	
	}
	
	
	function getMedia(areaId,instrumentId) {	 
	//alert(areaId + " " + instrumentId);
		var strURL="findMedia.php?study_area="+areaId+"&instrument="+instrumentId;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('mediaNav').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
	}
	//end of ajax menu selection

function NavMenu() {
  // Set CSS for the control border
  var controlUI = document.createElement('DIV');
   controlUI.id = 'navlaunch';
  //controlUI.style.backgroundColor = 'white';
  //controlUI.style.backgroundColor = '#CCCCCC';
  //controlUI.style.backgroundImage = 'url(scid_images/bg_1x1t.png)';
  controlUI.style.backgroundRepeat = 'repeat';
  //controlUI.style.backgroundRepeat = 'repeat-x';
  //controlUI.style.height = '300px';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.zIndex = 10001;
  controlUI.style.borderWidth = '2px';
  controlUI.style.padding = '10px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'left';
  //controlUI.style.marginTop = '25px';
  controlUI.title = 'Navigate SCID';

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');

  controlText.innerHTML ='<select name="transect" id="transect" onChange="update_area(this.value)"><option value="">Select Study Area</option><?php
          $area_menu = mysql_query("
                        SELECT study_area, area_name
                        FROM study_area
                        ORDER BY area_name ASC
                                      ");
                    while($show_area = mysql_fetch_array($area_menu))
                    
                            {	
                            echo '<option value="' . $show_area['study_area'] . '">' . $show_area['area_name'] . '</option>';
                            }
            ?></select>';
  
  //controlText.innerHTML = 'Help';
  controlUI.appendChild(controlText);
  
    // Set CSS for the control interior
  var controlText2 = document.createElement('DIV');
  controlText2.style.marginTop = '10px';
  controlText2.innerHTML ='<div id="instrumentNav" ><select id="instrument" ><option value="0">Select Study Area First</option></select></div>';
  controlUI.appendChild(controlText2);

//Media Selection
  var media = document.createElement('DIV');
  media.style.marginTop = '10px';
  media.innerHTML ='<div id="mediaNav"><select id="media" name="media"><option value="3">Select Instrument First</option></select></div>';
  controlUI.appendChild(media);
  
  document.body.appendChild(controlUI);
}//end the navigation controls


function initialize() {
	
// Map Options/parameters
var options = {
		zoom: 8,
	  //center to monterey
	  center: new google.maps.LatLng(36.815729, -121.783281),
	  //Map Types can be ROADMAP, HYBRID, SATELLITE, or TERRAIN
	  mapTypeId: google.maps.MapTypeId.SATELLITE,
	   //stop showing map type options
	   mapTypeControl: true,
	   mapTypeControlOptions: {
		   //can be DROPDOWN_MENU , HORIZONTAL_BAR , DEFAULT
			style: google.maps.MapTypeControlStyle.DEFAULT,
			position: google.maps.ControlPosition.RIGHT_TOP,
			//google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.SATELLITE
			mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.SATELLITE]
		},
	   streetViewControl: false,
		navigationControl: true,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.ZOOM_PAN,
			position: google.maps.ControlPosition.LEFT
		},
		 zoomControl: true,
   		 zoomControlOptions: {
      		style: google.maps.ZoomControlStyle.SMALL
   		 }
};
map = new google.maps.Map(document.getElementById('map_canvas'), options);

//---------------------------------------------------PolyLines---------------------------------------

<?php

		$boundary_result = mysql_query("Select study_area, area_name, latitude, longitude, image_directory, 
		                                bb_lat1, bb_long1, bb_lat2, bb_long2, bb_lat3, bb_long3, bb_lat4, bb_long4, bb_lat5, bb_long5 
									    From study_area
										ORDER BY study_area ASC
									   ");

	while($bb = mysql_fetch_array($boundary_result)){
		
		 echo 'var poly' . $bb['study_area'] . '= new google.maps.Polyline({ path:';
			echo '[new google.maps.LatLng(' . $bb['bb_lat1'] . ', ' . $bb['bb_long1'] . '),';
			echo 'new google.maps.LatLng(' . $bb['bb_lat2'] . ', ' . $bb['bb_long2'] . '),';
			echo 'new google.maps.LatLng(' . $bb['bb_lat3'] . ', ' . $bb['bb_long3'] . '),';
			echo 'new google.maps.LatLng(' . $bb['bb_lat4'] . ', ' . $bb['bb_long4'] . '),';
			echo 'new google.maps.LatLng(' . $bb['bb_lat5'] . ', ' . $bb['bb_long5'] . ')],';
			echo 'strokeColor: "#b85db8", strokeOpacity: 1.0, strokeWeight: 2';
		 echo '});' . " \n" . 'poly' . $bb['study_area'] . '.setMap(map);' . "\n \n" ;
	}

?>
//---------------------------------------------------End PolyLines--------------------------------------


//-------------------------------------------------- Boundary Lines ------------------------------------
<?php

$all_regions = mysql_query("SELECT * FROM region");
	
	while( $ar = mysql_fetch_array($all_regions)){
		   $current_region = $ar['region_id'];
					
	 echo 'var poly1' . $ar['region_id'] . '= new google.maps.Polyline({ path:';		
	 
	 							$region_result = mysql_query ("SELECT *
								 FROM region_coordinates
								 WHERE region_id =$current_region ORDER BY region_coordinate_id ASC");
		
								$regionRows = mysql_num_rows($region_result);
								$regionTotal = 1;
									echo '[';
									while($rr = mysql_fetch_array($region_result)){
										echo 'new google.maps.LatLng(' . $rr['latitude'] . ', ' . $rr['longitude'] . ')';
										if($regionTotal < $regionRows) {echo ', ';}
									$regionTotal++;	
									}
									echo '], ';
	 
	 echo 'strokeColor: "#ff0000", strokeOpacity: 1.0, strokeWeight: 2';
		 echo '});' . " \n" . 'poly1' . $ar['region_id'] . '.setMap(map);' . "\n \n" ;
		}

?>
//--------------------------------------------- End Boundary Lines ------------------------------------


//-----------------------------------------------Set divs onto the map---------------------------------

// Create the DIV to hold the control and call the HomeControl() constructor
 var homeControlDiv = document.createElement('DIV');
  var homeControl = new HomeControl(homeControlDiv, map);

  homeControlDiv.index = 1;
   map.controls[google.maps.ControlPosition.LEFT].push(homeControlDiv);
  
//Legend
  var legendControlDiv = document.createElement('DIV');
   var legendControl = new Legend(legendControlDiv, map);

  legendControlDiv.index = 1;
   map.controls[google.maps.ControlPosition.LEFT].push(legendControlDiv);
  
//Navigation Menu call 
NavMenu();

//-------------------------------End Set Divs onto the map-------------------------------------------------


//help user zoom in on selected study area
  google.maps.event.addListener(map, 'zoom_changed', function() {
    map.setCenter(new google.maps.LatLng(latitude, longitude));
  });
  
  
  //collect information about the browser. Mozilla5.0 (Windows)
  //browserType =  navigator.appName;
  
  browserVersion = navigator.appVersion;
  //alert(navigator.appCodeName + browserType + browserVersion);
  
if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)){ //test for Firefox/x.x or Firefox x.x (ignoring remaining digits);
 var ffversion=new Number(RegExp.$1) // capture x.x portion and store as a number
 if (ffversion==4 || ffversion==5)
  browserType = 'Mozilla';
  //alert("You're using FF 4.x or above")
 //else if (ffversion>=3)
  //alert("You're using FF 3.x or above")
 //else if (ffversion>=2)
  //alert("You're using FF 2.x")
 //else if (ffversion>=1)
  //alert("You're using FF 1.x")
}
//else
 //alert("n/a")



}; //end initialize function



//---------------------------------- AREAS -------------------------------------------------
function update_area(areaId) {
	
	currentArea = areaId; //to be used in other functions
	markerNum = "mark" + areaId; //sets which array to evaluate
	
	var currArea = 0;
		for (var i = 0; i < allAreas.length; i++) {
				if (areaId == allAreas[i]){
					 currArea = i;	
				}
		}

  studyAreaLocation = eval("areaLabel" + allAreas[currArea]);
  //alert("studyarea: " + studyAreaLocation);
	latitude = (studyAreaLocation[1]);
	 longitude = (studyAreaLocation[2]);
	  img_dir = (studyAreaLocation[3]);
	   locationed = (studyAreaLocation[0]); //area name

     getInstrument(areaId); //fixes navigation
	//changeArea (); //zooms and loads markers
	setTimeout(function() {changeArea();},300);
	 
} //end

//Called from the Area Drop Down Menu
function changeArea () {

	var markerNum = "mark" + currentArea;

	  areainfo = eval(markerNum);
	   remove(); //remove current markers
	   map.panTo(new google.maps.LatLng(latitude, longitude));
	   
	   if (map.getZoom() != 10){ // Change max/min zoom here
		map.setZoom(10);
		}

		polylines ();
}

//--------------------------- Instruments -----------------------------------------

function polylines () {
	if(lines.length > 0 ) {
		//alert("polylines function: lines.length>0: " + lines.length);
		removePolyLines();
	}
	
	instrumentId = document.getElementById("instrument").value;
	var nowArea = eval('transect'+currentArea);
	
	for (var n = 0; n < nowArea.length; n++) {
			
				var transectCoords = eval("transect"+currentArea+"["+n+"][1]");	
				var whatInstrument = eval("transect"+currentArea+"["+n+"][0]");		
				  	  
				 //put at top - Creating an empty MVCArray
				// Creating the Polyline object
				if(whatInstrument == 1) { //towed camera sled
						var lineColor = "#3399ff"; //blue
					} else if ( whatInstrument == 2 ) { //ROV
						var lineColor = "#ff3399";  //pink
					} else if ( whatInstrument == 3 ) { //Submersible
						var lineColor = "#ffff33"; //yellow
				}
				
				var polyline = new google.maps.Polyline({
					path: route,
					strokeColor: lineColor,
					strokeOpacity: 1.0,
					strokeWeight: 3
				});
				// Adding the polyline to the map
				polyline.setMap(map);
				
					for (var i = 0; i < transectCoords.length/2; i++) {
						var myLat = transectCoords[i*2];
						var myLon = transectCoords[i*2+1];
						var coords = new google.maps.LatLng(myLat,myLon);
						var path = polyline.getPath();
						
							if( instrumentId == 0 || whatInstrument == instrumentId ) {
								path.push(coords);
							}
						
					}
		
		lines.push(polyline);  
	}
	getMedia(document.getElementById("transect").value,document.getElementById("instrument").value);
	  // alert("mediaId " + mediaId);
		if ( mediaId != 3 ){
			//alert("!3");
			remove();
			//droper();
			//getInstrument(areaId); //fixes navigation
		}
	
}

function removePolyLines() {
	if (lines) {
		  for (i in lines) {
			lines[i].setMap(null);
		  }
	}
}


//---------------------------- Media - Pics - Videos -----------------------------------------

//Timer to drop Markers
function droper() {
	//alert("dropper");
	remove();
    for (var i = 0; i < areainfo.length; i++) {
      setTimeout(function() {
        markerme();
      }, 5000/areainfo.length);
    }
	
  }

//Put Markers into an Array
function markerme() {
	  	loc = areainfo[iterator];
	 	locLatLng = new google.maps.LatLng(loc[1], loc[2]);
	    mediaId = document.getElementById("media").value;
	
	if( instrumentId == 0 || loc[6] == instrumentId ) {
		
	  if((loc[5] == 0) && (mediaId == 0)) {
	  
		  if (browserType == 'Mozilla'){
			
				       marker = new google.maps.Marker({
							position: locLatLng,
							map: map,
							title: loc[0],
							zIndex: loc[3],
							//html: loc[4],
							html: 'Please View this video in a browser other than Mozilla ' + browserVersion,
							//icon: green_play,
							icon: 'http://maps.google.com/mapfiles/kml/paddle/red-circle_maps.png',
							optimized: false,
							animation: google.maps.Animation.DROP
						});
				}else {
				
						marker = new google.maps.Marker({
							position: locLatLng,
							map: map,
							title: loc[0],
							zIndex: loc[3],
							html: loc[4],
							//shadow: 'http://www.google.com/mapfiles/shadow50.png',
							//icon: green_play,
							icon: 'http://maps.google.com/mapfiles/kml/paddle/red-circle_maps.png',
							animation: google.maps.Animation.DROP
						});
						
				}
						
					markers.push(marker);
	 
	  google.maps.event.addListener(marker, "click", function () {
												//alert(this.html);
												infowindow.setContent(this.html);
												infowindow.open(map, this);
											});
		infowindow = new google.maps.InfoWindow({content: "<div id=\"infovideo\">This is not 275x80px</div>", maxWidth: 350});
		
	  } else if((loc[5] == 1) && (mediaId == 1)) {
		  
		  marker = new google.maps.Marker({
			position: locLatLng,
			map: map,
			title: loc[0],
			zIndex: loc[3],
			html: loc[4],
			icon: 'http://maps.google.com/mapfiles/kml/paddle/ylw-circle_maps.png',
			animation: google.maps.Animation.DROP
		  });
		  
		  			markers.push(marker);
	 
	  google.maps.event.addListener(marker, "click", function () {
												//alert(this.html);
												infowindow.setContent(this.html);
												infowindow.open(map, this);
											});
		infowindow = new google.maps.InfoWindow({content: "<div id=\"infovideo\">This is not 275x80px</div>", maxWidth: 350});
		  
	  }
	}

       iterator++;
  }


  //Remove/delete the markers from the map
  function remove() {
	if (markers) {
		//alert("Markers");
		  for (i in markers) {
			  //alert(markers[1][0]);
			markers[i].setMap(null);
		  }
		}
		
	iterator=0;
  }
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6090545-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body onload="initialize()">
<div id="map_canvas" ></div>

<!--ifame Logo Bottom Right -->
<div style="position:fixed; bottom:24px; right:15px; width:100px; height:138px; z-index:4;"><a href="http://sep.csumb.edu/ifame" target="_new" ><img src="scid_images/ifame_logo_link.png" border="0" alt="Institute for Applied Marine Ecology" /></a></div>

<!-- MBNMS logo Bottom Right -->
<div style="position:fixed; bottom:24px; right:130px; width:100px; height:138px; z-index:4;"><a href="http://www.sanctuarysimon.org/" target="_new" ><img src="scid_images/nms_logo_border.png" border="0" alt="National Marine Sanctuaries" /></a></div>


<!-- SCID logo Bottom Left -->
<div style="position:fixed; bottom:24px; left:15px; width:207px; height:138px; z-index:4;"><img src="scid_images/scid_logo_1.png" border="0" alt="Shelf Characterization and Image Display" /></div>


  <script type="text/javascript">
  
  fb.addEvent(window, 'load', function() {
  setTimeout(function() {
   fb.start('#navlaunch', 'modal:false scrolling:no cornerRadius:0 padding:1 controlsPos:tr boxLeft:+100% boxTop:50 showClose:false');
  
   fb.start( 'instructions.html', 'autoStart:once width:450px height:450px controlsPos:tr' );
  // HomeControl(homeControlDiv, map).click;
 // fb.start( 'questions' );
 //HomeControl(homeControlDiv, map);
  }, 2000);
});

  </script>

</body>
</html>

<?php
mysql_close($connection);
?>