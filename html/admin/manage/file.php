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
                            <strong class="card-title">Management -> File</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                        <button type="button" style="display:none" class="btn btn-outline-info btn-sm" title="Add New File" onclick="location.href='file_add.php'"><i class="fa fa-plus"></i>&nbsp; Add</button>
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link " href="category.php" >NS Category</a>
	                    </li>
	                    <li class="nav-item">
	                        <a class="nav-link " href="spec.php" >Specs</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link " href="site.php" >Sites</a>
                        </li>
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >Files</a>
	                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="masterlist.php" >Masters</a>
                        </li>
	                </ul><!------------ end of header tab ------------>

	                <!------------ begin of content ------------>
                    <div id="app" class="row">
                        <media-browser />
                        <div class="table-responsive" style="display: none">
                            
                            
                            <table id="image_table" class="table table-bordered table-hover" width="100%">
                                <thead><tr>
                                    <th width=5%>ID</th>
                                    <th width=45%>Name</th>
                                    <th width=20%><select name="file_type" id="file_type" class="form-control">
                                            <?php echo file_type_select_option($cms_connect);?>
                                    </select></th>
                                    <th width=20%>Site</th>
                                    <th width=10%>Action</th>
                                </tr></thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->


<!-- file modal -->
<div class="modal fade" id="file_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <form id="file_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="file_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label><strong>File Type</strong></label> <span style="color: red">*</span>
                        <select name="file_type_modal" id="file_type_modal" class="form-control" required>
                            <?php echo file_type_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Site Folder</strong></label> <span style="color: red">*</span>
                        <select name="site_folder_modal" id="site_folder_modal" class="form-control" required>
                            <?php echo site_folder_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Site</strong></label> <span style="color: red">*</span>
                        <select name="site_modal" id="site_modal" class="form-control" required>
                            <option value="">--- Select One ---</option>
                            <?php echo site_list_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>File Name</strong></label > (Do not include file type) <span style="color: red">*</span>
                        <input type="text" name="file_name_modal" id="file_name_modal" class="form-control" />
                    </div>

                    <input type="hidden" name="file_action" id="file_action">
                    <input type="hidden" name="fileID" id="fileID">
                    
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

    function fetch_data(file_type = 10){
        $('#image_table').DataTable().destroy();
        var img_action = 'fetch_file';
        $('#image_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "order":[],
            "ajax":{
                url:"manage_fetch.php",
                type:"POST",
                data:{
                    img_action:img_action, file_type:file_type
                }
            },
            "columnDefs":[{
                "targets":[0,1,2,3,4],
                "orderable":false,
                },
            ],
            'columns': [
                { 'data': 'id' },
                { 'data': 'name' },
                { 'data': 'type' },
                { 'data': 'site' },
                { 'data': 'action' }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {"infoFiltered": ""},
        });
    }
    
    $(window).on("load", function(){
        fetch_data();
    });
    $('#file_type').change(function(){
        var file_type = $('#file_type').val()
        fetch_data(file_type);
    });


    $(document).on('click', '.update', function(){
        var fileID = $(this).attr("id");
        var file_action = 'file_update_info';
        $.ajax({
            url:"manage_action.php",
            method:"POST",
            data:{fileID:fileID,file_action:file_action},
            success:function(mess){
                var fileObj = JSON.parse(mess);
                $('#file_modal').modal('show');
                $('#file_title').html("Update File");
                $('#file_form')[0].reset();
                $('#fileupload').css('display', 'none');

                $('#file_type_modal').selectpicker('val', fileObj.file_type_id);
                $('#site_folder_modal').selectpicker('val', fileObj.site_folder_id);
                $('#site_modal').selectpicker('val', fileObj.site_id);
                $('#file_name_modal').val(fileObj.file_name);
                $('#fileID').val(fileObj.ID);
                $('#file_action').val('update_file');
            }
        });
    });

    // submit add/update category form
    $('#file_form').submit(function(e){
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
                console.log(mess);
                //localStorage.setItem("file_result", mess);
                //window.location.reload(); 
            }
        });
    });

    $(document).on('click', '.delete', function(){
        var fileID = $(this).attr("id");
        var file_action = 'delete_file';
        if(confirm("Delete CANNOT be undo!!!\nWant to Delete File ?" )){
            $.ajax({
                url:"manage_action.php",
                method:"POST",
                data:{fileID:fileID,file_action:file_action},
                success:function(data){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                    $('#image_table').DataTable().ajax.reload();
                }
            });
        }
        else{
            return false;
        }
    });

    $(document).on("click", '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
});
</script>

<?php
include '../footer.php';
?>
