<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

     function __construct(){
      parent::__construct();
      date_default_timezone_set('Asia/Kolkata');
    $this->load->model('Bulksms_model','Bulksms_model');
  

  // $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
  // if(stripos($ua,'android') !== false) { // && stripos($ua,'mobile') !== false) {
  // }else{
  // }

  }
   

 public function send_bulk_sms1() {
if(isset($_GET['msg']) &&  isset($_GET['time'])){
  echo '<meta http-equiv="refresh" content="'.$_GET['time'].'">';
	if(isset($_GET['text'])){  
		  $msgdata = $_GET['text'];
	}else{
	$msgdata = "I AM MESSAGE ".date('Y-m-d h:i:s').' I Am DATE TIME ';	
	}

	if(isset($_GET['sender'])){
		$senderId = $_GET['sender'];
	}else{

  $string  = str_shuffle('ABCDEFGIJKLMNOPQRSTUVWXYZ');
  $newstr= substr($string, 20);
  $str = time();
  $senderId= $newstr."S";
	}

  $Count  =$_GET['msg'];
  echo $cnt =  strlen($msgdata);
	echo "<br>";
	echo "total Credit";
	echo "<br>";
  
   $credit = $cnt/160;
   echo " : ".$fcredit = ceil($credit);
  echo "<br>";
  echo "<br>";
   ini_set('max_input_time', 2400);
   ini_set('max_execution_time', 2400);
   ini_set('memory_limit', '107374182400');

  $data_campaign = array(
        'campaign_uid' => md5(rand(99999,11111)),
        'user_group_id' => 1,
        'campaign_name' => "New Capaingn",
        'admin_id' => 1, 
        'user_id' => 8937,
        'total_messages' => $Count,
        'total_credits' => $fcredit,
        'total_deducted' => $fcredit,
        'actual_message' => $Count,
        'sender_id' => $senderId,
        'request_by' => "By Panel",
        'submit_date' => date('Y-m-d H:i:s'),
        'message_type' => 1,
        'flash_message' => 0,
        'message' => $msgdata,
        'message_length' => 120,
        'route' => 'A',
        'black_listed' => "0|0",
        'is_xml' => 0,
        'reseller_key_balance_status' => '1',
        'pricing_error' => 0
    );

	$response_cm = $this->Bulksms_model->insertCampaign($data_campaign);

  $campaign_id = $this->db->insert_id();
    
      $mobile = "9144".rand(88888888,11111111);
      for ($i = 1; $i <=$Count; $i++) {
          $sent_sms[] = $data = array(
              'user_group_id' => 1,
              'campaign_id' => $campaign_id,
              'user_id' => 8937, 
              'msg_id' =>  md5(rand(99999,11111)),
              'message_id' => "",
              'message' => $msgdata,
              "msg_length" => 120, 
              'mobile_no' => $mobile + $i,
              'status' => 31,
              "submit_date" => date("Y-m-d H:i:s"),
              "dlr_status" => 0,
              "temporary_status" => 1,
              "default_route" => "MYSMPP1",
              "reseller_key_balance_status" => 1
          ); 



           $sqlbox_send_sms[] = $data = array(
                'momt' => "MT",
                'sender' => $senderId,
                'receiver' => $mobile + $i,
                'msgdata' => $msgdata,
                'smsc_id' => "MYSMPP1",
                "sms_type" => 2, 
                'dlr_mask' => 31,
                'dlr_url' => $campaign_id,
                "charset" => "UTF-8",
                'boxc_id' => "sqlbox",
                'validity'=>360,
                "priority" => 1
            ); 


 
      } 

     $this->db->insert_batch('sent_sms', $sent_sms);
     $this->db->insert_batch('sqlbox_send_sms', $sqlbox_send_sms);
echo $Count." Msg Sent";

}
    }


     
