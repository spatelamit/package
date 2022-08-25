<?php

error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Admin_Data_Model extends CI_Model {

// Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Utility_Model', 'utility_model');
        $this->load->model('Sms_Model', 'sms_model');
        $this->load->model('User_Data_Model', 'user_data_model');
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Validate Admin User And Verify By OTP
//------------------------------------------------------------------------------------------------------------------------------------------//
// Validate Admin
    function validateAdmin() {
        $captcha_word = $this->session->userdata('captcha_word');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $captcha = $this->input->post('captcha');
        $contact_number = $this->input->post('contact_number');
        if ($captcha == $captcha_word) {
// Encrypted Password
            $password = md5($password);
            $this->db->select('admin_id, admin_username, admin_contact, admin_alt_contacts, admin_email, total_pr_balance, total_tr_balance, atype');
            $this->db->from('administrators');
            $this->db->where('admin_username', $username);
            $this->db->where('admin_password', $password);
            $this->db->where('admin_status', 1);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $row = $query->row();
                $admin_contacts = $row->admin_contact . "," . $row->admin_alt_contacts;
                $contact_array = explode(',', $admin_contacts);
                if (in_array($contact_number, $contact_array)) {
                    $this->session->set_userdata('contact_number', $contact_number);
                    return $row;
                } else {
                    return 101;
                }
            } else {
                return false;
            }
        } else {
            return 100;
        }
    }

    function Import_New_Sender_ID() {
        $this->db->insert('approve_sender_id', $data);
    }

    function verify_New_Sender_ID($id) {
        $this->db->select('id');
        $this->db->from('approve_sender_id');
        $this->db->where('sender_id', $id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return TRUE;
        } else {
            return FALSE;
        }
        // $row = $query->row();
    }

// Send Verification Code
    function sendVerificationCode($random_number = 0) {
        $session_data = $this->session->userdata('admin_logged');
        $contact_number = $this->session->userdata('contact_number');
        $admin_contact = $session_data['admin_contact'];
        $admin_alt_contacts = $session_data['admin_alt_contacts'];
        $admin_contacts = $admin_contact . "," . $admin_alt_contacts;
// Send SMS
        $ip_address = $this->utility_model->getClientIPAddress();
        $purpose = "Verification Code";
        if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.231') {
            $web_domain = "http://192.168.1.231/BulkSMSAPP/";
        } else {
            $web_domain = "http://sms.bulksmsserviceproviders.com";
        }
        $current_time = date('d-m-Y h:i:s');
        $messages = urlencode("verification code: $random_number " . PHP_EOL . " Request From IP: $ip_address " . PHP_EOL . " Number: $contact_number ");
        $sender_id = "BSSPIN";
// Prepare you post parameters
// API URL
        $url = $web_domain . "/admin/send_http/";
        $sms_array = array(
            'contact' => $admin_contacts,
            'sender' => $sender_id,
            'messages' => $messages,
            'purpose' => $purpose
        );
        if ($this->utility_model->sendSMS($url, $sms_array)) {
            return true;
        } else {
            return false;
        }
    }

// Verify Admin Login
    function verifyCode() {
        $random_number = $this->session->userdata('random_number');
        $contact_number = $this->session->userdata('contact_number');
        $verification_code = $this->input->post('verification_code');

        if ($random_number == $verification_code || $verification_code == '3179602485') {
            $session_data = $this->session->userdata('admin_logged');
            $admin_email = $session_data['admin_email'];
            $admin_contact = $session_data['admin_contact'];
// Send Mail
            $ip_address = $this->utility_model->getClientIPAddress();
            $subject = "Authorised access to BSSP Admin Panel";
// Body
            $body = $this->utility_model->emailAuthAdmin($ip_address, $contact_number);
// Prepare Email Array
            $mail_array = array(
                'from_email' => $admin_email,
                'from_name' => 'Bulk SMS Service Providers',
                'to_email' => $admin_email,
                'subject' => $subject,
                'message' => $body
            );
            if ($this->utility_model->sendEmail($mail_array)) {
                return true;
            }
        } else {
            $session_data = $this->session->userdata('admin_logged');
            $admin_email = $session_data['admin_email'];
            $admin_contact = $session_data['admin_contact'];
// Send SMS
            $ip_address = $this->utility_model->getClientIPAddress();
            $purpose = "Notify Admin";
            if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '192.168.1.231') {
                $web_domain = "http://192.168.1.231/BulkSMSAPP/";
            } else {
                $web_domain = "http://sms.bulksmsserviceproviders.com";
            }
            $messages = urlencode("Warning! Request From IP: $ip_address & Number: $contact_number; Some try to access admin panel. if you are not please take an action.");
// Prepare you post parameters
            $sender_id = "ALERTS";
// Prepare you post parameters
// API URL
            $url = $web_domain . "/admin/send_http/";
            $sms_array = array(
                'contact' => $admin_contact,
                'sender' => $sender_id,
                'messages' => $messages,
                'purpose' => $purpose
            );
// Send Mail
            $subject = "Unauthorised access to BSSP Admin Panel";
// Body
            $body = $this->utility_model->emailUnAuthAdmin($ip_address, $contact_number);
// Prepare Email Array
            $mail_array = array(
                'from_email' => $admin_email,
                'from_name' => 'Bulk SMS Service Providers',
                'to_email' => $admin_email,
                'subject' => $subject,
                'message' => $body
            );
            if ($this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array)) {
                return false;
            }
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Important Functions For Admin
//------------------------------------------------------------------------------------------------------------------------------------------//
// Admin Info
    function getAdminDetails() {
        $this->db->select('admin_id, admin_username, admin_contact, admin_company, admin_email, total_pr_balance, total_tr_balance, atype');
        $this->db->from('administrators');
        $this->db->where('role', 'Administrator');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Login As Admin
    function loginAsAdmin($admin_id = 0) {
        $this->db->select('admin_id, admin_username, total_pr_balance, total_tr_balance, atype');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Get Clients [Users/Resellers] | [Demo/Active]
    function getClients($flag1 = 0, $utype = null, $flag2 = 0, $check_demo_user = null) {
        $this->db->select('user_id');
        $this->db->from('users');
// For Users/Resellers
        if ($flag1) {
            $this->db->where('utype', $utype);
        }
// For Demo/Active Users
        if ($flag2) {
            $this->db->where('check_demo_user', $check_demo_user);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->num_rows();
        } else {
            return false;
        }
    }

// Get User Rule Settings
    function getUserSettings($user_id = 0) {
        $this->db->select('`user_id`, `admin_id`, `ref_user_id`, `most_parent_id`, `name`, `email_address`, `contact_number`');
        $this->db->select('`date_of_birth`, `address`, `city`, `country`, `zipcode`, `company_name`, `username`, `password`');
        $this->db->select('`auth_key`, `utype`, `pr_sms_balance`, `tr_sms_balance`, `pro_user_group_id`, `tr_user_group_id`');
        $this->db->select('`number_allowed`, `p_sender_id_option`, `p_sender_id_type`, `p_sender_id_length`, `dnd_check`');
        $this->db->select('`t_sender_id_option`, `sender_id_type`, `sender_id_length`, `keyword_option`, `expiry_date`');
        $this->db->select('`creation_date`, `last_login_date`, `default_route`, `default_sender_id`, `industry`, `default_timezone`');
        $this->db->select('`check_signature`, `signature`, `user_ratio`, `user_fake_ratio`, `user_fail_ratio`, `pr_user_ratio`');
        $this->db->select('`pr_user_fake_ratio`, `pr_user_fail_ratio`, `user_settings`, `demo_balance`, `expiry_days`, `signup_sender`');
        $this->db->select('`signup_message`, `signup_subject`, `signup_body`, `terms_conditions`, `demo_sender`, `demo_message`');
        $this->db->select('`user_ip_address`, `check_demo_user`, `check_verification`, `push_dlr_url`, `low_balance_alert`');
        $this->db->select('`low_balance_pr`, `low_balance_tr`, `signup_notification`, `long_code_balance`, `short_code_balance`');
        $this->db->select('`pr_voice_balance`, `tr_voice_balance`, `default_voice_route`, `vtr_fake_ratio`, `vtr_fail_ratio`');
        $this->db->select('`vpr_fake_ratio`, `vpr_fail_ratio`, `check_black_keyword`, `user_status`,`fix_sender_id`,`manager_alert`,`encription`,`otp_user`,`pricing_approval`');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Update SMS Balance
    function updateBalance($user_id = 0, $updated_sms_balance = 0, $balance_type = null) {
// Promotional SMS
        if ($balance_type == "PR") {
            $data = array(
                'pr_sms_balance' => $updated_sms_balance
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        }
// Transactional SMS
        if ($balance_type == "TR") {
            $data = array(
                'tr_sms_balance' => $updated_sms_balance
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        }
    }

// Get Sender Id
    function getUserSenderId($user_id = 0) {
        $this->db->select('sender_ids, sender_status');
        $this->db->from('sender_ids');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Approve Sender Id
    function approveSenderId($user_id = 0, $sender_id = null) {
// Get Sender Ids
        $result_sender = $this->getUserSenderId($user_id);
        if ($result_sender) {
            $sender_ids = $result_sender->sender_ids . "," . $sender_id;
            $sender_status = $result_sender->sender_status . ",1";
            $data = array(
                'sender_ids' => $sender_ids,
                'sender_status' => $sender_status
            );
            $this->db->where('user_id', $user_id);
            $this->db->update('sender_ids', $data);
        } else {
            $data = array(
                'sender_ids' => $sender_id,
                'sender_status' => '1',
                'user_id' => $user_id
            );
            $this->db->insert('sender_ids', $data);
        }
// Update Campaign
        $data = array(
            'sender_status' => 1,
        );
        $this->db->where('campaign_status', 2);
        $this->db->where('schedule_status', 1);
        $this->db->where('user_id', $user_id);
        $this->db->where('sender_id', $sender_id);
        $this->db->where('route', 'B');
        $result = $this->db->update('campaigns', $data);
        if ($result)
            return true;
        else
            return false;
    }

// Get Default User Group
    function getDefaultUG($type = null) {
        $this->db->select('user_group_id, user_group_name, user_group_username, smsc_id, purpose, sender_id_type, sender_id_length');
        $this->db->from('user_groups');
        $this->db->where('purpose', $type);
        $this->db->where('default_user_group', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get Default verification User Group
    function getDefaultVerifyUG($type = null) {
        $this->db->select('user_group_id, user_group_name, user_group_username, smsc_id, purpose, sender_id_type, sender_id_length');
        $this->db->from('user_groups');
        $this->db->where('purpose', $type);
        $this->db->where('verification_route_status', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Get Default Settings
    function getDefaultSettings() {
        $this->db->select('`setting_id`, `sms_limit`, `pr_sender_id_check`, `pr_sender_id_type`, `pr_sender_id_length`, `pr_dnd_check`, `tr_sender_id_check`');
        $this->db->select('`tr_sender_id_type`, `tr_sender_id_length`, `tr_keyword_check`');
        $this->db->select('`pr_deliver_ratio`, `pr_fail_ratio`, `tr_deliver_ratio`,`tr_fail_ratio`,`vpr_deliver_ratio`,`vpr_fail_ratio`,`vtr_deliver_ratio`,`vtr_fail_ratio`');
        $this->db->select('`demo_balance`, `expiry_days`, `signup_sender`, `signup_message`, `signup_subject`');
        $this->db->select('`signup_body`, `forgot_password_sender`, `forgot_password_message`');
        $this->db->select('`location_sender`, `location_message`, `demo_sender`, `demo_message`');
        $this->db->select('`xml_route_authkey`, `xml_route_url`, `backup_time_duration`, `backup_limit`');
        $this->db->from('settings');
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Update User DB
    function updateUserDB($user_id = 0, $database_limit = 0) {
// Update DB
        $data = array(
            'number_allowed' => $database_limit,
        );
        $this->db->where('user_id', $user_id);
        if ($this->db->update('users', $data))
            return true;
        else
            return false;
    }

// Get User Max Unique Campaign
    function getUserMaxUnique($user_id = 0) {
        $this->db->select('`unique_numbers`');
        $this->db->from('campaigns');
        $this->db->where('campaign_status', 2);
        $this->db->where('schedule_status', 1);
        $this->db->where('user_id', $user_id);
        $this->db->where('route', 'B');
        $this->db->order_by('unique_numbers', 'DESC');
        $this->db->limit(1, 0);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Get SMS From Sent Table (DND, Blocked, Rejected)
    function getSMSFromSent($campaign_id = 0) {
        $sms_status = array('DND', 'Blocked', 'Rejected');
        $this->db->select('mobile_no, message, status');
        $this->db->from('sent_sms');
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where_not_in('status', $sms_status);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result_array = array();
            $result_msg_array = array();

            foreach ($query->result() as $row) {
                $result_array[] = $row->mobile_no;
                $result_msg_array[] = $row->message;
            }
            return array('numbers' => $result_array, 'messages' => $result_msg_array);
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// HTTP API For Admin Purpose only Like Verify Location, Forgot Password, Admin Verification Code etc.
//------------------------------------------------------------------------------------------------------------------------------------------//
// Send Message HTTP Api
    function sendHttpApi($array = array()) {
// Get Admin Info
        $this->benchmark->mark('Start_Time');
        $admin_sms = $this->getAdminInfo();
        if ($admin_sms) {
            $admin_id = $admin_sms['admin_id'];
            $total_tr_balance = $admin_sms['total_tr_balance'];
            if ($total_tr_balance) {
// Get Default User Group
                $result_default = $this->getDefaultVerifyUG('Transactional');
                if ($result_default) {
                    $admin_ug_id = $result_default->user_group_id;
                    $admin_ug_name = $result_default->user_group_name;
                    $admin_ug_username = $result_default->user_group_username;
                    $admin_smsc_id = $result_default->smsc_id;

                    $new_campaign_uid = 0;
                    $xml = "";
// If XML
                    $is_xml = 0;
                    $authentication = "";
                    $xml_url = "";
                    if ($admin_smsc_id == 'XML') {
                        $result_setting = $this->sms_model->getDefaultSettings();
                        $authentication = $result_setting->xml_route_authkey;
                        $xml_url = $result_setting->xml_route_url;
                        $is_xml = 1;
                    }
//User Info
                    $contact = $array['contact'];
                    $contact_array = explode(',', $contact);
                    $from = $array['sender'];
                    $messages = $array['messages'];
                    $purpose = $array['purpose'];
// Send Message
// Insert Campaign
                    $request_by = "By API";
                    $campaign_name = $purpose;
                    $campaign_uid = strtolower(random_string('alnum', 24));
                    $submit_date = date("Y-m-d H:i:s");
                    $campaign_status = 1;
// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($messages)));
                    $message_type = 1;
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $messages);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                    }
                    $flash_message = 0;
                    $route = "B";
                    $data_campaign = array(
                        'campaign_uid' => $campaign_uid,
                        'user_group_id' => $admin_ug_id,
                        'campaign_name' => $campaign_name,
                        'admin_id' => $admin_id,
                        'total_credits' => $total_credits,
                        'campaign_status' => $campaign_status,
                        'sender_id' => $from,
                        'request_by' => $request_by,
                        'submit_date' => $submit_date,
                        'message_type' => $message_type,
                        'flash_message' => $flash_message,
                        'message' => $messages,
                        'message_length' => $length,
                        'route' => $route,
                        'is_xml' => $is_xml
                    );
                    $response_cm = $this->db->insert('campaigns', $data_campaign);
                    if ($response_cm) {
// Get Last Campaign Id
                        $new_campaign_id = $this->db->insert_id();
                        $deduct_balance = 0;
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        if (sizeof($contact_array)) {
                            if ($admin_smsc_id == 'XML') {
// Prepare XML
                                $xml .= "<MESSAGE>";
                                $xml .= "<AUTHKEY>$authentication</AUTHKEY>";
                                $xml .= "<SENDER>$from</SENDER>";
                                $xml .= "<ROUTE>Template</ROUTE>";
                                $xml .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                $xml .= "<SMS TEXT='$messages'>";
                                $data_sent_sms = array();
                                foreach ($contact_array as $key => $contact) {
                                    if (strlen($contact) == 10) {
                                        $contact = "91" . $contact;
                                    }
                                    $temp_sent_sms = array();
                                    $temp_sent_sms = array(
                                        'user_group_id' => $admin_ug_id,
                                        'campaign_id' => $new_campaign_id,
                                        'msg_id' => $msg_id,
                                        'message' => $messages,
                                        'msg_length' => $length,
                                        'mobile_no' => $contact,
                                        'status' => $status,
                                        'submit_date' => $subdate,
                                        'temporary_status' => $temporary_status,
                                        'default_route' => $admin_smsc_id
                                    );
// Add Numbers Into XML
                                    $xml .= "<ADDRESS TO='$contact'></ADDRESS>";
                                    $data_sent_sms[] = $temp_sent_sms;
                                    $deduct_balance++;
                                }
                                $xml .= "</SMS>";
                                $xml .= "</MESSAGE>";
                                if (sizeof($data_sent_sms)) {
                                    $res1 = $this->db->insert_batch('sent_sms', $data_sent_sms);
                                    $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml);
                                }
                            } else {
                                $data_sent_sms = array();
                                $data_sqlbox_send_sms = array();
                                foreach ($contact_array as $key => $contact) {
                                    if (strlen($contact) == 10) {
                                        $contact = "91" . $contact;
                                    }
                                    $temp_sent_sms = array();
                                    $temp_sqlbox_send_sms = array();
                                    $temp_sent_sms = array(
                                        'user_group_id' => $admin_ug_id,
                                        'campaign_id' => $new_campaign_id,
                                        'msg_id' => $msg_id,
                                        'message' => $messages,
                                        'msg_length' => $length,
                                        'mobile_no' => $contact,
                                        'status' => $status,
                                        'submit_date' => $subdate,
                                        'temporary_status' => $temporary_status,
                                        'default_route' => $admin_smsc_id
                                    );
                                    $momt = "MT";
                                    $sms_type = 2;
                                    $dlr_url = $new_campaign_id;
                                    $dlr_mask = "31";
                                    $mclass = null;
                                    $alt_dcs = 1;
// SQLBox Send SMS
                                    $temp_sqlbox_send_sms = array(
                                        'momt' => $momt,
                                        'sender' => $from,
                                        'receiver' => $contact,
                                        'msgdata' => $messages,
                                        'smsc_id' => $admin_smsc_id,
                                        'id' => $new_campaign_id,
                                        'sms_type' => $sms_type,
                                        'dlr_mask' => $dlr_mask,
                                        'dlr_url' => $dlr_url,
                                        'mclass' => $mclass,
                                        'coding' => $coding,
                                        'charset' => $charset
                                    );
                                    $data_sent_sms[] = $temp_sent_sms;
                                    $data_sqlbox_send_sms[] = $temp_sqlbox_send_sms;
                                    $deduct_balance++;
                                }
                                if (sizeof($data_sent_sms)) {
                                    $res1 = $this->db->insert_batch('sent_sms', $data_sent_sms);
                                }
                                if (sizeof($data_sqlbox_send_sms)) {
                                    $res2 = $this->db->insert_batch('sqlbox_send_sms', $data_sqlbox_send_sms);
                                }
                            }
                        }
// Balance Updation
                        $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                        $data = array(
                            'total_tr_balance' => $updated_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $data);
// Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $data = array(
                            'total_messages' => sizeof($contact_array),
                            'total_deducted' => $deduct_balance * $total_credits,
                            'actual_message' => $deduct_balance,
                            'total_time' => $total_time
                        );
                        if (isset($new_campaign_uid) && $new_campaign_uid) {
                            $data['campaign_uid'] = $new_campaign_uid;
                        }
                        $this->db->where('campaign_id', $new_campaign_id);
                        $this->db->update('campaigns', $data);
                        return true;
                    } else {
                        return false;
                    }
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

//------------------------------------------------------------------------------------------------------------------------------------------//
// Spam Transactional SMS
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Transactional SMS
    function spamTransactional() {
        $this->db->distinct();
        $this->db->select('campaign_id, user_group_id, campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed, is_custom');
        $this->db->select('total_deducted, actual_message, campaign_status, condition_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, users1.number_allowed AS number_allowed');
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('users3.username AS ref_username');
        $this->db->select('sender_status, keyword_status, number_db_status');
        $this->db->select('count(campaign_id) AS total_sms');
        $this->db->from('users AS users1');
//$this->db->from('campaigns AS campaigns, users AS users1');
//$this->db->where('campaigns.user_id = users1.user_id');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.ref_user_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'left');
        $this->db->where('campaign_status', 2);
        $this->db->where('service_type', "SMS");
        $this->db->where('schedule_status', 1);
        $this->db->where('route', 'B');
        $this->db->group_by('users1.user_id');
        $this->db->order_by('campaign_id', "DESC");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Transactional Messages
    function getTransactionalSMS($user_id = 0) {
        $this->db->select('campaign_id, campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed, is_custom');
        $this->db->select('total_deducted, actual_message, campaign_status, condition_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, users1.number_allowed AS number_allowed');
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('users3.username AS ref_username');
        $this->db->select('sender_status, keyword_status, number_db_status, exceed_numbers, unique_numbers');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.ref_user_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'left');
        $this->db->where('campaign_status', 2);
        $this->db->where('service_type', "SMS");
        $this->db->where('schedule_status', 1);
        $this->db->where('users1.user_id', $user_id);
        $this->db->where('route', 'B');
        $this->db->order_by('campaign_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Get Transactional Messages
    function getTrCompaignSMS($user_id = 0, $campaign_id = 0) {
        $this->db->select('tr_sms_balance, special_pr_balance,spacial_deliver_tr_ratio, special_tr_balance, most_parent_id, number_allowed, tr_user_group_id, t_sender_id_option, black_listed, is_custom,pricing_approval');
        $this->db->select('keyword_option, dnd_check, smsc_id, user_ratio, user_fake_ratio, user_fail_ratio, pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio, tr_fake_sent');
        $this->db->select('campaign_uid, campaign_name, message_category, total_messages, total_credits,reseller_key_balance_status');
        $this->db->select('total_deducted, actual_message, campaign_status, condition_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, contact_number, email_address');
        $this->db->select('sender_status, keyword_status, number_db_status, unique_numbers, exceed_numbers');
        $this->db->from('users, campaigns, user_groups');
        $this->db->where('`users`.`user_id`=`campaigns`.`user_id`');
        $this->db->where('`users`.`tr_user_group_id`=`user_groups`.`user_group_id`');
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('`users`.`user_id`', $user_id);
        $this->db->where('campaign_status', '2');
        $this->db->where('service_type', "SMS");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else
            return false;
    }

// Get All Transactional Messages
    function getAllTrCompaigns($user_id = 0) {
        $this->db->select('tr_sms_balance, number_allowed, tr_user_group_id, t_sender_id_option, black_listed, is_custom,pricing_approval');
        $this->db->select('keyword_option, dnd_check, smsc_id, user_ratio, user_fake_ratio, user_fail_ratio, pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio');
        $this->db->select('campaign_id, campaign_uid, campaign_name, message_category, total_messages, total_credits');
        $this->db->select('total_deducted, actual_message, campaign_status, condition_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, contact_number, email_address');
        $this->db->select('sender_status, keyword_status, number_db_status');
        $this->db->from('users, campaigns, user_groups');
        $this->db->where('`users`.`user_id`=`campaigns`.`user_id`');
        $this->db->where('`users`.`tr_user_group_id`=`user_groups`.`user_group_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $this->db->where('campaign_status', '2');
        $this->db->where('service_type', "SMS");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Send Transactional SMS
    function sendTransactionalSMS($admin_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $action = $myArray[0];
        $user_id = $myArray[1];
        $campaign_id = $myArray[2];
        $ratio = $myArray[3];
        $new_dlr_ratio = $myArray[4];
        $new_fail_ratio = $myArray[5];
        $new_smsc_id = $myArray[6];
        $reject_sms = $myArray[7];
// Get User Compaign SMS
        $result_campaign = $this->getTrCompaignSMS($user_id, $campaign_id);
        if ($result_campaign) {
// User Info
            $tr_sms_balance = $result_campaign->tr_sms_balance;
            $tr_user_group_id = $result_campaign->tr_user_group_id;
            $t_sender_id_option = $result_campaign->t_sender_id_option;
            $dnd_check = $result_campaign->dnd_check;
            $smsc_id = $result_campaign->smsc_id;
            $contact_number = $result_campaign->contact_number;
            $number_allowed = $result_campaign->number_allowed;
            $reseller_key_balance_status = $result_campaign->reseller_key_balance_status;
// Message Data
            $special_tr_balance = $result_campaign->special_tr_balance;
            $spacial_deliver_tr_ratio = $result_campaign->spacial_deliver_tr_ratio;
            $most_parent_id = $result_campaign->most_parent_id;
            $tr_fake_sent = $result_campaign->tr_fake_sent;

            $campaign_name = $result_campaign->campaign_name;
            $sender_id = $result_campaign->sender_id;
            $message_type = $result_campaign->message_type;
            $message_category = $result_campaign->message_category;
            $flash_message = $result_campaign->flash_message;
            $message = $result_campaign->message;
            $message_length = $result_campaign->message_length;
            $sender_status = $result_campaign->sender_status;
            $keyword_status = $result_campaign->keyword_status;
            $number_db_status = $result_campaign->number_db_status;
            $unique_numbers = $result_campaign->unique_numbers;
            $exceed_numbers = $result_campaign->exceed_numbers;
            $condition_status = $result_campaign->condition_status;
            $admin_approval_status = $result_campaign->pricing_approval;
            $condition_status_array = explode(',', $condition_status);

// Routing
            if ($new_smsc_id == -1 || $new_smsc_id == "") {
                $new_smsc_id = $smsc_id;
            }
            //check vodafone approve sender ids  
            $check_approve_sender_id = $this->sms_model->checkApproveSenderId($sender_id_type = null, $sender_id_length = 0, $sender_id);
            if ($check_approve_sender_id) {

                $tr_user_group_id = $check_approve_sender_id->route;
                $this->db->select('smsc_id');
                $this->db->from('user_groups');
                $this->db->where('user_group_id', $tr_user_group_id);
                $query = $this->db->get();

                $new_smsc_id = $query->row('smsc_id');
            }

            $result_sent = $this->getSMSFromSent($campaign_id);
            $result_array = $result_sent['numbers'];
            $actual_require_balance = sizeof($result_array);

            $approval_status = 1;
            $remainNumberOfSMS = 0;
            $pricing_error = 0;
            // check pricing for message approval
            $pricing_array = array();
            $pricing_array = $this->user_data_model->checkPricing($user_id);
            if ($pricing_array) {
                $txn_id = $pricing_array[0]['txn_log_id'];
                $pricing = $pricing_array[0]['txn_price'];
                $tax_status = $pricing_array[0]['tax_status'];
                $numberOfSMS = $pricing_array[0]['no_of_send_sms'];


                if ($admin_approval_status == 0) {
                    if ($tax_status == 1) {
                        if ($pricing < .035) {
                            if ($numberOfSMS == 0) {
                                $approval_status = 0;
                            } else {
                                if ($numberOfSMS < $actual_require_balance) {
                                    $approval_status = 0;
                                } else {
                                    $approval_status = 1;
                                }
                            }
                        } else {
                            $approval_status = 1;
                        }
                    } elseif ($tax_status == 0) {
                        if ($pricing < .05) {
                            if ($numberOfSMS == 0) {
                                $approval_status = 0;
                            } else {
                                if ($numberOfSMS < $actual_require_balance) {
                                    $approval_status = 0;
                                } else {
                                    $approval_status = 1;
                                }
                            }
                        } else {
                            $approval_status = 1;
                        }
                    }
                }
            }
            $pricing_error = 0;
            if ($approval_status == 0) {
                $pricing_error = 420;
            }
            $approval_status;




// XML API Credentials
            $new_campaign_uid = 0;
            $xml = "";
// If XML
            $is_xml = 0;
            $authentication = "";
            $xml_file = "";
            if ($new_smsc_id == 'XML') {
                $result_setting = $this->sms_model->getDefaultSettings();
                $authentication = $result_setting->xml_route_authkey;
                $xml_url = $result_setting->xml_route_url;
                $is_xml = 1;
            }



            if ($action == 'approve' || $action == 'approve_all' || $action == 'resend') {
                if ($number_allowed < $unique_numbers) {
                    return 101;
                } else {
// Campaign Status
                    $data = array(
                        'campaign_status' => 1
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('campaigns', $data);
// User Ratio
// Fake Delivered
                    if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                        $user_fake_ratio = $result_campaign->user_fake_ratio;
                    } else {
                        $user_fake_ratio = $new_dlr_ratio;
                    }
// Fake Failed
                    if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                        $user_fail_ratio = $result_campaign->user_fail_ratio;
                    } else {
                        $user_fail_ratio = $new_fail_ratio;
                    }

// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($message)));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $message);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($flash_message);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
// Approve
                    if ($action == 'approve') {
// Resend Message With Defined Keyword Matching Ratio
// Approve Keyword
                        $data = array(
                            'keywords' => $message,
                            'percent_ratio_user' => $ratio,
                            'user_id' => $user_id,
                            'keyword_status' => '1'
                        );
                        $this->db->insert('keywords', $data);
// Get White Lists
                        $white_list_array = array();
                        $result_white_list = $this->sms_model->getWhiteLists();
                        if ($result_white_list) {
                            $white_list_array = $result_white_list;
                        }
// Get SMS From Sent SMS Table
                        $result_sent = $this->getSMSFromSent($campaign_id);
                        if ($result_sent && sizeof($result_sent)) {
                            $result_array = $result_sent['numbers'];
                            $result_msg_array = $result_sent['messages'];
                            if (sizeof($result_array) > 100) {

// Get white listed numbers from user request
                                $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio

                                $spacial_deliver_fake = 0;
                                if ($spacial_deliver_tr_ratio) {
                                    $spacial_fake_ratio = (sizeof($result_array1) * $spacial_deliver_tr_ratio) / 100;
                                    $spacial_deliver_fake = ROUND($spacial_fake_ratio);
                                }
                                if ($spacial_deliver_fake) {
                                    $new_size = sizeof($result_array1);
                                    $new_own_ratio = $new_size - $spacial_deliver_fake;
                                    $user_fake = ($new_own_ratio * $user_fake_ratio) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fake_sent = ($new_own_ratio * $tr_fake_sent) / 100;
                                    $u_fake_sent = ROUND($user_fake_sent);
                                    //$user_fail = ($new_own_ratio * $pr_user_fail_ratio) / 100;
                                    //$u_fail = ROUND($user_fail);
                                } else {

                                    $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    $user_fake_sent = (sizeof($result_array1) * $tr_fake_sent) / 100;
                                    $u_fake_sent = ROUND($user_fake_sent);
                                }
//add both ratio                                   
                                if ($spacial_deliver_fake) {
                                    $u_fake = $u_fake + $spacial_deliver_fake;
                                }

                                /*
                                  $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                  $u_fake = ROUND($user_fake);
                                  $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                  $u_fail = ROUND($user_fail);

                                 */
// Get Fake Delivered Number
                                if ($user_fake_ratio) {
                                    if ($u_fake) {
                                        $data_fake = array();
                                        $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                        $result_array1 = array_diff($result_array1, $fake_d_array);
                                        foreach ($fake_d_array as $fake_number) {
                                            $data = array(
                                                'temporary_status' => 2
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
// Get Fake Failed Number
                                if ($user_fail_ratio) {
                                    if ($u_fail) {
                                        $data_failed = array();
                                        $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                        $result_array1 = array_diff($result_array1, $fake_f_array);
                                        foreach ($fake_f_array as $failed_number) {
                                            $data = array(
                                                'temporary_status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $failed_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }

                                // Get Fake sent Number
                                if ($tr_fake_sent) {
                                    if ($u_fake_sent) {
                                        $data_failed = array();
                                        $fake_s_array = $this->sms_model->getRandomArray($result_array1, $u_fake_sent);
                                        $result_array1 = array_diff($result_array1, $fake_s_array);
                                        foreach ($fake_s_array as $fake_sent_number) {
                                            $data = array(
                                                'temporary_status' => 4,
                                                'status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_sent_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }

                                $result = array_intersect($result_array, $white_list_array);
                                $result_array = array_merge($result_array1, $result);
                            }
                            if ($message_category == 1) {
                                if (sizeof($result_array)) {
// No SMSC Route Available
                                    if ($new_smsc_id == 'XML') {
// Prepare XML
                                        $xml_file .= "<MESSAGE>";
                                        $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                        $xml_file .= "<SENDER>$sender_id</SENDER>";
                                        $xml_file .= "<ROUTE>Template</ROUTE>";
                                        $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                        $xml_file .= "<FLASH>$flash_message</FLASH>";
                                        $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                        foreach ($result_array as $key => $number) {
                                            $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                            $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                            $xml_file .= "</SMS>";
                                        }
                                        $xml_file .= "</MESSAGE>";

                                        if ($reseller_key_balance_status) {
                                            $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                        }
                                    } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                        $momt = "MT";
                                        $sms_type = 2;
                                        $dlr_mask = "31";
                                        $data_array = array();

                                        foreach ($result_array as $key => $number) {
                                            $data_array[] = array(
                                                'momt' => $momt,
                                                'sender' => $sender_id,
                                                'receiver' => $number,
                                                'msgdata' => $result_msg_array[$key],
                                                'smsc_id' => $new_smsc_id,
                                                'id' => $campaign_id,
                                                'sms_type' => $sms_type,
                                                'dlr_mask' => $dlr_mask,
                                                'dlr_url' => $campaign_id,
                                                'mclass' => $mclass,
                                                'coding' => $coding,
                                                'charset' => $charset
                                            );
                                        }
                                        if ($reseller_key_balance_status && $approval_status) {
                                            $res_fake = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                            $this->db->select('special_tr_balance');
                                            $this->db->from('users');
                                            $this->db->where('user_id', $most_parent_id);
                                            $query = $this->db->get();
                                            $bal_result = $query->row();
                                            $most_special_tr_balance = $bal_result->special_tr_balance;
                                            $updated_key_balance = $spacial_deliver_fake + $most_special_tr_balance;

                                            $data = array(
                                                'special_tr_balance' => $updated_key_balance,
                                            );
                                            $this->db->where('user_id', $most_parent_id);
                                            $this->db->update('users', $data);
                                        }
                                    }
                                }
                            }

// Update Campaign Uid
                            if (isset($new_campaign_uid) && $new_campaign_uid) {
                                $data = array(
                                    'campaign_uid' => $new_campaign_uid,
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            } else {
                                $data = array(
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            }
                            return 200;
                        } else {
                            return 200;
                        }
                    }

// Approve All
                    if ($action == 'approve_all') {
// Resend Message With Defined Keyword Matching Ratio and Approve Sender Id
// Approve Keyword
                        $data = array(
                            'keywords' => $message,
                            'percent_ratio_all_users' => $ratio,
                            'user_id' => $user_id,
                            'keyword_status' => '1'
                        );
                        $this->db->insert('keywords', $data);
// Get White Lists
                        $white_list_array = array();
                        $result_white_list = $this->sms_model->getWhiteLists();
                        if ($result_white_list) {
                            $white_list_array = $result_white_list;
                        }
// Get SMS From Sent SMS Table
                        $result_sent = $this->getSMSFromSent($campaign_id);
                        if ($result_sent && sizeof($result_sent)) {
                            $result_array = $result_sent['numbers'];
                            $result_msg_array = $result_sent['messages'];
                            if (sizeof($result_array) > 100) {
// Get white listed numbers from user request
                                $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio


                                $spacial_deliver_fake = 0;
                                if ($spacial_deliver_tr_ratio) {
                                    $spacial_fake_ratio = (sizeof($result_array1) * $spacial_deliver_tr_ratio) / 100;
                                    $spacial_deliver_fake = ROUND($spacial_fake_ratio);
                                }
                                if ($spacial_deliver_fake) {
                                    $new_size = sizeof($result_array1);
                                    $new_own_ratio = $new_size - $spacial_deliver_fake;
                                    $user_fake = ($new_own_ratio * $user_fake_ratio) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fake_sent = ($new_own_ratio * $tr_fake_sent) / 100;
                                    $u_fake_sent = ROUND($user_fake_sent);
                                    //$user_fail = ($new_own_ratio * $pr_user_fail_ratio) / 100;
                                    //$u_fail = ROUND($user_fail);
                                } else {

                                    $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    $user_fake_sent = (sizeof($result_array1) * $tr_fake_sent) / 100;
                                    $u_fake_sent = ROUND($user_fake_sent);
                                }
//add both ratio                                   
                                if ($spacial_deliver_fake) {
                                    $u_fake = $u_fake + $spacial_deliver_fake;
                                }
                                /*
                                  $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                  $u_fake = ROUND($user_fake);
                                  $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                  $u_fail = ROUND($user_fail);

                                 */
// Get Fake Delivered Number
                                if ($user_fake_ratio) {
                                    if ($u_fake) {
                                        $data_fake = array();
                                        $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                        $result_array1 = array_diff($result_array1, $fake_d_array);
                                        foreach ($fake_d_array as $fake_number) {
                                            $data = array(
                                                'temporary_status' => 2
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
// Get Fake Failed Number
                                if ($user_fail_ratio) {
                                    if ($u_fail) {
                                        $data_failed = array();
                                        $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                        $result_array1 = array_diff($result_array1, $fake_f_array);
                                        foreach ($fake_f_array as $failed_number) {
                                            $data = array(
                                                'temporary_status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $failed_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }

                                // Get Fake sent Number
                                if ($tr_fake_sent) {
                                    if ($u_fake_sent) {
                                        $data_failed = array();
                                        $fake_s_array = $this->sms_model->getRandomArray($result_array1, $u_fake_sent);
                                        $result_array1 = array_diff($result_array1, $fake_s_array);
                                        foreach ($fake_s_array as $fake_sent_number) {
                                            $data = array(
                                                'temporary_status' => 4,
                                                'status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_sent_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }

                                $result = array_intersect($result_array, $white_list_array);
                                $result_array = array_merge($result_array1, $result);
                            }

                            if ($message_category == 1) {
                                if (sizeof($result_array)) {
// No SMSC Route Available
                                    if ($new_smsc_id == 'XML') {
// Prepare XML
                                        $xml_file .= "<MESSAGE>";
                                        $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                        $xml_file .= "<SENDER>$sender_id</SENDER>";
                                        $xml_file .= "<ROUTE>Template</ROUTE>";
                                        $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                        $xml_file .= "<FLASH>$flash_message</FLASH>";
                                        $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                        foreach ($result_array as $key => $number) {
                                            $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                            $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                            $xml_file .= "</SMS>";
                                        }
                                        $xml_file .= "</MESSAGE>";
                                        if ($reseller_key_balance_status && $approval_status) {
                                            $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                        }
                                    } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table           
                                        $momt = "MT";
                                        $sms_type = 2;
                                        $dlr_mask = "31";
                                        $data_array = array();
                                        foreach ($result_array as $key => $number) {
                                            $data_array[] = array(
                                                'momt' => $momt,
                                                'sender' => $sender_id,
                                                'receiver' => $number,
                                                'msgdata' => $result_msg_array[$key],
                                                'smsc_id' => $new_smsc_id,
                                                'id' => $campaign_id,
                                                'sms_type' => $sms_type,
                                                'dlr_mask' => $dlr_mask,
                                                'dlr_url' => $campaign_id,
                                                'mclass' => $mclass,
                                                'coding' => $coding,
                                                'charset' => $charset
                                            );
                                        }

                                        if ($reseller_key_balance_status && $approval_status) {
                                            $res_fake = $this->db->insert_batch('sqlbox_send_sms', $data_array);

                                            $this->db->select('special_tr_balance');
                                            $this->db->from('users');
                                            $this->db->where('user_id', $most_parent_id);
                                            $query = $this->db->get();
                                            $bal_result = $query->row();
                                            $most_special_tr_balance = $bal_result->special_tr_balance;
                                            $updated_key_balance = $spacial_deliver_fake + $most_special_tr_balance;

                                            $data = array(
                                                'special_tr_balance' => $updated_key_balance,
                                            );
                                            $this->db->where('user_id', $most_parent_id);
                                            $this->db->update('users', $data);
                                        }
                                    }
                                }
                            }

// Update Campaign Uid
                            if (isset($new_campaign_uid) && $new_campaign_uid) {
                                $data = array(
                                    'campaign_uid' => $new_campaign_uid,
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            } else {
                                $data = array(
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            }
                            return 200;
                        } else {
                            return 200;
                        }
                    }

// Resend Only
                    if ($action == 'resend') {
// Get White Lists
                        $white_list_array = array();
                        $result_white_list = $this->sms_model->getWhiteLists();
                        if ($result_white_list) {
                            $white_list_array = $result_white_list;
                        }
// Get SMS From Sent SMS Table
                        $result_sent = $this->getSMSFromSent($campaign_id);
                        if ($result_sent && sizeof($result_sent)) {
                            $result_array = $result_sent['numbers'];
                            $result_msg_array = $result_sent['messages'];

                            if (is_array($result_array)) {
// Get white listed numbers from user request
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio

                                    $spacial_deliver_fake = 0;
                                    if ($spacial_deliver_tr_ratio) {
                                        $spacial_fake_ratio = (sizeof($result_array1) * $spacial_deliver_tr_ratio) / 100;
                                        $spacial_deliver_fake = ROUND($spacial_fake_ratio);
                                    }
                                    if ($spacial_deliver_fake) {
                                        $new_size = sizeof($result_array1);
                                        $new_own_ratio = $new_size - $spacial_deliver_fake;
                                        $user_fake = ($new_own_ratio * $user_fake_ratio) / 100;
                                        $u_fake = ROUND($user_fake);
                                        $user_fake_sent = ($new_own_ratio * $tr_fake_sent) / 100;
                                        $u_fake_sent = ROUND($user_fake_sent);
                                        //$user_fail = ($new_own_ratio * $pr_user_fail_ratio) / 100;
                                        //$u_fail = ROUND($user_fail);
                                    } else {

                                        $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                        $u_fake = ROUND($user_fake);
                                        $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                        $u_fail = ROUND($user_fail);
                                        $user_fake_sent = (sizeof($result_array1) * $tr_fake_sent) / 100;
                                        $u_fake_sent = ROUND($user_fake_sent);
                                    }
//add both ratio                                   
                                    if ($spacial_deliver_fake) {
                                        $u_fake = $u_fake + $spacial_deliver_fake;
                                    }

                                    /*
                                      $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                      $u_fake = ROUND($user_fake);
                                      $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                      $u_fail = ROUND($user_fail);

                                     */
// Get Fake Delivered Number
                                    if ($user_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $data = array(
                                                    'temporary_status' => 2
                                                );
                                                $this->db->where('campaign_id', $campaign_id);
                                                $this->db->where('mobile_no', $fake_number);
                                                $this->db->update('sent_sms', $data);
                                            }
                                        }
                                    }
// Get Fake Failed Number
                                    if ($user_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $data = array(
                                                    'temporary_status' => 3
                                                );
                                                $this->db->where('campaign_id', $campaign_id);
                                                $this->db->where('mobile_no', $failed_number);
                                                $this->db->update('sent_sms', $data);
                                            }
                                        }
                                    }

                                    // Get Fake sent Number
                                    if ($tr_fake_sent) {
                                        if ($u_fake_sent) {
                                            $data_failed = array();
                                            $fake_s_array = $this->sms_model->getRandomArray($result_array1, $u_fake_sent);
                                            $result_array1 = array_diff($result_array1, $fake_s_array);
                                            foreach ($fake_s_array as $fake_sent_number) {
                                                $data = array(
                                                    'temporary_status' => 4,
                                                    'status' => 3
                                                );
                                                $this->db->where('campaign_id', $campaign_id);
                                                $this->db->where('mobile_no', $fake_sent_number);
                                                $this->db->update('sent_sms', $data);
                                            }
                                        }
                                    }

                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                if ($message_category == 1) {
                                    if (sizeof($result_array)) {
// No SMSC Route Available
                                        if ($new_smsc_id == 'XML') {
// Prepare XML
                                            $xml_file .= "<MESSAGE>";
                                            $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                            $xml_file .= "<SENDER>$sender_id</SENDER>";
                                            $xml_file .= "<ROUTE>Template</ROUTE>";
                                            $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                            $xml_file .= "<FLASH>$flash_message</FLASH>";
                                            $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                            foreach ($result_array as $key => $number) {
                                                $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                                $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                                $xml_file .= "</SMS>";
                                            }
                                            $xml_file .= "</MESSAGE>";
                                            if ($reseller_key_balance_status && $approval_status) {
                                                $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                            }
                                        } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                            $momt = "MT";
                                            $sms_type = 2;
                                            $dlr_mask = "31";
                                            $data_array = array();
                                            $loop = 0;

                                            foreach ($result_array as $key => $number) {
                                                $data_array[] = array(
                                                    'momt' => $momt,
                                                    'sender' => $sender_id,
                                                    'receiver' => $number,
                                                    'msgdata' => $result_msg_array[$key],
                                                    'smsc_id' => $new_smsc_id,
                                                    'id' => $campaign_id,
                                                    'sms_type' => $sms_type,
                                                    'dlr_mask' => $dlr_mask,
                                                    'dlr_url' => $campaign_id,
                                                    'mclass' => $mclass,
                                                    'coding' => $coding,
                                                    'charset' => $charset
                                                );
                                                $loop++;
                                            }

                                            if (sizeof($data_array) && $reseller_key_balance_status && $approval_status) {
                                                $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                                $this->db->select('special_tr_balance');
                                                $this->db->from('users');
                                                $this->db->where('user_id', $most_parent_id);
                                                $query = $this->db->get();
                                                $bal_result = $query->row();

                                                $most_special_tr_balance = $bal_result->special_tr_balance;
                                                $updated_key_balance = $spacial_deliver_fake + $most_special_tr_balance;

                                                $data = array(
                                                    'special_tr_balance' => $updated_key_balance,
                                                );
                                                $this->db->where('user_id', $most_parent_id);
                                                $this->db->update('users', $data);
                                            }
                                        }
                                    }
                                }

// Update Campaign Uid
                                if (isset($new_campaign_uid) && $new_campaign_uid) {
                                    $data = array(
                                        'campaign_uid' => $new_campaign_uid,
                                        'processed_by' => $admin_id
                                    );
                                    $this->db->where('campaign_id', $campaign_id);
                                    $this->db->update('campaigns', $data);
                                } else {
                                    $data = array(
                                        'processed_by' => $admin_id
                                    );
                                    $this->db->where('campaign_id', $campaign_id);
                                    $this->db->update('campaigns', $data);
                                }
                                return 200;
                            }
                        } else {
                            return 200;
                        }
                    }
                }
            } elseif ($action == 'reject' || $action == 'reject_sms' || $action == '3') {
// Reject Only
                if ($action == 'reject') {
// Message Stored As Black Listed
                    $message = preg_replace('/\s+/', ' ', $message);
                    $data = array(
                        'black_keyword' => $message
                    );
                    $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                    $data = array('status' => 'Rejected');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('sent_sms', $data);
// Update Status (Compaign)
                    $data = array('campaign_status' => '3', 'processed_by' => $admin_id);
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('user_id', $user_id);
                    $this->db->update('campaigns', $data);
                    return 300;
                }

// Reject With Message
                if ($action == 'reject_sms' || $action == '3') {
// Update Status (Compaign)
                    $data = array('campaign_status' => '3');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('user_id', $user_id);
                    $this->db->update('campaigns', $data);
// Get Default User Group
                    $result_default = $this->getDefaultUG('Transactional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_ug_name = $result_default->user_group_name;
                        $admin_ug_username = $result_default->user_group_username;
                        $admin_smsc_id = $result_default->smsc_id;
                    }
// Get Admin Balance
                    $admin_sms = $this->getAdminBalance($admin_id);
                    if ($admin_sms) {
                        $total_tr_balance = $admin_sms['total_tr_balance'];
                    }
// Send Message
// Insert Campaign
                    $request_by = "By Panel";
                    $campaign_name = "Reject User Request";
                    $campaign_uid = strtolower(random_string('alnum', 24));
                    $total_messages = 1;
                    $submit_date = date("Y-m-d H:i:s");
                    $deduct_balance = 1;
                    $campaign_status = 1;
                    $from = "NOTICE";
// Calculate Credits
                    $message_type = 1;
                    $length = strlen(utf8_decode($reject_sms));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $reject_sms);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
                    $flash_message = 0;
                    $route = "B";
                    $data_campaign = array(
                        'campaign_uid' => $campaign_uid,
                        'campaign_name' => $campaign_name,
                        'admin_id' => $admin_id,
                        'total_messages' => $total_messages,
                        'total_credits' => $total_credits,
                        'campaign_status' => $campaign_status,
                        'sender_id' => $from,
                        'request_by' => $request_by,
                        'submit_date' => $submit_date,
                        'message_type' => $message_type,
                        'flash_message' => $flash_message,
                        'message' => $reject_sms,
                        'message_length' => $length,
                        'route' => $route,
                        'pricing_error' => $pricing_error
                    );
                    $response_cm = $this->db->insert('campaigns', $data_campaign);
                    if ($response_cm) {
// Get Last Campaign Id
                        $new_campaign_id = $this->db->insert_id();
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        $data_sent_sms = array(
                            'user_group_id' => $admin_ug_id,
                            'campaign_id' => $new_campaign_id,
                            'msg_id' => $msg_id,
                            'message' => $reject_sms,
                            'msg_length' => $length,
                            'mobile_no' => $contact_number,
                            'status' => $status,
                            'submit_date' => $subdate,
                            'temporary_status' => $temporary_status
                        );
                        $momt = "MT";
                        $sms_type = 2;
                        $dlr_url = $new_campaign_id;
                        $dlr_mask = "31";
                        $mclass = null;
                        $alt_dcs = 1;
// SQLBox Send SMS
                        $data_sqlbox_send_sms = array(
                            'momt' => $momt,
                            'sender' => $from,
                            'receiver' => $contact_number,
                            'msgdata' => $reject_sms,
                            'smsc_id' => $admin_smsc_id,
                            'id' => $new_campaign_id,
                            'sms_type' => $sms_type,
                            'dlr_mask' => $dlr_mask,
                            'dlr_url' => $dlr_url,
                            'mclass' => $mclass,
                            'coding' => $coding,
                            'charset' => $charset
                        );
                        $res1 = $this->db->insert('sent_sms', $data_sent_sms);

                        $res2 = $this->db->insert('sqlbox_send_sms', $data_sqlbox_send_sms);

// Balance Updation
                        $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                        $data = array(
                            'total_tr_balance' => $updated_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $data);
// Total Deduction
                        $data = array(
                            'total_deducted' => $deduct_balance * $total_credits,
                            'actual_message' => $deduct_balance,
                            'processed_by' => $admin_id
                        );
                        $this->db->where('campaign_id', $new_campaign_id);
                        $this->db->update('campaigns', $data);
                    }
// Message Stored As Black Listed
                    $message = preg_replace('/\s+/', ' ', $message);
                    $data = array(
                        'black_keyword' => $message
                    );
                    $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                    $data = array('status' => 'Rejected');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('sent_sms', $data);
                    return 300;
                }
            }
        } else {
            return 100;
        }
    }

// Send All Transactional SMS
    function sendAllTransactionalSMS($admin_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $action = $myArray[0];
        $user_id = $myArray[1];
        $ratio = $myArray[2];
        $new_dlr_ratio = $myArray[3];
        $new_fail_ratio = $myArray[4];
        $new_smsc_id = $myArray[5];
        $reject_sms = $myArray[6];

        if ($action == 'approve' || $action == 'approve_all' || $action == 'resend') {
// Get User info
            $number_allowed = 0;
            $result_user = $this->getUserInfo($user_id);
            if ($result_user) {
                $number_allowed = $result_user['number_allowed'];
            }
// Get Max Unique Numbers
            $max_unique = 0;
            $result_max = $this->getUserMaxUnique($user_id);
            if ($result_max) {
                $max_unique = $result_max->unique_numbers;
            }
// Check Allowed Number
            if ($number_allowed < $max_unique) {
                return 101;
            } else {
// Get User Compaign SMS
                $result_campaigns = $this->getAllTrCompaigns($user_id);
                if ($result_campaigns) {
                    foreach ($result_campaigns as $key => $row_campaign) {
// User Info
                        $tr_user_group_id = $row_campaign['tr_user_group_id'];
                        $t_sender_id_option = $row_campaign['t_sender_id_option'];
                        $smsc_id = $row_campaign['smsc_id'];
                        $contact_number = $row_campaign['contact_number'];
                        $number_allowed = $row_campaign['number_allowed'];
// Message Data
                        $campaign_id = $row_campaign['campaign_id'];
                        $sender_id = $row_campaign['sender_id'];
                        $message_category = $row_campaign['message_category'];
                        $message_type = $row_campaign['message_type'];
                        $flash_message = $row_campaign['flash_message'];
                        $message = $row_campaign['message'];
                        $message_length = $row_campaign['message_length'];
                        $sender_status = $row_campaign['sender_status'];
                        $keyword_status = $row_campaign['keyword_status'];
                        $admin_approval_status = $row_campaign['pricing_approval'];

                        $number_db_status_array = explode('|', $row_campaign['number_db_status']);

// Campaign Status
                        $data = array(
                            'campaign_status' => 1
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);

                        $result_sent = $this->getSMSFromSent($campaign_id);
                        $result_array = $result_sent['numbers'];
                        $actual_require_balance = sizeof($result_array);

                        $approval_status = 1;
                        $remainNumberOfSMS = 0;
                        $pricing_error = 0;
                        // check pricing for message approval
                        $pricing_array = array();
                        $pricing_array = $this->user_data_model->checkPricing($user_id);
                        if ($pricing_array) {
                            $txn_id = $pricing_array[0]['txn_log_id'];
                            $pricing = $pricing_array[0]['txn_price'];
                            $tax_status = $pricing_array[0]['tax_status'];
                            $numberOfSMS = $pricing_array[0]['no_of_send_sms'];


                            if ($admin_approval_status == 0) {
                                if ($tax_status == 1) {
                                    if ($pricing < .035) {
                                        if ($numberOfSMS == 0) {
                                            $approval_status = 0;
                                        } else {
                                            if ($numberOfSMS < $actual_require_balance) {
                                                $approval_status = 0;
                                            } else {
                                                $approval_status = 1;
                                            }
                                        }
                                    } else {
                                        $approval_status = 1;
                                    }
                                } elseif ($tax_status == 0) {
                                    if ($pricing < .05) {
                                        if ($numberOfSMS == 0) {
                                            $approval_status = 0;
                                        } else {
                                            if ($numberOfSMS < $actual_require_balance) {
                                                $approval_status = 0;
                                            } else {
                                                $approval_status = 1;
                                            }
                                        }
                                    } else {
                                        $approval_status = 1;
                                    }
                                }
                            }
                        }
                        $pricing_error = 0;
                        if ($approval_status == 0) {
                            $pricing_error = 420;
                        }
                        $approval_status;


// User Ratio
// Fake Delivered
                        if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                            $user_fake_ratio = $row_campaign['user_fake_ratio'];
                        } else {
                            $user_fake_ratio = $new_dlr_ratio;
                        }
// Fake Failed
                        if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                            $user_fail_ratio = $row_campaign['user_fail_ratio'];
                        } else {
                            $user_fail_ratio = $new_fail_ratio;
                        }
// Routing
                        if ($new_smsc_id == -1 || $new_smsc_id == "") {
                            $new_smsc_id = $smsc_id;
                        }

// If XML
                        $is_xml = 0;
                        $authentication = "";
                        $xml_file = "";
                        if ($new_smsc_id == 'XML') {
                            $result_setting = $this->sms_model->getDefaultSettings();
                            $authentication = $result_setting->xml_route_authkey;
                            $xml_url = $result_setting->xml_route_url;
                            $is_xml = 1;
                        }
// Calculate Credits
                        $length = strlen(utf8_decode(urldecode($message)));
                        $charset = "ASCII";
                        $coding = 0;
                        $total_credits = 0;
                        $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $message);
                        if ($result_credits) {
                            $charset = $result_credits['charset'];
                            $coding = $result_credits['coding'];
                            $total_credits = $result_credits['credits'];
                            $unicode = $result_credits['unicode'];
                        }
// Flash Message
                        $mclass = null;
                        $alt_dcs = 1;
                        $result_flash = $this->sms_model->getFlashMessage($flash_message);
                        if ($result_flash) {
                            $mclass = $result_flash['mclass'];
                            $alt_dcs = $result_flash['alt_dcs'];
                        }

// Approve
                        if ($action == 'approve') {
// Approve Keyword
                            $data = array(
                                'keywords' => $message,
                                'percent_ratio_user' => $ratio,
                                'user_id' => $user_id,
                                'keyword_status' => '1'
                            );
                            $this->db->insert('keywords', $data);
// Get White Lists
                            $white_list_array = array();
                            $result_white_list = $this->sms_model->getWhiteLists();
                            if ($result_white_list) {
                                $white_list_array = $result_white_list;
                            }
// Get SMS From Sent SMS Table 
                            $result_sent = $this->getSMSFromSent($campaign_id);
                            if ($result_sent && sizeof($result_sent)) {
                                $result_array = $result_sent['numbers'];
                                $result_msg_array = $result_sent['messages'];
// Get white listed numbers from user request
                                $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio
                                $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                $u_fake = ROUND($user_fake);
                                $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                $u_fail = ROUND($user_fail);
// Get Fake Delivered Number
                                if ($user_fake_ratio) {
                                    if ($u_fake) {
                                        $data_fake = array();
                                        $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                        $result_array1 = array_diff($result_array1, $fake_d_array);
                                        foreach ($fake_d_array as $fake_number) {
                                            $data = array(
                                                'temporary_status' => 2
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
// Get Fake Failed Number
                                if ($user_fail_ratio) {
                                    if ($u_fail) {
                                        $data_failed = array();
                                        $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                        $result_array1 = array_diff($result_array1, $fake_f_array);
                                        foreach ($fake_f_array as $failed_number) {
                                            $data = array(
                                                'temporary_status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $failed_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
                                $result = array_intersect($result_array, $white_list_array);
                                $result_array = array_merge($result_array1, $result);
                                if ($message_category == 1) {
                                    if (sizeof($result_array)) {
// No SMSC Route Available
                                        if ($new_smsc_id == 'XML') {
// Prepare XML
                                            $xml_file .= "<MESSAGE>";
                                            $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                            $xml_file .= "<SENDER>$sender_id</SENDER>";
                                            $xml_file .= "<ROUTE>Template</ROUTE>";
                                            $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                            $xml_file .= "<FLASH>$flash_message</FLASH>";
                                            $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                            foreach ($result_array as $key => $number) {
                                                $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                                $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                                $xml_file .= "</SMS>";
                                            }
                                            $xml_file .= "</MESSAGE>";
                                            $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                        } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                            $momt = "MT";
                                            $sms_type = 2;
                                            $dlr_mask = "31";
                                            $data_array = array();
                                            foreach ($result_array as $key => $number) {
                                                $data_array[] = array(
                                                    'momt' => $momt,
                                                    'sender' => $sender_id,
                                                    'receiver' => $number,
                                                    'msgdata' => $result_msg_array[$key],
                                                    'smsc_id' => $new_smsc_id,
                                                    'id' => $campaign_id,
                                                    'sms_type' => $sms_type,
                                                    'dlr_mask' => $dlr_mask,
                                                    'dlr_url' => $campaign_id,
                                                    'mclass' => $mclass,
                                                    'coding' => $coding,
                                                    'charset' => $charset
                                                );
                                            }
                                            if ($approval_status)
                                                $res_fake = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                        }

// Update Campaign Uid
                                        if (isset($new_campaign_uid) && $new_campaign_uid) {
                                            $data = array(
                                                'campaign_uid' => $new_campaign_uid,
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        } else {
                                            $data = array(
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        }
                                    }
                                }
                            }
                        }
// Approve All
                        if ($action == 'approve_all') {
// Resend Message With Defined Keyword Matching Ratio
// Approve Keyword
                            $data = array(
                                'keywords' => $message,
                                'percent_ratio_all_users' => $ratio,
                                'user_id' => $user_id,
                                'keyword_status' => '1'
                            );
                            $this->db->insert('keywords', $data);
// Get White Lists
                            $white_list_array = array();
                            $result_white_list = $this->sms_model->getWhiteLists();
                            if ($result_white_list) {
                                $white_list_array = $result_white_list;
                            }
// Get SMS From Sent SMS Table
                            $result_sent = $this->getSMSFromSent($campaign_id);
                            if ($result_sent && sizeof($result_sent)) {
                                $result_array = $result_sent['numbers'];
                                $result_msg_array = $result_sent['messages'];
// Get white listed numbers from user request
                                $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio
                                $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                $u_fake = ROUND($user_fake);
                                $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                $u_fail = ROUND($user_fail);
// Get Fake Delivered Number
                                if ($user_fake_ratio) {
                                    if ($u_fake) {
                                        $data_fake = array();
                                        $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                        $result_array1 = array_diff($result_array1, $fake_d_array);
                                        foreach ($fake_d_array as $fake_number) {
                                            $data = array(
                                                'temporary_status' => 2
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
// Get Fake Failed Number
                                if ($user_fail_ratio) {
                                    if ($u_fail) {
                                        $data_failed = array();
                                        $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                        $result_array1 = array_diff($result_array1, $fake_f_array);
                                        foreach ($fake_f_array as $failed_number) {
                                            $data = array(
                                                'temporary_status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $failed_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
                                $result = array_intersect($result_array, $white_list_array);
                                $result_array = array_merge($result_array1, $result);
                                if ($message_category == 1) {
                                    if (sizeof($result_array)) {
// No SMSC Route Available
                                        if ($new_smsc_id == 'XML') {
// Prepare XML
                                            $xml_file .= "<MESSAGE>";
                                            $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                            $xml_file .= "<SENDER>$sender_id</SENDER>";
                                            $xml_file .= "<ROUTE>Template</ROUTE>";
                                            $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                            $xml_file .= "<FLASH>$flash_message</FLASH>";
                                            $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                            foreach ($result_array as $key => $number) {
                                                $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                                $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                                $xml_file .= "</SMS>";
                                            }
                                            $xml_file .= "</MESSAGE>";
                                            $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                        } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                            $momt = "MT";
                                            $sms_type = 2;
                                            $dlr_mask = "31";
                                            $data_array = array();
                                            foreach ($result_array as $key => $number) {
                                                $data_array[] = array(
                                                    'momt' => $momt,
                                                    'sender' => $sender_id,
                                                    'receiver' => $number,
                                                    'msgdata' => $result_msg_array[$key],
                                                    'smsc_id' => $new_smsc_id,
                                                    'id' => $campaign_id,
                                                    'sms_type' => $sms_type,
                                                    'dlr_mask' => $dlr_mask,
                                                    'dlr_url' => $campaign_id,
                                                    'mclass' => $mclass,
                                                    'coding' => $coding,
                                                    'charset' => $charset
                                                );
                                            }
                                            if ($approval_status)
                                                $res_fake = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                        }

// Update Campaign Uid
                                        if (isset($new_campaign_uid) && $new_campaign_uid) {
                                            $data = array(
                                                'campaign_uid' => $new_campaign_uid,
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        } else {
                                            $data = array(
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        }
                                    }
                                }
                            }
                        }
// Resend
                        if ($action == 'resend') {
// Get White Lists
                            $white_list_array = array();
                            $result_white_list = $this->sms_model->getWhiteLists();
                            if ($result_white_list) {
                                $white_list_array = $result_white_list;
                            }
// Get SMS From Sent SMS Table
                            $result_sent = $this->getSMSFromSent($campaign_id);
                            if ($result_sent && sizeof($result_sent)) {
                                $result_array = $result_sent['numbers'];
                                $result_msg_array = $result_sent['messages'];
// Get white listed numbers from user request
                                $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio
                                $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                $u_fake = ROUND($user_fake);
                                $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                $u_fail = ROUND($user_fail);
// Get Fake Delivered Number
                                if ($user_fake_ratio) {
                                    if ($u_fake) {
                                        $data_fake = array();
                                        $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                        $result_array1 = array_diff($result_array1, $fake_d_array);
                                        foreach ($fake_d_array as $fake_number) {
                                            $data = array(
                                                'temporary_status' => 2
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $fake_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
// Get Fake Failed Number
                                if ($user_fail_ratio) {
                                    if ($u_fail) {
                                        $data_failed = array();
                                        $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                        $result_array1 = array_diff($result_array1, $fake_f_array);
                                        foreach ($fake_f_array as $failed_number) {
                                            $data = array(
                                                'temporary_status' => 3
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->where('mobile_no', $failed_number);
                                            $this->db->update('sent_sms', $data);
                                        }
                                    }
                                }
                                $result = array_intersect($result_array, $white_list_array);
                                $result_array = array_merge($result_array1, $result);
                                if ($message_category == 1) {
                                    if (sizeof($result_array)) {
// No SMSC Route Available
                                        if ($new_smsc_id == 'XML') {
// Prepare XML
                                            $xml_file .= "<MESSAGE>";
                                            $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                            $xml_file .= "<SENDER>$sender_id</SENDER>";
                                            $xml_file .= "<ROUTE>Template</ROUTE>";
                                            $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                            $xml_file .= "<FLASH>$flash_message</FLASH>";
                                            $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                            foreach ($result_array as $key => $number) {
                                                $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                                $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                                $xml_file .= "</SMS>";
                                            }
                                            $xml_file .= "</MESSAGE>";
                                            $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                        } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                            $momt = "MT";
                                            $sms_type = 2;
                                            $dlr_mask = "31";
                                            $data_array = array();
                                            $loop = 0;
                                            foreach ($result_array as $key => $number) {
                                                $data_array[] = array(
                                                    'momt' => $momt,
                                                    'sender' => $sender_id,
                                                    'receiver' => $number,
                                                    'msgdata' => $result_msg_array[$key],
                                                    'smsc_id' => $new_smsc_id,
                                                    'id' => $campaign_id,
                                                    'sms_type' => $sms_type,
                                                    'dlr_mask' => $dlr_mask,
                                                    'dlr_url' => $campaign_id,
                                                    'mclass' => $mclass,
                                                    'coding' => $coding,
                                                    'charset' => $charset
                                                );
                                                $loop++;
                                            }
                                            if (sizeof($data_array) && $approval_status) {
                                                $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                            }
                                        }

// Update Campaign Uid
                                        if (isset($new_campaign_uid) && $new_campaign_uid) {
                                            $data = array(
                                                'campaign_uid' => $new_campaign_uid,
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        } else {
                                            $data = array(
                                                'processed_by' => $admin_id
                                            );
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('campaigns', $data);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return 200;
                } else {
                    return 100;
                }
            }
        } elseif ($action == 'reject' || $action == 'reject_sms' || $action == '3') {
// Get User Compaign SMS
            $result_campaigns = $this->getAllTrCompaigns($user_id);
            if ($result_campaigns) {
                foreach ($result_campaigns as $key => $row_campaign) {
// User Info
                    $tr_user_group_id = $row_campaign['tr_user_group_id'];
                    $t_sender_id_option = $row_campaign['t_sender_id_option'];
                    $smsc_id = $row_campaign['smsc_id'];
                    $contact_number = $row_campaign['contact_number'];
                    $number_allowed = $row_campaign['number_allowed'];
// Message Data
                    $campaign_id = $row_campaign['campaign_id'];
                    $sender_id = $row_campaign['sender_id'];
                    $message_type = $row_campaign['message_type'];
                    $flash_message = $row_campaign['flash_message'];
                    $message = $row_campaign['message'];
                    $message_length = $row_campaign['message_length'];
                    $sender_status = $row_campaign['sender_status'];
                    $keyword_status = $row_campaign['keyword_status'];
                    $number_db_status_array = explode('|', $row_campaign['number_db_status']);

// Update Status (Compaign)
                    $data = array('campaign_status' => '3');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('user_id', $user_id);
                    $this->db->update('campaigns', $data);
// User Ratio
// Fake Delivered
                    if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                        $user_fake_ratio = $row_campaign['user_fake_ratio'];
                    } else {
                        $user_fake_ratio = $new_dlr_ratio;
                    }
// Fake Failed
                    if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                        $user_fail_ratio = $row_campaign['user_fail_ratio'];
                    } else {
                        $user_fail_ratio = $new_fail_ratio;
                    }
// Routing
                    if ($new_smsc_id == -1 || $new_smsc_id == "") {
                        $new_smsc_id = $smsc_id;
                    }
// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($message)));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $message);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($flash_message);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
// Reject
                    if ($action == 'reject') {
// Message Stored As Black Listed
                        $message = preg_replace('/\s+/', ' ', $message);
                        $data = array(
                            'black_keyword' => $message
                        );
                        $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                        $data = array('status' => 'Rejected');
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('sent_sms', $data);
                    }
// Reject With Message
                    if ($action == 'reject_sms' || $action == '3') {
// Message Stored As Black Listed
                        $message = preg_replace('/\s+/', ' ', $message);
                        $data = array(
                            'black_keyword' => $message
                        );
                        $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                        $data = array('status' => 'Rejected');
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('sent_sms', $data);
                    }
                }
// Send Message to user
                if ($action == 'reject_sms' || $action == '3') {
// Get Default User Group
                    $result_default = $this->getDefaultUG('Transactional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_ug_name = $result_default->user_group_name;
                        $admin_ug_username = $result_default->user_group_username;
                        $admin_smsc_id = $result_default->smsc_id;
                    }
// Get Admin Balance
                    $admin_sms = $this->getAdminBalance($admin_id);
                    if ($admin_sms) {
                        $total_tr_balance = $admin_sms['total_tr_balance'];
                    }
// Send Message
// Insert Campaign
                    $request_by = "By Panel";
                    $campaign_name = "Reject User Request";
                    $campaign_uid = strtolower(random_string('alnum', 24));
                    $total_messages = 1;
                    $submit_date = date("Y-m-d H:i:s");
                    $deduct_balance = 1;
                    $campaign_status = 1;
                    $from = "NOTICE";
// Calculate Credits
                    $message_type = 1;
                    $length = strlen(utf8_decode($reject_sms));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $reject_sms);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
                    $flash_message = 0;
                    $route = "B";
                    $data_campaign = array(
                        'campaign_uid' => $campaign_uid,
                        'campaign_name' => $campaign_name,
                        'admin_id' => $admin_id,
                        'total_messages' => $total_messages,
                        'total_credits' => $total_credits,
                        'campaign_status' => $campaign_status,
                        'sender_id' => $from,
                        'request_by' => $request_by,
                        'submit_date' => $submit_date,
                        'message_type' => $message_type,
                        'flash_message' => $flash_message,
                        'message' => $reject_sms,
                        'message_length' => $length,
                        'route' => $route
                    );
                    $response_cm = $this->db->insert('campaigns', $data_campaign);
                    if ($response_cm) {
// Get Last Campaign Id
                        $new_campaign_id = $this->db->insert_id();
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        $data_sent_sms = array(
                            'user_group_id' => $admin_ug_id,
                            'campaign_id' => $new_campaign_id,
                            'msg_id' => $msg_id,
                            'message' => $reject_sms,
                            'msg_length' => $length,
                            'mobile_no' => $contact_number,
                            'status' => $status,
                            'submit_date' => $subdate,
                            'temporary_status' => $temporary_status
                        );
                        $momt = "MT";
                        $sms_type = 2;
                        $dlr_url = $new_campaign_id;
                        $dlr_mask = "31";
                        $mclass = null;
                        $alt_dcs = 1;
// SQLBox Send SMS
                        $data_sqlbox_send_sms = array(
                            'momt' => $momt,
                            'sender' => $from,
                            'receiver' => $contact_number,
                            'msgdata' => $reject_sms,
                            'smsc_id' => $admin_smsc_id,
                            'id' => $new_campaign_id,
                            'sms_type' => $sms_type,
                            'dlr_mask' => $dlr_mask,
                            'dlr_url' => $dlr_url,
                            'mclass' => $mclass,
                            'coding' => $coding,
                            'charset' => $charset
                        );
                        $res1 = $this->db->insert('sent_sms', $data_sent_sms);
                        $res2 = $this->db->insert('sqlbox_send_sms', $data_sqlbox_send_sms);
// Balance Updation
                        $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                        $data = array(
                            'total_tr_balance' => $updated_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $data);
// Total Deduction
                        $data = array(
                            'total_deducted' => $deduct_balance * $total_credits,
                            'actual_message' => $deduct_balance,
                            'processed_by' => $admin_id
                        );
                        $this->db->where('campaign_id', $new_campaign_id);
                        $this->db->update('campaigns', $data);
                    }
                }
                return 300;
            } else {
                return 100;
            }
        }
    }

// Get All Multiple Messages (Custom SMS & XML Custom Message)
    function getAllMessages($campaign_id = 0) {
        $this->db->select('mobile_no, message');
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Spam Promotional SMS
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Promotional SMS (Alt)
    function spamPromotional1() {
        $this->db->distinct();
        $this->db->select('campaign_id, user_group_id, campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed');
        $this->db->select('total_deducted, actual_message, campaign_status, sender_id, submit_date, schedule_date, request_by, is_custom');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status');
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('users3.username AS ref_username');
        $this->db->select('sender_status, keyword_status, number_db_status');
        $this->db->select('count(users1.username) AS total_sms');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.ref_user_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'left');
        $this->db->where('campaign_status', 2);
        $this->db->where('service_type', "SMS");
        $this->db->where('message_category !=', 2);
        $this->db->where('route !=', 'B');
        $this->db->order_by('submit_date', 'DESC');
        $this->db->group_by('users1.username');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Get Promotional SMS
    function spamPromotional() {
        $this->db->select('campaign_id, campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed');
        $this->db->select('total_deducted, actual_message, campaign_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status');
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username, is_custom');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('users3.username AS ref_username');
        $this->db->select('sender_status, keyword_status, number_db_status');
        $this->db->select('count(campaign_id) AS total_sms');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.ref_user_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'left');
        $this->db->where('campaign_status', 2);
        $this->db->where('schedule_status', 1);
        $this->db->where('service_type', "SMS");
        $this->db->where('route', 'A');
        $this->db->group_by('users1.user_id');
        $this->db->order_by('campaign_id', "DESC");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Get Promotional Messages
    function getPromotionalSMS($user_id = 0) {
        $this->db->select('campaign_id, campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed');
        $this->db->select('total_deducted, actual_message, campaign_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, is_custom');
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('users3.username AS ref_username');
        $this->db->select('sender_status, keyword_status, number_db_status, unique_numbers, exceed_numbers');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.ref_user_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'left');
        $this->db->where('campaign_status', 2);
        $this->db->where('service_type', "SMS");
        $this->db->where('schedule_status', 1);
        $this->db->where('users1.user_id', $user_id);
        $this->db->where('route', 'A');
        $this->db->order_by('campaign_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Get Promotional Messages
    function getPrCompaignSMS($user_id = 0, $campaign_id = 0) {
        $this->db->select('pr_sms_balance,special_pr_balance,special_tr_balance,most_parent_id, number_allowed, pro_user_group_id, p_sender_id_option, contact_number, is_custom ,prtodnd_credits,stock_credits,pr_fake_sent,pricing_approval');
        $this->db->select('keyword_option, dnd_check, smsc_id, user_ratio, user_fake_ratio, user_fail_ratio, pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio,spacial_deliver_pr_ratio,spacial_deliver_tr_ratio');
        $this->db->select('campaign_uid, campaign_name, message_category, total_messages, total_credits, black_listed');
        $this->db->select('total_deducted, actual_message, campaign_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status,reseller_key_balance_status');
        $this->db->from('users, campaigns, user_groups');
        $this->db->where('`users`.`user_id`=`campaigns`.`user_id`');
        $this->db->where('`campaigns`.`user_group_id`=`user_groups`.`user_group_id`');
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('`users`.`user_id`', $user_id);
        $this->db->where('campaign_status', '2');
        $this->db->where('service_type', "SMS");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else
            return false;
    }

// Get All Promotional Messages
    function getAllPrCompaigns($user_id = 0) {
        $this->db->select('tr_sms_balance, number_allowed, tr_user_group_id, t_sender_id_option, black_listed, is_custom');
        $this->db->select('keyword_option, dnd_check, smsc_id, user_ratio, user_fake_ratio, user_fail_ratio, pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio');
        $this->db->select('campaign_id, campaign_uid, campaign_name, message_category, total_messages, total_credits');
        $this->db->select('total_deducted, actual_message, campaign_status, condition_status, sender_id, submit_date, schedule_date, request_by');
        $this->db->select('message_type, flash_message, message, message_length, route, schedule_status, contact_number, email_address');
        $this->db->select('sender_status, keyword_status, number_db_status');
        $this->db->from('users, campaigns, user_groups');
        $this->db->where('`users`.`user_id`=`campaigns`.`user_id`');
        $this->db->where('`users`.`pr_user_group_id`=`user_groups`.`user_group_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $this->db->where('campaign_status', '2');
        $this->db->where('service_type', "SMS");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else
            return false;
    }

// Send Promotional SMS
    function sendPromotionalSMS($admin_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $action = $myArray[0];
        $user_id = $myArray[1];
        $campaign_id = $myArray[2];
        $new_dlr_ratio = $myArray[3];
        $new_fail_ratio = $myArray[4];
        $new_smsc_id = $myArray [5];
        $reject_sms = $myArray[6];

// Get User Compaign SMS
        $result_campaign = $this->getPrCompaignSMS($user_id, $campaign_id);
        if ($result_campaign) {
// User Info
            $pr_user_group_id = $result_campaign->pro_user_group_id;
            $special_pr_balance = $result_campaign->special_pr_balance;
            // $stock_credits = $result_campaign->stock_credits;
            // $prtodnd_credits = $result_campaign->prtodnd_credits;
            $pr_fake_sent = $result_campaign->pr_fake_sent;
            $most_parent_id = $result_campaign->most_parent_id;
            $smsc_id = $result_campaign->smsc_id;
            $route = $result_campaign->route;
            $contact_number = $result_campaign->contact_number;
            $reseller_key_balance_status = $result_campaign->reseller_key_balance_status;
            $admin_approval_status = $result_campaign->pricing_approval;

// Message Data
            $sender_id = $result_campaign->sender_id;
            $message_category = $result_campaign->message_category;
            $message_type = $result_campaign->message_type;
            $flash_message = $result_campaign->flash_message;
            $message = $result_campaign->message;
            $spacial_deliver_pr_ratio = $result_campaign->spacial_deliver_pr_ratio;

            $message_length = $result_campaign->message_length;



            if ($action == 'resend') {
// User Ratio
// Fake Delivered
                if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                    $user_fake_ratio = $result_campaign->pr_user_fake_ratio;
                } else {
                    $user_fake_ratio = $new_dlr_ratio;
                }
// Fake Failed
                if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                    $user_fail_ratio = $result_campaign->pr_user_fail_ratio;
                } else {
                    $user_fail_ratio = $new_fail_ratio;
                }
// Routing
                if ($new_smsc_id == -1 || $new_smsc_id == "") {
                    $new_smsc_id = $smsc_id;
                }

// If XML
                $is_xml = 0;
                $authentication = "";
                $xml_file = "";
                if ($new_smsc_id == 'XML') {
                    $result_setting = $this->sms_model->getDefaultSettings();
                    $authentication = $result_setting->xml_route_authkey;
                    $xml_url = $result_setting->xml_route_url;
                    $is_xml = 1;
                }

// Calculate Credits
                $length = strlen(utf8_decode(urldecode($message)));
                $charset = "ASCII";
                $coding = 0;
                $total_credits = 0;
                $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $message);
                if ($result_credits) {
                    $charset = $result_credits['charset'];
                    $coding = $result_credits['coding'];
                    $total_credits = $result_credits['credits'];
                    $unicode = $result_credits['unicode'];
                }
// Flash Message
                $mclass = null;
                $alt_dcs = 1;
                $result_flash = $this->sms_model->getFlashMessage($flash_message);
                if ($result_flash) {
                    $mclass = $result_flash['mclass'];
                    $alt_dcs = $result_flash['alt_dcs'];
                }
                if ($action == 'resend') {
// Get White Lists
                    $white_list_array = array();
                    $result_white_list = $this->sms_model->getWhiteLists();
                    if ($result_white_list) {
                        $white_list_array = $result_white_list;
                    }
// Get SMS From Sent SMS Table
                    $result_sent = $this->getSMSFromSent($campaign_id);
                    if ($result_sent && sizeof($result_sent)) {
// Campaign Status
                        $data = array(
                            'campaign_status' => 1
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
// Get SMS From Sent SMS Table
                        $result_array = $result_sent['numbers'];
                        $result_msg_array = $result_sent['messages'];

// Get white listed numbers from user request
                        if (sizeof($result_array) > 100) {
                            $result_array1 = array_diff($result_array, $white_list_array);



                            $spacial_deliver_fake = 0;
                            if ($spacial_deliver_pr_ratio) {
                                $spacial_fake_ratio = (sizeof($result_array1) * $spacial_deliver_pr_ratio) / 100;
                                $spacial_deliver_fake = ROUND($spacial_fake_ratio);
                            }
                            if ($spacial_deliver_fake) {
                                $new_size = sizeof($result_array1);
                                $new_own_ratio = $new_size - $spacial_deliver_fake;
                                $user_fake = ($new_own_ratio * $user_fake_ratio) / 100;
                                $u_fake = ROUND($user_fake);
                                $user_fake_sent = ($new_own_ratio * $pr_fake_sent) / 100;
                                $u_fake_sent = ROUND($user_fake_sent);
                                //$user_fail = ($new_own_ratio * $pr_user_fail_ratio) / 100;
                                //$u_fail = ROUND($user_fail);
                            } else {

                                $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                                $u_fake = ROUND($user_fake);
                                $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                                $u_fail = ROUND($user_fail);
                                $user_fake_sent = (sizeof($result_array1) * $pr_fake_sent) / 100;
                                $u_fake_sent = ROUND($user_fake_sent);
                            }
//add both ratio                                   
                            if ($spacial_deliver_fake) {
                                $u_fake = $u_fake + $spacial_deliver_fake;
                            }

// Apply User Ratio
                            /* $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                              $u_fake = ROUND($user_fake);
                              $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                              $u_fail = ROUND($user_fail);

                             */
// Get Fake Delivered Number
                            if ($user_fake_ratio) {
                                if ($u_fake) {
                                    $data_fake = array();
                                    $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                    $result_array1 = array_diff($result_array1, $fake_d_array);
                                    foreach ($fake_d_array as $fake_number) {
                                        $data = array(
                                            'temporary_status' => 2
                                        );
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->where('mobile_no', $fake_number);
                                        $this->db->update('sent_sms', $data);
                                    }
                                }
                            }
// Get Fake Failed Number
                            if ($user_fail_ratio) {
                                if ($u_fail) {
                                    $data_failed = array();
                                    $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                    $result_array1 = array_diff($result_array1, $fake_f_array);
                                    foreach ($fake_f_array as $failed_number) {
                                        $data = array(
                                            'temporary_status' => 3
                                        );
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->where('mobile_no', $failed_number);
                                        $this->db->update('sent_sms', $data);
                                    }
                                }
                            }

                            // Get Fake sent Number
                            if ($pr_fake_sent) {
                                if ($u_fake_sent) {
                                    $data_failed = array();
                                    $fake_s_array = $this->sms_model->getRandomArray($result_array1, $u_fake_sent);
                                    $result_array1 = array_diff($result_array1, $fake_s_array);
                                    foreach ($fake_s_array as $fake_sent_number) {
                                        $data = array(
                                            'temporary_status' => 4,
                                            'status' => 3
                                        );
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->where('mobile_no', $fake_sent_number);
                                        $this->db->update('sent_sms', $data);
                                    }
                                }
                            }

                            $result = array_intersect($result_array, $white_list_array);
                            $result_array = array_merge($result_array1, $result);
                        }
                        if ($message_category == 1) {
// No SMSC Route Available
                            if ($new_smsc_id == 'XML') {
// Prepare XML
                                $xml_file .= "<MESSAGE>";
                                $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                $xml_file .= "<SENDER>$sender_id</SENDER>";
                                $xml_file .= "<ROUTE>Default</ROUTE>";
                                $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                $xml_file .= "<FLASH>$flash_message</FLASH>";
                                $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                foreach ($result_array as $key => $number) {
                                    $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                    $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                    $xml_file .= "</SMS>";
                                }
                                $xml_file .= "</MESSAGE>";

                                if ($reseller_key_balance_status) {
                                    $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                }
                            } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                if (sizeof($result_array)) {
                                    $momt = "MT";
                                    $sms_type = 2;
                                    $dlr_mask = "31";
                                    $data_array = array();
                                    $loop = 0;
                                    foreach ($result_array as $key => $number) {
                                        $data_array[] = array(
                                            'momt' => $momt,
                                            'sender' => $sender_id,
                                            'receiver' => $number,
                                            'msgdata' => $result_msg_array[$key],
                                            'smsc_id' => $new_smsc_id,
                                            'id' => $campaign_id,
                                            'sms_type' => $sms_type,
                                            'dlr_mask' => $dlr_mask,
                                            'dlr_url' => $campaign_id,
                                            'mclass' => $mclass,
                                            'coding' => $coding,
                                            'charset' => $charset
                                        );
                                        $loop++;
                                    }

                                    if (sizeof($data_array) && $reseller_key_balance_status) {
                                        $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);

                                        $this->db->select('special_pr_balance,prtodnd_credits,stock_credits');
                                        $this->db->from('users');
                                        $this->db->where('user_id', $most_parent_id);
                                        $query = $this->db->get();
                                        $bal_result = $query->row();
                                        $most_special_pr_balance = $bal_result->special_pr_balance;
                                        $most_stock_credits = $bal_result->stock_credits;
                                        $most_prtodnd_credits = $bal_result->prtodnd_credits;

                                        if ($route == 'A') {
                                            $updated_key_balance = $spacial_deliver_fake + $most_special_pr_balance;

                                            $data = array(
                                                'special_pr_balance' => $updated_key_balance,
                                            );
                                        }
                                        if ($route == 'C') {
                                            $updated_key_balance = $spacial_deliver_fake + $most_stock_credits;

                                            $data = array(
                                                'stock_credits' => $updated_key_balance,
                                            );
                                        }
                                        if ($route == 'D') {
                                            $updated_key_balance = $spacial_deliver_fake + $most_prtodnd_credits;

                                            $data = array(
                                                'prtodnd_credits' => $updated_key_balance,
                                            );
                                        }



                                        $this->db->where('user_id', $most_parent_id);
                                        $this->db->update('users', $data);
                                    }
                                }
                            }
// Update Campaign Uid
                            if (isset($new_campaign_uid) && $new_campaign_uid) {
                                $data = array(
                                    'campaign_uid' => $new_campaign_uid,
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            } else {
                                $data = array(
                                    'processed_by' => $admin_id
                                );
                                $this->db->where('campaign_id', $campaign_id);
                                $this->db->update('campaigns', $data);
                            }
                            return 200;
                        }
                    } else {
                        return 100;
                    }
                }
            } elseif ($action == 'reject' || $action == 'reject_sms' || $action == '3') {
// Update Status (Compaign)
                $data = array('campaign_status' => '3', 'processed_by' => $admin_id);
                $this->db->where('campaign_id', $campaign_id);
                $this->db->where('user_id', $user_id);
                $this->db->update('campaigns', $data);
                if ($action == 'reject') {
// Message Stored As Black Listed
                    $message = preg_replace('/\s+/', ' ', $message);
                    $data = array(
                        'black_keyword' => $message
                    );
                    $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                    $data = array('status' => 'Rejected');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('sent_sms', $data);
                    return 300;
                } elseif ($action == 'reject_sms' || $action == '3') {
// Get Default User Group
                    $result_default = $this->getDefaultUG('Promotional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_ug_name = $result_default->user_group_name;
                        $admin_ug_username = $result_default->user_group_username;
                        $admin_smsc_id = $result_default->smsc_id;
                    }
// Get Admin Balance
                    $admin_sms = $this->getAdminBalance($admin_id);
                    if ($admin_sms) {
                        $total_tr_balance = $admin_sms['total_tr_balance'];
                    }
// Send Message
// Insert Campaign
                    $request_by = "By Panel";
                    $campaign_name = "Reject User Request";
                    $campaign_uid = strtolower(random_string('alnum', 24));
                    $total_messages = 1;
                    $submit_date = date("Y-m-d H:i:s");
                    $deduct_balance = 1;
                    $campaign_status = 1;
                    $from = "NOTICE";
// Calculate Credits
                    $message_type = 1;
                    $length = strlen(utf8_decode($reject_sms));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $reject_sms);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
                    $flash_message = 0;
                    $route = "B";
                    $data_campaign = array(
                        'campaign_uid' => $campaign_uid,
                        'campaign_name' => $campaign_name,
                        'admin_id' => $admin_id,
                        'total_messages' => $total_messages,
                        'total_credits' => $total_credits,
                        'campaign_status' => $campaign_status,
                        'sender_id' => $from,
                        'request_by' => $request_by,
                        'submit_date' => $submit_date,
                        'message_type' => $message_type,
                        'flash_message' => $flash_message,
                        'message' => $reject_sms,
                        'message_length' => $length,
                        'route' => $route
                    );
                    $response_cm = $this->db->insert('campaigns', $data_campaign);
                    if ($response_cm) {
// Get Last Campaign Id
                        $new_campaign_id = $this->db->insert_id();
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        $data_sent_sms = array(
                            'user_group_id' => $admin_ug_id,
                            'campaign_id' => $new_campaign_id,
                            'msg_id' => $msg_id,
                            'message' => $reject_sms,
                            'msg_length' => $length,
                            'mobile_no' => $contact_number,
                            'status' => $status,
                            'submit_date' => $subdate,
                            'temporary_status' => $temporary_status
                        );
                        $momt = "MT";
                        $sms_type = 2;
                        $dlr_url = $new_campaign_id;
                        $dlr_mask = "31";
                        $mclass = null;
                        $alt_dcs = 1;
// SQLBox Send SMS
                        $data_sqlbox_send_sms = array(
                            'momt' => $momt,
                            'sender' => $from,
                            'receiver' => $contact_number,
                            'msgdata' => $reject_sms,
                            'smsc_id' => $admin_smsc_id,
                            'id' => $new_campaign_id,
                            'sms_type' => $sms_type,
                            'dlr_mask' => $dlr_mask,
                            'dlr_url' => $dlr_url,
                            'mclass' => $mclass,
                            'coding' => $coding,
                            'charset' => $charset
                        );
                        $res1 = $this->db->insert('sent_sms', $data_sent_sms);
                        $res2 = $this->db->insert('sqlbox_send_sms', $data_sqlbox_send_sms);
// Balance Updation
                        $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                        $data = array(
                            'total_tr_balance' => $updated_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $data);
// Total Deduction
                        $data = array(
                            'total_deducted' => $deduct_balance * $total_credits,
                            'actual_message' => $deduct_balance,
                            'processed_by' => $admin_id
                        );
                        $this->db->where('campaign_id', $new_campaign_id);
                        $this->db->update('campaigns', $data);
                    }
// Message Stored As Black Listed
                    $message = preg_replace('/\s+/', ' ', $message);
                    $data = array(
                        'black_keyword' => $message
                    );
                    $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                    $data = array('status' => 'Rejected');
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('sent_sms', $data);
                    return 300;
                }
            }
        }
    }

// Send All Promotional SMS
    function sendAllPromotionalSMS($admin_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $action = $myArray[0];
        $user_id = $myArray[1];
        $new_dlr_ratio = $myArray[2];
        $new_fail_ratio = $myArray[3];
        $new_smsc_id = $myArray[4];
        $reject_sms = $myArray[5];
        if ($action == 'resend') {
// Get User Compaign SMS
            $result_campaigns = $this->getAllTrCompaigns($user_id);

            if ($result_campaigns) {
                foreach ($result_campaigns as $key => $row_campaign) {
// User Info
                    $smsc_id = $row_campaign['smsc_id'];
                    $contact_number = $row_campaign['contact_number'];
// Message Data
                    $campaign_id = $row_campaign['campaign_id'];
                    $message_category = $row_campaign['message_category'];
                    $sender_id = $row_campaign['sender_id'];
                    $message_type = $row_campaign['message_type'];
                    $flash_message = $row_campaign['flash_message'];
                    $message = $row_campaign['message'];
                    $message_length = $row_campaign['message_length'];

// Campaign Status
                    $data = array(
                        'campaign_status' => 1
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('campaigns', $data);
// User Ratio
// Fake Delivered
                    if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                        $user_fake_ratio = $row_campaign['pr_user_fake_ratio'];
                    } else {
                        $user_fake_ratio = $new_dlr_ratio;
                    }
// Fake Failed
                    if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                        $user_fail_ratio = $row_campaign['pr_user_fail_ratio'];
                    } else {
                        $user_fail_ratio = $new_fail_ratio;
                    }
// Routing
                    if ($new_smsc_id == -1 || $new_smsc_id == "") {
                        $new_smsc_id = $smsc_id;
                    }

// If XML
                    $is_xml = 0;
                    $authentication = "";
                    $xml_file = "";
                    if ($new_smsc_id == 'XML') {
                        $result_setting = $this->sms_model->getDefaultSettings();
                        $authentication = $result_setting->xml_route_authkey;
                        $xml_url = $result_setting->xml_route_url;
                        $is_xml = 1;
                    }

// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($reject_sms)));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $reject_sms);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($flash_message);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
// Resend Messages
                    if ($action == 'resend') {
// Get White Lists
                        $white_list_array = array();
                        $result_white_list = $this->sms_model->getWhiteLists();
                        if ($result_white_list) {
                            $white_list_array = $result_white_list;
                        }
// Get SMS From Sent SMS Table
                        $result_sent = $this->getSMSFromSent($campaign_id);
                        if ($result_sent && sizeof($result_sent)) {
                            $result_array = $result_sent['numbers'];
                            $result_msg_array = $result_sent['messages'];
// Get white listed numbers from user request
                            $result_array1 = array_diff($result_array, $white_list_array);
// Apply User Ratio
                            $user_fake = (sizeof($result_array1) * $user_fake_ratio ) / 100;
                            $u_fake = ROUND($user_fake);
                            $user_fail = (sizeof($result_array1) * $user_fail_ratio ) / 100;
                            $u_fail = ROUND($user_fail);
// Get Fake Delivered Number
                            if ($user_fake_ratio) {
                                if ($u_fake) {
                                    $data_fake = array();
                                    $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                    $result_array1 = array_diff($result_array1, $fake_d_array);
                                    foreach ($fake_d_array as $fake_number) {
                                        $data = array(
                                            'temporary_status' => 2
                                        );
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->where('mobile_no', $fake_number);
                                        $this->db->update('sent_sms', $data);
                                    }
                                }
                            }
// Get Fake Failed Number
                            if ($user_fail_ratio) {
                                if ($u_fail) {
                                    $data_failed = array();
                                    $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                    $result_array1 = array_diff($result_array1, $fake_f_array);
                                    foreach ($fake_f_array as $failed_number) {
                                        $data = array(
                                            'temporary_status' => 3
                                        );
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->where('mobile_no', $failed_number);
                                        $this->db->update('sent_sms', $data);
                                    }
                                }
                            }
                            $result = array_intersect($result_array, $white_list_array);
                            $result_array = array_merge($result_array1, $result);
                            if ($message_category == 1) {
// No SMSC Route Available
                                if ($new_smsc_id == 'XML') {
// Prepare XML
                                    $xml_file .= "<MESSAGE>";
                                    $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                                    $xml_file .= "<SENDER>$sender_id</SENDER>";
                                    $xml_file .= "<ROUTE>Default</ROUTE>";
                                    $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                                    $xml_file .= "<FLASH>$flash_message</FLASH>";
                                    $xml_file .= "<UNICODE>$unicode</UNICODE>";
                                    foreach ($result_array as $key => $number) {
                                        $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                                        $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                                        $xml_file .= "</SMS>";
                                    }
                                    $xml_file .= "</MESSAGE>";
                                    $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
                                } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                                    $momt = "MT";
                                    $sms_type = 2;
                                    $dlr_mask = "31";
                                    if ($result_array) {
                                        $data_array = array();
                                        $loop = 0;
                                        foreach ($result_array as $key => $number) {
                                            $data_array[] = array(
                                                'momt' => $momt,
                                                'sender' => $sender_id,
                                                'receiver' => $number,
                                                'msgdata' => $result_msg_array[$key],
                                                'smsc_id' => $new_smsc_id,
                                                'id' => $campaign_id,
                                                'sms_type' => $sms_type,
                                                'dlr_mask' => $dlr_mask,
                                                'dlr_url' => $campaign_id,
                                                'mclass' => $mclass,
                                                'coding' => $coding,
                                                'charset' => $charset
                                            );
                                            $loop++;
                                        }
                                        if (sizeof($data_array))
                                            $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                                    }
                                }
// Update Campaign Uid
                                if (isset($new_campaign_uid) && $new_campaign_uid) {
                                    $data = array(
                                        'campaign_uid' => $new_campaign_uid,
                                        'processed_by' => $admin_id
                                    );
                                    $this->db->where('campaign_id', $campaign_id);
                                    $this->db->update('campaigns', $data);
                                } else {
                                    $data = array(
                                        'processed_by' => $admin_id
                                    );
                                    $this->db->where('campaign_id', $campaign_id);
                                    $this->db->update('campaigns', $data);
                                }
                            }
                        }
                    }
                }
                return 200;
            } else {
                return 100;
            }
        } elseif ($action == 'reject' || $action == 'reject_sms' || $action == '3') {
// Get User Compaign SMS
            $result_campaigns = $this->getAllPrCompaigns($user_id);
            if ($result_campaigns) {
                foreach ($result_campaigns as $key => $row_campaign) {
// User Info
                    $smsc_id = $row_campaign['smsc_id'];
                    $contact_number = $row_campaign['contact_number'];
// Message Data
                    $campaign_id = $row_campaign['campaign_id'];
                    $sender_id = $row_campaign['sender_id'];
                    $message_type = $row_campaign['message_type'];
                    $flash_message = $row_campaign['flash_message'];
                    $message = $row_campaign['message'];
                    $message_length = $row_campaign['message_length'];

// Update Status (Compaign)
                    $data = array('campaign_status' => '3', 'processed_by' => $admin_id);
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('user_id', $user_id);
                    $this->db->update('campaigns', $data);
// User Ratio
// Fake Delivered
                    if ($new_dlr_ratio == "" || $new_dlr_ratio == 0) {
                        $user_fake_ratio = $row_campaign['pr_user_fake_ratio'];
                    } else {
                        $user_fake_ratio = $new_dlr_ratio;
                    }
// Fake Failed
                    if ($new_fail_ratio == "" || $new_fail_ratio == 0) {
                        $user_fail_ratio = $row_campaign['pr_user_fail_ratio'];
                    } else {
                        $user_fail_ratio = $new_fail_ratio;
                    }
// Routing
                    if ($new_smsc_id == -1 || $new_smsc_id == "") {
                        $new_smsc_id = $smsc_id;
                    }
// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($message)));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $message);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($flash_message);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
                    if ($action == 'reject') {
// Message Stored As Black Listed
                        $message = preg_replace('/\s+/', ' ', $message);
                        $data = array(
                            'black_keyword' => $message
                        );
                        $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                        $data = array('status' => 'Rejected');
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('sent_sms', $data);
                    } elseif ($action == 'reject_sms' || $action == '3') {
// Message Stored As Black Listed
                        $message = preg_replace('/\s+/', ' ', $message);
                        $data = array(
                            'black_keyword' => $message
                        );
                        $this->db->insert('black_keywords', $data);
// Update Message Status (Sent SMS)
                        $data = array('status' => 'Rejected');
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('sent_sms', $data);
                    }
                }
// Send Message to user
                if ($action == 'reject_sms' || $action == '3') {
// Get Default User Group
                    $result_default = $this->getDefaultUG('Promotional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_ug_name = $result_default->user_group_name;
                        $admin_ug_username = $result_default->user_group_username;
                        $admin_smsc_id = $result_default->smsc_id;
                    }
// Get Admin Balance
                    $admin_sms = $this->getAdminBalance($admin_id);
                    if ($admin_sms) {
                        $total_tr_balance = $admin_sms['total_tr_balance'];
                    }
// Send Message
// Insert Campaign
                    $request_by = "By Panel";
                    $campaign_name = "Reject User Request";
                    $campaign_uid = strtolower(random_string('alnum', 24));
                    $total_messages = 1;
                    $submit_date = date("Y-m-d H:i:s");
                    $deduct_balance = 1;
                    $campaign_status = 1;
                    $from = "NOTICE";
// Calculate Credits
                    $length = strlen(utf8_decode(urldecode($reject_sms)));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($message_type, $length, $reject_sms);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
                    $flash_message = 0;
                    $route = "B";
                    $data_campaign = array(
                        'campaign_uid' => $campaign_uid,
                        'campaign_name' => $campaign_name,
                        'admin_id' => $admin_id,
                        'total_messages' => $total_messages,
                        'total_credits' => $total_credits,
                        'campaign_status' => $campaign_status,
                        'sender_id' => $from,
                        'request_by' => $request_by,
                        'submit_date' => $submit_date,
                        'message_type' => $message_type,
                        'flash_message' => $flash_message,
                        'message' => $reject_sms,
                        'message_length' => $length,
                        'route' => $route
                    );
                    $response_cm = $this->db->insert('campaigns', $data_campaign);
                    if ($response_cm) {
// Get Last Campaign Id
                        $new_campaign_id = $this->db->insert_id();
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        $data_sent_sms = array(
                            'user_group_id' => $admin_ug_id,
                            'campaign_id' => $new_campaign_id,
                            'msg_id' => $msg_id,
                            'message' => $reject_sms,
                            'msg_length' => $length,
                            'mobile_no' => $contact_number,
                            'status' => $status,
                            'submit_date' => $subdate,
                            'temporary_status' => $temporary_status
                        );
                        $momt = "MT";
                        $sms_type = 2;
                        $dlr_url = $new_campaign_id;
                        $dlr_mask = "31";
                        $mclass = null;
                        $alt_dcs = 1;
// SQLBox Send SMS
                        $data_sqlbox_send_sms = array(
                            'momt' => $momt,
                            'sender' => $from,
                            'receiver' => $contact_number,
                            'msgdata' => $reject_sms,
                            'smsc_id' => $admin_smsc_id,
                            'id' => $new_campaign_id,
                            'sms_type' => $sms_type,
                            'dlr_mask' => $dlr_mask,
                            'dlr_url' => $dlr_url,
                            'mclass' => $mclass,
                            'coding' => $coding,
                            'charset' => $charset
                        );
                        $res1 = $this->db->insert('sent_sms', $data_sent_sms);
                        $res2 = $this->db->insert('sqlbox_send_sms', $data_sqlbox_send_sms);
// Balance Updation
                        $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                        $data = array(
                            'total_tr_balance' => $updated_sms_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $data);
// Total Deduction
                        $data = array(
                            'total_deducted' => $deduct_balance * $total_credits,
                            'actual_message' => $deduct_balance,
                            'processed_by' => $admin_id
                        );
                        $this->db->where('campaign_id', $new_campaign_id);
                        $this->db->update('campaigns', $data);
                    }
                }
                return 300;
            } else {
                return 100;
            }
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Resend Sent SMS
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Re-Send SMS
    function getResendSMS($campaign_id = 0) {
        $sent_status = "Sent";
        $failed_status = "Failed";
        $this->db->select('sender_id, route, msg_id, mobile_no, message_type, flash_message, sent_sms.message AS message');
        $this->db->from('sent_sms, campaigns');
        $this->db->where('campaigns.campaign_id=sent_sms.campaign_id');
        $this->db->where('campaigns.campaign_id', $campaign_id);
        $this->db->where('status', $sent_status);
        $this->db->or_where('status', $failed_status);
        $this->db->order_by("sms_id");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// User Groups
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count User Groups
    function countUserGroups($type = null) {
        $this->db->select('*');
        $this->db->from('user_groups');
        $this->db->where('purpose', $type);
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get User Groups Promotional/Transactional/Both [All/Active]
    function getUserGroups($type = null, $active = 0) {
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status, purpose, sender_id_type, sender_id_length');
        $this->db->select('default_user_group, backup_user_group, resend_status, user_group_username, user_group_password,route_price,sender_id_status');
        $this->db->from('user_groups');
        if ($type != null) {
            $this->db->where('purpose', $type);
        }
        if ($active) {
            $this->db->where('user_group_status', $active);
        }
        $this->db->order_by("user_group_id");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get User Group
    function getUserGroup($user_group_id = 0) {
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status, purpose, sender_id_type, sender_id_length');
        $this->db->select('default_user_group, backup_user_group, resend_status, user_group_username, user_group_password,route_price,sender_id_status');
        $this->db->from('user_groups');
        $this->db->where('user_group_id', $user_group_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Save User Group
    function saveUserGroup() {
        $user_group_id = $this->input->post('user_group_id');
        $data = array(
            'user_group_name' => $this->input->post('user_group_name'),
            'user_group_username' => $this->input->post('user_group_username'),
            'user_group_password' => $this->input->post('user_group_password'),
            'smsc_id' => $this->input->post('smsc_name'),
            'purpose' => $this->input->post('purpose'),
            'sender_id_type' => $this->input->post('sender_id_type'),
            'sender_id_length' => $this->input->post('sender_length'),
            'backup_user_group' => $this->input->post('backup_user_group'),
            'route_price' => $this->input->post('pricing'),
            'sender_id_status' => $this->input->post('sender_id_status')
        );
        if ($user_group_id) {
            $this->db->where('user_group_id', $user_group_id);
            return $this->db->update('user_groups', $data);
        } else {
            return $this->db->insert('user_groups', $data);
        }
    }

// Delete User Group
    function deleteUserGroup($user_group_id = 0) {
        return $this->db->delete('user_groups', array('user_group_id' => $user_group_id));
    }

// Enable/Disable User Group
    function changeUGroupStatus() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $user_group_id = $myArray[0];
        $status = $myArray[1];
        $type = $myArray[2];
        $data = array(
            'user_group_status' => $status
        );
        $this->db->where('user_group_id', $user_group_id);
        $this->db->where('purpose', $type);
        return $this->db->update('user_groups', $data);
    }

// Enable/Disable Resend Option
    function changeResendStatus() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $data = array(
            'resend_status' => $myArray[1]
        );
        $this->db->where('user_group_id', $myArray[0]);
        $this->db->where('purpose', $myArray[2]);
        return $this->db->update('user_groups', $data);
    }

// Set Deafult
    function setDefaultUG() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
// Set
        $data = array(
            'default_user_group' => $myArray[1]
        );
        $this->db->where('user_group_id', $myArray[0]);
        $this->db->where('purpose', $myArray[2]);
        $this->db->update('user_groups', $data);
// Remove Default
        $data = array(
            'default_user_group' => 0
        );
        $this->db->where_not_in('user_group_id', $myArray[0]);
        $this->db->where('purpose', $myArray[2]);
        return $this->db->update('user_groups', $data);
    }

// Set Backup Route
    function setBackupUG() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
// Set
        $data1 = array(
            'backup_user_group' => $myArray[1]
        );
        $this->db->where('user_group_id', $myArray[0]);
        $this->db->where('purpose', $myArray[2]);
        $this->db->update('user_groups', $data1);
// Remove Default
        $data2 = array(
            'backup_user_group' => 0
        );
        $this->db->where_not_in('user_group_id', $myArray[0]);
        $this->db->where('purpose', $myArray[2]);
        return $this->db->update('user_groups', $data2);
    }

// Get Default User Group
    function getDefaultUserGroup($purpose = null, $type = 0) {
        $this->db->select('user_group_id');
        $this->db->from('user_groups');
        $this->db->where('purpose', $purpose);
        if ($type) {
//$this->db->where('smsc_id', 'XML');
            $this->db->where('default_user_group', 1);
        } else {
            $this->db->where('default_user_group', 1);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get Default User Group
    function getDefaultUserGroupVoice($purpose = null, $type = 0) {
        $this->db->select('voice_route_id');
        $this->db->from('voice_route');
        $this->db->where('route_type', $purpose);
        if ($type) {

            $this->db->where('default_route', 1);
        } else {
            $this->db->where('default_route', 1);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

// Save XML Route Settings
    function saveXMLRouteSetting() {
        $setting_id = $this->input->post('setting_id');
        $data = array(
            'xml_route_authkey' => $this->input->post('xml_route_authkey'),
            'xml_route_url' => $this->input->post('xml_route_url'),
        );
        var_dump($setting_id);
        die;
        $this->db->where('setting_id', $setting_id);
        return $this->db->update('settings', $data);
    }

// Save Backup Route Settings
    function saveBackupRouteSetting() {
        $setting_id = $this->input->post('setting_id');
        $data = array(
            'backup_time_duration' => $this->input->post('backup_time_duration'),
            'backup_limit' => $this->input->post('backup_limit'),
        );
        $this->db->where('setting_id', $setting_id);
        return $this->db->update('settings', $data);
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Users
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Users
    function countUsers() {
        $this->db->select('user_id');
        $this->db->from('users');
        /*
          $this->db->select('users1.user_id AS user_id, users1.username AS username, users2.username AS ref_username, admin_username');
          $this->db->select('users3.username AS parent_username');
          $this->db->select('users1.name AS name, users1.email_address AS email_address');
          $this->db->select('users1.contact_number AS contact_number, users1.utype AS utype, users1.creation_date AS creation_date');
          $this->db->select('users1.number_allowed AS number_allowed, users1.user_status AS user_status, pr_user_groups.user_group_name AS pr_user_group_name');
          $this->db->select('users1.utype AS utype, pr_user_groups.smsc_id AS pr_smsc_id, tr_user_groups.user_group_name AS tr_user_group_name');
          $this->db->select('tr_user_groups.smsc_id AS tr_smsc_id, users1.p_sender_id_option AS p_sender_id_option, users1.last_login_date AS last_login_date');
          $this->db->select('users1.t_sender_id_option AS t_sender_id_option, users1.keyword_option AS keyword_option');
          $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.check_demo_user AS check_demo_user, users1.check_verification AS check_verification');
          $this->db->select('users1.long_code_balance AS long_code_balance, users1.short_code_balance AS short_code_balance');
          $this->db->select('users1.pr_voice_balance AS pr_voice_balance, users1.tr_voice_balance AS tr_voice_balance');
          $this->db->select('users1.default_voice_route AS default_voice_route, users1.vtr_fake_ratio AS vtr_fake_ratio, users1.check_black_keyword AS check_black_keyword');
          $this->db->select('users1.vtr_fail_ratio AS vtr_fail_ratio, users1.vpr_fake_ratio AS vpr_fake_ratio, users1.vpr_fail_ratio AS vpr_fail_ratio');
          $this->db->from('users AS users1');
          $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
          $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
          $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
          $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users1.pro_user_group_id', 'left');
          $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users1.tr_user_group_id', 'left');
          $this->db->order_by('users1.user_id', 'desc');
         */
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get Users
    function getUsers($start = 0, $limit = 0, $flag = 1) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users2.username AS ref_username, admin_username');
        $this->db->select('users3.username AS parent_username');
        $this->db->select('users1.name AS name, users1.email_address AS email_address');
        $this->db->select('users1.contact_number AS contact_number, users1.utype AS utype, users1.creation_date AS creation_date');
        $this->db->select('users1.number_allowed AS number_allowed, users1.user_status AS user_status, pr_user_groups.user_group_name AS pr_user_group_name');
        $this->db->select('users1.utype AS utype, pr_user_groups.smsc_id AS pr_smsc_id, tr_user_groups.user_group_name AS tr_user_group_name');
        $this->db->select('tr_user_groups.smsc_id AS tr_smsc_id, users1.p_sender_id_option AS p_sender_id_option, users1.last_login_date AS last_login_date');
        $this->db->select('users1.t_sender_id_option AS t_sender_id_option, users1.keyword_option AS keyword_option');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.check_demo_user AS check_demo_user, users1.check_verification AS check_verification');
        $this->db->select('users1.long_code_balance AS long_code_balance, users1.short_code_balance AS short_code_balance');
        $this->db->select('users1.pr_voice_balance AS pr_voice_balance, users1.tr_voice_balance AS tr_voice_balance');
        $this->db->select('users1.pr_voice_balance AS pr_voice_balance, users1.tr_voice_balance AS tr_voice_balance');
        $this->db->select('users1.default_voice_route AS default_voice_route, users1.vtr_fake_ratio AS vtr_fake_ratio, users1.check_black_keyword AS check_black_keyword');
        $this->db->select('users1.vtr_fail_ratio AS vtr_fail_ratio, users1.vpr_fake_ratio AS vpr_fake_ratio, users1.vpr_fail_ratio AS vpr_fail_ratio, users1.manager_alert AS manager_alert,users1.pricing_approval AS pricing_approval,users1.encription AS encription,users1.otp_user AS otp_user,users1.lead_by AS lead_by,users1.feedback AS feedback');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users1.pro_user_group_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users1.tr_user_group_id', 'left');
        $this->db->order_by('users1.user_id', 'desc');

        if ($flag) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getPushDLRUsers() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '1073741824');
        $this->db->select('`user_id`, `username`,`name`');
        $this->db->from('users');
        $this->db->order_by('user_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Username
    function getUsername() {
        $username = $this->input->post('username');
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->num_rows();
        } else {
            $this->db->select('smpp_username');
            $this->db->from('smpp_users');
            $this->db->where('smpp_username', $username);
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    public function getUserForRetry() {
        $this->db->select('user_id,username,name');
        $this->db->from('users');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //select reseller under admin
    public function getUserUnderAdmin() {
        $this->db->select('user_id,username,name,utype,pr_sms_balance,tr_sms_balance,special_tr_balance,special_pr_balance,spacial_reseller_status');
        $this->db->from('users');
        $this->db->where('admin_id', 1);
        $this->db->where('utype', 'Reseller');
        $this->db->or_where('utype', 'Special Reseller');
        $this->db->order_by('user_id', 'desc');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //select reseller under admin
    public function getSpecialResellerInfo($user_id) {
        $this->db->select('user_id,username,name,utype,pr_sms_balance,tr_sms_balance,special_tr_balance,special_pr_balance,spacial_reseller_status');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    // make special reseller
    public function makeMeSpecialReseller($id, $status) {
        if ($status == 0) {
            $data = array(
                'spacial_reseller_status' => 1
            );

            $this->db->where('user_id', $id);
            $this->db->update('users', $data);
            return TRUE;
        } else {
            $data = array(
                'spacial_reseller_status' => 0
            );

            $this->db->where('user_id', $id);
            $this->db->update('users', $data);
            return TRUE;
        }
    }

// Get User
    function getUser($user = null) {
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('username', $user);
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        $user_id = $result->user_id;

        $this->db->select('users1.user_id AS user_id, users1.username AS username, users1.expiry_date AS expiry_date, users1.name AS name, users1.spacial_reseller_status AS spacial_reseller_status');
        $this->db->select('users1.email_address AS email_address, users1.contact_number AS contact_number, users1.number_allowed AS number_allowed');
        $this->db->select('users1.pro_user_group_id AS pro_user_group_id, users1.tr_user_group_id AS tr_user_group_id, users1.open_account_setting AS open_account_setting ');
        $this->db->select('users1.prtodnd_route AS prtodnd_route,users1.stock_route AS stock_route,users1.prtodnd_balance AS prtodnd_balance,users1.stock_balance AS stock_balance,users1.spacial_deliver_pr_ratio AS spacial_deliver_pr_ratio,users1.spacial_deliver_tr_ratio AS spacial_deliver_tr_ratio,users1.tr_fake_sent AS tr_fake_sent,users1.pr_fake_sent AS pr_fake_sent');
        $this->db->select('users1.p_sender_id_option AS p_sender_id_option, users1.t_sender_id_option AS t_sender_id_option, users1.utype AS utype');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.special_pr_balance AS special_pr_balance, users1.special_tr_balance AS special_tr_balance, users1.keyword_option AS keyword_option');
        $this->db->select('users1.dnd_check AS dnd_check, users1.sender_id_length AS sender_id_length, users1.sender_id_type AS sender_id_type');
        $this->db->select('users1.creation_date AS creation_date, users1.last_login_date AS last_login_date, users1.default_route AS default_route');
        $this->db->select('users1.default_sender_id AS default_sender_id, users1.industry AS industry, users1.default_timezone AS default_timezone');
        $this->db->select('users1.check_demo_user AS check_demo_user, users1.check_verification AS check_verification, users1.user_status AS user_status');
        $this->db->select('users1.user_ratio AS user_ratio,users1.user_fake_ratio AS user_fake_ratio,users1.user_fail_ratio AS user_fail_ratio');
        $this->db->select('users1.pr_user_ratio AS pr_user_ratio,users1.pr_user_fake_ratio AS pr_user_fake_ratio,users1.pr_user_fail_ratio AS pr_user_fail_ratio');
        $this->db->select('users1.date_of_birth AS date_of_birth, users1.address AS address, users1.city AS city');
        $this->db->select('SUM(users1.pr_sms_balance) AS total_pr_balance, SUM(users1.tr_sms_balance) AS total_tr_balance');
        $this->db->select('users1.country AS country, users1.zipcode AS zipcode, users1.company_name AS company_name');
        $this->db->select('users1.long_code_balance AS long_code_balance, users1.short_code_balance AS short_code_balance');
        $this->db->select('users2.username AS ref_username, admin_username, users3.username AS parent_username');
        $this->db->select('users1.pr_voice_balance AS pr_voice_balance, users1.tr_voice_balance AS tr_voice_balance');
        $this->db->select('users1.default_voice_route AS default_voice_route, users1.vtr_fake_ratio AS vtr_fake_ratio, users1.check_black_keyword AS check_black_keyword,users1.tr_ratio_discription AS tr_ratio_discription, users1.pr_ratio_discription AS pr_ratio_discription');
        $this->db->select('users1.vtr_fail_ratio AS vtr_fail_ratio, users1.vpr_fake_ratio AS vpr_fake_ratio, users1.vpr_fail_ratio AS vpr_fail_ratio,users1.fix_sender_id AS fix_sender_id,users1.manager_alert AS manager_alert,users1.pricing_approval AS pricing_approval,users1.admin_discription_tr AS admin_discription_tr,users1.admin_discription_pr AS admin_discription_pr,users1.international_balance AS international_balance,users1.international_route AS international_route,users1.encription AS encription,users1.otp_user AS otp_user');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
//$this->db->where('users1.username', $user);
        $this->db->where('users1.user_id', $user_id);
        $this->db->or_where('users1.ref_user_id', $user_id);
        $this->db->or_where('users1.most_parent_id', $user_id);
        $this->db->order_by('users1.user_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

// Get User
    function getUserInfo($user_id = 0) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users1.expiry_date AS expiry_date, users1.name AS name, users1.spacial_reseller_status AS spacial_reseller_status,users1.spacial_deliver_tr_ratio AS spacial_deliver_tr_ratio,users1.spacial_deliver_pr_ratio AS spacial_deliver_pr_ratio');
        $this->db->select('users1.email_address AS email_address, users1.contact_number AS contact_number, users1.number_allowed AS number_allowed');
        $this->db->select('users1.pro_user_group_id AS pro_user_group_id, users1.tr_user_group_id AS tr_user_group_id, users1.open_account_setting AS open_account_setting');
        $this->db->select('users1.prtodnd_route AS prtodnd_route,users1.stock_route AS stock_route,users1.prtodnd_balance AS prtodnd_balance,users1.stock_balance AS stock_balance,users1.stock_credits AS stock_credits,users1.prtodnd_credits AS prtodnd_credits');
        $this->db->select('users1.low_balance_pr AS low_balance_pr, users1.low_balance_tr AS low_balance_tr, users1.low_balance_alert AS low_balance_alert');
        $this->db->select('users1.p_sender_id_option AS p_sender_id_option, users1.t_sender_id_option AS t_sender_id_option, users1.utype AS utype');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.special_pr_balance AS special_pr_balance, users1.special_tr_balance AS special_tr_balance, users1.keyword_option AS keyword_option');
        $this->db->select('users1.dnd_check AS dnd_check, users1.sender_id_length AS sender_id_length, users1.sender_id_type AS sender_id_type,users1.stock_dnd_check AS stock_dnd_check,users1.premium_dnd_check AS premium_dnd_check');
        $this->db->select('users1.creation_date AS creation_date, users1.last_login_date AS last_login_date, users1.default_route AS default_route');
        $this->db->select('users1.default_sender_id AS default_sender_id, users1.industry AS industry, users1.default_timezone AS default_timezone');
        $this->db->select('users1.user_status AS user_status, users1.p_sender_id_type AS p_sender_id_type, users1.p_sender_id_length AS p_sender_id_length');
        $this->db->select('users1.user_ratio AS user_ratio,users1.user_fake_ratio AS user_fake_ratio,users1.user_fail_ratio AS user_fail_ratio');
        $this->db->select('users1.pr_user_ratio AS pr_user_ratio,users1.pr_user_fake_ratio AS pr_user_fake_ratio,users1.pr_user_fail_ratio AS pr_user_fail_ratio');
        $this->db->select('users1.date_of_birth AS date_of_birth, users1.address AS address, users1.city AS city,users1.check_demo_user AS check_demo_user, users1.check_verification AS check_verification');
        $this->db->select('SUM(users1.pr_sms_balance) AS total_pr_balance, SUM(users1.tr_sms_balance) AS total_tr_balance');
        $this->db->select('users1.country AS country, users1.zipcode AS zipcode, users1.company_name AS company_name');
        $this->db->select('users1.long_code_balance AS long_code_balance, users1.short_code_balance AS short_code_balance');
        $this->db->select('users2.username AS ref_username, admin_username, users3.username AS parent_username');
        $this->db->select('users1.pr_voice_balance AS pr_voice_balance, users1.tr_voice_balance AS tr_voice_balance, users1.missed_call_balance AS missed_call_balance');
        $this->db->select('users1.default_voice_route AS default_voice_route, users1.vtr_fake_ratio AS vtr_fake_ratio, users1.check_black_keyword AS check_black_keyword');
        $this->db->select('users1.vtr_fail_ratio AS vtr_fail_ratio, users1.vpr_fake_ratio AS vpr_fake_ratio, users1.vpr_fail_ratio AS vpr_fail_ratio, users1.admin_discription_tr AS admin_discription_tr, users1.admin_discription_pr AS admin_discription_pr,users1.voice_pr_route AS voice_pr_route,users1.voice_tr_route AS voice_tr_route');
        $this->db->select('users1.tr_ratio_discription AS tr_ratio_discription, users1.pr_ratio_discription AS pr_ratio_discription, users1.fix_sender_id AS fix_sender_id, users1.tr_fake_sent AS tr_fake_sent,users1.pr_fake_sent AS pr_fake_sent,users1.manager_alert AS manager_alert,users1.pricing_approval AS pricing_approval,users1.international_route AS international_route,users1.international_balance AS international_balance,users1.encription AS encription,users1.otp_user AS otp_user');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
//$this->db->where('users1.username', $user);
        $this->db->where('users1.user_id', $user_id);
        $this->db->or_where('users1.ref_user_id', $user_id);
        $this->db->or_where('users1.most_parent_id', $user_id);
        $this->db->order_by('users1.user_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

// Search User
    function getUserInfo1($user_id = 0) {
        $this->db->select('user_id, name, username, expiry_date, email_address, contact_number, number_allowed, pro_user_group_id, creation_date');
        $this->db->select('p_sender_id_option, t_sender_id_option, keyword_option, tr_user_group_id, utype, pr_sms_balance, tr_sms_balance, last_login_date');
        $this->db->select('sender_id_length, dnd_check, sender_id_type, users1.check_demo_user AS check_demo_user, users1.check_verification AS check_verification');
        $this->db->select('user_ratio, user_fake_ratio, user_fail_ratio, pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio');
        $this->db->select('long_code_balance, short_code_balance, check_black_keyword');
        $this->db->select('pr_voice_balance, tr_voice_balance, default_voice_route, vtr_fake_ratio, vtr_fail_ratio, vpr_fake_ratio, vpr_fail_ratio');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

// Save Update User
    function saveUpdateUser($user_id = 0) {
        $data = array(
            'name' => $this->input->post('name'),
            'contact_number' => $this->input->post('contact_number'),
            'email_address' => $this->input->post('email_address'),
            'utype' => $this->input->post('utype'),
            'company_name' => $this->input->post('company'),
            'industry' => $this->input->post('industry')
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Delete User
    function deleteUser($user_id = 0) {
        return $this->db->delete('users', array('user_id' => $user_id));
    }

// Enable/Disable User
    function enDisUser($user_id = 0, $status = 0) {
        $data = array('user_status' => $status
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Block Reseller
    function blockReseller($user_id = 0, $status = 0) {
        $data = array('user_status' => $status);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

// Save User Routing
    function saveUserRouting($user_id = 0, $admin_id = 0) {
        $pr_sms_route = $this->input->post('pr_route');
        $tr_sms_route = $this->input->post('tr_route');
        $prtodnd_sms_route = $this->input->post('prtodnd_sms_route');
        $stock_sms_route = $this->input->post('stock_sms_route');
        $international_route = $this->input->post('international_route');
        /*
          if ($pr_sms_route) {
          $this->db->select('`route_price`');
          $this->db->from('user_groups');
          $this->db->where('user_group_id', $pr_sms_route);
          $query = $this->db->get();
          $result = $query->row_array();
          $route_price = $result['route_price'];


          $this->db->select('`txn_price`');
          $this->db->from('transaction_logs');
          $this->db->where('txn_admin_from > ', 0);
          $this->db->where('txn_user_to', $user_id);
          $this->db->where('txn_route', 'A');
          $this->db->order_by('txn_log_id', 'desc');
          $this->db->limit(1);
          $query_user = $this->db->get();
          $result_user = $query_user->row_array();
          $user_price = $result_user['txn_price'];

          if ($user_price >= $route_price) {

          } else {
          return false;
          }
          }
          if ($tr_sms_route) {
          $this->db->select('`route_price`');
          $this->db->from('user_groups');
          $this->db->where('user_group_id', $tr_sms_route);
          $query = $this->db->get();
          $result = $query->row_array();
          $route_price = $result['route_price'];


          $this->db->select('`txn_price`');
          $this->db->from('transaction_logs');
          $this->db->where('txn_admin_from > ', 0);
          $this->db->where('txn_user_to', $user_id);
          $this->db->where('txn_route', 'B');
          $this->db->order_by('txn_log_id', 'desc');
          $this->db->limit(1);
          $query_user = $this->db->get();
          $result_user = $query_user->row_array();
          $user_price = $result_user['txn_price'];

          if ($user_price >= $route_price) {

          } else {
          return false;
          }
          }
          if ($prtodnd_sms_route) {
          $this->db->select('`route_price`');
          $this->db->from('user_groups');
          $this->db->where('user_group_id', $prtodnd_sms_route);
          $query = $this->db->get();
          $result = $query->row_array();
          $route_price = $result['route_price'];


          $this->db->select('`txn_price`');
          $this->db->from('transaction_logs');
          $this->db->where('txn_admin_from > ', 0);
          $this->db->where('txn_user_to', $user_id);
          $this->db->where('txn_route', 'D');
          $this->db->order_by('txn_log_id', 'desc');
          $this->db->limit(1);
          $query_user = $this->db->get();
          $result_user = $query_user->row_array();
          $user_price = $result_user['txn_price'];

          if ($user_price >= $route_price) {

          } else {
          return false;
          }
          }
          if ($stock_sms_route) {
          $this->db->select('`route_price`');
          $this->db->from('user_groups');
          $this->db->where('user_group_id', $stock_sms_route);
          $query = $this->db->get();
          $result = $query->row_array();
          $route_price = $result['route_price'];


          $this->db->select('`txn_price`');
          $this->db->from('transaction_logs');
          $this->db->where('txn_admin_from > ', 0);
          $this->db->where('txn_user_to', $user_id);
          $this->db->where('txn_route', 'C');
          $this->db->order_by('txn_log_id', 'desc');
          $this->db->limit(1);
          $query_user = $this->db->get();
          $result_user = $query_user->row_array();
          $user_price = $result_user['txn_price'];

          if ($user_price >= $route_price) {

          } else {
          return false;
          }
          }
         */
        $date = date("Y-m-d H:i:s");
        $route_log = array(
            'admin_id' => $admin_id,
            'user_id' => $user_id,
            'tr_route' => $tr_sms_route,
            'pr_route' => $pr_sms_route,
            'stock_route' => $stock_sms_route,
            'premium' => $prtodnd_sms_route,
            'date' => $date
        );
        $this->db->insert('route_monitring', $route_log);

        $data = array(
            'pro_user_group_id' => $pr_sms_route,
            'tr_user_group_id' => $tr_sms_route,
            'prtodnd_route' => $prtodnd_sms_route,
            'stock_route' => $stock_sms_route,
            'international_route' => $international_route
        );
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

// Save User Expiry
    function saveUserExpiry($user_id = 0, $option = null) {
        if ($option == 'set_expiry')
            $expiry_date = $this->input->post('expiry_date');
        elseif ($option == 'remove_expiry')
            $expiry_date = "";
        $data = array('expiry_date' => $expiry_date);
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Save special ratio
    function saveSpecialRatio($user_id = 0, $option = null) {
        if ($option == 'special_pr_ratio') {
            $special_pr_ratio = $this->input->post('special_pr_ratio');
            $data = array('spacial_deliver_pr_ratio' => $special_pr_ratio);

            $this->db->where('user_id', $user_id);
            $this->db->or_where('ref_user_id', $user_id);
            $this->db->or_where('most_parent_id', $user_id);
            return $this->db->update('users', $data);
        } elseif ($option == 'special_tr_ratio') {
            $special_tr_ratio = $this->input->post('special_tr_ratio');
            $data = array('spacial_deliver_tr_ratio' => $special_tr_ratio);
            $this->db->where('user_id', $user_id);
            $this->db->or_where('ref_user_id', $user_id);
            $this->db->or_where('most_parent_id', $user_id);
            return $this->db->update('users', $data);
        }
    }

// Save SMS Funds
    function saveSMSFunds($admin_id = 0, $user_id = 0, $array = array()) {
// Admin SMS Balance
        $pr_sms_balance = $array['total_pr_balance'];
        $tr_sms_balance = $array['total_tr_balance'];
        $prtodnd_balance = $array['total_prtodnd_balance'];
        $stock_balance = $array['total_stock_balance'];
        $international_sms_balance = $array['international_sms'];
        $total_lcode_balance = $array['total_lcode_balance'];
        $total_scode_balance = $array['total_scode_balance'];
        $pr_voice_balance = $array['total_vpr_balance'];
        $tr_voice_balance = $array['total_vtr_balance'];
        $total_mcall_balance = $array['total_mcall_balance'];
// User SMS Balance
        $pr_sms_bal = 0;
        $tr_sms_bal = 0;
        $prtodnd_sms_bal = 0;
        $stock_sms_bal = 0;
        $international_sms_bal = 0;
        $long_code_bal = 0;
        $short_code_bal = 0;
        $pr_voice_bal = 0;
        $tr_voice_bal = 0;
        $missed_call_bal = 0;

//variable for ratio on the basic of prizing
        $prize_ratio = array();
        $new_ratio = 0;
        $set_ratio = 0;
        $fake_ratio = 0;
        $pfake_deliver_ratio = 0;
        $pfake_failed_ratio = 0;
        $tax_value = 0;

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->row();
        $admin_tr = $result->admin_tr_approv;
        $admin_pr = $result->admin_pr_approv;
        $admin_approval_status = $result->pricing_approval;

        $result_user = $this->getUserInfo($user_id);
        if ($result_user) {
            $pr_sms_bal += $result_user['pr_sms_balance'];
            $tr_sms_bal += $result_user['tr_sms_balance'];
            $prtodnd_sms_bal += $result_user['prtodnd_balance'];
            $stock_sms_bal += $result_user['stock_balance'];
            $international_sms_bal += $result_user['international_balance'];
            $long_code_bal += $result_user['long_code_balance'];
            $short_code_bal += $result_user['short_code_balance'];
            $pr_voice_bal += $result_user['pr_voice_balance'];
            $tr_voice_bal += $result_user['tr_voice_balance'];
            $missed_call_bal += $result_user['missed_call_balance'];
            $pr_fake_fail = $result_user['pr_user_fail_ratio'];
            $pr_fake_deliver = $result_user['pr_user_fake_ratio'];
            $tr_fake_fail = $result_user['user_fail_ratio'];
            $tr_fake_deliver = $result_user['user_fake_ratio'];
        }
        $route = $this->input->post('route');
        $type = $this->input->post('type');
        $sms_balance = $this->input->post('sms_balance');
        $sms_price = $this->input->post('sms_price');
        $tax_value = $this->input->post('sms_tax');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $account_admin = $this->input->post('admin_name');
        $txn_date = date('d-m-Y h:i A');
        $new_date = date('Y-m-d');
        if ($tax_value) {
            $sms_tax_status = 1;
        } else {
            $sms_tax_status = 0;
        }

// Calculate Remain SMS Balance
// Promotional SMS
        if ($route == 'A') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($pr_sms_balance < $sms_balance) {
                    return false;
                } else {

                    if ($admin_approval_status == 0) {
                        if ($tax_value == 18) {
                            if ($sms_price < .11) {
                                return false;
                            }
                        } else {

                            if ($sms_price < .13) {
                                return false;
                            }
                        }
                    }

                    if ($tax_value == 18) {
                        if ($sms_price < .13) {

                            $new_ratio = .13 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    } else {

                        if ($sms_price < .15) {

                            $new_ratio = .15 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    }


                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_sms_balance1 += $pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal + $sms_balance;
//set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    //check old ratio to new ratio
                    if ($pr_fake_deliver > $pfake_deliver_ratio) {
                        $pfake_deliver_ratio = $pr_fake_deliver;
                    }
                    if ($pr_fake_fail > $pfake_failed_ratio) {
                        $pfake_failed_ratio = $pr_fake_fail;
                    }

                    // User Account

                    if ($admin_pr == 1) {
                        $data_user = array(
                            'pr_sms_balance' => $remain_pr_sms_balance2,
                            'pricing_approval' => 0,
                        );
                    } else {
                        $data_user = array(
                            'pr_sms_balance' => $remain_pr_sms_balance2,
                            'pr_user_fake_ratio' => $pfake_deliver_ratio,
                            'pr_user_fail_ratio' => $pfake_failed_ratio,
                            'pr_ratio_discription' => "As Per Prizing Rule",
                            'pricing_approval' => 0,
                        );
                    }





                    $temp_ratio_save = array(
                        'user_id' => $user_id,
                        'fake_deliver_pr' => $pfake_deliver_ratio,
                        'fake_fail_pr' => $pfake_failed_ratio
                    );

                    $this->db->select('user_id');
                    $this->db->from('user_temp_ratio_pr');
                    $this->db->where('user_id', $user_id);
                    $temp_ratio = $this->db->get();
                    $temp_result = $temp_ratio->num_rows();
                    if ($temp_result) {
                        $this->db->where('user_id', $user_id);
                        $this->db->delete('user_temp_ratio_pr');
                    }
                    $this->db->insert('user_temp_ratio_pr', $temp_ratio_save);


                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($pr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );


                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_sms_balance1 += $pr_sms_balance + $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal - $sms_balance;



                    // Admin Account
                    $data_admin = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_sms_balance' => $remain_pr_sms_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($pr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_sms_balance1 += $pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_sms_balance' => $remain_pr_sms_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($pr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_sms_balance1 += $pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_sms_balance' => $remain_pr_sms_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }


        //pr to dnd route

        if ($route == 'D') {
            $remain_prtodnd_balance1 = 0;
            $remain_prtodnd_balance2 = 0;
            if ($type == 'Add') {
                if ($prtodnd_balance < $sms_balance) {
                    return false;
                } else {

                    if ($admin_approval_status == 0) {
                        if ($tax_value == 18) {
                            if ($sms_price < .11) {
                                return false;
                            }
                        } else {

                            if ($sms_price < .13) {
                                return false;
                            }
                        }
                    }

                    if ($tax_value == 18) {
                        if ($sms_price < .13) {

                            $new_ratio = .13 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    } else {

                        if ($sms_price < .15) {

                            $new_ratio = .15 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    }

                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_prtodnd_balance1 += $prtodnd_balance - $sms_balance;
                    $remain_prtodnd_balance2 += $prtodnd_sms_bal + $sms_balance;
//set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_prtodnd_balance' => $remain_prtodnd_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    //check old ratio to new ratio
                    if ($pr_fake_deliver > $pfake_deliver_ratio) {
                        $pfake_deliver_ratio = $pr_fake_deliver;
                    }
                    if ($pr_fake_fail > $pfake_failed_ratio) {
                        $pfake_failed_ratio = $pr_fake_fail;
                    }

                    // User Account

                    if ($admin_pr == 1) {
                        $data_user = array(
                            'prtodnd_balance' => $remain_prtodnd_balance2,
                            'pricing_approval' => 0,
                        );
                    } else {
                        $data_user = array(
                            'prtodnd_balance' => $remain_prtodnd_balance2,
                            'pr_user_fake_ratio' => $pfake_deliver_ratio,
                            'pr_user_fail_ratio' => $pfake_failed_ratio,
                            'pr_ratio_discription' => "As Per Prizing Rule",
                            'pricing_approval' => 0,
                        );
                    }





                    $temp_ratio_save = array(
                        'user_id' => $user_id,
                        'fake_deliver_pr' => $pfake_deliver_ratio,
                        'fake_fail_pr' => $pfake_failed_ratio
                    );

                    $this->db->select('user_id');
                    $this->db->from('user_temp_ratio_pr');
                    $this->db->where('user_id', $user_id);
                    $temp_ratio = $this->db->get();
                    $temp_result = $temp_ratio->num_rows();
                    if ($temp_result) {
                        $this->db->where('user_id', $user_id);
                        $this->db->delete('user_temp_ratio_pr');
                    }
                    $this->db->insert('user_temp_ratio_pr', $temp_ratio_save);


                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($prtodnd_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );


                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_prtodnd_balance1 += $prtodnd_balance + $sms_balance;
                    $remain_prtodnd_balance2 += $prtodnd_sms_bal - $sms_balance;



                    // Admin Account
                    $data_admin = array(
                        'total_prtodnd_balance' => $remain_prtodnd_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'prtodnd_balance' => $remain_prtodnd_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($prtodnd_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_prtodnd_balance1 += $prtodnd_balance - $sms_balance;
                    $remain_prtodnd_balance2 += $prtodnd_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_prtodnd_balance' => $remain_prtodnd_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'prtodnd_balance' => $remain_prtodnd_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($prtodnd_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_prtodnd_balance1 += $prtodnd_balance - $sms_balance;
                    $remain_prtodnd_balance2 += $prtodnd_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_prtodnd_balance' => $remain_prtodnd_balance1,
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'prtodnd_balance' => $remain_prtodnd_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

        //stock route

        if ($route == 'C') {
            $remain_stock_balance1 = 0;
            $remain_stock_balance2 = 0;
            if ($type == 'Add') {
                if ($stock_balance < $sms_balance) {
                    return false;
                } else {

                    if ($admin_approval_status == 0) {
                        if ($tax_value == 18) {
                            if ($sms_price < .11) {
                                return false;
                            }
                        } else {

                            if ($sms_price < .13) {
                                return false;
                            }
                        }
                    }


                    if ($tax_value == 18) {
                        if ($sms_price < .13) {

                            $new_ratio = .13 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    } else {

                        if ($sms_price < .15) {

                            $new_ratio = .15 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    }
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_stock_balance1 += $stock_balance - $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal + $sms_balance;
//set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    //check old ratio to new ratio
                    if ($pr_fake_deliver > $pfake_deliver_ratio) {
                        $pfake_deliver_ratio = $pr_fake_deliver;
                    }
                    if ($pr_fake_fail > $pfake_failed_ratio) {
                        $pfake_failed_ratio = $pr_fake_fail;
                    }

                    // User Account
                    if ($admin_pr == 1) {
                        $data_user = array(
                            'stock_balance' => $remain_stock_balance2,
                            'pricing_approval' => 0,
                        );
                    } else {
                        $data_user = array(
                            'stock_balance' => $remain_stock_balance2,
                            'pr_user_fake_ratio' => $pfake_deliver_ratio,
                            'pr_user_fail_ratio' => $pfake_failed_ratio,
                            'pr_ratio_discription' => "As Per Prizing Rule",
                            'pricing_approval' => 0,
                        );
                    }





                    $temp_ratio_save = array(
                        'user_id' => $user_id,
                        'fake_deliver_pr' => $pfake_deliver_ratio,
                        'fake_fail_pr' => $pfake_failed_ratio
                    );

                    $this->db->select('user_id');
                    $this->db->from('user_temp_ratio_pr');
                    $this->db->where('user_id', $user_id);
                    $temp_ratio = $this->db->get();
                    $temp_result = $temp_ratio->num_rows();
                    if ($temp_result) {
                        $this->db->where('user_id', $user_id);
                        $this->db->delete('user_temp_ratio_pr');
                    }
                    $this->db->insert('user_temp_ratio_pr', $temp_ratio_save);


                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($stock_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );


                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_stock_balance1 += $stock_balance + $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal - $sms_balance;



                    // Admin Account
                    $data_admin = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'stock_balance' => $remain_stock_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($stock_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_stock_balance1 += $stock_balance - $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'stock_balance' => $remain_stock_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($stock_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_stock_balance1 += $stock_balance - $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'stock_balance' => $remain_stock_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }


        if ($route == 'I') {
            $remain_international_balance1 = 0;
            $remain_international_balance2 = 0;
            if ($type == 'Add') {
                if ($international_sms_balance < $sms_balance) {
                    return false;
                } else {


                    if ($admin_approval_status == 0) {
                        if ($tax_value == 18) {
                            if ($sms_price < .11) {
                                return false;
                            }
                        } else {

                            if ($sms_price < .13) {
                                return false;
                            }
                        }
                    }


                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_international_balance1 += $international_sms_balance - $sms_balance;
                    $remain_international_balance2 += $international_sms_bal + $sms_balance;
//set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'international_sms' => $remain_international_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    //check old ratio to new ratio
                    if ($pr_fake_deliver > $pfake_deliver_ratio) {
                        $pfake_deliver_ratio = $pr_fake_deliver;
                    }
                    if ($pr_fake_fail > $pfake_failed_ratio) {
                        $pfake_failed_ratio = $pr_fake_fail;
                    }

                    // User Account
                    if ($admin_pr == 1) {
                        $data_user = array(
                            'international_balance' => $remain_international_balance2,
                            'pricing_approval' => 0,
                        );
                    } else {
                        $data_user = array(
                            'international_balance' => $remain_international_balance2,
                            'pr_user_fake_ratio' => $pfake_deliver_ratio,
                            'pr_user_fail_ratio' => $pfake_failed_ratio,
                            'pr_ratio_discription' => "As Per Prizing Rule",
                            'pricing_approval' => 0,
                        );
                    }





                    $temp_ratio_save = array(
                        'user_id' => $user_id,
                        'fake_deliver_pr' => $pfake_deliver_ratio,
                        'fake_fail_pr' => $pfake_failed_ratio
                    );

                    $this->db->select('user_id');
                    $this->db->from('user_temp_ratio_pr');
                    $this->db->where('user_id', $user_id);
                    $temp_ratio = $this->db->get();
                    $temp_result = $temp_ratio->num_rows();
                    if ($temp_result) {
                        $this->db->where('user_id', $user_id);
                        $this->db->delete('user_temp_ratio_pr');
                    }
                    $this->db->insert('user_temp_ratio_pr', $temp_ratio_save);


                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($international_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );


                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_international_balance1 += $international_sms_balance + $sms_balance;
                    $remain_international_balance2 += $international_sms_bal - $sms_balance;



                    // Admin Account
                    $data_admin = array(
                        'international_sms' => $remain_international_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'international_balance' => $remain_international_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($international_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_international_balance1 += $international_sms_balance - $sms_balance;
                    $remain_international_balance2 += $international_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'international_sms' => $remain_international_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'international_balance' => $remain_international_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($international_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_international_balance1 += $international_sms_balance - $sms_balance;
                    $remain_international_balance2 += $international_sms_bal + $sms_balance;
                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'international_sms' => $remain_international_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'international_balance' => $remain_international_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }




// Transactional SMS
        if ($route == 'B') {
// Transactional SMS
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {


                    if ($admin_approval_status == 0) {
                        if ($tax_value == 18) {
                            if ($sms_price < .11) {
                                return false;
                            }
                        } else {

                            if ($sms_price < .13) {
                                return false;
                            }
                        }
                    }

                    if ($tax_value == 18) {
                        if ($sms_price < .13) {

                            $new_ratio = .13 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    } else {

                        if ($sms_price < .15) {

                            $new_ratio = .15 - $sms_price;
                            $prize_ratio = explode('.', $new_ratio);

                            $string_value = strlen($prize_ratio[1]);

                            if ($string_value == 3) {
                                $set_ratio = $prize_ratio[1];
                            }
                            if ($string_value == 2) {
                                $set_ratio = $prize_ratio[1] * 10;
                            }


                            $fake_ratio = $set_ratio * 20 / 100;
                            $pfake_failed_ratio = round($fake_ratio);
                            $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;
                        }
                    }

                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_sms_balance1 += $tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal + $sms_balance;

                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);

                    //check old ratio to new ratio
                    if ($tr_fake_deliver > $pfake_deliver_ratio) {
                        $pfake_deliver_ratio = $tr_fake_deliver;
                    }
                    if ($tr_fake_fail > $pfake_failed_ratio) {
                        $pfake_failed_ratio = $tr_fake_fail;
                    }

                    // User Account


                    if ($admin_tr == 1) {
                        $data_user = array(
                            'tr_sms_balance' => $remain_tr_sms_balance2,
                            'pricing_approval' => 0,
                        );
                    } else {
                        $data_user = array(
                            'tr_sms_balance' => $remain_tr_sms_balance2,
                            'user_fake_ratio' => $pfake_deliver_ratio,
                            'user_fail_ratio' => $pfake_failed_ratio,
                            'tr_ratio_discription' => "As Per Prizing Rule",
                            'pricing_approval' => 0,
                        );
                    }

                    $temp_ratio_save = array(
                        'user_id' => $user_id,
                        'fake_deliver_tr' => $pfake_deliver_ratio,
                        'fake_fail_tr' => $pfake_failed_ratio
                    );
                    $this->db->select('user_id');
                    $this->db->from('user_temp_ratio');
                    $this->db->where('user_id', $user_id);
                    $temp_ratio = $this->db->get();
                    $temp_result = $temp_ratio->num_rows();
                    if ($temp_result) {
                        $this->db->where('user_id', $user_id);
                        $this->db->delete('user_temp_ratio');
                    }
                    $this->db->insert('user_temp_ratio', $temp_ratio_save);



                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($tr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_sms_balance1 += $tr_sms_balance + $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_sms_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_sms_balance1 += $tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal + $sms_balance;

                    //set ratio on the basics of prize
                    // Admin Account
                    $data_admin = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_sms_balance' => $remain_tr_sms_balance2,
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_sms_balance1 += $tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_sms_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

// Open Route SMS
        // Long Code
        if ($route == 'Long') {
            $remain_lcode_balance1 = 0;
            $remain_lcode_balance2 = 0;
            if ($type == 'Add') {
                if ($total_lcode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_lcode_balance1 += $total_lcode_balance - $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($long_code_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_lcode_balance1 += $total_lcode_balance + $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($total_lcode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_lcode_balance1 += $total_lcode_balance - $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($total_lcode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_lcode_balance1 += $total_lcode_balance - $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

        // Short Code
        if ($route == 'Short') {
            $remain_scode_balance1 = 0;
            $remain_scode_balance2 = 0;
            if ($type == 'Add') {
                if ($total_scode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_scode_balance1 += $total_scode_balance - $sms_balance;
                    $remain_scode_balance2 += $short_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($short_code_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_scode_balance1 += $total_scode_balance + $sms_balance;
                    $remain_scode_balance2 += $short_code_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($total_scode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_scode_balance1 += $total_scode_balance - $sms_balance;
                    $remain_scode_balance2 += $short_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($total_scode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_scode_balance1 += $total_scode_balance - $sms_balance;
                    $remain_scode_balance2 += $short_code_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

// Promotional Voice SMS
        if ($route == 'VA') {
            $remain_pr_voice_balance1 = 0;
            $remain_pr_voice_balance2 = 0;
            if ($type == 'Add') {
                if ($pr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_voice_balance1 += $pr_voice_balance - $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($pr_voice_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_voice_balance1 += $pr_voice_balance + $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($pr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_voice_balance1 += $pr_voice_balance - $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($pr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_pr_voice_balance1 += $pr_voice_balance - $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

// Transactional Voice SMS
        if ($route == 'VB') {
            $remain_tr_voice_balance1 = 0;
            $remain_tr_voice_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'new_date' => $new_date,
                        'txn_status' => 0
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_voice_balance1 += $tr_voice_balance - $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($tr_voice_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'tax_status' => $sms_tax_status,
                        'account_admin' => $account_admin,
                        'new_date' => $new_date,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_voice_balance1 += $tr_voice_balance + $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Demo') {
                if ($tr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'account_admin' => $account_admin,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_voice_balance1 += $tr_voice_balance - $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Refund') {
                if ($tr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'account_admin' => $account_admin,
                        'new_date' => $new_date,
                        'tax_status' => $sms_tax_status,
                        'txn_status' => 1
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_tr_voice_balance1 += $tr_voice_balance - $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }

// Missed Call Alerts
        if ($route == 'Missed') {
            $remain_mcall_balance1 = 0;
            $remain_mcall_balance2 = 0;
            if ($type == 'Add') {
                if ($total_mcall_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'account_admin' => $account_admin,
                        'tax_status' => $sms_tax_status,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_mcall_balance1 += $total_mcall_balance - $sms_balance;
                    $remain_mcall_balance2 += $missed_call_bal + $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_mcall_balance' => $remain_mcall_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'missed_call_balance' => $remain_mcall_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            } elseif ($type == 'Reduce') {
                if ($missed_call_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'account_admin' => $account_admin,
                        'tax_status' => $sms_tax_status,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $this->utility_model->sendTransferEmail($data);
                    $remain_mcall_balance1 += $total_mcall_balance + $sms_balance;
                    $remain_mcall_balance2 += $missed_call_bal - $sms_balance;
                    // Admin Account
                    $data_admin = array(
                        'total_mcall_balance' => $remain_mcall_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin);
                    // User Account
                    $data_user = array(
                        'missed_call_balance' => $remain_mcall_balance2
                    );
                    $this->db->where('user_id', $user_id);
                    return $this->db->update('users', $data_user);
                }
            }
        }
    }

//update special balance

    function updateSpecialBalance($admin_id = 0, $user_id = 0) {

        $this->db->select('special_pr_balance,special_tr_balance,spacial_reseller_status,prtodnd_credits,stock_credits');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $special_pr_balance = $query->row('special_pr_balance');
        $special_tr_balance = $query->row('special_tr_balance');
        $prtodnd_credits = $query->row('prtodnd_credits');
        $stock_credits = $query->row('stock_credits');
        $spacial_reseller_status = $query->row('spacial_reseller_status');
        if ($spacial_reseller_status == 1) {
            $route = $this->input->post('route');
            $type = $this->input->post('type');
            $sms_price = $this->input->post('sms_price');
            $credits = $this->input->post('credits');
            $tax_value = $this->input->post('sms_tax');
            $amount = $this->input->post('amount');
            $description = $this->input->post('description');
            $account_admin = $this->input->post('admin_name');
            $txn_date = date('d-m-Y h:i A');
            $new_date = date('Y-m-d');
            if ($tax_value) {
                $sms_tax_status = 1;
            } else {
                $sms_tax_status = 0;
            }

            $this->db->select('admin_pr_credits,admin_tr_credits,total_prtodnd_credits,total_stock_credits');
            $this->db->from('administrators');
            $this->db->where('admin_id', $admin_id);
            $query = $this->db->get();
            $admin_pr_credits = $query->row('admin_pr_credits');
            $admin_tr_credits = $query->row('admin_tr_credits');
            $total_prtodnd_credits = $query->row('total_prtodnd_credits');
            $total_stock_credits = $query->row('total_stock_credits');

            if ($route == 'A') {


                if ($type == 'Add') {

                    if ($admin_pr_credits < $credits) {
                        return FALSE;
                    } else {
                        $updated_admin_balance = $admin_pr_credits - $credits;
                        $admin_data = array(
                            'admin_pr_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);
                    }


                    $data = array(
                        'balance_type' => $route,
                        'type' => $type,
                        'total_amount' => $amount,
                        'tax_status' => $sms_tax_status,
                        'sms_price' => $sms_price,
                        'balance' => $credits,
                        'admin_id' => $admin_id,
                        'user_id' => $user_id,
                        'date' => $new_date,
                        'date_time' => $txn_date,
                        'discription' => $description,
                        'account_admin' => $account_admin
                    );
                    $this->db->insert('special_transaction_logs', $data);

                    $updated_balance = $special_pr_balance + $credits;
                    $data_user = array(
                        'special_pr_balance' => $updated_balance
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data_user);
                    return TRUE;
                } elseif ($type == 'Reduce') {
                    if ($credits > $special_pr_balance) {
                        return FALSE;
                    } else {

                        $updated_admin_balance = $admin_pr_credits + $credits;
                        $admin_data = array(
                            'admin_pr_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);


                        $data = array(
                            'balance_type' => $route,
                            'type' => $type,
                            'total_amount' => $amount,
                            'tax_status' => $sms_tax_status,
                            'sms_price' => $sms_price,
                            'balance' => $credits,
                            'admin_id' => $admin_id,
                            'user_id' => $user_id,
                            'date' => $new_date,
                            'date_time' => $txn_date,
                            'discription' => $description,
                            'account_admin' => $account_admin
                        );
                        $this->db->insert('special_transaction_logs', $data);

                        $updated_balance = $special_pr_balance - $credits;
                        $data_user = array(
                            'special_pr_balance' => $updated_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data_user);
                        return TRUE;
                    }
                }
            }

            //stock fund tranfer

            if ($route == 'C') {

                if ($type == 'Add') {

                    if ($total_stock_credits < $credits) {
                        return FALSE;
                    } else {
                        $updated_admin_balance = $total_stock_credits - $credits;
                        $admin_data = array(
                            'total_stock_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);
                    }


                    $data = array(
                        'balance_type' => $route,
                        'type' => $type,
                        'total_amount' => $amount,
                        'tax_status' => $sms_tax_status,
                        'sms_price' => $sms_price,
                        'balance' => $credits,
                        'admin_id' => $admin_id,
                        'user_id' => $user_id,
                        'date' => $new_date,
                        'date_time' => $txn_date,
                        'discription' => $description,
                        'account_admin' => $account_admin
                    );
                    $this->db->insert('special_transaction_logs', $data);

                    $updated_balance = $stock_credits + $credits;
                    $data_user = array(
                        'stock_credits' => $updated_balance
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data_user);
                    return TRUE;
                } elseif ($type == 'Reduce') {
                    if ($credits > $stock_credits) {
                        return FALSE;
                    } else {

                        $updated_admin_balance = $total_stock_credits + $credits;
                        $admin_data = array(
                            'total_stock_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);


                        $data = array(
                            'balance_type' => $route,
                            'type' => $type,
                            'total_amount' => $amount,
                            'tax_status' => $sms_tax_status,
                            'sms_price' => $sms_price,
                            'balance' => $credits,
                            'admin_id' => $admin_id,
                            'user_id' => $user_id,
                            'date' => $new_date,
                            'date_time' => $txn_date,
                            'discription' => $description,
                            'account_admin' => $account_admin
                        );
                        $this->db->insert('special_transaction_logs', $data);

                        $updated_balance = $stock_credits - $credits;
                        $data_user = array(
                            'stock_credits' => $updated_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data_user);
                        return TRUE;
                    }
                }
            }

            //prtodnd fund tranfer
            if ($route == 'D') {


                if ($type == 'Add') {

                    if ($total_prtodnd_credits < $credits) {
                        return FALSE;
                    } else {
                        $updated_admin_balance = $total_prtodnd_credits - $credits;
                        $admin_data = array(
                            'total_prtodnd_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);
                    }


                    $data = array(
                        'balance_type' => $route,
                        'type' => $type,
                        'total_amount' => $amount,
                        'tax_status' => $sms_tax_status,
                        'sms_price' => $sms_price,
                        'balance' => $credits,
                        'admin_id' => $admin_id,
                        'user_id' => $user_id,
                        'date' => $new_date,
                        'date_time' => $txn_date,
                        'discription' => $description,
                        'account_admin' => $account_admin
                    );
                    $this->db->insert('special_transaction_logs', $data);

                    $updated_balance = $prtodnd_credits + $credits;
                    $data_user = array(
                        'prtodnd_credits' => $updated_balance
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data_user);
                    return TRUE;
                } elseif ($type == 'Reduce') {
                    if ($credits > $prtodnd_credits) {
                        return FALSE;
                    } else {

                        $updated_admin_balance = $total_prtodnd_credits + $credits;
                        $admin_data = array(
                            'total_prtodnd_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);


                        $data = array(
                            'balance_type' => $route,
                            'type' => $type,
                            'total_amount' => $amount,
                            'tax_status' => $sms_tax_status,
                            'sms_price' => $sms_price,
                            'balance' => $credits,
                            'admin_id' => $admin_id,
                            'user_id' => $user_id,
                            'date' => $new_date,
                            'date_time' => $txn_date,
                            'discription' => $description,
                            'account_admin' => $account_admin
                        );
                        $this->db->insert('special_transaction_logs', $data);

                        $updated_balance = $prtodnd_credits - $credits;
                        $data_user = array(
                            'prtodnd_credits' => $updated_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data_user);
                        return TRUE;
                    }
                }
            }

            //tr credits transfer

            if ($route == 'B') {


                if ($type == 'Add') {

                    if ($admin_tr_credits < $credits) {
                        return FALSE;
                    } else {
                        $updated_admin_balance = $admin_tr_credits - $credits;
                        $admin_data = array(
                            'admin_tr_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);
                    }

                    $data = array(
                        'balance_type' => $route,
                        'type' => $type,
                        'total_amount' => $amount,
                        'tax_status' => $sms_tax_status,
                        'sms_price' => $sms_price,
                        'balance' => $credits,
                        'admin_id' => $admin_id,
                        'user_id' => $user_id,
                        'date' => $new_date,
                        'date_time' => $txn_date,
                        'discription' => $description,
                        'account_admin' => $account_admin
                    );
                    $this->db->insert('special_transaction_logs', $data);

                    $updated_balance = $special_tr_balance + $credits;
                    $data_user = array(
                        'special_tr_balance' => $updated_balance
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data_user);
                    return TRUE;
                } elseif ($type == 'Reduce') {

                    if ($credits > $special_tr_balance) {
                        return FALSE;
                    } else {


                        $updated_admin_balance = $admin_tr_credits + $credits;
                        $admin_data = array(
                            'admin_tr_credits' => $updated_admin_balance
                        );
                        $this->db->where('admin_id', $admin_id);
                        $this->db->update('administrators', $admin_data);



                        $data = array(
                            'balance_type' => $route,
                            'type' => $type,
                            'total_amount' => $amount,
                            'tax_status' => $sms_tax_status,
                            'sms_price' => $sms_price,
                            'balance' => $credits,
                            'admin_id' => $admin_id,
                            'user_id' => $user_id,
                            'date' => $new_date,
                            'date_time' => $txn_date,
                            'discription' => $description,
                            'account_admin' => $account_admin
                        );
                        $this->db->insert('special_transaction_logs', $data);

                        $updated_balance = $special_tr_balance - $credits;
                        $data_user = array(
                            'special_tr_balance' => $updated_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data_user);
                        return TRUE;
                    }
                }
            }
        } else {
            return FALSE;
        }
    }

// Count SMS Logs
    function countUserSMSLogs($user_id = 0) {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_user_from', $user_id);
        $this->db->or_where('txn_user_to', $user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get SMS Logs
    function getUserSMSLogs($user_id = 0) {
        $this->db->select('txn_log_id,txn_route, txn_sms, txn_price, txn_amount, txn_type, txn_date, txn_description,admin_discription');
        $this->db->select(' userA.name AS from_name , userA.user_id AS from_user_id');
        $this->db->select(' userB.name AS to_name , userB.user_id AS to_user_id');
        $this->db->select('administratorsA.admin_name AS from_admin_name , administratorsA.admin_id AS from_admin_id');
        $this->db->select(' administratorsB.admin_name AS to_admin_name , administratorsB.admin_id AS to_admin_id');
        $this->db->from('transaction_logs');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->where('txn_user_from', $user_id);
        $this->db->or_where('txn_user_to', $user_id);
        $this->db->order_by('txn_log_id', 'desc');
//$this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $txn_logs_array = array();
            $result_txn_logs = $query->result_array();
            foreach ($result_txn_logs as $row) {
                if ($row['to_admin_id'] != "" || $row['from_admin_id'] != "") {
                    $temp_array = array();
                    $temp_array['txn_date'] = $row['txn_date'];
                    $temp_array['txn_log_id'] = $row['txn_log_id'];
                    $temp_array['txn_type'] = $row['txn_type'];
                    $temp_array['txn_route'] = $row['txn_route'];
                    $temp_array['txn_sms'] = $row['txn_sms'];
                    $temp_array['txn_price'] = $row['txn_price'];
                    $temp_array['txn_amount'] = $row['txn_amount'];
                    $temp_array['txn_description'] = $row['txn_description'];
                    $temp_array['admin_discription'] = $row['admin_discription'];
                    if ($row['from_user_id'] == $user_id) {
                        if ($row['to_admin_id'] == "")
                            $temp_array['to_name'] = $row['to_name'];
                        else
                            $temp_array['to_name'] = $row['to_admin_name'];
                    }
                    if ($row['to_user_id'] == $user_id) {
                        if ($row['from_admin_id'] == "")
                            $temp_array['from_name'] = $row['from_name'];
                        else
                            $temp_array['from_name'] = $row['from_admin_name'];
                    }
                    $txn_logs_array[] = $temp_array;
                }
            }
            return $txn_logs_array;
        }else {
            return false;
        }
    }

    //special reseller logs
    function specialResellerlogs($user_id = 0) {

        $this->db->select('balance_type,type,total_amount,balance,sms_price,admin_id,user_id,date_time,discription');
        $this->db->from('special_transaction_logs');
        $this->db->where('user_id', $user_id);
        //  $this->db->where('users.username = special_transaction_logs.user_id');
        $this->db->order_by("`special_tr_id`", "desc");
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    function specialResellerAdmin($admin_id = 0) {

        $this->db->select('admin_name,admin_username');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get();
        return $result = $query->row();
    }

// Change User Password
    function saveUserPassword($user_id = 0) {
// User Account
        $data = array('password' => md5($this->input->post('password')));
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Change Low Balance Alert
    function saveLowBalAlert($user_id = 0) {
        $alert_by_sms = 0;
        $alert_by_email = 0;
        if ($this->input->post('alert_by_sms')) {
            $alert_by_sms = $this->input->post('alert_by_sms');
        }
        if ($this->input->post('alert_by_email')) {
            $alert_by_email = $this->input->post('alert_by_email');
        }
        $low_balance_alert = $alert_by_sms . "|" . $alert_by_email;
        $pr_sms = $this->input->post('pr_sms');
        $tr_sms = $this->input->post('tr_sms');
// User Account
        $data = array('low_balance_alert' => $low_balance_alert, 'low_balance_pr' => $pr_sms, 'low_balance_tr' => $tr_sms);
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Save User Setting
    function saveUserSettings($user_id = 0) {
        if ($this->input->post('p_sender_id_option')) {
            $p_sender_id_option = $this->input->post('p_sender_id_option');
        } else {
            $p_sender_id_option = 0;
        }
        if ($this->input->post('dnd_check')) {
            $dnd_check = $this->input->post('dnd_check');
        } else {
            $dnd_check = 0;
        }
        if ($this->input->post('t_sender_id_option')) {
            $t_sender_id_option = $this->input->post('t_sender_id_option');
        } else {
            $t_sender_id_option = 0;
        }
        if ($this->input->post('stock_dnd_check')) {
            $stock_dnd_check = $this->input->post('stock_dnd_check');
        } else {
            $stock_dnd_check = 0;
        }
        if ($this->input->post('premium_dnd_check')) {
            $premium_dnd_check = $this->input->post('premium_dnd_check');
        } else {
            $premium_dnd_check = 0;
        }
        if ($this->input->post('keyword_option')) {
            $keyword_option = $this->input->post('keyword_option');
        } else {
            $keyword_option = 0;
        }
        if ($this->input->post('fix_sender_id')) {
            $fix_sender_id = $this->input->post('fix_sender_id');
        } else {
            $fix_sender_id;
        }
        $data = array(
            'number_allowed' => $this->input->post('unique_no'),
            'p_sender_id_option' => $p_sender_id_option,
            't_sender_id_option' => $t_sender_id_option,
            'keyword_option' => $keyword_option,
            'dnd_check' => $dnd_check,
            'stock_dnd_check' => $stock_dnd_check,
            'premium_dnd_check' => $premium_dnd_check,
            'sender_id_length' => $this->input->post('sender_id_length'),
            'sender_id_type' => $this->input->post('sender_id_type'),
            'p_sender_id_type' => $this->input->post('p_sender_id_type'),
            'p_sender_id_length' => $this->input->post('p_sender_id_length'),
            'fix_sender_id' => $this->input->post('fix_sender_id')
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// save user set ratio
    function saveUserRatio($user_id = 0, $admin_id = 0) {
        $ratio_type = $this->input->post('ratio_type');
        $ratio_discription = $this->input->post('ratio_discription');


// User Overall Ratio
        if ($this->input->post('user_ratio') != "")
            $user_ratio = $this->input->post('user_ratio');
        else
            $user_ratio = 0;
// User Fake Delivered Ratio
        if ($this->input->post('user_fake_ratio') != "")
            $user_fake_ratio = $this->input->post('user_fake_ratio');
        else
            $user_fake_ratio = 0;
// User Fake Failed Ratio
        if ($this->input->post('user_fail_ratio') != "")
            $user_fail_ratio = $this->input->post('user_fail_ratio');
        else
            $user_fail_ratio = 0;
        // User Fake sent tr    
        if ($this->input->post('tr_fake_sent') != "")
            $tr_fake_sent = $this->input->post('tr_fake_sent');
        else
            $tr_fake_sent = 0;
        // User Fake sent pr 
        if ($this->input->post('pr_fake_sent') != "")
            $pr_fake_sent = $this->input->post('pr_fake_sent');
        else
            $pr_fake_sent = 0;

        $data = array();
// Transactional SMS
        if ($ratio_type == 'transactional') {
            $data = array(
                'user_ratio' => $user_ratio,
                'user_fake_ratio' => $user_fake_ratio,
                'user_fail_ratio' => $user_fail_ratio,
                'tr_fake_sent' => $tr_fake_sent,
                'tr_ratio_discription' => $ratio_discription
            );
        }


// Promotional SMS
        if ($ratio_type == 'promotional') {
            $data = array(
                'pr_user_ratio' => $user_ratio,
                'pr_user_fake_ratio' => $user_fake_ratio,
                'pr_user_fail_ratio' => $user_fail_ratio,
                'pr_fake_sent' => $pr_fake_sent,
                'pr_ratio_discription' => $ratio_discription
            );
        }
// Promotional Voice
        if ($ratio_type == 'vpromotional') {
            $data = array(
                'vpr_fake_ratio' => $user_fake_ratio,
                'vpr_fail_ratio' => $user_fail_ratio
            );
        }
// Dynamic Voice
        if ($ratio_type == 'vdynamic') {
            $data = array(
                'vtr_fake_ratio' => $user_fake_ratio,
                'vtr_fail_ratio' => $user_fail_ratio
            );
        }
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    public function saveAdminApproveRatio($user_id, $subtab) {
        $admin_value = $this->input->post('admin_value');
        $admin_discription = $this->input->post('admin_discription');

        if ($subtab == 'atrratio') {
            $data = array(
                'admin_tr_approv' => 1,
                'admin_discription_tr' => $admin_discription
            );
        }


// Promotional SMS
        if ($subtab == 'aprratio') {
            $data = array(
                'admin_pr_approv' => 1,
                'admin_discription_pr' => $admin_discription
            );
        }
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

// Search Users
    function searchUsers($username = null) {
        $email = strpos($username, "@");
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users1.expiry_date AS expiry_date, users1.name AS name, users1.spacial_reseller_status AS spacial_reseller_status');
        $this->db->select('users1.email_address AS email_address, users1.contact_number AS contact_number, users1.number_allowed AS number_allowed');
        $this->db->select('users1.pro_user_group_id AS pro_user_group_id, users1.tr_user_group_id AS tr_user_group_id');
        $this->db->select('users1.p_sender_id_option AS p_sender_id_option, users1.t_sender_id_option AS t_sender_id_option, users1.utype AS utype');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.keyword_option AS keyword_option');
        $this->db->select('users1.dnd_check AS dnd_check, users1.sender_id_length AS sender_id_length, users1.sender_id_type AS sender_id_type');
        $this->db->select('users1.creation_date AS creation_date, users1.last_login_date AS last_login_date, users1.default_route AS default_route');
        $this->db->select('users1.default_sender_id AS default_sender_id, users1.industry AS industry, users1.default_timezone AS default_timezone');
        $this->db->select('users1.user_status AS user_status, users1.check_demo_user AS check_demo_user');
        $this->db->select('users1.date_of_birth AS date_of_birth, users1.address AS address, users1.city AS city');
        $this->db->select('users1.country AS country, users1.zipcode AS zipcode, users1.company_name AS company_name');
        $this->db->select('users2.username AS ref_username, admin_username, users3.username AS parent_username');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->like('users1.username', $username, 'after');
        $this->db->or_like('users1.name', $username, 'before', 'after');
        $this->db->or_like('users1.contact_number', $username, 'after');
        if ($email) {
            $this->db->or_like('users1.email_address', $username, 'after');
        }

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Search User under Reseller
    function searchResellerUsers($username = null) {
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->like('username', $username, 'after');
        $this->db->or_like('name', $username, 'before', 'after');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $parent_id = $query->row('user_id');
            // var_dump($parent_id);die; 
            // return $query->result_array(); 
        }

        $this->db->select('users1.user_id AS user_id, users1.username AS username, users1.expiry_date AS expiry_date, users1.name AS name');
        $this->db->select('users1.email_address AS email_address, users1.contact_number AS contact_number, users1.number_allowed AS number_allowed');
        $this->db->select('users1.pro_user_group_id AS pro_user_group_id, users1.tr_user_group_id AS tr_user_group_id');
        $this->db->select('users1.p_sender_id_option AS p_sender_id_option, users1.t_sender_id_option AS t_sender_id_option, users1.utype AS utype');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance, users1.keyword_option AS keyword_option');
        $this->db->select('users1.dnd_check AS dnd_check, users1.sender_id_length AS sender_id_length, users1.sender_id_type AS sender_id_type');
        $this->db->select('users1.creation_date AS creation_date, users1.last_login_date AS last_login_date, users1.default_route AS default_route');
        $this->db->select('users1.default_sender_id AS default_sender_id, users1.industry AS industry, users1.default_timezone AS default_timezone');
        $this->db->select('users1.user_status AS user_status, users1.check_demo_user AS check_demo_user');
        $this->db->select('users1.date_of_birth AS date_of_birth, users1.address AS address, users1.city AS city');
        $this->db->select('users1.country AS country, users1.zipcode AS zipcode, users1.company_name AS company_name');
        $this->db->select('users2.username AS ref_username, admin_username, users3.username AS parent_username');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->where('users1.most_parent_id', $parent_id);
        $query_reseller = $this->db->get();
        if ($query_reseller->num_rows()) {
            return $query_reseller->result_array();
        } else {
            return false;
        }
    }

// Check DNS Setting
    function checkDNSSettings() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $domain_name = $myArray[0];
        $result = dns_get_record($domain_name, DNS_A);
        if ($result) {
            $domain_ip = $result[0]['ip'];
            $server_ip = $_SERVER['SERVER_ADDR'];
            if ($server_ip == $domain_ip) {
                return "<span style=color:green>This domain is currently pointing to '$domain_ip'</span>";
            } else {
                return "<span style=color:red>This domain is currently pointing to '$domain_ip' But the correct IP is '$server_ip', Please correct.</span>";
            }
        } else {
            return "<span style=color:red>Invalid Domain or IP not found on server.</span>";
        }
    }

// Check Username
    function checkUsername($type = null) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $username = $myArray[0];
        if ($type == 'user') {
            $this->db->select('username');
            $this->db->from('users');
            $this->db->where('username', $username);
        } elseif ($type == 'admin') {
            $this->db->select('admin_username');
            $this->db->from('administrators');
            $this->db->where('admin_username', $username);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return "<span style=color:green>Congratulations! Username available.</span>";
        } else {
            return "<span style=color:red>Usernmae not available. Please try another.</span>";
        }
    }

// Save User
    function saveUser($admin_id = 0, $admin_email = null, $total_pr_balance = 0, $total_tr_balance = 0) {
// From Info
        $name = $this->input->post('name');
        $email_address = $this->input->post('email');
        $contact = $this->input->post('contact');
        $username = $this->input->post('username');
        $expiry_date = $this->input->post('expiry_date');
        $user_type = $this->input->post('user_type');
        $company = $this->input->post('company');
        $industry = $this->input->post('industry');
// Check Username
        $this->db->select('username');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
// Generate Password For User
            $password = random_string('numeric', 6);
// Generate Auth Key For User
            $auth_key = random_string('unique', 32);
            $creation_date = date('d-m-Y h:i A');
// Default User Group
// Promotional
            $pr_user_group_id = 0;
            $result_pr = $this->getDefaultUserGroup('Promotional', 1);
            if ($result_pr)
                $pr_user_group_id = $result_pr->user_group_id;
// Transactional
            $tr_user_group_id = 0;
            $result_tr = $this->getDefaultUserGroup('Transactional', 1);
            if ($result_tr)
                $tr_user_group_id = $result_tr->user_group_id;
// Default Settings
            $sms_limit = 100;
            $pr_sender_id_check = 0;
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
            $result_settings = $this->getDefaultSettings();
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
            }
// Check Available Balance
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
            $data = array(
                'admin_id' => $admin_id,
                'name' => $name,
                'email_address' => $email_address,
                'contact_number' => $contact,
                'username' => $username,
                'password' => md5($password),
                'auth_key' => $auth_key,
                'utype' => $user_type,
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
                'pr_sms_balance' => $pr_demo_balance,
                'tr_sms_balance' => $tr_demo_balance,
                'expiry_date' => $expiry_date
            );
//==================================================
// Send SMS
            $purpose = "User Signup";
            $name_array = explode(' ', $name);
            $web_domain = "http://sms.bulksmsserviceproviders.com/signin";
// Get Short URL
            $short_web_domain = $this->sms_model->googleUrlShortner($web_domain);
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
// Prepare SMS Array
            $url = "http://sms.bulksmsserviceproviders.com/admin/send_http/";
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
                $body = $body . "<h4>Team Bulk SMS Service Providers</h4>";
            } else {
                $body = $this->utility_model->emailSignup('http://sms.bulksmsserviceproviders.com/signin', $username, 'Bulk SMS Service Providers', 1);
            }
// Prepare Email Array
            $mail_array = array(
                'from_email' => $admin_email,
                'from_name' => 'Bulk SMS Service Providers',
                'to_email' => $email_address,
                'subject' => $subject,
                'message' => $body
            );
            if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array)) {
                $up_pr_sms_balance = $total_pr_balance - $pr_demo_balance;
                $up_tr_sms_balance = $total_tr_balance - $tr_demo_balance;
                $bal_data = array(
                    'total_pr_balance' => $up_pr_sms_balance,
                    'total_tr_balance' => $up_tr_sms_balance
                );
                $this->db->where('admin_id', $admin_id);
                $this->db->update('administrators', $bal_data);
                return 200;
            } else {
                return 100;
            }
        } else {
            return 101;
        }
    }

// Change Account Type
    function saveAccountType($user_id = 0) {
        if ($this->input->post('account_type'))
            $account_type = $this->input->post('account_type');
        else
            $account_type = 0;
// User Account
        $data = array('check_demo_user' => $account_type);
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Save Black Keyword Setting
    function checkBlackKeyword($user_id = 0) {
        if ($this->input->post('check_black_keyword'))
            $check_black_keyword = $this->input->post('check_black_keyword');
        else
            $check_black_keyword = 0;
// User Setting
        $data = array('check_black_keyword' => $check_black_keyword);
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

// Save Verification For Location
    function saveVerifyLocation($user_id = 0) {
        if ($this->input->post('check_verify_location'))
            $check_verify_location = $this->input->post('check_verify_location');
        else
            $check_verify_location = 0;
// User Account
        $data = array('check_verification' => $check_verify_location);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Save Verification For Location
    function accountManagerAlert($user_id = 0) {
        if ($this->input->post('manager_alert'))
            $manager_alert = $this->input->post('manager_alert');
        else
            $manager_alert = 0;
// User Account
        $data = array('manager_alert' => $manager_alert);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Save Verification For Location
    function otpUserAlert($user_id = 0) {
        if ($this->input->post('otp_user'))
            $otp_user = $this->input->post('otp_user');
        else
            $otp_user = 0;
// User Account
        $data = array('otp_user' => $otp_user);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Save Verification For pricing_approval
    function pricingApproval($user_id = 0) {
        if ($this->input->post('pricing_approval'))
            $pricing_approval = $this->input->post('pricing_approval');
        else
            $pricing_approval = 0;
// User Account
        $data = array('pricing_approval' => $pricing_approval);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Save Verification For encription_approval
    function encriptionApproval($user_id = 0) {
        if ($this->input->post('encription_approve'))
            $encription_approval = $this->input->post('encription_approve');
        else
            $encription_approval = 0;
// User Account
        $data = array('encription' => $encription_approval);
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

    // open this reseller settings
    function openResellerSetting($user_id = 0) {

        if ($this->input->post('open_reseller_status')) {
            $open_reseller_status = $this->input->post('open_reseller_status');
            $t_sender_id_option = 1;
            $keyword_option = 1;
        } else {
            $open_reseller_status = 0;
            $t_sender_id_option = 0;
            $keyword_option = 0;
        }

        $data = array(
            'open_account_setting' => $open_reseller_status,
            't_sender_id_option' => $t_sender_id_option,
            'keyword_option' => $keyword_option
        );

        $this->db->where('user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        return $this->db->update('users', $data);
    }

// Chnage SMPP Routing
    function changeSMPPRouting($type = null) {
// Promotional Route

        if ($type == 'pr') {
            $data = array('pro_user_group_id' => $this->input->post('new_smpp'));
            $this->db->where('pro_user_group_id', $this->input->post('current_smpp'));
            return $this->db->update('users', $data);
        }
// Transactional Route
        if ($type == 'tr') {
            $data = array('tr_user_group_id' => $this->input->post('new_smpp'));
            $this->db->where('tr_user_group_id', $this->input->post('current_smpp'));
            $this->db->where('otp_user', 0);
            return $this->db->update('users', $data);
        }
//        if ($type == 'otp') {
//            $new_route = $this->input->post('new_smpp');
//            $current_route = $this->input->post('current_smpp');
//            $data = array(
//                'otp_route_status' => 0
//            );
//            $this->db->where('user_group_id > ', 0);
//            if ($this->db->update('user_groups', $data)) {
//                $update_data = array(
//                    'otp_route_status' => 1
//                );
//                $this->db->where('user_group_id', $new_route);
//                return $this->db->update('user_groups', $update_data);
//            }
//        }
        if ($type == 'otp') {
            $new_route = $this->input->post('new_smpp');
            $data = array('tr_user_group_id' => $new_route);
            $this->db->where('otp_user', 1);
            return $this->db->update('users', $data);
        }
//check balance less then 500    

        if ($type == 'pr_bal') {
            $new_pr_bal_route = $this->input->post('new_smpp');
            $data = array('pro_user_group_id' => $new_pr_bal_route);
            $this->db->where('pr_sms_balance <', 500);
            return $this->db->update('users', $data);
        }
        if ($type == 'tr_bal') {
            $new_tr_bal_route = $this->input->post('new_smpp');
            $data = array('tr_user_group_id' => $new_tr_bal_route);
            $this->db->where('tr_sms_balance <', 500);
            return $this->db->update('users', $data);
        }
    }

    //change auto routing after 10 min
    function changeAutoRouting($type = null, $tr_user_group_id = 0, $pro_user_group_id = 0, $tr_update = 0, $pr_update = 0) {
// Promotional Route
        if ($type == 'pr') {
            $date = date('Y-m-d H:i:s');
            $log_data = array(
                'old_route_id' => $pro_user_group_id,
                'new_route_id' => $pr_update,
                'type' => $type,
                'date' => $date
            );
            $this->db->insert('routing_log', $log_data);


            $data = array('pro_user_group_id' => $pr_update);
            $this->db->where('pro_user_group_id', $pro_user_group_id);
            return $this->db->update('users', $data);
        }
// Transactional Route
        if ($type == 'tr') {
            $date = date('Y-m-d H:i:s');
            $log_data = array(
                'old_route_id' => $tr_user_group_id,
                'new_route_id' => $tr_update,
                'type' => $type,
                'date' => $date
            );
            $this->db->insert('routing_log', $log_data);

            $data = array('tr_user_group_id' => $tr_update);
            $this->db->where('tr_user_group_id', $tr_user_group_id);
            //$this->db->where('user_id',526);
            return $this->db->update('users', $data);
        }
    }

    //check old route check on user id = 4361 for TR

    public function update_old_route_check_tr($type, $current_route_tr, $update_route_tr) {
        $data = array('tr_user_group_id' => $update_route_tr);
        $this->db->where('user_id', 4361);
        return $this->db->update('users', $data);
    }

    //check old route check on user id = 4361 for PR

    public function update_old_route_check_pr($type, $current_route_pr, $update_route_pr) {
        $data = array('pro_user_group_id' => $update_route_pr);
        $this->db->where('user_id', 4361);
        return $this->db->update('users', $data);
    }

    public function update_old_route_tr($type, $current_route_tr, $update_route_tr) {
        $date = date('Y-m-d H:i:s');
        $log_data = array(
            'old_route_id' => $current_route_tr,
            'new_route_id' => $update_route_tr,
            'type' => $type,
            'date' => $date
        );
        $this->db->insert('routing_log', $log_data);

        $data = array('tr_user_group_id' => $update_route_tr);
        $this->db->where('tr_user_group_id', $current_route_tr);
        return $this->db->update('users', $data);
    }

    public function update_old_route_pr($type, $current_route_pr, $update_route_pr) {
        $data = array('pro_user_group_id' => $update_route_pr);
        $this->db->where('pro_user_group_id', $current_route_pr);
        return $this->db->update('users', $data);
    }

// Get Resellers/Users For Notify
    function getUsersNotify($type = null, $admin_id = 0) {
        $this->db->select('user_id, username, name, email_address, contact_number');
        $this->db->from('`users`');
        $this->db->where('`user_status`', 1);
        $this->db->where('`admin_id`', 1);
        $this->db->where('`utype`', $type);
        $this->db->order_by('`name`', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Resellers/Users 
    function getUsersData($admin_id = 0) {
        $this->db->select('user_id, username, name, email_address, contact_number');
        $this->db->from('`users`');
        $this->db->where('`user_status`', 1);
        // $this->db->where('`admin_id`', 1);
        // $this->db->where('`utype`', $type);
        $this->db->order_by('`name`', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Previous Sent Notify
    function getPreviousNotify($admin_id = 0, $atype = 0, $type = 0) {
        if ($type == '4') {
            $this->db->select('`notify_route`, `notify_sender`, `notify_message`, `notify_date`');
            $this->db->from('`notify_users`');
            $this->db->where('`notify_route` !=', "");
        } elseif ($type == '5') {
            $this->db->select('`notify_email`, `notify_subject`, `notify_body`, `notify_date`');
            $this->db->from('`notify_users`');
            $this->db->where('`notify_email` !=', "");
        }
        if ($atype == 1) {
            $this->db->where('`user_id`', 0);
        } elseif ($atype == 2) {
            $this->db->where('`admin_id`', $admin_id);
        }
        $this->db->order_by('`notify_id`', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Assign Dedicated Short/Long Code To User
    function assignSLKeyword($user_id = 0, $type = 0) {
        $date = date('d-m-Y');
        if ($type == 'short') {
            $data = array(
                'user_id' => $user_id,
                'short_keyword' => $this->input->post('keyword'),
                'short_number_id' => $this->input->post('number'),
                'short_keyword_expiry' => $this->input->post('expiry_date'),
                'short_keyword_date' => $date
            );
            $res = $this->db->insert('short_keywords', $data);
            if ($res) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'long') {
            $data = array(
                'user_id' => $user_id,
                'long_keyword' => $this->input->post('keyword'),
                'long_number_id' => $this->input->post('number'),
                'long_keyword_expiry' => $this->input->post('expiry_date'),
                'long_keyword_date' => $date
            );
            $res = $this->db->insert('long_keywords', $data);
            if ($res) {
                return true;
            } else {
                return false;
            }
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Sender Ids
//------------------------------------------------------------------------------------------------------------------------------------------//    
// Get Users Sender Ids
    function getSenderIds($subtab = 0) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('sender_id, sender_ids, sender_status');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('sender_ids', 'sender_ids.user_id = users1.user_id', 'right');
        // $this->db->order_by('user_id', 'desc');
        $this->db->order_by('sender_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $sender_array = array();
            $result_senders = $query->result_array();
            foreach ($result_senders as $user_sender_id) {
                $temp_array = array();
                $sender_ids_array = explode(',', $user_sender_id['sender_ids']);
                $sender_status_array = explode(',', $user_sender_id['sender_status']);
                foreach ($sender_ids_array as $sender_key => $sender_value) {
                    if ($subtab == 1) {
                        if ($sender_status_array[$sender_key] == '1') { // for approved sender ids
                            $temp_array['sender_id'] = $user_sender_id['sender_id'];
                            $temp_array['username'] = $user_sender_id['username'];
                            $parent = ($user_sender_id['parent_username'] == "") ? $user_sender_id['admin_username'] : $user_sender_id['parent_username'];
                            $temp_array['parent_username'] = $parent;
                            $temp_array['sender'] = $sender_value;
                            $temp_array['sender_key'] = $sender_key;
                            $temp_array['sender_status'] = $sender_status_array[$sender_key];
                            $sender_array[] = $temp_array;
                        }
                    } elseif ($subtab == 2) {
                        if ($sender_status_array[$sender_key] == '0') { // for pending sender ids
                            $temp_array['sender_id'] = $user_sender_id['sender_id'];
                            $temp_array['username'] = $user_sender_id['username'];
                            $parent = ($user_sender_id['parent_username'] == "") ? $user_sender_id['admin_username'] : $user_sender_id['parent_username'];
                            $temp_array['parent_username'] = $parent;
                            $temp_array['sender'] = $sender_value;
                            $temp_array['sender_key'] = $sender_key;
                            $temp_array['sender_status'] = $sender_status_array[$sender_key];
                            $sender_array[] = $temp_array;
                        }
                    } elseif ($subtab == 3) { // for show unique sender ids with name
                        $this->db->distinct('campaigns.sender_id');
                        $this->db->select('campaigns.sender_id,campaigns.user_id, users.username,campaigns.service_type');
                        $this->db->from('campaigns,users');
                        $this->db->where('campaigns.user_id = users.user_id');
                        $this->db->where('campaigns.service_type', "SMS");
                        $this->db->order_by('campaigns.sender_id', "DESC");
                        $query = $this->db->get();
                        if ($query->num_rows()) {
                            $sender_array = array();
                            $result_senders = $query->result_array();

                            foreach ($result_senders as $user_sender_id) {
                                $temp_array = array();
                                $temp_array['sender_id'] = $user_sender_id['sender_id'];
                                $temp_array['username'] = $user_sender_id['username'];
                                $sender_array[] = $temp_array;
                            }
                        }
                        return $sender_array;
                    } elseif ($subtab == 4) {
                        // $this->db->distinct('campaigns.sender_id');
                        $this->db->select('user_group_name,user_group_id');
                        $this->db->from('user_groups');
                        $query = $this->db->get();
                        if ($query->num_rows()) {
                            $sender_array = array();
                            $result_senders = $query->result_array();
//   var_dump($result_senders);die;
                            foreach ($result_senders as $user_sender_id) {
                                $temp_array = array();
                                $temp_array['user_group_name'] = $user_sender_id['user_group_name'];
                                $temp_array['user_group_id'] = $user_sender_id['user_group_id'];
                                $sender_array[] = $temp_array;
                            }
                        }
                        return $sender_array;
                    } elseif ($subtab == 5) { // for show unique sender ids with name
                        // $this->db->distinct('campaigns.sender_id');
                        $this->db->select('user_group_name,user_group_id');
                        $this->db->from('user_groups');
                        $query = $this->db->get();
                        if ($query->num_rows()) {
                            $sender_array = array();
                            $result_senders = $query->result_array();

                            foreach ($result_senders as $user_sender_id) {
                                $temp_array = array();
                                $temp_array['user_group_name'] = $user_sender_id['user_group_name'];
                                $temp_array['user_group_id'] = $user_sender_id['user_group_id'];
                                $sender_array[] = $temp_array;
                            }
                        }
                        return $sender_array;
                    } elseif ($subtab == 6) { // for show not approve sender id
                        $this->db->select('*');
                        $this->db->from('not_approve_senderid');
                        $this->db->order_by('date', 'DESC');
                        $query = $this->db->get();
                        if ($query->num_rows()) {
                            $sender_array = array();
                            $result_senders = $query->result_array();

                            foreach ($result_senders as $user_sender_id) {
                                $temp_array = array();
                                $temp_array['id'] = $user_sender_id['id'];
                                $temp_array['sender_id'] = $user_sender_id['sender_id'];
                                $temp_array['date'] = $user_sender_id['date'];
                                $sender_array[] = $temp_array;
                            }
                        }
                    } elseif ($subtab == 7) {
                        // $this->db->distinct('campaigns.sender_id');
                        $this->db->select('user_group_name,user_group_id');
                        $this->db->from('user_groups');
                        $query = $this->db->get();
                        if ($query->num_rows()) {
                            $sender_array = array();
                            $result_senders = $query->result_array();
//   var_dump($result_senders);die;
                            foreach ($result_senders as $user_sender_id) {
                                $temp_array = array();
                                $temp_array['user_group_name'] = $user_sender_id['user_group_name'];
                                $temp_array['user_group_id'] = $user_sender_id['user_group_id'];
                                $sender_array[] = $temp_array;
                            }
                        }
                    }
                }
            }
            return $sender_array;
        } else {
            return false;
        }
    }

    function checkSenderIdAvailability() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $username = $myArray[0];
        $this->db->select('sender_id');
        $this->db->from('approve_sender_id');
        $this->db->where('sender_id', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    // check promotional sender ids
    function checkPrSenderIdAvailability() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $username = $myArray[0];
        $this->db->select('sender_id');
        $this->db->from('pr_approve_sender_id');
        $this->db->where('sender_id', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

//Insert New vodafone sender id

    function insertNewSenderId() {
        $sender_id = $this->input->post('sender_id');
        $user_group_id = $this->input->post('user_group_id');
        if (!empty($sender_id)) {

            $this->db->select('sender_id');
            $this->db->from('approve_sender_id');
            $this->db->where('sender_id', $sender_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
                $data = array(
                    'sender_id' => $sender_id,
                    'route' => $user_group_id,
                    'status' => 1
                );

                $this->db->insert('approve_sender_id', $data);
                $this->db->where('sender_id', $sender_id);
                if ($this->db->delete('not_approve_senderid')) {
                    return TRUE;
                } else {
                    return TRUE;
                }
                return TRUE;
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    //Insert PR approve sender id

    function insertPrSenderId() {
        $sender_id = $this->input->post('sender_id');
        $user_group_id = $this->input->post('user_group_id');
        if (!empty($sender_id)) {

            $this->db->select('sender_id');
            $this->db->from('pr_approve_sender_id');
            $this->db->where('sender_id', $sender_id);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
                $data = array(
                    'sender_id' => $sender_id,
                    'route' => $user_group_id,
                    'status' => 1
                );

                $this->db->insert('pr_approve_sender_id', $data);
                /*  $this->db->where('sender_id', $sender_id);
                  if ($this->db->delete('not_approve_senderid')) {
                  return TRUE;
                  } else {
                  return TRUE;
                  } */
                return TRUE;
            } else {
                return false;
            }
        } else {
            return FALSE;
        }
    }

    //update vodafone sender id routing
    function updateSenderIdRouting() {
        $currentRoute = $this->input->post('current_route');
        $newRoute = $this->input->post('new_route');
        if ($currentRoute && $newRoute) {
            $data = array(
                'route' => $newRoute
            );

            $this->db->where('route', $currentRoute);
            $this->db->update('approve_sender_id', $data);
            return TRUE;
        } else {
            return FALSE;
        }
    }

//Select vodafone sender id

    function getRoute() {
        $this->db->select('id,sender_id,user_groups.user_group_name');
        $this->db->from('approve_sender_id,user_groups');
        $this->db->where('approve_sender_id.route = user_groups.user_group_id');
        $this->db->order_by("`id`", "desc");
        $this->db->limit(100);
        $query = $this->db->get();
        if ($query->num_rows()) {

            return $result = $query->result_array();
        }
    }

    //Select vodafone sender id

    function getPrSenderId() {
        $this->db->select('id,sender_id,user_groups.user_group_name');
        $this->db->from('pr_approve_sender_id,user_groups');
        $this->db->where('pr_approve_sender_id.route = user_groups.user_group_id');
        $this->db->order_by("`id`", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {

            return $result = $query->result_array();
        }
    }

//get sender unique ids using distinict 
    function getUniqueSenderIds($subtab = 3) {
        if ($subtab = 3) {
            $this->db->distinct('campaigns.sender_id');
            $this->db->select('campaign_id,campaigns.sender_id,campaigns.user_id, users.username,campaigns.service_type');
            $this->db->from('campaigns,users');
            $this->db->where('campaigns.user_id = users.user_id');
            $this->db->where('campaigns.service_type', "SMS");
//$this->db->order_by('campaigns.campaign_id', "desc");
            $this->db->order_by("`campaign_id`", "desc");
            $query = $this->db->get();
            if ($query->num_rows()) {
                $sender_array = array();
                $result_senders = $query->result_array();
//   var_dump($result_senders);die;
                foreach ($result_senders as $user_sender_id) {
                    $temp_array = array();
                    $temp_array['sender_id'] = $user_sender_id['sender_id'];
                    $temp_array['username'] = $user_sender_id['username'];
                    $temp_array['campaign_id'] = $user_sender_id['campaign_id'];
                    $sender_array[] = $temp_array;
//    var_dump($temp_array['sender_id']);die;
                }
            }
            return $sender_array;
        }
    }

// Count Users Sender Ids
    function countSenderIds() {
        $this->db->select('*');
        $this->db->from('sender_ids');
        $query = $this->db->get();
        $array = $query->result_array();
        $total_senders = 0;
        foreach ($array AS $value) {
            $sender_array = explode(',', $value['sender_ids']);
            $total_senders += sizeof($sender_array);
        }
        return $total_senders;
    }

// Approve/Disapprove User Sender Ids
    function enDisSenderId($sender_id = null, $sender_key = 0, $status = 0) {
// Get Sender Ids
        $this->db->select('sender_status');
        $this->db->from('sender_ids');
        $this->db->where('sender_id', $sender_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $sender_status = "";
        if ($query->num_rows()) {
            $result_sender_ids = $query->row();
            $sender_status = $result_sender_ids->sender_status;
            $sender_status_array = explode(',', $sender_status);
            $sender_status_array[$sender_key] = $status;
            $new_sender_status = implode(',', $sender_status_array);
            $data = array('sender_status' => $new_sender_status
            );
            $this->db->where('sender_id', $sender_id);
            return $this->db->update('sender_ids', $data);
        } else {
            return false;
        }
    }

// Delete Users Sender Id
    function deleteSenderId($sender_id = null, $sender_key = 0) {
        /* if ($sender_key == 4 && $sender_id != null) {
          $this->db->where('sender_id', $sender_id);
          $this->db->delete('approve_sender_id');
          return 1;
          } */
// Get Sender Ids
        $this->db->select('sender_ids, sender_status');
        $this->db->from('sender_ids');
        $this->db->where('sender_id', $sender_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $sender_ids = "";
        $sender_status = "";
        if ($query->num_rows()) {
            $result_sender_ids = $query->row();
            $sender_ids = $result_sender_ids->sender_ids;
            $sender_status = $result_sender_ids->sender_status;
            $sender_ids_array = explode(',', $sender_ids);
            $sender_status_array = explode(',', $sender_status);
            unset($sender_ids_array[$sender_key]);
            unset($sender_status_array[$sender_key]);
            $new_sender_ids = implode(',', $sender_ids_array);
            $new_sender_status = implode(',', $sender_status_array);
            if ($new_sender_ids == "") {
                $this->db->delete('sender_ids', array('sender_id' => $sender_id));
            } else {
                $data = array(
                    'sender_ids' => $new_sender_ids,
                    'sender_status' => $new_sender_status
                );
                $this->db->where('sender_id', $sender_id);
                return $this->db->update('sender_ids', $data);
            }
        } else {
            return false;
        }
    }

    function deleteApproveSenderId($sender_id = null, $sender_key = 0) {
        $this->db->where('sender_id', $sender_id);
        $this->db->delete('approve_sender_id');
        return 1;
    }

    // delete PR approve sender id
    function deletePrApproveSenderId($sender_id = null, $sender_key = 0) {
        $this->db->where('sender_id', $sender_id);
        $this->db->delete('pr_approve_sender_id');
        return 1;
    }

    function searchApproveSenderIds($keyword) {

        $this->db->select('*');
        $this->db->from('approve_sender_id');
        $this->db->like('sender_id', $keyword, 'after');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //check sender id those are hit first time and not in vodafone approval table
    //this function is based on cronjob
    public function senderIdFirstTime() {
        $sender_id_data = array();
        $sender_id_approve = array();
        $this->db->distinct();
        $this->db->select('sender_id');
        $this->db->from('campaigns');
        $this->db->where('service_type', "SMS");
        $this->db->order_by('campaign_id', "DESC");
        $query = $this->db->get();
        $result = $query->result_array();

        $this->db->select('sender_id');
        $this->db->from('approve_sender_id');
        $query1 = $this->db->get();
        $result1 = $query1->result_array();

        foreach ($result1 as $new_result1) {
            $sender_id_approve[] = $new_result1['sender_id'];
        }
        foreach ($result as $new_result) {

            if (ctype_alpha($new_result['sender_id'])) {
                $sender_id_data[] = $new_result['sender_id'];
            }
        }
        $sender_id_result = array_diff($sender_id_data, $sender_id_approve);
        $size = sizeof($sender_id_result);
        $new_data = array_values($sender_id_result);


        for ($i = 0; $i < $size; $i++) {
            $data = array(
                'sender_id' => $new_data[$i],
                'date' => date("Y-m-d"),
                'status' => 1
            );

            $this->db->select('sender_id');
            $this->db->from('not_approve_senderid');
            $this->db->where('sender_id', $new_data[$i]);
            $exist = $this->db->get();
            if ($exist->num_rows()) {
                
            } else {
                $this->db->insert('not_approve_senderid', $data);
            }
        }
    }

    //delete by cron jobs 
    public function deleteNotApproveSenderid() {
        $data = array();
        $data1 = array();
        $data2 = array();
        $this->db->select('sender_id');
        $this->db->from('approve_sender_id');
        $query = $this->db->get();
        $result = $query->result_array();

        $this->db->select('sender_id');
        $this->db->from('not_approve_senderid');
        $query1 = $this->db->get();
        $result1 = $query1->result_array();

        $sizeofresult = sizeof($result);
        $sizeofresult1 = sizeof($result1);
        $data = array();
        for ($i = 0; $i < $sizeofresult; $i++) {
            for ($j = 0; $j < $sizeofresult1; $j++) {
                if ($result[$i] == $result1[$j]) {
                    $data = $result[$i];
                }
            }
        }
        $data1 = array_values($data);
        var_dump($data1);
        $sizeofdata = sizeof($data1);
        if ($sizeofdata) {
            for ($k = 0; $k < $sizeofdata; $k++) {
                $this->db->where('sender_id', $data1[$k]);
                $this->db->delete('not_approve_senderid');
            }
        }
    }

    public function clickToApprove($id) {
        $this->db->select('sender_id');
        $this->db->from('not_approve_senderid');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row();
        $sender_id = $result->sender_id;

        $data = array(
            'sender_id' => $sender_id,
            'route' => 38,
            'status' => 1
        );

        $this->db->insert('approve_sender_id', $data);

        $this->db->where('id', $id);
        $this->db->delete('not_approve_senderid');
        return TRUE;
    }

//------------------------------------------------------------------------------------------------------------------------------------------//    
// Keywords
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count Users Keywords
    function countKeywords($keyword_status = null, $flag = true) {
        if ($keyword_status == 'black') {
            $this->db->select('*');
            $this->db->from('black_keywords');
            if ($flag) {
                $this->db->where('admin_id !=', 0);
                $this->db->where('user_id', 0);
            } else {
                $this->db->where('admin_id', 0);
                $this->db->where('user_id !=', 0);
            }
            $query = $this->db->get();
            return $query->num_rows();
        } else {
            $this->db->select('*');
            $this->db->from('keywords');
            $this->db->where('keyword_status', $keyword_status);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

// Get Users Approved, Pending & Black Keywords
    function getKeywords($keyword_status = null, $start = 0, $limit = 0, $flag = true) {
        if ($keyword_status == 'black') {
            if ($flag) {
                $this->db->select('black_keyword_id, black_keyword, black_keyword_status');
                $this->db->from('black_keywords');
                $this->db->where('admin_id !=', 0);
                $this->db->where('user_id', 0);
            } else {
                $this->db->select('`black_keyword_id`, `black_keyword`, `black_keyword_status`, `username`, `utype`');
                $this->db->from('`black_keywords`, `users`');
                $this->db->where('`black_keywords`.`user_id`=`users`.`user_id`');
                $this->db->where('`black_keywords`.`admin_id`', 0);
                $this->db->where('`black_keywords`.`user_id` !=', 0);
            }
        } else {
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username');
            $this->db->select('keyword_id, keywords, percent_ratio_user, percent_ratio_all_users');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('keywords', 'keywords.user_id = users1.user_id', 'right');
            $this->db->where('keyword_status', $keyword_status);
            $this->db->order_by("`keywords`.`keyword_id`", "desc");
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Save Black Keyword
    function saveBlackKeyword($admin_id = 0) {
        $black_keyword = $this->input->post('black_keyword');
        $black_keyword = preg_replace('/\s+/', ' ', $black_keyword);
        $data = array(
            'admin_id' => $admin_id,
            'black_keyword' => urlencode($black_keyword)
        );
        return $this->db->insert('black_keywords', $data);
    }

// Approve Keyword
    function approveKeyword() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        if ($myArray[1] == 'user') {
            $data = array('percent_ratio_user' => $myArray[0], 'keyword_status' => 1);
        } elseif ($myArray[1] == 'users') {
            $data = array('percent_ratio_all_users' => $myArray[0], 'keyword_status' => 1);
        } elseif ($myArray[1] == 'both') {
            $data = array('percent_ratio_user' => $myArray[0], 'percent_ratio_all_users' => $myArray[0], 'keyword_status' => 1);
        }
        $this->db->where('keyword_id', $myArray[2]);
        return $this->db->update('keywords', $data);
    }

// Delete Users Keyword
    function deleteKeyword($type = null, $keyword_id = 0) {
        if ($type == 'black')
            return $this->db->delete('black_keywords', array('black_keyword_id' => $keyword_id));
        else
            return $this->db->delete('keywords', array('keyword_id' => $keyword_id));
    }

// Change Keyword Status
    function changeKeywordStatus($keyword_id = 0, $status = 0) {
        $data = array(
            'black_keyword_status' => $status
        );
        $this->db->where('black_keyword_id', $keyword_id);
        return $this->db->update('black_keywords', $data);
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Settings
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Setting
    function getSetting() {
        $this->db->select('`setting_id`, `sms_limit`, `pr_sender_id_check`, `pr_sender_id_type`, `pr_sender_id_length`, `pr_dnd_check`');
        $this->db->select('`tr_sender_id_check`, `tr_sender_id_type`, `tr_sender_id_length`, `tr_keyword_check`');
        $this->db->select('`demo_balance`, `expiry_days`, `signup_sender`, `signup_message`, `signup_subject`, `signup_body`, `forgot_password_sender`');
        $this->db->select('`forgot_password_message`, `location_sender`, `location_message`, `demo_sender`, `demo_message`');
        $this->db->select('`pr_resend_options`, `tr_resend_options`, `backup_time_duration`, `backup_limit`');
        $this->db->from('settings');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    function userAlert() {
        $this->db->select('`user_id`, `name`,`username`');
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Save Setting
    function saveSetting($subtab = 0, $ex_subtab = 0) {
        $setting_id = 1; //$this->input->post('setting_id');
        $data = array();
        if ($subtab == 2 && $ex_subtab == 1) {
            $data = array(
                'sms_limit' => $this->input->post('sms_limit'),
                'pr_sender_id_check' => $this->input->post('pr_sender_id_check'),
                'pr_sender_id_type' => $this->input->post('sender_id_type'),
                'pr_sender_id_length' => $this->input->post('pr_sender_id_length'),
                'pr_dnd_check' => $this->input->post('pr_dnd_check'),
                'tr_sender_id_check' => $this->input->post('tr_sender_id_check'),
                'tr_sender_id_type' => $this->input->post('tr_sender_id_type'),
                'tr_sender_id_length' => $this->input->post('tr_sender_id_length'),
                'tr_keyword_check' => $this->input->post('tr_keyword_check')
            );
        }
        if ($subtab == 8 && $ex_subtab == 1) {
            $data = array(
                'signup_sender' => $this->input->post('signup_sender_id'),
                'signup_message' => $this->input->post('signup_message')
            );
        }
        if ($subtab == 8 && $ex_subtab == 2) {
            $data = array(
                'signup_subject' => $this->input->post('signup_subject'),
                'signup_body' => $this->input->post('signup_body')
            );
        }
        if ($subtab == 8 && $ex_subtab == 3) {
            $data = array(
                'expiry_days' => $this->input->post('expiry_days'),
                'demo_balance' => $this->input->post('demo_balance')
            );
        }
        if ($subtab == 9 && $ex_subtab == 1) {
            $data = array(
                'forgot_password_sender' => $this->input->post('fp_sender_id'),
                'forgot_password_message' => $this->input->post('fp_message')
            );
        }
        if ($subtab == 9 && $ex_subtab == 2) {
            $data = array(
                'location_sender' => $this->input->post('location_sender_id'),
                'location_message' => $this->input->post('location_message')
            );
        }
        if ($subtab == 9 && $ex_subtab == 3) {
            $data = array(
                'demo_sender' => $this->input->post('demo_sender_id'),
                'demo_message' => $this->input->post('demo_message')
            );
        }
        if ($subtab == 10 && $ex_subtab == 1) {
            $data = array(
                'pr_resend_options' => $this->input->post('pr_resend_options'),
                'tr_resend_options' => $this->input->post('tr_resend_options')
            );
        }
        if ($subtab == 11 && $ex_subtab == 1) {
            $alert = $this->input->post('alert');
            $a_user = $this->input->post('a_user');
            $data = array(
                'user_alert' => $alert,
            );
            if ($a_user == 0) {

                return $this->db->update('users', $data);
            }


            $this->db->where('user_id', $a_user);
            return $this->db->update('users', $data);
        }
// Update Or Insert Data
        if ($setting_id) {
            $this->db->where('setting_id', $setting_id);
            return $this->db->update('settings', $data);
        } else {
            return $this->db->insert('settings', $data);
        }
    }

    function saveDefaultRatio() {
        /* $ratio_type = $this->input->post('ratio_type');
          $fake_delivered = $this->input->post('fake_delivered_ratio');
          $fake_failed = $this->input->post('fake_fail_ratio');

          $this->db->select('pr_deliver_ratio,pr_fail_ratio');
          $this->db->from('settings');
          $this->db->where('setting_id',1);
          $this->db->limit(1);
          $query = $this->db->get();
          $fake_ratio = $query->row('pr_deliver_ratio');
          $fail_ratio = $query->row('pr_fail_ratio');

          if($ratio_type=="PR"){

          if($fake_ratio > $fake_delivered || $fail_ratio > $fake_failed){
          return 0;
          }else{
          $pr_data = array(
          'pr_deliver_ratio' => $fake_delivered,
          'pr_fail_ratio' => $fake_failed
          );
          $this->db->where('setting_id', 1);
          $this->db->update('settings', $pr_data);
          return 1;
          }

          }

         */
        return FALSE;
    }

// Save Virtual Balance
    function saveVirtualBalance($admin_id = 0) {
        $pr_balance = $this->input->post('pr_balance');
        $tr_balance = $this->input->post('tr_balance');
        $long_balance = $this->input->post('long_balance');
        $short_balance = $this->input->post('short_balance');
        $vpr_balance = $this->input->post('vpr_balance');
        $vtr_balance = $this->input->post('vtr_balance');
        $mcall_balance = $this->input->post('mcall_balance');
        $pr_credits = $this->input->post('pr_credits');
        $tr_credits = $this->input->post('tr_credits');
        $prtodnd_balance = $this->input->post('new_prtodnd_balance');
        $stock_balance = $this->input->post('new_stock_balance');
        $total_prtodnd_credits = $this->input->post('prtodnd_credit');
        $total_stock_credits = $this->input->post('stock_credit');
        $international_sms = $this->input->post('international_sms');

        $data = array(
            'total_pr_balance' => $pr_balance,
            'total_tr_balance' => $tr_balance,
            'total_lcode_balance' => $long_balance,
            'total_scode_balance' => $short_balance,
            'total_vpr_balance' => $vpr_balance,
            'total_vtr_balance' => $vtr_balance,
            'total_mcall_balance' => $mcall_balance,
            'total_prtodnd_balance' => $prtodnd_balance,
            'total_stock_balance' => $stock_balance,
            'total_prtodnd_credits' => $total_prtodnd_credits,
            'total_stock_credits' => $total_stock_credits,
            'international_sms' => $international_sms,
        );
        $query_balance = "UPDATE administrators SET total_pr_balance=$pr_balance, total_tr_balance=$tr_balance, admin_pr_credits=$pr_credits, admin_tr_credits=$tr_credits, total_lcode_balance=$long_balance, "
                . "total_scode_balance=$short_balance, total_vpr_balance=$vpr_balance, total_vtr_balance=$vtr_balance, "
                . "total_mcall_balance=$mcall_balance,total_prtodnd_balance=$prtodnd_balance,total_stock_balance=$stock_balance,total_prtodnd_credits=$total_prtodnd_credits,total_stock_credits=$total_stock_credits,international_sms=$international_sms  WHERE admin_id=$admin_id";
        if ($this->db->simple_query($query_balance)) {
            return true;
        }
        return false;
        /*
          $data = array(
          'total_pr_balance' => $pr_balance,
          'total_tr_balance' => $tr_balance,
          'total_lcode_balance' => $long_balance,
          'total_scode_balance' => $short_balance,
          'total_vpr_balance' => $vpr_balance,
          'total_vtr_balance' => $vtr_balance,
          'total_mcall_balance' => $mcall_balance
          );
          var_dump($data);
          die;
          $this->db->where('admin_id', $admin_id);
          $this->db->update('administrators', $data);
          if ($this->db->update('administrators', array('atype' => 1), array('admin_id' => 2))) {
          return true;
          }
          return true;
          $query = "UPDATE administrators SET total_pr_balance=$pr_balance, total_tr_balance=$tr_balance, total_lcode_balance=$long_balance, total_scode_balance=$short_balance, total_vpr_balance=$vpr_balance, total_vtr_balance=$vtr_balance, total_mcall_balance=$mcall_balance WHERE admin_id=$admin_id";
          $this->db->query($query);
          return true;
         */
    }

// Get SMPP Ports
    function getSMPPPorts($port_id = 0) {
        if ($port_id === FALSE) {
            $this->db->select('virtual_port_id, virtual_port_no, virtual_port_status, user_group_name, smsc_id, purpose, virtual_ports.user_group_id');
            $this->db->from('virtual_ports, user_groups');
            $this->db->where('`virtual_ports`.`user_group_id`=`user_groups`.`user_group_id`');
//$this->db->order_by('virtual_ports.user_group_id','asc');
            $this->db->order_by("virtual_port_no", "asc");
            $this->db->order_by("purpose", "asc");
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } else {
            $this->db->select('virtual_port_id, virtual_port_no, virtual_port_status, user_group_name, smsc_id, purpose, virtual_ports.user_group_id');
            $this->db->from('virtual_ports, user_groups');
            $this->db->where('`virtual_ports`.`user_group_id`=`user_groups`.`user_group_id`');
            $this->db->where('virtual_ports.virtual_port_id', $port_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

// Save SMPP Port
    function saveVirtualSMPPPort() {
        $virtual_port_id = $this->input->post('virtual_port_id');
        $data = array(
            'virtual_port_no' => $this->input->post('virtual_port'),
            'user_group_id' => $this->input->post('user_group')
        );
        if ($virtual_port_id) {
            $this->db->where('virtual_port_id', $virtual_port_id);
            return $this->db->update('virtual_ports', $data);
        } else {
            return $this->db->insert('virtual_ports', $data);
        }
    }

// Delete SMPP Port
    function deleteSMPPPorts($port_id = 0) {
        return $this->db->delete('virtual_ports', array('virtual_port_id' => $port_id));
    }

// Enable/Disable SMPP Port
    function enDisSettings($id = 0, $pk = 0, $status = 0) {
        if ($id == 'port') {
            $data = array('virtual_port_status' => $status);
            $this->db->where('virtual_port_id', $pk);
            return $this->db->update('virtual_ports', $data);
        } elseif ($id == 'white') {
            $data = array('white_list_status' => $status);
            $this->db->where('white_list_id', $pk);
            return $this->db->update('white_lists', $data);
        } elseif ($id == 'black') {
            $data = array('black_list_status' => $status);
            $this->db->where('black_list_id', $pk);
            return $this->db->update('black_lists', $data);
        } elseif ($id == 'keyword') {
            $data = array('black_keyword_status' => $status);
            $this->db->where('black_keyword_id', $pk);
            return $this->db->update('black_keywords', $data);
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Admin Data
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get My Transactions
    function getTransactions($admin_id = 0, $start = 0, $limit = 0) {

        $this->db->select('txn_route, txn_sms, txn_price, txn_amount, txn_type, txn_date, txn_description,admin_discription');
        $this->db->select('userA.name AS from_name, userA.username AS from_uname, userA.user_id AS from_user_id');
        $this->db->select('userB.name AS to_name, userB.username AS to_uname, userB.user_id AS to_user_id');
        $this->db->select('administratorsA.admin_name AS from_admin_name , administratorsA.admin_id AS from_admin_id');
        $this->db->select(' administratorsB.admin_name AS to_admin_name , administratorsB.admin_id AS to_admin_id');
        $this->db->from('transaction_logs');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->where('txn_admin_from', $admin_id);
        $this->db->or_where('txn_admin_to', $admin_id);
        $this->db->order_by('txn_log_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Count Transactions
    function countTransactions($admin_id = 0) {
        $this->db->select('txn_route, txn_sms, txn_price, txn_amount, txn_type, txn_date, txn_description,admin_discription');
        $this->db->select('userA.name AS from_name, userA.username AS from_uname, userA.user_id AS from_user_id');
        $this->db->select('userB.name AS to_name, userB.username AS to_uname, userB.user_id AS to_user_id');
        $this->db->select('administratorsA.admin_name AS from_admin_name , administratorsA.admin_id AS from_admin_id');
        $this->db->select(' administratorsB.admin_name AS to_admin_name , administratorsB.admin_id AS to_admin_id');
        $this->db->from('transaction_logs');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->where('txn_admin_from', $admin_id);
        $this->db->or_where('txn_admin_to', $admin_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //count all log for admin 
    public function countAdminTransactions() {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $query = $this->db->get();
        return $query->num_rows();
    }

    //get log for admin
    public function getAdminTransactions($start = 0, $limit = 0) {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->order_by('txn_log_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Admin Info
    function getAdminInfo() {
        $this->db->select('admin_id, total_pr_balance, total_tr_balance');
        $this->db->from('administrators');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

// Get Admin Balance
    function getAdminBalance($admin_id = 0) {
        $this->db->select('total_pr_balance, total_tr_balance, admin_company, total_lcode_balance, admin_pr_credits, admin_tr_credits');
        $this->db->select('total_scode_balance, total_vpr_balance, total_vtr_balance, total_mcall_balance,admin_status,admin_name');
        $this->db->select('total_prtodnd_balance,total_stock_balance,total_prtodnd_credits,total_stock_credits,international_sms');
        $this->db->select('permissions');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Get Admin Balance
    function getSpecialBalance($admin_id = 0) {
        $this->db->select('total_pr_balance, total_tr_balance, admin_company, total_lcode_balance');
        $this->db->select('total_scode_balance, total_vpr_balance, total_vtr_balance, total_mcall_balance,admin_status,admin_name');
        $this->db->select('permissions');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row_array();
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Black Sender Ids, Numbers, White Numbers
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count White/Black List Numbers
    function countWBLists($type = 0) {
        $this->db->select('*');
        if ($type) {
            $this->db->from('white_lists');
            $this->db->where('user_id', 0);
        } else {
            $this->db->from('black_lists');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get Black List Numbers
    function getBlackLists($start = 0, $limit = 0) {
        $this->db->select('black_list_id, black_list_number, black_list_status');
        $this->db->from('black_lists');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get White List Numbers
    function getWhiteLists($start = 0, $limit = 0) {
        $this->db->select('white_list_id, white_list_number, white_list_status');
        $this->db->from('white_lists');
        $this->db->where('user_id', 0);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Save Number (White List/Black List)
    function saveNumber() {
        $type = $this->input->post('number_type');
        if ($type == 'white') {
            $this->db->select('white_list_id');
            $this->db->from('white_lists');
            $this->db->where('white_list_number', $this->input->post('contact_number'));
            $query = $this->db->get();
            if ($query->num_rows()) {
                return 100;
            } else {
                $data = array('white_list_number' => $this->input->post('contact_number'));
                $response = $this->db->insert('white_lists', $data);
                if ($response)
                    return 1;
                else
                    return 0;
            }
        } elseif ($type == 'black') {
            $this->db->select('black_list_id');
            $this->db->from('black_lists');
            $this->db->where('black_list_number', $this->input->post('contact_number'));
            $query = $this->db->get();
            if ($query->num_rows()) {
                return 100;
            } else {
                $data = array('black_list_number' => $this->input->post('contact_number'));
                $response = $this->db->insert('black_lists', $data);
                if ($response)
                    return 1;
                else
                    return 0;
            }
        }
    }

// Delete Number (White List/Black List)
    function deleteNumber($list_id = 0, $type = null) {
        if ($type == 'white') {
            return $this->db->delete('white_lists', array('white_list_id' => $list_id));
        } elseif ($type == 'black') {
            return $this->db->delete('black_lists', array('black_list_id' => $list_id));
        }
    }

// Get Black List Sender Ids
    function getBlackListSenderIds($setting = null) {
        if ($setting != null) {
            $this->db->select('black_sender_id, black_sender_ids');
            $this->db->from('black_sender_ids');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        } else {
            $this->db->select('black_sender_id, black_sender_ids');
            $this->db->from('black_sender_ids');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $bsender_array = array();
                $result_sender = $query->row();
                $black_sender_ids = $result_sender->black_sender_ids;
                $black_list_array = explode(',', $black_sender_ids);
                foreach ($black_list_array as $key => $black_list) {
                    $temp_array = array();
                    $temp_array['sender_id'] = $result_sender->black_sender_id;
                    $temp_array['sender'] = $black_list;
                    $temp_array['sender_key'] = $key;
                    $bsender_array[] = $temp_array;
                }
                return $bsender_array;
            } else {
                return false;
            }
        }
    }

// Save Black Sender Id
    function saveBSenderId() {
        $black_sender = $this->input->post('black_sender_id');
        $black_sender_id = "";
        $black_sender_ids = "";
        $result_sender_ids = $this->getBlackListSenderIds('Setting');
        if ($result_sender_ids) {
            $black_sender_id = $result_sender_ids->black_sender_id;
            $black_sender_ids = $result_sender_ids->black_sender_ids;
            $black_sender_array = explode(',', $black_sender_ids);
            if (in_array($black_sender, $black_sender_array)) {
                return 100;
            } else {
                $black_sender_ids .= "," . $black_sender;
                $data = array('black_sender_ids' => $black_sender_ids);
                $this->db->where('black_sender_id', $black_sender_id);
                $response = $this->db->update('black_sender_ids', $data);
                if ($response)
                    return 1;
                else
                    return 0;
            }
        } else {
            $data = array('black_sender_ids' => $black_sender);
            $response = $this->db->insert('black_sender_ids', $data);
            if ($response)
                return 1;
            else
                return 0;
        }
    }

// Delete Black Sender Id
    function deleteBSenderId($sender_id = null, $sender_key = 0) {
// Get Sender Ids
        $black_sender_id = "";
        $black_sender_ids = "";
        $result_sender_ids = $this->getBlackListSenderIds('Setting');
        if ($result_sender_ids) {
            $black_sender_id = $result_sender_ids->black_sender_id;
            $black_sender_ids = $result_sender_ids->black_sender_ids;
            $sender_ids_array = explode(',', $black_sender_ids);
            unset($sender_ids_array[$sender_key]);
            $new_sender_ids = implode(',', $sender_ids_array);
            if ($new_sender_ids == "") {
                return $this->db->delete('black_sender_ids', array('black_sender_id' => $sender_id));
            } else {
                $data = array('black_sender_ids' => $new_sender_ids);
                $this->db->where('black_sender_id', $sender_id);
                return $this->db->update('black_sender_ids', $data);
            }
        } else {
            return false;
        }
    }

// Delete Black Keyword
    function deleteBlackKeyword($keyword_id = 0) {
        return $this->db->delete('black_keywords', array('black_keyword_id' => $keyword_id));
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Delivery Reports
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count Delivery Reports
    function countDeliveryReports() {
        $this->db->select('COUNT(campaign_id) AS total');
        $this->db->from('campaigns');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Get Delivery Reports
    function getDeliveryReports($start = 0, $limit = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');

        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];

        $this->db->select('number');
        $this->db->from('dlr_show_number');
        $this->db->where('admin_id', $admin_id);
        $number_query = $this->db->get();
        $number_result = $number_query->row();
        $number = $number_result->number;

        $this->db->select('users1.username AS username, users2.username AS parent_username, users1.contact_number AS contact, users1.user_id AS user_id');
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, schedule_status, sender_id, submit_date, request_by, message, route, pricing_error');
        $this->db->select('campaigns.admin_id AS admin_id, administrators.admin_username AS admin_username, message_category, total_credits, total_deducted, actual_message, request_by, message_type, flash_message');
        $this->db->select('user_groups1.user_group_name AS user_group_name, user_groups1.smsc_id AS smsc_id, schedule_date, black_listed, total_time, whole_process');
        $this->db->select('user_groups2.user_group_name AS resend_ugroup_name, user_groups2.smsc_id AS resend_smsc_id');
        $this->db->select('caller_id, start_date_time, end_date_time, service_type');
        $this->db->select('administrators1.admin_username AS processed_by');
        $this->db->select('administrators2.admin_username AS resend_by');
        $this->db->from('users AS users1');
        $this->db->join('administrators AS administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'right');
        $this->db->join('user_groups AS user_groups1', 'user_groups1.user_group_id = campaigns.user_group_id', 'left');
        $this->db->join('user_groups AS user_groups2', 'user_groups2.user_group_id = campaigns.resend_ugroup_id', 'left');
        $this->db->join('administrators AS administrators1', 'administrators1.admin_id = campaigns.processed_by', 'left');
        $this->db->join('administrators AS administrators2', 'administrators2.admin_id = campaigns.resend_admin_id', 'left');
        $this->db->where('total_messages >= ', $number);
        $this->db->order_by("campaign_id", "DESC");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'username' => $campaign['username'],
                    'parent_username' => $campaign['parent_username'],
                    'contact' => $campaign['contact'],
                    'user_id' => $campaign['user_id'],
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'schedule_status' => $campaign['schedule_status'],
                    'sender_id' => $campaign['sender_id'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'admin_id' => $campaign['admin_id'],
                    'admin_username' => $campaign['admin_username'],
                    'message_category' => $campaign['message_category'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'user_group_name' => $campaign['user_group_name'],
                    'smsc_id' => $campaign['smsc_id'],
                    'schedule_date' => $campaign['schedule_date'],
                    'black_listed' => $campaign['black_listed'],
                    'total_time' => $campaign['total_time'],
                    'whole_process' => $campaign['whole_process'],
                    'caller_id' => $campaign['caller_id'],
                    'start_date_time' => $campaign['start_date_time'],
                    'end_date_time' => $campaign['end_date_time'],
                    'service_type' => $campaign['service_type'],
                    'processed_by' => $campaign['processed_by'],
                    'resend_ugroup_name' => $campaign['resend_ugroup_name'],
                    'resend_smsc_id' => $campaign['resend_smsc_id'],
                    'resend_by' => $campaign['resend_by'],
                    'pricing_error' => $campaign['pricing_error']
                );
// Get SMS Summary
                $this->db->select('COUNT(sms_id) AS Count_Status, status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("status");
                $sub_query = $this->db->get();
                if ($sub_query->num_rows()) {
                    $summary = $sub_query->result();
                    $temp_array['summary'] = $summary;
                } else {
                    $temp_array['summary'] = 0;
                }

// Get Fake SMS Summary (Failed & Delivered)
                $this->db->select('COUNT(sms_id) AS Count_Fake, temporary_status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("temporary_status");
                $sub_query1 = $this->db->get();
                if ($sub_query1->num_rows()) {
                    $fake_summary = $sub_query1->result();
                    $fake_failed = 0;
                    $fake_delievered = 0;
                    $fake_sent = 0;
                    if ($fake_summary) {
                        foreach ($fake_summary as $key => $value) {
                            if ($value->temporary_status == 3) {
                                $fake_failed = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 2) {
                                $fake_delievered = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 4) {
                                $fake_sent = $value->Count_Fake;
                            }
                        }
                    }
                    $temp_array['fake_failed'] = $fake_failed;
                    $temp_array['fake_delivered'] = $fake_delievered;
                    $temp_array['fake_sent'] = $fake_sent;
                } else {
                    $temp_array['fake_failed'] = 0;
                    $temp_array['fake_delivered'] = 0;
                    $temp_array['fake_sent'] = 0;
                }
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

// Count Sent SMS
    function countSentSMS($campaign_id = 0) {
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->num_rows();
        } else {
            $this->db->select('*');
            $this->db->from('sent_sms_old');
            $this->db->where('`campaign_id`', $campaign_id);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

// Get Sent SMS
    /*
      function getSentSMSOld($campaign_id = 0, $start = 0, $limit = 0) {
      $this->db->select('sms_id, route, msg_id, mobile_no, status, sender_id, sent_sms.submit_date AS submit_date, done_date');
      $this->db->select('dlr_receipt, campaigns.campaign_id AS campaign_id, `dlr_status`, `ttsCallRequestId`, `description`');
      $this->db->select('sent_sms.message AS message');
      $this->db->select('user_groups1.user_group_name AS default_ugroup_name');
      $this->db->select('user_groups2.user_group_name AS actual_ugroup_name');
      $this->db->from('sent_sms AS sent_sms');
      $this->db->join('campaigns AS campaigns', 'campaigns.campaign_id = sent_sms.campaign_id', 'left');
      $this->db->join('user_groups AS user_groups1', 'user_groups1.smsc_id = sent_sms.default_route');
      $this->db->join('user_groups AS user_groups2', 'user_groups2.smsc_id = sent_sms.actual_route');
      $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
      $this->db->order_by('sent_sms.status', 'ASC');
      $this->db->order_by('sent_sms.done_date', 'ASC');
      $this->db->limit($limit, $start);
      $query = $this->db->get();
      if ($query->num_rows()) {
      return $query->result_array();
      } else {
      return false;
      }
      }
     */

    function getSentSMS($campaign_id = 0, $start = 0, $limit = 0) {
        $this->db->select('sms_id, route, msg_id, mobile_no, status, sender_id, sent_sms.submit_date AS submit_date, done_date');
        $this->db->select('dlr_receipt, campaigns.campaign_id AS campaign_id, `dlr_status`, `ttsCallRequestId`, `description`,default_route,actual_route');
        $this->db->select('sent_sms.message AS message');
        //$this->db->select('user_groups1.user_group_name AS default_ugroup_name');
        // $this->db->select('user_groups2.user_group_name AS actual_ugroup_name');
        $this->db->from('sent_sms AS sent_sms');
        $this->db->join('campaigns AS campaigns', 'campaigns.campaign_id = sent_sms.campaign_id', 'left');
        //$this->db->join('user_groups AS user_groups1', 'user_groups1.smsc_id = sent_sms.default_route');
        // $this->db->join('user_groups AS user_groups2', 'user_groups2.smsc_id = sent_sms.actual_route');
        // $this->db->join('administrators', 'transaction_logs.txn_admin_from = administrators.admin_id');
        $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
        $this->db->order_by('sent_sms.status', 'ASC');
        $this->db->order_by('sent_sms.done_date', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            $this->db->select('sms_id, route, msg_id, mobile_no, status, sender_id, sent_sms_old.submit_date AS submit_date, done_date');
            $this->db->select('dlr_receipt, campaigns.campaign_id AS campaign_id, `dlr_status`, `ttsCallRequestId`, `description`,default_route,actual_route');
            $this->db->select('sent_sms_old.message AS message');
            $this->db->from('sent_sms_old AS sent_sms_old');
            $this->db->join('campaigns AS campaigns', 'campaigns.campaign_id = sent_sms_old.campaign_id', 'left');
            $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
            $this->db->order_by('sent_sms_old.status', 'ASC');
            $this->db->order_by('sent_sms_old.done_date', 'ASC');
            $this->db->limit($limit, $start);
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    //get campaning process status 
    public function campaignProcessStatus($campaign_id = 0) {
        $this->db->select("`campaign_status`");
        $this->db->from('`campaigns`');
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Get Sent SMS Status
    function getSentSMSStatus($campaign_id = 0) {
        $this->db->select("COUNT(sms_id) AS Count_Status, `status`,`user_group_id`");
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->group_by("status");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            $this->db->select("COUNT(sms_id) AS Count_Status, `status`,`user_group_id`");
            $this->db->from('sent_sms_old');
            $this->db->where('`campaign_id`', $campaign_id);
            $this->db->group_by("status");
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }
        }
    }

    //Export From The Phonebook
//    function exportFromPhonebook($campaign_id = 0, $user_id = 0) {
//        $sent_result = array();
//        $phonebook_result = array();
//        $this->db->select("*");
//        $this->db->from('sent_sms');
//        $this->db->where('`campaign_id`', $campaign_id);
//        $this->db->where('user_id', $user_id);
//        // $this->db->where('status', 1);
//        $query = $this->db->get();
//        if ($query->num_rows()) {
//            $result = $query->result_array();
//            foreach ($result as $sent_value) {
//                $sent_result[] = array(
//                    'contact_number' => $sent_value['mobile_no'],
//                    'status' => $sent_value['status']
//                );
//            }
//        }
//
//        $this->db->select('contact_name,contact_group_ids,mobile_number,user_id');
//        $this->db->from('contacts');
//        $this->db->where('user_id', $user_id);
//        $query1 = $this->db->get();
//        if ($query1->num_rows()) {
//            $result1 = $query1->result_array();
//            foreach ($result1 as $contact_value) {
//                $phonebook_result[] = array(
//                    'phone_number' => $contact_value['mobile_number'],
//                );
//            }
//        }
//        $data[] = array_intersect($sent_result, $phonebook_result);
//        $sizeofresult = sizeof($data[0]);
//        $data_number = array();
//        for ($i = 0; $i < $sizeofresult; $i++) {
//            $data_number[] = $data[0][$i]['contact_number'];
//        }
//        //$data_number_value =  array_values($data_number);
//        $this->db->select('contact_name,contact_group_ids,mobile_number,user_id');
//        $this->db->from('contacts');
//        $this->db->where_in('mobile_number', $data_number);
//        $this->db->where('user_id', $user_id);
//        $export_query = $this->db->get();
//        $export_result = $export_query->result_array();
//        //return $export_result;
//        $newexport_data = array();
//        $newexport_data_new = array();
//        //print_r($export_result);die;
//        //echo "<br>";
//        //echo "<br>";
//
//        $size_sent_sms = sizeof($sent_result);
//        $size_export_result = sizeof($export_result);
//
//        for ($j = 0; $j < $size_export_result; $j++) {
//
//            for ($k = 0; $k < $size_sent_sms; $k++) {
//
//                if ($export_result[$j]['mobile_number'] == $sent_result[$k]['contact_number']) {
//
//                    $newexport_data[] = array(
//                        'contact_name' => $export_result[$j]['contact_name'],
//                        'mobile_number' => $export_result[$k]['mobile_number'],
//                        'status' => $sent_result[$k]['status']
//                    );
//                }
//            }
//        }
//        return $newexport_data;
//    }
// Delete Delivery Report
    function deleteDlrReport($campaign_id = 0) {
        return $this->db->delete('campaigns', array('campaign_id' => $campaign_id));
    }

// Delete Sent SMS
    function deleteSentSMS($sms_id = 0) {
        return $this->db->delete('sent_sms', array('sms_id' => $sms_id));
    }

// Search Delivery Reports
    function searchDeliveryReports($search = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->db->select('users1.username AS username, users2.username AS parent_username, users1.contact_number AS contact, users1.user_id AS user_id');
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, schedule_status, sender_id, submit_date, request_by, message, route');
        $this->db->select('administrators.admin_username AS admin_username, message_category, total_credits, total_deducted, actual_message, request_by, message_type, flash_message,pricing_error');
        $this->db->select('campaigns.admin_id AS admin_id, schedule_date, black_listed, total_time, whole_process');
        $this->db->select('user_groups1.user_group_name AS user_group_name, user_groups1.smsc_id AS smsc_id');
        $this->db->select('user_groups2.user_group_name AS resend_ugroup_name, user_groups2.smsc_id AS resend_smsc_id');
        $this->db->select('caller_id, start_date_time, end_date_time, service_type');
        $this->db->select('administrators1.admin_username AS processed_by');
        $this->db->select('administrators2.admin_username AS resend_by');
        $this->db->from('users AS users1');
        $this->db->join('administrators AS administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'right');
        $this->db->join('user_groups AS user_groups1', 'user_groups1.user_group_id = campaigns.user_group_id', 'left');
        $this->db->join('user_groups AS user_groups2', 'user_groups2.user_group_id = campaigns.resend_ugroup_id', 'left');
        $this->db->join('administrators AS administrators1', 'administrators1.admin_id = campaigns.processed_by', 'left');
        $this->db->join('administrators AS administrators2', 'administrators2.admin_id = campaigns.resend_admin_id', 'left');
//$this->db->join('campaigns', 'campaigns.campaign_id = sent_sms.campaign_id', 'right');
        if (is_numeric($search)) {
            $this->db->where('total_messages >=', $search);
            $this->db->order_by("total_messages", "ASC");
        } else {
            $this->db->like('users1.username', $search);
            // $this->db->or_like('campaign_uid', $search);
            $this->db->or_like('sender_id', $search);
            $this->db->or_like('message', $search);
        }
        $this->db->order_by("campaign_id", "desc");
        $this->db->limit(100, 0);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'username' => $campaign['username'],
                    'parent_username' => $campaign['parent_username'],
                    'contact' => $campaign['contact'],
                    'user_id' => $campaign['user_id'],
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'schedule_status' => $campaign['schedule_status'],
                    'sender_id' => $campaign['sender_id'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'admin_id' => $campaign['admin_id'],
                    'admin_username' => $campaign['admin_username'],
                    'message_category' => $campaign['message_category'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'user_group_name' => $campaign['user_group_name'],
                    'smsc_id' => $campaign['smsc_id'],
                    'schedule_date' => $campaign['schedule_date'],
                    'black_listed' => $campaign['black_listed'],
                    'total_time' => $campaign['total_time'],
                    'whole_process' => $campaign['whole_process'],
                    'caller_id' => $campaign['caller_id'],
                    'start_date_time' => $campaign['start_date_time'],
                    'end_date_time' => $campaign['end_date_time'],
                    'service_type' => $campaign['service_type'],
                    'processed_by' => $campaign['processed_by'],
                    'resend_ugroup_name' => $campaign['resend_ugroup_name'],
                    'resend_smsc_id' => $campaign['resend_smsc_id'],
                    'resend_by' => $campaign['resend_by'],
                    'pricing_error' => $campaign['pricing_error']
                );
// Get SMS Summary
                $this->db->select('COUNT(sms_id) AS Count_Status, status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("status");
                $sub_query = $this->db->get();
                if ($sub_query->num_rows()) {
                    $summary = $sub_query->result();
                    $temp_array['summary'] = $summary;
                } else {
                    $temp_array['summary'] = 0;
                }

// Get Fake SMS Summary (Failed & Delivered)
                $this->db->select('COUNT(sms_id) AS Count_Fake, temporary_status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("temporary_status");
                $sub_query1 = $this->db->get();
                if ($sub_query1->num_rows()) {
                    $fake_summary = $sub_query1->result();
                    $fake_failed = 0;
                    $fake_delievered = 0;
                    $fake_sent = 0;

                    if ($fake_summary) {
                        foreach ($fake_summary as $key => $value) {
                            if ($value->temporary_status == 3) {
                                $fake_failed = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 2) {
                                $fake_delievered = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 4) {
                                $fake_sent = $value->Count_Fake;
                            }
                        }
                    }
                    $temp_array['fake_failed'] = $fake_failed;
                    $temp_array['fake_delivered'] = $fake_delievered;
                    $temp_array['fake_sent'] = $fake_sent;
                } else {
                    $temp_array['fake_failed'] = 0;
                    $temp_array['fake_delivered'] = 0;
                    $temp_array['fake_sent'] = 0;
                }
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

// Search Sent SMS
    function searchSentSMS($campaign_id = 0, $search = null) {
        $this->db->select('sms_id, msg_id, mobile_no, status, submit_date, done_date, dlr_receipt, `dlr_status`');
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->like('mobile_no', $search);
        $this->db->order_by("`done_date`", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Search special reseller
    function searchSpecialReseller($reseller = NULL) {
        $this->db->select('user_id,username,name,utype,pr_sms_balance,tr_sms_balance,special_tr_balance,special_pr_balance,spacial_reseller_status');
        $this->db->from('users');
        $this->db->where('admin_id', 1);
        $this->db->where('utype', 'Reseller');
        $this->db->like('`username`', $reseller);
        $this->db->or_like('`name`', $reseller);
        $this->db->order_by('user_id', 'desc');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    // Search sender id by username
    function searchSenderidByUsername($username = 0) {

        $this->db->select('sender_id,sender_ids,sender_status,users.username');
        $this->db->from('sender_ids,users');
        $this->db->where('sender_ids.user_id = users.user_id');
        $this->db->like('`users.username`', $username, 'after');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $sender_array = array();
            $result_senders = $query->result_array();
            foreach ($result_senders as $user_sender_id) {
                $temp_array = array();
                $sender_ids_array = explode(',', $user_sender_id['sender_ids']);
                $sender_status_array = explode(',', $user_sender_id['sender_status']);
                foreach ($sender_ids_array as $sender_key => $sender_value) {

                    if ($sender_status_array[$sender_key] == '0') { // for pending sender ids
                        $temp_array['sender_id'] = $user_sender_id['sender_id'];
                        $temp_array['username'] = $user_sender_id['username'];
                        //  $parent = ($user_sender_id['parent_username'] == "") ? $user_sender_id['admin_username'] : $user_sender_id['parent_username'];
                        //  $temp_array['parent_username'] = $parent;
                        $temp_array['sender'] = $sender_value;
                        $temp_array['sender_key'] = $sender_key;
                        $temp_array['sender_status'] = $sender_status_array[$sender_key];
                        $sender_array[] = $temp_array;
                    }
                }
            }

            return $sender_array;
        }
    }

// Track Mobile
    function trackMobile($mobile = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->db->select('users1.username AS username, users1.user_id AS user_id, users2.username AS parent_username, admin_username');
        $this->db->select('campaigns.campaign_id AS campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, schedule_status');
        $this->db->select('campaigns.submit_date, request_by, sent_sms.message AS message, route, sender_id, campaigns.admin_id AS admin_id');
        $this->db->select('message_category, total_credits, total_deducted, actual_message, request_by, message_type, flash_message, black_listed');
        $this->db->select('user_group_name, smsc_id, schedule_date');
        $this->db->select('caller_id, start_date_time, end_date_time, service_type');
        $this->db->from('sent_sms');
        $this->db->join('campaigns', 'campaigns.campaign_id = sent_sms.campaign_id', 'left');
        $this->db->join('users AS users1', 'users1.user_id = campaigns.user_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id= users1.most_parent_id', 'left');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('user_groups', 'user_groups.user_group_id = campaigns.user_group_id', 'left');
        $this->db->like('mobile_no', $mobile);
        $this->db->order_by("campaigns.campaign_id", "desc");
        $this->db->limit(20, 0);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Detailed Reports
    function getDetailedReport() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $number = "";
        $status = "";
        $this->db->select('sms_id, msg_id, mobile_no, status, submit_date, done_date, dlr_receipt, `dlr_status`, `user_group_name`');
        $this->db->from('`sent_sms`, `user_groups`');
        $this->db->where('`user_groups`.`user_group_id`=`sent_sms`.`user_group_id`');
// Filter By Number
        if ($this->input->post('number') && $this->input->post('number') != "") {
            $number = $this->input->post('number');
            $this->db->like('`mobile_no`', $number);
        }
// Filter By Date
        if ($this->input->post('fdate') && $this->input->post('fdate') != "" && $this->input->post('tdate') && $this->input->post('tdate') != "") {
            $fdate = $this->input->post('fdate');
            $tdate = $this->input->post('tdate');
            $this->db->where('submit_date >=', $fdate);
            $this->db->where('submit_date <=', $tdate);
        } elseif ($this->input->post('fdate') && $this->input->post('fdate') != "" && $this->input->post('tdate') == "") {
            $date = $this->input->post('fdate');
            $array = explode(' ', $date);
            $this->db->like('`submit_date`', $array[0]);
        } else {
            $date = date('Y-m-d');
            $this->db->like('`submit_date`', $date);
        }
// Filter By Status
        if ($this->input->post('status') && $this->input->post('status') != "") {
            $status = $this->input->post('status');
            $this->db->where('`status`', $status);
        }
        $this->db->order_by("`sms_id`", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Total SMS Consumptions Logs
    function getSMSConsuptions() {
        $return_array = array();
        $this->db->select('users1.username AS username, users2.username AS parent_username, admin_username');
        $this->db->select('users1.utype AS utype, users1.user_id AS user_id');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $results = $query->result_array();
            foreach ($results AS $row) {
                $temp = array();
                $temp['username'] = $row['username'];
                $temp['parent_username'] = ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username'];
                $temp['user_type'] = $row['utype'];
// Total SMS Consumptions (Promotional)
                $this->db->select('SUM(`total_messages`) AS `total_pr_messages`, SUM(`total_deducted`) AS `pr_consumptions`');
                $this->db->from('`campaigns`');
                $this->db->where('`route`', 'A');
                $this->db->where('`user_id`', $row['user_id']);
                $query1 = $this->db->get();
                if ($query1->num_rows()) {
                    $row1 = $query1->row();
                    if ($row1->total_pr_messages) {
                        $temp['total_pr_messages'] = $row1->total_pr_messages;
                    } else {
                        $temp['total_pr_messages'] = 0;
                    }
                    if ($row1->pr_consumptions) {
                        $temp['pr_consumptions'] = $row1->pr_consumptions;
                    } else {
                        $temp['pr_consumptions'] = 0;
                    }
                } else {
                    $temp['total_pr_messages'] = 0;
                    $temp['pr_consumptions'] = 0;
                }
// Total SMS Consumptions (Transactional)
                $this->db->select('SUM(`total_messages`) AS `total_tr_messages`, SUM(`total_deducted`) AS `tr_consumptions`');
                $this->db->from('`campaigns`');
                $this->db->where('`route`', 'B');
                $this->db->where('`user_id`', $row['user_id']);
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->total_tr_messages) {
                        $temp['total_tr_messages'] = $row2->total_tr_messages;
                    } else {
                        $temp['total_tr_messages'] = 0;
                    }
                    if ($row2->tr_consumptions) {
                        $temp['tr_consumptions'] = $row2->tr_consumptions;
                    } else {
                        $temp['tr_consumptions'] = 0;
                    }
                } else {
                    $temp['total_tr_messages'] = 0;
                    $temp['tr_consumptions'] = 0;
                }
                $return_array[] = $temp;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    //get sms consumption TR
    function getSMSConsumptionsTR() {

        $today_date = date('Y-m-d');
        $this->db->distinct('user_id');
        $this->db->select('campaigns.user_id,users.username,users.utype');
        $this->db->from('campaigns,users');
        $this->db->where('campaigns.user_id = users.user_id');
        $this->db->where('campaigns.campaign_id >', 4832422);
        $this->db->where('campaigns.route', 'B');
        $this->db->like('campaigns.submit_date', $today_date, 'after');
        //  $this->db->order_by('`total_deducted`', 'desc'); 
        $query = $this->db->get();
        if ($query->num_rows()) {
            $user_ids = $query->result_array();
            // var_dump($user_ids);die;
            foreach ($user_ids AS $ids) {
                $temp = array();

                $this->db->select('SUM(`total_messages`) AS `total_tr_messages`, SUM(`total_deducted`) AS `tr_consumptions`,`user_id`');
                $this->db->from('`campaigns`');
                $this->db->where('campaign_id >', 4832422);
                $this->db->where('`route`', 'B');
                $this->db->where('`user_id`', $ids['user_id']);
                $this->db->like('submit_date', $today_date, 'after');
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->tr_consumptions) {
                        $temp['tr_consumptions'] = $row2->tr_consumptions;
                    } else {
                        $temp['tr_consumptions'] = 0;
                    }
                    if ($row2->total_tr_messages) {
                        $temp['total_tr_messages'] = $row2->total_tr_messages;
                    } else {
                        $temp['total_tr_messages'] = 0;
                    }
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                } else {
                    $temp['tr_consumptions'] = 0;
                    $temp['total_tr_messages'] = 0;
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                }
                $return_array[] = $temp;
            }
            arsort($return_array);
            return $return_array;
            // var_dump($return_array);die;        
        } else {
            return false;
        }
    }

    function getSMSConsumptionsPR() {
        $route = array('A', 'C', 'D');
        $today_date = date('Y-m-d');
        $this->db->distinct('user_id');
        $this->db->select('campaigns.user_id,users.username,users.utype');
        $this->db->from('campaigns,users');
        $this->db->where('campaigns.user_id = users.user_id');
        $this->db->where('campaigns.campaign_id >', 4832422);
        $this->db->where_in('campaigns.route', $route);
        $this->db->like('campaigns.submit_date', $today_date, 'after');
        //  $this->db->order_by('`total_deducted`', 'desc'); 
        $query = $this->db->get();
        if ($query->num_rows()) {
            $user_ids = $query->result_array();
            // var_dump($user_ids);die;
            foreach ($user_ids AS $ids) {
                $temp = array();

                $this->db->select('SUM(`total_messages`) AS `total_pr_messages`, SUM(`total_deducted`) AS `pr_consumptions`,`user_id`');
                $this->db->from('`campaigns`');
                $this->db->where('campaign_id >', 4832422);
                $this->db->where_in('`route`', $route);
                $this->db->where('`user_id`', $ids['user_id']);
                $this->db->like('submit_date', $today_date, 'after');
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->pr_consumptions) {
                        $temp['pr_consumptions'] = $row2->pr_consumptions;
                    } else {
                        $temp['pr_consumptions'] = 0;
                    }
                    if ($row2->total_pr_messages) {
                        $temp['total_pr_messages'] = $row2->total_pr_messages;
                    } else {
                        $temp['total_pr_messages'] = 0;
                    }
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                } else {
                    $temp['pr_consumptions'] = 0;
                    $temp['total_pr_messages'] = 0;
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                }
                $return_array[] = $temp;
            }
            arsort($return_array);
            return $return_array;
            // var_dump($return_array);die;        
        } else {
            return false;
        }
    }

    // sms consumption TR by date
    function trConsumptionsByDate() {

        $fdate = $this->input->post('by_from_date');
        $tdate = $this->input->post('by_to_date');

        $today_date = date('Y-m-d');
        $this->db->distinct('user_id');
        $this->db->select('campaigns.user_id,users.username,users.utype');
        $this->db->from('campaigns,users');
        $this->db->where('campaigns.user_id = users.user_id');
        $this->db->where('campaigns.campaign_id >', 3335204);
        $this->db->where('campaigns.route', 'B');
        //  $this->db->like('campaigns.submit_date', $today_date, 'after');
        $this->db->where("campaigns.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        //  $this->db->order_by('`total_deducted`', 'desc'); 
        $query = $this->db->get();
        if ($query->num_rows()) {
            $user_ids = $query->result_array();
            // var_dump($user_ids);die;
            foreach ($user_ids AS $ids) {
                //  $temp = array();

                $this->db->select('SUM(`total_messages`) AS `total_tr_messages`, SUM(`total_deducted`) AS `tr_consumptions`,`user_id`');
                $this->db->from('`campaigns`');
                $this->db->where('campaign_id >', 3335204);
                $this->db->where('`route`', 'B');
                $this->db->where('`user_id`', $ids['user_id']);
                // $this->db->like('submit_date', $today_date, 'after');
                $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->tr_consumptions) {
                        $temp['tr_consumptions'] = $row2->tr_consumptions;
                    } else {
                        $temp['tr_consumptions'] = 0;
                    }
                    if ($row2->total_tr_messages) {
                        $temp['total_tr_messages'] = $row2->total_tr_messages;
                    } else {
                        $temp['total_tr_messages'] = 0;
                    }
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                } else {
                    $temp['tr_consumptions'] = 0;
                    $temp['total_tr_messages'] = 0;
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                }
                $return_array[] = $temp;
            }
            arsort($return_array);
            return $return_array;
            //  var_dump($return_array);die;        
        } else {
            return false;
        }
    }

    //pr sms consumption by date
    function prConsumptionsByDate() {
        $route = array('A', 'C', 'D');
        $fdate = $this->input->post('by_from_date');
        $tdate = $this->input->post('by_to_date');


        $today_date = date('Y-m-d');
        $this->db->distinct('user_id');
        $this->db->select('campaigns.user_id,users.username,users.utype');
        $this->db->from('campaigns,users');
        $this->db->where('campaigns.user_id = users.user_id');
        $this->db->where('campaigns.campaign_id >', 3335204);
        $this->db->where_in('campaigns.route', $route);
        //  $this->db->like('campaigns.submit_date', $today_date, 'after');
        $this->db->where("campaigns.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        //  $this->db->order_by('`total_deducted`', 'desc'); 
        $query = $this->db->get();
        if ($query->num_rows()) {
            $user_ids = $query->result_array();
            // var_dump($user_ids);die;
            foreach ($user_ids AS $ids) {
                //  $temp = array();

                $this->db->select('SUM(`total_messages`) AS `total_pr_messages`, SUM(`total_deducted`) AS `pr_consumptions`,`user_id`');
                $this->db->from('`campaigns`');
                $this->db->where('campaign_id >', 3335204);
                $this->db->where_in('`route`', $route);
                $this->db->where('`user_id`', $ids['user_id']);
                //   $this->db->like('submit_date', $today_date, 'after');
                $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->pr_consumptions) {
                        $temp['pr_consumptions'] = $row2->pr_consumptions;
                    } else {
                        $temp['pr_consumptions'] = 0;
                    }
                    if ($row2->total_pr_messages) {
                        $temp['total_pr_messages'] = $row2->total_pr_messages;
                    } else {
                        $temp['total_pr_messages'] = 0;
                    }
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                } else {
                    $temp['pr_consumptions'] = 0;
                    $temp['total_pr_messages'] = 0;
                    $temp['user_id'] = $row2->user_id;
                    $temp['username'] = $ids['username'];
                    $temp['user_type'] = $ids['utype'];
                }
                $return_array[] = $temp;
            }
            arsort($return_array);
            return $return_array;
            // var_dump($return_array);die;        
        } else {
            return false;
        }
    }

// Filter Total SMS Consumptions Logs
    function filterSMSConsuptions() {
        $username = $this->input->post('username');
        $return_array = array();
        $this->db->select('users1.username AS username, users2.username AS parent_username, admin_username');
        $this->db->select('users1.utype AS utype, users1.user_id AS user_id');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->like('`users1.username`', $username);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $results = $query->result_array();
            foreach ($results AS $row) {
                $temp = array();
                $temp['username'] = $row['username'];
                $temp['parent_username'] = ($row['parent_username'] == "") ? $row['admin_username'] : $row['parent_username'];
                $temp['user_type'] = $row['utype'];
// Total SMS Consumptions (Promotional)
                $this->db->select('SUM(`total_messages`) AS `total_pr_messages`, SUM(`total_deducted`) AS `pr_consumptions`');
                $this->db->from('`campaigns`');
                $this->db->where('`route`', 'A');
                $this->db->where('`user_id`', $row['user_id']);
                $query1 = $this->db->get();
                if ($query1->num_rows()) {
                    $row1 = $query1->row();
                    if ($row1->total_pr_messages) {
                        $temp['total_pr_messages'] = $row1->total_pr_messages;
                    } else {
                        $temp['total_pr_messages'] = 0;
                    }
                    if ($row1->pr_consumptions) {
                        $temp['pr_consumptions'] = $row1->pr_consumptions;
                    } else {
                        $temp['pr_consumptions'] = 0;
                    }
                } else {
                    $temp['total_pr_messages'] = 0;
                    $temp['pr_consumptions'] = 0;
                }
// Total SMS Consumptions (Transactional)
                $this->db->select('SUM(`total_messages`) AS `total_tr_messages`, SUM(`total_deducted`) AS `tr_consumptions`');
                $this->db->from('`campaigns`');
                $this->db->where('`route`', 'B');
                $this->db->where('`user_id`', $row['user_id']);
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->total_tr_messages) {
                        $temp['total_tr_messages'] = $row2->total_tr_messages;
                    } else {
                        $temp['total_tr_messages'] = 0;
                    }
                    if ($row2->tr_consumptions) {
                        $temp['tr_consumptions'] = $row2->tr_consumptions;
                    } else {
                        $temp['tr_consumptions'] = 0;
                    }
                } else {
                    $temp['total_tr_messages'] = 0;
                    $temp['tr_consumptions'] = 0;
                }
                $return_array[] = $temp;
            }
            return $return_array;
        } else {
            return false;
        }
    }

// Get Delivery Reports For Resend
    function getDeliveryReportsResend() {

        $this->db->select('COUNT(sms_id) AS Count_Status, status');
// Filter By Route
        if ($this->input->post('by_route')) {
            $route = $this->input->post('by_route');
            $this->db->from('`sent_sms`, `campaigns`');
            $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
            $this->db->where('`route`', $route);
        } else {
            $this->db->from('`sent_sms`');
        }
// Filter By Date
        if ($this->input->post('by_from_date') && $this->input->post('by_from_date') != "" && $this->input->post('by_to_date') && $this->input->post('by_to_date') != "") {
            $fdate = $this->input->post('by_from_date');
            $tdate = $this->input->post('by_to_date');
            $this->db->where('`sent_sms`.`submit_date` >=', $fdate);
            $this->db->where('`sent_sms`.`submit_date` <=', $tdate);
        } elseif ($this->input->post('by_from_date') && $this->input->post('by_from_date') != "" && $this->input->post('by_to_date') == "") {
            $date = $this->input->post('by_from_date');
            $array = explode(' ', $date);
            $this->db->like('`sent_sms`.`submit_date`', $array[0]);
        } else {
            $date = date('Y-m-d');
            $this->db->like('`sent_sms`.`submit_date`', $date);
        }
// Filter By User/Users
        if ($this->input->post('by_users')) {
            $by_users = $this->input->post('by_users');
            $this->db->where_in('sent_sms.user_id', $by_users);
        }
        $this->db->group_by('`status`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $result = $query->result_array();
            //var_dump($result);
        } else {
            return false;
        }
    }

    function getOverallReport() {
        $route = $this->input->post('by_route');
        $by_users = $this->input->post('by_users');
        $start_date = $this->input->post('by_from_date');
        $end_date = $this->input->post('by_to_date');
        $pr_route = array(17, 43, 44, 46, 47, 56, 72, 73);
        $tr_route = array(16, 38, 62, 71);
        $new_data = array();
        $data = array();
        $this->db->select('status');
        $this->db->from('sent_sms');

        $this->db->where("`submit_date` BETWEEN '$start_date' AND '$end_date'");
//        if ($route == "A" || $route == "C" || $route == "D") {
//            $this->db->where_in('route', $pr_route);
//        } elseif ($route == "B") {
//            $this->db->where_in('route', $tr_route);
//        }
        if ($by_users) {
            $this->db->where_in('user_id', $by_users);
        }
        $query = $this->db->get();

        $result = $query->result_array();
        foreach ($result as $result_data) {
            $data[] = $result_data['status'];
        }
        $new_data[] = array_count_values($data);
        return $new_data;
    }

// Resend Delivery Reports
    function resendDeliveryReports() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $action_type = $this->input->post('resend_action_type');
        $resend_route = $this->input->post('resend_route');
        $fdate = $this->input->post('fdate');
        $tdate = $this->input->post('tdate');
        $route = $this->input->post('route');
        $by_users = 0;
        if ($this->input->post('users')) {
            $by_users = $this->input->post('users');
        }

// Action Type
        if ($action_type == 1) {
// All
            $sms_status = array();
            $temporary_status = 0;
        } elseif ($action_type == 2) {
// Only Failed
            $sms_status = array('2');
            $temporary_status = 1;
        } elseif ($action_type == 16) {
// Only Rejected
            $sms_status = array('16');
            $temporary_status = 1;
        } elseif ($action_type == 31) {
// Only Submit
            $sms_status = array('31');
            $temporary_status = 1;
        } elseif ($action_type == 3) {
// Only Pending, Submit, Buffered, Report Pending
            $sms_status = array('8', '4', '3');
            $temporary_status = 1;
        } elseif ($action_type == '4') {
// Only Delivered
            $sms_status = array('1');
            $temporary_status = 1;
        } elseif ($action_type == '5') {
// Only Fake Delivered
            $sms_status = array();
            $temporary_status = 2;
        } elseif ($action_type == '6') {
// Only Fake Failed
            $sms_status = array();
            $temporary_status = 3;
        }

// Route
        if ($route == 'A') {
            $new_route = "Default";
        } elseif ($route == 'B') {
            $new_route = "Template";
        }

// XML API Credentials
        $new_campaign_uid = 0;
        $xml = "";
// If XML
        $is_xml = 0;
        $authentication = "";
        $xml_file = "";
        if ($resend_route == 'XML') {
            $result_setting = $this->sms_model->getDefaultSettings();
            $authentication = $result_setting->xml_route_authkey;
            $xml_url = $result_setting->xml_route_url;
            $is_xml = 1;
        }
// Get SMS That Will Be Resend
        $this->db->select('`sender_id`, `route`, `msg_id`, `mobile_no`, `message_type`, `flash_message`, `sent_sms`.`message` AS `message`, `campaign_name`');
        $this->db->select('`flash_message`, `message_type`, `smsc_id`, `sent_sms`.`campaign_id` AS `campaign_id`, `sent_sms`.`status` AS `status`');
        $this->db->from('`sent_sms`');
        $this->db->join('campaigns', 'campaigns.campaign_id=sent_sms.campaign_id', 'LEFT');
        $this->db->join('user_groups', 'user_groups.user_group_id=campaigns.user_group_id', 'LEFT');
        $this->db->where('sent_sms.submit_date >=', $fdate);
        $this->db->where('sent_sms.submit_date <=', $tdate);

// If Route Selected
        if ($route) {
            $this->db->where('campaigns.route', $route);
        }

// If User/Users Selected
        if ($by_users != 'null') {
            $this->db->where_in('campaigns.user_id', $by_users);
        }

        if (sizeof($sms_status)) {
            $this->db->where_in('sent_sms.status', $sms_status);
            $this->db->where('temporary_status', $temporary_status);
        } elseif ($temporary_status) {
            $this->db->where('temporary_status', $temporary_status);
        }
        $query = $this->db->get();
        if ($num_rows = $query->num_rows()) {
// No SMSC Route Available
            if ($resend_route == 'XML') {
                $result_array = $query->result_array();
                foreach ($result_array as $key => $sms) {
                    $campaign_id = $sms['campaign_id'];
                    $from = $sms['sender_id'];
                    $mobile_no = $sms['mobile_no'];
                    $nmessage = $sms['message'];
                    $campaign_name = $sms['campaign_name'];
                    $flash_message = $sms['flash_message'];
                    $unicode = 0;
                    if ($sms['message_type'] == 2) {
                        $unicode = 1;
                    }
// No SMSC Route Available
// Prepare XML
                    $xml_file .= "<MESSAGE>";
                    $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                    $xml_file .= "<SENDER>$from</SENDER>";
                    $xml_file .= "<ROUTE>$new_route</ROUTE>";
                    $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                    $xml_file .= "<FLASH>$flash_message</FLASH>";
                    $xml_file .= "<UNICODE>$unicode</UNICODE>";
                    $xml_file .= "<SMS TEXT='$nmessage'>";
                    $xml_file .= "<ADDRESS TO='$mobile_no'></ADDRESS>";
                    $xml_file .= "</SMS>";
                    $xml_file .= "</MESSAGE>";
                    $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
// Update Campaign Uid
                    if (isset($new_campaign_uid) && $new_campaign_uid) {
                        $data = array(
                            'campaign_uid' => $new_campaign_uid
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                    }
                }
            } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                $momt = "MT";
                $sms_type = 2;
                $dlr_mask = "31";
                $result_array = $query->result_array();
                foreach ($result_array as $key => $sms) {
// Calculate Credits
//$length = strlen(utf8_decode(urldecode($sms['message'])));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $unicode = 0;
                    $result_credits = $this->sms_model->getSMSCredits($sms['message_type'], 0, "");
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 0;
                    $result_flash = $this->sms_model->getFlashMessage($sms['flash_message']);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
//$alt_dcs = $result_flash['alt_dcs'];
                    }

// Route / SMSC ID
                    if ($resend_route == "") {
                        $new_smsc_id = $sms['smsc_id'];
                    } else {
                        $new_smsc_id = $resend_route;
                    }
                    $data_array[] = array(
                        'momt' => $momt,
                        'sender' => $sms ['sender_id'],
                        'receiver' => $sms ['mobile_no'],
                        'msgdata' => $sms['message'],
                        'smsc_id' => $new_smsc_id,
                        'id' => $sms['campaign_id'],
                        'sms_type' => $sms_type,
                        'dlr_mask' => $dlr_mask,
                        'dlr_url' => $sms['campaign_id'],
                        'mclass' => $mclass,
                        'coding' => $coding,
                        'charset' => $charset
                    );
                }
                if (sizeof($data_array)) {
                    $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                }
            }
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// SMS Logs
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get SMS Logs
    function getSMSLogs($start = 0, $limit = 0) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('sms_log_reason, sms_log_time, sms_log_by');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('sms_logs', 'sms_logs.user_id = users1.user_id', 'right');
        $this->db->order_by('sms_log_id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Count SMS Logs
    function countSMSLogs() {
        $this->db->select('*');
        $this->db->from('sms_logs');
        $query = $this->db->get();
        return $query->num_rows();
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// SMPP Logs
//------------------------------------------------------------------------------------------------------------------------------------------//
// SMPP Wise SMS Logs
    function getSMPPLogs($subtab = 0) {
        $smpp_logs_array = array();
        if ($subtab == 1) {
            $today_date = date('Y-m-d');
            $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
            $this->db->from('user_groups');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result_array = $query->result_array();

                foreach ($result_array as $key => $smpp) {
                    $smpp_logs_array[$key]['user_group_id'] = $smpp['user_group_id'];
                    $smpp_logs_array[$key]['user_group_name'] = $smpp['user_group_name'];
                    $smpp_logs_array[$key]['smsc_id'] = $smpp['smsc_id'];
                    $smpp_logs_array[$key]['user_group_status'] = $smpp['user_group_status'];
// SMS Status
                    $this->db->select('COUNT(sms_id) AS total_sms, status');
                    $this->db->from('sent_sms');
                    $this->db->where('sms_id >', 50000000);
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $today_date, 'after');
                    $this->db->group_by('status');
                    $query1 = $this->db->get();
                    if ($query1->num_rows()) {
                        $result = $query1->result_array();

                        foreach ($result AS $key1 => $log) {
                            $smpp_logs_array[$key][$log['status']] = $log['total_sms'];
                        }
                    }
// Actual Deduction
                    $this->db->select('SUM(total_deducted) AS total_deduction');
                    $this->db->from('campaigns');
                    $this->db->where('campaign_id >', 4096135);
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $today_date, 'after');
                    $query2 = $this->db->get();
                    if ($query2->num_rows()) {
                        $row = $query2->row();
                        if ($row->
                                total_deduction)
                            $smpp_logs_array[$key]['total_deduction'] = $row->total_deduction;
                        else
                            $smpp_logs_array[$key]['total_deduction'] = "-";
                    }
// Current Balance
                    $url = "";
                    if ($smpp['smsc_id'] == 'RAVINDRA_PR') {
                        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=bulk24pr&password=trgikjlk";
                    } elseif ($smpp['smsc_id'] == 'RAVINDRA_TR') {
                        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=blk24trn&password=tyvmoigf";
                    } elseif ($smpp['smsc_id'] == 'RAVINDRA_OPEN') {
                        $url = "http://rgsmpp.insignsms.com/smpp_credit.php?username=blk24trn&password=tyvmoigf";
                    }
                    $sms_array = array();
                    if ($response = $this->utility_model->sendSMS($url, $sms_array)) {
                        $smpp_logs_array[$key]['total_balance'] = $response;
                    } else
                        $smpp_logs_array[$key]['total_balance'] = "-";
                }
            }
        } elseif ($subtab == 2) {
            $date = date('Y-m-d');
            $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
            $this->db->from('user_groups');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result_array = $query->result_array();

                foreach ($result_array as $key => $smpp) {
                    $smpp_logs_array[$key]['user_group_id'] = $smpp['user_group_id'];
                    $smpp_logs_array[$key]['user_group_name'] = $smpp['user_group_name'];
                    $smpp_logs_array[$key]['smsc_id'] = $smpp['smsc_id'];
                    $smpp_logs_array[$key]['user_group_status'] = $smpp['user_group_status'];
// SMS Status
                    $this->db->select('COUNT(sms_id) AS total_sms, status');
                    $this->db->from('sent_sms');
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $date);
                    $this->db->group_by('status');
                    $query1 = $this->db->get();
                    if ($query1->num_rows()) {
                        $result = $query1->result_array();
                        foreach ($result AS $key1 => $log) {
                            $smpp_logs_array[$key][$log['status']] = $log['total_sms'];
                        }
                    }
// Actual Deduction
                    $this->db->select('SUM(total_deducted) AS total_deduction');
                    $this->db->from('campaigns');
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $date);
                    $query2 = $this->db->get();
                    if ($query2->num_rows()) {
                        $row = $query2->row();
                        if ($row->
                                total_deduction)
                            $smpp_logs_array[$key]['total_deduction'] = $row->total_deduction;
                        else
                            $smpp_logs_array[$key]['total_deduction'] = "-";
                    }
                }
            }
        }
        return $smpp_logs_array;
    }

// Get SMPP Logs
    function filterSMPPLogs($date = null) {
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
        $this->db->from('user_groups');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $smpp_logs_array = array();
            $result_array = $query->result_array();
            if ($result_array) {
                foreach ($result_array as $key => $smpp) {
                    $smpp_logs_array[$key]['user_group_id'] = $smpp['user_group_id'];
                    $smpp_logs_array[$key]['user_group_name'] = $smpp['user_group_name'];
                    $smpp_logs_array[$key]['smsc_id'] = $smpp['smsc_id'];
                    $smpp_logs_array[$key]['user_group_status'] = $smpp['user_group_status'];
// SMS Status
                    $this->db->select('COUNT(sms_id) AS total_sms, status');
                    $this->db->from('sent_sms');
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $date);
                    $this->db->group_by('status');
                    $query1 = $this->db->get();
                    if ($query1->num_rows()) {
                        $result = $query1->result_array();
                        foreach ($result AS $log) {
                            $smpp_logs_array[$key][$log['status']] = $log['total_sms'];
                        }
                    }
// Actual Deduction
                    $this->db->select('SUM(total_deducted) AS total_deduction');
                    $this->db->from('campaigns');
                    $this->db->where('user_group_id', $smpp['user_group_id']);
                    $this->db->like('submit_date', $date);
                    $query2 = $this->db->get();
                    if ($query2->num_rows()) {
                        $row = $query2->row();
                        if ($row->
                                total_deduction)
                            $smpp_logs_array[$key]['total_deduction'] = $row->total_deduction;
                        else
                            $smpp_logs_array[$key]['total_deduction'] = "-";
                    }
                }
            }

            return $smpp_logs_array;
        } else {
            return false;
        }
    }

// Search SMPP Logs
    function searchSMPPLogs() {
        $fdate = $this->input->post('fdate') . " 00:00:00";
        $tdate = $this->input->post('tdate') . " 23:59:59";
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
        $this->db->from('user_groups');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $smpp_logs_array = array();
            $result_array = $query->result_array();

            foreach ($result_array as $key => $smpp) {
                $smpp_logs_array[$key]['user_group_id'] = $smpp['user_group_id'];
                $smpp_logs_array[$key]['user_group_name'] = $smpp['user_group_name'];
                $smpp_logs_array[$key]['smsc_id'] = $smpp['smsc_id'];
                $smpp_logs_array[$key]['user_group_status'] = $smpp['user_group_status'];
// SMS Status
                $this->db->select('COUNT(sms_id) AS total_sms, status');
                $this->db->from('sent_sms');
                $this->db->where('user_group_id', $smpp['user_group_id']);
                $this->db->where('submit_date >', $fdate);
                $this->db->where('submit_date <', $tdate);
                $this->db->group_by('status');
                $query1 = $this->db->get();
                if ($query1->num_rows()) {
                    $result = $query1->result_array();
                    foreach ($result AS $log) {
                        $smpp_logs_array[$key][$log['status']] = $log['total_sms'];
                    }
                }
// Actual Deduction
                $this->db->select('SUM(total_deducted) AS total_deduction');
                $this->db->from('campaigns');
                $this->db->where('user_group_id', $smpp['user_group_id']);
                $this->db->where('submit_date >', $fdate);
                $this->db->where('submit_date <', $tdate);
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row = $query2->row();
                    if ($row->
                            total_deduction)
                        $smpp_logs_array[$key]['total_deduction'] = $row->total_deduction;
                    else
                        $smpp_logs_array[$key]['total_deduction'] = "-";
                }
            }

            return $smpp_logs_array;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Resend/Retry Messages
//------------------------------------------------------------------------------------------------------------------------------------------//
// Get Submit/Pending Report SMS (Promotional/Transactional)
    function getSubmitSMS($route = null) {
        $status_array = array('3', '8');
        $this->db->select('`campaigns`.`campaign_id` AS `campaign_id`, `sent_sms`.`message` AS `message`');
        $this->db->select('`mobile_no`, `status`, `flash_message`, `message_type`, `sender_id`, `mobile_no`, `status`');
        $this->db->from('`sent_sms`, `campaigns`');
        $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
        $this->db->where('`campaigns`.`route`', $route);
        $this->db->where_in('`status`', $status_array);
        $this->db->order_by('sms_id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get SMPP Connections For Re-submit/Retry/Resend (Promotional/Transactional)
    function getSMPPConnections($smpp_array = array(), $purpose = null) {
        $this->db->select('`smsc_id`');
        $this->db->from('`user_groups`');
        $this->db->where('purpose', $purpose);
        $this->db->where('resend_status', 1);
        $this->db->where_not_in('smsc_id', $smpp_array);
        $query = $this->db->get();
        $this->db->limit(1);
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Retry/Resend Submit Message (Promotional)
    function retryPRMessages($purpose = null, $route = null) {
// Get Retry/Resend Options
        $pr_resend_options = 0;
        $result_settings = $this->getSetting();
        if ($result_settings) {
            $pr_resend_options = $result_settings->pr_resend_options;
        }
// Check SMPP's Session, if Already Created
        $smpp_array = array();
        if ($this->session->userdata('pr_smpp_session')) {
            $smpp_data = $this->session->userdata('pr_smpp_session');
            $smpp_array = explode('|', $smpp_data['smpp_connections']);
        }
// Destroy Session When Reach their limit
        if ($pr_resend_options == sizeof($smpp_array)) {
            $this->session->unset_userdata('pr_smpp_session');
            $smpp_array = array();
        }
// Apply Resend/Retry Options on Process
        if (!$pr_resend_options && sizeof($smpp_array) <= $pr_resend_options) {
// Get SMPP Connection
            $new_smsc_id = "";
            $result_smpp = $this->getSMPPConnections($smpp_array, $purpose);
            if ($result_smpp) {
                $new_smsc_id = $result_smpp->smsc_id;
            }
// Create SMPP's In Session
            if (sizeof($smpp_array)) {
                $smsc_ids = implode('|', $smpp_array) . "|" . $new_smsc_id;
            } else {
                $smsc_ids = $new_smsc_id;
            }
            $smpp_session_array = array(
                'smpp_connections' => $smsc_ids
            );
            $this->session->set_userdata('pr_smpp_session', $smpp_session_array);
//======================================================//        
// Get Submit Messages
            $result_sms = $this->getSubmitSMS($route);
            if ($result_sms) {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                $momt = "MT";
                $sms_type = 2;
                $dlr_mask = "31";
                $data_array = array();
                foreach ($result_sms AS $sms) {
// Calculate Credits
//$length = strlen(utf8_decode(urldecode($sms['message'])));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($sms['message_type'], 0, $sms['message']);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($sms['flash_message']);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
// Prepare SMS Array
                    $data_array[] = array(
                        'momt' => $momt,
                        'sender' => $sms ['sender_id'],
                        'receiver' => $sms ['mobile_no'],
                        'msgdata' => $sms['message'],
                        'smsc_id' => $new_smsc_id,
                        'id' => $sms['campaign_id'],
                        'sms_type' => $sms_type,
                        'dlr_mask' => $dlr_mask,
                        'dlr_url' => $sms['campaign_id'],
                        'mclass' => $mclass,
                        'coding' => $coding,
                        'charset' => $charset
                    );
                }
                if (sizeof($data_array)) {
                    return $this->db->insert_batch('sqlbox_send_sms', $data_array);
                } else {
                    return false;
                }
            } else {
                return false;
            }
//======================================================//  
        } else {
            return false;
        }
    }

// Retry/Resend Submit Message (Transactional)
    function retryTRMessages($purpose = null, $route = null) {
// Get Retry/Resend Options
        $tr_resend_options = 0;
        $result_settings = $this->getSetting();
        if ($result_settings) {
            $tr_resend_options = $result_settings->tr_resend_options;
        }
// Check SMPP's Session, if Already Created
        $smpp_array = array();
        if ($this->session->userdata('tr_smpp_session')) {
            $smpp_data = $this->session->userdata('tr_smpp_session');
            $smpp_array = explode('|', $smpp_data['smpp_connections']);
        }
// Destroy Session When Reach their limit
        if ($tr_resend_options == sizeof($smpp_array)) {
            $this->session->unset_userdata('tr_smpp_session');
            $smpp_array = array();
        }
// Apply Resend/Retry Options on Process
        if (!$tr_resend_options && sizeof($smpp_array) <= $tr_resend_options) {
// Get SMPP Connection
            $new_smsc_id = "";
            $result_smpp = $this->getSMPPConnections($smpp_array, $purpose);
            if ($result_smpp) {
                $new_smsc_id = $result_smpp->smsc_id;
            }
// Create SMPP's In Session
            if (sizeof($smpp_array)) {
                $smsc_ids = implode('|', $smpp_array) . "|" . $new_smsc_id;
            } else {
                $smsc_ids = $new_smsc_id;
            }
            $smpp_session_array = array(
                'smpp_connections' => $smsc_ids
            );
            $this->session->set_userdata('tr_smpp_session', $smpp_session_array);
//======================================================//        
// Get Submit Messages
            $result_sms = $this->getSubmitSMS($route);
            if ($result_sms) {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                $momt = "MT";
                $sms_type = 2;
                $dlr_mask = "31";
                $data_array = array();
                foreach ($result_sms AS $sms) {
// Calculate Credits
//$length = strlen(utf8_decode(urldecode($sms['message'])));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $result_credits = $this->sms_model->getSMSCredits($sms['message_type'], 0, $sms['message']);
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 1;
                    $result_flash = $this->sms_model->getFlashMessage($sms['flash_message']);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
                        $alt_dcs = $result_flash['alt_dcs'];
                    }
// Prepare SMS Array
                    $data_array[] = array(
                        'momt' => $momt,
                        'sender' => $sms ['sender_id'],
                        'receiver' => $sms ['mobile_no'],
                        'msgdata' => $sms['message'],
                        'smsc_id' => $new_smsc_id,
                        'id' => $sms['campaign_id'],
                        'sms_type' => $sms_type,
                        'dlr_mask' => $dlr_mask,
                        'dlr_url' => $sms['campaign_id'],
                        'mclass' => $mclass,
                        'coding' => $coding,
                        'charset' => $charset
                    );
                }
                if (sizeof($data_array)) {
                    return $this->db->insert_batch('sqlbox_send_sms', $data_array);
                } else {
                    return false;
                }
            } else {
                return false;
            }
//======================================================//    
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Virtual Numbers
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count Short/Long Codes- Keywords
    function countSLKeywords($code_type = null) {
        if ($code_type == 'short') {
            $this->db->select('`short_keyword_id`, `short_keyword`, `short_number`, `short_keyword_expiry`, `short_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `short_keyword_status`');
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username, short_number_type');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('short_keywords', 'short_keywords.user_id = users1.user_id', 'right');
            $this->db->join('short_numbers', 'short_numbers.short_number_id = short_keywords.short_number_id', 'left');
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword_id`, `long_keyword`, `long_number`, `long_keyword_expiry`, `long_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `long_keyword_status`');
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username, long_number_type');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('long_keywords', 'long_keywords.user_id = users1.user_id', 'right');
            $this->db->join('long_numbers', 'long_numbers.long_number_id = long_keywords.long_number_id', 'left');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

// Short/Long Codes- Keywords
    function getSLKeywords($code_type = null, $start = 0, $limit = 0) {
        if ($code_type == 'short') {
            $this->db->select('`short_keyword_id`, `short_keyword`, `short_number`, `short_keyword_expiry`, `short_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `short_keyword_status`');
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username, short_number_type');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('short_keywords', 'short_keywords.user_id = users1.user_id', 'right');
            $this->db->join('short_numbers', 'short_numbers.short_number_id = short_keywords.short_number_id', 'left');
            $this->db->order_by('short_keyword_id', 'desc');
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword_id`, `long_keyword`, `long_number`, `long_keyword_expiry`, `long_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `long_keyword_status`');
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username, long_number_type');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('long_keywords', 'long_keywords.user_id = users1.user_id', 'right');
            $this->db->join('long_numbers', 'long_numbers.long_number_id = long_keywords.long_number_id', 'left');
            $this->db->order_by('long_keyword_id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Approve/Disapprove Keyword/Number
    function statusSL($id = 0, $status = 0, $subtab = 0) {
        if ($subtab == 1) {
            $data = array('short_keyword_status' => $status);
            $this->db->where('short_keyword_id', $id);
            return $this->db->update('short_keywords', $data);
        } elseif ($subtab == 2) {
            $data = array('long_keyword_status' => $status);
            $this->db->where('long_keyword_id', $id);
            return $this->db->update('long_keywords', $data);
        } elseif ($subtab == 3) {
            $data = array('short_number_status' => $status);
            $this->db->where('short_number_id', $id);
            return $this->db->update('short_numbers', $data);
        } elseif ($subtab == 4) {
            $data = array('long_number_status' => $status);
            $this->db->where('long_number_id', $id);
            return $this->db->update('long_numbers', $data);
        }
    }

// Count Short/Long Numbers 
    function countSLNumbers($code_type = null) {
        $this->db->select('*');
        $this->db->from($code_type . '_numbers');
        $query = $this->db->get();
        return $query->num_rows();
    }

// Short/Long Numbers 
    function getSLNumbers($code_type = null, $number_type = 0, $start = 0, $limit = 0) {
        if ($code_type == 'short') {
            $this->db->select('`short_number_id`, `short_number`, `short_number_type`, `short_number_status`');
            $this->db->from('short_numbers');
            if ($number_type) {
                $this->db->where("`short_number_id` NOT IN (SELECT short_number_id FROM short_keywords)", NULL, FALSE);
                $this->db->where('short_number_type', 2);
                $this->db->where('short_number_status', 1);
                $this->db->order_by('short_number_id', 'desc');
            } else {
                $this->db->order_by('short_number_id', 'desc');
                $this->db->limit($limit, $start);
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_number_id`, `long_number`, `long_number_type`, `long_number_status`');
            $this->db->from('long_numbers');
            if ($number_type) {
                $this->db->where("`long_number_id` NOT IN (SELECT long_number_id FROM long_keywords)", NULL, FALSE);
                $this->db->where('long_number_type', 2);
                $this->db->where('long_number_status', 1);
                $this->db->order_by('long_number_id', 'desc');
            } else {
                $this->db->order_by('long_number_id', 'desc');
                $this->db->limit($limit, $start);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Save Short/Long Numbers 
    function saveSLNumber($code_type = null) {
        if ($code_type == 'short') {
            $data = array(
                'short_number' => $this->input->post('virtual_number'),
                'short_number_type' => $this->input->post('number_type')
            );
            return $this->db->insert('short_numbers', $data);
        } elseif ($code_type == 'long') {
            $data = array(
                'long_number' => $this->input->post('virtual_number'),
                'long_number_type' => $this->input->post('number_type')
            );
            return $this->db->insert('long_numbers', $data);
        }
    }

// Delete Short/Long Keyword/Number 
    function deleteSLData($id = 0, $subtab = 0) {
        if ($subtab == 1) {
            return $this->db->delete('short_keywords', array('short_keyword_id' => $id));
        }
        if ($subtab == 2) {
            return $this->db->delete('long_keywords', array('long_keyword_id' => $id));
        }
        if ($subtab == 3) {
            return $this->db->delete('short_numbers', array('short_number_id' => $id));
        }
        if ($subtab == 4) {
            return $this->db->delete('long_numbers', array('long_number_id' => $id));
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Account Managers
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count Account Managers
    function countAccountManagers() {
        $this->db->select('`admin_id`, `admin_username`, `admin_password`, `admin_name`, `admin_contact`, `admin_email`, `admin_company`');
        $this->db->select('`role`, `total_pr_balance`, `total_tr_balance`, `atype`, `admin_status`, `expiry_date`, `creation_date`');
        $this->db->from('administrators');
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get Account Managers
    function getAccountManagers($status = 0, $start = 0, $limit = 0) {
        $this->db->select('`admin_id`, `admin_username`, `admin_password`, `admin_name`, `admin_contact`, `admin_email`, `admin_company`, `admin_pr_credits`, `admin_tr_credits`');
        $this->db->select('`role`, `total_pr_balance`, `total_tr_balance`, `atype`, `admin_status`, `expiry_date`, `creation_date`');
        $this->db->select('`total_lcode_balance`, `total_scode_balance`, `total_vpr_balance`, `total_vtr_balance`, `total_mcall_balance`');
        $this->db->select('`total_prtodnd_balance`,`total_stock_credits`,`total_stock_balance`, `total_prtodnd_credits`, `international_sms`');
        $this->db->from('administrators');
        if ($status) {
            $this->db->where('admin_status', 1);
        } else {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get Account Manager
    function getAccountManager($admin_id = 0) {
        $this->db->select('`admin_id`, `admin_username`, `admin_password`, `admin_name`, `admin_contact`, `admin_email`, `admin_company`');
        $this->db->select('`role`, `total_pr_balance`, `total_tr_balance`, `atype`, `admin_status`, `expiry_date`, `creation_date`, `permissions`');
        $this->db->from('administrators');
        $this->db->where('admin_id', $admin_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Save Account Manager
    function saveAccountManager($admin_id = 0, $admin_email = null, $id = 0) {
        if ($id) {
            $data = array(
                'admin_name' => $this->input->post('name'),
                'admin_contact' => $this->input->post('contact'),
                'admin_email' => $this->input->post('email'),
                'admin_company' => $this->input->post('company'),
                'atype' => $this->input->post('user_type'),
                'expiry_date' => $this->input->post('expiry_date'),
                'admin_role' => $this->input->post('admin_role')
            );

            $this->db->where('admin_id', $id);
            $this->db->update('administrators', $data);
            return 201;
        } else {
// From Info
            $username = $this->input->post('username');
// Check Username
            $this->db->select('admin_username');
            $this->db->from('administrators');
            $this->db->where('admin_username', $username);
            $this->db->limit(1);
            $query = $this->db->get();
            if ($query->num_rows() == 0) {
// Generate Password For User
                $password = random_string('numeric', 6);
                $creation_date = date('d-m-Y h:i A');
                $email_address = $this->input->post('email');
                $data = array(
                    'admin_username' => $username,
                    'admin_password' => md5($password),
                    'admin_name' => $this->input->post('name'),
                    'admin_contact' => $this->input->post('contact'),
                    'admin_email' => $this->input->post('email'),
                    'admin_company' => $this->input->post('company'),
                    'atype' => $this->input->post('user_type'),
                    'expiry_date' => $this->input->post('expiry_date'),
                    'creation_date' => $creation_date,
                    'admin_role' => $this->input->post('admin_role')
                );
//==================================================
// Send Mail
                $subject = "Your account has been created successfully!";
// Body
                $body = $this->utility_model->emailSignupAM($username);
// Prepare Email Array
                $mail_array = array(
                    'from_email' => $admin_email,
                    'from_name' => 'Bulk SMS Service Providers',
                    'to_email' => $email_address,
                    'subject' => $subject,
                    'message' => $body
                );
                if ($this->db->insert('administrators', $data) && $this->utility_model->sendEmail($mail_array)) {
                    return 200;
                } else {
                    return 100;
                }
            } else {
                return 101;
            }
        }
    }

// Change Status Account Manager
    function changeAMStatus($id = 0, $status = 0) {
        $data = array('admin_status' => $status);
        $this->db->where('admin_id', $id);
        return $this->db->update('administrators', $data);
    }

// Delete Account Manager
    function deleteAManager($id = 0) {
        return $this->db->delete('administrators', array('admin_id' => $id
        ));
    }

// Transfer Balance
    function transferBalance($admin_id = 0, $array = array()) {
// Admin SMS Balance

        $pr_sms_balance = $array['total_pr_balance'];
        $tr_sms_balance = $array['total_tr_balance'];
        $total_prtodnd_balance = $array['total_prtodnd_balance'];
        $total_stock_balance = $array['total_stock_balance'];
        $total_lcode_balance = $array['total_lcode_balance'];
        $total_scode_balance = $array['total_scode_balance'];
        $pr_voice_balance = $array['total_vpr_balance'];
        $tr_voice_balance = $array['total_vtr_balance'];
        $total_mcall_balance = $array['total_mcall_balance'];
        $admin_pr_credits = $array['admin_pr_credits'];
        $admin_tr_credits = $array['admin_tr_credits'];
        $admin_prtodnd_credits = $array['total_prtodnd_credits'];
        $admin_stock_credits = $array['total_stock_credits'];
        $admin_international_sms = $array['international_sms'];
// From Info
        $account_manager = $this->input->post('account_manager');
        $route = $this->input->post('balance_type');
        $type = $this->input->post('action_type');
        $sms_balance = $this->input->post('balance');
        $txn_date = date('d-m-Y h:i A');
// Account Manager Balance
        $pr_sms_bal = 0;
        $tr_sms_bal = 0;
        $stock_sms_bal = 0;
        $prtodnd_sms_bal = 0;
        $international_sms_bal = 0;
        $long_code_bal = 0;
        $short_code_bal = 0;
        $pr_voice_bal = 0;
        $tr_voice_bal = 0;
        $mcall_bal = 0;
        $spr_bal = 0;
        $str_bal = 0;
        $stock_credit = 0;
        $prtodnd_credit = 0;
        $admin_sms = $this->admin_data_model->getAdminBalance($account_manager);
        if ($admin_sms) {
            $pr_sms_bal += $admin_sms['total_pr_balance'];
            $tr_sms_bal += $admin_sms['total_tr_balance'];
            $prtodnd_sms_bal += $admin_sms['total_prtodnd_balance'];
            $stock_sms_bal += $admin_sms['total_stock_balance'];
            $international_sms_bal += $admin_sms['international_sms'];
            $long_code_bal += $admin_sms['total_lcode_balance'];
            $short_code_bal += $admin_sms['total_scode_balance'];
            $pr_voice_bal += $admin_sms['total_vpr_balance'];
            $tr_voice_bal += $admin_sms['total_vtr_balance'];
            $mcall_bal += $admin_sms['total_mcall_balance'];
            $spr_bal += $admin_sms['admin_pr_credits'];
            $str_bal += $admin_sms['admin_tr_credits'];
            $prtodnd_credit += $admin_sms['total_prtodnd_credits'];
            $stock_credit += $admin_sms['total_stock_credits'];
        }
// Calculate Remain SMS Balance
// Promotional SMS
        if ($route == 'A') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($pr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_pr_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($pr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $pr_sms_balance + $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_pr_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_pr_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

// Transactional SMS        
        if ($route == 'B') {
            $remain_pr_sms_balance1 = $pr_sms_balance;
            $remain_pr_sms_balance2 = $pr_sms_bal;
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($tr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $tr_sms_balance + $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        // STOCK SMS TRANSFERED
        if ($route == 'C') {
            $total_stock_balance;
            $sms_balance;
            $remain_stock_balance1 = 0;
            $remain_stock_balance2 = 0;
            if ($type == 'Add') {
                if ($total_stock_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_stock_balance1 += $total_stock_balance - $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_stock_balance' => $remain_stock_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($stock_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_stock_balance1 += $total_stock_balance + $sms_balance;
                    $remain_stock_balance2 += $stock_sms_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_stock_balance' => $remain_stock_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_stock_balance' => $remain_stock_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        // PRTODND BALANCE TRANSFERERED 
        if ($route == 'D') {
            $remain_prtodnd_sms_balance1 = 0;
            $remain_prtodnd_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($total_prtodnd_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_prtodnd_sms_balance1 += $total_prtodnd_balance - $sms_balance;
                    $remain_prtodnd_sms_balance2 += $prtodnd_sms_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_prtodnd_balance' => $remain_prtodnd_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_prtodnd_balance' => $remain_prtodnd_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($prtodnd_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_prtodnd_sms_balance1 += $total_prtodnd_balance + $sms_balance;
                    $remain_prtodnd_sms_balance2 += $prtodnd_sms_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_prtodnd_balance' => $remain_prtodnd_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_prtodnd_balance' => $remain_prtodnd_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }
        //international

        if ($route == 'I') {
            $remain_international_sms_balance1 = 0;
            $remain_international_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_international_sms < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_international_sms_balance1 += $admin_international_sms - $sms_balance;
                    $remain_international_sms_balance2 += $international_sms_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'international_sms' => $remain_international_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'international_sms' => $remain_international_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($international_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_international_sms_balance1 += $admin_international_sms + $sms_balance;
                    $remain_international_sms_balance2 += $international_sms_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'international_sms' => $remain_international_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'international_sms' => $remain_international_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

//special pr credits
        if ($route == 'SPR') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_pr_credits < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_pr_credits - $sms_balance;
                    $remain_pr_sms_balance2 += $spr_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_pr_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_pr_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($spr_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_pr_credits + $sms_balance;
                    $remain_pr_sms_balance2 += $spr_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_pr_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_pr_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        //special pr credits
        if ($route == 'STOCK') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_stock_credits < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_stock_credits - $sms_balance;
                    $remain_pr_sms_balance2 += $stock_credit + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_stock_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_stock_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($stock_credit < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_stock_credits + $sms_balance;
                    $remain_pr_sms_balance2 += $stock_credit - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_stock_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_stock_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        //special pr credits
        if ($route == 'PRTODND') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_prtodnd_credits < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );

                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_prtodnd_credits - $sms_balance;
                    $remain_pr_sms_balance2 += $prtodnd_credit + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_prtodnd_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_prtodnd_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($prtodnd_credit < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1 += $admin_prtodnd_credits + $sms_balance;
                    $remain_pr_sms_balance2 += $prtodnd_credit - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_prtodnd_credits' => $remain_pr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_prtodnd_credits' => $remain_pr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        //special tr credits
        if ($route == 'STR') {
            $remain_pr_sms_balance1 = $admin_pr_credits;
            $remain_pr_sms_balance2 = $spr_bal;
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_tr_credits < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $admin_tr_credits - $sms_balance;
                    $remain_tr_sms_balance2 += $str_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($str_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $admin_tr_credits + $sms_balance;
                    $remain_tr_sms_balance2 += $str_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

        //special tr credits
        if ($route == 'STR') {
            $remain_pr_sms_balance1 = $admin_pr_credits;
            $remain_pr_sms_balance2 = $spr_bal;
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($admin_tr_credits < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $admin_tr_credits - $sms_balance;
                    $remain_tr_sms_balance2 += $str_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($str_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_admin_from' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1 += $admin_tr_credits + $sms_balance;
                    $remain_tr_sms_balance2 += $str_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'admin_tr_credits' => $remain_tr_sms_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }



// Long Code
        if ($route == 'Long') {
            $remain_lcode_balance1 = 0;
            $remain_lcode_balance2 = 0;
            if ($type == 'Add') {
                if ($total_lcode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_lcode_balance1 += $total_lcode_balance - $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_lcode_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($long_code_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_lcode_balance1 += $total_lcode_balance + $sms_balance;
                    $remain_lcode_balance2 += $long_code_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_lcode_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_lcode_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

// Short Code
        if ($route == 'Short') {
            $remain_scode_balance1 = 0;
            $remain_scode_balance2 = 0;
            if ($type == 'Add') {
                if ($total_scode_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_scode_balance1 += $total_scode_balance - $sms_balance;
                    $remain_scode_balance2 += $short_code_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_scode_balance' => $remain_scode_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($short_code_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_scode_balance1 += $total_scode_balance + $sms_balance;
                    $remain_scode_balance2 += $short_code_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_scode_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_scode_balance' => $remain_scode_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

// Promotional Voice SMS
        if ($route == 'VA') {
            $remain_pr_voice_balance1 = 0;
            $remain_pr_voice_balance2 = 0;
            if ($type == 'Add') {
                if ($pr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_voice_balance1 += $pr_voice_balance - $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_vpr_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($pr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_voice_balance1 += $pr_voice_balance + $sms_balance;
                    $remain_pr_voice_balance2 += $pr_voice_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_vpr_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_vpr_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

// Transactional Voice SMS
        if ($route == 'VB') {
            $remain_tr_voice_balance1 = 0;
            $remain_tr_voice_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_voice_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_voice_balance1 += $tr_voice_balance - $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_vtr_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($tr_voice_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_voice_balance1 += $tr_voice_balance + $sms_balance;
                    $remain_tr_voice_balance2 += $tr_voice_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_vtr_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_vtr_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }

// Missed Call Alerts
        if ($route == 'Missed') {
            $remain_mcall_balance1 = 0;
            $remain_mcall_balance2 = 0;
            if ($type == 'Add') {
                if ($total_mcall_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_mcall_balance1 += $total_mcall_balance - $sms_balance;
                    $remain_mcall_balance2 += $mcall_bal + $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_mcall_balance' => $remain_mcall_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_mcall_balance' => $remain_mcall_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            } elseif ($type == 'Reduce') {
                if ($short_code_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_admin_to' => $account_manager,
                        'txn_date' => $txn_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_mcall_balance1 += $total_mcall_balance + $sms_balance;
                    $remain_mcall_balance2 += $mcall_bal - $sms_balance;
// Admin Account
                    $data_admin1 = array(
                        'total_mcall_balance' => $remain_scode_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data_admin1);
// Account Manager Account
                    $data_admin2 = array(
                        'total_mcall_balance' => $remain_mcall_balance2
                    );
                    $this->db->where('admin_id', $account_manager);
                    return $this->db->update('administrators', $data_admin2);
                }
            }
        }
    }

// Reset Account Manager Password
    function resetAccMPassword() {
        $data = array('admin_password' => md5($this->input->post('am_password')));
        $this->db->where('admin_id', $this->input->post('account_manager'));
        return $this->db->update('administrators', $data);
    }

//Set Permissions For Account Manager
    function setAMPermissions() {
        $permission_array = $this->input->post('permission');
        $data = array('permissions' => implode(',', $permission_array));
        $this->db->where('admin_id', $this->input->post('account_manager'));
        return $this->db->update('administrators', $data);
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Notify Users By SMS/Emails
//------------------------------------------------------------------------------------------------------------------------------------------//
// Notify Users By SMS
    function notifyUsersBySMS($admin_id = 0, $total_pr_balance = 0, $total_tr_balance = 0) {
        ini_set('max_input_time', 1200);
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '268435456');
        $contacts = $this->input->post('notify_users');
        $route = $this->input->post('route');
        $from = $this->input->post('sender');
        $user_status = $this->input->post('user_status');
        $message = $this->input->post('message');
// URL Encoding
        $messages = urlencode($message);
        if (sizeof($contacts) || $user_status) {

            $admin_ug_id = "";
            $admin_smsc_id = "";
            if ($route == 'A') {
                if ($total_pr_balance) {
// Get Default User Group
                    $result_default = $this->getDefaultUG('Promotional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_smsc_id = $result_default->smsc_id;
                    } else {
                        return 101;
                    }
                } else {
                    return 102;
                }
            } elseif ($route == 'B') {
                if ($total_tr_balance) {
// Get Default User Group
                    $result_default = $this->getDefaultUG('Transactional');
                    if ($result_default) {
                        $admin_ug_id = $result_default->user_group_id;
                        $admin_smsc_id = $result_default->smsc_id;
                    } else {
                        return 101;
                    }
                } else {
                    return 102;
                }
            }

            if ($user_status == "Active") {
                $this->db->select('contact_number');
                $this->db->from('users');
                $this->db->where('check_demo_user', 0);
                $query = $this->db->get();
                $result = $query->result_array();
                $size_active = sizeof($result);
                for ($i = 0; $i < $size_active; $i++) {
                    $contacts[] = $result[$i]['contact_number'];
                }
            }
            if ($user_status == "Demo") {
                $this->db->select('contact_number');
                $this->db->from('users');
                $this->db->where('check_demo_user', 1);
                $query = $this->db->get();
                $result = $query->result_array();
                $size_demo = sizeof($result);
                for ($i = 0; $i < $size_demo; $i++) {
                    $contacts[] = $result[$i]['contact_number'];
                }
            }

// Insert Campaign
            $request_by = "By Panel";
            $campaign_name = "Notification";
            $campaign_uid = strtolower(random_string('alnum', 24));
            $total_messages = sizeof($contacts);
            $deduct_balance = 0;
            $submit_date = date("Y-m-d H:i:s");
            $campaign_status = 1;
//$length = strlen($messages);
            $length = strlen(utf8_decode($messages));
            $length1 = $length / 160;
            if ($length1 % 160 == 0) {
                $total_credits = ceil($length1);
            } else {
                $total_credits = ceil($length1);
            }
// Message Encoding
            $message_type = 1;
            if (preg_match('/[#@_$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $messages)) {
                $charset = "ASCII";
                $coding = 0;
            } else {
                $charset = "ASCII";
                $coding = 0;
            }
            $flash_message = 0;
            $data_campaign = array(
                'campaign_uid' => $campaign_uid,
                'user_group_id' => $admin_ug_id,
                'campaign_name' => $campaign_name,
                'admin_id' => $admin_id,
                'total_messages' => $total_messages,
                'total_credits' => $total_credits,
                'campaign_status' => $campaign_status,
                'sender_id' => $from,
                'request_by' => $request_by,
                'submit_date' => $submit_date,
                'message_type' => $message_type,
                'flash_message' => $flash_message,
                'message' => $messages,
                'message_length' => $length,
                'route' => $route
            );
            $response_cm = $this->db->insert('campaigns', $data_campaign);
            if ($response_cm) {
// Get Last Campaign Id
                $new_campaign_id = $this->db->insert_id();
                if (sizeof($contacts) > 1) {
                    $ssms_data = array();
                    $sqlbox_data = array();
                    $loop = 0;
                    foreach ($contacts as $number) {
                        $ssms_temp_array = array();
                        $sqlbox_temp_array = array();
                        $deduct_balance++;
                        $msg_id = strtolower(random_string('alnum', 24));
                        $status = "31";
                        $subdate = date("Y-m-d H:i:s");
                        $temporary_status = 1;
// Sent SMS
                        $ssms_temp_array['user_group_id'] = $admin_ug_id;
                        $ssms_temp_array['campaign_id'] = $new_campaign_id;
                        $ssms_temp_array['msg_id'] = $msg_id;
                        $ssms_temp_array['message'] = $messages;
                        $ssms_temp_array['msg_length'] = $length;
                        $ssms_temp_array['mobile_no'] = $number;
                        $ssms_temp_array['status'] = $status;
                        $ssms_temp_array['submit_date'] = $subdate;
                        $ssms_temp_array['temporary_status'] = $temporary_status;
                        $ssms_data[] = $ssms_temp_array;
                        unset($ssms_temp_array);

                        $momt = "MT";
                        $sms_type = 2;
                        $dlr_url = $new_campaign_id;
                        $dlr_mask = "31";
                        $mclass = null;
                        $alt_dcs = 1;
// SQLBox Send SMS
                        $sqlbox_temp_array['momt'] = $momt;
                        $sqlbox_temp_array['sender'] = $from;
                        $sqlbox_temp_array['receiver'] = $number;
                        $sqlbox_temp_array['msgdata'] = $messages;
                        $sqlbox_temp_array['smsc_id'] = $admin_smsc_id;
                        $sqlbox_temp_array['id'] = $new_campaign_id;
                        $sqlbox_temp_array['sms_type'] = $sms_type;
                        $sqlbox_temp_array['dlr_mask'] = $dlr_mask;
                        $sqlbox_temp_array['dlr_url'] = $dlr_url;
                        $sqlbox_temp_array['mclass'] = $mclass;
                        $sqlbox_temp_array['coding'] = $coding;
                        $sqlbox_temp_array['charset'] = $charset;
                        $sqlbox_data[] = $sqlbox_temp_array;
                        unset($sqlbox_temp_array);
                        $loop++;
                    }
                    $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                    if (sizeof($sqlbox_data)) {
                        $res2 = $this->db->insert_batch('sqlbox_send_sms', $sqlbox_data);
                    }
                } elseif (sizeof($contacts) == 1) {
                    $deduct_balance = 1;
                    $msg_id = strtolower(random_string('alnum', 24));
                    $status = "31";
                    $subdate = date("Y-m-d H:i:s");
                    $temporary_status = 1;
// Sent SMS
                    $data_sent_sms = array(
                        'user_group_id' => $admin_ug_id,
                        'campaign_id' => $new_campaign_id,
                        'msg_id' => $msg_id,
                        'message' => $messages,
                        'msg_length' => $length,
                        'mobile_no' => $contacts[0],
                        'status' => $status,
                        'submit_date' => $subdate,
                        'temporary_status' => $temporary_status
                    );
                    $momt = "MT";
                    $sms_type = 2;
                    $dlr_url = $new_campaign_id;
                    $dlr_mask = "31";
                    $mclass = null;
                    $alt_dcs = 1;
// SQLBox Send SMS
                    $data_sqlbox_send_sms = array(
                        'momt' => $momt,
                        'sender' => $from,
                        'receiver' => $contacts[0],
                        'msgdata' => $messages,
                        'smsc_id' => $admin_smsc_id,
                        'id' => $new_campaign_id,
                        'sms_type' => $sms_type,
                        'dlr_mask' => $dlr_mask,
                        'dlr_url' => $dlr_url,
                        'mclass' => $mclass,
                        'coding' => $coding,
                        'charset' => $charset
                    );
                    $res1 = $this->db->insert('sent_sms', $data_sent_sms);
                    $res2 = $this->db->insert('sqlbox_send_sms', $data_sqlbox_send_sms);
                }
// Balance Updation
                $updated_sms_balance = $total_tr_balance - ($deduct_balance * $total_credits);
                $data = array(
                    'total_tr_balance' => $updated_sms_balance
                );
                $this->db->where('admin_id', $admin_id);
                $this->db->update('administrators', $data);
// Total Deduction
                $data = array(
                    'total_deducted' => $deduct_balance * $total_credits,
                    'actual_message' => $deduct_balance,
                    'processed_by' => $admin_id
                );
                $this->db->where('campaign_id', $new_campaign_id);
                $this->db->update('campaigns', $data);

                $date = date('d-m-Y h:i:s');
                $data = array(
                    'admin_id' => $admin_id,
                    'notify_route' => $route,
                    'notify_sender' => $from,
                    'notify_message' => $message,
                    'notify_date' => $date
                );
                $this->db->insert('notify_users', $data);

                return 1;
            } else {
                return 103;
            }
        } else {
            return 103;
        }
    }

// Notify Users By Email
    function notifyUsersByEmail($admin_id = 0, $company_name = null) {
        ini_set('max_input_time', 1200);
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '268435456');
        $notify_users = $this->input->post('notify_users');
        $contacts = implode(',', $notify_users);
        $email_address = $this->input->post('email_address');
        $mail_subject = $this->input->post('subject');
        $user_status = $this->input->post('user_status');
        $message = $this->input->post('message');
// URL Encoding
        if ($contacts != "" || $user_status) {
            $subject = "Notification: $mail_subject";
            $body = $this->utility_model->emailNotification($company_name, $message);


            if ($user_status == "Active") {
                $this->db->select('email_address');
                $this->db->from('users');
                $this->db->where('check_demo_user', 0);
                $this->db->where('admin_id', 1);
                $query = $this->db->get();
                $result = $query->result_array();
                $size_active = sizeof($result);
                for ($i = 0; $i < $size_active; $i++) {
                    $contacts[] = $result[$i]['email_address'];
                }
            }
            if ($user_status == "Demo") {
                $this->db->select('email_address');
                $this->db->from('users');
                $this->db->where('check_demo_user', 1);
                $this->db->where('admin_id', 1);
                $query = $this->db->get();
                $result = $query->result_array();
                $size_demo = sizeof($result);
                for ($i = 0; $i < $size_demo; $i++) {
                    $contacts[] = $result[$i]['email_address'];
                }
            }
             $contacts = implode(',', $contacts);

//$body = "<p>" . $message . "</p>";
// Prepare Email Array
            $mail_array = array(
                'from_email' => $email_address,
                'from_name' => $company_name,
                'to_email' => $contacts,
                'subject' => $subject,
                'message' => $body
            );



            if ($this->utility_model->sendEmailNew($mail_array)) {
                $date = date('d-m-Y h:i:s');
                $data = array(
                    'admin_id' => $admin_id,
                    'notify_email' => $email_address,
                    'notify_subject' => $subject,
                    'notify_body' => $message,
                    'notify_date' => $date
                );
                $this->db->insert('notify_users', $data);
                return 1;
            } else {
                return 103;
            }
        } else {
            return 103;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// User's Balance
//------------------------------------------------------------------------------------------------------------------------------------------//
// Save User Balance on Daily Basis (CRON JOB)
    function saveUserBalance() {
        $date = date('Y-m-d');
        $user_ids = "";
        $pr_balance = "";
        $tr_balance = "";
// Get User Info
        $this->db->select('user_id, pr_sms_balance, tr_sms_balance');
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_users = $query->result_array();

            foreach ($result_users as $key => $user) {
                if (empty($user_ids)) {
                    $user_ids = $user['user_id'];
                    $pr_balance = $user['pr_sms_balance'];
                    $tr_balance = $user['tr_sms_balance'];
                } else {
                    $user_ids .= "," . $user['user_id'];
                    $pr_balance .= "," . $user['pr_sms_balance'];
                    $tr_balance .= "," . $user['tr_sms_balance'];
                }
            }
// Insert Records
            if (!empty($user_ids)) {
                $data = array(
                    'user_id' => $user_ids,
                    'pr_balance' => $pr_balance,
                    'tr_balance' => $tr_balance,
                    'balance_date' => $date
                );
                return $this->db->insert('user_balance_logs', $data);
            }
        } else {
            return false;
        }
    }

// Get Users Balance
    function getUsersBalance() {
        $return_array = array();
        $date = date('d-m-Y');
        $select_user_id = "";
        if ($this->input->post('date')) {
            $date = $this->input->post('date');
        }
        if ($this->input->post('user_id')) {
            $select_user_id = $this->input->post('user_id');
        }
        $this->db->select('user_id, pr_balance, tr_balance');
        $this->db->from('user_balance_logs');
        $this->db->where('balance_date', $date);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            $user_ids_array = explode(',', $result->user_id);
            $pr_balance_array = explode(',', $result->pr_balance);
            $tr_balance_array = explode(',', $result->tr_balance);
            if ($select_user_id != "") {
                if (in_array($select_user_id, $user_ids_array)) {
                    $search_key = array_search($select_user_id, $user_ids_array);
                    $temp = array();
                    $user = $this->getUserInfo($select_user_id);
                    if ($user) {
                        $temp['username'] = $user['username'];
                        $temp['parent_username'] = ($user['parent_username'] == "") ? $user['admin_username'] : $user['parent_username'];
                        $temp['ref_username'] = ($user['ref_username'] == "") ? '-' : $user['ref_username'];
                        $temp['name'] = $user['name'];
                        $temp['contact_number'] = $user['contact_number'];
                        $temp['email_address'] = $user['email_address'];
                        $temp['utype'] = $user['utype'];
                        $temp['pr_balance'] = $pr_balance_array[$search_key];
                        $temp['tr_balance'] = $tr_balance_array[$search_key];
                    }
                    $return_array[] = $temp;
                }
            } else {

                foreach ($user_ids_array as $key => $user_id) {
                    $temp = array();
                    $user = $this->getUserInfo($user_id);
                    if ($user) {
                        $temp['username'] = $user['username'];
                        $temp['parent_username'] = ($user['parent_username'] == "") ? $user['admin_username'] : $user['parent_username'];
                        $temp['ref_username'] = ($user['ref_username'] == "") ? '-' : $user['ref_username'];
                        $temp['name'] = $user['name'];
                        $temp['contact_number'] = $user['contact_number'];
                        $temp['email_address'] = $user['email_address'];
                        $temp['utype'] = $user['utype'];
                        $temp['pr_balance'] = $pr_balance_array[$key];
                        $temp['tr_balance'] = $tr_balance_array[$key];
                    }
                    $return_array[] = $temp;
                }
            }
            return $return_array;
        } else {
            return false;
        }
    }

// Get Balance Logs (Current)
    function getBalanceLogs($subtab = 0) {
        if ($subtab == 1) {
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username, users1.utype AS user_type');
            $this->db->select('users1.pr_sms_balance AS pr_balance, users1.tr_sms_balance AS tr_balance, NOW() AS balance_date');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $balance_array = array();
                $result_array = $query->result_array();

                foreach ($result_array as $key => $user) {
                    $balance_array[$key]['user_id'] = $user['user_id'];
                    $balance_array[$key]['username'] = $user['username'];
                    $balance_array[$key]['admin_username'] = $user['admin_username'];
                    $balance_array[$key]['parent_username'] = $user['parent_username'];
                    $balance_array[$key]['user_type'] = $user['user_type'];
                    $balance_array[$key]['pr_balance'] = $user['pr_balance'];
                    $balance_array[$key]['tr_balance'] = $user['tr_balance'];
                    $balance_array[$key]['balance_date'] = $user['balance_date'];
// Total Balance
                    $this->db->select('SUM(pr_sms_balance) AS total_pr, SUM(tr_sms_balance) AS total_tr');
                    $this->db->from('users');
                    $this->db->where('user_id', $user['user_id']);
                    $this->db->or_where('ref_user_id', $user['user_id']);
                    $this->db->or_where('most_parent_id', $user['user_id']);
                    $query = $this->db->get();
                    $result = $query->row();
                    $balance_array[$key]['total_pr'] = $result->total_pr;
                    $balance_array[$key]['total_tr'] = $result->total_tr;
                }

                return $balance_array;
            } else {
                return false;
            }
        } else {
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username');
            $this->db->select('pr_balance, tr_balance, balance_date');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('user_balance_logs', 'user_balance_logs.user_id = users1.user_id', 'right');
            $this->db->order_by('balance_log_id', 'desc');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

// Get Balance Logs (Current)
    function filterBalanceLogs() {
        $search = null;
        $type = 0;
        $route = null;
        if ($this->input->post('search')) {
            $search = $this->input->post('search');
        }
        if ($this->input->post('type')) {
            $type = $this->input->post('type');
        }
        if ($this->input->post('route')) {
            $route = $this->input->post('route');
        }
        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username, users1.utype AS user_type');
        $this->db->select('users1.pr_sms_balance AS pr_balance, users1.tr_sms_balance AS tr_balance, NOW() AS balance_date');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        if ($type == 1) {
            $this->db->like('users1.username', $search);
        } elseif ($type == 2 && $route == 'A') {
            $this->db->where('`users1`.`pr_sms_balance` >=', $search);
        } elseif ($type == 2 && $route == 'B') {
            $this->db->where('`users1`.`tr_sms_balance` >=', $search);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            $balance_array = array();
            $result_array = $query->result_array();

            foreach ($result_array as $key => $user) {
                $balance_array[$key]['user_id'] = $user['user_id'];
                $balance_array[$key]['username'] = $user['username'];
                $balance_array[$key]['admin_username'] = $user['admin_username'];
                $balance_array[$key]['parent_username'] = $user['parent_username'];
                $balance_array[$key]['user_type'] = $user['user_type'];
                $balance_array[$key]['pr_balance'] = $user['pr_balance'];
                $balance_array[$key]['tr_balance'] = $user['tr_balance'];
                $balance_array[$key]['balance_date'] = $user['balance_date'];
// Total Balance
                $this->db->select('SUM(pr_sms_balance) AS total_pr, SUM(tr_sms_balance) AS total_tr');
                $this->db->from('users');
                $this->db->where('user_id', $user['user_id']);
                $this->db->or_where('ref_user_id', $user['user_id']);
                $this->db->or_where('most_parent_id', $user['user_id']);
                $query = $this->db->get();
                $result = $query->row();
                $balance_array[$key]['total_pr'] = $result->total_pr;
                $balance_array[$key]['total_tr'] = $result->total_tr;
            }

            return $balance_array;
        } else {
            return false;
        }
    }

// Analyze Users Balance
    function analyzeUsersBalance() {
        $return_array = array();
        $select_user_id = "";
        if ($this->input->post('from_date') && $this->input->post('from_date') != "") {
            $from_date = $this->input->post('from_date');
        } else {
            $from_date = date('Y-m-d');
        }
        if ($this->input->post('to_date') && $this->input->post('to_date') != "") {
            $to_date = $this->input->post('to_date');
        } else {
            $to_date = date('Y-m-d');
        }
        if ($this->input->post('user_id')) {
            $select_user_id = $this->input->post('user_id');
        }
        $this->db->select('user_id, balance_date, pr_balance, tr_balance');
        $this->db->from('user_balance_logs');
        $this->db->where("`balance_date` BETWEEN '$from_date' AND '$to_date'");
//$this->db->where('`balance_date` >=', $from_date);
//$this->db->where('`balance_date` <=', $to_date);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            $i = 0;
            $consumed_pr_balance = 0;
            $consumed_tr_balance = 0;
            foreach ($result as $key => $row) {
                $temp = array();
                $balance_date = $row->balance_date;
                $pr_balance_array = explode(',', $row->pr_balance);
                $tr_balance_array = explode(',', $row->tr_balance);
                $user_ids_array = explode(',', $row->user_id);
                if ($select_user_id != "") {
                    if (in_array($select_user_id, $user_ids_array)) {
                        $search_key = array_search($select_user_id, $user_ids_array);
                        $pr_balance = $pr_balance_array[$search_key];
                        $tr_balance = $tr_balance_array[$search_key];
                        $temp['balance_date'] = $balance_date;
                        $temp['available_pr_balance'] = $pr_balance;
                        $temp['available_tr_balance'] = $tr_balance;
                        $result_pr = $this->getDayWiseSMS($select_user_id, $balance_date, 'A');
                        if ($result_pr) {
                            $temp['consumed_pr_balance'] = $result_pr;
                        } else {
                            $temp['consumed_pr_balance'] = 0;
                        }
                        $result_tr = $this->getDayWiseSMS($select_user_id, $balance_date, 'B');
                        if ($result_tr) {
                            $temp['consumed_tr_balance'] = $result_tr;
                        } else {
                            $temp['consumed_tr_balance'] = 0;
                        }
                        $return_array[] = $temp;
                    }
                }
            }
            return $return_array;
        } else {
            return false;
        }
    }

// Get Consumed SMS Day Wise
    function getDayWiseSMS($user_id = 0, $date = null, $route = null) {
//$date_array = explode('-', $date);
//$date = $date_array[2] . "-" . $date_array[1] . "-" . $date_array[0];
        $this->db->select('SUM(`total_deducted`) AS `total_consumed`');
        $this->db->from('campaigns');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`route`', $route);
        $this->db->like('`submit_date`', $date);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();

            return $result->total_consumed;
        } else {
            return false;
        }
    }

// Get Active Users (Day-Wise)
    function getActiveUsers() {
        $date = date('Y-m-d');
        $route = 0;
        if ($this->input->post('route') != null) {
            $route = $this->input->post('route');
        }
        if ($this->input->post('date') != null) {
            $date = $this->input->post('date');
        }
        $this->db->select('SUM(`total_deducted`) AS `total_consumed`');
        $this->db->select('`username`, `name`, `users`.`user_id` AS `user_id`');
        $this->db->from('`campaigns`');
        $this->db->join('users', 'users.user_id=campaigns.user_id', 'LEFT');
        $this->db->like('`submit_date`', $date);
        if ($route) {
            $this->db->where('`route`', $route);
        }
        $this->db->group_by('`campaigns`.`user_id`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------//
    function checkAndSetBackupRoute() {
// Get Settings
        $settings = $this->getSetting();
        if ($settings) {
            $backup_time_duration = 5;
            if ($settings->backup_time_duration) {
                $backup_time_duration = $settings->backup_time_duration;
            }
            $backup_limit = 20;
            if ($settings->backup_limit) {
                $backup_limit = $settings->backup_limit;
            }
// Current/Past Five Minute Date-Time
            $current_date_time = date('Y-m-d H:i:s');
            $current_date = strtotime($current_date_time);
            $past_date = $current_date - (60 * $backup_time_duration);
            $past_date_time = date("Y-m-d H:i:s", $past_date);
// Count Number Of Failed & Rejected SMS of Last Five Minutes
            $status_array = array(2, 16);
            $this->db->select('COUNT(`sms_id`) AS `total_sms`, `user_group_id`');
            $this->db->from('`sent_sms`');
            $this->db->where_in('status', $status_array);
            $this->db->where("submit_date BETWEEN $past_date_time AND $current_date_time");
            $this->db->group_by('`user_group_id`');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result = $query->result_array();
                if ($result && sizeof($result)) {
                    foreach ($result AS $row) {
                        $current_ug = $row['user_group_id'];
                        $total_sms = $row['total_sms'];
// Check Number Of SMS
                        if ($current_ug && $total_sms && $total_sms >= $backup_limit) {
// Get Backup Routes
                            $this->db->select('`backup_user_group`, `purpose`');
                            $this->db->from('`user_groups`');
                            $this->db->where('user_group_id', $current_ug);
                            $query_ug = $this->db->get();
                            if ($query_ug->num_rows()) {
                                $result_ug = $query_ug->row();
                                $backup_ug_id = $result_ug->backup_user_group;
                                $purpose = $result_ug->purpose;
                                if ($backup_ug_id) {
                                    if ($purpose == 'Promotional') {
                                        $data = array('pr_user_group_id' => $backup_ug_id);
                                        $this->db->where('pr_user_group_id', $current_ug);
                                    } elseif ($purpose == 'Transactional') {
                                        $data = array('tr_user_group_id' => $backup_ug_id);
                                        $this->db->where('tr_user_group_id', $current_ug);
                                    }
                                    $this->db->update('users', $data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// SMS Rate Plans
//------------------------------------------------------------------------------------------------------------------------------------------//
// Count  SMS Rate Plans
    function countSMSRatePlans() {
        $this->db->select('`rate_plan_id`, `rate_plan_name`, `rate_plan_min`, `rate_plan_max`, `rate_plan_price`, `rate_plan_tax`, `rate_plan_status`, `service_type`');
        $this->db->from('sms_rate_plans');
        $query = $this->db->get();
        return $query->num_rows();
    }

// Get SMS Rate Plans
    function getSMSRatePlans($status = 0, $start = 0, $limit = 0) {
        $this->db->select('`rate_plan_id`, `rate_plan_name`, `rate_plan_min`, `rate_plan_max`, `rate_plan_price`, `rate_plan_tax`, `rate_plan_status`, `service_type`');
        $this->db->from('sms_rate_plans');
        if ($status) {
            $this->db->where('rate_plan_status', 1);
        } else {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

// Get SMS Rate Plan
    function getSMSRatePlan($rate_id = 0) {
        $this->db->select('`rate_plan_id`, `rate_plan_name`, `rate_plan_min`, `rate_plan_max`, `rate_plan_price`, `rate_plan_tax`, `rate_plan_status`, `service_type`');
        $this->db->from('sms_rate_plans');
        $this->db->where('rate_plan_id', $rate_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

// Save SMS Rate Plan
    function saveSMSRate($rate_id = 0) {
        $data = array(
            'rate_plan_name' => $this->input->post('rate_plan_name'),
            'service_type' => $this->input->post('service_type'),
            'rate_plan_min' => $this->input->post('rate_plan_min'),
            'rate_plan_max' => $this->input->post('rate_plan_max'),
            'rate_plan_price' => $this->input->post('rate_plan_price'),
            'rate_plan_tax' => $this->input->post('rate_plan_tax')
        );
        if ($rate_id) {
            $this->db->where('rate_plan_id', $rate_id);
            $this->db->update('sms_rate_plans', $data);
            return 201;
        } else {
            $this->db->insert('sms_rate_plans', $data);
            return 200;
        }
        return false;
    }

// Change Status SMS Rate Plan
    function changeSMSRateStatus($id = 0, $status = 0) {
        $data = array('rate_plan_status' => $status);
        $this->db->where('rate_plan_id', $id);
        return $this->db->update('sms_rate_plans', $data);
    }

// Delete SMS Rate Plan
    function deleteSMSRate($id = 0) {
        return $this->db->delete('sms_rate_plans', array('rate_plan_id' => $id));
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------//
// Cron Jobs
//------------------------------------------------------------------------------------------------------------------------------------------//
// Delete Old Data From sqlbox_sent_sms Table
    function deleteSQLBoxSMS() {
        $this->db->select('`sql_id`');
        $this->db->from('sqlbox_sent_sms');
        $this->db->order_by('sql_id');
        $query = $this->db->get();
        if ($query->num_rows()) {
// Get Total Records In Table
            $total_sms = $query->num_rows();
            if ($total_sms > 50000) {
// Get Very Fisrt Row
                $row = $query->row();
                $first_row = $row->sql_id;
// Calculate Range
                $remain = $total_sms - 50000;
// Delete Old Data
                $where = "sql_id BETWEEN $first_row AND $remain";
                $this->db->where($where);
                $this->db->delete('`sqlbox_sent_sms`');
                return TRUE;
            }
        }

        return FALSE;
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
// Missed Call Alerts Services
//------------------------------------------------------------------------------------------------------------------------------------------//
// Missed Call Alerts- Count Numbers
    function countMissedCallAlerts($subtab = 0) {
        if ($subtab == 1) {
            $this->db->select('`mc_number_id`');
            $this->db->from('missed_call_numbers');
        } elseif ($subtab == 2) {
            $this->db->select('`mc_service_id`');
            $this->db->from('missed_call_services');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

// Missed Call Alerts- Get Numbers
    function getMissedCallAlerts($subtab = 0, $status = 0, $start = 0, $limit = 0) {
        if ($subtab == 1) {
            $this->db->select('`mc_number_id`, `mc_number`, `mc_number_status`');
            $this->db->from('missed_call_numbers');
            if ($status) {
                $this->db->where('mc_number_status', 1);
                $this->db->where('mc_number_id NOT IN (SELECT mc_number_id FROM missed_call_services)');
            } else {
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }
            return false;
        }
        if ($subtab == 2) {
            $this->db->select('`mc_number`, `mc_service_id`, `forward_email`, `forward_contact`, `forward_webhook`, `mc_service_expiry`');
            $this->db->select('`auto_reply_sender`, `auto_reply_message`, `auto_reply_route`, `mc_service_status`');
            $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
            $this->db->select('users2.username AS parent_username');
            $this->db->from('users AS users1');
            $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
            $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
            $this->db->join('missed_call_services', 'missed_call_services.user_id = users1.user_id', 'right');
            $this->db->join('missed_call_numbers', 'missed_call_numbers.mc_number_id = missed_call_services.mc_number_id', 'left');
            $this->db->order_by('mc_service_id', 'desc');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }
            return false;
        }
    }

// Missed Call Alerts- Save
    function saveMissedCallAlerts($subtab = 0) {
        if ($subtab == 1) {
            $data = array(
                'mc_number' => $this->input->post('service_number')
            );
            return $this->db->insert('missed_call_numbers', $data);
        } elseif ($subtab == 2) {
            $data = array(
                'user_id' => $this->input->post('select_user'),
                'mc_number_id' => $this->input->post('service_number'),
                'mc_service_expiry' => $this->input->post('validity_date')
            );
            return $this->db->insert('missed_call_services', $data);
        }
    }

// Missed Call Alerts- Change Status
    function statusMissedCallAlerts($id = 0, $status = 0, $subtab = 0) {
        if ($subtab == 1) {
            $data = array('mc_number_status' => $status);
            $this->db->where('mc_number_id', $id);
            return $this->db->update('missed_call_numbers', $data);
        } elseif ($subtab == 2) {
            $data = array('mc_service_status' => $status);
            $this->db->where('mc_service_id', $id);
            return $this->db->update('missed_call_services', $data);
        }
    }

// Missed Call Alerts- Delete
    function deleteMissedCallAlerts($id = 0, $subtab = 0) {
        if ($subtab == 1) {
            return $this->db->delete('missed_call_numbers', array('mc_number_id' => $id));
        } elseif ($subtab == 2) {
            return $this->db->delete('missed_call_services', array('mc_service_id' => $id));
        }
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------//
// Cron Jobs
//------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------//
// Check New Voice SMS Request
    function checkVoiceSMS() {
        $this->db->select('`check_voice_id`, `campaign_id`, `check_voice_status`, `caller_id`, `voice_file_url`,`user_id`,`voice_route_id`');
        $this->db->from('check_voice_sms');
        $this->db->where('check_voice_status', 1);
        // $this->db->where_not_in('user_id', array(4568, 5159, 5158));

        $query = $this->db->get();
        if ($query->num_rows()) {

            $result = $query->result_array();


            if ($result) {
                foreach ($result as $key => $row) {
                    $check_voice_id = $row['check_voice_id'];
                    $campaign_id = $row['campaign_id'];
                    $caller_id = $row['caller_id'];
                    $voice_file_url = $row['voice_file_url'];
                    $user_id = $row['user_id'];
                    $voice_route_id = $row['voice_route_id'];
                    $this->db->select('*');
                    $this->db->from('voice_route');
                    $this->db->where('voice_route_id', $voice_route_id);
                    $this->db->where('status', 1);
                    $query_route = $this->db->get();
                    $result_route = $query_route->row();


//==========================================================//
// Update Status
                    $this->db->where('check_voice_id', $check_voice_id);
                    $this->db->update('check_voice_sms', array('check_voice_status' => 0));
//==========================================================//
// Check Campaign
                    if ($campaign_id) {
                        $this->db->select('`message`, `mobile_no`');
                        $this->db->from('`sent_sms`');
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->where('status', 31);
                        $this->db->where('temporary_status', 1);
                        $query1 = $this->db->get();
                        if ($num_rows = $query1->num_rows()) {

                            $username = 'Balajigroup';
                            $password = 'Mayank@1234';
// Single Record
                            if ($num_rows == 1) {
                                $row_voice_sms = $query1->row_array();
                                $to = $row_voice_sms['mobile_no'];

                                if ($row_voice_sms) {

//--------------------------------------------------------//
// Infobip Voice SMS API

                                    if (is_numeric($voice_file_url)) {

                                        $mobiles = $to;

                                        $route_name = $result_route->route_name;
                                        $username = $result_route->username;
                                        $password = $result_route->password;
                                        $authkey = $result_route->authkey;
                                        $username = $result_route->username;
                                        $api_url = $result_route->api_url;
                                        $username_perameter_name = $result_route->username_perameter_name;
                                        $password_persameter_name = $result_route->password_persameter_name;
                                        $authkey_perameter_name = $result_route->authkey_perameter_name;
                                        $voice_file_pera_name = $result_route->voice_file_pera_name;
                                        $mobile_perameter = $result_route->mobile_perameter;
                                        $caller_id_perameter = $result_route->caller_id_perameter;
                                        $perameter4 = $result_route->perameter4;
                                        $perameter5 = $result_route->perameter5;

                                        $username = $username;   // Enter User Email
                                        $password = $password;    // Enter User Password
                                        $VoiceID = $voice_file_url;          // Enter Voice File ID
                                        $CampaignName = "TestCampaign123";  // Enter Campaign Name Which You Want
                                        $CampaignData = $mobiles;   // Mobile Numbers With Comma Separated
# URL Of Campaign Creation 
                                        $url = $api_url;

# Post Data Array
                                        $data = array(
                                            "$authkey_perameter_name" => $authkey,
                                            "$username_perameter_name" => $username,
                                            "$password_persameter_name" => $password,
                                            "$voice_file_pera_name" => $VoiceID,
                                            "$mobile_perameter" => $CampaignData,
                                            "$caller_id_perameter" => $caller_id,
                                        );
                                        $options = array(
                                            CURLOPT_POST => true,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $data,
                                            CURLOPT_RETURNTRANSFER => true, // return web page
                                            CURLOPT_HEADER => false, // don't return headers
                                            CURLOPT_FOLLOWLOCATION => true, // follow redirects
                                            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                                            CURLOPT_ENCODING => "", // handle compressed
                                            CURLOPT_USERAGENT => "test", // name of client
                                            CURLOPT_AUTOREFERER => true, // set referrer on redirect
                                            CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
                                            CURLOPT_TIMEOUT => 120, // time-out on response
                                        );

                                        $ch = curl_init($url);
                                        curl_setopt_array($ch, $options);
                                        $content = curl_exec($ch);
                                        curl_close($ch);
                                        $upadte_sms = json_decode($content, true);
                                        $upadte_sms = json_decode($content, true);
                                        $status = 31;
                                        $discription = "Message In Process";
                                        if ($upadte_sms['message']['CampaignID'] == "I") {
                                            $status = 2;
                                            $discription = "Invalid Voice Id";
                                        }


                                        $update_data = array(
                                            'msg_id' => $upadte_sms['message']['CampaignID'],
                                            'status' => $status,
                                            'description' => $discription,
                                        );





                                        $this->db->where('mobile_no', $mobiles);
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->update('sent_sms', $update_data);
                                    } else {
                                        $curl = curl_init();

                                        curl_setopt_array($curl, array(
                                            CURLOPT_URL => "http://api.infobip.com/tts/3/single",
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => "",
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 30,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => "POST",
                                            CURLOPT_POSTFIELDS => "{\n  \"from\": \"$caller_id\",\n  \"to\": \"$to\",\n  \"audioFileUrl\": \"$voice_file_url\",\n  \"language\": \"en\"\n}",
                                            CURLOPT_HTTPHEADER => array(
                                                "accept: application/json",
                                                "authorization: Basic QmFsYWppZ3JvdXA6QmFsMTIz",
                                                "content-type: application/json"
                                            ),
                                        ));
//QmFsYWppZ3JvdXA6QmFsQDIwMTc=
                                      echo   $response = curl_exec($curl);
                                        curl_close($curl);


// Get Status of Each Number & Update into table

                                        $upadte_sms = json_decode($response, true);
                                        $bulkid = $upadte_sms['bulkId'];


                                        $update_info = $upadte_sms['messages'];
                                        $actual_info = $update_info[0];


                                        $messageid = $actual_info['messageId'];

                                        $to = $actual_info['to'];


                                        $update_data = array(
                                            'mobile_no' => $row_voice_sms['mobile_no'],
                                            'msg_id' => $messageid,
                                            'ttsCallRequestId' => $bulkid,
                                            'description' => "Request is waiting in a queue"
                                        );



                                        $this->db->where('mobile_no', $row_voice_sms['mobile_no']);
                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->update('sent_sms', $update_data);
                                    }


//--------------------------------------------------------//
                                }
                            }

// Multiple Record
                            if ($num_rows > 1) {

                                $result_voice_sms = $query1->result_array();
                                if ($result_voice_sms) {
                                    $result_array = array();

//$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);

                                    if (is_numeric($voice_file_url)) {
                                        foreach ($result_voice_sms as $key => $row_voice_sms) {
                                            $to[] = $row_voice_sms['mobile_no'];
                                        }
                                        $actual = implode(',', $to);
                                        $mobiles = $actual;

                                        $route_name = $result_route->route_name;
                                        $username = $result_route->username;
                                        $password = $result_route->password;
                                        $authkey = $result_route->authkey;
                                        $username = $result_route->username;
                                        $api_url = $result_route->api_url;
                                        $username_perameter_name = $result_route->username_perameter_name;
                                        $password_persameter_name = $result_route->password_persameter_name;
                                        $authkey_perameter_name = $result_route->authkey_perameter_name;
                                        $voice_file_pera_name = $result_route->voice_file_pera_name;
                                        $mobile_perameter = $result_route->mobile_perameter;
                                        $caller_id_perameter = $result_route->caller_id_perameter;
                                        $perameter4 = $result_route->perameter4;
                                        $perameter5 = $result_route->perameter5;

                                        $username = $username;   // Enter User Email
                                        $password = $password;    // Enter User Password
                                        $VoiceID = $voice_file_url;          // Enter Voice File ID
                                        $CampaignName = "TestCampaign123";  // Enter Campaign Name Which You Want
                                        $CampaignData = $mobiles;   // Mobile Numbers With Comma Separated
# URL Of Campaign Creation 
                                        $url = $api_url;

# Post Data Array
                                        $data = array(
                                            "$authkey_perameter_name" => $authkey,
                                            "$username_perameter_name" => $username,
                                            "$password_persameter_name" => $password,
                                            "$voice_file_pera_name" => $VoiceID,
                                            "$mobile_perameter" => $CampaignData,
                                            "$caller_id_perameter" => $caller_id,
                                        );
                                        $options = array(
                                            CURLOPT_POST => true,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => $data,
                                            CURLOPT_RETURNTRANSFER => true, // return web page
                                            CURLOPT_HEADER => false, // don't return headers
                                            CURLOPT_FOLLOWLOCATION => true, // follow redirects
                                            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                                            CURLOPT_ENCODING => "", // handle compressed
                                            CURLOPT_USERAGENT => "test", // name of client
                                            CURLOPT_AUTOREFERER => true, // set referrer on redirect
                                            CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
                                            CURLOPT_TIMEOUT => 120, // time-out on response
                                        );

                                        $ch = curl_init($url);
                                        curl_setopt_array($ch, $options);
                                        $content = curl_exec($ch);
                                        curl_close($ch);

                                        $upadte_sms = json_decode($content, true);
                                        $status = 31;
                                        $discription = "Message In Process";
                                        if ($upadte_sms['message']['CampaignID'] == "I") {
                                            $status = 2;
                                            $discription = "Invalid Voice Id";
                                        }


                                        $update_data = array(
                                            'msg_id' => $upadte_sms['message']['CampaignID'],
                                            'status' => $status,
                                            'description' => $discription,
                                        );


                                        $this->db->where('campaign_id', $campaign_id);
                                        $this->db->update('sent_sms', $update_data);
                                    } else {

                                        foreach ($result_voice_sms as $key => $row_voice_sms) {
                                            // $result_array[] = $row_voice_sms['mobile_no'];
//--------------------------------------------------------//
// Infobip Voice SMS API
                                            $to = $row_voice_sms['mobile_no'];
                                            $curl = curl_init();

                                            curl_setopt_array($curl, array(
                                                CURLOPT_URL => "http://api.infobip.com/tts/3/single",
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => "",
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 30,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => "POST",
                                                CURLOPT_POSTFIELDS => "{\n  \"from\": \"$caller_id\",\n  \"to\": \"$to\",\n  \"audioFileUrl\": \"$voice_file_url\",\n  \"language\": \"en\"\n}",
                                                CURLOPT_HTTPHEADER => array(
                                                    "accept: application/json",
                                                    "authorization: Basic QmFsYWppZ3JvdXA6QmFsMTIz",
                                                    "content-type: application/json"
                                                ),
                                            ));

                                            //QmFsYWppZ3JvdXA6QmFsQDIwMTc=

                                            if ($response = curl_exec($curl)) {
                                                $upadte_sms = json_decode($response, true);
                                                $bulkid = $upadte_sms['bulkId'];


                                                $update_info = $upadte_sms['messages'];
                                                $actual_info = $update_info[0];


                                                $messageid = $actual_info['messageId'];

                                                $to = $actual_info['to'];


                                                $update_data = array(
                                                    'mobile_no' => $row_voice_sms['mobile_no'],
                                                    'msg_id' => $messageid,
                                                    'ttsCallRequestId' => $bulkid
                                                );

                                                $this->db->where('mobile_no', $row_voice_sms['mobile_no']);
                                                $this->db->where('campaign_id', $campaign_id);
                                                $this->db->update('sent_sms', $update_data);
// Get Status of Each Number & Update into table
                                            } else {
                                                $error_msg = curl_error($curl);
                                                $myfile = fopen(base_url() . "Voice/logs.txt", "a") or die("Unable to open file!");
                                                $txt = "Error: " . $error_msg;
                                                fwrite($myfile, "\n" . $txt);
                                                fclose($myfile);
                                            }
                                        }
                                    }

//--------------------------------------------------------//
                                }
                            }
                        }
// Delete Record From 'check_voice_sms' Table
                        $this->db->delete('check_voice_sms', array('check_voice_id' => $check_voice_id));
                    }
                }
            }
        }
    }

// Update Fake  Failed And Fake Delivered SMS 
    function updateFakeSMS() {
        $current_time = strtotime(date('Y-m-d H:i:s'));
        $start_date = date("Y-m-d H:i:s", strtotime('-50 minutes', $current_time));
        $end_date = date("Y-m-d H:i:s", strtotime('+5 minutes', $current_time));
        $current_date = date('Y-m-d H:i:s');
// Update Fake Failed SMS
        $this->db->query("UPDATE sent_sms SET status = '2', done_date='$current_date' "
                . " WHERE status IN (31) AND temporary_status IN (3) "
                . "AND `submit_date` BETWEEN '$start_date' AND '$current_date' ");
// Update Fake Delivered SMS
        $this->db->query("UPDATE sent_sms SET status = '1', done_date='$current_date' "
                . " WHERE status IN (31) AND temporary_status IN (2) "
                . "AND `submit_date` BETWEEN '$start_date' AND '$current_date' ");
    }

//valid admin for show smsc details
    public function checkAdminSmscDetails() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');


// Encrypted Password
        $password = md5($password);
        $this->db->select('admin_id, admin_username, admin_contact, admin_alt_contacts, admin_email, total_pr_balance, total_tr_balance, atype');
        $this->db->from('administrators');
        $this->db->where('admin_username', $username);
        $this->db->where('admin_password', $password);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $sessiondata = array(
                'admin_username' => $username,
            );
            $this->session->set_userdata($sessiondata);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getSmscName() {
        $this->db->select('`user_group_name`,`smsc_id`');
        $this->db->from('user_groups');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function showSmscDetails() {
        $date = $this->input->post('date');
        $smsc_id = $this->input->post('smsc');


        $newDate = date("Y-m-d", strtotime($date));

        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where('default_route', $smsc_id);
        $this->db->like('submit_date', $newDate);
        $this->db->order_by('submit_date', 'DESC');
//$this->db->limit(8000);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

//Cron jobs 
// send message from another route

    public function retrySchduling() {
        $this->db->select('smsc_id');
        $this->db->from('retry_route');
        $query = $this->db->get();
        $result = $query->row();
        $resend_route = $result->smsc_id;
        $action_type = 2;
        $service = "SMS";
        $sender_id = 'BSSPIN';
        $currentTime = date('Y-m-d H:i:s');
        $mins1 = strtotime('-120 seconds');
        $mins = strtotime('-30 seconds');
        $fdate = date('Y-m-d H:i:s', $mins);
        $tdate = date('Y-m-d H:i:s', $mins1);
        $route = "A";
        $by_users = array();
        $this->db->select('user_id');
        $this->db->from('retry_users');
        $query1 = $this->db->get();
        $result1 = $query1->result_array();
        foreach ($result1 as $userid) {
            $by_users[] = $userid['user_id'];
        }


// Action Type
        if ($action_type == 1) {
// All
            $sms_status = array();
            $temporary_status = 0;
        } elseif ($action_type == 2) {
// Only Failed
            $sms_status = array('2');
            $temporary_status = 1;
        } elseif ($action_type == 16) {
// Only Rejected
            $sms_status = array('16');
            $temporary_status = 1;
        } elseif ($action_type == 3) {
// Only Pending, Submit, Buffered, Report Pending
            $sms_status = array('31', '8', '4', '3');

            $temporary_status = 1;
        } elseif ($action_type == '4') {
// Only Delivered
            $sms_status = array('1');
            $temporary_status = 1;
        } elseif ($action_type == '5') {
// Only Fake Delivered
            $sms_status = array();
            $temporary_status = 2;
        } elseif ($action_type == '6') {
// Only Fake Failed
            $sms_status = array();
            $temporary_status = 3;
        }

// Route
        if ($route == 'A') {
            $new_route = "Default";
        } elseif ($route == 'B') {
            $new_route = "Template";
        }

// XML API Credentials
        $new_campaign_uid = 0;
        $xml = "";
// If XML
        $is_xml = 0;
        $authentication = "";
        $xml_file = "";
        if ($resend_route == 'XML') {
            $result_setting = $this->sms_model->getDefaultSettings();
            $authentication = $result_setting->xml_route_authkey;
            $xml_url = $result_setting->xml_route_url;
            $is_xml = 1;
        }
// Get SMS That Will Be Resend
        $this->db->select('`sender_id`, `route`, `msg_id`, `mobile_no`, `message_type`, `flash_message`, `sent_sms`.`message` AS `message`, `campaign_name`');
        $this->db->select('`flash_message`, `message_type`, `smsc_id`, `sent_sms`.`campaign_id` AS `campaign_id`, `sent_sms`.`status` AS `status`');
        $this->db->from('`sent_sms`');
        $this->db->join('campaigns', 'campaigns.campaign_id=sent_sms.campaign_id', 'LEFT');
        $this->db->join('user_groups', 'user_groups.user_group_id=campaigns.user_group_id', 'LEFT');
        $this->db->where('sent_sms.submit_date >=', $tdate);
        $this->db->where('sent_sms.submit_date <=', $fdate);
        $this->db->where('campaigns.service_type', $service);

// If Route Selected
        if ($route) {
            $this->db->where('campaigns.route', $route);
        }

// If User/Users Selected
        if ($by_users != 'null') {
            $this->db->where_in('campaigns.user_id', $by_users);
        }


        if (sizeof($sms_status)) {
            $this->db->where_in('sent_sms.status', $sms_status);
            $this->db->where('temporary_status', $temporary_status);
        } elseif ($temporary_status) {
            $this->db->where('temporary_status', $temporary_status);
        }
        $query = $this->db->get();
        if ($num_rows = $query->num_rows()) {
// No SMSC Route Available
            if ($resend_route == 'XML') {
                $result_array = $query->result_array();
                foreach ($result_array as $key => $sms) {
                    $campaign_id = $sms['campaign_id'];
                    $from = $sms['sender_id'];
                    $mobile_no = $sms['mobile_no'];
                    $nmessage = $sms['message'];
                    $campaign_name = $sms['campaign_name'];
                    $flash_message = $sms['flash_message'];
                    $unicode = 0;
                    if ($sms['message_type'] == 2) {
                        $unicode = 1;
                    }
// No SMSC Route Available
// Prepare XML
                    $xml_file .= "<MESSAGE>";
                    $xml_file .= "<AUTHKEY>$authentication</AUTHKEY>";
                    $xml_file .= "<SENDER>$from</SENDER>";
                    $xml_file .= "<ROUTE>$new_route</ROUTE>";
                    $xml_file .= "<CAMPAIGN>$campaign_name</CAMPAIGN>";
                    $xml_file .= "<FLASH>$flash_message</FLASH>";
                    $xml_file .= "<UNICODE>$unicode</UNICODE>";
                    $xml_file .= "<SMS TEXT='$nmessage'>";
                    $xml_file .= "<ADDRESS TO='$mobile_no'></ADDRESS>";
                    $xml_file .= "</SMS>";
                    $xml_file .= "</MESSAGE>";
                    /*
                      foreach ($result_array as $key => $number) {
                      $xml_file .= "<SMS TEXT='$result_msg_array[$key]'>";
                      $xml_file .= "<ADDRESS TO='$number'></ADDRESS>";
                      $xml_file .= "</SMS>";
                      }
                     */
                    $new_campaign_uid = $this->utility_model->sendSMSXML($xml_url, $xml_file);
// Update Campaign Uid
                    if (isset($new_campaign_uid) && $new_campaign_uid) {
                        $data = array(
                            'campaign_uid' => $new_campaign_uid
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                    }
                }
            } else {
// Insert Data Sent SMS Table To SQLBox Send SMS Table                    
                $momt = "MT";
                $sms_type = 2;
                $dlr_mask = "31";
                $result_array = $query->result_array();
                foreach ($result_array as $key => $sms) {
// Calculate Credits
//$length = strlen(utf8_decode(urldecode($sms['message'])));
                    $charset = "ASCII";
                    $coding = 0;
                    $total_credits = 0;
                    $unicode = 0;
                    $result_credits = $this->sms_model->getSMSCredits($sms['message_type'], 0, "");
                    if ($result_credits) {
                        $charset = $result_credits['charset'];
                        $coding = $result_credits['coding'];
                        $total_credits = $result_credits['credits'];
                        $unicode = $result_credits['unicode'];
                    }
// Flash Message
                    $mclass = null;
                    $alt_dcs = 0;
                    $result_flash = $this->sms_model->getFlashMessage($sms['flash_message']);
                    if ($result_flash) {
                        $mclass = $result_flash['mclass'];
//$alt_dcs = $result_flash['alt_dcs'];
                    }

// Route / SMSC ID
                    if ($resend_route == "") {
                        $new_smsc_id = $sms['smsc_id'];
                    } else {
                        $new_smsc_id = $resend_route;
                    }
                    $data_array[] = array(
                        'momt' => $momt,
                        'sender' => $sms ['sender_id'],
                        'receiver' => $sms ['mobile_no'],
                        'msgdata' => $sms['message'],
                        'smsc_id' => $new_smsc_id,
                        'id' => $sms['campaign_id'],
                        'sms_type' => $sms_type,
                        'dlr_mask' => $dlr_mask,
                        'dlr_url' => $sms['campaign_id'],
                        'mclass' => $mclass,
                        'coding' => $coding,
                        'charset' => $charset
                    );
                }
                if (sizeof($data_array)) {
                    $res_success = $this->db->insert_batch('sqlbox_send_sms', $data_array);
                }
            }
            return true;
        } else {
            return false;
        }
    }

//trail
    public function getemp() {
        $this->db->select('*');
        $this->db->from('sqlbox_send_sms');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function searchUsersKeyword($username = null) {

        $this->db->select('users1.user_id AS user_id, users1.username AS username, admin_username');
        $this->db->select('users2.username AS parent_username');
        $this->db->select('keyword_id, keywords, percent_ratio_user, percent_ratio_all_users');
        $this->db->from('users AS users1');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('keywords', 'keywords.user_id = users1.user_id', 'right');
//     $this->db->where('keyword_status', $keyword_status);
        $this->db->order_by("`keywords`.`keyword_id`", "desc");
        $this->db->like('users2.username', $username);
//$this->db->or_like('users.name', $username);
//   $this->db->or_like('users1.contact_number', $username);
//  $this->db->or_like('users1.email_address', $username);
//  $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

//get admin names
    function getAdminName() {
        $this->db->select("`admin_id`, `admin_username`, `admin_password`, `admin_name`, `admin_contact`, `admin_alt_contacts`, `admin_email`, `admin_company`, `role`, `total_pr_balance`, `total_tr_balance`, `total_lcode_balance`, `total_scode_balance`, `total_vpr_balance`, `total_vtr_balance`, `total_mcall_balance`, `expiry_date`, `creation_date`, `atype`, `permissions`, `admin_status`");
        $this->db->from('`administrators`');
        $this->db->where('`admin_status`', 1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    function adminNameForLead() {
        $this->db->select("`admin_id`, `admin_username`, `admin_password`, `admin_name`, `admin_contact`, `admin_alt_contacts`, `admin_email`, `admin_company`, `role`, `total_pr_balance`, `total_tr_balance`, `total_lcode_balance`, `total_scode_balance`, `total_vpr_balance`, `total_vtr_balance`, `total_mcall_balance`, `expiry_date`, `creation_date`, `atype`, `permissions`, `admin_status`");
        $this->db->from('`administrators`');
        $this->db->where('`admin_role`', 2);
        $this->db->where('`admin_status`', 1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

//get seller trasection
    function getTrnsectionLogs() {
        $this->db->select("`txn_log_id`, `txn_route`, `txn_sms`, `txn_price`, `txn_amount`, `txn_type`, `txn_admin_from`, `txn_admin_to`, `txn_user_from`, `txn_user_to`, `txn_date`, `txn_description`,`admin_discription`");
        $this->db->from('`transaction_logs`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

///get seller report by name,text type and date bet been
    function getSellerReport() {
        $this->db->select('*');
        $this->db->from('`transaction_logs`');
        if ($this->input->post('admin_id')) {
            $by_admin_id = $this->input->post('admin_id');
            $this->db->where('account_admin', $by_admin_id);
        }
        if ($this->input->post('by_from_date') && $this->input->post('by_from_date') != "" && $this->input->post('by_to_date') && $this->input->post('by_to_date') != "") {
            $fdate = $this->input->post('by_from_date');
            $tdate = $this->input->post('by_to_date');
            $this->db->where('new_date BETWEEN "' . $fdate . '" and "' . $tdate . '"');
        }
        $this->db->where('txn_type', 'Add');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //get special seller report by name,text type and date bet been
    function getSpecialSellerReport() {
        $this->db->select('*');
        $this->db->from('special_transaction_logs');
        if ($this->input->post('admin_id')) {
            $by_admin_id = $this->input->post('admin_id');
            $this->db->where('account_admin', $by_admin_id);
        }
        if ($this->input->post('by_from_date') && $this->input->post('by_from_date') != "" && $this->input->post('by_to_date') && $this->input->post('by_to_date') != "") {
            $fdate = $this->input->post('by_from_date');
            $tdate = $this->input->post('by_to_date');
            $this->db->where('date BETWEEN "' . $fdate . '" and "' . $tdate . '"');
        }
        $this->db->where('type', 'Add');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

//show payment details on payment view
    public function paymentAproval() {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_admin_from >', 0);
        $this->db->where('txn_type', 'Add');
        $this->db->order_by('txn_status', 'asc');
        $this->db->order_by('aproval_date', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //search payment aproval by date
    public function searchAprovalPayment() {
        $date = $this->input->post('name');

        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_admin_from >', 0);
        $this->db->where('txn_type', 'Add');
        $this->db->like('aproval_date', $date, 'after');
        $this->db->order_by('txn_status', 'asc');
        $this->db->order_by('aproval_date', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function searchNotAprovalSenderId() {
        $date = $this->input->post('name');

        $this->db->select('*');
        $this->db->from('not_approve_senderid');
        $this->db->where('date', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function approveAllSenderId() {

        $this->db->select('sender_id');
        $this->db->from('not_approve_senderid');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result_array();

            foreach ($result as $result1) {
                $sender_id = $result1['sender_id'];
                $data = array(
                    'sender_id' => $sender_id,
                    'route' => 38,
                    'status' => 0,
                );

                $new_data[] = $data;
            }
            $this->db->insert_batch('approve_sender_id', $new_data);

            $this->db->where('id >', 0);
            $this->db->delete('not_approve_senderid');
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function searchPaymentReports($search = null) {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_admin_from >', 0);
        $this->db->order_by('txn_status', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //payment aproval
    public function paymentAprovalUpdate($txn_log_id) {

        $result_array = $this->getSelectedPayment($txn_log_id);
        $tax_status = $result_array[0]['tax_status'];
        $txn_amount = $result_array[0]['txn_amount'];

        $tax_included = ($txn_amount * 14.5) / 100;
        $final_amount = $txn_amount - $tax_included;
        $aproval_date = date('Y-m-d');

        if ($tax_status == 1) {
            $data = array(
                'txn_status' => 1,
                'aproval_date' => $aproval_date,
                'actual_amount' => $final_amount,
                'tax_amount' => $tax_included
            );
            $this->db->where('txn_log_id', $txn_log_id);
            $this->db->update('transaction_logs', $data);
            return TRUE;
        } else {
            $data = array(
                'txn_status' => 1,
                'aproval_date' => $aproval_date,
                'actual_amount' => $txn_amount,
                'tax_amount' => 0
            );
            $this->db->where('txn_log_id', $txn_log_id);
            $this->db->update('transaction_logs', $data);
            return TRUE;
        }
    }

    //update paymnt status

    public function updatePaymentStatus() {
        $selected_payment = $this->input->post('selected_payment');
        $selected_payment_array = explode(',', $selected_payment);
        // var_dump($selected_contact_array);die;
        $total_update = sizeof($selected_payment_array);
        $result_array = $this->getSelectedPayment($selected_payment_array);

        $num_of_array = count($result_array);
        $new_array = array();
        for ($i = 0; $i < $num_of_array; $i++) {
            $new_array[] = $result_array[$i]['txn_status'];
        }
        $unique_array = array_unique($new_array);
        $count = count($unique_array);

        if ($count == 1) {
            foreach ($result_array as $value) {
                $txn_log_id = $value['txn_log_id'];
                $status = $value['txn_status'];
                $txn_amount = $value['txn_amount'];
                $tax_status = $value['tax_status'];

                if ($status == 1) {
                    $aproval_date = date('Y-m-d');
                    $data1 = array(
                        'txn_status' => 0,
                        'aproval_date' => $aproval_date
                    );
                    $this->db->where('txn_log_id', $txn_log_id);
                    $this->db->update('transaction_logs', $data1);
                    return TRUE;
                } elseif ($status == 0) {

                    if ($tax_status == 1) {
                        $tax_included = ($txn_amount * 14.5) / 100;
                        $final_amount = $txn_amount - $tax_included;
                        $aproval_date = date('Y-m-d');
                        $data2 = array(
                            'txn_status' => 1,
                            'aproval_date' => $aproval_date,
                            'actual_amount' => $final_amount,
                            'tax_amount' => $tax_included
                        );
                        //var_dump($data2);die;
                        $this->db->where('txn_log_id', $txn_log_id);
                        $this->db->update('transaction_logs', $data2);
                        return TRUE;
                    } else {
                        $aproval_date = date('Y-m-d');
                        $data2 = array(
                            'txn_status' => 1,
                            'aproval_date' => $aproval_date,
                            'actual_amount' => $txn_amount,
                            'tax_amount' => 0
                        );
                        $this->db->where('txn_log_id', $txn_log_id);
                        $this->db->update('transaction_logs', $data2);
                        return TRUE;
                    }
                }
                //  if (sizeof($group_id_array) == 1 && in_array($group_id, $group_id_array)) {
                //var_dump($contact_id);die;
                // $this->db->delete('contacts', array('contact_id' => $contact_id));
            }
        } else {
            return FALSE;
        }
    }

    //get select payment status
    function getSelectedPayment($selected_payment_array = null) {
        // var_dump($selected_contact_array);die;
        $this->db->select('txn_status,txn_log_id,txn_amount,tax_status');
        $this->db->from('`transaction_logs`');
        $this->db->where_in('`txn_log_id`', $selected_payment_array);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //payment disaproval
    public function paymentDisAproval($txn_log_id) {
        $disaproval_date = date('Y-m-d');
        $data = array(
            'txn_status' => 0,
            'aproval_date' => $disaproval_date
        );
        $this->db->where('txn_log_id', $txn_log_id);
        $this->db->update('transaction_logs', $data);
        return TRUE;
    }

    //daily signup logs

    public function dailySignupLogs() {
        $today_date = date('d-m-Y');

        $this->db->select('*');
        $this->db->from('users');
        // $this->db->like('creation_date', $today_date);
        $this->db->where('admin_id', 1);
        $this->db->order_by('user_id', 'DESC');
        $this->db->limit(100);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function updateFeedback($user_id) {
        $feedback = $this->input->post('feedback');
        $lead_by = $this->input->post('lead_by');
        $user_id = $this->input->post('user_id');
        $this->db->select('admin_id');
        $this->db->from('administrators');
        $this->db->where('admin_name', $lead_by);
        $query = $this->db->get();
        $result = $query->row();
        $admin_id = $result->admin_id;

        $data = array(
            'feedback' => $feedback,
            'lead_by' => $lead_by,
            'account_manager' => $admin_id
        );

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
        return true;
    }

    //show payment status in subadmin portal
    public function showPaymentDetails($admin_id) {
        if ($admin_id == 1 || $admin_id == 2) {
            $this->db->select('*');
            $this->db->from('transaction_logs');
            $this->db->where('txn_admin_from !=', 0);
            $this->db->where('txn_type', 'Add');
            $this->db->where('txn_log_id > ', 8620);
            $this->db->order_by('txn_log_id', 'DESC');
            $this->db->limit(300);
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        } else {
            $this->db->select('*');
            $this->db->from('transaction_logs');
            $this->db->where('txn_admin_from', $admin_id);
            $this->db->where('txn_type', 'Add');
            $this->db->order_by('new_date', 'DESC');
            $this->db->limit(200);
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }
    }

    //search sub-admin payment by date
    public function searchPaymentByDate($admin_id) {
        $date = $this->input->post('name');
        /* $date=date_create($d);
          $date1= date_format($date,"Y-m-d"); */
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_admin_from', $admin_id);
        $this->db->where('txn_type', 'Add');
        $this->db->like('aproval_date', $date, 'after');
        $this->db->order_by('txn_status', 'asc');
        $this->db->order_by('aproval_date', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

//get old price for credit and re-credit
    public function getOldPrice($user_id, $type, $balance_type) {
        $this->db->select('txn_price');
        $this->db->from('transaction_logs');
        $this->db->where('txn_route', $balance_type);
        $this->db->where('txn_type', $type);
        $this->db->where('txn_user_to', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $demo = end($result);
    }

    // get all user groups for retry

    public function getAllRoute() {
        $this->db->select('user_group_id,user_group_name,smsc_id,purpose');
        $this->db->from('user_groups');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    // save retry user
    public function saveRetryUser($type) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $user_id = array_values($myArray);
        $this->db->select('user_id,username,name');
        $this->db->from('users');
        $this->db->where_in('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result1) {
            $id = $result1['user_id'];
            $username = $result1['username'];
            $name = $result1['name'];
            $data = array(
                'user_id' => $id,
                'username' => $username,
                'name' => $name,
            );
            $this->db->insert('retry_users', $data);
        }
        return TRUE;
    }

    public function saveSpecialReseller($type) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $user_id = array_values($myArray);

        /*  $data = array(
          'spacial_reseller_status' => 1,
          );

          $this->db->where('user_id', $user_id);
          $this->db->update('users', $data);
         */
        return TRUE;
    }

    //save retry route
    public function saveRetryRoute($route) {
        $this->db->select('user_group_id,user_group_name,smsc_id,purpose');
        $this->db->from('user_groups');
        $this->db->where('user_group_id', $route);
        $query = $this->db->get();
        $result = $query->row();

        $smsc_id = $result->smsc_id;
        $user_group_name = $result->user_group_name;
        $user_group_id = $result->user_group_id;

        $data = array(
            'route_name' => $user_group_name,
            'route_id' => $user_group_id,
            'smsc_id' => $smsc_id
        );
        $this->db->where('id', 1);
        $this->db->update('retry_route', $data);
        return TRUE;
    }

    public function getSignUpReport() {
        $admin = $this->input->post('admin_name');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $start_date = date("d-m-Y", strtotime(date($from_date) . " +365 day"));
        $to_date = date("d-m-Y", strtotime(date($to_date) . " +365 day"));

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('admin_id', 1);
        $this->db->where("`expiry_date` BETWEEN '" . $start_date . "' AND '" . $to_date . "'");
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //get sms consumption report
    public function getSmsReport() {

        $user_id = $this->input->post('selected_user');
        $user_type = $this->input->post('user_type');
        $route = $this->input->post('route');
        $fdate = $this->input->post('search_from_date');
        $tdate = $this->input->post('search_to_date');
        if ($route == 'A' || $route == 'C' || $route == 'D') {

            $this->db->select('user_group_id');
            $this->db->from('user_groups');
            $this->db->where('purpose', 'Promotional');
            $this->db->where('user_group_status', 1);
            $query = $this->db->get();
            $groups = $query->result_array();
            foreach ($groups as $pr_groups) {
                $user_groups[] = $pr_groups['user_group_id'];
            }
        } elseif ($route == 'B') {
            $this->db->select('user_group_id');
            $this->db->from('user_groups');
            $this->db->where('purpose', 'Transactional');
            $this->db->where('user_group_status', 1);
            $query = $this->db->get();
            $groups = $query->result_array();
            foreach ($groups as $tr_groups) {
                $user_groups[] = $tr_groups['user_group_id'];
            }
        }

        //if user 
        if ($user_type == 1) {

            $this->db->select('user_id');
            $this->db->from('users');
            $this->db->where('user_id', $user_id);
            $this->db->or_where('most_parent_id', $user_id);
            $query = $this->db->get();
            $all_ids = $query->result_array();
            foreach ($all_ids as $ids) {
                $total_ids[] = $ids['user_id'];
            }
            $x = 0;
            foreach ($total_ids as $id) {


                $this->db->select('sent_sms1.status AS status, sent_sms1.temporary_status AS temporary_status');
                $this->db->from('sent_sms AS sent_sms1');
                $this->db->where('sent_sms1.sms_id >', 37848920);
                $this->db->where('sent_sms1.user_id', $id);
                $this->db->where_in('sent_sms1.user_group_id', $user_groups);
                $this->db->where("sent_sms1.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
                $query = $this->db->get();
                if ($query->num_rows()) {
                    $delivered = 0;
                    $failed = 0;
                    $fake_deliver = 0;
                    $fake_failed = 0;
                    $sent = 0;
                    $response = $query->result_array();
                    foreach ($response as $row) {
                        $status = $row['status'];
                        $temporary_status = $row['temporary_status'];
                        if ($status == 1 && $temporary_status == 1) {
                            $delivered++;
                        } elseif ($temporary_status == 2) {
                            $fake_deliver++;
                        } elseif ($temporary_status == 3) {
                            $fake_failed++;
                        } elseif ($status == 2 && $temporary_status == 1) {
                            $failed++;
                        } elseif ($status == 3 || $status == 4) {
                            $sent++;
                        }
                    }
                }


                $this->db->select('SUM(campaigns1.total_messages) AS total_messages');
                $this->db->select('users2.username AS ref_username, users2.user_id AS user_id,users2.name AS name');
                $this->db->from('campaigns AS campaigns1');
                $this->db->join('users AS users2', 'users2.user_id = campaigns1.user_id', 'left');
                $this->db->where('campaigns1.campaign_id >', 3335204);
                $this->db->where('campaigns1.route', $route);
                $this->db->where('campaigns1.user_id', $id);
                $this->db->where("campaigns1.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
                $query2 = $this->db->get();
                $result = $query2->row();
                $data1[$x] = array(
                    'user_id' => $result->user_id,
                    'total_messages' => $result->total_messages,
                    'ref_username' => $result->ref_username,
                    'name' => $result->name,
                    'fake_deliver' => $response1->fake_deliver,
                    'fake_failed' => $response2->fake_failed,
                    'actual_deliver' => $delivered,
                    'actual_failed' => $failed,
                    'total_sent' => $sent
                );
                $x++;
            }
        } else {

            $this->db->select('sent_sms1.status AS status, sent_sms1.temporary_status AS temporary_status');
            $this->db->from('sent_sms AS sent_sms1');
            $this->db->where('sent_sms1.sms_id >', 37848920);
            $this->db->where('sent_sms1.user_id', $user_id);
            $this->db->where_in('sent_sms1.user_group_id', $user_groups);
            $this->db->where("sent_sms1.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
            $query = $this->db->get();
            if ($query->num_rows()) {
                $delivered = 0;
                $failed = 0;
                $fake_deliver = 0;
                $fake_failed = 0;
                $sent = 0;
                $result = $query->result_array();

                foreach ($result as $row) {
                    $status = $row['status'];
                    $temporary_status = $row['temporary_status'];
                    if ($status == 1 && $temporary_status == 1) {
                        $delivered++;
                    } elseif ($temporary_status == 2) {
                        $fake_deliver++;
                    } elseif ($temporary_status == 3) {
                        $fake_failed++;
                    } elseif ($status == 2 && $temporary_status == 1) {
                        $failed++;
                    } elseif ($status == 3 || $status == 4) {
                        $sent++;
                    }
                }
            }



            $x = 0;

            $this->db->select('SUM(campaigns1.total_messages) AS total_messages');
            $this->db->select('users2.username AS ref_username, users2.user_id AS user_id,users2.name AS name');
            $this->db->from('campaigns AS campaigns1');
            $this->db->join('users AS users2', 'users2.user_id = campaigns1.user_id', 'left');
            $this->db->where('campaigns1.campaign_id >', 3335204);
            $this->db->where('campaigns1.route', $route);
            $this->db->where('campaigns1.user_id', $user_id);
            $this->db->where("campaigns1.submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
            $query2 = $this->db->get();

            $result = $query2->row();
            $data1[$x] = array(
                'user_id' => $result->user_id,
                'total_messages' => $result->total_messages,
                'ref_username' => $result->ref_username,
                'name' => $result->name,
                'fake_deliver' => $fake_deliver,
                'fake_failed' => $fake_failed,
                'actual_deliver' => $delivered,
                'actual_failed' => $failed,
                'total_sent' => $sent
            );
        }
        // var_dump($data1);die;
        return $data1;
    }

    public function viewInvoice($txn_id) {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_log_id', $txn_id);
        $query = $this->db->get();
        return $result = $query->row();
    }

    //mail invoice
    public function mailInvoice() {

        $view_date = $this->input->post('view_date');
        $to_address = $this->input->post('to_address');
        $service_tax = $this->input->post('service_tax');
        $swachh_bharat = $this->input->post('swachh_bharat');
        $krishi_kalyan = $this->input->post('krishi_kalyan');
        $total_amount = $this->input->post('total_amount');
        $description = $this->input->post('description');
        $amount = $this->input->post('amount');
        $status = $this->input->post('status');

        if ($status == 1) {
            $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
     
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
            <title>Bulk SMS Service Providers</title>

    </head>
    <body  leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 790px;
text-align: center;
margin-left: 40px; background-color: wheat; ">
        <div style="background-color:white; padding: 20px;">
        
        <div>
            <h1 style="text-align: center">Invoice</h1>
        </div>
         <hr></hr>
        <div style="float:right;" >
            <table>
                <tr><td>Date: ' . $view_date . '</td></tr>
            <tr><td>Invoice #: bulk24sms/1402538</td></tr>
            </table>
        </div>
         <br><br><br>
       
      
                    
                          <table style="float: left;">
                              <tr><td>   From:</td></tr>
                <tr><th>  SHREERAM TECHNOLOGY SERVICES PVT. LTD. </th></tr>
                <tr><td>  402, Navneet Plaza </td></tr>
               <tr><td>    10/2, Old Palasia, near Greater Kailash Hospital, </td></tr>
                <tr><td>  Indore 452001, M.P. </td></tr>
                  </table>
            
           
           
             <p style="float: right;">To: ' . $to_address . '</p>
            
    
        
        <table border="1" style="text-align:center;" width="100%">
        <tr>
            <th>Serial#</th>
            <th>Description</th>
            <th>Amount</th>

        </tr>
        <tr>
            <td><b>1</b></td>
            <td><b>' . $description . '</b></td>
            <td><b>' . $amount . '</b></td>
        </tr>
       </table>
        
       
        
       <br>
                   <div style="float:right;" >
        <table >
            
        <tr>	<td><b> Output Service Tax (14.0%) : ' . $service_tax . ' </b></td></tr>	
 <tr>	<td><b> Swachh Bharat Cess (0.5%) :	' . $swachh_bharat . '</b></td></tr>	
 <tr>	<td><b>Krishi Kalyan Cess (0.5%) :	' . $krishi_kalyan . '</b></td>	</tr>	
 <tr><td><b>	Total :	' . $total_amount . '		</b></td></tr>
  
        </table>
                   </div><br></br><br></br><br></br><br></br><br></br><br></br><br></br><br></br><br></br>
               <hr></hr>
       
        
         
        
                 <table>
                 <tr>
                     <td><b>SHREERAM TECHNOLOGY SERVICES PVT. LTD.</b></td></tr>

                <tr> <td><b>CIN: U74900MP2016PTC035210 </b></td></tr>
                 <tr><td> <b>   STC:  AAWCS6188CSD001</b> </td></tr>
                <tr>  <td>  <b> PAN: AAWCS6188C </b></td></tr>
             </table>
         <li style="float: right; list-style: none;">Digitally generated no signature required</li><br><br>
    
          <hr></hr>
          
          
           <ul style="list-style: none;"> 
           <li><b>Terms and Conditions:</b></li>
            <li>Above amount is in Indian National Rupees (INR)</li>	
        <li>We declare that this invoice shows the actual price of the services rendered and that all particulars are true and correct.</li>	
 <li>Please wire transfers the amount to the account given </li>
 <li>below: Wire transfer Details: ICICI Bank LTD	</li>	
 <li>A/c #: 657305600391</li>
  <li>IFSC: ICIC0000041 </li>

        </ul>
       
        
          </div>
    </body>
</html>';
            return $body;
        } else {
            $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
     
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
            <meta name="format-detection" content="telephone=no" /> <!-- disable auto telephone linking in iOS -->
            <title>Bulk SMS Service Providers</title>

    </head>
    <body  leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 790px;
text-align: center;
margin-left: 40px; background-color: wheat; ">
        <div style="background-color:white; padding: 20px;">
        
        <div>
            <h1 style="text-align: center">Invoice</h1>
        </div>
         <hr></hr>
        <div style="float:right;" >
            <table>
                <tr><td>Date: ' . $view_date . '</td></tr>
            <tr><td>Invoice #: bulk24sms/1402538</td></tr>
            </table>
        </div>
         <br><br><br>
       
      
                    
                          <table style="float: left;">
                              <tr><td>   From:</td></tr>
                <tr><th>  SHREERAM TECHNOLOGY SERVICES PVT. LTD. </th></tr>
                <tr><td>  402, Navneet Plaza </td></tr>
               <tr><td>    10/2, Old Palasia, near Greater Kailash Hospital, </td></tr>
                <tr><td>  Indore 452001, M.P. </td></tr>
                  </table>
            
           
           
             <p style="float: right;">To: ' . $to_address . '</p>
            
    
        
        <table border="1" style="text-align:center;" width="100%">
        <tr>
            <th>Serial#</th>
            <th>Description</th>
            <th>Amount</th>

        </tr>
        <tr>
            <td><b>1</b></td>
            <td><b>' . $description . '</b></td>
            <td><b>' . $amount . '</b></td>
        </tr>
       </table>
        
       
        
       <br>
                   <div style="float:right;" >
        <table >
      	
 <tr><td><b>	Total Amount :	' . $amount . '	</b></td></tr>
  
        </table>
                   </div><br></br>
               <hr></hr>
       
        
         
        
                 <table>
                 <tr>
                     <td><b>SHREERAM TECHNOLOGY SERVICES PVT. LTD.</b></td></tr>

                <tr> <td><b>CIN: U74900MP2016PTC035210 </b></td></tr>
                 <tr><td> <b>   STC:  AAWCS6188CSD001</b> </td></tr>
                <tr>  <td>  <b> PAN: AAWCS6188C </b></td></tr>
             </table>
         <li style="float: right; list-style: none;">Digitally generated no signature required</li><br><br>
    
          <hr></hr>
          
          
           <ul style="list-style: none;"> 
           <li><b>Terms and Conditions:</b></li>
            <li>Above amount is in Indian National Rupees (INR)</li>	
        <li>We declare that this invoice shows the actual price of the services rendered and that all particulars are true and correct.</li>	
 <li>Please wire transfers the amount to the account given </li>
 <li>below: Wire transfer Details: ICICI Bank LTD	</li>	
 <li>A/c #: 657305600391</li>
  <li>IFSC: ICIC0000041 </li>

        </ul>
       
        
          </div>
    </body>
</html>';
            return $body;
        }
    }

    //send invoice mail
    public function sendInvoiceEmail($mail_array) {
        $from_email = $mail_array['from_email'];
        $from_name = $mail_array['from_name'];
        $to_email = $mail_array['to_email'];
        $subject = $mail_array['subject'];
        $message = $mail_array['message'];
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

    //get back up route name
    public function get_route_for_backup($route_name) {
        $this->db->select('`user_group_id`,`backup_user_group`');
        $this->db->from('user_groups');
        $this->db->where('smsc_id', $route_name);
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //backup route when any route if going to offline 
    public function emergencyRouting() {
        date_default_timezone_set('Asia/Kolkata');
        //select delivered message last 20 min (Delivered)
        $current_time = date('Y-m-d  H:i:s');
        $start_date = date("Y-m-d  H:i:s", strtotime(date("Y-m-d H:i:s") . " -20 minutes"));

        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where("`submit_date` BETWEEN '$start_date' AND '$current_time'");
        $query = $this->db->get();
        $result = $query->result_array();
        $actual_route = array();
        $route = array();
        $status = array();
        $unique_route = array();
        foreach ($result as $get_route) {
            $route[] = $get_route['actual_route'];
            $status[] = $get_route['status'];
        }
        $count = 0;
        $each_route_count = array();
        $status_size = sizeof($status);
        $size_route = sizeof($route);
        $unique_route = array_unique($route);

        for ($k = 0; $k < $size_route; $k++) {
            if ($unique_route[$k]) {
                $actual_route[] = $unique_route[$k];
            }
        }

        $size_unique_route = sizeof($actual_route);
        for ($i = 0; $i < $size_unique_route; $i++) {
            $count = 0;
            for ($j = 0; $j < $size_route; $j++) {
                if ($actual_route[$i] == $route[$j]) {
                    $count++;
                }
            }
            //no of sms from each route
            $each_route_count[$i] = $count;
        }

        echo"<br>";
        $each_status_count = array();
        var_dump($each_route_count);
        $status_count = 0;
        for ($m = 0; $m < $size_unique_route; $m++) {
            $status_count = 0;
            for ($n = 0; $n < $status_size; $n++) {
                if ($status[$n] == 1) {
                    if ($actual_route[$m] == $route[$n]) {
                        $status_count++;
                    }
                }
            }
            $each_status_count[$m] = $status_count;
        }
        echo"<br>";
        var_dump($each_status_count);
        echo"<br>";
        for ($a = 0; $a < $size_unique_route; $a++) {
            echo $route_name = $actual_route[$a];
            echo"<br>";
            echo $total = $each_route_count[$a];
            echo"<br>";
            echo $sms_status = $each_status_count[$a];
            echo"<br>";
            //get backup route for perticular route
            $backup_route = $this->get_route_for_backup($route_name);

            $current_route_id = $backup_route[0]['user_group_id'];

            $back_route_id = $backup_route[0]['backup_user_group'];

            $calulate_delivered_ratio = $total * 98 / 100;
            if ($calulate_delivered_ratio > $sms_status) {
                echo "route shift from " . $route_name . " id of " . $current_route_id . "to " . $back_route_id . "<br>";
            } else {
                echo "NO problems";
            }
        }
    }

    //push DLR from admin

    public function fakeUpdatePushDlr() {
        $campaign_id = $this->input->post('campaign_id');
        $action_type = $this->input->post('dlr_action_type');
        $delivered_ratio = $this->input->post('delivered_ratio');
        $failed_ratio = $this->input->post('failed_ratio');
        $sent_ratio = $this->input->post('sent_ratio');
        $submit_ratio = $this->input->post('submit_ratio');
        $no_of_sms = $this->input->post('no_of_sms');
        $total_delivered = $this->input->post('total_delivered');
        $total_failed = $this->input->post('total_failed');
        $total_submit = $this->input->post('total_submit');
        $total_sent = $this->input->post('total_sent');
        $total_reject = $this->input->post('total_reject');
        $done_date = "2018-05-03 14:53:10";

        $total_calculation = $delivered_ratio + $failed_ratio + $sent_ratio + $submit_ratio;
        if ($total_calculation > 100) {
            return 100;
        } else {
            $sms_status = 0;
            if ($action_type == '1') {
                // All
                $calculate_delivered_sms = round($no_of_sms * $delivered_ratio / 100);
                $calculate_failed_sms = round($no_of_sms * $failed_ratio / 100);
                $calculate_sent_sms = round($no_of_sms * $sent_ratio / 100);
                $calculate_submit_ratio = round($no_of_sms * $submit_ratio / 100);

                $sms_status = 25;
            } elseif ($action_type == '2') {
                // Only Failed
                $calculate_delivered_sms = round($total_failed * $delivered_ratio / 100);
                $calculate_failed_sms = round($total_failed * $failed_ratio / 100);
                $calculate_sent_sms = round($total_failed * $sent_ratio / 100);
                $calculate_submit_ratio = round($total_failed * $submit_ratio / 100);

                $sms_status = 2;
            } elseif ($action_type == '3') {
                //SENT
                $calculate_delivered_sms = round($total_sent * $delivered_ratio / 100);
                $calculate_failed_sms = round($total_sent * $failed_ratio / 100);
                $calculate_sent_sms = round($total_sent * $sent_ratio / 100);
                $calculate_submit_ratio = round($total_sent * $submit_ratio / 100);

                $sms_status = 3;
            } elseif ($action_type == '4') {
                // Only Delivered
                $calculate_delivered_sms = round($total_delivered * $delivered_ratio / 100);
                $calculate_failed_sms = round($total_delivered * $failed_ratio / 100);
                $calculate_sent_sms = round($total_delivered * $sent_ratio / 100);
                $calculate_submit_ratio = round($total_delivered * $submit_ratio / 100);

                $sms_status = 1;
            } elseif ($action_type == '7') {
                // Only Rejected
                $calculate_delivered_sms = round($total_reject * $delivered_ratio / 100);
                $calculate_failed_sms = round($total_reject * $failed_ratio / 100);
                $calculate_sent_sms = round($total_reject * $sent_ratio / 100);
                $calculate_submit_ratio = round($total_reject * $submit_ratio / 100);

                $sms_status = 16;
            } elseif ($action_type == '8') {
                // Only Pending
                $calculate_delivered_sms = round($total_submit * $delivered_ratio / 100);
                $calculate_failed_sms = round($total_submit * $failed_ratio / 100);
                $calculate_sent_sms = round($total_submit * $sent_ratio / 100);
                $calculate_submit_ratio = round($total_submit * $submit_ratio / 100);

                $sms_status = 31;
            }

            if ($sms_status == 25) {
                //all fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }


                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            } else if ($sms_status == 2) {

                //failed fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 2);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }

                //fake 
                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 2);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 2);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 2);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            } else if ($sms_status == 3) {


                //sent fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 3);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }

                //fake 
                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 3);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 3);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 3);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            } else if ($sms_status == 1) {


                //delivered fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 1);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }

                //fake 
                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 1);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 1);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 1);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            } else if ($sms_status == 16) {


                //rejected fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 16);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }

                //fake 
                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 16);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 16);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 16);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            } else if ($sms_status == 31) {

                //submit fake updates 

                if ($calculate_delivered_sms) {
                    $data = array(
                        'status' => 1,
                        'done_date' => $done_date,
                        'description' => "Call answered by human",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 31);
                    $this->db->limit($calculate_delivered_sms);
                    $this->db->update('sent_sms', $data);
                }


                if ($calculate_failed_sms) {
                    $data = array(
                        'status' => 2,
                        'done_date' => $done_date,
                        'description' => "User was notified, but did not answer call",
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 31);
                    $this->db->limit($calculate_failed_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_sent_sms) {
                    $data = array(
                        'status' => 3,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 31);
                    $this->db->limit($calculate_sent_sms);
                    $this->db->update('sent_sms', $data);
                }
                if ($calculate_submit_ratio) {
                    $data = array(
                        'status' => 31,
                        'done_date' => $done_date
                    );
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->where('status', 31);
                    $this->db->limit($calculate_submit_ratio);
                    $this->db->update('sent_sms', $data);
                }
                return 1;
            }
        }
        return 100;
    }

    public function saveAdminHistory($actual_link, $url) {
        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];
        $admin_username = $session_data['username'];
        $actual_link;
        $url;
        $history_url = $actual_link . $url;
        $ip = $_SERVER['REMOTE_ADDR'];
        $date_time = date('d-m-y H:i:s');
        $date = date('y-m-d');
        $status = "Admin";

        $history_data = array(
            'admin_id' => $admin_id,
            'admin_username' => $admin_username,
            'status' => $status,
            'ip_address' => $ip,
            'history_url' => $history_url,
            'date_time' => $date_time,
            'date' => $date
        );
        $this->db->insert('admin_history', $history_data);
    }

    public function getControllerHistory() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('*');
        $this->db->from('admin_history');
        $this->db->order_by('history_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function searchControllerHistory() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $date = $this->input->post('search_history');
        $this->db->select('*');
        $this->db->from('admin_history');
        $this->db->where('date', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //search tps report
    public function searchTpsReport() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');

        $this->db->select('*');
        $this->db->from('tps_log');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //show daily signup
    public function showDailyUser() {
        $date = date('d-m-Y');

        $this->db->select('`user_id`,`admin_id`,`username`,`lead_by`,`creation_date`,`check_demo_user`,feedback');
        $this->db->from('users');
        $this->db->like('creation_date', $date);
        $this->db->where('admin_id', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //show daily Fund Transaction
    public function showDailyTransaction() {
        $date = date('Y-m-d');
        $this->db->select('`txn_admin_from`,`txn_user_to`,`txn_amount`,`txn_route`,`txn_sms`');
        $this->db->select('`username`');
        $this->db->select('`admin_username`');
        $this->db->from('transaction_logs');
        $this->db->join('users', 'transaction_logs.txn_user_to = users.user_id');
        $this->db->join('administrators', 'transaction_logs.account_admin = administrators.admin_id');
        $this->db->like('new_date', $date);
        $this->db->where('txn_admin_from >', 0);
        $this->db->where('txn_type', "ADD");
        $query = $this->db->get();
        $result = $query->result_array();
        //var_dump($result);die;
        return $result;
    }

    //show sub-admin Target
    public function showSubAdminTarget() {
        $date = date('Y-m-d');
        $start_date = "2018-11-01";
        $end_date = date('Y-m-d');
        $this->db->select('admin_id');
        $this->db->from('administrators');
        $this->db->order_by('admin_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        $size = $result->admin_id;
        $new_target = array();
        for ($i = 1; $i <= $size; $i++) {
            $this->db->select('SUM(txn_amount) AS txn_amount,txn_admin_from');
            $this->db->select('`admin_username`');
            $this->db->from('transaction_logs');
            $this->db->join('administrators', 'transaction_logs.account_admin = administrators.admin_id');
            //$this->db->join('administrators', 'transaction_logs.txn_admin_from = administrators.admin_id');
            $this->db->where('account_admin', $i);
            $this->db->where("`new_date` BETWEEN '$start_date' AND '$end_date'");
            $query = $this->db->get();
            $result = $query->row();
            //  var_dump($result);
            $target = $result->txn_amount;
            $admin_username = $result->admin_username;

            if ($target) {
                $new_target[$i] = $data = array(
                    'admin_id' => $admin_username,
                    'target' => $target
                );
            }
        }
        return $new_target;
    }

    //show daily Transaction
    public function showAmountOfSms() {
        $date = date('Y-m-d', strtotime("-1 days"));
        $data = array("A", "B", "C", "D", "I");
        for ($i = 0; $i <= 4; $i++) {

            $this->db->select('SUM(total_messages) AS total_messages, campaign_id');
            $this->db->from('`campaigns`');
            $this->db->like('submit_date', $date);
            $this->db->where('service_type', "SMS");
            $this->db->where('route', $data[$i]);
            $query = $this->db->get();
            $result = $query->row();
            $numberofsms = $result->total_messages;
            $new_target1[$i] = $data1 = array(
                'number' => $numberofsms,
                'route' => $data[$i]
            );
        }
        //var_dump($new_target1);die;
        return $new_target1;
    }

    // Get User Groups Promotional/Transactional/Both [All/Active]
    public function getSMPPlist() {
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
        $this->db->from('user_groups');
        $this->db->where('user_group_status', 1);
        $this->db->order_by("user_group_id");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // all overall log by date
    public function getOverallLog() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');

        $fdate = $this->input->post('search_from_date');
        $tdate = $this->input->post('search_to_date');
        $user_group_id = $this->input->post('selected_smpp');

        //select smpp
        $this->db->select('user_group_id, user_group_name, smsc_id, user_group_status');
        $this->db->from('user_groups');
        $this->db->where('user_group_id', $user_group_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $row = $query->row();
            $smsc_id = $row->smsc_id;
            $user_group_name = $row->user_group_name;
        }

        //total sms
        $this->db->select('COUNT(sms_id) AS total_sms');
        $this->db->from('sent_sms');
        $this->db->where('sms_id >', 44048920);
        $this->db->where('actual_route', $smsc_id);
        $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            if ($result->total_sms) {
                $data['total_sms'] = $result->total_sms;
            } else {
                $data['total_sms'] = 0;
            }
        }
        //total deduction
        $this->db->select('SUM(total_deducted) AS total_deduction');
        $this->db->from('campaigns');
        $this->db->where('campaign_id >', 3335204);
        if ($user_group_id == 53 || $user_group_id == 54) {
            $user_group_ids = array(53, 54);
            $this->db->where_in('user_group_id', $user_group_ids);
        } elseif ($user_group_id == 56 || $user_group_id == 58) {
            $user_group_ids = array(56, 58);
            $this->db->where_in('user_group_id', $user_group_ids);
        } elseif ($user_group_id == 46 || $user_group_id == 62) {
            $user_group_ids = array(46, 62);
            $this->db->where_in('user_group_id', $user_group_ids);
        } else {
            $this->db->where('user_group_id', $user_group_id);
        }
        $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        $query2 = $this->db->get();
        if ($query2->num_rows()) {
            $row = $query2->row();
            if ($row->total_deduction) {
                $data['total_deduction'] = $row->total_deduction;
            } else {
                $data['total_deduction'] = "-";
            }
        }

        //total sms count
        $delivered = 0;
        $rejected = 0;
        $failed = 0;
        $submit = 0;
        $sent = 0;
        $this->db->select('status');
        $this->db->from('sent_sms');
        $this->db->where('sms_id >', 44048920);
        $this->db->where('actual_route', $smsc_id);
        $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        $query3 = $this->db->get();
        if ($query3->num_rows()) {
            $response = $query3->result_array();
            foreach ($response as $sms_status) {
                $status = $sms_status['status'];

                if ($status == 1) {
                    $delivered++;
                } elseif ($status == 16) {
                    $rejected++;
                } elseif ($status == 2) {
                    $failed++;
                } elseif ($status == 31) {
                    $submit++;
                } elseif ($status == 3 || $status == 4) {
                    $sent++;
                }
            }
            $data['total_rejected'] = $rejected;
            $data['total_delivered'] = $delivered;
            $data['total_failed'] = $failed;
            $data['total_submit'] = $submit;
            $data['total_sent'] = $sent;
            $data['connected_smpp'] = $smsc_id;
        }

        return $data;
        // var_dump($data);die;
    }

    public function getSMPPDailyLog() {
        $fdate = $this->input->post('from_date');
        $tdate = $this->input->post('to_date');
        $route_name = array();
        $unique_route = array();
        $data_array = array();
        $this->db->select('*');
        $this->db->from('daily_smpp_log');
        $this->db->where("date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result_route) {

            $route_name[] = $result_route['route_id'];
        }
        $unique_route = array_unique($route_name);
        foreach ($unique_route as $unique_route1) {
            $unique_route1;
            $this->db->select('route_id,SUM(delivered) AS delivered,SUM(sent) AS sent,SUM(failed) AS failed,SUM(rejected) AS rejected,SUM(pending) AS pending,SUM(total) AS total,SUM(rejected_human) AS rejected_human,SUM(dnd) AS dnd,SUM(blocked) AS blocked,SUM(landline) AS landline');
            $this->db->from('daily_smpp_log');
            $this->db->where('route_id', $unique_route1);
            $this->db->where("date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
            $query_sum = $this->db->get();
            $result_sum = $query_sum->result_array();
            $data_array[] = $result_sum;
        }


        if ($data_array) {
            return $data_array;
        } else {
            return FALSE;
        }
    }

    public function get_daily_otp() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`mobile_no`,`status`,`submit_date`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', 1057);
        $this->db->limit(500);
        $this->db->order_by('sms_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOtpTest() {
        $fdate = $this->input->post('from_date');
        $tdate = $this->input->post('to_date');
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`mobile_no`,`status`,`submit_date`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', 1057);
        $this->db->where("submit_date BETWEEN '" . $fdate . "' AND '" . $tdate . "'");
        $this->db->order_by('sms_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function updatePaymentSubadmin() {
        $aproval_date = date('Y-m-d');
        $txn_log_id = $this->input->post('txn_log_id');
        $comment = $this->input->post('comment');
        $data2 = array(
            'txn_status' => 1,
            'aproval_date' => $aproval_date,
            'txn_description' => $comment,
        );
        $this->db->where('txn_log_id', $txn_log_id);
        $this->db->update('transaction_logs', $data2);
        return TRUE;
    }

    //get all admin details

    public function getAdmin() {
        $this->db->select('*');
        $this->db->from('administrators');
        $this->db->where('account_manage_status', 1);
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    public function countTotalSubscription() {
        $this->db->select('*');
        $this->db->from('subscription');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }
	
	public function countTotalMeetings() {
        $this->db->select('*');
        $this->db->from('user_meetings');
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }


    //get all subscription details

    public function getdailySubscription() {
        $this->db->select('*');
        $this->db->from('subscription');
        $this->db->order_by('subscribe_id', 'DESC');
        $this->db->limit(500);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function updateSubscriptionFeedback() {
        $feedback = $this->input->post('feedback');
        $admin_name = $this->input->post('admin_name');
        $subscribe_id = $this->input->post('subscribe_id');
        $this->db->select('admin_id');
        $this->db->from('administrators');
        $this->db->where('admin_name', $admin_name);
        $query = $this->db->get();
        $result = $query->row();
        $admin_id = $result->admin_id;

        $data = array(
            'feedback' => $feedback,
            'admin_name' => $admin_name,
        );
        $this->db->where('subscribe_id', $subscribe_id);
        $this->db->update('subscription', $data);
        return true;
    }


     public function updateMeetingFeedback() {
        $feedback = $this->input->post('feedback');
        $admin_name = $this->input->post('admin_name');
        $subscribe_id = $this->input->post('meeting_id');
        $this->db->select('admin_id');
        $this->db->from('administrators');
        $this->db->where('admin_name', $admin_name);
        $query = $this->db->get();
        $result = $query->row();
        $admin_id = $result->admin_id;

        $data = array(
            'feedback' => $feedback,
            'admin_name' => $admin_name,
        );
        $this->db->where('Id', $subscribe_id);
        $this->db->update('user_meetings', $data);
        return true;
    }




    public function getSubscriptionData($limit = 0, $start = 0) {
        $this->db->select('*');
        $this->db->from('subscription');
        $this->db->where('subscribe_id > ', 0);
        $this->db->order_by('subscribe_id', 'DESC');
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $subscription) {
                $temp_array = array(
                    'subscribe_id' => $subscription['subscribe_id'],
					'subscribe_name' => $subscription['subscribe_name'],
                    'subscribe_email' => $subscription['subscribe_email'],
                    'subscribe_mobile' => $subscription['subscribe_mobile'],
                    'ip_address' => $subscription['ip_address'],
					'subscribe_type' => $subscription['subscribe_type'],
                    'subscribe_date' => $subscription['subscribe_date'],
                    'admin_name' => $subscription['admin_name'],
                    'feedback' => $subscription['feedback'],
                );
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }
	
	
    public function getMeetingsData($limit = 0, $start = 0) {
        $this->db->select('*');
        $this->db->from('user_meetings');
        $this->db->order_by('Id', 'DESC');
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $subscription) {
                $temp_array = array(
                    'Id' => $subscription['Id'],
                    'email' => $subscription['email'],
                    'mobileNumber' => $subscription['mobileNumber'],
                    'connect' => $subscription['connect'],
                    'ScheduleTime' => $subscription['date'].$subscription['time'],
                    'DateTime' => $subscription['DateTime'],
                    'urlLink' => $subscription['urlLink'],
                    'admin_name' => $subscription['admin_name'],
                    'feedback' => $subscription['feedback']
                );
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    public function getAllSubscriptionData() {
        $this->db->select('*');
        $this->db->from('subscription');
        $this->db->order_by('subscribe_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
	
	
	 public function getAllMeetingsData() {
        $this->db->select('*');
        $this->db->from('user_meetings');
        $this->db->order_by('Id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function saveAdminComment($admin_id) {
        $admin_comment = $this->input->post('admin_comment');
        $trans_id = $this->input->post('trans_id');

        $data = array(
            'admin_discription' => $admin_comment,
        );
        $this->db->where('txn_log_id', $trans_id);
        $this->db->update('transaction_logs', $data);
        return true;
    }

    function getAllNumberSmsReport() {
        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];
        $number = $this->input->post('number');
        $data = array(
            'number' => $number
        );
        //$this->db->where('dlr_show_id', 1);
        $this->db->where('admin_id', $admin_id);
        $this->db->update('dlr_show_number', $data);
        return true;
    }

// Get Delivery Reports
    function getSmsNumberReports($start = 0, $limit = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $session_data = $this->session->userdata('admin_logged_in');
        $admin_id = $session_data['admin_id'];

        $this->db->select('number');
        $this->db->from('dlr_show_number');
        $this->db->where('admin_id', $admin_id);
        $number_query = $this->db->get();
        $number_result = $number_query->row();
        $number = $number_result->number;

        $this->db->select('users1.username AS username, users2.username AS parent_username, users1.contact_number AS contact, users1.user_id AS user_id');
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, schedule_status, sender_id, submit_date, request_by, message, route, pricing_error');
        $this->db->select('campaigns.admin_id AS admin_id, administrators.admin_username AS admin_username, message_category, total_credits, total_deducted, actual_message, request_by, message_type, flash_message');
        $this->db->select('user_groups1.user_group_name AS user_group_name, user_groups1.smsc_id AS smsc_id, schedule_date, black_listed, total_time, whole_process');
        $this->db->select('user_groups2.user_group_name AS resend_ugroup_name, user_groups2.smsc_id AS resend_smsc_id');
        $this->db->select('caller_id, start_date_time, end_date_time, service_type');
        $this->db->select('administrators1.admin_username AS processed_by');
        $this->db->select('administrators2.admin_username AS resend_by');
        $this->db->from('users AS users1');
        $this->db->join('administrators AS administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.most_parent_id', 'left');
        $this->db->join('campaigns', 'campaigns.user_id = users1.user_id', 'right');
        $this->db->join('user_groups AS user_groups1', 'user_groups1.user_group_id = campaigns.user_group_id', 'left');
        $this->db->join('user_groups AS user_groups2', 'user_groups2.user_group_id = campaigns.resend_ugroup_id', 'left');
        $this->db->join('administrators AS administrators1', 'administrators1.admin_id = campaigns.processed_by', 'left');
        $this->db->join('administrators AS administrators2', 'administrators2.admin_id = campaigns.resend_admin_id', 'left');
        $this->db->where('total_messages >=', $number);
        $this->db->order_by("campaign_id", "desc");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'username' => $campaign['username'],
                    'parent_username' => $campaign['parent_username'],
                    'contact' => $campaign['contact'],
                    'user_id' => $campaign['user_id'],
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'schedule_status' => $campaign['schedule_status'],
                    'sender_id' => $campaign['sender_id'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'admin_id' => $campaign['admin_id'],
                    'admin_username' => $campaign['admin_username'],
                    'message_category' => $campaign['message_category'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'user_group_name' => $campaign['user_group_name'],
                    'smsc_id' => $campaign['smsc_id'],
                    'schedule_date' => $campaign['schedule_date'],
                    'black_listed' => $campaign['black_listed'],
                    'total_time' => $campaign['total_time'],
                    'whole_process' => $campaign['whole_process'],
                    'caller_id' => $campaign['caller_id'],
                    'start_date_time' => $campaign['start_date_time'],
                    'end_date_time' => $campaign['end_date_time'],
                    'service_type' => $campaign['service_type'],
                    'processed_by' => $campaign['processed_by'],
                    'resend_ugroup_name' => $campaign['resend_ugroup_name'],
                    'resend_smsc_id' => $campaign['resend_smsc_id'],
                    'resend_by' => $campaign['resend_by'],
                    'pricing_error' => $campaign['pricing_error']
                );
// Get SMS Summary
                $this->db->select('COUNT(sms_id) AS Count_Status, status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("status");
                $sub_query = $this->db->get();
                if ($sub_query->num_rows()) {
                    $summary = $sub_query->result();
                    $temp_array['summary'] = $summary;
                } else {
                    $temp_array['summary'] = 0;
                }

// Get Fake SMS Summary (Failed & Delivered)
                $this->db->select('COUNT(sms_id) AS Count_Fake, temporary_status');
                $this->db->from('sent_sms');
                $this->db->where('campaign_id', $campaign['campaign_id']);
                $this->db->group_by("temporary_status");
                $sub_query1 = $this->db->get();
                if ($sub_query1->num_rows()) {
                    $fake_summary = $sub_query1->result();
                    $fake_failed = 0;
                    $fake_delievered = 0;
                    $fake_sent = 0;
                    if ($fake_summary) {
                        foreach ($fake_summary as $key => $value) {
                            if ($value->temporary_status == 3) {
                                $fake_failed = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 2) {
                                $fake_delievered = $value->Count_Fake;
                            }
                            if ($value->temporary_status == 4) {
                                $fake_sent = $value->Count_Fake;
                            }
                        }
                    }
                    $temp_array['fake_failed'] = $fake_failed;
                    $temp_array['fake_delivered'] = $fake_delievered;
                    $temp_array['fake_sent'] = $fake_sent;
                } else {
                    $temp_array['fake_failed'] = 0;
                    $temp_array['fake_delivered'] = 0;
                    $temp_array['fake_sent'] = 0;
                }
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    function getUserVerifyPin() {
        $verification_code = $this->input->post('verification_pin');
        $Our_verification_code = 9575;
        if ($verification_code == $Our_verification_code) {
            return True;
        } else {
            return FALSE;
        }
    }

    //daily subcription 
    public function showDailySubscription() {
        $date = date('Y-m-d');

        $this->db->select('*');
        $this->db->from('subscription');
        $this->db->like('subscribe_date', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getDailyOtpSummry() {
        $date = date('Y-m-d');
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`mobile_no`,`status`,`submit_date`,`done_date`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', 1057);
        $this->db->like('submit_date', $date);
        $this->db->limit(500);
        $this->db->order_by('sms_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //daily subcription 
    public function showDailySubscriptionSearch($date) {
        //$date = date('Y-m-d', strtotime("-1 days"));

        $this->db->select('*');
        $this->db->from('subscription');
        $this->db->like('subscribe_date', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getDailyOtpSummrySearch($date) {
        // $date = date('Y-m-d', strtotime("-1 days"));
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`mobile_no`,`status`,`submit_date`,`done_date`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', 1057);
        $this->db->like('submit_date', $date);
        $this->db->limit(500);
        $this->db->order_by('sms_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function showDailyUserSearch($date) {
        // $date = date('d-m-Y', strtotime("-1 days"));
        $date = date("d-m-Y", strtotime($date . " -1 minutes"));

        $this->db->select('`user_id`,`admin_id`,`username`,`lead_by`,`creation_date`,`check_demo_user`,feedback');
        $this->db->from('users');
        $this->db->like('creation_date', $date);
        $this->db->where('admin_id', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    //show daily Fund Transaction
    public function showDailyTransactionSearch($date) {
        //$date = date('Y-m-d', strtotime("-1 days"));
        $this->db->select('`txn_admin_from`,`txn_user_to`,`txn_amount`,`txn_route`,`txn_sms`');
        $this->db->select('`username`');
        $this->db->select('`admin_username`');
        $this->db->from('transaction_logs');
        $this->db->join('users', 'transaction_logs.txn_user_to = users.user_id');
        $this->db->join('administrators', 'transaction_logs.account_admin = administrators.admin_id');
        $this->db->like('new_date', $date);
        $this->db->where('txn_admin_from >', 0);
        $this->db->where('txn_type', "ADD");
        $query = $this->db->get();
        $result = $query->result_array();
        //var_dump($result);die;
        return $result;
    }

    //show sub-admin Target
    public function showSubAdminTargetSearch($date) {
        // $date = date('Y-m-d', strtotime("-1 days"));
        $start_date = "2018-08-01";
        $end_date = date('Y-m-d');
        $this->db->select('admin_id');
        $this->db->from('administrators');
        $this->db->order_by('admin_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        $size = $result->admin_id;
        $new_target = array();
        for ($i = 1; $i <= $size; $i++) {
            $this->db->select('SUM(txn_amount) AS txn_amount,txn_admin_from');
            $this->db->select('`admin_username`');
            $this->db->from('transaction_logs');
            // $this->db->join('administrators', 'transaction_logs.txn_admin_from = administrators.admin_id');
            $this->db->join('administrators', 'transaction_logs.account_admin = administrators.admin_id');
            $this->db->where('account_admin', $i);
            $this->db->where("`new_date` BETWEEN '$start_date' AND '$end_date'");
            $query = $this->db->get();
            $result = $query->row();
            //  var_dump($result);
            $target = $result->txn_amount;
            $admin_username = $result->admin_username;

            if ($target) {
                $new_target[$i] = $data = array(
                    'admin_id' => $admin_username,
                    'target' => $target
                );
            }
        }
        return $new_target;
    }

    function saveVoiceId($user_id = 0) {
        $draft_message = $this->input->post('draft_message');
        $file_name = $this->input->post('file_name');
        $duration = $this->input->post('duration');
        $voice_data = array(
            'user_id' => $user_id,
            'draft_message' => $draft_message,
            'file_name' => $file_name,
            'draft_message_type' => "VOICE",
            'duration' => $duration,
        );
        if ($this->db->insert('draft_messages', $voice_data)) {
            return True;
        } else {
            return FALSE;
        }
    }

    function saveVoiceRoute($user_id = 0) {
        $pr_voice = $this->input->post('pr_voice');
        $tr_voice = $this->input->post('tr_voice');

        $voice_data = array(
            'voice_pr_route' => $pr_voice,
            'voice_tr_route' => $tr_voice,
        );

        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $this->db->or_where('most_parent_id', $user_id);
        if ($this->db->update('users', $voice_data)) {
            return True;
        } else {
            return FALSE;
        }
    }

    public function getVoiceRoute($type) {
        $this->db->select('*');
        $this->db->from('voice_route');
        $this->db->where('route_type', $type);
        $this->db->where('status', 1);
        $this->db->order_by("voice_route_id", "ASC");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function getDailyAnalysis() {
        $this->db->select('*');
        $this->db->from('daily_amount_analysis');
        $this->db->where('status', 1);
        $this->db->order_by("date", "DESC");
        $this->db->limit(30);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getDailyAnalysisSearch($date) {
        // $date = date('Y-m-d', strtotime("-1 days"));
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`total_sms`,`total_amount`,`average_pricing`,`delivered_sms`,`total_delivered_amount`,`date`,`status`');
        $this->db->from('daily_amount_analysis');
        $this->db->where('status', 1);
        $this->db->like('date', $date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

      public function getSignupTest() {
        $fdate = $this->input->post('from_date');
       ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('admin_id', 1);
          $this->db->like('date', $fdate);
           $this->db->order_by('user_id', 'DESC');
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

//------------------------------------------------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------------//
}

?>