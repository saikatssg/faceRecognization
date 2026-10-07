<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['empid'])) {
    header("Location: login.php");
    exit();
}

$empid = $_SESSION['empid'];

// Fetch employee details
$sql = "SELECT e.*, d.dept_name, s.salary 
        FROM employees e 
        LEFT JOIN dept_master d ON e.dept = d.dept 
        LEFT JOIN salary_master s ON d.dept = s.dept
        WHERE e.emp_id = '$empid'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

// Fetch recent payslips
$payslips_sql = "SELECT * FROM payroll WHERE emp_id = '$empid' ORDER BY payroll_id DESC LIMIT 5";
$payslips_result = mysqli_query($conn, $payslips_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - PayrollMaster</title>
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
        .profile-card { background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); padding: 20px; text-align: center; }
        .profile-card img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 4px solid var(--primary-color); margin-bottom: 15px; }
        .info-card { background: #fff; border-radius: 8px; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); padding: 20px; height: 100%; }
    </style>
</head>
<body>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <i class="bi bi-building"></i> PayrollMaster
        </div>
        <a href="employee_dashboard.php" class="active"><i class="bi bi-person me-2"></i> My Profile</a>
        <a href="my_payslips.php"><i class="bi bi-receipt me-2"></i> My Payslips</a>
        <a href="leave_requests.php"><i class="bi bi-calendar me-2"></i> Leave Requests</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Employee Dashboard</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></span>
                <?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profile Column -->
            <div class="col-md-4">
                <div class="profile-card">
                    <img src="<?php echo htmlspecialchars($employee['photo'] ?? '../images/default.png'); ?>" alt="Profile Photo">
                    <h4><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($employee['dept_name'] ?? 'Department Not Assigned'); ?></p>
                    <p class="badge bg-primary">Employee ID: <?php echo htmlspecialchars($employee['emp_id']); ?></p>
                </div>
            </div>

            <!-- Details Column -->
            <div class="col-md-8">
                <div class="info-card">
                    <h5 class="mb-4 border-bottom pb-2 text-primary">Personal Details</h5>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Email</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars($employee['email']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Mobile</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars($employee['mobile']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Date of Birth</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars(date('d M Y', strtotime($employee['dob']))); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Join Date</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars(date('d M Y', strtotime($employee['join_date']))); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">City</div>
                        <div class="col-sm-8 fw-semibold"><?php echo htmlspecialchars($employee['city']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Basic Salary</div>
                        <div class="col-sm-8 fw-bold text-success">₹<?php echo number_format($employee['salary'] ?? 0, 2); ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Update & Leave Requests Row -->
        <div class="row mt-4 g-4">
            <!-- Edit Profile -->
            <div class="col-md-8">
                <div class="info-card">
                    <h5 class="mb-4 border-bottom pb-2 text-primary">Update Profile</h5>
                    <form action="update_profile.php" method="POST">
                        <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($employee['emp_id']); ?>">
                        <input type="hidden" name="is_self" value="1">
                        
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
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">City</label>
                                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($employee['city']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password">
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Leave Requests -->
            <div class="col-md-4">
                <div class="info-card text-center d-flex flex-column justify-content-center">
                    <h5 class="mb-4 border-bottom pb-2 text-primary">Request Leave</h5>
                    <p class="text-muted">Need a day off? Submit your leave request to Admin for approval.</p>
                    <a href="leave_requests.php" class="btn btn-outline-primary mt-2"><i class="bi bi-calendar-plus me-1"></i> Submit Leave Request</a>
                </div>
            </div>
        </div>
        
        <!-- Recent Payslips -->
        <div class="row mt-4" id="payslips-section">
            <div class="col-12">
                <div class="info-card">
                    <h5 class="mb-4 border-bottom pb-2 text-primary">Recent Payslips</h5>
                    <?php if (mysqli_num_rows($payslips_result) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Payroll ID</th>
                                        <th>Net Salary</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($payslips_result)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['payroll_id']); ?></td>
                                        <td class="fw-bold text-success">₹<?php echo number_format($row['net_salary'], 2); ?></td>
                                        <td>
                                            <a href="view_payslip.php?id=<?php echo urlencode($row['payroll_id']); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-3">No payslips generated yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
