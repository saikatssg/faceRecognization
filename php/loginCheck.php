<?php
session_start();
include './connection.php';

// Check if it's an AJAX request or form submission
if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    $sql = "SELECT * FROM employees WHERE username='$user' AND password='$pwd'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['empid'] = $row['emp_id'];
        $_SESSION['fname'] = $row['first_name'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['photo'] = $row['photo'];
        $_SESSION['dept'] = $row['dept'];
        
        // Admin & HR check
        if(isset($_POST['ajax'])) {
            $redirect = '../php/employee_dashboard.php';
            if ($row['dept'] == 11 || strtolower($row['dept']) == 'admin') {
                $redirect = '../php/admin_dashboard.php';
            } elseif ($row['dept'] == 2 || strtolower($row['dept']) == 'hr') {
                $redirect = '../php/hr_dashboard.php';
            }
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit();
        } else {
            if ($row['dept'] == 11 || strtolower($row['dept']) == 'admin') {
                header("Location: ../php/admin_dashboard.php");
            } elseif ($row['dept'] == 2 || strtolower($row['dept']) == 'hr') {
                header("Location: ../php/hr_dashboard.php");
            } else {
                header("Location: ../php/employee_dashboard.php");
            }
            exit();
        }
    } else {
        if(isset($_POST['ajax'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid Username or Password']);
        } else {
            header("Location: ./login.php?err=" . urlencode("Invalid Username or Password"));
        }
        exit();
    }
} else {
    header("Location: ./login.php");
    exit();
}
?>
