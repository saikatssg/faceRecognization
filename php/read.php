<?php 
include "./connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php   include "./header.php"; ?>

    <link rel="stylesheet" href="../css/read.css">
</head>
<body>
 <?php include './nav.php'; ?>
<header>
    <div class="container-fluid">
    <div id="slideShow" class="carousel slide" data-bs-ride="true">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="slider1" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="slider2" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="slider3" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="slider4" data-bs-slide-to="3" aria-label="Slide 4"></button>   
            <button type="button" data-bs-target="slider5" data-bs-slide-to="4" aria-label="Slide 5"></button>   
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../images/slider/slide1.jpg" alt="">
            </div>

            <div class="carousel-item">
                <img src="../images/slider/slide2.jpg" alt="">
            </div>


            <div class="carousel-item">
                <img src="../images/slider/slide3.jpg" alt="">
            </div>


            <div class="carousel-item">
                <img src="../images/slider/slide4.jpg" alt="">
            </div>

             <div class="carousel-item">
                <img src="../images/slider/slide5.jpg" alt="">
            </div>
        </div>

         <button class="carousel-control-prev" type="button" data-bs-target="#slideShow" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
  
         <button class="carousel-control-next" type="button" data-bs-target="#slideShow" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
  </button>
    </div>
</div>
</header>

<section class="view d-flex justify-content-center align-items-center ">
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="display-3 text-center my-5 text-primary fs-normal">User View Portal</h2>
        </div>
        <div class="col-12">

                <table class="table table-striped table-responsive">

                <thead class="table-dark">
                    <tr>
                        <th scope="col">Sl. No.</th>
                        <th scope="col">EMP ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">last Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Gender</th>
                        <th scope="col">City</th>
                        <th scope="col">Profile Pic</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        $sql = "SELECT DISTINCT * FROM `employee`,`gender_master` WHERE employee.gender = gender_master.gid ";
                        // echo $sql;

                        $query = mysqli_query($conn,$sql);

                        if(mysqli_num_rows($query)>0)
                        {
                            $count = 1;

                            while($row = mysqli_fetch_assoc($query))
                            {
                                echo "<tr>";
                                echo "<th scope='row'>".$count++."</th>";
                                echo "<td>".$row['empid']."</td>";
                                echo "<td>".$row['fname']."</td>";
                                echo "<td>".$row['lname']."</td>";
                                echo "<td>".$row['username']."</td>";
                                echo "<td>".$row['email']."</td>";
                                echo "<td>".$row['phone']."</td>";
                                echo "<td>".$row['gname']."</td>";
                                echo "<td>".$row['city']."</td>";
                                echo "<td><img src='".$row['photo']."' class='img-thumbnail'></td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
                </table>
        </div>
    </div>
</div>
</section>

    


    <?php   include "./footer.php"; ?>

</body>
</html>