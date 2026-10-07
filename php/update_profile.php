<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['empid']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['empid'];
$current_user_dept = $_SESSION['dept'];
$is_admin = ($current_user_dept == 11);
$is_hr = ($current_user_dept == 2);

$target_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
$is_self = isset($_POST['is_self']) && $_POST['is_self'] == '1';

// If they are not editing themselves, they must be HR or Admin
if (!$is_self && !$is_admin && !$is_hr) {
    die("Permission Denied: You cannot edit other profiles.");
}

// Ensure the user actually is who they claim for self-edits
if ($is_self && $target_id != $current_user_id) {
    die("Permission Denied: Identity mismatch.");
}

// Fetch current target details to enforce rules
$sql_target = "SELECT * FROM employees WHERE emp_id = '$target_id'";
$res_target = mysqli_query($conn, $sql_target);
if (!$res_target || mysqli_num_rows($res_target) == 0) {
    die("Target employee not found.");
}
$target_employee = mysqli_fetch_assoc($res_target);
$target_dept = $target_employee['dept'];

// Enforce HR/Admin rules for cross-edits
if (!$is_self) {
    if ($is_hr && in_array($target_dept, [2, 11])) {
        die("Permission Denied: HR cannot edit HR or Admin profiles.");
    }
    if ($is_admin && $target_dept == 11 && $target_id != $current_user_id) {
        die("Permission Denied: Admins cannot edit other Admin profiles.");
    }
}

// Collect data
$email = mysqli_real_escape_string($conn, $_POST['email']);
$mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$password = !empty($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : null;

// Only Admins or HR can update dept (privilege), and only if they aren't self-editing
$new_dept = $target_dept;
if (!$is_self && isset($_POST['dept'])) {
    $posted_dept = mysqli_real_escape_string($conn, $_POST['dept']);
    // HR cannot assign Admin or HR roles
    if ($is_hr && in_array($posted_dept, [2, 11])) {
        die("Permission Denied: HR cannot grant Admin or HR privileges.");
    }
    $new_dept = $posted_dept;
}

// Build query
$update_fields = [
    "email = '$email'",
    "mobile = '$mobile'",
    "city = '$city'",
    "dept = '$new_dept'"
];

if ($password) {
    $update_fields[] = "password = '$password'";
}

$update_query = "UPDATE employees SET " . implode(", ", $update_fields) . " WHERE emp_id = '$target_id'";

if (mysqli_query($conn, $update_query)) {
    // Redirect logic
    if ($is_self) {
        if ($current_user_dept == 11) header("Location: admin_dashboard.php?msg=updated");
        else if ($current_user_dept == 2) header("Location: hr_dashboard.php?msg=updated");
        else header("Location: employee_dashboard.php?msg=updated");
    } else {
        header("Location: manage_employees.php?msg=updated");
    }
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
