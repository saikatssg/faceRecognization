<?php
header('Content-Type: application/json');
require_once 'connection.php';

$user = isset($_POST['user']) ? mysqli_real_escape_string($conn, $_POST['user']) : '';
$pwd = isset($_POST['pwd']) ? mysqli_real_escape_string($conn, $_POST['pwd']) : '';

if (empty($user) || empty($pwd)) {
    echo json_encode(['success' => false, 'message' => 'Missing username or password']);
    exit();
}

$sql = "SELECT photo FROM employees WHERE username = '$user' AND password = '$pwd'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $photo = $row['photo'];
    
    if (empty($photo)) {
        echo json_encode(['success' => false, 'message' => 'No profile photo found in our system for this user.']);
        exit();
    }
    
    echo json_encode(['success' => true, 'photo' => $photo]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
}
?>
