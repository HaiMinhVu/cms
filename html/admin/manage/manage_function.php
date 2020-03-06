<?php
/*********************************************************************************/
/********************************* NS CATEGORY ***********************************/
/*********************************************************************************/
function manufacture_select_option($cms_connect){
    $sql = "SELECT id, name FROM list_manufacture WHERE manufacture_active = 1";
    $result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['name'].'</option>';
    }
    return $output;
}

function treeview_category_array($cms_connect){	
	$output = array();
	$sql = "SELECT id, label, parent FROM list_product_category WHERE active = 1";
	$result = $cms_connect->query($sql);
	while($row = $result->fetch_assoc()){
		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['parent'] == 0 ? '#' : $row['parent'];
		$tmp['text'] = $row['label'];
		$output[] = $tmp;
	}
	return $output;
}

function category_select_option($cms_connect){
	$sql = "SELECT id, label FROM list_product_category WHERE active = 1 ";
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['label'].'</option>';    
    }
    return $output;
}

function all_image_select_option($cms_connect){
    $sql = "SELECT DISTINCT fm.ID, concat(sl.url, ml.description, fm.file_name) as url, fm.file_name
			FROM file_manager fm LEFT JOIN site_list sl ON sl.id = fm.site_id
			                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE fm.file_type_id = 10 ";
    $result = $cms_connect->query($sql);
    $output = '';

    foreach ($result as $row){
    	$url = str_replace(' ', '%20', $row['url']);
	    //$output .='<option data-content="<img width=\'50\' height=\'50\' src=\''.$url.'\'> '.$row['file_name'].'" value = "'.$row['ID'].'"></option>';
	    $output .='<option value = "'.$row['ID'].'">'.$row['file_name'].'</option>';	    
    }

    return $output;
}

function associated_image_by_catID($cms_connect, $catID){
	$img = array();
	$sql = "SELECT id, file_id FROM product_category_hero WHERE pid = $catID ORDER BY pch_order";
	$stmt = $cms_connect->query($sql);
	while($row = $stmt->fetch_assoc()){
		$img[] = $row['file_id'];	
	}
	return $img;
}

function cat_image_info_by_id($cms_connect, $imageid, $catID){
	$output = array();
	$sql = "SELECT pch.*, fm.file_name, concat(sl.url, ml.description, fm.file_name) AS url
			FROM product_category_hero pch LEFT JOIN file_manager fm ON fm.ID = pch.file_id
											LEFT JOIN site_list sl ON sl.id = fm.site_id
						                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE pch.pid = $catID AND pch.active = 1 AND file_id = $imageid";
    $result = $cms_connect->query($sql);
    return $result->fetch_assoc();
}

function image_url_name_by_id($cms_connect, $imageid){
	$output = array();
	$sql = "SELECT concat(sl.url, ml.description, fm.file_name) as url, fm.file_name
			FROM file_manager fm LEFT JOIN site_list sl ON sl.id = fm.site_id
			                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE fm.file_type_id = 10 AND fm.ID = $imageid";
    $result = $cms_connect->query($sql);
    return $result->fetch_assoc();
}


/*********************************************************************************/
/*********************************** SPECS PAGE **********************************/
/*********************************************************************************/
function treeview_spec_array($cms_connect){	
	$output = array();
	$sql = "SELECT id, name, pid FROM list_product_spec WHERE active = 1";
	$result = $cms_connect->query($sql);
	while($row = $result->fetch_assoc()){
		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['pid'] == 0 ? '#' : $row['pid'];
		$tmp['text'] = utf8_decode(trim($row['name']));
		$output[] = $tmp;
	}
	return $output;
}

function spec_select_option($cms_connect){
	$sql = 'SELECT id, name FROM list_product_spec WHERE active = 1';
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['name'].'</option>';    
    }
    return $output;
}


/*********************************************************************************/
/********************************* FILES PAGE ************************************/
/*********************************************************************************/

function file_type_select_option($cms_connect){
	$sql = "SELECT id, description FROM master_list WHERE pid = 9";
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['description'].'</option>';    
    }
    return $output;
}

function site_folder_select_option($cms_connect){
	$sql = "SELECT id, description FROM master_list WHERE pid = 13";
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['description'].'</option>';    
    }
    return $output;
}

function site_list_select_option($cms_connect){
	$sql = "SELECT id, label FROM site_list";
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.utf8_encode($row['label']).'</option>';    
    }
    return $output;
}

// return folder name (to store files)
function folder_name_by_id($cms_connect, $id){
	$sql = "SELECT id, description FROM master_list WHERE id = $id";
	$result = $cms_connect->query($sql);
	$row = $result->fetch_assoc();
	return $row['description'];
}

function all_file_name($cms_connect){
	$output = array();
	$sql = "SELECT DISTINCT file_name FROM file_manager";
	$result = $cms_connect->query($sql);
	while($row = $result->fetch_assoc()){
		array_push($output, $row['file_name']);
	}
	return $output;
}


/*********************************************************************************/
/****************************** MASTER LIST SPACE ********************************/
/*********************************************************************************/
function treeview_masterlist_array($cms_connect){	
	$output = array();
	$sql = "SELECT id, description, pid FROM master_list";
	$result = $cms_connect->query($sql);
	while($row = $result->fetch_assoc()){
		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['pid'] == 0 ? '#' : $row['pid'];
		$tmp['text'] = $row['description'];
		$output[] = $tmp;
	}
	return $output;
}

function master_select_option_recursive($dbconnect, $pid = 0, $indent = ''){
    $sql = "SELECT id, description FROM master_list WHERE pid = $pid";
	$result = $dbconnect->query($sql);

    while($row = $result->fetch_assoc()){
        echo '<option value = "'.$row['id'].'">'.$indent.$row['description'].'</option>';
        master_select_option_recursive($dbconnect, $row['id'], $indent.$row['description'].' -> ');
    }    
}

/****************** functions for CATEGORY HERO List page ************************/
function treeview_category_hero_array($cms_connect){	
	$output = array();
	$sql = "SELECT pc.id, fm.file_name, pc.pid, concat(sl.url, ml.description, fm.file_name) as url
			FROM product_category_hero pc LEFT JOIN file_manager fm ON fm.ID = pc.file_id
									LEFT JOIN site_list sl ON sl.id = fm.site_id
						            LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE active = 1  
			";
	$result = $cms_connect->query($sql);
	

	while($row = $result->fetch_assoc()){
		$image = '<a href="'.$row['url'].'" target="_blank"><img src="'.$row['url'].'" width=30 height=30></a>';

		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['pid'] == 0 ? '#' : $row['pid'];
		$tmp['text'] = $row['file_name'];
		$tmp['data'] = array('image' => $image);
		$output[] = $tmp;
	}
	return $output;
}



/*********************************************************************************/
/******************************** RESOURCE SPACE *********************************/
/*********************************************************************************/


?>