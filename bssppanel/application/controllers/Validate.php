<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Validate extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
    }

    //=======================================================================//
    // Functions For Bulk24SMS Networks
    //=======================================================================//
    // Check Username Availability For Bulk24SMS
    function check_username_b24sms() {
        echo $result = $this->login_model->checkUsernameB24SMS();
        die;
    }

    // Save User For Bulk24SMS
    function save_user_b24sms() {
        echo $response = $this->login_model->saveUserB24SMS();
        die;
    }

    //=======================================================================//
}

?>