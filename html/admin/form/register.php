<?php
include('../../../newconnect.php');
include('../header.php');
require_once('function.php');
?>


<div id="app" class="content mt-3"> <!-- begining of display content -->
<span id="alert_action"></span>
	<div class="row">
			<form-builder />
        <div class="col-md-12" style="display:none">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">Registration Form</strong>
	                <small><span class="float-right mt-1">
		                <button type="button" name="back" id="back" class="btn btn-outline-info btn-sm" onclick="window.history.back()"><span class="fa fa-arrow-left" title="Go Back"></span> Back</button>
		            </span></small>
                </div>
                <div class="card-body">
                	<form method="POST" id="register_form" enctype="multipart/form-data">

	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">First Name <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="firstname" name="firstname" placeholder="First Name" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Last Name <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="lastname" name="lastname" placeholder="Last Name" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Address 1 <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="address1" name="address1" placeholder="Address 1" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Address 2</div>
	                		<div class="col-md-6"><input id="address2" name="address2" placeholder="Address 2" class="form-control" type="text" /></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">City <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="city" name="city" placeholder="City" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">State <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="state" name="state" placeholder="State" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Zip/Postal Code <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="zipcode" name="zipcode" placeholder="Zip / Postal Code" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Country <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="country" name="country" placeholder="Country" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Email Address <span style="color:red">*</span></div>
	                		<div class="col-md-6"><input id="email" name="email" placeholder="Email Address" class="form-control" type="email" required/></div>
	                	</div>
						<div class="form-group row">
	                		<div class="col-md-3" align="right">Phone Number</div>
	                		<div class="col-md-6"><input id="phone" name="phone" placeholder="Phone Number" class="form-control" type="text" /></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Model <span style="color: red"> *</span></div>
	                		<div class="col-md-6">
	                			<select class="form-control" id="model" name="model" required>
					                <option value="">--- Select Product ---</option>
					                <?php echo product_list_select_option($cms_connect) ?>
					            </select>
					        </div>
	                	</div>
					    <div class="form-group row">
	                		<div class="col-md-3" align="right">Serial Number</div>
	                		<div class="col-md-6"><input id="serial" name="serial" placeholder="Serial Number" class="form-control" type="text" /></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Transaction/PO/reciept</div>
	                		<div class="col-md-6"><input type="file" class="filepond" name="proof" id="proof" data-max-files="1" /></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Dealer/Store <span style="color: red"> *</span></div>
	                		<div class="col-md-6"><input id="dealer" name="dealer" placeholder="Dealer / Store" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Price Paid <span style="color: red"> *</span></div>
	                		<div class="col-md-6"><input id="price" name="price" placeholder="Dealer / Store" class="form-control" type="text" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Date Purchased <span style="color: red"> *</span></div>
	                		<div class="col-md-6"><input id="date_purchased" name="date_purchased" class="form-control" type="date" required/></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Satisfaction</div>
	                		<div class="col-md-6">
	                			<div class="radio">
					                <input type="radio" name="satisfaction" value="10"> 10 - Great product or high value
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="9"> 9 - Extremely Satisfied, frequent user, good customer service
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="8"> 8 - Good product
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="7"> 7 - Will use, but still looking at other products
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="6"> 6 - Works for me, even if issues with add-on features or price
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="5"> 5 - Never used, warranty replacement required
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="4"> 4 - I bought the wrong product or it is too difficult to use
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="3"> 3 - I am the intended customer and it works, but I will not use this
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="2"> 2 - Product can be made to work, but I am extremely dissatisfied
					            </div>
					            <div class="radio">
					                <input type="radio" name="satisfaction" value="1"> 1 - Inoperable, design does not function
					            </div>
	                		</div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right">Comment</div>
	                		<div class="col-md-6"><textarea rows="3" class="form-control" name="comment" id="comment"></textarea></div>
	                	</div>
	                	<div class="form-group row">
	                		<div class="col-md-3" align="right"></div>
	                		<div class="col-md-6"><strong>Add me to your mailing list</strong><br>
					            <input type="checkbox" name="add_mailing" id="add_mailing"> Yes</div>
	                	</div>

					    <div style="text-align:center; display: none" id="loader">
							<span><i class="fa fa-spinner fa-pulse fa-lg fa-fw"></i> Please Wait ...</span>
	                    </div>
	                    <div style="text-align:center">
	                        <input type="submit" class="btn btn-outline-info" value="Submit" />
	                    </div>
	                    <br>
	                </form>
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

	$(window).on("load", function(){
        if(sessionStorage.getItem('mess') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+sessionStorage.getItem('mess')+'</div>');
            sessionStorage.clear();
        }
        setTimeout(function(){
            $("#alert_action").fadeOut().empty();
        }, 5000);
    });


	$('#register_form').submit(function(e){
		$("#loader").show();
		e.preventDefault();
		var data = new FormData(this);
		data.append('action', 'register_action');
		$.ajax({
            type:"POST",
            url:"action.php",
            data:data,
            contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
                $("#loader").hide();
                alert(mess);
                window.location.reload();

                //localStorage.setItem("mess", mess);

            }
        });
	});

	/*********** filepond for upload files ***********/
    var files = FilePond.create(document.querySelector('input[id="proof"]'),
        {
            // all property of filepond go in here
            allowImagePreview: false,

        }
    );

});

</script>
