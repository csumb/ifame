<!-- Find Instruments for each Study Area -->
<? $study_area=intval($_GET['study_area']);
//$link = mysql_connect('localhost', 'root', ''); //changet the configuration in required
//if (!$link) {
    //die('Could not connect: ' . mysql_error());
//}

include 'db_connection.php';



//mysql_select_db('db_ajax');
//$query="SELECT id,statename FROM state WHERE countryid='$country'";
$query="SELECT Distinct instrument_id, type
FROM instruments
INNER JOIN transect
ON instruments.id=transect.instrument_id
WHERE study_area = '$study_area'
ORDER BY instrument_id ASC";
$result=mysql_query($query);

?>
<select name="instrument" id="instrument" onchange="polylines()">
<option value="0" selected="selected">All Instruments</option>
<? while($row=mysql_fetch_array($result)) { ?>
<option value=<?=$row['instrument_id']?>><?=$row['type']?></option>
<? } ?>
</select>
