<!-- includes/navbar_public.php -->
<nav class="navbar navbar-expand-lg navbar-public sticky-top ">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="../index.php">PayrollMaster</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar" aria-controls="publicNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./index.php#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./index.php#showcase">Showcase</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./index.php#blog">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./index.php#contact">Contact Us</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <?php if(isset($_SESSION['empid'])): ?>
                        <a class="nav-link fw-bold text-white" href="./php/logout.php">Logout</a>
                    <?php else: ?>
                        <a class="nav-link fw-bold text-white" href="./php/login.php">Login</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
