<?php
include('../../../newconnect.php');

/*************************** fetch data for file list ****************************/
if(isset($_POST) && $_POST['user_action'] == 'fetch_user'){
    $query = '';
    $output = array();
    $query .= " SELECT u.*, r.name FROM users u LEFT JOIN roles r ON r.id = u.role_id  
                WHERE ";


    if($_POST['inactive'] != "true"){
        $inactive = " u.status = 1 AND ";
    }
    else{
        $inactive = "";
    }
    $query .= $inactive;

    if(isset($_POST["search"]["value"])){
        $query .= ' ( username LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= '  OR email LIKE "%'.$_POST["search"]["value"].'%" ) ';
    }

    $query .= "ORDER BY r.id, u.id ";

    $totalrecord = $query;

    if($_POST['length'] != -1){
        $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }
    else{
        $query .= 'LIMIT 10 ';
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    while($row = $statement->fetch_assoc()){
        $sub_array = array();
        $sub_array['username'] = $row['username'];
        $sub_array['email'] = $row['email'];
        $sub_array['role'] = $row['name'];
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