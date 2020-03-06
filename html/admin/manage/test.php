<?php

$local_file = '/home/sellmar1/public_html/KJ/images/11.jpg';
$remote_file = "public_html/KJ/images/11.jpg";

$ftp_server = 'ftp.kjrests.com'; 
$ftp_user_name = 'cms@kjrests.com';
$ftp_user_pass = 'bvhfur84BVHFUR*$';


$conn_id = ftp_connect($ftp_server);


$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
    echo "successfully uploaded $file\n";
    exit;
} 
else {
    echo "There was a problem while uploading $file\n";
    exit;
}
// close the connection
ftp_close($conn_id);

?>





