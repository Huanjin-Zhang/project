<?php
session_start();
include_once 'db_connection.php';

//set validation error flag as false
$error = false;


//check if form is submitted
if (isset($_POST['next'])) {
    //setcookie('mycookie', $_POST['next']);
    $ptitle = mysqli_real_escape_string($conn, $_POST['ptitle']);
    $pdescription = mysqli_real_escape_string($conn, $_POST['pdescription']);
    

    // requirement for password
    if(strlen($ptitle) >10) {
        $error = true;
        $ptitle_error = "Title must be maximum of 10 characters";
    }

    // password should be the same
    if(strlen($pdescription) >30) {
        $error = true;
        $pdes_error = "Description must be maximum of 30 characters";
    }

    if (!$error) {
        $uemailquery = "SELECT * FROM user WHERE username = '" . $_SESSION['valid_user'] . "'";
        $uemailresult = $conn -> query($uemailquery);
        $useremailarray = $uemailresult -> fetch_assoc();
        $creator = $useremailarray['uemail'];

        
        try {
            // First of all, let's begin a transaction
            $conn->begin_transaction();

            // A set of queries; if one fails, an exception should be thrown
            $conn->query("INSERT INTO project(creator,ptitle,pdescription,pcreatetime) VALUES( '".$creator."','".$ptitle."','".$pdescription."',now())");
            $conn->query("INSERT INTO project_leads( pid,uemail,addtime) VALUES((SELECT max(pid) from project where creator = '".$creator."'),'".$creator."', now())");
            $conn->query("INSERT INTO project_status(pid,status) VALUES ((SELECT max(pid) from project where creator = '".$creator."'),'OPEN'),     ((SELECT max(pid) from project where creator = '".$creator."'),'CLOSED')");
           
            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            $successmsg = "Successfully Inserted!";
            $conn->commit();
         
            $pidquery = "SELECT * from project where creator = '".$creator."' order by pid DESC limit 1";
            $pidresult = $conn->query($pidquery);
            $pidarray = $pidresult -> fetch_assoc();
            $pid = $pidarray['pid'];

            $url = "addstatus.php?pid=".$pid;
            echo "<script type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>";

        } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $errormsg = "Error...Please try again later!";
            $conn->rollback();
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
<nav class ="navbar navbar-default" role="navigation">
    <div class = "container-fluid">
        <div class ="navbar-header">
            <a class="navbar-brand">Issue Tracking</a>
        </div>
        <div class="navbar-collapse">
            <ul class ="nav navbar-nav navbar-left">
                <li ><a href="homepage.php">Home</a></li>
                <li class="active" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"><a href="lead.php">Lead</a></li>
                <li style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"><a href="issue.php">Issues</a></li>
            </ul>
            
            <ul class="nav navbar-nav navbar-right" style="<?php if (isset($_SESSION['valid_user'])) echo "display:none"; ?>"> 
                <li><a href="signin.php">Sign In</a></li> 
                <li><a href="signup.php">Sign Up</a></li> 
            </ul> 
            <p class="navbar-form navbar-right" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>">Welcome! <a target="_blank" href="userinfo.php"><?php echo $_SESSION['valid_user']; ?></a><a style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"href="logout.php">
            Log Out</a></p>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
                <fieldset>
                    <legend>Create Project</legend>

                    <div class="form-group">
                        <label for="name">Project Title</label>
                        <input type="text" name="ptitle" placeholder="project title" class="form-control" />
                        <span class="text-danger"><?php if (isset($ptitle_error)) echo $ptitle_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Project Description</label>
                        <input type="text" name="pdescription" placeholder="description" class="form-control"/>
                        <span class="text-danger"><?php if (isset($pdes_error)) echo $pdes_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="next" value="Next" class="btn btn-primary" />
                    </div>
                    <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
                    <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
    </div>

</div>
</body>
</html>