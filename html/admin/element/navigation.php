<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

$navigation_tree = json_encode(treeview_navigation_array($cms_connect));
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
                            <strong class="card-title">Site Elements Management</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >Navigations</a>
	                    </li>
	                    <li class="nav-item">
	                        <a class="nav-link" href="slider.php" >Sliders</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link " href="tradeshow.php" >Trade Shows</a>
                        </li>
	                    <li class="nav-item">
	                        <a class="nav-link" href="content.php" >Content Page</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link " href="pressrelease.php" >Press Release</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="category.php" >Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="featureproduct.php" >Feature Product</a>
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
                			<button type="button" id="add_navigation" class="btn btn-outline-info btn-sm" title="Add Navigation"><i class="fa fa-plus"></i>&nbsp; Add</button>
                			<button type="button" id="update_navigation" class="btn btn-outline-warning btn-sm" title="Update Navigation"><i class="fa fa-pencil"></i>&nbsp; Update</button>
                			<button type="button" id="delete_navigation" class="btn btn-outline-danger btn-sm" title="Delete Navigation"><i class="fa fa-trash"></i>&nbsp; Delete</button>  
                		</div>
            		</div>
                	<div class="row form-group">
                		<div class="offset-md-2 col-md-8">
                			<div id="nav_tree"></div>
                		</div>
            		</div>

                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->



<div class="modal fade" id="navigation_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
    	<form id="navigation_form">
	        <div class="modal-content">
	            <div class="modal-header">
	                <h5 class="modal-title"><span id="navigation_title"></span></h5>
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
            		<div class="form-group">
                        <label>Parent Navigation</label>
                        <select id="navParentID" name="navParentID" data-live-search="true" class="selectpicker show-tick" data-width="100%">
                            <option value="">--- Select One ---</option>
                            <?php echo nav_select_option_recursive($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Navigation Name</label>
                        <input type="text" name="navName" id="navName" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input type="text" name="navLink" id="navLink" class="form-control" />
                    </div>
                    <input type="hidden" name="navigation_action" id="navigation_action">
                    <input type="hidden" name="navID" id="navID">
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
		if(localStorage.getItem('navigation_result') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('navigation_result')+'</div>');
            localStorage.removeItem('navigation_result');
        }
	});

    // treeview for navigation tab  
    var treedata = <?php echo $navigation_tree;?>;
    $('#nav_tree').jstree({
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
    // search in navigation tree
    $('#search').on("keyup change", function () {
		$('#nav_tree').jstree(true).search($(this).val())
	});

    $('#nav_tree').bind("dblclick.jstree", function (event) {
        var selected= $('#nav_tree').jstree(true).get_selected(true);
        var navID = selected[0].id;
        var navigation_action = 'nav_update_info';
        $.ajax({
            url:"element_action.php",
            method:"POST",
            data:{navID:navID,navigation_action:navigation_action},
            success:function(mess){
                var navObj = JSON.parse(mess);
                $('#navigation_modal').modal('show');
                $('#navigation_title').html("Add Navigation");
                $('#navigation_form')[0].reset();
                $('#navParentID').selectpicker('val', navObj.parent);
                $('#navName').val(navObj.label);
                $('#navLink').val(navObj.link);
                $('#navID').val(navObj.id);
                $('#navigation_action').val('update_navigation');
            }
        });
    });

    // add new navigation modal
	$('#add_navigation').click(function(){
		$('#navigation_modal').modal('show');
		$('#navigation_title').html("Add Navigation");
		$('#navigation_form')[0].reset();
        $('#navParentID').selectpicker('refresh');
		$('#navigation_action').val('add_navigation');
	});

	// update navigation modal
	$('#update_navigation').click(function(){
		var selected= $('#nav_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 value !!!");
		}
		else{
	        var navID = selected[0].id;
	        var navigation_action = 'nav_update_info';
	        $.ajax({
                url:"element_action.php",
                method:"POST",
                data:{navID:navID,navigation_action:navigation_action},
                success:function(mess){
                	var navObj = JSON.parse(mess);
            		$('#navigation_modal').modal('show');
                    $('#navigation_title').html("Add Navigation");
                    $('#navigation_form')[0].reset();
			        $('#navParentID').selectpicker('val', navObj.parent);
			        $('#navName').val(navObj.label);
			        $('#navLink').val(navObj.link);
			        $('#navID').val(navObj.id);
			        $('#navigation_action').val('update_navigation');
                }
            });
		}
	});

	// submit add/update navigation form
	$('#navigation_form').submit(function(e){
    	event.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type:"post",
            url:"element_action.php",
            data:data,
        	contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
                //console.log(mess);
            	localStorage.setItem("navigation_result", mess);
                window.location.reload(); 
            }
        });
    });

    // delete navigation
	$('#delete_navigation').click(function(){
		var selected= $('#nav_tree').jstree(true).get_selected(true);
		if(selected.length == 0){
			alert("must select 1 value !!!");
		}
		else{
			var navID = selected[0].id;
	        var navigation_action = 'delete_navigation';
	        if(confirm("Confirm to Delete Navigation?" )){
	            $.ajax({
	                url:"element_action.php",
	                method:"POST",
	                data:{navID:navID,navigation_action:navigation_action},
	                success:function(mess){
	                    localStorage.setItem("navigation_result", mess);
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