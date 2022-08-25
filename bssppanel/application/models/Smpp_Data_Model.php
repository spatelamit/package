<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Smpp_Data_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Get Delivery Reports
    /*
      function getSMPPUser($user_id) {
      $this->db->select('`smpp_user_id`, `smpp_username`, `smpp_password`, `smpp_pr_port`, `smpp_tr_port`, `smpp_open_port`');
      $this->db->select('`smpp_type`, `smpp_user_name`, `smpp_user_email`, `smpp_user_contact`, `smpp_user_authkey`');
      $this->db->select('`smpp_pr_balance`, `smpp_tr_balance`, `smpp_open_balance`, `smpp_pr_ugroup_id`, `smpp_tr_ugroup_id`, `smpp_open_ugroup_id`');
      $this->db->select('`creation_date`, `last_login_date`');
      $this->db->from('smpp_users');
      $this->db->where('smpp_user_id', $user_id);
      $query = $this->db->get();
      return $query->row();
     * 
     * 
     * 
      }
     */

    // Update SMPP User
    function updateSMPPUser($user_id) {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $contact = $this->input->post('contact');
        $data = array(
            'smpp_user_name' => $name,
            'smpp_user_email' => $email,
            'smpp_user_contact' => $contact
        );
        $this->db->where('smpp_user_id', $user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Get SMPP Port Info
    function getSMPPPort($port_id) {
        $this->db->select('`virtual_port_no`');
        $this->db->from('virtual_ports');
        $this->db->where('virtual_port_id', $port_id);
        $this->db->where('virtual_port_status', 1);
        $query = $this->db->get();
        return $query->row();
    }

    // Get My Transactions
    function getMyTransactions($user_id) {
        $this->db->select('`txn_route`, `txn_sms`, `txn_price`, `txn_amount`, `txn_type`, `txn_date`, `txn_description`');
        $this->db->select(' `userA`.`name` AS `from_name` , `userA`.`user_id` AS `from_user_id`');
        $this->db->select(' `userB`.`name` AS `to_name` , `userB`.`user_id` AS `to_user_id`');
        $this->db->select('`administratorsA`.`admin_name` AS `from_admin_name` , `administratorsA`.`admin_id` AS `from_admin_id`');
        $this->db->select(' `administratorsB`.`admin_name` AS `to_admin_name` , `administratorsB`.`admin_id` AS `to_admin_id`');
        $this->db->from('`smpp_transaction_logs`');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = smpp_transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = smpp_transaction_logs.txn_admin_to', 'left');
        $this->db->join('users AS userA', 'userA.user_id = smpp_transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = smpp_transaction_logs.txn_user_to', 'left');
        $this->db->where('`txn_user_from`', $user_id);
        $this->db->or_where('`txn_user_to`', $user_id);
        $this->db->order_by('`txn_log_id`', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Update New Password
    function updateSMPPPassword($user_id) {
        $current_password = $this->input->post('current_password');
        // Check Current Password
        $this->db->select('`smpp_password`');
        $this->db->from('`smpp_users`');
        $this->db->where_in('`smpp_password`', $current_password);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $new_password = $this->input->post('new_password');
            $data = array(
                'smpp_password' => $new_password
            );
            $this->db->where('smpp_user_id', $user_id);
            return $this->db->update('smpp_users', $data);
        } else {
            return false;
        }
    }

    //----------------------------------------------------------------
    //--------------------------------------------------------------------------//
    // Get SMPP Users
    function getSMPPUsers() {
        $this->db->select('smpp_user_id, smpp_username, smpp_pr_port, smpp_tr_port, smpp_open_port, smpp_type');
        $this->db->select('smpp_user_name, smpp_user_email, smpp_user_contact, smpp_user_authkey, smpp_pr_balance');
        $this->db->select('smpp_tr_balance, smpp_open_balance, creation_date, smpp_user_status');
        $this->db->select('pr_user_groups.user_group_name AS pr_user_group_name, pr_user_groups.smsc_id AS pr_smsc_id');
        $this->db->select(' tr_user_groups.user_group_name AS tr_user_group_name, tr_user_groups.smsc_id AS tr_smsc_id');
        $this->db->select(' open_user_groups.user_group_name AS tr_user_group_name, open_user_groups.smsc_id AS tr_smsc_id');
        $this->db->from('smpp_users');
        $this->db->join('administrators', 'administrators.admin_id = smpp_users.admin_id', 'left');
        $this->db->join('user_groups AS pr_user_groups', 'pr_user_groups.user_group_id = smpp_users.smpp_pr_ugroup_id', 'left');
        $this->db->join('user_groups AS tr_user_groups', 'tr_user_groups.user_group_id = smpp_users.smpp_tr_ugroup_id', 'left');
        $this->db->join('user_groups AS open_user_groups', 'open_user_groups.user_group_id = smpp_users.smpp_open_ugroup_id', 'left');
        $this->db->order_by('smpp_users.smpp_user_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Get SMPP Username
    function getSMPPUsername() {
        $username = $this->input->post('username');
        $this->db->select('smpp_username');
        $this->db->from('smpp_users');
        $this->db->where('smpp_username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->num_rows();
    }

    // Get SMPP User
    function getSMPPUser($smpp_user) {
        $this->db->select('smpp_user_id, smpp_username, smpp_pr_port, smpp_tr_port, smpp_open_port');
        $this->db->select('smpp_type, smpp_user_name, smpp_user_email, smpp_user_contact, smpp_user_authkey, smpp_pr_balance');
        $this->db->select('smpp_tr_balance, smpp_open_balance, creation_date, smpp_user_status');
        $this->db->select('smpp_pr_ugroup_id, smpp_tr_ugroup_id, smpp_open_ugroup_id, last_login_date');
        $this->db->select('`date_of_birth`, `address`, `city`, `country`, `zipcode`, `company_name`, `industry`, expiry_date');
        $this->db->from('smpp_users');
        $this->db->where('smpp_user_id', $smpp_user);
        $this->db->or_where('smpp_username', $smpp_user);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Save SMPP User
    function saveSMPPUser($admin_id) {
        $smpp_name = $this->input->post('smpp_name');
        $smpp_email = $this->input->post('smpp_email');
        $smpp_username = $this->input->post('smpp_username');
        $smpp_contact = $this->input->post('smpp_contact');
        $password = random_string('alnum', 6);
        // Generate Auth key
        $auth_key = random_string('unique', 32);
        $creation_date = date('d-m-Y h:i A');
        $data = array('admin_id' => $admin_id,
            'smpp_username' => $smpp_username,
            'smpp_password' => $password,
            'smpp_user_name' => $smpp_name,
            'smpp_user_email' => $smpp_email,
            'smpp_user_contact' => $smpp_contact,
            'smpp_user_authkey' => $auth_key,
            'creation_date' =>
            $creation_date
        );
        return $this->db->insert('smpp_users', $data);
    }

    // Save SMPP Routing
    function saveSMPPRouting($smpp_user_id) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $pr_route = $myArray[0];
        $tr_route = $myArray[1];
        $open_route = $myArray[2];
        $pr_port = $myArray[3];
        $tr_port = $myArray[4];
        $open_port = $myArray[5];
        $data = array(
            'smpp_pr_port' => $pr_port,
            'smpp_tr_port' => $tr_port,
            'smpp_open_port' => $open_port,
            'smpp_pr_ugroup_id' => $pr_route,
            'smpp_tr_ugroup_id' => $tr_route,
            'smpp_open_ugroup_id' => $open_route
        );
        $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Save SMPP User Expiry
    function saveSMPPUserExpiry($smpp_user_id, $option) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $expiry_date = $myArray[0];
        // User Account
        if ($option == 1) {
            $data = array('expiry_date' => $expiry_date);
        } elseif ($option == 2) {
            $data = array('expiry_date' => "");
        } $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Save Update SMPP User
    function saveUpdateSMPPUser($smpp_user_id) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $smpp_name = $myArray[0];
        $smpp_email = $myArray[1];
        $dob = $myArray[2];
        $city = $myArray[3];
        $country = $myArray[4];
        $zipcode = $myArray[5];
        $company = $myArray[6];
        $industry = $myArray[7];
        $address = $myArray[8];
        $data = array(
            'smpp_user_name' => $smpp_name,
            'smpp_user_email' => $smpp_email,
            'date_of_birth' => $dob,
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'zipcode' => $zipcode,
            'company_name' => $company,
            'industry' => $industry,
        );
        $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Delete SMPP User
    function deleteSMPPUser($smpp_user_id) {
        return $this->db->delete('smpp_users', array('smpp_user_id' => $smpp_user_id));
    }

    // Enable/Disable SMPP User
    function enDisSMPPUser($smpp_user_id, $status) {
        $data = array('smpp_user_status' => $status);
        $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Get SMPP SMS Logs
    function getSMPPUserSMSLogs($smpp_user_id) {
        $this->db->select('txn_route, txn_sms, txn_price, txn_amount, txn_type, txn_date, txn_description');
        $this->db->select(' userA.name AS from_name , userA.user_id AS from_user_id');
        $this->db->select(' userB.name AS to_name , userB.user_id AS to_user_id');
        $this->db->select('administratorsA.admin_name AS from_admin_name , administratorsA.admin_id AS from_admin_id');
        $this->db->select(' administratorsB.admin_name AS to_admin_name , administratorsB.admin_id AS to_admin_id');
        $this->db->from('smpp_transaction_logs');
        $this->db->join('users AS userA', 'userA.user_id = smpp_transaction_logs.txn_user_from', 'left');
        $this->db->join('users AS userB', 'userB.user_id = smpp_transaction_logs.txn_user_to', 'left');
        $this->db->join('administrators AS administratorsA', 'administratorsA.admin_id = smpp_transaction_logs.txn_admin_from', 'left');
        $this->db->join('administrators AS administratorsB', 'administratorsB.admin_id = smpp_transaction_logs.txn_admin_to', 'left');
        $this->db->where('txn_user_from', $smpp_user_id);
        $this->db->or_where('txn_user_to', $smpp_user_id);
        $this->db->order_by('txn_log_id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Change SMPP User Password
    function saveSMPPUserPassword($smpp_user_id) {
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $password = $myArray[0];
        // SMPP User Account
        $data = array(
            'smpp_password' => $password
        );
        $this->db->where('smpp_user_id', $smpp_user_id);
        return $this->db->update('smpp_users', $data);
    }

    // Get Virtual SMPP Ports
    function getVSMPPPorts($user_group_id) {
        $this->db->select('virtual_port_id, virtual_port_no');
        $this->db->from('virtual_ports');
        $this->db->where('user_group_id', $user_group_id);
        $this->db->where('virtual_port_status', '1');
        $this->db->order_by("virtual_port_no", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    // Save SMPP SMS Funds
    function saveSMPPSMSFunds($admin_id, $smpp_user_id, $pr_sms_balance, $tr_sms_balance) {
        // SMPP User SMS Balance
        $pr_sms_bal = 0;
        $tr_sms_bal = 0;
        $open_sms_bal = 0;
        $result_user = $this->getSMPPUser($smpp_user_id);
        if ($result_user) {
            $pr_sms_bal += $result_user['smpp_pr_balance'];
            $tr_sms_bal += $result_user['smpp_tr_balance'];
            $open_sms_bal += $result_user['smpp_open_balance'];
        }
        $myArray = json_decode(stripslashes($this->input->post('dataArray')), true);
        $route = $myArray[0];
        $type = $myArray[1];
        $sms_balance = $myArray[2];
        $sms_price = $myArray[3];
        $amount = $myArray[4];
        $description = $myArray [5];
        $txn_date = date('d-m-Y h:i A');
        // Calculate Remain SMS Balance
        // Promotional SMS
        if ($route == 'A') {
            $remain_pr_sms_balance1 = 0;
            $remain_pr_sms_balance2 = 0;
            $remain_tr_sms_balance1 = $tr_sms_balance;
            $remain_tr_sms_balance2 = $tr_sms_bal;
            if ($type == 'Add') {
                if ($pr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array('txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $smpp_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description
                    );
                    $this->db->insert('smpp_transaction_logs', $data);
                    $remain_pr_sms_balance1 += $pr_sms_balance - $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal + $sms_balance;
                    // Admin Account
                    $data = array('total_pr_balance' => $remain_pr_sms_balance1,
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data);
                    // User Account
                    $data = array('smpp_pr_balance' => $remain_pr_sms_balance2,
                        'smpp_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('smpp_user_id', $smpp_user_id);
                    $this->db->update('smpp_users', $data);



                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($pr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array('txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $smpp_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description
                    );
                    $this->db->insert('smpp_transaction_logs', $data);

                    $remain_pr_sms_balance1 += $pr_sms_balance + $sms_balance;
                    $remain_pr_sms_balance2 += $pr_sms_bal - $sms_balance;

// Admin Account
                    $data = array('total_pr_balance' => $remain_pr_sms_balance1,
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data);

// User Account
                    $data = array(
                        'smpp_pr_balance' => $remain_pr_sms_balance2,
                        'smpp_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('smpp_user_id', $smpp_user_id);
                    $this->db->update('smpp_users', $data);



                    return true;
                }
            }
        } elseif ($route == 'B') {
// Transactional SMS
            $remain_pr_sms_balance1 = $pr_sms_balance;
            $remain_pr_sms_balance2 = $pr_sms_bal;
            $remain_tr_sms_balance1 = 0;
            $remain_tr_sms_balance2 = 0;
            if ($type == 'Add') {
                if ($tr_sms_balance < $sms_balance) {
                    return false;
                } else {
                    $data = array('txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_from' => $admin_id,
                        'txn_user_to' => $smpp_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description
                    );
                    $this->db->insert('smpp_transaction_logs', $data);

                    $remain_tr_sms_balance1 += $tr_sms_balance - $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal + $sms_balance;

// Admin Account
                    $data = array('total_pr_balance' => $remain_pr_sms_balance1,
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id', $admin_id);
                    $this->db->update('administrators', $data);

// User Account
                    $data = array('smpp_pr_balance' => $remain_pr_sms_balance2,
                        'smpp_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('smpp_user_id', $smpp_user_id);
                    $this->db->update('smpp_users', $data);
                    return true;
                }
            } elseif ($type == 'Reduce') {
                if ($tr_sms_bal < $sms_balance) {
                    return false;
                } else {
                    $data = array('txn_route' => $route,
                        'txn_sms' => $sms_balance,
                        'txn_price' => $sms_price,
                        'txn_amount' => $amount,
                        'txn_type' => $type,
                        'txn_admin_to' => $admin_id,
                        'txn_user_from' => $smpp_user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => $description
                    );
                    $this->db->insert('smpp_transaction_logs', $data);

                    $remain_tr_sms_balance1 += $tr_sms_balance + $sms_balance;
                    $remain_tr_sms_balance2 += $tr_sms_bal - $sms_balance;

// Admin Account
                    $data = array('total_pr_balance' =>
                        $remain_pr_sms_balance1,
                        'total_tr_balance' => $remain_tr_sms_balance1
                    );
                    $this->db->where('admin_id'
                            , $admin_id);
                    $this->db->update('administrators', $data);

// User Account
                    $data = array(
                        'smpp_pr_balance' => $remain_pr_sms_balance2,
                        'smpp_tr_balance' => $remain_tr_sms_balance2
                    );
                    $this->db->where('smpp_user_id', $smpp_user_id);
                    $this->db->update('smpp_users', $data);



                    return true;
                }
            }
        }
    }

}

?>