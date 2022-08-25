<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signup extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
    }

    // User Signup
    function index() {
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == 'localhost' || $domain == '192.168.1.231' || $domain == 'sms.bulksmsserviceproviders.com' || $domain == 'bulk.bulk24sms.com') {
            if ($domain == 'localhost' || $domain == '192.168.1.231') {
                $data['main_domain'] = $domain . "/BulkSMSAPP";
            } else {
                $domain = explode('.', $domain);
                $domain = array_reverse($domain);
                $domain = "$domain[1].$domain[0]";
                $data['main_domain'] = $domain;
            }
            // Website Info
            $domain_name = $_SERVER['SERVER_NAME'];
            $result_web = $this->login_model->getWebsiteInfo($domain_name);
            if ($result_web) {
                $data['website_id'] = $result_web['website_id'];
                $data['user_id'] = $result_web['user_id'];
                $data['company_name'] = $result_web['company_name'];
                $data['website_domain'] = $result_web['website_domain'];
                $data['company_logo'] = $result_web['company_logo'];
                $data['website_theme'] = $result_web['website_theme'];
                $data['website_features'] = $result_web['website_features'];
                $data['website_clients'] = $result_web['website_clients'];
                $data['website_about_image'] = $result_web['website_about_image'];
                $data['website_about_contents'] = $result_web['website_about_contents'];
                $data['website_social_links'] = $result_web['website_social_links'];
                $data['website_emails'] = $result_web['website_emails'];
                $data['website_addresses'] = $result_web['website_addresses'];
                $data['website_cities'] = $result_web['website_cities'];
                $data['website_zipcodes'] = $result_web['website_zipcodes'];
                $data['website_countries'] = $result_web['website_countries'];
                $data['website_contacts'] = $result_web['website_contacts'];
                $data['website_banner'] = $result_web['website_banner'];
                $data['website_service1'] = $result_web['website_service1'];
                $data['website_service2'] = $result_web['website_service2'];
                $data['website_service3'] = $result_web['website_service3'];
                $data['reciever_email'] = $result_web['reciever_email'];
                $rgba = $this->utility_model->Hex2RGB($result_web['website_theme_color'], 0.6);
                $data['website_theme_color'] = $rgba;
                $this->load->view('user/signup', $data);
            } else {
                $this->load->view('user/signup');
            }
        } else {
            $this->load->view('user/invalid');
        }
    }

    // Save User
    function save_bulksms_user() {
        
        $response = $this->login_model->saveBulkSMSUser();
        $value = $response['status'] . "," . $response['message'];
        echo $value; 
         
        if ($response['status'] == '200') {
            $this->session->set_flashdata('message', 'Success: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-success success');
        } else {
            $this->session->set_flashdata('message', 'Error: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-danger danger');
        }
    redirect('https://www.bulksmsserviceproviders.com/signin?message='.$response['status'],'refresh');
      //redirect('signup', 'refresh');
    // flash( 'example_message', 'http://bulksmsserviceproviders.com/SignIn' );

     // redirect('http://bulksmsserviceproviders.com/SignIn');
    }
    
       function saveBulk24SMSUser() { 
        
        $response = $this->login_model->saveBulk24SMSUser();
        $value = $response['status'] . "," . $response['message'];
        //echo $value; die;
         
        if ($response['status'] == '200') {
            $this->session->set_flashdata('message', 'Success: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-success success');
        } else {
            $this->session->set_flashdata('message', 'Error: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-danger danger');
        }
        if($response['status'] == 200){
             redirect('http://www.bulk24sms.com/signin.php?message='.$response['status'],'refresh'); 
        }else{
              redirect('http://www.bulk24sms.com/signup.php?message='.$response['status'],'refresh');
        }
  
      //redirect('signup', 'refresh');
    // flash( 'example_message', 'http://bulksmsserviceproviders.com/SignIn' );

     // redirect('http://bulksmsserviceproviders.com/SignIn');
    }
    
    
     function save_bulksms_meeting() { 

        $data=array(
            'email'=>$this->input->post('email'),
            'mobileNumber'=>$this->input->post('mobileNumber'),
            'connect'=>$this->input->post('connect'),
            'date'=>$this->input->post('date'),
            'time'=>$this->input->post('time'),
            'urlLink'=>$this->input->post('urlLink'),
            'DateTime'=>date('d-m-Y h:i:s'));
        $query=$this->db->insert('user_meetings', $data);
	return $query;
        
        }
    
    
    
    

}

?>