<?php
include('../../../newconnect.php');
include('../header.php');
include('manage_function.php');


$masterlist_tree = json_encode(treeview_masterlist_array($cms_connect));
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
                            <strong class="card-title">Management -> Pick list</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link" href="category.php" >NS Category</a>
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
                            <a class="nav-link active" href="#" >Masters</a>
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
                			<button type="button" id="add_master" class="btn btn-outline-info btn-sm" title="Add Category"><i class="fa fa-plus"></i>&nbsp; Add</button>
                			<button type="button" id="update_master" class="btn btn-outline-warning btn-sm" title="Update Category"><i class="fa fa-pencil"></i>&nbsp; Update</button>
                			<button type="button" id="delete_master" class="btn btn-outline-danger btn-sm" title="Delete Category"><i class="fa fa-trash"></i>&nbsp; Delete</button>  
                		</div>
            		</div>
                	<div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<div id="master_tree"></div>
                		</div>
            		</div>

                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->



<div class="modal fade" id="master_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
    	<form id="master_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title"><span id="master_title"></span></h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
            		<div class="form-group">
                        <label>Parent List</label>
                        <select id="masterPID" name="masterPID" data-live-search="true" class="selectpicker show-tick" data-width="100%">
                        	<option value="">--- Select One ---</option>
                        	<?php echo master_select_option_recursive($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="masterName" id="masterName" class="form-control" />
                    </div>
                    <input type="hidden" name="master_action" id="master_action">
                    <input type="hidden" name="masterID" id="masterID">
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

	if(localStorage.getItem('master_result') != null){
		$('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('master_result')+'</div>');
		localStorage.removeItem('master_result');
	}


    // treeview for category tab  
    var treedata = <?php echo $masterlist_tree;?>;
    $('#master_tree').jstree({
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
		$('#master_tree').jstree(true).search($(this).val())
	});

    // add new category modal
	$('#add_master').click(function(){
		$('#master_modal').modal('show');
		$('#master_title').html("Add Pick List");
		$('#master_form')[0].reset();
		$("#masterPID").selectpicker("refresh");
		$('#master_action').val('add_master');
	});

    // double click on node to update
    $('#master_tree').bind("dblclick.jstree", function (event) {
        var selected= $('#master_tree').jstree(true).get_selected(true);
        $('#master_modal').modal('show');
        $('#master_title').html("Update Category");
        $('#master_form')[0].reset();
        $('#masterPID').selectpicker('val', selected[0].parent);
        $('#masterName').val(selected[0].text);
        $('#masterID').val(selected[0].id);
        $('#master_action').val('update_master');
    });
    
	// update category modal
	$('#update_master').click(function(){
		var selected= $('#master_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
    		$('#master_modal').modal('show');
			$('#master_title').html("Update Category");
	        $('#master_form')[0].reset();
	        $('#masterPID').selectpicker('val', selected[0].parent);
	        $('#masterName').val(selected[0].text);
	        $('#masterID').val(selected[0].id);
	        $('#master_action').val('update_master');
		}
		
	});

	// submit add/update category form
	$('#master_form').submit(function(e){
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
            	localStorage.setItem("master_result", mess);
                window.location.reload(); 
            }
        });
    });

    // delete category
	$('#delete_master').click(function(){
		var selected= $('#master_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 category !!!");
		}
		else{
			var masterID = selected[0].id;
	        var master_action = 'delete_master';
	        if(confirm("Confirm to Delete Pick List?" )){
	            $.ajax({
	                url:"manage_action.php",
	                method:"POST",
	                data:{masterID:masterID,master_action:master_action},
	                success:function(mess){
	                    localStorage.setItem("master_result", mess);
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