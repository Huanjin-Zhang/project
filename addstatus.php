<?php
session_start();
include_once 'db_connection.php';
if (isset($_GET['pid']))    
    $_SESSION['pid'] = $_GET['pid'];
$error = false;

//check if form is submitted
if (isset($_POST['add'])) {
    setcookie('mycookie', $_POST['add']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    // verify the username and email
    $userquery = "SELECT * FROM project_status where pid ='".$_SESSION['pid']."'";
    $userresult = $conn -> query($userquery);
    if ($userresult -> num_rows > 0) {
        while ($row = $userresult -> fetch_assoc()) {
            if ($row['status'] == $status) {
                $error = True;                 
                $status_error = "This status already exist";
            }
        }
    }
    
    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO project_status VALUES (?,?)");
        $stmt->bind_param('ss', $_SESSION['pid'],$status);
        if($stmt->execute()) {
            $successmsg = "Successfully Inserted!";
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
    <title>Issue Tracking</title>
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

<?php
if($_SESSION['pid']){
    echo "<div class='row'>
                <div class='center-block' style='width:80%;'>
                <div class='page-header'>
                <h2 align ='center'>Project Status</h2><br/>
                </div>";
    $get_issue = "select * from project_status where pid = '".$_SESSION['pid']."'";
    $issues = $conn->query($get_issue);

    if ($issues -> num_rows > 0) {
        echo "<table class= 'table table-striped table-hover'><tr><th>Project Status</th></tr>";
        while ($row = $issues -> fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['status'] . "</td>";
            echo "</tr>";                
        }
        echo "</table><br/>";       
    } else {
        echo "<h3 align ='center'>There are No issue under such project</h3><br/><br/><br/>";
    }
    echo "</div></div>";
}

?>
<div>
<div class = "row" style = "display: inline">
    <div class="center-block" style="width:80%;">
        <div class="page-header'">
            <a href='addworkflow.php?pid=<?php echo $_SESSION["pid"]; ?>'>
            <button type='button' class ='btn btn-success' style='<?php if(!isset($_SESSION['valid_user'])) echo "display:none"; ?>'>Next</button></a>
        </div>
    </div>
</div>       

</div>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="addleaderform">
                <fieldset>
                    <div class="form-group">
                        <label for="name">Status</label>
                        <input type="text" name="status" placeholder="Status" required class="form-control" />
                        <span class="text-danger"><?php if (isset($status_error)) echo $status_error; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="add" value="Add" class="btn btn-primary" />
                    </div>
                </fieldset>
            </form>
            <span class="text-success"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
            <span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
        </div>
        <!-- <form method="post" action="addworkflow.php?pid=<php? echo $_SESSION['pid'] ?>" name="jumptoworkflow" class="col-md-4 col-md-offset-4 well">
            <input type="submit" name="next" value="Next" class="btn btn-primary" />
        </form> -->
    </div>
    
</div>
        

<!-- Footer -->
<footer>
    <div>
        <div class="row center">
            <div class="col-lg-10 col-lg-offset-1 text-center">
                <h4><strong>Powered by</strong>
                </h4>
                <p>Huanjin Zhang
                    <br>Yuning Song</p>
            <hr class="small">
                <p class="text-muted">Copyright &copy; Issue Tracking</a></p>
            </div>
        </div>
    </div>
</footer>
			
</body>
</html>