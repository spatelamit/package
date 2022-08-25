<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct() {
        // Construct our parent class
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('user_data_model', '', TRUE);
        $this->load->model('voice_sms_model', '', TRUE);
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
        // Necessary Parameters
        // Validate User Authentication
        $auth_key = $this->get('authkey');
        $route = $this->get('route');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status) {
                if ($route == 'A') {
                    $this->response($result_auth->pr_sms_balance, 200);
                } elseif ($route == 'B') {
                    $this->response($result_auth->tr_sms_balance, 200);
                } elseif ($route == 'C') {
                    $this->response($result_auth->stock_balance, 200);
                } elseif ($route == 'D') {
                    $this->response($result_auth->prtodnd_balance, 200);
                }
                //$result_api = $this->api_model->getUserBalance($user_id, $route);
                //$this->response($result_api, 200);
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function balance_post() {
        // Necessary Parameters
        // Validate User Authentication
        $auth_key = $this->post('authkey');
        $route = $this->post('route');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status) {
                if ($route == 'A') {
                    $this->response($result_auth->pr_sms_balance, 200);
                } elseif ($route == 'B') {
                    $this->response($result_auth->tr_sms_balance, 200);
                } elseif ($route == 'C') {
                    $this->response($result_auth->stock_balance, 200);
                } elseif ($route == 'D') {
                    $this->response($result_auth->prtodnd_balance, 200);
                }
                //$result_api = $this->api_model->getUserBalance($user_id, $route);
                //$this->response($result_api, 200);
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Chnage User Password (Get & Post)
    function password_get() {
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $password = $result_auth->password;
            // Check Current Password
            if ($password == $cpassword) {
                // Check Status of User Account
                if ($user_status) {
                    $result_api = $this->api_model->changeUserPassword($user_id, $npassword);
                    if ($result_api) {
                        $array = array('message' => 'Password has been changed successfully', 'code' => '200', 'type' => 'success');
                        $this->response($array, 200);
                    } else {
                        $array = array('message' => 'Password changing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Wrong current password', 'code' => '304', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function password_post() {
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $cpassword = $this->post('cpassword');
        $npassword = $this->post('npassword');
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
                        $array = array('message' => 'Password has been changed successfully', 'code' => '200', 'type' => 'success');
                        $this->response($array, 200);
                    } else {
                        $array = array('message' => 'Password changing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Wrong current password', 'type' => '304', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Validate Authuntication Key (Get & Post)
    function validate_get() {
        // Validate User Authentication 
        $auth_key = $this->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $this->response($array, 200);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function validate_post() {
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $array = array('message' => 'Valid authentication key', 'code' => '200', 'type' => 'success');
            $this->response($array, 200);
        } else {
            $array = array('message' => 'Invalid authentication key', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    //====================================================================//
    // Users/Resellers API
    //====================================================================//
    // Add New User
    function add_user_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Full Name
        if (!$this->get('full_name')) {
            $array = array('message' => 'Missing full name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } else {
            $full_name = $this->get('full_name');
            $fn_array = explode(' ', $full_name);
            if (sizeof($fn_array) < 1) {
                $array = array('message' => 'Provide valid full name', 'type' => 'error');
                $this->response($array, 400);
            }
        }
        // Username
        if (!$this->get('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Mobile Number
        if (!$this->get('mobile')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->get('mobile')) != 10) {
            $array = array('message' => 'Invalid mobile number', 'type' => 'error');
            $this->response($array, 400);
        }
        // Email Address
        if (!$this->get('email')) {
            $array = array('message' => 'Missing email address', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!filter_var($this->get('email'), FILTER_VALIDATE_EMAIL)) {
            $array = array('message' => 'Invalid email address', 'type' => 'error');
            $this->response($array, 400);
        }
        // Company
        if (!$this->get('company')) {
            $array = array('message' => 'Missing company name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Industry
        if (!$this->get('industry')) {
            $array = array('message' => 'Missing industry name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Expiry Date
        if (!$this->get('expiry')) {
            $array = array('message' => 'Missing expiry date', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->get('authkey');
        $full_name = $this->get('full_name');
        $username = $this->get('username');
        $user_mobile = $this->get('mobile');
        $user_email = $this->get('email');
        $user_company = $this->get('company');
        $user_industry = $this->get('industry');
        $user_expiry = $this->get('expiry');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            // Check Username Availability
            $result_username = $this->user_data_model->getUsername($username);
            if ($result_username) {
                $array = array('message' => 'This username is already taken. Choose another', 'type' => 'error');
                $this->response($array, 400);
            } else {
                // Check Mobile Number Availability
                $result_mobile = $this->user_data_model->getMobileNumber($user_mobile);
                if ($result_mobile >= 4) {
                    $array = array('message' => 'This mobile number is already registered with us. Choose another', 'type' => 'error');
                    $this->response($array, 400);
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
                            $this->response($array, 400);
                        } else {
                            $array = array('message' => 'User creation failed', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function add_user_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Full Name
        if (!$this->post('full_name')) {
            $array = array('message' => 'Missing full name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } else {
            $full_name = $this->post('full_name');
            $fn_array = explode(' ', $full_name);
            if (sizeof($fn_array) <= 1) {
                $array = array('message' => 'Provide valid full name', 'type' => 'error');
                $this->response($array, 400);
            }
        }
        // Username
        if (!$this->post('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Mobile Number
        if (!$this->post('mobile')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->post('mobile')) != 10) {
            $array = array('message' => 'Invalid mobile number', 'type' => 'error');
            $this->response($array, 400);
        }
        // Email Address
        if (!$this->post('email')) {
            $array = array('message' => 'Missing email address', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!filter_var($this->post('email'), FILTER_VALIDATE_EMAIL)) {
            $array = array('message' => 'Invalid email address', 'type' => 'error');
            $this->response($array, 400);
        }
        // Company
        if (!$this->post('company')) {
            $array = array('message' => 'Missing company name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Industry
        if (!$this->post('industry')) {
            $array = array('message' => 'Missing industry name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Expiry Date
        if (!$this->post('expiry')) {
            $array = array('message' => 'Missing expiry date', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $full_name = $this->post('full_name');
        $username = $this->post('username');
        $user_mobile = $this->post('mobile');
        $user_email = $this->post('email');
        $user_company = $this->post('company');
        $user_industry = $this->post('industry');
        $user_expiry = $this->post('expiry');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            // Check Username Availability
            $result_username = $this->user_data_model->getUsername($username);
            if ($result_username) {
                $array = array('message' => 'This username is already taken. Choose another', 'type' => 'error');
                $this->response($array, 400);
            } else {
                // Check Mobile Number Availability
                $result_mobile = $this->user_data_model->getMobileNumber($user_mobile);
                if ($result_mobile >= 4) {
                    $array = array('message' => 'This mobile number is already registered with us. Choose another', 'type' => 'error');
                    $this->response($array, 400);
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
                            $this->response($array, 400);
                        } else {
                            $array = array('message' => 'User creation failed', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Users
    function users_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Users
            $type = 2;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, 200);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'success');
                $this->response($array, 200);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function users_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Users
            $type = 2;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, 200);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'success');
                $this->response($array, 200);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Transfer SMS Balance
    function transfer_balance_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->get('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // SMS
        if (!$this->get('sms')) {
            $array = array('message' => 'Missing number of sms', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('sms'))) {
            $array = array('message' => 'Provide valid number of sms', 'type' => 'error');
            $this->response($array, 400);
        }
        // Account Type/Route
        if (!$this->get('account_type')) {
            $array = array('message' => 'Missing account type', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif ($this->get('account_type') != "A" && $this->get('account_type') != "B" && $this->post('account_type') != "C" && $this->post('account_type') != "D") {
            $array = array('message' => 'Provide valid account type', 'type' => 'error');
            $this->response($array, 400);
        }
        // Txn Type
        if (!$this->get('type')) {
            $array = array('message' => 'Missing transaction type', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (($this->get('type') != "Add" && $this->get('type') != "1") && ($this->get('type') != "Reduce" && $this->get('type') != "2")) {
            $array = array('message' => 'Provide valid transaction type', 'type' => 'error');
            $this->response($array, 400);
        }
        // Price
        if (!$this->get('price')) {
            $array = array('message' => 'Missing price/sms', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('price'))) {
            $array = array('message' => 'Provide valid price/sms', 'type' => 'error');
            $this->response($array, 400);
        }
        // Description
        if (!$this->get('description')) {
            $array = array('message' => 'Missing description', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->get('authkey');
        $ref_user_id = $this->get('user_id');
        $sms = $this->get('sms');

        $account_type = $this->get('account_type');
        $type = $this->get('type');
        $price = $this->get('price');
        $description = $this->get('description');
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
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $pr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'B') {
                                if ($tr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $tr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'C') {
                                if ($stock_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $stock_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'D') {
                                if ($premium_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $premium_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function transfer_balance_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->post('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // SMS
        if (!$this->post('sms')) {
            $array = array('message' => 'Missing number of sms', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('sms'))) {
            $array = array('message' => 'Provide valid number of sms', 'type' => 'error');
            $this->response($array, 400);
        }
        // Account Type/Route
        if (!$this->post('account_type')) {
            $array = array('message' => 'Missing account type', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif ($this->post('account_type') != "A" && $this->post('account_type') != "B" && $this->post('account_type') != "C" && $this->post('account_type') != "D") {
            $array = array('message' => 'Provide valid account type', 'type' => 'error');
            $this->response($array, 400);
        }
        // Txn Type
        if (!$this->post('type')) {
            $array = array('message' => 'Missing transaction type', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (($this->post('type') != "Add" && $this->post('type') != "1") && ($this->post('type') != "Reduce" && $this->post('type') != "2")) {
            $array = array('message' => 'Provide valid transaction type', 'type' => 'error');
            $this->response($array, 400);
        }
        // Price
        if (!$this->post('price')) {
            $array = array('message' => 'Missing price/sms', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('price'))) {
            $array = array('message' => 'Provide valid price/sms', 'type' => 'error');
            $this->response($array, 400);
        }
        // Description
        if (!$this->post('description')) {
            $array = array('message' => 'Missing description', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $ref_user_id = $this->post('user_id');
        $sms = $this->post('sms');

        $account_type = $this->post('account_type');
        $type = $this->post('type');
        $price = $this->post('price');
        $description = $this->post('description');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            $utype = $result_auth->utype;
            $pr_sms_balance = $result_auth->pr_sms_balance;
            $tr_sms_balance = $result_auth->tr_sms_balance;
            $premium_sms_balance = $result_auth->prtodnd_balance;
            $stock_sms_balance = $result_auth->stock_balance;
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
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $pr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array, $pr_sms_balance);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'B') {
                                if ($tr_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $tr_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array, $tr_sms_balance);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'C') {
                                if ($stock_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $stock_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array, $stock_sms_balance);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            } elseif ($account_type == 'D') {
                                if ($premium_sms_balance < $sms) {
                                    $array = array('message' => 'You don\'t have sufficient balance for transfer', 'type' => 'error');
                                    $this->response($array, 400);
                                } else {
                                    // Post Array
                                    $user_array = array('user_id' => $user_id, 'ref_user_id' => $new_user_id, 'sms' => $sms, 'account_type' => $account_type,
                                        'type' => $type, 'price' => $price, 'description' => $description, 'sms_balance' => $premium_sms_balance);
                                    $result_api = $this->api_model->transferBalance($user_array, $premium_sms_balance);
                                    if ($result_api) {
                                        $array = array('message' => 'Transaction successfully. Please check account', 'type' => 'success');
                                        $this->response($array, 200);
                                    } else {
                                        $array = array('message' => 'Transaction failed. Please try after some time', 'type' => 'error');
                                        $this->response($array, 400);
                                    }
                                }
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Manage User
    function manage_user_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->get('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Status
        if (!$this->get('status')) {
            $array = array('message' => 'Missing status', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('status'))) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, 400);
        } elseif ($this->get('status') != 1 && $this->get('status') != 2) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->get('authkey');
        $ref_user_id = $this->get('user_id');
        $status = $this->get('status');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'User account status changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '301', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This is is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function manage_user_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->post('user_id')) {
            $array = array('message' => 'Missing user id/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Status
        if (!$this->post('status')) {
            $array = array('message' => 'Missing status', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('status'))) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, 400);
        } elseif ($this->post('status') != 1 && $this->post('status') != 2) {
            $array = array('message' => 'Provide valid status', 'type' => 'error');
            $this->response($array, 400);
        }
        // Validate User Authentication 
        $auth_key = $this->post('authkey');
        $ref_user_id = $this->post('user_id');
        $status = $this->post('status');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'User account status changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '301', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This is is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // User Forget Password
    function forget_password_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Username
        if (!$this->get('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $ref_user_id = $this->get('user_id');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function forget_password_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Username
        if (!$this->post('username')) {
            $array = array('message' => 'Missing username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $ref_user_id = $this->post('username');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // View Own Profile
    function profile_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get User
            $type = 1;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, 200);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function profile_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get User
            $type = 1;
            $result_users = $this->api_model->getUsers($user_id, $type);
            if ($result_users) {
                $this->response($result_users, 200);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Get Expiry Date
    function get_expiry_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $expiry_date = $result_auth->expiry_date;
            $this->response($expiry_date, 200);
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function get_expiry_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $expiry_date = $result_auth->expiry_date;
            $this->response($expiry_date, 200);
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // View User Profile
    function user_profile_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Auth Key
        if (!$this->get('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $ref_user_id = $this->get('user_id');
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
                            $this->response($result_users, 200);
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user does not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function user_profile_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->post('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $ref_user_id = $this->post('user_id');
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
                            $this->response($result_users, 200);
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user does not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Change User Password
    function change_password_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->get('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User Password
        if (!$this->get('user_password')) {
            $array = array('message' => 'Missing user new password', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $ref_user_id = $this->get('user_id');
        $user_password = $this->get('user_password');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function change_password_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User ID
        if (!$this->post('user_id')) {
            $array = array('message' => 'Missing user ID/username', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // User Password
        if (!$this->post('user_password')) {
            $array = array('message' => 'Missing user new password', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $ref_user_id = $this->post('user_id');
        $user_password = $this->post('user_password');
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
                                $this->response($array, 200);
                            } else {
                                $array = array('message' => 'Password changing failed', 'type' => 'error');
                                $this->response($array, 400);
                            }
                        } else {
                            $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This user is not belongs to you. Please check your user lists', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This user does not exists', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'You are not a reseller', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Get Transactions
    function transactions_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $client_id = $this->post('client_id');
        $auth_key = $this->get('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Transactions
            $result_txns = $this->api_model->getTransactions($user_id, $client_id);
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
                    elseif ($row_txn['txn_route'] == 'C')
                        $txn_array[$key]['txn_route'] = "Stock";
                    elseif ($row_txn['txn_route'] == 'D')
                        $txn_array[$key]['txn_route'] = "Premium";

                    $txn_array[$key]['txn_sms'] = $row_txn['txn_sms'];
                    $txn_array[$key]['txn_price'] = $row_txn['txn_price'];
                    $txn_array[$key]['txn_amount'] = $row_txn['txn_amount'];
                }
                $this->response($txn_array, 400);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function transactions_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $client_id = $this->post('client_id');
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Transactions
            $result_txns = $this->api_model->getTransactions($user_id, $client_id);
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
                    elseif ($row_txn['txn_route'] == 'C')
                        $txn_array[$key]['txn_route'] = "Stock";
                    elseif ($row_txn['txn_route'] == 'D')
                        $txn_array[$key]['txn_route'] = "Premium";

                    $txn_array[$key]['txn_sms'] = $row_txn['txn_sms'];
                    $txn_array[$key]['txn_price'] = $row_txn['txn_price'];
                    $txn_array[$key]['txn_amount'] = $row_txn['txn_amount'];
                }
                $this->response($txn_array, 400);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Get all user balance from api 
    function all_user_balance_get() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Transactions
            //$result_txns = $this->api_model->getAllUsersBalance($user_id);
            // if ($result_txns) {
            //     $txn_array = array();
            $this->response($user_id, 400);
        }
    }

    function all_user_balance_post() {

        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $bal_user_id = $this->input->post('bal_user_id');
            //Get balance
            $result_user_balance = $this->api_model->getAllUsersBalance($user_id, $bal_user_id);
            if ($result_user_balance) {
                $txn_array = array();
                foreach ($result_user_balance as $key => $result_all_balance) {
                    $txn_array[$key]['name'] = $result_all_balance['name'];
                    $txn_array[$key]['email_address'] = $result_all_balance['email_address'];
                    $txn_array[$key]['contact_number'] = $result_all_balance['contact_number'];
                    $txn_array[$key]['username'] = $result_all_balance['username'];
                    $txn_array[$key]['pr_sms_balance'] = $result_all_balance['pr_sms_balance'];
                    $txn_array[$key]['stock_balance'] = $result_all_balance['stock_balance'];
                    $txn_array[$key]['prtodnd_balance'] = $result_all_balance['prtodnd_balance'];
                    $txn_array[$key]['tr_sms_balance'] = $result_all_balance['tr_sms_balance'];
                    $txn_array[$key]['long_code_balance'] = $result_all_balance['long_code_balance'];
                    $txn_array[$key]['short_code_balance'] = $result_all_balance['short_code_balance'];
                    $txn_array[$key]['pr_voice_balance'] = $result_all_balance['pr_voice_balance'];
                    $txn_array[$key]['tr_voice_balance'] = $result_all_balance['tr_voice_balance'];
                }
                $this->response($txn_array, 400);
            } else {
                $array = array('message' => 'No user exists', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    //====================================================================//
    // Phonebook API
    //====================================================================//
    // Add Group
    function add_group_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group Name
        if (!$this->get('group_name')) {
            $array = array('message' => 'Missing group name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $group_name = $this->get('group_name');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->saveGroup($user_id, $group_name);
                if ($result_api) {
                    $array = array('message' => 'Group created succesfully', 'type' => 'success');
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'Group creation failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function add_group_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group Name
        if (!$this->post('group_name')) {
            $array = array('message' => 'Missing group name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $group_name = $this->post('group_name');
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
            // Check Status of User Account
            if ($user_status == 1) {
                $result_api = $this->api_model->saveGroup($user_id, $group_name);
                if ($result_api) {
                    $array = array('message' => 'Group created succesfully', 'type' => 'success');
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'Group creation failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Delete Group
    function delete_group_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->get('group_id')) {
            $array = array('message' => 'Missing group Id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('group_id'))) {
            $array = array('message' => 'Provide valid group Id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $group_id = $this->get('group_id');
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
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'Group deletion failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function delete_group_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->post('group_id')) {
            $array = array('message' => 'Missing group Id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('group_id'))) {
            $array = array('message' => 'Provide valid group Id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $group_id = $this->post('group_id');
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
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'Group deletion failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // List Groups
    function list_groups_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Groups
            $result_groups = $this->api_model->getGroups($user_id);
            if ($result_groups) {
                $this->response($result_groups, 200);
            } else {
                $array = array('message' => 'No groups exists', 'type' => 'success');
                $this->response($array, 200);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function list_groups_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        // Validate User Authentication 
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key);
        if ($result_auth) {
            $user_id = $result_auth->user_id;
            // Get Groups
            $result_groups = $this->api_model->getGroups($user_id, 0);
            if ($result_groups) {
                $this->response($result_groups, 200);
            } else {
                $array = array('message' => 'No groups exists', 'type' => 'success');
                $this->response($array, 200);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Add New Contact
    function add_contact_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Name
        if (!$this->get('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Mobile No
        if (!$this->get('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->get('mobile_no')) != 10) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group Id
        if (!$this->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $name = $this->get('name');
        $mobile = $this->get('mobile_no');
        $group_id = $this->get('group_id');
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
                            $this->response($array, 400);
                        } elseif ($result_api == 'Save') {
                            $array = array('message' => 'Contact has been inserted successfully', 'type' => 'success');
                            $this->response($array, 200);
                        }
                    } else {
                        $array = array('message' => 'Contact insertion failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function add_contact_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Name
        if (!$this->post('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Mobile No
        if (!$this->post('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->post('mobile_no')) != 10) {
            $array = array('message' => 'Provide valid mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group Id
        if (!$this->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $name = $this->post('name');
        $mobile = $this->post('mobile_no');
        $group_id = $this->post('group_id');
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
                            $this->response($array, 400);
                        } elseif ($result_api == 'Save') {
                            $array = array('message' => 'Contact has been inserted successfully', 'type' => 'success');
                            $this->response($array, 200);
                        }
                    } else {
                        $array = array('message' => 'Contact insertion failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Edit Contact
    function edit_contact_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact ID
        if (!$this->get('contact_id')) {
            $array = array('message' => 'Missing contact id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Number
        if (!$this->get('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->get('mobile_no') != 10)) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Name
        if (!$this->get('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $contact_id = $this->get('contact_id');
        $group_id = $this->get('group_id');
        $mobile = $this->get('mobile_no');
        $name = $this->get('name');
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
                                $this->response($array, 400);
                            } elseif ($result_api == 'Save') {
                                $array = array('message' => 'Contact updated successfully', 'type' => 'success');
                                $this->response($array, 200);
                            }
                        } else {
                            $array = array('message' => 'Contact updation failed', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function edit_contact_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact ID
        if (!$this->post('contact_id')) {
            $array = array('message' => 'Missing contact id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Number
        if (!$this->post('mobile_no')) {
            $array = array('message' => 'Missing mobile number', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('mobile_no'))) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->post('mobile_no') != 10)) {
            $array = array('message' => 'Provide valid mobile number', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Name
        if (!$this->post('name')) {
            $array = array('message' => 'Missing contact name', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $contact_id = $this->post('contact_id');
        $group_id = $this->post('group_id');
        $mobile = $this->post('mobile_no');
        $name = $this->post('name');
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
                                $this->response($array, 400);
                            } elseif ($result_api == 'Save') {
                                $array = array('message' => 'Contact updated successfully', 'type' => 'success');
                                $this->response($array, 200);
                            }
                        } else {
                            $array = array('message' => 'Contact updation failed', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Delete Contact
    function delete_contact_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Id
        if (!$this->get('contact_id')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $contact_id = $this->get('contact_id');
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
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function delete_contact_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Contact Id
        if (!$this->post('contact_id')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('contact_id'))) {
            $array = array('message' => 'Provide valid contact id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $contact_id = $this->post('contact_id');
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
                    $this->response($array, 200);
                } else {
                    $array = array('message' => 'This contact is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // List Contacts
    function list_contacts_get() {
        // Auth Key
        if (!$this->get('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->get('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->get('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->get('authkey');
        $group_id = $this->get('group_id');
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
                        $this->response($contact_array, 200);
                    } else {
                        $array = array('message' => 'No contact exists', 'type' => 'success');
                        $this->response($array, 200);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function list_contacts_post() {
        // Auth Key
        if (!$this->post('authkey')) {
            $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Group ID
        if (!$this->post('group_id')) {
            $array = array('message' => 'Missing group id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (!is_numeric($this->post('group_id'))) {
            $array = array('message' => 'Provide valid group id', 'type' => 'error');
            $this->response($array, 400);
        }
        $auth_key = $this->post('authkey');
        $group_id = $this->post('group_id');
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
                        $this->response($contact_array, 200);
                    } else {
                        $array = array('message' => 'No contact exists', 'type' => 'success');
                        $this->response($array, 200);
                    }
                } else {
                    $array = array('message' => 'This group is not created by you', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    //====================================================================//
    // Send SMS API
    //====================================================================//
    // Send SMS Through HTTP API (Get & Post)
    function send_http1_get() {
        // Mobile Number(s)
        if (!$this->get('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } else {
            $mobiles = $this->get('mobiles');
        }
        // Message
        if (!$this->get('message')) {
            $array = array('message' => 'Missing message', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Sender Id
        if (!$this->get('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->get('sender')) != 6) {
            $array = array('message' => 'Sender id must be 6 characters long', 'type' => 'error');
            $this->response($array, 400);
        }
        // Route
        if (!$this->get('route')) {
            $array = array('message' => 'Missing route', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $route = strtolower($this->get('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '201', 'type' => 'error');
            $this->response($array, 400);
        }

        $auth_key = $this->get('authkey');
        $username = $this->get('username');
        $password = $this->get('password');
        $mobiles = $this->get('mobiles');
        $message = $this->get('message');
        $sender = $this->get('sender');
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }

        // Response (string/json/xml)
        if ($this->get('response'))
            $response = $this->get('response');
        else
            $response = "string";

        // Campaign
        if ($this->get('campaign'))
            $campaign = $this->get('campaign');
        else
            $campaign = "New Campaign";

        // Flash SMS
        if ($this->get('flash'))
            $flash = $this->get('flash');
        else
            $flash = 0;

        // Unicode SMS
        if ($this->get('unicode'))
            $unicode = $this->get('unicode');
        else
            $unicode = 0;

        // Ignore National Do Not Call Number
        if ($this->get('ignoreNdnc'))
            $ignoreNdnc = $this->get('ignoreNdnc');
        else
            $ignoreNdnc = 0;

        // Schdeule Time
        if ($this->get('schtime'))
            $schtime = $this->get('schtime');
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
                    $this->response($result_api, 200);
                } else {
                    $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function send_http1_post() {
        // Mobile Number(s)
        if (!$this->post('mobiles')) {
            $array = array('message' => 'Missing mobile number(s)', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } else {
            $mobiles = $this->post('mobiles');
        }
        // Message
        if (!$this->post('message')) {
            $array = array('message' => 'Missing message', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        // Sender Id
        if (!$this->post('sender')) {
            $array = array('message' => 'Missing sender id', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } elseif (strlen($this->post('sender')) != 6) {
            $array = array('message' => 'Sender id must be 6 characters long', 'type' => 'error');
            $this->response($array, 400);
        }
        // Route
        if (!$this->post('route')) {
            $array = array('message' => 'Missing route', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        }
        $route = strtolower($this->post('route'));
        if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4') {
            $array = array('message' => 'Invalid route', 'code' => '201', 'type' => 'error');
            $this->response($array, 400);
        }

        $auth_key = $this->post('authkey');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobiles = $this->post('mobiles');
        $message = $this->post('message');
        $sender = $this->post('sender');
        if ($route == 'a' || $route == 'default' || $route == 1) {
            $route = 'A';
        } elseif ($route == 'b' || $route == 'template' || $route == 4) {
            $route = 'B';
        }

        // Response (string/json/xml)
        if ($this->post('response'))
            $response = $this->post('response');
        else
            $response = "string";

        // Campaign
        if ($this->post('campaign'))
            $campaign = $this->post('campaign');
        else
            $campaign = "New Campaign";

        // Flash SMS
        if ($this->post('flash'))
            $flash = $this->post('flash');
        else
            $flash = 0;

        // Unicode SMS
        if ($this->post('unicode'))
            $unicode = $this->post('unicode');
        else
            $unicode = 0;

        // Ignore National Do Not Call Number
        if ($this->post('ignoreNdnc'))
            $ignoreNdnc = $this->post('ignoreNdnc');
        else
            $ignoreNdnc = 0;

        // Schdeule Time
        if ($this->post('schtime'))
            $schtime = $this->post('schtime');
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
                    $this->response($result_api, 200);
                } else {
                    $array = array('message' => 'Message sendiing failed', 'type' => 'error', 'code' => '001');
                    $this->response($array, 400);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function send_http_get() {
        $auth_key = $this->get('authkey');
        $username = $this->get('username');
        $password = $this->get('password');
        $mobiles = $this->get('mobiles');
        $message = $this->get('message');
        $sender = $this->get('sender');
        $route = $this->get('route');
        $campaign = $this->get('campaign');
        $flash = $this->get('flash');
        $unicode = $this->get('unicode');
        $ignoreNdnc = $this->get('ignoreNdnc');
        $schtime = $this->get('schtime');
        $response = $this->get('response');
        $client_ip_address = $this->get('client_ip_address');
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
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
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $message);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, 400);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function send_http_post() {
        $auth_key = $this->post('authkey');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobiles = $this->post('mobiles');
        $message = $this->post('message');
        $sender = $this->post('sender');
        $route = $this->post('route');
        $campaign = $this->post('campaign');
        $flash = $this->post('flash');
        $unicode = $this->post('unicode');
        $ignoreNdnc = $this->post('ignoreNdnc');
        $schtime = $this->post('schtime');
        $response = $this->post('response');
        $client_ip_address = $this->post('client_ip_address');
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
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
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $message);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, 400);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    // Send SMS Through HTTP API (XML)
    function send_xml_get() {
        // XML Request
        if (!$this->get('xml')) {
            $array = array('message' => 'Missing xml request', 'type' => 'error');
            $this->response($array, 400);
        } else {
            // Get XML File Request
            $xml_file = urldecode($this->get('xml'));
            $xml = new SimpleXMLElement($xml_file);
            $client_ip_address = $this->get('client_ip_address');

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
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                $this->response($array, 400);
            }

            // Sender Id
            if ($xml->SENDER) {
                $sender = $xml->SENDER;
                if ($sender == "") {
                    $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                    $this->response($array, 400);
                } elseif (strlen($sender) < 6 || strlen($sender) > 6) {
                    $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                $this->response($array, 400);
            }

            // Route
            if ($xml->ROUTE) {
                $route = $xml->ROUTE;
                if ($route == "") {
                    $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                    $this->response($array, 400);
                }
                $route = strtolower($route);
                if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4' && $route != 'c' && $route != '2' && $route != 'd' && $route != '3') {
                    $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                $this->response($array, 400);
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
                    $this->response($array, 400);
                } elseif ($check > 0) {
                    $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
                $this->response($array, 400);
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
            } elseif ($route == 'c' || $route == 2) {
                $route = 'C';
            } elseif ($route == 'd' || $route == 3) {
                $route = 'D';
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
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $reason = "Expired User Account";
                    $log_by = "By API";
                    $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                    $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
                $this->response($array, 400);
            }
        }
    }

    function send_xml_post() {
        // XML Request
        if (!$this->post('xml')) {
            $array = array('message' => 'Missing xml request', 'code' => '101', 'type' => 'error');
            $this->response($array, 400);
        } else {
            // Get XML Request
            $xml_data = urldecode($this->post('xml'));
            $client_ip_address = $this->post('client_ip_address');
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
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Missing authentication key', 'code' => '101', 'type' => 'error');
                    $this->response($array, 400);
                }

                // Sender Id
                if ($xml->SENDER) {
                    $sender = $xml->SENDER;
                    if ($sender == "") {
                        $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                        $this->response($array, 400);
                    } elseif (strlen($sender) != 6) {
                        $array = array('message' => 'Invalid sender id', 'code' => '203', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Missing sender id', 'code' => '107', 'type' => 'error');
                    $this->response($array, 400);
                }

                // Route
                if ($xml->ROUTE) {
                    $route = $xml->ROUTE;
                    if ($route == "") {
                        $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                        $this->response($array, 400);
                    }
                    $route = strtolower($route);
                    if ($route != 'a' && $route != 'default' && $route != '1' && $route != 'b' && $route != 'template' && $route != 'b' && $route != '4' && $route != 'c' && $route != '2' && $route != 'd' && $route != '3') {
                        $array = array('message' => 'Invalid route', 'code' => '202', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Missing route', 'code' => '102', 'type' => 'error');
                    $this->response($array, 400);
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
                        $this->response($array, 400);
                    } elseif ($check > 0) {
                        $array = array('message' => 'Invalid mobile number(s)', 'code' => '204', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Missing message', 'code' => '106', 'type' => 'error');
                    $this->response($array, 400);
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
                } elseif ($route == 'c' || $route == 2) {
                    $route = 'C';
                } elseif ($route == 'd' || $route == 3) {
                    $route = 'D';
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
                            $this->response($result_api, 200);
                        } else {
                            $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                            $this->response($array, 400);
                        }
                    } else {
                        $reason = "Expired User Account";
                        $log_by = "By API";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                        $this->response($array, 400);
                    }
                } else {
                    $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $array = array('message' => 'Invalid XML Request. Please check your xml', 'code' => '205', 'type' => 'error');
                $this->response($array, 400);
            }
        }
    }

    //====================================================================//
    // User Login
    //====================================================================//
    // Validate User (Get & Post)
    function validate_user_get() {
        // Validate User Authentication
        $username = $this->get('username');
        $password = $this->get('password');
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
                $this->response($array, 200);
            } else {
                $array = array(
                    'message' => "Your account temporarely disabled!",
                    'type' => 'error'
                );
                $this->response($array, 400);
            }
        } else {
            $array = array(
                'message' => "Authentication failed!",
                'type' => 'error'
            );
            $this->response($array, 400);
        }
    }

    function validate_user_post() {
        // Validate User Authentication
        $username = $this->post('username');
        $password = $this->post('password');
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
                $this->response($array, 200);
            } else {
                $array = array(
                    'message' => "Your account temporarely disabled!",
                    'type' => 'error'
                );
                $this->response($array, 400);
            }
        } else {
            $array = array(
                'message' => "Authentication failed!",
                'type' => 'error'
            );
            $this->response($array, 400);
        }
    }

    //====================================================================//
    // Send SMS Testing API
    function send_sms_post() {
        $auth_key = $this->post('authkey');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobiles = $this->post('mobiles');
        $message = $this->post('message');
        $sender = $this->post('sender');
        $route = $this->post('route');
        $campaign = $this->post('campaign');
        $flash = $this->post('flash');
        $unicode = $this->post('unicode');
        $ignoreNdnc = $this->post('ignoreNdnc');
        $schtime = $this->post('schtime');
        $response = $this->post('response');
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
                    $this->response($result_api, 200);
                } else {
                    $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                    $this->response($array, 400);
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    public function send_voice_http_get() {
        $auth_key = $this->get('authkey');
        $username = $this->get('username');
        $password = $this->get('password');
        $mobiles = $this->get('mobiles');
        $message = $this->get('message');
        $duration = $this->get('duration');
        $sender = $this->get('sender');
        $route = $this->get('route');
        $campaign = $this->get('campaign');
        $schtime = $this->get('schtime');
        $response = $this->get('response');
        $client_ip_address = $this->get('client_ip_address');

// Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);

        if ($result_auth) {
            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
// Check Status of User Account
            if ($user_status == 1) {
                $flag = 1;

                if ($flag) {
//Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'duration' => $duration, 'campaign' => $campaign, 'schtime' => $schtime, 'client_ip_address' => $client_ip_address);
                    $result_api = $this->voice_sms_model->sendHttpVoiceMessage($array_users);
                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    public function send_voice_http_post() {
        $auth_key = $this->post('authkey');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobiles = $this->post('mobiles');
        $message = $this->post('message');
        $duration = $this->post('duration');
        $sender = $this->post('sender');
        $route = $this->post('route');
        $campaign = $this->post('campaign');
        $schtime = $this->post('schtime');
        $response = $this->post('response');
        $client_ip_address = $this->post('client_ip_address');

// Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);

        if ($result_auth) {

            $user_id = $result_auth->user_id;
            $user_status = $result_auth->user_status;
// Check Status of User Account
            if ($user_status == 1) {
                $flag = 1;

                if ($flag) {
//Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'duration' => $duration, 'campaign' => $campaign, 'schtime' => $schtime, 'client_ip_address' => $client_ip_address);
                    $result_api = $this->voice_sms_model->sendHttpVoiceMessage($array_users);

                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    public function save_http_user_post() {

        $name = $this->post('name');

        $contact = $this->post('contact');
        $email = $this->post('email');

        $array_users = array(
            'name' => $name,
            'email' => $email,
            'contact' => $contact,
        );
        $result_api = $response = $this->login_model->saveHttpBulkSMSUser($array_users);
        if ($response['status'] == '200') {
            $this->session->set_flashdata('message', 'Success: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-success success');
        } else {
            $this->session->set_flashdata('message', 'Error: ' . $response['message']);
            $this->session->set_flashdata('message_type', 'alert-danger danger');
        }
        redirect('signup', 'refresh');
    }

    function send_regional_http_get() {
        $auth_key = $this->get('authkey');
        $username = $this->get('username');
        $password = $this->get('password');
        $mobiles = $this->get('mobiles');
        $message = $this->get('message');
        $sender = $this->get('sender');
        $route = $this->get('route');
        $language = $this->get('language');
        $campaign = $this->get('campaign');
        $flash = $this->get('flash');
        $unicode = $this->get('unicode');
        $ignoreNdnc = $this->get('ignoreNdnc');
        $schtime = $this->get('schtime');
        $response = $this->get('response');
        $client_ip_address = $this->get('client_ip_address');
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
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
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $message);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, 400);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'language' => $language, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestRegionalHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sending failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

    function send_regional_http_post() {
        $auth_key = $this->post('authkey');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobiles = $this->post('mobiles');
        $message = $this->post('message');
        $sender = $this->post('sender');
        $route = $this->post('route');
        $language = $this->post('language');
        $campaign = $this->post('campaign');
        $flash = $this->post('flash');
        $unicode = $this->post('unicode');
        $ignoreNdnc = $this->post('ignoreNdnc');
        $schtime = $this->post('schtime');
        $response = $this->post('response');
        $client_ip_address = $this->post('client_ip_address');
        // Validate User Authentication
        $result_auth = $this->user_data_model->checkUserAuthKey($auth_key, $username, $password);
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
                    $response_black_keywords = $this->sms_model->checkBlackKeywords($admin_id, $user_id, $ref_user_id, $utype, $message);
                    if ($response_black_keywords) {
                        $flag = 0;
                        $array = array('message' => 'Black listed content in message', 'code' => '206', 'type' => 'error');
                        $this->response($array, 400);
                    } else {
                        $flag = 1;
                    }
                }
                if ($flag) {
                    //Post Array
                    $array_users = array('user_id' => $user_id, 'mobiles' => $mobiles, 'message' => $message, 'sender' => $sender, 'route' => $route,
                        'response' => $response, 'unicode' => $unicode, 'campaign' => $campaign, 'language' => $language, 'flash' => $flash, 'ignorendnc' => $ignoreNdnc, 'schtime' => $schtime,
                        'client_ip_address' => $client_ip_address);
                    $result_api = $this->send_bulk_sms_model->sendTestRegionalHttpApi($array_users);
                    if ($result_api) {
                        $this->response($result_api, 200);
                    } else {
                        $array = array('message' => 'Message sendiing failed', 'type' => 'error');
                        $this->response($array, 400);
                    }
                }
            } else {
                $reason = "Expired User Account";
                $log_by = "By API";
                $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                $array = array('message' => 'Expired user account', 'code' => '302', 'type' => 'error');
                $this->response($array, 400);
            }
        } else {
            $array = array('message' => 'Authentication failed', 'code' => '301', 'type' => 'error');
            $this->response($array, 400);
        }
    }

 

    //====================================================================//
}

?>