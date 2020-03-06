<?php
include('../../../newconnect.php');
include('function.php');

/****************** add occurrence status ******************/
if(isset($_POST) && $_POST['action'] == 'register_action'){
	$fname = $_POST['firstname'];
	$lname = $_POST['lastname'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$country = $_POST['country'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$product = $_POST['model'];
	$sku = get_sku($cms_connect, $product);
	$serial = $_POST['serial'];
	
	$dealer = $_POST['dealer'];
	$price = $_POST['price'];
	$date_purchased = $_POST['date_purchased'];
	$satisfaction = $_POST['satisfaction'];
	$comment = $_POST['comment'];
	$add_mailing = $_POST['add_mailing'];

	$user_id = $_SESSION['uid'];

	// for upload proof of purchased
	$site_id = 1;
	$site_folder_id = 22;
	$file_type_id = 23;
	$proof = $_POST['proof'];
   
   	

	$sql = "INSERT INTO form_product_registration (first_name, last_name, email, address1, address2, city, state, zip, phone_number, product_id, DealerStore, price_paid, date_purchased, satisfaction, serial_number, comments, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)" ;
	
	$stmt = $cms_connect->prepare($sql);

	$stmt->bind_param("sssssssssissssssii", $fname, $lname, $email, $address1, $address2, $city, $state, $zipcode, $phone, $product, $dealer, $price, $date_purchased, $satisfaction, $serial, $comment, $user_id, $user_id);

	if($stmt->execute()){

		$result = "Your form submitted";

		$last_form_id = $cms_connect->insert_id;

		if(count($proof) >= 1){
			$tmpfile = json_decode($proof);
		   	$file_type = end(explode('.', $tmpfile->name));
		   	$file_name = 'PRPOP-'.$last_form_id.'.'.$file_type;
		   	$file_content = $tmpfile->data;
		   	$target_file = "proof-of-purchase/".$file_name;
			if(file_put_contents($target_file,base64_decode($file_content))){
				
				$insertsql = "INSERT INTO file_manager (file_type_id, file_name, site_folder_id, site_id, uid_created, uid_modified) VALUES (?,?,?,?,?,?)";
				$insertstmt = $cms_connect->prepare($insertsql);
				$insertstmt->bind_param("isiiii", $file_type_id, $file_name, $site_folder_id, $site_id, $user_id, $user_id);
				if($insertstmt->execute()){
					$last_file_id = $cms_connect->insert_id;
					$update_form_sql = "UPDATE form_product_registration SET proof_of_purchase = ? WHERE id = ?";
					$updatestmt = $cms_connect->prepare($update_form_sql);
					$updatestmt->bind_param("ii", $last_file_id, $last_form_id);
					$updatestmt->execute();	
				}
			}
		}
			

		$body = "<strong>Product Registration Confirmation</strong><br>";
		$body .= "<h4>Customer Info</h4>";
		$body .= "<table border='1'>
					<tr>
						<td>Name</td>
						<td>".$fname." ".$lname."</td>
					</tr>
					<tr>
						<td>Address</td>
						<td>".$address1." ".$address2." ".$city." ".$state." ".$zipcode." ".$country."</td>
					</tr>
					<tr>
						<td>Phone</td>
						<td>".$phone."</td>
					</tr>
				</table>";
		$body .= "<h4>Order Info</h4>";
		$body .= "<table border='1'>
					<tr>
						<td>Model</td>
						<td>".$sku."</td>
					</tr>
					<tr>
						<td>Serial</td>
						<td>".$serial."</td>
					</tr>
					<tr>
						<td>Dealer/Store</td>
						<td>".$dealer."</td>
					</tr>
					<tr>
						<td>Price Paid</td>
						<td>".$price."</td>
					</tr>
					<tr>
						<td>Date Purchased</td>
						<td>".$date_purchased."</td>
					</tr>
				</table>";
		require_once 'mail.php';
	}
	else{
		$result = "Failed to submit form";
	}


	echo $result;
	
}



?>