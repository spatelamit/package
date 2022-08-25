<?php
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
        <title>Bulk SMS- Admin Verification</title>
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
                                <small>Admin Verification</small>
                            </h4>
                        </div>
                        <?php
                        $data = array('id' => "validate-basic");
                        echo form_open('admin/verify_code', $data);
                        ?> 
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <span class="<?php echo ($message_type) ? $message_type : ""; ?>"><?php echo ($message) ? $message : ""; ?></span>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <h4>We sent you a verification code on your registered mobile number.</h4>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <span class="fa fa-user"></span>
                                </div>
                                <input type="text" name="verification_code" id="verification_code" class="input-lg form-control" placeholder="Please Enter Verification Code" 
                                       data-parsley-required-message="Please Enter Verification Code" required="" >
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <input type="submit" value="Verify Code" name="verify" id="verify" class="btn btn-lg btn-block btn-primary btn-main">
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