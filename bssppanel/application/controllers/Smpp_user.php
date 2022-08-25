<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smpp_user extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('smpp_data_model', '', TRUE);
    }
 
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // SMPP User Home
    function index() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];

            // SMPP User Balance
            $result_user = $this->smpp_data_model->getSMPPUser($user_id);
            $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
            $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
            $data['smpp_open_balance'] = $result_user->smpp_open_balance;

            $data['smpp_user'] = $result_user;

            $this->load->view('smpp/header', $data);
            $this->load->view('smpp/index');
            $this->load->view('smpp/footer');
        } else {
           // redirect('home', 'refresh');
             redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    }

    // Update Profile
    function save_user_profile() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];

            $response = $this->smpp_data_model->updateSMPPUser($user_id);
            if ($response) {
                $data['success_msg'] = "Profile Updated Successfully!";
                // SMPP User Balance
                $result_user = $this->smpp_data_model->getSMPPUser($user_id);
                $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
                $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
                $data['smpp_open_balance'] = $result_user->smpp_open_balance;
                $data['smpp_user'] = $result_user;
                $this->load->view('smpp/header', $data);
                $this->load->view('smpp/index');
                $this->load->view('smpp/footer');
            } else {
                $data['error_msg'] = "Profile Updation Failed!";
                // SMPP User Balance
                $result_user = $this->smpp_data_model->getSMPPUser($user_id);
                $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
                $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
                $data['smpp_open_balance'] = $result_user->smpp_open_balance;
                $data['smpp_user'] = $result_user;
                $this->load->view('smpp/header', $data);
                $this->load->view('smpp/index');
                $this->load->view('smpp/footer');
            }
        } else {
           // redirect('home', 'refresh');
            redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    }

    // SMPP Information
    function smpp_info() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];

            // SMPP User Balance
            $result_user = $this->smpp_data_model->getSMPPUser($user_id);
            $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
            $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
            $data['smpp_open_balance'] = $result_user->smpp_open_balance;

            $smpp_pr_port = $result_user->smpp_pr_port;
            $smpp_tr_port = $result_user->smpp_tr_port;
            $smpp_open_port = $result_user->smpp_open_port;

            $data['smpp_pr_port'] = "";
            $data['smpp_tr_port'] = "";
            $data['smpp_open_port'] = "";
            if ($smpp_pr_port) {
                $result_port = $this->smpp_data_model->getSMPPPort($smpp_pr_port);
                $data['smpp_pr_port'] = $result_port->virtual_port_no;
            }
            if ($smpp_tr_port) {
                $result_port = $this->smpp_data_model->getSMPPPort($smpp_tr_port);
                $data['smpp_tr_port'] = $result_port->virtual_port_no;
            }
            if ($smpp_open_port) {
                $result_port = $this->smpp_data_model->getSMPPPort($smpp_open_port);
                $data['smpp_open_port'] = $result_port->virtual_port_no;
            }

            $data['smpp_user'] = $result_user;

            $this->load->view('smpp/header', $data);
            $this->load->view('smpp/smpp-info');
            $this->load->view('smpp/footer');
        } else {
            // redirect('home', 'refresh');
               redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
          
        }
    }

    // My Transactions
    function my_transactions() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];
            // SMPP User Balance
            $result_user = $this->smpp_data_model->getSMPPUser($user_id);
            $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
            $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
            $data['smpp_open_balance'] = $result_user->smpp_open_balance;

            $data['transactions'] = $this->smpp_data_model->getMyTransactions($user_id);
            $this->load->view('smpp/header', $data);
            $this->load->view('smpp/my-transactions');
            $this->load->view('smpp/footer');
        } else {
          // redirect('home', 'refresh');
            redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    }

    // Change Password
    function change_password() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];

            // SMPP User Balance
            $result_user = $this->smpp_data_model->getSMPPUser($user_id);
            $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
            $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
            $data['smpp_open_balance'] = $result_user->smpp_open_balance;

            $data['smpp_user'] = $result_user;

            $this->load->view('smpp/header', $data);
            $this->load->view('smpp/change-password');
            $this->load->view('smpp/footer');
        } else {
             // redirect('home', 'refresh');
           redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    }

    // Save New Password
    function save_password() {
        if ($this->session->userdata('smpp_logged_in')) {
            $session_data = $this->session->userdata('smpp_logged_in');
            $data['username'] = $session_data['smpp_username'];
            $data['user_id'] = $session_data['smpp_user_id'];
            $user_id = $session_data['smpp_user_id'];

            $response = $this->smpp_data_model->updateSMPPPassword($user_id);
            if ($response) {
                $data['success_msg'] = "Password Changed Successfully!";
                // SMPP User Balance
                $result_user = $this->smpp_data_model->getSMPPUser($user_id);
                $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
                $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
                $data['smpp_open_balance'] = $result_user->smpp_open_balance;
                $data['smpp_user'] = $result_user;

                $this->load->view('smpp/header', $data);
                $this->load->view('smpp/change-password');
                $this->load->view('smpp/footer');
            } else {
                $data['error_msg'] = "Current password is not matched!";
                // SMPP User Balance
                $result_user = $this->smpp_data_model->getSMPPUser($user_id);
                $data['smpp_pr_balance'] = $result_user->smpp_pr_balance;
                $data['smpp_tr_balance'] = $result_user->smpp_tr_balance;
                $data['smpp_open_balance'] = $result_user->smpp_open_balance;
                $data['smpp_user'] = $result_user;

                $this->load->view('smpp/header', $data);
                $this->load->view('smpp/change-password');
                $this->load->view('smpp/footer');
            }
        } else {
            // redirect('home', 'refresh');
            redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Logout
    function logout() {
        $this->session->unset_userdata('smpp_logged_in');
         //redirect('home', 'refresh');
            redirect('sav4h5gs5xf5wat85kgv58474r5d', 'refresh');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
}

?>