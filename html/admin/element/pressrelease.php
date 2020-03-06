<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

?>

<!----------- WYSIWYG --------->
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>

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
                            <a class="nav-link active" href="#" >Press Release</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="category.php" >Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="featureproduct.php" >Feature Product</a>
                        </li>
	                </ul><!------------ end of header tab ------------>

                    <!------------ begin of content ------------>
                    <form method="POST" id="press_release_form" enctype="multipart/form-data">
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Brand <span style="color:red">*</span></div>
                            <div class="col-md-4">
                                <select name="brandID" id="brandID" class="selectpicker form-control show-tick" data-live-search="true" required>
                                    <option>--- Select Brand ---</option>
                                    <?php echo brand_list_select_option($cms_connect);?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Press Release <span style="color:red">*</span></div>
                            <div class="col-md-4">
                                <select name="pressID" id="pressID" class="selectpicker form-control show-tick" data-live-search="true" required>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Title <span style="color:red">*</span></div>
                            <div class="col-md-8"><input type="text" name="press_title" id="press_title" class="form-control" required></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Short Description Image&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8">
                                <select name="short_thumbnail" id="short_thumbnail" class="selectpicker form-control" data-live-search="true">
                                <option value="">--- Select One ---</option>
                                <?php echo all_image_select_option($cms_connect);?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Short Description&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8"><textarea rows="7" name="short_description" id="short_description"></textarea></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Main Body Image&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8">
                                <select name="main_thumbnail" id="main_thumbnail" class="selectpicker form-control" data-live-search="true">
                                <option value="">--- Select One ---</option>
                                <?php echo all_image_select_option($cms_connect);?>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Main Body&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8"><textarea rows="7" name="main_body" id="main_body"></textarea></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Release Date&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8"><input type="date" name="release_date" id="release_date" class="form-control"></div>
                        </div>
                    </form>

                    <div style="text-align:center"> <!-- button submit form -->
                        <button type="submit" class="btn btn-outline-info" title="Save" form="press_release_form"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
                        <button type="button" class="btn btn-outline-warning" title="Reset" onClick="window.location.reload()"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                    </div>
	                <!------------ end of content ------------>

                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->

<?php
include '../footer.php';
?>


<script type="text/javascript">

$(document).ready(function(){   

    /**************************** initial load ****************************/
    // wysiwyg for press page
    var wysiwyg_config = {
        skin: 'moono',
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode:CKEDITOR.ENTER_P,
        toolbar: [{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', "-", 'TextColor', 'BGColor' ] },
                { name: 'styles', items: [ 'Format', 'Font', 'FontSize' ] },
                { name: 'scripts', items: [ 'Subscript', 'Superscript' ] },
                { name: 'justify', groups: [ 'blocks', 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'paragraph', groups: [ 'list', 'indent' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'insert', items: [ 'Image'] },
                { name: 'spell', items: [ 'jQuerySpellChecker' ] },
                { name: 'table', items: [ 'Table' ] }
                ],
    }
    CKEDITOR.replace('short_description', wysiwyg_config);
    CKEDITOR.replace('main_body', wysiwyg_config);
    /***************************** end of initial load *****************************/

    $(window).on("load", function(){
        
    });

    // on brand select, load press release for each brand
    $('#brandID').change(function(){
        var brandID = $(this).val();
        if(brandID != ''){
            var press_action = 'brand_filter';
            $.ajax({
                method:"POST",
                url:"element_action.php",
                data:{brandID:brandID,press_action:press_action},
                success: function(mess){
                    $('#pressID').html(mess);
                    $('#pressID').selectpicker('refresh');
                }
            });
        }
        else{
            $('#press_title').val('');
            $('#short_thumbnail').selectpicker('val', '');
            CKEDITOR.instances['short_description'].setData('');
            $('#main_thumbnail').selectpicker('val', '');
            CKEDITOR.instances['main_body'].setData('');
        }
        
    });

    // on select press release, pre-load info for update
    $('#pressID').change(function(){
        var pressID = $('#pressID').val();
        if(pressID != 0){
            var press_action = 'press_info';
            $.ajax({
                method:"POST",
                url:"element_action.php",
                data:{pressID:pressID,press_action:press_action},
                success: function(mess){
                    var pressObj = JSON.parse(mess);
                    $('#press_title').val(pressObj.title);
                    $('#short_thumbnail').selectpicker('val', pressObj.short_file_id);
                    CKEDITOR.instances['short_description'].setData(pressObj.short_description);
                    $('#main_thumbnail').selectpicker('val', pressObj.file_id);
                    CKEDITOR.instances['main_body'].setData(pressObj.body);
                    $('#release_date').val(pressObj.release_date);
                }
            });
        }
        else{
            $('#press_title').val('');
            $('#short_thumbnail').selectpicker('val', '');
            CKEDITOR.instances['short_description'].setData('');
            $('#main_thumbnail').selectpicker('val', '');
            CKEDITOR.instances['main_body'].setData('');
        }
    });


    /***************** submit press release form ******************/
    $('#press_release_form').submit(function(e){
        event.preventDefault();

        var press_action = 'submit_press_release_form';
        var short_description = (CKEDITOR.instances['short_description']).getData();
        var main_body = (CKEDITOR.instances['main_body']).getData();
        var data = new FormData(this);
        data.append("short_description", short_description);
        data.append("main_body", main_body);
        data.append("press_action", press_action);

        $.ajax({
            type:"post",
            url:"element_action.php",
            data:data,
            contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
                $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
            }
        });

    });
});
</script>