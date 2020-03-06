<?php
include('../../../newconnect.php');
include('manage_function.php');

use App\Services\AWS;

/*********************************************************************************/
/********************************* NS CATEGORY ***********************************/
/*********************************************************************************/

/****************** add new category ******************/
if($_POST['category_action'] == 'add_category'){
	$catParent = $_POST['catParentID'] == '' ? 0 : $_POST['catParentID'];
	$catName = $_POST['catName'];
	$catLink = $_POST['catLink'];
	$catText = $_POST['catText'];
	$catShortDescription = $_POST['catShortDescription'];
	$catLongDescription = $_POST['catLongDescription'];
	$catContent = $_POST['catContent'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO list_product_category (label, parent, short_description, long_description, content, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("sisssii", $catName, $catParent, $catShortDescription, $catLongDescription, $catContent, $user_id, $user_id);
	if($stmt->execute()){
		echo "Category Added.";
	}
	else{
		echo "Failed.";
	}
}

/****************** get spec info to initalize update form ******************/
if($_POST['category_action'] == 'cat_update_info'){
	$catID = $_POST['catID'];
	$sql = "SELECT * FROM list_product_category WHERE id = $catID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** update category *******************/
if($_POST['category_action'] == 'update_category'){
	$catParent = $_POST['catParentID'] == '' ? 0 : $_POST['catParentID'];
	$catID = $_POST['catID'];
	$catName = $_POST['catName'];
	$catShortDescription = $_POST['catShortDescription'];
	$catLongDescription = $_POST['catLongDescription'];
	$catContent = $_POST['catContent'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE list_product_category SET label = ?, parent = ?, short_description = ?, long_description = ?, content = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("sisssii", $catName, $catParent, $catShortDescription, $catLongDescription, $catContent, $user_id, $catID);
	if($stmt->execute()){
		echo "Category Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete category *******************/
if($_POST['category_action'] == 'delete_category'){
	$catID = $_POST['catID'];
	$user_id = $_SESSION['uid'];
	$deletesql = "UPDATE list_product_category SET active = 0, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("ii", $user_id, $catID);
	if($stmt->execute()){
		$updateparentsql = "UPDATE list_product_category SET parent = 0, uid_modified = ? WHERE parent = ?";
		$updatestmt = $cms_connect->prepare($updateparentsql);
		$updatestmt->bind_param("ii", $user_id, $catID);
		$updatestmt->execute();
		echo "Category Deleted.";
	}
	else{
		echo "Failed.";
	}
}


/*********************************************************************************/
/*********************************** SPECS PAGE **********************************/
/*********************************************************************************/

/****************** add new spec ******************/
if($_POST['spec_action'] == 'add_spec'){
	$specParent = $_POST['specParentID'] == '' ? 0 : $_POST['specParentID'];
	$specName = $_POST['specName'];
	$specSuffix = $_POST['specSuffix'];
	$specContext = $_POST['specContext'];
	$specMeasure = $_POST['specMeasure'];
	$specMeasureType = $_POST['specMeasureType'];
	$specSystem = $_POST['specSystem'];

	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO list_product_spec (name, unit_suffix, context, measures, measure_type, system, pid, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("ssssssiii", $specName, $specSuffix, $specContext, $specMeasure, $specMeasureType, $specSystem, $specParent, $user_id, $user_id);
	if($stmt->execute()){
		echo "Spec Added.";
		
	}
	else{
		echo "Failed.";
	}
}

/****************** get spec info to initalize update form ******************/
if($_POST['spec_action'] == 'spec_update_info'){
	$specID = $_POST['specID'];
	$sql = "SELECT * FROM list_product_spec WHERE id = $specID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** update spec ******************/
if($_POST['spec_action'] == 'update_spec'){
	$specID = $_POST['specID'];
	$specParent = $_POST['specParentID'] == '' ? 0 : $_POST['specParentID'];
	$specName = $_POST['specName'];
	$specSuffix = $_POST['specSuffix'];
	$specContext = $_POST['specContext'];
	$specMeasure = $_POST['specMeasure'];
	$specMeasureType = $_POST['specMeasureType'];
	$specSystem = $_POST['specSystem'];

	$user_id = $_SESSION['uid'];


	$updatesql = "UPDATE list_product_spec SET name = ?, unit_suffix = ?, context = ?, measures = ?, measure_type = ?, system = ?, pid = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);

	$stmt->bind_param("ssssssiii", $specName, $specSuffix, $specContext, $specMeasure, $specMeasureType, $specSystem, $specParent, $user_id, $specID);
	if($stmt->execute()){
		echo "Spec Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete spec *******************/
if($_POST['spec_action'] == 'delete_spec'){
	$specID = $_POST['specID'];
	$user_id = $_SESSION['uid'];

	$deletesql = "UPDATE list_product_spec SET active = 0, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("ii", $user_id, $specID);
	if($stmt->execute()){
		$updateparentsql = "UPDATE list_product_spec SET pid = 0, uid_modified = ? WHERE pid = ?";
		$updatestmt = $cms_connect->prepare($updateparentsql);
		$updatestmt->bind_param("ii", $user_id, $specID);
		$updatestmt->execute();
		echo "Category Deleted";
	}
	else{
		echo "Failed";
	}
}


/*********************************************************************************/
/********************************* FILES PAGE ************************************/
/*********************************************************************************/

/****************** pre load files ******************/
if($_POST['file_action'] == 'preload_file'){
	$allfilenames = all_file_name($cms_connect);
	$output = '';
	$filenames = $_POST['filenames'];

	foreach ($filenames as $name) {
		if(in_array($name, $allfilenames)){
			$name = "<span style='color:red'>".$name." (existing)</span>";
		}
		$output .= "<tr>";
	    $output .= "<td>".$name."</td>";
		$output .= "<td><input type='text' name='display_name[]' class='form-control'></td>";
	    $output .= "</tr>";
	}
	echo $output;
}

/****************** add new file ******************/
if($_POST['file_action'] == 'add_file'){

	$file_type_id = $_POST['file_type'];
	$site_folder_id = $_POST['folder'];
	$site_id = $_POST['site'];
	$user_id = $_SESSION['uid'];

	$display_nameArr = $_POST['display_name'];
	$filesArr = $_POST['files_upload'];

	// echo  json_encode($_POST);
	

	if($site_id === '4'){
		$sitepath = '/home/sellmar1/public_html/SM/';
	}
	elseif($site_id === '7'){
		$sitepath = '/home/sellmar1/public_html/FF/';
	}
	elseif($site_id === '10'){
		$sitepath = '/home/sellmark/public_html/PL/';
	}
	elseif($site_id === '11'){
		$sitepath = '/home/sellmar1/public_html/SC/';
	}
	elseif($site_id === '12'){
		$sitepath = '/home/sellmar1/public_html/TS/';
	}
	elseif($site_id === '17'){
		$sitepath = '/home/sellmar1/public_html/eternal/';
	}
	elseif($site_id === '21'){
		$sitepath = '/home/sellmar1/public_html/KJ/';
	}
	else{
		$sitepath = '/home/sellmar1/public_html/sellmark/';
	}
		
	$folder = folder_name_by_id($cms_connect, $site_folder_id);

	$uploaded = 0;
	if(count($filesArr) == 0){
		$message = "No files uploaded. dump ass";
	}
	else{
		for($i = 0; $i < count($filesArr); $i++) {
			$tmpfile = json_decode($filesArr[$i]);
			
			$file_name = $tmpfile->name;
			$display_name = $display_nameArr[$i];

			$target_file = $sitepath.$folder.$file_name;
			$filecontent = $tmpfile->data;
			$filedata = base64_decode($filecontent);

				try {
					$insertsql = "INSERT INTO file_manager(file_type_id, file_name, description, site_folder_id, site_id, uid_created, uid_modified)
								VALUES (?,?,?,?,?,?,?)";
					$stmt = $cms_connect->prepare($insertsql);
					$stmt->bind_param("issiiii", $file_type_id, $file_name, $display_name, $site_folder_id, $site_id, $user_id, $user_id);
					if($stmt->execute()){
						$uploaded++;
						$result = "files uploaded.";

						AWS::uploadToS3("test/{$folder}{$file_name}", $filedata);
					}
					else{
						$result = "files failed to upload.";
					}

					// copy file to sightmark.com if it is sightmark
					// if($site_id === '4'){
					// 	$local_file = $target_file;
					// 	$remote_file = "public_html/SM/".$folder.$file_name;
					// 	$ftp_server = 'sightmark-ds.com'; 
					// 	$ftp_user_name = 'sightma1@sightmark-ds.com';
					// 	$ftp_user_pass = 'bvhfur84BVHFUR*$';
					// 	$conn_id = ftp_connect($ftp_server);
					// 	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
					// 	ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
					// 	ftp_close($conn_id);
					// }
					// else if($site_id === '21'){
					// 	$local_file = $target_file;
					// 	$remote_file = "public_html/".$folder.$file_name;
					// 	$ftp_server = 'ftp.kjrests.com'; 
					// 	$ftp_user_name = 'cms@kjrests.com';
					// 	$ftp_user_pass = 'bvhfur84BVHFUR*$';
					// 	$conn_id = ftp_connect($ftp_server);
					// 	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
					// 	ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
					// 	ftp_close($conn_id);
					// }
				} catch(\Exception $e) {
					print_r($e->getMessage());
					exit();
				}

			// }
			// else{
			// 	$result = "files failed to upload.";
			// }
		}
		$message = $uploaded.' '. $result;
	}
	echo $message;
}

/****************** get file info to initalize update form ******************/
if($_POST['file_action'] == 'file_update_info'){
	$fileID = $_POST['fileID'];
	$sql = "SELECT * FROM file_manager WHERE ID = $fileID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** update file ******************/
if($_POST['file_action'] == 'update_file'){
	$typeID = $_POST['file_type_modal'];
	$folderID = $_POST['site_folder_modal'];
	$siteID = $_POST['site_modal'];
	$fileName = $_POST['file_name_modal'];

	//get path to website
	if($siteID === '4'){
		$sitepath = '/home2/sellmark/public_html/sightmark/';
	}
	elseif($siteID === '7'){
		$sitepath = '/home2/sellmark/public_html/firefield/';
	}
	elseif($siteID === '10'){
		$sitepath = '/home2/sellmark/public_html/pulsarnv/';
	}
	elseif($siteID === '11'){
		$sitepath = '/home2/sellmark/public_html/southerncrossbow/';
	}
	elseif($siteID === '12'){
		$sitepath = '/home2/sellmark/public_html/12survivors/';
	}

	// get folder which stored files
	$folder = folder_name_by_id($cms_connect, $folderID);

	$target_file = $sitepath.$folder.$fileName.'.'.$fileType;

	$filecontent = $uploadfile->data;
	$testpath = 'file/'.$fileName.'.'.$fileType;
	file_put_contents($testpath,base64_decode($filecontent));
	echo $testpath;

	
}

/****************** delete file *******************/
if($_POST['file_action'] == 'delete_file'){
	$fileID = $_POST['fileID'];

	$deletesql = "DELETE FROM file_manager WHERE ID = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("i", $fileID);
	if($stmt->execute()){
		echo "File Deleted";
	}
	else{
		echo "Failed";
	}
}


/*********************************************************************************/
/*********************************** SITES PAGE **********************************/
/*********************************************************************************/

/****************** get site info to initalize update form ******************/
if($_POST['site_action'] == 'site_update_info'){
	$siteID = $_POST['siteID'];
	$sql = "SELECT * FROM site_list WHERE id = $siteID";
	$stmt = $cms_connect->query($sql);
	echo json_encode(array_map("utf8_encode", $stmt->fetch_assoc()));
}

/****************** add new spec *******************/
if($_POST['site_action'] == 'add_site'){
	$siteName = $_POST['siteName'];
	$siteLink = $_POST['siteLink'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO site_list (label, url, uid_created, uid_modified) VALUES (?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("ssii", $siteName, $siteLink, $user_id, $user_id);
	if($stmt->execute()){
		echo "Site Added.";
	}
	else{
		echo "Failed.";
	}
}

/****************** update site *******************/
if($_POST['site_action'] == 'update_site'){
	$siteID = $_POST['siteID'];
	$siteName = $_POST['siteName'];
	$siteLink = $_POST['siteLink'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE site_list SET label = ?, url = ?, uid_modified = ? WHERE id = ? ";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("ssii", $siteName, $siteLink, $user_id, $siteID);
	if($stmt->execute()){
		echo "Site Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete site *******************/
if($_POST['site_action'] == 'delete_site'){
	$siteID = $_POST['siteID'];
	$user_id = $_SESSION['uid'];
	$deletesql = "DELETE FROM site_list WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("i", $siteID);
	if($stmt->execute()){
		echo "Site Deleted.";
	}
	else{
		echo "Failed";
	}
}


/*********************************************************************************/
/****************************** MASTER LIST SPACE ********************************/
/*********************************************************************************/

/****************** add new master list *******************/
if($_POST['master_action'] == 'add_master'){
	$masterName = $_POST['masterName'];
	$masterPID = $_POST['masterPID'] == '' ? 0 : $_POST['masterPID'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO master_list (description, pid, uid_created, uid_modified) VALUES (?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("siii", $masterName, $masterPID, $user_id, $user_id);
	if($stmt->execute()){
		echo "Master List Added.";
	}
	else{
		echo "Failed.";
	}
}

/****************** update master list *******************/
if($_POST['master_action'] == 'update_master'){
	$masterID = $_POST['masterID'];
	$masterName = $_POST['masterName'];
	$masterPID = $_POST['masterPID'] == '' ? 0 : $_POST['masterPID'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE master_list SET description = ?, pid = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("siii", $masterName, $masterPID, $user_id, $masterID);
	if($stmt->execute()){
		echo "Master List Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete master list *******************/
if($_POST['master_action'] == 'delete_master'){
	$masterID = $_POST['masterID'];
	$user_id = $_SESSION['uid'];
	$parent = 0;

	$deletesql = "DELETE FROM master_list WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("i", $masterID);
	if($stmt->execute()){
		$updateparentsql = "UPDATE master_list SET pid = ?, uid_modified = ? WHERE pid = ?";
		$updatestmt = $cms_connect->prepare($updateparentsql);
		$updatestmt->bind_param("iii", $parent, $user_id, $masterID);
		$updatestmt->execute();
		echo "Master List Deleted";
	}
	else{
		echo "Failed";
	}
}



?>