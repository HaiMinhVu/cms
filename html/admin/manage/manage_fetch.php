<?php
include('../../../newconnect.php');

/*************************** fetch data for file list ****************************/
if(isset($_POST) && $_POST['img_action'] == 'fetch_file'){
    $query = '';
    $output = array();
    $query .= " SELECT fm.ID, fm.file_name, sl.id AS siteID, sl.label AS siteName, sl.url AS siteUrl, ml1.id AS typeID, ml1.description AS typeName, ml2.id AS folderID, ml2.description AS folderName
                FROM file_manager fm LEFT JOIN site_list sl ON sl.id = fm.site_id
                                    LEFT JOIN master_list ml1 ON ml1.id = fm.file_type_id
                                    LEFT JOIN master_list ml2 ON ml2.id = fm.site_folder_id
                WHERE ";

    if($_POST['file_type'] != ""){
        $file_type = " ml1.id = ".$_POST['file_type']." AND ";
    }
    else{
        $file_type = " ml1.id = 10 AND ";
    }
    $query .= $file_type;

    if(isset($_POST["search"]["value"])){
        $query .= ' (file_name LIKE "%'.$_POST["search"]["value"].'%") ';
    }

    $query .= "ORDER BY fm.ID ";

    $totalrecord = $query;

    if($_POST['length'] != -1){
        $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    while($row = $statement->fetch_assoc()){
        $url = $row['siteUrl'].$row['folderName'].$row['file_name'];
        if($row['typeID'] == 10){
            $name = '<a href="'.$url.'" data-toggle="lightbox" data-max-height="600" class="btn btn-link"><img src="'.$url.'" height="30"> '.$row['file_name'].'</a>';
        }
        else{
            $name = '<a href="'.$url.'" target="_blank" class="btn btn-link">'.$row['file_name'].'</a>';
        }

        $sub_array = array();
        $sub_array['id'] = $row['ID'];
        $sub_array['name'] = $name;
        $sub_array['type'] = $row['typeName'];
        $sub_array['site'] = $row['siteName'];
        /*$sub_array['action'] = '<button type="button" name="update" id="'.$row["ID"].'" class="btn btn-outline-warning btn-sm update" title="Edit File"><span class="fa fa-pencil"></span></button>
                <button type="button" name="delete" id="'.$row["ID"].'" class="btn btn-outline-danger btn-sm delete" title="Delete File"><span class="fa fa-trash"></span></button>';*/
        $sub_array['action'] = '<button type="button" name="delete" id="'.$row["ID"].'" class="btn btn-outline-danger btn-sm delete" title="Delete File"><span class="fa fa-trash"></span></button>';
        $data[] = $sub_array;
    }

    function get_total_all_records($cms_connect, $totalrecord){
        $statement = $cms_connect->query($totalrecord);
        return mysqli_num_rows($statement);
    }

    $output = array(
        "recordsTotal"      =>  $filtered_rows,
        "recordsFiltered"   =>  get_total_all_records($cms_connect, $totalrecord),
        "data"              =>  $data
    );

    echo json_encode($output);
}


/*************************** fetch data for site list ****************************/
if(isset($_POST) && $_POST['site_action'] == 'fetch_site'){
    $query = '';
    $output = array();
    $query .= " SELECT * FROM site_list WHERE ";

    if(isset($_POST["search"]["value"])){
        $query .= ' (label LIKE "%'.$_POST["search"]["value"].'%") ';
    }

    $query .= " ORDER BY id ";

    $totalrecord = $query;

    if($_POST['length'] != -1){
        $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    print_r($filtered_rows);
    exit();
    while($row = $statement->fetch_assoc()){
        $sub_array = array();
        $sub_array['name'] = utf8_encode($row['label']);
        $sub_array['url'] = '<a href="'.$row['url'].'" target="_blank" class="btn btn-link">'.$row['url'].'</a>';
        $sub_array['wd'] = $row['wd'];
        $sub_array['action'] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-outline-warning btn-sm update" title="Edit File"><span class="fa fa-pencil"></span></button>
                <button type="button" name="delete" id="'.$row["id"].'" class="btn btn-outline-danger btn-sm delete" title="Delete File"><span class="fa fa-trash"></span></button>';
        $data[] = $sub_array;
    }

    function get_total_all_records($cms_connect, $totalrecord){
        $statement = $cms_connect->query($totalrecord);
        return mysqli_num_rows($statement);
    }

    $output = array(
        "recordsTotal"      =>  $filtered_rows,
        "recordsFiltered"   =>  get_total_all_records($cms_connect, $totalrecord),
        "data"              =>  $data
    );

    echo json_encode($output);
}
?>