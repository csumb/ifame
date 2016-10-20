<?php

//Database connection variables
 $server = 'earth.csumb.edu';
 $username = 'scid';
 $password = 'i19hGqQVRkak';
 $database = 'scid';


 // Opens a connection to a MySQL server.
$connection = mysql_connect ($server, $username, $password);

 
 if (!$connection)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($database, $connection);

?>