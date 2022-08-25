<?php
$message = "";
$message_type = "";
if ($this->session->flashdata('message_type') && $this->session->flashdata('message')) {
    $message = $this->session->flashdata('message');
    $message_type = $this->session->flashdata('message_type');
}
?>
<!DOCTYPE html>
<html style="margin-top: 0 !important" lang="en-US">
    <head>
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-101037230-1', 'auto');
            ga('send', 'pageview');

        </script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow">
        <meta name="sitelock-site-verification" content="2157" />
        <meta name="google-site-verification" content="TaITqcfYlPPqRC7M6XmkJt1YFvuvLiXEKaATl0arg_k" />
        <title><?php echo (isset($company_name) && $company_name) ? $company_name : "Company"; ?>- Sign Up</title>

        <meta name="keywords"  content="Best Bulk SMS Service Providers Indore,India, Bulk SMS API/gateway Provider, Transactional/Promotional bulk sms service providers, business promotional bulk SMS" />
        <meta name=description content="We are leading bulk sms service providers who has reputed clients from all over India. We provide sms solution for various needs, Transactional/Promotional SMS">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href='http://fonts.googleapis.com/css?family=Oswald|Armata' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/style.css" type="text/css" media="all" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/ionicons.min.css">
        <link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
        <link rel="canonical" href="http://sms.bulksmsserviceproviders.com/signup" />
    </head>
    <body >

        <section class="signup-page">
            <div class="signup-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                            <div class="Sign-up-heading">
                                <h1>Welcome To Signup</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sw">
                <div class="sw-nav">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">                           
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <a href="http://<?php echo $main_domain; ?>/" class="btn-out pull-right">
                                            <i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                                        <h3 class="panel-title">Sign Up</h3>
                                        <!-- <?php echo (isset($company_name) && $company_name) ? $company_name : "Company Name"; ?>-  -->
                                    </div>
                                    <div class="panel-body">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>signup/save_bulksms_user" id="signupForm" method="post">
                                            <div class="row">
                                                <div class="col-md-12" id="show_message">
                                                    <div id="success" class="alert alert-success success hidden"></div>
                                                    <div id="error" class="alert <?php echo (isset($message_type) && $message_type) ? $message_type : "alert-danger danger hidden"; ?>">
                                                        <?php echo (isset($message) && $message) ? $message : ""; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-person"></i></span>
                                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Full Name"  />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-ios-personadd-outline"></i></span>
                                                        <input type="text" id="signup_username" name="signup_username" class="form-control" 
                                                               placeholder="Enter Username" onkeyup="checkUsername(this.value);"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-ios-telephone-outline"></i></span>
                                                        <input type="text" id="contact_number" name="contact_number" class="form-control" 
                                                               placeholder="Enter Contact Number" />
                                                    </div>
                                                </div>    
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-ios-email-outline"></i></span>
                                                        <input type="text" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address"  />
                                                    </div>
                                                </div>    
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-ios-people-outline"></i></span>
                                                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name" />
                                                    </div>
                                                </div>    
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion-ios-world"></i></span>
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
                                                </div> 

                                            </div>

                                            <div class="row form-group">
                                                <div class="col-md-12">
                                                    <label for="terms">
                                                        <input type="checkbox" name="terms" id="terms" value="1" checked="" />
                                                        I agree with <a href="<?php echo base_url(); ?>terms_conditions" target="_blank" class="term2">terms of services</a>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />
                                                    <button type="submit" name="signup" id="signup" class="btn btn-lg btn-primary">Sign Up</button>
                                                </div>  
                                            </div>
                                        </form>  
                                    </div>
                                    <div class="panel-footer"><span class="account-span">Already have an account?</span>
                                        <a href="<?php echo base_url(); ?>signin" class="btn btn-success btn-sm pull-right pull-green-btn">Sign In</a>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="<?php echo base_url(); ?>Assets/signup/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>Assets/signup/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/validation.js"></script>
    </body>
</html>
<?php $this->session->unset_userdata('message_data'); ?>