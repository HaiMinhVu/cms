<?php
include('../../../newconnect.php');
include('../header.php');
include('element_function.php');

$category_tree = json_encode(treeview_category_array($cms_connect));
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
                            <strong class="card-title">Management</strong>
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
                            <a class="nav-link " href="pressrelease.php" >Press Release</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " href="category.php" >Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#" >Feature Product</a>
                        </li>
                    </ul><!------------ end of header tab ------------>

	                <!------------ begin of content ------------>
                    <h1>Coming soon ...</h1>
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



});
</script>