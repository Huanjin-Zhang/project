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
                <li><a href="homepage.php">Home</a></li>
                <li><a href="lead.php">Lead</a></li>
                <li class="active"><a href="issue.php">Issues</a></li>
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
echo "<div class='row'>
            <div class='center-block' style='width:80%;'>
            <div class='page-header'>
            <h2 align ='center'>Issues assigned to you</h2><br/>
            </div>";
$get_issue = "with current_status as(select iid,max(modifytime) as newest from status_history group by iid) select iid,assigndate,ititle,idescription,currentstatus,modifytime,u2.username as reporter from user u1 natural join assignee natural join issue natural join status_history natural join current_status,user u2 where u1.username = " . "'" . $_SESSION['valid_user'] . "'and modifytime = newest and reporter=u2.uemail group by iid order by modifytime DESC";
$issues = $conn->query($get_issue);

if ($issues -> num_rows > 0) {
    echo "<table class= 'table table-striped table-hover'><tr><th>Assign Date</th><th>Issue Title</th><th>Issue Description</th><th>Current Status</th><th>Modifytime</th><th>Reporter</th><th></th></tr>";
    while ($row = $issues -> fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['assigndate'] . "</td>";
        echo "<td>" . $row['ititle'] . "</td>";
        echo "<td>" . $row['idescription'] . "</td>";
        echo "<td>" . $row['currentstatus'] . "</td>";
        echo "<td>" . $row['modifytime'] . "</td>";
        echo "<td>" . $row['reporter'] . "</td>";
        echo "<td><a href='checkdetailbyassignee.php?iid=".$row['iid']."'>check detail</a></td>"; 
        echo "</tr>";                
    }
    echo "</table><br/>";       
} else {
    echo "<h3 align ='center'>There are No project led by you</h3><br/><br/><br/>";
}
echo "</div></div>";

?>

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