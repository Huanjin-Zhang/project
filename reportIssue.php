<?php
session_start();
include_once 'db_connection.php';

//set validation error flag as false
$error = false;

//check if form is submitted
if (isset($_POST['report'])) {
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
        $get_email = $conn->query("select * from user where username = '". $_SESSION['valid_user']."'");
        $user = $get_email->fetch_assoc();
        $reporter = $user['uemail'];

        $trans = 
        "START TRANSACTION; 
        INSERT INTO issue(pid,ititle,idescription,reporter) VALUES( ".$_GET['pid'].",'".$ititle."','".$idescription."','".$reporter."');      
        SELECT 
            @iid:=MAX(iid)
        FROM
            issue;
        INSERT INTO status_history( iid,currentstatus,modifytime) VALUES(@iid,'OPEN', now());     
        COMMIT; ";
        if(mysqli_query($conn, $trans)) {
            $successmsg = "Successfully Registered! <a href='checkIssue.php?pid=".$_GET['pid']."'>Click here to Login</a>";
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
                <input type="hidden" name="pid" value="<?php echo htmlspecialchars($_GET['pid']);?>">
                <fieldset>
                    <legend>Report Issue</legend>

                    <div class="form-group">
                        <label for="name">Issue Title</label>
                        <input type="text" name="ititle" placeholder="issue title" class="form-control" />
                        <span class="text-danger"><?php if (isset($issue_error)) echo $issue_error; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="name">Issue Description</label>
                        <input type="text" name="idescription" placeholder="description" class="form-control"/>
                        <span class="text-danger"><?php if (isset($ides_error)) echo $ides_error; ?></span>
                    </div>

                    <div class="form-group">
                        <input type="submit" name="report" value="Report" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
</body>
</html>