<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="./css/home.css">

</head>

<body>

    <!-- nav -->
     <?php include './php/nav.php'; ?>
    
    <!-- end of nav -->

    <!-- header -->

    <header class="container-fluid d-flex align-items-center justify-content-start" id="home">
        <div class="header_title">
            <?php if(isset($_SESSION['fname'])): ?>
                <div class="d-flex align-items-center mb-3">
                    <h3 class="text-warning mb-0 me-3">Welcome back, <?php echo htmlspecialchars($_SESSION['fname']); ?>!</h3>
                    <?php if(isset($_SESSION['photo']) && !empty($_SESSION['photo'])): ?>
                        <img src="<?php echo substr($_SESSION['photo'],1); ?>" alt="Profile Photo" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #ffc107;">
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <h1>BlackSilver Photography</h1>
            <p>Whether you're about to create a website for the first time, or you're looking for a theme that provides
                advanced capabilities, we've got them in Blacksilver theme.</p>
            <p>Professional photography website using Blacksilver.</p>
            <a href="#">View More</a>
        </div>
    </header>
    <!-- end of header -->


    <!-- about us -->

    <div class="about container-fluid  d-flex justify-content-center align-items-center flex-column" id="about">
        <div class="about-title">
            <h1>Designed for Photographers</h1>
            <p>Whether you're building a photography website, publishing a photography blog, or creating a professional
                website with features such as events and proofing galleries, Blacksilver theme can provide you those
                features without the need to use any coding.</p>
        </div>
        <div class="about-item d-flex align-items-center justify-content-space-between">
            <div class="left  d-flex align-items-center justify-content-center flex-column">
                <div class="imgPrev d-flex"> <img src="./images/product1.png" alt=""></div> <br><a href="#">View
                    More</a>
            </div>
            <div class="right d-flex align-items-center justify-content-center flex-column">
                <div class="imgPrev d-flex"><img src="./images/product2.png" alt=""></div> <br><a href="#">View
                    More</a>
            </div>
        </div>

    </div>
    <!-- end of about us -->

    <!-- blog -->
    <!-- end of blog -->

    <!-- showcase -->
    <!-- end of showcase -->


    <!-- footer -->
    <!-- end of footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>