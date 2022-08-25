<!-- Reseller Website -->
<link href="http://sms.bulksmsserviceproviders.com/Assets/user/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="http://sms.bulksmsserviceproviders.com/Assets/user/css/page.css">
<link href="http://sms.bulksmsserviceproviders.com/Assets/user/css/ionicons.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="http://sms.bulksmsserviceproviders.com/Assets/user/css/animations.min.css">
<style type="text/css">
          body {
              position: relative;
                width: 100%;
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
                height: 100%;
                background-image: url('http://sms.bulksmsserviceproviders.com/Assets/user/img/srts_new.jpg');

            }
            body::after {
  background: rgba(0, 0, 0, 0.3) none repeat scroll 0 0;
  bottom: 0;
  content: "";
  height: 100%;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
  z-index: -1;
}
            
            iframe  {
    border: medium none;
    left: -13%;
    position: relative;
    top: -340px;
}


        </style>
        <div class="col-md-12 text-center" style="margin-top:100px;">
                        <div class="logo">
                            <img src="http://sms.bulksmsserviceproviders.com/Website_Data/Logos/bssp_logo.png" alt="Company Logo">
                        </div>
                        <h2 class="animated growIn slow tagline go" data-id="1">We are the source, we are the provider</h2>
<!--                        <a href="#about" class="arrow"><i class="ion-ios7-arrow-thin-down"></i></a> -->
                    </div>       
<!-- Reseller Signin -->
<?php if (isset($form) && $form == "signin") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2" >
        <div class="wrap">
            <p class="form-title">Go To Sign In</p>
            
            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div><!--
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username" />
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" />-->
<!--            <button type="submit" name="login" id="login" class="btn btn-primary">Sign In</button>-->
            <div class="f-pass">
<!--                <a href="javascript:void(0);" onclick="showWebForm('forgot');"><i class="ion ion-help-circled"></i> Forgot Password</a>-->
                <p>Not have an account yet? <a href="https://www.bulksmsserviceproviders.com/SignIn/">Sign In</a></p>
            </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Signup -->
<?php if (isset($form) && $form == "signup") { ?>
    <div class="col-md-6 col-md-offset-3" data-id="2">
        <div class="wrap">
            <p class="form-title">Sign Up</p>
            <form role="form" class="login" id="signupForm" method='post' action="javascript:saveNewUser();">
                <div class="row">
                    <div class="col-md-12">
                        <div id="error" class="alert alert-danger danger hidden"></div>
                        <div id="success" class="alert alert-success success hidden"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="signup_username" name="signup_username" class="form-control" placeholder="Enter Username"
                               onkeyup="checkUsername(this.value);" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Enter Contact Number" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name" />
                    </div>
                    <div class="col-md-6">
                        <select name="industry" class="form-control" id="industry">
                            <option value="" selected="">Select Industry</option>
                            <option value="Agriculture ">Agriculture </option>
                            <option value="Automobile &amp; Transport">Automobile &amp; Transport</option>
                            <option value="Ecommerce">E-commerce</option>
                            <option value="Education">Education</option>
                            <option value="Financial Institution">Financial Institution</option>
                            <option value="Gym">Gym</option>
                            <option value="Hospitality">Hospitality</option>
                            <option value="IT Company">IT Company</option>
                            <option value="Lifestyle Clubs">Lifestyle Clubs</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Marriage Bureau">Marriage Bureau</option>
                            <option value="Media &amp; Advertisement">Media &amp; Advertisement</option>
                            <option value="Personal Use">Personal Use</option>
                            <option value="Political ">Political </option>
                            <option value="Public Sector">Public Sector</option>
                            <option value="Real estate">Real estate</option>
                            <option value="Reseller">Reseller</option>
                            <option value="Retail &amp; FMCG">Retail &amp; FMCG</option>
                            <option value="Stock and Commodity">Stock and Commodity</option>
                            <option value="Telecom">Telecom</option>
                            <option value="Tips And Alert">Tips And Alert</option>
                            <option value="Travel">Travel</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="country" class="form-control"  >
                            <option value="" selected="">Select Nationality</option>
                            <option value="0">India</option>
                            <option value="1">International</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="terms">
                            <input type="checkbox" name="terms" id="terms" value="1" checked="" />
                            I agree with <a href="<?php echo base_url(); ?>terms_conditions" target="_blank">terms of services</a>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="signup" id="signup" class="btn btn-primary">Sign Up</button>
                    </div>
                </div>
                <div class="f-pass">
                    <a href="javascript:void(0);" onclick="showWebForm('signin');" style="font-size:20px;"><i class="ion ion-reply"></i> Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Forgot Password -->
<?php if (isset($form) && $form == "forgot") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Forgot Password?</p>
            <form role="form" class="login" id="forgotPasswordForm" method='post' action="http://sms.bulksmsserviceproviders.com/sav4h5gs5xf5wat85kgv58474r5d/check_username_web">
                <div id="error" class="alert alert-danger danger hidden"></div>
                <input type="text" id="forgot_username" name="forgot_username" class="form-control" placeholder="Enter Username" />
                <button type="submit" name="forgot_password" id="forgot_password" class="btn btn-primary">Submit</button>
                <div class="f-pass">
                    <a href="javascript:void(0);" onclick="showWebForm('signin');"><i class="ion ion-reply"></i> Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Verify Code -->
<?php if (isset($form) && $form == "verify") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Verify Code</p>
            <form role="form" class="login" id="verifyCodeForm" method='post' action="http://sms.bulksmsserviceproviders.com/sav4h5gs5xf5wat85kgv58474r5d/verify_code_web">
                <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                <input type="text" id="verification_code" name="verification_code" class="form-control" placeholder="Enter Verification Code" />
                <button type="submit" name="verify_code" id="verify_code" class="btn btn-primary">Submit</button>
                <div class="f-pass">
                    Didn't get the code yet? <a href="javascript:void(0);" onclick="sendOTP('reseller');">Request</a> another one.
                </div>
                
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Reset Password -->
<?php if (isset($form) && $form == "reset") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Reset Password</p>
            <form role="form" class="login" id="resetPasswordFrom" method='post' action="http://sms.bulksmsserviceproviders.com/sav4h5gs5xf5wat85kgv58474r5d/reset_password_web">
                <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter New Password" />
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" />
                <button type="submit" name="reset_password" id="reset_password" class="btn btn-primary">Reset Password</button>
                <div class="f-pass">
<!--                    <a href="javascript:void(0);" onclick="showWebForm('verify');"><i class="ion ion-help-circled"></i> Forgot Password</a>-->
                </div>
            </form>
        </div>
    </div>
<?php } ?>


