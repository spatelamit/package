<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$message = "";
$message_type = "";
if ($this->session->flashdata('message_type') && $this->session->flashdata('message')) {
    $message = $this->session->flashdata('message');
    $message_type = $this->session->flashdata('message_type');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Bulk SMS- Admin Login</title>
        <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/controller.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12 col-md-offset-4">
                    <div class="login-container">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center index-title">
                            <h4>
                                BULK SMS SERVICE PROVIDERS<br/>
                                <small>Admin Login</small>
                            </h4>
                        </div>
                        <?php
                        $data = array('id' => "validate-basic");
                        echo form_open('admin/validate_admin', $data);
                        ?> 
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <span class="<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>">
                                <?php echo (isset($message) && $message) ? $message : ""; ?>
                            </span>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="fa fa-user"></span>
                                </div>
                                <input type="text" name="username" id="username" class="input-lg form-control" placeholder="Username" 
                                       data-parsley-required-message="Please Enter Username" required="" >
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="fa fa-lock"></span>
                                </div>
                                <input type="password" name="password" id="password" class="input-lg form-control" placeholder="Password" 
                                       data-parsley-required-message="Please Enter Password" required="">
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <?php echo (isset($captcha) && $captcha['image']) ? $captcha['image'] : ""; ?>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="fa fa-lock"></span>
                                </div>    
                                <input id="captcha" name="captcha" type="text" class="input-lg form-control" placeholder="Captcha" 
                                       data-parsley-required-message="Please Enter Captcha" required="" />
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="fa fa-phone"></span>
                                </div>
                                <input id="contact_number" name="contact_number" type="text" class="input-lg form-control" placeholder="Contact Number" 
                                       data-parsley-required-message="Please Enter Contact Number" required="" data-parsley-type="integer"
                                       data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-minlength="10" data-parsley-maxlength="10"
                                       data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number" />
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <input type="submit" value="Login" name="login" id="login" class="btn btn-lg btn-block btn-primary btn-main">
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/validator.js"></script>
        <script type="text/javascript">
            $('#validate-basic').parsley();
        </script>
    </body>
</html>