<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Login_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Utility_Model', 'utility_model');
    }

    // Check Valid Domain
    function checkValidDomain($domain_name = null) {
        $this->db->select('`website_domain`');
        $this->db->from('user_websites');
        $this->db->where('website_domain', $domain_name);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }
    }  

    // Validate User Via Form
    function userLogin() {

        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));
        if ($username && $password) {
            $this->db->select('user_id, most_parent_id, username, utype, user_ip_address, contact_number, name, user_status, check_verification,country_status,user_alert');
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->where('password', $password);
            //$this->db->where('user_status', '1');
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    // Validate User Via Form
    function userLoginweb($password, $username) {

        if ($username && $password) {
            $this->db->select('user_id, most_parent_id, username, utype, user_ip_address, contact_number, name, user_status, check_verification,country_status,user_alert');
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->where('password', $password);
            //$this->db->where('user_status', '1');
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    // Validate User Via API
    function validateUser($username = null, $password = null) {
        $password = md5($password);
        $this->db->select('user_id, auth_key, most_parent_id, username, utype, user_ip_address, contact_number, name, user_status, check_verification');
        $this->db->select('pr_sms_balance, tr_sms_balance');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Website Info
    function getWebsiteInfo($domain = null) {
        $this->db->select('`website_id`, `company_name`, `website_domain`, `user_id`, `company_logo`, `website_theme`');
        $this->db->select('`website_features`, `website_clients`, `website_about_image`, `website_about_contents`');
        $this->db->select('`website_social_links`, `logo_bg_color`, `external_url_text`, `external_url_link`, `banner_image`, `banner_text`');
        $this->db->select('`contact_form`, `website_contacts`, `website_emails`, `website_addresses`, `website_cities`');
        $this->db->select('`website_zipcodes`, `website_countries`, `website_google_map`, `reciever_email`, `google_analytic_id`, `meta_title`');
        $this->db->select('`meta_desc`, `meta_keywords`, `website_notification`, `website_theme_color`, `website_author`, `website_email`');
        $this->db->select('`website_service1`, `website_service2`, `website_service3`, `website_banner`');
        $this->db->from('user_websites');
        $this->db->where('website_domain', $domain);
        $this->db->where('website_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Get User Info For API
    function getUserInfo($user_id = 0) {
        $this->db->select('most_parent_id, number_allowed, pro_user_group_id, tr_user_group_id,stock_route,prtodnd_route, user_ratio, user_fake_ratio, user_fail_ratio');
        $this->db->select('pr_sms_balance, tr_sms_balance, default_sender_id, auth_key, email_address, company_name');
        $this->db->select('`demo_balance`, `expiry_days`, `signup_sender`, `signup_message`, `signup_subject`, `signup_body`,voice_tr_route,voice_pr_route');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Check Username For Signup
    function checkUsernameAvailability() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $username = $myArray[0];
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    // Save User
    function saveUser() {
        // Website Info
        $domain_name = $_SERVER['SERVER_NAME'];
        if ($domain_name == "sms.bulksmsserviceproviders.com") {
            $result_web = $this->login_model->getWebsiteInfo($domain_name);
            if ($result_web) {
                $user_id = $result_web['user_id'];
                $web_domain = $result_web['website_domain'];
                $company_name = $result_web['company_name'];
                $from = $result_web['reciever_email'];
            }
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $name = $this->input->post('name');
            $email_address = $this->input->post('email_address');
            $contact = $this->input->post('contact_number');
            $username = $this->input->post('signup_username');
            $company = $this->input->post('company_name');
            $industry = $this->input->post('industry');
            $country = $this->input->post('country');
            if ($country == "INDIA" || $country == "India") {
                $country_status = 1;
            } else {
                $country_status = 2;
            }
            //white list to contact number
            $white_level = array(
                'white_list_number' => $contact,
            );

            $this->db->insert('white_lists', $white_level);

            $terms = 0;
            if ($this->input->post('terms')) {
                $terms = $this->input->post('terms');
            }
            // Check Username
            $this->db->select('username');
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 1) {
                header('Content-Type: application/json');
                $message = "Username not available! Please try another";
                echo json_encode(array('status' => '100', 'message' => $message));
                die;
            } else {
                if (preg_match('/[^A-Za-z_\-0-9]/i', $username)) {
                    header('Content-Type: application/json');
                    $message = "Username must be start with a character";
                    echo json_encode(array('status' => '100', 'message' => $message));
                    die;
                } else {
                    $this->load->model('admin_data_model', '', TRUE);
                    // Default User Group
                    $result_admin = $this->admin_data_model->getAdminDetails();
                    if ($result_admin) {
                        $admin_id = $result_admin->admin_id;
                        $admin_email = $result_admin->admin_email;
                        $admin_contact = $result_admin->admin_contact;
                        $admin_company = $result_admin->admin_company;
                        $total_pr_balance = $result_admin->total_pr_balance;
                        $total_tr_balance = $result_admin->total_tr_balance;
                    }
                    // Promotional
                    $pr_user_group_id = 0;
                    $result_pr = $this->admin_data_model->getDefaultUserGroup('Promotional', 1);
                    if ($result_pr)
                        $pr_user_group_id = $result_pr->user_group_id;
                    // Transactional
                    $tr_user_group_id = 0;
                    $result_tr = $this->admin_data_model->getDefaultUserGroup('Transactional', 1);
                    if ($result_tr)
                        $tr_user_group_id = $result_tr->user_group_id;
                    //Voice tr
                    $voice_tr_route = 0;
                    $result_voice_tr = $this->admin_data_model->getDefaultUserGroupVoice('tr', 1);
                    if ($result_voice_tr)
                        $voice_tr_route = $result_voice_tr->voice_route_id;
                    //Voice pr
                    $voice_pr_route = 0;
                    $result_voice_pr = $this->admin_data_model->getDefaultUserGroupVoice('pr', 1);
                    if ($result_voice_pr)
                        $voice_pr_route = $result_voice_pr->voice_route_id;
                    // Default Settings
                    $sms_limit = 1000;
                    $pr_sender_id_check = 1;
                    $pr_sender_id_type = 'Alphabetic';
                    $pr_sender_id_length = 6;
                    $pr_dnd_check = 0;
                    $tr_sender_id_check = 1;
                    $tr_sender_id_type = 'Alphabetic';
                    $tr_sender_id_length = 6;
                    $tr_keyword_check = 0;

                    $demo_balance = 10;
                    $expiry_days = 180;
                    $signup_sender = "";
                    $signup_message = "";
                    $signup_subject = "";
                    $signup_body = "";
                    $result_settings = $this->admin_data_model->getDefaultSettings();
                    if ($result_settings) {
                        $sms_limit = $result_settings->sms_limit;
                        $pr_sender_id_check = $result_settings->pr_sender_id_check;
                        $pr_sender_id_type = $result_settings->pr_sender_id_type;
                        $pr_sender_id_length = $result_settings->pr_sender_id_length;
                        $pr_dnd_check = $result_settings->pr_dnd_check;
                        $tr_sender_id_check = $result_settings->tr_sender_id_check;
                        $tr_sender_id_type = $result_settings->tr_sender_id_type;
                        $tr_sender_id_length = $result_settings->tr_sender_id_length;
                        $tr_keyword_check = $result_settings->tr_keyword_check;

                        $demo_balance = $result_settings->demo_balance;
                        $expiry_days = $result_settings->expiry_days;
                        $signup_sender = $result_settings->signup_sender;
                        $signup_message = $result_settings->signup_message;
                        $signup_subject = $result_settings->signup_subject;
                        $signup_body = $result_settings->signup_body;

                        $pr_deliver_ratio = $result_settings->pr_deliver_ratio;
                        $pr_fail_ratio = $result_settings->pr_fail_ratio;
                        $tr_deliver_ratio = $result_settings->tr_deliver_ratio;
                        $tr_fail_ratio = $result_settings->tr_fail_ratio;
                        $vpr_deliver_ratio = $result_settings->vpr_deliver_ratio;
                        $vpr_fail_ratio = $result_settings->vpr_fail_ratio;
                        $vtr_deliver_ratio = $result_settings->vtr_deliver_ratio;
                        $vtr_fail_ratio = $result_settings->vtr_fail_ratio;
                    }
                    $pr_demo_balance = 0;
                    $tr_demo_balance = 0;
                    if ($total_pr_balance && $total_pr_balance > $demo_balance) {
                        $pr_demo_balance = $demo_balance;
                    }
                    if ($total_tr_balance && $total_tr_balance > $demo_balance) {
                        $tr_demo_balance = $demo_balance;
                    }
                    $current_date = date('d-m-Y');
                    $expiry_date = date('d-m-Y', strtotime($current_date . '+' . $expiry_days . 'days'));

                    // Generate Password For User
                    $password = random_string('numeric', 6);
                    // Generate Auth key
                    $auth_key = random_string('unique', 32);
                    $creation_date = date('d-m-Y h:i A');
                    $data = array(
                        'admin_id' => $admin_id,
                        'name' => $name,
                        'email_address' => $email_address,
                        'contact_number' => $contact,
                        'username' => $username,
                        'password' => md5($password),
                        'auth_key' => $auth_key,
                        'utype' => 'User',
                        'creation_date' => $creation_date,
                        'pro_user_group_id' => $pr_user_group_id,
                        'tr_user_group_id' => $tr_user_group_id,
                        'company_name' => $company,
                        'country' => $country,
                        'number_allowed' => $sms_limit,
                        'p_sender_id_option' => $pr_sender_id_check,
                        'p_sender_id_type' => $pr_sender_id_type,
                        'p_sender_id_length' => $pr_sender_id_length,
                        'dnd_check' => $pr_dnd_check,
                        't_sender_id_option' => $tr_sender_id_check,
                        'sender_id_type' => $tr_sender_id_type,
                        'sender_id_length' => $tr_sender_id_length,
                        'keyword_option' => $tr_keyword_check,
                        'industry' => $industry,
                        'country_status' => $country_status,
                        'terms_conditions' => $terms,
                        'pr_sms_balance' => 0,
                        'tr_sms_balance' => $tr_demo_balance,
                        'expiry_date' => $expiry_date,
                        'user_ip_address' => $ip_address,
                        'pr_user_fake_ratio' => $pr_deliver_ratio,
                        'pr_user_fail_ratio' => $pr_fail_ratio,
                        'user_fake_ratio' => $tr_deliver_ratio,
                        'user_fail_ratio' => $tr_fail_ratio,
                        'vpr_fake_ratio' => $vpr_deliver_ratio,
                        'vpr_fail_ratio' => $vpr_fail_ratio,
                        'vtr_fake_ratio' => 80,
                        'vtr_fail_ratio' => 5,
                        'prtodnd_route' => $pr_user_group_id,
                        'stock_route' => $pr_user_group_id,
                        'voice_tr_route' => $voice_tr_route,
                        'voice_pr_route' => $voice_pr_route,
                    );

                    //==================================================
                    // Send SMS
                    $purpose = "User Signup";
                    $name_array = explode(' ', $name);
                    $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                    $web_domain = $server_protocol . "://" . $web_domain;
                    // Load SMS_Model
                    $this->load->model('Sms_Model', 'sms_model');
                    // Get Short URL
                    $short_web_domain = $this->sms_model->googleUrlShortner($web_domain . "/signin");
                    //$short_web_domain = "http://sms.bulksmsserviceproviders.com/signin";
                    if ($signup_message != "") {
                        $messages = $signup_message;
                        $messages = str_replace("##fname##", $name_array[0], $messages);
                        $messages = str_replace("##url##", $short_web_domain, $messages);
                        $messages = str_replace("##username##", $username, $messages);
                        $messages = str_replace("##password##", $password, $messages);
                        $messages = urlencode($messages);
                    } else {
                        $messages = urlencode("Hello $name_array[0], Please login with following details: URL:" . $short_web_domain . "; Username:$username;Password: $password");
                    }
                    // Prepare you post parameters
                    if ($signup_sender != "")
                        $signup_sender_id = $signup_sender;
                    else
                        $signup_sender_id = "SIGNUP";

                    // Prepare you post parameters
                    // API URL
                    $url = $web_domain . "/admin/send_http/";
                    $sms_array = array(
                        'contact' => $contact,
                        'sender' => $signup_sender_id,
                        'messages' => $messages,
                        'purpose' => $purpose
                    );
                    //==================================================
                    // Send Mail
                    if ($signup_subject != "")
                        $subject = $signup_subject;
                    else
                        $subject = "Your account has been created successfully!";

                    if ($signup_body != "") {
                        $messages = $signup_body;
                        $messages = str_replace("##fname##", $name_array[0], $messages);
                        $messages = str_replace("##url##", $web_domain . "/signin", $messages);
                        $messages = str_replace("##username##", $username, $messages);
                        $messages = str_replace("##password##", $password, $messages);
                        $body = $messages;
                        $body = $body . "<br/><br/><br/>";
                        $body = $body . "<h4>Regards,</h4>";
                        $body = $body . "<h4>Team $admin_company</h4>";
                    } else {
                        $body = $this->utility_model->emailSignup($web_domain, $username, $admin_company, 1);
                    }

                    // Prepare Email Array
                    $mail_array1 = array(
                        'from_email' => $admin_email,
                        'from_name' => $admin_company,
                        'to_email' => $email_address,
                        'subject' => $subject,
                        'message' => $body
                    );

                    //==================================================
                    // Send Mail To User
                    $subject1 = "Signup on Bulk SMS Service Provider!";
                    $temp_array = array('Bulk SMS Service Provider', $name, $username, $contact, $email_address, $company, $industry, $web_domain);
                    $body1 = $this->utility_model->emailSignInfo($temp_array);
                    // Prepare Email Array
                    $mail_array2 = array(
                        'from_email' => $admin_email,
                        'from_name' => $admin_company,
                        'to_email' => $admin_email,
                        'subject' => $subject1,
                        'message' => $body1
                    );

                    //==================================================
                    if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array1) && $this->utility_model->sendEmail($mail_array2)) {
                        $up_pr_sms_balance = $total_pr_balance - $pr_demo_balance;
                        $up_tr_sms_balance = $total_tr_balance - $tr_demo_balance;
                        $bal_data = array(
                            'total_pr_balance' => $up_pr_sms_balance,
                            'total_tr_balance' => $up_tr_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $bal_data);
                        //curl_close($ch);
                        header('Content-Type: application/json');
                        $message = "You are registered successully! Your login details sent on your contact number.";
                        echo json_encode(array('status' => '200', 'message' => $message));
                        die;
                    } else {
                        header('Content-Type: application/json');
                        $message = "User creation failed! Please try again";
                        echo json_encode(array('status' => '102', 'message' => $message));
                        die;
                    }
                }
            }
        } else {
            // Website Info
            $user_id = $web_domain = $company_name = $from = "";
            $result_web = $this->login_model->getWebsiteInfo($domain_name);

            if ($result_web) {
                $user_id = $result_web['user_id'];
                $web_domain = $result_web['website_domain'];
                $company_name = $result_web['company_name'];
                $from = $result_web['reciever_email'];
            }
            if ($user_id) {
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $name = $this->input->post('name');
                $email_address = $this->input->post('email_address');
                $contact = $this->input->post('contact_number');
                $username = $this->input->post('signup_username');
                $company = $this->input->post('company_name');
                $industry = $this->input->post('industry');
                $country = $this->input->post('country');
                $country = $this->input->post('country');
                if ($country == "INDIA" || $country == "India") {
                    $country_status = 1;
                } else {
                    $country_status = 2;
                }
                $terms = 0;

                //white list to contact number
                $white_level = array(
                    'white_list_number' => $contact,
                );

                $this->db->insert('white_lists', $white_level);

                if ($this->input->post('terms')) {
                    $terms = $this->input->post('terms');
                }
                // Check Username
                $this->db->select('username');
                $this->db->from('users');
                $this->db->where('username', $username);
                $this->db->limit(1);
                $query = $this->db->get();
                if ($query->num_rows()) {
                    header('Content-Type: application/json');
                    $message = "Username not available! Please try another";
                    echo json_encode(array('status' => '100', 'message' => $message));
                    die;
                } else {
                    if (preg_match('/[^A-Za-z_\-0-9]/i', $username)) {
                        header('Content-Type: application/json');
                        $message = "Username must be start with a character";
                        echo json_encode(array('status' => '100', 'message' => $message));
                        die;
                    } else {
                        $result_user = $this->getUserInfo($user_id);
                        if ($result_user) {
                            if ($result_user->most_parent_id) {
                                $most_parent_id = $result_user->most_parent_id;
                            } else {
                                $most_parent_id = $user_id;
                            }
                            $voice_tr_route = 0;
                            $voice_pr_route = 0;
                            $pr_sms_balance = $result_user->pr_sms_balance;
                            $tr_sms_balance = $result_user->tr_sms_balance;
                            $number_allowed = $result_user->number_allowed;
                            $pro_user_group_id = $result_user->pro_user_group_id;
                            $tr_user_group_id = $result_user->tr_user_group_id;
                            $prtodnd_route = $result_user->prtodnd_route;
                            $stock_route = $result_user->stock_route;
                            $user_ratio = $result_user->user_ratio;
                            $user_fake_ratio = $result_user->user_fake_ratio;
                            $user_fail_ratio = $result_user->user_fail_ratio;
                            $voice_tr_route = $result_user->voice_tr_route;
                            $voice_pr_route = $result_user->voice_pr_route;
                            // Reseller Settings
                            $demo_balance = $result_user->demo_balance;
                            $pr_demo_balance = 0;
                            $tr_demo_balance = 0;
                            if ($pr_sms_balance && $pr_sms_balance > $demo_balance) {
                                $pr_demo_balance = $demo_balance;
                            }
                            if ($tr_sms_balance && $tr_sms_balance > $demo_balance) {
                                $tr_demo_balance = $demo_balance;
                            }
                            $expiry_days = $result_user->expiry_days;
                            $signup_sender = $result_user->signup_sender;
                            $signup_message = $result_user->signup_message;
                            $signup_subject = $result_user->signup_subject;
                            $signup_body = $result_user->signup_body;
                            $current_date = date('d-m-Y');
                            $expiry_date = date('d-m-Y', strtotime($current_date . '+' . $expiry_days . 'days'));
                            //==================================================
                            // Generate Password For User
                            $password = random_string('numeric', 6);
                            // Generate Auth key
                            $auth_key = random_string('unique', 32);
                            $creation_date = date('d-m-Y h:i A');
                            $data = array(
                                'ref_user_id' => $user_id,
                                'most_parent_id' => $most_parent_id,
                                'name' => $name,
                                'email_address' => $email_address,
                                'contact_number' => $contact,
                                'username' => $username,
                                'password' => md5($password),
                                'auth_key' => $auth_key,
                                'utype' => 'User',
                                'country' => $country,
                                'creation_date' => $creation_date,
                                'number_allowed' => $number_allowed,
                                'pro_user_group_id' => $pro_user_group_id,
                                'tr_user_group_id' => $tr_user_group_id,
                                'prtodnd_route' => $prtodnd_route,
                                'stock_route' => $stock_route,
                                'company_name' => $company,
                                'industry' => $industry,
                                'country_status' => $country_status,
                                'user_ratio' => $user_ratio,
                                'user_fake_ratio' => $user_fake_ratio,
                                'user_fail_ratio' => $user_fail_ratio,
                                'terms_conditions' => $terms,
                                'expiry_date' => $expiry_date,
                                'pr_sms_balance' => $pr_demo_balance,
                                'tr_sms_balance' => $tr_demo_balance,
                                'user_ip_address' => $ip_address,
                                'voice_tr_route' => $voice_tr_route,
                                'voice_pr_route' => $voice_pr_route,
                            );

                            //==================================================
                            // Send SMS
                            $purpose = "User Signup";
                            $name_array = explode(' ', $name);
                            $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                            $web_domain = $server_protocol . "://" . $web_domain;
                            // Load SMS_Model
                            $this->load->model('Sms_Model', 'sms_model');
                            // Get Short URL
                            $short_web_domain = $this->sms_model->googleUrlShortner($web_domain);
                            //$short_web_domain = "http://sms.bulksmsserviceproviders.com/signin";
                            if ($signup_message != "") {
                                $messages = $signup_message;
                                $messages = str_replace("##fname##", $name_array[0], $messages);
                                $messages = str_replace("##url##", $short_web_domain, $messages);
                                $messages = str_replace("##username##", $username, $messages);
                                $messages = str_replace("##password##", $password, $messages);
                                $messages = urlencode($messages);
                            } else {
                                $messages = urlencode("Hello $name_array[0], Please login with following details: URL:" . $short_web_domain . "; Username:$username;Password: $password");
                            }
                            // Prepare you post parameters
                            if ($signup_sender != "")
                                $signup_sender_id = $signup_sender;
                            else
                                $signup_sender_id = "SIGNUP";

                            // Prepare you post parameters
                            // API URL
                            $url = $web_domain . "/admin/send_http/";
                            $sms_array = array(
                                'contact' => $contact,
                                'sender' => $signup_sender_id,
                                'messages' => $messages,
                                'purpose' => $purpose
                            );
                            //==================================================
                            // Send Mail
                            if ($signup_subject != "")
                                $subject = $signup_subject;
                            else
                                $subject = "Your account has been created successfully!";

                            if ($signup_body != "") {
                                $messages = $signup_body;
                                $messages = str_replace("##fname##", $name_array[0], $messages);
                                $messages = str_replace("##url##", $web_domain, $messages);
                                $messages = str_replace("##username##", $username, $messages);
                                $messages = str_replace("##password##", $password, $messages);
                                $body = $messages;
                                $body = $body . "<br/><br/><br/>";
                                $body = $body . "<h4>Regards,</h4>";
                                $body = $body . "<h4>Team $company_name</h4>";
                            } else {
                                $body = $this->utility_model->emailSignup($web_domain, $username, $company_name, 0);
                            }

                            // Prepare Email Array
                            $mail_array1 = array(
                                'from_email' => $from,
                                'from_name' => $company_name,
                                'to_email' => $email_address,
                                'subject' => $subject,
                                'message' => $body
                            );
                            //==================================================
                            // Send Mail To Us
                            $subject1 = "Signup on $company_name!";
                            $temp_array = array($company_name, $name, $username, $contact, $email_address, $company, $industry, $web_domain);
                            $body1 = $this->utility_model->emailSignInfo($temp_array);
                            // Prepare Email Array
                            $mail_array2 = array(
                                'from_email' => $from,
                                'from_name' => "Bulk SMS Service Providers",
                                'to_email' => $from,
                                'subject' => $subject1,
                                'message' => $body1
                            );
                            //==================================================
                            if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array)) {
                                $this->utility_model->sendEmail($mail_array1);
                                $this->utility_model->sendEmail($mail_array2);

                                $up_pr_sms_balance = $pr_sms_balance - $pr_demo_balance;
                                $up_tr_sms_balance = $tr_sms_balance - $tr_demo_balance;
                                $bal_data = array(
                                    'pr_sms_balance' => $up_pr_sms_balance,
                                    'tr_sms_balance' => $up_tr_sms_balance
                                );
                                $this->db->where('user_id', $user_id);
                                $this->db->update('users', $bal_data);
                                //curl_close($ch);
                                header('Content-Type: application/json');
                                $message = "You are registered successully! Your login details sent on your contact number.";
                                echo json_encode(array('status' => '200', 'message' => $message));
                                die;
                            } else {
                                header('Content-Type: application/json');
                                $message = "User creation failed! Please try again";
                                echo json_encode(array('status' => '102', 'message' => $message));
                                die;
                            }
                        } else {
                            header('Content-Type: application/json');
                            $message = "User creation failed! Please try again";
                            echo json_encode(array('status' => '101', 'message' => $message));
                            die;
                        }
                    }
                }
            } else {
                header('Content-Type: application/json');
                $message = "Something wrong! Please try againg later.";
                echo json_encode(array('status' => '100', 'message' => $message));
                die;
            }
        }
    }

    // Check Username For Forgot Password
    function checkUsername() {
        // Website Info
        $domain_name = $_SERVER['SERVER_NAME'];


        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $user_id = $result_web['user_id'];
            $web_domain = $result_web['website_domain'];
            $company_name = $result_web['company_name'];
        }
        $username = $this->input->post('forgot_username');
     
        // Get Contact Number
        $this->db->select('user_id, contact_number, name');
        $this->db->from('users');
        $this->db->where('username', $username);
         $this->db->where('user_status', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            $ref_user_id = $result->user_id;
            $contact_number = $result->contact_number;
            $name = $result->name;
            $result_data = array('user_id' => $ref_user_id, 'contact_number' => $contact_number);
            $verification_code = random_string('numeric', 4);
            $data = array(
                'verification_code' => $verification_code,
                'user_id' => $ref_user_id
            );
            $this->load->model('admin_data_model', '', TRUE);
            // Forgot Password Settings
            $fp_sender = "";
            $fp_message = "";
            $result_settings = $this->admin_data_model->getDefaultSettings();
            if ($result_settings) {
                $fp_sender = $result_settings->forgot_password_sender;
                $fp_message = $result_settings->forgot_password_message;
            }
            //==================================================
            // Send SMS
            $purpose = "Forgot Password";
            $name_array = explode(' ', $name);
            if ($fp_message != "") {
                $messages = $fp_message;
                $messages = str_replace("##fname##", $name_array[0], $messages);
                $messages = str_replace("##username##", $username, $messages);
                $messages = str_replace("##code##", $verification_code, $messages);
                $messages = urlencode($messages);
            } else {
                $messages = urlencode("Hello $name_array[0], Enter: $verification_code to change your password for username: $username Keep messageing!");
            }
            // Prepare you post parameters
            if ($fp_sender != "")
                $fp_sender_id = $fp_sender;
            else
                $fp_sender_id = "BULKIN";

            // API URL
            $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
            $web_domain = $server_protocol . "://" . $web_domain;
            $url = $web_domain . "/admin/send_http/";
            $sms_array = array(
                'contact' => $contact_number,
                'sender' => $fp_sender_id,
                'messages' => $messages,
                'purpose' => $purpose
            );
            if ($this->db->insert('user_forgot_passwords', $data) && $this->utility_model->sendSMS($url, $sms_array)) {
                //curl_close($ch);
                $this->session->set_userdata('sms_array', $sms_array);
                $this->session->set_userdata('verification_domain', $web_domain);
                return $result_data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Verify Code
    function verifyCode($user_id = 0) {
        $verification_code = $this->input->post('verification_code');
        // Check Verification Code
        $this->db->select('user_id, verification_code');
        $this->db->from('user_forgot_passwords');
        $this->db->where('verification_code', $verification_code);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('forgot_password_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }
    }

    // Verify Code For IP Address
    function verifyCodeIPAddress($user_id = 0) {
        $verification_code = $this->input->post('verification_code');
        // Check Verification Code
        $this->db->select('user_id, verification_code');
        $this->db->from('user_ip_addresses');
        $this->db->where('verification_code', $verification_code);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('ip_address_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $data = array(
                'user_ip_address' => $ip_address
            );
            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data);
            return true;
        } else {
            return false;
        }
    }

    // Reset Password
    function resetPassword($user_id = 0, $contact_number = null) {
        $data = array(
            'password' => md5($this->input->post('new_password'))
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Validate SMPP User
    function smppUserLogin() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $password = md5($password);
        $this->db->select('smpp_user_id, smpp_username');
        $this->db->from('smpp_users');
        $this->db->where('smpp_username', $username);
        $this->db->where('smpp_password', $password);
        $this->db->where('smpp_user_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Validate User (Login As)
    function loginAsUser($user,$parent_user) {
        //$user = $this->input->get('ref_id');
        $parant_id = $this->input->post('parant_id');
        $this->db->select('user_id, most_parent_id, username, utype');
        $this->db->from('users');
        $this->db->where('user_id', $user);
        $this->db->where('ref_user_id', $parent_user);
        //$this->db->where('user_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
       
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function loginAsAdminUser($user = null) {
        $this->db->select('user_id, most_parent_id, username, utype');
        $this->db->from('users');
        $this->db->where('user_id', $user);
        //$this->db->where('user_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    function loginAsforUser($user, $child) {
        if ($user != "") {
            $this->db->select('user_id');
            $this->db->from('users');
            $this->db->where('most_parent_id', $user);
            $this->db->where('user_id', $child);
            $this->db->limit(1);
            $query1 = $this->db->get();
            if ($query1->num_rows()) {

                $this->db->select('user_id, ref_user_id, most_parent_id, username, utype');
                $this->db->from('users');
                $this->db->where('user_id', $user);
                //$this->db->where('user_status', '1');
                $this->db->limit(1);
                $query = $this->db->get();
                if ($query->num_rows()) {
                    return $query->row();
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Update Last Login Info (User)
    function updateUserLLogin($user_id = 0, $last_login_date = null) {
        $data = array(
            'last_login_date' => $last_login_date
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Update Last Login Info (SMPP User)
    function updateSMPPUserLLogin($smpp_user_id = 0, $last_login_date = null) {
        $data = array(
            'last_login_date' => $last_login_date
        );
        $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Send Mail
    function sendMail() {
        // Website Info
        $company_name = "";
        $from = "";
        $domain_name = $_SERVER['SERVER_NAME'];

        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $company_name = $result_web['company_name'];
            $from = $result_web['reciever_email'];
            // $from ="bulk24sms.kapil@gmail.com";
        }
        if ($from != "") {

            $name = $this->input->post('contact_name');
            $contact = $this->input->post('contact_contact');
            $email_address = $this->input->post('contact_email');
            $message = $this->input->post('contact_message');
            $subject = "Contact For " . $company_name;
            if ($name != "" && $contact != "" && $email_address != "" && $message != "" && $subject != "") {

                $temp_array = array($company_name, $name, $contact, $email_address, $message);
                $body = $this->utility_model->emailContactInfo($temp_array);
                // Prepare Email Array
                $mail_array = array(
                    'from_email' => $from,
                    'from_name' => 'Bulk SMS',
                    'to_email' => $from,
                    'subject' => $subject,
                    'message' => $body
                );
                if ($this->utility_model->sendEmail($mail_array)) {
                    echo true;
                } else {
                    echo false;
                }
            } else {
                echo false;
            }
        } else {
            echo false;
        }
    }

    //=======================================================================//
    // Save User For Bulk SMS Service Provider
    function saveBulkSMSUser() {
        // Website Info
        $domain_name = $_SERVER['SERVER_NAME'];
        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $user_id = $result_web['user_id'];
            $web_domain = $result_web['website_domain'];
            $company_name = $result_web['company_name'];
            $from = $result_web['reciever_email'];
        }
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $name = $this->input->post('name');
        $email_address = $this->input->post('email_address');
        $contact = $this->input->post('contact_number');
        $username = $this->input->post('signup_username');
        $company = $this->input->post('company_name');
        $industry = $this->input->post('industry');
        $ip = $this->input->post('ip');
        $country = $this->input->post('country');


        $username1 = trim($username);
        $size_username = strlen($username1);

        $contact1 = trim($contact);
        $size_contact = strlen($contact1);

        if ($size_username < 5 || $size_contact < 10) {
            $message = "User creation failed! Please try again later.";
            return array('status' => '103', 'message' => $message);
        }



        if ($country == "INDIA" || $country == "India") {
            $country_status = 1;
        } else {
            $country_status = 2;
        }
        //white list to contact number
        $white_level = array(
            'white_list_number' => $contact,
        );

        $this->db->insert('white_lists', $white_level);

        $terms = 0;
        if ($this->input->post('terms')) {
            $terms = $this->input->post('terms');
        }
        // Check Username
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $message = "Username not available! Please try another";
            return array('status' => '100', 'message' => $message);
        } else {
            if (preg_match('/[^A-Za-z_\-0-9]/i', $username)) {
                $message = "Username must be start with a character";
                return array('status' => '100', 'message' => $message);
            } else {
                $this->load->model('admin_data_model', '', TRUE);
                // Default User Group
                $result_admin = $this->admin_data_model->getAdminDetails();
                if ($result_admin) {
                    $admin_id = $result_admin->admin_id;
                    $admin_email = $result_admin->admin_email;
                    $admin_contact = $result_admin->admin_contact;
                    $admin_company = $result_admin->admin_company;
                    $total_pr_balance = $result_admin->total_pr_balance;
                    $total_tr_balance = $result_admin->total_tr_balance;
                }
                // Promotional
                $pr_user_group_id = 0;
                $result_pr = $this->admin_data_model->getDefaultUserGroup('Promotional', 1);
                if ($result_pr)
                    $pr_user_group_id = $result_pr->user_group_id;
                // Transactional
                $tr_user_group_id = 0;
                $result_tr = $this->admin_data_model->getDefaultUserGroup('Transactional', 1);
                if ($result_tr)
                    $tr_user_group_id = $result_tr->user_group_id;

                // Transactional
                $tr_user_group_id = 0;
                $result_tr = $this->admin_data_model->getDefaultUserGroup('Transactional', 1);
                if ($result_tr)
                    $tr_user_group_id = $result_tr->user_group_id;

                //Voice tr
                $voice_tr_route = 0;
                $result_voice_tr = $this->admin_data_model->getDefaultUserGroupVoice('tr', 1);
                if ($result_voice_tr)
                    $voice_tr_route = $result_voice_tr->voice_route_id;
                //Voice pr
                $voice_pr_route = 0;
                $result_voice_pr = $this->admin_data_model->getDefaultUserGroupVoice('pr', 1);
                if ($result_voice_pr)
                    $voice_pr_route = $result_voice_pr->voice_route_id;

                // Default Settings
                $sms_limit = 1000;
                $pr_sender_id_check = 1;
                $pr_sender_id_type = 'Alphabetic';
                $pr_sender_id_length = 6;
                $pr_dnd_check = 0;
                $tr_sender_id_check = 1;
                $tr_sender_id_type = 'Alphabetic';
                $tr_sender_id_length = 6;
                $tr_keyword_check = 0;

                $demo_balance = 10;
                $expiry_days = 180;
                $signup_sender = "";
                $signup_message = "";
                $signup_subject = "";
                $signup_body = "";
                $result_settings = $this->admin_data_model->getDefaultSettings();
                if ($result_settings) {
                    $sms_limit = $result_settings->sms_limit;
                    $pr_sender_id_check = $result_settings->pr_sender_id_check;
                    $pr_sender_id_type = $result_settings->pr_sender_id_type;
                    $pr_sender_id_length = $result_settings->pr_sender_id_length;
                    $pr_dnd_check = $result_settings->pr_dnd_check;
                    $tr_sender_id_check = $result_settings->tr_sender_id_check;
                    $tr_sender_id_type = $result_settings->tr_sender_id_type;
                    $tr_sender_id_length = $result_settings->tr_sender_id_length;
                    $tr_keyword_check = $result_settings->tr_keyword_check;

                    $demo_balance = $result_settings->demo_balance;
                    $expiry_days = $result_settings->expiry_days;
                    $signup_sender = $result_settings->signup_sender;
                    $signup_message = $result_settings->signup_message;
                    $signup_subject = $result_settings->signup_subject;
                    $signup_body = $result_settings->signup_body;

                    $pr_deliver_ratio = $result_settings->pr_deliver_ratio;
                    $pr_fail_ratio = $result_settings->pr_fail_ratio;
                    $tr_deliver_ratio = $result_settings->tr_deliver_ratio;
                    $tr_fail_ratio = $result_settings->tr_fail_ratio;
                    $vpr_deliver_ratio = $result_settings->vpr_deliver_ratio;
                    $vpr_fail_ratio = $result_settings->vpr_fail_ratio;
                    $vtr_deliver_ratio = $result_settings->vtr_deliver_ratio;
                    $vtr_fail_ratio = $result_settings->vtr_fail_ratio;
                }
                $pr_demo_balance = 0;
                $tr_demo_balance = 0;
                if ($total_pr_balance && $total_pr_balance > $demo_balance) {
                    $pr_demo_balance = $demo_balance;
                }
                if ($total_tr_balance && $total_tr_balance > $demo_balance) {
                    $tr_demo_balance = $demo_balance;
                }
                $current_date = date('d-m-Y');
                $expiry_date = date('d-m-Y', strtotime($current_date . '+' . $expiry_days . 'days'));

                // Generate Password For User
                $password = random_string('numeric', 6);
                // Generate Auth key
                $auth_key = random_string('unique', 32);
                $creation_date = date('d-m-Y h:i A');
                $data = array(
                    'admin_id' => $admin_id,
                    'name' => $name,
                    'email_address' => $email_address,
                    'contact_number' => $contact,
                    'username' => $username,
                    'password' => md5($password),
                    'auth_key' => $auth_key,
                    'utype' => 'User',
                    'country' => $country,
                    'creation_date' => $creation_date,
                    'pro_user_group_id' => $pr_user_group_id,
                    'tr_user_group_id' => $tr_user_group_id,
                    'company_name' => $company,
                    'number_allowed' => $sms_limit,
                    'p_sender_id_option' => $pr_sender_id_check,
                    'p_sender_id_type' => $pr_sender_id_type,
                    'p_sender_id_length' => $pr_sender_id_length,
                    'dnd_check' => $pr_dnd_check,
                    't_sender_id_option' => $tr_sender_id_check,
                    'sender_id_type' => $tr_sender_id_type,
                    'sender_id_length' => $tr_sender_id_length,
                    'keyword_option' => $tr_keyword_check,
                    'industry' => $industry,
                    'country_status' => $country_status,
                    'terms_conditions' => $terms,
                    'pr_sms_balance' => 0,
                    'tr_sms_balance' => $tr_demo_balance,
                    'expiry_date' => $expiry_date,
                    'user_ip_address' => $ip,
                    'pr_user_fake_ratio' => $pr_deliver_ratio,
                    'pr_user_fail_ratio' => $pr_fail_ratio,
                    'user_fake_ratio' => $tr_deliver_ratio,
                    'user_fail_ratio' => $tr_fail_ratio,
                    'vpr_fake_ratio' => $vpr_deliver_ratio,
                    'vpr_fail_ratio' => $vpr_fail_ratio,
                    'vtr_fake_ratio' => 80,
                    'vtr_fail_ratio' => 5,
                    'prtodnd_route' => $pr_user_group_id,
                    'stock_route' => $pr_user_group_id,
                    'voice_tr_route' => $voice_tr_route,
                    'voice_pr_route' => $voice_pr_route,
                );

                //==================================================
                // Send SMS
                $purpose = "User Signup";
                $name_array = explode(' ', $name);
                $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                $web_domain = $server_protocol . "://" . $web_domain;
                // Load SMS_Model
                $this->load->model('Sms_Model', 'sms_model');
                // Get Short URL
                $short_web_domain = $this->sms_model->googleUrlShortner($web_domain . "/signin");
                //$short_web_domain = "http://sms.bulksmsserviceproviders.com/signin";
                if ($signup_message != "") {
                    $messages = $signup_message;
                    $messages = str_replace("##fname##", $name_array[0], $messages);
                    $messages = str_replace("##url##", $short_web_domain, $messages);
                    $messages = str_replace("##username##", $username, $messages);
                    $messages = str_replace("##password##", $password, $messages);
                    $messages = urlencode($messages);
                } else {
                    $messages = urlencode("Hello $name_array[0], Please login with following details: URL:" . $short_web_domain . "; Username:$username;Password: $password");
                }
                // Prepare you post parameters
                if ($signup_sender != "")
                    $signup_sender_id = $signup_sender;
                else
                    $signup_sender_id = "SIGNUP";

                // Prepare you post parameters
                // API URL
                $url = $web_domain . "/admin/send_http/";
                $sms_array = array(
                    'contact' => $contact,
                    'sender' => $signup_sender_id,
                    'messages' => $messages,
                    'purpose' => $purpose
                );
                //==================================================
                // Send Mail
                if ($signup_subject != "")
                    $subject = $signup_subject;
                else
                    $subject = "Your account has been created successfully!";

                if ($signup_body != "") {
                    $messages = $signup_body;
                    $messages = str_replace("##fname##", $name_array[0], $messages);
                    $messages = str_replace("##url##", $web_domain . "/signin", $messages);
                    $messages = str_replace("##username##", $username, $messages);
                    $messages = str_replace("##password##", $password, $messages);
                    $body = $messages;
                    $body = $body . "<br/><br/><br/>";
                    $body = $body . "<h4>Regards,</h4>";
                    $body = $body . "<h4>Team $admin_company</h4>";
                } else {
                    $body = $this->utility_model->emailSignup($web_domain, $username, $admin_company, 1);
                }

                // Prepare Email Array
                $mail_array1 = array(
                    'from_email' => $admin_email,
                    'from_name' => $admin_company,
                    'to_email' => $email_address,
                    'subject' => $subject,
                    'message' => $body
                );

                //==================================================
                // Send Mail To User
                $subject1 = "Signup on Bulk SMS Service Provider!";
                $temp_array = array('Bulk SMS Service Provider', $name, $username, $contact, $email_address, $company, $industry, $web_domain);
                $body1 = $this->utility_model->emailSignInfo($temp_array);
                // Prepare Email Array
                $mail_array2 = array(
                    'from_email' => $admin_email,
                    'from_name' => $admin_company,
                    'to_email' => $admin_email,
                    'subject' => $subject1,
                    'message' => $body1
                );

                //==================================================
                if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array1) && $this->utility_model->sendEmail($mail_array2)) {
                    $up_pr_sms_balance = $total_pr_balance - $pr_demo_balance;
                    $up_tr_sms_balance = $total_tr_balance - $tr_demo_balance;
                    $bal_data = array(
                        'total_pr_balance' => $up_pr_sms_balance,
                        'total_tr_balance' => $up_tr_sms_balance
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $bal_data);

                    //curl_close($ch);
                    $message = "You are registered successully! Your login details sent on your contact number.";
                    return array('status' => '200', 'message' => $message);
                } else {
                    $message = "User creation failed! Please try again later.";
                    return array('status' => '103', 'message' => $message);
                }
            }
        }
    }

    function saveHttpBulkSMSUser($array_users) {

        $name = $array_users['name'];
        $email = $array_users['email'];
        $contact = $array_users['contact'];

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
                                                                                        ' . $name . '
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
                                                                                        ' . $contact . '
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
                                                                                        ' . $email . '
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
                                                                                            <a href="http://www.bulksmsserviceproviders.com" target="_blank" style="text-decoration:none;color:#828282;">
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
        $subject = "Signup on Bulk SMS Service Provider!";

        // Send Mail To Admin

        $from_email = "bulk24sms.vijendra@gmail.com";
        $from_name = "BULK24SMS Network";

        // Load Email Library For Sending E-mails
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);
        $this->email->send();

        //==================================================
    }

    // Cron Event
    function cronEvent() {
        $current_date = date('d-m-Y h:i:s A');
        $data = array(
            'current_date_time' => $current_date
        );
        $this->db->insert('cron_table', $data);
    }

    // Update Contacts
    function updateContacts() {
        // Get Contact Number
        $this->db->select('mobile_no, sms_id');
        $this->db->from('sent_sms');
        $query = $this->db->get();
        if ($query->num_rows()) {
            foreach ($query->result() as $sms) {
                $sms_id = $sms->sms_id;
                $number = $sms->mobile_no;
                if (strlen($number) == 10) {
                    $new_number = "91" . $number;
                    $data = array(
                        'mobile_no' => $new_number
                    );
                    $this->db->where('sms_id', $sms_id);
                    $this->db->update('sent_sms', $data);
                }
            }
        }
    }

    // Generate Verification Code
    function generateVerifyCode($user_id = 0, $contact_number = null, $username = null, $name = null) {
        // Website Info
        $domain_name = $_SERVER['SERVER_NAME'];
        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $web_domain = $result_web['website_domain'];
            $company_name = $result_web['company_name'];
            $from = $result_web['reciever_email'];
        }
        $verification_code = random_string('numeric', 4);
        $data = array(
            'verification_code' => $verification_code,
            'user_id' => $user_id
        );

        $this->load->model('admin_data_model', '', TRUE);
        // Location Settings
        $location_sender = "";
        $location_message = "";
        $result_settings = $this->admin_data_model->getDefaultSettings();
        if ($result_settings) {
            $location_sender = $result_settings->location_sender;
            $location_message = $result_settings->location_message;
        }
        //==================================================
        // Send SMS
        $purpose = "Verify Location";
        $name_array = explode(' ', $name);
        if ($location_message != "") {
            $messages = $location_message;
            $messages = str_replace("##fname##", $name_array[0], $messages);
            $messages = str_replace("##username##", $username, $messages);
            $messages = str_replace("##code##", $verification_code, $messages);
            $messages = urlencode($messages);
        } else {
            $messages = urlencode("Enter: $verification_code to verify your location for username: $username. Keep messageing!");
        }
        // Prepare you post parameters
        if ($location_sender != "")
            $location_sender_id = $location_sender;
        else
            $location_sender_id = "BULKIN";

        // API URL
        $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
        $web_domain = $server_protocol . "://" . $web_domain;
        $url = $web_domain . "/admin/send_http/";
        $sms_array = array(
            'contact' => $contact_number,
            'sender' => $location_sender_id,
            'messages' => $messages,
            'purpose' => $purpose
        );
        //========================================================================================//
        if ($this->db->insert('user_ip_addresses', $data) && $this->utility_model->sendSMS($url, $sms_array)) {
            //curl_close($ch);
            return true;
        } else {
            return false;
        }
    }

    //=======================================================================//
    // Send OTP
    function sendOTP() {
        $sms_array = $this->session->userdata('sms_array');
        $web_domain = $this->session->userdata('verification_domain');
        // API URL
        $url = $web_domain . "/admin/send_http/";
        if ($this->utility_model->sendSMS($url, $sms_array)) {
            return true;
        } else {
            return false;
        }
    }

    //=======================================================================//
    //=======================================================================//
    // Functions For Bulk24SMS Networks
    //=======================================================================//
    // Check Username For Bulk24SMS Networks Signup
    function checkUsernameB24SMS() {
        $username = $this->input->post('username');
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    // Save User For Bulk24SMS Networks
    function saveUserB24SMS() {
        // Website Info
        $user_id = $web_domain = $company_name = $from = "";
        $domain_name = 'sms.bulksmsserviceproviders.com';
        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $user_id = $result_web['user_id'];
            $web_domain = $result_web['website_domain'];
            $company_name = $result_web['company_name'];
            $from = $result_web['reciever_email'];
        }
        if ($user_id) {
            $name = $this->input->post('full_name');
            $username = $this->input->post('username');
            $contact = $this->input->post('contact_number');
            $email_address = $this->input->post('email_address');
            $company = $this->input->post('company_name');
            $industry = $this->input->post('industry');
            $ip_address = $this->input->post('ip_address');
            $terms = 0;
            if ($this->input->post('terms')) {
                $terms = $this->input->post('terms');
            }
            // Check Username
            $this->db->select('username');
            $this->db->from('users');
            $this->db->where('username', $username);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return 100;
            } else {
                if (preg_match('/[^A-Za-z_\-0-9]/i', $username)) {
                    return 101;
                } else {
                    $result_user = $this->getUserInfo($user_id);
                    if ($result_user) {
                        if ($result_user->most_parent_id) {
                            $most_parent_id = $result_user->most_parent_id;
                        } else {
                            $most_parent_id = $user_id;
                        }
                        $pr_sms_balance = $result_user->pr_sms_balance;
                        $tr_sms_balance = $result_user->tr_sms_balance;
                        $number_allowed = $result_user->number_allowed;
                        $pro_user_group_id = $result_user->pro_user_group_id;
                        $tr_user_group_id = $result_user->tr_user_group_id;
                        $user_ratio = $result_user->user_ratio;
                        $user_fake_ratio = $result_user->user_fake_ratio;
                        $user_fail_ratio = $result_user->user_fail_ratio;
                        // Reseller Settings
                        $demo_balance = $result_user->demo_balance;
                        $pr_demo_balance = 0;
                        $tr_demo_balance = 0;
                        if ($pr_sms_balance && $pr_sms_balance > $demo_balance) {
                            $pr_demo_balance = $demo_balance;
                        }
                        if ($tr_sms_balance && $tr_sms_balance > $demo_balance) {
                            $tr_demo_balance = $demo_balance;
                        }
                        $expiry_days = $result_user->expiry_days;
                        $signup_sender = $result_user->signup_sender;
                        $signup_message = $result_user->signup_message;
                        $signup_subject = $result_user->signup_subject;
                        $signup_body = $result_user->signup_body;
                        $current_date = date('d-m-Y');
                        $expiry_date = date('d-m-Y', strtotime($current_date . '+' . $expiry_days . 'days'));
                        //==================================================
                        // Generate Password For User
                        $password = random_string('numeric', 6);
                        // Generate Auth key
                        $auth_key = random_string('unique', 32);
                        $creation_date = date('d-m-Y h:i A');
                        $data = array(
                            'ref_user_id' => $user_id,
                            'most_parent_id' => $most_parent_id,
                            'name' => $name,
                            'email_address' => $email_address,
                            'contact_number' => $contact,
                            'username' => $username,
                            'password' => md5($password),
                            'auth_key' => $auth_key,
                            'utype' => 'User',
                            'creation_date' => $creation_date,
                            'number_allowed' => $number_allowed,
                            'pro_user_group_id' => $pro_user_group_id,
                            'tr_user_group_id' => $tr_user_group_id,
                            'company_name' => $company,
                            'industry' => $industry,
                            'user_ratio' => $user_ratio,
                            'user_fake_ratio' => $user_fake_ratio,
                            'user_fail_ratio' => $user_fail_ratio,
                            'terms_conditions' => $terms,
                            'expiry_date' => $expiry_date,
                            'pr_sms_balance' => $pr_demo_balance,
                            'tr_sms_balance' => $tr_demo_balance,
                            'user_ip_address' => $ip_address,
                            'voice_tr_route' => 1,
                            'voice_pr_route' => 2,
                        );
                        //==================================================
                        // Send SMS
                        $purpose = "User Signup";
                        $name_array = explode(' ', $name);
                        $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                        $web_domain = $server_protocol . "://" . $web_domain;
                        $real_web_domain = "http://bulk24sms.com/bssp.php";
                        // Load SMS_Model
                        $this->load->model('Sms_Model', 'sms_model');
                        // Get Short URL
                        $short_web_domain = $this->sms_model->googleUrlShortner($real_web_domain);
                        // $short_web_domain = "http://sms.bulksmsserviceproviders.com/signin";
                        if ($signup_message != "") {
                            $messages = $signup_message;
                            $messages = str_replace("##fname##", $name_array[0], $messages);
                            $messages = str_replace("##url##", $short_web_domain, $messages);
                            $messages = str_replace("##username##", $username, $messages);
                            $messages = str_replace("##password##", $password, $messages);
                            $messages = urlencode($messages);
                        } else {
                            $messages = urlencode("Hello $name_array[0], Please login with following details: URL:" . $short_web_domain . "; Username:$username;Password: $password");
                        }
                        // Prepare you post parameters
                        if ($signup_sender != "")
                            $signup_sender_id = $signup_sender;
                        else
                            $signup_sender_id = "SIGNUP";

                        // Prepare you post parameters
                        // API URL
                        $url = $web_domain . "/admin/send_http/";
                        $sms_array = array(
                            'contact' => $contact,
                            'sender' => $signup_sender_id,
                            'messages' => $messages,
                            'purpose' => $purpose
                        );
                        //==================================================
                        // Send Mail
                        if ($signup_subject != "")
                            $subject = $signup_subject;
                        else
                            $subject = "Your account has been created successfully!";

                        if ($signup_body != "") {
                            $messages = $signup_body;
                            $messages = str_replace("##fname##", $name_array[0], $messages);
                            $messages = str_replace("##url##", $real_web_domain, $messages);
                            $messages = str_replace("##username##", $username, $messages);
                            $messages = str_replace("##password##", $password, $messages);
                            $body = $messages;
                            $body = $body . "<br/><br/><br/>";
                            $body = $body . "<h4>Regards,</h4>";
                            $body = $body . "<h4>Team Bulk24SMS Networks</h4>";
                        } else {
                            $body = $this->utility_model->emailSignup($real_web_domain, $username, 'Bulk24SMS Networks', 0);
                        }
                        // Prepare Email Array
                        $mail_array1 = array(
                            'from_email' => $from,
                            'from_name' => 'Bulk24SMS Networks',
                            'to_email' => $email_address,
                            'subject' => $subject,
                            'message' => $body
                        );
                        //==================================================
                        // Send Mail To Us
                        $subject1 = "Signup on Bulk24SMS Networks!";
                        $temp_array = array('Bulk24SMS Networks', $name, $username, $contact, $email_address, $company, $industry, $real_web_domain);
                        $body1 = $this->utility_model->emailSignInfo($temp_array);
                        // Prepare Email Array
                        $mail_array2 = array(
                            'from_email' => $from,
                            'from_name' => "Bulk24SMS Networks",
                            'to_email' => $from,
                            'subject' => $subject1,
                            'message' => $body1
                        );
                        //==================================================
                        if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array1) && $this->utility_model->sendEmail($mail_array2)) {
                            $up_pr_sms_balance = $pr_sms_balance - $pr_demo_balance;
                            $up_tr_sms_balance = $tr_sms_balance - $tr_demo_balance;
                            $bal_data = array(
                                'pr_sms_balance' => $up_pr_sms_balance,
                                'tr_sms_balance' => $up_tr_sms_balance
                            );
                            $this->db->where('user_id', $user_id);
                            $this->db->update('users', $bal_data);
                            return 200;
                        } else {
                            return 102;
                        }
                    } else {
                        return 102;
                    }
                }
            }
        } else {
            return 103;
        }
    }

    //save users history
    public function saveUserHistory($actual_link, $url) {
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];
        $username = $session_data['username'];
        $actual_link;
        $url;
        $history_url = $actual_link . $url;
        $ip = $_SERVER['REMOTE_ADDR'];
        $date_time = date('d-m-y H:i:s');
        $date = date('y-m-d');
        $status = "User";

        $history_data = array(
            'admin_id' => $user_id,
            'admin_username' => $username,
            'status' => $status,
            'ip_address' => $ip,
            'history_url' => $history_url,
            'date_time' => $date_time,
            'date' => $date
        );
        $this->db->insert('admin_history', $history_data);
    }

    // Save User For Bulk 24  SMS Service Provider
    function saveBulk24SMSUser() {
        // Website Info
        $user_id = $web_domain = $company_name = $from = "";
        $domain_name = 'sms.bulksmsserviceproviders.com';
        $result_web = $this->login_model->getWebsiteInfo($domain_name);
        if ($result_web) {
            $user_id = $result_web['user_id'];
            $web_domain = $result_web['website_domain'];
            $company_name = $result_web['company_name'];
            $from = $result_web['reciever_email'];
        }

        $ip_address = $_SERVER['REMOTE_ADDR'];
        $name = $this->input->post('full_name');
        $email_address = $this->input->post('email');
        $contact = $this->input->post('contact_no');
        $username = $this->input->post('username');
        $company = $this->input->post('company_name');
        $industry = $this->input->post('industry');
        $country_status = 1;


        //white list to contact number
        $white_level = array(
            'white_list_number' => $contact,
        );

        $this->db->insert('white_lists', $white_level);

        $terms = 0;
        if ($this->input->post('terms')) {
            $terms = $this->input->post('terms');
        }
        // Check Username
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $message = "Username not available! Please try another";
            return array('status' => '100', 'message' => $message);
        } else {
            if (preg_match('/[^A-Za-z_\-0-9]/i', $username)) {
                $message = "Username must be start with a character";
                return array('status' => '100', 'message' => $message);
            } else {
                $this->load->model('admin_data_model', '', TRUE);
                // Default User Group
                $result_admin = $this->admin_data_model->getAdminDetails();
                if ($result_admin) {
                    $admin_id = $result_admin->admin_id;
                    $admin_email = $result_admin->admin_email;
                    $admin_contact = $result_admin->admin_contact;
                    $admin_company = $result_admin->admin_company;
                    $total_pr_balance = $result_admin->total_pr_balance;
                    $total_tr_balance = $result_admin->total_tr_balance;
                }

                $this->db->select('stock_route,prtodnd_route');
                $this->db->from('users');
                $this->db->where('user_id', 5798);
                $this->db->limit(1);
                $query_route = $this->db->get();
                $result_route = $query_route->row();
                $prtodnd_route = $result_route->prtodnd_route;
                $stock_route = $result_route->stock_route;
                // Promotional
                $pr_user_group_id = 0;
                $result_pr = $this->admin_data_model->getDefaultUserGroup('Promotional', 1);
                if ($result_pr)
                    $pr_user_group_id = $result_pr->user_group_id;
                // Transactional
                $tr_user_group_id = 0;
                $result_tr = $this->admin_data_model->getDefaultUserGroup('Transactional', 1);
                if ($result_tr)
                    $tr_user_group_id = $result_tr->user_group_id;
                // Default Settings
                $sms_limit = 1000;
                $pr_sender_id_check = 1;
                $pr_sender_id_type = 'Alphabetic';
                $pr_sender_id_length = 6;
                $pr_dnd_check = 0;
                $tr_sender_id_check = 1;
                $tr_sender_id_type = 'Alphabetic';
                $tr_sender_id_length = 6;
                $tr_keyword_check = 0;

                $demo_balance = 10;
                $expiry_days = 180;
                $signup_sender = "";
                $signup_message = "";
                $signup_subject = "";
                $signup_body = "";
                $result_settings = $this->admin_data_model->getDefaultSettings();
                if ($result_settings) {
                    $sms_limit = $result_settings->sms_limit;
                    $pr_sender_id_check = $result_settings->pr_sender_id_check;
                    $pr_sender_id_type = $result_settings->pr_sender_id_type;
                    $pr_sender_id_length = $result_settings->pr_sender_id_length;
                    $pr_dnd_check = $result_settings->pr_dnd_check;
                    $tr_sender_id_check = $result_settings->tr_sender_id_check;
                    $tr_sender_id_type = $result_settings->tr_sender_id_type;
                    $tr_sender_id_length = $result_settings->tr_sender_id_length;
                    $tr_keyword_check = $result_settings->tr_keyword_check;

                    $demo_balance = $result_settings->demo_balance;
                    $expiry_days = $result_settings->expiry_days;
                    $signup_sender = $result_settings->signup_sender;
                    $signup_message = $result_settings->signup_message;
                    $signup_subject = $result_settings->signup_subject;
                    $signup_body = $result_settings->signup_body;

                    $pr_deliver_ratio = $result_settings->pr_deliver_ratio;
                    $pr_fail_ratio = $result_settings->pr_fail_ratio;
                    $tr_deliver_ratio = $result_settings->tr_deliver_ratio;
                    $tr_fail_ratio = $result_settings->tr_fail_ratio;
                    $vpr_deliver_ratio = $result_settings->vpr_deliver_ratio;
                    $vpr_fail_ratio = $result_settings->vpr_fail_ratio;
                    $vtr_deliver_ratio = $result_settings->vtr_deliver_ratio;
                    $vtr_fail_ratio = $result_settings->vtr_fail_ratio;
                }
                $pr_demo_balance = 0;
                $tr_demo_balance = 0;
                if ($total_pr_balance && $total_pr_balance > $demo_balance) {
                    $pr_demo_balance = $demo_balance;
                }
                if ($total_tr_balance && $total_tr_balance > $demo_balance) {
                    $tr_demo_balance = $demo_balance;
                }
                $current_date = date('d-m-Y');
                $expiry_date = date('d-m-Y', strtotime($current_date . '+' . $expiry_days . 'days'));

                // Generate Password For User
                $password = random_string('numeric', 6);
                // Generate Auth key
                $auth_key = random_string('unique', 32);
                $creation_date = date('d-m-Y h:i A');
                $data = array(
                    'ref_user_id' => 5798,
                    'most_parent_id' => 5798,
                    'name' => $name,
                    'email_address' => $email_address,
                    'contact_number' => $contact,
                    'username' => $username,
                    'password' => md5($password),
                    'auth_key' => $auth_key,
                    'utype' => 'User',
                    'country' => $country,
                    'creation_date' => $creation_date,
                    'pro_user_group_id' => $pr_user_group_id,
                    'tr_user_group_id' => $tr_user_group_id,
                    'prtodnd_route' => $prtodnd_route,
                    'stock_route' => $stock_route,
                    'company_name' => $company,
                    'number_allowed' => $sms_limit,
                    'p_sender_id_option' => $pr_sender_id_check,
                    'p_sender_id_type' => $pr_sender_id_type,
                    'p_sender_id_length' => $pr_sender_id_length,
                    'dnd_check' => $pr_dnd_check,
                    't_sender_id_option' => $tr_sender_id_check,
                    'sender_id_type' => $tr_sender_id_type,
                    'sender_id_length' => $tr_sender_id_length,
                    'keyword_option' => $tr_keyword_check,
                    'industry' => $industry,
                    'country_status' => $country_status,
                    'terms_conditions' => $terms,
                    'pr_sms_balance' => 0,
                    'tr_sms_balance' => $tr_demo_balance,
                    'expiry_date' => $expiry_date,
                    'user_ip_address' => $ip_address,
                    'pr_user_fake_ratio' => $pr_deliver_ratio,
                    'pr_user_fail_ratio' => $pr_fail_ratio,
                    'user_fake_ratio' => $tr_deliver_ratio,
                    'user_fail_ratio' => $tr_fail_ratio,
                    'vpr_fake_ratio' => $vpr_deliver_ratio,
                    'vpr_fail_ratio' => $vpr_fail_ratio,
                    'vtr_fake_ratio' => 80,
                    'vtr_fail_ratio' => 5,
                    'link_value' => $link_value,
                    'prtodnd_route' => $pr_user_group_id,
                    'stock_route' => $pr_user_group_id,
                    'voice_tr_route' => 1,
                    'voice_pr_route' => 2,
                );

                //==================================================
                // Send SMS
                $purpose = "User Signup";
                $name_array = explode(' ', $name);
                $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                $web_domain = $server_protocol . "://" . $web_domain;
                // Load SMS_Model
                $this->load->model('Sms_Model', 'sms_model');
                // Get Short URL
                $short_web_domain = $this->sms_model->googleUrlShortner("http://www.bulk24sms.com/signin.php");
                //$short_web_domain = "http://sms.bulksmsserviceproviders.com/signin";
                if ($signup_message != "") {
                    $messages = $signup_message;
                    $messages = str_replace("##fname##", $name_array[0], $messages);
                    $messages = str_replace("##url##", $short_web_domain, $messages);
                    $messages = str_replace("##username##", $username, $messages);
                    $messages = str_replace("##password##", $password, $messages);
                    $messages = urlencode($messages);
                } else {
                    $messages = urlencode("Hello $name_array[0], Please login with following details: URL:" . $short_web_domain . "; Username:$username;Password: $password");
                }
                // Prepare you post parameters
                if ($signup_sender != "")
                    $signup_sender_id = $signup_sender;
                else
                    $signup_sender_id = "SIGNUP";

                // Prepare you post parameters
                // API URL
                $url = $web_domain . "/admin/send_http/";
                $sms_array = array(
                    'contact' => $contact,
                    'sender' => $signup_sender_id,
                    'messages' => $messages,
                    'purpose' => $purpose
                );
                //==================================================
                // Send Mail
                if ($signup_subject != "")
                    $subject = $signup_subject;
                else
                    $subject = "Your account has been created successfully!";

                if ($signup_body != "") {
                    $messages = $signup_body;
                    $messages = str_replace("##fname##", $name_array[0], $messages);
                    $messages = str_replace("##url##", "http://www.bulk24sms.com/signin.php", $messages);
                    $messages = str_replace("##username##", $username, $messages);
                    $messages = str_replace("##password##", $password, $messages);
                    $body = $messages;
                    $body = $body . "<br/><br/><br/>";
                    $body = $body . "<h4>Regards,</h4>";
                    $body = $body . "<h4>Team $admin_company</h4>";
                } else {
                    $body = $this->utility_model->emailbulk24smsSignup($web_domain, $username, $admin_company, 1);
                }

                // Prepare Email Array
                $mail_array1 = array(
                    'from_email' => "rupal@bulk24sms.com",
                    'from_name' => "BULK24SMS",
                    'to_email' => $email_address,
                    'subject' => $subject,
                    'message' => $body
                );

                //==================================================
                // Send Mail To User
                $subject1 = "Signup on Bulk24SMS Network";
                $temp_array = array('Bulk24SMS Network', $name, $username, $contact, $email_address, $company, $industry, 'http://www.bulk24sms.com/signin.php');
                $body1 = $this->utility_model->emailSignInfoBulk24SMS($temp_array);
                // Prepare Email Array
                $mail_array2 = array(
                    'from_email' => "bulk24sms.vijendra@gmail.com",
                    'from_name' => "BULK24SMS",
                    'to_email' => "bulk24sms.vijendra@gmail.com",
                    'subject' => $subject1,
                    'message' => $body1
                );

                //==================================================
                if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array1)) {
                    $up_pr_sms_balance = $total_pr_balance - $pr_demo_balance;
                    $up_tr_sms_balance = $total_tr_balance - $tr_demo_balance;
                    $bal_data = array(
                        'total_pr_balance' => $up_pr_sms_balance,
                        'total_tr_balance' => $up_tr_sms_balance
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $bal_data);

                    //curl_close($ch);
                    $message = "You are registered successully! Your login details sent on your contact number.";
                    return array('status' => '200', 'message' => $message);
                } else {
                    $message = "User creation failed! Please try again later.";
                    return array('status' => '103', 'message' => $message);
                }
            }
        }
    }

    //=======================================================================//
}

?>