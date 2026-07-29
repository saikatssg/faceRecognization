<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark  fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Edge of Eye</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="./php/read.php">View User</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Showcase</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/empFace/php/signup.php">Contact Us</a>
                    </li>
                    <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/empFace/php/logout.php">Logout</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/empFace/php/login.php">Login</a>
                    </li>
                    <?php endif; ?>



                </ul>

            </div>
        </div>
    </nav>