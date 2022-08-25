<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$message = "";
if ($this->session->userdata('login')) {
    $session_data = $this->session->userdata('login');
    $message = $session_data['message'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="robots" content="noindex,nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="TaITqcfYlPPqRC7M6XmkJt1YFvuvLiXEKaATl0arg_k" />
        <title><?php echo (isset($company_name) && $company_name) ? $company_name : "Company Name"; ?></title>
        <link href="<?php echo base_url(); ?>Assets/user/css/bootstrap.min.css" rel="stylesheet">
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/page.css">
        <link href="<?php echo base_url(); ?>Assets/user/css/ionicons.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/animations.min.css">
        <style type="text/css">
            #bg-image {
                position: fixed;
                width: 100%;
                background-size: cover;
                background-position: center center;
                background-repeat: no-repeat;
                height: 100%;
                <?php
                if (isset($website_banner)) {
                    if ($website_banner == 1)
                        echo "background-image:url('" . base_url() . "Assets/user/img/banner1.jpg');";
                    elseif ($website_banner == 2)
                        echo "background-image:url('" . base_url() . "Assets/user/img/banner2.jpg');";
                    elseif ($website_banner == 3)
                        echo "background-image:url('" . base_url() . "Assets/user/img/srts_new.jpg');";
                    else
                        echo "background-image:url('" . base_url() . "Assets/user/img/banner1.jpg');";
                } else
                    echo "background-image:url('" . base_url() . "Assets/user/img/banner1.jpg');";
                ?>
            }
            
            iframe  {
    border: medium none;
    left: -13%;
    position: relative;
    top: -340px;
}


        </style>
    </head>
    <body data-spy="scroll" data-target=".nav-spy" oncontextmenu="return false;">
        <div id="bg-image"></div>
        <div class="overlay"></div>

        <div class="navbar-fixed-top visible-xs ">
            <ul class="list-inline">
                <li><a href="#home"><i class="ion ion-home"></i> <span class="sr-only">(current)</span></a></li>
                <li><a href="#about"><i class="ion ion-leaf"></i> </a></li>
                <li><a href="#services"><i class="ion ion-levels"></i> </a></li>
                <li><a href="#contact"><i class="ion ion-chatbubbles"></i> </a></li>
            </ul>
        </div>
        <!--
        <div id="loading">
            <div id="loading-center">
                <div id="loading-center-absolute">
                    <div class="object" id="object_four"></div>
                    <div class="object" id="object_three"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_one"></div>
                </div>
            </div>
        </div>
        -->
        <nav class="navbar navbar-default l0 nav-spy hidden-xs" style="background-color: <?php echo (isset($website_theme_color) && $website_theme_color != "") ? $website_theme_color : "rgba(0, 0, 0, 0.6)"; ?>;">
            <div class="container-fluid padding0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse padding0" id="bs-example-navbar-collapse-1">
                    <ul class="nav">
                        <li>
                            <a href="#home" data-toggle="tooltip" data-placement="right" title="Home">
                                <i class="ion ion-home"></i>
                                <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li>
                            <a href="#about" data-toggle="tooltip" data-placement="right" title="About">
                                <i class="ion ion-leaf"></i> 
                            </a>
                        </li>
                        <li>
                            <a href="#services" data-toggle="tooltip" data-placement="right" title="Services">
                                <i class="ion ion-levels"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#contact" data-toggle="tooltip" data-placement="right" title="Contact">
                                <i class="ion ion-chatbubbles"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <nav class="navbar navbar-default r0 hidden-xs" style="background-color: <?php echo (isset($website_theme_color) && $website_theme_color != "") ? $website_theme_color : "rgba(0, 0, 0, 0.6)"; ?>;">
            <div class="container-fluid padding0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse padding0" id="bs-example-navbar-collapse-2">
                    <?php
                    $facebook = "";
                    $gplus = "";
                    $twitter = "";
                    $linkedin = "";
                    if (isset($website_social_links) && $website_social_links != "") {
                        $links_array = explode('|', $website_social_links);
                        $facebook = $links_array[0];
                        $twitter = $links_array[1];
                        $linkedin = $links_array[2];
                        $gplus = $links_array[3];
                    }
                    ?>
                    <ul class="nav">
                        <li>
                            <a href="<?php echo $facebook; ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="Facebook"><i class="ion ion-social-facebook"></i> </a>
                        </li>
                        <li>
                            <a href="<?php echo $gplus; ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="GooglePlus"><i class="ion ion-social-googleplus"></i> </a>
                        </li>
                        <li>
                            <a href="<?php echo $twitter; ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="Twitter"><i class="ion ion-social-twitter"></i> </a>
                        </li>
                        <li>
                            <a href="<?php echo $linkedin; ?>" target="_blank" data-toggle="tooltip" data-placement="left" title="LinkedIn"><i class="ion ion-social-linkedin"></i> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />

        <div class="container">

            <section id="home" style="margin-bottom: 370px;">
                <div class="animatedParent row" data-sequence="500">
                    <div class="col-md-12 text-center">
                        <div class="logo">
                            <img src="<?php echo base_url(); ?><?php echo (isset($company_logo) && $company_logo != "") ? "Website_Data/Logos/" . $company_logo : "Assets/user/img/logo.png"; ?>" alt="Company Logo" />
                        </div>
                        <h2 class="animated growIn slow tagline" data-id="1">We are the source, we are the provider</h2>
                        <a href="#about" class="arrow"><i class="ion-ios7-arrow-thin-down"></i></a> 
                    </div>
                    <div class="row" id="show_web_form" >
                        <div class="col-md-4 col-md-offset-4" data-id="2" >
                            <div class="wrap" style="height:350px; width: 500px; margin-left: -70px;">
                                <p class="form-title">Sign In</p>
                                <form class="login" action="<?php echo base_url(); ?>sav4h5gs5xf5wat85kgv58474r5d/domain_auth_details/hgh5fg5df6gfgh5gfszhtc4k56ja5n45hjzkjfrlkk445696gdf5gfsfsdhjc8vbkjsxhjfc5gnbhxx8nhjd5ibhdb2ujlnmhjsf565g2h" id="signinForm" method="post">
                                    <?php
                                    if ($this->session->flashdata('login_msg')) {
                                        ?>
                                        <div class="alert alert-success">
                                            <?php echo ($this->session->flashdata('login_msg') != "") ? $this->session->flashdata('login_msg') : ""; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div id="error" class="alert alert-danger danger <?php echo (isset($message) && $message != "") ? "" : "hidden"; ?>">
                                        <?php echo (isset($message) && $message != "") ? $message : ""; ?>
                                    </div> 
                                    
                                   <input type="text" id="username" name="username" class="form-control NONE-BLOCK" placeholder="Enter Username" />
                                    <input type="password" id="password" name="password" class="form-control NONE-BLOCK" placeholder="Enter Password" />
                                    <button type="submit" name="login" id="login" class="btn btn-primary NONE-BLOCK">Sign In</button>
                                   
                                    <div class="f-pass">
                                        <a href="javascript:void(0);" onclick="showWebForm('forgot');"><i class="ion ion-help-circled"></i> Forgot Password</a>
                                        <p>Not have an account yet? <a href="javascript:void(0);" onclick="showWebForm('signup');" style="font-size:20px;">Sign Up</a></p>
                                    </div>
                                </form>
                                
                                <span style="color: darkgrey; font-size: 10px; font-weight:bold;">The security of your personal information and our Clients information is important to us.
                                    When you enter sensitive information ,
                                    such as login credentials we encrypt the transmission of that information.
                                    this information only see by 
                                    <?php
                                    echo $actual_link = $_SERVER['HTTP_HOST'];
                                    ?></span>
                             
                            </div>
                        </div>
                    </div>
                </div>
              
            </section>
 
            <section id="about">
                <div class="row">
                    <div class="animatedParent col-md-12 bgw">
                        <h2 class="title">Who we are</h2>
                        <div class="animated fadeInRightShort col-md-6">
                            <p class="tagline margin0">Now its a wrap of best online Bulk SMS service provider...</p>
                            <?php
                            if (isset($website_about_contents) && $website_about_contents != "") {
                                echo "<p>$website_about_contents</p>";
                            } else {
                                ?>
                                <p>We are leading bulk sms service provider company in India who has reputed client from all over India & world. We are providing sms solution for various needs, Transactional SMS & Promotional SMS. We strive to keep you connected and communicated seamlessly with your target audience, using the most methods of verifications, automating notifications, and promotional offers.</p>
                                <p>Using bulk SMS marketing, you can regularly update your clients about latest products, discounts, vouchers and super saver deals. It is an effective tool of mass marketing. This software enables you to send online SMS to India either in bulk SMS, Group SMS or online SMS all over india.</p>
                                <p>Bulk SMS is an ever growing company that provides Bulk SMS services. We provide fast, personalized, scalable and reliable SMS solutions. Our Web based panel is very user friendly, simple & eazy to use. Our aim is to make our clients a brand and not just serve them and forget. In order to do so, we provide quality solutions that are reliable and fast. In our tenure of providing unmatched service since long now, we have realized that to capture both the corporate and the consumer market it is surely not a cake walk. The basic features, we have elaborated below-</p>
                            <?php } ?>
                        </div>
                        <div class="animated fadeInLeftShort col-md-6 hidden-xs">
                            <img src="<?php echo base_url(); ?><?php echo (isset($website_about_image) && $website_about_image != "") ? "Website_Data/About/" . $website_about_image : "Assets/user/img/pc.jpg"; ?>" alt="About Us">
                        </div>
                    </div>
                </div>
            </section>

            <section id="services">
                <div class="animatedParent row">
                    <div class="col-md-12">
                        <h2 class="title">Services we provide</h2>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="animated rotateInUpLeft slow s-bg bgw">
                            <i class="ion ion-android-contacts red"></i>
                            <h3>Reseller</h3>
                            <?php
                            if (isset($website_service1) && $website_service1 != "") {
                                echo "<p>$website_service1</p>";
                            } else {
                                ?>
                                <p>We offer an Excellent Reseller program for Bulk SMS. Now a days Bulk sms has become a very powerful medium for Marketing and information processing, and you can be a one stop solution all SMS needs of any individuals/business. You can start your own bulk sms business today with your own Website and start earning today itself and can initiate your own branded SMS reseller website with our web server connectivity. We provide you with all backend services that you need to be successful in selling the SMS keeping ourself completely hidden from retail business working dedicatedly for you at backend.</p>
                                <?php
                            }
                            ?>
                        </div>  
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="animated rotateInDownLeft slow s-bg bgw">
                            <i class="ion ion-pricetags blue"></i>
                            <h3>White Label</h3>
                            <?php
                            if (isset($website_service2) && $website_service2 != "") {
                                echo "<p>$website_service2</p>";
                            } else {
                                ?>
                                <p>We offer white label to our resellers, they can use this system and interface as their application. White Label is just as important for small businesses as it is for big names. Indeed, many corporate brands try to look more like small firms in order to appeal to consumers that prefer to support independent brands. No matter the strategy, our process is set to help our audience, and our mission forward. Here you’ll get a customized, re-branded version of this system that allows you to offer complete software marketing, a self-service execution or a combination of both.</p>
                                <?php
                            }
                            ?>
                        </div>  
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="animated rotateInUpRight slow s-bg bgw">
                            <i class="ion ion-ios7-cog yellow"></i>
                            <h3>HTTP API</h3>
                            <?php
                            if (isset($website_service3) && $website_service3 != "") {
                                echo "<p>$website_service3</p>";
                            } else {
                                ?>
                                <p>Using our HTTP APIs it is easy to integrate Bulk SMS features to your website or applications. Developers can easily unite our API’s in their application, system or site. With this we aim to include the best services and creating joyful arena in the world of bulk sms. Our outstanding technical team will help you to integrate our API in your application. API is effectively used to send sms in large group in a single shot. It is like a customized plan for sending Sms in bulk only to your targeted customers as per the requirement.</p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>  
                </div>
            </section>

            <section id="contact" style="margin-top: 200px;">
                <div class="animatedParent col-md-12 bgw">
                    <div class="animated fadeInRightShort col-md-6 col-md-offset-1">
                        <h2 class="title">Where we are</h2>
                        <div class="col-md-12">
                            <i class="ion-ios7-telephone c-ico"></i>
                            <div class="sicon-cont">
                                <h5 class="c-tag">
                                    <?php echo (isset($website_contacts) && $website_contacts != "") ? $website_contacts : "91-xxxxxxx"; ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <i class="ion-location c-ico"></i>
                            <div class="sicon-cont">
                                <h5 class="c-tag">
                                    <?php echo (isset($website_addresses) && $website_addresses != "") ? $website_addresses : "Your Address"; ?>
                                    <?php echo (isset($website_cities) && $website_cities != "") ? ", " . $website_cities : ", Your City"; ?>
                                    <?php echo (isset($website_zipcodes) && $website_zipcodes != "") ? ", " . $website_zipcodes : ", Your Pincode"; ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <i class="ion-email c-ico"></i>
                            <div class="sicon-cont">
                                <h5 class="c-tag">
                                    <?php echo (isset($website_emails) && $website_emails != "") ? $website_emails : "info@yourdomain.com"; ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <i class="ion-earth c-ico"></i>
                            <div class="sicon-cont">
                                <h5 class="c-tag">
                                    <a href="http://<?php echo (isset($website_domain) && $website_domain != "") ? $website_domain : "www.yourdomain.com"; ?>" target="_blank">
                                        <?php echo (isset($website_domain) && $website_domain != "") ? $website_domain : "www.yourdomain.com"; ?></a>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="animated fadeInLeftShort col-md-4 padding0">
                        <h2 class="title">Feel Free To Contact Us</h2>
                        <form role="form" class="contact-form inverse" id="commentForm" method='post' action="javascript:sendOtherMail();">
                            <div id="error1" class="alert alert-danger danger hidden"></div>
                            <div id="success1" class="alert alert-success success hidden"></div>
                            <div class="form-group">
                                <input type="text" class="form-control input-sm" name="contact_name" id="contact_name" placeholder="Please Enter Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control input-sm" name="contact_contact" id="contact_contact" placeholder="Please Enter Contact Number">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control input-sm" name="contact_email" id="contact_email" placeholder="Please Enter Email Address">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control input-sm" name="contact_message" id="contact_message" placeholder="Please Enter Message"></textarea>
                            </div>
                            <button type="submit" name="contact_form" id="contact_form" class="btn btn-danger btn-sm">SUBMIT</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 text-center mt">
                    <span class="copyright">
                        © <?php echo date('Y'); ?> <?php echo (isset($company_name) && $company_name != "") ? $company_name : "Company Name"; ?>. All rights reserved.
                    </span>
                </div>
               
            </section>
 
        </div>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/animate.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/validation.js"></script>


   <script type="text/javascript">
        $(document).ready(function() {
            $(body).on("contextmenu",function(){
               return false;
            });  
        });
            $(document).ready(function() {
       $(body).bind('cut copy paste', function (e) {
        e.preventDefault();
    });
    });
    $(document).bind("contextmenu",function(e) {
     e.preventDefault();
});
</script>
<script>
document.onkeydown = function(e) {
if(event.keyCode == 123) {
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
return false;
}
if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
return false;
}
}
</script>
<script>
$(document).keydown(function (event) {
    if (event.keyCode == 123) { // Prevent F12
        return false;
    } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
        return false;
    }
});
</script>
<script>
document.onkeydown = function(e) {
        if (e.ctrlKey && 
            (e.keyCode === 67 || 
             e.keyCode === 86 || 
             e.keyCode === 85 || 
             e.keyCode === 117)) {
           return false;
        } else {
            return true;
        }
};

    </script>

    </body>
</html>
<?php $this->session->unset_userdata('login'); ?>


