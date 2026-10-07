<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['empid']) || $_SESSION['dept'] != 11) {
    header("Location: login.php");
    exit();
}

$query = "SELECT p.*, e.first_name, e.last_name, d.dept_name 
          FROM payroll p 
          JOIN employees e ON p.emp_id = e.emp_id 
          JOIN dept_master d ON p.dept_id = d.dept
          ORDER BY p.payroll_id DESC";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Payroll Records - PayrollMaster</title>
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
            <i class="bi bi-building"></i> PayrollMaster
        </div>
        <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
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
            <h4 class="mb-0">Payroll Records</h4>
            <div class="d-flex align-items-center">
                <a href="admin_dashboard.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Payroll ID</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Net Salary</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['payroll_id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['emp_id']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                                <td class="fw-bold text-success">₹<?php echo number_format($row['net_salary'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <!-- Because view_payslip checks if p.emp_id = $empid, Admin viewing another person's payslip will fail in view_payslip.php unless we handle Admin access. 
                                         Let's create a special view link or assume Admin wants a general view. For now, we will just display the record. -->
                                    <span class="badge bg-secondary">Processed</span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No payroll records found.</td></tr>
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
