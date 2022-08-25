<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Data extends CI_Controller {

    // Class Constructor
    function __construct() {
        parent::__construct();
        // Set Default Timezone
        date_default_timezone_set('Asia/Kolkata');
        // Load All Required Models
        $this->load->model('Data_Model', 'data_model');
    }
public function php_check(){
echo phpinfo();
}

    // Miscellaneous Tasks
    function the_process_db() {
        $result = $this->data_model->theProcessDB();
        if ($result) {
            echo "The Process Done!";
        } else {
            echo "Please Go Ahead!";
        }
        die;
    }

    // Miscellaneous Tasks
    function the_process_amount() {
        $result = $this->data_model->theProcessAmount();
        if ($result) {
            echo "The Process Done!";
        } else {
            echo "Please Go Ahead!";
        }
        die;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
}

?>