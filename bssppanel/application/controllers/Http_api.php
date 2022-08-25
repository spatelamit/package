<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Http_Api extends REST_Controller {

    function __construct() {
        // Construct our parent class
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('user_data_model', '', TRUE);
        $this->load->model('login_model', '', TRUE);
        $this->load->model('api_model', '', TRUE);
        $this->load->model('sms_model', '', TRUE);
        $this->load->model('send_bulk_sms_model', '', TRUE);
        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }

    //====================================================================//
    // Basics API
    //====================================================================//
    // Get User Balance (Get & Post)
    function balance_get() {
        // Validate Parameters
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller::HTTP_BAD_REQUEST);
        }
        if ($this->get('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Route
        if (!$this->get('route')) {
            $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->get('route') == "") {
            $array = array('message' => 'Route can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $authkey = $this->get('authkey');
        $route = strtolower($this->get('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }
        $result_auth = $this->user_data_model->checkUserAuthKey($authkey);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status) {
                $result_api = $this->api_model->getUserBalance($user_id, $route);
                $this->response($result_api, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function balance_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Route
        if (!$this->input->post('route')) {
            $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('route') == "") {
            $array = array('message' => 'Route can not be null', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->post('authkey');
        $route = strtolower($this->input->post('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status) {
                $result_api = $this->api_model->getUserBalance($user_id, $route);
                $this->response($result_api, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Change User Password (Get & Post)
    function password_get() {
        // Validate Parameters 
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Current Password
        if (!$this->input->get('cpassword')) {
            $array = array('message' => 'Missing current password', 'code' => '103', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('cpassword') == "") {
            $array = array('message' => 'Current password can not be null', 'code' => '103', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // New Password
        if (!$this->input->get('npassword')) {
            $array = array('message' => 'Missing new password', 'code' => '104', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('npassword') == "") {
            $array = array('message' => 'New password can not be null', 'code' => '104', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Confirm New Password
        if (!$this->input->get('ncpassword')) {
            $array = array('message' => 'Missing confirm password', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('ncpassword') == "") {
            $array = array('message' => 'confirm password can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Check New Password & Confirm Password
        if ($this->input->get('npassword') != $this->input->get('ncpassword')) {
            $array = array('message' => 'New password & confirm password are not same', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->get('authkey');
        $cpassword = $this->input->get('cpassword');
        $npassword = $this->input->get('npassword');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $password = $result_auth->password;
            // Check Current Password
            if ($password == md5($cpassword)) {
                // Check Status of User Account
                if ($user_status) {
                    $result_api = $this->api_model->changeUserPassword($user_id, $npassword);
                    if ($result_api) {
                        $array = array('message' => 'Password has been changed successfully', 'code' => 'REST_Controller:: HTTP_OK', 'type' => 'success');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'Password changing failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Wrong current password', 'code' => '304', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function password_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Current Password
        if (!$this->input->post('cpassword')) {
            $array = array('message' => 'Missing current password', 'code' => '103', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('cpassword') == "") {
            $array = array('message' => 'Current password can not be null', 'code' => '103', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // New Password
        if (!$this->input->post('npassword')) {
            $array = array('message' => 'Missing new password', 'code' => '104', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('npassword') == "") {
            $array = array('message' => 'New password can not be null', 'code' => '104', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Confirm New Password
        if (!$this->input->post('ncpassword')) {
            $array = array('message' => 'Missing confirm password', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('ncpassword') == "") {
            $array = array('message' => 'confirm password can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Check New Password & Confirm Password
        if ($this->input->post('npassword') != $this->input->post('ncpassword')) {
            $array = array('message' => 'New password & confirm password are not same', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->post('authkey');
        $cpassword = $this->input->post('cpassword');
        $npassword = $this->input->post('npassword');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $password = $result_auth->password;
            // Check Current Password
            if ($password == md5($cpassword)) {
                // Check Status of User Account
                if ($user_status) {
                    $result_api = $this->api_model->changeUserPassword($user_id, $npassword);
                    if ($result_api) {
                        $array = array('message' => 'Password has been changed successfully', 'code' => 'REST_Controller:: HTTP_OK', 'type' => 'success');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'Password changing failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Wrong current password', 'type' => '304', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Validate Authuntication Key (Get & Post)
    function validate_get() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $response = $this->response($array, REST_Controller:: HTTP_OK);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function validate_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->post('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $this->response($array, REST_Controller:: HTTP_OK);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Users/Resellers API
    //====================================================================//
    // Add New User
    function add_user_get() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Full Name
        if (!$this->input->get('full_name')) {
            $array = array('message' => 'Missing full name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('full_name') == "") {
            $array = array('message' => 'Full name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $full_name = $this->input->get('full_name');
        $fn_array = explode(' ', $full_name);
        if (sizeof($fn_array) <= 1) {
            $array = array('message' => 'Provide valid full name', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Username
        if (!$this->input->get('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('username') == "") {
            $array = array('message' => 'Username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Mobile Number
        if (!$this->input->get('mobile')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('mobile') == "") {
            $array = array('message' => 'Mobile number cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->get('mobile')) != 10) {
            $array = array('message' => 'Invalid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Email Address
        if (!$this->input->get('email')) {
            $array = array('message' => 'Missing email address', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('email') == "") {
            $array = array('message' => 'Email address cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!filter_var($this->input->get('email'), FILTER_VALIDATE_EMAIL)) {
            $array = array('message' => 'Invalid email address', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Company
        if (!$this->input->get('company')) {
            $array = array('message' => 'Missing company name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('company') == "") {
            $array = array('message' => 'Company name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Industry
        if (!$this->input->get('industry')) {
            $array = array('message' => 'Missing industry name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('industry') == "") {
            $array = array('message' => 'Industry name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Expiry Date
        if (!$this->input->get('expiry')) {
            $array = array('message' => 'Missing expiry date', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('expiry') == "") {
            $array = array('message' => 'Expiry date cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->get('authkey');
        $username = $this->input->get('username');
        $user_mobile = $this->input->get('mobile');
        $user_email = $this->input->get('email');
        $user_company = $this->input->get('company');
        $user_industry = $this->input->get('industry');
        $user_expiry = $this->input->get('expiry');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            // Check Username Availability
            $result_username = $this->user_data_model->getUsername($username);
            if ($result_username) {
                $array = array('message' => 'This username is already taken. Choose another', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            } else {
                // Check Mobile Number Availability
                $result_mobile = $this->user_data_model->getMobileNumber($user_mobile);
                if ($result_mobile >= 4) {
                    $array = array('message' => 'This mobile number is already registered with us. Choose another', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $user_id = $result_auth->user_id;
                    $user_status = $result_auth->user_status;
                    $most_parent_id = $result_auth->most_parent_id;
                    $pro_user_group_id = $result_auth->pro_user_group_id;
                    $tr_user_group_id = $result_auth->tr_user_group_id;
                    $number_allowed = $result_auth->number_allowed;
                    $user_ratio = $result_auth->user_ratio;
                    $user_fake_ratio = $result_auth->user_fake_ratio;
                    $user_fail_ratio = $result_auth->user_fail_ratio;
                    $pr_user_ratio = $result_auth->pr_user_ratio;
                    $pr_user_fake_ratio = $result_auth->pr_user_fake_ratio;
                    $pr_user_fail_ratio = $result_auth->pr_user_fail_ratio;
                    // Check Status of User Account
                    if ($user_status) {
                        // Post Array
                        $user_array = array('user_id' => $user_id, 'most_parent_id' => $most_parent_id, 'pro_user_group_id' => $pro_user_group_id,
                            'tr_user_group_id' => $tr_user_group_id,
                            'full_name' => $full_name, 'username' => $username, 'mobile' => $user_mobile, 'user_email' => $user_email,
                            'user_company' => $user_company, 'user_industry' => $user_industry, 'user_expiry' => $user_expiry, 'number_allowed' => $number_allowed,
                            'user_ratio' => $user_ratio, 'user_fake_ratio' => $user_fake_ratio, 'user_fail_ratio' => $user_fail_ratio,
                            'pr_user_ratio' => $pr_user_ratio, 'pr_user_fake_ratio' => $pr_user_fake_ratio, 'pr_user_fail_ratio' => $pr_user_fail_ratio);
                        $result_api = $this->api_model->saveNewUser($user_array);
                        if ($result_api) {
                            $array = array('message' => 'User created successfully', 'type' => 'success');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        } else {
                            $array = array('message' => 'User creation failed', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                }
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function add_user_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Full Name
        if (!$this->input->post('full_name')) {
            $array = array('message' => 'Missing full name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('full_name') == "") {
            $array = array('message' => 'Full name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $full_name = $this->input->post('full_name');
        $fn_array = explode(' ', $full_name);
        if (sizeof($fn_array) <= 1) {
            $array = array('message' => 'Provide valid full name', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Username
        if (!$this->input->post('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('username') == "") {
            $array = array('message' => 'Username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Mobile Number
        if (!$this->input->post('mobile')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('mobile') == "") {
            $array = array('message' => 'Mobile number cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->post('mobile')) != 10) {
            $array = array('message' => 'Invalid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Email Address
        if (!$this->input->post('email')) {
            $array = array('message' => 'Missing email address', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('email') == "") {
            $array = array('message' => 'Email address cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!filter_var($this->input->post('email'), FILTER_VALIDATE_EMAIL)) {
            $array = array('message' => 'Invalid email address', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Company
        if (!$this->input->post('company')) {
            $array = array('message' => 'Missing company name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('company') == "") {
            $array = array('message' => 'Company name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Industry
        if (!$this->input->post('industry')) {
            $array = array('message' => 'Missing industry name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('industry') == "") {
            $array = array('message' => 'Industry name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Expiry Date
        if (!$this->input->post('expiry')) {
            $array = array('message' => 'Missing expiry date', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('expiry') == "") {
            $array = array('message' => 'Expiry date cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->post('authkey');
        $username = $this->input->post('username');
        $user_mobile = $this->input->post('mobile');
        $user_email = $this->input->post('email');
        $user_company = $this->input->post('company');
        $user_industry = $this->input->post('industry');
        $user_expiry = $this->input->post('expiry');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            // Check Username Availability
            $result_username = $this->user_data_model->getUsername($username);
            if ($result_username) {
                $array = array('message' => 'This username is already taken. Choose another', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            } else {
                // Check Mobile Number Availability
                $result_mobile = $this->user_data_model->getMobileNumber($user_mobile);
                if ($result_mobile >= 4) {
                    $array = array('message' => 'This mobile number is already registered with us. Choose another', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $user_id = $result_auth->user_id;
                    $user_status = $result_auth->user_status;
                    $most_parent_id = $result_auth->most_parent_id;
                    $pro_user_group_id = $result_auth->pro_user_group_id;
                    $tr_user_group_id = $result_auth->tr_user_group_id;
                    $number_allowed = $result_auth->number_allowed;
                    $user_ratio = $result_auth->user_ratio;
                    $user_fake_ratio = $result_auth->user_fake_ratio;
                    $user_fail_ratio = $result_auth->user_fail_ratio;
                    $pr_user_ratio = $result_auth->pr_user_ratio;
                    $pr_user_fake_ratio = $result_auth->pr_user_fake_ratio;
                    $pr_user_fail_ratio = $result_auth->pr_user_fail_ratio;
                    // Check Status of User Account
                    if ($user_status) {
                        // Post Array
                        $user_array = array('user_id' => $user_id, 'most_parent_id' => $most_parent_id, 'pro_user_group_id' => $pro_user_group_id,
                            'tr_user_group_id' => $tr_user_group_id,
                            'full_name' => $full_name, 'username' => $username, 'mobile' => $user_mobile, 'user_email' => $user_email,
                            'user_company' => $user_company, 'user_industry' => $user_industry, 'user_expiry' => $user_expiry, 'number_allowed' => $number_allowed,
                            'user_ratio' => $user_ratio, 'user_fake_ratio' => $user_fake_ratio, 'user_fail_ratio' => $user_fail_ratio,
                            'pr_user_ratio' => $pr_user_ratio, 'pr_user_fake_ratio' => $pr_user_fake_ratio, 'pr_user_fail_ratio' => $pr_user_fail_ratio);
                        $result_api = $this->api_model->saveNewUser($user_array);
                        if ($result_api) {
                            $array = array('message' => 'User created successfully', 'type' => 'success');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        } else {
                            $array = array('message' => 'User creation failed', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                }
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Users
    function users_get() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Users
            $type = 2;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'success');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function users_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Users
            $type = 2;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'success');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Transfer SMS Balance
    function transfer_balance_get() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User Id/Username
        if (!$this->input->get('user_id')) {
            $array = array('message' => 'Missing user id or username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('user_id') == "") {
            $array = array('message' => 'User id or username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Number Of SMS
        if (!$this->input->get('sms')) {
            $array = array('message' => 'Missing number of sms', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('sms') == "") {
            $array = array('message' => 'Number of sms cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('sms'))) {
            $array = array('message' => 'Provide valid number of sms', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Account Type
        if (!$this->input->get('account_type')) {
            $array = array('message' => 'Missing account type', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('account_type') == "") {
            $array = array('message' => 'Account type cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('account_type') != "A" && $this->input->get('account_type') != "B") {
            $array = array('message' => 'Provide valid account type', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Type
        if (!$this->input->get('type')) {
            $array = array('message' => 'Missing type', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('type') == "") {
            $array = array('message' => 'Type cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (($this->input->get('type') != "Add" && $this->input->get('type') != "1") && ($this->input->get('type') != "Reduce" && $this->input->get('type') != "2")) {
            $array = array('message' => 'Provide valid transaction type', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Price Per SMS
        if (!$this->input->get('price')) {
            $array = array('message' => 'Missing price per sms', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('price') == "") {
            $array = array('message' => 'Price per sms cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('price'))) {
            $array = array('message' => 'Provide valid price/sms', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Description
        if (!$this->input->get('description')) {
            $array = array('message' => 'Missing description', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('description') == "") {
            $array = array('message' => 'Description cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->get('authkey');
        $ref_user_id = $this->input->get('user_id');
        $sms = $this->input->get('sms');
        $account_type = $this->input->get('account_type');
        $type = $this->input->get('type');
        $price = $this->input->get('price');
        $description = $this->input->get('description');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            $pr_sms_balance = $result_auth->pr_sms_balance;
            $tr_sms_balance = $result_auth->tr_sms_balance;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Check User Balance
                            if ($account_type == 'A') {
                                if ($pr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, REST_Controller:: HTTP_OK);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $pr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    }
                                }
                            } elseif ($account_type == 'B') {
                                if ($tr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, REST_Controller:: HTTP_OK);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $tr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    }
                                }
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function transfer_balance_post() {
        // Validate Parameters
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User Id/Username
        if (!$this->input->post('user_id')) {
            $array = array('message' => 'Missing user id or username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('user_id') == "") {
            $array = array('message' => 'User id or username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Number Of SMS
        if (!$this->input->post('sms')) {
            $array = array('message' => 'Missing number of sms', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('sms') == "") {
            $array = array('message' => 'Number of sms cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('sms'))) {
            $array = array('message' => 'Provide valid number of sms', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Account Type
        if (!$this->input->post('account_type')) {
            $array = array('message' => 'Missing account type', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('account_type') == "") {
            $array = array('message' => 'Account type cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('account_type') != "A" && $this->input->post('account_type') != "B") {
            $array = array('message' => 'Provide valid account type', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Type
        if (!$this->input->post('type')) {
            $array = array('message' => 'Missing type', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('type') == "") {
            $array = array('message' => 'Type cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (($this->input->post('type') != "Add" && $this->input->post('type') != "1") && ($this->input->post('type') != "Reduce" && $this->input->post('type') != "2")) {
            $array = array('message' => 'Provide valid transaction type', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Price Per SMS
        if (!$this->input->post('price')) {
            $array = array('message' => 'Missing price per sms', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('price') == "") {
            $array = array('message' => 'Price per sms cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('price'))) {
            $array = array('message' => 'Provide valid price/sms', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Description
        if (!$this->input->post('description')) {
            $array = array('message' => 'Missing description', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('description') == "") {
            $array = array('message' => 'Description cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Retrieve Parameters
        $auth_key = $this->input->post('authkey');
        $ref_user_id = $this->input->post('user_id');
        $sms = $this->input->post('sms');
        $account_type = $this->input->post('account_type');
        $type = $this->input->post('type');
        $price = $this->input->post('price');
        $description = $this->input->post('description');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            $pr_sms_balance = $result_auth->pr_sms_balance;
            $tr_sms_balance = $result_auth->tr_sms_balance;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Check User Balance
                            if ($account_type == 'A') {
                                if ($pr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, REST_Controller:: HTTP_OK);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $pr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    }
                                }
                            } elseif ($account_type == 'B') {
                                if ($tr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, REST_Controller:: HTTP_OK);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $tr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, REST_Controller:: HTTP_OK);
                                    }
                                }
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Manage User
    function manage_user_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User ID
        if (!$this->input->get('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('user_id') == "") {
            $array = array('message' => 'User id/username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Status
        if (!$this->input->get('status')) {
            $array = array('message' => 'Missing status', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('status') == "") {
            $array = array('message' => 'Status cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('status'))) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('status') != 1 && $this->input->get('status') != 2) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Validate User Authentication 
        $auth_key = $this->input->get('authkey');
        $ref_user_id = $this->input->get('user_id');
        $status = $this->input->get('status');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            $result_api = $this->api_model->manageUser($user_id, $new_user_id, $status);
                            if ($result_api) {
                                $array = array('message' => 'User account status changed successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'User account status changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '301', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This is is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function manage_user_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User ID
        if (!$this->input->post('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('user_id') == "") {
            $array = array('message' => 'User id/username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Status
        if (!$this->input->post('status')) {
            $array = array('message' => 'Missing status', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('status') == "") {
            $array = array('message' => 'Status cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('status'))) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('status') != 1 && $this->input->post('status') != 2) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Validate User Authentication 
        $auth_key = $this->input->post('authkey');
        $ref_user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            $result_api = $this->api_model->manageUser($user_id, $new_user_id, $status);
                            if ($result_api) {
                                $array = array('message' => 'User account status changed successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'User account status changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '301', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This is is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // User Forget Password
    function forget_password_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Username
        if (!$this->input->get('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('username') == "") {
            $array = array('message' => 'Username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $ref_user_id = $this->input->get('username');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    $name = $result_puser->name;
                    $username = $result_puser->username;
                    $contact_number = $result_puser->contact_number;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            $result_api = $this->api_model->forgetPassword($new_user_id, $contact_number, $username, $name);
                            if ($result_api) {
                                $array = array('message' => 'Password has been changed successfully and sent to your registered mobile number', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function forget_password_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Username
        if (!$this->input->post('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('username') == "") {
            $array = array('message' => 'Username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $ref_user_id = $this->input->post('username');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    $contact_number = $result_puser->contact_number;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            $result_api = $this->api_model->forgetPassword($new_user_id, $contact_number);
                            if ($result_api) {
                                $array = array('message' => 'Password has been changed successfully adn sent to your registered mobile number', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // View Own Profile
    function profile_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get User
            $type = 1;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function profile_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get User
            $type = 1;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Get Expiry Date
    function get_expiry_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $expiry_date = $result_auth->expiry_date;
            $this->response($expiry_date, REST_Controller:: HTTP_OK);
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function get_expiry_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $expiry_date = $result_auth->expiry_date;
            $this->response($expiry_date, REST_Controller:: HTTP_OK);
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // View User Profile
    function user_profile_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Auth Key
        if (!$this->input->get('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        if ($this->input->get('user_id') == "") {
            $array = array('message' => 'User id/username key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        $auth_key = $this->input->get('authkey');
        $ref_user_id = $this->input->get('user_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Get User Profile
                            $type = 1;
                            $result_users = $this->api_model->getUsers($new_user_id, $type);
                            $this->response($result_users, REST_Controller:: HTTP_OK);
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user does not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function user_profile_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User ID
        if (!$this->input->post('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        if ($this->input->post('user_id') == "") {
            $array = array('message' => 'User id/username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        $auth_key = $this->input->post('authkey');
        $ref_user_id = $this->input->post('user_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Get User Profile
                            $type = 1;
                            $result_users = $this->api_model->getUsers($new_user_id, $type);
                            $this->response($result_users, REST_Controller:: HTTP_OK);
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user does not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Change User Password
    function change_password_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User ID
        if (!$this->input->get('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('user_id') == "") {
            $array = array('message' => 'User id/username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User Password
        if (!$this->input->get('user_password')) {
            $array = array('message' => 'Missing user new password', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('user_password') == "") {
            $array = array('message' => 'User password key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $ref_user_id = $this->input->get('user_id');
        $user_password = $this->input->get('user_password');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Change User Password
                            $result_api = $this->api_model->changeUserPassword($new_user_id, $user_password);
                            if ($result_api) {
                                $array = array('message' => 'User password changed successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function change_password_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User ID
        if (!$this->input->post('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('user_id') == "") {
            $array = array('message' => 'User id/username cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // User Password
        if (!$this->input->post('user_password')) {
            $array = array('message' => 'Missing user new password', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('user_password') == "") {
            $array = array('message' => 'User passwors cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $ref_user_id = $this->input->post('user_id');
        $user_password = $this->input->post('user_password');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            if ($utype == 'Reseller') {
                $result_puser = $this->api_model->getUserParent($ref_user_id);
                if ($result_puser) {
                    $new_user_id = $result_puser->user_id;
                    $parent_user_id = $result_puser->ref_user_id;
                    // Check Parent
                    if ($parent_user_id == $user_id) {
                        // Check Status of User Account
                        if ($user_status == 1) {
                            // Change User Password
                            $result_api = $this->api_model->changeUserPassword($new_user_id, $user_password);
                            if ($result_api) {
                                $array = array('message' => 'User password changed successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Get Transactions
    function transactions_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Transactions
            $result_txns = $this->api_model->getTransactions($user_id);
            if ($result_txns) {
                $txn_array = array();
                foreach ($result_txns as $key => $row_txn) {
                    $txn_array[$key]['txn_date'] = $row_txn['txn_date'];
                    if ($row_txn['from_user_id'] == "")
                        $txn_array[$key]['from'] = $row_txn['from_admin_uname'];
                    else
                        $txn_array[$key]['from'] = $row_txn['from_username'];

                    if ($row_txn['to_user_id'] == "")
                        $txn_array[$key]['to'] = $row_txn['to_admin_uname'];
                    else
                        $txn_array[$key]['to'] = $row_txn['to_username'];

                    $txn_array[$key]['txn_type'] = $row_txn['txn_type'];

                    if ($row_txn['txn_route'] == 'A')
                        $txn_array[$key]['txn_route'] = "Promotional";
                    elseif ($row_txn['txn_route'] == 'B')
                        $txn_array[$key]['txn_route'] = "Transactional";

                    $txn_array[$key]['txn_sms'] = $row_txn['txn_sms'];
                    $txn_array[$key]['txn_price'] = $row_txn['txn_price'];
                    $txn_array[$key]['txn_amount'] = $row_txn['txn_amount'];
                }
                $this->response($txn_array, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function transactions_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Transactions
            $result_txns = $this->api_model->getTransactions($user_id);
            if ($result_txns) {
                $txn_array = array();
                foreach ($result_txns as $key => $row_txn) {
                    $txn_array[$key]['txn_date'] = $row_txn['txn_date'];
                    if ($row_txn['from_user_id'] == "")
                        $txn_array[$key]['from'] = $row_txn['from_admin_uname'];
                    else
                        $txn_array[$key]['from'] = $row_txn['from_username'];

                    if ($row_txn['to_user_id'] == "")
                        $txn_array[$key]['to'] = $row_txn['to_admin_uname'];
                    else
                        $txn_array[$key]['to'] = $row_txn['to_username'];

                    $txn_array[$key]['txn_type'] = $row_txn['txn_type'];

                    if ($row_txn['txn_route'] == 'A')
                        $txn_array[$key]['txn_route'] = "Promotional";
                    elseif ($row_txn['txn_route'] == 'B')
                        $txn_array[$key]['txn_route'] = "Transactional";

                    $txn_array[$key]['txn_sms'] = $row_txn['txn_sms'];
                    $txn_array[$key]['txn_price'] = $row_txn['txn_price'];
                    $txn_array[$key]['txn_amount'] = $row_txn['txn_amount'];
                }
                $this->response($txn_array, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Phonebook API
    //====================================================================//
    // Add Group
    function add_group_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group Name
        if (!$this->input->get('group_name')) {
            $array = array('message' => 'Missing group name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('group_name') == "") {
            $array = array('message' => 'Group name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $group_name = $this->input->get('group_name');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->saveGroup($user_id, $group_name);
                if ($result_api == 1) {
                    $array = array('message' => 'Group created succesfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } elseif ($result_api == 100) {
                    $array = array('message' => 'Group already exists! Please try another', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Group creation failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function add_group_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group Name
        if (!$this->input->post('group_name')) {
            $array = array('message' => 'Missing group name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('group_name') == "") {
            $array = array('message' => 'Group name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $group_name = $this->input->post('group_name');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->saveGroup($user_id, $group_name);
                if ($result_api == 1) {
                    $array = array('message' => 'Group created succesfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } elseif ($result_api == 100) {
                    $array = array('message' => 'Group already exists! Please try another', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Group creation failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Delete Group
    function delete_group_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->get('group_id')) {
            $array = array('message' => 'Missing group Id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('group_id'))) {
            $array = array('message' => 'Provide valid group Id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $group_id = $this->input->get('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->deleteGroup($user_id, $group_id);
                if ($result_api) {
                    $array = array('message' => 'Group has been deleted successfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Group deletion failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function delete_group_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->post('group_id')) {
            $array = array('message' => 'Missing group Id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('group_id'))) {
            $array = array('message' => 'Provide valid group Id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $group_id = $this->input->post('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->deleteGroup($user_id, $group_id);
                if ($result_api) {
                    $array = array('message' => 'Group has been deleted successfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Group deletion failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // List Groups
    function list_groups_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Groups
            $result_groups = $this->api_model->getGroups($user_id);
            if ($result_groups) {
                $this->response($result_groups, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No groups exists', 'type' => 'success');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function list_groups_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Groups
            $result_groups = $this->api_model->getGroups($user_id);
            if ($result_groups) {
                $this->response($result_groups, REST_Controller:: HTTP_OK);
            } else {
                $array = array('message' => 'No groups exists', 'type' => 'success');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Add New Contact
    function add_contact_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Name
        if (!$this->input->get('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('name') == "") {
            $array = array('message' => 'Name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Mobile No
        if (!$this->input->get('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('mobile_no') == "") {
            $array = array('message' => 'Mobile number cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->get('mobile_no')) != 10) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group Id
        if (!$this->input->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $name = $this->input->get('name');
        $mobile = $this->input->get('mobile_no');
        $group_id = $this->input->get('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_api = $this->api_model->saveContact($user_id, $name, $mobile, $group_id);
                    if ($result_api) {
                        if ($result_api == 'Already') {
                            $array = array('message' => 'This contact has been already exists', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        } elseif ($result_api == 'Save') {
                            $array = array('message' => 'Contact has been inserted successfully', 'type' => 'success');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'Contact insertion failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function add_contact_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Name
        if (!$this->input->post('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('name') == "") {
            $array = array('message' => 'Name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Mobile No
        if (!$this->input->post('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('mobile_no') == "") {
            $array = array('message' => 'Mobile number cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->post('mobile_no')) != 10) {
            $array = array('message' => 'Provide valid mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group Id
        if (!$this->input->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile_no');
        $group_id = $this->input->post('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_api = $this->api_model->saveContact($user_id, $name, $mobile, $group_id);
                    if ($result_api) {
                        if ($result_api == 'Already') {
                            $array = array('message' => 'This contact has been already exists', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        } elseif ($result_api == 'Save') {
                            $array = array('message' => 'Contact has been inserted successfully', 'type' => 'success');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'Contact insertion failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Edit Contact
    function edit_contact_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact ID
        if (!$this->input->get('contact_id')) {
            $array = array('message' => 'Missing contact id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('contact_id') == "") {
            $array = array('message' => 'Contact id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Number
        if (!$this->input->get('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('mobile_no') == "") {
            $array = array('message' => 'Mobile numebr cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->get('mobile_no') != 10)) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Name
        if (!$this->input->get('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('name') == "") {
            $array = array('message' => 'Contact name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $contact_id = $this->input->get('contact_id');
        $group_id = $this->input->get('group_id');
        $mobile = $this->input->get('mobile_no');
        $name = $this->input->get('name');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_contact = $this->api_model->getContacts($user_id, $contact_id);
                    if ($result_contact) {
                        $result_api = $this->api_model->updateContact($user_id, $contact_id, $group_id, $mobile, $name);
                        if ($result_api) {
                            if ($result_api == 'Already') {
                                $array = array('message' => 'This contact already exists', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } elseif ($result_api == 'Save') {
                                $array = array('message' => 'Contact updated successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Contact updation failed', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function edit_contact_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact ID
        if (!$this->input->post('contact_id')) {
            $array = array('message' => 'Missing contact id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('contact_id') == "") {
            $array = array('message' => 'Contact id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Number
        if (!$this->input->post('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('mobile_no') == "") {
            $array = array('message' => 'Mobile number cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->post('mobile_no') != 10)) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Name
        if (!$this->input->post('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('name') == "") {
            $array = array('message' => 'Contact name cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $contact_id = $this->input->post('contact_id');
        $group_id = $this->input->post('group_id');
        $mobile = $this->input->post('mobile_no');
        $name = $this->input->post('name');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_contact = $this->api_model->getContacts($user_id, $contact_id);
                    if ($result_contact) {
                        $result_api = $this->api_model->updateContact($user_id, $contact_id, $group_id, $mobile, $name);
                        if ($result_api) {
                            if ($result_api == 'Already') {
                                $array = array('message' => 'This contact already exists', 'type' => 'error');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            } elseif ($result_api == 'Save') {
                                $array = array('message' => 'Contact updated successfully', 'type' => 'success');
                                $this->response($array, REST_Controller:: HTTP_OK);
                            }
                        } else {
                            $array = array('message' => 'Contact updation failed', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Delete Contact
    function delete_contact_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Id
        if (!$this->input->get('contact_id')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('contact_id') == "") {
            $array = array('message' => 'Contact id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $contact_id = $this->input->get('contact_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->deleteContact($user_id, $contact_id);
                if ($result_api) {
                    $array = array('message' => 'Contact deleted successfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function delete_contact_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Contact Id
        if (!$this->input->post('contact_id')) {
            $array = array('message' => 'Missing contact id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('contact_id') == "") {
            $array = array('message' => 'Contact id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $contact_id = $this->input->post('contact_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->deleteContact($user_id, $contact_id);
                if ($result_api) {
                    $array = array('message' => 'Contact deleted successfully', 'type' => 'success');
                    $this->response($array, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // List Contacts
    function list_contacts_get() {
        // Auth Key
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('group_id') == "") {
            $array = array('message' => 'Group id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $group_id = $this->input->get('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_api = $this->api_model->getGroupContacts($user_id);
                    if ($result_api) {
                        $contact_array = array();
                        $i = 0;
                        foreach ($result_api as $key => $contact) {
                            if (in_array($group_id, explode(',', $contact['contact_group_ids']))) {
                                $contact_array[$i]['contact_id'] = $contact['contact_id'];
                                $contact_array[$i]['contact_number'] = $contact['mobile_number'];
                                $contact_array[$i]['contact_name'] = $contact['contact_name'];
                                $i++;
                            }
                        }
                        $this->response($contact_array, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'No contact exists', 'type' => 'success');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function list_contacts_post() {
        // Auth Key
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Group ID
        if (!$this->input->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('group_id') == "") {
            $array = array('message' => 'Group Id cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!is_numeric($this->input->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        $group_id = $this->input->post('group_id');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_group = $this->api_model->getGroups($user_id, $group_id);
                if ($result_group) {
                    $result_api = $this->api_model->getGroupContacts($user_id);
                    if ($result_api) {
                        $contact_array = array();
                        $i = 0;
                        foreach ($result_api as $key => $contact) {
                            if (in_array($group_id, explode(',', $contact['contact_group_ids']))) {
                                $contact_array[$i]['contact_id'] = $contact['contact_id'];
                                $contact_array[$i]['contact_number'] = $contact['mobile_number'];
                                $contact_array[$i]['contact_name'] = $contact['contact_name'];
                                $i++;
                            }
                        }
                        $this->response($contact_array, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'No contact exists', 'type' => 'success');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Send SMS API
    //====================================================================//
    // Send SMS Through HTTP API (Get & Post)
    function send_http1_get() {
        // Mobile Number(s)
        if (!$this->input->get('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        } else {
            $mobiles = $this->input->get('mobiles');
        }
        // Message
        if (!$this->input->get('message')) {
            $array = array('message' => 'Missing message', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        // Sender Id
        if (!$this->input->get('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        } elseif (strlen($this->input->get('sender')) != 6) {
            $array = array('message' => 'Sender id must be 6 characters long', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        // Route
        if (!$this->input->get('route')) {
            $array = array('message' => 'Missing route', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        $route = strtolower($this->input->get('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '201', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }

        $auth_key = $this->input->get('authkey');
        $username = $this->input->get('username');
        $password = $this->input->get('password');
        $mobiles = $this->input->get('mobiles');
        $message = $this->input->get('message');
        $sender = $this->input->get('sender');
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }

        // Response (string/json/xml)
        if ($this->input->get('response'))
            $response = $this->input->get('response');
        else
            $response = "string";

        // Campaign
        if ($this->input->get('campaign'))
            $campaign = $this->input->get('campaign');
        else
            $campaign = "New Campaign";

        // Flash SMS
        if ($this->input->get('flash'))
            $flash = $this->input->get('flash');
        else
            $flash = 0;

        // Unicode SMS
        if ($this->input->get('unicode'))
            $unicode = $this->input->get('unicode');
        else
            $unicode = 0;

        // Ignore National Do Not Call Number
        if ($this->input->get('ignoreNdnc'))
            $ignoreNdnc = $this->input->get('ignoreNdnc');
        else
            $ignoreNdnc = 0;

        // Schdeule Time
        if ($this->input->get('schtime'))
            $schtime = $this->input->get('schtime');
        else
            $schtime = "";

        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                //Post Array
                $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                    'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime);
                // Send Message
                $result_api = $this->send_bulk_sms_model->sendMessageHttpApi($array_users);
                if ($result_api) {
                    $this->response($result_api, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Message sending failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
    }

    function send_http1_post() {
        // Mobile Number(s)
        if (!$this->input->post('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        } else {
            $mobiles = $this->input->post('mobiles');
        }
        // Message
        if (!$this->input->post('message')) {
            $array = array('message' => 'Missing message', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        // Sender Id
        if (!$this->input->post('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        } elseif (strlen($this->input->post('sender')) != 6) {
            $array = array('message' => 'Sender id must be 6 characters long', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        // Route
        if (!$this->input->post('route')) {
            $array = array('message' => 'Missing route', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
        $route = strtolower($this->input->post('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '201', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }

        $auth_key = $this->input->post('authkey');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $mobiles = $this->input->post('mobiles');
        $message = $this->input->post('message');
        $sender = $this->input->post('sender');
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }

        // Response (string/json/xml)
        if ($this->input->post('response'))
            $response = $this->input->post('response');
        else
            $response = "string";

        // Campaign
        if ($this->input->post('campaign'))
            $campaign = $this->input->post('campaign');
        else
            $campaign = "New Campaign";

        // Flash SMS
        if ($this->input->post('flash'))
            $flash = $this->input->post('flash');
        else
            $flash = 0;

        // Unicode SMS
        if ($this->input->post('unicode'))
            $unicode = $this->input->post('unicode');
        else
            $unicode = 0;

        // Ignore National Do Not Call Number
        if ($this->input->post('ignoreNdnc'))
            $ignoreNdnc = $this->input->post('ignoreNdnc');
        else
            $ignoreNdnc = 0;

        // Schdeule Time
        if ($this->input->post('schtime'))
            $schtime = $this->input->post('schtime');
        else
            $schtime = "";

        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                //Post Array
                $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                    'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime);
                // Send Message
                $result_api = $this->send_bulk_sms_model->sendMessageHttpApi($array_users);
                if ($result_api) {
                    $this->response($result_api, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Message sending failed', 'type' => 'error', 'code' => '001');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
    }

    //====================================================================//
    function send_http_get() {
        if (!$this->input->get('authkey') && !$this->input->get('username') && !$this->input->get('password')) {
            $array = array('message' => 'Missing authentication key', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        } elseif (!$this->input->get('authkey')) {
            if (!$this->input->get('username') && !$this->input->get('password')) {
                $array = array('message' => 'Missing username and password', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif (!$this->input->get('username') && $this->input->get('password')) {
                $array = array('message' => 'Missing username', 'code' => '108', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->get('username') && !$this->input->get('password')) {
                $array = array('message' => 'Missing password', 'code' => '109', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
            if ($this->input->get('username') == "" && $this->input->get('password') == "") {
                $array = array('message' => 'Username and password can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->get('username') == "" && $this->input->get('password') != "") {
                $array = array('message' => 'Username can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->get('username') != "" && $this->input->get('password') == "") {
                $array = array('message' => 'Password can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        } elseif (!$this->input->get('username') && !$this->input->get('password')) {
            if (!$this->input->get('authkey')) {
                $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
            if ($this->input->get('authkey') == "") {
                $array = array('message' => 'Authentication key can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
        // Mobile Numbers
        if (!$this->input->get('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '105', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('mobiles') == "") {
            $array = array('message' => 'Mobiles can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Messages
        if (!$this->input->get('message')) {
            $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('message') == "") {
            $array = array('message' => 'Message can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Sender
        if (!$this->input->get('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('sender') == "") {
            $array = array('message' => 'Sender id can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->get('sender')) != 6) {
            $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Route
        if (!$this->input->get('route')) {
            $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('route') == "") {
            $array = array('message' => 'Route can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $route = strtolower($this->input->get('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Check Mobile Numbers
        $mobiles = $this->input->get('mobiles');
        $check = 0;
        $mobile_array = explode(',', $mobiles);
        $new_array = array();
        foreach ($mobile_array as $mobile) {
            if (strlen($mobile) == 10) {
                $new_array[] = "91" . $mobile;
            } elseif (strlen($mobile) == 12) {
                $new_array[] = $mobile;
            } else {
                $check++;
            }
        }
        if ($check > 0) {
            $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey')) {
            $authkey = $this->input->get('authkey');
            $username = "";
            $password = "";
        } elseif ($this->input->get('username') && $this->input->get('password')) {
            $authkey = "";
            $username = $this->input->get('username');
            $password = $this->input->get('password');
        }
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }
        $messages = $this->input->get('message');
        $sender = $this->input->get('sender');
        $mobiles = implode(',', $new_array);
        //$route = $_GET['route'];
        $flash = 0;
        if ($this->input->get('flash'))
            $flash = $this->input->get('flash');
        $unicode = 0;
        if ($this->input->get('unicode'))
            $unicode = $this->input->get('unicode');
        $ignoreNdnc = 0;
        if ($this->input->get('ignoreNdnc'))
            $ignoreNdnc = $this->input->get('ignoreNdnc');
        $schtime = "";
        if ($this->input->get('schtime'))
            $schtime = $this->input->get('schtime');
        $response = "";
        if ($this->input->get('response'))
            $response = $this->input->get('schtime');
        $campaign = "API";
        if ($this->input->get('campaign'))
            $campaign = $this->input->get('campaign');
        $ip = 0;
        if ($this->input->get('ip'))
            $ip = $this->input->get('ip');

        // Client Machine IP Address For Security
        if ($ip) {
            $client_ip_address = $ip;
        } else {
            $client_ip_address = $_SERVER['REMOTE_ADDR'];
        }

        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($authkey, $username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $flag = 1;
                $check_black_keyword = $result_auth->check_black_keyword;
                if ($check_black_keyword) {
                    $ref_user_id = $result_auth->ref_user_id;
                    $admin_id = $result_auth->admin_id;
                    $utype = $result_auth->utype;
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $messages);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $messages, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function send_http_post() {
        if (!$this->input->post('authkey') && !$this->input->post('username') && !$this->input->post('password')) {
            $array = array('message' => 'Missing authentication key', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        } elseif (!$this->input->post('authkey')) {
            if (!$this->input->post('username') && !$this->input->post('password')) {
                $array = array('message' => 'Missing username and password', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif (!$this->input->post('username') && $this->input->post('password')) {
                $array = array('message' => 'Missing username', 'code' => '108', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->post('username') && !$this->input->post('password')) {
                $array = array('message' => 'Missing password', 'code' => '109', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
            if ($this->input->post('username') == "" && $this->input->post('password') == "") {
                $array = array('message' => 'Username and password can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->post('username') == "" && $this->input->post('password') != "") {
                $array = array('message' => 'Username can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            } elseif ($this->input->post('username') != "" && $this->input->post('password') == "") {
                $array = array('message' => 'Password can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        } elseif (!$this->input->post('username') && !$this->input->post('password')) {
            if (!$this->input->post('authkey')) {
                $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
            if ($this->input->post('authkey') == "") {
                $array = array('message' => 'Authentication key can not be null', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
        // Mobile Numbers
        if (!$this->input->post('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '105', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('mobiles') == "") {
            $array = array('message' => 'Mobiles can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Messages
        if (!$this->input->post('message')) {
            $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('message') == "") {
            $array = array('message' => 'Message can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Sender
        if (!$this->input->post('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('sender') == "") {
            $array = array('message' => 'Sender id can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (strlen($this->input->post('sender')) != 6) {
            $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Route
        if (!$this->input->post('route')) {
            $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('route') == "") {
            $array = array('message' => 'Route can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $route = strtolower($this->input->post('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Check Mobile Numbers
        $mobiles = $this->input->post('mobiles');
        $check = 0;
        $mobile_array = explode(',', $mobiles);
        $new_array = array();
        foreach ($mobile_array as $mobile) {
            if (strlen($mobile) == 10) {
                $new_array[] = "91" . $mobile;
            } elseif (strlen($mobile) == 12) {
                $new_array[] = $mobile;
            } else {
                $check++;
            }
        }
        if ($check > 0) {
            $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey')) {
            $authkey = $this->input->post('authkey');
            $username = "";
            $password = "";
        } elseif ($this->input->post('username') && $this->input->post('password')) {
            $authkey = "";
            $username = $this->input->post('username');
            $password = $this->input->post('password');
        }
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }
        $messages = $this->input->post('message');
        $sender = $this->input->post('sender');
        $mobiles = implode(',', $new_array);
        //$route = $_GET['route'];
        $flash = 0;
        if ($this->input->post('flash'))
            $flash = $this->input->post('flash');
        $unicode = 0;
        if ($this->input->post('unicode'))
            $unicode = $this->input->post('unicode');
        $ignoreNdnc = 0;
        if ($this->input->post('ignoreNdnc'))
            $ignoreNdnc = $this->input->post('ignoreNdnc');
        $schtime = "";
        if ($this->input->post('schtime'))
            $schtime = $this->input->post('schtime');
        $response = "";
        if ($this->input->post('response'))
            $response = $this->input->post('schtime');
        $campaign = "API";
        if ($this->input->post('campaign'))
            $campaign = $this->input->post('campaign');
        $ip = 0;
        if ($this->input->post('ip'))
            $ip = $this->input->post('ip');

        // Client Machine IP Address For Security
        if ($ip) {
            $client_ip_address = $ip;
        } else {
            $client_ip_address = $_SERVER['REMOTE_ADDR'];
        }
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($authkey, $username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $flag = 1;
                $check_black_keyword = $result_auth->check_black_keyword;
                if ($check_black_keyword) {
                    $ref_user_id = $result_auth->ref_user_id;
                    $admin_id = $result_auth->admin_id;
                    $utype = $result_auth->utype;
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $messages);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $messages, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Send SMS Through HTTP API (XML)
    function send_xml_get() {
        // XML Request
        if (!$this->input->get('xml')) {
            $array = array('message' => 'Missing xml request', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('xml') == "") {
            $array = array('message' => 'XML request cannot be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        } else {
            // Get XML File Request
            $xml_file = urldecode($this->input->get('xml'));
            $xml = new SimpleXMLElement($xml_file);
            $client_ip_address = $_SERVER['REMOTE_ADDR'];
            // Response (string/json/xml)
            if ($xml->RESPONSE)
                $response = $xml->RESPONSE;
            else
                $response = "string";
            // Validate Necessary Fields
            // Auth Key
            if ($xml->AUTHKEY) {
                $auth_key = $xml->AUTHKEY;
                if ($auth_key == "") {
                    $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }

            // Sender Id
            if ($xml->SENDER) {
                $sender = $xml->SENDER;
                if ($sender == "") {
                    $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                } elseif (strlen($sender) < 6 || strlen($sender) > 6) {
                    $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }

            // Route
            if ($xml->ROUTE) {
                $route = $xml->ROUTE;
                if ($route == "") {
                    $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }
                $route = strtolower($route);
                if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
                    $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }

            // Message
            if ($xml->SMS) {
                $sms = $xml->SMS;
                $sms_array = array();
                $mobile_array = array();
                $check = 0;
                $i = 0;
                foreach ($xml->SMS as $sms) {
                    $sms_array[$i] = $sms['TEXT'];
                    $j = 0;
                    foreach ($sms->ADDRESS as $address) {
                        if (strlen($address['TO']) == 10) {
                            $mobile_array[$i][$j] = $address['TO'];
                        } else {
                            $check++;
                        }
                        $j++;
                    }
                    $i++;
                }

                // Mobile Number(s)
                if (sizeof($mobile_array) == 0) {
                    $array = array('message' => 'Missing mobile number(s)', 'code' => '105', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                } elseif ($check > 0) {
                    $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }
            } else {
                $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }

            // Campaign
            if ($xml->CAMPAIGN)
                $campaign = $xml->CAMPAIGN;
            else
                $campaign = "API";

            // Flash SMS
            if ($xml->FLASH)
                $flash = $xml->FLASH;
            else
                $flash = 0;

            // Unicode SMS
            if ($xml->UNICODE)
                $unicode = $xml->UNICODE;
            else
                $unicode = 0;

            // Schdeule Time
            if ($xml->SCHEDULEDATETIME)
                $schtime = $xml->SCHEDULEDATETIME;
            else
                $schtime = "";

            if ($route == 'a' || $route == 'default' || $route == 1) {
                $route = 'A';
            } elseif ($route == 'b' || $route == 'template' || $route == 4) {
                $route = 'B';
            }

            // Validate User Authentication 
            $auth_key = $auth_key . "";
            $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
            if ($result_auth) {
                $user_id = $result_auth->user_id;
                $user_status = $result_auth->user_status;
                // Check Status of User Account (Expiry)
                if ($user_status == 1) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobile_array, 'message' => $sms_array, 'sender' => $sender . "", 'route' => $route . "",
                        'response' => $response . "", 'unicode' => $unicode . "", 'campaign' => $campaign . "", 'flash' => $flash . "", 'schtime' => $schtime . "",
                        'client_ip_address' => $client_ip_address);
                    // Send Message
                    $result_api = $this->send_bulk_sms_model->sendMessageXmlApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, REST_Controller:: HTTP_OK);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $reason = "Expired User Account";
                    $log_by = "By API";
                    $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
            }
        }
    }

    function send_xml_post() {
        // XML Request
        if (!$this->input->post('xml')) {
            $array = array('message' => 'Missing xml request', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('xml') == "") {
            $array = array('message' => 'XML request cannot be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        } else {
            // Get XML Request
            $xml_data = urldecode($this->input->post('xml'));
            $client_ip_address = $_SERVER['REMOTE_ADDR'];
            if (($position = strpos($xml_data, '<MESSAGE>')) !== false) {
                $new_xml_data = substr($xml_data, $position);
            } else {
                $new_xml_data = get_last_word($xml_data);
            }
            $xml = new SimpleXMLElement($new_xml_data);
            if ($xml) {
                //Response (string/json/xml)
                if ($xml->RESPONSE)
                    $response = $xml->RESPONSE;
                else
                    $response = "string";

                // Validate Necessary Fields
                // Auth Key
                if ($xml->AUTHKEY) {
                    $auth_key = $xml->AUTHKEY;
                    if ($auth_key == "") {
                        $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    }
                } else {
                    $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }

                // Sender Id
                if ($xml->SENDER) {
                    $sender = $xml->SENDER;
                    if ($sender == "") {
                        $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    } elseif (strlen($sender) != 6) {
                        $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    }
                } else {
                    $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }

                // Route
                if ($xml->ROUTE) {
                    $route = $xml->ROUTE;
                    if ($route == "") {
                        $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    }
                    $route = strtolower($route);
                    if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
                        $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    }
                } else {
                    $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }

                // Message
                if ($xml->SMS) {
                    $sms = $xml->SMS;
                    $sms_array = array();
                    $mobile_array = array();
                    $check = 0;
                    $i = 0;
                    $total_size = 0;
                    if (sizeof($sms) > 1) {
                        $request_type = 2;
                        foreach ($xml->SMS as $sms) {
                            $sms_array[$i] = "" . $sms['TEXT'];
                            $j = 0;
                            foreach ($sms->ADDRESS as $address) {
                                if (strlen($address['TO']) == 10) {
                                    $mobile = "91" . $address['TO'];
                                    $mobile_array[$i][$j] = $mobile;
                                } elseif (strlen($address['TO']) == 12) {
                                    $mobile = "" . $address['TO'];
                                    $mobile_array[$i][$j] = $mobile;
                                } else {
                                    $check++;
                                }
                                $j++;
                            }
                            $i++;
                        }
                        $total_size = sizeof($mobile_array, COUNT_RECURSIVE) - sizeof($mobile_array);
                    } elseif (sizeof($sms) == 1) {
                        $request_type = 1;
                        foreach ($xml->SMS as $sms) {
                            $sms_array[$i] = "" . $sms['TEXT'];
                            foreach ($sms->ADDRESS as $address) {
                                if (strlen($address['TO']) == 10) {
                                    $mobile = "91" . $address['TO'];
                                    $mobile_array[] = $mobile;
                                } elseif (strlen($address['TO']) == 12) {
                                    $mobile = "" . $address['TO'];
                                    $mobile_array[] = $mobile;
                                } else {
                                    $check++;
                                }
                                $i++;
                            }
                        }
                        $total_size = sizeof($mobile_array);
                    }

                    // Mobile Number(s)
                    if (!$total_size) {
                        $array = array('message' => 'Missing mobile number(s)', 'code' => '105', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    } elseif ($check > 0) {
                        $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                    }
                } else {
                    $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
                }

                // Campaign
                if ($xml->CAMPAIGN)
                    $campaign = $xml->CAMPAIGN;
                else
                    $campaign = "XML API";

                // Flash SMS
                if ($xml->FLASH)
                    $flash = $xml->FLASH;
                else
                    $flash = 0;

                // Unicode SMS
                if ($xml->UNICODE)
                    $unicode = $xml->UNICODE;
                else
                    $unicode = 0;

                // Schdeule Time
                if ($xml->SCHEDULEDATETIME)
                    $schtime = $xml->SCHEDULEDATETIME;
                else
                    $schtime = "";

                if ($route == 'a' || $route == 'default' || $route == 1) {
                    $route = 'A';
                } elseif ($route == 'b' || $route == 'template' || $route == 4) {
                    $route = 'B';
                }

                // Validate User Authentication 
                $auth_key = $auth_key . "";
                $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
                if ($result_auth) {
                    $user_id = $result_auth->user_id;
                    $user_status = $result_auth->user_status;
                    // Check Status of User Account (Expiry)
                    if ($user_status == 1) {
                        //Post Array
                        $array_users = array('user_id' => $user_id, 'mobiles' => $mobile_array, 'message' => $sms_array, 'sender' => $sender . "", 'route' => $route . "",
                            'response' => $response . "", 'unicode' => $unicode . "", 'campaign' => $campaign . "", 'flash' => $flash . "", 'schtime' => $schtime . "",
                            'request_type' => $request_type, 'client_ip_address' => $client_ip_address);
                        $result_api = $this->send_bulk_sms_model->sendMessageXmlApi($array_users);
                        if ($result_api) {
                            $this->response($result_api, REST_Controller:: HTTP_OK);
                        } else {
                            $array = array('message' => 'Message sending failed', 'type' => 'error');
                            $this->response($array, REST_Controller:: HTTP_OK);
                        }
                    } else {
                        $reason = "Expired User Account";
                        $log_by = "By API";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, REST_Controller:: HTTP_OK);
                    }
                } else {
                    $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
                }
            } else {
                $array = array('message' => 'Invalid XML Request. Please check your xml', 'code' => '205', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
            }
        }
    }

    //====================================================================//
    // User Login
    //====================================================================//
    // Validate User (Get & Post)
    function validate_user_get() {
        if (!$this->input->get('username')) {
            $message = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('username') == "") {
            $message = array('message' => 'Username can not be null!', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!$this->input->get('password')) {
            $message = array('message' => 'Missing password', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('password') == "") {
            $message = array('message' => 'Password can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Validate User Authentication
        $username = $this->input->get('username');
        $password = $this->input->get('password');
        $result_auth = $this->login_model->validateUser($username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $auth_key = $result_auth->auth_key;
            $username = $result_auth->username;
            $pr_sms_balance = $result_auth->pr_sms_balance;
            $tr_sms_balance = $result_auth->tr_sms_balance;
            // Check Status of User Account
            if ($user_status) {
                $array = array(
                    'user_id' => $user_id,
                    'auth_key' => $auth_key,
                    'username' => $username,
                    'pr_sms_balance' => $pr_sms_balance,
                    'tr_sms_balance' => $tr_sms_balance,
                    'type' => 'success'
                );
                $this->response($array, REST_Controller:: HTTP_OK);
            } else {
                $array = array(
                    'message' => "Your account temporarely disabled!",
                    'type' => 'success'
                );
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array(
                'message' => "Authentication failed!",
                'type' => 'error'
            );
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function validate_user_post() {
        if (!$this->input->post('username')) {
            $message = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('username') == "") {
            $message = array('message' => 'Username can not be null!', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if (!$this->input->post('password')) {
            $message = array('message' => 'Missing password', 'code' => '102', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('password') == "") {
            $message = array('message' => 'Password can not be null', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        // Validate User Authentication
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $result_auth = $this->login_model->validateUser($username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $auth_key = $result_auth->auth_key;
            $username = $result_auth->username;
            $pr_sms_balance = $result_auth->pr_sms_balance;
            $tr_sms_balance = $result_auth->tr_sms_balance;
            // Check Status of User Account
            if ($user_status) {
                $array = array(
                    'user_id' => $user_id,
                    'auth_key' => $auth_key,
                    'username' => $username,
                    'pr_sms_balance' => $pr_sms_balance,
                    'tr_sms_balance' => $tr_sms_balance,
                    'type' => 'success'
                );
                $this->response($array, REST_Controller:: HTTP_OK);
            } else {
                $array = array(
                    'message' => "Your account temporarely disabled!",
                    'type' => 'success'
                );
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array(
                'message' => "Authentication failed!",
                'type' => 'error'
            );
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
    // Send SMS Testing API
    function send_sms_post() {
        $auth_key = $this->input->post('authkey');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $mobiles = $this->input->post('mobiles');
        $message = $this->input->post('message');
        $sender = $this->input->post('sender');
        $route = $this->input->post('route');
        $campaign = $this->input->post('campaign');
        $flash = $this->input->post('flash');
        $unicode = $this->input->post('unicode');
        $ignoreNdnc = $this->input->post('ignoreNdnc');
        $schtime = $this->input->post('schtime');
        $response = $this->input->post('response');
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                //Post Array
                $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                    'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime);
                $result_api = $this->send_bulk_sms_model->sendTestHttpApi($array_users);
                if ($result_api) {
                    $this->response($result_api, REST_Controller:: HTTP_OK);
                } else {
                    $array = array('message' => 'Message sending failed', 'type' => 'error');
                    $this->response($array, REST_Controller:: HTTP_OK);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, REST_Controller:: HTTP_OK);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_OK);
        }
    }

    //====================================================================//
    // Validate Authuntication Key (Get & Post)
    function checking_get() {
        // Validate User Authentication 
        if (!$this->input->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->get('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $this->response($array, REST_Controller:: HTTP_OK);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    function checking_post() {
        // Validate User Authentication 
        if (!$this->input->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        if ($this->input->post('authkey') == "") {
            $array = array('message' => 'Authentication key can not be null', 'code' => '101', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_BAD_REQUEST);
        }
        $auth_key = $this->input->post('authkey');
        // Validate Auth Key
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $this->response($array, 400);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, REST_Controller:: HTTP_UNAUTHORIZED);
        }
    }

    //====================================================================//
}

?>