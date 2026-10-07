<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is Admin
if (!isset($_SESSION['empid']) || $_SESSION['dept'] != 11) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'])) {
    $emp_id = mysqli_real_escape_string($conn, $_POST['emp_id']);
    
    // Fetch employee, department, and basic salary
    $sql = "SELECT e.emp_id, e.dept, s.sal_id, s.salary 
            FROM employees e 
            JOIN salary_master s ON e.dept = s.dept 
            WHERE e.emp_id = '$emp_id'";
            
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        $basic = $row['salary'];
        $dept_id = $row['dept'];
        $sal_id = $row['sal_id'];
        
        // Calculations
        $da = $basic * 0.15;
        $hra = $basic * 0.12;
        $ta = $basic * 0.05;
        $pf = $basic * 0.145;
        $ptax = $basic * 0.12;
        
        $gross_salary = $basic + $da + $hra + $ta;
        $total_deductions = $pf + $ptax;
        $net_salary = $gross_salary - $total_deductions;
        
        $payroll_id = 'PRL' . time() . rand(10,99);
        
        // Insert into payroll table
        $insert_sql = "INSERT INTO payroll (payroll_id, emp_id, dept_id, sal_id, da, hra, ta, pf, ptax, net_salary) 
                       VALUES ('$payroll_id', '$emp_id', '$dept_id', '$sal_id', '$da', '$hra', '$ta', '$pf', '$ptax', '$net_salary')";
                       
        if (mysqli_query($conn, $insert_sql)) {
            header("Location: generate_payroll.php?success=" . urlencode($payroll_id) . "&emp=" . urlencode($emp_id));
            exit();
        } else {
            $error = "Error generating payroll: " . mysqli_error($conn);
        }
    } else {
        $error = "Could not fetch salary details for this employee. Ensure they are assigned to a department with a valid salary master.";
    }
}

// Handle GET success redirect
if (isset($_GET['success'])) {
    $payroll_id = mysqli_real_escape_string($conn, $_GET['success']);
    $emp_id_success = isset($_GET['emp']) ? htmlspecialchars($_GET['emp']) : '';
    $message = "Payroll generated successfully for Employee ID: $emp_id_success";
    
    // Fetch the breakdown to display
    $breakdown_sql = "SELECT p.*, s.salary FROM payroll p JOIN salary_master s ON p.sal_id = s.sal_id WHERE p.payroll_id = '$payroll_id'";
    $breakdown_res = mysqli_query($conn, $breakdown_sql);
    if ($breakdown_res && mysqli_num_rows($breakdown_res) > 0) {
        $row = mysqli_fetch_assoc($breakdown_res);
        $basic = $row['salary'];
        $da = $row['da'];
        $hra = $row['hra'];
        $ta = $row['ta'];
        $total_deductions = $row['pf'] + $row['ptax'];
        $net_salary = $row['net_salary'];
    }
}

// Fetch all employees for dropdown
$emp_list = mysqli_query($conn, "SELECT emp_id, first_name, last_name FROM employees WHERE dept != 11");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Payroll - Admin</title>
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
        <a href="generate_payroll.php" class="active"><i class="bi bi-cash-stack me-2"></i> Generate Payroll</a>
        <a href="addUser.php"><i class="bi bi-person-plus me-2"></i> Add Employee</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Generate Payroll</h4>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></span>
                <?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                <?php endif; ?>
            </div>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h5 class="mb-0 text-primary">Run Payroll Calculation</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if($message): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-4">
                                <label for="emp_id" class="form-label text-muted">Select Employee</label>
                                <select name="emp_id" id="emp_id" class="form-select border-0 border-bottom rounded-0 shadow-none px-0" required style="border-bottom: 1px solid var(--primary-color) !important;">
                                    <option value="" disabled selected>-- Choose an Employee --</option>
                                    <?php while($emp = mysqli_fetch_assoc($emp_list)): ?>
                                        <option value="<?php echo htmlspecialchars($emp['emp_id']); ?>">
                                            <?php echo htmlspecialchars($emp['emp_id'] . ' - ' . $emp['first_name'] . ' ' . $emp['last_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="alert alert-info mt-4" style="font-size: 0.9rem;">
                                <strong>System Note:</strong> 
                                Payroll calculation will automatically apply DA (15%), HRA (12%), TA (5%), PF (14.5%), and PTAX (12%) based on the employee's basic salary mapped from their department.
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" name="generate" class="btn btn-primary px-4"><i class="bi bi-gear-fill me-2"></i> Generate & Save Payslip</button>
                            </div>
                        </form>

                        <?php if($message && isset($payroll_id)): ?>
                            <div class="mt-5 border-top pt-4">
                                <h5 class="text-success mb-3"><i class="bi bi-receipt me-2"></i>Generated Payroll Breakdown</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0">
                                        <tbody>
                                            <tr>
                                                <th class="bg-light" style="width: 30%;">Payroll ID</th>
                                                <td><?php echo htmlspecialchars($payroll_id); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Basic Salary</th>
                                                <td>₹<?php echo number_format($basic, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Allowances (DA + HRA + TA)</th>
                                                <td class="text-success">+ ₹<?php echo number_format($da + $hra + $ta, 2); ?></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Deductions (PF + PTAX)</th>
                                                <td class="text-danger">- ₹<?php echo number_format($total_deductions, 2); ?></td>
                                            </tr>
                                            <tr class="table-success">
                                                <th>Net Salary</th>
                                                <th class="fs-5">₹<?php echo number_format($net_salary, 2); ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-end mt-3">
                                    <a href="view_payslip.php?id=<?php echo htmlspecialchars($payroll_id); ?>" class="btn btn-outline-success">
                                        <i class="bi bi-printer me-2"></i> View & Print Payslip
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
