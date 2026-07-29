<?php
header('Content-Type: application/json');
include './connection.php';

if (isset($_POST['user']) && isset($_POST['pwd'])) {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    $sql = "SELECT * FROM employee WHERE username='$user' AND password='$pwd'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $photo_url = $row['photo'];
        
        // If the URL is full, return it as is, otherwise construct it
        echo json_encode(['success' => true, 'photo' => $photo_url]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid Credentials']);
        exit();
    }
}

echo json_encode(['success' => false, 'message' => 'Missing parameters']);
?>
