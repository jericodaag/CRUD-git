<?php

if(!isset($_SESSION)){
    session_start();
}
include_once("connections/connect.php");
$con = connect();

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbluser WHERE email = '$email' AND password = '$password'";
    $users = $con->query($sql) or die ($con->error);
    $row = $users->fetch_assoc();
    $total = $users->num_rows;

    if(($total > 0) && ($row['Access'] == "Admin" || $row['Access'] == "User" || $row['Access'] == "Supervisor")){
        $id = $row['userID'];
        $_SESSION['ID'] = $row['userID'];
        $_SESSION['UserLogIn'] = $row['email'];
        $_SESSION['Access'] = $row['Access'];
        $_SESSION['firstname'] = $row['Firstname'];
        $_SESSION['lastname'] = $row['Lastname'];
        $_SESSION['photo'] = $row['photo'];

        if($row['status'] == "offline.png"){
            $sql = "UPDATE tbluser SET `status`='online.png' WHERE `userID`='$id'";
            $con->query($sql) or die ($con->error);
        }

        if($row['Access'] == "User"){
            $_SESSION['houseno'] = $row['HouseNo'];
            $_SESSION['street'] = $row['Street'];
            $_SESSION['brgy'] = $row['Brgy'];
            $_SESSION['city'] = $row['City'];
            $_SESSION['prov'] = $row['Province'];
            $_SESSION['contact'] = $row['phone'];
        }

        $_SESSION['message'] = "<script>
        $(function() {
            $.notify({
                message: '". $row['Access'] ." Login Successful!'
            },{
                animate: {
                    enter: 'animate__animated animate__fadeInDown',
                    exit: 'animate__animated animate__fadeOutLeft'
                },
                delay: 2000,
                placement: {
                    from: 'bottom',
                    align: 'left'
                },
                type: 'success'
            });
        });
        </script>";

        if($row['Access'] == "Admin" || $row['Access'] == "Supervisor"){
            echo header("Refresh:1; url=accounts.php");
        }
        else if($row['Access'] == "User"){
            echo header("Refresh:1; url=home.php");
        }
    }
    else{
        $_SESSION['message'] = "<script>
        $(function() {
            $.notify({
                message: 'Invalid Email Address and Password!' 
            },{
                animate: {
                    enter: 'animate__animated animate__fadeInDown',
                    exit: 'animate__animated animate__fadeOutLeft'
                },
                delay: 2000,
                placement: {
                    from: 'bottom',
                    align: 'left'
                },
                type: 'danger'
            });
        });
        </script>";
    }
}

if(isset($_POST['register'])){
    echo header("Location: Register.php");
}
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="shortcut icon" type=image/x-icon href=images/icon.png>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/animate.min.css">

    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-notify.min.js"></script>
</head>
<body>


    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-md sticky-top navigation">
        <div class="container-fluid">
            <a href="home.php" class="navbar-brand logo-container"><img src="images/Logo.png" alt="" class="logo"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="fas fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                    <a href="cars.php" class="nav-link dropbtn">Cars</a>
                        <div class="dropdown-content">
                            <a href="hot-deals.php">Hot Deals</a>
                            <a href="new-arrival.php">New Arrival</a>
                            <a href="jdm-classics.php">Classic Cars</a>
                        </div>
                    </li>
               
                    <li class="nav-item dropdown">
                        <a href="merchandise.php" class="nav-link dropbtn">Merchandise</a>
                        <div class="dropdown-content">
                            <a href="best-sellers.php">Best Sellers</a>
                            <a href="car-accessories.php">Car Accessories</a>
                            <a href="jdm-clothing.php">Jdm Clothing</a>
                        </div>
                    </li>
                
                    <li class="nav-item dropdown">
                        <a href="about.php" class="nav-link dropbtn">About</a>
                    </li>
                </ul>
                    <li class="nav-item">
                        <a href="sign-up.php" class="nav-link">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="LogIn.php" class="nav-link">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- LOG IN -->
    <div class="login-background">
        
    <!-- ALERT -->
    
    <?php if(isset($_SESSION['message'])){?>
        <?php 
                
               echo $_SESSION['message'];
               unset($_SESSION['message']);
               
           ?>
    <?php }?>

        <br><br><br>
        <br><br><br>
        <div class="container">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-sm-12 col-lg-6 login-container">
                    <h4 class="display-Log">Log In</h4>
                    <br>
                    <form name="loginform" action="" method=post>
                        <div class="form-group col-12">
                            <label for="inputEmail">Email Address: </label>
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email Address" required>
                            <br>
                            <label for="inputPassword">Password: </label>
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            <br><br>
                            <button type="submit" name=login class="btn btn-primary btn-md" style="border-color:#bf2e2e; background-color:#bf2e2e;">Log In</button>
                        </div>
                    </form>
                </div>
                <div class="col-3"></div>
            </div>
        </div>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </div>








     <!-- FOOTER -->
 <footer>
        <div class="container-fluid footer">
            <div class="row" style="justify-content: space-around;">
                <div class="col-sm-6 col-lg-3" align="left">
                    <h4 class="display-4 name">JDMania Auto Deals</h4>
                    <p class="lead">
                    We are JDMania Auto Deals, the ultimate destination for JDM enthusiasts. We offer a curated 
                    selection of top-tier JDM vehicles that ignite the senses, from iconic classics to cutting-edge 
                    performance machines. Experience the heart and soul of JDM culture with us, where horsepower meets 
                    passion in perfect harmony.
                    </p>
                </div>

                <div class="col-sm-6 col-lg-3" align="center">
                    <p class="lead">Follow Us On:</p>
                    <div class="col-12 social">
                        <a href="#"><span class="fab fa-facebook"></span> Facebook</a><br>
                        <a href="#"><span class="fab fa-instagram"></span> Instagram</a><br>
                        <a href="#"><span class="fab fa-twitter"></span> Twitter</a><br>
                        <a href="#"><span class="fab fa-youtube"></span> YouTube</a><br>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3" align="center">
                    <p class="lead">Email Us:</p>
                    <div class="textbox">
                        <input type="text" placeholder="Write Your Thoughts">
                    </div>
                    <div class="button">
                    <a href="mailto:" class="btn btn-primary" style="border-color:#bf2e2e; background-color:#bf2e2e;">Send</a>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <div class="row text-center">
                <div class="col-12">
                   <p>Copyright © 2023 | All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>