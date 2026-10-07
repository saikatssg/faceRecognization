<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is Admin (11) or HR (2)
if (!isset($_SESSION['empid']) || !in_array($_SESSION['dept'], [2, 11])) {
    header("Location: login.php");
    exit();
}

$current_user_dept = $_SESSION['dept'];
$current_user_id = $_SESSION['empid'];
$is_admin = ($current_user_dept == 11);

if (!isset($_GET['id'])) {
    die("Employee ID not provided.");
}

$target_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch target employee
$sql = "SELECT * FROM employees WHERE emp_id = '$target_id'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

if (!$employee) {
    die("Employee not found.");
}

// Enforce rules
$target_dept = $employee['dept'];
if (!$is_admin && in_array($target_dept, [2, 11])) {
    die("Permission Denied: HR cannot edit other HR or Admin profiles.");
}
if ($is_admin && $target_dept == 11 && $target_id != $current_user_id) {
    die("Permission Denied: Admins cannot edit other Admin profiles.");
}

// Fetch all departments for dropdown
$dept_query = "SELECT * FROM dept_master";
$dept_result = mysqli_query($conn, $dept_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - PayrollMaster</title>
    <?php include './header.php'; ?>
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); padding-top: 20px; flex-shrink: 0; }
        .sidebar .brand { padding: 0 20px 20px; font-size: 1.2rem; font-weight: 700; color: var(--primary-color); border-bottom: 1px solid var(--border-color); margin-bottom: 15px; }
        .sidebar a { color: #555; text-decoration: none; display: block; padding: 12px 20px; font-size: 0.95rem; }
        .sidebar a:hover, .sidebar a.active { background: #f1f3f9; color: var(--primary-color); font-weight: 600; }
        .main-content { flex-grow: 1; padding: 30px; background-color: var(--bg-color); }
        .topbar { background: #fff; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; justify-content: space-between; align-items: center; margin: -30px -30px 30px -30px; }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="bi <?php echo $is_admin ? 'bi-building' : 'bi-people-fill'; ?>"></i> 
            <?php echo $is_admin ? 'PayrollMaster' : 'HR Portal'; ?>
        </div>
        <a href="manage_employees.php" class="active"><i class="bi bi-arrow-left me-2"></i> Back to List</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Edit Employee: <?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="update_profile.php" method="POST">
                            <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($employee['emp_id']); ?>">
                            <input type="hidden" name="is_self" value="0">
                            
                            <h6 class="border-bottom pb-2 mb-4 text-primary">Contact Details</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" value="<?php echo htmlspecialchars($employee['mobile']); ?>" required>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">City</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($employee['city']); ?>" required>
                                </div>
                            </div>

                            <h6 class="border-bottom pb-2 mb-4 text-primary">Privileges & Roles</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Department / Privilege Role</label>
                                    <select name="dept" class="form-select" required>
                                        <?php while($d = mysqli_fetch_assoc($dept_result)): ?>
                                            <?php 
                                                // HR cannot assign Admin (11) or HR (2) roles
                                                if (!$is_admin && in_array($d['dept'], [2, 11])) continue; 
                                            ?>
                                            <option value="<?php echo $d['dept']; ?>" <?php if($employee['dept'] == $d['dept']) echo 'selected'; ?>>
                                                <?php echo htmlspecialchars($d['dept_name']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <?php if(!$is_admin): ?>
                                        <small class="text-danger mt-1 d-block">Note: HR cannot assign Admin or HR privileges.</small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Reset Password (leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
