<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Api_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Basic API
    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Get User Balance
    function getUserBalance($user_id = 0, $route = null) {
        if ($route == "A") {
            $this->db->select('`pr_sms_balance` AS `balance`');
        } elseif ($route == "B") {
            $this->db->select('`tr_sms_balance` AS `balance`');
        }
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            $row = $query->row();
            return $row->balance;
        } else {
            return false;
        }
    }

    // Get User Balance
    function changeUserPassword($user_id = 0, $npassword = null) {
        $data = array(
            'password' => md5($npassword)
        );
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Reseller API
    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Save New User
    function saveNewUser($user_array = array()) {
        $user_id = $user_array['user_id'];
        $most_parent_id = $user_array['most_parent_id'];
        $pro_user_group_id = $user_array['pro_user_group_id'];
        $tr_user_group_id = $user_array['tr_user_group_id'];
        $full_name = $user_array['full_name'];
        $username = $user_array['username'];
        $mobile = $user_array['mobile'];
        $user_email = $user_array['user_email'];
        $user_company = $user_array['user_company'];
        $user_industry = $user_array['user_industry'];
        $user_expiry = $user_array['user_expiry'];
        $number_allowed = $user_array['number_allowed'];
        $user_ratio = $user_array['user_ratio'];
        $user_fake_ratio = $user_array['user_fake_ratio'];
        $user_fail_ratio = $user_array['user_fail_ratio'];

        $user_type = "User";
        $p_sender_id_option = 0;
        $t_sender_id_option = 0;
        $keyword_option = 0;
        // Generate Password
        $password = random_string('numeric', 6);
        // Generate Auth key
        $auth_key = random_string('unique', 32);
        $creation_date = date('d-m-Y h:i A');
        if ($most_parent_id == 0) {
            $data = array(
                'ref_user_id' => $user_id,
                'most_parent_id' => $user_id,
                'name' => $full_name,
                'email_address' => $user_email,
                'contact_number' => $mobile,
                'username' => $username,
                'password' => md5($password),
                'auth_key' => $auth_key,
                'utype' => $user_type,
                'number_allowed' => $number_allowed,
                'pro_user_group_id' => $pro_user_group_id,
                'p_sender_id_option' => $p_sender_id_option,
                't_sender_id_option' => $t_sender_id_option,
                'keyword_option' => $keyword_option,
                'tr_user_group_id' => $tr_user_group_id,
                'expiry_date' => $user_expiry,
                'creation_date' => $creation_date,
                'user_ratio' => $user_ratio,
                'user_fake_ratio' => $user_fake_ratio,
                'user_fail_ratio' => $user_fail_ratio
            );
        } else {
            $data = array(
                'ref_user_id' => $user_id,
                'most_parent_id' => $most_parent_id,
                'name' => $full_name,
                'email_address' => $user_email,
                'contact_number' => $mobile,
                'username' => $username,
                'password' => md5($password),
                'auth_key' => $auth_key,
                'utype' => $user_type,
                'number_allowed' => $number_allowed,
                'pro_user_group_id' => $pro_user_group_id,
                'p_sender_id_option' => $p_sender_id_option,
                't_sender_id_option' => $t_sender_id_option,
                'keyword_option' => $keyword_option,
                'tr_user_group_id' => $tr_user_group_id,
                'expiry_date' => $user_expiry,
                'creation_date' => $creation_date,
                'user_ratio' => $user_ratio,
                'user_fake_ratio' => $user_fake_ratio,
                'user_fail_ratio' => $user_fail_ratio
            );
        }
        return $this->db->insert('users', $data);
    }

    // Get Users
    function getUsers($user_id = 0, $type = 0) {
        if ($type == 1) {
            $this->db->select('`user_id`, `name`, `username`,`auth_key`, `contact_number`, `email_address`');
            $this->db->select('`expiry_date`, `creation_date`, `user_status`, `utype`');
            $this->db->from('`users`');
            $this->db->where('`user_id`', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row_array();
            } else {
                return false;
            }
        } else {
            $this->db->select('`user_id`, `name`, `username`, `auth_key` ,`contact_number`, `email_address`');
            $this->db->select('`expiry_date`, `creation_date`, `user_status`, `utype`');
            $this->db->from('`users`');
            $this->db->where('`ref_user_id`', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    // Get Valid Parent
    function getUserParent($ref_user_id = 0) {
        $this->db->select('`user_id`, `ref_user_id`, `contact_number`');
        $this->db->from('`users`');
        $this->db->where('`user_id`', $ref_user_id);
        $this->db->or_where('`username`', $ref_user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        } else {
            return false;
        }
    }

    // Transfer SMS Balance
    function transferBalance($user_array = array(), $upr_sms_balance = 0) {

        $user_id = $user_array['user_id'];
        $ref_user_id = $user_array['ref_user_id'];
        $sms = $user_array['sms'];
        $account_type = $user_array['account_type'];
        $type = $user_array['type'];
        $price = $user_array['price'];
        $description = $user_array['description'];
        $sms_balance = $user_array['sms_balance'];
        $txn_date = date('d-m-Y h:i A');

        // Ref User SMS Balance
        $ref_pr_sms_bal = 0;
        $ref_tr_sms_bal = 0;
        $ref_stock_sms_bal = 0;
        $ref_premium_sms_bal = 0;
        $result_user = $this->user_data_model->getUser($ref_user_id);
        if ($result_user) {
            $ref_pr_sms_bal += $result_user['pr_sms_balance'];
            $ref_tr_sms_bal += $result_user['tr_sms_balance'];
            $ref_stock_sms_bal += $result_user['stock_balance'];
            $ref_premium_sms_bal += $result_user['prtodnd_balance'];
        }

        // Calculate Amount
        $amount = $sms * $price;

        // Calculate Remain SMS Balance
        if ($account_type == 'A') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;

            if ($type == 'Add') {
                if ($upr_sms_balance < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $user_id,
                    'txn_user_to' => $ref_user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_pr_sms_balance1+=$sms_balance - $sms;
                $remain_pr_sms_balance2+=$ref_pr_sms_bal + $sms;

                // Reseller Account
                $data = array(
                    'pr_sms_balance' => $remain_pr_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);
                // User Account
                $data = array(
                    'pr_sms_balance' => $remain_pr_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            } elseif ($type == 'Reduce') {
                if ($ref_pr_sms_bal < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $ref_user_id,
                    'txn_user_to' => $user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_pr_sms_balance1+=$sms_balance + $sms;
                $remain_pr_sms_balance2+=$ref_pr_sms_bal - $sms;

                // Reseller Account
                $data = array(
                    'pr_sms_balance' => $remain_pr_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'pr_sms_balance' => $remain_pr_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            }
        } elseif ($account_type == 'B') {
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($upr_sms_balance < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $user_id,
                    'txn_user_to' => $ref_user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_tr_sms_balance1+=$sms_balance - $sms;
                $remain_tr_sms_balance2+=$ref_tr_sms_bal + $sms;

                // Reseller Account
                $data = array(
                    'tr_sms_balance' => $remain_tr_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'tr_sms_balance' => $remain_tr_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            } elseif ($type == 'Reduce') {
                if ($ref_tr_sms_bal < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $ref_user_id,
                    'txn_user_to' => $user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_tr_sms_balance1+=$sms_balance + $sms;
                $remain_tr_sms_balance2+=$ref_tr_sms_bal - $sms;

                // Reseller Account
                $data = array(
                    'tr_sms_balance' => $remain_tr_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'tr_sms_balance' => $remain_tr_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            }
        } elseif ($account_type == 'C') {
            $remain_stock_sms_balance1 = 0;
            $remain_stock_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($upr_sms_balance < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $user_id,
                    'txn_user_to' => $ref_user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_stock_sms_balance1+=$sms_balance - $sms;
                $remain_stock_sms_balance2+=$ref_stock_sms_bal + $sms;

                // Reseller Account
                $data = array(
                    'stock_balance' => $remain_stock_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'stock_balance' => $remain_stock_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            } elseif ($type == 'Reduce') {
                if ($ref_stock_sms_bal < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $ref_user_id,
                    'txn_user_to' => $user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_stock_sms_balance1+=$sms_balance + $sms;
                $remain_stock_sms_balance2+=$ref_stock_sms_bal - $sms;

                // Reseller Account
                $data = array(
                    'stock_balance' => $remain_stock_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'stock_balance' => $remain_stock_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            }
        } elseif ($account_type == 'D') {
            $remain_premium_sms_balance1 = 0;
            $remain_premium_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($upr_sms_balance < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $user_id,
                    'txn_user_to' => $ref_user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_premium_sms_balance1+=$sms_balance - $sms;
                $remain_premium_sms_balance2+=$ref_premium_sms_bal + $sms;

                // Reseller Account
                $data = array(
                    'prtodnd_balance' => $remain_premium_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'prtodnd_balance' => $remain_premium_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            } elseif ($type == 'Reduce') {
                if ($ref_premium_sms_bal < $sms) {
                    return false;
                }
                $data = array(
                    'txn_route' => $account_type,
                    'txn_sms' => $sms,
                    'txn_price' => $price,
                    'txn_amount' => $amount,
                    'txn_type' => $type,
                    'txn_user_from' => $ref_user_id,
                    'txn_user_to' => $user_id,
                    'txn_date' => $txn_date,
                    'txn_description' => $description
                );
                $this->db->insert('transaction_logs', $data);

                $remain_premium_sms_balance1+=$sms_balance + $sms;
                $remain_premium_sms_balance2+=$ref_premium_sms_bal - $sms;

                // Reseller Account
                $data = array(
                    'prtodnd_balance' => $remain_premium_sms_balance1
                );
                $this->db->where('user_id', $user_id);
                $this->db->update('users', $data);

                // User Account
                $data = array(
                    'prtodnd_balance' => $remain_premium_sms_balance2
                );
                $this->db->where('user_id', $ref_user_id);
                $this->db->update('users', $data);

                return true;
            }
        }
    }

    // Manage User
    function manageUser($user_id = 0, $ref_user_id = 0, $status = 0) {
        $data = array(
            'user_status' => $status
        );
        $this->db->where('user_id', $ref_user_id);
        $this->db->where('ref_user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // User Forget Password
    function forgetPassword($new_user_id = 0, $contact_number = 0) {
        // Generate Password For User
        $password = random_string('numeric', 6);
        // Encrypt Password For User
        $password = md5($password);
        $data = array(
            'password' => $password
        );
        $this->db->where('user_id', $new_user_id);
        return $this->db->update('users', $data);
    }

    // Get Transactions
    function getTransactions($user_id = 0, $client_id) {
        $this->db->select('`txn_date`');
        $this->db->select('`userA`.`username` AS `from_username`, `userB`.`username` AS `to_username`');
        $this->db->select('`administratorsA`.`admin_username` AS `from_admin_uname`, `administratorsB`.`admin_username` AS `to_admin_uname`');
        $this->db->select('`userA`.`user_id` AS `from_user_id`, `userB`.`user_id` AS `to_user_id`');
        $this->db->select(', `administratorsA`.`admin_id` AS `from_admin_id`, `administratorsB`.`admin_id` AS `to_admin_id`');
        $this->db->select('`txn_type`, `txn_route`, `txn_sms`, `txn_price`, `txn_amount`');
        $this->db->from('`transaction_logs`');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = transaction_logs.txn_user_to', 'left');
        $this->db->where('`txn_user_from`', $user_id);

        if ($client_id) {
            $this->db->where('`txn_user_to`', $client_id);
            $this->db->or_where('`txn_user_from`', $client_id);
        } else {
            $this->db->or_where('`txn_user_to`', $user_id);
        }
        $this->db->order_by('`txn_log_id`', 'asc');
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Phonebook API
    //---------------------------------------------------------------------------------------------------------------------------------------------------//
    // Add Group
    function saveGroup($user_id = 0, $group_name = null) {
        $data = array(
            'contact_group_name' => $group_name,
            'user_id' => $user_id
        );
        return $this->db->insert('contact_groups', $data);
    }

    // Delete Group
    function deleteGroup($user_id = 0, $group_id = 0) {
        return $this->db->delete('contact_groups', array('contact_group_id' => $group_id, 'user_id' => $user_id));
    }

    // Get Groups
    function getGroups($user_id = 0, $group_id = 0) {
        if ($group_id === 0) {
            $this->db->select('`contact_group_id` AS `group_id`, `contact_group_name` AS `group_name`');
            $this->db->from('`contact_groups`');
            $this->db->where('`user_id`', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } else {
            $this->db->select('`contact_group_id`');
            $this->db->from('`contact_groups`');
            $this->db->where('`contact_group_id`', $group_id);
            $this->db->where('`user_id`', $user_id);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    // Add Contact
    function saveContact($user_id = 0, $name = null, $mobile = 0, $group_id = 0) {
        $this->db->select('`contact_id`, `mobile_number`, `contact_group_ids`');
        $this->db->from('`contacts`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`mobile_number`', $mobile);
        $query = $this->db->get();
        $count = $query->num_rows();
        if ($count > 0) {
            $result = $query->row();
            $contact_id = $result->contact_id;
            $contact_group_ids = $result->contact_group_ids;
            $group_ids_array = explode(',', $contact_group_ids);
            if (in_array($group_id, $group_ids_array)) {
                return "Already";
            } else {
                $contact_group_ids.="," . $group_id;
                $data = array(
                    'contact_group_ids' => $contact_group_ids
                );
                $this->db->where('contact_id', $contact_id);
                $this->db->update('contacts', $data);
                return 'Save';
            }
        } else {
            $data = array(
                'contact_name' => $name,
                'mobile_number' => $mobile,
                'contact_group_ids' => $group_id,
                'user_id' => $user_id
            );
            $this->db->insert('contacts', $data);
            return 'Save';
        }
    }

    // Update Contact
    function updateContact($user_id = 0, $contact_id = 0, $group_id = 0, $mobile = 0, $name = null) {
        $this->db->select('`contact_id`, `mobile_number`, `contact_group_ids`');
        $this->db->from('`contacts`');
        $this->db->where('`user_id`', $user_id);
        $this->db->where('`mobile_number`', $mobile);
        $query = $this->db->get();
        $count = $query->num_rows();
        if ($count > 0) {
            $result = $query->row();
            $contact_id = $result->contact_id;
            $contact_group_ids = $result->contact_group_ids;
            $group_ids_array = explode(',', $contact_group_ids);
            if (in_array($group_id, $group_ids_array)) {
                return "Already";
            } else {
                $contact_group_ids.="," . $group_id;
                $data = array(
                    'contact_group_ids' => $contact_group_ids
                );
                $this->db->where('contact_id', $contact_id);
                $this->db->update('contacts', $data);
                return 'Save';
            }
        }
    }

    // Get Contacts
    function getContacts($user_id = 0, $contact_id = 0) {
        if ($contact_id === FALSE) {
            $this->db->select('`contact_id`, `contact_name`, `mobile_number`, `contact_group_ids`');
            $this->db->from('`contacts`');
            $this->db->where('`user_id`', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } else {
            $this->db->select('`contact_id`, `contact_name`, `mobile_number`, `contact_group_ids`');
            $this->db->from('`contacts`');
            $this->db->where('`contact_id`', $contact_id);
            $this->db->where('`user_id`', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

    // Delete Contact
    function deleteContact($user_id = 0, $contact_id = 0) {
        return $this->db->delete('contacts', array('contact_id' => $contact_id, 'user_id' => $user_id));
    }

    // Get Group Contacts
    function getGroupContacts($user_id = 0) {
        $this->db->select('`contact_id`, `contact_name`, `mobile_number`, `contact_group_ids`');
        $this->db->from('`contacts`');
        $this->db->where('`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //get all user balance from API
    public function getAllUsersBalance($user_id = 0, $bal_user_id = 0) {
        $this->db->select('`name` , `email_address` , `contact_number` , `username` , `pr_sms_balance` , `tr_sms_balance` , `long_code_balance` , `short_code_balance` , `pr_voice_balance` , `tr_voice_balance`,`prtodnd_balance`,`stock_balance`');
        $this->db->from('users');
        $this->db->where('most_parent_id', $user_id);
        if ($bal_user_id) {
            $this->db->where('user_id', $bal_user_id);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    //---------------------------------------------------------------------------------------------------------------------------------------------------//
}

?>