<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>EDIT USERS</h3>
            </div>

            <div class="title_right">
                <div class="pull-right">
                    <a href="<?php echo base_url() . 'admin/users/'; ?>" class="btn btn-default" >User List</a>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Edit Users<small><?php echo ucfirst($get_user_list[0]['fname']); ?></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>                      
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <div class="row">
                            <div class="col-md-6 col-xs-12 col-sm-6 col-sm-offset-3 col-md-offset-3 col-xs-offset-0">
                                <div class="alert alert-danger" style="display:none;">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>

                                </div>
                            </div>
                        </div>
                        <form  class="form-horizontal form-label-left" method="post" autocomplete="off" id="user-form" action="<?php echo base_url() . 'admin/users/ajax_edit'; ?>">
                            <input type="hidden" name="pk_cust_id" id="pk_cust_id" value="<?php echo $pk_cust_id; ?>">
                            <div class="form-group elVal">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">First Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="fname" name="fname" class="form-control col-md-7 col-xs-12" maxlength="30" minlength="3" value="<?php echo $get_user_list[0]['fname']; ?>">
                                </div>
                            </div>
                            <div class="form-group elVal">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Last Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="lname" name="lname"  class="form-control col-md-7 col-xs-12" maxlength="20" minlength="3" value="<?php echo $get_user_list[0]['lname']; ?>">
                                </div>
                            </div>
                            <div class="form-group elVal">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Email ID</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="emailid" name="emailid" class="form-control col-md-7 col-xs-12" type="text"  maxlength="30" minlength="3" value="<?php echo $get_user_list[0]['emailid']; ?>">
                                </div>
                            </div>                           
                            <div class="form-group elVal">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Mobile Number<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="mobileno" name="mobileno" class="date-picker form-control col-md-7 col-xs-12" maxlength="10" minlength="10" type="text" value="<?php echo $get_user_list[0]['mobileno']; ?>">
                                </div>
                            </div>
                            <div class="form-group elVal">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">VC Number <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" id="vc_number" name="vc_number"  class="form-control col-md-7 col-xs-12" maxlength="30" minlength="3" value="<?php echo $get_user_list[0]['vc_number']; ?>">
                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <a href="<?php echo base_url();?>admin/users" class="btn btn-primary">Cancel</a>        
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script src="<?php echo base_url(); ?>/assets/lib/jquery.validate.js"></script>
<script>
    $(document).ready(function () {

        $("#user-form").validate({
            highlight: function (element) {
                $(element).closest('.elVal').addClass("form-field text-error");
            },
            unhighlight: function (element) {
                $(element).closest('.elVal').removeClass("form-field text-error");
            }, errorElement: 'span',
            rules: {
                fname: {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                },
                lname: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                emailid: {
                    required: true,
                    email: true,
                    Exist_Email: true
                },
                mobileno: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true
                },
                vc_number: {
                    required: true,
                    minlength: 3,
                    maxlength: 30,
                    Exist_VC:true
                }
            },
            messages: {
                fname: {
                    required: "Please enter first name"

                },
                lname: {
                    required: "Please enter last name"

                },
                emailid: {
                    required: "Please enter your email"

                },
                mobileno: {
                    required: "Please enter mobile number"

                },
                vc_number: {
                    required: "Please enter VC number"

                }
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.closest(".elVal"));
            },
            submitHandler: function (form) {
                var $form = $("#user-form");
                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json'
                }).done(function (response) {

                    if (response.status == "1")
                    {
                        window.location = "<?php echo base_url(); ?>admin/users/";
                    }
                    else
                    {
                        $('.alert-danger').show();
                        $('.alert-danger').html(response.msg);
                        setTimeout(function () {
                            $('.alert-danger').hide('slow');
                        }, 4000);
                    }
                });
                return false; // required to block normal submit since you used ajax
            }
        });

        $.validator.addMethod("Exist_Email", function (value, element) {

            var pk_cust_id = $("#pk_cust_id").val();
            var checkEmail = check_exist_email(value, pk_cust_id);           
            if (checkEmail =="1")
            {
                return false;
            }
            return true;

        }, "Email Already Exists!");
        
        $.validator.addMethod("Exist_VC", function (value, element) {
            var pk_cust_id = $("#pk_cust_id").val();
            var checkVC = check_exist_vc(value, pk_cust_id);
            if (checkVC == "1")
            {
                return false;
            }
            return true;

        }, "VC Number Already Exists!");

        $.validator.addMethod("Alphaspace", function (value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Username must contain only letters, numbers, or dashes.");

        $.validator.addMethod("Alphanumeric", function (value, element) {
            return this.optional(element) || /^[a-z0-9]+$/i.test(value);
        }, "Username must contain only letters, numbers, or dashes.");

        $.validator.addMethod("nowhitespace", function (value, element) {
            return this.optional(element) || /^\S+$/i.test(value);
        }, "Space are not allowed");

    });

    function check_exist_email(email, pk_cust_id) {
        var isSuccess = 0;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>admin/users/exist_email_check",
            data: "email=" + email + "&pk_cust_id=" + pk_cust_id,
            async: false,
            success:
                    function (msg) {
                        isSuccess = msg === "1" ? 1 : 0
                    }
        });
        return isSuccess;
    }
    function check_exist_vc(vc_number, pk_cust_id) {
        var isSuccess = 0;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>admin/users/exist_vcnumber_check",
            data: "vc_number=" + vc_number + "&pk_cust_id=" + pk_cust_id,
            async: false,
            success:
                    function (msg) {
                        isSuccess = msg === "1" ? 1 : 0
                    }
        });
        return isSuccess;
    }
</script>