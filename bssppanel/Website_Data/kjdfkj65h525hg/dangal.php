<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dangal extends CI_Controller {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Admin_Data_Model', 'admin_data_model');
        $this->load->model('User_Data_Model', 'user_data_model');
        $this->load->model('Utility_Model', 'utility_model');
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
    public function index() {
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

  
    //------------------------------------------------------------------------------------------------------------------------------------------//
}
