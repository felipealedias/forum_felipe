<?php
    
    //declare variables
    $server = "localhost";
    $user = "root";
    $password = "";
    $dataBase = "forum";
    $connect = mysqli_connect($server,$user,$password,$dataBase);

    //test connection
    if ( mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_errno() );
    }

?>