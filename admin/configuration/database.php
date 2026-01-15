<?php
    // database variables
    $server = "localhost";
    $username = "root";
    $password = "";
    $DBname = "amoto";
    // make connection
    $connection = new mysqli($server, $username, $password, $DBname);
    if (mysqli_errno($connection)) {
        // decision
        die("Connection failed: " .mysqli_errno($connection));
    } 