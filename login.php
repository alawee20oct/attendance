<?php
session_start();
if (isset($_SESSION['daily_plan_en']) && isset($_SESSION['daily_plan'])) {
    echo "<script>window.location.href = 'index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="libs/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="icon" type="image/png" href="images/icon.png"/>
    
    <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="api/https.js"></script>

    <title>Welcome</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <img src="images/icon.png" class="mx-auto d-block mb-3" alt="web-icon" width="80" height="80">
        <h2 class="fw-semibold text-center">WELCOME</h2>
    </div>
    <div class="container text-center justify-content-center">
        <div class="form-signin m-auto pt-5 pb-5" style="width: 480px;">
            <div class="form-floating mb-3">
                <input type="text" class="form-control shadow-sm" id="en" placeholder="EN">
                <label for="en"><i class="bi bi-person-badge-fill me-2"></i>EN</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control shadow-sm" id="password" placeholder="Password">
                <label for="password"><i class="bi bi-person-fill-lock me-2"></i>Password</label>
            </div>
            <button class="btn btn-lg w-100 mt-4 shadow text-white fw-semibold" style="background-color: #9ACD32;" onclick="login()">LOGIN</button>
        </div>
        <div class="container mt-4">
            <div class="alert alert-danger text-center shadow visually-hidden" role="alert" id="alert"></div>
        </div>
    </div>
</body>
<script>
    function login() {
        var en = document.getElementById("en").value;
        var password = document.getElementById("password").value;

        document.getElementById("en").classList.remove("border-danger");
        document.getElementById("password").classList.remove("border-danger");
        
        if (en == "") {
            document.getElementById("alert").classList.remove("visually-hidden");
            document.getElementById("alert").classList.add("visually-visible");
            document.getElementById("alert").innerText = "Please Enter Your EN.";
            document.getElementById("en").classList.add("border-danger");
            return;
        }
        else if (password == "") {
            document.getElementById("alert").classList.remove("visually-hidden");
            document.getElementById("alert").classList.add("visually-visible");
            document.getElementById("alert").innerText = "Please Enter Your Password.";
            document.getElementById("password").classList.add("border-danger");
            return;
        }
        else {
            var login_api = requestHTTPS('api/backend.php', {
                'api': 'login',
                'en': en,
                'password': password
            }, true);
            if (login_api.result == true) {
                window.location.href = "index.php";
            }
            else if (login_api.result == false) {
                if (login_api.message == "wrong-password") {
                    document.getElementById("alert").classList.remove("visually-hidden");
                    document.getElementById("alert").classList.add("visually-visible");
                    document.getElementById("alert").innerText = "Password is Incorrect.";
                    document.getElementById("password").classList.add("border-danger");
                    return;

                }
                else if (login_api.message == "user-does-not-exist") {
                    document.getElementById("alert").classList.remove("visually-hidden");
                    document.getElementById("alert").classList.add("visually-visible");
                    document.getElementById("alert").innerText = "This User Doesn't Exist.";
                    document.getElementById("en").classList.add("border-danger");
                    document.getElementById("password").classList.add("border-danger");
                    return;
                }
            }
        }
    }

    document.getElementById('en').addEventListener('keypress', function(e) {
        if (e.keyCode == 13) {
            login();	
        }
    });

    document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.keyCode == 13) {
            login();	
        }
    });
</script>
</html>