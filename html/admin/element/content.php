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
	                        <a class="nav-link active" href="#" >Content Page</a>
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
                    <form method="POST" id="content_page_form" enctype="multipart/form-data">
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Select Page&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-4">
                                <select name="pageID" id="pageID" class="form-control">
                                    <option value="">--- New Page ---</option>
                                    <?php echo page_list_select_option($cms_connect);?>
                                </select>
                            </div>
                            <div class="col-md-8" align="right">
                                
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Description/Link <span style="color:red">*</span></div>
                            <div class="col-md-8"><input type="text" name="page_description" id="page_description" class="form-control" required></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Title <span style="color:red">*</span></div>
                            <div class="col-md-8"><input type="text" name="page_cont_title" id="page_cont_title" class="form-control" required></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" align="right">Content&nbsp;&nbsp;&nbsp;</div>
                            <div class="col-md-8"><textarea rows="10" name="page_content" id="page_content"></textarea></div>
                        </div>
                    </form>
                    <div style="text-align:center"> <!-- button submit form -->
                        <button type="submit" class="btn btn-outline-info" title="Save" form="content_page_form"><i class="fa fa-floppy-o"></i>&nbsp; Save</button>
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
    $(window).on("load", function(){
        
    });

    /**************************** initial load ****************************/
    // wysiwyg for content page
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
    CKEDITOR.replace('page_content', wysiwyg_config);
    /***************************** end of initial load *****************************/

    $('#pageID').change(function(){
        var pageID = $(this).val();
        if(pageID != ''){
            var page_action = 'page_info_update';
            $.ajax({
                url:"element_action.php",
                method:"POST",
                data:{pageID:pageID,page_action:page_action},
                success:function(mess){
                    var pageObj = JSON.parse(mess);                   
                    $('#page_description').val(pageObj.description);
                    $('#page_cont_title').val(pageObj.cont_title);
                    CKEDITOR.instances['page_content'].setData(pageObj.cont_content);
                }
            });
        }
        else{
            $('#page_description').val('');
            $('#page_cont_title').val('');
            CKEDITOR.instances['page_content'].setData('');
        }
        
    });


    /***************** submit tradeshow add/update form ******************/
    $('#content_page_form').submit(function(e){
        event.preventDefault();
        var page_action = 'submit_page_form';
        var page_content = (CKEDITOR.instances['page_content']).getData();
        var data = new FormData(this);
        data.append("page_content", page_content);
        data.append("page_action", page_action);

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