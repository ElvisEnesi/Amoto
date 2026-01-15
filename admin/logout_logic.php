<?php    
    include "./configuration/constant.php";
    session_destroy();
    header("location: " . root_url . "index.php");
    die();