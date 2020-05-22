<?php
include('../../../newconnect.php');
include('../header.php');
include('manage_function.php');


$spec_tree = json_encode(treeview_spec_array($cms_connect));

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
                            <strong class="card-title">Management -> Specs</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link " href="category.php" >NS Category</a>
	                    </li>
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >Specs</a>
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
                			<button type="button" id="add_spec" class="btn btn-outline-info btn-sm" title="Add Category"><i class="fa fa-plus"></i>&nbsp; Add</button>
                			<button type="button" id="update_spec" class="btn btn-outline-warning btn-sm" title="Update Category"><i class="fa fa-pencil"></i>&nbsp; Update</button>
                			<button type="button" id="delete_spec" class="btn btn-outline-danger btn-sm" title="Delete Category"><i class="fa fa-trash"></i>&nbsp; Delete</button>  
                		</div>
            		</div>
                	<div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<div id="spec_tree"></div>
                		</div>
            		</div>

                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->


<!-- add/update modal -->
<div class="modal fade" id="spec_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
    	<form id="spec_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title"><span id="spec_title"></span></h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
            		<div class="form-group">
                        <label>Parent Spec</label>
                        <select id="specParentID" name="specParentID" data-live-search="true" class="selectpicker show-tick" data-width="100%">
                        	<option value="">--- Select One ---</option>
                        	<?php echo spec_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Spec Name <span style="color: red">*</span></label>
                        <input type="text" name="specName" id="specName" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Suffix</label>
                        <input type="text" name="specSuffix" id="specSuffix" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Context</label>
                        <input type="text" name="specContext" id="specContext" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Measure</label>
                        <input type="text" name="specMeasure" id="specMeasure" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Measure Type</label>
                        <input type="text" name="specMeasureType" id="specMeasureType" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>System</label>
                        <input type="text" name="specSystem" id="specSystem" class="form-control" />
                    </div>
                    <input type="hidden" name="spec_action" id="spec_action">
                    <input type="hidden" name="specID" id="specID">
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
		// display result after updating
		if(localStorage.getItem('spec_result') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('spec_result')+'</div>');
            localStorage.removeItem('spec_result');
        }

	// treeview for category tab  
    var treedata = <?php echo $spec_tree;?>;

    $('#spec_tree').jstree({
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
    // search in spec tree
    $('#search').on("keyup change", function () {
		$('#spec_tree').jstree(true).search($(this).val())
	});


	// add spec modal
	$('#add_spec').click(function(){
		$('#spec_modal').modal('show');
		$('#spec_title').html("Add Spec");
		$('#spec_form')[0].reset();
		$("#specParentID").selectpicker("refresh");
		$('#spec_action').val('add_spec');
	});

    // double click on node to update
    $('#spec_tree').bind("dblclick.jstree", function (event) {
        var selected= $('#spec_tree').jstree(true).get_selected(true);
        var specID = selected[0].id;
        var spec_action = 'spec_update_info';
        $.ajax({
            url:"manage_action.php",
            method:"POST",
            data:{specID:specID,spec_action:spec_action},
            success:function(mess){
                var specObj = JSON.parse(mess);
                $('#spec_modal').modal('show');
                $('#spec_title').html("Update Category");
                $('#spec_form')[0].reset();
                $('#specParentID').selectpicker('val', selected[0].parent);
                $('#specName').val(specObj.name);
                $('#specID').val(specObj.id);
                $('#specSuffix').val(specObj.unit_suffix);
                $('#specContext').val(specObj.context);
                $('#specMeasure').val(specObj.measures);
                $('#specMeasureType').val(specObj.measure_type);
                $('#specSystem').val(specObj.system);
                $('#spec_action').val('update_spec');
            }
        });
    });
    
	// update category modal
	$('#update_spec').click(function(){
		var selected= $('#spec_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
	        var specID = selected[0].id;
	        var spec_action = 'spec_update_info';
	        $.ajax({
                url:"manage_action.php",
                method:"POST",
                data:{specID:specID,spec_action:spec_action},
                success:function(mess){
                	var specObj = JSON.parse(mess);
            		$('#spec_modal').modal('show');
					$('#spec_title').html("Update Category");
			        $('#spec_form')[0].reset();
			        $('#specParentID').selectpicker('val', selected[0].parent);
			        $('#specName').val(specObj.name);
			        $('#specID').val(specObj.id);
			        $('#specSuffix').val(specObj.unit_suffix);
			        $('#specContext').val(specObj.context);
			        $('#specMeasure').val(specObj.measures);
			        $('#specMeasureType').val(specObj.measure_type);
			        $('#specSystem').val(specObj.system);
			        $('#spec_action').val('update_spec');
                }
            });
		}
		
	});

	// submit add/update spec form
	$('#spec_form').submit(function(e){
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
            	localStorage.setItem("spec_result", mess);
                window.location.reload(); 
            }
        });
    });

    // delete spec
	$('#delete_spec').click(function(){
		var selected= $('#spec_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
			var specID = selected[0].id;
	        var spec_action = 'delete_spec';
	        if(confirm("Confirm to Delete Category?" )){
	            $.ajax({
	                url:"manage_action.php",
	                method:"POST",
	                data:{specID:specID,spec_action:spec_action},
	                success:function(mess){
	                    localStorage.setItem("spec_result", mess);
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