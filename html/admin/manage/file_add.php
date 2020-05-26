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
                            <strong class="card-title">Management -> Add Files</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                        <button type="button" class="btn btn-outline-info btn-sm" title="Go Back" onclick="window.location.href='file.php'"><i class="fa fa-arrow-left"></i>&nbsp; Back</button>
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
                    <form method="POST" id="file_upload_form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-4">
                                <select name='file_type' class="selectpicker form-control show-tick" data-live-search="true" data-style="btn-primary" required>
                                    <option value=''>--- Select Type ---</option>
                                    <?php echo file_type_select_option($cms_connect)?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name='folder' class="selectpicker form-control show-tick" data-live-search="true" data-style="btn-primary" required>
                                    <option value=''> --- Select Folder ---</option>
                                    <?php echo site_folder_select_option($cms_connect)?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name='site' id="brand-select" class="selectpicker form-control show-tick" data-live-search="true" data-style="btn-primary" required>
                                    <option value=''>--- Select Brand ---</option>
                                    <?php echo site_list_select_option($cms_connect)?>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">

                            <div class="col-md-2"></div>
                            <div class="col-md-4">
                                <input type="file" class="filepond" name="files_upload[]" id="files_upload" data-max-file-size="25MB" multiple />
                            </div>
                            <div class="col-md-4">
                                <div class="table-responsive">
                                    <table id="file_table" class="table table-bordered table-hover">
                                        <thead><tr>
                                            <th width=50%>Name</th>
                                            <th width=50%>Display Name</th>
                                        </tr></thead>
                                        <tbody id="file_table_body"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div style="text-align:center; display: none" id="loader">
                            <label><img style="width: 100px; height: 100px"  src="../images/loading.gif"/>Uploading ...</label>
                        </div>
                        <div style="text-align:center"> <!-- button submit form -->
                            <button type="submit" class="btn btn-outline-info" title="Save"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->

<script type="text/javascript">

$(document).ready(function(){

	if(localStorage.getItem('file_result') != null){
		$('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('file_result')+'</div>');
		localStorage.removeItem('file_result');
	}

    var files = FilePond.create(
        document.querySelector('input[id="files_upload"]'),
        {
            // all property of filepond go in here
            imagePreviewHeight: 50,
            imagePreviewMaxInstantPreviewFileSize: 50,
        }
    );

    files.on('addfile', function(error, file) {
        load_files();
    });
    files.on('removefile', function(error, file) {

        load_files();
    });

    function load_files(){
        filenames = [];
        var file_action = 'preload_file';
        var filesObj = files.getFiles();
        for(i = 0; i < filesObj.length; i++){
            filenames.push(filesObj[i].filename);
        }
        $.ajax({
            url:"manage_action.php",
            method:"POST",
            data:{filenames:filenames,file_action:file_action},
            success:function(mess){
                //console.log(mess);
                $('#file_table_body').html('');
                $('#file_table_body').append(mess);
            }
        });
    }
    

    $('#file_upload_form').submit(function(e){
        $("#loader").show();
        event.preventDefault();
        var data = new FormData(this);
        data.append("file_action", "add_file");
        data.append("selected_brand", $("#brand-select option:selected").text());
        $.ajax({
            type:"post",
            url:"manage_action.php",
            data:data,
            contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
                $("#loader").hide();
                localStorage.setItem("file_result", mess);
                window.location.reload();
            }
        });
    });
    
});
</script>

<?php
include '../footer.php';
?>
