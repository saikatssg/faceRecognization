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

// Build query based on role
if ($is_admin) {
    // Admin can see everyone except OTHER admins
    $sql = "SELECT e.*, d.dept_name 
            FROM employees e 
            LEFT JOIN dept_master d ON e.dept = d.dept 
            WHERE e.dept != 11 OR e.emp_id = '$current_user_id'";
} else {
    // HR can see standard employees and themselves
    $sql = "SELECT e.*, d.dept_name 
            FROM employees e 
            LEFT JOIN dept_master d ON e.dept = d.dept 
            WHERE e.dept NOT IN (2, 11) OR e.emp_id = '$current_user_id'";
}

$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - PayrollMaster</title>
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
        .table-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px; }
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
        <a href="<?php echo $is_admin ? 'admin_dashboard.php' : 'hr_dashboard.php'; ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="manage_employees.php" class="active"><i class="bi bi-person-lines-fill me-2"></i> Manage Employees</a>
        <a href="mark_attendance.php"><i class="bi bi-calendar-check me-2"></i> Mark Attendance</a>
        <?php if($is_admin): ?>
            <a href="manage_leaves.php"><i class="bi bi-calendar-x me-2"></i> Leave Approvals</a>
            <a href="generate_payroll.php"><i class="bi bi-cash-stack me-2"></i> Generate Payroll</a>
            <a href="addUser.php"><i class="bi bi-person-plus me-2"></i> Add Employee</a>
        <?php endif; ?>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Manage Employees</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></span>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Department / Role</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($row['photo'] ?? '../images/default.png'); ?>" class="table-avatar" alt="Avatar">
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                            <div class="text-muted small">ID: <?php echo htmlspecialchars($row['emp_id']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                        $dept_badge = 'bg-secondary';
                                        if($row['dept'] == 11) $dept_badge = 'bg-danger';
                                        if($row['dept'] == 2) $dept_badge = 'bg-info text-dark';
                                    ?>
                                    <span class="badge <?php echo $dept_badge; ?>"><?php echo htmlspecialchars($row['dept_name'] ?? 'Unassigned'); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                <td class="text-end pe-4">
                                    <a href="edit_employee.php?id=<?php echo urlencode($row['emp_id']); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No employees found.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
