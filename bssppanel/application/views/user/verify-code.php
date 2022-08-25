<?php
$user_id = "";
$contact_number = "";
$message = "";
$type=0;
if ($this->session->userdata('message_data')) {
    $message_data = $this->session->userdata('message_data');
    $user_id = $message_data['user_id'];
    $contact_number = $message_data['contact_number'];
    $message = $message_data['message'];
    $type = $message_data['type'];
}
?>
<!DOCTYPE html>
<html style="margin-top: 0 !important" lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo (isset($company_name) && $company_name) ? $company_name : "Company"; ?>- Sign In</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Oswald|Armata' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/style.css" type="text/css" media="all" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/ionicons.min.css">
    </head>
    <body class="bg-image">
        <div id="sw">
            <div class="sw-nav">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                                    <h3 class="panel-title" id="actionHeading">
                                        Does XXXXXXX<?php echo substr($contact_number, -3); ?> really belong to you?
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $data = array('id' => "signinForm", 'class' => "form-horizontal");
                                    echo form_open('signin/verify_code_process/' . $user_id, $data);
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12" id="show_message">
                                            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                                                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                                            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                                                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="ion-person"></i></span>
                                                <input class="form-control" type="text" placeholder="Enter Verification Code" name="verification_code" id="verification_code" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" name="verify_code" id="verify_code" class="btn btn-lg btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            Didn't get the code yet? <a href="javascript:void();" onclick="sendAnothrRequest();">Request</a> another one.
                                        </div>
                                    </div>
                                    </form>
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