<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/signup.css">

    <?php
    include './header.php';
    ?>

    <style>
        
         .hero-section{
            height:70dvh !important;
             background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../images/header.jpg') center/cover !important; 
             background-attachment: fixed;   
             background-position: top;
             
        }
        .navbar-public {
    background: linear-gradient(90deg,#88256b, #207a6a) !important;
    background-attachment: fixed;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    
}
.navbar-public .navbar-brand {
    color: #fff !important;
    font-weight: 600;
}

.navbar-public .nav-link {
    color: rgba(255, 255, 255, 0.8) !important;
    font-size: 0.9rem;
}
    </style>
</head>
<body>
    <!-- nav menu -->
      <?php include 'nav.php' ?>
<div class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Employee Registraion Portal</h1>
            <br>
            <h3 class="mb-5 text-light opacity-75 fs-4 fw-normal">Secure, fast, and reliable employee management.</h3>
          
        </div>
    </div>
</div>

 <div class="form-background">
    
    <div class="container my-5 text-center" style="width: 50%;">
        <div>
            <h3 class="fw-normal display-6 mb-4 text-success">Let's get you started</h3>
            <p class="mt-4 form-text fs-6">Enter the details to get going</p>
        </div>
    </div>

    <div class="container " style="width: 50%;">
        <div class="row">
             <div class="col">
                <div class="alert alert-danger d-flex align-items-center" role="alert" id="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2" ></i>
                <span id="error"> 
                    <?php 

                        if(isset($_GET['err']))
                        {
                            echo $_GET['err'];
                        }

                    ?>
                </span>
             </div>
        </div>
        </div>
        <div class="row">
            <form action="./addUser.php"  method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name:</label>
                    <input type="text" id="fname" placeholder="First Name" class="form-control" name="fname" >
                </div>

                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" id="lname" placeholder="Last Name" class="form-control" name="lname">
                </div>

                <div class="mb-3">
                    <label for="user" class="form-label">User Name</label>
                    <input type="text" id="user" placeholder="User Name" class="form-control" name="user">
                </div>

                <div class="mb-3">
                    <label for="dept" class="form-label">Department Name</label>
                    <select id="dept" class="form-select" name="dept">
                        <option value="0" selected disabled>Select Your Department Name</option>
                        <option value="1">Information Technology</option>
                        <option value="2">Human Resource</option>
                        <option value="3">Research and Development</option>
                        <option value="4">Finance and Accounting</option>
                        <option value="5">Sales and Marketing</option>
                        <option value="6">Customer Service</option>
                        <option value="7">Quality Assurance</option>
                        <option value="8">Operations and Logistics</option>
                        <option value="9">Legal and Compliance</option>
                        <option value="10">Product Management </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label">Password</label>
                    <div class="passview">
                        <input type="password" id="pwd" placeholder="Enter Password" class="form-control" name="pwd" maxlength="20">
                        <i class="bi bi-eye-fill eyeprev" id="eyeprev" ></i>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="dob" class="form-label">DOB</label>
                    <input type="date" id="dob"  class="form-control" name="dob">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email ID:</label>
                    <input type="email" id="email" placeholder="Email Id" class="form-control" name="email" aria-describedby="emailHelp" >
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Mobile Number</label>
                    <input type="text" id="phone"  class="form-control" name="phone" placeholder="Phone Number">
                </div>

                <div class="mb-3"  >
                    <label  class="form-label">Select Your Gender</label>
                    <div class="container d-flex  " style="width: 40%; justify-content:space-between;  margin-left: 0;">
                        <label for="male"> Male </label>
                        <input type="radio" id="male" name="gender" value="1" class="form-radio" >

                        <label for="female"> Female </label>
                        <input type="radio" id="female" name="gender" value="2"  class="form-radio" >

                        <label for="others"> Others </label>
                        <input type="radio" id="others" name="gender" value="3"  class="form-radio" >
                    </div>
                </div>

                 <div class="mb-3">
                    <label for="jdate" class="form-label">Joining Date</label>
                    <input type="date" id="jdate"  class="form-control" name="jdate">
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City Name :</label>
                    <select id="city" class="form-select" name="city">
                        <option value="0" selected disabled>Select The City Name</option>
                        <option value="kolkata">Kolkata</option>
                        <option value="delhi">New Delhi</option>
                        <option value="pune">Pune</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="bangaluru">Bangaluru</option>
                    </select>

        
                </div>

                <div class="mb-3">
                    <label for="upload" class="form-label">Upload Your Photo <span id="errFile"></span></label>
                    <input type="file" id="upload"  class="form-control btn btn-success" name="upload">
                    <div class="imgPrev">
                        <img src="../images/noprev.jpg" name="prev" id="prev">
                    </div>
                </div>

                <div class="mb-3">
                   
                    <input type="checkbox" id="checkPointer"  class="form-checkbox">
                     <label for="checkPointer" class="form-label ms-2">I Agree</label>
                </div>

                <div class="mb-3">
                    
                    <button class="px-4 py-2 btn btn-success" id="btnSUbmit" name="btnSUbmit" type="submit">Signup</button>
                    <button class="px-4 py-2  ms-2 btn btn-danger" type="reset">Reset</button>
                </div>

            </form>

            <div class="mb-3">
                <p class="form-text">Already having Account ID click here - <a href="./login.php">Login</a></p>
            </div>
        </div>
    </div>
 </div>
   <?php include './footer.php'; ?>
    <script src="../js/upload.js"></script>
    <script src="../js/validate.js"></script>

    <script>

        

        let btn1 = document.getElementById("eyeprev");

        let pass = document.getElementById("pwd")

        // console.log(btn1.classList);
        

        btn1.addEventListener("click",function(){
            if(pass.type == "password")
            {
                pass.type="text";
                btn1.classList.remove('bi-eye-fill');
                btn1.classList.add('bi-eye-slash-fill')
            }
            else{
                pass.type="password";
                btn1.classList.remove('bi-eye-slash-fill');
                btn1.classList.add('bi-eye-fill')
            }
        })
    </script>
   
</body>
</html>