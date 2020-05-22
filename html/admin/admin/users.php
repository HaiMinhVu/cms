<?php
include('../../../newconnect.php');
include('../header.php');
include('admin_function.php');

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
                            <strong class="card-title">Admin</strong>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" align="right">
                         <button type="button" id="add_user" class="btn btn-outline-info btn-sm" title="Add New User"><i class="fa fa-plus"></i>&nbsp; Add</button>
                    </div>
                </div>
                <div class="card-body">
                	<!------------ header tab ------------>
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="producttab">
	                    <li class="nav-item">
	                        <a class="nav-link active" href="#" >Users</a>
	                    </li>
	                </ul>
                    <!------------ end of header tab ------------>
                    
	                <!------------ begin of content ------------>
                    <div class="row">
                        <div class="col-lg-12 table-responsive">
                            <div class="row" align="right">
                                <div class="col-lg-12">
                                    <input type="checkbox" id="inactive" > InActive
                                </div>
                            </div>
                            <table id="user_table" class="table table-bordered table-hover">
                                <thead><tr>
                                    <th width=30%>Username</th>
                                    <th width=30%>Email</th>
                                    <th width=30%>Role</th>
                                    <th width=10%>Action</th>
                                </tr></thead>
                            </table>
                        </div>
                    </div>
                    <!------------ end of of content ------------>
                
                </div>
            </div>
        </div>
    </div>
</div> <!-- end of display content -->


<!-- modal -->
<div class="modal fade" id="user_modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <form id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="user_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="roleID" name="roleID" data-live-search="true" class="selectpicker show-tick" data-width="100%" required>
                            <option value="">--- Select One ---</option>
                            <?php echo user_role_select_option($cms_connect);?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" required/>
                    </div>
                    <input type="hidden" name="user_action" id="user_action">
                    <input type="hidden" name="userID" id="userID">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-outline-info" value="Save" />
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){

	function fetch_data(inactive = 'false'){
        $('#user_table').DataTable().destroy();
        var user_action = 'fetch_user';
        $('#user_table').DataTable({
            "processing":true,
            "serverSide":true,
            "stateSave": true,
            "order":[],
            "ajax":{
                url:"admin_fetch.php",
                type:"POST",
                data:{
                    inactive:inactive, user_action:user_action
                }
            },
            "columnDefs":[{
                "targets":[0,1,2,3],
                "orderable":false,
                },
            ],
            'columns': [
                { 'data': 'username' },
                { 'data': 'email' },
                { 'data': 'role' },
                { 'data': 'action' }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {"infoFiltered": ""},
        });
    }

    $(window).on("load", function(){
        fetch_data();
        if(localStorage.getItem('user_result') != null){
            $('#alert_action').fadeIn().html('<div class="alert alert-info">'+localStorage.getItem('user_result')+'</div>');
            localStorage.removeItem('user_result');
        }
    });

    $('#inactive').change(function(){
        var inactive = $('#inactive').is(':checked');
        fetch_data(inactive);
    });

    // add new user modal
    $('#add_user').click(function(){
        $('#user_modal').modal('show');
        $('#user_title').html("Add User");
        $('#user_form')[0].reset();
        $('#roleID').selectpicker('refresh');
        $('#user_action').val('add_user');
    });

    // update user modal
    $(document).on('click', '.update', function(){
        var userID = $(this).attr("id");
        var user_action = 'user_update_info';
        $.ajax({
            url:"admin_action.php",
            method:"POST",
            data:{userID:userID,user_action:user_action},
            success:function(mess){
                var userObj = JSON.parse(mess);
                $('#user_modal').modal('show');
                $('#user_title').html("Update User");
                $('#user_form')[0].reset();
                $('#user_action').val('update_user');

                $('#username').val(userObj.username);
                $('#roleID').selectpicker('val', userObj.role_id);
                $('#email').val(userObj.email);
                $('#password').val(userObj.pwd);
                $('#userID').val(userID);
            }
        });
    });

    // submit add/update user form
    $('#user_form').submit(function(e){
        event.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type:"post",
            url:"admin_action.php",
            data:data,
            contentType: false,
            cache: false,
            processData:false,
            success: function(mess){
                $('#user_modal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                $('#user_table').DataTable().ajax.reload();
            }
        });
    });


    // delete site 
    $(document).on('click', '.delete', function(){
        var userID = $(this).attr("id");
        var user_action = 'delete_user';
        if(confirm("Confirm to Delete Category?" )){
            $.ajax({
                url:"admin_action.php",
                method:"POST",
                data:{userID:userID,user_action:user_action},
                success:function(mess){
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">'+mess+'</div>');
                    $('#user_table').DataTable().ajax.reload();
                }
            });
        }
        else{
            return false;
        }
        
    });
});
</script>

<?php
include '../footer.php';
?>

