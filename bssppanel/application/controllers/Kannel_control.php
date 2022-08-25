<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kannel_Control extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Kannel_Model', 'kannel_model');
        $this->load->model('Admin_Data_Model', 'admin_data_model');
        // Update Login Info And Some Common Data
        $data = new stdClass();
        if ($this->session->userdata('admin_logged_in')) {
            $session_userdata = $this->session->userdata('admin_logged_in');
            $username = $session_userdata['username'];
            $admin_id = $session_userdata['admin_id'];
            $atype = $session_userdata['atype'];
            // Admin Username,Admin Id & Admin Type
            if ($admin_id) {
                $data->admin_id = $admin_id;
                $data->username = $username;
                $data->atype = $atype;
            }
            // Admin Total Balance (TR, PR, Long Code, Short Code)
            $admin_balance = $this->admin_data_model->getAdminBalance($admin_id);
            if ($admin_balance) {
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
    // Load All Kannel Configuration View
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Kannel Configuration Dashboard
    public function index() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('16', $permissions_array)) {
                $data['page_title'] = "Kannel Configuration Files";
                $this->load->view('kannel/header', $data);
                $this->load->view('kannel/index');
                $this->load->view('kannel/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Kannel Configuration Dashboard
    public function dashboard() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('16', $permissions_array)) {
                $data['page_title'] = "Kannel Configuration Dashboard";
                $this->load->view('kannel/header', $data);
                $this->load->view('kannel/dashboard');
                $this->load->view('kannel/footer');
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Create Kannel Configuration
    public function create($group_type = null, $type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $data['page'] = $group_type;
            if ($type == 'transceiver') {
                $data['id'] = 'smsc_trx';
            } elseif ($type == 'transmitter') {
                $data['id'] = 'smsc_tx';
            } elseif ($type == 'receiver') {
                $data['id'] = 'smsc_rx';
            } else {
                $data['id'] = $type;
            }
            switch ($group_type) {
                case 'core':
                    $data['page_title'] = "Create Core Group";
                    break;
                case 'smsbox':
                    $data['page_title'] = "Create SMSBox Group";
                    break;
                case 'db_connection':
                    $data['page_title'] = "Create DB Connection Group";
                    break;
                case 'dlr_db':
                    $data['page_title'] = "Create DLR DB Group";
                    break;
                case 'smsc':
                    $data['page_title'] = "Create Real SMSC Group";
                    break;
                case 'fake_smsc':
                    $data['page_title'] = "Create Fake SMSC Group";
                    break;
                case 'smsbox_route':
                    $data['page_title'] = "Create SMSBox Route Group";
                    break;
                case 'sendsms_user':
                    $data['page_title'] = "Create SendSMS User Group";
                    break;
                case 'sms_service':
                    $data['page_title'] = "Create SMS Service Group";
                    break;
                case 'sqlbox':
                    $data['page_title'] = "Create SQLBox Group";
                    break;
                default:
                    $data['page_title'] = "Kannel Configuration";
                    break;
            }
            $this->load->view('kannel/header', $data);
            $this->load->view('kannel/create');
            $this->load->view('kannel/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Save Kannel Configuration Into File
    public function save_config($group_type = null, $type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $result = $this->kannel_model->saveConfiguration();
            if ($result) {
                $this->session->set_flashdata('message', 'Success: Configuration created successfully!');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Error: Configuration creation failed!');
                $this->session->set_flashdata('message_type', 'danger');
            }
            redirect('kannel_control/index');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Update Kannel Configuration
    public function update($group_type = null, $file = null) {
        if ($this->session->userdata('admin_logged_in')) {
            if ($group_type == 'smsc_trx' || $group_type == 'smsc_tx' || $group_type == 'smsc_rx') {
                $data['page'] = 'smsc';
                $data['id'] = $group_type;
            } else {
                $data['page'] = $group_type;
            }
            $data['file'] = $file;
            switch ($group_type) {
                case 'core':
                    $data['page_title'] = "Update Core Group";
                    break;
                case 'smsbox':
                    $data['page_title'] = "Update SMSBox Group";
                    break;
                case 'db_connection':
                    $data['page_title'] = "Update DB Connection Group";
                    break;
                case 'dlr_db':
                    $data['page_title'] = "Update DLR DB Group";
                    break;
                case 'smsc_trx':
                    $data['page_title'] = "Update Real SMSC Group";
                    break;
                case 'smsc_tx':
                    $data['page_title'] = "Update Real SMSC Group";
                    break;
                case 'smsc_rx':
                    $data['page_title'] = "Update Real SMSC Group";
                    break;
                case 'fake_smsc':
                    $data['page_title'] = "Update Fake SMSC Group";
                    break;
                case 'smsbox_route':
                    $data['page_title'] = "Update SMSBox Route Group";
                    break;
                case 'sendsms_user':
                    $data['page_title'] = "Update SendSMS User Group";
                    break;
                case 'sms_service':
                    $data['page_title'] = "Update SMS Service Group";
                    break;
                case 'sqlbox':
                    $data['page_title'] = "Update SQLBox Group";
                    break;
                default:
                    $data['page_title'] = "Kannel Configuration";
                    break;
            }
            $this->load->view('kannel/header', $data);
            $this->load->view('kannel/update');
            $this->load->view('kannel/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Delete Kannel Configuration
    public function delete($group_type = null, $file = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $response = $this->kannel_model->deleteConfiguration($group_type, $file);
            if ($response) {
                $this->session->set_flashdata('message', 'Success: Configuration deleted successfully!');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Error: Configuration deletion failed!');
                $this->session->set_flashdata('message_type', 'danger');
            }
            redirect('kannel_control/index');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Generate Kannel Configuration
    public function generate($file_type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $data['page'] = $file_type;
            //$data['page_title'] = "Kannel Configuration";
            $this->load->view('kannel/header', $data);
            $this->load->view('kannel/generate');
            $this->load->view('kannel/footer');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // Generate Kannel Configuration Into File
    public function generate_config($file_type = null) {
        if ($this->session->userdata('admin_logged_in')) {
            $response = $this->kannel_model->generateConfiguration($file_type);
            if ($response) {
                $this->session->set_flashdata('message', 'Success: Configuration generated successfully!');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', 'Error: Configuration generation failed!');
                $this->session->set_flashdata('message_type', 'danger');
            }
            redirect('kannel_control/index');
        } else {
            redirect('admin', 'refresh');
        }
    }

    // SQLBox User-Guide
    public function sqlbox_userguide() {
        $this->load->view('kannel/sqlbox-userguide.html');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Kannel Monitor Wondow For SMS Tracking
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Kannel Monitor
    public function monitor() {
        if ($this->session->userdata('admin_logged_in')) {
            $session_data = $this->session->userdata('admin_logged_in');
            $permissions = $this->load->get_var('permissions');
            $permissions_array = explode(',', $permissions);
            if (isset($permissions_array) && $permissions_array && in_array('15', $permissions_array)) {
                $domain_name = $_SERVER['SERVER_NAME'];
                if ($domain_name == 'sms.bulksmsserviceproviders.com' || $domain_name == 'localhost' || $domain_name == '192.168.1.231') {
                    $this->load->view('kannel/monitor');
                } else {
                    redirect('admin', 'refresh');
                }
            } else {
                redirect('admin/unauthorized_access', 'refresh');
            }
        } else {
            redirect('admin', 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // PDF Generation
    //------------------------------------------------------------------------------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Generate PDF
    public function pdf() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        //load the view, pass the variable and do not show it but "save" the output into $html variable        
        $data['company'] = "Bulk24SMS Networks";
        $html = $this->load->view('kannel/pdf_output', $data, true);
        //this the the PDF filename that user will get to download
        $pdfFilePath = "output_monitor.pdf";
        //load mPDF library
        $this->load->library('m_pdf');
        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        // Add External CSS Files
        $stylesheet = file_get_contents('http://192.168.1.231/BulkSMSAPP/Assets/kannel/css/bootstrap.min.css'); // external css
//        $stylesheet1 = file_get_contents('http://192.168.1.231/BulkSMSAPP/Assets/kannel/css/dashboard.css'); // external css
        $pdf->WriteHTML($stylesheet, 1);
//        $pdf->WriteHTML($stylesheet1, 2);
        //generate the PDF!
        $pdf->WriteHTML($html, 3);
        /*
          $style1 = file_get_contents(base_url() . 'Assets/kannel/css/bootstrap.min.css');
          $style2 = file_get_contents(base_url() . 'Assets/kannel/css/dashboard.css');
          $script1 = file_get_contents(base_url() . 'Assets/admin/js/jquery-1.11.1.min.js');
          $script2 = file_get_contents(base_url() . 'Assets/kannel/js/bootstrap.min.js');
          $pdf->WriteHTML($style1, 2);
          $pdf->WriteHTML($style2, 3);
          $pdf->WriteHTML($script1, 4);
          $pdf->WriteHTML($script2, 5);
         */
        //offer it to user via browser download! (The PDF won't be saved on your server HDD)
        //$pdf->Output("./Reports/" . $pdfFilePath, "F");
        $pdf->Output();
        exit();
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    
    //Mail alert when kannel is OFF
    public function kannel_status_mail(){
   $this->kannel_model->kannelStatusMail();  
    }
   //------------------------------------------------------------------------------------------------------------------------------------------//   
}

?>