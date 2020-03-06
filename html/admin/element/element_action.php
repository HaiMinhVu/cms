<?php
include('../../../newconnect.php');
include('element_function.php');

/************************************************************
*************************NAVIGATION*************************
*************************************************************/

/****************** get navigation info to initalize update form ******************/
if($_POST['navigation_action'] == 'nav_update_info'){
	$navID = $_POST['navID'];
	$sql = "SELECT * FROM nav_cat WHERE id = $navID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** add new navigation ******************/
if($_POST['navigation_action'] == 'add_navigation'){
	$navParent = $_POST['navParentID'] == '' ? 0 : $_POST['navParentID'];
	$navName = $_POST['navName'];
	$navLink = $_POST['navLink'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO nav_cat (label, link, parent, uid_created, uid_modified) VALUES (?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("ssiii", $navName, $navLink, $navParent, $user_id, $user_id);
	if($stmt->execute()){
		echo "Navigation Added.";
	}
	else{
		echo "Failed.";
	}
}

/****************** update navigation *******************/
if($_POST['navigation_action'] == 'update_navigation'){
	$navID = $_POST['navID'];
	$navParent = $_POST['navParentID'] == '' ? 0 : $_POST['navParentID'];
	$navName = $_POST['navName'];
	$navLink = $_POST['navLink'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE nav_cat SET label = ?, link = ?, parent = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("ssiii", $navName, $navLink, $navParent, $user_id, $navID);
	if($stmt->execute()){
		echo "Navigation Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete navigation *******************/
if($_POST['navigation_action'] == 'delete_navigation'){
	$navID = $_POST['navID'];
	$user_id = $_SESSION['uid'];
	$status = 0;

	$deletesql = "DELETE FROM nav_cat WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("i", $navID);
	if($stmt->execute()){
		$updateparentsql = "UPDATE nav_cat SET parent = ?, uid_modified = ? WHERE parent = ?";
		$updatestmt = $cms_connect->prepare($updateparentsql);
		$updatestmt->bind_param("iii", $status, $user_id, $navID);
		$updatestmt->execute();
		echo "Navigation Deleted.";
	}
	else{
		echo "Failed.";
	}
}

/************************************************************
*************************TRADE SHOW**************************
*************************************************************/

/****************** get tradeshow info to initalize update form ******************/
if($_POST['show_action'] == 'tradeshow_info_update'){
	$showID = $_POST['showID'];
	$sql = "SELECT * FROM tradeshow WHERE id = $showID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** submit trade show add form ******************/
if($_POST['show_action'] == 'add_tradeshow'){

	$show_brandID = $_POST['show_brandID'];
	$show_thumbnail = $_POST['show_thumbnail'];
	$show_name = $_POST['show_name'];
	$show_datefrom = $_POST['show_datefrom'];
	$show_dateto = $_POST['show_dateto'];
	$show_location = $_POST['show_location'];
	$show_booth = $_POST['show_booth'];
	$show_link = $_POST['show_link'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO tradeshow (brand_id, file_id, ts_show, ts_date_from, ts_date_to, location, booth, ts_link, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("iissssssii", $show_brandID, $show_thumbnail, $show_name, $show_datefrom, $show_dateto, $show_location, $show_booth, $show_link, $user_id, $user_id);
	if($stmt->execute()){
		echo "Trade Show Added.";
	}
	else{
		echo "Failed.";
	}
}	

/****************** submit trade show update form ******************/
if($_POST['show_action'] == 'update_tradeshow'){
	$showID = $_POST['showID'];
	$show_brandID = $_POST['show_brandID'];
	$show_thumbnail = $_POST['show_thumbnail'];
	$show_name = $_POST['show_name'];
	$show_datefrom = $_POST['show_datefrom'];
	$show_dateto = $_POST['show_dateto'];
	$show_location = $_POST['show_location'];
	$show_booth = $_POST['show_booth'];
	$show_link = $_POST['show_link'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE tradeshow SET brand_id = ?, file_id = ?, ts_show = ?, ts_date_from = ?, ts_date_to = ?, location = ?, booth = ?, ts_link = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("iissssssii", $show_brandID, $show_thumbnail, $show_name, $show_datefrom, $show_dateto, $show_location, $show_booth, $show_link, $user_id, $showID);
	if($stmt->execute()){
		echo "Trade Show Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete trade show *******************/
if($_POST['show_action'] == 'delete_tradeshow'){
	$showID = $_POST['showID'];
	$user_id = $_SESSION['uid'];
	$status = 0;
	$deletesql = "UPDATE tradeshow SET ts_status = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("iii", $status, $user_id, $showID);
	if($stmt->execute()){
		echo "Show Deleted";
	}
	else{
		echo "Failed";
	}
}


/************************************************************
****************************SLIDER***************************
*************************************************************/

/****************** get slider info to initalize update form ******************/
if($_POST['slider_action'] == 'slider_info_update'){
	$sliderID = $_POST['sliderID'];
	$sql = "SELECT * FROM slider_image WHERE id = $sliderID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** submit slider add form ******************/
if($_POST['slider_action'] == 'add_slider'){

	$slider_pid = $_POST['slider_pid'];
	$page_name = $_POST['page_name'];
	$slider_img = $_POST['slider_img'];
	$slider_link = $_POST['slider_link'];
	$slider_text = $_POST['slider_text'];
	$slider_order = $_POST['slider_order'];
	
	$user_id = $_SESSION['uid'];

	if($slider_pid == 0){
		$newpageSql = "INSERT INTO slider_image (description, uid_created, uid_modified) VALUES (?,?,?)";
		$stmt = $cms_connect->prepare($newpageSql);
		$stmt->bind_param("sii", $page_name, $user_id, $user_id);
		if($stmt->execute()){
			$slider_pid = $cms_connect->insert_id;    
		}
		else{
			echo $cms_connect->errorInfo();
		}
	}

	$insertsql = "INSERT INTO slider_image (file_id, pid, link_value, text, slider_order, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("iissiii", $slider_img, $slider_pid, $slider_link, $slider_text, $slider_order, $user_id, $user_id);
	if($stmt->execute()){
		echo "Slider Added.";
	}
	else{
		echo "Failed.";
	}
}	

/****************** submit slider update form ******************/
if($_POST['slider_action'] == 'update_slider'){
	$sliderID = $_POST['sliderID'];
	$slider_pid = $_POST['slider_pid'];
	$slider_img = $_POST['slider_img'];
	$slider_link = $_POST['slider_link'];
	$slider_text = $_POST['slider_text'];
	$slider_order = $_POST['slider_order'];
	
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE slider_image SET file_id = ?, pid = ?, link_value = ?, text = ?, slider_order = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("iissiii", $slider_img, $slider_pid, $slider_link, $slider_text, $slider_order, $user_id, $sliderID);
	if($stmt->execute()){
		echo "Slider Updated.";
	}
	else{
		echo "Failed.";
	}
}

/****************** delete slider *******************/
if($_POST['slider_action'] == 'delete_slider'){
	$sliderID = $_POST['sliderID'];
	$user_id = $_SESSION['uid'];
	$deletesql = "DELETE FROM slider_image WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("i", $sliderID);
	if($stmt->execute()){
		echo "Slider Deleted";
	}
	else{
		echo "Failed";
	}
}


/************************************************************
************************CONTENT PAGE*************************
*************************************************************/

/****************** get page info to initalize update form ******************/
if($_POST['page_action'] == 'page_info_update'){
	$pageID = $_POST['pageID'];
	$sql = "SELECT id, description, cont_title, cont_content FROM content WHERE id = $pageID";
	$stmt = $cms_connect->query($sql);
	echo json_encode(array_map("utf8_encode", $stmt->fetch_assoc()));
}

/****************** submit page content form ******************/
if($_POST['page_action'] == 'submit_page_form'){
	$pageID = $_POST['pageID'];
	$page_description = $_POST['page_description'];
	$page_title = $_POST['page_cont_title'];
	$page_content = $_POST['page_content'];

	if($pageID == ''){
		$insertsql = "INSERT INTO content (description, cont_title, cont_content) VALUES (?,?,?)";
		$istmt = $cms_connect->prepare($insertsql);
		$istmt->bind_param("sss", $page_description, $page_title, $page_content);
		if($istmt->execute()){
			echo "New Page Added.";
		}
		else{
			echo "Failed.";
		}
	}
	else{
		$updatesql = "UPDATE content SET description = ?, cont_title = ?, cont_content = ? WHERE id = ?";
		$ustmt = $cms_connect->prepare($updatesql);
		$ustmt->bind_param("sssi", $page_description, $page_title, $page_content, $pageID);
		if($ustmt->execute()){
			echo "Page Updated.";
		}
		else{
			echo "Failed.".$cms_connect->error();
		}
	}
}


/************************************************************
*********************PRESS RELEASE PAGE**********************
*************************************************************/

/****************** press release list filter by brand ******************/
if($_POST['press_action'] == 'brand_filter'){
	$brandID = $_POST['brandID'];
	$output = '<option value="0">--- New Press Release ---</option>';
	$output .= press_release_select_option($cms_connect, $brandID);
	echo $output;

}

/****************** get page info to initalize update form ******************/
if($_POST['press_action'] == 'press_info'){
	$pressID = $_POST['pressID'];
	$sql = "SELECT * FROM press_release WHERE id = $pressID";
	$stmt = $cms_connect->query($sql);
	echo json_encode(array_map("utf8_encode", $stmt->fetch_assoc()));
}

/****************** submit press release form ******************/
if($_POST['press_action'] == 'submit_press_release_form'){
	$brandID = $_POST['brandID'];
	$pressID = $_POST['pressID'];
	$press_title = $_POST['press_title'];
	$short_thumbnail = $_POST['short_thumbnail'];
	$short_description = $_POST['short_description'];
	$main_thumbnail = $_POST['main_thumbnail'];
	$main_body = $_POST['main_body'];
	$release_date = $_POST['release_date'];
	$user_id = $_SESSION['uid'];

	if($pressID == 0){
		$insertsql = "INSERT INTO press_release (title, short_description, short_file_id, body, file_id, manufacture, release_date, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?,?,?)";
		$istmt = $cms_connect->prepare($insertsql);
		$istmt->bind_param("ssisiisii", $press_title, $short_description, $short_thumbnail, $main_body, $main_thumbnail, $brandID, $release_date, $user_id, $user_id);
		if($istmt->execute()){
			echo "New Press Release Added.";
		}
		else{
			echo "Failed.";
		}
	}
	else{
		$updatesql = "UPDATE press_release SET title = ?, short_description = ?, short_file_id = ?, body = ?, file_id = ?, manufacture = ?, release_date = ?, uid_modified = ? WHERE id = ?";
		$ustmt = $cms_connect->prepare($updatesql);
		$ustmt->bind_param("ssisiisii", $press_title, $short_description, $short_thumbnail, $main_body, $main_thumbnail, $brandID, $release_date, $user_id, $pressID);
		if($ustmt->execute()){
			echo "Press Release Updated.";
		}
		else{
			echo "Failed.".$cms_connect->error();
		}
	}
}



/************************************************************
************************CATEGORY PAGE************************
*************************************************************/

/****************** add new category ******************/
if($_POST['category_action'] == 'add_category'){
	$catParent = $_POST['catParentID'] == '' ? 0 : $_POST['catParentID'];
	$catName = $_POST['catName'];
	$catLink = $_POST['catLink'];
	$catText = $_POST['catText'];
	$catShortDescription = $_POST['catShortDescription'];
	$catLongDescription = $_POST['catLongDescription'];
	$catThumbnail = $_POST['catThumbnail'] == '' ? 0 : $_POST['catThumbnail'];
	$catManufacture = $_POST['catManufacture'] == '' ? 0 : $_POST['catManufacture'];
	$user_id = $_SESSION['uid'];

	$insertsql = "INSERT INTO product_category (label, link, parent, pc_text, short_description, long_description, thumbnail, manufacture, uid_created, uid_modified) VALUES (?,?,?,?,?,?,?,?,?,?)";
	$stmt = $cms_connect->prepare($insertsql);
	$stmt->bind_param("ssisssiiii", $catName, $catLink, $catParent, $catText, $catShortDescription, $catLongDescription, $catThumbnail, $catManufacture, $user_id, $user_id);
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
	$sql = "SELECT * FROM product_category WHERE id = $catID";
	$stmt = $cms_connect->query($sql);
	echo json_encode($stmt->fetch_assoc());
}

/****************** update category *******************/
if($_POST['category_action'] == 'update_category'){
	$catParent = $_POST['catParentID'] == '' ? 0 : $_POST['catParentID'];
	$catID = $_POST['catID'];
	$catName = $_POST['catName'];
	$catLink = $_POST['catLink'];
	$catText = $_POST['catText'];
	$catShortDescription = $_POST['catShortDescription'];
	$catLongDescription = $_POST['catLongDescription'];
	$catThumbnail = $_POST['catThumbnail'] == '' ? 0 : $_POST['catThumbnail'];
	$catManufacture = $_POST['catManufacture'] == '' ? 0 : $_POST['catManufacture'];
	$user_id = $_SESSION['uid'];

	$updatesql = "UPDATE product_category SET label = ?, link = ?, parent = ?, pc_text = ?, short_description = ?, long_description = ?, thumbnail = ?, manufacture = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($updatesql);
	$stmt->bind_param("ssisssiiii", $catName, $catLink, $catParent, $catText, $catShortDescription, $catLongDescription, $catThumbnail, $catManufacture, $user_id, $catID);
	if($stmt->execute()){
		echo "Category Updated";
	}
	else{
		echo "Failed";
	}
}

/****************** delete category *******************/
if($_POST['category_action'] == 'delete_category'){
	$catID = $_POST['catID'];
	$user_id = $_SESSION['uid'];
	$status = 0;
	$deletesql = "UPDATE product_category SET status = ?, uid_modified = ? WHERE id = ?";
	$stmt = $cms_connect->prepare($deletesql);
	$stmt->bind_param("iii", $status, $user_id, $catID);
	if($stmt->execute()){
		$updateparentsql = "UPDATE product_category SET parent = ?, uid_modified = ? WHERE parent = ?";
		$updatestmt = $cms_connect->prepare($updateparentsql);
		$updatestmt->bind_param("iii", $status, $user_id, $catID);
		$updatestmt->execute();
		echo "Category Deleted";
	}
	else{
		echo "Failed";
	}
}

/******** on select CatID -> preload associated images *******/
if($_POST['category_action'] == 'on_select_catID'){
	$tmp = array();

	$output = '';
	$catID = $_POST['catID'];
	$sql = "SELECT pch.*, fm.file_name, concat(sl.url, ml.description, fm.file_name) AS url
			FROM product_category_hero pch LEFT JOIN file_manager fm ON fm.ID = pch.file_id
											LEFT JOIN site_list sl ON sl.id = fm.site_id
						                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE pch.pid = $catID AND pch.active = 1
			ORDER BY pch.pch_order";
	$stmt = $cms_connect->query($sql);
	while($row = $stmt->fetch_assoc()){
		$tmp[] = $row['file_id'];

		$output .= "<tr>";
	    $output .= "<td><a href='".$row['url']."' data-toggle='lightbox' data-max-height='600'><img src='".$row['url']."' height=100></a></td>";
	    $output .= "<td><input type='text' name='tags[]' value='".$row['tags']."' ></td>";
	    $output .= "<td><button type='button' name='delete' id='".$row['file_id']."'' class='btn btn-outline-danger btn-sm disassociate' title='Disassociate Image'><i class='fa fa-minus'></i></button></td>";
	    $output .= "<input type='hidden' value='".$row['file_id']."' name='associatedImages[]'>";
	    $output .= "</tr>";

	}

	$readoutput = array();
	$readoutput['output'] = $output;
	$readoutput['fileIDs'] = $tmp;
	echo json_encode($readoutput);
}

/******** on change cat images *******/
if($_POST['category_action'] == 'on_image_change'){
	$catID = $_POST['catID'];
	$selected_imgIDArr = $_POST['imgIDs'];
	$existing_Order_imgIDArr = associated_image_by_catID($cms_connect, $catID);
	$output = "";
	
	// add to non order array if new cat img selected
	$non_existing_Order_imgIDArr = array();
	foreach ($selected_imgIDArr as $imgId){
		if(!in_array($imgId, $existing_Order_imgIDArr)){
			$non_existing_Order_imgIDArr[] = $imgId;
		}
	}
	// remove cat img that exist in the order array
	$lostImgIdArr = array_diff($existing_Order_imgIDArr, $selected_imgIDArr);
	foreach($lostImgIdArr as $lost){
		$key = array_search($lost, $existing_Order_imgIDArr);
		unset($existing_Order_imgIDArr[$key]);
	}

	$final_imgArr = array_merge($existing_Order_imgIDArr, $non_existing_Order_imgIDArr);

	foreach ($final_imgArr as $imgId){
		$catImgInfo = cat_image_info_by_id($cms_connect, $imgId, $catID);
		$ImgInfo = image_url_name_by_id($cms_connect, $imgId);
		$tags = '';
		if(count($specInfo) != 0){
			$tags = $catImgInfo['tags'];
		}
		$output .= "<tr>";
	    $output .= "<td><a href='".$ImgInfo['url']."' data-toggle='lightbox' data-max-height='600'><img src='".$ImgInfo['url']."' height=80></a></td>";
	    $output .= "<td><input type='text' name='tags[]' value='".$catImgInfo['tags']."'></td>";
	    $output .= "<td><button type='button' name='disassociate' id='".$imgId."'' class='btn btn-outline-danger btn-sm disassociate' title='Disassociate Image'><i class='fa fa-minus'></i></button></td>";
	    $output .= "<input type='hidden' value='".$imgId."' name='associatedImages[]'>";
	    $output .= "</tr>";

	}
	echo $output;
}

/******** on change cat images *******/
if($_POST['category_action'] == 'submit_cat_image_form'){
	$catID = $_POST['catID'];
	$imgArr = $_POST['associatedImages'];
	$tagArr = $_POST['tags'];
	$user_id = $_SESSION['uid'];

	// delete all existing associated
	$delete_stmt = $cms_connect->prepare("DELETE FROM product_category_hero WHERE pid = ?");
	$delete_stmt->bind_param("i",  $catID);
	$delete_stmt->execute();
	$delete_stmt->close();

	// insert new images
	$order = 1;
	for($i = 0; $i < count($imgArr); $i++){
		$image_insertsql = "INSERT INTO product_category_hero (file_id, pid, pch_order, tags, uid_created, uid_modified) VALUES (?, ?, ?, ?, ?, ?)";
		$image_stmt = $cms_connect->prepare($image_insertsql);
		$image_stmt->bind_param("iiisii", $imgArr[$i], $catID, $order, $tagArr[$i], $user_id, $user_id);
		if($image_stmt->execute()){
			$result = "Category Associated Image Updated";
			$order++;
		}
		else{
			$result = $cms_connect->error;
		}
		$image_stmt->close();
	}
	echo $result;
}
?>