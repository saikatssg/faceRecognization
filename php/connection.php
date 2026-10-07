<?php 

$server = "localhost";
$username = "root";
$password = "";
$dbname = "emp_payroll";


$conn = mysqli_connect($server,$username,$password,$dbname);
if(!$conn)
{
    die("Database failed to connect due to : " . mysqli_connect_error());
    exit();
}

?>