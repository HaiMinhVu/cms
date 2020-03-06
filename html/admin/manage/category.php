<?php
include('../../../newconnect.php');
include('../header.php');
include('manage_function.php');

$category_tree = json_encode(treeview_category_array($cms_connect));
?>

<div class="content mt-3"> <!-- begining of display content -->
<span id="alert_action"></span>
<!---------------------- main table  ------------------------------>
	<div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <strong class="card-title">Management -> Category</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >NS Category</a>
	                    </li>

	                    <li class="nav-item">
	                        <a class="nav-link" href="spec.php" >Specs</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link " href="site.php" >Sites</a>
                        </li>
	                    <li class="nav-item">
	                        <a class="nav-link" href="file.php" >Files</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="masterlist.php" >Masters</a>
                        </li>
	                </ul><!------------ end of header tab ------------>

	                <!------------ begin of content ------------>
                    <div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<input type="text" class="form-control" placeholder="Search in tree" id="search" />
                		</div>
            		</div>
            		<div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<button type="button" id="add_category" class="btn btn-outline-info btn-sm" title="Add Category"><i class="fa fa-plus"></i>&nbsp; Add</button>
                			<button type="button" id="update_category" class="btn btn-outline-warning btn-sm" title="Update Category"><i class="fa fa-pencil"></i>&nbsp; Update</button>
                			<button type="button" id="delete_category" class="btn btn-outline-danger btn-sm" title="Delete Category"><i class="fa fa-trash"></i>&nbsp; Delete</button>  
                		</div>
            		</div>
                	<div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<div id="cat_tree"></div>
                		</div>
            		</div>
                </div>

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
            		<div class="form-group">
                        <label>Parent Category</label>
                        <select id="catParentID" name="catParentID" class="selectpicker form-control" data-live-search="true">
                        	<option value="">--- Select One ---</option>
                        	<?php echo category_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category Name</label>
                        <input type="text" name="catName" id="catName" class="form-control" />
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
                        <label>Content</label>
                        <textarea rows="3" name="catContent" id="catContent" class="form-control"></textarea>
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



<?php
include '../footer.php';
?>

<script type="text/javascript">

$(document).ready(function(){
	$(window).on("load", function(){
		// display result after updating
		if(localStorage.getItem('category_result') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('category_result')+'</div>');
            localStorage.removeItem('category_result');
        }
	});

    // treeview for category tab  
    var treedata = <?php echo $category_tree;?>;
    $('#cat_tree').jstree({
        'core': {
            'data': treedata,
            'check_callback' : true,
        },
        'plugins': ["search"],
        'search': {
            'show_only_matches': true,
            'show_only_matches_children': true
        },
    });
    // search in category tree
    $('#search').on("keyup change", function () {
		$('#cat_tree').jstree(true).search($(this).val())
	});

    // add new category modal
	$('#add_category').click(function(){
		$('#category_modal').modal('show');
		$('#category_title').html("Add Category");
		$('#category_form')[0].reset();
		$("#catParentID").selectpicker("refresh");
		$('#category_action').val('add_category');
	});

    // double click on node to update
    $('#cat_tree').bind("dblclick.jstree", function (event) {
        var selected= $('#cat_tree').jstree(true).get_selected(true);
        var catID = selected[0].id;
        var category_action = 'cat_update_info';
        $.ajax({
            url:"manage_action.php",
            method:"POST",
            data:{catID:catID,category_action:category_action},
            success:function(mess){
                var catObj = JSON.parse(mess);
                $('#category_modal').modal('show');
                $('#category_title').html("Update Category");
                $('#category_form')[0].reset();

                $('#catParentID').selectpicker('val', catObj.parent);
                $('#catName').val(catObj.label);
                $('#catShortDescription').val(catObj.short_description);
                $('#catLongDescription').val(catObj.long_description);
                $('#catContent').val(catObj.content);
                $('#catID').val(catObj.id);
                $('#category_action').val('update_category');
            }
        });
    });


	// update category modal
	$('#update_category').click(function(){
		var selected= $('#cat_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
	        var catID = selected[0].id;
	        var category_action = 'cat_update_info';
	        $.ajax({
                url:"manage_action.php",
                method:"POST",
                data:{catID:catID,category_action:category_action},
                success:function(mess){
                	var catObj = JSON.parse(mess);
            		$('#category_modal').modal('show');
					$('#category_title').html("Update Category");
			        $('#category_form')[0].reset();

			        $('#catParentID').selectpicker('val', catObj.parent);
			        $('#catName').val(catObj.label);
                    $('#catShortDescription').val(catObj.short_description);
                    $('#catLongDescription').val(catObj.long_description);
                    $('#catContent').val(catObj.content);
			        $('#catID').val(catObj.id);
			        $('#category_action').val('update_category');
                }
            });
		}
		
	});

	// submit add/update category form
	$('#category_form').submit(function(e){
    	event.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type:"post",
            url:"manage_action.php",
            data:data,
        	contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
            	localStorage.setItem("category_result", mess);
                window.location.reload(); 
            }
        });
    });

    // delete category
	$('#delete_category').click(function(){
		var selected= $('#cat_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
			var catID = selected[0].id;
	        var category_action = 'delete_category';
	        if(confirm("Confirm to Delete Category?" )){
	            $.ajax({
	                url:"manage_action.php",
	                method:"POST",
	                data:{catID:catID,category_action:category_action},
	                success:function(mess){
	                    localStorage.setItem("category_result", mess);
                		window.location.reload(); 
	                }
	            });
	        }
	        else{
	            return false;
	        }
		}
	});  
});

</script>