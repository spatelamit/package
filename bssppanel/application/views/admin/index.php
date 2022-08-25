

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

        <meta name="robots" content="noindex,nofollow">

        <meta name="sitelock-site-verification" content="2157" />

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">

        <meta name="author" content="">

        <meta name="robots" content="noindex,nofollow">

        <title>Bulk SMS- Admin Login</title>

        <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/font-awesome.min.css">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/admin/css/ui.css">



    </head>

    <body oncontextmenu="return false;">

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

                                        <h5 class="text-center">Admin Panel</h5>

                                    </div>

                                    <div class="panel-body">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">

                                            <form action="<?php echo base_url(); ?>admin/auth_portal_beaing_log_data/hgh5nhjdfg5n2s5f5vb5gf5bf5fvx5ds5cc5ghd5sax5g5fgef5sdw5fsx2cds2f5fs645sc64d5f4s5a4d3fh54jhn54vgjhgfkfhjdsffgnj54j64n5jh14fggh5j411532fg1h53245322h" method="post" class="control_form" id="validate-basic">

                                                <div class="form-group <?php echo (isset($message_type) && $message_type) ? $message_type : "hidden"; ?>">

                                                    <span class="<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>">

                                                        <?php echo (isset($message) && $message) ? $message : ""; ?>

                                                    </span>

                                                </div>

                                                <div class="form-group">

                                                    <label>Username</label>

                                                    <input type="text" name="username" id="username" placeholder="Username" class="form-control"

                                                           required data-parsley-required-message="Please Enter Username" 

                                                           data-parsley-pattern="/^[A-Za-z][A-Za-z0-9_]*$/" data-parsley-pattern-message="Username must be start with a character" />

                                                </div>

                                                <div class="form-group">

                                                    <label>Password</label>

                                                    <input type="password" name="password" id="password" placeholder="Password" class="form-control"

                                                           required data-parsley-required-message="Please Enter Password" />

                                                </div>

                                                <div class="form-group">

                                                    <?php echo (isset($captcha) && $captcha['image']) ? $captcha['image'] : ""; ?>

                                                </div>

                                                <div class="form-group">

                                                    <label>Password</label>

                                                    <input type="text" id="captcha" name="captcha" placeholder="Captcha" class="form-control"

                                                           required data-parsley-required-message="Please Enter Captcha" autocomplete="off" />

                                                </div>

                                                <div class="form-group">

                                                    <label>Contact Number</label>

                                                    <input id="contact_number" name="contact_number" type="text" class="form-control" placeholder="Contact Number" 

                                                           data-parsley-required-message="Please Enter Contact Number" required="" data-parsley-type="integer"

                                                           data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-minlength="10" data-parsley-maxlength="10"

                                                           data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number" />

                                                </div>

                                                <div class="form-group">

                                                    <button type="submit" name="login" class="btn-group btn-group-justified btn btn-primary">Login</button>

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