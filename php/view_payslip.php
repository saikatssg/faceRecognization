<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['empid'])) {
    header("Location: login.php");
    exit();
}

$empid = $_SESSION['empid'];
$payroll_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($payroll_id)) {
    die("Invalid Payslip ID");
}

// Fetch payroll data and employee data
if ($_SESSION['dept'] == 11) {
    // Admin can view any payslip
    $sql = "SELECT p.*, e.first_name, e.last_name, e.email, d.dept_name, s.salary
            FROM payroll p
            JOIN employees e ON p.emp_id = e.emp_id
            JOIN dept_master d ON p.dept_id = d.dept
            JOIN salary_master s ON p.sal_id = s.sal_id
            WHERE p.payroll_id = '$payroll_id'";
} else {
    // Employee can only view their own
    $sql = "SELECT p.*, e.first_name, e.last_name, e.email, d.dept_name, s.salary
            FROM payroll p
            JOIN employees e ON p.emp_id = e.emp_id
            JOIN dept_master d ON p.dept_id = d.dept
            JOIN salary_master s ON p.sal_id = s.sal_id
            WHERE p.payroll_id = '$payroll_id' AND p.emp_id = '$empid'";
}

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Payslip not found or you do not have permission to view it.");
}

$payslip = mysqli_fetch_assoc($result);

// Calculate gross and deductions
$basic = $payslip['salary'];
$da = $payslip['da'];
$hra = $payslip['hra'];
$ta = $payslip['ta'];
$gross = $basic + $da + $hra + $ta;

$pf = $payslip['pf'];
$ptax = $payslip['ptax'];
$deductions = $pf + $ptax;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - <?php echo htmlspecialchars($payroll_id); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .payslip-container { max-width: 800px; margin: 40px auto; background: #fff; padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
        .company-header { text-align: center; border-bottom: 2px solid var(--primary-color); padding-bottom: 20px; margin-bottom: 30px; }
        .company-header h2 { color: var(--primary-color); font-weight: 700; margin-bottom: 5px; }
        .table-custom th { background-color: #f8f9fc; }
        .print-btn { position: fixed; bottom: 30px; right: 30px; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 1000; }
        @media print {
            .print-btn, .back-btn { display: none !important; }
            body { background-color: #fff; }
            .payslip-container { box-shadow: none; margin: 0; padding: 0; }
        }
    </style>
</head>
<body>

<a href="javascript:history.back()" class="btn btn-secondary m-3 back-btn">&larr; Go Back</a>

<button onclick="window.print()" class="btn btn-primary print-btn" title="Print Payslip">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
    </svg>
</button>

<div class="payslip-container">
    <div class="company-header">
        <h2>PayrollMaster Enterprise</h2>
        <p class="text-muted mb-0">123 Enterprise Blvd, Tech City | support@payrollmaster.com</p>
        <h4 class="mt-4 text-uppercase">Salary Slip</h4>
        <p class="mb-0 text-muted">Transaction ID: <?php echo htmlspecialchars($payslip['payroll_id']); ?></p>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($payslip['emp_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($payslip['first_name'] . ' ' . $payslip['last_name']); ?></p>
        </div>
        <div class="col-md-6 text-md-end">
            <p><strong>Department:</strong> <?php echo htmlspecialchars($payslip['dept_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($payslip['email']); ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h6 class="border-bottom pb-2 mb-3">Earnings</h6>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Basic Salary</td>
                    <td class="text-end">₹<?php echo number_format($basic, 2); ?></td>
                </tr>
                <tr>
                    <td>Dearness Allowance (DA)</td>
                    <td class="text-end">₹<?php echo number_format($da, 2); ?></td>
                </tr>
                <tr>
                    <td>House Rent Allowance (HRA)</td>
                    <td class="text-end">₹<?php echo number_format($hra, 2); ?></td>
                </tr>
                <tr>
                    <td>Travel Allowance (TA)</td>
                    <td class="text-end">₹<?php echo number_format($ta, 2); ?></td>
                </tr>
                <tr class="fw-bold border-top">
                    <td class="pt-2">Gross Salary</td>
                    <td class="text-end pt-2 text-success">₹<?php echo number_format($gross, 2); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="border-bottom pb-2 mb-3">Deductions</h6>
            <table class="table table-borderless table-sm">
                <tr>
                    <td>Provident Fund (PF)</td>
                    <td class="text-end text-danger">- ₹<?php echo number_format($pf, 2); ?></td>
                </tr>
                <tr>
                    <td>Professional Tax (PTAX)</td>
                    <td class="text-end text-danger">- ₹<?php echo number_format($ptax, 2); ?></td>
                </tr>
                <tr class="fw-bold border-top">
                    <td class="pt-2">Total Deductions</td>
                    <td class="text-end pt-2 text-danger">- ₹<?php echo number_format($deductions, 2); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="alert alert-success mt-4 p-4 text-center">
        <h5 class="mb-1">Net Payable Salary</h5>
        <h2 class="mb-0 fw-bold">₹<?php echo number_format($payslip['net_salary'], 2); ?></h2>
    </div>
    
    <div class="text-center text-muted mt-5" style="font-size: 0.8rem;">
        <p>This is a computer-generated payslip and does not require a physical signature.</p>
    </div>
</div>

</body>
</html>