<!-- Bulk SMS Service Providers Website -->

<!-- Bulk SMS Service Providers Signin -->
<?php if (isset($form) && $form == "web_login") { ?>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title">Sign In</h3>
                <!-- <?php echo (isset($company_name)) ? $company_name : "Company Name"; ?>- -->
            </div>
            <div class="panel-body">
                <?php
                $data = array('id' => "signinForm", 'class' => "form-horizontal");
                echo form_open('signin/validate_user', $data);
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
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-person"></i></span>
                            <input class="form-control" type="text" placeholder="Enter Username" name="username" id="username" />
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-locked"></i></span>
                            <input class="form-control" type="password" placeholder="Enter Password" id="password" name="password" />
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" name="login" id="login" class="btn btn-lg btn-primary">Sign In</button>
                    </div>
                </div>
                </form>
                <div class="row form-group">
                    <div class="col-md-12">
                        <a href="<?php echo base_url(); ?>forgot_password">Forgot Password?</a>
                    </div>
                </div>
            </div>
            <div class="panel-footer"><span>Want an account?</span>
                <a href="<?php echo base_url(); ?>signup" class="btn btn-success btn-sm pull-right">Sign Up</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Forgot Password -->
<?php if (isset($form) && $form == "web_forgot") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
<!--                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>-->
                <h3 class="panel-title" id="actionHeading">Forgot Password?</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>We will help you to reset it!</h4>
                    </div>
                </div>
                <form role="form" class="form-horizontal" id="forgotPasswordForm" method='post' action="javascript:webForgotPassword();">
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
                                <input class="form-control" type="text" placeholder="Enter Username" name="forgot_username" id="forgot_username" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="forgot_password" id="forgot_password" class="btn btn-lg btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Sign In?</span>
                <a href="<?php echo base_url(); ?>signin" class="btn btn-success btn-sm pull-right">Sign In</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Verify Code -->
<?php if (isset($form) && $form == "web_verify") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title" id="actionHeading">
                    Does XXXXXXX<?php echo substr($contact_number, -3); ?> really belong to you?
                </h3>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" id="verifyCodeForm" method='post' action="javascript:webVerifyCode();">
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
                            Didn't get the code yet? <a href="javascript:void();" onclick="sendOTP('web');">Request</a> another one.
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Forgot Password?</span>
                <a href="<?php echo base_url(); ?>forgot_password" class="btn btn-success btn-sm pull-right">Forgot Password</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Reset Password -->
<?php if (isset($form) && $form == "web_reset") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title" id="actionHeading">Reset Password</h3>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" id="resetPasswordFrom" method='post' action="javascript:webResetPassword();">
                    <div class="row">
                        <div class="col-md-12" id="show_message">
                            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-locked"></i></span>
                                <input class="form-control" type="password" placeholder="Enter New Password" name="new_password" id="new_password" />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-locked"></i></span>
                                <input class="form-control" type="password" placeholder="Enter Confirm Password" name="confirm_password" id="confirm_password" />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <button type="submit" name="reset_password" id="reset_password" class="btn btn-lg btn-primary">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Forgot Password?</span>
                <a href="<?php echo base_url(); ?>forgot_password" class="btn btn-success btn-sm pull-right">Forgot Password</a>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript" src="http://sms.bulksmsserviceproviders.com/Assets/user/js/validation.js"></script>
    <script type="text/javascript" src="http://sms.bulksmsserviceproviders.com/Assets/user/js/jquery.min.js"></script>
        <script type="text/javascript" src="http://sms.bulksmsserviceproviders.com/Assets/user/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://sms.bulksmsserviceproviders.com/Assets/user/js/custom.js"></script>
        <script type="text/javascript" src="http://sms.bulksmsserviceproviders.com/Assets/user/js/animate.min.js"></script>
        