<?php
session_start();
include_once 'db_connection.php';
if (isset($_GET['pid']))    
    $_SESSION['pid'] = $_GET['pid'];
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
            <p class="navbar-form navbar-right" style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>">Welcome! <?php echo $_SESSION['valid_user']; ?><a style="<?php if (!isset($_SESSION['valid_user'])) echo "display:none"; ?>"href="logout.php">
            Log Out</a></p>
        </div>
    </div>
</nav>

<div class = "row">
    <div class="center-block" style="width:80%;">
        <div class="page-header'">
            <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="searchform">
                <fieldset style="display: inline-block;" class="raw">
                    <div class="form-group">
                        <input type="text" name="keyword" placeholder="Filter for title or description" required class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="submit" name="search" value="Filter" class="btn btn-success" />
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<div class = "row">
    <div class="center-block" style="width:80%;">
        <div class="page-header'">
            <a href='checkissue.php?pid=<?php echo $_SESSION['pid']; ?>'>
            <button type='button' class ='btn btn-success' style='<?php if(!isset($_SESSION['valid_user'])) echo "display:none"; ?>'>Show Original</button></a>
        </div>
    </div>
</div>

<?php

if(isset($_SESSION["pid"])){
    echo "<div class='row'>
                <div class='center-block' style='width:80%;'>
                <div class='page-header'>
                <h2 align ='center'>Issues of the Project</h2><br/>
                </div>";
    if(!isset($_POST["keyword"])){
        $get_issue = "with current_status as(select iid,max(modifytime) as newest from status_history group by iid) select iid,ititle,idescription,currentstatus,modifytime,u2.username as reporter from user u1 natural join issue natural join status_history natural join current_status,user u2 where issue.pid = " . "'" .$_SESSION["pid"] . "'and modifytime = newest and reporter=u2.uemail group by iid";
    }
    else{
        $get_issue = "with current_status as(select iid,max(modifytime) as newest from status_history group by iid) select iid,ititle,idescription,currentstatus,modifytime,u2.username as reporter from user u1 natural join issue natural join status_history natural join current_status,user u2 where issue.pid = " . "'" .$_SESSION["pid"] . "'and modifytime = newest and reporter=u2.uemail and (ititle like '%" .$_POST["keyword"] . "%' or idescription like '%" .$_POST["keyword"] . "%') group by iid";
    }
    $issues = $conn->query($get_issue);

    if ($issues -> num_rows > 0) {
        echo "<table class= 'table table-striped table-hover'><tr><th>Issue Title</th><th>Issue Description</th><th>Current Status</th><th>Modifytime</th><th>Reporter</th><th></th></tr>";
        while ($row = $issues -> fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['ititle'] . "</td>";
            echo "<td>" . $row['idescription'] . "</td>";
            echo "<td>" . $row['currentstatus'] . "</td>";
            echo "<td>" . $row['modifytime'] . "</td>";
            echo "<td>" . $row['reporter'] . "</td>";
            echo "<td><a href='checkdetail.php?iid=".$row['iid']."'>check detail</a></td>";
            echo "</tr>";                
        }
        echo "</table><br/>";       
    } else {
        echo "<h3 align ='center'>There are No such issue under such project</h3><br/><br/><br/>";
    }
    echo "</div></div>";
}
?>

<div class = "row">
    <div class="center-block" style="width:80%;">
        <div class="page-header'">
            <a href='reportIssue.php?pid=<?php echo $_SESSION['pid']; ?>'>
            <button type='button' class ='btn btn-success' style='<?php if(!isset($_SESSION['valid_user'])) echo "display:none"; ?>'>Report Issues</button></a>
        </div>
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