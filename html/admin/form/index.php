<?php
include('../../../newconnect.php');
include('../header.php');
require_once('function.php');

?>

<div class="content mt-3"> <!-- begining of display content -->
<span id="alert_action"></span>
	<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Product Registration List</strong>
	                <small><span class="float-right mt-1">
		                <button type="button" name="back" id="back" class="btn btn-outline-info btn-sm" onclick="window.history.back()"><span class="fa fa-arrow-left" title="Go Back"></span> Back</button>    
		            </span></small>
                </div>

                <div class="card-body">
                	<div class="row">
	                    <div class="col-md-12">
	                        <button class="btn btn-sm" id="show_filter" data-toggle="collapse" data-target="#display_filter"><i class="fa fa-chevron-down"></i> Filter</button>
	                    </div>
	                    <div class="collapse col-md-12" id="display_filter">
	                        <div class="form-group row">
	                        	<div class=" col-md-2">Site</div>
	                        	<div class=" col-md-3">
	                        		<select name="site" id="site" class="form-control">
		                                <option value="">--- Select ---</option>
		                                <?php echo site_list($cms_connect);?>
		                            </select>
	                        	</div>
	                        </div>

	                        <div class="form-group row">
	                            <div class=" col-md-12">
	                            	<button type="button" name="applyfilter" id="applyfilter" class="btn btn-outline-info btn-sm ">Apply</button>
	                                <button type="button" name="clearfilter" id="clearfilter" class="btn btn-outline-danger btn-sm ">Clear</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>

	                <hr>

                    <div class="row">

                        <div class="col-lg-12 col-sm-12 table-responsive">
                            <table id="product_reg_table" class="table table-bordered table-hover" width="100%">
                                <thead class="thead-dark"><tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Dealer</th>
                                    <th>Price</th>
                                    <th>Date Purchased</th>
                                    <th>Site</th>
                                    <th width=5%></th>
                                </tr></thead>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<?php
include('../footer.php')
?>


<script type="text/javascript">

$(document).ready(function(){
	fetch_product();
	function fetch_product(){
		var action = "fetch_product";
        $('#product_reg_table').DataTable().destroy();
        $('#product_reg_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "order":[],
            "ajax":{
                url:"fetch.php",
                type:"POST",
                data:{
                    action:action
                }
            },
            "columnDefs":[{
                "targets":[1,2,3,4,6,7],
                "orderable":false,
                },
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "dom": 'lBfrtip',
            "buttons": ['excel','csv','pdf'],
            "language": {"infoFiltered": ""},
        });
    }


});

</script>

