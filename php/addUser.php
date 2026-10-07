<?php 

include 'connection.php';
$msg = "";

// check form is submitted by user or hecker 



// collect all data 

$fname = trim($_POST['fname']);
$lname = trim($_POST['lname']);
$user = trim($_POST['user']);
$pwd = trim($_POST['pwd']);
$dob = trim($_POST['dob']);
$jdate = trim($_POST['jdate']);
$email = trim($_POST['email']);
$gender = trim($_POST['gender']);
$city = trim($_POST['city']);
$dept = trim($_POST['dept']);
$phone = trim($_POST['phone']);


$filename = trim($_FILES['upload']['name']);
$tempname = trim($_FILES['upload']['tmp_name']);

$checkUser = "SELECT * FROM employees WHERE username = '".$user."' OR password ='".$pwd."' OR email = '".$email."'";

// echo $checkUser;

$checkQuery = mysqli_query($conn,$checkUser);


if(mysqli_num_rows($checkQuery) > 0)
{
    $msg = "Username ".$user."  is already exist, Pls login .....";

    header("Location: signup.php?err=".$msg);
}
else{

    $fetchDept = "SELECT d.abbr FROM `dept_master` d WHERE d.dept = '".$dept."'" ;

    $deptId ='';

    $fetchQuery = mysqli_query($conn,$fetchDept);

    if(mysqli_num_rows($fetchQuery)> 0)
    {
        while($row =mysqli_fetch_assoc($fetchQuery))
        {
                $deptId = $row['abbr'];
        }
    }

    $prefix = "EMP/".$deptId."/".date("mY")."/";

    $sqlID = "SELECT COALESCE(MAX(CAST(SUBSTR(e.emp_id,LENGTH(e.emp_id)-2) AS UNSIGNED)),0) AS 'EMPID' FROM employees e WHERE e.emp_id LIKE '".$prefix."%'";

    //  echo $sqlID;

    $empIDQuery = mysqli_query($conn,$sqlID);

    $empID ='';

    while($row = mysqli_fetch_assoc($empIDQuery))
    {
        $empID = $row['EMPID'];
    }

    $ID = $prefix.str_pad((intval($empID)+1),3,"0",STR_PAD_LEFT );

    $file_path = "../others/uploads/".$filename;
    // echo $ID;

    $sql = "INSERT INTO `employees` (`emp_id`, `first_name`, `last_name`, `username`, `dept`, `password`, `dob`,`join_date`, `email`, `mobile`, `gender`, `city`, `photo`, `created_on`) VALUES ('".$ID."', '".$fname."', '".$lname."', '".$user."', '".$dept."', '".$pwd."', '".$dob."','".$jdate."', '".$email."', '".$phone."', '".$gender."', '".$city."', '".$file_path."', current_timestamp())";

    // echo $sql;

    $query = mysqli_query($conn,$sql);

    if($query)
    {
        $msg ="You have successfuly created your account!!... Pls login...";
        if(move_uploaded_file($tempname,$file_path))
        {
             header("location:./login.php?err=".$msg);
        }
    }
}





?>