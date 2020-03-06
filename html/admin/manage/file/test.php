<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../../../newconnect.php');
include_once "../../../rs/include/db.php";
include('header.php');

function rs_file_select_option($rs_connect){
    $sql = "SELECT * FROM resource WHERE ref > 0";
    $result = $rs_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $id = $row['ref'];
        $imgUrl = get_resource_path($id, false, 'pre');
        $output .='<option data-content="<img height=\'80\' src=\''.$imgUrl.'\'> '.$row['field8'].'.'.$row['file_extension'].'" value = "'.$row['ref'].'"></option>';
        //$output .='<option value = "'.$row['ref'].'">'.$row['field8'].'.'.$row['file_extension'].'</option>';  
    }
    return $output;
}

?>

<div class="content mt-3">


                    <select name="rs_file_id" id="rs_file_id" class="selectpicker form-control show-tick" data-live-search="true" required>
                        <option value="">--- Select One ---</option>
                        <?php echo rs_file_select_option($rs_connect);?>
                    </select>


</div> <!-- end of display content -->




<?php
include '../../footer.php';
?>
