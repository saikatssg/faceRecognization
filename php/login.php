<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <?php include './header.php'; ?>
    <link rel="stylesheet" href="../css/home.css">

    <link rel="stylesheet" href="../css/signup.css">
    
    <!-- face-api.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
</head>
<body>
    <!-- nav menu -->
     <?php include './nav.php'; ?>
 <header class="container-fluid d-flex align-items-center justify-content-start">
        <div class="header_title">
           <h1 class="my-4 p-2  fw-normal text-light ">Employee Online Registraion Portal</h1>
        </div>
 </header>

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
            <form action="./loginCheck.php"  method="post" >
                 
                <div class="mb-3">
                    <label for="user" class="form-label">User Name</label>
                    <input type="text" id="user" placeholder="User Name" class="form-control" name="user">
                </div>

                <div class="mb-3">
                    <label for="pwd" class="form-label">Password</label>
                    <div class="passview">
                        <input type="password" id="pwd" placeholder="Enter Password" class="form-control" name="pwd" maxlength="20">
                        <i class="bi bi-eye-fill eyeprev" id="eyeprev" ></i>
                    </div>
                </div>

               
                <div class="mb-3 d-flex flex-column align-items-center">
                    <label class="form-label">Face Verification (Required for Login)</label>
                    <video id="webcam" width="320" height="240" autoplay class="border rounded"></video>
                    <canvas id="canvas" width="320" height="240" style="display:none;" class="border rounded"></canvas>
                    <button type="button" class="btn btn-secondary mt-2" id="captureBtn">Capture Photo</button>
                    <p id="captureStatus" class="text-success mt-2" style="display:none;">Photo captured successfully!</p>
                    <div id="face-verification-status" style="display:none; width: 320px; text-align: center;"></div>
                </div>
             
                <div class="mb-3">
                    
                    <button class="px-4 py-2 btn btn-success" id="btnSUbmit" name="btnSUbmit" type="submit">Login</button>
                    <button class="px-4 py-2  ms-2 btn btn-danger" type="reset">Reset</button>
                </div>

            </form>

            <div class="mb-3">
                <p class="form-text">No Account ID click here - <a href="./signup.php">Register</a></p>
            </div>
        </div>
    </div>
 </div>
   <?php include './footer.php'; ?>
    

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
        });

        // Webcam capture logic
        const video = document.getElementById('webcam');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const captureStatus = document.getElementById('captureStatus');
        let photoCaptured = false;
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
                video.srcObject = stream;
                video.play();
            }).catch(function(err) {
                console.error("Error accessing webcam: ", err);
                alert("Please allow webcam access for face verification.");
            });
        }

        captureBtn.addEventListener('click', function() {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, 320, 240);
            video.style.display = 'none';
            canvas.style.display = 'block';
            captureStatus.style.display = 'block';
            photoCaptured = true;
            captureBtn.innerText = 'Retake Photo';
            captureBtn.addEventListener('click', function retake() {
                video.style.display = 'block';
                canvas.style.display = 'none';
                captureStatus.style.display = 'none';
                photoCaptured = false;
                captureBtn.innerText = 'Capture Photo';
                captureBtn.removeEventListener('click', retake);
            }, { once: true });
        });

        let modelsLoaded = false;
        
        async function loadModels() {
            if (modelsLoaded) return;
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            modelsLoaded = true;
        }

        document.querySelector('form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!photoCaptured) {
                alert("Please capture your photo before logging in.");
                return;
            }
            
            const user = document.getElementById('user').value;
            const pwd = document.getElementById('pwd').value;
            const statusDiv = document.getElementById('face-verification-status');
            
            if(!user || !pwd) {
                alert("Please enter username and password");
                return;
            }
            
            statusDiv.style.display = 'block';
            statusDiv.className = 'alert alert-info mt-2';
            statusDiv.innerText = 'Verifying credentials...';
            
            try {
                // 1. Fetch user photo
                const formData = new FormData();
                formData.append('user', user);
                formData.append('pwd', pwd);
                
                const response = await fetch('./fetchUserPhoto.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (!data.success) {
                    statusDiv.className = 'alert alert-danger mt-2';
                    statusDiv.innerText = data.message || 'Invalid Credentials';
                    return;
                }
                
                statusDiv.className = 'alert alert-warning mt-2';
                statusDiv.innerText = 'Loading AI Models (this may take a moment)...';
                await loadModels();
                
                statusDiv.className = 'alert alert-primary mt-2';
                statusDiv.innerText = 'Analyzing captured face...';
                
                // Load the profile image
                const profileImg = await faceapi.fetchImage(data.photo);
                
                // Get descriptors
                const profileDetection = await faceapi.detectSingleFace(profileImg).withFaceLandmarks().withFaceDescriptor();
                
                if (!profileDetection) {
                    statusDiv.className = 'alert alert-danger mt-2';
                    statusDiv.innerText = 'Could not detect a face in your profile photo.';
                    return;
                }
                
                // Get webcam descriptor from the static canvas image
                const webcamDetection = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();
                
                if (!webcamDetection) {
                    statusDiv.className = 'alert alert-danger mt-2';
                    statusDiv.innerText = 'Could not detect a face in your captured photo. Please try retaking it.';
                    return;
                }
                
                // Compare
                const distance = faceapi.euclideanDistance(profileDetection.descriptor, webcamDetection.descriptor);
                
                if (distance < 0.5) {
                    statusDiv.className = 'alert alert-success mt-2';
                    statusDiv.innerText = 'Face Verified! Logging in...';
                    
                    // Actually submit the form to loginCheck.php
                    HTMLFormElement.prototype.submit.call(document.querySelector('form'));
                } else {
                    statusDiv.className = 'alert alert-danger mt-2';
                    statusDiv.innerText = 'Face verification failed. You do not match the profile photo.';
                }
                
            } catch (err) {
                console.error(err);
                statusDiv.className = 'alert alert-danger mt-2';
                statusDiv.innerText = 'An error occurred during verification.';
            }
        });


    </script>
   
</body>
</html>