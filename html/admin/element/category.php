<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

$category_tree = json_encode(treeview_category_array($cms_connect));
?>

<div id="app">
	<div class="content mt-3"> <!-- begining of display content -->
		<span id="alert_action"></span>
		<!---------------------- main table  ------------------------------>
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<div class="row">
								<strong class="card-title">Management</strong>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">

						</div>
					</div>
					<div class="card-body">
						<!------------ header tab ------------>
						<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
							<li class="nav-item">
								<a class="nav-link " href="navigation.php" >Navigations</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " href="slider.php" >Sliders</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " href="tradeshow.php" >Trade Shows</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " href="content.php" >Content Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " href="pressrelease.php" >Press Release</a>
							</li>
							<li class="nav-item">
								<a class="nav-link active" href="#" >Category</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " href="featureproduct.php" >Feature Product</a>
							</li>
						</ul><!------------ end of header tab ------------>
						<hr />

						<category-tree-view />
					</div>

					<!-------------------------------- category associated images ----------------------------->
					<div class="card-body" style="display:none">

						<div class="row form-group">
							<div class="col-md-2" align="right">Selected Images</div>
							<div class="col-md-8">
								<select id="catImage" name="catImage[]" class="selectpicker form-control show-tick" data-live-search="true" multiple data-style="btn-primary" data-actions-box="true" data-selected-text-format="count" data-size="10" disabled>
									<option value="">--- Select One ---</option>
									<?php echo all_image_select_option($cms_connect)?>
								</select>
							</div>
						</div>
						<form id="cat_image_form">
							<div class="row form-group">

								<div class="offset-md-2 col-md-8">
									<table class="table" id="cat_image_table">
										<thead><tr>
											<th width=20%>Image</th>
											<th width=70%>Tags</th>
											<th width=10%></th>
										</tr></thead>
										<tbody id="cat_image_table_body"></tbody>
									</table>
								</div>

							</div>
						</form>

						<div style="text-align:center"> <!-- button submit form -->
							<button type="submit" class="btn btn-outline-info" title="Save" form="cat_image_form"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
							<button type="button" class="btn btn-outline-warning" title="Reset" onClick="window.location.reload()"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
						</div>
					</div>
					<!-------------------------------- end of category associated images ----------------------------->
				</div>
			</div>
		</div>

	</div> <!-- end of display content -->



	<div class="modal fade" id="category_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<form id="category_form">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><span id="category_title"></span></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="catParentID" id="catParentID-test" />
						<div class="form-group" style="display:none">
							<label>Parent Category</label>
							<select id="catParentID" name="catParentID-old" style="display:none" class="selectpicker form-control" data-live-search="true">
								<option value="">--- Select One ---</option>
								<?php echo category_select_option($cms_connect);?>
							</select>
						</div>
						<div class="form-group">
							<label>Category Name</label>
							<input type="text" name="catName" id="catName" class="form-control" />
						</div>
						<div class="form-group">
							<label>Link</label>
							<input type="text" name="catLink" id="catLink" class="form-control" />
						</div>
						<div class="form-group">
							<label>Text</label>
							<textarea rows="2" name="catText" id="catText" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label>Short Description</label>
							<textarea rows="2" name="catShortDescription" id="catShortDescription" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label>Long Description</label>
							<textarea rows="3" name="catLongDescription" id="catLongDescription" class="form-control"></textarea>
						</div>
						<div class="form-group">
							<label>Thumbnail</label>
							<select id="catThumbnail" name="catThumbnail" class="selectpicker form-control" data-live-search="true" >
								<?php echo all_image_select_option($cms_connect)?>
							</select>
						</div>
						<div class="form-group">
							<label>Manufacture</label>
							<select id="catManufacture" name="catManufacture" class="selectpicker form-control" data-live-search="true">
								<option value="">--- Select One ---</option>
								<?php echo brand_list_select_option($cms_connect);?>
							</select>
						</div>
						<input type="hidden" name="category_action" id="category_action">
						<input type="hidden" name="catID" id="catID">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
						<input type="submit" class="btn btn-outline-info" value="Save" />
					</div>
				</div>
			</form>
		</div>
	</div>

</div>


<?php
include '../footer.php';
?>
