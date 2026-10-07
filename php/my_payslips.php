<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['empid'])) {
    header("Location: login.php");
    exit();
}

$empid = $_SESSION['empid'];

$query = "SELECT p.*, e.first_name, e.last_name, d.dept_name 
          FROM payroll p 
          JOIN employees e ON p.emp_id = e.emp_id 
          JOIN dept_master d ON p.dept_id = d.dept
          WHERE p.emp_id = '$empid'
          ORDER BY p.payroll_id DESC";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payslips - PayrollMaster</title>
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
        <a href="employee_dashboard.php"><i class="bi bi-person me-2"></i> My Profile</a>
        <a href="my_payslips.php" class="active"><i class="bi bi-receipt me-2"></i> My Payslips</a>
        <a href="leave_requests.php"><i class="bi bi-calendar me-2"></i> Leave Requests</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">My Payslips</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></span>
                <?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0 text-primary">All Generated Payslips</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Payroll ID</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Net Salary</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <?php 
                                    $basic = $row['sal_id'] ? $row['sal_id'] : 0; // We don't have basic salary strictly in this row, it's joined in view_payslip but we can show gross/deductions
                                    $allowances = $row['da'] + $row['hra'] + $row['ta'];
                                    $deductions = $row['pf'] + $row['ptax'];
                                ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary"><?php echo htmlspecialchars($row['payroll_id']); ?></td>
                                <td class="text-muted">Generated</td>
                                <td class="text-success">+ ₹<?php echo number_format($allowances, 2); ?></td>
                                <td class="text-danger">- ₹<?php echo number_format($deductions, 2); ?></td>
                                <td class="fw-bold text-success">₹<?php echo number_format($row['net_salary'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <a href="view_payslip.php?id=<?php echo urlencode($row['payroll_id']); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-printer"></i> View & Print
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted">You have no payslips generated yet.</td></tr>
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
