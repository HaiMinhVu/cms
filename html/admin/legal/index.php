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
                            <strong class="card-title">Legal</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                        <button type="button" style="display:none" class="btn btn-outline-info btn-sm" title="Add New File" onclick="location.href='file_add.php'"><i class="fa fa-plus"></i>&nbsp; Add</button>
                    </div>
                </div>
                <div class="card-body">

	                <!------------ begin of content ------------>
                    <div id="app" class="row">
                        <legal />
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->

<?php
include '../footer.php';
?>
