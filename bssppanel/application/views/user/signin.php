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
        <meta https-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
        <meta name="robots" content="noindex,nofollow">
        <meta name="google-site-verification" content="TaITqcfYlPPqRC7M6XmkJt1YFvuvLiXEKaATl0arg_k" />
        <meta name="sitelock-site-verification" content="2157" />
        <title><?php echo (isset($company_name) && $company_name) ? $company_name : "Company"; ?>- Sign In</title>

        <meta name="keywords"  content="Best Bulk SMS Service Providers Indore,India, Bulk SMS API/gateway Provider, Transactional/Promotional bulk sms service providers, business promotional bulk SMS" />
        <meta name=description content="We are leading bulk sms service providers who has reputed clients from all over India. We provide sms solution for various needs, Transactional/Promotional SMS">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Oswald|Armata' rel='stylesheet' type='text/css'> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>Assets/signup/css/style.css" type="text/css" media="all" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/signup/css/ionicons.min.css">
        <link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
        <link rel="canonical" href="https://sms.bulksmsserviceproviders.com/signin" />
    </head>
    <body class="bg-image">

        <section class="signup-page">
            <div class="signup-title">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                            <div class="Sign-up-heading">
                                <h1>Welcome To Signin</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sw">
                <div class="sw-nav">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <a href="https://<?php echo $main_domain; ?>/" class="btn-out pull-right">
                                            <i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                                        <h3 class="panel-title">Sign In</h3>
                                    </div>
                                    <div class="panel-body panel-body2">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>signin/validate_user" id="signinForm" method="post">
                                            <div class="row">
                                                <div class="col-md-12" id="show_message">
                                                    <?php
                                                    if ($this->session->flashdata('login_msg')) {
                                                        ?>
                                                        <div class="alert alert-success">
                                                            <?php echo ($this->session->flashdata('login_msg') != "") ? $this->session->flashdata('login_msg') : ""; ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div id="error" class="alert <?php echo (isset($message_type) && $message_type) ? $message_type : "alert-danger danger hidden"; ?>">
                                                        <?php echo (isset($message) && $message) ? $message : ""; ?>
                                                    </div>
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
                                                    <div class="input-group input-2">
                                                        <span class="input-group-addon"><i class="ion-locked"></i></span>
                                                        <input class="form-control" type="password" placeholder="Enter Password" id="password" name="password" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row form-group">
                                                <div class="col-md-12">
                                                    <input type="hidden" name="web_domain" id="web_domain" value="sms.bulksmsserviceproviders.com" />
                                                    <button type="submit" name="login" id="login" class="btn btn-lg btn-primary">Sign In</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="row form-group">
                                            <div class="col-md-12">
                                                <a href="<?php echo base_url(); ?>forgot_password" class="term2">Forgot Password?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer"><span>Want an account?</span>
                                        <a href="<?php echo base_url(); ?>signup" class="btn btn-success btn-sm pull-right pull-green-btn">Sign Up</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/signup/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/signup/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/validation.js"></script>

    </body>
</html>