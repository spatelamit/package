<?php
$message = "";
if ($this->session->userdata('message_data')) {
    $message_data = $this->session->userdata('message_data');
    $message = $message_data['message'];
}
?>
<!DOCTYPE html>
<html style="margin-top: 0 !important" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow">
        <title><?php echo (isset($company_name) && $company_name) ? $company_name : "Company"; ?>- Forgot Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Oswald|Armata' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/style.css" type="text/css" media="all" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/ionicons.min.css">
    </head>
    <body class="bg-image">
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />
        <div id="sw">
            <div class="sw-nav">
                <div class="container">
                    <div class="row" id="show_web_form">
                        <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a href="http://<?php echo $main_domain; ?>/" class="btn-out pull-right">
                                        <i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                                    <h3 class="panel-title" id="actionHeading">Forgot Password?</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>We will help you to reset it!</h5>
                                        </div>
                                    </div>
                                    <form role="form" class="form-horizontal" id="forgotPasswordForm" method='post' action="javascript:webForgotPassword();">
                                        <div class="row">
                                            <div class="col-md-12" id="show_message">
                                                <div id="error" class="alert alert-danger danger hidden"></div>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="ion-person"></i></span>
                                                    <input class="form-control" type="text" placeholder="Enter Username" name="forgot_username" id="forgot_username" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" name="forgot_password" id="forgot_password" class="btn btn-lg btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <div class="col-md-12">
                                                <input type="hidden" name="web_domain" id="web_domain" value="<?php echo (isset($website_domain) && $website_domain != "") ? $website_domain : ""; ?>" />
                                                <input type="hidden" name="user_id" id="user_id" value="<?php echo (isset($user_id)) ? $user_id : "0"; ?>" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="panel-footer"><span>Back to Sign In?</span>
                                    <a href="<?php echo base_url(); ?>signin" class="btn btn-success btn-sm pull-right">Sign In</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>Assets/signup/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>Assets/signup/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/validation.js"></script>
    </body>
</html>
<?php $this->session->unset_userdata('message_data'); ?>