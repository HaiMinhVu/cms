<?php
/************** get sku by id ***************/
function get_sku($cms_connect, $id){
	$output = array();
	$sql = "SELECT sku FROM product WHERE id = $id";
	$result = $cms_connect->query($sql);
	$row =  $result->fetch_assoc();
	return $row['sku'];
}


function site_list($cms_connect){
	$output = array();
	$sql = 'SELECT * FROM site_list';
	$result = $cms_connect->query($sql);
    foreach ($result as $row) {
		$output .='<option value = "'.$row['id'].'">'.$row['label'].'</option>';
    }
	return $output;
}


/************* SKU list to check duplicated when adding new product ***********/
function product_list_select_option($cms_connect){
	$output = array();
	$sql = 'SELECT p.id as prod_id, p.nsid, p.sku, CONCAT(CONCAT(m.site,"/images/"),fm.file_name) as mainimg,p.UPC, p.feature_name,p.status,product_category_id, pppc.label as ppcat,ppc.label as pcat,pc.label as cat, fm.*,m.* 
				FROM product p
				 LEFT JOIN file_manager fm ON (p.main_img_id=fm.ID) left join product_category pc ON (pc.id=p.product_category_id) 
				left join product_category ppc on (pc.parent=ppc.id) left join product_category pppc on (ppc.parent=pppc.id), list_manufacture m
				where p.manufacture = m.id and 
					m.id = "1" 
				order by p.status DESC, sku ASC';
	$result = $cms_connect->query($sql);
    foreach ($result as $row) {
		$output .='<option value = "'.$row['prod_id'].'">'.$row['sku'].'</option>';
    }
	return $output;
}

function send_mail($mail, $body, $email){
	$mail->IsSMTP(); // enable SMTP
	//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587; 
	$mail->IsHTML(true);
	$mail->Username = "autobot.sellmark@gmail.com";
	$mail->Password = "H@i1101989";

	$mail->setFrom('autobot.sellmark@gmail.com', 'AutoBot');
	//$mail->addAddress('hvu@sellmark.net', 'Hai Vu'); 
	$mail->addAddress($email, ''); 


	$mail->Subject = "Confirmation Email";

	$mail->Body = $body;


	if($mail->Send()){
		return "Email has been sent.";
	}
	else{
		return "Fail to send email";
	}
}

?>