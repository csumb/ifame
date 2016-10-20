<?php

//Database connection variables
 $server = 'localhost';
 $username = 'scid';
 $password = 'coz90&Marches';
 $database = 'scid';


 // Opens a connection to a MySQL server.
$connection = mysql_connect ($server, $username, $password);

 
 if (!$connection)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($database, $connection);

?>
