<?php
session_start();
include_once 'db_connection.php';
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


<?php

if(isset($_GET["iid"])){
    echo "<div class='row'>
                <div class='center-block' style='width:80%;'>
                <div class='page-header'>
                <h2 align ='center'>Status History of the Issue</h2><br/>
                </div>";
    $get_history = "select * from status_history where iid = '"  . $_GET['iid'] . "' order by modifytime DESC";
    $history = $conn->query($get_history);

    if ($history -> num_rows > 0) {
        echo "<table class= 'table table-striped table-hover'><tr><th>Issue ID</th><th>Stutus</th><th>Modify Time</th></tr>";
        while ($row = $history -> fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['iid'] . "</td>";
            echo "<td>" . $row['currentstatus'] . "</td>";
            echo "<td>" . $row['modifytime'] . "</td>";
            echo "</tr>";                
        }
        echo "</table><br/>";       
    } else {
        echo "<h3 align ='center'>There are No issue under such project</h3><br/><br/><br/>";
    }
    echo "</div></div>";
}
?>

<!-- <div class = "row">
	<div class="center-block" style="width:80%;">
        <div class="page-header'">
        	<button type='button' class ='btn btn-success' style='<?php if(!isset($_SESSION['valid_user'])) echo "display:none"; ?>'      onclick='window.location.href="reportIssue.php?pid=".$_GET["pid"]."\""'>Report Issues</button>
        </div>
   	</div>
</div> -->
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