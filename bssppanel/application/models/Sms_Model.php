<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Sms_Model extends CI_Model {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Default Settings
    function getDefaultSettings() {
        $this->db->select('`setting_id`, `sms_limit`, `pr_sender_id_check`, `pr_sender_id_type`, `pr_sender_id_length`, `pr_dnd_check`, `tr_sender_id_check`');
        $this->db->select('`tr_sender_id_type`, `tr_sender_id_length`, `tr_keyword_check`');
        $this->db->select('`demo_balance`, `expiry_days`, `signup_sender`, `signup_message`, `signup_subject`');
        $this->db->select('`signup_body`, `forgot_password_sender`, `forgot_password_message`');
        $this->db->select('`location_sender`, `location_message`, `demo_sender`, `demo_message`');
        $this->db->select('`xml_route_authkey`, `xml_route_url`');
        $this->db->from('settings');
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get User Rule Settings
    function getUserSettings($user_id = 0) {
        $this->db->select('`user_id`, `admin_id`, `ref_user_id`, `most_parent_id`, `name`, `email_address`, `contact_number`, `date_of_birth`');
        $this->db->select('`address`, `city`, `country`, `zipcode`, `company_name`, `username`, `password`, `auth_key`, `utype`, `pr_sms_balance`');
        $this->db->select('`prtodnd_balance`,`stock_balance`,`international_balance`,`prtodnd_credits`,`stock_credits`,`prtodnd_route`,`stock_route`,`international_route`');
        $this->db->select('`tr_sms_balance`, `pro_user_group_id`, `tr_user_group_id`, `number_allowed`, `p_sender_id_option`, `p_sender_id_type`');
        $this->db->select('`p_sender_id_length`, `dnd_check`, `t_sender_id_option`, users.`sender_id_type`, users.`sender_id_length`, `keyword_option`');
        $this->db->select('`expiry_date`, `creation_date`, `last_login_date`, `default_route`, `default_sender_id`, `industry`, `default_timezone`');
        $this->db->select('`check_signature`, `signature`, `user_ratio`, `user_fake_ratio`, `user_fail_ratio`, `user_settings`, `user_status`, `check_demo_user`');
        $this->db->select('`pr_user_ratio`, `pr_user_fake_ratio`, `pr_user_fail_ratio`,`pr_fake_sent`,`tr_fake_sent`,`stock_dnd_check`,`premium_dnd_check`,`pricing_approval`,`voice_tr_route`, `voice_pr_route`');
        $this->db->select('`demo_sender`, `demo_message`');
        $this->db->select('pr_user_groups.`smsc_id` AS pr_smsc, tr_user_groups.`smsc_id` AS tr_smsc');
        $this->db->select('prtodnd_user_groups.`smsc_id` AS prtodnd_smsc, stock_user_groups.`smsc_id` AS stock_smsc,international_user_groups.`smsc_id` AS international_smsc');
        $this->db->select('pr_voice_balance, tr_voice_balance');
        $this->db->select('default_voice_route, vtr_fake_ratio, vtr_fail_ratio, vpr_fake_ratio, vpr_fail_ratio,fix_sender_id');
        $this->db->select('`spacial_fake_pr_ratio`, `spacial_deliver_pr_ratio`, `spacial_fake_tr_ratio`, `spacial_deliver_tr_ratio`');
        $this->db->from('users');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users.pro_user_group_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users.tr_user_group_id', 'left');
        $this->db->join('user_groups AS prtodnd_user_groups', 'prtodnd_user_groups.user_group_id = users.prtodnd_route', 'left');
        $this->db->join('user_groups AS stock_user_groups', 'stock_user_groups.user_group_id = users.stock_route', 'left');
        $this->db->join('user_groups AS international_user_groups', 'international_user_groups.user_group_id = users.international_route', 'left');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    function getUserSettingsTest($user_id) {
        $this->db->select('`user_id`, `admin_id`, `ref_user_id`, `most_parent_id`, `name`, `email_address`, `contact_number`, `date_of_birth`');
        $this->db->select('`address`, `city`, `country`, `zipcode`, `company_name`, `username`, `password`, `auth_key`, `utype`, `pr_sms_balance`');
        $this->db->select('`tr_sms_balance`, `pro_user_group_id`, `tr_user_group_id`, `number_allowed`, `p_sender_id_option`, `p_sender_id_type`');
        $this->db->select('`p_sender_id_length`, `dnd_check`, `t_sender_id_option`, users.`sender_id_type`, users.`sender_id_length`, `keyword_option`');
        $this->db->select('`expiry_date`, `creation_date`, `last_login_date`, `default_route`, `default_sender_id`, `industry`, `default_timezone`');
        $this->db->select('`check_signature`, `signature`, `user_ratio`, `user_fake_ratio`, `user_fail_ratio`, `user_settings`, `user_status`, `check_demo_user`');
        $this->db->select('`pr_user_ratio`, `pr_user_fake_ratio`, `pr_user_fail_ratio`');
        $this->db->select('`demo_sender`, `demo_message`');
        $this->db->select('pr_user_groups.`smsc_id` AS pr_smsc, tr_user_groups.`smsc_id` AS tr_smsc');
        $this->db->select('pr_voice_balance, tr_voice_balance');
        $this->db->select('default_voice_route, vtr_fake_ratio, vtr_fail_ratio, vpr_fake_ratio, vpr_fail_ratio');
        $this->db->from('users');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users.pro_user_group_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users.tr_user_group_id', 'left');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get White List Numbers
    function getWhiteLists() {
        $this->db->select('`white_list_number`');
        $this->db->from('`white_lists`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_white_list = $query->result_array();
            $white_list_array = array();
            foreach ($result_white_list as $row_white) {
                if (strlen($row_white['white_list_number']) == 10)
                    $white_list_array[] = "91" . $row_white['white_list_number'];
                elseif (strlen($row_white['white_list_number']) == 12)
                    $white_list_array[] = $row_white['white_list_number'];
            }
            if (sizeof($white_list_array))
                return $white_list_array;
            else
                return false;
        } else {
            return false;
        }
    }

    // Get Black List Numbers
    function getBlackLists() {
        $this->db->select('`black_list_number`');
        $this->db->from('`black_lists`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_black_list = $query->result_array();
            $black_list_array = array();
            foreach ($result_black_list as $row_black) {
                if (strlen($row_black['black_list_number']) == 10)
                    $black_list_array[] = "91" . $row_black['black_list_number'];
                elseif (strlen($row_black['black_list_number']) == 12)
                    $black_list_array[] = $row_black['black_list_number'];
            }
            if (sizeof($black_list_array))
                return $black_list_array;
            else
                return false;
        } else {
            return false;
        }
    }

    // Get Black List Sender Ids
    function checkBlackSenderIds($sender = null) {
        $this->db->select('`black_sender_ids`');
        $this->db->from('`black_sender_ids`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_black_list = $query->row();
            $black_senders_array = explode(',', $result_black_list->black_sender_ids);
            if (in_array($sender, $black_senders_array)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    // Get Black Listed Keywords
    function getBlackKeywords() {
        $this->db->select('`black_keyword`');
        $this->db->from('`black_keywords`');
        $this->db->where('`black_keyword_status`', '1');
        $this->db->where('admin_id !=', 0);
        $this->db->where('user_id', 0);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Check Black Listed Keywords
    function checkBlackKeywords($admin_id = 0, $user_id = 0, $reseller_id = 0, $utype = null, $message = null) {
        $this->db->select('`black_keyword`');
        $this->db->from('`black_keywords`');
        $this->db->where('`black_keyword_status`', '1');
        // If User Under Admin
        if ($utype == 'User' && $admin_id && !$reseller_id) {
            $this->db->where('`user_id`', $user_id);
        }
        // If Reseller Under Admin
        if ($utype == 'Reseller' && $admin_id && !$reseller_id) {
            $this->db->where('`user_id`', $user_id);
        }
        // If User Under Reseller
        if ($utype == 'User' && !$admin_id && $reseller_id) {
            $this->db->where("(user_id=$user_id OR user_id=$reseller_id)");
        }
        // If Reseller Under Reseller
        if ($utype == 'Reseller' && !$admin_id && $reseller_id) {
            $this->db->where("(user_id=$user_id OR user_id=$reseller_id)");
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result_black_keywords = $query->result_array();
            // Get Black Listed Keywrods
            $black_keyword_array = array();
            if ($result_black_keywords) {
                foreach ($result_black_keywords as $row_bkeyword) {
                    $bkeyword = preg_replace('/\s+/', ' ', urldecode($row_bkeyword['black_keyword']));
                    $bkeyword_array = explode(' ', $bkeyword);
                    $black_keyword_array = array_merge($black_keyword_array, $bkeyword_array);
                }
            }
            $black_keyword_array = array_map('strtolower', $black_keyword_array);
            $black_keyword_array = array_filter($black_keyword_array);
            $message_array = explode(' ', preg_replace('/\s+/', ' ', $message));
            $message_array = array_map('strtolower', $message_array);
            $result_message_array = array_intersect($black_keyword_array, $message_array);
            if ($result_message_array && sizeof($result_message_array)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get DND Numbers
    function getDNDNumbers($result_array) {

        $arranged_array = array();

        foreach ($result_array as $mobile) {

            $arreng_number[] = substr($mobile, 2, 11);
        }
        $admin_db = $this->load->database('another', TRUE);
        $admin_db->select('Phone_Numbers');
        $admin_db->where_in('Phone_Numbers', $arreng_number);
        $query = $admin_db->get('sqlbox_send_sms1');
        $result_data = $query->result_array();
        $user_dnd_array = array();
        foreach ($result_data as $key => $value) {
            $user_dnd_array[] = $value['Phone_Numbers'];
        }

        foreach ($user_dnd_array as $phone) {
            if (strlen($phone) == 10) {
                $new_array[] = "91" . $phone;
            }
        }

        //$user_dnd_array = array("9999900001", "918432608955", "918432276615", "9999900004", "9999900005", "9999900006", "9999900007", "9999900008", "9999900009", "9999900010");
        // DND Numbers
        $dnd_result = array_intersect($result_array, $new_array);


        if (sizeof($dnd_result))
            return $dnd_result;
        else
            return false;
    }

    // Get User Groups (Promotional/Transactional)
    function getUserGroup($user_group_id = 0) {
        $this->db->select('user_group_name, user_group_username, user_group_password, smsc_id');
        $this->db->from('user_groups');
        $this->db->where('user_group_id', $user_group_id);
        $this->db->where('user_group_status', '1');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Insert SMS Logs (Error/Reason)
    function insertSMSLog($user_id = 0, $reason = null, $log_by = null, $mobile_data = null, $message_data = null) {
        $log_time = date("Y-m-d H:i:s");
        $data = array(
            'user_id' => $user_id,
            'sms_log_reason' => $reason,
            'sms_log_time' => $log_time,
            'sms_log_by' => $log_by,
            'mobile' => $mobile_data,
            'message' => $message_data
        );
        return $this->db->insert('sms_logs', $data);
    }

    //

    function varifySpecialReseller($user_id) {
        $this->db->select('spacial_reseller_status,most_parent_id');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    function varifyMostParentId($mostParentID) {
        $this->db->select('spacial_reseller_status');
        $this->db->from('users');
        $this->db->where('user_id', $mostParentID);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    function checkSpecialPrBalance($user_id) {
        $this->db->select('special_pr_balance,prtodnd_credits,stock_credits');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $special_pr_balance = $query->row();
        } else {
            return false;
        }
    }

    function checkSpecialTrBalance($user_id) {
        $this->db->select('special_tr_balance');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $special_pr_balance = $query->row('special_tr_balance');
        } else {
            return false;
        }
    }

    // Get Users Sender Ids
    function getUserSenderIds($user_id = 0) {
        $this->db->select('sender_ids, sender_status');
        $this->db->from('sender_ids');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get User Keywords
    function getUserKeywords($user_id = 0) {
        $this->db->select('keywords, percent_ratio_user');
        $this->db->from('keywords');
        $this->db->where('keyword_status', '1');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows())
            return $query->result();
        else
            return false;
    }

    // Get All Users Keywords
    function getAllUserKeywords() {
        $this->db->select('keywords, percent_ratio_all_users');
        $this->db->from('keywords');
        $this->db->where('keyword_status', '1');
        $query = $this->db->get();
        if ($query->num_rows())
            return $query->result();
        else
            return false;
    }

    // Get User Unique Numbers
    function getUniqueNumbers($user_id = 0) {
        $this->db->distinct();
        $this->db->select('mobile_no');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            //return $query->num_rows();
            return $query->result_array();
        } else
            return false;
    }

    // Get User Unique Numbers
    function getUniqueNumbers1($user_id = 0) {
        //$this->db->distinct();
        $this->db->select('COUNT(DISTINCT(`mobile_no`)) AS unique_numbers');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $row = $query->row();
            return $row->unique_numbers;
        } else {
            return false;
        }
    }

    // Insert Bulk SMS Campaign
    function insertCampaign($data_campaign = array()) {
        return $this->db->insert('campaigns', $data_campaign);
    }

    // Insert Voice SMS Campaign
    function insertVoiceCampaign($data_campaign = array()) {
        return $this->db->insert('campaigns', $data_campaign);
    }

    // Get Last Campaign Id
    function getLastCampaign($user_id = 0, $campaign_uid = null) {
        $this->db->select('campaign_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('campaign_uid', $campaign_uid);
        $this->db->order_by('campaign_id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Update SMS Balance
    /*
      function updateBalance($user_id, $updated_sms_balance, $balance_type) {

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
      // Promotional Voice SMS
      if ($balance_type == "VPR") {
      $data = array(
      'pr_voice_balance' => $updated_sms_balance
      );
      $this->db->where('user_id', $user_id);
      return $this->db->update('users', $data);
      }
      // Transactional Voice SMS
      if ($balance_type == "VTR") {

      $data = array(
      'tr_voice_balance' => $updated_sms_balance
      );
      $this->db->where('user_id', $user_id);
      return $this->db->update('users', $data);
      }
      }
     */
    function updateBalance($user_id, $mostParentID, $updated_sms_balance, $balance_type, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance, $route) {
        // Promotional SMS

        if ($balance_type == "PR") {
            if ($spacial_reseller_status == 1 || $most_parent_id_reseller_status == 1) {
                if ($spacial_reseller_status == 1) {

                    if ($route == 'A') {
                        $data = array(
                            'special_pr_balance' => $updated_key_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data);
                    } elseif ($route == 'C') {
                        $data = array(
                            'stock_credits' => $updated_key_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data);
                    } elseif ($route == 'D') {
                        $data = array(
                            'prtodnd_credits' => $updated_key_balance
                        );
                        $this->db->where('user_id', $user_id);
                        $this->db->update('users', $data);
                    }
                } else {

                    if ($route == 'A') {
                        $data = array(
                            'special_pr_balance' => $updated_key_balance
                        );
                        $this->db->where('user_id', $mostParentID);
                        $this->db->update('users', $data);
                    } elseif ($route == 'C') {
                        $data = array(
                            'stock_credits' => $updated_key_balance
                        );
                        $this->db->where('user_id', $mostParentID);
                        $this->db->update('users', $data);
                    } elseif ($route == 'D') {
                        $data = array(
                            'prtodnd_credits' => $updated_key_balance
                        );
                        $this->db->where('user_id', $mostParentID);
                        $this->db->update('users', $data);
                    }

                    /*  $data = array(
                      'special_pr_balance' => $updated_key_balance
                      );
                      $this->db->where('user_id', $mostParentID);
                      $this->db->update('users', $data); */
                }
            }
            // $route = 'A';
            if ($route == 'A') {
                $data = array(
                    'pr_sms_balance' => $updated_sms_balance
                );
                $this->db->where('user_id', $user_id);
                return $this->db->update('users', $data);
            } elseif ($route == 'C') {
                $data = array(
                    'stock_balance' => $updated_sms_balance
                );
                $this->db->where('user_id', $user_id);
                return $this->db->update('users', $data);
            } elseif ($route == 'D') {
                $data = array(
                    'prtodnd_balance' => $updated_sms_balance
                );
                $this->db->where('user_id', $user_id);
                return $this->db->update('users', $data);
            } elseif ($route == 'I') {
                $data = array(
                    'international_balance' => $updated_sms_balance
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);
            }
        }
        // Transactional SMS
        if ($balance_type == "TR") {
            if ($spacial_reseller_status == 1 || $most_parent_id_reseller_status == 1) {
                if ($spacial_reseller_status == 1) {
                    $data = array(
                        'special_tr_balance' => $updated_key_balance
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                } else {
                    $data = array(
                        'special_tr_balance' => $updated_key_balance
                    );
                    $this->db->where('user_id', $mostParentID);
                    $this->db->update('users', $data);
                }
            }

            $data = array(
                'tr_sms_balance' => $updated_sms_balance
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        }
        // Promotional Voice SMS
        if ($balance_type == "VPR") {
            $data = array(
                'pr_voice_balance' => $updated_sms_balance
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        }
        // Transactional Voice SMS
        if ($balance_type == "VTR") {
            $data = array(
                'tr_voice_balance' => $updated_sms_balance
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        }
    }

    // Get Random Number
    function getRandomArray($result_array = array(), $ratio = 0) {
        $rand_keys = array_rand($result_array, $ratio);
        $result = array();
        if ($ratio > 1) {
            for ($b = 0; $b < $ratio; $b++) {
                $result[] = $result_array[$rand_keys[$b]];
            }
        } else {
            $result[] = $result_array[$rand_keys];
        }
        return $result;
    }

    // Insert Schedule SMS
    function insertSchSMS($sch_sms_array = array()) {
        // Schedule Array
        return $this->db->insert('campaigns', $sch_sms_array);
    }

    // Check Transaction Conditions
    function checkTRConditions() {
        $dnd_array = array();
        if (sizeof($dnd_array))
            return $dnd_array;
        else
            return false;
    }

    // Get Group Contacts
    function getGroupsContacts($user_id = 0) {
        $this->db->select('`contact_id`, `mobile_number`, `contacts`.`contact_name`, `contact_group_ids`');
        $this->db->from('`contacts`, `users`');
        $this->db->where('`contacts`.`user_id`=`users`.`user_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Check Sender Id Type & Length
    function checkSenderIdType($sender_id_type = null, $sender_id_length = 0, $from = null) {
        $check_sender = 0;
        // Check Sender Type
        switch ($sender_id_type) {
            case 'Numeric':
                $check_sender = 0;
                break;
            case 'Alphabetic':
                $check_sender = 0;
                break;
            case 'Alphanumeric':
                $check_sender = 0;
                break;
            default:
                $check_sender = 1;
                break;
        }
        // Check Sender Length
        if (!$check_sender) {
            if (strlen($from) != $sender_id_length) {
                $check_sender = 1;
            }
        }
        return $check_sender;
    }

    function checkApproveSenderId($sender_id_type = null, $sender_id_length = 0, $from = null) {

        $this->db->select('*');
        $this->db->from('approve_sender_id');
        $this->db->where('sender_id', $from);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return 0;
        }
    }

    //check pr approve sender id
    function prApproveSenderId($sender_id_type = null, $sender_id_length = 0, $from = null) {

        $this->db->select('*');
        $this->db->from('pr_approve_sender_id');
        $this->db->where('sender_id', $from);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return 0;
        }
    }

    // Check Keyword Conditions 
    function checkKeyword($user_id = 0, $message = null) {
        $array_message = array_map('strtolower', explode(' ', urldecode($message)));
        // Get Keywords (User Wise)
        $keyword_ratio_array = array();
        $match_ratio_array = array();
        $keyword_status = 0;
        $result_user_keywords = $this->sms_model->getUserKeywords($user_id);
        if ($result_user_keywords) {
            $kk = 0;
            foreach ($result_user_keywords as $row_user_keyword) {
                $keyword = urldecode($row_user_keyword->keywords);
                $keyword_ratio = $row_user_keyword->percent_ratio_user;
                $array_keyword = array_map('strtolower', explode(' ', $keyword));
                $array_intersect = array_intersect($array_keyword, $array_message);
                // Get Matching Ratio
                $match_percent = (count($array_intersect) * 100 / count($array_keyword));
                //similar_text($keyword, urldecode($message), $match_percent);
                $match_ratio_array[$kk] = $match_percent;
                if ($keyword_ratio <= $match_percent) {
                    $keyword_status++;
                }
                $kk++;
            }
        }
        // Get Keywords (All Users)
        if ($keyword_status == 0) {
            $result_keywords = $this->sms_model->getAllUserKeywords();
            if ($result_keywords) {
                $kk = 0;
                foreach ($result_keywords as $row_keyword) {
                    $keyword = urldecode($row_keyword->keywords);
                    $keyword_ratio = $row_keyword->percent_ratio_all_users;
                    // Get Matching Ratio
                    $array_keyword = array_map('strtolower', explode(' ', $keyword));
                    $array_intersect = array_intersect($array_keyword, $array_message);
                    $match_percent = (count($array_intersect) * 100 / count($array_keyword));
                    //similar_text($keyword, urldecode($message), $match_percent);
                    $match_ratio_array[$kk] = $match_percent;
                    if ($keyword_ratio <= $match_percent) {
                        $keyword_status++;
                    }
                    $kk++;
                }
            }
        }
        return $keyword_status;
    }

    // Check Sender Id Conditions
    function checkSenderId($user_id = 0, $from = null) {
        // Get Sender Ids
        $sender_array = array();
        $sender_status_array = array();
        $result_sender_ids = $this->sms_model->getUserSenderIds($user_id);
        if ($result_sender_ids) {
            $sender_array = explode(',', $result_sender_ids->sender_ids);
            $sender_status_array = explode(',', $result_sender_ids->sender_status);
        }
        // Check Sender Id
        $check_sender = 0;
        if (in_array($from, $sender_array)) {
            $match_sender_key = array_search($from, $sender_array);
            if ($sender_status_array[$match_sender_key] == '1') {
                $check_sender = 1;
            }
        }
        return $check_sender;
    }

    // Export All Reports
    function getAllDeliveryReports($user_id = 0, $from = null, $to = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->db->select('`campaign_name`, `sender_id`, `sent_sms`.`message` AS `message`');
        $this->db->select('`mobile_no`, `status`, `sent_sms`.`submit_date` AS `submit_date`, `done_date`');
        $this->db->from('`campaigns`, `sent_sms`');
        $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
        $this->db->where('`campaigns`.`user_id`', $user_id);
        $this->db->where("`sent_sms`.`submit_date` BETWEEN '" . $from . "' AND '" . $to . "'");
        //$this->db->like('`sent_sms`.`submit_date`', $from);
        //$this->db->like('`sent_sms`.`done_date`', $to);
        $this->db->order_by('`campaigns`.`campaign_id`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Scheduled SMS
    function getScheduledSMS($current_time = null) {
        $this->db->select('`campaign_id`, `sender_id`, `user_id`, `schedule_date`, `message_type`, `flash_message`, `route`, `actual_message`, `message`');
        $this->db->select('`sender_status`, `keyword_status`, `number_db_status`, `condition_status`, `is_xml`, `campaign_name`,`reseller_key_balance_status`');
        $this->db->from('`campaigns`');
        $this->db->where('`message_category`', 2);
        $this->db->where('`schedule_status`', 1);
        $this->db->where('`campaign_status`', 1);
        $this->db->where('`schedule_date`', $current_time);
        $this->db->order_by("`campaign_id`");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Sent SMS
    function getSentSMS($campaign_id = 0) {
        $this->db->select('`msg_id`, `mobile_no`, `message`');
        $this->db->from('`sent_sms`');
        $this->db->where('`temporary_status`', 1);
        $this->db->where('`status` != ', "DND");
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Get Re-Send SMS
    function getResendSMS($campaign_id = 0, $sms_status = 0, $temporary_status = 0) {
        $this->db->select('`sender_id`, `route`, `msg_id`, `mobile_no`, `message_type`, `flash_message`, `sent_sms`.`message` AS `message`');
        $this->db->from('`sent_sms`, `campaigns`');
        $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
        $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
        if (sizeof($sms_status)) {
            $this->db->where_in('status', $sms_status);
            $this->db->where('temporary_status', $temporary_status);
        } elseif ($temporary_status) {
            $this->db->where('temporary_status', $temporary_status);
        }
        $this->db->order_by("`sms_id`");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Get Campaign Status
    function getCampaignStatus($campaign_id = 0) {
        $this->db->select('`campaign_status`, `total_messages`');
        $this->db->from('`campaigns`');
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Caluclate Message Credits
    function getSMSCredits($message_type = 0, $length = 0, $message = null) {
        $charset = "ASCII";
        $coding = 0;
        $total_credits = 0;
        $unicode = 0;
        if ($message_type == 2) {
            $charset = "UTF-8";
            $coding = 2;
            // Total Credits
            if ($length % 70 == 0) {
                $total_credits = intval($length / 70);
            } else {
                $total_credits = intval($length / 70) + 1;
            }
            $unicode = 1;
        } elseif ($message_type == 1) {
            $message = urldecode($message);
            //if (preg_match('/[#@_$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/', $message)) {
            if (preg_match('/[!#@$%&^*]/', $message)) {
                $charset = "UTF-8";
                $coding = 0;
            } else {
                $charset = "UTF-8";
                $coding = 0;
            }
            // Total Credits
            if ($length) {
                if ($length % 160 == 0) {
                    $total_credits = intval($length / 160);
                } else {
                    $total_credits = intval($length / 160) + 1;
                }
            }
        }
        return array('charset' => $charset, 'coding' => $coding, 'credits' => $total_credits, 'unicode' => $unicode);
    }

    // Get Duraion of Any Audio FIle
    function getDuration($file = null) {
        $fp = fopen($file, 'r');
        $size_in_bytes = filesize($file);
        fseek($fp, 20);
        $rawheader = fread($fp, 16);
        $header = unpack('vtype/vchannels/Vsamplerate/Vbytespersec/valignment/vbits', $rawheader);
        $sec = ceil($size_in_bytes / $header['bytespersec']);
        return $sec;
    }

    // Get Voice SMS For Updting DLR Status
    function getVoiceSMS() {
        $array = array('PENDING', '31');
        $this->db->select('`campaign_id`, `user_id`, msg_id, mobile_no, ttsCallRequestId');
        $this->db->from('`sent_sms`');
        $this->db->where("`user_group_id`", 0);
        $this->db->where('`status`', 31);
        $this->db->where('`voice_process_status`', 1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Insert API Hit
    function insertAPIHit($user_id = 0, $client_ip_address = null) {
        $api_hit_date = date("Y-m-d H:i:s");
        // Delete Previous IP Address Of User
        $array = array(
            'user_id' => $user_id,
            'client_ip_address' => $client_ip_address
        );
        $this->db->delete('api_hits', $array);
        // Insert New Record
        $data_array = array(
            'user_id' => $user_id,
            'server_ip_address' => $_SERVER['SERVER_ADDR'],
            'client_ip_address' => $client_ip_address,
            'api_hit_date' => $api_hit_date
        );
        $this->db->insert('api_hits', $data_array);
        return true;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Function to get the client ip address
    function get_client_ip_server() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    // Function to get the client ip address
    function get_ip() {
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
        ) {
            $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
        } else {

            $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        }
        return $the_ip;
    }

    /**
     * Retrieves the best guess of the client's actual IP address.
     * Takes into account numerous HTTP proxy headers due to variations
     * in how different ISPs handle IP addresses in headers between hops.
     */
    function get_ip_address() {
        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (validate_ip($ip))
                        return $ip;
                }
            } else {
                if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];
        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    function validate_ip($ip) {
        if (strtolower($ip) === 'unknown')
            return false;
        // generate ipv4 network address
        $ip = ip2long($ip);
        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647)
                return false;
            if ($ip >= 167772160 && $ip <= 184549375)
                return false;
            if ($ip >= 2130706432 && $ip <= 2147483647)
                return false;
            if ($ip >= 2851995648 && $ip <= 2852061183)
                return false;
            if ($ip >= 2886729728 && $ip <= 2887778303)
                return false;
            if ($ip >= 3221225984 && $ip <= 3221226239)
                return false;
            if ($ip >= 3232235520 && $ip <= 3232301055)
                return false;
            if ($ip >= 4294967040)
                return false;
        }
        return true;
    }

    // Google URL Shortner
    function googleUrlShortner($url = null) {
        $this->load->library('google_url_api');
        $short_url = $this->google_url_api->shorten($url);
        return $short_url;
    }

    // Get Flash Message
    function getFlashMessage($flash_message = 0) {
        $mclass = null;
        $alt_dcs = 1;
        if ($flash_message) {
            $mclass = 0;
            $alt_dcs = 1;
        } else {
            $mclass = null;
            $alt_dcs = 1;
        }
        return array('mclass' => $mclass, 'alt_dcs' => $alt_dcs);
    }

    public function stopSMSOnRatio($user_id) {
        $date = date("Y-m-d");
        $this->db->select('sms_id');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $user_id);
        $this->db->like('submit_date', $date);
        $query = $this->db->get();
        if ($query->num_rows() > 100) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTempRatio() {
        /*
          $this->db->select('`user_id`,`pr_user_fail_ratio`,`pr_user_fake_ratio`,`user_fake_ratio`,`user_fail_ratio`');
          $this->db->from('users');
          $ratio = $this->db->get();
          $result_ratio = $ratio->result_array();

          foreach ($result_ratio as $result_ratio1) {
          $user_id = $result_ratio1['user_id'];
          $pr_user_fail= $result_ratio1['pr_user_fail_ratio'];
          $pr_user_deliver = $result_ratio1['pr_user_fake_ratio'];
          $tr_user_deliver = $result_ratio1['user_fake_ratio'];
          $tr_user_fail = $result_ratio1['user_fail_ratio'];
          }
         */


        //update fake ratio fail
        $this->db->select('*');
        $this->db->from('user_temp_ratio_pr');
        $pr_query = $this->db->get();
        $pr_result = $pr_query->result_array();
        foreach ($pr_result as $pr_result1) {
            $user_id = $pr_result1['user_id'];
            $pr_fake_fail = $pr_result1['fake_fail_pr'];
            $pr_fake_deliver = $pr_result1['fake_deliver_pr'];

            $data_pr = array(
                'pr_user_fake_ratio' => $pr_fake_deliver,
                'pr_user_fail_ratio' => $pr_fake_fail
            );

            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data_pr);
        }
        //update fake deliver ratio
        $this->db->select('*');
        $this->db->from('user_temp_ratio');
        $tr_query = $this->db->get();
        $tr_result = $tr_query->result_array();
        foreach ($tr_result as $tr_result1) {
            $user_id = $tr_result1['user_id'];
            $tr_fake_fail = $tr_result1['fake_fail_tr'];
            $tr_fake_deliver = $tr_result1['fake_deliver_tr'];

            $data_tr = array(
                'user_fake_ratio' => $tr_fake_deliver,
                'user_fail_ratio' => $tr_fake_fail
            );

            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data_tr);
        }
    }

    public function shortURL($url = null) {
        $url = "https://www.bulksmsserviceproviders.com/signin/?s=1";
        $this->load->library('google_url_api');
        $short_url = $this->google_url_api->shorten($url);
        echo $short_url->id;
//        if ($language == 1) {
//            $message_converted = $this->sms_model->checkLanguage($message, $ndnd_number);
//            $ssms_temp_array['message'] = urlencode($message_converted);
//        } else {
//            $ssms_temp_array['message'] = $message;
        //      }
    }

    public function checkLanguage($message = NULL, $mobile = 0) {

        $text = urlencode($message);
        $number_code = substr($mobile, 2, 5);

        $this->db->select('`language`');
        $this->db->from('state_data');
        $this->db->where('number', $number_code);
        $query = $this->db->get();
        $result = $query->row();
        $lang = $result->language;

        $ch = curl_init();
//Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//Set the URL
        curl_setopt($ch, CURLOPT_URL, "https://translate.yandex.net/api/v1.5/tr/translate?key=trnsl.1.1.20180727T122924Z.d24cc5c667252888.28a948beb16510573e2b23d7fb770775ccac6357&text=$text&lang=$lang");
//Execute the fetch
        return $data = curl_exec($ch);
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

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
}

?>