<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
       <meta name="robots" content="noindex,nofollow">
        <title>Bulk SMS Application</title>
        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/JS/scripts.js"></script>
    </head>

    <body style="margin: 0 60px 0 10px;">
        <div>
            <div style="border: 2px solid #000;padding: 20px;width: 100%;" align="center">
                <div>
                    <table width="100%">
                        <tr>
                            <td>
                                <div align="left">
                                    <strong>BULK SMS APPLICATION</strong>
                                </div>
                            </td>
                            <td>
                                <div align="right">
                                    <strong><?php echo strtoupper("Welcome " . $username); ?></strong>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>index.php/smpp_user/logout">LOGOUT</a>
                                </div>
                                <div align="right">
                                    <strong>Promotional Balance: <?php echo $smpp_pr_balance; ?> SMS</strong>
                                    <br/>
                                    <strong>Transactional Balance: <?php echo $smpp_tr_balance; ?> SMS</strong>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr/>
                <div>
                    <div align="center">
                        <a href="<?php echo base_url(); ?>index.php/smpp_user/index">MY PROFILE</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>index.php/smpp_user/smpp_info">SMPP DETAILS</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>index.php/smpp_user/my_transactions">MY TRANSACTIONS</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="<?php echo base_url(); ?>index.php/smpp_user/change_password">CHANGE PASSWORD</a>
                    </div>
                </div>
                <hr/>
                <hr/>
                <br/>
                <span id="error_message" style="color:red;font-weight: 700;">
                    <?php
                    if (isset($error_msg))
                        echo $error_msg;
                    ?>
                </span>
                <span style="color:green;font-weight: 700;">
                    <?php
                    if (isset($success_msg))
                        echo $success_msg;
                    ?>
                </span>
                <hr/>