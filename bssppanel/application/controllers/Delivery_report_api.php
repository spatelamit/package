<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Delivery_report_api extends CI_Controller {

    // Class Constructor
    function __construct() {
        parent::__construct();
        // Set Default Timezone
        date_default_timezone_set('Asia/Kolkata');
        // Load All Required Models
        $this->load->model('User_Data_Model', 'user_data_model');
        $this->load->model('sms_model', '', TRUE);
        //$this->load->model('Code_Data_Model', 'code_data_model');
        //$this->load->model('Utility_Model', 'utility_model');
        //$this->load->model('Data_Model', 'data_model');
        //$this->load->model('Voice_Sms_Model', 'voice_sms_model');
        //$this->load->model('Kannel_Model', 'kannel_model');
    }
    // Update Voice Call DLR Through CSV File
   
    
    
    
    public function index() {
        
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        
        $auth_key  = $this->input->post('authkey');
        $username  = $this->input->post('username');
        $password  = $this->input->post('password');
        $campaign_uid  = $this->input->post('campaignkey');
        
        
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username,$password);
        //print_r($result_auth);
        if ($result_auth) {
            $user_id = $result_auth->user_id;

           
            
            $data = $this->user_data_model->getDeliveryReportsapi($user_id,$campaign_uid);
            
            if($data ){
            //print_r($data);
             $data1 = array();
             $j = "1";
             for($i=0;$i!=count($data);$i++){
                 
                  if ($data[$i]['status'] == "1") {
                    $status = "Delivered";
                } elseif ($data[$i]['status'] == "2") {
                    $status = "Failed";
                } elseif ($data[$i]['status'] == "31" || $data[$i]['status'] == "4") {
                    $status = "Pending";
                } elseif ($data[$i]['status'] == "8") {
                    $status = "Submit";
                } elseif ($data[$i]['status'] == "DND") {
                    $status = "Failed";
                } elseif ($data[$i]['status'] == "16") {
                    $status = "Rejected from operator";
                } elseif ($data[$i]['status'] == "Blocked") {
                    $status = "Block By Operator";
                } elseif ($data[$i]['status'] == "3") {
                    $status = "Sent";
                } elseif ($data[$i]['status'] == "48") {
                    $status = "Landline";
                } else {
                    $status = $data[$i]['status'];
                }
                 $data1[$i] = array(
                     'Sno' =>$j,
                     'Mobile' =>$data[$i]['mobile_no'],
                     'SenderId' =>$data[$i]['sender_id'],
                     'Message' => urldecode($data[$i]['message']),
                     'SubmitDate' =>$data[$i]['submit_date'],
                     'DeliveryStatus' =>$status
                        );                
                 
                $j++; 
             }
             $value['delivery_report']= $data1;
             echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
             echo json_encode($value,JSON_UNESCAPED_UNICODE);
             
             
            }else
            {
                    $message = array('message' => 'Invalid Campaignkey', 'code' => '302', 'type' => 'error');
                    header('Content-type: application/json');
                    echo json_encode($message);
                    die;

            }
            
            
        } else {
            $message = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            header('Content-type: application/json');
            echo json_encode($message);
            die;
        }
         
        
    }
}
    ?>