<?php
session_start();
include './connection.php';

// Check if it's an AJAX request or form submission
if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    $sql = "SELECT * FROM employee WHERE username='$user' AND password='$pwd'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['empid'] = $row['empid'];
        $_SESSION['fname'] = $row['fname'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['photo'] = $row['photo'];
        
        // Success response or redirect
        if(isset($_POST['ajax'])) {
            echo json_encode(['success' => true]);
        } else {
            header("Location: ../index.php");
        }
        exit();
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
