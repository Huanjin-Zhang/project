<?php
session_start();
include_once 'db_connection.php';
if (isset($_GET['pid']))    
    $_SESSION['pid'] = $_GET['pid'];
//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['report'])) {
    setcookie('mycookie', $_POST['report']);
    $ititle = mysqli_real_escape_string($conn, $_POST['ititle']);
    $idescription = mysqli_real_escape_string($conn, $_POST['idescription']);
    

    // requirement for password
    if(strlen($ititle) >10) {
        $error = true;
        $ititle_error = "Title must be maximum of 10 characters";
    }

    // password should be the same
    if(strlen($idescription) >30) {
        $error = true;
        $ides_error = "Description must be maximum of 30 characters";
    }

    if (!$error) {
        $uemailquery = "SELECT * FROM user WHERE username = '" . $_SESSION['valid_user'] . "'";
        $uemailresult = $conn -> query($uemailquery);
        $useremailarray = $uemailresult -> fetch_assoc();
        $reporter = $useremailarray['uemail'];

        
        try {
            // First of all, let's begin a transaction
            $conn->begin_transaction();

            // A set of queries; if one fails, an exception should be thrown
            $conn->query("INSERT INTO issue(pid,ititle,idescription,reporter) VALUES( '".$_SESSION['pid']."','".$ititle."','".$idescription."','".$reporter."')");
            $conn->query("INSERT INTO status_history( iid,currentstatus,modifytime) VALUES((SELECT max(iid) from issue where pid = '".$_SESSION['pid']."'),'OPEN', now())");

            // If we arrive here, it means that no exception was thrown
            // i.e. no query has failed, and we can commit the transaction
            $successmsg = "Successfully Inserted!";
            $conn->commit();
            //jump to checkissue
            $url = "checkissue.php?pid=".$_SESSION["pid"];
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
                <li class="active"><a href="homepage.php">Home</a></li>
                <li style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"><a href="lead.php">Lead</a></li>
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
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="reportissueform">
                <fieldset>
                    <legend>Report Issue</legend>

                    <div class="form-group">
                        <label for="name">Issue Title</label>
                        <input type="text" name="ititle" placeholder="issue title" class="form-control" />
                        <span class="text-danger"><?php if (isset($ititle_error)) echo $ititle_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Issue Description</label>
                        <input type="text" name="idescription" placeholder="description" class="form-control"/>
                        <span class="text-danger"><?php if (isset($ides_error)) echo $ides_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="report" value="Report" class="btn btn-primary" />
                    </div>
                    <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
                    <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
                </fieldset>
            </form>

        </div>
    </div>
</div>
</body>
</html>