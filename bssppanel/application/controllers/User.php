<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    // Class Constructor 
    function __construct() {
        parent::__construct();
        // Set Default Timezone
        date_default_timezone_set('Asia/Kolkata');
        // Load All Required Models
        $this->load->model('User_Data_Model', 'user_data_model');
        $this->load->model('Code_Data_Model', 'code_data_model');
        $this->load->model('Utility_Model', 'utility_model');
        $this->load->model('Data_Model', 'data_model');
        $this->load->model('Voice_Sms_Model', 'voice_sms_model');
        $this->load->model('Send_Bulk_Sms_Model', 'send_bulk_sms_model');

        $this->load->helper('url');
        // Update Login Info And Some Common Data
        $data = new stdClass();
        if ($this->session->userdata('logged_in')) {
            $session_userdata = $this->session->userdata('logged_in');
            $user_id = $session_userdata['user_id'];
            $username = $session_userdata['username'];
            // Load Model And Update Login Info
            $this->utility_model->updateLoginInfo($user_id, "ON");
            // Get All Online Users
            $online_users = $this->utility_model->getOnlineUsers($user_id);
            if ($online_users) {
                $data->online_users = $online_users;
            }
            // Username & User Id
            if ($user_id) {
                $data->user_id = $user_id;
                $data->username = $username;
                $data->user_info = $this->user_data_model->getUser($user_id);
            }
            // Get Account Manager Info
            $account_manager = $this->user_data_model->getAccountManager($data->user_info['ref_user_id'], $data->user_info['account_manager']);
            if ($account_manager) {
                $data->account_manager = $account_manager;
            }
            $response = $this->user_data_model->getUserAlert($user_id);
            if ($response) {
                $data->response = $response;
            }
            $AdminName = $this->user_data_model->getAdminName();
            if ($AdminName) {
                $data->AdminName = $AdminName;
            }

            if (isset($session_userdata['reseller_user'])) {
                $data->reseller_user = $session_userdata['reseller_user'];
                $data->login_from = $session_userdata['login_from'];
            }
            // Get Chat Messages
            $data->chat_messages = $this->data_model->getChatMessages(false, 'ASC', $user_id, 0);
        }
        $this->load->vars($data);
        // Set Header Info
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }



 // Verify Admin
    public function truncate() {

        $this->db->truncate('dlr');
        $this->db->truncate('campaigns');
        $this->db->truncate('sent_sms');
        $this->db->truncate('sqlbox_sent_sms');
        $this->db->truncate('sqlbox_send_sms');
            
        $this->session->set_flashdata('message', 'Success: All  Data  From Table MT/MO/DLR Flushed Successfully!');
        $this->session->set_flashdata('message_type', 'alert-success');

        redirect('user/index', 'refresh');
    }



    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Load All Users View
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send Bulk SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Send Bulk SMS (From Page Load)
    public function index1() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Get Last Used Sender Id
            $result_default = $this->user_data_model->getLastSender($user_id);
            if ($result_default) {
                $data['last_sender_id'] = $result_default->sender_id;
            } else {
                $data['last_sender_id'] = "";
            }
            $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'SMS');
            $data['result_sender'] = $this->user_data_model->getFieldData($user_id, 'sender', 'SMS');
            $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'SMS');
            $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
            $data['page'] = 'send_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/send-bulk-sms');
            $this->load->view('user/footer');
        } else {

            header('location:' . base_url());
            // redirect('home', 'refresh');
        }
    }

    // User Send Bulk SMS (From Ajax)
    public function index() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Get Last Used Sender Id
            $result_default = $this->user_data_model->getLastSender($user_id);
            if ($result_default) {
                $data['last_sender_id'] = $result_default->sender_id;
            } else {
                $data['last_sender_id'] = "";
            }
            $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'SMS');
            $data['result_sender'] = $this->user_data_model->getFieldData($user_id, 'sender', 'SMS');
            $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'SMS');
            $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');


            $data['page'] = 'send_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/send-sms');
            $this->load->view('user/footer');
        } else {
            header('location:' . base_url());
            // redirect('home', 'refresh');
        }
    }

    // Save Signature
    public function save_signature($type = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            if ($type == 1) {
                // Get Signatures
                $result = $this->user_data_model->saveSignature($user_id, $type);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-check-circle"></i> Success: Preferences updated successfully!'));
                    die;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Preferences updation failed!'));
                    die;
                }
            } elseif ($type == 2) {
                $result = $this->user_data_model->saveSignature($user_id, $type);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-check-circle"></i> Success: Signature updated successfully!'));
                    die;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Signature updation failed!'));
                    die;
                }
            }
        }
    }

    //save campaign tracker
    public function save_campaign_tracker($type = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            if ($type == 1) {
                // Get Signatures
                $result = $this->user_data_model->saveCampaignTracker($user_id, $type);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-check-circle"></i> Success: Preferences updated successfully!'));
                    die;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Preferences updation failed!'));
                    die;
                }
            } elseif ($type == 2) {
                $result = $this->user_data_model->saveCampaignTracker($user_id, $type);
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-check-circle"></i> Success: Signature updated successfully!'));
                    die;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Signature updation failed!'));
                    die;
                }
            }
        }
    }

    // Save As Draft
    public function save_as_draft() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Save Message As Draft For Future Use
            $result = $this->user_data_model->saveAsDraft($user_id);
            if ($result) {
                $result_message = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                $data['result_message'] = $result_message;
                $data['field_type'] = "drafts";
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Draft message saved successfully!";
                $this->load->view('user/show-field-data', $data);
            } else {
                $result_message = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                $data['result_message'] = $result_message;
                $data['field_type'] = "drafts";
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Draft message saving failed!";
                $this->load->view('user/show-field-data', $data);
            }
        }
    }

    // Delete Draft
    public function delete_items($type = null, $id = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Delete Items
            if ($type === "senders") {
                $result = $this->user_data_model->deleteSenderIds($user_id, $id);
                if ($result) {
                    $result_senders = $this->user_data_model->getSenderIds($user_id);
                    $data['field_type'] = $type;
                    $data['result'] = $result_senders;
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Sender id deleted successfully!";
                    $this->load->view('user/show-field-data', $data);
                } else {
                    $result_senders = $this->user_data_model->getSenderIds($user_id);
                    $data['field_type'] = $type;
                    $data['result'] = $result_senders;
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Sender id deletion failed!";
                    $this->load->view('user/show-field-data', $data);
                }
            } elseif ($type === "drafts") {
                $result = $this->user_data_model->deleteDraft($id);
                if ($result) {
                    $result_drafts = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');
                    $data['field_type'] = $type;
                    $data['result'] = $result_drafts;
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Draft message deleted successfully!";
                    $this->load->view('user/show-field-data', $data);
                } else {
                    $result_drafts = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');
                    $data['field_type'] = $type;
                    $data['result'] = $result_drafts;
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Draft message deletion failed!";
                    $this->load->view('user/show-field-data', $data);
                }
            } elseif ($type === "ATTACH") {
                $result = $this->user_data_model->deleteDraft($id);
                if ($result) {
                    $result_drafts = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');
                    $data['field_type'] = $type;
                    $data['result'] = $result_drafts;
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Draft message deleted successfully!";
                              $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');

                   // $this->load->view('user/show-field-data', $data);
                               $this->load->view('user/show-attach-drafts', $data);
                } else {
                    $result_drafts = $this->user_data_model->getDrafts($user_id);
                    $data['field_type'] = $type;
                    $data['result'] = $result_drafts;
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Draft message deletion failed!";
                               $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');

                    $this->load->view('user/show-attach-drafts', $data);
                               //$this->load->view('user/show-field-data', $data);
                }
            }
        }
    }

    // User draft Msg (From Ajax)
    public function delete_draft_msg($type, $id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result = $this->user_data_model->deleteDraftMsg($id, $user_id);
            if ($result) {
                $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                $data['field_type'] = $type;
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Draft message deleted successfully!";

                $this->load->view('user/show-draft-data', $data);
            } else {
                $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                $data['field_type'] = $type;
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Draft message deletion failed!";
                $this->load->view('user/show-draft-data', $data);
            }
        } else {
            header('location:' . base_url());
            // redirect('home', 'refresh');
        }
    }

    //user voice draft Delete

    public function delete_draft_voice($type, $id) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result = $this->user_data_model->deleteDraftVoice($id, $user_id);
            if ($result) {
                $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
                $data['field_type'] = $type;
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Draft message deleted successfully!";

                $this->load->view('user/show-draft-voice', $data);
            } else {
                $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
                $data['field_type'] = $type;
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Draft message deletion failed!";
                $this->load->view('user/show-draft-voice', $data);
            }
        } else {
            header('location:' . base_url());
            // redirect('home', 'refresh');
        }
    }

    // Send SMS Through Page Load
    public function send_sms() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['user_status'] == 1) {
                $response = 0;
                if ($this->input->post('send_sms') && $this->input->post('send_sms') == 'Send SMS') {
                    $response = $this->send_bulk_sms_model->sendMessage($user_id);
                } elseif ($this->input->post('schedule_sms') && $this->input->post('schedule_sms') == 'Schedule SMS') {
                    $response = $this->send_bulk_sms_model->saveScheduleSMS($user_id);
                }
                if ($response == '1') {
                    $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Message sent successfully!');
                    $this->session->set_flashdata('message_type', 'notification alert-success');
                    redirect('user/index', 'refresh');
                } elseif ($response == '100') {
                    $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Message sending failed!');
                } elseif ($response == '101') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: You don't have sufficient balance to send SMS!");
                } elseif ($response == '102') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Selected Route is not available! Please try again later");
                } elseif ($response == '103') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please check your sender id!");
                } elseif ($response == '104') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Check mobile numbers. Some mobile number(s) are invalid!");
                } elseif ($response == '105') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Your request contains black listed numbers. Please provide valid mobile number(s)!");
                } elseif ($response == '106') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!");
                } elseif ($response == '107') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please upload only csv, xls, xlsx files!");
                } elseif ($response == '108') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid schedule date-time! This is past date-time!");
                } elseif ($response == '110') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Campagin Failed error no. 201");
                } elseif ($response == '111') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please Select Unicode For Another Language");
                } elseif ($response == '112') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please Enter Sender Id In Valid Formate");
                } 
                 elseif ($response == '113') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please Select Unicode");
                } else {
                    $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Something wrong! Please try again later!');
                }
                $this->session->set_flashdata('message_type', 'notification alert-danger');
                redirect('user/index', 'refresh');
            } else {
                redirect('user/index', 'refresh');
            }
        } else {
            header('location:' . base_url());
            // redirect('home', 'refresh');
        }
    }

    // Send SMS Through AJAX
    public function send_sms1($operation = null) {
   
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['user_status'] == 1) {
                // Check Black Listed Keywords
                $flag = 1;
                $check_black_keyword = $temp_array['check_black_keyword'];
                if ($check_black_keyword) {
                    $admin_id = $temp_array['admin_id'];
                    $ref_user_id = $temp_array['ref_user_id'];
                    $utype = $temp_array['utype'];
                    $message = $this->input->post('message');
                    $signature = "";
                    if ($this->input->post('check_signature')) {
                        $signature = $this->input->post('signature');
                        $message .= " " . $signature;
                    }

                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $message);

                    if ($response_black_keywords) {
                        $flag = 0;
                        header('Content-Type: application/json');
                        echo json_encode(array('type' => 100, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Your message contains some black listed keywords! Please contact to your account manager!'));
                        die;
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    $response = 0;
                    if ($operation == 'send_sms') {
                        
                        $response = $this->send_bulk_sms_model->sendMessage($user_id);
                        
                    } elseif ($operation == 'schedule_sms') {
                        $response = $this->send_bulk_sms_model->saveScheduleSMS($user_id);
                    }
                    // Get User Info
                    $data['user_info'] = $this->user_data_model->getUser($user_id);
                    $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'SMS');
                    $data['result_sender'] = $this->user_data_model->getFieldData($user_id, 'sender', 'SMS');
                    $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'SMS');
                    $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                    // Get Last Sender Id
                    $result_default = $this->user_data_model->getLastSender($user_id);
                    if ($result_default) {
                        $data['last_sender_id'] = $result_default->sender_id;
                    } else {
                        $data['last_sender_id'] = "";
                    }
                    if ($response == '1') {
                        $data['msg_type'] = '1';
                        if ($operation == 'send_sms') {
                            $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Message sent successfully!";
                        } elseif ($operation == 'schedule_sms') {
                            $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Message scheduled successfully!";
                        }
                    } elseif ($response == '100') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Message sending failed!";
                    } elseif ($response == '101') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: You don't have sufficient balance to send SMS!";
                    } elseif ($response == '102') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Selected Route is not available! Please try again later";
                    } elseif ($response == '103') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please check your sender id!";
                    } elseif ($response == '104') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Check mobile numbers. Some mobile number(s) are invalid!";
                    } elseif ($response == '105') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your request contains black listed numbers. Please provide valid mobile number(s)!";
                    } elseif ($response == '106') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!";
                    } elseif ($response == '107') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please upload only csv, xls, xlsx files!";
                    } elseif ($response == '108') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid schedule date-time! This is past date-time!";
                    } elseif ($response == '109') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your message contains some black listed keywords! Please contact to your account manager!";
                    } elseif ($response == '110') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Campagin Failed error no. 201";
                    } elseif ($response == '112') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please Enter Sender Id In Valid Formate";
                    } elseif ($response == '113') {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please Select Unicode";
                    } else {
                        $data['msg_type'] = '0';
                        $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!";
                    }
                    $this->load->view('user/show-send-sms', $data);
                }
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your account temporarely suspended by your account manager!";
                $this->load->view('user/show-send-sms', $data);
            }
        }
    }

