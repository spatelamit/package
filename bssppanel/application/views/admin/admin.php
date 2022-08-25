<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends CI_Controller {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Admin_Data_Model', 'admin_data_model');
        $this->load->model('User_Data_Model', 'user_data_model');
        $this->load->model('Utility_Model', 'utility_model');
        $this->load->model('Sms_Model', 'sms_model');
        $this->load->library('pagination');
        // Update Login Info And Some Common Data
        $data = new stdClass();
        if ($this->session->userdata('admin_logged_in')) {
            $session_userdata = $this->session->userdata('admin_logged_in');
            $username = $session_userdata['username'];
            $admin_id = $session_userdata['admin_id'];
            $atype = $session_userdata['atype'];
            // Get All Online Users
            $online_users = $this->utility_model->getAllOnlineUsers();
            if ($online_users) {
                $data->online_users = $online_users;
            }

            if ($atype == 1) {
                // Get All SMSC Balance
                $smsc_balance = $this->utility_model->getAllSMSCBalance();
                if ($smsc_balance) {
                    $data->smsc_balance = $smsc_balance;
                }
            }
            // Admin Username,Admin Id & Admin Type
            if ($admin_id) {
                $data->admin_id = $admin_id;
                $data->username = $username;
                $data->atype = $atype;
            }


            // Admin Total Balance (TR, PR, Long Code, Short Code)
            $admin_balance = $this->admin_data_model->getAdminBalance($admin_id);
            if ($admin_balance) {
                $data->total_pr_balance = $admin_balance['total_pr_balance'];
                $data->total_tr_balance = $admin_balance['total_tr_balance'];
                $data->prtodnd_balance = $admin_balance['total_prtodnd_balance'];
                $data->stock_balance = $admin_balance['total_stock_balance'];
                $data->admin_pr_credits = $admin_balance['admin_pr_credits'];
                $data->total_prtodnd_credits = $admin_balance['total_prtodnd_credits'];
                $data->total_stock_credits = $admin_balance['total_stock_credits'];
                $data->admin_tr_credits = $admin_balance['admin_tr_credits'];
                $data->total_vpr_balance = $admin_balance['total_vpr_balance'];
                $data->total_vtr_balance = $admin_balance['total_vtr_balance'];
                $data->total_lcode_balance = $admin_balance['total_lcode_balance'];
                $data->total_scode_balance = $admin_balance['total_scode_balance'];
                $data->total_mcall_balance = $admin_balance['total_mcall_balance'];
                $data->international_sms = $admin_balance['international_sms'];
                $data->permissions = $admin_balance['permissions'];
            }
        }
        $this->load->vars($data);
        // Set Header
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Load All Admin View
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Admin Login View

    public function demo_test() {
        echo "string";
       
    }
    public function __1index9754() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $domain_name = $_SERVER['SERVER_NAME'];
        if ($domain_name == 'sms.bulksmsserviceproviders.com' || $domain_name == 'localhost' || $domain_name == '192.168.1.231') {
            if ($this->session->userdata('admin_logged_in')) {
                redirect('admin/spam_transactional', 'refresh');
            } else {
                $this->load->helper('captcha');
                // numeric random number for captcha
                $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                // setting up captcha config
                $vals = array(
                    'word' => $random_number,
                    'img_path' => './captcha/',
                    'img_url' => base_url() . 'captcha/',
                    'img_width' => 250,
                    'img_height' => 40,
                    'expiration' => 7200
                );
                $data['captcha'] = create_captcha($vals);
                $this->session->set_userdata('captcha_word', $data['captcha']['word']);
                $this->load->view('admin/index', $data);
            }
        } else {
            redirect('http://' . $domain_name, 'refresh');
        }
    }

    // Validate Admin
