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
                        <button type="button" id="add_tradeshow" class="btn btn-outline-info btn-sm" title="Add New Show"><i class="fa fa-plus"></i>&nbsp; Add</button>
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
                            <a class="nav-link active" href="#" >Trade Shows</a>
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
                        <div class="col-md-4">
                            <select name="brandID" id="brandID" class="form-control">
                                <option value="">--- Select Brand ---</option>
                                <?php echo brand_list_select_option($cms_connect);?>
                            </select>
                        </div>
                        <div class="col-md-8" align="right">
                            <input type="checkbox" id="inactive" > InActive
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="trashow_table" class="table table-bordered table-hover" width="100%">
                                <thead><tr>
                                    <th width=5%>Thumbnail</th>
                                    <th width=25%>Show</th>
                                    <th width=10%>From</th>
                                    <th width=10%>To</th>
                                    <th width=10%>Location</th>
                                    <th width=10%>Booth</th>
                                    <th width=20%>Link</th>
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
<div class="modal fade" id="tradeshow_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <form id="tradeshow_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="tradeshow_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Brand</strong></label> <span style="color: red">*</span>
                        <select name="show_brandID" id="show_brandID" class="selectpicker form-control" data-live-search="true" required>
                            <option value="">--- Select Brand ---</option>
                            <?php echo brand_list_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Thumbnail</strong></label> <span style="color: red">*</span>
                        <select name="show_thumbnail" id="show_thumbnail" class="selectpicker form-control" data-live-search="true" required>
                            <option value="">--- Select One ---</option>
                            <?php echo all_image_select_option_imgview($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>Show Name</strong></label> <span style="color: red">*</span>
                        <input type="text" name="show_name" id="show_name" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label><strong>From</strong></label> <span style="color: red">*</span>
                        <input type="date" name="show_datefrom" id="show_datefrom" value="<?php echo date('Y-m-d')?>" class="form-control" required/>
                    <div class="form-group">
                        <label><strong>To</strong></label > <span style="color: red">*</span>
                        <input type="date" name="show_dateto" id="show_dateto" value="<?php echo date('Y-m-d')?>" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label><strong>Location</strong></label > <span style="color: red">*</span>
                        <input type="text" name="show_location" id="show_location" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label><strong>Booth</strong></label >
                        <input type="text" name="show_booth" id="show_booth" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label><strong>Link</strong></label > 
                        <input type="text" name="show_link" id="show_link" class="form-control" />
                    </div>
                    <input type="hidden" name="show_action" id="show_action">
                    <input type="hidden" name="showID" id="showID">
                    
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

    function fetch_data(inactive='false', brandID = ''){
        $('#trashow_table').DataTable().destroy();
        var tradeshow_action = 'fetch_show';
        $('#trashow_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "order":[],
            "ajax":{
                url:"element_fetch.php",
                type:"POST",
                data:{
                    tradeshow_action:tradeshow_action, inactive:inactive, brandID:brandID
                }
            },
            "columnDefs":[{
                "targets":[0,1,2,3,4,5,6,7],
                "orderable":false,
                },
            ],
            'columns': [
                { 'data': 'img' },
                { 'data': 'show' },
                { 'data': 'from' },
                { 'data': 'to' },
                { 'data': 'location' },
                { 'data': 'booth' },
                { 'data': 'link' },
                { 'data': 'action' }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {"infoFiltered": ""},
        });
    }
    
    $(window).on("load", function(){
        fetch_data();
    });

    $('#inactive, #brandID').on('change', function(){
        var inactive = $('#inactive').is(':checked');
        var brandID = $('#brandID').val();
        fetch_data(inactive, brandID);
    });


    /************** add new tradeshow modal ***************/
    $('#add_tradeshow').click(function(){
        $('#tradeshow_modal').modal('show');
        $('#tradeshow_title').html("Add Trade Show");
        $('#tradeshow_form')[0].reset();
        $('#show_thumbnail, #show_brandID').selectpicker('refresh');
        $('#show_action').val('add_tradeshow');
    });

    /************** update tradeshow modal **************/
    $(document).on('click', '.update', function(){
        var showID = $(this).attr("id");
        var show_action = 'tradeshow_info_update';
        $.ajax({
            url:"element_action.php",
            method:"POST",
            data:{showID:showID,show_action:show_action},
            success:function(mess){
                var showObj = JSON.parse(mess);
                $('#tradeshow_modal').modal('show');
                $('#tradeshow_title').html("Update Trade Show");
                $('#tradeshow_form')[0].reset();

                $('#show_brandID').selectpicker('val', showObj.brand_id);
                $('#show_thumbnail').selectpicker('val', showObj.file_id);
                $('#show_name').val(showObj.ts_show);
                $('#show_datefrom').val(showObj.ts_date_from);
                $('#show_dateto').val(showObj.ts_date_to);
                $('#show_location').val(showObj.location);
                $('#show_booth').val(showObj.booth);
                $('#show_link').val(showObj.ts_link);
                $('#showID').val(showObj.id);
                $('#show_action').val('update_tradeshow');
            }
        });
    });

    /***************** submit tradeshow add/update form ******************/
    $('#tradeshow_form').submit(function(e){
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
                $('#tradeshow_modal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                $('#trashow_table').DataTable().ajax.reload();
            }
        });
    });

    $(document).on('click', '.delete', function(){
        var showID = $(this).attr("id");
        var show_action = 'delete_tradeshow';
        if(confirm("Delete CANNOT be undo!!!\nStill want to delete ?" )){
            $.ajax({
                url:"element_action.php",
                method:"POST",
                data:{showID:showID,show_action:show_action},
                success:function(mess){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                    $('#trashow_table').DataTable().ajax.reload();
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
