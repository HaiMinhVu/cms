<?php
include('../../../newconnect.php');
include('../header.php');
include('product_function.php');

$temp = $_GET['id'];
if(!preg_match('#[^0-9]#',$temp)){
    $productid = $temp;
}
else{
    //$productid = "";
    header('location:product.php');
}

$productinfo = product_info_by_id($cms_connect, $productid);
$associatedIdArr = associated_imgs_by_product_id($cms_connect, $productid);
$main_img_id = $productinfo['main_img_id'];

include('subheader.php');
?>
	<div class="tab-content" id="app">
		<product-image-selector product-id="<?= intval($productid) ?>" />
	</div> <!-- end tab content-->
</div> <!-- end of display content -->

<?php
include('subfooter.php');
include('../footer.php');
?>