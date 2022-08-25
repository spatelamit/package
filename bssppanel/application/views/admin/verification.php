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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Bulk SMS- Admin Verification</title>
        <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/ui.css">
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="container">
            <div role="tabpanel">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active fade in">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title text-center"> BULK SMS SERVICE PROVIDERS</h4>
                                        <h5 class="text-center">Admin Verification</h5>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                            <form action="<?php echo base_url(); ?>admin/verify_code" method="post" class="control_form" id="validate-basic" autocomplete="off">
                                                <div class="form-group <?php echo (isset($message_type) && $message_type) ? $message_type : "hidden"; ?>">
                                                    <span class="<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>">
                                                        <?php echo (isset($message) && $message) ? $message : ""; ?>
                                                    </span>
                                                </div>
                                                <div class="form-group">
                                                    <label>We sent you a verification code on your registered mobile number.</label>
                                                </div>
                                                <div class="form-group">
                                                    <label>Verification Code</label>
                                                    <input type="text" name="verification_code" id="verification_code" class="form-control" placeholder="Please Enter Verification Code" 
                                                           data-parsley-required-message="Please Enter Verification Code" required="" autocomplete="off" >
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="verify" class="btn-group btn-group-justified btn btn-primary">Verify Code</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                        </div>
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