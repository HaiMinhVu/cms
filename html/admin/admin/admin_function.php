<?php

function user_role_select_option($cms_connect){
    $sql = "SELECT id, name FROM roles ";
    $result = $cms_connect->query($sql);
    $output = '';
    foreach ($result as $row){
        $output .='<option value = "'.$row['id'].'">'.$row['name'].'</option>';
    }
    return $output;
}

?>