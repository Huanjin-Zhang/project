<?php
session_start();
include_once 'db_connection.php';
if (isset($_GET['pid']))    
    $_SESSION['pid'] = $_GET['pid'];
$error = false;

//check if form is submitted
if (isset($_POST['add'])) {
    setcookie('mycookie', $_POST['add']);
    $beginstatus = mysqli_real_escape_string($conn, $_POST['beginstatus']);
    $endstatus = mysqli_real_escape_string($conn, $_POST['endstatus']);
    // verify the username and email
    $userquery = "SELECT * FROM workflow where pid ='".$_SESSION['pid']."'";
    $userresult = $conn -> query($userquery);
    if ($userresult -> num_rows > 0) {
        while ($row = $userresult -> fetch_assoc()) {
            if ($row['beginstatus'] == $beginstatus && $row['endstatus'] == $endstatus) {
                $error = True;                 
                $status_error = "This workflow already exist";
            }
        }
    }
    
    if (!$error) {
        if(mysqli_query($conn, "insert into workflow values ('". $_SESSION['pid'] ."','" .$beginstatus."','". $endstatus ."')")) {
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
            <p class="navbar-form navbar-right" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>">Welcome! <?php echo $_SESSION['valid_user']; ?><a style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"href="logout.php">
            Log Out</a></p>
        </div>
    </div>
</nav>

<?php
if(isset($_POST['beginstatus'])&& isset($_POST['endstatus'])){
    if(mysqli_query($conn, "insert into workflow values ('". $_SESSION['pid'] ."','" . $_POST['beginstatus'] . "', '" . $_POST['endstatus'] . "')")) {
            $successmsg = "Successfully Inserted!";
        } else {
            $errormsg = "Error...Please try again later!";
        }
    }
?>

<?php
if(isset($_SESSION['pid'])){
    echo "<div class='row'>
                <div class='center-block' style='width:80%;'>
                <div class='page-header'>
                <h2 align ='center'>Project Workflow</h2><br/>
                </div>";
    $get_issue = "select * from workflow where pid = '".$_SESSION['pid']."'";
    $issues = $conn->query($get_issue);

    if ($issues -> num_rows > 0) {
        echo "<table class= 'table table-striped table-hover'><tr><th>Begin Status</th><th>End Status</th></tr>";
        while ($row = $issues -> fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['beginstatus'] . "</td>";
            echo "<td>" . $row['endstatus'] . "</td>";
            echo "</tr>";                
        }
        echo "</table><br/>";       
    } else {
        echo "<h3 align ='center'>There are No workflow under such project</h3><br/><br/><br/>";
    }
    echo "</div></div>";
}

?>

<div class = "row">
    <div class="center-block" style="width:80%;">
        <div class="page-header'">
            <a href='lead.php'>
            <button type='button' class ='btn btn-success' style='<?php if(!isset($_SESSION['valid_user'])) echo "display:none"; ?>'>Done</button></a>
        </div>
    </div>
</div>       
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 well">
            <h2 align ='center'>Add workflow from 
                <form action="" method="post">
                    <select name="beginstatus">
                        <option value=0>begin status</option>
                        <?php
                            $sql = "SELECT status FROM project_status WHERE pid = '" . $_GET['pid'] . "'";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc())
                            {
                                echo "<option value = '".$row['status']."'>'".$row['status']."'</option>";
                            } 
                        ?>
                    </select>
                    to
                    <select name="endstatus">
                        <option value=0>end status</option>
                        <?php
                            $sql = "SELECT status FROM project_status WHERE pid = '" . $_GET['pid'] . "'";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc())
                            {
                                echo "<option value = '".$row['status']."'>'".$row['status']."'</option>";
                            } 
                        ?>
                    </select>
                    <input type="submit" value="Submit">
                </form>
            </h2>

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