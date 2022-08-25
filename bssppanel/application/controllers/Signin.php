<?php

error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signin extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
        $this->load->model('Admin_Data_Model', 'admin_data_model');
    }

    // User Signin
    function index() {
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == 'localhost' || $domain == '192.168.1.231' || $domain == 'sms.bulksmsserviceproviders.com' || $domain == 'bulk.bulk24sms.com') {
            if ($this->session->userdata('logged_in')) {
                redirect('user/index', 'refresh');
            } else {
                // Website Info
                if ($domain == 'localhost' || $domain == '192.168.1.231') {
                    $data['main_domain'] = $domain . "/BulkSMSAPP";
                } else {
                    $domain = explode('.', $domain);
                    $domain = array_reverse($domain);
                    $domain = "$domain[1].$domain[0]";
                    $data['main_domain'] = $domain;
                }
                $domain_name = $_SERVER['SERVER_NAME'];
                if ($domain_name == 'localhost') {
                    $domain_name = "192.168.1.231";
                }
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
                    $this->load->view('user/signin', $data);
                } else {
                    $this->load->view('user/signin');
                }
            }
        } else {
            $this->load->view('user/invalid');
        }
    }

    // Validate User
    function validate_user($pass, $user) {
        $username = $user;
        $password = substr($pass, 16, 32);
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $result_user = $this->login_model->userLoginweb($password, $username);
        if ($result_user) {
            $user_id = $result_user->user_id;
            $username = $result_user->username;
            $name = $result_user->name;
            $user_ip_address = $result_user->user_ip_address;
            $contact_number = $result_user->contact_number;
            $user_status = $result_user->user_status;
            $check_verification = $result_user->check_verification;
            if ($user_status) {
                $session_array = array();
                $last_login_date = date('d-m-Y h:i A');
                $response = $this->login_model->updateUserLLogin($user_id, $last_login_date);
                $ip_address = $_SERVER['REMOTE_ADDR'];
                // If Same Location OR without verify location
                if ($ip_address == $user_ip_address || $check_verification == 0) {
                    $session_array = array(
                        'user_id' => $result_user->user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_place' => 'web_login'
                    );
                    $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: You have login successfully. Enjoy messaging!');
                    $this->session->set_flashdata('message_type', 'notification alert-success');
                    $this->session->set_userdata('logged_in', $session_array);
                    $this->session->unset_userdata('login');
                    redirect('user/index', 'refresh');
                } else {
                    $response = $this->login_model->generateVerifyCode($user_id, $contact_number, $username, $name);
                    // User Info
                    $session_array = array(
                        'user_id' => $result_user->user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_from' => 'web_login'
                    );
                    $this->session->set_userdata('user_logged_in', $session_array);
                    // Verify Code
                    $array = array(
                        'user_id' => $user_id,
                        'contact_number' => $contact_number,
                        'type' => 1,
                        'message' => 'Verification code has been sent on your registered mobile number.'
                    );
                    $this->session->set_userdata('message_data', $array);
                    redirect('signin/verify_code', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', 'Error: Your account temporary disabled!');
                $this->session->set_flashdata('message_type', 'alert-danger danger');
                //redirect('signin', 'refresh');
                redirect('http://bulksmsserviceproviders.com/signin');
                // redirect('http://bulksmsserviceproviders.com/SignIn?message='.$response['message'],'refresh');
            }
        } else {
            /* $this->session->set_flashdata('message', 'Error: You entered wrong username & password!');
              $this->session->set_flashdata('message_type', 'alert-danger danger'); */
            //echo 404;die;
            //redirect('signin', 'refresh');
            // redirect('http://bulksmsserviceproviders.com/SignIn?message='.$response['message'],'refresh');
            $response['message'] = "kdfjhdc=db&nfhkmfdbj&invelid_login&fhjdfhgsfa&@snfdndkjf@lkjkjlkbf=gh7hnm&dgf5bfjhd&f5n5jmk4m3285&556dfcbdfsxl&kfml&kclk=5637#dlkf958#7";
            redirect('https://bulksmsserviceproviders.com/?no=0&message=' . $response['message'], 'refresh');
            //redirect('http://bulksmsserviceproviders.com/SignIn');
        }
    }

    // Verify Code
    function verify_code() {
        // Website Info
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == 'localhost') {
            $data['main_domain'] = $domain;
        } else {
            $domain = explode('.', $domain);
            $domain = array_reverse($domain);
            $domain = "$domain[1].$domain[0]";
            $data['main_domain'] = $domain;
        }
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
            $this->load->view('user/verify-code', $data);
        } else {
            $this->load->view('user/signin');
        }
    }

    // Verify Code Process
    function verify_code_process($user_id) {
        $result = $this->login_model->verifyCodeIPAddress($user_id);
        if ($result) {
            if ($this->session->userdata('user_logged_in')) {
                $user_logged_in = $this->session->userdata('user_logged_in');
                $session_array = array(
                    'user_id' => $user_logged_in['user_id'],
                    'most_parent_id' => $user_logged_in['most_parent_id'],
                    'username' => $user_logged_in['username'],
                    'utype' => $user_logged_in['utype'],
                    'login_place' => 'web_login'
                );
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: You have login successfully. Enjoy messaging!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
                $this->session->set_userdata('logged_in', $session_array);
                $this->session->unset_userdata('user_logged_in');
                redirect('user/index', 'refresh');
            } else {

                //  redirect('home', 'refresh');
                header('location:' . base_url());
            }
        } else {
            redirect('signin', 'refresh');
        }
    }

}

?>