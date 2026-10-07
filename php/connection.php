<?php 

$server = "localhost";
$port = "3307";
$username = "root";
$password = "";
$dbname = "emp_payroll";


$conn = mysqli_connect($server,$username,$password,$dbname,$port);
if(!$conn)
{
    die("Database failed to connect due to : " . mysqli_connect_error());
    exit();
}

?>