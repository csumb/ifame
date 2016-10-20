<?php
//Get variables from URL
$boundary = $_GET['boundary'];
//$boundary = 2;
$area = $_GET['area'];
$directory = $_GET['dir'];
$media_points = $_GET['media'];
$instrument = $_GET['instr'];
	//Constants
	$study_area = $area;
	$image_path = 'http://sep.csumb.edu/ifame/scid/nick/images/';
	$image_directory = $directory . '/';

header('Content-Type: application/vnd.google-earth.kml+xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'; 

//Database Connection (open)
include 'includes/db_connection.php';


//start of kml document
echo '<kml xmlns="http://www.opengis.net/kml/2.2">';

echo"
 <Document>
			
  <Style id=\"yellow_icon\">
      <IconStyle>
         <Icon>
            <href>http://maps.google.com/mapfiles/kml/paddle/ylw-circle.png</href>
         </Icon>
      </IconStyle>
   </Style>
   
 
   <Style id=\"red_icon\">
      <IconStyle>
         <Icon>
            <href>http://maps.google.com/mapfiles/kml/paddle/red-circle.png</href>
         </Icon>
      </IconStyle>
   </Style>
	

	<Style id=\"trans_line\">
		<LineStyle>
			<color>ffffff00</color>
			<width>1.5</width>
		</LineStyle>
	</Style> 	
	
	<Style id=\"boundary_box\">
		<LineStyle>
			<color>ffb85db8</color>
			<width>3</width>
		</LineStyle>
	</Style>
	
	<Style id=\"mbnms\">
		<LineStyle>
			<color>ff441bb7</color>
			<width>3</width>
		</LineStyle>
	</Style>
	
	<Style id=\"camera\">
		<LineStyle>
			<color>fff88a38</color>
			<width>1.5</width>
		</LineStyle>
	</Style>
	
	<Style id=\"rov\">
		<LineStyle>
			<color>ffc038f8</color>
			<width>1.5</width>
		</LineStyle>
	</Style>
	
	<Style id=\"sub\">
		<LineStyle>
			<color>ff38f8f1</color>
			<width>1.5</width>
		</LineStyle>
	</Style>
		
 <Folder>
";

//MBNMS outline
if ($boundary == 0) {
	$all_regions = mysql_query("SELECT * FROM region");
	
		while( $ar = mysql_fetch_array($all_regions)){
					$current_region = $ar['region_id'];
	echo '<Placemark>' . "\n";
		echo '<name>' . $ar['name'] . $current_transect .'</name>' . "\n";
			echo '<description>' . $ar['description'] . '</description>' . "\n";
				echo '<styleUrl>#mbnms</styleUrl>' . "\n";
					echo '<LineString>' . "\n";
   						 echo '   <extrude>1</extrude>' . "\n";
    						//echo '    <tessellate>1</tessellate>';
    						echo '    <altitudeMode>absolute</altitudeMode>' . "\n";
							
								//Coordinates
								echo '<coordinates>' . "\n";
																
								$region_result = mysql_query ("SELECT *
								 FROM region_coordinates
								 WHERE region_id = $current_region ORDER BY region_coordinate_id ASC");
		
								while($rr = mysql_fetch_array($region_result)){
										echo  $rr['longitude']. ',' . $rr['latitude'] . ',' . '0 ';	
								}
		
							echo "\n" . '</coordinates>' . "\n";
						//End Coordinates
  	  				echo '</LineString>' . "\n";
  				echo '</Placemark>' . "\n";
			
		}
	

	
}



//MBNMS end


//Make Boundary Box
if ($boundary == 0){
	$boundary_result = mysql_query("Select area_name, bb_lat1, bb_long1, bb_lat2, bb_long2, bb_lat3, bb_long3, bb_lat4, bb_long4, bb_lat5, bb_long5 
									From study_area
									");
}
else if ($boundary >=1) {
		$boundary_result = mysql_query("Select area_name, bb_lat1, bb_long1, bb_lat2, bb_long2, bb_lat3, bb_long3, bb_lat4, bb_long4, bb_lat5, bb_long5 
									From study_area
									WHERE study_area = $boundary
									");
}
	while($bb = mysql_fetch_array($boundary_result)){
				echo '<Placemark>' . "\n";
					echo '<name>' . $bb['area_name'] . '</name>' . "\n";
						echo '<description>' . $bb['area_name'] . '</description>' . "\n";
							echo '<styleUrl>#boundary_box</styleUrl>' . "\n";
								echo '<LineString>' . "\n";
   						 			echo '   <extrude>1</extrude>' . "\n";
										echo '    <altitudeMode>absolute</altitudeMode>' . "\n";
									
										echo '<coordinates>' . "\n";
											$counter=1;
											while($counter<6){
													echo  $bb["bb_long$counter"]. ',' . $bb["bb_lat$counter"] . ',' . '0 ';	
													$counter++;
											}
										echo '</coordinates>' . "\n";
								echo '</LineString>' . "\n";
				echo '</Placemark>' . "\n";	
	}
//End of Boundary Box

if($study_area>=1){ //see if study area is greater than zero
//Build Transect Lines
if($instrument >= 1 ) {
    $transect_result = mysql_query("SELECT * FROM media_points
									INNER JOIN transect
									ON media_points.transect_id = transect.transect_id
									WHERE instrument_id = $instrument AND study_area = $study_area");
}
else {
	$transect_result = mysql_query("Select *
								From transect
								WHERE study_area = $study_area");
}

while($row = mysql_fetch_array($transect_result)){
	$current_transect = $row['transect_id'];
	echo '<Placemark>' . "\n";
		echo '<name>' . $row['scid_transect_id'] . $current_transect .'</name>' . "\n";
			echo '<description>' . $row['description'] . '</description>' . "\n";
				echo '<styleUrl>';
				
				if ($row['instrument_id'] == 1){
				    echo '#camera';	
				}
				else if ($row['instrument_id'] == 2){
				    echo '#rov';	
				}
				else{
				    echo '#sub';	
				}
				
				
				
				
				 echo '</styleUrl>' . "\n";
					echo '<LineString>' . "\n";
   						 echo '   <extrude>1</extrude>' . "\n";
    //echo '    <tessellate>1</tessellate>';
    						echo '    <altitudeMode>absolute</altitudeMode>' . "\n";
							
		//Coordinates
		echo '<coordinates>' . "\n";
		$coordinate_result = mysql_query("SELECT *
											FROM transect_coordinates
											WHERE transect_id = $current_transect ORDER BY transect_coordinate_id ASC");
		
		while($row2 = mysql_fetch_array($coordinate_result)){
				echo  $row2['longitude']. ',' . $row2['latitude'] . ',' . '0 ';	
		}
		
		echo "\n" . '</coordinates>' . "\n";
		//End Coordinates
  	  				echo '</LineString>' . "\n";
  	echo '</Placemark>' . "\n";
}  // End Transect Lines

//echo '</Document>';

	//Shows Media Points
	if($instrument >= 1 ) {
	$point_result = mysql_query("SELECT * FROM media_points
									INNER JOIN transect
									ON media_points.transect_id = transect.transect_id
									WHERE instrument_id = $instrument AND study_area = $study_area");
	}
	else {
		$point_result = mysql_query("SELECT *
								 FROM media_points
								 WHERE study_area_id = $study_area");
	}
		
		while($row3 = mysql_fetch_array($point_result)){
				if ( $media_points == 1 || $media_points == 2 ){
					if ($row3['media_type'] == 1 ) {
						echo '<Placemark>';	
							echo '<name>' . $row3['title'] .  '</name>';
								echo '<styleUrl>#yellow_icon</styleUrl>';
									echo '<description>';
								echo  $row3['subtitle'] ;
							echo '<![CDATA[
								<img src="' .  $image_path . $image_directory . $row3['media_link'] .'" width="320" height="240">';
							echo ']]>';
									echo '</description>';
							echo '<Point>';
								echo '<coordinates>' . $row3['longitude'] . ',' . $row3['latitude'] . ',' . '0' . '</coordinates>';
							echo '</Point>';
						echo '</Placemark>' . "\n";
					}
				} //end if for media points
				//end for PHOTOS
				
				//VIDEOS
				if ( $media_points == 0 || $media_points == 2 ){		
					if ($row3['media_type'] == 0 ) {
						echo '<Placemark>';	
							echo '<name>' . $row3['title'] . '</name>';	
								echo '<styleUrl>#red_icon</styleUrl>';
									echo '<description>';
									echo  $row3['subtitle'] ;
				echo '<![CDATA[
							   
<object width="320" height="240"><param name="movie" value="http://www.youtube.com/v/' . $row3['media_link'] . '&hl=en_US&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $row3['media_link'] . '&hl=en_US&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="240"></embed></object>
						';
				echo ']]>' ; 
									echo '</description>';
							echo '<Point>';
								echo '<coordinates>' . $row3['longitude'] . ',' . $row3['latitude'] . ',' . '0' . '</coordinates>';
							echo '</Point>';
						echo '</Placemark>' . "\n";
					}
			  	}
			}
}//end if study area greater than zero

?> 
		</Folder>
   </Document> 
</kml>

<?php
mysql_close($connection);
?>