<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

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
                        <button type="button" id="add_slider" class="btn btn-outline-info btn-sm" title="Add New Slider"><i class="fa fa-plus"></i>&nbsp; Add</button>
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link " href="navigation.php" >Navigations</a>
	                    </li>
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >Sliders</a>
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
                    <div class="row">
                        <div class="col-md-1">Page</div>
                        <div class="col-md-4">
                            <select name="slider_page" id="slider_page" class="form-control">
                                <option value="">--- Select a Page ---</option>
                                <?php echo page_have_sliders($cms_connect);?>
                            </select>
                        </div>
                        <div class="col-md-7" align="right">
                            
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="slider_table" class="table table-bordered table-hover" width="100%">
                                <thead><tr>
                                    <th width=30%>Image</th>
                                    <th width=25%>Link</th>
                                    <th width=30%>Overlay Text</th>
                                    <th width=5%>Order</th>
                                    <th width=10%></th>
                                </tr></thead>
                            </table>
                        </div>
                    </div>
                    <!------------ end of content ------------>

                </div>
            </div>
        </div>
    </div>
</div> <!-- end of display content -->

<!-- update/add modal -->
<div class="modal fade" id="slider_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <form id="slider_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="slider_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Page</strong></label> <span style="color: red">*</span>
                        <select name="slider_pid" id="slider_pid" class="selectpicker form-control show-tick" data-live-search="true" required>
                            <option value="">--- Select Page ---</option>
                            <option value="0">New Page</option>
                            <?php echo page_have_sliders($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group" id="display_page_name" style="display: none">
                        <label><strong>Page Name</strong></label> <span style="color: red">*</span>
                        <input type="text" name="page_name" id="page_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><strong>Thumbnail</strong></label> <span style="color: red">*</span>
                        <select name="slider_img" id="slider_img" class="selectpicker form-control show-tick" data-live-search="true" required>
                            <option value="">--- Select One ---</option>
                            <?php echo all_image_select_option_imgview($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Link</strong></label>
                        <input type="text" name="slider_link" id="slider_link" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><strong>Text</strong></label>
                        <input type="text" name="slider_text" id="slider_text" class="form-control" />
                    <div class="form-group">
                        <label><strong>Order</strong></label >
                        <input type="number" name="slider_order" id="slider_order" class="form-control" />
                    </div>
                    
                    <input type="hidden" name="slider_action" id="slider_action">
                    <input type="hidden" name="sliderID" id="sliderID">
                    
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

    function fetch_data(pageID = ''){
        $('#slider_table').DataTable().destroy();
        var slider_action = 'fetch_slider';
        $('#slider_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "searching": false,
            "order":[],
            "ajax":{
                url:"element_fetch.php",
                type:"POST",
                data:{
                    slider_action:slider_action, pageID:pageID
                }
            },
            "columnDefs":[{
                "targets":[0,1,2,3,4],
                "orderable":false,
                },
            ],
            'columns': [
                { 'data': 'img' },
                { 'data': 'link' },
                { 'data': 'text' },
                { 'data': 'order' },
                { 'data': 'action' }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {"infoFiltered": ""},
        });
    }
    
    $(window).on("load", function(){
        if(localStorage.getItem('slider_result') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('slider_result')+'</div>');
            localStorage.removeItem('slider_result');
        }
    });

    $('#slider_page').on('change', function(){
        var pageID = $('#slider_page').val();
        if(pageID != ''){
            fetch_data(pageID);
        }
        
    });



    $('#slider_pid').on('change', function(){
        var pageID = $('#slider_pid').val();
        if(pageID == 0){
            $('#display_page_name').css('display', 'block');
        }
        else{
            $('#display_page_name').css('display', 'none');
        }
        
    });

    /************** add new tradeshow modal ***************/
    $('#add_slider').click(function(){
        $('#slider_modal').modal('show');
        $('#slider_title').html("Add Slider");
        $('#slider_form')[0].reset();
        $('#slider_img, #slider_pid').selectpicker('refresh');
        $('#slider_action').val('add_slider');
        $('#display_page_name').css('display', 'none');

        var pageID = $('#slider_page').val();
        if(pageID != ''){
            $('#slider_pid').selectpicker('val', pageID);
        }
    });

    /************** update tradeshow modal **************/
    $(document).on('click', '.update', function(){
        var sliderID = $(this).attr("id");
        var slider_action = 'slider_info_update';
        $.ajax({
            url:"element_action.php",
            method:"POST",
            data:{sliderID:sliderID,slider_action:slider_action},
            success:function(mess){
                var sliderObj = JSON.parse(mess);
                $('#slider_modal').modal('show');
                $('#slider_title').html("Update slider");
                $('#slider_form')[0].reset();

                $('#slider_pid').selectpicker('val', sliderObj.pid);
                $('#display_page_name').css('display', 'none');
                $('#slider_img').selectpicker('val', sliderObj.file_id);
                $('#slider_link').val(sliderObj.link_value);
                $('#slider_text').val(sliderObj.text);
                $('#slider_order').val(sliderObj.slider_order);

                $('#sliderID').val(sliderObj.id);
                $('#slider_action').val('update_slider');
            }
        });
    });

    /***************** submit tradeshow add/update form ******************/
    $('#slider_form').submit(function(e){
        var pageID = $('#slider_pid').val();
        
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
                if(pageID == 0){
                    localStorage.setItem("slider_result", mess);
                    window.location.reload(); 
                }
                else{
                    $('#slider_modal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                    $('#slider_table').DataTable().ajax.reload();
                }

                
            }
        });
    });

    $(document).on('click', '.delete', function(){
        var sliderID = $(this).attr("id");
        var slider_action = 'delete_slider';
        if(confirm("Delete CANNOT be undo!!!\nStill want to delete ?" )){
            $.ajax({
                url:"element_action.php",
                method:"POST",
                data:{sliderID:sliderID,slider_action:slider_action},
                success:function(mess){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                    $('#slider_table').DataTable().ajax.reload();
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