<!--Select Media points for Study Area -->

<? 
$study_area=intval($_GET['study_area']);
$instrument=intval($_GET['instrument']);

include 'db_connection.php';
//$stateId=intval($_GET['state']);
//$link = mysql_connect('localhost', 'root', ''); //changet the configuration in required
//if (!$link) {
   // die('Could not connect: ' . mysql_error());
//}
//mysql_select_db('db_ajax');
//$query="SELECT id,city FROM city WHERE countryid='$areaId' AND stateid='$stateId'";

if($instrument == 0){
	
	$query="SELECT Distinct media_points.media_type, media.type
	FROM media
	INNER JOIN media_points
	ON media.id=media_points.media_type
	JOIN transect
	ON media_points.transect_id = transect.transect_id
	WHERE study_area_id = '$study_area'
	ORDER BY id ASC";
	
}else{

	$query="SELECT Distinct media_points.media_type, media.type, transect.instrument_id
	FROM media
	INNER JOIN media_points
	ON media.id=media_points.media_type
	JOIN transect
	ON media_points.transect_id = transect.transect_id
	WHERE study_area_id = '$study_area' AND instrument_id = '$instrument'
	ORDER BY id ASC";
}

$result=mysql_query($query);

?>
<select name="media" id="media" onchange="droper();">
<option value="3">Select Media</option>
<option value="3">No Media</option>
<? while($row=mysql_fetch_array($result)) { ?>
<!--<option value><?//=$row['type']?></option>-->
<option value=<?=$row['media_type']?>><?=$row['type']?></option>
<? } ?>
</select>
