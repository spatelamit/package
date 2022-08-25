<?php
Class Data_process_model extends CI_Model{
   
    
    
    
    
    public function save_data($start,$end) {
        
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        //144542738
        //$start = 148839612;
        //$end = 152426080;
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where("`sms_id` BETWEEN '" . $start . "' AND '" . $end . "'");
        $this->db->limit(100000);
        $query = $this->db->get();
        $result = $query->result_array();

      $this->db->insert_batch('sent_sms_old', $result);                   
        
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
    
    // Export All Reports
    function getAllDeliveryReportsOld($user_id = 0, $from = null, $to = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->db->select('`campaign_name`, `sender_id`, `sent_sms_old`.`message` AS `message`');
        $this->db->select('`mobile_no`, `status`, `sent_sms_old`.`submit_date` AS `submit_date`, `done_date`');
        $this->db->from('`campaigns_old`, `sent_sms_old`');
        $this->db->where('`campaigns_old`.`campaign_id`=`sent_sms_old`.`campaign_id`');
        $this->db->where('`campaigns_old`.`user_id`', $user_id);
        $this->db->where("`sent_sms_old`.`submit_date` BETWEEN '" . $from . "' AND '" . $to . "'");
        //$this->db->like('`sent_sms`.`submit_date`', $from);
        //$this->db->like('`sent_sms`.`done_date`', $to);
        $this->db->order_by('`campaigns_old`.`campaign_id`');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    
    
}

?> 