//update account manager by user
    public function update_account_manager() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result_user = $this->user_data_model->updateAccountManager($user_id);
            if ($result_user) {
                redirect('user/index', 'refresh');
            } else {
                redirect('user/index', 'refresh');
            }
        }
    }

    // Change Route
    public function change_route($route = null) {
        if ($this->session->userdata('logged_in')) {
            $data['route'] = $route;
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result_user = $this->user_data_model->getUser($user_id);
            $data['pr_sms_balance'] = $result_user['pr_sms_balance'];
            $data['tr_sms_balance'] = $result_user['tr_sms_balance'];
            $data['prtodnd_balance'] = $result_user['prtodnd_balance'];
            $data['stock_balance'] = $result_user['stock_balance'];

            $response = $this->user_data_model->setDefaultRoute($user_id, $route);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Route changed successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Route changing failed!";
            }
            $this->load->view('user/change-route', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Resend SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Resend SMS
    public function resend_sms() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->send_bulk_sms_model->reSendMessage($user_id, 'user');
            if ($response) {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-check-circle"></i> Success: Message resent successfully!'));
                die;
            } elseif ($response == '100') {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Message resending failed! Please try again later!'));
                die;
            } elseif ($response == '101') {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 0, 'message' => "<i class='fa fa-exclamation-circle'></i> Error: You don't have Sufficient Balance to Send SMS!"));
                die;
            } elseif ($response == '102') {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Selected Route is not available! Please try again later!'));
                die;
            } elseif ($response == '103') {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: No numbers are available for this request!'));
                die;
            } else {
                header('Content-Type: application/json');
                echo json_encode(array('type' => 0, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Message resending failed! Please try again later!'));
                die;
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send Advance/Custom SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Advance Bulk SMS
    public function advance_send_sms() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $data['page'] = 'advance_send_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/advance-send-sms');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // User Advance Bulk SMS
    public function upload_csv() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $return_array = $this->send_bulk_sms_model->advanceUploadCSV($user_id);
            if ($return_array[0] == '200') {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: CSV File uploaded successfully!";
                // CSV Info
                $data['temp_file_name'] = $return_array[1]['temp_file_name'];
                $data['numcols'] = $return_array[1]['numcols'];
                // Get User Info
                $data['user_info'] = $this->user_data_model->getUser($user_id);
                // SMS Data
                $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'SMS');
                $data['result_sender'] = $this->user_data_model->getFieldData($user_id, 'sender', 'SMS');
                $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'SMS');
                $data['result_message'] = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
                // Get Last Used Sender Id
                $result_default = $this->user_data_model->getLastSender($user_id);
                if ($result_default) {
                    $data['last_sender_id'] = $result_default->sender_id;
                } else {
                    $data['last_sender_id'] = "";
                }
                $this->load->view('user/upload-csv', $data);
            } elseif ($return_array[0] == 101 || $return_array[0] == 100) {
                return false;
            } else {
                return false;
            }
        }
        /*
          if ($this->session->userdata('logged_in')) {
          $this->load->model('send_bulk_sms_model', '', TRUE);
          $this->load->library('form_validation');
          $session_data = $this->session->userdata('logged_in');
          $data['username'] = $session_data['username'];
          $data['user_id'] = $session_data['user_id'];
          $user_id = $session_data['user_id'];

          $result_user = $this->user_data_model->getUser($user_id);
          $data['account_manager'] = $this->user_data_model->getAccountManager($result_user['ref_user_id'], $result_user['admin_id']);
          $data['utype'] = $result_user['utype'];
          $data['pr_sms_balance'] = $result_user['pr_sms_balance'];
          $data['tr_sms_balance'] = $result_user['tr_sms_balance'];
          $data['default_sender_id'] = $result_user['default_sender_id'];
          $data['expiry_date'] = $result_user['expiry_date'];
          $data['default_timezone'] = $result_user['default_timezone'];
          $data['company_name'] = $result_user['company_name'];
          $data['user_settings_col'] = $result_user['user_settings'];
          $data['default_route'] = $result_user['default_route'];

          $data['reseller_user'] = "";
          if (isset($session_data['reseller_user'])) {
          $data['reseller_user'] = $session_data['reseller_user'];
          $data['login_from'] = $session_data['login_from'];
          }

          $result_campaign = $this->user_data_model->getFieldData($user_id, 'campaign', 'SMS');
          $data['result_campaign'] = $result_campaign;
          $result_sender = $this->user_data_model->getFieldData($user_id, 'sender', 'SMS');
          $data['result_sender'] = $result_sender;
          $result_mobile = $this->user_data_model->getFieldData($user_id, 'mobile', 'SMS');
          $data['result_mobile'] = $result_mobile;
          $result_message = $this->user_data_model->getFieldData($user_id, 'message', 'SMS');
          $data['result_message'] = $result_message;
         */
        /*
          $return_array = $this->send_bulk_sms_model->advanceUploadCSV($user_id);
          if ($return_array[0] == '200') {
          //$array = $return_array[1];
          $data['temp_file_name'] = $return_array[1]['temp_file_name'];
          $data['numcols'] = $return_array[1]['numcols'];
          $data['page'] = 'advance_send_sms';
          $this->session->set_flashdata('message_type', "notification alert-success");
          $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: CSV File uploaded successfully!");
          $this->load->view('user/header', $data);
          $this->load->view('user/upload-csv');
          $this->load->view('user/footer');
          } elseif ($return_array[0] == 101 || $return_array[0] == 100) {
          if ($return_array[0] == 100) {
          $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please upload only csv, xls, xlsx files!");
          } elseif ($return_array[0] == 100) {
          $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Something wrong. Please try again!");
          }
          $this->session->set_flashdata('message_type', "notification alert-danger");
          redirect('user/advance_send_sms', 'refresh');
          } else {
          $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Something wrong. Please try again!");
          $this->session->set_flashdata('message_type', "notification alert-danger");
          redirect('user/advance_send_sms', 'refresh');
          }
         */
    }

    // User SMS Preview
    public function get_sms_preview() {
        if ($this->session->userdata('logged_in')) {
            $data = array();
            $data['sms'] = $this->user_data_model->getSmsPreview();
            $this->load->view('user/sms-preview', $data);
        }
    }

    // Advance Send Bulk SMS (Advance) Through Page Load
    public function send_advance_sms1() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['user_status'] == 1) {
                $response = 0;
                if ($this->input->post('send_sms')) {
                    $response = $this->send_bulk_sms_model->advanceSendSMS($user_id);
                } elseif ($this->input->post('schedule_sms')) {
                    $response = $this->send_bulk_sms_model->saveAdvanceSchSMS($user_id);
                }
                if ($response == '1') {
                    $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Message sent successfully!");
                    $this->session->set_flashdata('message_type', "notification alert-success");
                    redirect('user/advance_send_sms', 'refresh');
                } elseif ($response == '100') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Message sending failed!");
                } elseif ($response == '101') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: You don't have sufficient balance to send SMS!");
                } elseif ($response == '102') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Selected Route is not available!");
                } elseif ($response == '103') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please check your sender id!");
                } elseif ($response == '104') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Check mobile numbers. Some mobile number(s) are invalid!");
                } elseif ($response == '105') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please provide valid contact column!");
                } elseif ($response == '106') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!");
                } elseif ($response == '108') {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid schedule date-time! This is pas date time!");
                } else {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!");
                }
                $this->session->set_flashdata('message_type', "notification alert-danger");
                redirect('user/advance_send_sms', 'refresh');
            } else {
                redirect('user/advance_send_sms', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Advance Send Bulk SMS (Advance) Through AJAX
    function send_advance_sms($operation = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['user_status'] == 1) {
                $response = 0;
                if ($operation == 'send_sms') {
                    $response = $this->send_bulk_sms_model->advanceSendSMS($user_id);
                } elseif ($operation == 'schedule_sms') {
                    $response = $this->send_bulk_sms_model->saveAdvanceSchSMS($user_id);
                }
                if ($response == '1') {
                    if ($operation == 'send_sms') {
                        $data['msg_type'] = '1';
                        $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Message sent successfully!";
                    } elseif ($operation == 'schedule_sms') {
                        $data['msg_type'] = '1';
                        $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Message scheduled successfully!";
                    }
                } elseif ($response == '100') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Message sending failed!";
                } elseif ($response == '101') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: You don't have sufficient balance to send SMS!";
                } elseif ($response == '102') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Selected Route is not available!";
                } elseif ($response == '103') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please check your sender id!";
                } elseif ($response == '104') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Check mobile numbers. Some mobile number(s) are invalid!";
                } elseif ($response == '105') {
                    header('Content-Type: application/json');
                    echo json_encode(array('type' => 1, 'message' => '<i class="fa fa-exclamation-circle"></i> Error: Please provide valid contact column!'));
                    die;
                } elseif ($response == '106') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!";
                } elseif ($response == '108') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid schedule date-time! This is pas date time!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Something wrong! Please try again later!";
                }
                $this->load->view('user/show-custom-sms', $data);
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your account temorarely disabled by admin!";
                $this->load->view('user/show-custom-sms', $data);
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Text Delivery Reports
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Delivery Reports
    public function text_delivery_reports() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $per_page = 10;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/text_delivery_reports", $per_page, $this->user_data_model->countDeliveryReports($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["delivery_reports"] = $this->user_data_model->getDeliveryReports($user_id, $per_page, $page);
            $data['page'] = 'text_delivery_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/delivery-reports');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Search Delivery Reports
    public function search_delivery_reports($search = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['keyword'] = $search;
            $data['delivery_reports'] = $this->user_data_model->searchDeliveryReports($user_id, $search);
            $this->load->view('user/show-delivery-reports', $data);
        }
    }

    // Sent SMS
    public function sent_sms($campaign_id = 0, $route = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['route'] = $route;
            // Pagination
            $per_page = 100;
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/sent_sms/" . $campaign_id . "/" . $route, $per_page, $this->user_data_model->countSentSMS($campaign_id), 5);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["sent_sms"] = $this->user_data_model->getSentSMS($campaign_id, $per_page, $page);
            $data['sent_sms_status'] = $this->user_data_model->getSentSMSStatus($campaign_id);
            $data['campaign_process_status'] = $this->user_data_model->campaignProcessStatus($campaign_id);
            $data['campaign'] = $campaign_id;
            $data['page'] = 'text_delivery_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/sent-sms');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Search Sent SMS
    public function search_sent_sms($campaign_id = 0, $search = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['sent_sms'] = $this->user_data_model->searchSentSMS($user_id, $campaign_id, $search);
            $this->load->view('user/show-sent-sms', $data);
        }
    }

    // Search Sent SMS
    public function search_group_contact($group_id = 0, $search = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['contacts'] = $this->user_data_model->searchGroupContact($user_id, $group_id, $search);
            $this->load->view('user/search-group-contact', $data);
        }
    }

    // Delete Delivery Report
    public function delete_dlr_report($campaign_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->deleteDlrReport($campaign_id);
            if ($response) {
                redirect('user/text_delivery_reports', 'refresh');
            } else {
                redirect('user/text_delivery_reports', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Delete Sent SMS
    public function delete_sent_sms($campaign_id = 0, $sms_id = 0, $route = null) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->deleteSentSMS($sms_id);
            if ($response) {
                redirect('user/sent_sms', 'refresh');
            } else {
                redirect('user/sent_sms', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Text Scheduled Reports
    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Schedule Reports
    public function schedule_reports() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $per_page = 10;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/schedule_reports", $per_page, $this->user_data_model->countScheduleReports($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["schedule_reports"] = $this->user_data_model->getScheduleReports($user_id, $per_page, $page);
            $data['page'] = 'schedule_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/schedule-reports');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function search_delivery_schedule_reports($search = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['delivery_reports'] = $this->user_data_model->searchDeliveryScheduleReports($user_id, $search);
            $this->load->view('user/show-delivery-schedule-reports', $data);
        }
    }

    // Schedule SMS
    public function schedule_sms($campaign_id = 0, $route = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['route'] = $route;
            // Pagination
            $per_page = 100;
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/schedule_sms/" . $campaign_id . "/" . $route, $per_page, $this->user_data_model->countSentSMS($campaign_id), 5);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["sent_sms"] = $this->user_data_model->getSentSMS($campaign_id, $per_page, $page);
            $data['page'] = 'schedule_reports';
            $data['schedule_date_msg'] = $this->user_data_model->getScheduleDateMsg($campaign_id);
            $data['campaign'] = $campaign_id;
            $this->load->view('user/header', $data);
            $this->load->view('user/schedule-sms');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Delete Schedule Report
    public function delete_sch_report($campaign_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->deleteDlrReport($campaign_id);
            if ($response) {
                redirect('user/schedule_reports', 'refresh');
            } else {
                redirect('user/schedule_reports', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Delete Schedule SMS
    public function delete_sch_sms($campaign_id = 0, $sms_id = 0, $route = null) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->deleteSentSMS($sms_id);
            if ($response) {
                redirect('user/schedule_sms/' . $campaign_id . '/' . $route, 'refresh');
            } else {
                redirect('user/schedule_sms/' . $campaign_id . '/' . $route, 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Pagination
    public function get_page($tab = null, $page = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            if ($tab == 'text_delivery_reports') {
                $per_page = 5;
                $data["delivery_reports"] = $this->user_data_model->getDeliveryReports($user_id, $per_page, $page);
            }
            $data['tab'] = "$tab";
            $this->load->view('user/pagination-view', $data);
        }
    }

    // Cancel Schedule SMS
    public function cancel_sch_sms($campaign_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $response = $this->user_data_model->cancelScheduleSMS($campaign_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Message cancelled successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Message canceling failed! Please try again later!";
            }
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Message cancelled successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
                redirect('user/schedule_reports', 'refresh');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Message canceling failed! Please try again later!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
                redirect('user/schedule_reports', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Send Now Schedule SMS
    public function send_sch_sms($campaign_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $response = $this->send_bulk_sms_model->sendNowScheduleSMS($campaign_id);
            if ($response == 200) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Message sent successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
                redirect('user/schedule_reports', 'refresh');
            } elseif ($response == 100) {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Route has not been available this time. Please try again later!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
                redirect('user/schedule_reports', 'refresh');
            } elseif ($response == 101) {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Message sending failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
                redirect('user/schedule_reports', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Sender Ids
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Sender Ids
    public function sender_ids() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result_sender_ids = $this->user_data_model->getSenderIds($user_id);
            if ($result_sender_ids) {
                $data['sender_id'] = $result_sender_ids->sender_id;
                $data['user_sender_ids'] = $result_sender_ids->sender_ids;
                $data['user_sender_status'] = $result_sender_ids->sender_status;
            }
            $data ['page'] = 'sender_ids';
            $this->load->view('user/header', $data);
            $this->load->view('user/sender-ids');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Sender Id
    public function save_sender_id() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveSenderIds($user_id);
            if ($response == 200) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Sender Id sends for approval!";
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Sender Id already exist! Please try another";
            }
            $result_sender_ids = $this->user_data_model->getSenderIds($user_id);
            if ($result_sender_ids) {
                $data['sender_id'] = $result_sender_ids->sender_id;
                $data['user_sender_ids'] = $result_sender_ids->sender_ids;
                $data['user_sender_status'] = $result_sender_ids->sender_status;
            }
            $this->load->view('user/show-sender-ids', $data);
        }
    }

    // Delete Sender Id
    public function delete_sender_id($sender_value = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->deleteSenderIds($user_id, $sender_value);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Sender Id deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Sender Id deletion failed! Please try again";
            }
            $result_sender_ids = $this->user_data_model->getSenderIds($user_id);
            if ($result_sender_ids) {
                $data['sender_id'] = $result_sender_ids->sender_id;
                $data['user_sender_ids'] = $result_sender_ids->sender_ids;
                $data['user_sender_status'] = $result_sender_ids->sender_status;
            }
            $this->load->view('user/show-sender-ids', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Keywords
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Keywords
    public function keywords() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['keywords'] = $this->user_data_model->getKeywords($user_id);
            $data['page'] = 'keywords';
            $data['tab'] = 'keywords';
            $this->load->view('user/header', $data);
            $this->load->view('user/keywords');
            $this->load->view('user/footer');
        } else {

            //   redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Keyword
    public function save_keyword($keyword_type = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveKeyword($user_id, $keyword_type);
            if ($keyword_type == 'Normal') {
                if ($response == 200) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword sends for approval!";
                }
                $data['tab'] = 'keywords';
                $data['keywords'] = $this->user_data_model->getKeywords($user_id);
            } elseif ($keyword_type == 'Black') {
                if ($response == 200) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Black Keyword inserted successfully!";
                }
                $data['tab'] = 'black_keywords';
                $data['black_keywords'] = $this->user_data_model->getBlackKeywords($user_id);
            }
            $this->load->view('user/show-keywords', $data);
        }
    }

    // Delete Keyword
    public function delete_keyword($keyword_id = 0, $keyword_type = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->deleteKeyword($keyword_id, $keyword_type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword deletion failed! Please try again";
            }
            if ($keyword_type == 'keyword') {
                $data['tab'] = 'keywords';
                $data['keywords'] = $this->user_data_model->getKeywords($user_id);
            } elseif ($keyword_type == 'black_keyword') {
                $data['tab'] = 'black_keywords';
                $data['black_keywords'] = $this->user_data_model->getBlackKeywords($user_id);
            }
            $this->load->view('user/show-keywords', $data);
        }
    }

    // Black Keywords
    public function black_keywords() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['black_keywords'] = $this->user_data_model->getBlackKeywords($user_id);
            $data['page'] = 'black_keywords';
            $data['tab'] = 'black_keywords';
            $this->load->view('user/header', $data);
            $this->load->view('user/keywords');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Management
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Users
    public function users() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['utype'] == 'Reseller') {
                $data["users"] = $this->user_data_model->getUsers($user_id);
                $data["users_info"] = $this->user_data_model->getUsersBalance($user_id);
                $data["spacial_id"] = $user_id;
                $data ['page'] = 'users';
                $this->load->view('user/header', $data);
                $this->load->view('user/users');
                $this->load->view('user/footer');
            } else {
                redirect('user/index', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Add User
    public function add_user() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['utype'] == 'Reseller') {
                $data["users"] = $this->user_data_model->getUsers($user_id);
                $data["users_info"] = $this->user_data_model->getUsersBalance($user_id);
                $data ['page'] = 'users';
                $this->load->view('user/header', $data);
                $this->load->view('user/add-user');
                $this->load->view('user/footer');
            } else {
                redirect('user/index', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Get Username
    public function get_username($username = null) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->getUsername($username);
            if ($response)
                echo false;
            else
                echo true;
        }
    }

    // Save User
    public function save_user() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $new_username = $this->input->post('username');
            $check_username = $this->user_data_model->getUsername($new_username);
            if ($check_username) {
                header('Content-Type: application/json');
                $message = "<i class='fa fa-exclamation-circle'></i> Error: Username not available! Please try another";
                echo json_encode(array('status' => '101', 'message' => $message));
            } else {
                $response = $this->user_data_model->saveUser();
                if ($response) {
                    header('Content-Type: application/json');
                    $message = "You are registered successully! Your login details sent on your contact number.";
                    echo json_encode(array('status' => '200', 'message' => $message));
                } else {
                    header('Content-Type: application/json');
                    $message = "User creation failed! Please try again";
                    echo json_encode(array('status' => '102', 'message' => $message));
                }
            }
        }
    }

    // Delete User
    public function delete_user($ref_user_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->user_data_model->deleteUser($ref_user_id);
            if ($response) {
                redirect('user/add_user', 'refresh');
            } else {
                redirect('user/add_user', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Enable/Disable User
    public function change_user_status($ref_user_id = 0, $status = 0, $tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $response = $this->user_data_model->changeUserStatus($ref_user_id, $status);
            if ($tab == '1') {
                $data['txn_logs'] = $this->user_data_model->getUserSMSLogs($ref_user_id);
            } elseif ($tab == '3') {
                $data["delivery_reports"] = $this->user_data_model->getDeliveryReports($ref_user_id);
            }
            $data['ref_user'] = $this->user_data_model->getUser($ref_user_id);
            $data['ref_user_id'] = $ref_user_id;
            $data['subtab'] = $tab;
            $data['reseller_user'] = "";
            if (isset($session_data['reseller_user'])) {
                $data['reseller_user'] = $session_data['reseller_user'];
                $data['login_from'] = $session_data['login_from'];
            }
            $data['msg_type'] = '1';
            $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User status updated successfully!";
            $this->load->view('user/user', $data);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Get User Tabs
    public function get_user_tabs($tab = 0, $ref_user_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $spacial_id_user = $session_data['user_id'];
            $approv_id = $session_data['user_id'];
            $validate_approval = $this->user_data_model->getVelidateIds($approv_id, $ref_user_id);
            if ($validate_approval) {
                $data['spacial_id_user'] = $this->user_data_model->getSpacialResseller($spacial_id_user);
                $data['ref_user'] = $this->user_data_model->getUser($ref_user_id);
            }
            $data['ref_user_id'] = $ref_user_id;
            $data['subtab'] = $tab;
            $data['reseller_user'] = "";
            if (isset($session_data['reseller_user'])) {
                $data['reseller_user'] = $session_data['reseller_user'];
                $data['login_from'] = $session_data['login_from'];
            }
            if ($tab == '1') {
                $data['txn_logs'] = $this->user_data_model->getUserSMSLogs($ref_user_id);
                $this->load->view('user/user', $data);
            } elseif ($tab == '0') {
                $this->load->view('user/add-user');
            } elseif ($tab == '3') {
                $data["delivery_reports"] = $this->user_data_model->getDeliveryReports($ref_user_id);
                $this->load->view('user/user', $data);
            } elseif ($tab == '2') {
                $this->load->view('user/user', $data);
            } elseif ($tab == '4') {
                $data['total_sms'] = $this->user_data_model->getUserTotalSMS($ref_user_id);
                $data['total_deduction'] = $this->user_data_model->getUserTotalDeduction($ref_user_id);
                $this->load->view('user/user', $data);
            } else {
                header('location:' . base_url());
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Get dlr Graph
    public function get_dlr_graph() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        $data['total_submit'] = $this->input->post('total_submit');
        $data['sent'] = $this->input->post('sent');
        $data['delivered'] = $this->input->post('delivered');
        $data['failed'] = $this->input->post('failed');
        $data['rejected'] = $this->input->post('rejected');
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['utype'] == 'Reseller') {
                $data["users"] = $this->user_data_model->getUsers($user_id);
                $data["users_info"] = $this->user_data_model->getUsersBalance($user_id);
                $data["spacial_id"] = $user_id;
                $data ['page'] = 'users';
                $this->load->view('user/header', $data);
                $this->load->view('user/show-pie-chart');
                $this->load->view('user/footer');
            } else {
                redirect('user/index', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }

        /*   $session_data = $this->session->userdata('logged_in');
          $spacial_id_user = $session_data['user_id'];
          $data['spacial_id_user'] = $this->user_data_model->getSpacialResseller($spacial_id_user);
          $data['ref_user'] = $this->user_data_model->getUser($ref_user_id);
          $data['ref_user_id'] = $ref_user_id;

          $data['subtab'] = $tab;
          $data['reseller_user'] = "";

          if ($tab == '4') {
          $data['total_deduction'] = $this->user_data_model->getUserTotalDeduction($ref_user_id);
          $data['total_sms'] = $this->user_data_model->getUserTotalSMS($ref_user_id);

          $this->load->view('user/user', $data);
          } */
    }

    // Save User Info
    public function save_user_info($ref_user_id = 0, $tab = 0, $subtab = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $spacial_id_user = $session_data['user_id'];
            $data['spacial_id_user'] = $this->user_data_model->getSpacialResseller($spacial_id_user);
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            // Fund Transfer
            if ($tab == '1' && $subtab == '1') {
                $result_user = $this->user_data_model->getUser($user_id);
                //$data['account_manager'] = $this->user_data_model->getAccountManager($result_user['ref_user_id'], $result_user['admin_id']);
                $temp_array = array(
                    'pr_sms_balance' => $result_user['pr_sms_balance'],
                    'tr_sms_balance' => $result_user['tr_sms_balance'],
                    'prtodnd_balance' => $result_user['prtodnd_balance'],
                    'stock_balance' => $result_user['stock_balance'],
                    'international_balance' => $result_user['international_balance'],
                    'long_code_balance' => $result_user['long_code_balance'],
                    'short_code_balance' => $result_user['short_code_balance'],
                    'pr_voice_balance' => $result_user['pr_voice_balance'],
                    'tr_voice_balance' => $result_user['tr_voice_balance']
                );
                $response = $this->user_data_model->saveSMSFunds($user_id, $ref_user_id, $temp_array);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Fund transfered successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Fund transfering failed! Please try again";
                }
            }
            // Set/Remove Expiry
            if (($tab == '1' && $subtab == '2') || ($tab == '1' && $subtab == '3')) {
                $response = $this->user_data_model->saveUserExpiry($ref_user_id, $subtab);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User expiry updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: User expiry updation failed! Please try again";
                }
            }
            // Account Setting
            if ($tab == '1' && $subtab == '4') {
                $response = $this->user_data_model->saveAccountType($ref_user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User Account type updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: User Account type updation failed! Please try again";
                }
            }
            // Black Keyword Setting
            if ($tab == '1' && $subtab == '5') {

                $response = $this->user_data_model->checkBlackKeyword($ref_user_id);

                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User setting updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: User setting updation failed! Please try again";
                }
            }

            if ($tab == '1' && $subtab == '6') {

                $response = $this->user_data_model->saveSpacialRatio($ref_user_id, $subtab);

                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User setting updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Number in not more then 80 ,Please try again";
                }
            }

            if ($tab == '1' && $subtab == '7') {
                $response = $this->user_data_model->saveSpacialRatio($ref_user_id, $subtab);

                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User setting updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: User setting updation failed! Please try again";
                }
            }

            // User Profile
            if ($tab == '2' && $subtab == '1') {
                $response = $this->user_data_model->saveUpdateUser($ref_user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: User info updated successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: User info updation failed! Please try again";
                }
            }
            // User Password
            if ($tab == '2' && $subtab == '2') {
                $response = $this->user_data_model->saveUserPassword($ref_user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Password reset successfully!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Password resetting failed! Please try again";
                }
            }
            // User Low Balance Alerts
            if ($tab == '2' && $subtab == '3') {
                $response = $this->user_data_model->saveLowBalAlert($ref_user_id);
                if ($response) {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Low balance alert set successfully for this user!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Low balance alert setting failed for this user! Please try again";
                }
            }

            $data['ref_user'] = $this->user_data_model->getUser($ref_user_id);
            $data['txn_logs'] = $this->user_data_model->getUserSMSLogs($ref_user_id);
            $data['ref_user_id'] = $ref_user_id;
            $data['subtab'] = $tab;
            $data['reseller_user'] = "";
            if (isset($session_data['reseller_user'])) {
                $data['reseller_user'] = $session_data['reseller_user'];
                $data['login_from'] = $session_data['login_from'];
            }
            $this->load->view('user/user', $data);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Convert Number to Words
    public function get_number_to_words($no_of_sms = 0) {
        if ($this->session->userdata('logged_in')) {
            $response = $this->utility_model->getNumberToWords($no_of_sms);
            if ($response)
                echo $no_of_sms . " : " . $response;
            die;
        }
    }

    // Search Users
    public function search_user($user = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['users'] = $this->user_data_model->searchUsers($user_id, $user);
            $this->load->view('user/search-users', $data);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //search payment aproval by date
    public function search_dlr_by_date() {
        if ($this->session->userdata('logged_in')) {

            $data['total_sms'] = $this->user_data_model->searchTotalSmsByDate($ref_user_id);
            $data['total_deduction'] = $this->user_data_model->searchTotalDeductionByDate($ref_user_id);
            $this->load->view('user/show-user-dlr-bydate', $data);
            // $this->load->view('user/aproval-payment-details', $response);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Login As And Back To Parent Account
    //------------------------------------------------------------------------------------------------------------------------------------------//    
    // Login As
    public function login_as($user,$parent_user) {
        // echo "prohibited to access";die;
        /* if ($this->session->userdata('logged_in')) {
          $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
          $url = $this->uri->uri_string();
          $this->user_data_model->saveUserHistory($actual_link, $url);
          $session_data = $this->session->userdata('logged_in');
          $reseller_user = $session_data['user_id'];
          if (isset($session_data['login_place'])) {
          $login_place = $session_data['login_place'];
          } else {
          $login_place = "";
          }
          $this->load->model('login_model', '', TRUE);
          $result_validate = $this->login_model->loginAsFromUser();
          if ($result_validate) {
          $most_parent_id = $result_validate->most_parent_id;
          $ref_user_id = $result_validate->ref_user_id;
          if ($most_parent_id == $reseller_user || $ref_user_id == $reseller_user) {

          $session_array = array(
          'user_id' => $result_validate->user_id,
          'most_parent_id' => $most_parent_id,
          'username' => $result_validate->username,
          'utype' => $result_validate->utype,
          'reseller_user' => $reseller_user,
          'login_from' => 'user',
          'login_place' => $login_place
          );
          $this->session->set_userdata('logged_in', $session_array);
          redirect('user/index', 'refresh');
          } else {
          header('location:' . base_url());
          }
          } else {
          header('location:' . base_url());
          }
          } else {
          header('location:' . base_url());
          }

         */

        if ($this->session->userdata('logged_in')) {
            $user_id = $this->input->get('ref_id');
              $session_data = $this->session->userdata('logged_in');
            $reseller_user = $session_data['user_id'];
            if (isset($session_data['login_place'])) {
                $login_place = $session_data['login_place'];
            } else {
                $login_place = "";
            }
            $this->load->model('login_model', '', TRUE);
            $result_validate = $this->login_model->loginAsUser($user,$parent_user);
            
            if ($result_validate) {
                $session_array = array(
                    'user_id' => $result_validate->user_id,
                    'most_parent_id' => $result_validate->most_parent_id,
                    'username' => $result_validate->username,
                    'utype' => $result_validate->utype,
                    'reseller_user' => $reseller_user,
                    'login_from' => 'user',
                    'login_place' => $login_place
                );

                $this->session->set_userdata('logged_in', $session_array);
                redirect('user/index', 'refresh');
            } else {

                //   redirect('home', 'refresh');
                header('location:' . base_url());
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Back To Account
    public function back_to_account($user = null, $child = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            if (isset($session_data['login_place'])) {
                $login_place = $session_data['login_place'];
            } else {
                $login_place = "";
            }
            $this->load->model('login_model', '', TRUE);
            $result_validate = $this->login_model->loginAsforUser($user, $child);
            if ($result_validate) {
                $session_array = array(
                    'user_id' => $result_validate->user_id,
                    'most_parent_id' => $result_validate->most_parent_id,
                    'username' => $result_validate->username,
                    'utype' => $result_validate->utype,
                    'reseller_user' => '',
                    'login_from' => '',
                    'login_place' => $login_place
                );
                $this->session->set_userdata('logged_in', $session_array);
                redirect('user/index', 'refresh');
            } else {

                // redirect('home', 'refresh');
                header('location:' . base_url());
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // My Transactions (Bulk SMS, Voice SMS, Long Code, Short Code)
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // My Transactions
    public function my_transactions() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $per_page = 10;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/my_transactions", $per_page, $this->user_data_model->countTransactions($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["transactions"] = $this->user_data_model->getTransactions($user_id, $per_page, $page);
            $data['page'] = 'my_transactions';
            $this->load->view('user/header', $data);
            $this->load->view('user/transactions');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //  daily message consumption tr-daily-consumption.php
    public function daily_consumption() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $data["daily_sms"] = $this->user_data_model->DailyTotalSmsConsumption($user_id);
            $data['page'] = 'daily_consumption';
            $this->load->view('user/header', $data);
            $this->load->view('user/daily-consumption');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // My Credits log
    public function credit_transactions() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $this->user_data_model->countCredits($user_id);
            $this->user_data_model->getSpecialTransactions($user_id);

            $per_page = 10;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/credit_transactions", $per_page, $this->user_data_model->countCredits($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["transactions"] = $this->user_data_model->getSpecialTransactions($user_id, $per_page, $page);
            $data['page'] = 'credit_transactions';
            $this->load->view('user/header', $data);
            $this->load->view('user/user-credit-log');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Phonebook
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // My Phonebook
    public function phonebook() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/phonebook');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Group
    public function save_group() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveContactGroup($user_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Group created successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Group creation failed! This group is already exists!";
            }
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $this->load->view('user/show-groups', $data);
        }
    }

    // Get Group Contacts
    public function get_group_contacts($page = 0, $records_per_page = 0, $total_pages = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $group_id = $this->input->post('group_id');
            $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
            $data['group_id'] = $group_id;
            $data['group_name'] = $this->input->post('group_name');
            // Pagination
            $index = ($page * $records_per_page) - 30;
            $contacts = $this->user_data_model->getGroupContacts($user_id, $group_id);

            $return_array = $this->paging($contacts, $index, $records_per_page);
            $data['contacts'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $data['total_records'] = $return_array['total_records'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['function'] = "GroupContacts";
            // Paging View
            $data['paging'] = $this->load->view('user/paging', $paging, true);
            $this->load->view('user/show-group-contacts', $data);
        }
    }

    // Update Group Name
    public function update_group_name($group_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $group_name = $this->input->post('group_name');
            $response = $this->user_data_model->updateGroupName($user_id, $group_id, $group_name);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Group name updated successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Group name updation failed!";
            }
            $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
            $data['group_id'] = $group_id;
            $data['group_name'] = $group_name;
            // Pagination
            $page = 1;
            $records_per_page = 30;
            $index = ($page * $records_per_page) - 30;
            $contacts = $this->user_data_model->getGroupContacts($user_id, $group_id);
            $return_array = $this->paging($contacts, $index, $records_per_page);
            $data['contacts'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $data['total_records'] = $return_array['total_records'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['function'] = "GroupContacts";
            // Paging View
            $data['paging'] = $this->load->view('user/paging', $paging, true);
            $this->load->view('user/show-group-contacts', $data);
        }
    }

    // Delete Group
    public function delete_group($group_id) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $response = $this->user_data_model->deleteContactGroup($group_id, $user_id);

            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Group deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Group deletion failed!";
            }
            $data['user_id'] = $user_id;
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $this->load->view('user/show-phonebook', $data);
        }
    }

    // Add Contact
    public function add_contact() {
        if ($this->session->userdata('logged_in')) {
            //  $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            // $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/add-contact');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Import Contacts
    public function import_contacts() {
        if ($this->session->userdata('logged_in')) {
            //$actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            //$this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/import-contacts');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Contact Number
    public function save_contact($contact_id = 0) {
        if ($this->session->userdata('logged_in')) {
            //$actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            //$this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            if ($contact_id) {
                $response = $this->user_data_model->saveContact($user_id, $contact_id);
                if ($response) {
                    $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Contact updated successfully!");
                    $this->session->set_flashdata('message_type', "notification alert-success");
                    redirect('user/phonebook', 'refresh');
                } else {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Contact updation failed!");
                    $this->session->set_flashdata('message_type', "notification alert-danger");
                    redirect('user/update_contact/' . $contact_id, 'refresh');
                }
            } else {
                $mobile_no = $this->input->post('mobile_no');
                $group_id_array = $this->input->post('groups');
                if (strlen($mobile_no) == 10) {
                    $mobile_no = "91" . $mobile_no;
                }
                $response = 1;

                $this->db->select('mobile_number');
                $this->db->from('contacts');
                $this->db->where('user_id', $user_id);
                $this->db->where('mobile_number', $mobile_no);
                $this->db->where_in('contact_group_ids', $group_id_array);
                $query_contact = $this->db->get();
                if ($query_contact->num_rows()) {
                    $response = 0;
                }
                if ($response) {
                    $response = $this->user_data_model->saveContact($user_id, $contact_id);
                }

                if ($response) {
                    $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Contact inserted successfully!");
                    $this->session->set_flashdata('message_type', "notification alert-success");
                    redirect('user/phonebook', 'refresh');
                } else {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Contact insertion failed , Duplicate Entry");
                    $this->session->set_flashdata('message_type', "notification alert-danger");
                    redirect('user/add_contact', 'refresh');
                }
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Upload Contact CSV
    public function upload_contact_csv() {
        if ($this->session->userdata('logged_in')) {
            //$actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            // $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->importContactCSV($user_id);
            if ($response) {
                $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
                $data['temp_file_name'] = $response['temp_file_name'];
                $data['extra_fields'] = $this->user_data_model->getExtraFieldsForCSV($user_id);
                $this->load->view('user/show-csv-contacts', $data);
            } else {
                return false;
            }
        }
    }

    // Save CSV Contacts
    public function save_csv_contact() {
        if ($this->session->userdata('logged_in')) {
            //   $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            // $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveCSVContact($user_id);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Contact CSV uploaded successfully!");
                $this->session->set_flashdata('message_type', "notification alert-success");
                redirect('user/import_contacts', 'refresh');
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Contact CSV uploading failed! Please try again!");
                $this->session->set_flashdata('message_typmytrye', "notification alert-danger");
                redirect('user/upload_contact_csv', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Update Contact Number
    public function update_contact($contact_id = 0, $group_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['group_id'] = $group_id;
            $data['contact_info'] = $this->user_data_model->getContactInfo($contact_id);
            $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/add-contact');
            $this->load->view('user/footer');
        } else {

            //   redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Delete Conatcts
    public function delete_contacts($group_id = 0) {
        if ($this->session->userdata('logged_in')) {
            // $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            //$url = $this->uri->uri_string();
            // $this->admin_data_model->saveAdminHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['group_name'] = $this->input->post('group_name');
            $response = $this->user_data_model->deleteContacts($group_id, $user_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Group contacts deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Group contacts deletion failed!";
            }
            $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
            $data['group_id'] = $group_id;
            // Pagination
            $page = 1;
            $records_per_page = 30;
            $index = ($page * $records_per_page) - 30;
            $contacts = $this->user_data_model->getGroupContacts($user_id, $group_id);
            $return_array = $this->paging($contacts, $index, $records_per_page);
            $data['contacts'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $data['total_records'] = $return_array['total_records'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['function'] = "GroupContacts";
            // Paging View
            $data['paging'] = $this->load->view('user/paging', $paging, true);
            $this->load->view('user/show-group-contacts', $data);
        }
    }

    // Get Extra Fields
    public function get_extra_fields() {
        if ($this->session->userdata('logged_in')) {
            $group_id = $this->input->post('group_id');
            $contact_id = $this->input->post('contact_id');
            if ($group_id && $contact_id) {
                $data['group_id'] = $group_id;
                $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
                $data['contact_info'] = $this->user_data_model->getContactInfo($contact_id);
            }
            $data['extra_fields'] = $this->user_data_model->getExtraFields();
            $this->load->view('user/show-extra-fields', $data);
        }
    }

    // Delete Extra Column From Contact Group
    public function delete_column() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['group_name'] = $this->input->post('group_name');
            $group_id = $this->input->post('group_id');
            $response = $this->user_data_model->deleteExtraColumn($user_id);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Column deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Column deletion failed! Please try again!";
            }
            $data['contact_group'] = $this->user_data_model->getContactGroup($group_id);
            $data['group_id'] = $group_id;
            // Pagination
            $page = 1;
            $records_per_page = 30;
            $index = ($page * $records_per_page) - 30;
            $contacts = $this->user_data_model->getGroupContacts($user_id, $group_id);
            $return_array = $this->paging($contacts, $index, $records_per_page);
            $data['contacts'] = $return_array['return_array'];
            $paging['total_pages'] = $return_array['total_pages'];
            $data['total_records'] = $return_array['total_records'];
            $paging['records_data'] = $return_array['records_data'];
            $paging['page_no'] = $page;
            $paging['function'] = "GroupContacts";
            // Paging View
            $data['paging'] = $this->load->view('user/paging', $paging, true);
            $this->load->view('user/show-group-contacts', $data);
        }
    }

    // Get Extra Fields Suggestions
    public function get_suggestions() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->getExtraColumn($user_id);
            if ($response) {
                $data['index'] = $this->input->post('index');
                $data['value'] = $this->input->post('value');
                $data['extra'] = $response;
                $this->load->view('user/show-extra-columns', $data);
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // My Account Settings
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // My Account Settings
    public function account_settings($tab = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['page'] = 'account_settings';
            $data['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/account-settings');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save General Settings
    public function save_account_settings() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->updateGeneralSetting($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: General settings updated successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: General settings updation failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/account_settings/general', 'refresh');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Personal Settings
    public function save_personal_settings() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->updatePersonalSetting($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Personal settings updated successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Personal settings updation failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/account_settings/personal', 'refresh');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // My Other Settings
    public function save_other_settings() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->updateOtherSetting($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Other settings updated successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Other settings updation failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/account_settings/other', 'refresh');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save User Setting
    public function save_setting() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveUserSettings($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Settings updated successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Settings updation failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/account_settings/panel', 'refresh');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Change Settings
    public function change_password() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'change_password';
            $this->load->view('user/header', $data);
            $this->load->view('user/change-password');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save New Password
    public function save_password() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->updatePassword($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Password changed successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: You entered wrong current password!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/change_password', 'refresh');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Authentication Key
    public function generate_key() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['page'] = 'generate_key';
            $this->load->view('user/header', $data);
            $this->load->view('user/generate-key');
            $this->load->view('user/footer');
        } else {

            //   redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Regenerate Authentication Key
    public function regenerate_auth_key() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->regenerateAuthKey($user_id);
            if ($response) {
                redirect('user/generate_key', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Web Hooks
    public function webhooks() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['page'] = 'webhooks';
            $this->load->view('user/header', $data);
            $this->load->view('user/web-hooks');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Web Hooks
    public function save_webhooks() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveWebHooks($user_id);
            if ($response) {
                $this->session->set_flashdata('message', '<i class="fa fa-check-circle"></i> Success: Push DLR Url updated successfully!');
                $this->session->set_flashdata('message_type', 'notification alert-success');
            } else {
                $this->session->set_flashdata('message', '<i class="fa fa-exclamation-circle"></i> Error: Push DLR Url updation failed!');
                $this->session->set_flashdata('message_type', 'notification alert-danger');
            }
            redirect('user/webhooks', 'refresh');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // HTTP API Security
    public function api_security() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['api_hits'] = $this->user_data_model->getHTTPAPIHits($user_id);
            $data['page'] = 'api_security';
            $this->load->view('user/header', $data);
            $this->load->view('user/api-security');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Manage Websites
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Manage Website
    public function manage_website() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['websites'] = $this->user_data_model->getWebsites($user_id);
            $data ['page'] = 'manage_website';
            $this->load->view('user/header', $data);
            $this->load->view('user/manage-website');
            $this->load->view('user/footer');
        }
    }

    // Check DNS Setting
    public function check_dns_settings() {
        if ($this->session->userdata('logged_in')) {
            echo $result = $this->user_data_model->checkDNSSettings();
            die;
        }
    }

    // Save Website
    public function save_website($website_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveWebsite($user_id, $website_id);
            if ($response) {
                if ($response[0] == "100" || $response[0] == "111") {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = $response[1];
                    $data['websites'] = $this->user_data_model->getWebsites($user_id);
                    if ($response[0] == "100") {
                        if (!$website_id) {
                            $new_website_id = $this->user_data_model->getLastWebsite($user_id);
                            if ($new_website_id) {
                                $data['website_id'] = $new_website_id;
                                $data['website'] = $this->user_data_model->getWebsite($new_website_id);
                            }
                        }
                    } elseif ($response[0] == "111") {
                        if ($website_id) {
                            $data['website_id'] = $website_id;
                            $data['website'] = $this->user_data_model->getWebsite($website_id);
                        }
                    }
                    $this->load->view('user/website-subtab', $data);
                } elseif ($response[0] == "101" || $response[0] == "102" || $response[0] == "103") {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = $response[1];
                    $data['websites'] = $this->user_data_model->getWebsites($user_id);
                    if (!$website_id) {
                        $data['website_id'] = 0;
                    } else {
                        $new_website_id = $this->user_data_model->getLastWebsite($user_id);
                        $data['website_id'] = $new_website_id;
                        $data['website'] = $this->user_data_model->getWebsite($new_website_id);
                    }
                    $this->load->view('user/website-subtab', $data);
                }
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Webiste Insertion Failed!";
                $data['websites'] = $this->user_data_model->getWebsites($user_id);
                $new_website_id = $this->user_data_model->getLastWebsite($user_id);
                if ($new_website_id) {
                    $data['website_id'] = $new_website_id;
                    $data['website'] = $this->user_data_model->getWebsite($new_website_id);
                }
                $this->load->view('user/website-subtab', $data);
            }
        }
    }

    // Delete Website
    public function delete_website($website_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->deleteWebsite($website_id);
            $data['websites'] = $this->user_data_model->getWebsites($user_id);
            $website_id = 0;
            $data['website_id'] = 0;
            if ($website_id)
                $data['website'] = $this->user_data_model->getWebsite($website_id);
            $this->load->view('user/website-subtab', $data);
        }
    }

    // Website Subtabs
    public function get_website_subtab($website_id = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['websites'] = $this->user_data_model->getWebsites($user_id);
            $data['website_id'] = $website_id;
            if ($website_id)
                $data['website'] = $this->user_data_model->getWebsite($website_id);
            $this->load->view('user/website-subtab', $data);
        }
    }

    // Delete Web Data
    public function delete_web_data($type = null, $website_id = 0, $key = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result = $this->user_data_model->deleteWebsiteData($type, $website_id, $key);
            $data['websites'] = $this->user_data_model->getWebsites($user_id);
            $data['website_id'] = $website_id;
            if ($website_id)
                $data['website'] = $this->user_data_model->getWebsite($website_id);
            $this->load->view('user/website-subtab', $data);
        }
    }

    // Save Website Data
    public function save_website_data($website_id = 0, $tab = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            if ($this->input->post('website_id'))
                $website_id = $this->input->post('website_id');
            if ($this->input->post('tab'))
                $tab = $this->input->post('tab');
            if ($tab == 'banner' || $tab == 'about_us') {
                $response = $this->user_data_model->saveWebsiteData($user_id, $website_id, $tab);
                redirect('user/manage_website', 'refresh');
            } else {
                $response = $this->user_data_model->saveWebsiteData($user_id, $website_id, $tab);
                $data['websites'] = $this->user_data_model->getWebsites($user_id);
                $data['website_id'] = $website_id;
                $data['website'] = $this->user_data_model->getWebsite($website_id);
                $this->load->view('user/website-subtab', $data);
            }
        }
    }

    // Save Website Data
    public function save_website_data1($website_id = 0, $tab = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveWebsiteData1($user_id, $website_id, $tab);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Website updated successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Website updation failed!";
            }
            $data['websites'] = $this->user_data_model->getWebsites($user_id);
            $data['website_id'] = $website_id;
            $data['website_tab'] = $tab;
            $data['website'] = $this->user_data_model->getWebsite($website_id);
            $this->load->view('user/show-website-subtab', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Change Panel Theme
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Change Theme
    public function change_theme($theme = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $this->user_data_model->saveThemeColor($user_id, $theme);
            $session_array = array('theme' => $theme);
            $this->session->set_userdata('theme_data', $session_array);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Process Schedule SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send Schedule SMS To Kannel
    public function send_schedule_sms() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        $this->load->model('send_bulk_sms_model', '', TRUE);
        date_default_timezone_set('Asia/Kolkata');
        $current_time = date("Y-m-d H:i:00");
        $response = $this->send_bulk_sms_model->sendScheduleSMS($current_time);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Call DLR Push URL
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Send DLR's to User's Push DLR URL
    public function call_push_dlr_url() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        $result_users = $this->user_data_model->getAllUsers();
        if ($result_users) {
            date_default_timezone_set('Asia/Kolkata');
            $current_date = date("Y-m-d");

            foreach ($result_users as $user) {
                $user_id = $user['user_id'];
                $push_dlr_url = $user['push_dlr_url'];

                $result_campaigns = $this->user_data_model->getCampaigns($user_id, $current_date);

                if ($result_campaigns) {
                    foreach ($result_campaigns as $campaign) {
                        $temp_array = array();
                        $campaign_id = $campaign['campaign_id'];
                        $campaign_uid = strtoupper($campaign['campaign_uid']);
                        $temp_array['requestId'] = $campaign_uid;
                        $result_dlrs = $this->user_data_model->getSentMessages($campaign_id);
                        if ($result_dlrs) {

                            $temp_array['numbers'] = $result_dlrs;
                        }

                        $response = $this->utility_model->callDLRPushURL($push_dlr_url, json_encode($temp_array));
                    }
                }
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Notify Users By SMS And Email
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Notify All User By SMS
    public function notify_users($tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'notify_users';
            $data['result_resellers'] = $this->user_data_model->getUsersNotify('Reseller', $user_id);
            $data['result_users'] = $this->user_data_model->getUsersNotify('User', $user_id);
            $data['previous'] = $this->user_data_model->getPreviousNotify($user_id, $tab);
            $data['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/notify-users');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Search User
    public function search_user_notify($tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $ref_user_id = $session_data['user_id'];

            $data['tab'] = $tab;
            $search = $this->input->post('search');
            $data['result_users'] = $this->user_data_model->searchUserNotify($search, $ref_user_id);
            $this->load->view('user/search-users-notify', $data);
        }
    }

    // Send Notify Users
    public function send_notify_users($tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('send_bulk_sms_model', '', TRUE);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result_user = $this->user_data_model->getUser($user_id);
            $response = 0;
            if ($tab == 'by_sms') {
                $pr_sms_balance = $result_user['pr_sms_balance'];
                $tr_sms_balance = $result_user['tr_sms_balance'];
                $auth_key = $result_user['auth_key'];
                $response = $this->send_bulk_sms_model->notifyUsersBySMS($user_id, $pr_sms_balance, $tr_sms_balance, $auth_key);
            } elseif ($tab == 'by_email') {
                $company_name = $result_user['company_name'];
                $response = $this->send_bulk_sms_model->notifyUsersByEmail($user_id, $company_name);
            }
            $data['result_resellers'] = $this->user_data_model->getUsersNotify('Reseller', $user_id);
            $data['result_users'] = $this->user_data_model->getUsersNotify('User', $user_id);
            $data['previous'] = $this->user_data_model->getPreviousNotify($user_id, $tab);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Notification sent successfully!");
                $this->session->set_flashdata('message_type', "notification alert-success");
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Notification sending failed! Please try after sometime");
                $this->session->set_flashdata('message_type', "notification alert-danger");
            }
            redirect('user/notify_users/' . $tab, 'refresh');
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Reseller Settings
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Reseller Setting
    public function reseller_settings($tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $data['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/reseller-settings');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Reseller Setting
    public function save_reseller_setting($type = 0, $tab = 0) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveResellerSetting($user_id, $type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Reseller setting updated successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Reseller setting updation failed!";
            }
            /*
              $result_user = $this->user_data_model->getUser($user_id);
              $data['demo_balance'] = $result_user['demo_balance'];
              $data['expiry_days'] = $result_user['expiry_days'];
              $data['signup_sender'] = $result_user['signup_sender'];
              $data['signup_message'] = $result_user['signup_message'];
              $data['signup_subject'] = $result_user['signup_subject'];
              $data['signup_body'] = $result_user['signup_body'];
              $data['demo_sender'] = $result_user['demo_sender'];
              $data['demo_message'] = $result_user['demo_message'];
              $data['signup_notification'] = $result_user['signup_notification'];
              $data['email_address'] = $result_user['email_address'];
             */
            $data['tab'] = $tab;
            $this->load->view('user/show-reseller-setting', $data);
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Change User Type
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Make Me Reseller
    public function make_me_reseller() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $user_type = "Reseller";
            $make_spacial_reseller = 0;
            $response = $this->user_data_model->changeUserType($user_id, $user_type, $make_spacial_reseller);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Now you are a reseller!");
                $this->session->set_flashdata('message_type', "notification alert-success");
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please try again later!");
                $this->session->set_flashdata('message_type', "notification alert-danger");
            }
            redirect('user/index', 'refresh');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function make_spacial_reseller() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $user_type = "Reseller";
            $make_spacial_reseller = 1;
            $response = $this->user_data_model->changeUserType($user_id, $user_type, $make_spacial_reseller);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Now you are a reseller!");
                $this->session->set_flashdata('message_type', "notification alert-success");
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please try again later!");
                $this->session->set_flashdata('message_type', "notification alert-danger");
            }
            redirect('user/index', 'refresh');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Make Me User
    public function make_me_user() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $make_spacial_reseller = 0;
            $response = $this->user_data_model->checkChilds($user_id);
            if ($response) {
                $user_type = "User";
                $response1 = $this->user_data_model->changeUserType($user_id, $user_type, $make_spacial_reseller);
                if ($response1) {
                    $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Now you are a user!");
                    $this->session->set_flashdata('message_type', "notification alert-success");
                } else {
                    $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Please try again later!");
                    $this->session->set_flashdata('message_type', "notification alert-danger");
                }
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: You have some child accounts!");
                $this->session->set_flashdata('message_type', "notification alert-danger");
            }
            redirect('user/index', 'refresh');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Update DLR Status From Kannel
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Update DLR From Kannel
    public function update_dlr_report($myid, $campaign_id, $msgid, $type, $dlr = false) {
        $this->load->model('send_bulk_sms_model', '', TRUE);
        $response = $this->send_bulk_sms_model->updateDlrReport($myid, $campaign_id, $msgid, $type, $dlr);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Logout User Session
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Logout
    public function logout() {
        $login_place = "";
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $response_session = $this->utility_model->updateLoginInfo($user_id, "OFF");
            if (isset($session_data['login_place'])) {
                $login_place = $session_data['login_place'];
            }
            $this->session->unset_userdata('logged_in');
            $this->session->unset_userdata('user_data');
            $this->session->unset_userdata('theme_data');
            $this->session->set_flashdata('login_msg', 'We will get back you soon!');
            if ($login_place == 'web_login') {
                // redirect('signin', 'refresh');
                redirect('http://bulksmsserviceproviders.com');
                // header('Location: ');
            } elseif ($login_place == 'reseller_login') {
                // header('location:' . base_url());
                // redirect('home', 'refresh');
                header('location:' . base_url());
            } elseif ($login_place == 'bulk24sms') {
                redirect('http://www.bulk24sms.com/signin.php');
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Subscribers
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Subscribers
    public function subscribers() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'subscribers';
            $this->load->view('user/header', $data);
            $this->load->view('user/subscribers');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Pagination
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Pagination
    public function pagination() {
        $page_number = $this->input->post('page_number');
        $item_par_page = 10;
        $position = ($page_number * $item_par_page);
        $result_set = $this->db->query("SELECT * FROM countries LIMIT " . $position . "," . $item_par_page);
        $total_set = $result_set->num_rows();
        $page = $this->db->get('countries');
        $total = $page->num_rows();
        //break total recoed into pages
        $total = ceil($total / $item_par_page);
        if ($total_set > 0) {
            $entries = null;
            // get data and store in a json array
            foreach ($result_set->result() as $row) {
                $entries[] = $row;
            }
            $data = array(
                'TotalRows' => $total,
                'Rows' => $entries
            );
            $this->output->set_content_type('application/json');
            echo json_encode(array($data));
        }
        exit;
    }

    // Pagination
    public function paging($array = array(), $index = 0, $records_per_page = 0) {
        $total_pages = 0;
        $total_records = 0;
        $slice_array = array();
        if ($array) {
            $slice_array = array_slice($array, $index, $records_per_page);
            $total_pages = ceil(count($array) / $records_per_page);
            $total_records = sizeof($array);
        }
        $return_array = $slice_array;
        $page_total_records = $index + sizeof($slice_array);
        $start_page = $index + 1;
        if ($total_records)
            $records_data = "Showing $start_page to $page_total_records of $total_records Records";
        else
            $records_data = "Showing $total_records Records";
        // Return Data
        return array('return_array' => $return_array, 'total_pages' => $total_pages, 'records_data' => $records_data, 'total_records' => $total_records);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Short/Long Codes
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Long Codes-Inbox
    public function long_codes($tab = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            if ($tab == 'dashboard') {
                $data['long_keywords'] = $this->code_data_model->getDashboradKeywords('long', $user_id);
            } elseif ($tab == 'inbox') {
                if ($user_id != 72) {
                    $data['long_inbox'] = $this->code_data_model->getInBox('long', $user_id);
                }
            } elseif ($tab == 'sentbox') {
                if ($user_id != 72) {
                    $data['long_sentbox'] = $this->code_data_model->getSentBox('long', $user_id);
                }
            } elseif ($tab == 'keywords') {
                $data['long_numbers'] = $this->code_data_model->getNumbers('long');
                $data['long_keywords'] = $this->code_data_model->getKeywords('long', $user_id, 0);
            } elseif ($tab == 'keyword_reply') {
                if ($user_id != 72) {
                    $data['long_keywords'] = $this->code_data_model->getKeywords('long', $user_id, 1);
                    $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply('long', $user_id, 0, 0);
                }
            }
            $data ['page'] = 'long_codes';
            $data ['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/long-codes');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Short Codes-Inbox
    public function short_codes($tab = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            if ($tab == 'dashboard') {
                $data['short_keywords'] = $this->code_data_model->getDashboradKeywords('short', $user_id);
            } elseif ($tab == 'inbox') {
                $data['short_inbox'] = $this->code_data_model->getInBox('short', $user_id);
            } elseif ($tab == 'sentbox') {
                $data['short_sentbox'] = $this->code_data_model->getSentBox('short', $user_id);
            } elseif ($tab == 'keywords') {
                $data['short_numbers'] = $this->code_data_model->getNumbers('short');
                $data['short_keywords'] = $this->code_data_model->getKeywords('short', $user_id, 0);
            } elseif ($tab == 'keyword_reply') {
                $data['short_keywords'] = $this->code_data_model->getKeywords('short', $user_id, 1);
                $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply('short', $user_id, 0, 0);
            }
            $data ['page'] = 'short_codes';
            $data ['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/short-codes');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Short/Long Keyword
    public function save_options() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->code_data_model->saveForwardOptions($user_id);
            echo 1;
            die;
        }
    }

    // Save Short/Long Keyword
    public function save_sl_keyword($type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->code_data_model->saveKeyword($user_id, $type);
            if ($response == 1) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword inserted successfully!";
            } elseif ($response == 101) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword already exist! Please try another";
            } elseif ($response == 102) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword insertion failed!";
            }
            if ($type == 'short') {
                $data['type'] = 'keyword';
                $data['short_keywords'] = $this->code_data_model->getKeywords($type, $user_id, 0);
                $this->load->view('user/show-short-keywords', $data);
            } elseif ($type == 'long') {
                $data['type'] = 'keyword';
                $data['long_keywords'] = $this->code_data_model->getKeywords($type, $user_id, 0);
                $this->load->view('user/show-long-keywords', $data);
            }
        }
    }

    // Delete Short/Long Keyword
    public function delete_sl_keyword($type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->code_data_model->deleteKeyword($type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword deletion failed!";
            }
            if ($type == 'short') {
                $data['type'] = 'keyword';
                $data['short_keywords'] = $this->code_data_model->getKeywords($type, $user_id, 0);
                $this->load->view('user/show-short-keywords', $data);
            } elseif ($type == 'long') {
                $data['type'] = 'keyword';
                $data['long_keywords'] = $this->code_data_model->getKeywords($type, $user_id, 0);
                $this->load->view('user/show-long-keywords', $data);
            }
        }
    }

    // Delete Short/Long Keyword Reply
    public function delete_sl_keyword_reply($type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->code_data_model->deleteKeywordReply($type);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword reply deleted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword reply deletion failed!";
            }
            if ($type == 'short') {
                $data['type'] = 'keyword_reply';
                $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                $this->load->view('user/show-short-keywords', $data);
            } elseif ($type == 'long') {
                $data['type'] = 'keyword_reply';
                $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                $this->load->view('user/show-long-keywords', $data);
            }
        }
    }

    // Save Short/Long Keyword Reply
    public function save_sl_keyword_reply($type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->code_data_model->saveKeywordReply($user_id, $type);
            if ($response == 1) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword reply inserted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword reply insertion failed!";
            }
            if ($type == 'short') {
                $data['type'] = 'keyword_reply';
                $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                $this->load->view('user/show-short-keywords', $data);
            } elseif ($type == 'long') {
                $data['type'] = 'keyword_reply';
                $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                $this->load->view('user/show-long-keywords', $data);
            }
        }
    }

    // Save Keyword Reply From Dashboard
    public function save_keyword_reply($type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['number'] = $this->input->post('number');
            $keyword_id = $this->input->post('keyword_id');
            $data['keyword_id'] = $keyword_id;
            $response = $this->code_data_model->saveKeywordReplyD($type);
            if ($response == 1) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Keyword reply inserted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Keyword reply insertion failed!";
            }
            if ($type == 'short') {
                $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply($type, 0, 0, $keyword_id);
            } elseif ($type == 'long') {
                $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply($type, 0, 0, $keyword_id);
            }
            $data['type'] = $type;
            $this->load->view('user/show-keywords-replies', $data);
        }
    }

    // Filter Keyword Replies
    public function filter_keyword_replies($keyword_id = 0, $type = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['type'] = 'keyword_reply';
            if ($type == 'short') {
                if ($keyword_id == "-1") {
                    $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                } else {
                    $data['short_keyword_reply'] = $this->code_data_model->getKeywordReply($type, 0, 0, $keyword_id);
                }
                $this->load->view('user/show-short-keywords', $data);
            } elseif ($type == 'long') {
                if ($keyword_id == "-1") {
                    $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply($type, $user_id, 0, 0);
                } else {
                    $data['long_keyword_reply'] = $this->code_data_model->getKeywordReply($type, 0, 0, $keyword_id);
                }
                $this->load->view('user/show-long-keywords', $data);
            }
        }
    }

    // Check Keyword Avaialibity
    public function check_keyword_availability($type = null) {
        $result = $this->code_data_model->checkKeywordAvailability($type);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(array('type' => 1, 'message' => 'Congratulations! Keyword available!'));
            die;
        } else {
            header('Content-Type: application/json');
            echo json_encode(array('type' => 0, 'message' => 'Sorry! Keyword not available. Please try another keyword!'));
            die;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Receive Long/Short Code Requests    
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Receive Message On Portal Short
    public function get_short_requests() {
        // Get Request From URL

        $from = $this->input->get('from');
        $message = $this->input->get('message');

        $message = urldecode($message);
        $message_array = explode(' ', trim($message));

        if (sizeof($message_array)) {
            $keyword = $message_array[0];
            $copy = $message_array;
            unset($copy[0]);
            $message = implode(' ', $copy);
            // Get All Keywords
            $result_short_keyword = $this->code_data_model->checkKeyword('short', $keyword);
            if ($result_short_keyword) {
                $short_keyword_id = $result_short_keyword->short_keyword_id;
                $user_id = $result_short_keyword->user_id;
                $auth_key = $result_short_keyword->auth_key;
                $short_keyword = $result_short_keyword->short_keyword;
                $short_number = $result_short_keyword->short_number;
                $short_code_balance = $result_short_keyword->short_code_balance;
                $short_keyword_expiry = $result_short_keyword->short_keyword_expiry;
                $current_date = date('d-m-Y');
                // Forwarding Options
                $forward_email = $result_short_keyword->forward_email;
                $forward_contact = $result_short_keyword->forward_contact;
                $forward_webhook = $result_short_keyword->forward_webhook;


                // Check Keyword Expiry Date With Current Date
                //  if ($current_date <= $short_keyword_expiry) {
                // Insert Into Inbox Table
                $date = date('Y-m-d H:i:s');

                $array = array(
                    'from' => $from,
                    'message' => $message,
                    'short_keyword_id' => $short_keyword_id,
                    'date' => $date,
                    'status' => 1
                );

                $response = $this->code_data_model->saveInbox('short', $array);
                if ($response) {
                    // Deduct Balance From User Account
                    $remain_balance = $short_code_balance - 1;
                    $this->code_data_model->updateUserBalance('short', $user_id, $remain_balance);
                    // Send On User Email
                    if ($forward_email != "") {
                        // Prepare Email Array
                        $subject = "New sms received on your shortcode keyword { $keyword }";
                        $body = "<p><strong>Mobile Number</strong>: $from</p>";
                        $body .= "<p><strong>Message</strong>: $message</p>";
                        $body .= "<p><strong>Keyword</strong>: $keyword</p>";
                        $body .= "<p><strong>To your longcode</strong>: $short_number</p>";
                        $mail_array = array(
                            'from_email' => $forward_email,
                            'from_name' => $forward_email,
                            'to_email' => $forward_email,
                            'subject' => $subject,
                            'message' => $body
                        );
                        $this->utility_model->sendEmail($mail_array);
                    }
                    // Send On User Web Hook URL
                    if ($forward_webhook != "") {
                        $temp_array = array(
                            'sender' => $from,
                            'message' => $message,
                            'keyword' => $keyword,
                            'receiver' => $long_number,
                            'datetime' => $date
                        );
                        // Build POST Body
                        $count_post = sizeof($temp_array);
                        $post_body = http_build_query($temp_array);
                        $this->utility_model->callLSWebHookURL($forward_webhook, $post_body, $count_post);
                    }
                }
            }
        }
    }

    // Receive Message On Portal Long (Shared Number)
    public function get_long_requests1() {
        // Get Request From URL
        $date = $this->input->get('date_time');

        $from = $this->input->get('recipentnumber');
        $operator = $this->input->get('operator');
        $circle = $this->input->get('circle');
        $message = $this->input->get('messagecontent');
        $message_array = explode(' ', trim($message));
        if (sizeof($message_array)) {
            $keyword = $message_array[0];
            // Get All Keywords
            $result_long_keyword = $this->code_data_model->checkKeyword('long', $keyword);

            if ($result_long_keyword) {
                $short_keyword_id = $result_long_keyword->short_keyword_id;
                $user_id = $result_long_keyword->user_id;
                $auth_key = $result_long_keyword->auth_key;
                $long_keyword = $result_long_keyword->long_number;
                $long_number = $result_long_keyword->long_number;
                // Forwarding Options
                $forward_email = $result_long_keyword->forward_email;
                $forward_contact = $result_long_keyword->forward_contact;
                $forward_webhook = $result_long_keyword->forward_webhook;
                // Insert Into Inbox Table
                $array = array(
                    'from' => $from,
                    'message' => $message,
                    'short_keyword_id' => $short_keyword_id,
                    'date' => $date
                );
                $response = $this->code_data_model->$ch = curl_init();
                //('short', $array);
                if ($response) {
                    /*
                      $short_keyword_reply = $this->code_data_model->getKeywordReply('short', 0, $short_keyword_id, 0);
                      if ($short_keyword_reply) {
                      $keyword_reply_id = $short_keyword_reply->keyword_reply_id;
                      $keyword_reply_sender = $short_keyword_reply->keyword_reply_sender;
                      $keyword_reply = $short_keyword_reply->keyword_reply;
                      // Send Reply Message
                      // API URL
                      $domain_name = $_SERVER['SERVER_NAME'];
                      $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                      $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                      // Prepare SMS Array
                      $encoded_message = urlencode($keyword_reply);
                      $route = "B";
                      $sms_array = array(
                      'authkey' => $auth_key,
                      'mobiles' => $from,
                      'message' => $encoded_message,
                      'sender' => $keyword_reply_sender,
                      'route' => $route
                      );
                      // Insert Into Inbox Table
                      $date = date('Y-m-d H:i:s');
                      $array = array(
                      'sentbox_reciever' => $from,
                      'sentbox_date' => $date,
                      'keyword_reply_id' => $keyword_reply_id
                      );
                      if ($this->utility_model->sendSMS($url, $sms_array) && $this->code_data_model->saveShortSentBox($array)) {

                      }
                      }
                     */
                    // Send On User Email
                    if ($forward_email != "") {
                        // Prepare Email Array
                        $subject = "New sms received on your shortcode keyword { $keyword }";
                        $body = "<p><strong>Mobile Number</strong>: $from</p>";
                        $body .= "<p><strong>Message</strong>: $message</p>";
                        $body .= "<p><strong>Keyword</strong>: $keyword</p>";
                        $body .= "<p><strong>To your longcode</strong>: $short_number</p>";
                        $mail_array = array(
                            'from_email' => $forward_email,
                            'from_name' => $forward_email,
                            'to_email' => $forward_email,
                            'subject' => $subject,
                            'message' => $body
                        );
                        $this->utility_model->sendEmail($mail_array);
                    }
                    /*
                      // Send On User Contact Number
                      if ($forward_contact != "") {
                      $forward_message = urlencode("Test");
                      $route = "B";
                      $sms_array = array(
                      'authkey' => $auth_key,
                      'mobiles' => $forward_contact,
                      'message' => $forward_message,
                      'sender' => 'ALERTS',
                      'route' => 'B'
                      );
                      }
                     */
                    // Send On User Web Hook URL
                    if ($forward_webhook != "") {
                        $temp_array = array();
                        $temp_array['sender'] = $from;
                        $temp_array['message'] = $message;
                        $temp_array['keyword'] = $keyword;
                        $temp_array['reciever'] = $short_number;
                        $temp_array['datetime'] = $date;

                        $this->utility_model->callDLRPushURL($forward_webhook, json_encode($temp_array));
                    }
                }
            }
        }

        // Get Request From URL

        $long_keyword_id = 0;
        $message_array = explode(' ', trim($message));
        if (sizeof($message_array)) {
            $keyword = $message_array[0];
        }
        // Get All Keywords

        $result_long_keyword = $this->code_data_model->checkKeyword('long', $keyword);
        if ($result_long_keyword) {
            $long_keyword_id = $result_long_keyword->long_keyword_id;
            $user_id = $result_long_keyword->user_id;
            $auth_key = $result_long_keyword->auth_key;
            $long_keyword = $result_long_keyword->long_keyword;
            $long_number = $result_long_keyword->long_number;
            // Forwarding Options
            $forward_email = $result_long_keyword->forward_email;
            $forward_contact = $result_long_keyword->forward_contact;
            $forward_webhook = $result_long_keyword->forward_webhook;

            // Insert Into Inbox Table
            $date = date('Y-m-d H:i:s');
            $array = array(
                'from' => $from,
                'message' => $message,
                'long_keyword_id' => $long_keyword_id,
                'date' => $date,
            );
            $response = $this->code_data_model->saveInbox('long', $array);
            if ($response) {
                /*
                  $long_keyword_reply = $this->code_data_model->getKeywordReply('long', 0, $long_keyword_id, 0);
                  if ($long_keyword_reply) {
                  $keyword_reply_id = $long_keyword_reply->keyword_reply_id;
                  $keyword_reply_sender = $long_keyword_reply->keyword_reply_sender;
                  $keyword_reply = $long_keyword_reply->keyword_reply;
                  // Send Reply Message
                  // API URL
                  $domain_name = $_SERVER['SERVER_NAME'];
                  $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                  $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                  // Prepare SMS Array
                  $encoded_message = urlencode($keyword_reply);
                  $route = "B";
                  $sms_array = array(
                  'authkey' => $auth_key,
                  'mobiles' => $from,
                  'message' => $encoded_message,
                  'sender' => $keyword_reply_sender,
                  'route' => $route
                  );
                  // Insert Into Inbox Table
                  $date = date('Y-m-d H:i:s');
                  $array = array(
                  'sentbox_reciever' => $from,
                  'sentbox_date' => $date,
                  'keyword_reply_id' => $keyword_reply_id
                  );
                  if ($this->utility_model->sendSMS($url, $sms_array) && $this->code_data_model->saveSentBox('long', $array)) {

                  }
                  }
                 */
                // Send On User Email
                if ($forward_email != "") {
                    // Prepare Email Array
                    $subject = "New sms received on your longcode keyword { $keyword }";
                    $body = "<p><strong>Mobile Number</strong>: $from</p>";
                    $body .= "<p><strong>Message</strong>: $message</p>";
                    $body .= "<p><strong>Keyword</strong>: $keyword</p>";
                    $body .= "<p><strong>To your longcode</strong>: $long_number</p>";
                    $mail_array = array(
                        'from_email' => $forward_email,
                        'from_name' => $forward_email,
                        'to_email' => $forward_email,
                        'subject' => $subject,
                        'message' => $body
                    );
                    $this->utility_model->sendEmail($mail_array);
                }
                /*
                  // Send On User Contact Number
                  if ($forward_contact != "") {
                  $forward_message = urlencode("Test");
                  $route = "B";
                  $sms_array = array(
                  'authkey' => $auth_key,
                  'mobiles' => $forward_contact,
                  'message' => $forward_message,
                  'sender' => 'ALERTS',
                  'route' => 'B'
                  );
                  }
                 */
                // Send On User Web Hook URL
                if ($forward_webhook != "") {
                    $temp_array = array();
                    $temp_array['sender'] = $from;
                    $temp_array['message'] = $message;
                    $temp_array['keyword'] = $keyword;
                    $temp_array['reciever'] = $long_number;

                    $this->utility_model->callDLRPushURL($forward_webhook, json_encode($temp_array));
                }
            }
        }
    }

    // Receive Message On Portal Long (Dedicated Number)
    public function get_long_requests() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        // Get Request From URL
        $date = $this->input->get('date_time');
        $unixtime = strtotime($date);
        $date = date("Y-m-d H:i:s", $unixtime);
        $from = $this->input->get('recipentnumber');
        $operator = $this->input->get('operator');
        $circle = $this->input->get('circle');
        $message = $this->input->get('messagecontent');
        $message = urldecode($message);
        $message_array = explode(' ', trim($message));
        if (sizeof($message_array)) {
            $keyword = $message_array[0];
            $copy = $message_array;
            unset($copy[0]);
            $message = implode(' ', $copy);
            // Get All Keywords
            $result_long_keyword = $this->code_data_model->checkKeyword('long', $keyword);
            if ($result_long_keyword) {
                $long_keyword_id = $result_long_keyword->long_keyword_id;
                $user_id = $result_long_keyword->user_id;
                $auth_key = $result_long_keyword->auth_key;
                $long_keyword = $result_long_keyword->long_keyword;
                $long_number = $result_long_keyword->long_number;
                $default_sender_id = $result_long_keyword->default_sender_id;
                $long_code_balance = $result_long_keyword->long_code_balance;
                // Forwarding Options
                $forward_email = $result_long_keyword->forward_email;
                $forward_contact = $result_long_keyword->forward_contact;
                $forward_webhook = $result_long_keyword->forward_webhook;

                // Check User Balance
                if ($long_code_balance) {
                    $array = array(
                        'from' => $from,
                        'message' => $message,
                        'operator' => $operator,
                        'circle' => $circle,
                        'long_keyword_id' => $long_keyword_id,
                        'date' => $date,
                        'status' => 1
                    );
                    $response = $this->code_data_model->saveInbox('long', $array);
                    if ($response) {
                        // Deduct Balance From User Account
                        $remain_balance = $long_code_balance - 1;
                        $this->code_data_model->updateUserBalance('long', $user_id, $remain_balance);

                        // Send keyword Replies
                        $long_keyword_reply = $this->code_data_model->getKeywordReply('long', 0, $long_keyword_id, 0);
                        if ($long_keyword_reply) {
                            $keyword_reply_id = $long_keyword_reply->keyword_reply_id;
                            $keyword_reply_sender = $long_keyword_reply->keyword_reply_sender;
                            $keyword_reply = $long_keyword_reply->keyword_reply;
                            // Send Reply Message
                            // API URL
                            $domain_name = $_SERVER['SERVER_NAME'];
                            $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                            $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                            // Prepare SMS Array
                            $encoded_message = $keyword_reply;
                            $route = "B";
                            $sms_array = array(
                                'authkey' => $auth_key,
                                'mobiles' => $from,
                                'message' => $encoded_message,
                                'sender' => $keyword_reply_sender,
                                'route' => $route
                            );
                            // Insert Into Inbox Table
                            $date = date('Y-m-d H:i:s');
                            $array = array(
                                'sentbox_reciever' => $from,
                                'sentbox_date' => $date,
                                'keyword_reply_id' => $keyword_reply_id
                            );
                            if ($this->utility_model->sendSMS($url, $sms_array)) {
                                $this->code_data_model->saveSentBox('long', $array);
                            }
                        }

                        // Send On User Email
                        if ($forward_email != "") {
                            // Prepare Email Array
                            $subject = "New sms received on your longcode keyword { $keyword }";
                            $body = "<p><strong>Mobile Number</strong>: $from</p>";
                            $body .= "<p><strong>Message</strong>: $message</p>";
                            $body .= "<p><strong>Keyword</strong>: $keyword</p>";
                            $body .= "<p><strong>To your longcode</strong>: $long_number</p>";
                            $mail_array = array(
                                'from_email' => $forward_email,
                                'from_name' => $forward_email,
                                'to_email' => $forward_email,
                                'subject' => $subject,
                                'message' => $body
                            );
                            $this->utility_model->sendEmail($mail_array);
                        }

                        // Send On User Contact Number
                        if ($forward_contact != "") {
                            // API URL
                            $domain_name = $_SERVER['SERVER_NAME'];
                            $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                            $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                            // Prepare SMS Array
                            $encoded_message = $message;
                            $route = "B";
                            if ($default_sender_id == "") {
                                $default_sender_id = "ALERTS";
                            }
                            $sms_array = array(
                                'authkey' => $auth_key,
                                'mobiles' => $forward_contact,
                                'message' => $encoded_message,
                                'sender' => $default_sender_id,
                                'route' => $route
                            );
                            $this->utility_model->sendSMS($url, $sms_array);
                        }

                        // Send On User Web Hook URL
                        if ($forward_webhook != "") {
                            $temp_array = array(
                                'sender' => $from,
                                'message' => $message,
                                'keyword' => $keyword,
                                'number' => $long_number,
                                'datetime' => $date
                            );
                            // Build POST Body
                            $count_post = sizeof($temp_array);
                            $post_body = http_build_query($temp_array);

                            $this->utility_model->callLSWebHookURL($forward_webhook, $post_body, $count_post);
                        }
                    }
                }
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Voice SMS
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // User Send Voice SMS (Old)
    public function voice_sms1() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'voice_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/send-voice-sms1');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // User Send Voice SMS (New)
    public function voice_sms() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Update User Login Info
            $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'VOICE');
            $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'VOICE');
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
            $data['result_demo'] = $this->user_data_model->getDemoFile();
            // var_dump($data['result_demo']);die;
            $data['page'] = 'voice_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/send-voice-sms');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Change Voice Route
    public function change_voice_route($route = null) {
        if ($this->session->userdata('logged_in')) {
            $data['route'] = $route;
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result_user = $this->user_data_model->getUser($user_id);
            $data['utype'] = $result_user['utype'];
            $data['pr_voice_balance'] = $result_user['pr_voice_balance'];
            $data['tr_voice_balance'] = $result_user['tr_voice_balance'];
            $response = $this->user_data_model->setDefaultVoiceRoute($user_id, $route);
            if ($response) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Route changed successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Route changing failed!";
            }
            $this->load->view('user/change-voice-route', $data);
        }
    }

    // Send Voice SMS Action
    public function send_voice_sms() {
        if ($this->session->userdata('logged_in')) {
           
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $user = $this->user_data_model->getUser($user_id);
            $temp_array = $this->load->get_var('user_info');
            if ($temp_array['user_status'] == 1) {
                $response = $this->voice_sms_model->sendVoiceMessage1($user_id);
                if ($response == '1') {
                    $data['msg_type'] = '1';
                    $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Voice message scheduled successfully!";
                } elseif ($response == '100') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Voice message sending failed!";
                } elseif ($response == '101') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: You don't have sufficient balance to send SMS!";
                } elseif ($response == '102') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Selected Route is not available! Please try again later";
                } elseif ($response == '103') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please check your caller id!";
                } elseif ($response == '104') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Check mobile numbers. Some mobile number(s) are invalid!";
                } elseif ($response == '105') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your request contains black listed numbers. Please provide valid mobile number(s)!";
                } elseif ($response == '106') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "$response Error: Something wrong! Please try again later!";
                } elseif ($response == '107') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please upload only csv, xls, xlsx files!";
                } elseif ($response == '108') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid start date-time! This is past date-time!";
                } elseif ($response == '109') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time! This is past date-time!";
                } elseif ($response == '110') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time! It must be greater than start date time!";
                } elseif ($response == '111') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Please upload only .mp3, .wav files!";
                } elseif ($response == '112') {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Invalid caller id! Please check!";
                } else {
                    $data['msg_type'] = '0';
                    $data['msg_data'] = "$response Error: Something wrong! Please try again later!";
                }
                // Get User Info
                $data['user_info'] = $this->user_data_model->getUser($user_id);
                $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'VOICE');
                $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'VOICE');
                $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
                $data['result_demo'] = $this->user_data_model->getDemoFile();
                //  var_dump( $data['result_demo']);die;
                $this->load->view('user/show-voice-sms', $data);
            } else {
                // Get User Info
                $data['user_info'] = $this->user_data_model->getUser($user_id);
                $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'VOICE');
                $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'VOICE');
                $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Your account temporarely suspended by your account manager!";
                $this->load->view('user/show-voice-sms', $data);
            }
        }
    }

    // Voice Delivery Reports
    public function voice_delivery_reports() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $per_page = 10;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/voice_delivery_reports", $per_page, $this->user_data_model->countVoiceDeliveryReports($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["voice_delivery_reports"] = $this->user_data_model->getVoiceDeliveryReports($user_id, $per_page, $page);
            $data['page'] = 'voice_delivery_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/voice-delivery-reports');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Voice Sent SMS
    public function voice_sent_sms($campaign_id = 0, $route = null) {

        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['route'] = $route;
            // Pagination
            $per_page = 100;
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/voice_sent_sms/" . $campaign_id . "/" . $route, $per_page, $this->user_data_model->countVoiceSentSMS($campaign_id), 5);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["sent_sms"] = $this->user_data_model->getVoiceSentSMS($campaign_id, $per_page, $page);
            $data['sent_sms_status'] = $this->user_data_model->getSentVoiceSMSStatus($campaign_id);
            $data['campaign'] = $campaign_id;
            $data['page'] = 'voice_delivery_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/voice-sent-sms');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Update Voice SMS DLR Using Infobip API
    public function update_voice_dlr() {
        $result_sms = $this->voice_sms_model->updateVoiceSMSStatus();
    }

    public function update_voice_dlr_videocon() {
        $result_sms = $this->voice_sms_model->updateVoiceStatusVideocon();
    }

    // Upload Audio File For Drafts
    public function upload_audio_file() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->uploadAudioFile($user_id);
            if ($response == '200') {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Audio File uploaded successfully!";
                //header('Location: http://sms.bulksmsserviceproviders.com/user/voice_sms');
                //  $this->load->view('user/voice_sms');
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Invalid audio file! Please upload only mp3 or wav files!";
            } elseif ($response == 101) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            }
            // Draft Voice SMS
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
            //  $data['demo_drafts'] = $this->user_data_model->getDemoFile();
            // var_dump( $data['demo_drafts']);die;
            $this->load->view('user/show-voice-drafts', $data);
            // $this->update_voice_dlr();
        }
    }

    public function save_voice_id() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->uploadAudioFile($user_id);
            if ($response == '200') {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Audio File uploaded successfully!";
                //header('Location: http://sms.bulksmsserviceproviders.com/user/voice_sms');
                //  $this->load->view('user/voice_sms');
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Invalid audio file! Please upload only mp3 or wav files!";
            } elseif ($response == 101) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            }
            // Draft Voice SMS
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
            // $data['demo_drafts'] = $this->user_data_model->getDemoFile();
            // var_dump( $data['demo_drafts']);die;
            $this->load->view('user/show-voice-drafts', $data);
            // $this->update_voice_dlr();
        }
    }

    // Upload Audio File For Drafts
    // Upload Audio File For Drafts
    public function upload_attach_file() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->uploadAttachFile($user_id);
            if ($response == '200') {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success:  File uploaded successfully!";
                //header('Location: http://sms.bulksmsserviceproviders.com/user/voice_sms');
                //  $this->load->view('user/voice_sms');
            } elseif ($response == 100) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Invalid file! Please upload file!";
            } elseif ($response == 101) {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Error: Something wrong! Please try again later!";
            }
            // Draft Voice SMS
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');
            //  $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'attach_file', 'ATTACH');
