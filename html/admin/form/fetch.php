<?php
include('../../../newconnect.php');

if(isset($_POST) && $_POST['action'] == 'fetch_product'){
    $query = '';

    $output = array();
    $query .= "
        SELECT pr.id, pr.first_name, p.sku, pr.DealerStore, pr.price_paid, pr.date_purchased, pr.form_site, pr.last_name
        FROM form_product_registration pr LEFT JOIN product p ON p.id = pr.product_id
                                        LEFT JOIN file_manager fm ON fm.ID = pr.proof_of_purchase
        WHERE 
        ";

    /*if($_POST['inactive'] == "false"){
        $active = " status = 1 AND ";
    }
    else{
        $active = "";
    }

    $query .= $active;*/

    if(isset($_POST["search"]["value"]))
    {
        $query .= ' (sku LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR CONCAT(first_name, " ", last_name) LIKE "%'.$_POST["search"]["value"].'%" ';
        $query .= 'OR DealerStore LIKE "%'.$_POST["search"]["value"].'%" ) ';
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
        $sub_array[] = $row['id'];
        $sub_array[] = $row['first_name'].' '.$row['last_name'];
        $sub_array[] = $row['sku'];
        $sub_array[] = $row['DealerStore'];
        $sub_array[] = $row['price_paid'];
        $sub_array[] = $row['date_purchased'];
        $sub_array[] = $row['form_site'];
        $sub_array[] = '<button type="button" name="view" id="'.$row["id"].'" class="btn btn-outline-primary btn-sm delete" title="View"><span class="fa fa-eye"></span></button>';
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