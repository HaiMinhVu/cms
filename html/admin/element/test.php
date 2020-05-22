<?php
include('../../../newconnect.php');


	$sql = "SELECT id, cont_title, cont_content FROM content WHERE id = 4";
	$stmt = $cms_connect->query($sql);
	echo json_encode(array_map("utf8_encode", $stmt->fetch_assoc()));
	
?>