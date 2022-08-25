<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System_alerts extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('content_management_model', 'Content_management_model');
        $this->load->model('system_alert_model', 'System_alert_model');
    }

//load attendance tab
    public function index() {
        if ($this->session->userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $school_id = $user_data['school_id'];
            $room_name['logo'] = $this->Content_management_model->showSchoolLogo($school_id);
            if ($room_name) {
                $this->load->view('employee/header', $room_name);
                $this->load->view('alerts/index');
                $this->load->view('employee/footer');
            } else {
                $this->load->view('employee/header');
                $this->load->view('alerts/index');
                $this->load->view('employee/footer');
            }
        } else {
            redirect('Signup/login', 'refresh');
        }
    }

    public function sms_alerts_setting() {
        if ($this->session->userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $school_id = $user_data['school_id'];
            $room_name['logo'] = $this->Content_management_model->showSchoolLogo($school_id);
               $room_name['sms_alerts_name'] = $this->System_alert_model->getAlertsName();
            if ($room_name) {
                $this->load->view('employee/header', $room_name);
                $this->load->view('alerts/send_sms_setting');
                $this->load->view('employee/footer');
            } else {
                $this->load->view('employee/header');
                $this->load->view('alerts/send_sms_setting');
                $this->load->view('employee/footer');
            }
        } else {
            redirect('Signup/login', 'refresh');
        }
    }

    public function sms_api_configration() {
        if ($this->session->userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $school_id = $user_data['school_id'];
            $sms_api['logo'] = $this->Content_management_model->showSchoolLogo($school_id);
            $sms_api['api_value'] = $this->System_alert_model->get_sms_api($school_id);
            if ($sms_api) {
                $this->load->view('employee/header', $sms_api);
                $this->load->view('alerts/sms_api_form');
                $this->load->view('employee/footer');
            } else {
                $this->load->view('employee/header');
                $this->load->view('alerts/sms_api_form');
                $this->load->view('employee/footer');
            }
        } else {
            redirect('Signup/login', 'refresh');
        }
    }

    public function save_sms_api() {
        if ($this->session->userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $school_id = $user_data['school_id'];
            $response = $this->System_alert_model->saveSmsApi($school_id);
            if ($response) {
                $session_array = array('message' => "<i class='fa fa-check-circle'></i> API Update Successfully ", 'tab' => 3, 'value' => 1);
                $this->session->set_userdata('set_api', $session_array);
                redirect('System_alerts/sms_api_configration', 'refresh');
            } else {
                $session_array = array('message' => "<i class='fa fa-times-circle-o'></i> API Updation Failed ", 'tab' => 3, 'value' => 0);
                $this->session->set_userdata('set_api', $session_array);
                redirect('System_alerts/sms_api_configration', 'refresh');
            }
        } else {
            redirect('Signup/login', 'refresh');
        }
    }
    public function save_school_alert_name(){
         if ($this->session->userdata('logged_in')) {
            $user_data = $this->session->userdata('logged_in');
            $school_id = $user_data['school_id'];
            $response = $this->System_alert_model->saveSchoolSMSAlert($school_id);
            if ($response) {
                $session_array = array('message' => "<i class='fa fa-check-circle'></i> API Update Successfully ", 'tab' => 3, 'value' => 1);
                $this->session->set_userdata('set_api', $session_array);
                redirect('System_alerts/sms_api_configration', 'refresh');
            } else {
                $session_array = array('message' => "<i class='fa fa-times-circle-o'></i> API Updation Failed ", 'tab' => 3, 'value' => 0);
                $this->session->set_userdata('set_api', $session_array);
                redirect('System_alerts/sms_api_configration', 'refresh');
            }
        } else {
            redirect('Signup/login', 'refresh');
        }
    }

  
    }
    