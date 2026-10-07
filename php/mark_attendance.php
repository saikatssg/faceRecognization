<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is Admin (11) or HR (2)
if (!isset($_SESSION['empid']) || !in_array($_SESSION['dept'], [2, 11])) {
    header("Location: login.php");
    exit();
}

$current_user_dept = $_SESSION['dept'];
$is_admin = ($current_user_dept == 11);
$message = '';

$selected_date = isset($_GET['date']) ? mysqli_real_escape_string($conn, $_GET['date']) : date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_bulk_attendance'])) {
    $att_date = mysqli_real_escape_string($conn, $_POST['att_date']);
    $statuses = $_POST['status']; // Array of emp_id => status
    
    $success_count = 0;
    
    // Get max id first
    $max_res = mysqli_query($conn, "SELECT MAX(att_id) as max_id FROM attendance");
    $next_id = ($max_row = mysqli_fetch_assoc($max_res)) ? $max_row['max_id'] + 1 : 1;
    
    foreach ($statuses as $emp_id => $status) {
        $emp_id = mysqli_real_escape_string($conn, $emp_id);
        $status = mysqli_real_escape_string($conn, $status);
        
        $check_sql = "SELECT att_id FROM attendance WHERE emp_id = '$emp_id' AND att_date = '$att_date'";
        $check_res = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_res) > 0) {
            $update_sql = "UPDATE attendance SET status = '$status' WHERE emp_id = '$emp_id' AND att_date = '$att_date'";
            if (mysqli_query($conn, $update_sql)) $success_count++;
        } else {
            $insert_sql = "INSERT INTO attendance (att_id, emp_id, att_date, status) VALUES ($next_id, '$emp_id', '$att_date', '$status')";
            if (mysqli_query($conn, $insert_sql)) {
                $success_count++;
                $next_id++;
            }
        }
    }
    
    $message = "Successfully saved attendance for $success_count employees on $att_date.";
    $selected_date = $att_date; // Keep selected date active
}

// Fetch employees and their attendance for the selected date
$query_cond = $is_admin ? "" : "WHERE e.dept NOT IN (2, 11)";
$sql = "SELECT e.emp_id, e.first_name, e.last_name, a.status 
        FROM employees e 
        LEFT JOIN attendance a ON e.emp_id = a.emp_id AND a.att_date = '$selected_date' 
        $query_cond
        ORDER BY e.first_name ASC";
$emp_list = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Mark Attendance - PayrollMaster</title>
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
        <a href="<?php echo $is_admin ? 'admin_dashboard.php' : 'hr_dashboard.php'; ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="manage_employees.php"><i class="bi bi-person-lines-fill me-2"></i> Manage Employees</a>
        <a href="mark_attendance.php" class="active"><i class="bi bi-calendar-check me-2"></i> Mark Attendance</a>
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
            <h4 class="mb-0">Bulk Attendance Entry</h4>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                
                <?php if($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <form method="GET" action="" class="row align-items-center">
                            <div class="col-auto">
                                <label class="form-label fw-bold mb-0">Select Date to Mark:</label>
                            </div>
                            <div class="col-auto">
                                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
                            </div>
                            <div class="col-auto">
                                <noscript><button type="submit" class="btn btn-primary">Load</button></noscript>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <form method="POST" action="">
                            <input type="hidden" name="att_date" value="<?php echo htmlspecialchars($selected_date); ?>">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Employee ID</th>
                                            <th>Name</th>
                                            <th>Attendance Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($emp = mysqli_fetch_assoc($emp_list)): ?>
                                        <tr>
                                            <td class="ps-4 text-muted fw-bold"><?php echo htmlspecialchars($emp['emp_id']); ?></td>
                                            <td><?php echo htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']); ?></td>
                                            <td style="width: 300px;">
                                                <select name="status[<?php echo htmlspecialchars($emp['emp_id']); ?>]" class="form-select form-select-sm shadow-none border-secondary" required>
                                                    <option value="Present" <?php echo ($emp['status'] == 'Present' || empty($emp['status'])) ? 'selected' : ''; ?>>Present</option>
                                                    <option value="Absent" <?php echo ($emp['status'] == 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                                    <option value="Half" <?php echo ($emp['status'] == 'Half') ? 'selected' : ''; ?>>Half Day</option>
                                                    <option value="Leave" <?php echo ($emp['status'] == 'Leave') ? 'selected' : ''; ?>>On Leave</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                        <?php if(mysqli_num_rows($emp_list) == 0): ?>
                                            <tr><td colspan="3" class="text-center py-4 text-muted">No employees found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php if(mysqli_num_rows($emp_list) > 0): ?>
                            <div class="p-3 text-end bg-light border-top">
                                <button type="submit" name="save_bulk_attendance" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i> Save All Attendance</button>
                            </div>
                            <?php endif; ?>
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
