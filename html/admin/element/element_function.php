<?php

/************************ functions used by multiple pages ****************************/
function brand_list_select_option($cms_connect){
    $sql = "SELECT id, name FROM list_manufacture WHERE manufacture_active = 1 ORDER BY id";
    $result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.utf8_encode($row['name']).'</option>';
    }
    return $output;
}

/****************** functions for CATEGORY page ************************/
function treeview_category_array($cms_connect){	
	$output = array();
	$sql = "SELECT pc.id, pc.label, pc.parent, concat(sl.url, ml.description, fm.file_name) as url, pc.thumbnail
			FROM product_category pc LEFT JOIN file_manager fm ON fm.ID = pc.thumbnail
									LEFT JOIN site_list sl ON sl.id = fm.site_id
						            LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE status = 1  
			";
	$result = $cms_connect->query($sql);
	

	while($row = $result->fetch_assoc()){
		$image = '';
		if($row['thumbnail'] != 0){
			$image = '<a href="'.$row['url'].'" data-toggle="lightbox" data-max-height="600"><img src="'.$row['url'].'" height=30></a>';
		}
		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['parent'] == 0 ? '#' : $row['parent'];
		$tmp['text'] = '['.$row['id'].'] '.$row['label'];
		$tmp['data'] = array('image' => $image);
		$output[] = $tmp;
	}
	return $output;
}

function category_select_option($cms_connect){
	$sql = "SELECT id, label FROM product_category WHERE status = 1";
	$result = $cms_connect->query($sql);
	$output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['label'].'</option>';    
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


/****************** functions for NAVIGATION page ************************/

function treeview_navigation_array($cms_connect){	
	$output = array();
	$sql = "SELECT id, label, parent FROM nav_cat";
	$result = $cms_connect->query($sql);
	while($row = $result->fetch_assoc()){
		$tmp = array();
		$tmp['id'] = $row['id'];
		$tmp['parent'] = $row['parent'] == 0 ? '#' : $row['parent'];
		$tmp['text'] = '['.$row['id'].'] '.$row['label'];
		$output[] = $tmp;
	}
	return $output;
}

function nav_select_option_recursive($cms_connect, $pid = 0, $indent = ''){
    $sql = "SELECT id, label FROM nav_cat WHERE parent = $pid";
	$result = $cms_connect->query($sql);

    while($row = $result->fetch_assoc()){
        echo '<option value = "'.$row['id'].'">'.$indent.$row['label'].'</option>';
        nav_select_option_recursive($cms_connect, $row['id'], $indent.$row['label'].' -> ');
        //nav_select_option_recursive($cms_connect, $row['id'], $indent.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
    }    
}


/****************** functions for TRADE SHOWs page ************************/
function all_image_select_option($cms_connect){
    $sql = "SELECT DISTINCT fm.ID, concat(sl.url, ml.description, fm.file_name) as url, fm.file_name
			FROM file_manager fm LEFT JOIN site_list sl ON sl.id = fm.site_id
			                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE fm.file_type_id = 10 ";
    $result = $cms_connect->query($sql);
    $output = '';

    foreach ($result as $row){
	    $output .='<option value = "'.$row['ID'].'">'.$row['file_name'].'</option>';	    
    }

    return $output;
}

function all_image_select_option_imgview($cms_connect){
    $sql = "SELECT DISTINCT fm.ID, concat(sl.url, ml.description, fm.file_name) as url, fm.file_name
			FROM file_manager fm LEFT JOIN site_list sl ON sl.id = fm.site_id
			                	LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
			WHERE fm.file_type_id = 10 ";
    $result = $cms_connect->query($sql);
    $output = '';

    foreach ($result as $row){
    	$url = str_replace(' ', '%20', $row['url']);
	    $output .='<option data-content="<img height=\'50\' src=\''.$url.'\'> '.$row['file_name'].'" value = "'.$row['ID'].'"></option>';
	    //$output .='<option value = "'.$row['ID'].'">'.$row['file_name'].'</option>';	    
    }

    return $output;
}



/****************** functions for SLIDERs page ************************/
function page_have_sliders($cms_connect){
	$sql = "SELECT * FROM slider_image  WHERE pid = 0 AND active = 1";
	$result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['description'].'</option>';
    }
    return $output;
}


/****************** functions for CONTENT page ************************/
function page_list_select_option($cms_connect){
	$sql = "SELECT id, description FROM content WHERE cont_active = 1 ORDER BY id ";
	$result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['id'].' : '.$row['description'].'</option>';
    }
    return $output;
}


/****************** functions for PRESS RELEASE page ************************/
function press_release_select_option($cms_connect, $brandID){
	if($brandID != ''){
		$sql = "SELECT * FROM press_release WHERE manufacture = $brandID AND status = 1 ";
	}
    else{
    	$sql = "SELECT * FROM press_release WHERE status = 1 ";
    }
    $result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['id'].' : '.$row['title'].'</option>';
    }
    return $output;
}


?>