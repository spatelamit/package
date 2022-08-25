<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Bulksms_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Basic API
    //---------------------------------------------------------------------------------------------------------------------------------------------------//
   
    // Insert Bulk SMS Campaign
    function insertCampaign($data_campaign = array()) {
        return $this->db->insert('campaigns', $data_campaign);
    }

}