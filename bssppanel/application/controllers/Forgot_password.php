<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
    }

    // Forgot Password
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
                $this->load->view('user/forgot-password', $data);
            } else {
                $this->load->view('user/forgot-password');
            }
        } else {
            $this->load->view('user/invalid');
        }
    }

    // Check Username
    function check_username() {
        $data['user_id'] = $this->input->post('forgot_username');
        $data['website_domain'] = $this->input->post('forgot_username');
        $result = $this->login_model->checkUsername();
        if ($result) {
            // Go to verfication code
            $session_array = array(
                'user_id' => $result['user_id'],
                'contact_number' => $result['contact_number']
            );
            $this->session->set_userdata('user_data', $session_array);
            $data['contact_number'] = $result['contact_number'];
            $data['form'] = 'web_verify';
            $data['message'] = 'Verification code has been sent on your registered mobile number.';
            $data['type'] = '1';
            $this->load->view('user/get-web-form', $data);
        } else {
            // Go to check username
            $data['form'] = 'web_forgot';
            $data['message'] = 'This username does not exists.';
            $data['type'] = '2';
            $this->load->view('user/get-web-form', $data);
        }
    }

    // Verify Code
    function verify_code() {
        if ($this->session->userdata('user_data')) {
            $session_data = $this->session->userdata('user_data');
            $user_id = $session_data['user_id'];
            $contact_number = $session_data['contact_number'];
            $result = $this->login_model->verifyCode($user_id);
            if ($result) {
                // Go to reset password
                $data['form'] = 'web_reset';
                $data['message'] = 'You can reset your password below.';
                $data['type'] = '1';
                $this->load->view('user/get-web-form', $data);
            } else {
                // Go to verfication code
                $data['contact_number'] = $contact_number;
                $data['form'] = 'web_verify';
                $data['message'] = 'You entered wrong code.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form', $data);
            }
        }
    }

    // Reset Password
    function reset_password() {
        if ($this->session->userdata('user_data')) {
            $session_data = $this->session->userdata('user_data');
            $user_id = $session_data['user_id'];
            $contact_number = $session_data['contact_number'];
            $result = $this->login_model->resetPassword($user_id, $contact_number);
            if ($result) {
                // Go to success
                $data['form'] = 'web_login';
                $data['message'] = 'Your password has been reset. Please login';
                $data['type'] = '1';
                $this->load->view('user/get-web-form', $data);
            } else {
                // Go to reset password
                $data['form'] = 'web_reset';
                $data['message'] = 'Your password has not been reset.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form', $data);
            }
        }
    }

}

?>