<?php

//error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class User_Data_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Utility_Model', 'utility_model');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Count Delivery Reports
    function countDeliveryReports($user_id = 0) {
        //$this->db->cache_on();
        $this->db->select('campaign_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'SMS');
        $this->db->order_by("campaign_id", "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Delivery Reports
    function getDeliveryReports($user_id = 0, $limit = 0, $start = 0) {
        //$this->db->cache_on();
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, sender_id, message_category, schedule_status');
        $this->db->select('submit_date, request_by, message_type, flash_message, message, route, total_credits, total_deducted, actual_message');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'SMS');
        $this->db->order_by("campaign_id", "desc");
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'sender_id' => $campaign['sender_id'],
                    'message_category' => $campaign['message_category'],
                    'schedule_status' => $campaign['schedule_status'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message']
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
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }
	
	
	
	
    
    // Get Delivery Reports
    function getDeliveryReportsapi($user_id = 0,$campaign_uid=0) {
//$this->db->cache_on();

        //$session_data = $this->session->userdata('logged_in');
        //$user_id = $session_data['user_id'];
        
        $this->db->select('campaign_id, sender_id');
        $this->db->from('`campaigns`');
        $this->db->where('`campaign_uid`', $campaign_uid);
        $this->db->where('`user_id`', $user_id);
        $query1 = $this->db->get();
        $result1 = $query1->result();
        if($result1){
             
        $this->db->select('*');
        $this->db->from('`sent_sms`');
        $this->db->where('`campaign_id`', $result1[0]->campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by("status", "DESC");
        $this->db->order_by("`done_date`", "DESC");

        $query = $this->db->get();
        if ($query->num_rows()) {
            $data =  $query->result_array();

            $delivery = array();
            foreach ($data as $value) {

                $data = array(
                    'mobile_no' => $value['mobile_no'],
                    'sender_id' => $result1[0]->sender_id,
                    'submit_date' => $value['submit_date'],
                     'message' => $value['message'],
                    'status' => $value['status']

                ); 
               $delivery[] = $data;  
            }


return $delivery;


        } else {
            
              $this->db->select('*');
        $this->db->from('`sent_sms_old`');
        $this->db->where('`campaign_id`', $result1[0]->campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by("status", "DESC");
        $this->db->order_by("`done_date`", "DESC");
      
        $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }else{
                return false;
            }
        }
        }else{
            return false;
        }
        
    }
    
    
	
	

    // Search Delivery Reports
    function searchDeliveryReports($user_id = 0, $search = null) {
        //$this->db->cache_on();
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, sender_id,user_id, message_category, schedule_status');
        $this->db->select('submit_date, request_by, message_type, flash_message, message, route, total_credits, total_deducted, actual_message');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'SMS');
        $this->db->like('sender_id', $search);
        //  $this->db->or_like('campaign_uid', $search);
        // $this->db->or_like('sender_id', $search);
        // $this->db->or_like('message', urlencode($search));
        $this->db->order_by("campaign_id", "desc");
        $this->db->limit(2000, 0);
        $query = $this->db->get();
        $result = $query->result_array();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'sender_id' => $campaign['sender_id'],
                    'user_id' => $campaign['user_id'],
                    'message_category' => $campaign['message_category'],
                    'schedule_status' => $campaign['schedule_status'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message']
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
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    //search dlr by date 
    function searchTotalSmsByDate() {
        $date = $this->input->post('search');
        $ref_user_id = $this->input->post('ref_user_id');
        $this->db->select('`status`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $ref_user_id);
        $this->db->like('submit_date', $date, 'after');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //search total deduct    
    function searchTotalDeductionByDate() {

        $date = $this->input->post('search');
        $ref_user_id = $this->input->post('ref_user_id');
        $this->db->select('SUM(`total_deducted`)');
        $this->db->from('campaigns');
        $this->db->where('user_id', $ref_user_id);
        $this->db->like('submit_date', $date, 'after');
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    // Search Delivery Schedule Reports
    function searchDeliveryScheduleReports($user_id = 0, $search = null) {
        //$this->db->cache_on();
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, sender_id,user_id, message_category, schedule_status');
        $this->db->select('submit_date, request_by, message_type, flash_message, message, route, total_credits, total_deducted, actual_message');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'SMS');
        $this->db->like('campaign_name', $search);
        $this->db->or_like('campaign_uid', $search);
        $this->db->or_like('sender_id', $search);
        $this->db->or_like('message', urlencode($search));
        $this->db->order_by("campaign_id", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_messages' => $campaign['total_messages'],
                    'campaign_status' => $campaign['campaign_status'],
                    'sender_id' => $campaign['sender_id'],
                    'user_id' => $campaign['user_id'],
                    'message_category' => $campaign['message_category'],
                    'schedule_status' => $campaign['schedule_status'],
                    'submit_date' => $campaign['submit_date'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'message' => $campaign['message'],
                    'route' => $campaign['route'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'actual_message' => $campaign['actual_message']
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
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    // Count Sent SMS
    function countSentSMS($campaign_id = 0) {
        //$this->db->cache_on();
        $this->db->select('`sms_id`, `route`, `msg_id`, `mobile_no`, `status`, `sender_id`, `sent_sms`.`submit_date` AS `submit_date`, `done_date`, `dlr_receipt`, `campaigns`.`campaign_id` AS `campaign_id`');
        $this->db->from('`sent_sms`, `campaigns`');
        $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
        $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
        $this->db->order_by("`sms_id`", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->num_rows();
        } else {
            $this->db->select('`sms_id`, `route`, `msg_id`, `mobile_no`, `status`, `sender_id`, `sent_sms_old`.`submit_date` AS `submit_date`, `done_date`, `dlr_receipt`, `campaigns`.`campaign_id` AS `campaign_id`');
            $this->db->from('`sent_sms_old`, `campaigns`');
            $this->db->where('`campaigns`.`campaign_id`=`sent_sms_old`.`campaign_id`');
            $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
            $this->db->order_by("`sms_id`", "desc");
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->num_rows();
            }
        }
    }

    function getSentSMSStatus($campaign_id = 0) {

        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];

        $this->db->select("COUNT(sms_id) AS Count_Status, `status`,`user_group_id`");
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->group_by("status");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            $this->db->select("COUNT(sms_id) AS Count_Status, `status`,`user_group_id`");
            $this->db->from('sent_sms_old');
            $this->db->where('`campaign_id`', $campaign_id);
            $this->db->where('`user_id`', $user_id);
            $this->db->group_by("status");
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }
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

    // Get Sent voice SMS Status
    function getSentVoiceSMSStatus($campaign_id = 0) {
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];
        $this->db->select("COUNT(sms_id) AS Count_Status, `status`,`user_group_id`");
        $this->db->from('sent_sms');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->group_by("status");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Sent SMS
    function getSentSMS($campaign_id = 0, $limit = 0, $start = 0) {
//$this->db->cache_on();
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];
        $this->db->select('`sms_id`, `user_id`, `msg_id`, `mobile_no`,`message`, `status`, `submit_date`, `done_date`, `dlr_receipt`, `dlr_status`,`msg_length`,`description`');
        $this->db->from('`sent_sms`');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by("status", "ASC");
        $this->db->order_by("`done_date`", "ASC");
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            $this->db->select('`sms_id`, `user_id`, `msg_id`, `mobile_no`,`message`, `status`, `submit_date`, `done_date`, `dlr_receipt`, `dlr_status`,`msg_length`,`description`');
            $this->db->from('`sent_sms_old`');
            $this->db->where('`campaign_id`', $campaign_id);
            $this->db->where('`user_id`', $user_id);
            $this->db->order_by("status", "ASC");
            $this->db->order_by("`done_date`", "ASC");
            if ($limit != null)
                $this->db->limit($limit, $start);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            }
        }
    }

    public function getCreditsSMS($campaign_id) {
        $this->db->select('`total_credits`,`sender_id`,`route`');
        $this->db->from('`campaigns`');
        $this->db->where('`campaign_id`', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Search Sent SMS
    function searchSentSMS($user_id = 0, $campaign_id = 0, $search = null) {
        //$this->db->cache_on();
        $this->db->select('`sms_id`, `user_id`, `msg_id`, `mobile_no`, `status`, `submit_date`, `done_date`, `dlr_receipt`, `dlr_status`');
        $this->db->from('`sent_sms`');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->like('mobile_no', $search);
        $this->db->order_by("`done_date`", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Delete Delivery Report
    function deleteDlrReport($campaign_id = 0) {
        return $this->db->delete('campaigns', array('campaign_id' => $campaign_id));
    }

    // Delete Sent SMS
    function deleteSentSMS($sms_id = 0) {
        return $this->db->delete('sent_sms', array('sms_id' => $sms_id));
    }

    // Count Schedule Reports
    function countScheduleReports($user_id = 0) {
        $this->db->select('campaign_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 2);
        $this->db->where('service_type', 'SMS');
        $this->db->order_by("campaign_id", "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Schedule Reports
    function getScheduleReports($user_id = 0, $limit = 0, $start = 0) {
        $this->db->select('`campaign_id`, `campaign_uid`, `campaign_name`,total_credits, total_deducted, message_category, schedule_status');
        $this->db->select('`total_messages`, `actual_message`, `campaign_status`, `sender_id`, `submit_date`, `schedule_date`');
        $this->db->select('`request_by`, `message_type`, `flash_message`, `message`, `message_length`, `route`, `schedule_action`');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 2);
        $this->db->where('service_type', 'SMS');
        $this->db->order_by("campaign_id", "desc");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $key => $campaign) {
                $temp_array = array(
                    'campaign_id' => $campaign['campaign_id'],
                    'campaign_uid' => $campaign['campaign_uid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'total_credits' => $campaign['total_credits'],
                    'total_deducted' => $campaign['total_deducted'],
                    'message_category' => $campaign['message_category'],
                    'schedule_status' => $campaign['schedule_status'],
                    'total_messages' => $campaign['total_messages'],
                    'actual_message' => $campaign['actual_message'],
                    'campaign_status' => $campaign['campaign_status'],
                    'sender_id' => $campaign['sender_id'],
                    'submit_date' => $campaign['submit_date'],
                    'schedule_date' => $campaign['schedule_date'],
                    'request_by' => $campaign['request_by'],
                    'message_type' => $campaign['message_type'],
                    'flash_message' => $campaign['flash_message'],
                    'message' => $campaign['message'],
                    'message_length' => $campaign['message_length'],
                    'route' => $campaign['route'],
                    'schedule_action' => $campaign['schedule_action']
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
                $return_array[] = $temp_array;
            }
            return $return_array;
        } else {
            return false;
        }
    }

    // Get Last Sender ID
    function getLastSender($user_id = 0) {
        $this->db->select('sender_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('route', 'B');
        $this->db->order_by("campaign_id", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get Campaign Info
    function getCampaignInfo($campaign_id = 0) {
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, sender_id, message_category, schedule_status, `user_id`');
        $this->db->select('submit_date, request_by, message_type, flash_message, message, message_length, route, total_credits, total_deducted, actual_message');
        $this->db->select('`sender_status`, `keyword_status`, `number_db_status`, `condition_status`, `is_xml`,`reseller_key_balance_status`');
        $this->db->from('campaigns');
        $this->db->where('campaign_id', $campaign_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Cancel Schedule SMS
    function cancelScheduleSMS($campaign_id = 0) {
        // Get Campaign Info
        $result_campaign = $this->getCampaignInfo($campaign_id);
        if ($result_campaign) {
            $total_deducted = $result_campaign->total_deducted;
            $route = $result_campaign->route;
            $user_id = $result_campaign->user_id;
            // Update User Balance
//            if ($route == 'A') {
//                $this->db->set('pr_sms_balance', '`pr_sms_balance`+' . $total_deducted, FALSE);
//            } elseif ($route == 'B') {
//                $this->db->set('tr_sms_balance', '`tr_sms_balance`+' . $total_deducted, FALSE);
//            } elseif ($route == 'C') {
//                $this->db->set('stock_balance', '`stock_balance`+' . $total_deducted, FALSE);
//            } elseif ($route == 'D') {
//                $this->db->set('prtodnd_balance', '`prtodnd_balance`+' . $total_deducted, FALSE);
//            }
//            $this->db->where('`user_id`', $user_id);
//            $this->db->update('users');
            // Update Schedule Action
            $data = array(
                'schedule_status' => 0,
                'schedule_action' => 0
            );
            $this->db->where('campaign_id', $campaign_id);
            $this->db->update('campaigns', $data);
            // Update SMS Status
            $data = array(
                'status' => 25
            );
            $this->db->where('campaign_id', $campaign_id);
            $this->db->update('sent_sms', $data);


            return true;
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Get Users Sender Ids
    function getSenderIds($user_id = 0) {
        $this->db->select('sender_id, sender_ids, sender_status');
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

    //Sender_id Tracker
    function getSenderIdTracker($user_id = 0) {
        $this->db->select('sender_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $id_data = array();
        $id_data_unique = array();

        foreach ($result as $result_id) {
            $id_data[] = $result_id['sender_id'];
        }
        $id_data_unique = array_unique($id_data);
        $size = sizeof($id_data);
        for ($i = 0; $i < $size; $i++) {
            if ($id_data_unique[$i]) {
                $arrange_ids[] = $id_data_unique[$i];
            }
        }
        $arrange_ids_size = sizeof($arrange_ids);
        $main_array = array();

        for ($k = 0; $k < $arrange_ids_size; $k++) {
            $this->db->select('SUM(total_messages) AS total_messages');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_id);
            $this->db->where('sender_id', $arrange_ids[$k]);
            $actual_query = $this->db->get();
            $actual_result = $actual_query->row();
            $no_of_sms = $actual_result->total_messages;
            $main_array[$k] = $sub_array = array(
                'sender_id' => $arrange_ids[$k],
                'no_of_sms' => $no_of_sms,
            );
        }
        return $main_array;
    }

// Save Users Sender Ids
    function saveSenderIds($user_id = 0) {
        $sender = $this->input->post('sender');
        // Get Sender Ids
        $this->db->select('sender_ids, sender_status');
        $this->db->from('sender_ids');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $sender_ids = "";
        $sender_status = "";
        if ($query->num_rows()) {
            $result_sender_ids = $query->row();
            $sender_ids = $result_sender_ids->sender_ids;
            $sender_status = $result_sender_ids->sender_status;
            $sender_ids_array = explode(',', $sender_ids);
            if (in_array($sender, $sender_ids_array)) {
                return 100;
            } else {
                $sender_ids.="," . $sender;
                $sender_status.=",0";
                $data = array(
                    'sender_ids' => $sender_ids,
                    'sender_status' => $sender_status
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('sender_ids', $data);

                //sender id alert
                $this->db->select('most_parent_id,account_manager,manager_alert,username');
                $this->db->from('users');
                $this->db->where('user_id', $user_id);
                $query_alert = $this->db->get();
                $alert_sender_ids = $query_alert->row();
                $manager_alert = $alert_sender_ids->manager_alert;
                $username = $alert_sender_ids->username;
                $parent_alert_id = $alert_sender_ids->most_parent_id;
                $account_manager = $alert_sender_ids->account_manager;

                if ($parent_alert_id != 0) {
                    //select most parent username
                    $this->db->select('most_parent_id,account_manager,manager_alert,username');
                    $this->db->from('users');
                    $this->db->where('user_id', $parent_alert_id);
                    $select_username = $this->db->get();
                    $username_id = $select_username->row();
                    $manager_alert = $username_id->manager_alert;
                    $most_parent_username = $username_id->username;
                    $parent_alert_id = $username_id->most_parent_id;
                    $account_manager = $username_id->account_manager;
                }

                if ($manager_alert == 1) {
                    $this->db->select('admin_contact,admin_username');
                    $this->db->from('administrators');
                    $this->db->where('admin_id', $account_manager);
                    $query = $this->db->get();
                    $alert = $query->row();
                    $account_manager_name = $alert->admin_username;
                    $admin_contact = $alert->admin_contact;

                    $authKey = "971d3401cdfa0e4427335e1cc1cd08b0";
                    $senderId = "MANAGR";
                    $message = "Sender ID : " . $sender . " <br/> " . "Requested By : " . $username . " <br/> " . " Reseller : " . $most_parent_username;
                    $route = "4";
                    $postData = array(
                        'authkey' => $authKey,
                        'mobiles' => $admin_contact,
                        'sender' => $senderId,
                        'message' => $message,
                        'route' => $route
                    );
                    //API URL
                    $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                    // init the resource
                    $ch = curl_init();
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postData
                            //,CURLOPT_FOLLOWLOCATION => true
                    ));
                    //Ignore SSL certificate verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    //get response
                    $output = curl_exec($ch);
                    if (curl_errno($ch)) {
                        //echo 'error:' . curl_error($ch);
                    }
                    curl_close($ch);
                    //echo $output;
                }
                return 200;
            }
        } else {
            $sender_ids.=$sender;
            $sender_status.="0";
            $data = array(
                'sender_ids' => $sender_ids,
                'user_id' => $user_id,
                'sender_status' => $sender_status
            );
            $this->db->insert('sender_ids', $data);

            //sender id alert
            $this->db->select('most_parent_id,account_manager,manager_alert,username');
            $this->db->from('users');
            $this->db->where('user_id', $user_id);
            $query_alert = $this->db->get();
            $alert_sender_ids = $query_alert->row();
            $manager_alert = $alert_sender_ids->manager_alert;
            $username = $alert_sender_ids->username;
            $parent_alert_id = $alert_sender_ids->most_parent_id;
            $account_manager = $alert_sender_ids->account_manager;

            if ($parent_alert_id != 0) {
                //select most parent username
                $this->db->select('most_parent_id,account_manager,manager_alert,username');
                $this->db->from('users');
                $this->db->where('user_id', $parent_alert_id);
                $select_username = $this->db->get();
                $username_id = $select_username->row();
                $manager_alert = $username_id->manager_alert;
                $most_parent_username = $username_id->username;
                $parent_alert_id = $username_id->most_parent_id;
                $account_manager = $username_id->account_manager;
            }
            if ($manager_alert == 1) {
                $this->db->select('admin_contact');
                $this->db->from('administrators');
                $this->db->where('admin_id', $account_manager);
                $query = $this->db->get();
                $alert = $query->row();
                $admin_contact = $alert->admin_contact;

                $authKey = "971d3401cdfa0e4427335e1cc1cd08b0";
                $senderId = "MANAGR";
                $message = "Sender ID : " . $sender . " <br/> " . "Requested By : " . $username . " <br/> " . " Reseller : " . $most_parent_username;
                $route = "4";
                $postData = array(
                    'authkey' => $authKey,
                    'mobiles' => $admin_contact,
                    'sender' => $senderId,
                    'message' => $message,
                    'route' => $route
                );
                //API URL
                $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
                // init the resource
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postData
                        //,CURLOPT_FOLLOWLOCATION => true
                ));
                //Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                //get response
                $output = curl_exec($ch);
                //Print error if any
                if (curl_errno($ch)) {
                    // echo 'error:' . curl_error($ch);
                }
                curl_close($ch);
                //echo $output; 
            }

            return 200;
        }
    }

    // Delete Users Sender Id
    function deleteSenderIds($user_id = 0, $sender_value = null) {
        // Get Sender Ids
        $this->db->select('sender_ids, sender_status');
        $this->db->from('sender_ids');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $sender_ids = "";
        $sender_status = "";
        if ($query->num_rows()) {
            $result_sender_ids = $query->result();
            foreach ($result_sender_ids as $row) {
                $sender_ids = $row->sender_ids;
                $sender_status = $row->sender_status;
            }
            $sender_ids_array = explode(',', $sender_ids);
            $sender_status_array = explode(',', $sender_status);
            unset($sender_ids_array[$sender_value]);
            unset($sender_status_array[$sender_value]);
            $new_sender_ids = implode(',', $sender_ids_array);
            $new_sender_status = implode(',', $sender_status_array);
            if ($new_sender_ids == "") {
                return $this->db->delete('sender_ids', array('user_id' => $user_id));
            } else {
                $data = array(
                    'sender_ids' => $new_sender_ids,
                    'sender_status' => $new_sender_status
                );
                $this->db->where('user_id', $user_id);
                return $this->db->update('sender_ids', $data);
            }
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Users Keywords
    function getKeywords($user_id = 0) {
        $this->db->select('keyword_id, keywords, percent_ratio_user, percent_ratio_all_users, keyword_status');
        $this->db->from('keywords');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Save Users Keyword
    function saveKeyword($user_id = 0, $keyword_type = null) {
        if ($keyword_type == 'Normal') {
            $data = array(
                'keywords' => $this->input->post('keyword'),
                'user_id' => $user_id
            );
            return $this->db->insert('keywords', $data);
        } elseif ($keyword_type == 'Black') {
            $data = array(
                'black_keyword' => $this->input->post('keyword'),
                'user_id' => $user_id
            );
            return $this->db->insert('black_keywords', $data);
        }
    }

    // Delete Users Keyword
    function deleteKeyword($keyword_id = 0, $keyword_type = null) {
        if ($keyword_type == 'keyword') {
            return $this->db->delete('keywords', array('keyword_id' => $keyword_id));
        } elseif ($keyword_type == 'black_keyword') {
            return $this->db->delete('black_keywords', array('black_keyword_id' => $keyword_id));
        }
    }

    // Get Users Black Keywords
    function getBlackKeywords($user_id = 0) {
        $this->db->select('black_keyword_id, black_keyword, black_keyword_status');
        $this->db->from('black_keywords');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get SMS Preview
    function getSmsPreview() {
        $message = $this->input->post('message');
        $file_name = $this->input->post('file_name');
        $total_column = $this->input->post('total_column');
        $msg_array = explode(' ', $message);
        $total_column_array = explode(',', $total_column);
        $new_message_array = array();
        $i = 0;
        $limit = 5;
        $filename = "./Uploads/" . $file_name;
        $handle = fopen($filename, 'r');

        while (($file_data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($i == $limit) {
                break;
            } else {
                $new_message = "";
                foreach ($msg_array as $value) {
                    if (in_array($value, $total_column_array)) {
                        $key = array_search($value, $total_column_array);
                        if ($new_message == "")
                            $new_message = $file_data[$key];
                        else
                            $new_message = $new_message . " " . $file_data[$key];
                    }else {
                        if ($new_message == "")
                            $new_message = $value;
                        else
                            $new_message = $new_message . " " . $value;
                    }
                }
                $new_message_array[$i] = $new_message;
            }
            $i++;
        }
        fclose($handle);

        return $new_message_array;
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Count Users
    function countUsers($user_id = 0) {
        $this->db->select('`user_id`, `username`, `user_status`, `utype`, `name`, `email_address`, `contact_number`');
        $this->db->select('`pr_sms_balance`, `tr_sms_balance`, `expiry_date`, `creation_date`');
        $this->db->from('`users`');
        $this->db->where('`ref_user_id`', $user_id);
        $this->db->order_by('`user_id`', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Users
    function getUsers1($user_id = 0) {
        $this->db->select('users1.user_id AS user_id, users1.username AS username, users2.username AS ref_username, admin_username');
        $this->db->select('users3.username AS parent_username');
        $this->db->select('users1.number_allowed AS number_allowed, users1.user_status AS user_status, pr_user_groups.user_group_name AS pr_user_group_name');
        $this->db->select('users1.utype AS utype, pr_user_groups.smsc_id AS pr_smsc_id, tr_user_groups.user_group_name AS tr_user_group_name');
        $this->db->select('tr_user_groups.smsc_id AS tr_smsc_id, users1.p_sender_id_option AS p_sender_id_option, users1.last_login_date AS last_login_date');
        $this->db->select('users1.t_sender_id_option AS t_sender_id_option, users1.keyword_option AS keyword_option');
        $this->db->select('users1.pr_sms_balance AS pr_sms_balance, users1.tr_sms_balance AS tr_sms_balance');
        $this->db->select('users1.name AS name, users1.email_address AS email_address,  users1.contact_number AS contact_number');
        $this->db->from('users AS users1');
        $this->db->join('administrators', 'administrators.admin_id = users1.admin_id', 'left');
        $this->db->join('users AS users2', 'users2.user_id = users1.ref_user_id', 'left');
        $this->db->join('users AS users3', 'users3.user_id = users1.most_parent_id', 'left');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = users1.pro_user_group_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = users1.tr_user_group_id', 'left');
        $this->db->where('`users1`.`ref_user_id`', $user_id);
        $this->db->order_by('users1.user_id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Users
    function getUsers($user_id = 0) {
        $this->db->select('user_id, username, number_allowed, user_status, utype, last_login_date, pr_sms_balance, tr_sms_balance');
        $this->db->select('name, email_address, contact_number');
        $this->db->from('users');
        $this->db->where('`ref_user_id`', $user_id);
        $this->db->order_by('user_id', 'name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get All Users
    function getAllUsers() {
        $this->db->select('user_id, push_dlr_url');
        $this->db->from('users');
        $this->db->where('push_dlr_url !=', "");
        $this->db->order_by('user_id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Search Users
    function searchUsers($user_id = 0, $user = null) {
        $this->db->select('user_id, username, ref_user_id, number_allowed, user_status, utype, last_login_date, pr_sms_balance, tr_sms_balance');
        $this->db->select('name, email_address, contact_number');
        $this->db->from('users');
        $this->db->where('`ref_user_id`', $user_id);
        $this->db->or_like('`username`', $user);
        $this->db->or_like('`name`', $user);
        $this->db->or_like('`email_address`', $user);
        $this->db->or_like('`contact_number`', $user);
        $this->db->order_by('user_id', 'name');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Users Balance
    function getUsersBalance($ref_user_id = 0) {
        $this->db->select('COUNT(user_id) AS total_users, SUM(pr_sms_balance) AS total_pr_balance, SUM(tr_sms_balance) AS total_tr_balance');
        $this->db->select('SUM(long_code_balance) AS long_code_balance, SUM(short_code_balance) AS short_code_balance');
        $this->db->select('SUM(pr_voice_balance) AS total_pr_voice_balance, SUM(tr_voice_balance) AS total_tr_voice_balance');
        $this->db->select('SUM(prtodnd_balance) AS total_prtodnd_balance, SUM(stock_balance) AS total_stock_balance');
        $this->db->from('users');
        $this->db->where('ref_user_id', $ref_user_id);
        $this->db->or_where('user_id', $ref_user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get Username
    function getUsername($username = null) {
        // Check In Users
        $this->db->select('`username`');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }
    }

    // Get User
    function getUser($user_id = 0) {
        $this->db->select('user_id, name, username, email_address, contact_number, pro_user_group_id, tr_user_group_id, auth_key');
        $this->db->select('`utype`, `pr_sms_balance`, `tr_sms_balance`, `expiry_date`, last_login_date, creation_date, open_account_setting');
        $this->db->select('spacial_reseller_status,special_pr_balance,special_tr_balance');
        $this->db->select('number_allowed, p_sender_id_option, t_sender_id_option, keyword_option, dnd_check, sender_id_length, sender_id_type');
        $this->db->select('`default_sender_id`, `industry`, `default_timezone`, `user_status`, `check_signature`, `signature`, `check_verification`');
        $this->db->select('`date_of_birth`, `address`, `city`, `country`, `zipcode`, user_settings, default_route, company_name');
        $this->db->select('`user_ratio`, `user_fake_ratio`, `user_fail_ratio`, `p_sender_id_type`, `p_sender_id_length`, `check_demo_user`, `push_dlr_url`');
        $this->db->select('pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio, user_settings AS user_settings_col');
        $this->db->select('`demo_balance`, `expiry_days`, `signup_sender`, `signup_message`, `signup_subject`, `signup_body`, `demo_sender`, `demo_message`');
        $this->db->select('`low_balance_alert`, `low_balance_pr`, `low_balance_tr`, `ref_user_id`, `admin_id`, `signup_notification`, `short_code_balance`, `long_code_balance`');
        $this->db->select('pr_voice_balance, tr_voice_balance, default_voice_route, check_black_keyword, `theme_color`, `missed_call_balance`,`account_manager`, `spacial_fake_pr_ratio`, `spacial_deliver_pr_ratio`, `spacial_fake_tr_ratio`, `spacial_deliver_tr_ratio`');
        $this->db->select('`prtodnd_credits`,`stock_credits`,`prtodnd_balance`,`stock_balance`,`encription`,`voice_tr_route`, `voice_pr_route`');
        $this->db->from('`users`');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Get Account Manager
    function getAccountManager($reseller = 0, $admin = 0) {
        if ($admin == 0) {
            $admin = 1;
        }


        if ($reseller) {
            $this->db->select('`name` AS `name`, `email_address` AS `email_address`, `contact_number` AS `contact_number`');
            $this->db->from('`users`');
            $this->db->where('`user_id`', $reseller);
        } elseif ($admin) {
            $this->db->select('`admin_name` AS `name`, `admin_contact` AS `contact_number`, `admin_email` AS `email_address`');
            $this->db->from('`administrators`');
            $this->db->where('admin_id', $admin);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $result = $query->row();
            return array('name' => $result->name, 'email_address' => $result->email_address, 'contact_number' => $result->contact_number);
        } else {
            return false;
        }
    }

    // Get User Mobile Numbers
    function getMobileNumber($contact_number = 0) {
        $this->db->select('contact_number');
        $this->db->from('`users`');
        $this->db->where('`contact_number`', $contact_number);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Save User
    function saveUser() {
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];
        $most_parent_id = $session_data['most_parent_id'];

        // User Info
        $result_user = $this->getUser($user_id);
        if ($result_user) {
            // Parent Details
            if ($result_user['demo_balance']) {
                $demo_balance = $result_user['demo_balance'];
            } else {
                $demo_balance = 5;
            }
            $expiry_days = $result_user['expiry_days'];
            $signup_sender = $result_user['signup_sender'];
            $signup_message = $result_user['signup_message'];
            $signup_subject = $result_user['signup_subject'];
            $signup_body = $result_user['signup_body'];
            $dnd_check = $result_user['dnd_check'];
            $check_verification = $result_user['check_verification'];
            $open_account_setting = $result_user['open_account_setting'];



            $pr_sms_balance = $result_user['pr_sms_balance'];
            $tr_sms_balance = $result_user['tr_sms_balance'];
            $pro_user_group_id = $result_user['pro_user_group_id'];
            $tr_user_group_id = $result_user['tr_user_group_id'];
            $number_allowed = $result_user['number_allowed'];
            $user_ratio = $result_user['user_ratio'];
            $user_fake_ratio = $result_user['user_fake_ratio'];
            $user_fail_ratio = $result_user['user_fail_ratio'];
            $pr_user_ratio = $result_user['pr_user_ratio'];
            $pr_user_fake_ratio = $result_user['pr_user_fake_ratio'];
            $pr_user_fail_ratio = $result_user['pr_user_fail_ratio'];
            $spacial_deliver_pr_ratio = $result_user['spacial_deliver_pr_ratio'];
            $spacial_deliver_tr_ratio = $result_user['spacial_deliver_tr_ratio'];
            $voice_tr_route = $result_user['voice_tr_route'];
            $voice_pr_route = $result_user['voice_pr_route'];
            // Check Available Balance
            $pr_demo_balance = 0;
            $tr_demo_balance = 0;
            if ($pr_sms_balance && $pr_sms_balance > $demo_balance) {
                $pr_demo_balance = $demo_balance;
            }
            if ($tr_sms_balance && $tr_sms_balance > $demo_balance) {
                $tr_demo_balance = $demo_balance;
            }

            // Website Info
            $web_domain = $_SERVER['SERVER_NAME'];
            $this->load->model('login_model', 'login_model');
            $result_website = $this->login_model->getWebsiteInfo($web_domain);
            if ($result_website) {
                $ref_company_name = $result_website['company_name'];
                $from = $result_website['reciever_email'];
            } else {
                $from = $result_user['email_address'];
                $ref_company_name = $result_user['company_name'];
            }

            $name = $this->input->post('name');
            $username = $this->input->post('username');
            $contact = $this->input->post('contact');
            $email_address = $this->input->post('email_address');
            $company_name = $this->input->post('company_name');
            $industry = $this->input->post('industry');
            $expiry_date = $this->input->post('expiry_date');
            $country = $this->input->post('country');

            $p_sender_id_option = 1;
            $t_sender_id_option = 0;
            $keyword_option = 0;
            // Generate Password
            $password = random_string('numeric', 6);
            // Generate Auth key
            $auth_key = random_string('unique', 32);
            $creation_date = date('d-m-Y h:i A');
            $p_sender_id_type = "Alphabetic";
            $p_sender_id_length = 6;
            $sender_id_type = "Alphabetic";
            $sender_id_length = 6;
            if ($most_parent_id == 0) {
                $parent_id = $user_id;
            } else {
                $parent_id = $most_parent_id;
            }
            $data = array(
                'ref_user_id' => $user_id,
                'most_parent_id' => $parent_id,
                'name' => $name,
                'email_address' => $email_address,
                'contact_number' => $contact,
                'company_name' => $company_name,
                'username' => $username,
                'password' => md5($password),
                'auth_key' => $auth_key,
                'utype' => 'User',
                'dnd_check' => $dnd_check,
                'number_allowed' => $number_allowed,
                'pro_user_group_id' => $pro_user_group_id,
                'p_sender_id_option' => $p_sender_id_option,
                't_sender_id_option' => $t_sender_id_option,
                'keyword_option' => $keyword_option,
                'tr_user_group_id' => $tr_user_group_id,
                'expiry_date' => $expiry_date,
                'creation_date' => $creation_date,
                'industry' => $industry,
                'user_ratio' => $user_ratio,
                'user_fake_ratio' => $user_fake_ratio,
                'user_fail_ratio' => $user_fail_ratio,
                'pr_user_ratio' => $pr_user_ratio,
                'pr_user_fake_ratio' => $pr_user_fake_ratio,
                'pr_user_fail_ratio' => $pr_user_fail_ratio,
                'pr_sms_balance' => $pr_demo_balance,
                'tr_sms_balance' => $tr_demo_balance,
                'p_sender_id_type' => $p_sender_id_type,
                'p_sender_id_length' => $p_sender_id_length,
                'sender_id_type' => $sender_id_type,
                'sender_id_length' => $sender_id_length,
                'spacial_deliver_tr_ratio' => $spacial_deliver_tr_ratio,
                'spacial_deliver_pr_ratio' => $spacial_deliver_pr_ratio,
                'country_status' => $country,
                'check_verification' => $check_verification,
                'open_account_setting' => $open_account_setting,
                'prtodnd_route' => $pro_user_group_id,
                'stock_route' => $pro_user_group_id,
                'voice_tr_route' => $voice_tr_route,
                'voice_pr_route' => $voice_pr_route,
            );
            //=====================================================================//

            $this->db->select('*');
            $this->db->from('`users`');
            $this->db->where('`user_id`', $parent_id);
            $query_manager = $this->db->get();
            $result_manager = $query_manager->row();
            $manager_alert = $result_manager->manager_alert;
            $manager_username = $result_manager->username;
            $account_manager = $result_manager->account_manager;

            if ($manager_alert == 1) {

                $this->db->select('admin_contact');
                $this->db->from('`administrators`');
                $this->db->where('`admin_id`', $account_manager);
                $query_admin = $this->db->get();
                $admin_manager = $query_admin->row();
                $admin_contact = $admin_manager->admin_contact;
                $alert_sms = "New account created in " . $manager_username . " with username " . $username;
                $route = 4;
                $postData = array(
                    'authkey' => "971d3401cdfa0e4427335e1cc1cd08b0",
                    'mobiles' => $admin_contact,
                    'message' => $alert_sms,
                    'sender' => "MANAGR",
                    'route' => $route
                );
//API URL
                $url = "http://sms.bulksmsserviceproviders.com/api/send_http.php";
// init the resource
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postData
                        //,CURLOPT_FOLLOWLOCATION => true
                ));
//Ignore SSL certificate verification
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
                $output = curl_exec($ch);
//Print error if any
            }

            // Send SMS
            $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
            $web_domain = $server_protocol . "://" . $web_domain;
            // Load SMS_Model
            $this->load->model('Sms_Model', 'sms_model');
            // Get Short URL
            $short_web_domain = $this->sms_model->googleUrlShortner($web_domain);
            $name_array = explode(' ', $name);
            $purpose = "User Signup";
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
            //$messages = urlencode("Thank you! Your login username:$username & password: $password");
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
            //=====================================================================//
            // Send Mail
            //$to = $email_address;
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
                $body = $body . "<h4>Team $ref_company_name</h4>";
            } else {
                $body = $this->utility_model->emailSignup($web_domain, $username, $ref_company_name, 0);
            }

            // Prepare Email Array
            $mail_array = array(
                'from_email' => $from,
                'from_name' => $ref_company_name,
                'to_email' => $email_address,
                'subject' => $subject,
                'message' => $body
            );

            if ($this->db->insert('users', $data) && $this->utility_model->sendSMS($url, $sms_array) && $this->utility_model->sendEmail($mail_array)) {
                $up_pr_sms_balance = $pr_sms_balance - $pr_demo_balance;
                $up_tr_sms_balance = $tr_sms_balance - $tr_demo_balance;
                $bal_data = array(
                    'pr_sms_balance' => $up_pr_sms_balance,
                    'tr_sms_balance' => $up_tr_sms_balance
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $bal_data);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Save Update User
    function saveUpdateUser($ref_user_id = 0) {
        $data = array(
            'name' => $this->input->post('name'),
            'contact_number' => $this->input->post('contact_number'),
            'email_address' => $this->input->post('email_address'),
            'industry' => $this->input->post('industry'),
            'company_name' => $this->input->post('company_name')
        );
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Delete User
    function deleteUser($user_id = 0) {
        return $this->db->delete('users', array('user_id' => $user_id));
    }

    // Enable/Disable User
    function changeUserStatus($user_id = 0, $status = 0) {
        $data = array(
            'user_status' => $status
        );
        $this->db->where('user_id', $user_id);
        return $result = $this->db->update('users', $data);
    }

    // Save SMS Funds
    function saveSMSFunds($user_id = 0, $ref_user_id = 0, $temp_array = array()) {
        // User Balance 

        $pr_sms_balance = $temp_array['pr_sms_balance'];
        $tr_sms_balance = $temp_array['tr_sms_balance'];
        $stock_balance = $temp_array['stock_balance'];
        $prtodnd_balance = $temp_array['prtodnd_balance'];
        $long_code_balance = $temp_array['long_code_balance'];
        $short_code_balance = $temp_array['short_code_balance'];
        $pr_voice_balance = $temp_array['pr_voice_balance'];
        $tr_voice_balance = $temp_array['tr_voice_balance'];
        // User SMS Balance
        $ref_pr_sms_bal = 0;
        $ref_tr_sms_bal = 0;
        $ref_prtodnd_sms_bal = 0;
        $ref_stock_sms_bal = 0;
        $ref_lcode_bal = 0;
        $ref_scode_bal = 0;
        $ref_pr_voice_bal = 0;
        $ref_tr_voice_bal = 0;
        $result_user = $this->getUser($ref_user_id);
        if ($result_user) {
            $ref_pr_sms_bal += $result_user['pr_sms_balance'];
            $ref_stock_sms_bal += $result_user['stock_balance'];
            $ref_prtodnd_sms_bal += $result_user['prtodnd_balance'];
            $ref_tr_sms_bal += $result_user['tr_sms_balance'];
            $ref_lcode_bal += $result_user['long_code_balance'];
            $ref_scode_bal += $result_user['short_code_balance'];
            $ref_pr_voice_bal += $result_user['pr_voice_balance'];
            $ref_tr_voice_bal += $result_user['tr_voice_balance'];
        }
        $route = $this->input->post('route');
        $type = $this->input->post('type');
        $sms_balance = $this->input->post('sms_balance');
        if (is_numeric($sms_balance)) {
            
        } else {
            return FALSE;
        }

        $sms_price = $this->input->post('sms_price');
        $amount = $this->input->post('amount');
        $description = $this->input->post('description');
        $txn_date = date('d-m-Y h:i A');
        $new_date = date('Y-m-d');
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
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2+=$ref_pr_sms_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'pr_sms_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'pr_sms_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_pr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$pr_sms_balance + $sms_balance;
                    $remain_pr_sms_balance2+=$ref_pr_sms_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'pr_sms_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'pr_sms_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }

        // stock fund transfered
        if ($route == 'C') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($stock_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$stock_balance - $sms_balance;
                    $remain_pr_sms_balance2+=$ref_stock_sms_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'stock_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'stock_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_stock_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$stock_balance + $sms_balance;
                    $remain_pr_sms_balance2+=$ref_stock_sms_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'stock_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'stock_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }

        // prtodnd fund transfered
        if ($route == 'D') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($prtodnd_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$prtodnd_balance - $sms_balance;
                    $remain_pr_sms_balance2+=$ref_prtodnd_sms_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'prtodnd_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'prtodnd_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_prtodnd_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_sms_balance1+=$prtodnd_balance + $sms_balance;
                    $remain_pr_sms_balance2+=$ref_prtodnd_sms_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'prtodnd_balance' => $remain_pr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'prtodnd_balance' => $remain_pr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
        // Transactional SMS
        if ($route == 'B') {
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1+=$tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2+=$ref_tr_sms_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'tr_sms_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'tr_sms_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_tr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_sms_balance1+=$tr_sms_balance + $sms_balance;
                    $remain_tr_sms_balance2+=$ref_tr_sms_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'tr_sms_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'tr_sms_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
        // Long Code
        if ($route == 'Long') {
            $remain_lcode_balance1 = 0;
            $remain_lcode_balance2 = 0;
            if ($type == 'Add') {
                if ($long_code_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_lcode_balance1+=$long_code_balance - $sms_balance;
                    $remain_lcode_balance2+=$ref_lcode_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'long_code_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_lcode_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_lcode_balance1+=$long_code_balance + $sms_balance;
                    $remain_lcode_balance2+=$ref_lcode_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'long_code_balance' => $remain_lcode_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'long_code_balance' => $remain_lcode_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
        // Short Code
        if ($route == 'Short') {
            $remain_scode_balance1 = 0;
            $remain_scode_balance2 = 0;
            if ($type == 'Add') {
                if ($short_code_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_scode_balance1+=$short_code_balance - $sms_balance;
                    $remain_scode_balance2+=$ref_scode_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'short_code_balance' => $remain_scode_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_scode_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_scode_balance1+=$short_code_balance + $sms_balance;
                    $remain_scode_balance2+=$ref_scode_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'short_code_balance' => $remain_scode_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'short_code_balance' => $remain_scode_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
        // Promotional Voice
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
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_voice_balance1+=$pr_voice_balance - $sms_balance;
                    $remain_pr_voice_balance2+=$ref_pr_voice_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'pr_voice_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_pr_voice_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_pr_voice_balance1+=$pr_voice_balance + $sms_balance;
                    $remain_pr_voice_balance2+=$ref_pr_voice_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'pr_voice_balance' => $remain_pr_voice_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'pr_voice_balance' => $remain_pr_voice_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
        // Dynamic Voice
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
                        'txn_user_from' => $user_id,
                        'txn_user_to' => $ref_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_voice_balance1+=$tr_voice_balance - $sms_balance;
                    $remain_tr_voice_balance2+=$ref_tr_voice_bal + $sms_balance;
                    // Reseller Account
                    $data = array(
                        'tr_voice_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($ref_tr_voice_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array(
                        'txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_user_from' => $ref_user_id,
                        'txn_user_to' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description,
                        'new_date' => $new_date
                    );
                    $this->db->insert('transaction_logs', $data);
                    $remain_tr_voice_balance1+=$tr_voice_balance + $sms_balance;
                    $remain_tr_voice_balance2+=$ref_tr_voice_bal - $sms_balance;
                    // Reseller Account
                    $data = array(
                        'tr_voice_balance' => $remain_tr_voice_balance1
                    );
                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data);
                    // User Account
                    $data = array(
                        'tr_voice_balance' => $remain_tr_voice_balance2
                    );
                    $this->db->where('user_id', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                }
            }
        }
    }

    // Get SMS Logs
    function getUserSMSLogs($ref_user_id = 0) {
        $this->db->select('`txn_route`, `txn_sms`, `txn_price`, `txn_amount`, `txn_type`, `txn_date`, `txn_description`');
        $this->db->select(' `userA`.`name` AS `from_name` , `userA`.`user_id` AS `from_user_id`');
        $this->db->select(' `userB`.`name` AS `to_name` , `userB`.`user_id` AS `to_user_id`');
        $this->db->select('`administratorsA`.`admin_name` AS `from_admin_name` , `administratorsA`.`admin_id` AS `from_admin_id`');
        $this->db->select(' `administratorsB`.`admin_name` AS `to_admin_name` , `administratorsB`.`admin_id` AS `to_admin_id`');
        $this->db->from('`transaction_logs`');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->where('`txn_user_from`', $ref_user_id);
        $this->db->or_where('`txn_user_to`', $ref_user_id);
        $this->db->order_by('`txn_log_id`', 'desc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Save User Expiry
    function saveUserExpiry($ref_user_id = 0, $subtab = 0) {
        $expiry_date = $this->input->post('expiry_date');
        // User Account
        if ($subtab == '2') {
            $data = array(
                'expiry_date' => $expiry_date
            );
        } elseif ($subtab == '3') {
            $data = array(
                'expiry_date' => ""
            );
        }
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Save New Password
    function saveUserPassword($ref_user_id = 0) {
        // User Account
        $data = array(
            'password' => md5($this->input->post('password'))
        );
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Change User Password
    function saveLowBalAlert($ref_user_id = 0) {
        $alert_by_sms = 0;
        $alert_by_email = 0;
        if ($this->input->post('alert_by_sms'))
            $alert_by_sms = $this->input->post('alert_by_sms');
        if ($this->input->post('alert_by_email'))
            $alert_by_email = $this->input->post('alert_by_email');

        $low_balance_alert = $alert_by_sms . "|" . $alert_by_email;
        $pr_sms = $this->input->post('pr_sms');
        $tr_sms = $this->input->post('tr_sms');
        // User Account
        $data = array('low_balance_alert' => $low_balance_alert, 'low_balance_pr' => $pr_sms, 'low_balance_tr' => $tr_sms);
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Save Account Type
    function saveAccountType($ref_user_id = 0) {
        if ($this->input->post('account_type'))
            $account_type = $this->input->post('account_type');
        else
            $account_type = 0;
        // User Account
        $data = array('check_demo_user' => $account_type);
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Save Black Keyword Setting
    function checkBlackKeyword($ref_user_id = 0) {
        if ($this->input->post('check_black_keyword'))
            $check_black_keyword = $this->input->post('check_black_keyword');
        else
            $check_black_keyword = 0;
        // User Setting
        $data = array('check_black_keyword' => $check_black_keyword);
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    // Save Web Hooks
    function saveWebHooks($ref_user_id = 0) {
        // User Account
        $data = array(
            'push_dlr_url' => $this->input->post('push_dlr_url')
        );
        $this->db->where('user_id', $ref_user_id);
        return $this->db->update('users', $data);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Contact Groups
    function getContactGroups($user_id = 0) {
        $this->db->select('`contact_group_id`, `contact_group_name`, `total_contacts`, `contact_group_status`');
        $this->db->select('`extra_column_names`, `extra_column_types`, `extra_column_status`, `extra_column_ids`');
        $this->db->from('`contact_groups`, `users`');
        $this->db->where('`contact_groups`.`user_id`=`users`.`user_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Contact Group
    function getContactGroup($group_id = 0) {
        $this->db->select('`contact_group_id`, `contact_group_name`, `total_contacts`, `contact_group_status`, `extra_column_names`, `extra_column_types`, extra_column_ids, extra_column_status');
        $this->db->from('`contact_groups`');

        $this->db->where('`contact_group_id`', $group_id);

        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Save Contact Group
    function saveContactGroup($user_id = 0) {
        $group_name = $this->input->post('group_name');
        $this->db->select('`contact_group_id`');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`contact_group_name`', $group_name);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return false;
        } else {
            $data = array(
                'contact_group_name' => $group_name,
                'user_id' => $user_id
            );
            return $this->db->insert('contact_groups', $data);
        }
    }

    // Get Group Contacts
    function getGroupContacts($user_id = 0, $group_id = 0) {
        $contacts_array = array();
        // Contact Group Name And Ids
        $group_name_array = $this->getGroupName($user_id);
        $group_id_array = $this->getGroupId($user_id);
        $this->db->select('`contact_id`, `mobile_number`, `contacts`.`contact_name`, `contact_group_ids`, `extra_column_values`, `extra_column_ids`');
        $this->db->from('`contacts`, `users`');
        $this->db->where('`contacts`.`user_id`=`users`.`user_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        //backup  $this->db->where('`contacts`.`contact_group_ids`', $group_id);
        $this->db->like('`contacts`.`contact_group_ids`', $group_id);
        $this->db->limit(10000);
        $query_contacts = $this->db->get();
        if ($query_contacts->num_rows()) {
            $result_contacts = $query_contacts->result_array();
            foreach ($result_contacts as $key => $contact) {
                $group_array = explode(',', $contact['contact_group_ids']);
                $extra_column_id_array = explode('|', $contact['extra_column_ids']);
                $extra_column_values_array = explode('|', $contact['extra_column_values']);
                if (in_array($group_id, $group_array)) {
                    $temp_array = array();
                    $temp_array['contact_id'] = $contact['contact_id'];
                    $temp_array['mobile_number'] = $contact['mobile_number'];
                    $temp_array['contact_group_ids'] = $contact['contact_group_ids'];
                    $temp_array['contact_name'] = $contact['contact_name'];
                    $temp_array['extra_column_values'] = $contact['extra_column_values'];
                    $temp_array['extra_column_ids'] = $contact['extra_column_ids'];
                    $jj = 0;
                    $group_name_string = "-";
                    foreach ($group_name_array as $g_name) {
                        if ($group_id_array[$jj]['contact_group_id'] != $group_id) {
                            if (in_array($group_id_array[$jj]['contact_group_id'], $group_array)) {
                                if ($group_name_string == "-")
                                    $group_name_string = $g_name['contact_group_name'];
                                else
                                    $group_name_string.=", " . $g_name['contact_group_name'];
                            }
                        }
                        $jj++;
                    }
                    $temp_array['group_name_string'] = $group_name_string;
                    $contacts_array[] = $temp_array;
                }
            }
            return $contacts_array;
        } else {
            return false;
        }
    }

    // Update Group Name
    function updateGroupName($user_id = 0, $group_id = 0, $group_name = null) {
        $this->db->select('`contact_group_id`');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`contact_group_name`', $group_name);
        $query = $this->db->get();
        $count = $query->num_rows();
        if ($count > 0) {
            return false;
        } else {
            $data = array(
                'contact_group_name' => $group_name
            );
            $this->db->where('contact_group_id', $group_id);
            return $this->db->update('contact_groups', $data);
        }
    }

    // Delete Contact Group
    function deleteContactGroup($group_id = 0, $user_id = 0) {

//        $result_array = $this->getGroupContacts($user_id, $group_id);
//        if ($result_array) {
//            foreach ($result_array as $value) {
//                $id_array = explode(',', $value['contact_group_ids']);
//                if (in_array($group_id, $id_array)) {
//                    if (sizeof($id_array) == 1) {
//                        $this->db->delete('contacts', array('contact_group_ids' => $id_array[0]));
//                    } else {
//                        $key = array_search($group_id, $id_array);
//                        unset($id_array[$key]);
//                        $new_group_ids = implode(',', $id_array);
//                        var_dump($new_group_ids);die;
//                        $data = array(
//                            'contact_group_ids' => $new_group_ids
//                        );
//                        $this->db->where('user_id', $user_id);
//                        $this->db->update('contacts', $data);
//                    }
//                }
//            }
//        }
//        return $this->db->delete('contact_groups', array('contact_group_id' => $group_id));
        $this->db->delete('contacts', array('contact_group_ids' => $group_id, 'user_id' => $user_id));
        return $this->db->delete('contact_groups', array('contact_group_id' => $group_id, 'user_id' => $user_id));
    }

    // Save Contacts
    function saveContact1($user_id = 0, $contact_id = 0) {
        if ($contact_id) {
            $mobile_no = $this->input->post('mobile_no');
            if (strlen($mobile_no) == 10) {
                $mobile_no = "91" . $mobile_no;
            }
            // Check This Number In DB
            $this->db->select('`mobile_number`, contact_name, contact_group_ids');
            $this->db->from('`contacts`');
            $this->db->where('`user_id`', $user_id);
            $this->db->where('`mobile_number`', $mobile_no);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result = $query->row();
                $group_ids_array1 = explode(',', $result->contact_group_ids);
                // Count Total Contacts In A Group
                foreach ($group_ids_array1 as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }

                $name = $this->input->post('name');
                $group_id_array2 = $this->input->post('groups');
                // Count Total Contacts In A Group
                foreach ($group_id_array2 as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }

                $group_id_string = implode(',', $group_id_array2);
                $data = array(
                    'contact_name' => $name,
                    'contact_group_ids' => $group_id_string
                );
                $this->db->where('contact_id', $contact_id);
                return $this->db->update('contacts', $data);
            } else {
                $name = $this->input->post('name');
                $group_id_array = $this->input->post('groups');
                // Count Total Contacts In A Group
                foreach ($group_id_array as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }
                $group_id_string = implode(',', $group_id_array);
                $data = array(
                    'contact_name' => $name,
                    'mobile_number' => $mobile_no,
                    'contact_group_ids' => $group_id_string
                );
                $this->db->where('contact_id', $contact_id);
                return $this->db->update('contacts', $data);
            }
        } else {
            $mobile_no = $this->input->post('mobile_no');
            if (strlen($mobile_no) == 10) {
                $mobile_no = "91" . $mobile_no;
            }
            // Check This Number In DB
            $this->db->select('`mobile_number`, contact_name, contact_group_ids');
            $this->db->from('`contacts`');
            $this->db->where('`user_id`', $user_id);
            $this->db->where('`mobile_number`', $mobile_no);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result = $query->row();
                $group_ids_array1 = explode(',', $result->contact_group_ids);
                // Count Total Contacts In A Group
                foreach ($group_ids_array1 as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }

                $name = $this->input->post('name');
                $group_id_array2 = $this->input->post('groups');
                $union_array = array_unique(array_merge($group_ids_array1, $group_id_array2));

                // Count Total Contacts In A Group
                foreach ($union_array as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }

                $group_id_string = implode(',', $union_array);
                $data = array(
                    'contact_name' => $name,
                    'contact_group_ids' => $group_id_string
                );
                $this->db->where('user_id', $user_id);
                $this->db->where('`mobile_number`', $mobile_no);
                return $this->db->update('contacts', $data);
            } else {
                $name = $this->input->post('name');
                $group_id_array = $this->input->post('groups');
                // Count Total Contacts In A Group
                foreach ($group_id_array as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }
                /*
                  foreach ($group_id_array as $group_id) {
                  $this->db->select('`total_contacts`');
                  $this->db->from('`contact_groups`');
                  $this->db->where('`contact_group_id`', $group_id);
                  $query = $this->db->get();
                  $result = $query->row();
                  $total_contacts = $result->total_contacts + 1;
                  $data = array(
                  'total_contacts' => $total_contacts
                  );
                  $this->db->where('`contact_group_id`', $group_id);
                  $this->db->update('contact_groups', $data);
                  }
                 */
                $group_id_string = implode(',', $group_id_array);

                $data = array(
                    'contact_name' => $name,
                    'mobile_number' => $mobile_no,
                    'contact_group_ids' => $group_id_string,
                    'user_id' => $user_id
                );

                return $this->db->insert('contacts', $data);
            }
        }
    }

    // Check Mobile No Has Been Exist Or Not
    function checkContactNo($user_id = 0, $mobile_no = null, $contact_id = 0) {
        $this->db->select('`contact_id`, `mobile_number`, `contact_name`, `extra_column_ids`, `extra_column_values`, `contact_group_ids`');
        $this->db->from('`contacts`');
        $this->db->where('`user_id`', $user_id);
        if ($contact_id == null) {
            $this->db->where('`mobile_number`', $mobile_no);
            $query_contact = $this->db->get();
            if ($query_contact->num_rows()) {
                return $query_contact->row();
            } else {
                return false;
            }
            //backup    return $query_contact->num_rows();
        }
        if ($mobile_no == null) {
            $this->db->where('`contact_id`', $contact_id);
            $query_contact = $this->db->get();
            if ($query_contact->num_rows()) {
                return $query_contact->row();
            } else {
                return false;
            }
        }
    }

    // Save Contacts
    function saveContact($user_id = 0, $contact_id = 0) {
        if ($contact_id) {
            $mobile_no = $this->input->post('mobile_no');
            if (strlen($mobile_no) == 10) {
                $mobile_no = "91" . $mobile_no;
            }
            // Check This Number In DB
            $result_contact = $this->checkContactNo($user_id, null, $contact_id);
            if ($result_contact) {
                // From DB
                $mobile_number = $result_contact->mobile_number;
                $extra_column_ids_db = explode('|', $result_contact->extra_column_ids);
                $extra_column_values_db = explode('|', $result_contact->extra_column_values);
                $extra_column_gid_db = explode(',', $result_contact->contact_group_ids);
                // From Form
                $name = $this->input->post('name');
                $group_id_array = $this->input->post('groups');
                /* ================================================================ */
                // Decrease Total Contacts In A Group
                if (sizeof($extra_column_gid_db)) {
                    foreach ($extra_column_gid_db as $group_id) {
                        $this->db->select('`total_contacts`, `extra_column_ids`, `extra_column_names`, `extra_column_types`, `extra_column_status`');
                        $this->db->from('`contact_groups`');
                        $this->db->where('`contact_group_id`', $group_id);
                        $query_contactg = $this->db->get();
                        if ($query_contactg->num_rows()) {
                            $result_contactg = $query_contactg->row();
                            // If Total Cntacts will be 0 in particular group
                            if ($result_contactg->total_contacts == 1) {
                                $data = array(
                                    'total_contacts' => 0,
                                    'extra_column_ids' => '',
                                    'extra_column_names' => '',
                                    'extra_column_types' => '',
                                    'extra_column_status' => ''
                                );
                                $this->db->where('`contact_group_id`', $group_id);
                                $this->db->update('contact_groups', $data);
                            } else {
                                $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                                $this->db->where('`contact_group_id`', $group_id);
                                $this->db->update('contact_groups');
                            }
                        }
                    }
                }
                // Increase Total Contacts In A Group
                if (sizeof($group_id_array)) {
                    foreach ($group_id_array as $group_id) {
                        $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                        $this->db->where('`contact_group_id`', $group_id);
                        $this->db->update('contact_groups');
                    }
                }
                /* ================================================================ */
                // Extra Fields (Form)
                $extra_field_ids_array = array();
                $extra_field_names_array = array();
                $extra_field_types_array = array();
                $extra_field_values_array = array();
                if ($this->input->post('extra_field_names')) {
                    $extra_field_ids_array = $this->input->post('extra_field_ids');
                    $extra_field_names_array = $this->input->post('extra_field_names');
                    $extra_field_types_array = $this->input->post('extra_field_types');
                    $extra_field_values_array = $this->input->post('extra_field_values');
                }
                /* ================================================================ */
                // New Fields (Form)
                $new_field_ids_array = array();
                $new_field_names_array = array();
                $new_field_types_array = array();
                $new_field_values_array = array();
                if ($this->input->post('new_field_names')) {
                    if (array_filter($this->input->post('new_field_names'))) {
                        $new_field_ids_array = $this->input->post('new_field_ids');
                        $new_field_names_array = $this->input->post('new_field_names');
                        $new_field_types_array = $this->input->post('new_field_types');
                        $new_field_values_array = $this->input->post('new_field_values');
                    }
                }
                /* ================================================================ */
                // If Already Exists
                if ($mobile_number == $mobile_no) {
                    $extra_name_array = array_unique(array_merge($extra_field_names_array, $new_field_names_array));
                    // All Fields
                    $field_ids = "";
                    $field_names = "";
                    $field_types = "";
                    $field_values = "";
                    $field_status = "";
                    if (sizeof($extra_name_array)) {
                        foreach ($extra_name_array as $key => $extra) {
                            if (empty($field_ids)) {
                                if (in_array($extra, $extra_field_names_array)) {
                                    $search_key = array_search($extra, $extra_field_names_array);
                                    $field_ids = $extra_field_ids_array[$search_key];
                                    $field_names = $extra;
                                    $field_types = $extra_field_types_array[$search_key];
                                    if (empty($extra_field_values_array[$search_key])) {
                                        if (in_array($extra, $new_field_names_array)) {
                                            $search_key = array_search($extra, $new_field_names_array);
                                            $field_values = $new_field_values_array[$search_key];
                                        } else {
                                            $field_values = $extra_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values = $extra_field_values_array[$search_key];
                                    }
                                    $field_status = '1';
                                } elseif (in_array($extra, $new_field_names_array)) {
                                    $search_key = array_search($extra, $new_field_names_array);
                                    $field_ids = $new_field_ids_array[$search_key];
                                    $field_names = $extra;
                                    $field_types = $new_field_types_array[$search_key];
                                    if (empty($new_field_values_array[$search_key])) {
                                        if (in_array($extra, $extra_field_values_array)) {
                                            $search_key = array_search($extra, $extra_field_values_array);
                                            $field_values = $extra_field_values_array[$search_key];
                                        } else {
                                            $field_values = $new_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values = $new_field_values_array[$search_key];
                                    }
                                    $field_status = '1';
                                }
                            } else {
                                if (in_array($extra, $extra_field_names_array)) {
                                    $search_key = array_search($extra, $extra_field_names_array);
                                    $field_ids .="|" . $extra_field_ids_array[$search_key];
                                    $field_names .="|" . $extra;
                                    $field_types .="|" . $extra_field_types_array[$search_key];
                                    if (empty($extra_field_values_array[$search_key])) {
                                        if (in_array($extra, $new_field_names_array)) {
                                            $search_key = array_search($extra, $new_field_names_array);
                                            $field_values .="|" . $new_field_values_array[$search_key];
                                        } else {
                                            $field_values .="|" . $extra_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    }
                                    $field_status.="|1";
                                } elseif (in_array($extra, $new_field_names_array)) {
                                    $search_key = array_search($extra, $new_field_names_array);
                                    $field_ids .="|" . $new_field_ids_array[$search_key];
                                    $field_names .="|" . $extra;
                                    $field_types .="|" . $new_field_types_array[$search_key];
                                    if (empty($new_field_values_array[$search_key])) {
                                        if (in_array($extra, $extra_field_values_array)) {
                                            $search_key = array_search($extra, $extra_field_values_array);
                                            $field_values .="|" . $extra_field_values_array[$search_key];
                                        } else {
                                            $field_values .="|" . $new_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    }
                                    $field_status .="|1";
                                }
                            }
                        }
                    }
                    /* ================================================================ */
                    // Update Data Into Contact Group
                    if (sizeof($group_id_array)) {
                        foreach ($group_id_array as $group_id) {
                            $data = array(
                                'extra_column_ids' => $field_ids,
                                'extra_column_names' => $field_names,
                                'extra_column_types' => $field_types,
                                'extra_column_status' => $field_status
                            );
                            $this->db->where('`contact_group_id`', $group_id);
                            $this->db->update('contact_groups', $data);
                        }
                    }
                    /* ================================================================ */
                    // Update Data Into Contacts
                    //$group_id_string = implode(',', $group_id_array);
                    $field_ids_array = explode('|', $field_ids);
                    $field_values_array = explode('|', $field_values);
                    $field_values1 = implode('|', $field_values_array);
                    /*
                      $common_field_ids_array = array_unique(array_merge($extra_column_ids_db, $field_ids_array));
                      if (sizeof($common_field_ids_array)) {
                      foreach ($common_field_ids_array as $key => $field) {
                      if (empty($field_values1)) {
                      if (in_array($field, $field_ids_array)) {
                      $search_key = array_search($field, $field_ids_array);
                      if (empty($field_values_array[$search_key])) {
                      $field_values1 = $extra_column_values_db[$key];
                      } else {
                      $field_values1 = $field_values_array[$search_key];
                      }
                      } else {
                      $field_values1 = $field_values_array[$search_key];
                      }
                      } else {
                      if (in_array($field, $field_ids_array)) {
                      $search_key = array_search($field, $field_ids_array);
                      if (empty($field_values_array[$search_key])) {
                      $field_values1 .= "|" . $extra_column_values_db[$key];
                      } else {
                      $field_values1 .= "|" . $field_values_array[$search_key];
                      }
                      } else {
                      $field_values1 .= "|" . $field_values_array[$search_key];
                      }
                      }
                      }
                      }
                     */
                    //$field_ids = implode('|', $common_field_ids_array);
                    $group_id_string = implode(',', $group_id_array);
                    $data = array(
                        'contact_name' => $name,
                        'mobile_number' => $mobile_no,
                        'extra_column_ids' => $field_ids,
                        'extra_column_values' => $field_values1,
                        'contact_group_ids' => $group_id_string
                    );
                    $this->db->where('contact_id', $contact_id);
                    return $this->db->update('contacts', $data);
                } else {
                    $extra_name_array = array_unique(array_merge($extra_field_names_array, $new_field_names_array));
                    // All Fields
                    $field_ids = "";
                    $field_names = "";
                    $field_types = "";
                    $field_values = "";
                    $field_status = "";
                    if (sizeof($extra_name_array)) {
                        foreach ($extra_name_array as $key => $extra) {
                            if (empty($field_ids)) {
                                if (in_array($extra, $extra_field_names_array)) {
                                    $search_key = array_search($extra, $extra_field_names_array);
                                    $field_ids = $extra_field_ids_array[$search_key];
                                    $field_names = $extra;
                                    $field_types = $extra_field_types_array[$search_key];
                                    if (empty($extra_field_values_array[$search_key])) {
                                        if (in_array($extra, $new_field_names_array)) {
                                            $search_key = array_search($extra, $new_field_names_array);
                                            $field_values = $new_field_values_array[$search_key];
                                        } else {
                                            $field_values = $extra_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values = $extra_field_values_array[$search_key];
                                    }
                                    $field_status = '1';
                                } elseif (in_array($extra, $new_field_names_array)) {
                                    $search_key = array_search($extra, $new_field_names_array);
                                    $field_ids = $new_field_ids_array[$search_key];
                                    $field_names = $extra;
                                    $field_types = $new_field_types_array[$search_key];
                                    if (empty($new_field_values_array[$search_key])) {
                                        if (in_array($extra, $extra_field_values_array)) {
                                            $search_key = array_search($extra, $extra_field_values_array);
                                            $field_values = $extra_field_values_array[$search_key];
                                        } else {
                                            $field_values = $new_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values = $new_field_values_array[$search_key];
                                    }
                                    $field_status = '1';
                                }
                            } else {
                                if (in_array($extra, $extra_field_names_array)) {
                                    $search_key = array_search($extra, $extra_field_names_array);
                                    $field_ids .="|" . $extra_field_ids_array[$search_key];
                                    $field_names .="|" . $extra;
                                    $field_types .="|" . $extra_field_types_array[$search_key];
                                    if (empty($extra_field_values_array[$search_key])) {
                                        if (in_array($extra, $new_field_names_array)) {
                                            $search_key = array_search($extra, $new_field_names_array);
                                            $field_values .="|" . $new_field_values_array[$search_key];
                                        } else {
                                            $field_values .="|" . $extra_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    }
                                    $field_status.="|1";
                                } elseif (in_array($extra, $new_field_names_array)) {
                                    $search_key = array_search($extra, $new_field_names_array);
                                    $field_ids .="|" . $new_field_ids_array[$search_key];
                                    $field_names .="|" . $extra;
                                    $field_types .="|" . $new_field_types_array[$search_key];
                                    if (empty($new_field_values_array[$search_key])) {
                                        if (in_array($extra, $extra_field_values_array)) {
                                            $search_key = array_search($extra, $extra_field_values_array);
                                            $field_values .="|" . $extra_field_values_array[$search_key];
                                        } else {
                                            $field_values .="|" . $new_field_values_array[$search_key];
                                        }
                                    } else {
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    }
                                    $field_status .="|1";
                                }
                            }
                        }
                    }
                    /* ================================================================ */
                    // Update Data Into Contact Group
                    foreach ($group_id_array as $group_id) {
                        $data = array(
                            'extra_column_ids' => $field_ids,
                            'extra_column_names' => $field_names,
                            'extra_column_types' => $field_types,
                            'extra_column_status' => $field_status
                        );
                        $this->db->where('`contact_group_id`', $group_id);
                        $this->db->update('contact_groups', $data);
                    }
                    /* ================================================================ */
                    // Update Data Into Contacts
                    $group_id_string = implode(',', $group_id_array);
                    $data = array(
                        'contact_name' => $name,
                        'mobile_number' => $mobile_no,
                        'extra_column_ids' => $field_ids,
                        'extra_column_values' => $field_values,
                        'contact_group_ids' => $group_id_string
                    );
                    $this->db->where('contact_id', $contact_id);
                    return $this->db->update('contacts', $data);
                }
            } else {
                return false;
            }
        } else {
            $mobile_no = $this->input->post('mobile_no');
            if (strlen($mobile_no) == 10) {
                $mobile_no = "91" . $mobile_no;
            }
            // Check This Number In DB
            $result_contact = $this->checkContactNo($user_id, $mobile_no, null);
            // If Already Exists
            if ($result_contact) {
                // From DB
                $extra_column_ids_db = explode('|', $result_contact->extra_column_ids);
                $extra_column_values_db = explode('|', $result_contact->extra_column_values);
                $extra_column_gid_db = explode(',', $result_contact->contact_group_ids);
                // From Form
                $name = $this->input->post('name');
                $group_id_array = $this->input->post('groups');
                $array_unique = array_unique(array_merge($extra_column_gid_db, $group_id_array));
                /* ================================================================ */
                // Decrease Total Contacts In A Group
                if (sizeof($extra_column_gid_db)) {
                    foreach ($extra_column_gid_db as $group_id) {
                        $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                        $this->db->where('`contact_group_id`', $group_id);
                        $this->db->update('contact_groups');
                    }
                }
                // Increase Total Contacts In A Group
                if (sizeof($array_unique)) {
                    foreach ($array_unique as $group_id) {
                        $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                        $this->db->where('`contact_group_id`', $group_id);
                        $this->db->update('contact_groups');
                    }
                }
                /* ================================================================ */
                // Extra Fields (Form)
                $extra_field_ids_array = array();
                $extra_field_names_array = array();
                $extra_field_types_array = array();
                $extra_field_values_array = array();
                if ($this->input->post('extra_field_names')) {
                    $extra_field_ids_array = $this->input->post('extra_field_ids');
                    $extra_field_names_array = $this->input->post('extra_field_names');
                    $extra_field_types_array = $this->input->post('extra_field_types');
                    $extra_field_values_array = $this->input->post('extra_field_values');
                }
                /* ================================================================ */
                // New Fields (Form)
                $new_field_ids_array = array();
                $new_field_names_array = array();
                $new_field_types_array = array();
                $new_field_values_array = array();
                if ($this->input->post('new_field_names')) {
                    if (array_filter($this->input->post('new_field_names'))) {
                        $new_field_ids_array = $this->input->post('new_field_ids');
                        $new_field_names_array = $this->input->post('new_field_names');
                        $new_field_types_array = $this->input->post('new_field_types');
                        $new_field_values_array = $this->input->post('new_field_values');
                    }
                }
                /* ================================================================ */
                $extra_name_array = array_unique(array_merge($extra_field_names_array, $new_field_names_array));
                // All Fields
                $field_ids = "";
                $field_names = "";
                $field_types = "";
                $field_values = "";
                $field_status = "";
                if (sizeof($extra_name_array)) {
                    foreach ($extra_name_array as $key => $extra) {
                        if (empty($field_ids)) {
                            if (in_array($extra, $extra_field_names_array)) {
                                $search_key = array_search($extra, $extra_field_names_array);
                                $field_ids = $extra_field_ids_array[$search_key];
                                $field_names = $extra;
                                $field_types = $extra_field_types_array[$search_key];
                                if (empty($extra_field_values_array[$search_key])) {
                                    if (in_array($extra, $new_field_names_array)) {
                                        $search_key = array_search($extra, $new_field_names_array);
                                        $field_values = $new_field_values_array[$search_key];
                                    } else {
                                        $field_values = $extra_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values = $extra_field_values_array[$search_key];
                                }
                                $field_status = '1';
                            } elseif (in_array($extra, $new_field_names_array)) {
                                $search_key = array_search($extra, $new_field_names_array);
                                $field_ids = $new_field_ids_array[$search_key];
                                $field_names = $extra;
                                $field_types = $new_field_types_array[$search_key];
                                if (empty($new_field_values_array[$search_key])) {
                                    if (in_array($extra, $extra_field_values_array)) {
                                        $search_key = array_search($extra, $extra_field_values_array);
                                        $field_values = $extra_field_values_array[$search_key];
                                    } else {
                                        $field_values = $new_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values = $new_field_values_array[$search_key];
                                }
                                $field_status = '1';
                            }
                        } else {
                            if (in_array($extra, $extra_field_names_array)) {
                                $search_key = array_search($extra, $extra_field_names_array);
                                $field_ids .="|" . $extra_field_ids_array[$search_key];
                                $field_names .="|" . $extra;
                                $field_types .="|" . $extra_field_types_array[$search_key];
                                if (empty($extra_field_values_array[$search_key])) {
                                    if (in_array($extra, $new_field_names_array)) {
                                        $search_key = array_search($extra, $new_field_names_array);
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    } else {
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values .="|" . $extra_field_values_array[$search_key];
                                }
                                $field_status.="|1";
                            } elseif (in_array($extra, $new_field_names_array)) {
                                $search_key = array_search($extra, $new_field_names_array);
                                $field_ids .="|" . $new_field_ids_array[$search_key];
                                $field_names .="|" . $extra;
                                $field_types .="|" . $new_field_types_array[$search_key];
                                if (empty($new_field_values_array[$search_key])) {
                                    if (in_array($extra, $extra_field_values_array)) {
                                        $search_key = array_search($extra, $extra_field_values_array);
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    } else {
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values .="|" . $new_field_values_array[$search_key];
                                }
                                $field_status .="|1";
                            }
                        }
                    }
                }
                /* ================================================================ */
                // Update Data Into Contact Group
                foreach ($group_id_array as $group_id) {
                    $data = array(
                        'extra_column_ids' => $field_ids,
                        'extra_column_names' => $field_names,
                        'extra_column_types' => $field_types,
                        'extra_column_status' => $field_status
                    );
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups', $data);
                }
                /* ================================================================ */
                // Update Data Into Contacts
                //$group_id_string = implode(',', $group_id_array);
                $field_ids_array = explode('|', $field_ids);
                $field_values_array = explode('|', $field_values);
                $field_values1 = "";
                $common_field_ids_array = array_unique(array_merge($extra_column_ids_db, $field_ids_array));
                foreach ($common_field_ids_array as $key => $field) {
                    if (empty($field_values1)) {
                        if (in_array($field, $field_ids_array)) {
                            $search_key = array_search($field, $field_ids_array);
                            if (empty($field_values_array[$search_key])) {
                                $field_values1 = " ";
                            } else {
                                $field_values1 = $field_values_array[$search_key];
                            }
                        } else {
                            if (empty($extra_column_values_db[$search_key])) {
                                $field_values1 = " ";
                            } else {
                                $field_values1 = $extra_column_values_db[$search_key];
                            }
                        }
                    } else {
                        if (in_array($field, $field_ids_array)) {
                            $search_key = array_search($field, $field_ids_array);
                            if (empty($field_values_array[$search_key])) {
                                $field_values1 .= "| ";
                            } else {
                                $field_values1 .= "|" . $field_values_array[$search_key];
                            }
                        } else {
                            if (empty($extra_column_values_db[$search_key])) {
                                $field_values1 .= "| ";
                            } else {
                                $field_values1 .= "|" . $extra_column_values_db[$search_key];
                            }
                        }
                    }
                }
                $field_ids = implode('|', $common_field_ids_array);
                $array = array_unique(array_merge($extra_column_gid_db, $group_id_array));
                asort($array);
                $group_id_string = implode(',', $array);
                $data = array(
                    'contact_name' => $name,
                    'extra_column_ids' => $field_ids,
                    'extra_column_values' => $field_values1,
                    'contact_group_ids' => $group_id_string
                );
                $this->db->where('user_id', $user_id);
                $this->db->where('`mobile_number`', $mobile_no);
                return $this->db->update('contacts', $data);
            } else {
                $name = $this->input->post('name');
                $group_id_array = $this->input->post('groups');
                /* ================================================================ */
                // Extra Fields (Form)
                $extra_field_ids_array = array();
                $extra_field_names_array = array();
                $extra_field_types_array = array();
                $extra_field_values_array = array();
                if ($this->input->post('extra_field_names')) {
                    $extra_field_ids_array = $this->input->post('extra_field_ids');
                    $extra_field_names_array = $this->input->post('extra_field_names');
                    $extra_field_types_array = $this->input->post('extra_field_types');
                    $extra_field_values_array = $this->input->post('extra_field_values');
                }
                /* ================================================================ */
                // New Fields (Form)
                $new_field_ids_array = array();
                $new_field_names_array = array();
                $new_field_types_array = array();
                $new_field_values_array = array();
                if ($this->input->post('new_field_names')) {
                    if (array_filter($this->input->post('new_field_names'))) {
                        $new_field_ids_array = $this->input->post('new_field_ids');
                        $new_field_names_array = $this->input->post('new_field_names');
                        $new_field_types_array = $this->input->post('new_field_types');
                        $new_field_values_array = $this->input->post('new_field_values');
                    }
                }
                /* ================================================================ */
                $extra_name_array = array_unique(array_merge($extra_field_names_array, $new_field_names_array));
                // All Fields
                $field_ids = "";
                $field_names = "";
                $field_types = "";
                $field_values = "";
                $field_status = "";
                if (sizeof($extra_name_array)) {
                    foreach ($extra_name_array as $key => $extra) {
                        if (empty($field_ids)) {
                            if (in_array($extra, $extra_field_names_array)) {
                                $search_key = array_search($extra, $extra_field_names_array);
                                $field_ids = $extra_field_ids_array[$search_key];
                                $field_names = $extra;
                                $field_types = $extra_field_types_array[$search_key];
                                if (empty($extra_field_values_array[$search_key])) {
                                    if (in_array($extra, $new_field_names_array)) {
                                        $search_key = array_search($extra, $new_field_names_array);
                                        $field_values = $new_field_values_array[$search_key];
                                    } else {
                                        $field_values = $extra_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values = $extra_field_values_array[$search_key];
                                }
                                $field_status = '1';
                            } elseif (in_array($extra, $new_field_names_array)) {
                                $search_key = array_search($extra, $new_field_names_array);
                                $field_ids = $new_field_ids_array[$search_key];
                                $field_names = $extra;
                                $field_types = $new_field_types_array[$search_key];
                                if (empty($new_field_values_array[$search_key])) {
                                    if (in_array($extra, $extra_field_values_array)) {
                                        $search_key = array_search($extra, $extra_field_values_array);
                                        $field_values = $extra_field_values_array[$search_key];
                                    } else {
                                        $field_values = $new_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values = $new_field_values_array[$search_key];
                                }
                                $field_status = '1';
                            }
                        } else {
                            if (in_array($extra, $extra_field_names_array)) {
                                $search_key = array_search($extra, $extra_field_names_array);
                                $field_ids .="|" . $extra_field_ids_array[$search_key];
                                $field_names .="|" . $extra;
                                $field_types .="|" . $extra_field_types_array[$search_key];
                                if (empty($extra_field_values_array[$search_key])) {
                                    if (in_array($extra, $new_field_names_array)) {
                                        $search_key = array_search($extra, $new_field_names_array);
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    } else {
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values .="|" . $extra_field_values_array[$search_key];
                                }
                                $field_status.="|1";
                            } elseif (in_array($extra, $new_field_names_array)) {
                                $search_key = array_search($extra, $new_field_names_array);
                                $field_ids .="|" . $new_field_ids_array[$search_key];
                                $field_names .="|" . $extra;
                                $field_types .="|" . $new_field_types_array[$search_key];
                                if (empty($new_field_values_array[$search_key])) {
                                    if (in_array($extra, $extra_field_values_array)) {
                                        $search_key = array_search($extra, $extra_field_values_array);
                                        $field_values .="|" . $extra_field_values_array[$search_key];
                                    } else {
                                        $field_values .="|" . $new_field_values_array[$search_key];
                                    }
                                } else {
                                    $field_values .="|" . $new_field_values_array[$search_key];
                                }
                                $field_status .="|1";
                            }
                        }
                    }
                }
                /* ================================================================ */
                // Increase Total Contacts In A Group
                foreach ($group_id_array as $group_id) {
                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups');
                }
                // Update Contact Group Info
                foreach ($group_id_array as $group_id) {
                    $data = array(
                        'extra_column_ids' => $field_ids,
                        'extra_column_names' => $field_names,
                        'extra_column_types' => $field_types,
                        'extra_column_status' => $field_status
                    );
                    $this->db->where('`contact_group_id`', $group_id);
                    $this->db->update('contact_groups', $data);
                }
                /* ================================================================ */
                // Insert Contact Info
                $group_id_string = implode(',', $group_id_array);

                $data = array(
                    'contact_name' => $name,
                    'mobile_number' => $mobile_no,
                    'extra_column_ids' => $field_ids,
                    'extra_column_values' => $field_values,
                    'contact_group_ids' => $group_id_string,
                    'user_id' => $user_id
                );

                return $this->db->insert('contacts', $data);
            }
        }
    }

    // Get Contact Info
    function getContactInfo($contact_id = 0) {
        $this->db->select('`contact_id`, `mobile_number`, `contact_name`, `contact_group_ids`, extra_column_values, extra_column_ids');
        $this->db->from('`contacts`');
        $this->db->where('`contact_id`', $contact_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Get Groups Names
    function getGroupName($user_id = 0) {
        $this->db->select('contact_group_name, contact_group_id');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Groups Ids
    function getGroupId($user_id = 0) {
        $this->db->select('contact_group_id');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Get Selected Contacts
    function getSelectedContacts($selected_contact_array = null) {
        $this->db->select('`contact_id`, `mobile_number`, `contact_name`, `contact_group_ids`');
        $this->db->from('`contacts`');
        $this->db->where_in('`contact_id`', $selected_contact_array);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Delete Contacts
    function deleteContacts($group_id = 0, $user_id = 0) {
        $selected_contacts = $this->input->post('selected_contacts');
        $selected_contact_array = explode(',', $selected_contacts);
        $total_contacts = sizeof($selected_contact_array);
        $result_array = $this->getSelectedContacts($selected_contact_array);
        foreach ($result_array as $value) {
            $contact_id = $value['contact_id'];
            $group_id_array = explode(',', $value['contact_group_ids']);
            if (sizeof($group_id_array) == 1 && in_array($group_id, $group_id_array)) {
                $this->db->delete('contacts', array('contact_id' => $contact_id));
            } else {
                $key = array_search($group_id, $group_id_array);
                unset($group_id_array[$key]);
                $new_group_ids = implode(',', $group_id_array);
                $data = array(
                    'contact_group_ids' => $new_group_ids
                );
                $this->db->where('contact_id', $contact_id);
                $this->db->update('contacts', $data);
            }
        }
        // Update Count
        $this->db->set('total_contacts', '`total_contacts`-' . $total_contacts, FALSE);
        $this->db->where('`contact_group_id`', $group_id);
        $this->db->update('contact_groups');
        return true;
    }

    // Import Contact CSV
    function importContactCSV($user_id = 0) {
        $return_array = array();
        $temp_file_name = date('dmYhis') . "-" . $user_id;
        $config['file_name'] = $temp_file_name;
        $config['upload_path'] = './ContactCSV/';
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('contact_csv', $temp_file_name)) {
            $return_array['temp_file_name'] = $temp_file_name . ".csv";
            return $return_array;
        } else {
            return false;
        }
    }

    // Save CSV Contacts
    function saveCSVContact($user_id = 0) {
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '1073741824');
        $temp_file_name = $this->input->post('temp_file_name');
        $total_columns = $this->input->post('total_columns');
        $column_array = array();
        $number_field = 0;
        $name_field = 0;
        $full_data = array();
        $field_id_array = array();
        $field_name_array = array();
        $field_type_array = array();
        $field_status_array = array();
        $field_key_array = array();
        $new_fields = array();
        $old_fields = array();
        for ($i = 1; $i <= $total_columns; $i++) {
            if ($this->input->post('action' . $i)) {
                $column_array[] = $i;
                if ($this->input->post('field_name' . $i) == 'number') {
                    $number_field = $i;
                } elseif ($this->input->post('field_name' . $i) == 'name') {
                    $name_field = $i;
                } elseif ($this->input->post('field_name' . $i) == '_add_') {
                    $field_id_array[] = $this->input->post('new_field_id' . $i);
                    $field_name_array[] = $this->input->post('new_field_name' . $i);
                    $field_type_array[] = $this->input->post('new_field_type' . $i);
                    $field_status_array[] = 1;
                    $field_key_array[] = $i - 1;
                    $new_fields[$i - 1] = $this->input->post('new_field_id' . $i);
                } else {
                    $temp = explode('|', $this->input->post('field_name' . $i));
                    $field_id_array[] = $temp[0];
                    $field_name_array[] = $temp[1];
                    $field_type_array[] = $temp[2];
                    $field_status_array[] = $temp[3];
                    $field_key_array[] = $i - 1;
                    $old_fields[$i - 1] = $temp[0];
                }
            }
        }
        $field_id_string = implode('|', $field_id_array);
        $group_id_array = $this->input->post('groups');
        // Contact Group Info
        $new_id_array = array();
        $this->db->select('`contact_group_id`, `extra_column_ids`, `extra_column_names`, `extra_column_types`, `extra_column_status`');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where_in('`contact_group_id`', $group_id_array);
        $query_cgroup = $this->db->get();
        if ($query_cgroup->num_rows()) {
            $result_cgroup = $query_cgroup->result();
            foreach ($result_cgroup as $key => $row_cgroup) {
                $update_ids = "";
                $update_names = "";
                $update_types = "";
                $update_status = "";
                $extra_column_ids = explode('|', $row_cgroup->extra_column_ids);
                $extra_column_names = explode('|', $row_cgroup->extra_column_names);
                $extra_column_types = explode('|', $row_cgroup->extra_column_types);
                $extra_column_status = explode('|', $row_cgroup->extra_column_status);
                // Already Have Extra Columns
                if ($row_cgroup->extra_column_ids != "") {
                    $result_id_array = array_intersect($extra_column_ids, $field_id_array);
                    // If Common Ids
                    if (sizeof($result_id_array)) {
                        $result_id_array1 = array_diff($field_id_array, $extra_column_ids);
                        //$update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_ids = $row_cgroup->extra_column_ids;
                        $update_names = $row_cgroup->extra_column_names;
                        $update_types = $row_cgroup->extra_column_types;
                        $update_status = $row_cgroup->extra_column_status;
                        if (sizeof($result_id_array1)) {
                            foreach ($result_id_array1 as $key => $id) {
                                if (in_array($id, $field_id_array)) {
                                    $search_array = array_search($id, $field_id_array);
                                    $update_ids.="|" . $field_id_array[$search_array];
                                    $update_names.="|" . $field_name_array[$search_array];
                                    $update_types.="|" . $field_type_array[$search_array];
                                    $update_status.="|" . 1;
                                }
                            }
                        }
                    } else {
                        $update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_names = $row_cgroup->extra_column_names . "|" . implode('|', $field_name_array);
                        $update_types = $row_cgroup->extra_column_types . "|" . implode('|', $field_type_array);
                        $update_status = $row_cgroup->extra_column_status . "|" . implode('|', $field_status_array);
                    }
                } else {
                    $update_ids = implode('|', $field_id_array);
                    $update_names = implode('|', $field_name_array);
                    $update_types = implode('|', $field_type_array);
                    $update_status = implode('|', $field_status_array);
                }
                // Update Data Into Contact Group
                $data = array(
                    'extra_column_ids' => $update_ids,
                    'extra_column_names' => $update_names,
                    'extra_column_types' => $update_types,
                    'extra_column_status' => $update_status
                );
                $this->db->where('contact_group_id', $row_cgroup->contact_group_id);
                $this->db->update('contact_groups', $data);
            }
        }
        $group_ids_string = implode(',', $group_id_array);
        $csvFile = "./ContactCSV/" . $temp_file_name;
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (!feof($handle)) {
                $line = fgetcsv($handle);
                $row++;
                if ($line != "") {
                    //$line = explode(',', trim($record));
                    if (sizeof($line)) {
                        $contact_number;
                        if ($number_field) {
                            $contact_number = $line[$number_field - 1];
                            // Check Valid Value For Contact Number
                            if (is_numeric($contact_number)) {
                                if (strlen($contact_number) == 10) {
                                    $contact_number = "91" . $contact_number;
                                }
                                /*
                                  // Check This Number In DB
                                  $this->db->select('`mobile_number`, `contact_name`, `extra_column_ids`, `extra_column_values`, `contact_group_ids`');
                                  $this->db->from('`contacts`');
                                  $this->db->where('`user_id`', $user_id);
                                  $this->db->where('`mobile_number`', $contact_number);
                                  $query_contact = $this->db->get();
                                  // If Number Already Exists
                                  if ($query_contact->num_rows()) {
                                  // Check Contact Name
                                  $contact_name = "";
                                  if ($name_field) {
                                  $contact_name = $line[$name_field - 1];
                                  }
                                  $result_contact = $query_contact->row();
                                  $ccontact_group_ids = explode(',', $result_contact->contact_group_ids);
                                  $array_diff = array_diff($group_id_array, $ccontact_group_ids);
                                  $union_array = array_unique(array_merge($ccontact_group_ids, $group_id_array));
                                  $group_id_string1 = implode(',', $union_array);
                                  $cextra_column_ids = explode('|', $result_contact->extra_column_ids);
                                  $intersect_array = array_intersect($cextra_column_ids, $field_id_array);
                                  $cextra_column_values = explode('|', $result_contact->extra_column_values);
                                  $field_id_string1 = $result_contact->extra_column_ids;
                                  $field_values_string = "";
                                  if (sizeof($intersect_array)) {
                                  foreach ($old_fields as $key => $old_id) {
                                  if (in_array($old_id, $cextra_column_ids)) {
                                  $search_key = array_search($old_id, $cextra_column_ids);
                                  $cextra_column_values[$search_key] = $line[$key];
                                  } else {
                                  $field_id_string1.="|" . $old_id;
                                  array_push($cextra_column_values, $line[$key]);
                                  }
                                  }
                                  $field_values_string = implode('|', $cextra_column_values);
                                  if (sizeof($new_fields)) {
                                  $keys = array_keys($new_fields);
                                  $field_values_string .= "|" . implode('|', array_intersect_key($line, array_flip($keys)));
                                  $field_id_string1.="|" . implode('|', $new_fields);
                                  }
                                  // Update Count
                                  // Increase
                                  foreach ($array_diff as $group_id) {
                                  $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                  $this->db->where('`contact_group_id`', $group_id);
                                  $this->db->update('contact_groups');
                                  }
                                  // Insert Contact Number
                                  $data = array(
                                  'contact_name' => $contact_name,
                                  'extra_column_ids' => $field_id_string1,
                                  'extra_column_values' => $field_values_string,
                                  'contact_group_ids' => $group_id_string1
                                  );
                                  $this->db->where('user_id', $user_id);
                                  $this->db->where('`mobile_number`', $contact_number);
                                  $this->db->update('contacts', $data);
                                  } else {
                                  // Update Count
                                  // Decrease
                                  /*
                                  foreach ($union_array as $group_id) {
                                  $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                                  $this->db->where('`contact_group_id`', $group_id);
                                  $this->db->update('contact_groups');
                                  }

                                  // Increase
                                  foreach ($array_diff as $group_id) {
                                  $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                  $this->db->where('`contact_group_id`', $group_id);
                                  $this->db->update('contact_groups');
                                  }
                                  $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));
                                  // Insert Contact Number
                                  $data = array(
                                  'contact_name' => $contact_name,
                                  'extra_column_ids' => $result_contact->extra_column_ids . "|" . $field_id_string,
                                  'extra_column_values' => $result_contact->extra_column_values . "|" . $field_values_string,
                                  'contact_group_ids' => $group_id_string1
                                  );
                                  $this->db->where('user_id', $user_id);
                                  $this->db->where('`mobile_number`', $contact_number);
                                  $this->db->update('contacts', $data);
                                  }
                                  } else {

                                 */

                                // Update Count
                                // Increase
                                foreach ($group_id_array as $group_id) {
                                    $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                    $this->db->where('`contact_group_id`', $group_id);
                                    $this->db->update('contact_groups');
                                }
                                // Check Contact Name
                                $contact_name = "";
                                if ($name_field) {
                                    $contact_name = $line[$name_field - 1];
                                }
                                $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));
                                // Insert Contact Number

                                $data = array(
                                    'contact_name' => $contact_name,
                                    'mobile_number' => $contact_number,
                                    'extra_column_ids' => $field_id_string,
                                    'extra_column_values' => $field_values_string,
                                    'contact_group_ids' => $group_ids_string,
                                    'user_id' => $user_id
                                );

                                $full_data[] = $data;
                            } else {
                                continue;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
            fclose($handle);
            $this->db->insert_batch('contacts', $full_data);
            return true;
        } else {
            return false;
        }
    }

    // Get Extra Fields
    function getExtraFields() {
        $group_id_array = explode(',', $this->input->post('group_ids'));
        if (sizeof($group_id_array)) {
            $array = array();
            foreach ($group_id_array as $key => $group_id) {
                $this->db->select('extra_column_ids, extra_column_names, extra_column_types, extra_column_status');
                $this->db->from('`contact_groups`');
                $this->db->where('`contact_group_id`', $group_id);
                $query = $this->db->get();
                if ($query->num_rows()) {
                    $result = $query->row();
                    $array[] = $result;
                }
            }
            return $array;
        } else {
            return false;
        }
    }

    // Get Extra Fields
    function getExtraFieldsForCSV($user_id = 0) {
        $this->db->select('extra_column_ids, extra_column_names, extra_column_types, extra_column_status');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $result = $query->result();
        } else {
            return false;
        }
    }

    // Delete Extra Column From Contact Group
    function deleteExtraColumn($user_id = 0) {
        $group_id = $this->input->post('group_id');
        $key = $this->input->post('key');
        $col_id = $this->input->post('col_id');
        $this->db->select('extra_column_ids, extra_column_names, extra_column_types, extra_column_status');
        $this->db->from('`contact_groups`');
        $this->db->where('`contact_group_id`', $group_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            /*
              $extra_column_status_a = explode('|', $result->extra_column_status);
              $extra_column_status_a[$key] = 0;
              $extra_column_status = implode('|', $extra_column_status_a);
              $data = array(
              'extra_column_status' => $extra_column_status
              );
              $this->db->where('`contact_group_id`', $group_id);
              return $this->db->update('contact_groups', $data);
             */
            $extra_column_ids_a = explode('|', $result->extra_column_ids);
            $extra_column_names_a = explode('|', $result->extra_column_names);
            $extra_column_types_a = explode('|', $result->extra_column_types);
            $extra_column_status_a = explode('|', $result->extra_column_status);
            unset($extra_column_ids_a[$key]);
            unset($extra_column_names_a[$key]);
            unset($extra_column_types_a[$key]);
            unset($extra_column_status_a[$key]);
            $extra_column_ids = implode('|', $extra_column_ids_a);
            $extra_column_names = implode('|', $extra_column_names_a);
            $extra_column_values = implode('|', $extra_column_types_a);
            $extra_column_status = implode('|', $extra_column_status_a);
            $data = array(
                'extra_column_ids' => $extra_column_ids,
                'extra_column_names' => $extra_column_names,
                'extra_column_types' => $extra_column_values,
                'extra_column_status' => $extra_column_status
            );
            $this->db->where('`contact_group_id`', $group_id);
            return $this->db->update('contact_groups', $data);
        } else {
            return false;
        }
    }

    // Get Extra Column Name
    function getExtraColumn($user_id = 0) {
        $column_name_array = array();
        $column_type_array = array();
        $this->db->select('extra_column_names, extra_column_status, extra_column_types');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->result();
            $temp_type_array = array();
            foreach ($result AS $row) {
                if ($row->extra_column_names != "") {
                    $column_name_array = array_merge($column_name_array, explode('|', $row->extra_column_names));
                    $temp_type_array = array_merge($temp_type_array, explode('|', $row->extra_column_types));
                }
            }
            if (sizeof($column_name_array)) {
                $column_name_array = array_unique($column_name_array);
                foreach ($column_name_array as $key => $value) {
                    $column_type_array[$key] = $temp_type_array[$key];
                }
                return array('name' => $column_name_array, 'type' => $column_type_array);
            } else
                return false;
        } else {
            return false;
        }
    }

    //get spaicial reseller status
    public function getSpacialResseller($spacial_id_user) {
        $this->db->select('spacial_reseller_status');
        $this->db->from('users');
        $this->db->where('user_id', $spacial_id_user);
        $this->db->where('spacial_reseller_status', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get User Setting
    function getUserSettings($user_id = 0) {
        $this->db->select('`user_setting_id`, `user_settings`');
        $this->db->from('`user_settings`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Save User Setting
    function saveUserSettings($user_id = 0) {
        $setting = 0;
        if ($this->input->post('setting'))
            $setting = $this->input->post('setting');
        $data = array(
            'user_settings' => $setting
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Count My Transactions
    function countTransactions($user_id = 0) {
        $this->db->select('`txn_route`, `txn_sms`, `txn_price`, `txn_amount`, `txn_type`, `txn_date`, `txn_description`');
        $this->db->select(' `userA`.`name` AS `from_name` , `userA`.`user_id` AS `from_user_id`');
        $this->db->select(' `userB`.`name` AS `to_name` , `userB`.`user_id` AS `to_user_id`');
        $this->db->select('`administratorsA`.`admin_name` AS `from_admin_name` , `administratorsA`.`admin_id` AS `from_admin_id`');
        $this->db->select(' `administratorsB`.`admin_name` AS `to_admin_name` , `administratorsB`.`admin_id` AS `to_admin_id`');
        $this->db->from('`transaction_logs`');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->where('`txn_user_from`', $user_id);
        $this->db->or_where('`txn_user_to`', $user_id);
        $this->db->order_by('`txn_log_id`', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get My Transactions
    function getTransactions($user_id = 0, $limit = 0, $start = 0) {
        $this->db->select('`txn_route`, `txn_sms`, `txn_price`, `txn_amount`, `txn_type`, `txn_date`,`txn_user_to`, `txn_user_from`,`txn_description`');
        $this->db->select(' `userA`.`name` AS `from_name` , `userA`.`user_id` AS `from_user_id`');
        $this->db->select(' `userB`.`name` AS `to_name` , `userB`.`user_id` AS `to_user_id`');
        $this->db->select('`administratorsA`.`admin_name` AS `from_admin_name` , `administratorsA`.`admin_id` AS `from_admin_id`');
        $this->db->select(' `administratorsB`.`admin_name` AS `to_admin_name` , `administratorsB`.`admin_id` AS `to_admin_id`');
        $this->db->from('`transaction_logs`');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->where('`txn_user_from`', $user_id);
        $this->db->or_where('`txn_user_to`', $user_id);
        $this->db->order_by('`txn_log_id`', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //count credits log
    function countCredits($user_id = 0) {
        $this->db->select('`balance_type`, `type`, `total_amount`, `tax_status`, `sms_price`, `balance`, `special_transaction_logs`.`admin_id`,`special_transaction_logs`.`user_id`,`date`,`date_time`,`discription`,`approval_date`');
        $this->db->select(' `users`.`name` , `users`.`user_id`');
        $this->db->select('`administrators`.`admin_name`, `administrators`.`admin_id`');
        $this->db->from('`special_transaction_logs`,`users`,`administrators`');
        $this->db->where('administrators.admin_id = special_transaction_logs.admin_id');
        $this->db->where('users.user_id = special_transaction_logs.user_id');
        $this->db->where('`special_transaction_logs`.`user_id`', $user_id);
        $this->db->order_by('`special_tr_id`', 'desc');
        $query = $this->db->get();
        return $query->num_rows();
    }

    //get My special transactions
    function getSpecialTransactions($user_id = 0, $limit = 0, $start = 0) {
        $this->db->select('`balance_type`, `type`, `total_amount`, `tax_status`, `sms_price`, `balance`, `special_transaction_logs`.`admin_id`,`special_transaction_logs`.`user_id`,`date`,`date_time`,`discription`,`approval_date`');
        $this->db->select(' `users`.`name` , `users`.`user_id`');
        $this->db->select('`administrators`.`admin_name`, `administrators`.`admin_id`');
        $this->db->from('`special_transaction_logs`,`users`,`administrators`');
        $this->db->where('administrators.admin_id = special_transaction_logs.admin_id');
        $this->db->where('users.user_id = special_transaction_logs.user_id');
        $this->db->where('`special_transaction_logs`.`user_id`', $user_id);
        $this->db->order_by('`special_tr_id`', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
            die;
        }
    }

    //count account sms log
    function DailyConsumption($user_id = 0) {
        $today_date = date('Y-m-d');

        $sms_data = array();
        $total_ids = array();
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $query = $this->db->get();
        $all_ids = $query->result_array();
        foreach ($all_ids as $ids) {
            $total_ids[] = $ids['user_id'];
        }
        $size = sizeof($total_ids);
        for ($i = 0; $i < $size; $i++) {
            $user_ids = $total_ids[$i];
            $pr_sms = 0;
            $tr_sms = 0;
            $stock_sms = 0;
            $premium_sms = 0;
            //Route A
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'A');
            $this->db->like('submit_date', $today_date, 'after');
            $query_pr_sms = $this->db->get();
            $result_pr_sms = $query_pr_sms->row();
            $pr_sms = $result_pr_sms->total_messages;

            //Route B
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'B');
            $this->db->like('submit_date', $today_date, 'after');
            $query_tr_sms = $this->db->get();
            $result_tr_sms = $query_tr_sms->row();
            $tr_sms = $result_tr_sms->total_messages;

            //Route C
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'C');
            $this->db->like('submit_date', $today_date, 'after');
            $query_stock_sms = $this->db->get();
            $result_stock_sms = $query_stock_sms->row();
            $stock_sms = $result_stock_sms->total_messages;

            //Route D
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'D');
            $this->db->like('submit_date', $today_date, 'after');
            $query_premium_sms = $this->db->get();
            $result_premium_sms = $query_premium_sms->row();
            $premium_sms = $result_premium_sms->total_messages;

            $sms_data[] = $data = array(
                'user_id' => $user_ids,
                'promotional_sms' => $pr_sms,
                'transactional_sms' => $tr_sms,
                'stock_sms' => $stock_sms,
                'premium_sms' => $premium_sms,
            );
        }
        return $sms_data;
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Update General Settings
    function updateGeneralSetting($user_id = 0) {
        $data = array(
            'name' => $this->input->post('name'),
            'contact_number' => $this->input->post('contact_number'),
            'email_address' => $this->input->post('email_id'),
            'company_name' => $this->input->post('company')
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Update Personal Settings
    function updatePersonalSetting($user_id = 0) {
        $data = array(
            'date_of_birth' => $this->input->post('date_of_birth'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'country' => $this->input->post('country'),
            'zipcode' => $this->input->post('zipcode')
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Update Other Settings
    function updateOtherSetting($user_id = 0) {
        $data = array(
            'default_sender_id' => $this->input->post('default_sender_id'),
            'industry' => $this->input->post('user_industry'),
            'default_timezone' => $this->input->post('timezone')
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Update New Password
    function updatePassword($user_id = 0) {
        $current_password = md5($this->input->post('current_password'));
        // Check Current Password
        $this->db->select('`password`');
        $this->db->from('`users`');
        $this->db->where('`password`', $current_password);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = array(
                'password' => md5($this->input->post('new_password'))
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        } else {
            return false;
        }
    }

    // Check Auth key
    function checkUserAuthKey($auth_key = null, $username = null, $password = null) {
        $this->db->select('`user_id`, `auth_key`, `user_status`, `password`, `most_parent_id`, `pro_user_group_id`, `tr_user_group_id`, `number_allowed`');
        $this->db->select('`pr_sms_balance`, `tr_sms_balance`, `utype`, `expiry_date`, user_ratio, user_fake_ratio, user_fail_ratio');
        $this->db->select('pr_user_ratio, pr_user_fake_ratio, pr_user_fail_ratio, ref_user_id, admin_id, check_black_keyword,prtodnd_balance,stock_balance');
        $this->db->from('`users`');
        if ($auth_key != "") {
            $this->db->where('`auth_key`', $auth_key);
        } elseif ($username != "" && $password != "") {
            $this->db->where('`username`', $username);
            $this->db->where('`password`', md5($password));
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Regenerate Auth key
    function regenerateAuthKey($user_id = 0) {
        $auth_key = random_string('unique', 32);
        $check = $this->checkUserAuthKey($auth_key);
        if (!$check) {
            $data = array(
                'auth_key' => $auth_key
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $data);
        } else {
            $this->regenerateAuthKey($user_id);
        }
    }

    // Regenerate Auth key
    function regenerateAuthKey1($user_id = 0) {
        $auth_key = random_string('unique', 32);
        $check = $this->checkUserAuthKey($auth_key);
        if (!$check) {
            $data = array(
                'auth_key' => $auth_key
            );
            $this->db->where('user_id', $user_id);
            $response = $this->db->update('users', $data);
            if ($response) {
                return $auth_key;
            }
        } else {
            $this->regenerateAuthKey($user_id);
        }
    }

    // Get HTTP API Hits For Security
    function getHTTPAPIHits($user_id = 0) {
        $this->db->select('`api_hit_id`, `user_id`, `client_ip_address`, `server_ip_address`, `api_hit_date`');
        $this->db->from('api_hits');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('api_hit_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

// Upload Attachment
    function uploadAttachFile($user_id = 0) {
        //   $upload_audio_file = $this->input->post('upload_audio_file');
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        ini_set('post_max_size', '200M');
        //  var_dump($_FILES['upload_audio_file']['name']);die;
        $uploaded_file = explode('.', $_FILES['upload_attach_file']['name']);
        $extension = $uploaded_file[1];
        $array = array();
        $temp_file_name = substr($_FILES['upload_attach_file']['name'], 0, 4) . $user_id . date('dmYhis');
        $config['file_name'] = $temp_file_name;
        $config['upload_path'] = './attachment/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif|mp3|mp4|pdf|docx|';
        $config['max_size'] = 40960000;
        $config['max_file'] = 1024;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('upload_attach_file', $temp_file_name)) {
            $file_data = $this->upload->data('upload_attach_file');
            $data = array(
                'draft_message' => $temp_file_name . "" . $file_data['file_ext'],
                'draft_message_type' => 'ATTACH',
                'user_id' => $user_id
            );
            $this->db->insert('draft_messages', $data);
            return 200;
        } else {
            return 100;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Get Field Data
    function getFieldData($user_id = 0, $field_type = null, $service_type = null) {
        // Campaigns
        if ($field_type == 'campaign') {
            $this->db->distinct();
            $this->db->select('campaign_name');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_id);
            $this->db->where('service_type', $service_type);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            } else {
                return false;
            }
        }
        // Sender Ids
        if ($field_type == 'sender') {
            $this->db->select('sender_ids, sender_status');
            $this->db->from('sender_ids');
            $this->db->where('user_id', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        }
        // Mobiles
        if ($field_type == 'mobile') {
            $this->db->select('contact_group_name, total_contacts, contact_group_id');
            $this->db->from('contact_groups');
            $this->db->where('user_id', $user_id);
            $this->db->where('`total_contacts` !=', 0);
            $this->db->where('contact_group_status', 1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            } else {
                return false;
            }
        }

        if ($field_type == 'attach_file') {
            $this->db->select('draft_message_id, draft_message');
            $this->db->from('draft_messages');
            $this->db->where('user_id', $user_id);
            $this->db->where('draft_message_type', $service_type);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            } else {
                $this->db->select('draft_message_id, draft_message');
                $this->db->from('demo_voice_draft');
                $this->db->where('user_id', $user_id);
                $this->db->where('draft_message_type', $service_type);
                $query = $this->db->get();
                return $query->result();
                //return false; */
            }
        }
        // Messages/Drafts
        if ($field_type == 'message') {
            $signature = array();
            $draft_messages = array();
            $sent_messages = array();
            // Signature
            $this->db->select('check_signature, signature,check_tracker,tracker_link');
            $this->db->from('users');
            $this->db->where('user_id', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $signature = $query->row();
            }

            // Saved Messages
            $this->db->select('draft_message_id, draft_message');
            $this->db->from('draft_messages');
            $this->db->where('user_id', $user_id);
            $this->db->where('draft_message_type', $service_type);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $draft_messages = $query->result();
            }
            // Previous Sent Messages
            $this->db->distinct();
            $this->db->select('message');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_id);
            $this->db->order_by('campaign_id', 'desc');
            $this->db->limit('2', '0');
            $query = $this->db->get();
            if ($query->num_rows()) {
                $sent_messages = $query->result();
            }
            return $result = array('signatures' => $signature, 'drafts' => $draft_messages, 'sent' => $sent_messages);
        }
        // Drafts Messages
        if ($field_type == 'drafts') {
            $this->db->select('draft_message_id, draft_message');
            $this->db->from('draft_messages');
            $this->db->where('user_id', $user_id);
            $this->db->where('draft_message_type', $service_type);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            } else {
                /*   $this->db->select('draft_message_id, draft_message');
                  $this->db->from('demo_voice_draft');
                  //$this->db->where('user_id', $user_id);
                  $this->db->where('draft_message_type', $service_type);
                  $query = $this->db->get();
                  return $query->result();
                  //return false; */
            }
        }
    }

    //Delete Draft Msg
    public function deleteDraftMsg($id, $user_id) {
        $this->db->where('draft_message_id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->where('draft_message_type', 'SMS');
        $this->db->delete('draft_messages');
        return true;
    }

    //Delete Voice Draft
    public function deleteDraftVoice($id, $user_id) {
        $this->db->where('draft_message_id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->where('draft_message_type', 'VOICE');
        $this->db->delete('draft_messages');
        return true;
    }

    function getDemoFile() {
        $this->db->select('draft_message');
        $this->db->from('demo_voice_draft');
        //$this->db->where('user_id', $user_id);
        $this->db->where('draft_message_id', 1);
        $query = $this->db->get();
        $result = $query->row('draft_message');
        return $result;
    }

    function getUserAlert($user_id) {
        $this->db->select('`user_alert`,`user_id`');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);

        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            return array('user_id' => $result->user_id, 'user_alert' => $result->user_alert);
        } else {
            return false;
        }
    }

    // Save Signature
    function saveSignature($user_id = 0, $type = null) {
        if ($type == 1) {
            $data = array('check_signature' => $this->input->post('check'));
            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data);
            return true;
        } elseif ($type == 2) {
            $data = array('signature' => $this->input->post('signature'));
            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data);
            return true;
        }
    }

    function saveCampaignTracker($user_id = 0, $type = null) {

        if ($type == 1) {
            $data = array('check_tracker' => $this->input->post('check'));
            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data);
            return true;
        } elseif ($type == 2) {
            $data = array('tracker_link' => $this->input->post('tracker_link'));
            $this->db->where('user_id', $user_id);
            $this->db->update('users', $data);
            return true;
        }
    }

    // Get Drafts
    function getDrafts($user_id = 0) {
        $this->db->select('draft_message_id, , draft_message');
        $this->db->from('draft_messages');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result();
        } else {
            return false;
        }
    }

    // Save As Draft
    function saveAsDraft($user_id = 0) {
        // Check Message
        $this->db->select('draft_message');
        $this->db->from('draft_messages');
        $this->db->where('draft_message', $this->input->post('message'));
        $query = $this->db->get();
        $count = $query->num_rows();
        if ($count) {
            return false;
        } else {
            $data = array(
                'draft_message' => $this->input->post('message'),
                'user_id' => $user_id
            );
            $this->db->insert('draft_messages', $data);
            return true;
        }
    }

    // Delete Draft
    function deleteDraft($draft_id = 0) {
        return $this->db->delete('draft_messages', array('draft_message_id' => $draft_id));
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Check DNS Setting
    function checkDNSSettings() {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $domain_name = $myArray[0];
        $domain_name = strtolower($domain_name);
        $domain_array = explode('.', $domain_name);
        if (in_array('www', $domain_array)) {
            $search_key = array_search('www', $domain_array);
            unset($domain_array[$search_key]);
            $domain_name = implode('.', $domain_array);
        } else {
            $domain_name = implode('.', $domain_array);
        }
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

    // Get User Websites
    function getWebsites($user_id = 0) {
        $this->db->select('`website_id`, company_name, `website_domain`, website_status');
        $this->db->from('`user_websites`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows())
            return $query->result_array();
        else
            return false;
    }

    // Get User Website
    function getWebsite($website_id = 0) {
        $this->db->select('website_id, company_name, website_domain, user_id, company_logo, website_theme, website_features, website_clients');
        $this->db->select('website_about_image, website_about_contents, website_social_links, logo_bg_color, external_url_text, external_url_link');
        $this->db->select('banner_image, banner_text, contact_form, reciever_email, google_analytic_id, meta_title, meta_desc, meta_keywords');
        $this->db->select('website_notification, website_theme_color, website_author, website_email, website_status, website_google_map');
        $this->db->select('website_contacts, website_emails, website_addresses, website_cities, website_zipcodes, website_countries, website_banner, website_service1, website_service2, website_service3');
        $this->db->from('`user_websites`');
        $this->db->where('`website_id`', $website_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Save Website
    function saveWebsite($user_id = 0, $website_id = 0) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $company_name = $myArray[0];
        $domain_name = $myArray[1];
        $domain_name = strtolower($domain_name);
        // Remove WWW
        $domain_array = explode('.', $domain_name);
        if (in_array('www', $domain_array)) {
            $search_key = array_search('www', $domain_array);
            unset($domain_array[$search_key]);
            $domain_name = implode('.', $domain_array);
        } else {
            $domain_name = implode('.', $domain_array);
        }
        // Website Updation
        if ($website_id) {
            $this->db->select('website_domain');
            $this->db->from('user_websites');
            $this->db->where('website_id', $website_id);
            $query = $this->db->get();
            $result = $query->row();
            if ($result->website_domain == $domain_name) {
                $data = array(
                    'company_name' => $company_name,
                    'website_domain' => $domain_name
                );
                $this->db->where('website_id', $website_id);
                $this->db->update('user_websites', $data);
                $msg = 'Success: User Website updated successfully!';
                return $return_array = array('111', $msg);
            } else {
                // Check Domain
                $this->db->select('website_domain');
                $this->db->from('user_websites');
                $this->db->where('website_domain', $domain_name);
                //$this->db->where('user_id', $user_id);
                $query = $this->db->get();
                $count = $query->num_rows();
                if ($count) {
                    $msg = 'Error: This Domain is already exists!';
                    return $return_array = array('101', $msg);
                    //return 'This Domain is already exists!';
                } else {
                    $result = dns_get_record($domain_name, DNS_A);
                    if ($result) {
                        $domain_ip = $result[0]['ip'];
                        $server_ip = $_SERVER['SERVER_ADDR'];
                        if ($server_ip == $domain_ip) {
                            $data = array(
                                'company_name' => $company_name,
                                'website_domain' => $domain_name
                            );
                            $this->db->where('website_id', $website_id);
                            $this->db->update('user_websites', $data);
                            $msg = 'Success: User Website updated successfully!';
                            return $return_array = array('111', $msg);
                            //return "This domain is currently pointing to '$domain_ip'";
                        } else {
                            $msg = 'Error: This domain is currently pointing to ' . $domain_ip . ' But the correct IP is ' . $server_ip . ', Please correct';
                            return $return_array = array('102', $msg);
                            //return "This domain is currently pointing to '$domain_ip' But the correct IP is '$server_ip', Please correct.";
                        }
                    } else {
                        $msg = 'Error: Invalid Domain or IP not found on server';
                        return $return_array = array('103', $msg);
                        //return "Invalid Domain or IP not found on server.";
                    }
                }
            }
        } else {
            // Check Domain
            $this->db->select('website_domain');
            $this->db->from('user_websites');
            $this->db->where('website_domain', $domain_name);
            //$this->db->where('user_id', $user_id);
            $query = $this->db->get();
            $count = $query->num_rows();
            if ($count) {
                $msg = 'Error: This Domain is already exists!';
                return $return_array = array('101', $msg);
                //return 'This Domain is already exists!';
            } else {
                $result = dns_get_record($domain_name, DNS_A);
                if ($result) {
                    $domain_ip = $result[0]['ip'];
                    $server_ip = $_SERVER['SERVER_ADDR'];
                    if ($server_ip == $domain_ip) {
                        $data = array(
                            'company_name' => $company_name,
                            'website_domain' => $domain_name,
                            'user_id' => $user_id,
                        );
                        $this->db->insert('user_websites', $data);
                        $msg = 'Success: This domain is currently pointing to ' . $domain_ip;
                        return $return_array = array('100', $msg);
                        //return "This domain is currently pointing to '$domain_ip'";
                    } else {
                        $msg = 'Error: This domain is currently pointing to ' . $domain_ip . ' But the correct IP is ' . $server_ip . ', Please correct';
                        return $return_array = array('102', $msg);
                        //return "This domain is currently pointing to '$domain_ip' But the correct IP is '$server_ip', Please correct.";
                    }
                } else {
                    $msg = 'Error: Invalid Domain or IP not found on server';
                    return $return_array = array('103', $msg);
                    //return "Invalid Domain or IP not found on server.";
                }
            }
        }
    }

    // Delete Website
    function deleteWebsite($website_id = 0) {
        return $this->db->delete('user_websites', array('website_id' => $website_id));
    }

    // Delete Website Data
    function deleteWebsiteData($type = 0, $website_id = 0, $key = 0) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $image = $myArray[0];
        if ($type == "logo") {
            if (@unlink('Website_Data/Logos/' . $image)) {
                $data = array(
                    'company_logo' => ''
                );
                $this->db->where('website_id', $website_id);
                $this->db->update('user_websites', $data);
                return true;
            } else {
                return false;
            }
        } elseif ($type == "banner") {
            if (@unlink('Website_Data/Banners/' . $image)) {
                $data = array(
                    'banner_image' => ''
                );
                $this->db->where('website_id', $website_id);
                $this->db->update('user_websites', $data);
                return true;
            } else {
                return false;
            }
        } elseif ($type == "client") {
            $array1 = explode('<>', $image);
            $client = $array1[0];
            $clients = explode('|', $array1[1]);
            unset($clients[$key]);
            $new_clients = implode('|', $clients);
            if (@unlink('Website_Data/Clients/' . $client)) {
                $data = array(
                    'website_clients' => $new_clients
                );
                $this->db->where('website_id', $website_id);
                $this->db->update('user_websites', $data);
                return true;
            } else {
                return false;
            }
        } elseif ($type == "about") {
            if (@unlink('Website_Data/About/' . $image)) {
                $data = array(
                    'website_about_image' => ''
                );
                $this->db->where('website_id', $website_id);
                $this->db->update('user_websites', $data);
                return true;
            } else {
                return false;
            }
        }
    }

    // Get Last Added Website
    function getLastWebsite($user_id = 0) {
        $this->db->select('website_id');
        $this->db->from('`user_websites`');
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by('`website_id`', 'desc');
        $this->db->limit('1', '0');
        $query = $this->db->get();
        if ($query->num_rows()) {
            $result = $query->row();
            return $result->website_id;
        } else {
            return false;
        }
    }

    // Save Website Data
    function saveWebsiteData($user_id = 0, $website_id = 0, $tab = null) {
        // Website Banners
        if ($tab == 'banner') {
            $banner_text = $this->input->post('banner_text');
            $banner = $this->input->post('banner');
            $data = array('banner_text' => $banner_text, 'website_banner' => $banner);
            if ($this->input->post('image_name') != "") {
                if ($_FILES['company_logo']['name'] != "") {
                    @unlink('Website_Data/Logos/' . $this->input->post('image_name'));
                    $temp_name = "company_logo_" . $website_id . "" . $user_id;
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/Logos/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('company_logo')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "company_logo_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 220,
                            'height' => 150,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data = array('company_logo' => $temp_file_name);
                        }
                    }
                }
            } else {
                if ($_FILES['company_logo']['name'] != "") {
                    $temp_name = "company_logo_" . $website_id . "" . $user_id;
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/Logos/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('company_logo')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "company_logo_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 220,
                            'height' => 150,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data = array('company_logo' => $temp_file_name);
                        }
                    }
                }
            }
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Services
        if ($tab == 'services') {
            $service_text1 = $this->input->post('service_text1');
            $service_text2 = $this->input->post('service_text2');
            $service_text3 = $this->input->post('service_text3');
            $data = array(
                'website_service1' => $service_text1,
                'website_service2' => $service_text2,
                'website_service3' => $service_text3
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Clients
        if ($tab == 'clients') {
            $clients = $this->input->post('image_name');
            $array = explode('|', $clients);
            $name_array = array();
            $files = $_FILES;
            $no = count($array);
            $count = count($_FILES['clients_logo']['name']);
            for ($s = 0; $s < $count; $s++) {
                $new_name = "clients_logo" . $no;
                $_FILES[$new_name]['name'] = $files['clients_logo']['name'][$s];
                $_FILES[$new_name]['type'] = $files['clients_logo']['type'][$s];
                $_FILES[$new_name]['tmp_name'] = $files['clients_logo']['tmp_name'][$s];
                $_FILES[$new_name]['error'] = $files['clients_logo']['error'][$s];
                $_FILES[$new_name]['size'] = $files['clients_logo']['size'][$s];
                $temp_name = "client" . $website_id . "" . $user_id . "_" . $no;
                $config['file_name'] = $temp_name;
                $config['upload_path'] = './Website_Data/Clients/';
                $config['allowed_types'] = 'gif|jpg|png';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload($new_name)) {
                    $upload_data = $this->upload->data($new_name);
                    $ext = $upload_data['file_ext'];
                    $temp_file_name = "client" . $website_id . "" . $user_id . "_" . $no . "" . $ext;
                    $no++;
                    $temp_name = "";
                    unset($config);
                    $this->load->library('image_lib');
                    $resize_conf = array(
                        'source_image' => $upload_data['full_path'],
                        'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                        'width' => 100,
                        'height' => 100,
                        'maintain_ratio' => true
                    );
                    $this->image_lib->initialize($resize_conf);
                    if ($this->image_lib->resize()) {
                        $name_array[] = $temp_file_name;
                    }
                } else {
                    $this->upload->display_errors();
                }
            }
            $names = implode('|', $name_array);
            if ($clients == "")
                $data = array('website_clients' => $names);
            else
                $data = array('website_clients' => $clients . "|" . $names);
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website About Us
        if ($tab == 'about_us') {
            $about_us_text = $this->input->post('about_us_text');
            $data = array('website_about_contents' => $about_us_text);

            if ($this->input->post('image_name') != "") {
                if ($_FILES['about_us_image']['name'] != "") {
                    @unlink('Website_Data/About/' . $this->input->post('image_name'));
                    $temp_name = "about_us_" . $website_id . "" . $user_id . "";
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/About/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('about_us_image')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "about_us_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 545,
                            'height' => 345,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data = array('website_about_image' => $temp_file_name);
                        }
                    }
                }
            } else {
                if ($_FILES['about_us_image']['name'] != "") {
                    $temp_name = "about_us_" . $website_id . "" . $user_id . "";
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/About/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('about_us_image')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "about_us_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 545,
                            'height' => 345,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data = array('website_about_image' => $temp_file_name);
                        }
                    }
                }
            }

            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Contact Us
        if ($tab == 'contact_us') {
            $reciever_email = $this->input->post('reciever_email');
            $website_contact = $this->input->post('website_contact');
            $website_email = $this->input->post('website_email');
            $website_address = $this->input->post('website_address');
            $website_city = $this->input->post('website_city');
            $website_zipcode = $this->input->post('website_zipcode');
            $website_country = $this->input->post('website_country');
            $website_google_map = $this->input->post('website_google_map');
            $data = array(
                'website_contacts' => $website_contact,
                'website_emails' => $website_email,
                'website_addresses' => $website_address,
                'website_cities' => $website_city,
                'website_zipcodes' => $website_zipcode,
                'website_countries' => $website_country,
                'website_google_map' => $website_google_map,
                'reciever_email' => $reciever_email
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Social
        if ($tab == 'social') {
            $facebook_link = $this->input->post('facebook_link');
            $twitter_link = $this->input->post('twitter_link');
            $linkedin_link = $this->input->post('linkedin_link');
            $google_plus_link = $this->input->post('google_plus_link');
            $social_links = "";
            $social_links.=$facebook_link . "|" . $twitter_link . "|" . $linkedin_link . "|" . $google_plus_link;
            $data = array(
                'website_social_links' => $social_links
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website SEO
        if ($tab == 'seo') {
            $google_analytic_id = $this->input->post('google_analytic_id');
            $meta_title = $this->input->post('meta_title');
            $meta_desc = $this->input->post('meta_desc');
            $keywords = "";
            for ($i = 1; $i <= 4; $i++) {
                if ($keywords == "") {
                    $keywords.=$this->input->post('keyword' . $i);
                } else {
                    $keywords.="|" . $this->input->post('keyword' . $i);
                }
            }
            $data = array(
                'google_analytic_id' => $google_analytic_id,
                'meta_title' => $meta_title,
                'meta_desc' => $meta_desc,
                'meta_keywords' => $keywords
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Other
        if ($tab == 'other') {
            $external_url_text = $this->input->post('external_url_text');
            $external_url_link = $this->input->post('external_url_link');
            $website_notification = $this->input->post('notification');
            $website_theme_color = $this->input->post('theme_color');
            $website_author = $this->input->post('author_name');
            $website_email = $this->input->post('author_email');
            $data = array(
                'external_url_text' => $external_url_text,
                'external_url_link' => $external_url_link,
                'website_notification' => $website_notification,
                'website_theme_color' => $website_theme_color,
                'website_author' => $website_author,
                'website_email' => $website_email
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
    }

    // Save Website Data
    function saveWebsiteData1($user_id = 0, $website_id = 0, $tab = null) {
        // Website Banners
        if ($tab == 'banner') {
            $banner_text = $this->input->post('banner_text');
            $banner = $this->input->post('banner');
            $data = array();
            $data['banner_text'] = $banner_text;
            $data['website_banner'] = $banner;
            //$data = array('banner_text' => $banner_text, 'website_banner' => $banner);           
            if ($this->input->post('image_name') != "") {
                if (isset($_FILES['company_logo']['name'])) {
                    @unlink('Website_Data/Logos/' . $this->input->post('image_name'));
                    $temp_name = "company_logo_" . $website_id . "" . $user_id;
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/Logos/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('company_logo')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "company_logo_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 220,
                            'height' => 150,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data['company_logo'] = $temp_file_name;
                            //$data = array('company_logo' => $temp_file_name);
                        }
                    }
                }
            } else {
                if (isset($_FILES['company_logo']['name'])) {
                    $temp_name = "company_logo_" . $website_id . "" . $user_id;
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/Logos/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('company_logo')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "company_logo_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 220,
                            'height' => 150,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data['company_logo'] = $temp_file_name;
                            //$data = array('company_logo' => $temp_file_name);
                        }
                    }
                }
            }

            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Services
        if ($tab == 'services') {
            $service_text1 = $this->input->post('service_text1');
            $service_text2 = $this->input->post('service_text2');
            $service_text3 = $this->input->post('service_text3');
            $data = array(
                'website_service1' => $service_text1,
                'website_service2' => $service_text2,
                'website_service3' => $service_text3
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Clients
        if ($tab == 'clients') {
            $clients = $this->input->post('image_name');
            $array = explode('|', $clients);
            $name_array = array();
            $files = $_FILES;
            $no = count($array);
            $count = count($_FILES['clients_logo']['name']);
            for ($s = 0; $s < $count; $s++) {
                $new_name = "clients_logo" . $no;
                $_FILES[$new_name]['name'] = $files['clients_logo']['name'][$s];
                $_FILES[$new_name]['type'] = $files['clients_logo']['type'][$s];
                $_FILES[$new_name]['tmp_name'] = $files['clients_logo']['tmp_name'][$s];
                $_FILES[$new_name]['error'] = $files['clients_logo']['error'][$s];
                $_FILES[$new_name]['size'] = $files['clients_logo']['size'][$s];
                $temp_name = "client" . $website_id . "" . $user_id . "_" . $no;
                $config['file_name'] = $temp_name;
                $config['upload_path'] = './Website_Data/Clients/';
                $config['allowed_types'] = 'gif|jpg|png';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload($new_name)) {
                    $upload_data = $this->upload->data($new_name);
                    $ext = $upload_data['file_ext'];
                    $temp_file_name = "client" . $website_id . "" . $user_id . "_" . $no . "" . $ext;
                    $no++;
                    $temp_name = "";
                    unset($config);
                    $this->load->library('image_lib');
                    $resize_conf = array(
                        'source_image' => $upload_data['full_path'],
                        'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                        'width' => 100,
                        'height' => 100,
                        'maintain_ratio' => true
                    );
                    $this->image_lib->initialize($resize_conf);
                    if ($this->image_lib->resize()) {
                        $name_array[] = $temp_file_name;
                    }
                } else {
                    $this->upload->display_errors();
                }
            }
            $names = implode('|', $name_array);
            if ($clients == "")
                $data = array('website_clients' => $names);
            else
                $data = array('website_clients' => $clients . "|" . $names);
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website About Us
        if ($tab == 'about_us') {
            $about_us_text = $this->input->post('about_us_text');
            //$data = array('website_about_contents' => $about_us_text);
            $data = array();
            $data['website_about_contents'] = $about_us_text;
            if ($this->input->post('image_name') != "") {
                if (isset($_FILES['about_us_image']['name'])) {
                    @unlink('Website_Data/About/' . $this->input->post('image_name'));
                    $temp_name = "about_us_" . $website_id . "" . $user_id . "";
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/About/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('about_us_image')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "about_us_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 545,
                            'height' => 345,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data['website_about_image'] = $temp_file_name;
                            //$data = array('website_about_image' => $temp_file_name);
                        }
                    }
                }
            } else {
                if (isset($_FILES['about_us_image']['name'])) {
                    $temp_name = "about_us_" . $website_id . "" . $user_id . "";
                    $config['file_name'] = $temp_name;
                    $config['upload_path'] = './Website_Data/About/';
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size'] = 1024 * 50 * 8;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('about_us_image')) {
                        $upload_data = $this->upload->data();
                        $ext = $upload_data['file_ext'];
                        $temp_file_name = "about_us_" . $website_id . "" . $user_id . "" . $ext;
                        $this->load->library('image_lib');
                        $resize_conf = array(
                            'source_image' => $upload_data['full_path'],
                            'new_image' => $upload_data['file_path'] . $upload_data['file_name'],
                            'width' => 545,
                            'height' => 345,
                            'maintain_ratio' => true
                        );
                        $this->image_lib->initialize($resize_conf);
                        if ($this->image_lib->resize()) {
                            $data['website_about_image'] = $temp_file_name;
                            //$data = array('website_about_image' => $temp_file_name);
                        }
                    }
                }
            }
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Contact Us
        if ($tab == 'contact_us') {
            $reciever_email = $this->input->post('reciever_email');
            $website_contact = $this->input->post('website_contact');
            $website_email = $this->input->post('website_email');
            $website_address = $this->input->post('website_address');
            $website_city = $this->input->post('website_city');
            $website_zipcode = $this->input->post('website_zipcode');
            $website_country = $this->input->post('website_country');
            $website_google_map = "";
            //$website_google_map = $this->input->post('website_google_map');
            $data = array(
                'website_contacts' => $website_contact,
                'website_emails' => $website_email,
                'website_addresses' => $website_address,
                'website_cities' => $website_city,
                'website_zipcodes' => $website_zipcode,
                'website_countries' => $website_country,
                'website_google_map' => $website_google_map,
                'reciever_email' => $reciever_email
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Social
        if ($tab == 'social') {
            $facebook_link = $this->input->post('facebook_link');
            $twitter_link = $this->input->post('twitter_link');
            $linkedin_link = $this->input->post('linkedin_link');
            $google_plus_link = $this->input->post('google_plus_link');
            $social_links = "";
            $social_links.=$facebook_link . "|" . $twitter_link . "|" . $linkedin_link . "|" . $google_plus_link;
            $data = array(
                'website_social_links' => $social_links
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website SEO
        if ($tab == 'seo') {
            $google_analytic_id = $this->input->post('google_analytic_id');
            $meta_title = $this->input->post('meta_title');
            $meta_desc = $this->input->post('meta_desc');
            $keywords = "";
            for ($i = 1; $i <= 4; $i++) {
                if ($keywords == "") {
                    $keywords.=$this->input->post('keyword' . $i);
                } else {
                    $keywords.="|" . $this->input->post('keyword' . $i);
                }
            }
            $data = array(
                'google_analytic_id' => $google_analytic_id,
                'meta_title' => $meta_title,
                'meta_desc' => $meta_desc,
                'meta_keywords' => $keywords
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
        // Website Other
        if ($tab == 'other') {
            $external_url_text = $this->input->post('external_url_text');
            $external_url_link = $this->input->post('external_url_link');
            $website_notification = $this->input->post('notification');
            $website_theme_color = $this->input->post('theme_color');
            $website_author = $this->input->post('author_name');
            $website_email = $this->input->post('author_email');
            $data = array(
                'external_url_text' => $external_url_text,
                'external_url_link' => $external_url_link,
                'website_notification' => $website_notification,
                'website_theme_color' => $website_theme_color,
                'website_author' => $website_author,
                'website_email' => $website_email
            );
            $this->db->where('website_id', $website_id);
            $result = $this->db->update('user_websites', $data);
            return true;
        }
    }

    // Set Default Route
    function setDefaultRoute($user_id = 0, $route = null) {
        $data = array(
            'default_route' => $route
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Get Resellers/Users For Notify
    function getUsersNotify($type = null, $user_id = 0) {
        $this->db->select('user_id, username, name, email_address, contact_number');
        $this->db->from('`users`');
        $this->db->where('`ref_user_id`', $user_id);
        $this->db->where('`user_status`', 1);
        $this->db->where('`utype`', $type);
        $this->db->order_by('`username`', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Save Reseller Setting
    function saveResellerSetting($user_id = 0, $type = null) {
        if ($type == 1) {
            $data = array(
                'signup_sender' => $this->input->post('signup_sender_id'),
                'signup_message' => $this->input->post('signup_message')
            );
        } elseif ($type == 2) {
            $data = array(
                'signup_subject' => $this->input->post('signup_subject'),
                'signup_body' => $this->input->post('signup_body')
            );
        } elseif ($type == 3) {
            $data = array(
                'expiry_days' => $this->input->post('expiry_days'),
                'demo_balance' => $this->input->post('demo_balance')
            );
        } elseif ($type == 4) {
            $data = array(
                'demo_sender' => $this->input->post('demo_sender'),
                'demo_message' => $this->input->post('demo_message')
            );
        } elseif ($type == 5) {
            $signup_notification = 0;
            if ($this->input->post('signup_notification')) {
                $signup_notification = $this->input->post('signup_notification');
            }
            $data = array(
                'signup_notification' => $signup_notification
            );
        }
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Make Me Reseller Check Childs
    function checkChilds($user_id = 0) {
        $this->db->select('user_id');
        $this->db->from('`users`');
        $this->db->where('`ref_user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return false;
        } else {
            return true;
        }
    }

    // Make Me Reseller
    function changeUserType($user_id = 0, $user_type = null, $make_spacial_reseller = 0) {


        $data = array(
            'utype' => $user_type,
            'spacial_reseller_status' => $make_spacial_reseller
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Get Previous Sent Notify
    function getPreviousNotify($user_id = 0, $type = null) {
        if ($type == 'by_sms') {
            $this->db->select('`notify_route`, `notify_sender`, `notify_message`, `notify_date`');
            $this->db->from('`notify_users`');
            $this->db->where('`notify_route` !=', "");
        } elseif ($type == 'by_email') {
            $this->db->select('`notify_email`, `notify_subject`, `notify_body`, `notify_date`');
            $this->db->from('`notify_users`');
            $this->db->where('`notify_email` !=', "");
        }
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by('`notify_id`', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // DLR Push
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Campaigns Messages For DLR Push
    function getCampaigns($user_id = 0, $current_date = null) {
        $this->db->select('`campaign_id`, `campaign_uid`');
        $this->db->from('`campaigns`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`dlr_push_status`', '1');
        $this->db->like('submit_date', $current_date);
        $query_campaign = $this->db->get();
        if ($query_campaign->num_rows()) {
            return $query_campaign->result_array();
        } else {
            return false;
        }
    }

    // Get Sent Messages For DLR Push
    function getSentMessages($campaign_id = 0) {
        $return_array = array();
        $this->db->select('`mobile_no`, `status`, `done_date`');
        $this->db->from('`sent_sms`');
        $this->db->where('`campaign_id`', $campaign_id);
        $query_sms = $this->db->get();
        if ($query_sms->num_rows()) {
            $return_array = array();
            $result_sms = $query_sms->result_array();
            foreach ($result_sms as $sms) {
                $temp_sarray1 = array();
                if ($sms['status'] == "1") {
                    $status = 1;
                    $desc = "DELIVERED";
                } elseif ($sms['status'] == "2") {
                    $status = 2;
                    $desc = "FAILED";
                } elseif ($sms['status'] == "31" || $sms['status'] == "23" || $sms['status'] == "4") {
                    $status = 31;
                    $desc = "SENT";
                } elseif ($sms['status'] == "8") {
                    $status = 8;
                    $desc = "SUBMITTED";
                } elseif ($sms['status'] == "DND") {
                    $status = 16;
                    $desc = "NDNC";
                } elseif ($sms['status'] == "16" || $sms['status'] == "Rejected") {
                    $status = 10;
                    $desc = "REJECTED";
                } elseif ($sms['status'] == "Blocked") {
                    $status = 20;
                    $desc = "BLOCKED";
                }
                $temp_sarray1['date'] = $sms['done_date'];
                $temp_sarray1['status'] = $status;
                $temp_sarray1['desc'] = $desc;
                $return_array[$sms['mobile_no']] = $temp_sarray1;
            }
        }
        // Update DLR PUSH Status On Campaign
        $data = array('dlr_push_status' => 0);
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->update('campaigns', $data);
        return $return_array;
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Set Default Voice Route
    function setDefaultVoiceRoute($user_id = 0, $route = null) {
        $data = array(
            'default_voice_route' => $route
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Count Voice Delivery Reports
    function countVoiceDeliveryReports($user_id = 0) {
        //$this->db->cache_on();
        $this->db->select('campaign_id');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'VOICE');
        $this->db->order_by("campaign_id", "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Voice Delivery Reports
    function getVoiceDeliveryReports($user_id = 0, $limit = 0, $start = 0) {
        //$this->db->cache_on();
        $this->db->select('campaign_id, campaign_uid, campaign_name, total_messages, campaign_status, caller_id, message_category, schedule_status');
        $this->db->select('submit_date, request_by, message, route, total_credits, total_deducted, actual_message');
        $this->db->select('start_date_time AS start_date, end_date_time AS end_date');
        $this->db->from('campaigns');
        $this->db->where('user_id', $user_id);
        $this->db->where('message_category', 1);
        $this->db->where('service_type', 'VOICE');
        $this->db->order_by("campaign_id", "desc");
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Count Voice Sent SMS
    function countVoiceSentSMS($campaign_id = 0) {
        //$this->db->cache_on();
        $this->db->select('`sms_id`');
        $this->db->from('`sent_sms`, `campaigns`');
        $this->db->where('`campaigns`.`campaign_id`=`sent_sms`.`campaign_id`');
        $this->db->where('`campaigns`.`campaign_id`', $campaign_id);
        $this->db->order_by("`sms_id`", "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get Voice Sent SMS
    function getVoiceSentSMS($campaign_id = 0, $limit = 0, $start = 0) {
        //$this->db->cache_on();
        $session_data = $this->session->userdata('logged_in');
        $user_id = $session_data['user_id'];
        $this->db->select('`sms_id`, `user_id`, `msg_id`, `mobile_no`, `status`, `submit_date`, `done_date`, `dlr_receipt`, `dlr_status`');
        $this->db->select('`ttsCallRequestId`, `description`');
        $this->db->from('`sent_sms`');
        $this->db->where('`campaign_id`', $campaign_id);
        $this->db->where('`user_id`', $user_id);
        $this->db->order_by("status", "ASC");
        $this->db->order_by("`done_date`", "ASC");
        if ($limit != null)
            $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // Upload Audio File
    function uploadAudioFile($user_id = 0) {
        //   $upload_audio_file = $this->input->post('upload_audio_file');
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        //  var_dump($_FILES['upload_audio_file']['name']);die;
        $uploaded_file = explode('.', $_FILES['upload_audio_file']['name']);
        $extension = $uploaded_file[1];
        $array = array();
        if ($extension == 'mp3' || $extension == 'wav') {
            $temp_file_name = substr($_FILES['upload_audio_file']['name'], 0, 3) . $user_id . date('dmYhis');
            $config['file_name'] = $temp_file_name;
            $config['upload_path'] = './Voice/';
            $config['allowed_types'] = 'mp3|wav';
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('upload_audio_file', $temp_file_name)) {
                $file_data = $this->upload->data('upload_audio_file');
                $data = array(
                    'draft_message' => $temp_file_name . "" . $file_data['file_ext'],
                    'draft_message_type' => 'VOICE',
                    'user_id' => $user_id
                );
                $this->db->insert('draft_messages', $data);
                return 200;
            } else {
                return 100;
            }
        } else {
            return 101;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Save Theme Color Permanently
    function saveThemeColor($user_id = 0, $theme = null) {
        $data = array(
            'theme_color' => $theme
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    //get datatable of bulk_data field name
    // Get Contact Groups
    function getFieldName() {
        $result = $this->db->list_fields('bulk_data');
        return $result;
    }

//save csv contact2
    // Save CSV Contacts
    function saveCSVContact2($user_id = 0) {
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '1073741824');
        $temp_file_name = $this->input->post('temp_file_name');
        $total_columns = $this->input->post('total_columns');
        $column_array = array();
        $number_field = 0;
        $name_field = 0;
        $full_data = array();
        $field_id_array = array();
        $field_name_array = array();
        $field_type_array = array();
        $field_status_array = array();
        $field_key_array = array();
        $new_fields = array();
        $old_fields = array();
        for ($i = 1; $i <= $total_columns; $i++) {
            if ($this->input->post('action' . $i)) {
                $column_array[] = $i;
                if ($this->input->post('field_name' . $i) == 'number') {
                    $number_field = $i;
                } elseif ($this->input->post('field_name' . $i) == 'name') {
                    $name_field = $i;
                } elseif ($this->input->post('field_name' . $i) == '_add_') {
                    $field_id_array[] = $this->input->post('new_field_id' . $i);
                    $field_name_array[] = $this->input->post('new_field_name' . $i);
                    $field_type_array[] = $this->input->post('new_field_type' . $i);
                    $field_status_array[] = 1;
                    $field_key_array[] = $i - 1;
                    $new_fields[$i - 1] = $this->input->post('new_field_id' . $i);
                } else {
                    $temp = explode('|', $this->input->post('field_name' . $i));
                    $field_id_array[] = $temp[0];
                    $field_name_array[] = $temp[1];
                    $field_type_array[] = $temp[2];
                    $field_status_array[] = $temp[3];
                    $field_key_array[] = $i - 1;
                    $old_fields[$i - 1] = $temp[0];
                }
            }
        }
        $field_id_string = implode('|', $field_id_array);
        $group_id_array = $this->input->post('groups');
        // Contact Group Info
        $new_id_array = array();
        $this->db->select('`contact_group_id`, `extra_column_ids`, `extra_column_names`, `extra_column_types`, `extra_column_status`');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where_in('`contact_group_id`', $group_id_array);
        $query_cgroup = $this->db->get();
        if ($query_cgroup->num_rows()) {
            $result_cgroup = $query_cgroup->result();
            foreach ($result_cgroup as $key => $row_cgroup) {
                $update_ids = "";
                $update_names = "";
                $update_types = "";
                $update_status = "";
                $extra_column_ids = explode('|', $row_cgroup->extra_column_ids);
                $extra_column_names = explode('|', $row_cgroup->extra_column_names);
                $extra_column_types = explode('|', $row_cgroup->extra_column_types);
                $extra_column_status = explode('|', $row_cgroup->extra_column_status);
                // Already Have Extra Columns
                if ($row_cgroup->extra_column_ids != "") {
                    $result_id_array = array_intersect($extra_column_ids, $field_id_array);
                    // If Common Ids
                    if (sizeof($result_id_array)) {
                        $result_id_array1 = array_diff($field_id_array, $extra_column_ids);
                        //$update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_ids = $row_cgroup->extra_column_ids;
                        $update_names = $row_cgroup->extra_column_names;
                        $update_types = $row_cgroup->extra_column_types;
                        $update_status = $row_cgroup->extra_column_status;
                        if (sizeof($result_id_array1)) {
                            foreach ($result_id_array1 as $key => $id) {
                                if (in_array($id, $field_id_array)) {
                                    $search_array = array_search($id, $field_id_array);
                                    $update_ids.="|" . $field_id_array[$search_array];
                                    $update_names.="|" . $field_name_array[$search_array];
                                    $update_types.="|" . $field_type_array[$search_array];
                                    $update_status.="|" . 1;
                                }
                            }
                        }
                    } else {
                        $update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_names = $row_cgroup->extra_column_names . "|" . implode('|', $field_name_array);
                        $update_types = $row_cgroup->extra_column_types . "|" . implode('|', $field_type_array);
                        $update_status = $row_cgroup->extra_column_status . "|" . implode('|', $field_status_array);
                    }
                } else {
                    $update_ids = implode('|', $field_id_array);
                    $update_names = implode('|', $field_name_array);
                    $update_types = implode('|', $field_type_array);
                    $update_status = implode('|', $field_status_array);
                }
                // Update Data Into Contact Group
                $data = array(
                    'extra_column_ids' => $update_ids,
                    'extra_column_names' => $update_names,
                    'extra_column_types' => $update_types,
                    'extra_column_status' => $update_status
                );
                $this->db->where('contact_group_id', $row_cgroup->contact_group_id);
                $this->db->update('contact_groups', $data);
            }
        }
        $group_ids_string = implode(',', $group_id_array);
        $csvFile = "./ContactCSV/" . $temp_file_name;
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (!feof($handle)) {
                $line = fgetcsv($handle);
                $row++;
                if ($line != "") {
                    //$line = explode(',', trim($record));
                    if (sizeof($line)) {
                        $contact_number;
                        if ($number_field) {
                            $contact_number = $line[$number_field - 1];
                            // Check Valid Value For Contact Number
                            if (is_numeric($contact_number)) {
                                if (strlen($contact_number) == 10) {
                                    $contact_number = "91" . $contact_number;
                                }
                                // Check This Number In DB
                                $this->db->select('`mobile_number`, `contact_name`, `extra_column_ids`, `extra_column_values`, `contact_group_ids`');
                                $this->db->from('`contacts`');
                                $this->db->where('`user_id`', $user_id);
                                $this->db->where('`mobile_number`', $contact_number);
                                $query_contact = $this->db->get();
                                // If Number Already Exists
                                if ($query_contact->num_rows()) {
                                    // Check Contact Name
                                    $contact_name = "";
                                    if ($name_field) {
                                        $contact_name = $line[$name_field - 1];
                                    }
                                    $result_contact = $query_contact->row();
                                    $ccontact_group_ids = explode(',', $result_contact->contact_group_ids);
                                    $array_diff = array_diff($group_id_array, $ccontact_group_ids);
                                    $union_array = array_unique(array_merge($ccontact_group_ids, $group_id_array));
                                    $group_id_string1 = implode(',', $union_array);
                                    $cextra_column_ids = explode('|', $result_contact->extra_column_ids);
                                    $intersect_array = array_intersect($cextra_column_ids, $field_id_array);
                                    $cextra_column_values = explode('|', $result_contact->extra_column_values);
                                    $field_id_string1 = $result_contact->extra_column_ids;
                                    $field_values_string = "";
                                    if (sizeof($intersect_array)) {
                                        foreach ($old_fields as $key => $old_id) {
                                            if (in_array($old_id, $cextra_column_ids)) {
                                                $search_key = array_search($old_id, $cextra_column_ids);
                                                $cextra_column_values[$search_key] = $line[$key];
                                            } else {
                                                $field_id_string1.="|" . $old_id;
                                                array_push($cextra_column_values, $line[$key]);
                                            }
                                        }
                                        $field_values_string = implode('|', $cextra_column_values);
                                        if (sizeof($new_fields)) {
                                            $keys = array_keys($new_fields);
                                            $field_values_string .= "|" . implode('|', array_intersect_key($line, array_flip($keys)));
                                            $field_id_string1.="|" . implode('|', $new_fields);
                                        }
                                        // Update Count
                                        // Increase
                                        foreach ($array_diff as $group_id) {
                                            $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                            $this->db->where('`contact_group_id`', $group_id);
                                            $this->db->update('contact_groups');
                                        }
                                        // Insert Contact Number
                                        $data = array(
                                            'contact_name' => $contact_name,
                                            'extra_column_ids' => $field_id_string1,
                                            'extra_column_values' => $field_values_string,
                                            'contact_group_ids' => $group_id_string1
                                        );
                                        $this->db->where('user_id', $user_id);
                                        $this->db->where('`mobile_number`', $contact_number);
                                        $this->db->update('contacts', $data);
                                    } else {
                                        // Update Count 
                                        // Decrease
                                        /*
                                          foreach ($union_array as $group_id) {
                                          $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                                          $this->db->where('`contact_group_id`', $group_id);
                                          $this->db->update('contact_groups');
                                          }
                                         */
                                        // Increase
                                        foreach ($array_diff as $group_id) {
                                            $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                            $this->db->where('`contact_group_id`', $group_id);
                                            $this->db->update('contact_groups');
                                        }
                                        $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));
                                        // Insert Contact Number
                                        $data = array(
                                            'contact_name' => $contact_name,
                                            'extra_column_ids' => $result_contact->extra_column_ids . "|" . $field_id_string,
                                            'extra_column_values' => $result_contact->extra_column_values . "|" . $field_values_string,
                                            'contact_group_ids' => $group_id_string1
                                        );
                                        $this->db->where('user_id', $user_id);
                                        $this->db->where('`mobile_number`', $contact_number);
                                        $this->db->update('contacts', $data);
                                    }
                                } else {
                                    // Update Count
                                    // Increase
                                    foreach ($group_id_array as $group_id) {
                                        $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                        $this->db->where('`contact_group_id`', $group_id);
                                        $this->db->update('contact_groups');
                                    }
                                    // Check Contact Name
                                    $contact_name = "";
                                    if ($name_field) {
                                        $contact_name = $line[$name_field - 1];
                                    }
                                    $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));
                                    // Insert Contact Number

                                    $data = array(
                                        'contact_name' => $contact_name,
                                        'mobile_number' => $contact_number,
                                        'extra_column_ids' => $field_id_string,
                                        'extra_column_values' => $field_values_string,
                                        'contact_group_ids' => $group_ids_string,
                                        'user_id' => $user_id
                                    );

                                    $full_data[] = $data;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }

            fclose($handle);
            $this->db->insert_batch('contacts', $full_data);
            return true;
        } else {
            return false;
        }
    }

    function mytry($user_id = 0) {
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '1073741824');
        $temp_file_name = $this->input->post('temp_file_name');
        $total_columns = $this->input->post('total_columns');
        $column_array = array();
        $number_field = 0;
        $name_field = 0;
        $full_data = array();
        $field_id_array = array();
        $field_name_array = array();
        $field_type_array = array();
        $field_status_array = array();
        $field_key_array = array();
        $new_fields = array();
        $old_fields = array();
        for ($i = 1; $i <= $total_columns; $i++) {
            if ($this->input->post('action' . $i)) {
                $column_array[] = $i;
                if ($this->input->post('field_name' . $i) == 'number') {
                    $number_field = $i;
                } elseif ($this->input->post('field_name' . $i) == 'name') {
                    $name_field = $i;
                } elseif ($this->input->post('field_name' . $i) == '_add_') {
                    $field_id_array[] = $this->input->post('new_field_id' . $i);
                    $field_name_array[] = $this->input->post('new_field_name' . $i);
                    $field_type_array[] = $this->input->post('new_field_type' . $i);
                    $field_status_array[] = 1;
                    $field_key_array[] = $i - 1;
                    $new_fields[$i - 1] = $this->input->post('new_field_id' . $i);
                } else {
                    $temp = explode('|', $this->input->post('field_name' . $i));
                    $field_id_array[] = $temp[0];
                    $field_name_array[] = $temp[1];
                    $field_type_array[] = $temp[2];
                    $field_status_array[] = $temp[3];
                    $field_key_array[] = $i - 1;
                    $old_fields[$i - 1] = $temp[0];
                }
            }
        }
        $field_id_string = implode('|', $field_id_array);
        $group_id_array = $this->input->post('groups');
        // Contact Group Info
        $new_id_array = array();
        $this->db->select('`contact_group_id`, `extra_column_ids`, `extra_column_names`, `extra_column_types`, `extra_column_status`');
        $this->db->from('`contact_groups`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where_in('`contact_group_id`', $group_id_array);
        $query_cgroup = $this->db->get();
        if ($query_cgroup->num_rows()) {
            $result_cgroup = $query_cgroup->result();
            foreach ($result_cgroup as $key => $row_cgroup) {
                $update_ids = "";
                $update_names = "";
                $update_types = "";
                $update_status = "";
                $extra_column_ids = explode('|', $row_cgroup->extra_column_ids);
                $extra_column_names = explode('|', $row_cgroup->extra_column_names);
                $extra_column_types = explode('|', $row_cgroup->extra_column_types);
                $extra_column_status = explode('|', $row_cgroup->extra_column_status);
                // Already Have Extra Columns
                if ($row_cgroup->extra_column_ids != "") {
                    $result_id_array = array_intersect($extra_column_ids, $field_id_array);
                    // If Common Ids
                    if (sizeof($result_id_array)) {
                        $result_id_array1 = array_diff($field_id_array, $extra_column_ids);
                        //$update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_ids = $row_cgroup->extra_column_ids;
                        $update_names = $row_cgroup->extra_column_names;
                        $update_types = $row_cgroup->extra_column_types;
                        $update_status = $row_cgroup->extra_column_status;
                        if (sizeof($result_id_array1)) {
                            foreach ($result_id_array1 as $key => $id) {
                                if (in_array($id, $field_id_array)) {
                                    $search_array = array_search($id, $field_id_array);
                                    $update_ids.="|" . $field_id_array[$search_array];
                                    $update_names.="|" . $field_name_array[$search_array];
                                    $update_types.="|" . $field_type_array[$search_array];
                                    $update_status.="|" . 1;
                                }
                            }
                        }
                    } else {
                        $update_ids = $row_cgroup->extra_column_ids . "|" . implode('|', $field_id_array);
                        $update_names = $row_cgroup->extra_column_names . "|" . implode('|', $field_name_array);
                        $update_types = $row_cgroup->extra_column_types . "|" . implode('|', $field_type_array);
                        $update_status = $row_cgroup->extra_column_status . "|" . implode('|', $field_status_array);
                    }
                } else {
                    $update_ids = implode('|', $field_id_array);
                    $update_names = implode('|', $field_name_array);
                    $update_types = implode('|', $field_type_array);
                    $update_status = implode('|', $field_status_array);
                }
                // Update Data Into Contact Group
                $data = array(
                    'extra_column_ids' => $update_ids,
                    'extra_column_names' => $update_names,
                    'extra_column_types' => $update_types,
                    'extra_column_status' => $update_status
                );
                $this->db->where('contact_group_id', $row_cgroup->contact_group_id);
                $this->db->update('contact_groups', $data);
            }
        }
        $group_ids_string = implode(',', $group_id_array);
        $csvFile = "./ContactCSV/" . $temp_file_name;
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (!feof($handle)) {
                $line = fgetcsv($handle);
                $row++;
                if ($line != "") {
                    //$line = explode(',', trim($record));
                    if (sizeof($line)) {
                        $contact_number;
                        if ($number_field) {
                            $contact_number = $line[$number_field - 1];
                            // Check Valid Value For Contact Number
                            if (is_numeric($contact_number)) {
                                if (strlen($contact_number) == 10) {
                                    $contact_number = "91" . $contact_number;
                                }

                                $this->db->select('`mobile_number`, `contact_name`, `extra_column_ids`, `extra_column_values`, `contact_group_ids`');
                                $this->db->from('`contacts`');
                                $this->db->where('`user_id`', $user_id);
                                $this->db->where('`mobile_number`', $contact_number);
                                $query_contact = $this->db->get();
                                // If Number Already Exists
                                if ($query_contact->num_rows()) {
                                    // Check Contact Name
                                    $contact_name = "";
                                    if ($name_field) {
                                        $contact_name = $line[$name_field - 1];
                                    }
                                    $result_contact = $query_contact->row();
                                    $ccontact_group_ids = explode(',', $result_contact->contact_group_ids);
                                    $array_diff = array_diff($group_id_array, $ccontact_group_ids);
                                    $union_array = array_unique(array_merge($ccontact_group_ids, $group_id_array));
                                    $group_id_string1 = implode(',', $union_array);
                                    $cextra_column_ids = explode('|', $result_contact->extra_column_ids);
                                    $intersect_array = array_intersect($cextra_column_ids, $field_id_array);
                                    $cextra_column_values = explode('|', $result_contact->extra_column_values);
                                    $field_id_string1 = $result_contact->extra_column_ids;
                                    $field_values_string = "";
                                    if (sizeof($intersect_array)) {
                                        foreach ($old_fields as $key => $old_id) {
                                            if (in_array($old_id, $cextra_column_ids)) {
                                                $search_key = array_search($old_id, $cextra_column_ids);
                                                $cextra_column_values[$search_key] = $line[$key];
                                            } else {
                                                $field_id_string1.="|" . $old_id;
                                                array_push($cextra_column_values, $line[$key]);
                                            }
                                        }
                                        $field_values_string = implode('|', $cextra_column_values);
                                        if (sizeof($new_fields)) {
                                            $keys = array_keys($new_fields);
                                            $field_values_string .= "|" . implode('|', array_intersect_key($line, array_flip($keys)));
                                            $field_id_string1.="|" . implode('|', $new_fields);
                                        }
                                        // Update Count
                                        // Increase
                                        foreach ($array_diff as $group_id) {
                                            $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                            $this->db->where('`contact_group_id`', $group_id);
                                            $this->db->update('contact_groups');
                                        }
                                        // Insert Contact Number
                                        $data = array(
                                            'contact_name' => $contact_name,
                                            'extra_column_ids' => $field_id_string1,
                                            'extra_column_values' => $field_values_string,
                                            'contact_group_ids' => $group_id_string1
                                        );
                                        $this->db->where('user_id', $user_id);
                                        $this->db->where('`mobile_number`', $contact_number);
                                        $this->db->update('contacts', $data);
                                    } else {
                                        // Update Count 
                                        // Decrease
                                        /*
                                          foreach ($union_array as $group_id) {
                                          $this->db->set('total_contacts', '`total_contacts`-1', FALSE);
                                          $this->db->where('`contact_group_id`', $group_id);
                                          $this->db->update('contact_groups');
                                          }
                                         */
                                        // Increase
                                        foreach ($array_diff as $group_id) {
                                            $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                            $this->db->where('`contact_group_id`', $group_id);
                                            $this->db->update('contact_groups');
                                        }
                                        $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));
                                        // Insert Contact Number
                                        $data = array(
                                            'contact_name' => $contact_name,
                                            'extra_column_ids' => $result_contact->extra_column_ids . "|" . $field_id_string,
                                            'extra_column_values' => $result_contact->extra_column_values . "|" . $field_values_string,
                                            'contact_group_ids' => $group_id_string1
                                        );
                                        $this->db->where('user_id', $user_id);
                                        $this->db->where('`mobile_number`', $contact_number);
                                        $this->db->update('contacts', $data);
                                    }
                                } else {
                                    // Update Count
                                    // Increase +1
                                    foreach ($group_id_array as $group_id) {
                                        $this->db->set('total_contacts', '`total_contacts`+1', FALSE);
                                        $this->db->where('`contact_group_id`', $group_id);
                                        $this->db->update('contact_groups');
                                    }
                                    // Check Contact Name
                                    $contact_name = "";
                                    if ($name_field) {
                                        $contact_name = $line[$name_field - 1];
                                    }
                                    $field_values_string = implode('|', array_intersect_key($line, array_flip($field_key_array)));

                                    $data = array(
                                        'contact_name' => $contact_name,
                                        'mobile_number' => $contact_number,
                                        'extra_column_ids' => $field_id_string,
                                        'extra_column_values' => $field_values_string,
                                        'contact_group_ids' => $group_ids_string,
                                        'user_id' => $user_id
                                    );

                                    $full_data[] = $data;
                                }
                            } else {
                                continue;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }

            fclose($handle);
            $this->db->insert_batch('contacts', $full_data);
            return true;
        } else {
            return false;
        }
    }

    public function getAdminName() {
        $this->db->select('admin_id,admin_username,admin_name,admin_contact,admin_email');
        $this->db->from('administrators');
        $this->db->where('admin_role', 2);
        $this->db->where('`admin_status`', 1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $result = $query->result_array();
        } else {
            return FALSE;
        }
    }

    //update account manager name by user
    public function updateAccountManager($user_id) {
        $account_manager = $this->input->post('account_manager');
        $data = array(
            'account_manager' => $account_manager
        );
        $this->db->where('`user_id`', $user_id);
        $this->db->update('users', $data);
        return true;
    }

    //white list number insert into database (start 50 numbers)

    public function insertWhiteList($result_array = array(), $user_id) {
        if ($user_id = 526) {
            $new_data = array();
            $size = sizeof($result_array);
            $this->db->select('user_id');
            $this->db->from('white_lists');
            $this->db->where('user_id ', $user_id);
            $query = $this->db->get();
            if ($query->num_rows() < 50) {
                for ($i = 0; $i < $size; $i++) {
                    $mobile = $result_array[$i];
                    $new_mobile = substr($mobile, 2, 12);
                    $new_data[] = $data = array(
                        'white_list_number' => $new_mobile,
                        'white_list_status' => 1,
                        'user_id' => $user_id
                    );
                }
                $this->db->insert_batch('white_lists', $new_data);
                return true;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function saveSpacialRatio($ref_user_id, $subtab) {
        if ($subtab == 6) {

            $spatial_tr_deliver_ratio = $this->input->post('spatial_tr_deliver_ratio');
            if ($spatial_tr_deliver_ratio > 80) {
                return FALSE;
            } else {
                $data = array(
                    'spacial_deliver_tr_ratio' => $spatial_tr_deliver_ratio
                );
                $this->db->where('`user_id`', $ref_user_id);
                if ($this->db->update('users', $data)) {
                    //UPDATE REFRENCE ID
                    $this->db->where('`ref_user_id`', $ref_user_id);
                    $this->db->update('users', $data);

                    return true;
                } else {
                    return FALSE;
                }
            }
        } else if ($subtab == 7) {

            $spatial_pr_deliver_ratio = $this->input->post('spatial_pr_deliver_ratio');
            if ($spatial_pr_deliver_ratio > 80) {
                return FALSE;
            } else {

                $data = array(
                    'spacial_deliver_pr_ratio' => $spatial_pr_deliver_ratio,
                );
                $this->db->where('`user_id`', $ref_user_id);
                if ($this->db->update('users', $data)) {
                    $this->db->where('`ref_user_id`', $ref_user_id);
                    $this->db->update('users', $data);
                    return true;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
    }

    public function count_all_users_balance($user_id) {
        $this->db->select('`user_id`');
        $this->db->from('users');
        $this->db->where('ref_user_id', $user_id);
        $query = $this->db->get();
        $result = $query->num_rows();
        return $result;
    }

    public function get_all_users_balance($user_id, $per_page, $page) {
        $this->db->select('name,username,email_address,contact_number,pr_sms_balance,tr_sms_balance,pr_voice_balance,tr_voice_balance,short_code_balance,long_code_balance,user_status');
        $this->db->select('prtodnd_balance,stock_balance,creation_date');
        $this->db->from('users');
        $this->db->where('ref_user_id', $user_id);
        $this->db->order_by('user_id', 'desc');
        $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    // get total sms of user from resseller
    public function getUserTotalSMS($ref_user_id) {
        $date = date('Y-m-d');
        $this->db->select('`status`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $ref_user_id);
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    public function getUserTotalDeduction($ref_user_id) {

        $this->db->select('SUM(`total_deducted`)');
        $this->db->from('campaigns');
        $this->db->where('user_id', $ref_user_id);
        $query = $this->db->get();
        return $result = $query->result_array();
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

    public function saveUserwWhite($user_id) {
        $number = $this->input->post('contact_number');
        if ($number) {
            $data = array(
                'white_list_number' => $number,
                'white_list_status' => 1,
                'user_id' => $user_id,
            );
            if ($this->db->insert('white_lists', $data)) {
                return true;
            } else {
                return FALSE;
            }
        } else {
            return true;
        }
    }

    public function getWhiteListNo($id) {


        $this->db->select('*');
        $this->db->from('white_lists');
        $this->db->where('user_id', $id);
        $this->db->order_by("white_list_id", "desc");
        $query = $this->db->get();
        return $result = $query->result_array();
    }

    //search existing balance
    public function searchExistBalance($user_id) {


        $select_route = $this->input->post('select_route');
        $exist_balance = $this->input->post('exist_balance');

        if ($select_route != "" && $exist_balance != "") {


            if ($select_route == 'A') {
                $route = 'pr_sms_balance';
            } elseif ($select_route == 'B') {
                $route = 'tr_sms_balance';
            } elseif ($select_route == 'C') {
                $route = 'stock_balance';
            } elseif ($select_route == 'D') {
                $route = 'prtodnd_balance';
            }

            $this->db->select('name,username,email_address,contact_number,pr_sms_balance,tr_sms_balance,pr_voice_balance,tr_voice_balance,short_code_balance,long_code_balance,user_status');
            $this->db->select('prtodnd_balance,stock_balance');
            $this->db->from('users');
            $this->db->where('ref_user_id', $user_id);
            $this->db->where($route . ' <= ', $exist_balance);
            $this->db->order_by('user_id', 'desc');
            // $this->db->limit($per_page, $page);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $result = $query->result_array();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //search existing balance by username
    public function searchAllBalance($user_id, $username) {
        $this->db->select('name,username,email_address,contact_number,pr_sms_balance,tr_sms_balance,pr_voice_balance,tr_voice_balance,short_code_balance,long_code_balance,user_status');
        $this->db->select('prtodnd_balance,stock_balance');
        $this->db->from('users');
        $this->db->where('ref_user_id', $user_id);
        $this->db->like('username', $username, 'after');
        // $this->db->limit($per_page, $page);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $result = $query->result_array();
        } else {
            return false;
        }
    }

    // check pricing for message approval
    public function checkPricing($user_id) {
        $this->db->select('*');
        $this->db->from('transaction_logs');
        $this->db->where('txn_user_to', $user_id);
        $this->db->where('txn_admin_from > ', 0);
        $this->db->order_by('txn_log_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $result = $query->result_array();
        } else {
            return false;
        }
    }

    public function updateNumberSMS($txn_id, $remainNumberOfSMS) {
        $data = array(
            'no_of_send_sms' => $remainNumberOfSMS,
        );

        $this->db->where('txn_log_id', $txn_id);
        $this->db->update('transaction_logs', $data);
    }

    function DailyTotalSmsConsumption($user_id = 0) {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '1073741824');

        $delivered_sms = 0;
        $failed_sms = 0;
        $pending_sms = 0;
        $sent_sms = 0;
        $rejected_sms = 0;
        $total_sms = 0;

        $total_status = array();
        $final_array = array();
        $final_key = array();

        $today_date = date('Y-m-d');
        $this->db->select('`status`');
        $this->db->from('sent_sms');
        $this->db->where('user_id', $user_id);
        $this->db->like('submit_date', $today_date, 'after');
        $query = $this->db->get();
        $all_status = $query->result_array();

        foreach ($all_status as $result_status) {
            $total_status[] = $result_status['status'];
        }

        $final_array = array_count_values($total_status);
        $final_key = array_keys($final_array);
        $size_final_array = sizeof($final_array);
        return $final_array;
    }

    public function getVelidateIds($approv_id, $ref_user_id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('ref_user_id', $approv_id);
        $this->db->where('user_id', $ref_user_id);
        $query = $this->db->get();
        return $result = $query->num_rows();
    }

    public function getScheduleDateMsg($campaign_id) {
        $this->db->select('`schedule_date`');
        $this->db->from('campaigns');
        $this->db->where('campaign_id', $campaign_id);
        $query = $this->db->get();
        return $result = $query->row();
    }

    public function searchAccountCunsumtion($user_id) {


        $user_from_date = $this->input->post('user_from_date');
        $to_date = $this->input->post('user_to_date');
        $user_to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));


        $sms_data = array();
        $total_ids = array();
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->or_where('ref_user_id', $user_id);
        $query = $this->db->get();
        $all_ids = $query->result_array();
        foreach ($all_ids as $ids) {
            $total_ids[] = $ids['user_id'];
        }
        $size = sizeof($total_ids);
        for ($i = 0; $i < $size; $i++) {
            $user_ids = $total_ids[$i];
            $pr_sms = 0;
            $tr_sms = 0;
            $stock_sms = 0;
            $premium_sms = 0;
            //Route A
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'A');
            $this->db->where("submit_date BETWEEN '" . $user_from_date . "' AND '" . $user_to_date . "'");
            $query_pr_sms = $this->db->get();
            $result_pr_sms = $query_pr_sms->row();
            $pr_sms = $result_pr_sms->total_messages;

            //Route B
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'B');
            $this->db->where("submit_date BETWEEN '" . $user_from_date . "' AND '" . $user_to_date . "'");
            $query_tr_sms = $this->db->get();
            $result_tr_sms = $query_tr_sms->row();
            $tr_sms = $result_tr_sms->total_messages;

            //Route C
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'C');
            $this->db->where("submit_date BETWEEN '" . $user_from_date . "' AND '" . $user_to_date . "'");
            $query_stock_sms = $this->db->get();
            $result_stock_sms = $query_stock_sms->row();
            $stock_sms = $result_stock_sms->total_messages;

            //Route D
            $this->db->select('SUM(`total_messages`) AS `total_messages`');
            $this->db->from('campaigns');
            $this->db->where('user_id', $user_ids);
            $this->db->where('route', 'D');
            $this->db->where("submit_date BETWEEN '" . $user_from_date . "' AND '" . $user_to_date . "'");
            $query_premium_sms = $this->db->get();
            $result_premium_sms = $query_premium_sms->row();
            $premium_sms = $result_premium_sms->total_messages;

            $sms_data[] = $data = array(
                'user_id' => $user_ids,
                'promotional_sms' => $pr_sms,
                'transactional_sms' => $tr_sms,
                'stock_sms' => $stock_sms,
                'premium_sms' => $premium_sms,
            );
        }
        return $sms_data;
    }

    public function searchGroupContact($user_id, $group_id, $search) {
        $this->db->select('*');
        $this->db->from('contacts');
        $this->db->like('mobile_number', $search);
        $this->db->where('user_id', $user_id);
        $this->db->where('contact_group_ids', $group_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $data = $query->result_array();
            return $data;
        } else {
            return false;
        }
    }

}
