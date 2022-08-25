<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sav4h5gs5xf5wat85kgv58474r5d extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Login_Model', 'login_model');
        $this->load->model('Utility_Model', 'utility_model');
    }

    // User Login
    function index() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        $domain_name = $_SERVER['SERVER_NAME'];
        if ($domain_name == 'localhost') {
            $domain_name = "192.168.1.231";
        }
        $result_reseller = $this->login_model->checkValidDomain($domain_name);
        if ($result_reseller) {
            if ($this->session->userdata('logged_in')) {
                redirect('user/index', 'refresh');
            } else {
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
                    $this->load->view('user/index', $data);
                } else {
                    $this->load->view('user/index');
                }
            }
        } else {
            $this->load->view('user/default');
        }
    }

    // Validate User
// 
//    
    function domain_auth_details($web_string) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        $activity_approve = $this->utility_model->ilegalActivity($web_string);
          $string = "bnvkjvdfsgeg&54c53=b24fgh53&6gdf4b52m14fg5=cv1d4fv53nh&gghghgh62g=4df56gd41vd@35vgf6145d362fb14gh&j321bgh3g210gj34434fgf4g2gg3df";
        if($activity_approve){
           $result_user = $this->login_model->userLogin(); 
        }else{
            $session_array = array('message' => "<i class='fa fa-exclamation-circle'></i> Error: You Are Donig Something wrong !");
            $this->session->set_userdata('login', $session_array);
            redirect('sav4h5gs5xf5wat85kgv58474r5d?' . $string, 'refresh'); 
        }
        
        
      

        if($result_user) {
            $user_id = $result_user->user_id;
            $user_ip_address = $result_user->user_ip_address;
            $contact_number = $result_user->contact_number;
            $username = $result_user->username;
            $name = $result_user->name;
            $user_status = $result_user->user_status;
            $check_verification = $result_user->check_verification;
            $country_status = $result_user->country_status;
            $user_alert = $result_user->user_alert;
            if ($user_status) {
                $session_array = array();
                $last_login_date = date('d-m-Y h:i A');
                $response = $this->login_model->updateUserLLogin($user_id, $last_login_date);
                $ip_address = $_SERVER['REMOTE_ADDR'];
                // If Same Location OR without verify location
                if ($ip_address == $user_ip_address || $check_verification == 0) {
                    $session_array = array(
                        'user_id' => $user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_place' => 'reseller_login',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: You have login successfully. Enjoy messaging!');
                    $this->session->set_flashdata('message_type', 'notification alert-success');
                    $this->session->set_userdata('logged_in', $session_array);
                    $this->session->unset_userdata('login');
                    redirect('user/index', 'refresh');
                    //load('http://sms.bulksmsserviceproviders.com/user/index');
                } else {
                    $response = $this->login_model->generateVerifyCode($user_id, $contact_number, $username, $name);
                    // User Info
                    $session_array = array(
                        'user_id' => $result_user->user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_from' => 'reseller_login',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_userdata('user_logged_in', $session_array);
                    // Verify Code
                    $array = array(
                        'user_id' => $user_id,
                        'contact_number' => $contact_number,
                        'type' => 1,
                        'message' => 'Verification code has been sent on your registered mobile number.',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_userdata('message_data', $array);
                    redirect('sav4h5gs5xf5wat85kgv58474r5d/verify_code_ip', 'refresh');
                }
            } else {
                $session_array = array('message' => "<i class='fa fa-exclamation-circle'></i> Error: Your account temporary disabled!");
                $this->session->set_userdata('login', $session_array);
                redirect('sav4h5gs5xf5wat85kgv58474r5d?' . $string, 'refresh');
                // header('location:'.base_url());
            }
        } else {
            $session_array = array('message' => "<i class='fa fa-exclamation-circle'></i> Error: You entered wrong username & password!");
            $this->session->set_userdata('login', $session_array);
            redirect('sav4h5gs5xf5wat85kgv58474r5d?' . $string, 'refresh');
            //  header('location:'.base_url());
        }
    }

    function validate_user24() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        $result_user = $this->login_model->userLogin();

        if ($result_user) {
            $user_id = $result_user->user_id;
            $user_ip_address = $result_user->user_ip_address;
            $contact_number = $result_user->contact_number;
            $username = $result_user->username;
            $name = $result_user->name;
            $user_status = $result_user->user_status;
            $check_verification = $result_user->check_verification;
            $country_status = $result_user->country_status;
            $user_alert = $result_user->user_alert;
            if ($user_status) {
                $session_array = array();
                $last_login_date = date('d-m-Y h:i A');
                $response = $this->login_model->updateUserLLogin($user_id, $last_login_date);
                $ip_address = $_SERVER['REMOTE_ADDR'];
                // If Same Location OR without verify location
                if ($ip_address == $user_ip_address || $check_verification == 0) {
                    $session_array = array(
                        'user_id' => $user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_place' => 'bulk24sms',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: You have login successfully. Enjoy messaging!');
                    $this->session->set_flashdata('message_type', 'notification alert-success');
                    $this->session->set_userdata('logged_in', $session_array);
                    $this->session->unset_userdata('login');
                    redirect('user/index', 'refresh');
                    //load('http://sms.bulksmsserviceproviders.com/user/index');
                } else {
                    $response = $this->login_model->generateVerifyCode($user_id, $contact_number, $username, $name);
                    // User Info
                    $session_array = array(
                        'user_id' => $result_user->user_id,
                        'most_parent_id' => $result_user->most_parent_id,
                        'username' => $result_user->username,
                        'utype' => $result_user->utype,
                        'login_place' => 'bulk24sms',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_userdata('user_logged_in', $session_array);
                    // Verify Code
                    $array = array(
                        'user_id' => $user_id,
                        'contact_number' => $contact_number,
                        'type' => 1,
                        'message' => 'Verification code has been sent on your registered mobile number.',
                        'country_status' => $country_status,
                        'user_alert' => $user_alert
                    );
                    $this->session->set_userdata('message_data', $array);
                    redirect('sav4h5gs5xf5wat85kgv58474r5d/verify_code_ip', 'refresh');
                }
            } else {
                $session_array = array('message' => "<i class='fa fa-exclamation-circle'></i> Error: Your account temporary disabled!");
                $this->session->set_userdata('login', $session_array);
                redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
                // header('location:'.base_url());
            }
        } else {
            $session_array = array('message' => "<i class='fa fa-exclamation-circle'></i> Error: You entered wrong username & password!");
            $this->session->set_userdata('login', $session_array);
            redirect('http://www.bulk24sms.com/signin.php?message=102');
            //  header('location:'.base_url());
        }
    }

    // Show Web Forms
    function show_web_form() {
        $data['form'] = $this->input->post('form');
        $this->load->view('user/get-web-form', $data);
    }

    // Check Username Availability
    function check_username_availability() {
        echo $result = $this->login_model->checkUsernameAvailability();
        die;
    }

    // Save User
    function save_user() {
        echo $response = $this->login_model->saveUser();
        die;
    }

    // Check Username
    function check_username() {
        $result = $this->login_model->checkUsername();
       
        if ($result) {
            // Go to verfication code
            $session_array = array(
                'user_id' => $result['user_id'],
                'contact_number' => $result['contact_number']
            );
            $this->session->set_userdata('user_data', $session_array);
            $data['contact_number'] = $result['contact_number'];
            $data['form'] = 'verify';
            $data['message'] = 'Verification code has been sent on your registered mobile number.';
            $data['type'] = '1';
            $this->load->view('user/get-web-form', $data);
        } else {
            // Go to check username
            $data['form'] = 'forgot';
            $data['message'] = 'This username does not exists.';
            $data['type'] = '2';
            $this->load->view('user/get-web-form', $data);
           
        }
    }

    // Check Username
    function check_username_web() {
        $result = $this->login_model->checkUsername();
        if ($result) {
            // Go to verfication code
            $session_array = array(
                'user_id' => $result['user_id'],
                'contact_number' => $result['contact_number']
            );
            $this->session->set_userdata('user_data', $session_array);
            $data['contact_number'] = $result['contact_number'];
            $data['form'] = 'verify';
            $data['message'] = 'Verification code has been sent on your registered mobile number.';
            $data['type'] = '1';
            $this->load->view('user/get-web-form2', $data);
        } else {
            // Go to check username
            $data['form'] = 'forgot';
            $data['message'] = 'This username does not exists.';
            $data['type'] = '2';
            //$this->load->view('user/get-web-form2', $data);
             redirect("https://www.bulksmsserviceproviders.com/forgot-password?error=404");
        }
    }

    // Verify Code
    function verify_code() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        if ($this->session->userdata('user_data')) {
            $session_data = $this->session->userdata('user_data');
            $user_id = $session_data['user_id'];
            $contact_number = $session_data['contact_number'];
            $result = $this->login_model->verifyCode($user_id);
            if ($result) {
                // Go to reset password
                $data['form'] = 'reset';
                $data['message'] = 'You can reset your password below.';
                $data['type'] = '1';
                $this->load->view('user/get-web-form', $data);
            } else {
                // Go to verfication code
                $data['contact_number'] = $contact_number;
                $data['form'] = 'verify';
                $data['message'] = 'You entered wrong code.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form', $data);
            }
        }
    }

    // Verify Code
    function verify_code_web() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        if ($this->session->userdata('user_data')) {
            $session_data = $this->session->userdata('user_data');
            $user_id = $session_data['user_id'];
            $contact_number = $session_data['contact_number'];
            $result = $this->login_model->verifyCode($user_id);
            if ($result) {
                // Go to reset password
                $data['form'] = 'reset';
                $data['message'] = 'You can reset your password below.';
                $data['type'] = '1';
                $this->load->view('user/get-web-form2', $data);
            } else {
                // Go to verfication code
                $data['contact_number'] = $contact_number;
                $data['form'] = 'verify';
                $data['message'] = 'You entered wrong code.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form2', $data);
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
                $data['form'] = 'signin';
                $data['message'] = 'Your password has been reset. Please login';
                $data['type'] = '1';
                $this->load->view('user/get-web-form', $data);
                 
                
            } else {
                // Go to reset password
                $data['form'] = 'reset';
                $data['message'] = 'Your password has not been reset.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form', $data);
            }
        }
    }

    // Reset Password
    function reset_password_web() {
        if ($this->session->userdata('user_data')) {
            $session_data = $this->session->userdata('user_data');
            $user_id = $session_data['user_id'];
            $contact_number = $session_data['contact_number'];
            $result = $this->login_model->resetPassword($user_id, $contact_number);
            if ($result) {
                // Go to success
                $data['form'] = 'signin';
                $data['message'] = 'Your password has been reset. Please login';
                $data['type'] = '1';
                $this->load->view('user/get-web-form2', $data);
            } else {
                // Go to reset password
                $data['form'] = 'reset';
                $data['message'] = 'Your password has not been reset.';
                $data['type'] = '2';
                $this->load->view('user/get-web-form2', $data);
            }
        }
    }

    // Send Mail From Contact Form
    function send_other_mail() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->login_model->saveUserHistory($actual_link, $url);
        $result = $this->login_model->sendMail();
        die;
    }

    // Call From Curl
    function cron_event() {
        $result = $this->login_model->cronEvent();
    }

    // Update Contact Numbers In DB (sent_sms table)
    function contacts() {
        $result = $this->login_model->updateContacts();
    }

    // Verify Code
    function verify_code_ip() {
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
            $this->load->view('user/verify-code-ip', $data);
        } else {
            $this->load->view('user/index');
        }
    }

    // Verify Code Process
    function verify_code_process($user_id = 0) {
        $result = $this->login_model->verifyCodeIPAddress($user_id);
        if ($result) {
            if ($this->session->userdata('user_logged_in')) {
                $user_logged_in = $this->session->userdata('user_logged_in');
                $session_array = array(
                    'user_id' => $user_logged_in['user_id'],
                    'most_parent_id' => $user_logged_in['most_parent_id'],
                    'username' => $user_logged_in['username'],
                    'utype' => $user_logged_in['utype'],
                    'login_place' => 'reseller_login'
                );
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: You have login successfully. Enjoy messaging!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
                $this->session->set_userdata('logged_in', $session_array);
                $this->session->unset_userdata('user_logged_in');
                redirect('user/index', 'refresh');
            } else {
                redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
            }
        } else {
            redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    } 

    // Send OTP
    function send_otp($type = null) {
        $result = $this->login_model->sendOTP();
        if ($result) {
            $data['message'] = 'Verification code has been resent on your registered mobile number.';
            $data['type'] = '1';
        } else {
            $data['message'] = 'Something wrong! Please try again later.';
            $data['type'] = '2';
        }
        if ($type == 'reseller') {
            $data['form'] = 'verify';
            $this->load->view('user/get-web-form', $data);
        } elseif ($type == 'web') {
            $session_array = $this->session->userdata('user_data');
            $data['contact_number'] = $session_array['contact_number'];
            $data['form'] = 'web_verify';
            $this->load->view('user/get-web-form', $data);
        }
    }

    //============================================================//
    //============================================================//
    // Cookie In CodeIgniter
    function cookies() {
        /*
          // Load Cookie Helper
          $this->load->helper('cookie');
          $cookie = array(
          'name' => 'my_cookie',
          'value' => 'This is value of cookie',
          'expire' => 86500
          );
          $data['cookie'] = $cookie;
          // Set Cookie
          $this->input->set_cookie($cookie);
          // Get Cookie
          $my_cookie = $this->input->cookie('my_cookie', false);
          $data['cookie_data'] = $my_cookie;
         */
        $this->load->view('cookies');
    }

    // Create Cookie
    function create_cookie($array = null, $cookie_name) {
        $json = json_encode($array);
        $cookie = array(
            'name' => $cookie_name,
            'value' => $json,
            'expire' => 86500
        );
        $this->input->set_cookie($cookie);
        return true;
    }

    // Manage Cookies
    function cookie() {
        $this->load->helper('cookie');
        $charge_id = 103;
        $charge_name = 'Add. Tax';
        $amount = 1500;
        $amount_type = 1;
        $session_id = 's1';
        $form_id = 'f1';
        // Check Cookie Exists Or Not
        if ($this->input->cookie('extra_session', false)) {
            // Read Cookie Values
            $array1 = $this->input->cookie('extra_session', false);
            $array2 = $this->input->cookie('extra_form', false);
            $array3 = $this->input->cookie('extra_charges', false);
            $new_array1 = json_decode(json_encode(json_decode($array1)), true);
            $new_array2 = json_decode(json_encode(json_decode($array2)), true);
            $new_array3 = json_decode(json_encode(json_decode($array3)), true);
            // Check If Cookie Already Exists With Different Form Id Then Delete The Cookie
            if (in_array($session_id, $new_array1) && !in_array($form_id, $new_array2)) {
                set_cookie('extra_session', '');
                set_cookie('extra_form', '');
                set_cookie('extra_charges', '');
                // Add New
                $narray1 = array($session_id);
                $narray2 = array($form_id);
                $narray3[$charge_id] = array(
                    'charge_name' => $charge_name,
                    'amount' => $amount,
                    'amount_type' => $amount_type
                );
                $this->create_cookie($narray1, 'extra_session');
                $this->create_cookie($narray2, 'extra_form');
                $this->create_cookie($narray3, 'extra_charges');
            } else {
                $new_array3[$charge_id] = array(
                    'charge_name' => $charge_name,
                    'amount' => $amount,
                    'amount_type' => $amount_type
                );
                $this->create_cookie($new_array3, 'extra_charges');
            }
        } else {
            $array1 = array($session_id);
            $array2 = array($form_id);
            $array3[$charge_id] = array(
                'charge_name' => $charge_name,
                'amount' => $amount,
                'amount_type' => $amount_type
            );
            $this->create_cookie($array1, 'extra_session');
            $this->create_cookie($array2, 'extra_form');
            $this->create_cookie($array3, 'extra_charges');
        }

        echo 1;
        die;
        //$this->load->view('cookies');
        // Print Cookie
        /*
          $my_cookie1 = $this->input->cookie('extra_session', false);
          $my_cookie2 = $this->input->cookie('extra_form', false);
          $my_cookie3 = $this->input->cookie('extra_charges', false);
         * 
         */
        // Pass Data To The View
        /*
          echo $_COOKIE['array1'] = $my_cookie1;
          echo $_COOKIE['array2'] = $my_cookie2;
          echo $_COOKIE['array3'] = $my_cookie3;
          die;
         */
        //$data['array1'] = $my_cookie1;
        //$data['array2'] = $my_cookie2;
        //$data['array3'] = $my_cookie3;
        //$this->load->view('cookies', $data);

        /*
          var_dump($my_cookie1);
          var_dump($my_cookie2);
          var_dump($my_cookie3);
          $new_array3 = json_decode(json_encode(json_decode($my_cookie3)), true);
          var_dump($new_array3);
          if (array_key_exists('104', $new_array3)) {
          unset($new_array3['104']);
          var_dump($new_array3);
          $this->create_cookie($new_array3, 'extra_charges');
          }
          die;
         */
    }

    // Read Cookies
    function get_cookie() {
        $this->load->helper('cookie');
        // Print Cookie
        $my_cookie1 = $this->input->cookie('extra_session', false);
        $my_cookie2 = $this->input->cookie('extra_form', false);
        $my_cookie3 = $this->input->cookie('extra_charges', false);
        // Pass Data To The View
        $data['array1'] = $my_cookie1;
        $data['array2'] = $my_cookie2;
        $data['array3'] = $my_cookie3;
        $this->load->view('get_cookies', $data);
    }

    // Delete Cookies
    function delete_cookies() {
        $this->load->helper('cookie');
        $this->input->set_cookie('extra_session', '');
        $this->input->set_cookie('extra_form', '');
        $this->input->set_cookie('extra_charges', '');
    }

    //============================================================//
    // Test DB
    function test() {
        $this->load->model('utility_model', '', TRUE);
        $result = $this->utility_model->testDBTxn();
    }

    // Show Google
    function google() {
        $this->load->view('google');
    }

    public function demo() {
        echo $username = $this->input->method('username');
        echo $password = $this->input->method('password');
    }

    //============================================================//
}

?>