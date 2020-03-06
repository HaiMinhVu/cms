<?php
include('../../../newconnect.php');
include('../header.php');
include('manage_function.php');

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
                            <strong class="card-title">Management -> Sites</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         <button type="button" id="add_site" class="btn btn-outline-info btn-sm" title="Add New Site"><i class="fa fa-plus"></i>&nbsp; Add</button>
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link " href="category.php" >NS Category</a>
	                    </li>
	                    <li class="nav-item">
	                        <a class="nav-link" href="spec.php" >Specs</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#" >Sites</a>
                        </li>
	                    <li class="nav-item">
	                        <a class="nav-link" href="file.php" >Files</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="masterlist.php" >Masters</a>
                        </li>
	                </ul>
                    <!------------ end of header tab ------------>
                    
	                <!------------ begin of content ------------>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="site_table" class="table table-bordered table-hover" width="100%">
                                <thead><tr>
                                    <th width=10%>Name</th>
                                    <th width=40%>Url</th>
                                    <th width=40%>Work Directory</th>
                                    <th width=10%>Action</th>
                                </tr></thead>
                            </table>
                        </div>
                    </div>
                    <!------------ end of of content ------------>
                
                </div>
            </div>
        </div>
    </div>
</div> <!-- end of display content -->


<!-- modal -->
<div class="modal fade" id="site_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <form id="site_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="site_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="siteName" id="siteName" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Link</label>
                        <input type="text" name="siteLink" id="siteLink" class="form-control" />
                    </div>
                    <input type="hidden" name="site_action" id="site_action">
                    <input type="hidden" name="siteID" id="siteID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-outline-info" value="Save" />
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){
	fetch_data();

	if(localStorage.getItem('site_result') != null){
		$('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('site_result')+'</div>');
		localStorage.removeItem('site_result');
	}

	function fetch_data(){
        var site_action = 'fetch_site';
        $('#site_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "order":[],
            "ajax":{
                url:"manage_fetch.php",
                type:"POST",
                data:{
                    site_action:site_action
                }
            },
            "columnDefs":[{
                "targets":[0,1,2,3],
                "orderable":false,
                },
            ],
            'columns': [
                { 'data': 'name' },
                { 'data': 'url' },
                { 'data': 'wd' },
                { 'data': 'action' }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {"infoFiltered": ""},
        });
    }

        


    // add new site modal
    $('#add_site').click(function(){
        $('#site_modal').modal('show');
        $('#site_title').html("Add Site");
        $('#site_form')[0].reset();
        $('#site_action').val('add_site');
    });

    // update site modal
    $(document).on('click', '.update', function(){
        var siteID = $(this).attr("id");
        var site_action = 'site_update_info';
        $.ajax({
            url:"manage_action.php",
            method:"POST",
            data:{siteID:siteID,site_action:site_action},
            success:function(mess){
                var siteObj = JSON.parse(mess);
                $('#site_modal').modal('show');
                $('#site_title').html("Update Site");
                $('#site_form')[0].reset();

                $('#siteName').val(siteObj.label);
                $('#siteLink').val(siteObj.url);
                $('#siteID').val(siteObj.id);
                $('#site_action').val('update_site');
            }
        });
    });

    // submit add/update site form
    $('#site_form').submit(function(e){
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
                localStorage.setItem("site_result", mess);
                window.location.reload(); 
            }
        });
    });


    // delete site 
    $(document).on('click', '.delete', function(){
        var siteID = $(this).attr("id");
        var site_action = 'delete_site';
        if(confirm("Confirm to Delete Category?" )){
            $.ajax({
                url:"manage_action.php",
                method:"POST",
                data:{siteID:siteID,site_action:site_action},
                success:function(mess){
                    localStorage.setItem("site_result", mess);
                    window.location.reload(); 
                }
            });
        }
        else{
            return false;
        }
        
    });
});
</script>

<?php
include '../footer.php';
?>

