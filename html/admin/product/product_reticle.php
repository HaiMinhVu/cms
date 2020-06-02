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
$associatedReticleArr = associated_reticle_by_product_id($cms_connect, $productid);

include('subheader.php');
?>

    <div class="tab-content" id="app">
        <product-reticle-selector product-id="<?= intval($productid) ?>" media-type="reticle" />
    </div> <!-- end tab content-->
</div> <!-- end of display content -->

<?php
include('subfooter.php');
include('../footer.php');
?>