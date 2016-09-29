<!--==================================================
                   DATABASE PAGE                                                                                 
===================================================-->

<?php
  $server = 'XXX.XX.XX.XXX';
  $username = "XXXXX";
  $password = "XXXXX";
  $database_I = "XXXXX";
  $database_II = "XXXXX";

  session_start();

  try
  {
    $connection_I = new PDO("mysql:host=$server;dbname=$database_I;", $username, $password);
    $connection_II = new PDO("mysql:host=$server;dbname=$database_II;", $username, $password);
  }

  catch(PDOException $e)
  {
    die("Connection Failed:  " . $e->getMessage());
  }	
?>

<!--==================================================-->
