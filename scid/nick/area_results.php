<?php
/**
 * Connects to the database.
 * Return false if connection failed.
 * Be sure to change the $database_name. $database_username , and 
 * $database_password values  to reflect your database settings.
 */
function db_connect() {
  $database_name = 'scid'; // Set this to your Database Name
  $database_username = 'scid'; // Set this to your MySQL username
  $database_password = 'i19hGqQVRkak'; // Set this to your MySQL password
  $result = mysql_pconnect('earth.csumb.edu',$database_username, $database_password); 
  if (!$result) return false;
  if (!mysql_select_db($database_name)) return false;
  return $result;
}
$conn = db_connect(); // Connect to database
if ($conn) {
  $study_area = $_GET['area']; // The parameter passed to us
  $query = "select area_name, latitude, longitude, image_directory from study_area where study_area = '$study_area'";
  $result = mysql_query($query,$conn);
  $count = mysql_num_rows($result);
  if ($count > 0) {
    $area = mysql_result($result,0,'area_name');
	$lat = mysql_result($result,0,'latitude');
	$lon = mysql_result($result,0,'longitude');
	$dir = mysql_result($result,0,'image_directory');
  }
}
if (isset($area) && isset($lat)) { 
  $return_value = $area . "," . $lat . "," . $lon . "," . $dir; 
}
else {  
  $return_value = "invalid".",".$_GET['area']; // Include Zip for debugging purposes
}
echo $return_value; // This will become the response value for the XMLHttpRequest object
?>