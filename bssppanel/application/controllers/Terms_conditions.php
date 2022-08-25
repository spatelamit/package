<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Terms_Conditions extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('login_model', '', TRUE);
    }

    // Terms & Conditions
    function index() {
        $domain = $_SERVER['HTTP_HOST'];
        $domain = explode('.', $domain);
        $domain = array_reverse($domain);
        $domain = "$domain[1].$domain[0]";
        $data['main_domain'] = $domain;
        $domain_name = $_SERVER['SERVER_NAME'];
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
            $rgba = $this->utility_model->Hex2RGB($result_web['website_theme_color'], 0.6);
            $data['website_theme_color'] = $rgba;
            $this->load->view('user/terms-conditions', $data);
        } else {
            $this->load->view('user/terms-conditions');
        }
    }

}

?>