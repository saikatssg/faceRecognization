<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is an Admin (assuming dept 11 is admin based on setup)
if (!isset($_SESSION['empid']) || $_SESSION['dept'] != 11) {
    header("Location: login.php");
    exit();
}

// Fetch some basic stats for the dashboard
$total_employees = 0;
$total_departments = 0;
$total_payroll_generated = 0;

$emp_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM employees WHERE dept != 11");
if ($emp_result) { $total_employees = mysqli_fetch_assoc($emp_result)['count']; }

$dept_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM dept_master");
if ($dept_result) { $total_departments = mysqli_fetch_assoc($dept_result)['count']; }

$payroll_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM payroll");
if ($payroll_result) { $total_payroll_generated = mysqli_fetch_assoc($payroll_result)['count']; }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PayrollMaster</title>
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
        .stat-card { border: none; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card .card-body { padding: 1.5rem; }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-building"></i> PayrollMaster
        </div>
        <a href="admin_dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="manage_employees.php"><i class="bi bi-person-lines-fill me-2"></i> Manage Employees</a>
        <a href="mark_attendance.php"><i class="bi bi-calendar-check me-2"></i> Mark Attendance</a>
        <a href="manage_leaves.php"><i class="bi bi-calendar-x me-2"></i> Leave Approvals</a>
        <a href="generate_payroll.php"><i class="bi bi-cash-stack me-2"></i> Generate Payroll</a>
        <a href="addUser.php"><i class="bi bi-person-plus me-2"></i> Add Employee</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Admin Dashboard</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></span>
                <?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-4 col-md-6">
                <a href="manage_employees.php" class="text-decoration-none">
                    <div class="card stat-card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Total Employees</h6>
                                    <h2 class="mb-0 font-weight-bold"><?php echo $total_employees; ?></h2>
                                </div>
                                <i class="bi bi-people fa-2x" style="font-size: 2.5rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-6">
                <a href="view_departments.php" class="text-decoration-none">
                    <div class="card stat-card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Departments</h6>
                                    <h2 class="mb-0 font-weight-bold"><?php echo $total_departments; ?></h2>
                                </div>
                                <i class="bi bi-building fa-2x" style="font-size: 2.5rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 col-md-6">
                <a href="view_all_payslips.php" class="text-decoration-none">
                    <div class="card stat-card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Payroll Records Generated</h6>
                                    <h2 class="mb-0 font-weight-bold"><?php echo $total_payroll_generated; ?></h2>
                                </div>
                                <i class="bi bi-file-earmark-text fa-2x" style="font-size: 2.5rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="mb-0 text-primary">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Use the sidebar to navigate or start here to quickly perform administrative tasks.</p>
                        <a href="generate_payroll.php" class="btn btn-primary me-2"><i class="bi bi-calculator me-1"></i> Run Payroll System</a>
                        <a href="addUser.php" class="btn btn-outline-secondary"><i class="bi bi-person-plus me-1"></i> Register New Employee</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
