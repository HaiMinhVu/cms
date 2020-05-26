<?php
include('../../../newconnect.php');

/*************************** fetch data for TRADE SHOW ****************************/
if(isset($_POST) && $_POST['tradeshow_action'] == 'fetch_show'){
    $query = '';
    $output = array();
    $query .= " SELECT ts.id, ts.ts_show, ts.ts_date_from, ts.ts_date_to, ts.location, ts.booth, ts.ts_link, concat(sl.url, ml.description, fm.file_name) as url
                FROM tradeshow ts LEFT JOIN file_manager fm ON fm.ID = ts.file_id
                                LEFT JOIN site_list sl ON sl.id = fm.site_id
                                LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
                WHERE ";

    if($_POST['inactive'] == "false"){
        $active = " ts_status = 1 AND ";
    }
    else{
        $active = "";
    }

    if($_POST['brandID'] != ""){
        $brand = " brand_id = ".$_POST['brandID']." AND ";
    }
    else{
        $brand = "";
    }

    $query .= $active.$brand;

    if(isset($_POST["search"]["value"])){
        $query .= ' (ts_show LIKE "%'.$_POST["search"]["value"].'%") ';
    }

    $query .= " ORDER BY ts.id DESC ";

    $totalrecord = $query;

    if($_POST['length'] != -1){
        $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    else{
        $query .= ' LIMIT 10 ';
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    while($row = $statement->fetch_assoc()){
        $sub_array = array();
        $sub_array['img'] = '<a href="'.$row['url'].'" data-toggle="lightbox" data-max-height="600"><img src="'.$row['url'].'" height="50" ></a>';
        $sub_array['show'] = $row['ts_show'];
        $sub_array['from'] = $row['ts_date_from'];
        $sub_array['to'] = $row['ts_date_to'];
        $sub_array['location'] = $row['location'];
        $sub_array['booth'] = $row['booth'];
        $sub_array['link'] = $row['ts_link'];
        $sub_array['action'] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-outline-warning btn-sm update" title="Edit Trashow"><span class="fa fa-pencil"></span></button>
                <button type="button" name="delete" id="'.$row["id"].'" class="btn btn-outline-danger btn-sm delete" title="Delete Trashow"><span class="fa fa-trash"></span></button>';
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


/*************************** fetch data for SLIDER ****************************/
if(isset($_POST) && $_POST['slider_action'] == 'fetch_slider'){
    $query = '';
    $output = array();
    $query .= " SELECT si.*, concat(sl.url, ml.description, fm.file_name) as url
                FROM slider_image si LEFT JOIN file_manager fm ON fm.ID = si.file_id
                                    LEFT JOIN site_list sl ON sl.id = fm.site_id
                                    LEFT JOIN master_list ml ON ml.id = fm.site_folder_id
                WHERE active = 1 AND si.pid = ".$_POST['pageID'];

    $query .= " ORDER BY si.slider_order ";

    $totalrecord = $query;

    if($_POST['length'] != -1){
        $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    while($row = $statement->fetch_assoc()){
        $sub_array = array();
        $sub_array['img'] = '<a href="'.$row['url'].'" data-toggle="lightbox" data-max-height="600"><img src="'.$row['url'].'" height=100 /></a>';
        $sub_array['link'] = $row['link_value'];
        $sub_array['text'] = $row['text'];
        $sub_array['order'] = $row['slider_order'];
        $sub_array['action'] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-outline-warning btn-sm update" title="Edit Trashow"><span class="fa fa-pencil"></span></button>
                                <button type="button" name="delete" id="'.$row["id"].'" class="btn btn-outline-danger btn-sm delete" title="Delete Slider"><span class="fa fa-trash"></span></button>';
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