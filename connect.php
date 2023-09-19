<?php
    header("Access-Control-Allow-Origin:http://localhost:4200");
    header("Access-Control-Allow-Headers: Content-Type");
    $server = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'school_db';
    $connection = new mysqli($server, $username, $password, $dbname);

    if ($connection->connect_error) {   
        echo 'Error! No connection';
    }
    else{
        // echo 'Success';
    }
?>