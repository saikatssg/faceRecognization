<?php
session_start();
require_once 'connection.php';

// Check if user is logged in and is Admin (11)
if (!isset($_SESSION['empid']) || $_SESSION['dept'] != 11) {
    header("Location: login.php");
    exit();
}

$message = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $req_id = (int)$_GET['id'];
    
    // Fetch request details
    $req_res = mysqli_query($conn, "SELECT * FROM leave_requests WHERE id = $req_id");
    if ($req_row = mysqli_fetch_assoc($req_res)) {
        if ($action == 'approve') {
            mysqli_query($conn, "UPDATE leave_requests SET status = 'Approved' WHERE id = $req_id");
            
            // Insert into attendance table for each day in range
            $emp_id = $req_row['emp_id'];
            $start = new DateTime($req_row['start_date']);
            $end = new DateTime($req_row['end_date']);
            $end->modify('+1 day'); // Include end date in period
            
            $period = new DatePeriod($start, new DateInterval('P1D'), $end);
            
            foreach ($period as $date) {
                $dt = $date->format('Y-m-d');
                // Use REPLACE or INSERT IGNORE. The current dump doesn't have unique keys on att_date + emp_id, 
                // so we will query max id and insert. Let's delete existing first to be safe.
                mysqli_query($conn, "DELETE FROM attendance WHERE emp_id = '$emp_id' AND att_date = '$dt'");
                
                $max_res = mysqli_query($conn, "SELECT MAX(att_id) as max_id FROM attendance");
                $next_id = ($max_row = mysqli_fetch_assoc($max_res)) ? $max_row['max_id'] + 1 : 1;
                
                mysqli_query($conn, "INSERT INTO attendance (att_id, emp_id, att_date, status) VALUES ($next_id, '$emp_id', '$dt', 'Leave')");
            }
            $message = "Leave approved and attendance records updated.";
        } else if ($action == 'reject') {
            mysqli_query($conn, "UPDATE leave_requests SET status = 'Rejected' WHERE id = $req_id");
            $message = "Leave request rejected.";
        }
    }
}

// Fetch all requests
$requests_query = "
    SELECT l.*, e.first_name, e.last_name 
    FROM leave_requests l 
    JOIN employees e ON l.emp_id = e.emp_id 
    ORDER BY l.created_at DESC
";
$requests_res = mysqli_query($conn, $requests_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Approvals - PayrollMaster</title>
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
        <a href="manage_leaves.php" class="active"><i class="bi bi-calendar-x me-2"></i> Leave Approvals</a>
        <a href="generate_payroll.php"><i class="bi bi-cash-stack me-2"></i> Generate Payroll</a>
        <a href="addUser.php"><i class="bi bi-person-plus me-2"></i> Add Employee</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Leave Approvals</h4>
        </div>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-0">
                <?php if($message): ?>
                    <div class="alert alert-success m-3"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Employee</th>
                                <th>Date Range</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($requests_res)): ?>
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?><br>
                                    <small class="text-muted fw-normal"><?php echo htmlspecialchars($row['emp_id']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['start_date']); ?><br>
                                    <small class="text-muted">to <?php echo htmlspecialchars($row['end_date']); ?></small>
                                </td>
                                <td><div style="max-width: 250px;" class="text-truncate"><?php echo htmlspecialchars($row['reason']); ?></div></td>
                                <td>
                                    <?php
                                        $badge = 'bg-secondary';
                                        if($row['status'] == 'Approved') $badge = 'bg-success';
                                        if($row['status'] == 'Rejected') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if($row['status'] == 'Pending'): ?>
                                        <a href="?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></a>
                                        <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></a>
                                    <?php else: ?>
                                        <span class="text-muted small">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if(mysqli_num_rows($requests_res) == 0): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">No leave requests found.</td></tr>
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
