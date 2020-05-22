<?php
session_start();
include_once 'db_connection.php';

//set validation error flag as false
$error = false;
$mailerror = false;

//check if form is submitted
if (isset($_POST['signup'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $uname = mysqli_real_escape_string($conn, $_POST['uname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $displayname = mysqli_real_escape_string($conn, $_POST['displayname']);
    

    // requirement for password
    if(strlen($password) < 6) {
        $error = true;
        $password_error = "Password must be minimum of 6 characters";
    }
    // password should be the same
    if($password != $cpassword) {
        $error = true;
        $cpassword_error = "Password and Confirm Password doesn't match";
    }

    //name can contain only alpha characters and space
    if (!preg_match("/^[a-zA-Z ]+$/",$displayname)) {
        $error = true;
        $displayname_error = "Name must contain only alphabets and space";
    }

    // verify email
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $mailerror = true;
        $email_error = "Please Enter Valid Email ID";
    }

    // verify the username and email
    $userquery = "SELECT * FROM user";
    $userresult = $conn -> query($userquery);
    if ($userresult -> num_rows > 0) {
        while ($row = $userresult -> fetch_assoc()) {
            if ($row['username'] == $uname) {
                $error = true;
                $uname_error = "This username has been registered";
            }
            if (!$mailerror && $row['uemail'] == $email) {
                $mailerror = true;
                $email_error = "This email has been registered";
            }
        }
    }

    if (!$error && !$mailerror) {
        if(mysqli_query($conn, "insert into user values ('". $email ."','" . $uname . "','" . $password . "','" . $displayname ."')")) {
            $url = "signin.php";
            echo "<script type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>";
        } else {
            $errormsg = "Error...Please try again later!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    

    

    <style>

        .title{
            color: #FFFFFF;
        }

        .navbar-brand{
            font-size: 1.8em;
        }


        #topRow h1 {
            font-size: 300%;

        }

        .center{
            text-align: center;
        }

        .title{
            margin-top: 100px;
            font-size: 300%;
        }

        #footer {
            background-color: #B0D1FB;
        }

        .marginBottom{
            margin-bottom: 30px;
        }

        .container{
            height: 350px;
            width: 100%;
            background-color: white;
        }

    </style>

</head>
<body>
<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- add header -->
        <div class="navbar-header">
            <a class="navbar-brand" href="homepage.php">Home Page</a></li>
        </div>
        <!-- menu items -->
        <div class="collapse navbar-collapse" id="navbar1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="signin.php">Sign In</a></li>
                <li class="active"><a href="signup.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>Sign Up</legend>

                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="text" name="email" placeholder="Email" required value="<?php if($error) echo $email; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($email_error)) echo $email_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" name="uname" placeholder="Username" required value="<?php if($error) echo $uname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($uname_error)) echo $uname_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Password</label>
                        <input type="password" name="password" placeholder="Password" required class="form-control" />
                        <span class="text-danger"><?php if (isset($password_error)) echo $password_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Type Your Password Again" required class="form-control" />
                        <span class="text-danger"><?php if (isset($cpassword_error)) echo $cpassword_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">DisplayName</label>
                        <input type="text" name="displayname" placeholder="Enter Display Name" required value="<?php if($error) echo $displayname; ?>" class="form-control" />
                        <span class="text-danger"><?php if (isset($displayname_error)) echo $displayname_error; ?></span>
                    </div>

                    

                    <div class="form-group">
                        <input type="submit" name="signup" value="Sign Up" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
        Already Registered? <a href="signin.php">Click Here to Sign In</a>
        </div>
    </div>
</div>
</body>
</html>