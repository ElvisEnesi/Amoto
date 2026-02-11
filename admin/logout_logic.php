<?php    
    include "./configuration/constant.php";
    session_unset();
    session_destroy();
    header("location: " . root_url . "index.php");
    die();