//     public function validate_admin() {
//        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
//        $url = $this->uri->uri_string();
//        $this->admin_data_model->saveAdminHistory($actual_link, $url);
//        $result = $this->admin_data_model->validateAdmin();
//         $string  = "dghjkfdfg%f5g4=f5524&invelid_login&1h&f12bhh=%21hjmh5j4=hj52h4df#32hk1bn&invelid_login&53h#g5h52h2#jg3211yhjh2%fg1yj54ggh42@df1b465hf463214f%5g2fh46";
//        if ($result == '100') {
//            $this->session->set_flashdata('message', 'Error: You entered wrong captcha!');
//            $this->session->set_flashdata('message_type', 'alert-danger');
//            redirect('vision/index/'.$string, 'refresh');
//        } elseif ($result == '101') {
//            $this->session->set_flashdata('message', 'Error: You entered wrong contact number!');
//            $this->session->set_flashdata('message_type', 'alert-danger');
//            redirect('vision/index/'.$string, 'refresh');
//        } elseif ($result) {
//            $session_array = array(
//                'admin_id' => $result->admin_id,
//                'username' => $result->admin_username,
//                'admin_email' => $result->admin_email,
//                'admin_contact' => $result->admin_contact,
//                'admin_alt_contacts' => $result->admin_alt_contacts,
//                'total_pr_balance' => $result->total_pr_balance,
//                'total_tr_balance' => $result->total_tr_balance,
//                'atype' => $result->atype
//            );
//            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.231') {
//                $this->session->set_userdata('admin_logged_in', $session_array);
//                $this->session->set_flashdata('message', 'You have login successfully!');
//                $this->session->set_flashdata('message_type', 'success');
//                redirect('admin/spam_transactional', 'refresh');
//            } else {
//                $this->session->set_userdata('admin_logged', $session_array);
//                $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
//                // Send Verification Code
//                $response = $this->admin_data_model->sendVerificationCode($random_number);
//                if ($response) {
//                    $this->session->set_userdata('random_number', $random_number);
//                    $this->session->unset_userdata('captcha_word');
//                    $this->session->unset_userdata('login');
//                    redirect('admin/verification', 'refresh');
//                } else {
//                    $this->session->unset_userdata('admin_logged_in');
//                     redirect('vision/index/'.$string, 'refresh');
//                }
//            }
//        } else {
//           
//            $this->session->set_flashdata('message', 'Error: Username & password are not matched!');
//            $this->session->set_flashdata('message_type', 'alert-danger');
//            redirect('vision/index/'.$string, 'refresh');
//        }
//    }

    public function auth_portal_beaing_log_data($web_string_admin) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $activity_approve = $this->utility_model->ilegalAdminActivity($web_string_admin);
        $string = "dghjkfdfg%f5g4=f5524&invelid_login&1h&f12bhh21hjmh5j4hj52h4df#32hk1bn&invelid_login&53hg5h52h2jg3211yhjh2fg1yj54ggh42df1b465hf463214f5g2fh46";
        if ($activity_approve) {
            $result = $this->admin_data_model->validateAdmin();
        } else {
            $this->session->set_flashdata('message', 'Error: Username & password are not matched!');
            $this->session->set_flashdata('message_type', 'alert-danger');
            redirect('vision/index/' . $string, 'refresh');
        }


        if ($result == '100') {
            $this->session->set_flashdata('message', 'Error: You entered wrong captcha!');
            $this->session->set_flashdata('message_type', 'alert-danger');
            redirect('vision/index/' . $string, 'refresh');
        } elseif ($result == '101') {
            $this->session->set_flashdata('message', 'Error: You entered wrong contact number!');
            $this->session->set_flashdata('message_type', 'alert-danger');
            redirect('vision/index/' . $string, 'refresh');
        } elseif ($result) {
            $session_array = array(
                'admin_id' => $result->admin_id,
                'username' => $result->admin_username,
                'admin_email' => $result->admin_email,
                'admin_contact' => $result->admin_contact,
                'admin_alt_contacts' => $result->admin_alt_contacts,
                'total_pr_balance' => $result->total_pr_balance,
                'total_tr_balance' => $result->total_tr_balance,
                'atype' => $result->atype
            );
            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.231') {
                $this->session->set_userdata('admin_logged_in', $session_array);
                $this->session->set_flashdata('message', 'You have login successfully!');
                $this->session->set_flashdata('message_type', 'success');
                redirect('admin/spam_transactional', 'refresh');
            } else {
                $this->session->set_userdata('admin_logged', $session_array);
                $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 4);
                // Send Verification Code
                $response = $this->admin_data_model->sendVerificationCode($random_number);
                if ($response) {
                    $this->session->set_userdata('random_number', $random_number);
                    $this->session->unset_userdata('captcha_word');
                    $this->session->unset_userdata('login');
                    redirect('admin/verification', 'refresh');
                } else {
                    $this->session->unset_userdata('admin_logged_in');
                    redirect('vision/index/' . $string, 'refresh');
                }
            }
        } else {

            $this->session->set_flashdata('message', 'Error: Username & password are not matched!');
            $this->session->set_flashdata('message_type', 'alert-danger');
            redirect('vision/index/' . $string, 'refresh');
        }
    }

    // Verify Admin
    public function verification() {
        if ($this->session->userdata('admin_logged')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->view('admin/verification');
        } else {
            redirect('admin/index', 'refresh');
        }
    }

    // Verify Admin 
    public function verify_code() {
        if ($this->session->userdata('admin_logged')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $result = $this->admin_data_model->verifyCode();
            if ($result) {
                $session_data = $this->session->userdata('admin_logged');
                $session_array = array(
                    'admin_id' => $session_data['admin_id'],
                    'username' => $session_data['username'],
                    'admin_email' => $session_data['admin_email'],
                    'admin_contact' => $session_data['admin_contact'],
                    'admin_alt_contacts' => $session_data['admin_alt_contacts'],
                    'total_pr_balance' => $session_data['total_pr_balance'],
                    'total_tr_balance' => $session_data['total_tr_balance'],
                    'atype' => $session_data['atype']
                );
                $this->session->set_userdata('admin_logged_in', $session_array);
                $this->session->unset_userdata('admin_logged');
                $this->session->unset_userdata('random_number');
                $this->session->unset_userdata('contact_number');
                $this->session->set_flashdata('message', 'You have login successfully!');
                $this->session->set_flashdata('message_type', 'success');
                redirect('admin/spam_transactional', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Error: You entered wrong verification code!');
                $this->session->set_flashdata('message_type', 'alert-danger');
                redirect('admin/verification', 'refresh');
            }
        } else {
            redirect('admin/index', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // HTTP API For Admin Purpose only Like Verify Location, Forgot Password, Admin Verification Code etc.
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send SMS Through HTTP API (Get & Post)
    public function send_http() {
        $contact = $this->input->post('contact');
        $sender = $this->input->post('sender');
        $message = $this->input->post('messages');
        $purpose = $this->input->post('purpose');
        //Post Array
        $array_users = array('contact' => $contact, 'sender' => $sender, 'messages' => $message, 'purpose' => $purpose);
        // Send Message
        echo $result_api = $this->admin_data_model->sendHttpApi($array_users);
        die;
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Transactional SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Transactional SMS
    public function spam_transactional() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
            $data['page'] = "spam_transactional";
            $this->load->view('admin/header', $data, 'refresh');
            $this->load->view('admin/spam-transactional');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Auto Refresh TR Spam
    public function show_tr_spam() {
        if ($this->session->userdata('admin_logged_in')) {
            $data['type'] = "Transactional";
            $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
            $this->load->view('admin/show-spam', $data);
        }
    }

    // Show All User Messages
    public function show_user_sms($user_id = 0, $type = null, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($type == 'tr') {
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $data['total_sms'] = $total;
                $this->load->view('admin/show-tr-sms', $data);
            } elseif ($type == 'pr') {
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['promotional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['spam_promotional'] = $this->admin_data_model->getPromotionalSMS($user_id);
                $data['total_sms'] = $total;
                $this->load->view('admin/show-pr-sms', $data);
            }
        }
    }

    // Show All User Messages
    public function show_all_requests($user_id = 0, $type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($type == 'tr') {
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $this->load->view('admin/show-all-requests', $data);
            } elseif ($type == 'pr') {
                $data['promotional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['spam_promotional'] = $this->admin_data_model->getPromotionalSMS($user_id);
                $this->load->view('admin/show-all-requests', $data);
            }
        }
    }

    // Approve Sender Id
    public function approve_sender($user_id = 0, $sender_id = null, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $result = $this->admin_data_model->approveSenderId($user_id, $sender_id);
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($result) {
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $data['total_sms'] = $total;
                $data['msg_type'] = '1';
                $data['msg_data'] = $sender_id . ' approved successfully!';
                $this->load->view('admin/show-tr-sms', $data);
            } else {
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $data['total_sms'] = $total;
                $data['msg_type'] = '0';
                $data['msg_data'] = $sender_id . ' approval failed! Please try again later!';
                $this->load->view('admin/show-tr-sms', $data);
            }
        }
    }

    // Update User DB (Unique Contact Numbers)
    public function update_user_db($user_id = 0, $database_limit = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $result = $this->admin_data_model->updateUserDB($user_id, $database_limit);
            if ($result) {
                echo 1;
                die;
            } else {
                echo 0;
                die;
            }
        }
    }

    // Resend SPAM Transactional SMS
    public function send_tr_message($position = null, $user_id = 0, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $response = $this->admin_data_model->sendTransactionalSMS($admin_id);
            if ($response == 200) {
                $total = $total - 1;
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message sent successfully!';
            } elseif ($response == 300) {
                $total = $total - 1;
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message rejected successfully!';
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message already processed! Please refresh page';
            } elseif ($response == 101) {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Please check user database limit! Then process!';
            }
            $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            if ($position == 'outer') {
                $data['type'] = "Transactional";
                $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($position == 'inner') {
                if ($total) {
                    echo "<input type='hidden' id='condition' value='0' />";
                    $data['total_sms'] = $total;
                    $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                    $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                    $this->load->view('admin/show-tr-sms', $data);
                } else {
                    echo "<input type='hidden' id='condition' value='1' />";
                    $data['type'] = "Transactional";
                    $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
                    $this->load->view('admin/show-spam', $data);
                }
            }
        }
    }

    // Process All SPAM Transactional SMS
    public function send_all_tr_message($user_id = 0, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $response = $this->admin_data_model->sendAllTransactionalSMS($admin_id);
            if ($response == 200) {
                echo "<input type='hidden' id='condition' value='1' />";
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message sent successfully!';
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['type'] = "Transactional";
                $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($response == 300) {
                echo "<input type='hidden' id='condition' value='1' />";
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message rejected successfully!';
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['type'] = "Transactional";
                $data['spam_transactional'] = $this->admin_data_model->spamTransactional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($response == 100) {
                echo "<input type='hidden' id='condition' value='0' />";
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message sending failed!';
                $data['total_sms'] = $total;
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $this->load->view('admin/show-tr-sms', $data);
            } elseif ($response == 101) {
                echo "<input type='hidden' id='condition' value='0' />";
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Please check user database limit! Then process!';
                $data['total_sms'] = $total;
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $this->load->view('admin/show-tr-sms', $data);
            } elseif ($response == 102) {
                echo "<input type='hidden' id='condition' value='0' />";
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message already processed!';
                $data['total_sms'] = $total;
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['spam_transactional'] = $this->admin_data_model->getTransactionalSMS($user_id);
                $this->load->view('admin/show-tr-sms', $data);
            }
        }
    }

    // Get All Multiple Messages
    public function get_all_messages() {
        if ($this->session->userdata('admin_logged_in')) {
            $campaign_id = $this->input->post('campaign_id');
            $data['all_messages'] = $this->admin_data_model->getAllMessages($campaign_id);
            $this->load->view('admin/show-all-messages', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SPAM Promotional SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    public function spam_promotional1() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['spam_promotional'] = $this->admin_data_model->spamPromotional();
            $data['page'] = "spam_promotional";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/spam-promotional');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Auto Refresh PR Spam
    public function show_pr_spam() {
        if ($this->session->userdata('admin_logged_in')) {
            $data['type'] = "Promotional";
            $data['promotional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['spam_promotional'] = $this->admin_data_model->spamPromotional1();
            $this->load->view('admin/show-spam', $data);
        }
    }

    // SPAM Promotional SMS
    public function spam_promotional() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['promotional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['spam_promotional'] = $this->admin_data_model->spamPromotional1();
            $data['page'] = "spam_promotional";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/spam-promotional');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Resend SPAM Promotional SMS
    public function send_pr_message($position = null, $user_id = 0, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $response = $this->admin_data_model->sendPromotionalSMS($admin_id);
            if ($response == '200') {
                $total = $total - 1;
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message sent successfully!';
            } elseif ($response == '300') {
                $total = $total - 1;
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message rejected successfully!';
            } elseif ($response == '100') {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message already processed! Please refresh page';
            }
            $data['promotional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            if ($position == 'outer') {
                $data['type'] = "Promotional";
                $data['spam_promotional'] = $this->admin_data_model->spamPromotional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($position == 'inner') {
                if ($total) {
                    echo "<input type='hidden' id='condition' value='0' />";
                    $data['total_sms'] = $total;
                    $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                    $data['spam_promotional'] = $this->admin_data_model->getPromotionalSMS($user_id);
                    $this->load->view('admin/show-pr-sms', $data);
                } else {
                    echo "<input type='hidden' id='condition' value='1' />";
                    $data['type'] = "Promotional";
                    $data['spam_promotional'] = $this->admin_data_model->spamPromotional();
                    $this->load->view('admin/show-spam', $data);
                }
            }
        }
    }

    // Process All SPAM Promotional SMS
    public function send_all_pr_message($user_id = 0, $total = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $response = $this->admin_data_model->sendAllPromotionalSMS($admin_id);
            if ($response == 200) {
                echo "<input type='hidden' id='condition' value='1' />";
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message sent successfully!';
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['type'] = "Promotional";
                $data['spam_promotional'] = $this->admin_data_model->spamPromotional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($response == 300) {
                echo "<input type='hidden' id='condition' value='1' />";
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Message rejected successfully!';
                $data['transactional_smsc'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['type'] = "Promotional";
                $data['spam_promotional'] = $this->admin_data_model->spamPromotional();
                $this->load->view('admin/show-spam', $data);
            } elseif ($response == 100) {
                echo "<input type='hidden' id='condition' value='0' />";
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message sending failed!';
                $data['total_sms'] = $total;
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['spam_promotional'] = $this->admin_data_model->getPromotionalSMS($user_id);
                $this->load->view('admin/show-pr-sms', $data);
            } elseif ($response == 102) {
                echo "<input type='hidden' id='condition' value='0' />";
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Message already processed!';
                $data['total_sms'] = $total;
                $data['user'] = $this->admin_data_model->getUserInfo($user_id);
                $data['spam_promotional'] = $this->admin_data_model->getPromotionalSMS($user_id);
                $this->load->view('admin/show-pr-sms', $data);
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Resend Transactional & Promotional SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Resend SMS
    public function resend_sms($user_id = 0) {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $this->load->model('user_data_model', '', TRUE);
            $this->load->model('voice_sms_model', '', TRUE);
            $voice_call = $this->input->post('resend_route');
            if ($voice_call == "A" || $voice_call == "B") {
                $response = $this->voice_sms_model->reSendVoiceCallAdminTest($user_id);
            } else {
                $response = $this->send_bulk_sms_model->reSendMessageAdmin($user_id, 'admin');
            }


            if ($response == 1) {
                header('Content-Type: application/json');
                $session_array = array(
                    'type' => 1,
                    'message' => ' Message resent successfully!',
                    'message_type' => "success"
                );
                echo json_encode($session_array);
                die;
            } else {
                if ($response == '100') {
                    $session_array = array(
                        'type' => 0,
                        'message' => ' Message resending failed! Please try again later!',
                        'message_type' => "error"
                    );
                } elseif ($response == '101') {
                    $session_array = array(
                        'type' => 0,
                        'message' => ' Not Have Sufficient Balance to Send SMS!',
                        'message_type' => "error"
                    );
                } elseif ($response == '102') {
                    $session_array = array(
                        'type' => 0,
                        'message' => ' Route is not available!',
                        'message_type' => "error"
                    );
                } elseif ($response == '103') {
                    $session_array = array(
                        'type' => 0,
                        'message' => ' No numbers are available for this request!',
                        'message_type' => "error"
                    );
                } else {
                    $session_array = array(
                        'type' => 0,
                        'message' => ' Message resending failed! Please try again later!',
                        'message_type' => "error"
                    );
                }
                echo json_encode($session_array);
                die;
            }
        }
    }

    public function resend_sms_test() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->load->model('voice_sms_model');
        $response = $this->voice_sms_model->reSendVoiceCallAdminTest();
        //  var_dump($response);die;
        if ($response == 1) {
            header('Content-Type: application/json');
            $session_array = array(
                'type' => 1,
                'message' => ' Message resent successfully!',
                'message_type' => "success"
            );
            echo json_encode($session_array);
            die;
        } else {
            if ($response == '100') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Message resending failed! Please try again later!',
                    'message_type' => "error"
                );
            } elseif ($response == '101') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Not Have Sufficient Balance to Send SMS!',
                    'message_type' => "error"
                );
            } elseif ($response == '102') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Route is not available!',
                    'message_type' => "error"
                );
            } elseif ($response == '103') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' No numbers are available for this request!',
                    'message_type' => "error"
                );
            } else {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Message resending failed! Please try again later!',
                    'message_type' => "error"
                );
            }
            echo json_encode($session_array);
            die;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Groups Or SMPP Accounts
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Groups
    public function user_groups() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_array = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('7', $permissions_array)) {
                $table["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
                $table["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
                // Table View
                $table['subtab'] = 1;
                $data['table'] = $this->load->view('admin/show-ugroups-tab', $table, true);
                // Main View
                $data['subtab'] = 1;
                $data['page'] = "user_groups";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/user-groups');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save User Group
    public function save_user_group() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $type = $this->input->post('purpose');
            $response = $this->admin_data_model->saveUserGroup();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' User Group inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' User Group insertion failed! Please try again!';
            }
            if ($type == 'Promotional') {
                $data['subtab'] = 1;
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($type == 'Transactional') {
                $data['subtab'] = 2;
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Set Default User Group
    public function set_default($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->setDefaultUG();
            $data['msg_type'] = '1';
            $data['msg_data'] = ' User Group set as a default!';
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Set Default User Group
    public function set_backup_route($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->setBackupUG();
            $data['msg_type'] = '1';
            $data['msg_data'] = ' User Group set as a backup route!';
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Update User Group
    public function update_user_group($user_group_id = 0, $type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_group'] = $this->admin_data_model->getUserGroup($user_group_id);
            if ($type == 'PR') {
                $data["backup_pr_ugroups"] = $this->admin_data_model->getUserGroups('Promotional', 1);
            } elseif ($type == 'TR') {
                $data["backup_tr_ugroups"] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $data['subtab'] = 3;
            $data['user_group_id'] = $user_group_id;
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Delete User Group
    public function delete_user_group($subtab = 0, $user_group_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteUserGroup($user_group_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' User Group deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' User Group deletion failed! Please try again!';
            }
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Enable/Disable User Group
    public function change_ugroup_status($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->changeUGroupStatus();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Status changing failed! Please try again!';
            }
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Enable/Disable Resend Option
    public function change_resend_status($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->changeResendStatus();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Resend Status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Resend Status changing failed! Please try again!';
            }
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
            }
            $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Save XML Route Setting
    public function xml_route_setting() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveXMLRouteSetting();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' XML Route Setting updated successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' XML Route Setting updation failed! Please try again!';
            }
            $result_settings = $this->admin_data_model->getDefaultSettings();
            if ($result_settings) {
                $data['setting_id'] = $result_settings->sms_limit;
                $data['xml_route_authkey'] = $result_settings->xml_route_authkey;
                $data['xml_route_url'] = $result_settings->xml_route_url;
            }
            $data['subtab'] = 4;
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Save Backup Route Setting
    public function backup_route_setting() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveBackupRouteSetting();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Backup Route Setting updated successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Backup Route Setting updation failed! Please try again!';
            }
            $result_settings = $this->admin_data_model->getDefaultSettings();
            if ($result_settings) {
                $data['setting_id'] = $result_settings->sms_limit;
                $data['backup_time_duration'] = $result_settings->backup_time_duration;
                $data['backup_limit'] = $result_settings->backup_limit;
            }
            $data['subtab'] = 5;
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Management
    //------------------------------------------------------------------------------------------------------------------------------------------//
//save verify pin

    public function verify_users_pin() {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->view('admin/header');
            $this->load->view('admin/verify-users-pin');
            $this->load->view('admin/footer');
        }
    }

    public function save_users_pin() {

        if ($this->session->userdata('admin_logged_in')) {
            $session_userdata = $this->session->userdata('admin_logged_in');
            $admin_id = $session_userdata['admin_id'];
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->getUserVerifyPin();
            if ($response) {
                $session_user_pin = array(
                    'admin_id' => $admin_id,
                );
                $this->session->set_userdata('pin_logged_in', $session_user_pin);
                $this->session->set_flashdata('message', 'You have entered Right Pin!');
                $this->session->set_flashdata('message_type', 'success');
                redirect('admin/users', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Error: You entered wrong verification Pin!');
                $this->session->set_flashdata('message_type', 'alert-danger');
                redirect('admin/verify_users_pin', 'refresh');
            }
        }
    }

// Users
    public function users() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_array = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if ($this->session->userdata('pin_logged_in')) {
                if (isset($permissions_array) && $permissions_array && in_array('8', $permissions_array)) {
                    $table['total_resellers'] = $this->admin_data_model->getClients(1, 'Reseller', 0, null);
                    $table['total_users'] = $this->admin_data_model->getClients(1, 'User', 0, null);
                    $table['demo_users'] = $this->admin_data_model->getClients(0, null, 1, 1);
                    $table['active_users'] = $this->admin_data_model->getClients(0, null, 1, 0);
                    // Pagination
                    $page = 1;
                    $records_per_page = 100;
                    $index = ($page * $records_per_page) - 100;
                    $total_logs = $this->admin_data_model->countUsers();
                    $logs = $this->admin_data_model->getUsers($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $table['users'] = $return_array['return_array'];
                    $paging['total_pages'] = $return_array['total_pages'];
                    $paging['records_data'] = $return_array['records_data'];
                    $paging['page_no'] = $page;
                    $paging['subtab'] = 1;
                    $paging['function'] = "Users";
                    // Paging View
                    $table['paging'] = $this->load->view('admin/paging', $paging, true);
                    // Sub Tab View
                    $table['subtab'] = 1;
                    $data['table'] = $this->load->view('admin/get-users-tab', $table, true);


                    // Main View
                    $data['subtab'] = 1;
                    $data['page'] = "users";
                    $this->load->view('admin/header', $data);
                    $this->load->view('admin/users');
                    $this->load->view('admin/footer');
                } else {
                    redirect('admin/unauthorized_access', 'refresh');
                }
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    //save retry users

    public function save_retry_user($type) {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['all_user_groups'] = $this->admin_data_model->getAllRoute();
            $data['retry_user'] = $this->admin_data_model->getUserForRetry();
            $response = $this->admin_data_model->saveRetryUser($type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = 'User Save successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'failed! Please try again!';
            }
            $data['subtab'] = 6;
            $this->load->view('admin/get-users-tab', $data);
        }
    }

    //make me special reseller
    public function make_me_special_reseller($id, $status) {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->makeMeSpecialReseller($id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = 'Reseller status updated successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'failed! Please try again!';
            }
            $data['subtab'] = 7;
            $data['users_admin'] = $this->admin_data_model->getUserUnderAdmin();
            $this->load->view('admin/get-users-tab', $data);
        }
    }

    //search special reseller
    public function search_special_reseller($reseller = NULL) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $reseller = $myArray[0];
            $data['reseller'] = $reseller;
            $data['resellers'] = $this->admin_data_model->searchSpecialReseller($reseller);

            // var_dump($data['Reseller_users']);die;
            $this->load->view('admin/show-special-reseller', $data);
        }
    }

    // Search sender id by username
    public function search_senderid_by_username() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $username = $myArray[0];
            $data['username'] = $username;
            $data['subtab'] = 2;
            $data['user_sender_ids'] = $this->admin_data_model->searchSenderidByUsername($username);

            $this->load->view('admin/show-pending-senderid', $data);
        }
    }

    //save retry route
    public function save_retry_route($route) {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['all_user_groups'] = $this->admin_data_model->getAllRoute();
            $data['retry_user'] = $this->admin_data_model->getUserForRetry();
            $response = $this->admin_data_model->saveRetryRoute($route);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Save successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'failed! Please try again!';
            }
            $data['subtab'] = 6;
            $this->load->view('admin/get-users-tab', $data);
        }
    }

    // Get Users Tabs
    public function get_users_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $atype = $session_data['atype'];
            $data['subtab'] = $subtab;
            // Users
            if ($subtab == 1) {
                $data['total_resellers'] = $this->admin_data_model->getClients(1, 'Reseller', 0, null);
                $data['total_users'] = $this->admin_data_model->getClients(1, 'User', 0, null);
                $data['demo_users'] = $this->admin_data_model->getClients(0, null, 1, 1);
                $data['active_users'] = $this->admin_data_model->getClients(0, null, 1, 0);
                // Pagination
                $page = 1;
                $records_per_page = 10;
                $index = ($page * $records_per_page) - 10;
                $total_logs = $this->admin_data_model->countUsers();
                $logs = $this->admin_data_model->getUsers($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['users'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['function'] = "Users";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            // Add User
            if ($subtab == 3) {
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // Notify User
            if ($subtab == 4 || $subtab == 5) {
                $data['result_resellers'] = $this->admin_data_model->getUsersNotify('Reseller', $admin_id);
                $data['result_users'] = $this->admin_data_model->getUsersNotify('User', $admin_id);
                $data['previous'] = $this->admin_data_model->getPreviousNotify($admin_id, $atype, $subtab);
            }
            if ($subtab == 6) {
                $data['all_user_groups'] = $this->admin_data_model->getAllRoute();
                $data['retry_user'] = $this->admin_data_model->getUserForRetry();
            }
            if ($subtab == 7) {
                $data['users_admin'] = $this->admin_data_model->getUserUnderAdmin();
            }
            if ($subtab == 8) {
                $data['admin'] = $this->admin_data_model->getAdmin();
            }
            if ($subtab == 9) {
                $data['reseller_users'] = $data['result_resellers'] = $this->admin_data_model->getUsersData($admin_id);
            }



            $this->load->view('admin/get-users-tab', $data);
        }
    }

    // Change User's SMPP Routing
    public function change_smpp_routing($type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);

            $response = $this->admin_data_model->changeSMPPRouting($type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = 'Routing updated successfully for all users!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Routing updation failed! Please try again!';
            }
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['subtab'] = 3;
            $this->load->view('admin/get-users-tab', $data);
        }
    }

    // Paging Users
    public function paging_users($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countUsers();
            $logs = $this->admin_data_model->getUsers($index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['users'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Users";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $data['subtab'] = $subtab;
            $data['type'] = 'user';
            $this->load->view('admin/show-users', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Username
    public function get_username() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->getUsername();
            if ($response)
                echo "<span style=color:red;font-weight: 700;>Username is not available!</span>";
            else
                echo "<span style=color:green;font-weight: 700;>Username is available!</span>";
        }
    }

    // Save Update User
    public function save_update_user($user_id = 0, $username = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveUpdateUser($user_id);
            $data['user'] = $this->admin_data_model->getUser($username);
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['user_tab'] = 1;
            $this->load->view('admin/show-user-tab', $data);
        }
    }

    // Delete User
    public function delete_user($user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteUser($user_id);
            if ($response)
                echo "User Deleted Successfully!";
            else
                echo "User Deletion Failed!";
        }
    }

    // Enable/Disable User
    public function enable_disable_user($user_id = 0, $username = null, $status = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $response = $this->admin_data_model->enDisUser($user_id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                if ($status == 1)
                    $data['msg_data'] = 'User enabled successfully!';
                elseif ($status == 0)
                    $data['msg_data'] = 'User disabled successfully!';
            }
            $data['user'] = $this->admin_data_model->getUser($username);
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['user_tab'] = 1;
            $this->load->view('admin/show-user-tab', $data);
        }
    }

    // Block Reseller
    public function block_reseller($user_id = 0, $username = null, $status = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $response = $this->admin_data_model->blockReseller($user_id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                if ($status == 0)
                    $data['msg_data'] = 'Reseller blocked successfully!';
                elseif ($status == 1)
                    $data['msg_data'] = 'Reseller un-block successfully!';
            }
            $data['user'] = $this->admin_data_model->getUser($username);
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['user_tab'] = 1;
            $this->load->view('admin/show-user-tab', $data);
        }
    }

    // Search User
    public function search_user($username) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user'] = $this->admin_data_model->getUser($username);
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['user_tab'] = 1;
            $this->load->view('admin/get-user', $data);
        }
    }

    // Get User Tabs
    public function get_user_tab($user_id = 0, $tab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $data['user_tab'] = $tab;
            // SMPP Routing
            if ($tab == 1) {
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Funds
            if ($tab == 3) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $logs = $this->admin_data_model->getUserSMSLogs($user_id);
                $return_array = $this->paging($logs, $index, $records_per_page);
                /*
                  $total_logs = $this->admin_data_model->countUserSMSLogs($user_id);
                  $logs = $this->admin_data_model->getUserSMSLogs($user_id, $index, $records_per_page);
                  $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                 */
                $table['txn_logs'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 3;
                $paging['section'] = 1;
                $paging['function'] = "User";
                $paging['admin_name'] = $this->admin_data_model->adminNameForLead();
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Main View
                $data['table'] = $this->load->view('admin/show-user-funds', $table, true);
            }
            // Short/Long Codes
            if ($tab == 7) {
                $data['short_numbers'] = $this->admin_data_model->getSLNumbers('short', 1);
                $data['long_numbers'] = $this->admin_data_model->getSLNumbers('long', 1);
            }
            if ($tab == 8) {
                $data['voice_tr_route'] = $this->admin_data_model->getVoiceRoute('tr', 1);
                $data['voice_pr_route'] = $this->admin_data_model->getVoiceRoute('pr', 1);
            }
            //special reseller setting
            if ($tab == 9) {

                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $logs = $this->admin_data_model->specialResellerlogs($user_id);
                $return_array = $this->paging($logs, $index, $records_per_page);

                $table['txn_logs'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 9;
                $paging['section'] = 1;
                $paging['function'] = "User";
                $paging['admin_name'] = $this->admin_data_model->adminNameForLead();
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Main View
                $data['table'] = $this->load->view('admin/special-reseller-funds', $table, true);
            }

            $data['user'] = $this->admin_data_model->getUserInfo($user_id);
            $this->load->view('admin/show-user-tab', $data);
        }
    }

    // Paging User
    public function paging_user($page = 0, $records_per_page = 0, $subtab = 0, $user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            /*
              $total_logs = $this->admin_data_model->countUserSMSLogs($user_id);
              $logs = $this->admin_data_model->getUserSMSLogs($user_id, $index, $records_per_page);
              $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
             */
            $logs = $this->admin_data_model->getUserSMSLogs($user_id);
            $return_array = $this->paging($logs, $index, $records_per_page);
            $data['txn_logs'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 3;
            $paging['section'] = 1;
            $paging['function'] = "User";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $this->load->view('admin/show-user-funds', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save User Info
    public function save_user_info($user_id = 0, $username = null, $tab = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $admin_discription = $this->input->post('admin_discription');

            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $admin_id = $session_data['admin_id'];
            $data['user_tab'] = $tab;
            // User Settings
            if ($tab == 1 && $subtab == 'routing') {
                $response = $this->admin_data_model->saveUserRouting($user_id, $admin_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Routing updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Routing updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Ratio
            if (($tab == 1 && $subtab == 'trratio') || ($tab == 1 && $subtab == 'prratio') || ($tab == 8 && $subtab == 'vtrratio') || ($tab == 8 && $subtab == 'vprratio')) {
                $response = $this->admin_data_model->saveUserRatio($user_id, $admin_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Ratio setting updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Ratio setting updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            //Admin  User Ratio Approve
            if (($tab == 1 && $subtab == 'atrratio') || ($tab == 1 && $subtab == 'aprratio')) {
                $response = $this->admin_data_model->saveAdminApproveRatio($user_id, $subtab);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Ratio setting updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Ratio setting updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }



            // User Expiry
            if (($tab == 1 && $subtab == 'set_expiry') || ($tab == 1 && $subtab == 'remove_expiry')) {
                $response = $this->admin_data_model->saveUserExpiry($user_id, $subtab);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Expiry updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Expiry updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            //save special ratio
            if (($tab == 1 && $subtab == 'special_pr_ratio') || ($tab == 1 && $subtab == 'special_tr_ratio')) {
                $response = $this->admin_data_model->saveSpecialRatio($user_id, $subtab);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Expiry updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Expiry updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }


            // User Black Keyword Setting
            if (($tab == 1 && $subtab == 'bkeyword')) {

                $response = $this->admin_data_model->checkBlackKeyword($user_id);

                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Account setting updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Account setting updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Account Type
            if (($tab == 1 && $subtab == 'account_type')) {
                $response = $this->admin_data_model->saveAccountType($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Account type updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Account type updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Verify Location
            if (($tab == 1 && $subtab == 'verify_location')) {
                $response = $this->admin_data_model->saveVerifyLocation($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Verify location updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Verify location updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Rules
            // User Manager Alert
            if (($tab == 1 && $subtab == 'manager_alert')) {
                $response = $this->admin_data_model->accountManagerAlert($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Account manager alert updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Account manager alert updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            // User Manager Alert
            if (($tab == 1 && $subtab == 'otp_user')) {
                $response = $this->admin_data_model->otpUserAlert($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'OPT check successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'OPT check updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }

            // User Verify Price Approval
            if (($tab == 1 && $subtab == 'pricing_approval')) {
                $response = $this->admin_data_model->pricingApproval($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Price Approval updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Price Approval updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }

            // User Verify Encription Approval
            if (($tab == 1 && $subtab == 'encription_approve')) {
                $response = $this->admin_data_model->encriptionApproval($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Encription Approval updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Encription Approval updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }

            // change reseller settings
            if (($tab == 1 && $subtab == 'open_reseller_setting')) {
                $response = $this->admin_data_model->openResellerSetting($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Reseller Settings updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Reseller Setting updation failed for this user!';
                }
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }



            // User Rules


            if ($tab == 2 && $subtab == 'rules') {
                $response = $this->admin_data_model->saveUserSettings($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Rules updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Rules updation failed for this user!';
                }
            }
            // User Funds
            if ($tab == 3 && $subtab == 'funds') {
                $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
                $temp_array = array(
                    'total_pr_balance' => $admin_sms['total_pr_balance'],
                    'total_tr_balance' => $admin_sms['total_tr_balance'],
                    'total_prtodnd_balance' => $admin_sms['total_prtodnd_balance'],
                    'total_stock_balance' => $admin_sms['total_stock_balance'],
                    'international_sms' => $admin_sms['international_sms'],
                    'total_lcode_balance' => $admin_sms['total_lcode_balance'],
                    'total_scode_balance' => $admin_sms['total_scode_balance'],
                    'total_vpr_balance' => $admin_sms['total_vpr_balance'],
                    'total_vtr_balance' => $admin_sms['total_vtr_balance'],
                    'total_mcall_balance' => $admin_sms['total_mcall_balance']
                );
                $response = $this->admin_data_model->saveSMSFunds($admin_id, $user_id, $temp_array);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Fund transfered successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Fund transferring failed for this user!';
                }

                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                /*
                  $total_logs = $this->admin_data_model->countUserSMSLogs($user_id);
                  $logs = $this->admin_data_model->getUserSMSLogs($user_id, $index, $records_per_page);
                  $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                 */
                $logs = $this->admin_data_model->getUserSMSLogs($user_id);
                $return_array = $this->paging($logs, $index, $records_per_page);
                $table['txn_logs'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 3;
                $paging['section'] = 1;
                $paging['function'] = "User";
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Main View
                $data['table'] = $this->load->view('admin/show-user-funds', $table, true);
            }

            if ($tab == 3 && $subtab == 'admin_comment') {

                $response = $this->admin_data_model->saveAdminComment($admin_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Comment Add successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Process failed for this user!';
                }


                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                /*
                  $total_logs = $this->admin_data_model->countUserSMSLogs($user_id);
                  $logs = $this->admin_data_model->getUserSMSLogs($user_id, $index, $records_per_page);
                  $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                 */
                $logs = $this->admin_data_model->getUserSMSLogs($user_id);
                $return_array = $this->paging($logs, $index, $records_per_page);
                $table['txn_logs'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 3;
                $paging['section'] = 1;
                $paging['function'] = "User";
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Main View
                $data['table'] = $this->load->view('admin/show-user-funds', $table, true);
            }

            // User Profile
            if ($tab == 4 && $subtab == 'profile') {
                $response = $this->admin_data_model->saveUpdateUser($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Profile updated successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Profile updation failed for this user!';
                }
            }
            // User Reset Password
            if ($tab == 5 && $subtab == 'reset') {
                $response = $this->admin_data_model->saveUserPassword($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Password reset successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Password reseting failed for this user!';
                }
            }
            // User Low Balance Alert
            if ($tab == 6 && $subtab == 'alert') {
                $response = $this->admin_data_model->saveLowBalAlert($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Low balance alert saved successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Low balance alert saving failed for this user!';
                }
            }
            // User Short/Long Codes
            if (($tab == 7 && $subtab == 'short') || ($tab == 7 && $subtab == 'long')) {
                $response = $this->admin_data_model->assignSLKeyword($user_id, $subtab);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Keyword assigned successfully!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Keyword insertion failed!';
                }
                $data['short_numbers'] = $this->admin_data_model->getSLNumbers('short', 1);
                $data['long_numbers'] = $this->admin_data_model->getSLNumbers('long', 1);
            }
            //update special balance
            if ($tab == 9 && $subtab == 'funds') {

                $response = $this->admin_data_model->updateSpecialBalance($admin_id, $user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Fund transfered successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Fund transferring failed for this user!';
                }
            }
            if ($tab == 8 && $subtab == 'addvoiceid') {
                $response = $this->admin_data_model->saveVoiceId($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Voice Id Save successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Voice Id updation failed for this user!';
                }
            }

            if ($tab == 8 && $subtab == 'save_voice_route') {
                $response = $this->admin_data_model->saveVoiceRoute($user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = 'Voice Route Save successfully for this user!';
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = 'Voice Route updation failed for this user!';
                }

                $data['voice_tr_route'] = $this->admin_data_model->getVoiceRoute('tr', 1);
                $data['voice_pr_route'] = $this->admin_data_model->getVoiceRoute('pr', 1);
            }

            $data['user'] = $this->admin_data_model->getUserInfo($user_id);
            $this->load->view('admin/show-user-tab', $data);
        }
    }

    // Check DNS Setting
    public function check_dns_settings() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            echo $result = $this->admin_data_model->checkDNSSettings();
            die;
        }
    }

    // Check Username
    public function check_username($type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            echo $result = $this->admin_data_model->checkUsername($type);
            die;
        }
    }

    // Save User
    public function save_user() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $admin_email = $session_data['admin_email'];
            $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
            $total_pr_balance = $admin_sms['total_pr_balance'];
            $total_tr_balance = $admin_sms['total_tr_balance'];
            $response = $this->admin_data_model->saveUser($admin_id, $admin_email, $total_pr_balance, $total_tr_balance);
            if ($response == 200) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Congratulations! User created successfully!';
                $data['subtab'] = 1;
                $data['total_resellers'] = $this->admin_data_model->getClients(1, 'Reseller', 0, null);
                $data['total_users'] = $this->admin_data_model->getClients(1, 'User', 0, null);
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countUsers();
                $logs = $this->admin_data_model->getUsers($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                //$users = $this->admin_data_model->getUsers();
                //$return_array = $this->paging($users, $index, $records_per_page);
                $data['users'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "Users";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
                $this->load->view('admin/get-users-tab', $data);
            } elseif ($response == 100 || $response == 101) {
                if ($response == 100) {
                    $data['msg_data'] = ' User creation failed! Please try again!';
                }
                if ($response == 101) {
                    $data['msg_data'] = ' Username not available! Please try another!';
                }
                $data['subtab'] = 2;
                $data['msg_type'] = '0';
                $this->load->view('admin/get-users-tab', $data);
            } else {
                $data['subtab'] = 2;
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
                $this->load->view('admin/get-users-tab', $data);
            }
        }
    }

    // Notify Users By SMS/Email
    public function send_notify_users($type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $atype = $session_data['atype'];
            $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
            $response = 0;
            $subtab = 0;
            if ($type == 'sms') {
                $subtab = 4;
                $data['subtab'] = 4;
                $total_pr_balance = $admin_sms['total_pr_balance'];
                $total_tr_balance = $admin_sms['total_tr_balance'];
                $response = $this->admin_data_model->notifyUsersBySMS($admin_id, $total_pr_balance, $total_tr_balance);
            } elseif ($type == 'email') {
                $subtab = 5;
                $data['subtab'] = 5;
                $company_name = $admin_sms['admin_company'];
                $response = $this->admin_data_model->notifyUsersByEmail($admin_id, $company_name);
            }
            if ($response) {
                if ($response == 1) {
                    $data['msg_data'] = ' Notification sent successfully!';
                    $data['msg_type'] = '1';
                } elseif ($response == 101 || $response == 102 || $response == 103) {
                    if ($response == 101) {
                        $data['msg_data'] = ' Default route not available this time!';
                    } elseif ($response == 102) {
                        $data['msg_data'] = ' You dont have sufficient balance!';
                    } elseif ($response == 103) {
                        $data['msg_data'] = ' Something wrong! Please try again later!';
                    }
                    $data['msg_type'] = '0';
                }
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
            }
            $data['result_resellers'] = $this->admin_data_model->getUsersNotify('Reseller', $admin_id);
            $data['result_users'] = $this->admin_data_model->getUsersNotify('User', $admin_id);
            $data['previous'] = $this->admin_data_model->getPreviousNotify($admin_id, $atype, $subtab);
            $this->load->view('admin/get-users-tab', $data);
        }
    }

    // Search Users
    public function search_users($username = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $username = $myArray[0];
            $data['username'] = $username;
            $data['search_users'] = $this->admin_data_model->searchUsers($username);
            $data['Reseller_users'] = $this->admin_data_model->searchResellerUsers($username);
            // var_dump($data['Reseller_users']);die;
            $this->load->view('admin/search-users', $data);
        }
    }

    // Search User
    public function get_user($username = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $table['admin_id'] = $session_data['admin_id'];
            $table['username'] = $username;
            $table['user'] = $this->admin_data_model->getUser($username);

            $table['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $table['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            //$data['user'] = $this->admin_data_model->getUserInfo($user_id);
            $table['user_tab'] = 1;
            // Tab View
            $data['user_info'] = $this->load->view('admin/show-user-tab', $table, true);
            $data['user_tab'] = 1;
            $this->load->view('admin/search-user', $data);
        }
    }

    // Add User
    public function add_user1() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->helper(array('form'));
            $data['setting'] = $this->admin_data_model->getSetting();
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['page'] = "users";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/add-user');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Login As & Back To Parent Account
    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Login As User
    public function login_as($user = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin = $session_data['admin_id'];
            $this->load->model('login_model', '', TRUE);
            $result_validate = $this->login_model->loginAsAdminUser($user);
            if ($result_validate) {
                $session_array = array(
                    'user_id' => $result_validate->user_id,
                    'most_parent_id' => $result_validate->most_parent_id,
                    'username' => $result_validate->username,
                    'utype' => $result_validate->utype,
                    'reseller_user' => $admin,
                    'login_from' => 'admin'
                );
                $this->session->set_userdata('logged_in', $session_array);
                redirect('user/index', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Back To Account
    public function back_to_account($admin = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $result = $this->admin_data_model->loginAsAdmin($admin);
            if ($result) {
                $session_array = array(
                    'admin_id' => $result->admin_id,
                    'username' => $result->admin_username,
                    'total_pr_balance' => $result->total_pr_balance,
                    'total_tr_balance' => $result->total_tr_balance,
                    'atype' => $result->atype
                );
                $this->session->set_userdata('admin_logged_in', $session_array);
                $this->session->unset_userdata('login');
                $this->session->unset_userdata('logged_in');
                redirect('admin/spam_transactional', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMPP Users
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMPP Users
    public function smpp_users() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_array = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('17', $permissions_array)) {
                $data['smpp_users'] = $this->admin_data_model->getSMPPUsers();
                $data['page'] = "smpp_users";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/smpp-users');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Search SMPP Users
    public function search_smpp_user($username = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $smpp_user = $this->admin_data_model->getSMPPUser($username);
            $data['smpp_user'] = $smpp_user;
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
            $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
            $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
            $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
            if ($pr_ugroup_id) {
                $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
            }
            if ($tr_ugroup_id) {
                $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
            }
            if ($open_ugroup_id) {
                $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
            }
            $data['user_tab'] = 1;
            $this->load->view('admin/get-smpp-user', $data);
        }
    }

    // Get SMPP Username
    public function get_smpp_username() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->getSMPPUsername();
            if ($response)
                echo "<span style=color:red;font-weight: 700;>Username is not available!</span>";
            else
                echo "<span style=color:green;font-weight: 700;>Username is available!</span>";
        }
    }

    // Save SMPP Update User
    public function save_update_smpp_user($smpp_user_id = 0, $smpp_username = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveUpdateSMPPUser($smpp_user_id);
            if ($response) {
                $smpp_user = $this->admin_data_model->getSMPPUser($smpp_username);
                $data['smpp_user'] = $smpp_user;
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                if ($pr_ugroup_id) {
                    $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                }
                if ($tr_ugroup_id) {
                    $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                }
                if ($open_ugroup_id) {
                    $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                }
            } else {
                $smpp_user = $this->admin_data_model->getSMPPUser($smpp_username);
                $data['smpp_user'] = $smpp_user;
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                if ($pr_ugroup_id) {
                    $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                } if ($tr_ugroup_id) {
                    $data[
                            'tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                }
                if ($open_ugroup_id) {
                    $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                }
            }
            $data['user_tab'] = 1;
            $this->load->view('admin/get-smpp-user', $data);
        }
    }

    // Delete SMPP User
    public function delete_smpp_user($smpp_user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteSMPPUser($smpp_user_id);
            if ($response)
                echo "SMPP User Deleted Successfully!";
            else
                echo "SMPP User Deletion Failed!";
        }
    }

    // Enable/Disable SMPP User
    public function enable_disable_smpp_user($smpp_user_id = 0, $smpp_username = null, $status = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->enDisSMPPUser($smpp_user_id, $status);
            if ($response) {
                $smpp_user = $this->admin_data_model->getSMPPUser($smpp_username);
                $data['smpp_user'] = $smpp_user;
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                if ($pr_ugroup_id) {
                    $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                }
                if ($tr_ugroup_id) {
                    $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                }
                if ($open_ugroup_id) {
                    $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                }
            } else {
                $smpp_user = $this->admin_data_model->getSMPPUser($smpp_username);
                $data['smpp_user'] = $smpp_user;
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                if ($pr_ugroup_id) {
                    $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                } if ($tr_ugroup_id) {
                    $data[
                            'tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                }
                if ($open_ugroup_id) {
                    $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                }
            }
            $data['user_tab'] = 1;
            $this->load->view('admin/get-smpp-user', $data);
        }
    }

    // Get SMPP User Tabs
    public function get_smpp_user_tab($user_id = 0, $tab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_tab'] = $tab;
            // Tab 1 
            if ($tab == 1) {
                $smpp_user = $this->admin_data_model->getSMPPUser($user_id);
                $data['smpp_user'] = $smpp_user;
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                if ($pr_ugroup_id) {
                    $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                }
                if ($tr_ugroup_id) {
                    $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                }
                if ($open_ugroup_id) {
                    $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                }
            }
            // Tab 2
            if ($tab == 2) {
                $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
            }
            // Tab 3
            if ($tab == 3) {
                $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
            }
            // Tab 4
            if ($tab == 4) {
                $data['smpp_user'] = $this
                        ->admin_data_model->getSMPPUser($user_id);
                $data['txn_logs'] = $this->admin_data_model->getSMPPUserSMSLogs($user_id);
            }
            // Tab 5
            if ($tab == 5) {
                $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
            }
            $this->load->view('admin/show-smpp-user-tab', $data);
        }
    }

    // Save User Info
    public function save_smpp_user_info($user_id = 0, $username = null, $tab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_tab'] = $tab;
            if ($tab == 1) {
                $response = $this->admin_data_model->saveSMPPRouting($user_id);
                if ($response) {
                    $smpp_user = $this->admin_data_model->getSMPPUser($user_id);
                    $data['smpp_user'] = $smpp_user;
                    $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                    $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                    $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                    $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                    $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                    $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                    if ($pr_ugroup_id) {
                        $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                    }
                    if ($tr_ugroup_id) {
                        $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                    }
                    if ($open_ugroup_id) {
                        $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                    }
                } else {
                    $smpp_user = $this->admin_data_model->getSMPPUser($user_id);
                    $data['smpp_user'] = $smpp_user;
                    $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                    $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                    $data['open_user_groups'] = $this->admin_data_model->getUserGroups(null, 1);
                    $pr_ugroup_id = $smpp_user['smpp_pr_ugroup_id'];
                    $tr_ugroup_id = $smpp_user['smpp_tr_ugroup_id'];
                    $open_ugroup_id = $smpp_user['smpp_open_ugroup_id'];
                    if ($pr_ugroup_id) {
                        $data['pr_ports'] = $this->admin_data_model->getVSMPPPorts($pr_ugroup_id);
                    }
                    if ($tr_ugroup_id) {
                        $data['tr_ports'] = $this->admin_data_model->getVSMPPPorts($tr_ugroup_id);
                    }
                    if ($open_ugroup_id) {
                        $data['open_ports'] = $this->admin_data_model->getVSMPPPorts($open_ugroup_id);
                    }
                }
            } elseif ($tab == 2) {
                $response = $this->admin_data_model->saveSMPPUserExpiry($user_id, '1');
                if ($response) {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                } else {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                }
            } elseif ($tab == 3) {
                $session_data = $this->session->userdata('admin_logged_in');
                $admin_id = $session_data['admin_id'];
                $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
                $total_pr_balance = $admin_sms['total_pr_balance'];
                $total_tr_balance = $admin_sms['total_tr_balance'];
                $response = $this->admin_data_model->saveSMPPSMSFunds($admin_id, $user_id, $total_pr_balance, $total_tr_balance);
                if ($response) {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                } else {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                }
            } elseif ($tab == 5) {
                $response = $this->admin_data_model->
                        saveSMPPUserPassword($user_id);
                if ($response) {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                } else {
                    $data['smpp_user'] = $this->admin_data_model->getSMPPUser($user_id);
                }
            }
            $this->load->view('admin/show-smpp-user-tab', $data);
        }
    }

    // Get SMPP Port
    public function get_smpp_ports($smpp_type = 0, $user_group_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $port_id = FALSE;
            $data['smpp_type'] = $smpp_type;
            $data['smpp_ports'] = $this->admin_data_model->getVSMPPPorts($user_group_id);
            $this->load->view('admin/show-smpp-ports', $data);
        }
    }

    // Add SMPP User
    public function add_smpp_user() {
        $this->load->helper(array('form'));
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['username'] = $session_data['username'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            $data['online_users'] = $this->utility_model->getAllOnlineUsers();
            $admin_id = $session_data['admin_id'];
            $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
            $data['total_pr_balance'] = $admin_sms['total_pr_balance'];

            $data['total_tr_balance'] = $admin_sms['total_tr_balance'];
            $data['page'] = "smpp_users";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/add-smpp-user');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save SMPP User
    public function save_smpp_user() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['username'] = $session_data['username'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            $data['online_users'] = $this->utility_model->getAllOnlineUsers();

            $admin_id = $session_data['admin_id'];
            $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
            $data['total_pr_balance'] = $admin_sms['total_pr_balance'];
            $data['total_tr_balance'] = $admin_sms['total_tr_balance'];

            $response = $this->admin_data_model->saveSMPPUser($admin_id);
            if ($response) {
                $data['smpp_users'] = $this->admin_data_model->getSMPPUsers();
                $data['success_msg'] = "SMPP User Inserted Successfully!";
                $data['page'] = "smpp_users";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/smpp-users');
                $this->load->view('admin/footer');
            } else {
                $data['smpp_users'] = $this->admin_data_model->getSMPPUsers();
                $data['error_msg'] = "SMPP User Insertion Failed!";
                $data['page'] = "smpp_users";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/smpp-users');
                $this->load->view('admin/footer');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMS Tracking
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMS Tracking
    public function delivery_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $table['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $table['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $result_logs = $this->admin_data_model->countDeliveryReports();
            $total_logs = 0;
            if ($result_logs) {
                $total_logs = $result_logs->total;
            }
            $logs = $this->admin_data_model->getDeliveryReports($index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $table['delivery_reports'] = $return_array['return_array'];
            $table['total_pages'] = $return_array['total_pages'];
            $table['records_data'] = $return_array['records_data'];
            $table['total_records'] = $total_logs;
            $table['page_no'] = $page;
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-sms-tracking', $table, true);
            // Main View
            $data['page'] = "delivery_reports";
            $data['subtab'] = 1;
            $this->load->view('admin/header', $data);
            $this->load->view('admin/delivery-reports');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Paging Delievery Reports
    public function paging_delivery_reports($page = 0, $records_per_page = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $index = ($page * $records_per_page) - 100;
            $result_logs = $this->admin_data_model->countDeliveryReports();
            $total_logs = 0;
            if ($result_logs) {
                $total_logs = $result_logs->total;
            }
            $logs = $this->admin_data_model->getDeliveryReports($index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['total_records'] = $total_logs;
            $data['delivery_reports'] = $return_array['return_array'];
            $data['total_pages'] = $return_array['total_pages'];
            $data['records_data'] = $return_array['records_data'];
            $data['page_no'] = $page;
            $data['subtab'] = 1;
            $this->load->view('admin/show-sms-tracking', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get SMS Tracking Tabs
    public function get_sms_tracking_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_userdata['admin_id'];
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $result_logs = $this->admin_data_model->countDeliveryReports();
                $total_logs = 0;
                if ($result_logs) {
                    $total_logs = $result_logs->total;
                }
                $logs = $this->admin_data_model->getDeliveryReports($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['total_records'] = $total_logs;
                $data['delivery_reports'] = $return_array['return_array'];
                $data['total_pages'] = $return_array['total_pages'];
                $data['records_data'] = $return_array['records_data'];
                $data['page_no'] = $page;
                $data['subtab'] = 1;
            }
            if ($subtab == 7) {
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                // Pagination
                $page = 1;
                $records_per_page = 200;
                $index = ($page * $records_per_page) - 200;
                $result_logs = $this->admin_data_model->countDeliveryReports();
                $total_logs = 0;
                if ($result_logs) {
                    $total_logs = $result_logs->total;
                }
                $logs = $this->admin_data_model->getSmsNumberReports($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['total_records'] = $total_logs;
                $data['delivery_reports'] = $return_array['return_array'];
                $data['total_pages'] = $return_array['total_pages'];
                $data['records_data'] = $return_array['records_data'];
                $data['page_no'] = $page;
                $data['subtab'] = 7;
            }
            if ($subtab == 4) {
               $data['result_users'] = $this->admin_data_model->getPushDLRUsers();
            }
            $this->load->view('admin/show-sms-tracking', $data);
        }
    }

    // Track Mobile Number
    public function track_mobile($mobile = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($mobile != null) {
                $data["delivery_reports"] = $this->admin_data_model->trackMobile($mobile);
            } else {
                $data["delivery_reports"] = false;
            }
            $this->load->view('admin/show-track-mobile', $data);
        }
    }

    // Sent SMS
    public function sent_sms($campaign_id = 0, $route = null, $user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_id'] = $user_id;
            $data['route'] = $route;
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $data['sent_sms_status'] = $this->admin_data_model->getSentSMSStatus($campaign_id);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSentSMS($campaign_id);
            $logs = $this->admin_data_model->getSentSMS($campaign_id, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['sent_sms'] = $return_array['return_array'];
            $data['total_pages'] = $return_array['total_pages'];
            $data['records_data'] = $return_array['records_data'];
            $data['total_records'] = $total_logs;
            $data['page_no'] = $page;
            $data['subtab'] = 1;
            $data['page'] = "delivery_reports";
            $data['campaign'] = $campaign_id;
            $data['campaign_process_status'] = $this->admin_data_model->campaignProcessStatus($campaign_id);
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sent-sms');
            $this->load->view('admin/footer');
        }
    }

    // Paging Sent SMS
    public function paging_sent_sms($page = 0, $records_per_page = 0, $campaign_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSentSMS($campaign_id);
            $logs = $this->admin_data_model->getSentSMS($campaign_id, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['sent_sms'] = $return_array['return_array'];
            $data['total_pages'] = $return_array['total_pages'];
            $data['records_data'] = $return_array['records_data'];
            $data['total_records'] = $total_logs;
            $data['page_no'] = $page;
            $data['subtab'] = 1;
            $data['campaign'] = $campaign_id;
            $this->load->view('admin/show-sent-sms', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Delete SMS Tracking
    public function delete_dlr_report($campaign_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteDlrReport($campaign_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Delivery report deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Delivery report deletion failed! Please try again!';
            }
            $data['delivery_reports'] = $this->admin_data_model->getDeliveryReports();
            $data['page'] = "delivery_reports";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sent-sms');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Delete Sent SMS
    public function delete_sent_sms($sms_id = 0, $campaign_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteSentSMS($sms_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' SMS deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' SMS deletion failed! Please try again!';
            }
            $data['sent_sms'] = $this->admin_data_model->getSentSMS($campaign_id);
            $this->load->view('admin/sent-sms', $data);
        }
    }

    // Search Delivery Reports
    public function search_delivery_reports($search = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            if ($search == "" && $search == null) {
                if ($this->session->unset_userdata('dlr_search_data')) {
                    $this->session->unset_userdata('dlr_search_data');
                }
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $delivery_reports = $this->admin_data_model->getDeliveryReports($index, $records_per_page);
                $slice_array = array();
                if ($delivery_reports) {
                    $slice_array = array_slice($delivery_reports, $index, $records_per_page);
                    $total_pages = ceil(count($delivery_reports) / $records_per_page);
                    $total_records = sizeof($delivery_reports);
                    $data['delivery_reports'] = $slice_array;
                } else {
                    $total_pages = 0;
                    $total_records = 0;
                    $data['delivery_reports'] = $slice_array;
                }
                $data['total_pages'] = $total_pages;
                $data['page_no'] = $page;
                $data['total_records'] = $total_records;
                $data['subtab'] = 1;
                $data['purpose'] = 1;
                $this->load->view('admin/show-delivery-reports', $data);
            } else {
                $this->session->set_userdata('dlr_search_data', $search);
                $data['delivery_reports'] = $this->admin_data_model->searchDeliveryReports($search);
                $this->load->view('admin/search-delivery-reports', $data);
            }
        }
    }

    // Search Sent SMS
    public function search_sent_sms($campaign_id = 0, $search = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $page = 1;
            $records_per_page = 100;
            $sent_sms = $this->admin_data_model->searchSentSMS($campaign_id, $search);
            $index = ($page * $records_per_page) - 100;
            $data['sent_sms'] = array_slice($sent_sms, $index, $records_per_page);
            $total_pages = ceil(count($sent_sms) / $records_per_page);
            $data['total_pages'] = $total_pages;
            $data['page_no'] = $page;
            $data['total_records'] = sizeof($sent_sms);
            $data['campaign'] = $campaign_id;
            $this->load->view('admin/show-sent-sms', $data);
        }
    }

    // Get Detailed Reports
    public function get_detailed_report() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['detailed_reports'] = $this->admin_data_model->getDetailedReport();
            $this->load->view('admin/show-detailed-reports', $data);
        }
    }

    // Get Delivery Reports For Resend
    public function get_delivery_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['sent_sms_status'] = $this->admin_data_model->getDeliveryReportsResend();
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $this->load->view('admin/get-delivery-reports', $data);
        }
    }

    public function get_overall_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['sent_sms_status'] = $this->admin_data_model->getOverallReport();
            $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $this->load->view('admin/get-overall-report', $data);
        }
    }

    // Resend Delivery Reports
    public function resend_delivery_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->resendDeliveryReports();
            if ($response) {
                header('Content-Type: application/json');
                $session_array = array(
                    'type' => 1,
                    'message' => ' Message resent successfully!',
                    'message_type' => "success"
                );
                echo json_encode($session_array);
                die;
            } else {
                header('Content-Type: application/json');
                $session_array = array(
                    'type' => 0,
                    'message' => ' Message resending failed!',
                    'message_type' => "error"
                );
                echo json_encode($session_array);
                die;
            }
        }
    }

    // Get Active Users (Day-Wise)
    public function filter_active_users() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['active_users'] = $this->admin_data_model->getActiveUsers();
            $this->load->view('admin/show-active-users', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Settings Like Virtual Balance, Signup Setting Etc.
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Settings
    public function settings() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('4', $permissions_array)) {
                if ($session_data['atype'] == 1) {
                    $table['subtab'] = 1;
                    $data['subtab'] = "1";
                    // Table View
                    $table['atype'] = $session_data['atype'];
                    $data['table'] = $this->load->view('admin/show-setting-tab', $table, true);
                } elseif ($session_data['atype'] == 2) {
                    // Table View
                    $table['subtab'] = 2;
                    $table['atype'] = $session_data['atype'];
                    $data['table'] = $this->load->view('admin/show-setting-tab', $table, true);
                    $data['subtab'] = "2";
                }
                // Main View
                $data['page'] = "settings";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/settings');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Setting Tabs
    public function get_setting_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            $data['subtab'] = $subtab;
            // Virtual Balance
            if ($subtab == 1) {
                $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
                $data['total_pr_balance'] = $admin_sms['total_pr_balance'];
                $data['total_tr_balance'] = $admin_sms['total_tr_balance'];
                $data['total_lcode_balance'] = $admin_sms['total_lcode_balance'];
                $data['total_scode_balance'] = $admin_sms['total_scode_balance'];
                $data['total_vpr_balance'] = $admin_sms['total_vpr_balance'];
                $data['total_vtr_balance'] = $admin_sms['total_vtr_balance'];
                $data['total_prtodnd_credits'] = $admin_sms['total_prtodnd_credits'];
                $data['total_stock_credits'] = $admin_sms['total_stock_credits'];
                $data['international_sms'] = $admin_sms['international_sms'];
            }
            // Default Setting For New User
            if ($subtab == 2) {
                $data['settings'] = $this->admin_data_model->getSetting();
            }
            // SMPP Ports
            if ($subtab == 3) {
                $port_id = FALSE;
                $data['smpp_ports'] = $this->admin_data_model->getSMPPPorts($port_id);
            }
            // White, Black List Numbers And Black Sender Ids
            if ($subtab == 5 || $subtab == 6 || $subtab == 7) {
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                // White List Numbers
                if ($subtab == 5) {
                    $total_logs = $this->admin_data_model->countWBLists(1);
                    $logs = $this->admin_data_model->getWhiteLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['white_lists'] = $return_array['return_array'];
                }
                // Black List Numbers
                if ($subtab == 6) {
                    $total_logs = $this->admin_data_model->countWBLists(0);
                    $logs = $this->admin_data_model->getBlackLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['black_lists'] = $return_array['return_array'];
                }
                // Black Sender Ids
                if ($subtab == 7) {
                    $black_lists = $this->admin_data_model->getBlackListSenderIds();
                    $return_array = $this->paging($black_lists, $index, $records_per_page);
                    $data['black_lists'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['section'] = 1;
                $paging['function'] = "Settings";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            // Default Signup Settings
            if ($subtab == 8) {
                $data['settings'] = $this->admin_data_model->getSetting();
            }
            // Other Settings Like Demo SMS, Forgot Passowrd, Location Verification
            if ($subtab == 9) {
                $data['settings'] = $this->admin_data_model->getSetting();
            }
            // Retry/Resend Settings
            if ($subtab == 10) {
                $data['settings'] = $this->admin_data_model->getSetting();
            }
            if ($subtab == 11) {
                $data['settings'] = $this->admin_data_model->userAlert();
            }
            if ($subtab == 12) {
                // $data['settings'] = $this->admin_data_model->userAlert();
            }
            $this->load->view('admin/show-setting-tab', $data);
        }
    }

    // Paging Settings
    public function paging_settings($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $index = ($page * $records_per_page) - 100;
            // White List Numbers
            if ($subtab == 5) {
                $total_logs = $this->admin_data_model->countWBLists(1);
                $logs = $this->admin_data_model->getWhiteLists($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['white_lists'] = $return_array['return_array'];
            }
            // Black List Numbers
            if ($subtab == 6) {
                $total_logs = $this->admin_data_model->countWBLists(0);
                $logs = $this->admin_data_model->getBlackLists($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_lists'] = $return_array['return_array'];
            }
            // Black List Sender Ids
            if ($subtab == 7) {
                $black_lists = $this->admin_data_model->getBlackListSenderIds();
                $return_array = $this->paging($black_lists, $index, $records_per_page);
                $data['black_lists'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['section'] = 1;
            $paging['function'] = "Settings";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-setting-tab', $data);
        }
    }

    // Get User Groups Tabs
    public function get_ugroups_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            if ($subtab == 1) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Promotional', 0);
                $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            } elseif ($subtab == 2) {
                $data["user_groups"] = $this->admin_data_model->getUserGroups('Transactional', 0);
                $data["all_user_groups"] = $this->admin_data_model->getUserGroups(null, 0);
            } elseif ($subtab == 3) {
                $data["backup_pr_ugroups"] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data["backup_tr_ugroups"] = $this->admin_data_model->getUserGroups('Transactional', 1);
            } elseif ($subtab == 4 || $subtab == 5) {
                $result_settings = $this->admin_data_model->getDefaultSettings();
                if ($result_settings) {
                    $data['setting_id'] = $result_settings->setting_id;
                    $data['xml_route_authkey'] = $result_settings->xml_route_authkey;
                    $data['xml_route_url'] = $result_settings->xml_route_url;
                    $data['backup_time_duration'] = $result_settings->backup_time_duration;
                    $data['backup_limit'] = $result_settings->backup_limit;
                }
            }
            $this->load->view('admin/show-ugroups-tab', $data);
        }
    }

    // Get User Groups
    public function get_user_groups($type = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($type == 'Promotional')
                $data['user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            elseif ($type == 'Transactional')
                $data['user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $this->load->view('admin/show-user-groups', $data);
        }
    }

    // Save Setting
    public function save_setting($subtab = 0, $ex_subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            // Virtual Balance
            if ($subtab == 1 && $ex_subtab == 1) {
                $response = $this->admin_data_model->saveVirtualBalance($admin_id);
                $data['admin_id'] = $session_data['admin_id'];
                $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
                $data['total_pr_balance'] = $admin_sms['total_pr_balance'];
                $data['total_tr_balance'] = $admin_sms['total_tr_balance'];
                $data['total_lcode_balance'] = $admin_sms['total_lcode_balance'];
                $data['total_scode_balance'] = $admin_sms['total_scode_balance'];
                $data['total_vpr_balance'] = $admin_sms['total_vpr_balance'];
                $data['total_vtr_balance'] = $admin_sms['total_vtr_balance'];
                $data['total_mcall_balance'] = $admin_sms['total_mcall_balance'];
                $data['admin_pr_credits'] = $admin_sms['admin_pr_credits'];
                $data['admin_tr_credits'] = $admin_sms['admin_tr_credits'];
                $data['prtodnd_balance'] = $admin_sms['total_prtodnd_balance'];
                $data['stock_balance'] = $admin_sms['total_stock_balance'];
                $data['total_prtodnd_credits'] = $admin_sms['total_prtodnd_credits'];
                $data['total_stock_credits'] = $admin_sms['total_stock_credits'];
                $data['international_sms'] = $admin_sms['international_sms'];
            }
            // SMPP Ports
            if ($subtab == 4 && $ex_subtab == 1) {
                $response = $this->admin_data_model->saveVirtualSMPPPort();
                $port_id = FALSE;
                $data['smpp_ports'] = $this->admin_data_model->getSMPPPorts($port_id);
                $data['subtab'] = '3';
            }



            // White/Black Listed Contents
            if ($subtab == 5 || $subtab == 6 || $subtab == 7 || $subtab == 12 || $subtab == 11) {
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                // White Listed Numbers
                if ($subtab == 5 && $ex_subtab == 1) {
                    $type = "Number";
                    $response = $this->admin_data_model->saveNumber();
                    $total_logs = $this->admin_data_model->countWBLists(1);
                    $logs = $this->admin_data_model->getWhiteLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['white_lists'] = $return_array['return_array'];
                }
                // Black Listed Numbers
                if ($subtab == 6 && $ex_subtab == 1) {
                    $type = "Number";
                    $response = $this->admin_data_model->saveNumber();
                    $total_logs = $this->admin_data_model->countWBLists(0);
                    $logs = $this->admin_data_model->getBlackLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['black_lists'] = $return_array['return_array'];
                }
                //save alert
                if ($subtab == 11 && $ex_subtab == 1) {
                    $response = $this->admin_data_model->saveSetting($subtab, $ex_subtab);
                }
                if ($subtab == 12) {
                    $response = $this->admin_data_model->saveDefaultRatio();
                }
                // Black Listed Sender ids
                if ($subtab == 7 && $ex_subtab == 1) {
                    $response = $this->admin_data_model->saveBSenderId();
                    $type = "Sender Id";
                    $black_lists = $this->admin_data_model->getBlackListSenderIds();
                    $return_array = $this->paging($black_lists, $index, $records_per_page);
                    $data['black_lists'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['section'] = 1;
                $paging['function'] = "Settings";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }/* else {
              $response = $this->admin_data_model->saveSetting($subtab, $ex_subtab);
              $data['settings'] = $this->admin_data_model->getSetting();
              } */
            if ($subtab == 2 && $ex_subtab == 1) {
                $response = $this->admin_data_model->saveSetting($subtab, $ex_subtab);
            }
            $data['settings'] = $this->admin_data_model->getSetting();
            if ($response == 1) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Setting updated successfully!';
            } elseif ($response == 0) {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Setting updation failed! Please try again!';
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = '' . $type . ' already exists! Please try another';
            }
            $data['subtab'] = $subtab;

            $this->load->view('admin/show-setting-tab', $data);
        }
    }

    // Delete Settings
    public function delete_settings($id = 0, $subtab = 0, $pk = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            $data['subtab'] = $subtab;
            // SMPP Ports
            if ($id == 'port') {
                $response = $this->admin_data_model->deleteSMPPPorts($pk);
                $port_id = FALSE;
                $data['smpp_ports'] = $this->admin_data_model->getSMPPPorts($port_id);
            }
            // White/Black Listed Contents
            if ($id == 'white' || $id == 'black' || $id == 'sender') {
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                // White Listed Numbers
                if ($id == 'white') {
                    $response = $this->admin_data_model->deleteNumber($pk, $id);
                    $total_logs = $this->admin_data_model->countWBLists(1);
                    $logs = $this->admin_data_model->getWhiteLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['white_lists'] = $return_array['return_array'];
                }
                // Black Listed Numbers
                if ($id == 'black') {
                    $response = $this->admin_data_model->deleteNumber($pk, $id);
                    $total_logs = $this->admin_data_model->countWBLists(0);
                    $logs = $this->admin_data_model->getBlackLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['black_lists'] = $return_array['return_array'];
                }
                // Black Listed Sender
                if ($id == 'sender') {
                    $array = explode('_', $pk);
                    $response = $this->admin_data_model->deleteBSenderId($array[0], $array[1]);
                    $black_lists = $this->admin_data_model->getBlackListSenderIds();
                    $return_array = $this->paging($black_lists, $index, $records_per_page);
                    $data['black_lists'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['section'] = 1;
                $paging['function'] = "Settings";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Item deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Item deletion failed! Please try again!';
            }
            $this->load->view('admin/show-setting-tab', $data);
        }
    }

    // Enable/Disable Settings
    public function change_status($id = 0, $subtab = 0, $pk = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['admin_id'] = $session_data['admin_id'];
            $data['atype'] = $session_data['atype'];
            $data['subtab'] = $subtab;
            $array = explode('_', $pk);
            // SMPP Ports
            if ($id == 'port') {
                $response = $this->admin_data_model->enDisSettings($id, $array [0], $array[1]);
                $port_id = FALSE;
                $data['smpp_ports'] = $this->admin_data_model->getSMPPPorts($port_id);
            }
            // White/Black Numbers
            if ($id == 'white' || $id == 'black') {
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                // White Numbers
                if ($id == 'white') {
                    $response = $this->admin_data_model->enDisSettings($id, $array[0], $array[1]);
                    $total_logs = $this->admin_data_model->countWBLists(1);
                    $logs = $this->admin_data_model->getWhiteLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['white_lists'] = $return_array['return_array'];
                }
                // Black Numbers
                if ($id == 'black') {
                    $response = $this->admin_data_model->enDisSettings($id, $array[0], $array[1]);
                    $total_logs = $this->admin_data_model->countWBLists(0);
                    $logs = $this->admin_data_model->getBlackLists($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['black_lists'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['section'] = 1;
                $paging['function'] = "Settings";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Status changing failed! Please try again!';
            }
            $this->load->view('admin/show-setting-tab', $data);
        }
    }

    // Update SMPP Port
    public function update_port($port_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = 4;
            $admin_id = $session_data['admin_id'];
            $data['smpp_port'] = $this->admin_data_model->getSMPPPorts($port_id);
            $smpp_port = $this->admin_data_model->getSMPPPorts($port_id);
            $smpp_type = $smpp_port->purpose;
            if ($smpp_type == 'Promotional') {
                $data['user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            } elseif ($smpp_type == 'Transactional') {
                $data['user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            }
            $data['page'] = "settings";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/setting-sub-tab');
            $this->load->view('admin/settings');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Sender Ids
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Sender Ids
    public function sender_ids() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Sender Ids 
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds(1);

            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);

            $table['user_sender_ids'] = $return_array['return_array'];

            // var_dump($table['table_data']);die;
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Senders";
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Table View
            $table['subtab'] = 1;

            $data['table'] = $this->load->view('admin/show-sender-ids-tab', $table, true);

            // Main View
            $data['subtab'] = "1";
            $data['page'] = "sender_ids";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sender-ids');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

//export vodafone approve sender id
    public function exportNewSenderID() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->dbutil();
            $this->load->helper('file');
            $this->load->helper('download');
            $delimiter = ",";
            $newline = "\r\n";
            $filename = "filename_you_wish.csv";
            $query = "SELECT sender_id FROM approve_sender_id";
            $result = $this->db->query($query);
            $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
            force_download($filename, $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    //export Not approve sender id by date
    public function export_senderid_by_date() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $from_date = $this->input->post('export_from_date');
            $to_date = $this->input->post('export_to_date');
            if ($from_date && $to_date) {
                $this->load->dbutil();
                $this->load->helper('file');
                $this->load->helper('download');
                $delimiter = ",";
                $newline = "\r\n";
                $filename = "filename_you_wish.csv";
                $query = "SELECT sender_id FROM not_approve_senderid WHERE date BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
                $result = $this->db->query($query);
                $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                force_download($filename, $data);
            } else {
                $this->load->dbutil();
                $this->load->helper('file');
                $this->load->helper('download');
                $delimiter = ",";
                $newline = "\r\n";
                $filename = "filename_you_wish.csv";
                $query = "SELECT sender_id FROM not_approve_senderid";
                $result = $this->db->query($query);
                $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                force_download($filename, $data);
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function approve_all_sender_id() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->approveAllSenderId();
            if ($response) {
                redirect('admin/sender_ids', 'refresh');
            } else {
                redirect('admin/sender_ids', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function ImportNewSenderID() {
        /*  if (isset($_POST["import"])) {
          $route = $_POST['user_group_id'];
          //    $read= readfile($_FILES['file']['tmp_name']) ;
          $filename = $_FILES["file"]["tmp_name"];


          if ($_FILES["file"]["size"] > 0) {
          $file = fopen($filename, "r");
          while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
          $row++;
          $import1[] = $importdata[0];
          }
          $row;
          $new_data = array();
          $new_data1 = array();
          for ($i = 0; $i < $row; $i++) {
          $id = $import1[$i];

          $verifyID = $this->admin_data_model->verify_New_Sender_ID($id);
          if ($verifyID) {

          } else {
          $new_data1 = array(
          'sender_id' => $import1[$i],
          'route' => $route,
          'status' => 1
          );
          $new_data[] = $new_data1;
          }
          }
          //var_dump($new_data);die;
          //       echo $size = sizeof($new_data);
          $import_data = array_unique($new_data);

          $this->db->insert_batch('approve_sender_id', $import_data);

          }
          fclose($file);
          $this->session->set_flashdata('message', 'Data are imported successfully..');
          redirect('admin/sender_ids');
          } else {
          $this->session->set_flashdata('message', 'Something went wrong..');
          redirect('admin/sender_ids');
          } */
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        if (isset($_POST["import"])) {
            $route = $_POST['user_group_id'];
            //    $read= readfile($_FILES['file']['tmp_name']) ;
            $filename = $_FILES["file"]["tmp_name"];


            if ($_FILES["file"]["size"] > 0) {
                $file = fopen($filename, "r");
                while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $row++;
                    $import1[] = $importdata[0];
                }
                $row;
                $new_data = array();
                $new_data1 = array();
                for ($i = 0; $i < $row; $i++) {
                    //  $id = $import1[$i];
                    // $verifyID = $this->test_model->unique_sender($id);
                    // if ($verifyID) {
                    // } else {
                    $new_data1 = array(
                        'route' => $route,
                        'status' => 1,
                        'sender_id' => $import1[$i]
                    );
                    //  $new_data[] = $new_data1;
                    $this->db->insert('approve_sender_id', $new_data1);
                    // }
                }
                //var_dump($new_data);die;
                //       echo $size = sizeof($new_data);
                //  $import_data = array_unique($new_data);          
                //  $this->db->insert_batch('demo_table',$import_data);
            }
            fclose($file);
            $this->session->set_flashdata('message', 'Data are imported successfully..');
            redirect('admin/sender_ids');
        } else {
            $this->session->set_flashdata('message', 'Something went wrong..');
            redirect('admin/sender_ids');
        }
    }

    public function import_New_ID() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $contacts = array();
        $target_file = $_FILES["file"]["name"];
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($imageFileType != "csv") {
            $data['msg_type'] = '0';
            $data['msg_data'] = 'Please upload CSV file only';
        } else {
            $user_group_id = $this->input->post('user_group_id');

            $config['upload_path'] = './newCSV/';
            $config['allowed_types'] = '*';
            $config['max_filename'] = '255';
            $config['encrypt_name'] = TRUE;
            $config['max_size'] = '1024'; //1 MB

            if (isset($_FILES['file']['name'])) {
                if (0 < $_FILES['file']['error']) {
                    echo 'Error during file upload' . $_FILES['file']['error'];
                } else {
                    if (file_exists('uploads/' . $_FILES['file']['name'])) {
                        echo 'File already exists : uploads/' . $_FILES['file']['name'];
                    } else {
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('file')) {
                            echo $this->upload->display_errors();
                            $response = 0;
                        } else {

                            $filename = $_FILES["file"]["tmp_name"];

                            $file = fopen($filename, "r");

                            while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                                $row++;
                                $import1[] = $importdata[0];
                            }
                            $row;
                            $new_data = array();
                            $new_data1 = array();
                            for ($i = 0; $i < $row; $i++) {

                                $id = $import1[$i];
                                $size = strlen($id);

                                // $verifyID = $this->test_model->unique_sender();
                                $this->db->select('*');
                                $this->db->from('approve_sender_id');
                                $this->db->where('sender_id', $id);

                                $query = $this->db->get();
                                if ($query->num_rows() || $id == NULL) {
                                    
                                } else {
                                    $new_data1 = array(
                                        'sender_id' => $import1[$i],
                                        'route' => $user_group_id,
                                        'status' => 1
                                    );
                                    //  $new_data[] = $new_data1;
                                    $contacts[] = $new_data1;
                                    //$this->db->insert('import_data', $new_data1);
                                }
                            }
                            if (empty($contacts)) {
                                $data['msg_type'] = '0';
                                $data['msg_data'] = ' Sender Id already available!';
                            } else {
                                $number_of_id = sizeof($contacts);
                                if ($this->db->insert_batch('approve_sender_id', $contacts)) {
                                    $data['msg_type'] = '1';
                                    $data['msg_data'] = ' Sender Id inserted successfully!';
                                }
                            }
                            // echo $number_of_id . " Sender Id Import seccessfully ";
                        }
                    }
                }
            } else {
                // echo 'Please choose a file';
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id process failed!';
            }
        }

        $data['subtab'] = 4;
        $data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab = 4);
        $data['table_data'] = $this->admin_data_model->getRoute();
        $data['size'] = sizeof($data['table_data']);

        $this->load->view('admin/show-sender-ids-tab', $data);
    }

    public function import_pr_sender_id() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $contacts = array();
        $target_file = $_FILES["file"]["name"];
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($imageFileType != "csv") {
            $data['msg_type'] = '0';
            $data['msg_data'] = 'Please upload CSV file only';
        } else {
            $user_group_id = $this->input->post('pr_user_group_id');

            $config['upload_path'] = './newCSV/';
            $config['allowed_types'] = '*';
            $config['max_filename'] = '255';
            $config['encrypt_name'] = TRUE;
            $config['max_size'] = '1024'; //1 MB

            if (isset($_FILES['file']['name'])) {
                if (0 < $_FILES['file']['error']) {
                    echo 'Error during file upload' . $_FILES['file']['error'];
                } else {
                    if (file_exists('uploads/' . $_FILES['file']['name'])) {
                        echo 'File already exists : uploads/' . $_FILES['file']['name'];
                    } else {
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('file')) {
                            echo $this->upload->display_errors();
                            $response = 0;
                        } else {

                            $filename = $_FILES["file"]["tmp_name"];

                            $file = fopen($filename, "r");

                            while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                                $row++;
                                $import1[] = $importdata[0];
                            }
                            $row;
                            $new_data = array();
                            $new_data1 = array();
                            for ($i = 0; $i < $row; $i++) {

                                $id = $import1[$i];
                                $size = strlen($id);

                                // $verifyID = $this->test_model->unique_sender();
                                $this->db->select('*');
                                $this->db->from('pr_approve_sender_id');
                                $this->db->where('sender_id', $id);

                                $query = $this->db->get();
                                if ($query->num_rows() || $id == NULL) {
                                    
                                } else {
                                    $new_data1 = array(
                                        'sender_id' => $import1[$i],
                                        'route' => $user_group_id,
                                        'status' => 1
                                    );
                                    //  $new_data[] = $new_data1;
                                    $contacts[] = $new_data1;
                                    //$this->db->insert('import_data', $new_data1);
                                }
                            }
                            if (empty($contacts)) {
                                $data['msg_type'] = '0';
                                $data['msg_data'] = ' Sender Id already available!';
                            } else {
                                $number_of_id = sizeof($contacts);
                                if ($this->db->insert_batch('pr_approve_sender_id', $contacts)) {
                                    $data['msg_type'] = '1';
                                    $data['msg_data'] = ' Sender Id inserted successfully!';
                                }
                            }
                            // echo $number_of_id . " Sender Id Import seccessfully ";
                        }
                    }
                }
            } else {
                // echo 'Please choose a file';
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id process failed!';
            }
        }

        $data['subtab'] = 7;
        $data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab = 7);
        $data['table_data'] = $this->admin_data_model->getRoute();
        $data['table_pr_data'] = $this->admin_data_model->getPrSenderId();
        $data['size'] = sizeof($data['table_data']);

        $this->load->view('admin/show-sender-ids-tab', $data);
    }

    // Get Sender Ids Tabs
    public function get_sender_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['table_data'] = $this->admin_data_model->getRoute();
            $data['table_pr_data'] = $this->admin_data_model->getPrSenderId();
            $data['size'] = sizeof($data['table_data']);
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    //Insert New vodafone approve sender id
    public function insert_new_sender_id() {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->insertNewSenderId();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id process failed!';
            }

            $data['subtab'] = 4;
            $data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab = 4);
            $data['table_data'] = $this->admin_data_model->getRoute();
            $data['size'] = sizeof($data['table_data']);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }

        // echo $this->admin_data_model->insertNewSenderId();
        // die;
    }

    //Insert promotional approve sender id
    public function insert_pr_sender_id() {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->insertPrSenderId();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id process failed!';
            }

            $data['subtab'] = 7;
            $data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab = 7);
            $data['table_pr_data'] = $this->admin_data_model->getPrSenderId();
            $data['size'] = sizeof($data['table_data']);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

//check vodafone sender id
    public function check_sender_id_availability() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        echo $result = $this->admin_data_model->checkSenderIdAvailability();
        die;
    }

    //check promotional sender id
    public function check_pr_sender_id_availability() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        echo $result = $this->admin_data_model->checkPrSenderIdAvailability();
        die;
    }

    //update vodafone sender id routing
    public function update_sender_id_routing() {

        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->updateSenderIdRouting();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = 'Route Change successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Process Error!';
            }
            $data['subtab'] = 5;
            $data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab = 5);
            $data['table_data'] = $this->admin_data_model->getRoute();

            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    //delete by cron job 
    public function delete_not_sender_id() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->admin_data_model->deleteNotApproveSenderid();
    }

    public function get_sender_tab_uniq($subtab = 3) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;

            $data['user_sender_ids'] = $this->admin_data_model->getUniqueSenderIds();
            //      var_dump($data['user_sender_ids']);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    // Paging Sender Ids
    public function paging_sender_id($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-sender-ids-tab', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    //paging payment log
    public function paging_payment_logs($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $records_per_page = 200;
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $index = ($page * $records_per_page) - 200;
            $payment_log = $this->admin_data_model->paymentAproval();
            $return_array = $this->paging($payment_log, $index, $records_per_page);
            $data['transation_log'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Payment";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-payment-logs', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    //subadmin payment log 

    public function paging_payment_subadmin_logs($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $records_per_page = 100;
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $index = ($page * $records_per_page) - 100;
            $payment_log = $this->admin_data_model->showPaymentDetails($admin_id);
            $return_array = $this->paging($payment_log, $index, $records_per_page);
            $data['transation_log'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "PaymentSubadmin";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-payment-logs', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Approve/Disapprove Sender Id
    public function sender_id_status($sender_id = null, $sender_key = 0, $status = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->enDisSenderId($sender_id, $sender_key, $status);
            if ($status) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id approved successfully!';
            } else {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id disapproved successfully!';
            }
            if ($status && $subtab == 2) {
                $data['subtab'] = '1';
            } elseif (!$status && $subtab == 1) {
                $data['subtab'] = '2';
            }
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            //$data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    // Delete Sender Id
    public function delete_sender_id($sender_id = null, $sender_key = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            $response = $this->admin_data_model->deleteSenderId($sender_id, $sender_key);
            //  $result = $this->admin_data_model->deleteApproveSenderId($sender_id, $sender_key);
            /*  if ($result==1) {
              $data['msg_type'] = '1';
              $data['msg_data'] = ' Sender Id deleted successfully!';
              } */
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id deletion failed!';
            }

            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            //$data['user_sender_ids'] = $this->admin_data_model->getSenderIds($subtab);
            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

// Delete vodafone Sender Id
    public function delete_approve_sender_id($sender_id, $sender_key, $subtab) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            $result = $this->admin_data_model->deleteApproveSenderId($sender_id, $sender_key);
            if ($result) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id deletion failed!';
            }
            //$this->get_sender_tab($subtab = 4);

            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['table_data'] = $this->admin_data_model->getRoute();
            $data['paging'] = $this->load->view('admin/paging', $paging, true);

            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    // Delete vodafone Sender Id
    public function delete_pr_approve_sender_id($sender_id, $sender_key, $subtab) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            $result = $this->admin_data_model->deletePrApproveSenderId($sender_id, $sender_key);
            if ($result) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id deletion failed!';
            }
            //$this->get_sender_tab($subtab = 4);

            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $user_sender_ids = $this->admin_data_model->getSenderIds($subtab);
            $return_array = $this->paging($user_sender_ids, $index, $records_per_page);
            $data['user_sender_ids'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Senders";
            // Paging View
            $data['table_pr_data'] = $this->admin_data_model->getPrSenderId();
            $data['paging'] = $this->load->view('admin/paging', $paging, true);

            $this->load->view('admin/show-sender-ids-tab', $data);
        }
    }

    //check sender id those are hit first time and not in vodafone approval table
    //this function is based on cronjob
    public function sender_id_first_time() {
        $this->admin_data_model->senderIdFirstTime();
    }

    //approve sender id from not approve sender id block
    public function click_to_approve($id = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $result = $this->admin_data_model->clickToApprove($id);
            if ($result) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Sender Id approve successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Sender Id approve failed!';
            }


            $this->get_sender_tab($subtab = 6);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Keywords & Black Listed Keywords To Stop Spaming
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Approved Keywords
    public function keywords() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countKeywords('1', true);
            $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page, true);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $table['approve_keywords'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Keywords";
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Table View
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-keyword-tab', $table, true);
            // Main View
            $data['page'] = "keywords";
            $data['subtab'] = "1";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/keywords');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

//delete all keywords by checkbox
    public function delete_data() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if (isset($_POST['bulk_delete_submit'])) {
                $idArr = $_POST['checked_id'];
                foreach ($idArr as $id) {
                    $this->db->where('keyword_id', $id);
                    $this->db->delete('keywords');
                }
                //  echo 'Users have been deleted successfully.';
                redirect('admin/keywords', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Keyword Tabs
    public function get_keyword_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Approved Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countKeywords('1', true);
                $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['approve_keywords'] = $return_array['return_array'];
            }
            // Pending Keywords
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countKeywords('0', true);
                $logs = $this->admin_data_model->getKeywords('0', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['pending_keywords'] = $return_array['return_array'];
            }
            // Black Keywords
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countKeywords('black', true);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            // Users/Resellers Black Keywords
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countKeywords('black', false);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, false);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

    // Paging Keyword
    public function paging_keywords($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            // Approved Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countKeywords('1', true);
                $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['approve_keywords'] = $return_array['return_array'];
            }
            // Pending Keywords
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countKeywords('0', true);
                $logs = $this->admin_data_model->getKeywords('0', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['pending_keywords'] = $return_array['return_array'];
            }
            // Black Keywords
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countKeywords('black', true);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            // Users/Resellers Black Keywords
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countKeywords('black', false);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, false);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-keyword-tab', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Delete Keywords
    public function delete_keyword($id = 0, $subtab = 0, $pk = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Approved Keywords
            if ($subtab == 1) {
                $response = $this->admin_data_model->deleteKeyword($id, $pk);
                $total_logs = $this->admin_data_model->countKeywords('1', true);
                $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['approve_keywords'] = $return_array['return_array'];
            }
            // Pending Keywords
            if ($subtab == 2) {
                $response = $this->admin_data_model->deleteKeyword($id, $pk);
                $total_logs = $this->admin_data_model->countKeywords('0', true);
                $logs = $this->admin_data_model->getKeywords('0', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['pending_keywords'] = $return_array['return_array'];
            }
            // Admin Black Keywords
            if ($subtab == 3) {
                $response = $this->admin_data_model->deleteKeyword($id, $pk);
                $total_logs = $this->admin_data_model->countKeywords('black', true);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            // Users/Resellers Black Keywords
            if ($subtab == 4) {
                $response = $this->admin_data_model->deleteKeyword($id, $pk);
                $total_logs = $this->admin_data_model->countKeywords('black', false);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, false);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Keyword deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Keyword deletion failed!';
            }
            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

    // Save Black Keywords
    public function save_black_keyword($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['subtab'] = $subtab;
            $response = $this->admin_data_model->saveBlackKeyword($admin_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Black Keyword inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Black Keyword insertion failed!';
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countKeywords('black', true);
            $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, true);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['black_keywords'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

    // Approve Keyword
    public function approve_keyword() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = 1;
            $response = $this->admin_data_model->approveKeyword();
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = '<h4><i class="fa fa-check-circle"></i> Success</h4><p> Keyword approved successfully!</p>';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = '<h4><i class="fa fa-exclamation-circle"></i> Error</h4><p> Keyword approval failed!</p>';
            }
            // Pagination
            $page = 1;
            $records_per_page = 10;
            $index = ($page * $records_per_page) - 10;
            $total_logs = $this->admin_data_model->countKeywords('1');
            $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['approve_keywords'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

    // Change Keyword Status
    public function change_keyword_status($id = 0, $status = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            $response = $this->admin_data_model->changeKeywordStatus($id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Keyword status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Keyword status changing successfully!';
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Admin Black Keywords
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countKeywords('black', true);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, true);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            // Users/Resellers Black Keywords
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countKeywords('black', false);
                $logs = $this->admin_data_model->getKeywords('black', $index, $records_per_page, false);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['black_keywords'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Keywords";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMPP Logs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMPP Logs
    public function smpp_logs() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Table View
            $table['subtab'] = 1;
            $fdate = $this->input->post('from_date');
            $tdate = $this->input->post('to_date');
            //$table["smpp_logs"] = $this->admin_data_model->getSMPPLogs('1');
            // $table["smpp_list"] = $this->admin_data_model->getSMPPlist();
            $table["smpp_list"] = $this->admin_data_model->getSMPPDailyLog();
            $table["fdate"] = $fdate;
            $table["tdate"] = $tdate;
            $data['table'] = $this->load->view('admin/show-smpp-logs', $table, true);
            // Main View
            $data['page'] = "smpp_logs";
            $data['subtab'] = 1;
            $this->load->view('admin/header', $data);
            $this->load->view('admin/smpp-logs');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get SMPP Logs Tabs
    public function get_smpp_logs($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($subtab == 2) {
                $table['subtab'] = $subtab;
                $table["smpp_logs"] = $this->admin_data_model->getSMPPLogs($subtab);
                // Table View
                $data['table'] = $this->load->view('admin/filter-smpp-logs', $table, true);
            } elseif ($subtab == 4) {
                $data["smpp_list"] = $this->admin_data_model->getSMPPlist();
            } else {
                $data["smpp_logs"] = $this->admin_data_model->getSMPPDailyLog();
            }
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-smpp-logs', $data);
        }
    }

    public function get_overall_log() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data["smpp_logs"] = $this->admin_data_model->getOverallLog();
            $data['subtab'] = 4;
            $this->load->view('admin/show-overall-log', $data);
        }
    }

    // Filter SMPP Logs
    public function filter_smpp_logs($date = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            if ($date == null) {
                $date = date('Y-m-d');
            }
            $data["smpp_logs"] = $this->admin_data_model->filterSMPPLogs($date);
            $this->load->view('admin/filter-smpp-logs', $data);
        }
    }

    // Search SMPP Logs
    public function search_smpp_logs() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data["smpp_logs"] = $this->admin_data_model->searchSMPPLogs();
            $this->load->view('admin/filter-smpp-logs', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Misellaneous Functions Like Number To Words And Paging
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Convert Number to Words
    public function get_number_to_words($no_of_sms = 0) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->utility_model->getNumberToWords($no_of_sms);
        if ($response) {
            echo $response . " SMS";
            die;
        }
    }

    // Pagination
    public function paging($array = array(), $index = 0, $records_per_page = 0) {
        $total_pages = 0;
        $total_records = 0;
        $slice_array = array();
        if ($array) {
            $slice_array = array_slice($array, $index, $records_per_page);
            $total_pages = ceil(count($array) / $records_per_page);
            $total_records = sizeof($array);
        }
        $return_array = $slice_array;
        $page_total_records = $index + sizeof($slice_array);
        $start_page = $index + 1;
        if ($total_records) {
            $records_data = "Showing $start_page to $page_total_records of $total_records Records";
        } else {
            $records_data = "Showing $total_records Records";
        }
        // Return Data
        return array('return_array' => $return_array, 'total_pages' => $total_pages, 'records_data' => $records_data);
    }

    // Pagination
    public function paging_table($array = array(), $index = 0, $records_per_page = 0, $total_records = 0) {
        $total_pages = 0;
        if (is_array($array) && $array && sizeof($array)) {
            $total_pages = ceil($total_records / $records_per_page);
        }
        $return_array = $array;
        $page_total_records = $index + sizeof($array);
        $start_page = $index + 1;
        if ($total_records) {
            $records_data = "Showing $start_page to $page_total_records of $total_records Records";
        } else {
            $records_data = "Showing $total_records Records";
        }
        // Return Data
        return array('return_array' => $return_array, 'total_pages' => $total_pages, 'records_data' => $records_data);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//   
    // System Logs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Transactions
    public function all_logs() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            // Transaction Logs

            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            if ($admin_id == 1 || $admin_id == 2) {
                $total_logs = $this->admin_data_model->countAdminTransactions();
                $logs = $this->admin_data_model->getAdminTransactions($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['transactions1'] = $return_array['return_array'];
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $table['transactions1'] = $return_array['return_array'];
            } else {
                $total_logs = $this->admin_data_model->countTransactions($admin_id);
                $logs = $this->admin_data_model->getTransactions($admin_id, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['transactions'] = $return_array['return_array'];
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $table['transactions'] = $return_array['return_array'];
            }



            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Logs";
            $paging['admin_id'] = $admin_id;
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Table View
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-logs-tab', $table, true);
            // Main View
            $data['page'] = "all_logs";
            $data['subtab'] = "1";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/all-logs');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Logs Tabs
    public function get_logs_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            if ($subtab == 1) {
                // Transaction Logs
                if ($admin_id == 1 || $admin_id == 6 || $admin_id == 2) {
                    $total_logs = $this->admin_data_model->countAdminTransactions();
                    $logs = $this->admin_data_model->getAdminTransactions($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['transactions1'] = $return_array['return_array'];
                } else {
                    $total_logs = $this->admin_data_model->countTransactions($admin_id);
                    $logs = $this->admin_data_model->getTransactions($admin_id, $index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['transactions'] = $return_array['return_array'];
                }
            } elseif ($subtab == 2) {
                // Error Logs
                $total_logs = $this->admin_data_model->countSMSLogs();
                $logs = $this->admin_data_model->getSMSLogs($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['sms_logs'] = $return_array['return_array'];
            } elseif ($subtab == 3) {
                // Error Logs
                $consumption_logs = $this->admin_data_model->getSMSConsuptions();
                $return_array = $this->paging($consumption_logs, $index, $records_per_page);
                $table['consumption_logs'] = $return_array['return_array'];
            } elseif ($subtab == 4) {
                // tr sms consumption
                $data['tr_consumption_logs'] = $this->admin_data_model->getSMSConsumptionsTR();
            } elseif ($subtab == 5) {
                // tr sms consumption
                $data['pr_consumption_logs'] = $this->admin_data_model->getSMSConsumptionsPR();
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Logs";
            $paging['admin_id'] = $admin_id;
            if ($subtab == 1 || $subtab == 2) {
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            if ($subtab == 3) {
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                $table['subtab'] = $subtab;
                $data['table'] = $this->load->view('admin/show-sms-consumptions', $table, true);
            }
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-logs-tab', $data);
        }
    }

    // Paging Logs
    public function paging_logs($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $index = ($page * $records_per_page) - 100;
            if ($subtab == 1) {
                // Transaction Logs
                if ($admin_id == 1 || $admin_id == 6) {
                    $total_logs = $this->admin_data_model->countAdminTransactions();
                    $logs = $this->admin_data_model->getAdminTransactions($index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['transactions1'] = $return_array['return_array'];
                } else {
                    $total_logs = $this->admin_data_model->countTransactions($admin_id);
                    $logs = $this->admin_data_model->getTransactions($admin_id, $index, $records_per_page);
                    $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                    $data['transactions'] = $return_array['return_array'];
                }
            } elseif ($subtab == 2) {
                // Error Logs
                $total_logs = $this->admin_data_model->countSMSLogs();
                $logs = $this->admin_data_model->getSMSLogs($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['sms_logs'] = $return_array['return_array'];
            } elseif ($subtab == 3) {
                // SMS Consumption Logs
                $consumption_logs = $this->admin_data_model->getSMSConsuptions();
                $return_array = $this->paging($consumption_logs, $index, $records_per_page);
                $table['consumption_logs'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Logs";
            $paging['admin_id'] = $admin_id;
            // Paging View
            if ($subtab == 1 || $subtab == 2) {
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            if ($subtab == 3) {
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                $table['subtab'] = $subtab;
                $data['table'] = $this->load->view('admin/show-sms-consumptions', $table, true);
            }
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-logs-tab', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Filter SMS Consumptions By Username
    public function filter_sms_consumptions() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = 3;
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // SMS Consumption Logs
            $consumption_logs = $this->admin_data_model->filterSMSConsuptions();
            $return_array = $this->paging($consumption_logs, $index, $records_per_page);
            $data['consumption_logs'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 3;
            $paging['function'] = "Logs";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-sms-consumptions', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Cron Job Functions For Server
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Disable Users After Expiry Date
    public function disable_on_expiry() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->disableOnExpiry();
    }

    // Update User Status For Online Status
    public function update_online_status() {
        $last_seen = strtotime(date("d-m-Y h:i:s", strtotime("-30 minutes")));
        $this->utility_model->updateOnlineStatus($last_seen);
    }

    // Check User Balance and Notify When Balance has been below 1000 By SMS
    public function check_user_balance() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->utility_model->checkUserBalance();
    }

    // Save User Balance On Daily Basis By Cron JOB
    public function save_user_balance() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->admin_data_model->saveUserBalance();
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Retry PR & TR SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Resend/Retry Submit SMS (Promotional)
    public function retry_pr_message() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->retryPRMessages('Promotional', 'A');
    }

    // Resend/Retry Submit SMS (Transactional)
    public function retry_tr_message() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->retryTRMessages('Transactional', 'B');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Admin Logout
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Admin Logout
    public function logout() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        session_start();
        $this->session->unset_userdata('admin_logged_in');
        $this->session->unset_userdata('pin_logged_in');
        session_unset();
        session_destroy();
        redirect('admin', 'refresh');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Virtual Numbers Like Short & Long Codes
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Short/Long Codes
    public function virtual_numbers() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSLKeywords('short');
            $logs = $this->admin_data_model->getSLKeywords('short', $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $table['keywords'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Logs";
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Sub Tab View
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-virtual-tab', $table, true);
            // Main View
            $data['page'] = "virtual_numbers";
            $data['subtab'] = "1";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/virtual-numbers');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Short/Long Codes
    public function get_virtual_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Short Number Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countSLKeywords('short');
                $logs = $this->admin_data_model->getSLKeywords('short', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Long Number Keywords
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countSLKeywords('long');
                $logs = $this->admin_data_model->getSLKeywords('long', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Short Numbers
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countSLNumbers('short');
                $logs = $this->admin_data_model->getSLNumbers('short', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            // Long Numbers
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countSLNumbers('long');
                $logs = $this->admin_data_model->getSLNumbers('long', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "VirtualNumbers";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-virtual-tab', $data);
        }
    }

    // Paging Short/Long Codes
    public function paging_virtual($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            // Short Number Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countSLKeywords('short');
                $logs = $this->admin_data_model->getSLKeywords('short', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Long Number Keywords            
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countSLKeywords('long');
                $logs = $this->admin_data_model->getSLKeywords('long', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Short Numbers            
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countSLNumbers('short');
                $logs = $this->admin_data_model->getSLNumbers('short', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            // Long Numbers            
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countSLNumbers('long');
                $logs = $this->admin_data_model->getSLNumbers('long', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "VirtualNumbers";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-virtual-tab', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Approve/Disapprove Short/Long Keyword/Number
    public function sl_status($id = 0, $status = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->statusSL($id, $status, $subtab);
            if ($subtab == 1 || $subtab == 2) {
                if ($status) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = ' Keyword approved successfully!';
                } else {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = ' Keyword disapproved successfully!';
                }
            }
            if ($subtab == 3 || $subtab == 4) {
                if ($status) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = ' Number activated successfully!';
                } else {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = ' Number deactivated successfully!';
                }
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Short Number Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countSLKeywords('short');
                $logs = $this->admin_data_model->getSLKeywords('short', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Long Number Keywords            
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countSLKeywords('long');
                $logs = $this->admin_data_model->getSLKeywords('long', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Short Numbers            
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countSLNumbers('short');
                $logs = $this->admin_data_model->getSLNumbers('short', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            // Long Numbers            
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countSLNumbers('long');
                $logs = $this->admin_data_model->getSLNumbers('long', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "VirtualNumbers";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-virtual-tab', $data);
        }
    }

    // Save Short/Long Number
    public function save_sl_number($type = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveSLNumber($type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Number inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Number insertion failed!';
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Short Numbers
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countSLNumbers('short');
                $logs = $this->admin_data_model->getSLNumbers('short', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            // Long Numbers
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countSLNumbers('long');
                $logs = $this->admin_data_model->getSLNumbers('long', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "VirtualNumbers";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-virtual-tab', $data);
        }
    }

    // Delete Short/Long Keyword/Number
    public function delete_sl_data($id = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteSLData($id, $subtab);
            if ($subtab == 1 || $subtab == 2) {
                $message_type = 'Keyword';
            }
            if ($subtab == 3 || $subtab == 4) {
                $message_type = 'Number';
            }
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = '' . $message_type . ' deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = '' . $message_type . ' deletion failed!';
            }
            // Paignation
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            // Short Number Keywords
            if ($subtab == 1) {
                $total_logs = $this->admin_data_model->countSLKeywords('short');
                $logs = $this->admin_data_model->getSLKeywords('short', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Long Number Keywords
            if ($subtab == 2) {
                $total_logs = $this->admin_data_model->countSLKeywords('long');
                $logs = $this->admin_data_model->getSLKeywords('long', $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['keywords'] = $return_array['return_array'];
            }
            // Short Numbers
            if ($subtab == 3) {
                $total_logs = $this->admin_data_model->countSLNumbers('short');
                $logs = $this->admin_data_model->getSLNumbers('short', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            // Long Numbers
            if ($subtab == 4) {
                $total_logs = $this->admin_data_model->countSLNumbers('long');
                $logs = $this->admin_data_model->getSLNumbers('long', 0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['numbers'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "VirtualNumbers";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-virtual-tab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Account Manager
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Account Managers
    public function account_managers() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('6', $permissions_array)) {
                $admin_id = $session_data['admin_id'];
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countAccountManagers();
                $logs = $this->admin_data_model->getAccountManagers(1, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $table['account_managers'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "Account";
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Table View
                $table['atype'] = $session_data['atype'];
                $table['admin_id'] = $session_data['admin_id'];
                $table['subtab'] = 1;
                $data['table'] = $this->load->view('admin/get-accounts-tab', $table, true);
                // Main View
                $data['subtab'] = 1;
                $data['page'] = "account_managers";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/account-managers');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Account Managers Tab
    public function get_account_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = $subtab;
            // Account Managers
            if ($subtab == 1) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countAccountManagers();
                $logs = $this->admin_data_model->getAccountManagers(0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['account_managers'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['function'] = "Account";
                $data['admin_id'] = $session_data['admin_id'];
                $data['atype'] = $session_data['atype'];
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            // Transfer Balance To Account Managers
            if ($subtab == 3) {
                $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            }
            // Reset Password of Account Managers
            if ($subtab == 4) {
                $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            }
            // Get Permissions For Account Manager
            if ($subtab == 5) {
                $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            }
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/get-accounts-tab', $data);
        }
    }

    // Paging Account Managers
    public function paging_accounts($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            // Pagination
            $index = ($page * $records_per_page) - 10;
            $total_logs = $this->admin_data_model->countAccountManagers();
            $logs = $this->admin_data_model->getAccountManagers(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['account_managers'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "Account";
            $paging['admin_id'] = $session_data['admin_id'];
            $paging['atype'] = $session_data['atype'];
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $data['subtab'] = $subtab;
            $data['type'] = 'admin';
            $this->load->view('admin/show-users', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save Account Manager
    public function save_account_manager($id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $admin_email = $session_data['admin_email'];
            $response = $this->admin_data_model->saveAccountManager($admin_id, $admin_email, $id);
            if ($response == 200 || $response == 201) {
                if ($response == 200) {
                    $data['msg_data'] = ' Congratulations! Account Manager created successfully!';
                } elseif ($response == 201) {
                    $data['msg_data'] = ' Congratulations! Account Manager info updated successfully!';
                }
                $data['msg_type'] = '1';
                $data['subtab'] = 1;
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countAccountManagers();
                $logs = $this->admin_data_model->getAccountManagers(0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['account_managers'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "Account";
                $paging['admin_id'] = $session_data['admin_id'];
                $paging['atype'] = $session_data['atype'];
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
                $this->load->view('admin/get-accounts-tab', $data);
            } elseif ($response == 100 || $response == 101) {
                if ($response == 100) {
                    $data['msg_data'] = ' Account Manager creation failed! Please try again!';
                }
                if ($response == 101) {
                    $data['msg_data'] = ' Username not available! Please try another!';
                }
                $data['subtab'] = 2;
                $data['msg_type'] = '0';
                $this->load->view('admin/get-accounts-tab', $data);
            } else {
                $data['subtab'] = 2;
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
                $this->load->view('admin/get-accounts-tab', $data);
            }
        }
    }

    // Delete Account Manager
    public function delete_account_manager($id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = 1;
            $response = $this->admin_data_model->deleteAManager($id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Account Manager deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Account Manager deletion failed!';
            }
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countAccountManagers();
            $logs = $this->admin_data_model->getAccountManagers(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['account_managers'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Account";
            $paging['admin_id'] = $session_data['admin_id'];
            $paging['atype'] = $session_data['atype'];
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['type'] = 'admin';
            $this->load->view('admin/show-users', $data);
        }
    }

    // Enable/Disable Account Manager
    public function change_amanager_status($id = 0, $status = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = 1;
            $response = $this->admin_data_model->changeAMStatus($id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Account Manager status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Account Manager status changing failed!';
            }
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countAccountManagers();
            $logs = $this->admin_data_model->getAccountManagers(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['account_managers'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Account";
            $paging['admin_id'] = $session_data['admin_id'];
            $paging['atype'] = $session_data['atype'];
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['type'] = 'admin';
            $this->load->view('admin/show-users', $data);
        }
    }

    // Update Account Manager
    public function update_account_manager($id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['account_manager'] = $this->admin_data_model->getAccountManager($id);
            $data['subtab'] = 2;
            $data['admin_id'] = $id;
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/get-accounts-tab', $data);
        }
    }

    // Transfer Balance
    public function transfer_balance() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $admin_sms = $this->admin_data_model->getAdminBalance($admin_id);
            $temp_array = array(
                'total_pr_balance' => $admin_sms['total_pr_balance'],
                'total_tr_balance' => $admin_sms['total_tr_balance'],
                'total_prtodnd_balance' => $admin_sms['total_prtodnd_balance'],
                'total_stock_balance' => $admin_sms['total_stock_balance'],
                'total_lcode_balance' => $admin_sms['total_lcode_balance'],
                'total_scode_balance' => $admin_sms['total_scode_balance'],
                'total_vpr_balance' => $admin_sms['total_vpr_balance'],
                'total_vtr_balance' => $admin_sms['total_vtr_balance'],
                'total_mcall_balance' => $admin_sms['total_mcall_balance'],
                'admin_pr_credits' => $admin_sms['admin_pr_credits'],
                'admin_tr_credits' => $admin_sms['admin_tr_credits'],
                'total_prtodnd_credits' => $admin_sms['total_prtodnd_credits'],
                'total_stock_credits' => $admin_sms['total_stock_credits'],
                'international_sms' => $admin_sms['international_sms']
            );
            $response = $this->admin_data_model->transferBalance($admin_id, $temp_array);
            if ($response) {
                $data['msg_data'] = ' Congratulations! Balance transfered successfully!';
                $data['msg_type'] = '1';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
            }
            $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            $data['subtab'] = 3;
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/get-accounts-tab', $data);
        }
    }

    // Reset Account Manager Password
    public function reset_am_password() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $response = $this->admin_data_model->resetAccMPassword();
            if ($response) {
                $data['msg_data'] = ' Password reseted successfully!';
                $data['msg_type'] = '1';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
            }
            $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            $data['subtab'] = 4;
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/get-accounts-tab', $data);
        }
    }

    // Get Permissions For Account Manager
    public function get_am_permissions($id) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['allocated_permissions'] = $this->admin_data_model->getAccountManager($id);
            $this->load->view('admin/show-am-permissions', $data);
        }
    }

    // Set Permissions For Account Manager
    public function set_am_permissions() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);

            $session_data = $this->session->userdata('admin_logged_in');
            $response = $this->admin_data_model->setAMPermissions();
            if ($response) {
                $data['msg_data'] = ' Permissions set successfully!';
                $data['msg_type'] = '1';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Something wrong! Please try again later!';
            }
            $data['account_managers'] = $this->admin_data_model->getAccountManagers(1);
            $data['subtab'] = 5;
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/get-accounts-tab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    //User Balance Management
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Users Balance
    public function users_balance() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('9', $permissions_array)) {
                $admin_id = $session_data['admin_id'];
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $balance_logs = $this->admin_data_model->getBalanceLogs('1');
                $return_array = $this->paging($balance_logs, $index, $records_per_page);
                $table['balance_logs'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "UsersBalance";
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Sub Tab View
                $table['subtab'] = 1;
                $data['table'] = $this->load->view('admin/show-users-balance-tab', $table, true);
                // Main View
                $data['subtab'] = 1;
                $data['page'] = "users_balance";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/users-balance');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Users Balance Tabs
    public function get_users_balance_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            if ($subtab == 1 || $subtab == 2) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                if ($subtab == 1) {
                    $balance_logs = $this->admin_data_model->getBalanceLogs('1');
                    $return_array = $this->paging($balance_logs, $index, $records_per_page);
                    $data['balance_logs'] = $return_array['return_array'];
                } elseif ($subtab == 2) {
                    $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
                    $users_balance = $this->admin_data_model->getUsersBalance();
                    $return_array = $this->paging($users_balance, $index, $records_per_page);
                    $data['users_balance'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['function'] = "UsersBalance";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            } elseif ($subtab == 3) {
                $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
            }
            $this->load->view('admin/show-users-balance-tab', $data);
        }
    }

    // Paging Users Balance
    public function paging_users_balance($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            if ($subtab == 1) {
                $balance_logs = $this->admin_data_model->getBalanceLogs('1');
                $return_array = $this->paging($balance_logs, $index, $records_per_page);
                $data['balance_logs'] = $return_array['return_array'];
            } elseif ($subtab == 2) {
                $users_balance = $this->admin_data_model->getUsersBalance();
                $return_array = $this->paging($users_balance, $index, $records_per_page);
                $data['users_balance'] = $return_array['return_array'];
            }
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "UsersBalance";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-users-balance', $data);
        }
    }

    // Filter Users Balance By Date
    public function filter_users_balance($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            if ($subtab == 1 || $subtab == 2) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                if ($subtab == 1) {
                    $balance_logs = $this->admin_data_model->filterBalanceLogs();
                    $return_array = $this->paging($balance_logs, $index, $records_per_page);
                    $data['balance_logs'] = $return_array['return_array'];
                } elseif ($subtab == 2) {
                    $users_balance = $this->admin_data_model->getUsersBalance();
                    $return_array = $this->paging($users_balance, $index, $records_per_page);
                    $data['users_balance'] = $return_array['return_array'];
                }
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['function'] = "UsersBalance";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            } elseif ($subtab == 3) {
                $data['users_balance'] = $this->admin_data_model->analyzeUsersBalance();
            }
            $this->load->view('admin/show-users-balance', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Chat Support
    public function chat_support() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->model('Data_Model', 'data_model');
            $data['page'] = "chat_support";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/chat-support');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Admin-Users Chat Message
    public function admin_chat_messages($user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->model('Data_Model', 'data_model');
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['admin_id'] = $admin_id;
            $data['user_id'] = $user_id;
            $data['chat_messages'] = $this->data_model->getChatMessages(false, 'DESC', $user_id, $admin_id);
            $this->load->view('admin/admin-chat-box', $data);
        }
    }

    // Admin Chat Submit To The Server
    public function chat_submit() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->model('Data_Model', 'data_model');
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $user_id = $this->input->post('user_id');
            $array['admin_id'] = $admin_id;
            $array['chat_message'] = $this->input->post('chat_message');
            $array['user_id'] = $user_id;
            // Insert Into DB
            $this->data_model->insertChatMessage($array);
            // Get Last Chat Message
            $last_message = $this->data_model->getChatMessages(true, null, $user_id, $admin_id);
            if ($last_message) {
                $array['chat_user'] = $last_message->user_id;
                $array['chat_admin'] = $last_message->admin_id;
                $array['chat_datetime'] = $last_message->chat_datetime;
                $array['chat_id'] = $last_message->chat_id;
                $array['success'] = true;
            }
            echo json_encode($array);
            die;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Check Message Status and Set Backup Routes To All Users
    //------------------------------------------------------------------------------------------------------------------------------------------//
    public function backup_route() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->admin_data_model->checkAndSetBackupRoute();
    }

    // Alternate Admin Login Page
    public function index1() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $domain_name = $_SERVER['SERVER_NAME'];
        if ($domain_name == 'sms.bulksmsserviceproviders.com' || $domain_name == 'localhost' || $domain_name == '192.168.1.231') {
            if ($this->session->userdata('admin_logged_in')) {
                redirect('admin/spam_transactional', 'refresh');
            } else {
                $this->load->helper('captcha');
                // numeric random number for captcha
                $random_number = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
                // setting up captcha config
                $vals = array(
                    'word' => $random_number,
                    'img_path' => './captcha/',
                    'img_url' => base_url() . 'captcha/',
                    'img_width' => 140,
                    'img_height' => 32,
                    'expiration' => 7200
                );
                $data['captcha'] = create_captcha($vals);
                $this->session->set_userdata('captcha_word', $data['captcha']['word']);
                $this->load->view('admin/index1', $data);
            }
        } else {
            redirect('http://' . $domain_name, 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Unauthorized Access
    //------------------------------------------------------------------------------------------------------------------------------------------//
    public function unauthorized_access() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $this->load->view('admin/header');
            $this->load->view('admin/unauthorized-access');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMS Rate Plans Manager
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMS Rate Plans
    public function sms_rates() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('5', $permissions_array)) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countSMSRatePlans();
                $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $table['sms_rates'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "SMSRate";
                // Paging View
                $table['paging'] = $this->load->view('admin/paging', $paging, true);
                // Table View
                $table['atype'] = $session_data['atype'];
                $table['admin_id'] = $session_data['admin_id'];
                $table['subtab'] = 1;
                $data['table'] = $this->load->view('admin/show-sms-rates-tab', $table, true);
                // Main View
                $data['subtab'] = 1;
                $data['page'] = "sms_rates";
                $this->load->view('admin/header', $data);
                $this->load->view('admin/sms-rate-plans');
                $this->load->view('admin/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get SMS Rate Plans Tab 
    public function get_sms_rates_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = $subtab;
            // Account Managers
            if ($subtab == 1) {
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countSMSRatePlans();
                $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['sms_rates'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = $subtab;
                $paging['function'] = "SMSRate";
                $data['admin_id'] = $session_data['admin_id'];
                $data['atype'] = $session_data['atype'];
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
            }
            $data['atype'] = $session_data['atype'];
            $this->load->view('admin/show-sms-rates-tab', $data);
        }
    }

    // Paging SMS Rate Plans
    public function paging_sms_rates($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            // Pagination
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSMSRatePlans();
            $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['sms_rates'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "SMSRate";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            // Main View
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-sms-rates-tab', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save SMS Rate Plan
    public function save_sms_rate($rate_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $response = $this->admin_data_model->saveSMSRate($rate_id);
            if ($response == 200 || $response == 201) {
                if ($response == 200) {
                    $data['msg_data'] = ' SMS Rate Plan created successfully!';
                } elseif ($response == 201) {
                    $data['msg_data'] = 'SMS Rate Plan updated successfully!';
                }
                $data['msg_type'] = '1';
                $data['subtab'] = 1;
                // Pagination
                $page = 1;
                $records_per_page = 100;
                $index = ($page * $records_per_page) - 100;
                $total_logs = $this->admin_data_model->countSMSRatePlans();
                $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['sms_rates'] = $return_array['return_array'];
                $paging['total_pages'] = $return_array['total_pages'];
                $paging['records_data'] = $return_array['records_data'];
                $paging['page_no'] = $page;
                $paging['subtab'] = 1;
                $paging['function'] = "SMSRate";
                // Paging View
                $data['paging'] = $this->load->view('admin/paging', $paging, true);
                $this->load->view('admin/show-sms-rates-tab', $data);
            } else {
                $data['subtab'] = 2;
                $data['msg_type'] = '0';
                $data['msg_data'] = ' SMS Rate Plan creation failed! Please try again!';
                $this->load->view('admin/show-sms-rates-tab', $data);
            }
        }
    }

    // Delete SMS Rate Plan
    public function delete_sms_rate($id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['subtab'] = 1;
            $response = $this->admin_data_model->deleteSMSRate($id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' SMS Rate deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' SMS Rate deletion failed!';
            }
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSMSRatePlans();
            $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['sms_rates'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "SMSRate";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['type'] = 'admin';
            $this->load->view('admin/show-sms-rates-tab', $data);
        }
    }

    // Enable/Disable SMS Rate Plan
    public function change_sms_rate_status($id = 0, $status = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = 1;
            $response = $this->admin_data_model->changeSMSRateStatus($id, $status);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' SMS Rate status changed successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' SMS Rate status changing failed!';
            }
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countSMSRatePlans();
            $logs = $this->admin_data_model->getSMSRatePlans(0, $index, $records_per_page);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $data['sms_rates'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "SMSRate";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-sms-rates-tab', $data);
        }
    }

    // Update SMS Rate Plan
    public function update_sms_rates($id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['sms_rate'] = $this->admin_data_model->getSMSRatePlan($id);
            $data['subtab'] = 2;
            $this->load->view('admin/show-sms-rates-tab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Cron Jobs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Delete Old Data From sqlbox_sent_sms Table
    public function delete_sqlbox_sms() {
        $this->admin_data_model->deleteSQLBoxSMS();
    }

    // Check New Voice SMS Request And Process
    public function check_voice_sms() {
        $this->admin_data_model->checkVoiceSMS();
    }

    // Update Fake Failed And Fake Delivered SMS
    public function update_fake_sms() {
        $this->admin_data_model->updateFakeSMS();
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts Services
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts
    public function missed_call_alerts() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts(1);
            $missed_call = $this->admin_data_model->getMissedCallAlerts(1, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $table['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "MissedCall";
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Sub Tab View
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-missed-call-tab', $table, true);
            // Main View
            $data['page'] = "missed_call_alerts";
            $data['subtab'] = "1";
            $this->load->view('admin/header', $data);
            $this->load->view('admin/missed-call-alerts');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Get Missed Call Alerts Tab
    public function get_missed_call_tab($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['subtab'] = $subtab;
            if ($subtab == 2) {
                $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
                $data['service_numbers'] = $this->admin_data_model->getMissedCallAlerts(1, 1, 0, 0);
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts($subtab);
            $missed_call = $this->admin_data_model->getMissedCallAlerts($subtab, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $data['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "MissedCall";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $this->load->view('admin/show-missed-call-tab', $data);
        }
    }

    // Paging Missed Call Alerts
    public function paging_missed_call($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            // Pagination
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts($subtab);
            $missed_call = $this->admin_data_model->getMissedCallAlerts($subtab, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $data['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "MissedCall";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-missed-call-tab', $data);
        }
    }

    // Save Missed Call Alerts
    public function save_missed_call($subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->saveMissedCallAlerts($subtab);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Number inserted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = ' Number insertion failed!';
            }
            if ($subtab == 2) {
                $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
                $data['service_numbers'] = $this->admin_data_model->getMissedCallAlerts(1, 1, 0, 0);
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts($subtab);
            $missed_call = $this->admin_data_model->getMissedCallAlerts($subtab, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $data['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "MissedCall";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-missed-call-tab', $data);
        }
    }

    // Change Status Missed Call Alerts
    public function status_missed_call($id = 0, $status = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->statusMissedCallAlerts($id, $status, $subtab);
            if ($status) {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Number activated successfully!';
            } else {
                $data['msg_type'] = '1';
                $data['msg_data'] = ' Number deactivated successfully!';
            }
            if ($subtab == 2) {
                $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
                $data['service_numbers'] = $this->admin_data_model->getMissedCallAlerts(1, 1, 0, 0);
            }
            // Pagination
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts($subtab);
            $missed_call = $this->admin_data_model->getMissedCallAlerts($subtab, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $data['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "MissedCall";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-missed-call-tab', $data);
        }
    }

    // Delete Missed Call Alerts
    public function delete_missed_call($id = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $response = $this->admin_data_model->deleteMissedCallAlerts($id, $subtab);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = 'Number deleted successfully!';
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = 'Number deletion failed!';
            }
            if ($subtab == 2) {
                $data['users'] = $this->admin_data_model->getUsers(0, 0, 0);
                $data['service_numbers'] = $this->admin_data_model->getMissedCallAlerts(1, 1, 0, 0);
            }
            // Paignation
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_missed_call = $this->admin_data_model->countMissedCallAlerts($subtab);
            $missed_call = $this->admin_data_model->getMissedCallAlerts($subtab, 0, $index, $records_per_page);
            $return_array = $this->paging_table($missed_call, $index, $records_per_page, $total_missed_call);
            $data['missed_call'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = $subtab;
            $paging['function'] = "MissedCall";
            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;
            $this->load->view('admin/show-missed-call-tab', $data);
        }
    }

    //Login admin for show smsc details.
    public function smsc_validation_login() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->load->view('admin/smsc_detail');
    }

    //Check admin for show smsc details.
    public function check_admin_smsc_details() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->checkAdminSmscDetails();

        if ($response) {
            $this->session->set_flashdata('abc', 'Login success');
            redirect("admin/show_smsc_name");
        } else {
            $this->session->set_flashdata('abc', 'Login failed');
            redirect("admin/smsc_validation_login");
        }
    }

    public function show_smsc_name() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $data['smsc'] = $this->admin_data_model->getSmscName();
        $this->load->view('admin/show_smsc_name', $data);
    }

    public function get_date($smsc_id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $data['smsc'] = $smsc_id;
        $this->load->view('admin/get_sms_date', $data);
    }

    public function full_smsc_details() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);

        $data['full_details'] = $this->admin_data_model->showSmscDetails();
        $this->load->view('admin/full_smsc_details', $data);
    }

//Cron jobs 
// send message from another route

    public function retry_schduling() {
        $this->admin_data_model->retrySchduling();
    }

    ///trail
    public function search() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['employees'] = $this->admin_data_model->getemp();
            //   var_dump($data['employees']);
            /// $this->load->view('admin/header');
            $this->load->view('admin/search', $data);
            //    $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

//searcjh keyword

    public function search_users_keywords() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $page = 1;
            $records_per_page = 100;
            $index = ($page * $records_per_page) - 100;
            $total_logs = $this->admin_data_model->countKeywords('1', true);
            $logs = $this->admin_data_model->getKeywords('1', $index, $records_per_page, true);
            $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
            $table['approve_keywords'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "Keywords";
            $data['username'] = $username;
            $data['search_users'] = $this->admin_data_model->searchUsersKetwords($username);
            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            // Table View
            $table['subtab'] = 1;
            $data['table'] = $this->load->view('admin/show-keyword-tab', $table, true);


            //  $this->load->view('admin/search-users', $data);
        }
    }

    //search keywords    
    public function search_users_keyword() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);

            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $username = $myArray[0];
            $data['username'] = $username;
            $data['approve_keywords'] = $this->admin_data_model->searchUsersKeyword($username);

            $this->load->view('admin/show-keyword-tab', $data);
        }
    }

////for sells tracker
    public function selles_tracker($campaign_id = 0, $route = null, $user_id = 0) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_id'] = $user_id;
            $data['route'] = $route;
            $data['admin_names'] = $this->admin_data_model->getAdminName();
            $data['transection_logs'] = $this->admin_data_model->getTrnsectionLogs();

            $data['subtab'] = 1;
            $data['page'] = "sells_tracker";
            $data['campaign'] = $campaign_id;
            $this->load->view('admin/header', $data);
            $this->load->view('admin/sells-tracker');
            $this->load->view('admin/footer');
        }
    }

    //get seller reeports
    public function get_seller_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['user_id'] = $user_id;
            $data['route'] = $route;
            $data['admin_names'] = $this->admin_data_model->getAdminName();
            $data['transection_logs'] = $this->admin_data_model->getTrnsectionLogs();

            $data['subtab'] = 1;
            $data['page'] = "sells_tracker";
            $data['campaign'] = $campaign_id;
            $data['seller_reports'] = $this->admin_data_model->getSellerReport();
            $data['special_seller_reports'] = $this->admin_data_model->getSpecialSellerReport();

            $this->load->view('admin/get_seller_report', $data);
        }
    }

    // //get signup data with filter 
    public function get_signup_reports() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);


            $data['users_report'] = $this->admin_data_model->getSignUpReport();
            $data['admin'] = $this->admin_data_model->getAdmin();
            $data['subtab'] = 8;


            $this->load->view('admin/get-users-tab', $data);
        }
    }

    //get sms report 
    public function get_sms_report() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];

            $data['sms_report'] = $this->admin_data_model->getSmsReport();
            // var_dump($data['sms_report']);die;
            $data['subtab'] = 9;
            $this->load->view('admin/show-users-record', $data);
        }
    }

//get transication details for aproval
    public function payment_aproval() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $page = 1;
        $records_per_page = 200;
        $index = ($page * $records_per_page) - 200;
        $payment_log = $this->admin_data_model->paymentAproval();

        $return_array = $this->paging($payment_log, $index, $records_per_page);

        $table['transation_log'] = $return_array['return_array'];

        // var_dump($table['table_data']);die;
        $paging['total_pages'] = $return_array['total_pages'];
        $paging['records_data'] = $return_array['records_data'];
        $paging['page_no'] = $page;
        $paging['subtab'] = 1;
        $paging['function'] = "Payment";
        // Paging View
        $table['paging'] = $this->load->view('admin/paging', $paging, true);
        $data['table'] = $this->load->view('admin/show-payment-logs', $table, true);

        $data['page'] = "payment_aproval";
        $this->load->view('admin/header', $data);
        $this->load->view('admin/payment_aproval');
        $this->load->view('admin/footer');

        /*  $table['transation_log'] = $this->admin_data_model->paymentAproval();

          $data['table'] = $this->load->view('admin/show-payment-logs', $table, true);

          // Main View
          $data['subtab'] = "1";
          $data['page'] = "payment_aproval";
          $this->load->view('admin/header', $data);
          $this->load->view('admin/payment_aproval');
          $this->load->view('admin/footer'); */
    }

    //daily sign-up
    public function daily_signup() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['daily_signup'] = $this->admin_data_model->dailySignupLogs();
            $data['admin_name'] = $this->admin_data_model->adminNameForLead();

            $this->load->view('admin/header', $data);
            $this->load->view('admin/daily-signup');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    //upadte daily feed back
    public function update_feedback($user_id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);

        $responce = $this->admin_data_model->updateFeedback($user_id);
        if ($responce) {
            redirect('admin/daily_signup', 'refresh');
        } else {
            redirect('admin/daily_signup', 'refresh');
        }
    }

    //search vodafone approve sender id
    public function search_sender_ids() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $keyword = $myArray[0];
        $data['username'] = $keyword;
        $data['table_data'] = $this->admin_data_model->searchApproveSenderIds($keyword);
        $this->load->view('admin/search-vodafone-sender-id', $data);
    }

    //search payment aproval by date
    public function search_aproval_payment() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response['transation_log'] = $this->admin_data_model->searchAprovalPayment();
        //$this->load->view('admin/header', $response);
        $this->load->view('admin/aproval-payment-details', $response);
        // $this->load->view('admin/footer');
    }

    //search not approve sender id by date
    public function search_not_aproval_sender_id() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response['result'] = $this->admin_data_model->searchNotAprovalSenderId();
        //$this->load->view('admin/header', $response);
        $this->load->view('admin/not-approve-senderid', $response);
        // $this->load->view('admin/footer');
    }

    // payment aproval 
    public function payment_aprova_update($txn_log_id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->paymentAprovalUpdate($txn_log_id);
        if ($response) {
            redirect("admin/payment_aproval");
        } else {
            redirect("admin/payment_aproval");
        }
    }

    // payment status update
    public function update_payment_status() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->updatePaymentStatus();
        if ($response) {
            $data['msg_type'] = '1';
            $data['msg_data'] = 'Payment approved successfully!';
        } else {
            $data['msg_type'] = '0';
            $data['msg_data'] = 'Process Error!';
        }
        $subtab = 1;
        $page = 1;
        $records_per_page = 200;
        $index = ($page * $records_per_page) - 200;
        $payment_log = $this->admin_data_model->paymentAproval();
        $return_array = $this->paging($payment_log, $index, $records_per_page);
        $data['transation_log'] = $return_array['return_array'];
        $paging['total_pages'] = $return_array['total_pages'];
        $paging['records_data'] = $return_array['records_data'];
        $paging['page_no'] = $page;
        $paging['subtab'] = $subtab;
        $paging['function'] = "Payment";
        // Paging View
        $data['paging'] = $this->load->view('admin/paging', $paging, true);
        $data['subtab'] = $subtab;
        $this->load->view('admin/show-payment-logs', $data);

        /* $data['page'] = "payment_approval";
          $data['transation_log'] = $this->admin_data_model->paymentAproval();
          $this->load->view('admin/show-payment-logs', $data); */
    }

    //payment disaproval 
    public function payment_disaprova($txn_log_id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->paymentDisAproval($txn_log_id);
        if ($response) {
            redirect("admin/payment_aproval");
        } else {
            redirect("admin/payment_aproval");
        }
    }

//get unique
    public function get_unique() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        if ($this->session->userdata('admin_logged_in')) {
            $data = $this->admin_data_model->getUniqueSenderIds();
            var_dump($data);
            die;
            //    $this->load->view('admin/get_seller_report', $data);
        }
    }

    public function search_payment_reports($search = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('admin_logged_in');
            $data['admin_id'] = $session_data['admin_id'];
            $response['transation_log'] = $this->admin_data_model->paymentAproval();
            $this->load->view('admin/header', $response);
            $this->load->view('admin/payment_aproval');
            $this->load->view('admin/footer');
        } else {
            $data['transation_log'] = $this->admin_data_model->searchPaymentReports($search);
            $this->load->view('admin/payment_aproval', $data);
        }
    }

    //show payment status in subadmin portal
    public function show_payment_subadmin() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];
        $page = 1;
        $records_per_page = 100;
        $index = ($page * $records_per_page) - 100;
        $payment_log = $this->admin_data_model->showPaymentDetails($admin_id);

        $return_array = $this->paging($payment_log, $index, $records_per_page);

        $table['transation_log'] = $return_array['return_array'];

        // var_dump($table['table_data']);die;
        $paging['total_pages'] = $return_array['total_pages'];
        $paging['records_data'] = $return_array['records_data'];
        $paging['page_no'] = $page;
        $paging['subtab'] = 1;
        $paging['function'] = "PaymentSubadmin";
        // Paging View
        $table['paging'] = $this->load->view('admin/paging', $paging, true);
        $data['table'] = $this->load->view('admin/show-payment-subadmin', $table, true);

        $data['page'] = "show-subadmin-approve";
        $this->load->view('admin/header', $data);
        $this->load->view('admin/show-subadmin-approve');
        $this->load->view('admin/footer');
    }

    //search sub-admin payment by date
    public function search_payment_by_date() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];
        $data['show_payment_details'] = $this->admin_data_model->searchPaymentByDate($admin_id);

        // $this->load->view('admin/header',$data);
        $this->load->view('admin/search-payment-aproval', $data);
        //  $this->load->view('admin/footer');
    }

    //get old sms price for credit or re-credits
    public function get_old_price($user_id, $type, $balance_type) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $data = $this->admin_data_model->getOldPrice($user_id, $type, $balance_type);
        $array = array_values($data);
        echo $value = $array[0];
        die;
    }

    public function resend_voice_call() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $user_id = 526;
        $result_user = $this->sms_model->getUserSettingsTest($user_id);

        // Campaign Id
        $campaign_id = 2333595; //$this->input->post('resend_campaign_id');
        //action
        $action_type = 2; //$this->input->post('resend_action_type');
        // Routing
        $route = "B"; //$this->input->post('resend_route');

        $campaign_name = "Resend";

        $campaign_uid = strtolower(random_string('alnum', 24));


        if ($result_user) {
            $this->db->select("*");
            $this->db->from('sent_sms');
            $this->db->where('campaign_id', $campaign_id);
            $this->db->where('status', $action_type);
            $query = $this->db->get();
            $sizeofsms = $query->num_rows();
            if ($query->num_rows()) {
                $this->db->select("*");
                $this->db->from('campaigns');
                $this->db->where('campaign_id', $campaign_id);
                $camp_query = $this->db->get();
                $camp_result = $camp_query->row();
                $total_messages = $sizeofsms;
                $total_credits = $camp_result->total_credits;
                $caller_id = $camp_result->caller_id;
                $message_length = $camp_result->message_length;
                $submit_date = date('Y-m-d H:i:s');
                $voice_file = $camp_result->message;

                $data_campaign = array(
                    'user_group_id' => 0,
                    'campaign_uid' => $campaign_uid,
                    'campaign_name' => $campaign_name,
                    'user_id' => $user_id,
                    'admin_id' => 1,
                    'total_messages' => $total_messages,
                    'total_credits' => $total_credits,
                    'caller_id' => $caller_id,
                    'submit_date' => $submit_date,
                    'message' => $voice_file,
                    'message_length' => $message_length,
                    'route' => $route,
                    'end_date_time' => $submit_date,
                    'service_type' => 'VOICE',
                    'request_by' => "By Panel"
                );


                $response_cm = $this->db->insert('campaigns', $data_campaign);
                if ($response_cm) {
                    $result_array = $query->result_array();
                    $campaign_id = $this->db->insert_id();
                    foreach ($result_array as $result_insert_array) {
                        $data = array(
                            'campaign_id' => $campaign_id,
                            'user_id' => $user_id,
                            'message' => $voice_file,
                            'msg_length' => $message_length,
                            'mobile_no' => $result_insert_array['mobile_no'],
                            'status' => 31,
                            'submit_date' => $submit_date,
                            'temporary_status' => 1
                        );
                    }
                    var_dump($data);
                    $sent_responce = $this->db->insert('sent_sms', $data);
                    if ($sent_responce) {
                        return TRUE;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //external form function filter for sender id
    public function filter_sender_id() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->load->view('admin/filter_senderid');
    }

    //external controller function filter for sender id
    public function upload_single_row() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $row;

        if (isset($_POST["import"])) {
            //$route = $_POST['user_group_id'];
            //    $read= readfile($_FILES['file']['tmp_name']) ;
            $filename = $_FILES["file"]["tmp_name"];
            $importdata = array();
            if ($_FILES["file"]["size"] > 0) {
                $file = fopen($filename, "r");
                while (!feof($file)) {
                    $importdata[] = fgetcsv($file);
                }
                fclose($file);
                $size = sizeof($importdata);

                for ($i = 0; $i < $size; $i++) {
                    $senderid = $importdata[$i][0];
                    $status = $importdata[$i][1];

                    $length = strlen($senderid);
                    if ($length == 6) {

                        $filterid = preg_replace('/[^A-Za-z\-]/', '', $senderid);

                        $validlength = strlen($filterid);
                        if ($validlength == 6) {

                            $insert_data = array(
                                'sender_id' => $senderid,
                                'status' => $status
                            );

                            $this->db->insert('filter_sender_id', $insert_data);
                        } else {
                            
                        }
                    }
                }
                echo "<a href='http://sms.bulksmsserviceproviders.com/get_senderid.php'>Download Approve Ids</a>";
                echo "<br><br><br>";
                echo "<a href='http://sms.bulksmsserviceproviders.com/get_not_approve_id.php'>Download Not Approve Ids</a>";
                echo "<br><br><br>";
                echo "<a href='http://sms.bulksmsserviceproviders.com/all_approve_id.php'>Download All SenderIds</a>";
            }
        }
    }

    //Genrate invoice for users 
    public function view_invoice($txn_id) {
        $responce['txn_data'] = $this->admin_data_model->viewInvoice($txn_id);
        $this->load->view('admin/view-invoice', $responce);
    }

    //mail invoice
    public function mail_invoice($id) {
        $this->db->select('email_address');
        $this->db->from('users');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        $to_email = $query->row('email_address');
        $email_ids = "$to_email,bulk24sms.vijendra@gmail.com";


        $body = $this->admin_data_model->mailInvoice();
        $mail_array = array(
            'from_email' => $to_email,
            'from_name' => 'Bulk SMS Service Providers',
            'to_email' => $email_ids,
            'subject' => 'Invoice',
            'message' => $body
        );
        if ($this->admin_data_model->sendInvoiceEmail($mail_array)) {
            echo "success";
        }
    }

    //backup route when any route if going to offline 
    public function emergency_routing() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $this->admin_data_model->emergencyRouting();
    }

    //push dlr from admin fake

    public function update_push_dlr($campaign_id, $route) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $data['campaign_id'] = $campaign_id;
        $data['sent_sms_status'] = $this->admin_data_model->getSentSMSStatus($campaign_id);
        $data['campaign_status'] = $this->admin_data_model->campaignProcessStatus($campaign_id);
        $this->load->view('admin/header', $data);
        $this->load->view('admin/push_dlr_update');
        $this->load->view('admin/footer');
    }

    public function fake_update_push_dlr() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->fakeUpdatePushDlr();

        if ($response == 1) {
            header('Content-Type: application/json');
            $session_array = array(
                'type' => 1,
                'message' => 'DLR Successfully Updated',
                'message_type' => "success"
            );
            echo json_encode($session_array);
            die;
        } else {
            if ($response == '100') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Message resending failed! Please try again later!',
                    'message_type' => "error"
                );
            } elseif ($response == '101') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Not Have Sufficient Balance to Send SMS!',
                    'message_type' => "error"
                );
            } elseif ($response == '102') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Route is not available!',
                    'message_type' => "error"
                );
            } elseif ($response == '103') {
                $session_array = array(
                    'type' => 0,
                    'message' => ' No numbers are available for this request!',
                    'message_type' => "error"
                );
            } else {
                $session_array = array(
                    'type' => 0,
                    'message' => ' Message resending failed! Please try again later!',
                    'message_type' => "error"
                );
            }
            echo json_encode($session_array);
        }
    }

    public function controller_history() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);

            $page = 1;
            $records_per_page = 200;
            $index = ($page * $records_per_page) - 200;
            $history_log = $this->admin_data_model->getControllerHistory();

            $return_array = $this->paging($history_log, $index, $records_per_page);

            $table['history_details'] = $return_array['return_array'];

            // var_dump($table['table_data']);die;
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "history";

            // Paging View
            $table['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['table'] = $this->load->view('admin/show_controller_history', $table, true);

            $data['page'] = "controller_history";



            $this->load->view('admin/header', $data);
            $this->load->view('admin/controller_history');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function paging_history($page = 0, $records_per_page = 0, $subtab = 0) {
        if ($this->session->userdata('admin_logged_in')) {

            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);

            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $index = ($page * $records_per_page) - 100;
            $history_log = $this->admin_data_model->getControllerHistory();

            $return_array = $this->paging($history_log, $index, $records_per_page);

            $data['history_details'] = $return_array['return_array'];

            // var_dump($table['table_data']);die;
            $paging['total_pages'] = $return_array['total_pages'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['subtab'] = 1;
            $paging['function'] = "history";

            // Paging View
            $data['paging'] = $this->load->view('admin/paging', $paging, true);
            $data['subtab'] = $subtab;

            $this->load->view('admin/show_controller_history', $data);
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function search_controller_history() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response['history_details'] = $this->admin_data_model->searchControllerHistory();
        //$this->load->view('admin/header', $response);
        $this->load->view('admin/search_controller_history', $response);
        // $this->load->view('admin/footer'); 
    }

    // search tps report
    public function search_tps_report() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response['tps_details'] = $this->admin_data_model->searchTpsReport();
        var_dump($response['tps_details']);
        die;
        //$this->load->view('admin/header', $response);
        //$this->load->view('admin/search_controller_history', $response);
        // $this->load->view('admin/footer'); 
    }

    //export from phonebook
//    public function export_from_phonebook($campaign_id = 0, $user_id = 0) {
//        ini_set('max_input_time', 2400);
//        ini_set('max_execution_time', 2400);
//        ini_set('memory_limit', '1073741824');
//
//        $sent_sms = $this->admin_data_model->exportFromPhonebook($campaign_id, $user_id);
//        $filename = "exportphone" . date('Ymdhis');
//        $csvFile = "./Reports/$filename.csv";
//        $file = fopen($csvFile, 'w') or die("can't open file");
//        //fwrite($fh, $stringData);
//        $headings = "S.No., Contact Name, Mobile Number, Status";
//        fputcsv($file, explode(',', $headings));
//        $i = 1;
//        if ($sent_sms) {
//            foreach ($sent_sms as $sms) {
//                $line = $i;
//                if ($sms['status'] == "1") {
//                    $status = "Delivered";
//                } elseif ($sms['status'] == "2") {
//                    $status = "Failed";
//                } elseif ($sms['status'] == "31" || $sms['status'] == "4") {
//                    $status = "Pending";
//                } elseif ($sms['status'] == "8") {
//                    $status = "Submit";
//                } elseif ($sms['status'] == "DND" || $sms['status'] == "16") {
//                    $status = "DND";
//                } elseif ($sms['status'] == "Blocked") {
//                    $status = "Block By Operator";
//                } else {
//                    $status = $sms['status'];
//                }
//
//                $line .= "," . $sms['contact_name'];
//                $line .= "," . $sms['mobile_number'];
//                $line .= "," . $status;
//                fputcsv($file, explode(',', $line));
//                $i++;
//            }
//        }
//        echo '<a href= http://sms.bulksmsserviceproviders.com/Reports/' . $filename . '.csv>download excel file</a>';
//    }
//Show Daily REports
    public function show_daily_reports() {
        $data['daily_signup'] = $this->admin_data_model->showDailyUser();
        $data['daily_subscription'] = $this->admin_data_model->showDailySubscription();
        $data['daily_transaction'] = $this->admin_data_model->showDailyTransaction();
        $data['subadmin_target'] = $this->admin_data_model->showSubAdminTarget();
        $data['daily_otp'] = $this->admin_data_model->getDailyOtpSummry();
        $this->load->view('admin/header', $data);
        $this->load->view('admin/daily_reports');
        $this->load->view('admin/footer');
    }

    // Get TR consumption by date
    public function get_tr_consumption() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['tr_consumption_logs'] = $this->admin_data_model->trConsumptionsByDate();
            // var_dump($data['tr_consumption_logs']);die;
            // $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            //  $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $this->load->view('admin/tr-consumption-log', $data);
        }
    }

    // Get PR consumption by date
    public function get_pr_consumption() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['pr_consumption_logs'] = $this->admin_data_model->prConsumptionsByDate();
            // $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
            // $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
            $this->load->view('admin/pr-consumption-log', $data);
        }
    }

    public function otp_test() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['otp_test'] = $this->admin_data_model->get_daily_otp();

            $this->load->view('admin/header', $data);
            $this->load->view('admin/show_test_otp');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function get_otp_test() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];

            $data['otp_test'] = $this->admin_data_model->getOtpTest();



            $this->load->view('admin/header', $data);
            $this->load->view('admin/show_test_otp');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }


    public function update_payment_subadmin() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $response = $this->admin_data_model->updatePaymentSubadmin();
        if ($response) {
            redirect("admin/show_payment_subadmin");
        } else {
            redirect("admin/show_payment_subadmin");
        }
    }

    function googleUrlShortner() {
        $url = "http://theofficearea.in/cm_file.php";
        $this->load->library('google_url_api');
        $short_url = $this->google_url_api->shorten($url);
        echo $short_url->id;
    }

    public function daily_subscription() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $config = array(); // Initialize empty array.
            $config["base_url"] = base_url() . 'admin/daily_subscription'; // Set base_url for every links
            $total_row = $this->admin_data_model->countTotalSubscription();
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            if ($this->uri->segment(3)) {
                $data['page'] = ($this->uri->segment(3));
            } else {
                $data['page'] = 0;
            }

            $data["subscribe_data"] = $this->admin_data_model->getSubscriptionData($config["per_page"], $data['page']);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data['subscribe_record'] = $this->admin_data_model->getAllSubscriptionData();
            $data['admin_name'] = $this->admin_data_model->adminNameForLead();
            // $data['subscribe_data'] = $this->admin_data_model->getdailySubscription();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/daily_subscription');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
	
	
	
	//User Scheduled Meetings
	
	public function meetings() {
        if ($this->session->userdata('admin_logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $config = array(); // Initialize empty array.
            $config["base_url"] = base_url() . 'admin/meetings'; // Set base_url for every links
            $total_row = $this->admin_data_model->countTotalMeetings();
            $config["total_rows"] = $total_row;
            $config["per_page"] = 100;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li class="prev">';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            if ($this->uri->segment(3)) {
                $data['page'] = ($this->uri->segment(3));
            } else {
                $data['page'] = 0;
            }

            $data["subscribe_data"] = $this->admin_data_model->getMeetingsData($config["per_page"], $data['page']);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data['subscribe_record'] = $this->admin_data_model->getAllMeetingsData();
            $data['admin_name'] = $this->admin_data_model->adminNameForLead();
            // $data['subscribe_data'] = $this->admin_data_model->getdailySubscription();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/meetings');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }
	
	
	
	
	
	
	
	
	
	
	
	

    //upadte daily feed back
    public function update_subscription_feedback() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);

        $responce = $this->admin_data_model->updateSubscriptionFeedback();
        if ($responce) {
            redirect('admin/daily_subscription', 'refresh');
        } else {
            redirect('admin/daily_subscription', 'refresh');
        }
    }



    //upadte daily feed back
    public function update_meeting_feedback() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);

        $responce = $this->admin_data_model->updateMeetingFeedback();
        if ($responce) {
            redirect('admin/meetings', 'refresh');
        } else {
            redirect('admin/meetings', 'refresh');
        }
    }


    public function get_all_numbersms_report() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $subtab = 7;
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $data['delivery_reports'] = $this->admin_data_model->getAllNumberSmsReport($admin_id);
            $data['subtab'] = $subtab;
            if ($subtab == 7) {
                $data['pr_user_groups'] = $this->admin_data_model->getUserGroups('Promotional', 1);
                $data['tr_user_groups'] = $this->admin_data_model->getUserGroups('Transactional', 1);
                // Pagination
                $page = 1;
                $records_per_page = 200;
                $index = ($page * $records_per_page) - 200;
                $result_logs = $this->admin_data_model->countDeliveryReports();
                $total_logs = 0;
                if ($result_logs) {
                    $total_logs = $result_logs->total;
                }
                $logs = $this->admin_data_model->getSmsNumberReports($index, $records_per_page);
                $return_array = $this->paging_table($logs, $index, $records_per_page, $total_logs);
                $data['total_records'] = $total_logs;
                $data['delivery_reports'] = $return_array['return_array'];
                $data['total_pages'] = $return_array['total_pages'];
                $data['records_data'] = $return_array['records_data'];
                $data['page_no'] = $page;
                $data['subtab'] = 7;
            }
            $this->load->view('admin/get-all-numbersms-report', $data);
        }
    }

    public function search_daily_report() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $date = $this->input->post('date');

            $data['daily_signup'] = $this->admin_data_model->showDailyUserSearch($date);
            $data['daily_subscription'] = $this->admin_data_model->showDailySubscriptionSearch($date);
            $data['daily_transaction'] = $this->admin_data_model->showDailyTransactionSearch($date);
            $data['subadmin_target'] = $this->admin_data_model->showSubAdminTargetSearch($date);
            $data['daily_otp'] = $this->admin_data_model->getDailyOtpSummrySearch($date);
            $this->load->view('admin/search_daily_report', $data);
        }
    }

    // Daily System Analysis
    public function daily_system_analysis() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $data['daily_data'] = $this->admin_data_model->getDailyAnalysis();
            $this->load->view('admin/header', $data);
            $this->load->view('admin/daily-amount-analysis');
            $this->load->view('admin/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    public function search_daily_analysis_amount() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $admin_id = $session_data['admin_id'];
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $date = $this->input->post('date');
            $data['daily_data'] = $this->admin_data_model->getDailyAnalysisSearch($date);
            $this->load->view('admin/search-daily-amount-analysis', $data);
        }
    }
      public function subscribe_mailer(){
        
        $this->load->view('admin/header');
        $this->load->view('admin/subscription_mailer');
        $this->load->view('admin/footer');
     }
   
    public function  meeting_mail(){
        $this->load->view('admin/header');
        $this->load->view('admin/meeting_mail');
        $this->load->view('admin/footer');
      }

    public function daily_sign_mail(){    
        $this->load->view('admin/header');
        $this->load->view('admin/signup_mail');
        $this->load->view('admin/footer');
      
        
    } 

   public function user_subscribe_mail(){
     $this->admin_data_model->subscribe_mail();
    }

   public function otp_sms() {    
        $this->load->view('admin/header');
        $this->load->view('admin/otp_sms');
        $this->load->view('admin/footer');
      
     } 

    public function subscribe_sms() {    
        $this->load->view('admin/header');
        $this->load->view('admin/subscribe_sms');
        $this->load->view('admin/footer');

       
      
     } 

      public function  daily_sign_sms() {    
        $this->load->view('admin/header');
        $this->load->view('admin/daily_sign_sms');
        $this->load->view('admin/footer');
      
     } 
  
  public function  meeting_sms() {    
        $this->load->view('admin/header');
        $this->load->view('admin/meeting_sms');
        $this->load->view('admin/footer');
     } 



 //------------------------------------------------------------------------------------------------------------------------------------------//
}
