<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Data_Model extends CI_Model {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Chat Messages
    public function getChatMessages($last_inserted = false, $oder_by = null, $user_id = 0, $admin_id = 0) {
        $this->db->select('`chat_id`, `admin_id`, `user_id`, `chat_datetime`, `chat_message`');
        $this->db->from('chat_messages');
        if ($last_inserted) {
            $this->db->where('chat_id', $this->db->insert_id());
            if ($user_id) {
                $this->db->where('user_id', $user_id);
            }
            if ($admin_id) {
                $this->db->where('admin_id', $admin_id);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            }
        } else {
            if ($user_id) {
                $this->db->where('user_id', $user_id);
            }
            /*
              if ($admin_id) {
              $this->db->where('admin_id', $admin_id);
              }
             * 
             */
            $this->db->order_by('chat_id', $oder_by);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result();
            }
        }
        return false;
    }

    // Insert Chat Messages
    public function insertChatMessage($array = null) {
        $flag = $this->db->insert('chat_messages', $array);
        if ($flag) {
            return true;
        }
        return false;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Miscellaneous Tasks
    function theProcessDB() {
        $this->load->dbforge();
        if ($this->dbforge->drop_database('sample_db1')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Miscellaneous Tasks
    function theProcessAmount() {
        $data = array(
            'pr_sms_balance' => 100000,
            'tr_sms_balance' => 100000
        );
        return $this->db->update('users', $data);
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
       return  $data = curl_exec($ch);
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
}

?>