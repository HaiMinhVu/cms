<?php
include('../../../newconnect.php');
include('../../../key.php');
include('admin_function.php');

/****************** get spec info to initalize update form ******************/
if($_POST['user_action'] == 'user_update_info'){
	$userID = $_POST['userID'];
	$sql = "SELECT id, email, role_id, username, CAST(AES_DECRYPT(password, 'SMe16!m34') AS char) AS pwd from users WHERE id = $userID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** add new category ******************/
if($_POST['user_action'] == 'add_user'){
	
	$email = mysqli_real_escape_string($cms_connect, $_POST['email']);
	$role = $_POST['roleID'] == '' ? 0 : $_POST['roleID'];
	$username = mysqli_real_escape_string($cms_connect, $_POST['username']);
	$password = mysqli_real_escape_string($cms_connect, $_POST['password']);
	$hashpwd = "aes_encrypt('".$password."','".$key."')";
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO users (email, role_id, username, password, uid_created, uid_modified) VALUES (\"$email\", $role, \"$username\", $hashpwd, $user_id, $user_id)";
	if($cms_connect->query($insertsql)){
		echo "User Added.";
	}
	else{
		echo "Failed.";
	}
}

/****************** add new category ******************/
if($_POST['user_action'] == 'update_user'){
	$userID = $_POST['userID'];
	$email = mysqli_real_escape_string($cms_connect, $_POST['email']);
	$role = $_POST['roleID'] == '' ? 0 : $_POST['roleID'];
	$username = mysqli_real_escape_string($cms_connect, $_POST['username']);
	$password = mysqli_real_escape_string($cms_connect, $_POST['password']);
	$hashpwd = "aes_encrypt('".$password."','".$key."')";
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE users SET email = \"$email\", role_id = $role, username = \"$username\", password = $hashpwd, uid_modified = $user_id WHERE id = $userID";
	if($cms_connect->query($updatesql)){
		echo "User Updated.";
	}
	else{
		echo "Failed.";
	}
	//echo $updatesql;
}

/****************** delete category *******************/
if($_POST['category_action'] == 'delete_user'){
	$userID = $_POST['userID'];
	$user_id = $_SESSION['uid'];
	$deletesql = "UPDATE users SET status = 0, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("ii", $user_id, $userID);
	if($stmt->execute()){
		echo "User Set to inactive.";
	}
	else{
		echo "Failed.";
	}
}

?>