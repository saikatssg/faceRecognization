<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PayrollMaster - Modern HR & Payroll</title>
  
    <link href="css/style.css?v=2" rel="stylesheet">
    <link href="css/header.css?v=3" rel="stylesheet">
     <?php include './php/header.php'; ?>
    
  
</head>
<body>

<?php include './php/nav.php'; ?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <?php if(isset($_SESSION['fname'])): ?>
					<div class="d-flex align-items-center mb-3">
						<h3 class="text-warning mb-0 me-3">Welcome back, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</h3>
						<?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
							<img src="<?php echo substr($_SESSION['photo'],1); ?>" alt="Profile Photo" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #ffc107;">
						<?php endif; ?>
					</div>
				<?php endif; ?>
                
            <h1>Automate Your Payroll</h1>
            <p class="mt-4 mb-4">Streamline your HR workflows, track attendance accurately, and process monthly salaries with a single click. Designed for enterprises of all sizes.</p>
            <p class="mb-5 text-light opacity-75">Secure, fast, and reliable employee management.</p>
            <a href="#services" class="btn btn-outline-light px-4 py-2" style="border-radius: 0;">Discover Features</a>
        </div>
    </div>
</div>

<!-- Intro / About Section -->
<section id="about" class="section-padding text-center bg-white">
    <div class="container">
        <h2 class="display-5 mb-4 text-dark" style="font-weight: 300;">Designed for<br>Modern Enterprises</h2>
        <p class="text-muted mx-auto" style="max-width: 700px;">
            Say goodbye to manual spreadsheets. PayrollMaster provides a complete ecosystem for managing employee master data, leave approvals, attendance tracking, and one-click payroll generation, all from an intuitive dashboard.
        </p>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section-padding bg-light-purple">
    <div class="container text-center">
        <h2 class="mb-5" style="font-weight: 300;">Our Features</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <i class="bi bi-person-lines-fill display-4 text-primary mb-3" style="color: var(--primary-color) !important;"></i>
                <h4>Employee Onboarding</h4>
                <p class="text-muted">Easily manage departments, designations, and employee records with secure self-service portals.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-calendar-check display-4 text-primary mb-3" style="color: var(--primary-color) !important;"></i>
                <h4>Attendance & Leaves</h4>
                <p class="text-muted">Track daily presence, handle half-days, and approve or reject leave applications seamlessly.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-calculator display-4 text-primary mb-3" style="color: var(--primary-color) !important;"></i>
                <h4>1-Click Payroll</h4>
                <p class="text-muted">Automatically calculate net salaries based on basic pay, unpaid leaves, and daily attendance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Showcase Section -->
<section id="showcase" class="section-padding bg-white">
    <div class="container text-center">
        <h2 class="mb-5" style="font-weight: 300;">System Showcase</h2>
        <p class="text-muted mb-4">A glimpse into our intuitive dashboards.</p>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="blog-img">
                    <img src="./images/dataAnalytics.jpg" alt="Data Analytics" class="img-fluid rounded shadow-sm">
                </div>
                <h5 class="mt-3">Data Analytics</h5>
            </div>
            <div class="col-md-4">
                <div class="blog-img">
                    <img src="./images/finialcial.jpg" alt="Financial Tracking" class="img-fluid rounded shadow-sm">
                </div>
                <h5 class="mt-3">Financial Tracking</h5>
            </div>
            <div class="col-md-4">
                <div class="blog-img">
                    <img src="./images/team.jpg" alt="Team Collaboration" class="img-fluid rounded shadow-sm">
                </div>
                <h5 class="mt-3">Team Management</h5>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="section-padding bg-light-purple">
    <div class="container">
        <h2 class="mb-5 text-center" style="font-weight: 300;">Latest from the HR Blog</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">How to Reduce Payroll Errors in 2026</h5>
                        <h6 class="card-subtitle mb-2 text-muted">July 10, 2026</h6>
                        <p class="card-text text-muted">Discover how automation and proper attendance tracking can eliminate the most common manual calculation errors.</p>
                        <a href="#" class="card-link text-decoration-none" style="color: var(--primary-color);">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">The Importance of Employee Self-Service</h5>
                        <h6 class="card-subtitle mb-2 text-muted">July 15, 2026</h6>
                        <p class="card-text text-muted">Empower your workforce by giving them direct access to their payslips, attendance records, and leave applications.</p>
                        <a href="#" class="card-link text-decoration-none" style="color: var(--primary-color);">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="contact" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-white">PayrollMaster</h5>
                <p class="text-light opacity-75">A premium, highly-secure payroll and HR management system for the modern enterprise.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="text-white">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#about" class="text-light opacity-75 text-decoration-none">About Us</a></li>
                    <li><a href="#services" class="text-light opacity-75 text-decoration-none">Services</a></li>
                    <li><a href="#showcase" class="text-light opacity-75 text-decoration-none">Showcase</a></li>
                    <li><a href="#blog" class="text-light opacity-75 text-decoration-none">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="text-white">Contact</h5>
                <ul class="list-unstyled text-light opacity-75">
                    <li><i class="bi bi-geo-alt me-2"></i> 123 Enterprise Blvd, Tech City</li>
                    <li><i class="bi bi-envelope me-2"></i> support@payrollmaster.com</li>
                    <li><i class="bi bi-telephone me-2"></i> +1 800 555 0199</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-4 mb-4">
        <div class="text-center text-light opacity-75">
            <small>&copy; <?= date('Y') ?> PayrollMaster. All rights reserved.</small>
        </div>
    </div>
</footer>


<?php include './php/footer.php'; ?>
</body>
</html>
