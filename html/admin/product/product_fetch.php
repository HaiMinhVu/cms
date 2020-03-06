<?php
include('../../../newconnect.php');

if(isset($_POST) && $_POST['action'] == 'fetch_product'){
    $query = '';

    $output = array();
    $query .= "
        SELECT * FROM product
        WHERE 
        ";

    if($_POST['inactive'] == "false"){
        $active = " status = 1 AND ";
    }
    else{
        $active = "";
    }

    $query .= $active;

    if(isset($_POST["search"]["value"]))
    {
        $query .= '(sku LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR Name LIKE "%'.$_POST["search"]["value"].'%" ) ';
    }

    if(isset($_POST['order']))
    {
        $query .= ' ORDER BY ';
        for($i = 0; $i < count($_POST['order']); $i++){
            $orderby = $_POST['order'][$i]['column'] +1;
            $query .= $orderby.' '.$_POST['order'][$i]['dir'].' ';
            if($i != (count($_POST['order']) - 1)){
                $query .= ', ';
            }
        }
    }
    else
    {
        $query .= "ORDER BY id DESC ";
    }
    $totalrecord = $query;
    if($_POST['length'] != -1)
    {
        $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $statement = $cms_connect->query($query);
    $data = array();
    $filtered_rows = mysqli_num_rows($statement);
    while($row = $statement->fetch_assoc())
    {
        $sub_array = array();
        $sub_array['id'] = $row['id'];
        $sub_array['sku'] = '<a href="product_general.php?id='.$row["id"].'" class="btn btn-link" title="Edit">'.$row['sku'].'</a>';
        $sub_array['name'] = $row['Name'];
        $sub_array['action'] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-outline-danger btn-sm delete" data-status="'.$row["status"].'" title="Delete"><span class="fa fa-trash-o"></span></button>';
        $data[] = $sub_array;
    }

    function get_total_all_records($cms_connect, $totalrecord)
    {
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