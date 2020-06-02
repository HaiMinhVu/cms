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
		<div class="tab-pane active" style="display: none"> 
			<form method="POST" id="product_image_form" enctype="multipart/form-data">
				<div class="row form-group">
	        		<div class="col-md-2" align="right">Files</div>
	        		<div class="col-md-8">
	            		<product-image-selector product-id="<?= intval($productid) ?>" />
					</div>
	    		</div>
				<div class="row form-group">
	        		<div class="col-md-2" align="right">File List</div>
	        		<div class="col-md-8">
	            		<select id="imageids" class="selectpicker form-control show-tick" data-live-search="true" multiple data-style="btn-primary" data-actions-box="true" data-selected-text-format="count" data-size="15">
					        <?php echo all_image_select_option($cms_connect)?>
					    </select>
					</div>
	    		</div>
				<div class="row form-group">
	    			<div class="col-md-2" align="right">Associated Images</div>
	        		<div class="col-md-8">
	        			<table class="table" id="image_table">
		                    <thead><tr>
		                            <th width=20%>Image</th>
		                            <th width=20%>Name</th>
		                            <th width=40%>Description</th>
		                            <th width=10%>Main Img</th>
		                            <th width=10%>Disassociate</th>
		                    </tr></thead>
		                    <tbody id="image_table_body"></tbody>
		                </table>
	        		</div>
	        	</div>
	        	
	    	</form>
	    	<div style="text-align:center"> <!-- button submit form -->
		    	<button type="submit" class="btn btn-outline-info" title="Save" form="product_image_form"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
		    	<button type="button" class="btn btn-outline-warning" title="Reset" onClick="window.location.reload()"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
		    </div>
		</div> 
		<!-- end image tab -->
	</div> <!-- end tab content-->
</div> <!-- end of display content -->

<?php
include('subfooter.php');
include('../footer.php');
?>