//  $data['demo_drafts'] = $this->user_data_model->getDemoFile();
            // var_dump( $data['demo_drafts']);die;
            $this->load->view('user/show-attach-drafts', $data);
            // $this->update_voice_dlr();
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Update XML Route SMS DLR
    public function update_xml_dlr() {
        $request = $this->input->post('data');
        //$result['dlr'] =   $this->load->view('user/xml_dlr', $request);
        $result_sms = $this->utility_model->updateXMLSMSStatus($request);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Unit Testing
    public function tesing() {
        $this->load->library('unit_test');
        $test = 1 + 1;
        $expected_result = 2;
        $test_name = 'Adds one plus one';
        echo $this->unit->run($test, $expected_result, $test_name);
        die;
    }

    // Unit Testing
    public function dummy() {
        $this->load->view('user/header');
        $this->load->view('user/dummy');
        $this->load->view('user/footer');
    }

    // Google URL Shortner
    public function google_url_shortner() {
        $this->load->model('Sms_Model', 'sms_model');
        $url = 'http://sms.bulksmsserviceproviders.com';
        echo $short_web_domain = $this->sms_model->googleUrlShortner($url);
        die;
        //echo 'Response code: ' . $this->google_url_api->get_http_status();
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Chat Messages
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get User Chat Message
    public function user_chat_messages() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('Data_Model', 'data_model');
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['chat_messages'] = $this->data_model->getChatMessages(false, 'DESC', $user_id, 0);
            $this->load->view('user-chat-box', $data);
        }
    }

    // Chat Message Submit
    public function chat_submit() {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('Data_Model', 'data_model');
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $array['user_id'] = $user_id;
            $array['chat_message'] = $this->input->post('chat_message');
            // Insert Into DB
            $this->data_model->insertChatMessage($array);
            // Get Last Chat Message
            $last_message = $this->data_model->getChatMessages(true, null, $user_id, 0);
            if ($last_message) {
                $array['chat_user'] = $last_message->user_id;
                $array['chat_admin'] = $last_message->admin_id;
                $array['chat_datetime'] = $last_message->chat_datetime;
                $array['chat_id'] = $last_message->chat_id;
                $array['success'] = true;
            }
            echo json_encode($array);
            die;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts Services
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts-Inbox
    public function missed_call_alerts($tab = null) {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            if ($tab == 'dashboard') {
                $data['missed_call_services'] = $this->code_data_model->getMissedCallServices($user_id);
            } elseif ($tab == 'inbox') {
                $data['missed_call_inbox'] = $this->code_data_model->getMissedCallInbox($user_id);
            } elseif ($tab == 'sentbox') {
                $data['missed_call_sentbox'] = $this->code_data_model->getMissedCallSentbox($user_id);
            }
            $data ['page'] = 'missed_call_alerts';
            $data ['tab'] = $tab;
            $this->load->view('user/header', $data);
            $this->load->view('user/missed-call-alerts');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Save Auto Reply From Dashboard
    public function save_auto_reply() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['number'] = $this->input->post('number');
            $service_id = $this->input->post('service_id');
            $data['service_id'] = $service_id;
            $response = $this->code_data_model->saveAutoReply();
            if ($response == 1) {
                $data['msg_type'] = '1';
                $data['msg_data'] = "<i class='fa fa-check-circle'></i> Success: Auto reply inserted successfully!";
            } else {
                $data['msg_type'] = '0';
                $data['msg_data'] = "<i class='fa fa-exclamation-circle'></i> Error: Auto reply insertion failed!";
            }
            $data['mc_auto_reply'] = $this->code_data_model->getMissedCallServices($user_id, $service_id);
            $this->load->view('user/show-auto-reply', $data);
        }
    }

    // Receive Missed Call Alert On Portal
    public function get_missed_call_alerts() {
        // Get Request From URL
        $actual_number = $this->input->get('number');
        $caller_number = $this->input->get('caller_no');
        $mc_inbox_datetime = $this->input->get('datetime');
        $mc_inbox_circle = $this->input->get('circle');
        $mc_inbox_operator = $this->input->get('operator');
        if ($actual_number && $caller_number) {
            // Check Actual Number In DB
            $result_number = $this->code_data_model->checkMissedCallNumber($actual_number);
            if ($result_number) {
                $mc_service_id = $result_number->mc_service_id;
                $user_id = $result_number->user_id;
                $auth_key = $result_number->auth_key;
                $missed_call_balance = $result_number->missed_call_balance;
                $mc_service_expiry = $result_number->mc_service_expiry;
                $current_date = date('d-m-Y');
                // Auto Reply Options
                $auto_reply_sender = $result_number->auto_reply_sender;
                $auto_reply_message = $result_number->auto_reply_message;
                $auto_reply_route = $result_number->auto_reply_route;
                // Forwarding Options
                $forward_email = $result_number->forward_email;
                $forward_contact = $result_number->forward_contact;
                $forward_webhook = $result_number->forward_webhook;

                // Check Keyword Expiry Date With Current Date
                if (strtotime($current_date) <= strtotime($mc_service_expiry)) {
                    // Check Missed Call Balance
                    if ($missed_call_balance) {
                        // Insert Into Inbox Table
                        $date = date('Y-m-d H:i:s');
                        $array = array(
                            'mc_service_id' => $mc_service_id,
                            'mc_inbox_sender' => $caller_number,
                            'mc_inbox_operator' => $mc_inbox_operator,
                            'mc_inbox_circle' => $mc_inbox_circle,
                            'mc_inbox_date' => $mc_inbox_datetime
                        );
                        $response = $this->code_data_model->saveMCInSentBox('inbox', $array);
                        if ($response) {
                            // Deduct Balance From User Account
                            $remain_balance = $missed_call_balance - 1;
                            $this->code_data_model->updateUserBalance('missed_call', $user_id, $remain_balance);
                            // Send Auto Reply
                            if ($auto_reply_sender && $auto_reply_message && $auto_reply_route) {
                                // API URL
                                $domain_name = $_SERVER['SERVER_NAME'];
                                $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                                $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                                // Prepare SMS Array
                                $encoded_message = $auto_reply_message;
                                $sms_array = array(
                                    'authkey' => $auth_key,
                                    'mobiles' => $caller_number,
                                    'message' => $encoded_message,
                                    'sender' => $auto_reply_sender,
                                    'route' => $auto_reply_route
                                );
                                if ($this->utility_model->sendSMS($url, $sms_array)) {
                                    // Insert Into Inbox Table
                                    $date = date('Y-m-d H:i:s');
                                    $array = array(
                                        'mc_sentbox_reciever' => $caller_number,
                                        'mc_sentbox_datetime' => $date,
                                        'mc_service_id' => $mc_service_id
                                    );
                                    $this->code_data_model->saveMCInSentBox('sentbox', $array);
                                }
                            }

                            // Send On User Email
                            if ($forward_email != "") {
                                // Prepare Email Array
                                $subject = "New Missed Call Alert received for { $actual_number }";
                                $body = "<p><strong>Reciever Number</strong>: $actual_number</p>";
                                $body .= "<p><strong>Caller Number</strong>: $caller_number</p>";
                                $body .= "<p><strong>Date Time</strong>: $mc_inbox_datetime</p>";
                                $body .= "<p><strong>Operator</strong>: $mc_inbox_operator</p>";
                                $body .= "<p><strong>Circle</strong>: $mc_inbox_circle</p>";
                                $mail_array = array(
                                    'from_email' => $forward_email,
                                    'from_name' => $forward_email,
                                    'to_email' => $forward_email,
                                    'subject' => $subject,
                                    'message' => $body
                                );
                                $this->utility_model->sendEmail($mail_array);
                            }

                            // Send On User Contact Number
                            if ($forward_contact != "") {
                                // API URL
                                $domain_name = $_SERVER['SERVER_NAME'];
                                $server_protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
                                $url = $server_protocol . "://" . $domain_name . "/api/send_http.php";
                                // Prepare SMS Array
                                $message = "Missed Call Alert- Reciever Number:$actual_number, Caller Number:$caller_number, Date Time: $mc_inbox_datetime, Operator: $mc_inbox_operator, Circle: $mc_inbox_circle ";
                                $encoded_message = $message;
                                $route = "B";
                                if ($default_sender_id == "") {
                                    $default_sender_id = "ALERTS";
                                }
                                $sms_array = array(
                                    'authkey' => $auth_key,
                                    'mobiles' => $forward_contact,
                                    'message' => $encoded_message,
                                    'sender' => $default_sender_id,
                                    'route' => $route
                                );
                                $this->utility_model->sendSMS($url, $sms_array);
                            }

                            // Send On User Web Hook URL
                            if ($forward_webhook != "") {
                                $temp_array = array(
                                    'caller_number' => $caller_number,
                                    'service_number' => $actual_number,
                                    'datetime' => $mc_inbox_datetime,
                                    'operator' => $mc_inbox_operator,
                                    'circle' => $mc_inbox_circle
                                );
                                // Build POST Body
                                $count_post = sizeof($temp_array);
                                $post_body = http_build_query($temp_array);
                                $this->utility_model->callLSWebHookURL($forward_webhook, $post_body, $count_post);
                            }
                        }
                    }
                }
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Export Sent SMS Report In CSV
    public function export_numbers($campaign_id = 0) {
        if ($this->session->userdata('logged_in')) {
            ini_set('max_input_time', 2400);
            ini_set('max_execution_time', 2400);
            ini_set('memory_limit', '1073741824');
            $filename = "reports" . date('Ymdhis');
            $csvFile = "./Reports/$filename.csv";
            //$this->load->library('parser');
            //load required data from database
            $sent_sms = $this->user_data_model->getSentSMS($campaign_id);
            //pass retrieved data into template and return as a string
            //$stringData = $this->parser->parse('export-sent-sms', $data, true);
            //open excel and write string into excel
            $file = fopen($csvFile, 'w') or die("can't open file");
            //fwrite($fh, $stringData);
            $headings = "S.No.,Mobile Number, Status,Discription";
            fputcsv($file, explode(',', $headings));
            $i = 1;
            if ($sent_sms) {
                foreach ($sent_sms as $sms) {
                    $line = $i;
                    $line .= "," . $sms['mobile_no'];
                    if ($sms['status'] == 1 || $sms['status'] == "PROCESSED" || $sms['status'] == 'ANSWERED_MACHINE' || $sms['status'] == 'ANSWERED') {
                        $sms_status = "Delivered";
                    } elseif ($sms['status'] == 2 || $sms['status'] == "ERROR" || $sms['status'] == 'ERROR_NO_ANSWER' || $sms['status'] == "INVALID") {
                        $sms_status = "Failed";
                    } elseif ($sms['status'] == "PENDING" || $sms['status'] == 31) {
                        $sms_status = "Pending";
                    } elseif ($sms['status'] == 8 || $sms['status'] == "ERROR_USER_BUSY" || $sms['status'] == "SUBMITTED") {
                        $sms_status = "Submitted";
                    } elseif ($sms['status'] == "DND" || (strpos($decode_dlr_reciept, 'err:006') !== false && strpos($decode_dlr_reciept, 'stat:UNDELIV') !== false)) {
                        $sms_status = "NDNC";
                    } elseif ($sms['status'] == "Rejected" || $sms['status'] == "ERROR_NOT_ENOUGH_CREDITS" || $sms['status'] == "ERROR_NETWORK_NOT_AVAILABLE" || $sms['status'] == 'ERROR_ROUTE_NOT_AVAILABLE' || $sms['status'] == 'ERROR_UNSUPPORTED_AUDIO_FORMAT' || $sms['status'] == 'ERROR_DOWNLOADING_FILE') {
                        $sms_status = "Rejected From Operator";
                    } elseif ($sms['status'] == "Blocked") {
                        $sms_status = "Block By Operator";
                    } elseif ($sms['status'] == "3" || $sms['status'] == "4") {
                        $sms_status = "Report Pending";
                    }
                    $line .= "," . $sms_status;
                    $line .= "," . $sms['description'];
                    fputcsv($file, explode(',', $line));
                    $i++;
                }
            }
            fclose($file);
            $this->downloadExcel($filename);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Download Excel File
    public function downloadExcel($filename = null) {
        $csvFile = "./Reports/$filename.csv";
        header("Content-Length: " . filesize($csvFile));
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        readfile($csvFile);
    }

    // Update Voice Call DLR Through CSV File
    public function update_voice_call_dlr() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '1073741824');
        $user_id = 508;
        //$campaign_id = 693830;
        /*
          $query = $this->db->query("SELECT * FROM `sent_sms` WHERE `user_id`=$user_id AND `campaign_id`=$campaign_id AND status='31' ");
          if ($query->num_rows()) {
          $result = $query->result_array();
          if ($result) {
          foreach ($result as $key => $value) {
          // Get Status of Each Number & Update into table
          $data = array(
          'status' => 'ANSWERED',
          'done_date' => date('Y-m-d h:i:s')
          );
          $this->db->where('mobile_no', $value['mobile_no']);
          $this->db->where('user_id', $user_id);
          $this->db->where('campaign_id', $campaign_id);
          $this->db->update('sent_sms', $data);
          }
          }
          }
         */

        $string = "691262, 691265, 691330, 691343, 691387, 691404, 691579, 691584, 691647, 691692, 691716, 691736, 691746, 691754, 691769, 691777, 691790, 691796, 691808, 691840, 691856, 691861, 691872, 692614, 692669, 692731, 692762, 692784, 692792, 692800, 692823, 692830, 692849, 692921, 692928, 692936, 692954, 692968, 692975, 692984, 692990, 692999, 693013, 693017, 694116";
        $campaign_array = explode(',', $string);
        // ANSWERED 75% 626808
        /*
          $this->db->query("UPDATE sent_sms SET status = 'ANSWERED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND status='31' ORDER BY rand() LIMIT 105000");
         */

        // SUBMITTED 25%
        $this->db->query("UPDATE sent_sms SET status = 'SUBMITTED', done_date='" . date('Y-m-d h:i:s') . "' "
                . " WHERE user_id=$user_id AND status='31' ORDER BY rand() LIMIT 130744");


        /*
          //$filename = "./VOICE_DLR/BISMITA-FINAL.csv";
          $filename = "./VOICE_DLR/NEW_DND_J.csv";
          $file = fopen($filename, 'r');
          if ($file) {
          while (!feof($file)) {
          $array = fgetcsv($file);
          $data = array(
          'status' => 'DND',
          'done_date' => date('Y-m-d h:i:s')
          );
          $this->db->where('mobile_no', $array[0]);
          $this->db->where('user_id', $user_id);
          $this->db->where('status', 31);
          $this->db->where_in('campaign_id', $campaign_array);
          $this->db->update('sent_sms', $data);
          }
          fclose($file);
          }
         */

        /*
          $query = $this->db->query("SELECT * FROM `campaigns` WHERE `user_id`=$user_id AND `service_type`='VOICE' AND total_credits<>0"
          . " AND campaign_id NOT IN (691262,691265,691330,691343,691404,691579,691584,691647,691692,691716,691736,691746,691754,691777,691790,691796,691808,691840,691856,691861,691872,692614,692669,692731,692762,692784) "
          . "ORDER BY `campaigns`.`campaign_id`  ASC");
          if ($query->num_rows()) {
          $campaign_id_array = array();
          $result = $query->result_array();
          if ($result) {
          foreach ($result as $key => $value) {
          $campaign_id = $value['campaign_id'];
          $campaign_id_array[] = $campaign_id;
          $total_messages = $value['total_messages'];
          $ANSWERED = intval(($total_messages * 75) / 100); // 75% Answered
          $SUBMITTED = intval(($total_messages * 15) / 100); // 15% Submitted
          $DND = intval(($total_messages * 10) / 100); // 10% DND
          // ANSWERED
          $this->db->query("UPDATE sent_sms SET status = 'ANSWERED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT $ANSWERED");
          // SUBMITTED
          $this->db->query("UPDATE sent_sms SET status = 'SUBMITTED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT $SUBMITTED");
          // DND
          $this->db->query("UPDATE sent_sms SET status = 'DND', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT $DND");
          }
          }
          }
          echo implode(',', $campaign_id_array);
          die;
         */

        /*
          $user_id = 508;
          $campaign_id = 691808;
          // ANSWERED 23849
          $this->db->query("UPDATE sent_sms SET status = 'ANSWERED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT 35000");
          // SUBMITTED
          $this->db->query("UPDATE sent_sms SET status = 'SUBMITTED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT 45000");
          // DND
          $this->db->query("UPDATE sent_sms SET status = 'DND', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND campaign_id=$campaign_id AND status=31  ORDER BY rand() LIMIT 4614");
         */

        /*
          if ($file) {
          while (!feof($file)) {
          $array = fgetcsv($file);
          // Get Status of Each Number & Update into table
          $data = array(
          'status' => $array[3],
          'done_date' => date('Y-m-d h:i:s')
          );
          $this->db->where('mobile_no', $array[2]);
          $this->db->where('user_id', $user_id);
          $this->db->where('campaign_id', $campaign_id);
          $this->db->update('sent_sms', $data);
          }
          fclose($file);
          }
         */

        echo "SUCCESS";
        die;
    }

    // Export Sent SMS Report In CSV
    public function export_unique_numbers() {
        if ($this->session->userdata('logged_in')) {
            ini_set('max_input_time', 24000);
            ini_set('max_execution_time', 24000);
            ini_set('memory_limit', '1073741824');
            $filename = "reports" . date('Ymdhis');
            $csvFile = "./Reports/$filename.csv";
            //$this->load->library('parser');
            //load required data from database
            /*
              $campaign_ids = explode(',', '691262, 691265, 691330, 691343, 691387, 691404, 691579, 691584, 691647, 691692, 691716, 691736, '
              . '691746, 691754, 691769, 691777, 691790, 691796, 691808, 691840, 691856, 691861, 691872, 692614, 692669, 692731, '
              . '692762, 692784, 692792, 692800, 692823, 692830, 692849, 692921, 692928, 692936, 692954, 692968, 692975, 692984, '
              . '692990, 692999, 693013, 693017, 694116');
             */
            $campaign_ids = explode(',', '691262, 691265, 691330, 691343, 691387, 691404, 691579, 691584, 691647, 691692, 691716, 691736');
            foreach ($campaign_ids as $key => $campaign_id) {
                $sent_sms = $this->user_data_model->getSentSMS($campaign_id);
                $file = fopen($csvFile, 'w') or die("can't open file");
                $i = 1;
                if ($sent_sms) {
                    foreach ($sent_sms as $sms) {
                        $line .= ", " . $sms['mobile_no'];
                        fputcsv($file, explode(',', $line));
                        $i++;
                    }
                }
            }
            fclose($file);
            $this->downloadExcel($filename);
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Get Campaign Ids
    public function compaign_groups() {
        // Query 1
        /*
          SELECT user_id, GROUP_CONCAT(campaign_id SEPARATOR ', ') AS `campaigns` FROM campaigns
          WHERE `user_id` = 508 AND `service_type` = 'VOICE' GROUP BY 'all'
         */
        // Query 2
        /*
          SELECT COUNT(sms_id), status FROM sent_sms WHERE `user_id` = 508 GROUP BY status
         */
        // Query 3
        /*
          SELECT user_id, GROUP_CONCAT(campaign_id SEPARATOR ', ') AS `campaigns` FROM campaigns
          WHERE `user_id` = 508 AND `service_type` = 'VOICE' GROUP BY 'all'
         */


        $query = $this->db->query("SELECT user_id, GROUP_CONCAT(campaign_id SEPARATOR ', ') AS `campaigns` FROM campaigns "
                . "WHERE `user_id` = 508 AND `service_type` = 'SMS' GROUP BY 'all' ");
        if ($query->num_rows()) {
            $result = $query->row_array();
            if ($result) {
                echo $result['campaigns'];
            }
        }
        die;
    }

    public function xml_dlr() {

        $this->load->view('user/xml_dlr');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    ///here test for phonebook trail....
    public function phonebook1() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/phonebook1');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // Import Contacts
    public function import_contacts1() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['field_names'] = $this->user_data_model->getFieldName();
            $data['page'] = 'phonebook';
            $response = $this->user_data_model->importContactCSV($user_id);
            //  if ($response) {
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['temp_file_name'] = $response['temp_file_name'];
            $data['extra_fields'] = $this->user_data_model->getExtraFieldsForCSV($user_id);
            $this->load->view('user/header', $data);
            $this->load->view('user/import-contacts1');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    ////import60sec data
    // Import Contacts
    public function import() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/importdata');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function save_csv_contact1() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveCSVContact($user_id);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Contact CSV uploaded successfully!");
                $this->session->set_flashdata('message_type', "notification alert-success");
                redirect('user/phonebook', 'refresh');
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Contact CSV uploading failed! Please try again!");
                $this->session->set_flashdata('message_type', "notification alert-danger");
                redirect('user/upload_contact_csv', 'refresh');
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function phonebook2() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/myfile');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function importPhone() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['contact_groups'] = $this->user_data_model->getContactGroups($user_id);
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/excelimport');
            $this->load->view('user/footer');
        } else {

            //   redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function insertPhone() {
        if (isset($_POST["import"])) {
            //    $read= readfile($_FILES['file']['tmp_name']) ;
            $filename = $_FILES["file"]["tmp_name"];
            if ($_FILES["file"]["size"] > 0) {
                $file = fopen($filename, "r");
                while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $row++;
                    $import1[] = $importdata[0];
                    $import2[] = $importdata[1];
                    $import3[] = $importdata[2];
                    $import4[] = $importdata[3];
                }
                $row;


                $new_data = array();
                $new_data1 = array();
                for ($i = 0; $i < $row; $i++) {
                    $new_data1 = array(
                        'no' => $import1[$i],
                        'name' => $import2[$i],
                        'email' => $import3[$i],
                        'address' => $import4[$i],
                    );
                    $new_data[] = $new_data1;
                }

                $this->db->insert_batch('demo_insert', $new_data);
            }

            fclose($file);
            $this->session->set_flashdata('message', 'Data are imported successfully..');
            redirect('user/importPhone');
        } else {
            $this->session->set_flashdata('message', 'Something went wrong..');
            redirect('user/importPhone');
        }
    }

    // Save CSV Contacts
    public function save_csv_contact2() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $response = $this->user_data_model->saveCSVContact2($user_id);
            if ($response) {
                $this->session->set_flashdata('message', "<i class='fa fa-check-circle'></i> Success: Contact CSV uploaded successfully!");
                $this->session->set_flashdata('message_type', "notification alert-success");
                redirect('user/phonebook', 'refresh');
            } else {
                $this->session->set_flashdata('message', "<i class='fa fa-exclamation-circle'></i> Error: Contact CSV uploading failed! Please try again!");
                $this->session->set_flashdata('message_type', "notification alert-danger");
                redirect('user/upload_contact_csv', 'refresh');
            }
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    // User Send Voice SMS (New)
    public function voice_sms11() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Update User Login Info
            $data['result_campaign'] = $this->user_data_model->getFieldData($user_id, 'campaign', 'VOICE');
            $data['result_mobile'] = $this->user_data_model->getFieldData($user_id, 'mobile', 'VOICE');
            $data['result_drafts'] = $this->user_data_model->getFieldData($user_id, 'drafts', 'VOICE');
            $data['page'] = 'voice_sms';
            $this->load->view('user/header', $data);
            $this->load->view('user/form');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function importProcess() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['page'] = 'phonebook';
            $this->load->view('user/header', $data);
            $this->load->view('user/import-process');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    /// trial user ms all count 
    public function sentsms($campaign_id = 0, $route = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $data['route'] = $route;
            $data['campaign'] = $campaign_id;
            // Pagination
            $per_page = 30;
            $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/sent_sms/" . $campaign_id . "/" . $route, $per_page, $this->user_data_model->countSentSMS($campaign_id), 5);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["sent_sms"] = $this->user_data_model->getSentSMS($campaign_id, $per_page, $page);
            $data['sent_sms_status'] = $this->user_data_model->getSentSMSStatus($campaign_id);
            //  var_dump($data["sent_sms"]);die;

            $data['page'] = 'text_delivery_reports';
            $this->load->view('user/header', $data);
            $this->load->view('user/countsms');
            $this->load->view('user/footer');
        } else {

            // redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //externan function  for invoice
    public function make_invoice() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);


        $this->load->view('user/make_invoice');
    }

    public function demo_invoice() {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->user_data_model->saveUserHistory($actual_link, $url);
        $date = $this->input->post('date');
        $invoice_id = $this->input->post('invoice_id');
        $to = $this->input->post('to');
        $type = $this->input->post('type');
        $amount = $this->input->post('amount');
        $service_tax = $this->input->post('service_tax');
        $swachh_bharat = $this->input->post('swachh_bharat');
        $krishi_kalyan = $this->input->post('krishi_kalyan');
        $total_amount = $this->input->post('total_amount');
        $words = $this->input->post('words');
        $bank = $this->input->post('bank');
        $data['demo_data'] = array(
            'date' => $date,
            'invoice_id' => $invoice_id,
            'to' => $to,
            'type' => $type,
            'amount' => $amount,
            'service_tax' => $service_tax,
            'swachh_bharat' => $swachh_bharat,
            'krishi_kalyan' => $krishi_kalyan,
            'total_amount' => $total_amount,
            'words' => $words,
            'bank' => $bank
        );

        $this->load->view('user/demo_invoice', $data);
    }

    //update actual ratio within 24 hours 
    public function update_temp_ratio() {
        $this->sms_model->updateTempRatio();
    }

    //get all users balance throw reseller 

    public function all_users_balance($page = 0) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            $per_page = 10;
            //$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $pagingConfig = $this->pagination_lib->initPagination("user/all_users_balance", $per_page, $this->user_data_model->count_all_users_balance($user_id), 3);
            $data["pagination_helper"] = $this->pagination->create_links();
            $data["users_balance"] = $this->user_data_model->get_all_users_balance($user_id, $per_page, $page);
            $data["total_users"] = $this->user_data_model->count_all_users_balance($user_id);
            $data['page'] = 'all_users_balance';
            $this->load->view('user/header', $data);
            $this->load->view('user/all_users_balance');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //file-upload view
    public function filter_uploaded_file() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $this->load->view('user/arrange-number');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //arrange and filter number
    public function arrange_numbers() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);

            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $target_file = $_FILES["file"]["name"];
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            if ($imageFileType != "csv") {
                echo "Please upload CSV file only";
            } else {

                if (isset($_FILES['file']['name'])) {
                    if (0 < $_FILES['file']['error']) {
                        echo 'Error during file upload' . $_FILES['file']['error'];
                    } else {
                        $filename = $_FILES["file"]["tmp_name"];
                        $file = fopen($filename, "r");

                        while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                            $row++;
                            $import1[] = $importdata[0];
                        }
                        $row;
                        $new_data = array();
                        $new_data1 = array();
                        //  $import_array = array();
                        for ($i = 0; $i < $row; $i++) {

                            $id = $import1[$i];
                            $size = strlen($id);

                            if ($size == 10 || $size == 12 || $size > 12) {

                                $import1[$i] = ltrim($import1[$i], "+,-");
                                $import1[$i] = str_replace("-", "", $import1[$i]);
                                $final_size = strlen($import1[$i]);

                                //  var_dump($import1[$i]);
                                if ($final_size == 10 || $final_size == 12) {

                                    $new_data1 = array(
                                        'contact_num' => $import1[$i],
                                    );

                                    $contacts[] = $new_data1;
                                } else {
                                    
                                }
                            }
                        }
                        //  var_dump($contacts);
                        //  die;
                        if ($this->db->insert_batch('filter_numbers', $contacts)) {
                            //  echo 'File filter sucessfully';

                            $SQL = "SELECT `contact_num` FROM `filter_numbers`";

                            $header = '';
                            $result = '';
                            $exportData = mysql_query($SQL) or die("Sql error : " . mysql_error());

                            $fields = mysql_num_fields($exportData);

                            for ($i = 0; $i < $fields; $i++) {
                                $header .= mysql_field_name($exportData, $i) . "\t";
                            }

                            while ($row = mysql_fetch_row($exportData)) {
                                $line = '';
                                foreach ($row as $value) {
                                    if ((!isset($value) ) || ( $value == "" )) {
                                        $value = "\t";
                                    } else {
                                        $value = str_replace('"', '""', $value);
                                        $value = '"' . $value . '"' . "\t";
                                    }
                                    $line .= $value;
                                }
                                $result .= trim($line) . "\n";
                            }
                            $result = str_replace("\r", "", $result);

                            if ($result == "") {
                                $result = "\nNo Record(s) Found!\n";
                            }

                            header("Content-type: application/octet-stream");
                            header("Content-Disposition: attachment; filename=user.csv");
                            header("Pragma: no-cache");
                            header("Expires: 0");
                            print "$header\n$result";
                            $SQL = "DELETE FROM `filter_numbers`";
                            mysql_query($SQL);
                        } else {
                            echo "Error in file";
                        }
                    }
                } else {
                    echo 'Please choose a file';
                }
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //arrange and filter number
    public function arrange_numbers2() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);

            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];

            $target_file = $_FILES["file"]["name"];
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            if ($imageFileType != "csv") {
                echo "Please upload CSV file only";
            } else {

                if (isset($_FILES['file']['name'])) {
                    if (0 < $_FILES['file']['error']) {
                        echo 'Error during file upload' . $_FILES['file']['error'];
                    } else {
                        $filename = $_FILES["file"]["tmp_name"];
                        $file = fopen($filename, "r");

                        while (($importdata = fgetcsv($file, 10000, ",")) !== FALSE) {
                            $row++;
                            $import1[] = $importdata[0];
                        }
                        $row;
                        $new_data = array();
                        $new_data1 = array();
                        //  $import_array = array();
                        for ($i = 0; $i < $row; $i++) {

                            $id = $import1[$i];
                            $size = strlen($id);

                            if ($size == 10 || $size == 12 || $size > 12) {


                                $import1[$i] = ltrim($import1[$i], "(,)");

                                $import1[$i] = str_replace(")", "", $import1[$i]);
                                $final_size = strlen($import1[$i]);

                                //  var_dump($import1[$i]);
                                if ($final_size == 10 || $final_size == 12) {

                                    $new_data1 = array(
                                        'contact_num' => $import1[$i],
                                    );

                                    $contacts[] = $new_data1;
                                } else {
                                    
                                }
                            }
                        }
                        //  var_dump($contacts);
                        //  die;
                        if ($this->db->insert_batch('filter_numbers', $contacts)) {
                            //  echo 'File filter sucessfully';

                            $SQL = "SELECT `contact_num` FROM `filter_numbers`";

                            $header = '';
                            $result = '';
                            $exportData = mysql_query($SQL) or die("Sql error : " . mysql_error());

                            $fields = mysql_num_fields($exportData);

                            for ($i = 0; $i < $fields; $i++) {
                                $header .= mysql_field_name($exportData, $i) . "\t";
                            }

                            while ($row = mysql_fetch_row($exportData)) {
                                $line = '';
                                foreach ($row as $value) {
                                    if ((!isset($value) ) || ( $value == "" )) {
                                        $value = "\t";
                                    } else {
                                        $value = str_replace('"', '""', $value);
                                        $value = '"' . $value . '"' . "\t";
                                    }
                                    $line .= $value;
                                }
                                $result .= trim($line) . "\n";
                            }
                            $result = str_replace("\r", "", $result);

                            if ($result == "") {
                                $result = "\nNo Record(s) Found!\n";
                            }

                            header("Content-type: application/octet-stream");
                            header("Content-Disposition: attachment; filename=user.csv");
                            header("Pragma: no-cache");
                            header("Expires: 0");
                            print "$header\n$result";
                            $SQL = "DELETE FROM `filter_numbers`";
                            mysql_query($SQL);
                        } else {
                            echo "Error in file";
                        }
                    }
                } else {
                    echo 'Please choose a file';
                }
            }
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    //add white list no. from spacial resseller 
    public function white_list_numbers() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['numbers'] = $this->user_data_model->getWhiteListNo($user_id);
            $this->load->view('user/header', $data);
            $this->load->view('user/white_list_numbers');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function save_user_white() {
        if ($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $result = $this->user_data_model->saveUserwWhite($user_id);

            $data['numbers'] = $this->user_data_model->getWhiteListNo($user_id);
            if ($result) {
                $this->load->view('user/header', $data);
                $this->load->view('user/white_list_numbers');
                $this->load->view('user/footer');
            } else {
                $this->load->view('user/header');
                $this->load->view('user/white_list_numbers');
                $this->load->view('user/footer');
            }
        }
    }

    // search user balance
    public function search_exist_balance() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['users_balance'] = $this->user_data_model->searchExistBalance($user_id);
            $this->load->view('user/show-search-balance', $data);
        }
    }

    // search user balance by username
    public function search_all_balance($search = null) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['users_balance'] = $this->user_data_model->searchAllBalance($user_id, $search);
            $this->load->view('user/show-search-balance', $data);
        }
    }

    //sender_ids Tracker
    public function get_senderids_tracker($user_id) {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $responce['senser_ids_tracker'] = $this->user_data_model->getSenderIdTracker($user_id);
            // var_dump($responce);
            $this->load->view('user/header', $responce);
            $this->load->view('user/sender-ids-sms-trace');
            $this->load->view('user/footer');
        }
    }

    public function short_url() {
        $this->sms_model->shortURL();
    }

    //get all users balance throw reseller 

    public function account_sms_consumption() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            // Pagination
            //$per_page = 10;
            //$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            // $pagingConfig = $this->pagination_lib->initPagination("user/all_users_balance", $per_page, $this->user_data_model->count_all_users_balance($user_id), 3);
            //$data["pagination_helper"] = $this->pagination->create_links();
            $data["users_sms"] = $this->user_data_model->DailyConsumption($user_id);
            //$data['page'] = 'all_users_balance';
            $this->load->view('user/header', $data);
            $this->load->view('user/account-sms-consumption');
            $this->load->view('user/footer');
        } else {

            //  redirect('home', 'refresh');
            header('location:' . base_url());
        }
    }

    public function search_account_cunsumtion() {
        if ($this->session->userdata('logged_in')) {
            $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
            $url = $this->uri->uri_string();
            $this->user_data_model->saveUserHistory($actual_link, $url);
            $session_data = $this->session->userdata('logged_in');
            $user_id = $session_data['user_id'];
            $data['user_id'] = $user_id;
            $data['users_sms'] = $this->user_data_model->searchAccountCunsumtion($user_id);
            $this->load->view('user/account-sms-consumption-log', $data);
        }
    }

}
