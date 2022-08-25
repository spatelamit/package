<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_docs extends CI_Controller {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('user_data_model', '', TRUE);
        // Update Login Info And Some Common Data
        $data = new stdClass();
        if ($this->session->userdata('logged_in')) {
            $session_userdata = $this->session->userdata('logged_in');
            $user_id = $session_userdata['user_id'];
            $username = $session_userdata['username'];
            // Load Model And Update Login Info
            $this->utility_model->updateLoginInfo($user_id, "ON");
            // Username & User Id
            if ($user_id) {
                $data->user_id = $user_id;
                $data->username = $username;
                $data->user_info = $this->user_data_model->getUser($user_id);
            }
            if (isset($session_userdata['reseller_user'])) {
                $data->reseller_user = $session_userdata['reseller_user'];
                $data->login_from = $session_userdata['login_from'];
            }
            $data->domain_name = base_url();
        }
        $this->load->vars($data);
        // Set Header Info
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Load All API View
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Regenerate Key
    function regenerate_key() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            echo $this->user_data_model->regenerateAuthKey1($user_id);
            die;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Basics APIs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Check Balance
    function check_balance() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "basic";
            $this->load->view('api/header', $data);
            $this->load->view('api/basics');
            $this->load->view('api/footer');
        } else {
            
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Change Password
    function change_password() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "basic";
            $this->load->view('api/header', $data);
            $this->load->view('api/basics');
            $this->load->view('api/footer');
        } else {
           
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Validation
    function validation() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "basic";
            $this->load->view('api/header', $data);
            $this->load->view('api/basics');
            $this->load->view('api/footer');
        } else {
          
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Text SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send SMS
    function send_sms_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "send_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/text-sms');
            $this->load->view('api/footer');
        } else {
            
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }
     // Send  Voice SMS
    function voice_sms_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "voice_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/voice-sms');
            $this->load->view('api/footer');
        } else {
          
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // XML Send SMS
    function xml_send_sms_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "send_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/text-sms');
            $this->load->view('api/footer');
        } else {
          
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

      // XML Send Voice SMS
    function xml_send_voice_sms_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "send_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/voice-sms');
            $this->load->view('api/footer');
        } else {
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }
    
    // Delivery Report Web Hooks
    function delivery_report_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "send_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/text-sms');
            $this->load->view('api/footer');
        } else {
            
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

      // Voice Delivery Report Web Hooks
    function voice_delivery_report_api() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "send_sms";
            $this->load->view('api/header', $data);
            $this->load->view('api/voice-sms');
            $this->load->view('api/footer');
        } else {
            
            
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Sample Codes APIs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // In PHP
    function in_php() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
          
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In C#
    function in_csharp() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
          
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In Java
    function in_java() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
          
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In Java XML
    function in_java_xml() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "4";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
            
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    
        // In Java jsp
    function in_java_jsp() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "10";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
            
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }
    
    
    
    // In Python
    function in_python() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "5";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
           
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In Windows 8
    function in_windows8() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "6";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
           
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In Android
    function in_android() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "7";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
           
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In iOS
    function in_ios() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "8";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
          
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // In VB6
    function in_vb6() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "9";
            $data['page'] = "sample_code";
            $this->load->view('api/header', $data);
            $this->load->view('api/sample-codes');
            $this->load->view('api/footer');
        } else {
           
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Reseller APIs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Add New User
    function add_user() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // List Users
    function list_users() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Update User Balance
    function update_user_balance() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
           
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Manage Users
    function manage_users() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "4";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Forget Password
    function forget_password() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "5";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // View Own Profile
    function view_own_profile() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "6";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
          
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // View Account Expiry
    function view_account_expiry() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "7";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
          
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // View User Profile
    function view_user_profile() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "8";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Change User Password
    function change_user_password() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "9";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Transaction History
    function transaction_history() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "10";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
           
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }
    
    public function check_users_balance(){
          if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "11";
            $data['page'] = "reseller";
            $this->load->view('api/header', $data);
            $this->load->view('api/resellers');
            $this->load->view('api/footer');
        } else {
            
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Phonebook APIs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Add Group
    function add_group() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "1";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
            
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Delete Group
    function delete_group() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "2";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
          
          //  redirect('home', 'refresh');
             header('location:' . base_url());
            
        }
    }

    // List Groups
    function list_groups() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "3";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
            
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Add Contact
    function add_contact() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "4";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
          
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Edit Contact
    function edit_contact() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "5";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
           
           // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // Delete Contact
    function delete_contact() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "6";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
          
            // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    // List Contacts
    function list_contacts() {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = "7";
            $data['page'] = "phonebook";
            $this->load->view('api/header', $data);
            $this->load->view('api/phonebook');
            $this->load->view('api/footer');
        } else {
           
          //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Generate API
    //------------------------------------------------------------------------------------------------------------------------------------------//
    function show_generate_api($page = 0, $tab = 0, $method = null) {
        if ($this->session->userdata('logged_in')) {
            $api_string = "";
             $authkey = $this->input->post('authkey');
            
            if ($page === '1' && $tab === '1') {
                $route = $this->input->post('route');
                $api_string = $method . ".php?authkey=" . $authkey . "&route=" . $route;
            }
            if ($page === '1' && $tab === '2') {
                $cpassword = $this->input->post('password');
                $npassword = $this->input->post('npassword');
                $cnpassword = $this->input->post('cnpassword');
                $api_string = $method . ".php?authkey=" . $authkey . "&cpassword=" . $cpassword . "&npassword=" . $npassword .
                        "&ncpassword=" . $cnpassword;
            }
            if ($page === '1' && $tab === '3') {
                $api_string = $method . ".php?authkey=" . $authkey;
            }
            if ($page === '2' && $tab === '1') {
                $full_name = $this->input->post('full_name');
                $username = $this->input->post('username');
                $mobile = $this->input->post('mobile');
                $email = $this->input->post('email');
                $company = $this->input->post('company');
                $industry = $this->input->post('industry');
                $expiry = $this->input->post('expiry');
                $api_string = $method . ".php?authkey=" . $authkey . "&full_name=" . urlencode($full_name) .
                        "&username=" . $username .
                        "&mobile=" . $mobile . "&email=" . urlencode($email) . "&company=" . urlencode($company) .
                        "&industry=" . urlencode($industry) . "&expiry=" . urlencode($expiry);
            }
            if ($page === '2' && $tab === '2') {
                $api_string = $method . ".php?authkey=" . $authkey;
            }
            if ($page === '2' && $tab === '3') {
                $buser_id = $this->input->post('buser_id');
                $sms = $this->input->post('sms');
                $account_type = $this->input->post('account_type');
                $type = $this->input->post('type');
                $price = $this->input->post('price');
                $description = $this->input->post('description');
                $api_string = $method . ".php?authkey=" . $authkey . "&user_id=" . $buser_id . "&sms=" . $sms .
                        "&account_type=" . urlencode($account_type) . "&type=" . urlencode($type) . "&price=" . urlencode($price) .
                        "&description=" . urlencode($description);
            }
            if ($page === '2' && $tab === '4') {
                $muser_id = $this->input->post('muser_id');
                $status = $this->input->post('status');
                $api_string = $method . ".php?authkey=" . $authkey . "&user_id=" . $muser_id . "&status=" . $status;
            }
            if ($page === '2' && $tab === '5') {
                $fusername = $this->input->post('fusername');
                $api_string = $method . ".php?authkey=" . $authkey . "&username=" . urlencode($fusername);
            }
            if ($page === '2' && $tab === '6') {
                $api_string = $method . ".php?authkey=" . $authkey;
            }
            if ($page === '2' && $tab === '7') {
                $api_string = $method . ".php?authkey=" . $authkey;
            }
            if ($page === '2' && $tab === '8') {
                $puser_id = $this->input->post('puser_id');
                $api_string = $method . ".php?authkey=" . $authkey . "&user_id=" . $puser_id;
            }
            if ($page === '2' && $tab === '9') {
                $pusername = $this->input->post('pusername');
                $new_password = $this->input->post('new_password');
                $api_string = $method . ".php?authkey=" . $authkey . "&user_id=" . urlencode($pusername) .
                        "&user_password=" . urlencode($new_password);
            }
            if ($page === '2' && $tab === '10') {
               $client_id = $this->input->post('user_id');
                $api_string = $method . ".php?authkey=" . $authkey."&user_id=".$client_id;
            }
             if ($page === '2' && $tab === '11') {
                 $bal_user_id = $this->input->post('bal_user_id');
                $api_string = $method . ".php?authkey=" . $authkey."&bal_user_id=".$bal_user_id;
               
            }
            if ($page === '3' && $tab === '1') {
                $group_name = $this->input->post('group_name');
                $api_string = $method . ".php?authkey=" . $authkey . "&group_name=" . $group_name;
            }
            if ($page === '3' && $tab === '2') {
                $dgroup_id = $this->input->post('dgroup_id');
                $api_string = $method . ".php?authkey=" . $authkey . "&group_id=" . $dgroup_id;
            }
            if ($page === '3' && $tab === '3') {
                $api_string = $method . ".php?authkey=" . $authkey;
            }
            if ($page === '3' && $tab === '4') {
                $acontact_name = $this->input->post('acontact_name');
                $amobile = $this->input->post('amobile');
                $agroup_id = $this->input->post('agroup_id');
                $api_string = $method . ".php?authkey=" . $authkey . "&name=" . urlencode($acontact_name) .
                        "&mobile_no=" . urlencode($amobile) . "&group_id=" . urlencode($agroup_id);
            }
            if ($page === '3' && $tab === '5') {
                $econtact_id = $this->input->post('econtact_id');
                $egroup_id = $this->input->post('egroup_id');
                $emobile = $this->input->post('emobile');
                $ename = $this->input->post('ename');
                $api_string = $method . ".php?authkey=" . $authkey . "&contact_id=" . urlencode($econtact_id) .
                        "&egroup_id=" . urlencode($egroup_id) . "&mobile_no=" . urlencode($emobile) . "&name=" . urlencode($ename);
            }
            if ($page === '3' && $tab === '6') {
                $dcontact_id = $this->input->post('dcontact_id');
                $api_string = $method . ".php?authkey=" . $authkey . "&contact_id=" . $dcontact_id;
            }
            if ($page === '3' && $tab === '7') {
                $lgroup_id = $this->input->post('lgroup_id');
                $api_string = $method . ".php?authkey=" . $authkey . "&group_id=" . $lgroup_id;
            }
            if ($page === '4' && $tab === '1') {
                $mobiles = $this->input->post('mobiles');
                $message = $this->input->post('message');
                $sender = $this->input->post('sender');
                $route = $this->input->post('route');
                 $duration = $this->input->post('duration');
                $api_string = $method . ".php?authkey=" . $authkey . "&mobiles=" . $mobiles . "&message=" . urlencode($message) .
                        "&sender=" . $sender . "&route=" . $route;
                 if ($this->input->post('duration') != '') {
                    $api_string .= "&duration=" . $this->input->post('duration');
                }
                if ($this->input->post('flash') != '') {
                    $api_string .= "&flash=" . $this->input->post('flash');
                }
                if ($this->input->post('unicode') != '') {
                    $api_string .= "&unicode=" . $this->input->post('unicode');
                }
                if ($this->input->post('schdate') != '') {
                    $api_string .= "&schtime=" . $this->input->post('schdate');
                }
                if ($this->input->post('response') != '') {
                    $api_string .= "&response=" . $this->input->post('response');
                }
                if ($this->input->post('campaign') != '') {
                    $api_string .= "&campaign=" . $this->input->post('campaign');
                }
            }
            if ($page == 4 && $tab == 2) {
                $xml_data = $this->input->post('xml_data');
                $api_string = $method . ".php?xml=" . urlencode($xml_data);
            }
            $data['api_string'] = $api_string;

            $this->load->view('api/show-generate-api', $data);
        }
    }

    // Call API
    function call_api() {
        if ($this->session->userdata('logged_in')) {
           
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $url = $myArray[0] . "&ip=$_SERVER[REMOTE_ADDR]";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            echo "<h4>Output -</h4><p class='text-success'>" . curl_exec($ch) . "</p>";
            curl_close($ch);
             
           
           
            die;
        }
    }

    // Check XML Code
    function check_xml_code() {
        if ($this->session->userdata('logged_in')) {
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $xml_data = $myArray[0];
            $xml = new XMLReader();
            if (!$xml->xml($xml_data, NULL, LIBXML_DTDVALID)) {
                echo "<div class='col-md-12 mt5'><p class='text-success' id='generate_api_id'>XML not valid. Please check</p></div>";
                die;
            } else {
                echo "<div class='col-md-12 mt5'><p class='text-success' id='generate_api_id'>XML valid</p></div>";
                die;
            }
        }
    }

    // Change Theme
    function change_theme($theme = null) {
        if ($this->session->userdata('logged_in')) {
            $session_array = array('theme' => $theme);
            $this->session->set_userdata('theme_data', $session_array);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Error Codes
    function error_codes($type = null) {
        if ($this->session->userdata('logged_in')) {
            if ($type == 'basic') {
                $data['page_type'] = "4";
                $data['page'] = "basic";
                $this->load->view('api/header', $data);
                $this->load->view('api/basics');
                $this->load->view('api/footer');
            } elseif ($type == 'text_sms') {
                $data['page_type'] = "4";
                $data['page'] = "send_sms";
                $this->load->view('api/header', $data);
                $this->load->view('api/text-sms');
                $this->load->view('api/footer');
            } elseif ($type == 'reseller') {
                $data['page_type'] = "12";
                $data['page'] = "reseller";
                $this->load->view('api/header', $data);
                $this->load->view('api/resellers');
                $this->load->view('api/footer');
            } elseif ($type == 'phonebook') {
                $data['page_type'] = "8";
                $data['page'] = "phonebook";
                $this->load->view('api/header', $data);
                $this->load->view('api/phonebook');
                $this->load->view('api/footer');
            }
        } else {
            
          // redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//       
    // Virtual Numbers APIs
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Virtual Numbers
    function virtual_numbers($type = null) {
        if ($this->session->userdata('logged_in')) {
            $data['page_type'] = $type;
            $data['page'] = "virtual_numbers";
            $this->load->view('api/header', $data);
            $this->load->view('api/virtual-numbers');
            $this->load->view('api/footer');
        } else {
           
           //  redirect('home', 'refresh');
             header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Generate PDF
    function generate_pdf($name = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        //this data will be passed on to the view
        $data['the_content'] = 'mPDF and CodeIgniter are cool!';
        //load the view, pass the variable and do not show it but "save" the output into $html variable
        $html = $this->load->view('api/pdf_output', true);
        //this the the PDF filename that user will get to download
        $pdfFilePath = "$name.pdf";
        //load mPDF library
        $this->load->library('m_pdf');
        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        //generate the PDF!
        $pdf->WriteHTML($html);
        //offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "D");
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
}

?>
