<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

?>
<div class="content mt-3"> <!-- begining of display content -->
<span id="alert_action"></span>
<!---------------------- main table  ------------------------------>
	 <div class="row" id="app">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="row">
                            <strong class="card-title">Brands</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">

                    </div>
                </div>
                <div class="card-body">
                	<brand />
                </div>
            </div>
        </div>
    </div>

</div> <!-- end of display content -->

<?php
include '../footer.php';
?>
