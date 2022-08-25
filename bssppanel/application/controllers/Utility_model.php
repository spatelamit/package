<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Utility_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //==========================================================//
    // Get Client IP Address
    function getClientIPAddress() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    // Convert HEX to RGB Color Code
    function Hex2RGB($color = null, $opacity = false) {
        $default = 'rgb(0,0,0)';
        //Return default if no color provided
        if (empty($color))
            return $default;
        //Sanitize $color if "#" is provided 
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }
        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);
        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }
        //Return rgb(a) color string
        return $output;
    }
    
      function sendEmail($mail_array = null) {
        $from_email = $mail_array['from_email'];
        $from_name = $mail_array['from_name'];
        $mail = $from_name;
        //$to_email = $mail_array['to_email'];
        $bcc_email = $mail_array['to_email'];
        $subject = $mail_array['subject'];
        $message = $mail_array['message'];
        // Load Email Library For Sending E-mails
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($mail);
        $this->email->bcc($bcc_email);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
  //==========================================================//
    // Send E-Mail
     public  function sendEmailNew($mail_array = null) {
        $from_email = $mail_array['from_email'];
        $from_name = $mail_array['from_name'];
        $mail = $from_name;
        //$to_email = $mail_array['to_email'];
        $bcc_email = $mail_array['to_email'];
        $subject = $mail_array['subject'];
        $message = $mail_array['message'];

   define('MULTIPART_BOUNDARY', '----' . md5(time()));
        define('EOL', "\r\n");

        function getBodyPart($FORM_FIELD, $value) {
            if ($FORM_FIELD === 'attachment') {
                $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"; filename="' . basename($value) . '"' . EOL;

                $content .= 'Content-Transfer-Encoding: binary' . EOL;
                $content .= EOL . file_get_contents($value) . EOL;
            } else {
                $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . EOL;
                $content .= EOL . $value . EOL;
            }

            return $content;
        }

        function getBody($fields) {
            $content = '';
            foreach ($fields as $FORM_FIELD => $value) {
                $values = is_array($value) ? $value : array($value);
                foreach ($values as $v) {
                    $content .= '--' . MULTIPART_BOUNDARY . EOL . getBodyPart($FORM_FIELD, $v);
                }
            }
            return $content . '--' . MULTIPART_BOUNDARY . '--';
        }

        function getHeader($username, $password) {

            $auth = base64_encode("$username:$password");

            return array('Authorization:Basic ' . $auth, 'Content-Type: multipart/form-data ; boundary=' . MULTIPART_BOUNDARY);
        }

        $url = 'https://xv83g.api.infobip.com/email/1/send';



        $explode_mail = explode(',', $bcc_email);
        //print_r($explode_mail);die;

        for ($i = 0; $i < count($explode_mail); $i++) {

            $to_mail = $explode_mail[$i];

            $postData = array(
                'forom' => 'BULKSMS <bulksmsserviceproviders@yourinbox.in>',
                'to' => $to_mail,
                'replyTo' => "info@bulksmsserviceproviders.com",
                'subject' => "Its Here ! Announcing the launch of our Newly Redesigned Website",
                'text' => $message,
                'html' => $message,
            );


            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => getHeader('EmailSRTS', 'Srts@123'),
                    'content' => getBody($postData),
                )
            ));
        
        $response = file_get_contents($url, false, $context);
        }
        if ($response) {
            return true;
        } else {
            return false;
        }

        // Load Email Library For Sending E-mails
        // $this->load->library('email');
        // $config['mailtype'] = 'html';
        // $this->email->initialize($config);
        // $this->email->from($from_email, $from_name);
        // $this->email->to($mail);
        // $this->email->bcc($bcc_email);
        // $this->email->subject($subject);
        // $this->email->message($message);
        // if ($this->email->send()) {
        //     return true;
        // } else {
        //     return false;
        // }
    }

    // Send SMS Using Curl
    function sendSMS($url = null, $sms_array = null) {
        // API URL

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $sms_array
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($output = curl_exec($ch)) {
            curl_close($ch);
            return $output;
        } else {
            return false;
        }
    }

    //mail for any fund transfer
    function sendTransferEmail($data) {
        if ($data['txn_type'] == "Add") {
            $admin_id = $data['txn_admin_from'];
            $route = $data['txn_route'];
            $type = $data['txn_type'];
            $user_id = $data['txn_user_to'];
            $sms_balance = $data['txn_sms'];
            $sms_price = $data['txn_price'];
            $amount = $data['txn_amount'];
            $date = $data['txn_date'];
            $description = $data['txn_description'];
        }
        if ($data['txn_type'] == "Reduce") {
            $admin_id = $data['txn_admin_to'];
            $route = $data['txn_route'];
            $type = $data['txn_type'];
            $user_id = $data['txn_user_from'];
            $sms_balance = $data['txn_sms'];
            $sms_price = $data['txn_price'];
            $amount = $data['txn_amount'];
            $date = $data['txn_date'];
            $description = $data['txn_description'];
        }
        if ($data['txn_type'] == "Demo") {
            $admin_id = $data['txn_admin_from'];
            $route = $data['txn_route'];
            $type = $data['txn_type'];
            $user_id = $data['txn_user_to'];
            $sms_balance = $data['txn_sms'];
            $sms_price = $data['txn_price'];
            $amount = $data['txn_amount'];
            $date = $data['txn_date'];
            $description = $data['txn_description'];
        }
        if ($data['txn_type'] == "Refund") {
            $admin_id = $data['txn_admin_from'];
            $route = $data['txn_route'];
            $type = $data['txn_type'];
            $user_id = $data['txn_user_to'];
            $sms_balance = $data['txn_sms'];
            $sms_price = $data['txn_price'];
            $amount = $data['txn_amount'];
            $date = $data['txn_date'];
            $description = $data['txn_description'];
        }
        $this->db->select('admin_name');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get();
        $result = $query->row();
        $admin_name = $result->admin_name;

        $this->db->select('name, username');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query1 = $this->db->get();
        $result1 = $query1->row();
        $user = $result1->name;
        $username = $result1->username;
        //source mail id

        $from_email = "info@bulksmsserviceproviders.com";

        //$from_email = "bulk24sms.vijendra@gmail.com";

        $from_name = "Bulk24sms";

        //destination mail id

        $to_email = "info@bulksmsserviceproviders.com" . "," . "Rupal@bulk24sms.com" . "," . "Mayank@bulk24sms.com";
        //$to_email = "bulk24sms.vijendra@gmail.com";
        $subject = "Fund Transfared";
        $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>Mail About Fund Transfared</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>

    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            Bulk SMS Service Providers
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Fund Transfared Info
                                                                                    </h3>
                                                                                    <div  style="width: 140px ; height: 300px; margin-left: 50px;  float: left;">
                                                                                        <br> Admin Name : <br><br>
                                                                                          Name :  <br><br>
                                                                                          Username :  <br><br>
                                                                                      Balance type :  <br><br>  
                                                                                          Message type: <br><br>   
                                                                                            No of message :   <br><br> 
                                                                                            Price :   <br><br> 
                                                                                            Amount : <br><br>
                                                                                           Date & Time :  <br><br> 
                                                                                           Discription :  <br><br>  

                                                                                    </div>
                                                                                      <div  style="width: 140px ; height: 300px; margin-left: 0px;  float: left;">
                                                                                          <br> <label>' . $admin_name . ' <label> <br><br>
                                                                                         <label>' . $user . '<label><br><br>  
                                                                                              <label>' . $username . '<label><br><br>  
                                                                                      <label>' . $route . '<label> <br><br>  
                                                                                          <label>' . $type . '<label> <br><br>   
                                                                                            <label>' . $sms_balance . '<label>  <br><br> 
                                                                                          <label>  ' . $sms_price . '<label>   <br><br> 
                                                                                          <label>' . $amount . '<label>  <br><br>  
                                                                                               <label>' . $date . '<label>  <br><br>  
                                                                                                    <label>' . $description . '<label>  <br><br>  

                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="' . $temp_array[7] . '" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">Bulk SMS Service Providers</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';

        // Load Email Library For Sending E-mails
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    // Disable Users After Expiry Date
    function disableOnExpiry() {
        $current_date = date('d-m-Y');
        $data = array(
            'user_status' => 0
        );
        $this->db->where('expiry_date <', $current_date);
        return $this->db->update('users', $data);
    }

    // Get Number To Words
    function getNumberToWords($number = 0) {
        if (( $number < 0 ) || ($number > 999999999999)) {
            throw new Exception("Number is out of range");
        }
        $Bn = floor($number / 100000000);
        /* Millions (giga) */
        $number -= $Bn * 100000000;
        $Cn = floor($number / 10000000);
        /* Millions (giga) */
        $number -= $Cn * 10000000;
        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $Mn = floor($number / 100000);
        /* Millions (giga) */
        $number -= $Mn * 100000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */
        $res = "";
        if ($Bn) {
            $res .= $this->getNumberToWords($Bn) . " Billion";
        }
        if ($Cn) {
            $res .=(empty($res) ? "" : " " ) . $this->getNumberToWords($Cn) . " Crore";
        }
        if ($Gn) {
            $res .=(empty($res) ? "" : " " ) . $this->getNumberToWords($Gn) . " Million";
        }
        if ($Mn) {
            $res .=(empty($res) ? "" : " " ) . $this->getNumberToWords($Mn) . " Lac";
        }
        if ($kn) {
            $res .=(empty($res) ? "" : " " ) . $this->getNumberToWords($kn) . " Thousand";
        }
        if ($Hn) {
            $res .=(empty($res) ? "" : " ") . $this->getNumberToWords($Hn) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty",
            "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($Dn < 2) {
                $res .= $ones [$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];
                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }
        if (empty($res)) {
            $res = "Zero";
        }
        return $res;
    }

    // Call DLR Push URL Using Curl
    function callDLRPushURL($url = null, $dlr_array = null) {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        // initialize curl handle
        $ch = curl_init();
        // set url to post to
        curl_setopt($ch, CURLOPT_URL, $url);
        // Fail on errors
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // allow redirects
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // return into a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // times out after 15s
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        // add POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . $dlr_array);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        if (curl_exec($ch)) {
            curl_close($ch);
            return true;
        } else {
            return false;
        }
    }

    // Call DLR Push URL Using Curl
    function callLSWebHookURL($url = null, $post_body = null, $count_post = 0) {
        // Open Connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $count_post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /*
          $user_agent = $_SERVER['HTTP_USER_AGENT'];
          // initialize curl handle
          $ch = curl_init();
          // set url to post to
          curl_setopt($ch, CURLOPT_URL, $url);
          // Fail on errors
          curl_setopt($ch, CURLOPT_FAILONERROR, 1);
          // allow redirects
          if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          // return into a variable
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          // times out after 15s
          curl_setopt($ch, CURLOPT_TIMEOUT, 0);
          // add POST fields
          curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . $dlr_array);
          curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         */
        if (curl_exec($ch)) {
            curl_close($ch);
            return true;
        } else {
            return false;
        }
    }

    //==========================================================//
    // Online Users For Chat
    //==========================================================//
    // Update Login Info (Every Time When User Login/Logout)
    function updateLoginInfo($user_id = 0, $status = 0) {
        $last_seen = date('d-m-Y h:i:s');
        $last_activity = strtotime($last_seen);
        $this->db->select('`user_id`');
        $this->db->from('`login_info`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $session_data = $this->session->all_userdata();
            // Update Info
            $data = array(
                'session_id' => $session_data['session_id'],
                'ip_address' => $session_data['ip_address'],
                'log_time' => $last_activity,
                'last_seen' => $last_seen,
                'log_status' => $status
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('login_info', $data);
        } else {
            $session_data = $this->session->all_userdata();
            // Insert Info
            $data = array(
                'session_id' => $session_data['session_id'],
                'user_id' => $user_id,
                'ip_address' => $session_data['ip_address'],
                'log_time' => $last_activity,
                'last_seen' => $last_seen,
                'log_status' => $status
            );
            return $this->db->insert('login_info', $data);
        }
    }

    // Get All Online Users Admin
    function getAllOnlineUsers() {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users2.username AS ref_username, admin_username');
        $this->db->select('users3.username AS parent_username, last_seen');
        $this->db->select('users1.name AS name, users1.email_address AS email_address, users1.contact_number AS contact_number, users1.utype AS utype');
        $this->db->select('pr_user_groups.user_group_name AS pr_user_group_name, pr_user_groups.smsc_id AS pr_smsc_id');
        $this->db->select('tr_user_groups.user_group_name AS tr_user_group_name, tr_user_groups.smsc_id AS tr_smsc_id');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->join('login_info AS login_info', 'login_info.user_id = users1.user_id', 'left');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users1.pro_user_group_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users1.tr_user_group_id', 'left');
        $this->db->where('`log_status`', 'ON');
        $this->db->order_by('users1.username', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get All Online Users Reseller
    function getOnlineUsers($user_id = 0) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users2.username AS ref_username, admin_username');
        $this->db->select('users3.username AS parent_username, last_seen');
        $this->db->select('users1.name AS name, users1.email_address AS email_address, users1.contact_number AS contact_number, users1.utype AS utype');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->join('login_info AS login_info', 'login_info.user_id = users1.user_id', 'left');
        $this->db->where('`users1`.`ref_user_id`', $user_id);
        $this->db->where('`log_status`', 'ON');
        $this->db->order_by('users1.username', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Update User Status (After 20 Min. of Inactivity)
    function updateOnlineStatus($last_seen = null) {
        // Update Info
        $data = array(
            'log_status' => 'OFF'
        );
        $this->db->where('log_time <', $last_seen);
        return $this->db->update('login_info', $data);
    }

    //==========================================================//
    // Get SMSC Balance From API
    function getAllSMSCBalance() {
        $array = array();
        $sms_array = array();
        // Ravindra SMSC (Bulk24SMS TR)
        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=bulk24trn&password=tyvmian";
        if ($response = $this->utility_model->sendSMS($url, $sms_array)) {
            $array['smsc1'] = $response;
        } else {
            $array['smsc1'] = 0;
        }
        // Ravindra SMSC (Bulk24SMS PR)
        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=bulk24pr&password=trgikjlk";
        if ($response = $this->utility_model->sendSMS($url, $sms_array)) {
            $array['smsc2'] = $response;
        } else {
            $array['smsc2'] = 0;
        }
        // Ravindra SMSC (BSSP OPEN)
        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=blk24trn&password=tyvmoigf";
        if ($response = $this->utility_model->sendSMS($url, $sms_array)) {
            $array['smsc3'] = $response;
        } else {
            $array['smsc3'] = 0;
        }
        return $array;
    }

    //==========================================================//
    // Check User Balance Everytime (Every Five Minutes By Cron Job)
    function checkUserBalance() {
        // Default User Group
        $admin_email = "";
        $admin_company = "";
        $full_array = array();
        $result_admin = $this->admin_data_model->getAdminDetails();
        if ($result_admin) {
            $admin_email = $result_admin->admin_email;
            $admin_company = $result_admin->admin_company;
        }
        // Users
        $this->db->select('user_id,username,name, email_address, contact_number, pr_sms_balance, tr_sms_balance, low_balance_alert, low_balance_pr, low_balance_tr,country_status');
        $this->db->from('users');
        $this->db->where('check_demo_user', '0');
        $this->db->like('low_balance_alert', '1|1');
        $this->db->or_like('low_balance_alert', '1|0');
        $this->db->or_like('low_balance_alert', '0|1');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_users = $query->result_array();
            foreach ($result_users as $user) {
                $low_balance_alert = explode('|', $user['low_balance_alert']);
                $low_balance_pr = $user['low_balance_pr'];
                $low_balance_tr = $user['low_balance_tr'];
                $pr_sms_balance = $user['pr_sms_balance'];
                $tr_sms_balance = $user['tr_sms_balance'];
                $contact = $user['contact_number'];
                $username = $user['username'];
                $user_id = $user['user_id'];
                $email_address = $user['email_address'];
                // Low Balance Alert By SMS
                if ($low_balance_alert[0]) {
                    $message = "LOW BALANCE ";
                    $message .= "Please buy sms";
                    if ($pr_sms_balance < $low_balance_pr) {
                        $message.=" Promotional: " . $pr_sms_balance;
                    }
                    if ($tr_sms_balance < $low_balance_tr) {
                        $message.=" Transactional: " . $tr_sms_balance;
                    }

                    $sender_id = "ALERTS";
                    $purpose = "Low Balance Alert";
                    $domain_name = $_SERVER['SERVER_NAME'];
                    $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                    $web_domain = $server_protocol . "://" . $domain_name;
                    $url = $web_domain . "/admin/send_http/";
                    $full_array[] = $sms_array = array(
                        'user_id' => $user_id,
                        'username' => $username,
                        'message' => $message,
                        'purpose' => $purpose
                    );

                    //$this->sendSMS($url, $sms_array);
                }
                // Low Balance Alert By Email
                if ($low_balance_alert[1]) {
                    $subject = "Bulk SMS Alert: Low Balance In Your Account";
                    $body = "";
                    $body .= "<h2>Bulk SMS: Please buy sms!</h2>";
                    $body .= "<h3>You have low balance!</h3>";
                    if ($pr_sms_balance < $low_balance_pr) {
                        $body.="<h4>Promotional SMS: " . $pr_sms_balance . "</h4>";
                    }
                    if ($tr_sms_balance < $low_balance_tr) {
                        $body.="<h4>Transactional SMS: " . $tr_sms_balance . "</h4>";
                    }
                    $body .= "<br/><br/>";
                    $body .= "<p>Keep messaging!</p>";
                    // Prepare Email Array
                    $mail_array = array(
                        'from_email' => $admin_email,
                        'from_name' => $admin_company,
                        'to_email' => $email_address,
                        'subject' => $subject,
                        'message' => $body
                    );
                    
                  
                    $this->sendEmail($mail_array);
                }
            }

            $this->db->insert_batch('low_balance_log', $full_array);
        }
    }

    //==========================================================//
    // Email Templates
    //==========================================================//
    // For Signup (BSSP, Reseller, User Panel And Admin Panel)
    function emailSignup($web_domain = null, $username = null, $company_name = null, $identity = 0) {
        if ($identity) {
            $web_domain.="/signin";
            $web_domain1 = "http://bulksmsserviceproviders.com/";
        } else {
            $web_domain1 = $web_domain;
        }
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
            <title>' . $company_name . '</title>
            <style type="text/css">
                html { background-color:#E1E1E1; margin:0; padding:0; }
                body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
                table{border-collapse:collapse;}
                table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
                img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
                a {text-decoration:none !important;border-bottom: 1px solid;}
                h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
                .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
                table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
                #outlook a{padding:0;}
                img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
                body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
                .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
                h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
                h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
                h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
                h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
                .flexibleImage{height:auto;}
                .linkRemoveBorder{border-bottom:0 !important;}
                table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
                body, #bodyTable{background-color:#E1E1E1;}
                #emailHeader{background-color:#E1E1E1;}
                #emailBody{background-color:#FFFFFF;}
                #emailFooter{background-color:#E1E1E1;}
                .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
                .emailButton{background-color:#205478; border-collapse:separate;}
                .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
                .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
                .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
                .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
                .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
                .imageContentText {margin-top: 10px;line-height:0;}
                .imageContentText a {line-height:0;}
                #invisibleIntroduction {display:none !important;}
                span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
                span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
                span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
                .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
                .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
                @media only screen and (max-width: 480px){
                    body{width:100% !important; min-width:100% !important;}
                    /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                    table[id="emailHeader"],
                    table[id="emailBody"],
                    table[id="emailFooter"],
                    table[class="flexibleContainer"],
                    td[class="flexibleContainerCell"] {width:100% !important;}
                    td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                    td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                    img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                    img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                    table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                    table[class="emailButton"]{width:100% !important;}
                    td[class="buttonContent"]{padding:0 !important;}
                    td[class="buttonContent"] a{padding:15px !important;}

                }
                @media only screen and (-webkit-device-pixel-ratio:.75){
                }
                @media only screen and (-webkit-device-pixel-ratio:1){
                }
                @media only screen and (-webkit-device-pixel-ratio:1.5){
                }
                @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
                }
            </style>
            <!--[if mso 12]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
            <!--[if mso 14]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
        </head>
        <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                        ' . ucwords($company_name) . '
                                                                        </h1>
                                                                        <h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:135%;">
                                                                        Bulk SMS Provider to make your brand shine
                                                                        </h2>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                        We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                    Account Details
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                    We are glad that you signed up! Your login details are given below!
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- URL -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">URL</h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        <a href="' . $web_domain . '" target="_blank">
                                                                                            ' . ucwords($company_name) . '
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                    Username
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                    ' . $username . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Password -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px 30px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">Password</h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> Sent to registered mobile number.</div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="' . $web_domain1 . '" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">' . ucwords($company_name) . '</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
        </body>
        </html>';
        return $body;
    }

    function emailbulk24smsSignup($web_domain = null, $username = null, $company_name = null, $identity = 0) {
        if ($identity) {
            $web_domain.="/signin";
            $web_domain1 = "http://www.bulk24sms.com/";
        } else {
            $web_domain1 = $web_domain;
        }
        $company_name = "BULK24SMS Network";
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
            <title>' . $company_name . '</title>
            <style type="text/css">
                html { background-color:#E1E1E1; margin:0; padding:0; }
                body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
                table{border-collapse:collapse;}
                table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
                img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
                a {text-decoration:none !important;border-bottom: 1px solid;}
                h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
                .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
                table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
                #outlook a{padding:0;}
                img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
                body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
                .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
                h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
                h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
                h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
                h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
                .flexibleImage{height:auto;}
                .linkRemoveBorder{border-bottom:0 !important;}
                table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
                body, #bodyTable{background-color:#E1E1E1;}
                #emailHeader{background-color:#E1E1E1;}
                #emailBody{background-color:#FFFFFF;}
                #emailFooter{background-color:#E1E1E1;}
                .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
                .emailButton{background-color:#205478; border-collapse:separate;}
                .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
                .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
                .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
                .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
                .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
                .imageContentText {margin-top: 10px;line-height:0;}
                .imageContentText a {line-height:0;}
                #invisibleIntroduction {display:none !important;}
                span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
                span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
                span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
                .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
                .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
                @media only screen and (max-width: 480px){
                    body{width:100% !important; min-width:100% !important;}
                    /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                    table[id="emailHeader"],
                    table[id="emailBody"],
                    table[id="emailFooter"],
                    table[class="flexibleContainer"],
                    td[class="flexibleContainerCell"] {width:100% !important;}
                    td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                    td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                    img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                    img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                    table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                    table[class="emailButton"]{width:100% !important;}
                    td[class="buttonContent"]{padding:0 !important;}
                    td[class="buttonContent"] a{padding:15px !important;}

                }
                @media only screen and (-webkit-device-pixel-ratio:.75){
                }
                @media only screen and (-webkit-device-pixel-ratio:1){
                }
                @media only screen and (-webkit-device-pixel-ratio:1.5){
                }
                @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
                }
            </style>
            <!--[if mso 12]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
            <!--[if mso 14]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
        </head>
        <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                        ' . ucwords($company_name) . '
                                                                        </h1>
                                                                        <h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:135%;">
                                                                        Bulk SMS Provider to make your brand shine
                                                                        </h2>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                        We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                    Account Details
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                    We are glad that you signed up! Your login details are given below!
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- URL -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">URL</h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        <a href="http://www.bulk24sms.com/signin.php" target="_blank">
                                                                                            ' . ucwords($company_name) . '
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                    Username
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                    ' . $username . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Password -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px 30px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">Password</h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> Sent to registered mobile number.</div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="' . $web_domain1 . '" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">' . ucwords($company_name) . '</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
        </body>
        </html>';
        return $body;
    }

    // For Account Manager
    function emailSignupAM($username = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
            <title>Bulk SMS Service Providers</title>
            <style type="text/css">
                html { background-color:#E1E1E1; margin:0; padding:0; }
                body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
                table{border-collapse:collapse;}
                table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
                img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
                a {text-decoration:none !important;border-bottom: 1px solid;}
                h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
                .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
                .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
                table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
                #outlook a{padding:0;}
                img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
                body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
                .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
                h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
                h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
                h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
                h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
                .flexibleImage{height:auto;}
                .linkRemoveBorder{border-bottom:0 !important;}
                table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
                body, #bodyTable{background-color:#E1E1E1;}
                #emailHeader{background-color:#E1E1E1;}
                #emailBody{background-color:#FFFFFF;}
                #emailFooter{background-color:#E1E1E1;}
                .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
                .emailButton{background-color:#205478; border-collapse:separate;}
                .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
                .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
                .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
                .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
                .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
                .imageContentText {margin-top: 10px;line-height:0;}
                .imageContentText a {line-height:0;}
                #invisibleIntroduction {display:none !important;}
                span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
                span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
                span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
                .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
                .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
                @media only screen and (max-width: 480px){
                    body{width:100% !important; min-width:100% !important;}
                    /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                    table[id="emailHeader"],
                    table[id="emailBody"],
                    table[id="emailFooter"],
                    table[class="flexibleContainer"],
                    td[class="flexibleContainerCell"] {width:100% !important;}
                    td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                    td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                    img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                    img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                    table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                    table[class="emailButton"]{width:100% !important;}
                    td[class="buttonContent"]{padding:0 !important;}
                    td[class="buttonContent"] a{padding:15px !important;}

                }
                @media only screen and (-webkit-device-pixel-ratio:.75){
                }
                @media only screen and (-webkit-device-pixel-ratio:1){
                }
                @media only screen and (-webkit-device-pixel-ratio:1.5){
                }
                @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
                }
            </style>
            <!--[if mso 12]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
            <!--[if mso 14]>
                    <style type="text/css">
                            .flexibleContainer{display:block !important; width:100% !important;}
                    </style>
            <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            Bulk SMS Service Providers
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Account Details
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        We are glad that you are part of our team!
                                                                                    </div>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        Your login details are given below!
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- URL -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">URL</h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        <a href="http://sms.bulksmsserviceproviders.com/admin/" target="_blank">
                                                                                            Bulk SMS Service Providers Admin Panel
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Username
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $username . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Password -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px 30px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Password
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        Sent to registered mobile number.
                                                                                    </div>                                                                                    
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="http://bulksmsserviceproviders.com" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">Bulk SMS Service Providers</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    // For BSSP Authorized Access
    function emailAuthAdmin($ip_address = null, $contact_number = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>Bulk SMS Service Providers</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>
        <!--[if mso 12]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
        <!--[if mso 14]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            Bulk SMS Service Providers
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Warning
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        Someone access BSSP Admin Panel. Please verify and take an action
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- URL -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Request From IP Address
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        ' . $ip_address . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Number
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $contact_number . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Password -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px 30px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Date & Time
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . date('d-m-Y h:i:s A') . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="http://bulksmsserviceproviders.com/" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">Bulk SMS Service Providers</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    // For BSSP Unauthorized Access
    function emailUnAuthAdmin($ip_address = null, $contact_number = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>Bulk SMS Service Providers</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>
        <!--[if mso 12]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
        <!--[if mso 14]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            Bulk SMS Service Providers
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Warning
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        Someone try to access BSSP Admin Panel.
                                                                                    </div>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        Please verify and take an action
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- URL -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Request From IP Address
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                                        ' . $ip_address . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Number
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $contact_number . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Password -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px 30px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Date & Time
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . date('d-m-Y h:i:s A') . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="http://bulksmsserviceproviders.com/" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">Bulk SMS Service Providers</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    // For BSSP Notification
    function emailNotification($company_name = null, $message = null) {
        $body = '<html> <body>
  <pre style="font-family: sans-serif;font-size: 14px;">
Dear Valuable Customer,

We are pleased to announce the launch of our newly revamped website https://bulksmsserviceproviders.com, that aims to create a user-friendly browsing experience for our trusted and valued customers.

In addition to re-designing our website, we have also refined the product category and menus structure for an enhanced user experience in accessing information relating to our products.

We invite you to view the new website and let us know your thoughts.If you have any questions or feedback you would like to share with our team, please do so by filling out the form on <a href="https://www.bulksmsserviceproviders.com/contact" style="">Contact Us  </a>
 
<b>Wish you a Very Colorful and Vivacious Holi to You and Your Family.</b> For Holi Offers Contact your Account Manager. 

 --
 Team <a href="https://bulksmsserviceproviders.com">Bulksmsserviceproviders</a>
 +918982-805000</pre>
  </body> </html>';
        return $body;
    }

    // For Bulk Signup Info (BSSP & Resellers)
    function emailSignInfo($temp_array = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>' . $temp_array[0] . '</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>
        <!--[if mso 12]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
        <!--[if mso 14]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            ' . $temp_array[0] . '
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        New User Info
                                                                                    </h3>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Name -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Name
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[1] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Username
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[2] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Contact -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Number
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[3] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Email -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Email Address
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[4] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Company -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Company Name
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[5] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Industry -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Industry
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[6] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="' . $temp_array[7] . '" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">' . $temp_array[0] . '</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    function emailSignInfoBulk24SMS($temp_array = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>' . $temp_array[0] . '</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>
        <!--[if mso 12]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
        <!--[if mso 14]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            ' . $temp_array[0] . '
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        New User Info
                                                                                    </h3>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Name -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Name
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[1] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Username -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Username
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[2] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Contact -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Number
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[3] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Email -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Email Address
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[4] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Company -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Company Name
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[5] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Industry -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Industry
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[6] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                            <a href="' . $temp_array[7] . '" target="_blank" style="text-decoration:none;color:#828282;">
                                                                                                <span style="color:#828282;">' . $temp_array[0] . '</span>
                                                                                            </a>
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    // For Bulk Contact Info (BSSP & Resellers)
    function emailContactInfo($temp_array = null) {
        $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
        <title>' . $temp_array[0] . '</title>
        <style type="text/css">
            html { background-color:#E1E1E1; margin:0; padding:0; }
            body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, "Lucida Grande", sans-serif;}
            table{border-collapse:collapse;}
            table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;}
            img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;}
            a {text-decoration:none !important;border-bottom: 1px solid;}
            h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;}
            .ReadMsgBody{width:100%;} .ExternalClass{width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;}
            table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;}
            #outlook a{padding:0;}
            img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;}
            body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;}
            .ExternalClass td[class="ecxflexibleContainerBox"] h3 {padding-top: 10px !important;}
            h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;}
            h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;}
            h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;}
            h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;}
            .flexibleImage{height:auto;}
            .linkRemoveBorder{border-bottom:0 !important;}
            table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;}
            body, #bodyTable{background-color:#E1E1E1;}
            #emailHeader{background-color:#E1E1E1;}
            #emailBody{background-color:#FFFFFF;}
            #emailFooter{background-color:#E1E1E1;}
            .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;}
            .emailButton{background-color:#205478; border-collapse:separate;}
            .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;}
            .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;}
            .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;}
            .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;}
            .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;}
            .imageContentText {margin-top: 10px;line-height:0;}
            .imageContentText a {line-height:0;}
            #invisibleIntroduction {display:none !important;}
            span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;}
            span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;}
            span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;}
            .a[href^="tel"], a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;}
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;}
            @media only screen and (max-width: 480px){
                body{width:100% !important; min-width:100% !important;}
                /*td[class="textContent"], td[class="flexibleContainerCell"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; }*/
                table[id="emailHeader"],
                table[id="emailBody"],
                table[id="emailFooter"],
                table[class="flexibleContainer"],
                td[class="flexibleContainerCell"] {width:100% !important;}
                td[class="flexibleContainerBox"], td[class="flexibleContainerBox"] table {display: block;width: 100%;text-align: left;}
                td[class="imageContent"] img {height:auto !important; width:100% !important; max-width:100% !important; }
                img[class="flexibleImage"]{height:auto !important; width:100% !important;max-width:100% !important;}
                img[class="flexibleImageSmall"]{height:auto !important; width:auto !important;}
                table[class="flexibleContainerBoxNext"]{padding-top: 10px !important;}
                table[class="emailButton"]{width:100% !important;}
                td[class="buttonContent"]{padding:0 !important;}
                td[class="buttonContent"] a{padding:15px !important;}

            }
            @media only screen and (-webkit-device-pixel-ratio:.75){
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
            }
            @media only screen and (min-device-width : 320px) and (max-device-width:568px) {
            }
        </style>
        <!--[if mso 12]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
        <!--[if mso 14]>
                <style type="text/css">
                        .flexibleContainer{display:block !important; width:100% !important;}
                </style>
        <![endif]-->
    </head>
    <body bgcolor="#E1E1E1" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center style="background-color:#E1E1E1;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell">
                        <table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="500" id="emailBody">
                            <!-- Header -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#3498db">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top" class="textContent">
                                                                        <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">
                                                                            ' . $temp_array[0] . '
                                                                        </h1>
                                                                        <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                                            We are simple and better. You will get dedicated support on this clean & powerful platform.
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Description -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 15px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Info
                                                                                    </h3>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Name -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Name
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[1] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Contact -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Contact Number
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[2] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Email -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Email Address
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[3] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <!-- Message -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#F8F8F8">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: 10px 30px !important;">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <h3 mc:edit="header" style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;">
                                                                                        Message
                                                                                    </h3>
                                                                                    <div mc:edit="body" style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;"> 
                                                                                        ' . $temp_array[4] . '
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td align="center" valign="top">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top">
                                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="flexibleContainer">
                                                    <tr>
                                                        <td align="center" valign="top" width="500" class="flexibleContainerCell">
                                                            <table border="0" cellpadding="30" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td align="center" valign="top">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                            <tr>
                                                                                <td valign="top" class="textContent">
                                                                                    <div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
                                                                                        <div>Copyright &#169; 2015
                                                                                                ' . $temp_array[0] . '
                                                                                        </div>
                                                                                        <br/>
                                                                                        <div>
                                                                                            All&nbsp;rights&nbsp;reserved.                                                                                            
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>';
        return $body;
    }

    //==========================================================//
    // Send SMS With XML Using Curl
    function sendSMSXML($url = null, $xml = null) {
        //$user_agent = $_SERVER['HTTP_USER_AGENT'];
        // initialize curl handle
        $ch = curl_init();
        // set url to post to
        curl_setopt($ch, CURLOPT_URL, $url);
        // Fail on errors
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        // allow redirects
        if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // return into a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // times out after 15s
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        // add POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data=' . $xml);
        //curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if ($output = curl_exec($ch)) {
            curl_close($ch);
            return $output;
        } else {
            return $output;
        }
    }

    // Update SMS Status For XML Route Requests
    function updateXMLSMSStatus($request = null) {
        $jsonData = json_decode($request);
        if ($jsonData) {
            foreach ($jsonData as $value) {
                $request_id = "";
                if (is_object($value)) {
                    $request_id = $value->requestId;
                } elseif (is_array($value)) {
                    $request_id = $value['requestId'];
                }
                // Get Campaign Id
                $this->db->select('campaign_id, campaign_uid');
                $this->db->from('campaigns');
                $this->db->where('is_xml', '1');
                $this->db->where('campaign_uid', $request_id);
                //$this->db->where('sender_id', $senderId);
                $query = $this->db->get();
                if ($query->num_rows()) {
                    $result_campaign = $query->row();
                    if ($result_campaign) {
                        $campaign_id = $result_campaign->campaign_id;
                        // Details of Each Number
                        $details = $value['numbers'];
                        /*
                          if (is_object($value)) {
                          $details = $value->numbers;
                          } elseif (is_array($value)) {
                          $details = $value['numbers'];
                          }
                         */
                        $array = json_decode(json_encode($details), true);
                        foreach ($array as $number => $value1) {
                            $dlr_receipt = "";
                            $status = "";
                            $done_date = "";
                            if (is_object($value1)) {
                                // Detail Description of Report
                                $dlr_receipt = $value1->desc;
                                // Status of Each Number
                                $status = $value1->status;
                                // Destination Number
                                $receiver = $number;
                                // Delivery Report Time
                                $done_date = date('Y-m-d H:i:s');
                            } elseif (is_array($value1)) {
                                // Detail Description of Report
                                $dlr_receipt = $value1['desc'];
                                // Status of Each Number
                                $status = $value1['status'];
                                // Destination Number
                                $receiver = $number;
                                // Delivery Report Time
                                $done_date = date('Y-m-d H:i:s');
                            }
                            // Preapare Data
                            $data = array(
                                'status' => $status,
                                'done_date' => $done_date,
                                'dlr_receipt' => $dlr_receipt
                            );
                            $this->db->where('campaign_id', $campaign_id);
                            $this->db->where('mobile_no', $receiver);
                            $this->db->update('sent_sms', $data);
                        }
                    }
                }
            }
        }
    }

    // Update SMS Status For XML Route Requests
    function updateXMLSMSStatus1($request = null) {
        $jsonData = json_decode($request, true);
        $jsonData = json_decode(json_encode(json_decode($request)), true);
        if (sizeof($jsonData) && $jsonData) {
            foreach ($jsonData as $key => $value) {
                // Request ID, Userid, Sender Id
                $request_id = $value['requestId'];
                //$userId = $value['userId'];
                //$senderId = $value['senderId'];
                // Get Campaign Id
                $this->db->select('campaign_id, campaign_uid');
                $this->db->from('campaigns');
                $this->db->where('is_xml', '1');
                $this->db->where('campaign_uid', $request_id);
                //$this->db->where('sender_id', $senderId);
                $query = $this->db->get();
                if ($query->num_rows()) {
                    $result_campaign = $query->row();
                    if ($result_campaign) {
                        $campaign_id = $result_campaign->campaign_id;
                        foreach ($value['report'] as $key1 => $value1) {
                            // Detail Description of Report
                            $dlr_receipt = $value1['desc'];
                            // Status of Each Number
                            $status = $value1['status'];
                            // Destination Number
                            $receiver = $value1['number'];
                            // Delivery Report Time
                            $done_date = date('Y-m-d H:i:s');
                            // Preapare Data
                            $data = array(
                                'status' => $status,
                                'done_date' => $done_date,
                                'dlr_receipt' => $dlr_receipt
                            );
                            $this->db->where('campaign_id', $campaign_id);
                            $this->db->where('mobile_no', $receiver);
                            $this->db->update('sent_sms', $data);
                        }
                    }
                }
            }
        }
    }

    //==========================================================//
    function testDBTxn() {
        $this->db->trans_begin();
        for ($i = 0; $i <= 5; $i++) {
            $data = array(
                'name' => 'Name' . $i,
                'contact' => '999990000' . $i,
                'email' => 'email' . $i . '@domain.com'
            );
            $this->db->where('id', $i);
            $this->db->update('dummy', $data);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function ilegalActivity($web_string) {
        $our_string = "hgh5fg5df6gfgh5gfszhtc4k56ja5n45hjzkjfrlkk445696gdf5gfsfsdhjc8vbkjsxhjfc5gnbhxx8nhjd5ibhdb2ujlnmhjsf565g2h";

        if ($our_string == $web_string) {
            return true;
        } else {
            return FALSE;
        }
    }

    public function ilegalAdminActivity($web_string_admin) {
        $our_string = "hgh5nhjdfg5n2s5f5vb5gf5bf5fvx5ds5cc5ghd5sax5g5fgef5sdw5fsx2cds2f5fs645sc64d5f4s5a4d3fh54jhn54vgjhgfkfhjdsffgnj54j64n5jh14fggh5j411532fg1h53245322h";

        if ($our_string == $web_string_admin) {
            return true;
        } else {
            return FALSE;
        }
    }

    //==========================================================//

 // Send E-Mail for suscription  test
  public function sendEmailNew_test()  {
     $start_date = $this->input->post('to_date');
     $end_date = $this->input->post('from_date');
     $subject =  $this->input->post('subject');
     $tempalte = $this->input->post('tempalte');
     $template_message ='<html><body><pre style="color:black; font-size: 16px; font-family: sans-serif;" >'. $tempalte.'</pre></body> </html>';
      $this->db->select('subscribe_email');
      $this->db->from('subscription');
      $this->db->where("`subscribe_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
         $query = $this->db->get();
         $result = $query->result_array();
         foreach($result  as $val)  {
         $mail_list[] = $val['subscribe_email'];
         //print_r($mail_list);
}

    define('MULTIPART_BOUNDARY', '----'.md5(time()));
    define('EOL',"\r\n");// PHP_EOL cannot be used for emails we need the CRFL '\r\n'

    function getBodyPart($FORM_FIELD, $value) {
        if ($FORM_FIELD === 'attachment') {
            $content = 'Content-Disposition: form-data; name="'.$FORM_FIELD.'"; filename="'.basename($value).'"' . EOL;
            //$content .= 'Content-Type: '.mime_content_type($value) . EOL;
            $content .= 'Content-Transfer-Encoding: binary' . EOL;
            $content .= EOL . file_get_contents($value) .EOL;
        } else {
            $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . EOL;
            $content .= EOL . $value . EOL;
        }

        return $content;
    }
   function getBody($fields) {
        $content = '';
        foreach ($fields as $FORM_FIELD => $value) {
            $values = is_array($value) ? $value : array($value);
            foreach ($values as $v) {
                $content .= '--' . MULTIPART_BOUNDARY . EOL . getBodyPart($FORM_FIELD, $v);
            }
        }
        return $content . '--' . MULTIPART_BOUNDARY . '--'; // Email body should end with "--"
    }
    function getHeader($username, $password) {
        // basic Authentication
        $auth = base64_encode("$username:$password");
    return array('Authorization:Basic '.$auth, 'Content-Type: multipart/form-data ; boundary=' . MULTIPART_BOUNDARY );
    }
    $url = 'http://xv83g.api.infobip.com/email/1/send';
    for($i=0; $i<count($mail_list);  $i++) {
   
      $to_mail=$mail_list[$i];
   
      print_r($template_message);
   
      print_r($subject);
     // print_r('<br>');
    // Associate Array of the post parameters to be sent to the API
     $postData = array(
        'from' => 'BULKSMS <Bulksmsserviceproviders@yourinbox.in>',
         'to' => $to_mail,
         'replyTo' => $to_mail,
        'subject' => $subject,
         'html' => $template_message 
     );
   // Create the stream context.
    $context = stream_context_create(array(
              'http' => array(
              'method' => 'POST',
              'header' => getHeader('EmailSRTS', 'Srts@123'),
              'content' =>  getBody($postData),
       )
     ));
    // init the resource
    $ch = curl_init();
   curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData
    //,CURLOPT_FOLLOWLOCATION => true
));
//Ignore SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
 $output = curl_exec($ch);
  // Read the response using the Stream Context.
 $response = file_get_contents($url, false, $context);
}
  }

 //end of subscripition part
 // Send E-Mail for daily_signup_test
  
   public function daily_signup_mailer() {
   	  echo "welcome to signup";
      $start_date = '17-02-2015 ';
      $end_date = '17-03-2015';
   // $subject =  $this->input->post('subject');
   //$tempalte = $this->input->post('tempalte');
  // $template_message ='<html><body><pre style="color:black; font-size: 16px; font-family: sans-serif;" >'. $tempalte.'</pre></body> </html>';
       $this->db->select('*');
       $this->db->from('users');
       $this->db->where('admin_id',1);
       $this->db->where("`creation_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
         $query = $this->db->get();
         $result = $query->result_array();
         print_r($result);
         foreach($result  as $val)  {
         $mail_list[] = $val['subscribe_email'];
         print_r($mail_list);
         //print_r($mail_list);
     }

//     define('MULTIPART_BOUNDARY', '----'.md5(time()));
//     define('EOL',"\r\n");// PHP_EOL cannot be used for emails we need the CRFL '\r\n'

//     function getBodyPart($FORM_FIELD, $value) {
//         if ($FORM_FIELD === 'attachment') {
//             $content = 'Content-Disposition: form-data; name="'.$FORM_FIELD.'"; filename="'.basename($value).'"' . EOL;
//             //$content .= 'Content-Type: '.mime_content_type($value) . EOL;
//             $content .= 'Content-Transfer-Encoding: binary' . EOL;
//             $content .= EOL . file_get_contents($value) .EOL;
//         } else {
//             $content = 'Content-Disposition: form-data; name="' . $FORM_FIELD . '"' . EOL;
//             $content .= EOL . $value . EOL;
//         }

//         return $content;
//     }
//    function getBody($fields) {
//         $content = '';
//         foreach ($fields as $FORM_FIELD => $value) {
//             $values = is_array($value) ? $value : array($value);
//             foreach ($values as $v) {
//                 $content .= '--' . MULTIPART_BOUNDARY . EOL . getBodyPart($FORM_FIELD, $v);
//             }
//         }
//         return $content . '--' . MULTIPART_BOUNDARY . '--'; // Email body should end with "--"
//     }
//     function getHeader($username, $password) {
//         // basic Authentication
//         $auth = base64_encode("$username:$password");
//     return array('Authorization:Basic '.$auth, 'Content-Type: multipart/form-data ; boundary=' . MULTIPART_BOUNDARY );
//     }
//     $url = 'http://xv83g.api.infobip.com/email/1/send';
//     for($i=0; $i<count($mail_list);  $i++) {
   
//       $to_mail=$mail_list[$i];
   
//       print_r($template_message);
   
//       print_r($subject);
//      // print_r('<br>');
//     // Associate Array of the post parameters to be sent to the API
//      $postData = array(
//         'from' => 'BULKSMS <Bulksmsserviceproviders@yourinbox.in>',
//          'to' => $to_mail,
//          'replyTo' => $to_mail,
//         'subject' => $subject,
//          'html' => $template_message 
//      );
//    // Create the stream context.
//     $context = stream_context_create(array(
//               'http' => array(
//               'method' => 'POST',
//               'header' => getHeader('EmailSRTS', 'Srts@123'),
//               'content' =>  getBody($postData),
//        )
//      ));
//     // init the resource
//     $ch = curl_init();
//     curl_setopt_array($ch, array(
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => $postData
//     //,CURLOPT_FOLLOWLOCATION => true
// ));
// //Ignore SSL certificate verification
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// //get response
//  $output = curl_exec($ch);
//   // Read the response using the Stream Context.
//  $response = file_get_contents($url, false, $context);
// }
  } 
   public function meeting_mailer_test()  {
   	echo "hello model";
	print_r($_REQUEST);
   	
     // $start_date = $this->input->post('to_date');
     // $end_date = $this->input->post('from_date');
     // $subject =  $this->input->post('subject');
     // $tempalte = $this->input->post('tempalte');
     // $template_message ='<html><body><pre style="color:black; font-size: 16px; font-family: sans-serif;" >'. $tempalte.'</pre></body> </html>';
     // print_r($_REQUEST);
     

//       $this->db->select('*');
//       $this->db->from('user_meetings');
//       $this->db->where("`DateTime` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
//          $query = $this->db->get();
//          $result = $query->result_array();
//         print_r($result);

//          foreach($result  as $val)  {
//          $mail_list[] = $val['subscribe_email'];
//          print_r($mail_list);
// }

   }
 
 }

?>