<?php
  require "database.php";
  
  $word = $_REQUEST['word'];
  $header = $_REQUEST['header'];
  $global_id = $_REQUEST['global_id'];
  $submit_name = $_REQUEST['submit_name'];
  
  
  $update_name = $connection_II->prepare("UPDATE main SET name = :name WHERE global_id = :global_id");
  $update_name->bindParam(":global_id", $global_id);
  $update_name->bindParam(":name", $submit_name);
  $update_name->execute();
  
  header("Location:words_search?word=$word&header=$header");  
?>