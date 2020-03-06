<?php
include('../../../newconnect.php');

$sql = "select * from SM_Employees";
$stmt = $mk_connect->query($sql);
while($row = $stmt->fetch_assoc()){
	print_r($row);
}

?>