function smsbox() {
  echo '<meta http-equiv="refresh" content="1">';
   $mobile = 9826448836;
        for ($i = 1; $i <=100; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       //print_r($numbers);
       $url = "http://127.0.0.1:18010/cgi-bin/sendsms?username=tester&password=tester&text=HEllo+brothers&to=".$numbers."&from=VIJOHN&smsc=MYSMPP1&dlr-mask=1";
   file_get_contents($url);
   echo sizeof($full_data)." Data Sent FROM SMSBOX";
}






 public function index() {
        $this->load->view('welcome_message');
    }




    public function send_bulk_sms() {



        echo ' <form action="" method="post" style="text-align:center;  ">
  <div class="container"  >
   
    SENDER<br>
    <input type="text" placeholder="Enter sender 6 alphabetic" name="sender" value="" required style="width:300px;">
    <br>
    MESSAGE<br>
     <textarea rows="4" cols="50" placeholder="Message msgdata" name="msgdata" required style="width:300px;"></textarea>
    <br>
    NUMBER OF MESSAGE<br>
    <input type="number" placeholder="Number of message" name="number" value="10" required style="width:300px;">
    <br>
    <hr>

    <button type="submit" name="submit" class="registerbtn" style="width:100px;">Send</button>
  </div>
</form> ';
if(isset($_REQUEST['submit'])){
        $full_data = array();
        $type = "MT";
        $dlr_mask = 31;
        $smsc_id = "MYSMPP1";
        //$message = "TEST1122";
       //echo  $message = "Message: The date and time is ".date('y-m-d h:i:s A ').microtime().rand(9999999999,11111111111);
        $sqlbox = "sqlbox";
        $mobile = "9926000000";
        for ($i = 1; $i <=$_REQUEST['number']; $i++) {
            $full_data[] = $data = array(
                'momt' => $type,
                'sender' => $_REQUEST['sender'],
                'receiver' => $mobile + $i,
                'msgdata' => $_REQUEST['msgdata'],
                'smsc_id' => $smsc_id,
                "sms_type" => 2, 
                'dlr_mask' => 31,
                'dlr_url' => 'http://srts.in/smsbox/get.php',
                "charset" => "UTF-8",
                'boxc_id' => $sqlbox,
                "priority" => 1
            ); 
        } 

       
       $this->db->insert_batch('sqlbox_send_sms', $full_data);
         echo sizeof($full_data)."Data Send successfully";
        redirect('send');
               
   }


    }
    


    function newkannelamit() {

 $mobile = "992600".rand(9999,1111);
        for ($i = 1; $i <=4000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
    $url = "http://34.93.48.63:18010/cgi-bin/sendsms?username=tester&password=tester&text=Iammsg&to=".$numbers."&from=BLKSMV&smsc=MYSMPP1";
   file_get_contents($url);
   echo sizeof($full_data)." Data Sent";

}





function mykannel() {
  //echo '<meta http-equiv="refresh" content="2">';
   $mobile = 9826448836;
        for ($i = 1; $i <=1000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       print_r($numbers);
       $url = "http://34.77.121.216:18010/cgi-bin/sendsms?username=tester&password=tester&text=NEWTESTING&to=".$numbers."&from=POWER7&smsc=MYSMPP1&dlr-mask=31";
   file_get_contents($url);
   echo sizeof($full_data)." Data Sent";
}






function marry() {

//  echo '<meta http-equiv="refresh" content="2">';
 $mobile = 9826448836;
        for ($i = 1; $i <=1000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       print_r($numbers);
$url = "http://34.93.14.199:18010/cgi-bin/sendsms?username=tester&password=tester&charset=ascii&text=hello-I-am_from_MARRY&to=".$numbers."&from=marry&smsc=kannel1";
   file_get_contents($url);
   echo sizeof($full_data)." Data Sent";
}






function tolj() {


 $mobile = 9826448836;
        for ($i = 1; $i <=1000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       $url = "http://35.154.249.234:12013/cgi-bin/sendsms?username=tester&password=foobar&text=HELLO&to=".$numbers."&from=TOLJ&smsc=MYSMPP1";
   file_get_contents($url);
   echo sizeof($full_data)." Data Sent";
}





function devid() {
	//echo '<meta http-equiv="refresh" content="2">';
 $mobile = 9826448836;
        for ($i = 1; $i <=1000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       print_r($numbers);
       $url = "http://34.93.14.199:18010/cgi-bin/sendsms?username=tester&password=tester&text=NEWTESTING&to=".$numbers."&from=KABBIT&smsc=kannel2";
   file_get_contents($url);
      echo sizeof($full_data)." Data Sent";
}




function bharat() {
  //echo '<meta http-equiv="refresh" content="2">';
 $mobile = 9826448836;
        for ($i = 1; $i <=1000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       print_r($numbers);
               $url = "http://34.93.14.199:18010/cgi-bin/sendsms?username=tester&password=tester&charset=ascii&text=hello-Iambharat&to=".$numbers."&from=bharat&smsc=kannel3";
   file_get_contents($url);
  echo sizeof($full_data)." Data Sent";
}




function naveen() {
 //echo '<meta http-equiv="refresh" content="2">';
 $mobile = 9826448836;
        for ($i = 1; $i <=4000; $i++) {
            $full_data[] = $mobile + $i; 
        } 
       $numbers  = implode('+', $full_data);
       print_r($numbers);

       $url = "http://35.244.165.213:18010/cgi-bin/sendsms?username=tester&password=tester&charset=ascii&text=hello-I-am_from_NAVEEN&to=".$numbers."&from=NAVEEZ&smsc=MYSMPP1";
   file_get_contents($url);
  echo sizeof($full_data)." Data Sent";

}

// Google URL Shortner
    function googleUrlShortner($url = null) {
        $this->load->library('google_url_api');
        $short_url = $this->google_url_api->shorten('http://sms.theofficearea.in/attachment/Parichay.pdf');
        return $short_url;
    }





public function send_sms() {
      
      
        $request = $_POST['data'];
        $my_data = json_decode($request);
        $array = json_decode(json_encode($my_data), true);

        $this->db->insert_batch('sqlbox_send_sms', $array);
    }


public function send() {
      

if(isset($_GET['msg']) &&  isset($_GET['time'])){
  echo '<meta http-equiv="refresh" content="'.$_GET['time'].'">';

file_get_contents('http://kannel.theofficearea.in/bulksms/welcome/send_bulk_sms1?time='.$_GET['time'].'&msg='.$_GET['msg']);
file_get_contents('http://kannelconfig.com/bulksms/welcome/send_bulk_sms1?time='.$_GET['time'].'&msg='.$_GET['msg']);
file_get_contents('http://srts.in/bulksms/welcome/send_bulk_sms1?time='.$_GET['time'].'&msg='.$_GET['msg']);

  }

  }

}





