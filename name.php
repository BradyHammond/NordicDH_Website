<?php
    require "database.php";
    
    $corpus = $_REQUEST['corpus'];
    $part_of_speech = $_REQUEST['part_of_speech'];
    $chunk_size = $_REQUEST['chunk_size'];
    $topic_number = $_REQUEST['topic_number'];	
    $global_id = $_REQUEST['global_id'];
    $submit_name = $_REQUEST['submit_name'];
    
    $update_name = $connection_II->prepare("UPDATE main SET name = :name WHERE global_id = :global_id");
    $update_name->bindParam(":global_id", $global_id);
    $update_name->bindParam(":name", $submit_name);
    $update_name->execute();
    header("Location:combined_topics?corpus=$corpus&part-of-speech=$part_of_speech&chunk-size=$chunk_size&topic-number=$topic_number&submit_name=$submit_name");  
?>