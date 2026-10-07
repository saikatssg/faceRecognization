<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['empid'])) {
    header("Location: login.php");
    exit();
}

$emp_id = $_SESSION['empid'];
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_leave'])) {
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    
    $sql = "INSERT INTO leave_requests (emp_id, start_date, end_date, reason, status) VALUES ('$emp_id', '$start_date', '$end_date', '$reason', 'Pending')";
    if (mysqli_query($conn, $sql)) {
        $message = "Leave request submitted successfully. Awaiting approval.";
    } else {
        $message = "Error submitting leave request: " . mysqli_error($conn);
    }
}

// Fetch user's leave requests
$leaves_res = mysqli_query($conn, "SELECT * FROM leave_requests WHERE emp_id = '$emp_id' ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Leave Requests - PayrollMaster</title>
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
        <a href="my_payslips.php"><i class="bi bi-receipt me-2"></i> My Payslips</a>
        <a href="leave_requests.php" class="active"><i class="bi bi-calendar me-2"></i> Leave Requests</a>
        <a href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h4 class="mb-0">Leave Requests</h4>
        </div>

        <div class="row mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="mb-4 text-primary">Submit New Request</h5>
                        
                        <?php if($message): ?>
                            <div class="alert alert-info py-2"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="text-end mt-4">
                                <button type="submit" name="submit_leave" class="btn btn-primary px-4">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Dates</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($leaves_res)): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="small fw-bold text-nowrap"><?php echo htmlspecialchars($row['start_date']); ?></div>
                                            <div class="text-muted small text-nowrap">to <?php echo htmlspecialchars($row['end_date']); ?></div>
                                        </td>
                                        <td><div class="small text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($row['reason']); ?></div></td>
                                        <td>
                                            <?php
                                                $badge = 'bg-secondary';
                                                if($row['status'] == 'Approved') $badge = 'bg-success';
                                                if($row['status'] == 'Rejected') $badge = 'bg-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if(mysqli_num_rows($leaves_res) == 0): ?>
                                    <tr><td colspan="3" class="text-center py-4 text-muted">No leave requests found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
