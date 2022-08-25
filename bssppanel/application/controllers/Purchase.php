<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
        $this->load->model('Admin_Data_Model', 'admin_data_model');
    }

    // Purchase Service
    public function index() {
        $domain = $_SERVER['HTTP_HOST'];
        if ($domain == 'localhost' || $domain == '192.168.1.231' || $domain == 'sms.bulksmsserviceproviders.com' || $domain == 'bulk.bulk24sms.com') {
            // Website Info
            if ($domain == 'localhost' || $domain == '192.168.1.231') {
                $data['main_domain'] = $domain . "/BulkSMSAPP";
            } else {
                $domain = explode('.', $domain);
                $domain = array_reverse($domain);
                $domain = "$domain[1].$domain[0]";
                $data['main_domain'] = $domain;
            }
            $domain_name = $_SERVER['SERVER_NAME'];
            if ($domain_name == 'localhost') {
                $domain_name = "192.168.1.231";
            }
            $result_web = $this->login_model->getWebsiteInfo($domain_name);
            if ($result_web) {
                $data['website_id'] = $result_web['website_id'];
                $data['user_id'] = $result_web['user_id'];
                $data['company_name'] = $result_web['company_name'];
                $data['website_domain'] = $result_web['website_domain'];
                $data['company_logo'] = $result_web['company_logo'];
                $data['website_theme'] = $result_web['website_theme'];
                $data['website_features'] = $result_web['website_features'];
                $data['website_clients'] = $result_web['website_clients'];
                $data['website_about_image'] = $result_web['website_about_image'];
                $data['website_about_contents'] = $result_web['website_about_contents'];
                $data['website_social_links'] = $result_web['website_social_links'];
                $data['website_emails'] = $result_web['website_emails'];
                $data['website_addresses'] = $result_web['website_addresses'];
                $data['website_cities'] = $result_web['website_cities'];
                $data['website_zipcodes'] = $result_web['website_zipcodes'];
                $data['website_countries'] = $result_web['website_countries'];
                $data['website_contacts'] = $result_web['website_contacts'];
                $data['website_banner'] = $result_web['website_banner'];
                $data['website_service1'] = $result_web['website_service1'];
                $data['website_service2'] = $result_web['website_service2'];
                $data['website_service3'] = $result_web['website_service3'];
                $data['reciever_email'] = $result_web['reciever_email'];
                $this->load->view('user/payment', $data);
            } else {
                $this->load->view('user/payment');
            }
        } else {
            $this->load->view('user/invalid');
        }
    }

    // Calculate Price/SMS And Total Amount
    public function calculate_total_amount() {
        $service_type = $this->input->post('service_type');
        $no_of_sms = $this->input->post('no_of_sms');
        $response = $this->login_model->calculateTotalAmount($service_type, $no_of_sms);
        if ($response) {
            header('Content-Type: application/json');
            echo json_encode($response);
            die;
        }
    }

    // Add To Cart Selected Services
    public function add_to_cart() {
        $service_type = $this->input->post('service_type');
        $no_of_sms = $this->input->post('no_of_sms');
        $response = $this->login_model->calculateTotalAmount($service_type, $no_of_sms);
        if ($response) {
            $total_include_tax = $response['total'] + (($response['total'] * $response['tax']) / 100);
            $session_array = array(
                'price_per_sms' => $response['price'],
                'total_amount' => $response['total'],
                'tax' => $response['tax'],
                'total_sms' => $no_of_sms,
                'service_type' => $service_type,
                'total_include_tax' => $total_include_tax
            );
            $this->session->set_userdata('shopping_cart', $session_array);
            redirect('purchase/cart_summary', 'refresh');
        } else {
            $this->session->set_flashdata('message', 'Error: Something wrong! Please try again later!');
            $this->session->set_flashdata('message_type', 'alert-danger danger');
            redirect('purchase', 'refresh');
        }
    }

    // View Cart Summary
    public function cart_summary() {
        if ($this->session->userdata('shopping_cart')) {
            $domain = $_SERVER['HTTP_HOST'];
            if ($domain == 'localhost' || $domain == '192.168.1.231' || $domain == 'sms.bulksmsserviceproviders.com' || $domain == 'bulk.bulk24sms.com') {
                // Website Info
                if ($domain == 'localhost' || $domain == '192.168.1.231') {
                    $data['main_domain'] = $domain . "/BulkSMSAPP";
                } else {
                    $domain = explode('.', $domain);
                    $domain = array_reverse($domain);
                    $domain = "$domain[1].$domain[0]";
                    $data['main_domain'] = $domain;
                }
                $domain_name = $_SERVER['SERVER_NAME'];
                if ($domain_name == 'localhost') {
                    $domain_name = "192.168.1.231";
                }
                $result_web = $this->login_model->getWebsiteInfo($domain_name);
                if ($result_web) {
                    $data['website_id'] = $result_web['website_id'];
                    $data['user_id'] = $result_web['user_id'];
                    $data['company_name'] = $result_web['company_name'];
                    $data['website_domain'] = $result_web['website_domain'];
                    $data['company_logo'] = $result_web['company_logo'];
                    $data['website_theme'] = $result_web['website_theme'];
                    $data['website_features'] = $result_web['website_features'];
                    $data['website_clients'] = $result_web['website_clients'];
                    $data['website_about_image'] = $result_web['website_about_image'];
                    $data['website_about_contents'] = $result_web['website_about_contents'];
                    $data['website_social_links'] = $result_web['website_social_links'];
                    $data['website_emails'] = $result_web['website_emails'];
                    $data['website_addresses'] = $result_web['website_addresses'];
                    $data['website_cities'] = $result_web['website_cities'];
                    $data['website_zipcodes'] = $result_web['website_zipcodes'];
                    $data['website_countries'] = $result_web['website_countries'];
                    $data['website_contacts'] = $result_web['website_contacts'];
                    $data['website_banner'] = $result_web['website_banner'];
                    $data['website_service1'] = $result_web['website_service1'];
                    $data['website_service2'] = $result_web['website_service2'];
                    $data['website_service3'] = $result_web['website_service3'];
                    $data['reciever_email'] = $result_web['reciever_email'];
                }
                $data['cart_summary'] = $this->session->userdata('shopping_cart');
                $this->load->view('user/cart-summary', $data);
            } else {
                $this->load->view('user/invalid');
            }
        } else {
            $this->session->set_flashdata('message', 'Error: First you have add services!');
            $this->session->set_flashdata('message_type', 'alert-danger danger');
            redirect('purchase', 'refresh');
        }
    }

    // Save User
    public function save_user() {
        if ($this->session->userdata('shopping_cart')) {
            $response = $this->login_model->saveBulkSMSUser();
            if ($response['status'] == '200') {
                $user_id = $response['last_user_id'];
                $user_info = $this->login_model->getUserInfo($user_id);
                $session_array = array(
                    'user_id' => $user_info->user_id,
                    'most_parent_id' => $user_info->most_parent_id,
                    'username' => $user_info->username,
                    'utype' => $user_info->utype
                );
                $this->session->set_userdata('logged_in', $session_array);
                redirect('purchase/checkout', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Error: ' . $response['message']);
                $this->session->set_flashdata('message_type', 'alert-danger danger');
                redirect('purchase/cart_summary', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'Error: First you have add services!');
            $this->session->set_flashdata('message_type', 'alert-danger danger');
            redirect('purchase', 'refresh');
        }
    }

    // Validate User
    public function validate_user() {
        if ($this->session->userdata('shopping_cart')) {
            $result_user = $this->login_model->userLogin();
            if ($result_user) {
                $session_array = array(
                    'user_id' => $result_user->user_id,
                    'most_parent_id' => $result_user->most_parent_id,
                    'username' => $result_user->username,
                    'name' => $result_user->name,
                    'email_address' => $result_user->email_address,
                    'contact_number' => $result_user->contact_number,
                    'utype' => $result_user->utype,
                    'address' => $result_user->address,
                    'city' => $result_user->city,
                    'country' => $result_user->country,
                    'zipcode' => $result_user->zipcode,
                    'login_place' => 'web_login'
                );
                $this->session->set_userdata('logged_in', $session_array);
                redirect('purchase/checkout', 'refresh');
            } else {
                $this->session->set_flashdata('message', 'Error: You entered wrong username or password!');
                $this->session->set_flashdata('message_type', 'alert-danger danger');
                redirect('purchase/cart_summary', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', 'Error: First you have to add services!');
            $this->session->set_flashdata('message_type', 'alert-danger danger');
            redirect('purchase', 'refresh');
        }
    }

    // Checkout Cart
    public function checkout() {
        if ($this->session->userdata('shopping_cart') && $this->session->userdata('logged_in')) {
            $domain = $_SERVER['HTTP_HOST'];
            if ($domain == 'localhost' || $domain == '192.168.1.231' || $domain == 'sms.bulksmsserviceproviders.com' || $domain == 'bulk.bulk24sms.com') {
                // Website Info
                if ($domain == 'localhost' || $domain == '192.168.1.231') {
                    $data['main_domain'] = $domain . "/BulkSMSAPP";
                } else {
                    $domain = explode('.', $domain);
                    $domain = array_reverse($domain);
                    $domain = "$domain[1].$domain[0]";
                    $data['main_domain'] = $domain;
                }
                $domain_name = $_SERVER['SERVER_NAME'];
                if ($domain_name == 'localhost') {
                    $domain_name = "192.168.1.231";
                }
                $result_web = $this->login_model->getWebsiteInfo($domain_name);
                if ($result_web) {
                    $data['website_id'] = $result_web['website_id'];
                    $data['user_id'] = $result_web['user_id'];
                    $data['company_name'] = $result_web['company_name'];
                    $data['website_domain'] = $result_web['website_domain'];
                    $data['company_logo'] = $result_web['company_logo'];
                    $data['website_theme'] = $result_web['website_theme'];
                    $data['website_features'] = $result_web['website_features'];
                    $data['website_clients'] = $result_web['website_clients'];
                    $data['website_about_image'] = $result_web['website_about_image'];
                    $data['website_about_contents'] = $result_web['website_about_contents'];
                    $data['website_social_links'] = $result_web['website_social_links'];
                    $data['website_emails'] = $result_web['website_emails'];
                    $data['website_addresses'] = $result_web['website_addresses'];
                    $data['website_cities'] = $result_web['website_cities'];
                    $data['website_zipcodes'] = $result_web['website_zipcodes'];
                    $data['website_countries'] = $result_web['website_countries'];
                    $data['website_contacts'] = $result_web['website_contacts'];
                    $data['website_banner'] = $result_web['website_banner'];
                    $data['website_service1'] = $result_web['website_service1'];
                    $data['website_service2'] = $result_web['website_service2'];
                    $data['website_service3'] = $result_web['website_service3'];
                    $data['reciever_email'] = $result_web['reciever_email'];
                }
                $data['cart_summary'] = $this->session->userdata('shopping_cart');
                $data['user_info'] = $this->session->userdata('logged_in');
                $this->load->view('user/checkout', $data);
            } else {
                $this->load->view('user/invalid');
            }
        } else {
            $this->session->set_flashdata('message', 'Error: First you have add services!');
            $this->session->set_flashdata('message_type', 'alert-danger danger');
            redirect('purchase', 'refresh');
        }
    }

    public function update_fake_sent() {

        date_default_timezone_set('Asia/Kolkata');

        $current_time = date('Y-m-d H:i:s');
        $start_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -120 minutes"));

        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -1 minutes"));
        $current_date = date('Y-m-d H:i:s');

        $this->db->select('`campaign_id`,`total_messages`');
        $this->db->from('campaigns');
        $this->db->where("`submit_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $this->db->where('total_messages > ', 100);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result_new) {
            $campaign_id = $result_new['campaign_id'];
            $total_sms = $result_new['total_messages'];
            $this->db->select('`campaign_id`,`status`,`temporary_status`');
            $this->db->from('sent_sms');
            $this->db->where('campaign_id', $campaign_id);
            $query_sent_sms = $this->db->get();
            $result_sent = $query_sent_sms->result_array();

            $fake_sent = 0;
            $sent_status = 0;
            foreach ($result_sent as $result_sent_sms) {
                $campaign_id = $result_sent_sms['campaign_id'];
                $status = $result_sent_sms['status'];
                $temporary_status = $result_sent_sms['temporary_status'];

                if ($temporary_status == 4) {
                    $fake_sent++;
                }
                if ($status == 3) {
                    $sent_status++;
                }
            }
            if ($fake_sent == $sent_status && $fake_sent != 0) {
                $fake_sent = $fake_sent / 2;
                $fake_sent = ROUND($fake_sent);
                echo $fake_sent . "<br>";
                echo $campaign_id . "<br>";
                $updateSent = array(
                    'status' => 1
                );
                $this->db->limit($fake_sent);
                $this->db->where('status', 3);
                $this->db->where('temporary_status', 4);
                $this->db->where('reseller_key_balance_status', 1);
                $this->db->where('campaign_id', $campaign_id);
                $this->db->update('sent_sms', $updateSent);
            }
        }
    }

    public function tps_report() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '107374182400');

        date_default_timezone_set('Asia/Kolkata');

        $current_time = date('Y-m-d H:i:s');
        $start_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -120 minutes"));
        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -1 minutes"));


        $this->db->select('user_group_name , user_group_id');
        $this->db->from('user_groups');
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result_new) {
            $user_group_id = $result_new['user_group_id'];
            $user_group_name = $result_new['user_group_name'];

            $this->db->select('`status`,`temporary_status`');
            $this->db->from('sent_sms');
            $this->db->where('user_group_id', $user_group_id);
            $this->db->where('reseller_key_balance_status', 1);
            $this->db->where("`submit_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $query_sent_sms = $this->db->get();
            if ($query_sent_sms->num_rows()) {
                $result_sent = $query_sent_sms->result_array();
                $data1 = array();
                $total_sms = 0;
                $delivered = 0;
                $failed = 0;
                $sent = 0;
                foreach ($result_sent as $result_sent_sms) {
                    $status = $result_sent_sms['status'];
                    $temporary_status = $result_sent_sms['temporary_status'];

                    if ($temporary_status == 1 && $status == 1) {
                        $delivered++;
                    }
                    if ($temporary_status == 1 && $status == 2) {
                        $failed++;
                    }
                    if ($temporary_status == 1 && $status == 3) {
                        $sent++;
                    }
                    $total_sms++;
                }
                $data1[] = array(
                    'user_group_id' => $user_group_id,
                    'from_time' => $start_date,
                    'to_time' => $end_date,
                    'actual_delivered' => $delivered,
                    'actual_sent' => $sent,
                    'actual_failed' => $failed,
                    'total_sms' => $total_sms
                );

                $this->db->insert_batch('tps_log', $data1);
            } else {
                
            }
        }
    }

    //Image Upload

    public function images_upload() {
        $this->load->view('user/image_upload');
    }

    public function uploads() {
        $config['upload_path'] = './Images';
        $config['allowed_types'] = 'jpg|png|jpeg|gif';
        $config['max_size'] = 204800000;
        $config['max_filename'] = 255;
        // $config['max_width'] = 1024;
        //$config['max_height'] = 600;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file_upload')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());

            $this->image_resize($data['upload_data']['full_path'], $data['upload_data']['file_name']);
            redirect('purchase/images_upload', 'refresh');
        }
    }

  
}

?>