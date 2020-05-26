<?php
include '../../dbconnect.php';
$server = 'http://'.$_SERVER['HTTP_HOST'];

if(isset($_SESSION['loggedin'])){
	header('location:index.php');
}
else{
	$message="";
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

       	$loginsql = "";
       	$stmt = $dbconnect->prepare("SELECT * FROM SM_Employees WHERE Username = ? AND Password = ?");
       	$stmt->execute([$username, md5($password)]);
       	$count = $stmt->rowCount();
       	if($count === 1){
       		$row = $stmt->fetch(PDO::FETCH_ASSOC);
       		switch ($row['Role']){
                case 1:
                	$_SESSION['role'] = "Admin";
                	break;
                case 2:
                	$_SESSION['role'] = "Manager";
                	break;
                case 3:
                	$_SESSION['role'] = "User";
                	break;
                case 4:
                	$_SESSION['role'] = "Learner";
                	break;
                case 5:
                	$_SESSION['role'] = "Visitor";
                	break;
            }
            $_SESSION['emp_id'] = $empid = $row['SMEmID'];
        	//$_SESSION['emp_name'] = $row['SMEmFirstName'].' '.$row['SMEmLastName'];
        	//$_SESSION['tande_owner'] = $row['SMEmFirstName'].' '.substr($row['SMEmLastName'],0,1).'.';
            $_SESSION['username'] = $row['Username'];
        	$_SESSION['loggedin'] = 1;

        	$dstmt = $dbconnect->prepare("SELECT SMDeptID FROM SM_E_BelongTo_D WHERE SMEmID = ?");
       		$dstmt->execute([$empid]);
        	while($drow = $dstmt->fetch(PDO::FETCH_ASSOC)){
            	$deptid[] = $drow['SMDeptID'];
            }
        	$_SESSION['dept'] = $deptid;
        	header('location:index.php');
       	}
       	else{
       		$message = "<label><font color='red'>Check your login</font></label>";
       	}
       	

       	
	}   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title >Sellmark Marketing Management</title>

    <!-- Bootstrap -->
    <link href="<?php echo $server?>/assets/css/bootstrap.min.css" rel="stylesheet">


</head>
<body>
  	
	<div class="row" style="margin-top: 100px">
		<div class="col-xs-12 col-sm-12 col-md-12 ">
			<h1 align="center" style="color: blue"><strong>SELLMARK MARKETING</strong></h1>
		</div>
	</div>
	<div class="row">
		<div class="offset-sm-3 offset-md-3 offset-lg-3 col-sm-6 col-md-6 col-lg-6" align="center">
			<p>Please use the login credentials provided to you to access Sellmark External Resources.</p>
			<p align="center"><?php echo $message; ?></p>
			<div id="center">
				
				<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
					<div class="form-group">
	  					<div class="col-sm-10">
	  						<input type="text" name="username" class="form-control" placeholder="Username">
	  					</div>
	  				</div>
	  				<div class="form-group">
	  					<div class="col-sm-10">
	  						<input type="password" name="password" class="form-control" placeholder="Password">
	  					</div>
	  				</div>
	  				<div class="form-group">
					    <div class="col-sm-10">
					    	<input type="submit" name="login" value="Login" class="btn btn-primary" />
					    </div>
				    </div>
				</form>
			</div>
		</div>
	</div>
  </body>
</html>


