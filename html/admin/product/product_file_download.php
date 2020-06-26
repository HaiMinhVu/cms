<?php
include('../../../newconnect.php');
include('../header.php');
include('product_function.php');

$temp = $_GET['id'];
if(!preg_match('#[^0-9]#',$temp)){
    $productid = $temp;
}
else{
    header('location:product.php');
}

$productinfo = product_info_by_id($cms_connect, $productid);
$associatedManualArr = associated_manual_by_product_id($cms_connect, $productid);
include('subheader.php');

?>

<div class="content mt-3" id="app"> <!-- begining of display content -->
    <div class="container mx-auto">
	       <file-selector file-type="download" title="Downloads" product-id="<?= $_GET['id'] ?>" />
    </div>
</div> <!-- end of display content -->


<script type="text/javascript">
    $(document).ready(function(){
    	$('#product_file').addClass('active');
    });
</script>

<?php
include('subfooter.php');
include('../footer.php');
?>
