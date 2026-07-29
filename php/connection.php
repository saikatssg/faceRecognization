<?php 

$server="localhost"; //127.0.0.1:3306
$user ="root";
$password="";
$database="emp";

$conn = mysqli_connect($server,$user,$password,$database);

if(!$conn)
    {
        echo "Database connection Failed due to ...".die(mysqli_connect_error());
        exit();
    }
  
?>