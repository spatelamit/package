<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Code_Data_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Utility_Model', 'utility_model');
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Short/Long Codes
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Short/Long Codes- Keywords
    function checkKeyword($code_type = null, $keyword = null) {
        if ($code_type == 'short') {
            $this->db->select('`short_keyword_id`, `auth_key`, `short_keyword`, `short_number`, `short_keywords`.`user_id` AS `user_id`, `short_keyword_expiry`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `default_sender_id`, `short_code_balance`, `short_number_type`');
            $this->db->from('`short_keywords`, `short_numbers`, `users`');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            $this->db->where('`users`.`user_id`=`short_keywords`.`user_id`');
            $this->db->where('`short_keyword_status`', 1);
            $this->db->where('short_keyword', $keyword);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword_id`, `auth_key`, `long_keyword`, `long_number`, `long_keywords`.`user_id` AS `user_id`, `long_keyword_expiry`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `default_sender_id`, `long_code_balance`, `long_number_type`');
            $this->db->from('`long_keywords`, `long_numbers`, `users`');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            $this->db->where('`users`.`user_id`=`long_keywords`.`user_id`');
            $this->db->where('`long_keyword_status`', 1);
            $this->db->where('long_keyword', $keyword);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->row();
            } else {
                return false;
            }
        }
    }

    // Short/Long Codes- Keywords & Keyword Replies
    function getDashboradKeywords($code_type = null, $user_id = 0) {
        $array = array();
        if ($code_type == 'short') {
            $this->db->select('`short_keyword_id`, `user_id`, `short_keyword`, `short_number`, `short_keyword_expiry`, `short_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `short_keyword_status`, `short_number_type`');
            $this->db->from('short_keywords, short_numbers');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            $this->db->where('short_keyword_status', 1);
            $this->db->where('user_id', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result_keywords = $query->result_array();
                foreach ($result_keywords as $key => $keyword) {
                    $return_array = array();
                    $return_array['short_keyword_id'] = $keyword['short_keyword_id'];
                    $return_array['short_keyword'] = $keyword['short_keyword'];
                    $return_array['short_number'] = $keyword['short_number'];
                    $return_array['short_number_type'] = $keyword['short_number_type'];
                    $return_array['short_keyword_expiry'] = $keyword['short_keyword_expiry'];
                    $return_array['short_keyword_date'] = $keyword['short_keyword_date'];
                    $return_array['forward_email'] = $keyword['forward_email'];
                    $return_array['forward_contact'] = $keyword['forward_contact'];
                    $return_array['forward_webhook'] = $keyword['forward_webhook'];
                    // Get Replies
                    $this->db->select('`keyword_reply_id`, `keyword_reply_sender`, `keyword_reply`, `keyword_reply_status`');
                    $this->db->from('`short_keyword_replies`');
                    $this->db->where('`short_keyword_id`', $keyword['short_keyword_id']);
                    $query1 = $this->db->get();
                    if ($query1->num_rows()) {
                        $result_replies = $query1->result_array();
                        $return_array['replies'] = $result_replies;
                    } else {
                        $return_array['replies'] = 0;
                    }
                    $array[] = $return_array;
                }
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword_id`, `user_id`, `long_keyword`, `long_number`, `long_keyword_expiry`, `long_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `long_keyword_status`, `long_number_type`');
            $this->db->from('long_keywords, long_numbers');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            $this->db->where('long_keyword_status', 1);
            $this->db->where('user_id', $user_id);
            $query = $this->db->get();
            if ($query->num_rows()) {
                $result_keywords = $query->result_array();
                foreach ($result_keywords as $key => $keyword) {
                    $return_array = array();
                    $return_array['long_keyword_id'] = $keyword['long_keyword_id'];
                    $return_array['long_keyword'] = $keyword['long_keyword'];
                    $return_array['long_number'] = $keyword['long_number'];
                    $return_array['long_number_type'] = $keyword['long_number_type'];
                    $return_array['long_keyword_expiry'] = $keyword['long_keyword_expiry'];
                    $return_array['long_keyword_date'] = $keyword['long_keyword_date'];
                    $return_array['forward_email'] = $keyword['forward_email'];
                    $return_array['forward_contact'] = $keyword['forward_contact'];
                    $return_array['forward_webhook'] = $keyword['forward_webhook'];
                    // Get Replies
                    $this->db->select('`keyword_reply_id`, `keyword_reply_sender`, `keyword_reply`, `keyword_reply_status`');
                    $this->db->from('`long_keyword_replies`');
                    $this->db->where('`long_keyword_id`', $keyword['long_keyword_id']);
                    $query1 = $this->db->get();
                    if ($query1->num_rows()) {
                        $result_replies = $query1->result_array();
                        $return_array['replies'] = $result_replies;
                    } else {
                        $return_array['replies'] = 0;
                    }
                    $array[] = $return_array;
                }
            }
        }
        return $array;
    }

    // Short/Long Codes- Keywords
    function getKeywords($code_type = null, $user_id = 0, $type = 0) {
        if ($code_type == 'short') {
            $this->db->select('`short_keyword_id`, `user_id`, `short_keyword`, `short_number`, `short_keyword_expiry`, `short_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `short_keyword_status`, `short_number_type`');
            $this->db->from('short_keywords, short_numbers');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            if ($type) {
                $this->db->where('short_keyword_status', 1);
            }
            if ($user_id) {
                $this->db->where('user_id', $user_id);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword_id`, `user_id`, `long_keyword`, `long_number`, `long_keyword_expiry`, `long_keyword_date`');
            $this->db->select('`forward_email`, `forward_contact`, `forward_webhook`, `long_keyword_status`, `long_number_type`');
            $this->db->from('long_keywords, long_numbers');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            if ($type) {
                $this->db->where('long_keyword_status', 1);
            }
            if ($user_id) {
                $this->db->where('user_id', $user_id);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    // Short/Long Codes- Keyword Replies
    function getKeywordReply($code_type = null, $user_id = 0, $keyword_id = 0, $keyword = null) {
        if ($code_type == 'short') {
            $this->db->select('`keyword_reply_id`, `keyword_reply_sender`, `keyword_reply`, `keyword_reply_status`');
            $this->db->select('`short_keyword`, `short_number`, `short_number_type`');
            $this->db->from('`short_keywords`, `short_numbers`, `short_keyword_replies`');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            $this->db->where('`short_keywords`.`short_keyword_id`=`short_keyword_replies`.`short_keyword_id`');
            $this->db->where('`short_keyword_status`', 1);
            if ($user_id) {
                $this->db->where('`user_id`', $user_id);
            }
            if ($keyword_id) {
                $this->db->where('`short_keywords`.`short_keyword_id`', $keyword_id);
            }
            if ($keyword) {
                $this->db->where('`short_keywords`.`short_keyword_id`', $keyword);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                if ($user_id) {
                    return $query->result_array();
                }
                if ($keyword_id) {
                    return $query->row();
                }
                if ($keyword) {
                    return $query->result_array();
                }
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`keyword_reply_id`, `keyword_reply_sender`, `keyword_reply`, `keyword_reply_status`');
            $this->db->select('`long_keyword`, `long_number`, `long_number_type`');
            $this->db->from('`long_keywords`, `long_numbers`, `long_keyword_replies`');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            $this->db->where('`long_keywords`.`long_keyword_id`=`long_keyword_replies`.`long_keyword_id`');
            $this->db->where('`long_keyword_status`', 1);
            if ($user_id) {
                $this->db->where('`user_id`', $user_id);
            }
            if ($keyword_id) {
                $this->db->where('`long_keywords`.`long_keyword_id`', $keyword_id);
            }
            if ($keyword) {
                $this->db->where('`long_keywords`.`long_keyword_id`', $keyword);
            }
            $query = $this->db->get();
            if ($query->num_rows()) {
                if ($user_id) {
                    return $query->result_array();
                }
                if ($keyword_id) {
                    return $query->row();
                }
                if ($keyword) {
                    return $query->result_array();
                }
            } else {
                return false;
            }
        }
    }

    // Short/Long Codes- SentBox
    function getSentBox($code_type = null, $user_id = 0) {
        if ($code_type == 'short') {
            $this->db->select('`short_sentbox_id`, `sentbox_reciever`, `sentbox_date`, `short_number_type`');
            $this->db->select('`short_keyword`, `short_number`, `keyword_reply_sender`, `keyword_reply`');
            $this->db->from('`short_keywords`, `short_numbers`, `short_keyword_replies`, `short_sentbox`');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            $this->db->where('`short_keywords`.`short_keyword_id`=`short_keyword_replies`.`short_keyword_id`');
            $this->db->where('`short_keyword_replies`.`keyword_reply_id`=`short_sentbox`.`keyword_reply_id`');
            $this->db->where('`user_id`', $user_id);
            $this->db->order_by('short_sentbox_id', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_sentbox_id`, `sentbox_reciever`, `sentbox_date`, `long_number_type`');
            $this->db->select('`long_keyword`, `long_number`, `keyword_reply_sender`, `keyword_reply`');
            $this->db->from('`long_keywords`, `long_numbers`, `long_keyword_replies`, `long_sentbox`');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            $this->db->where('`long_keywords`.`long_keyword_id`=`long_keyword_replies`.`long_keyword_id`');
            $this->db->where('`long_keyword_replies`.`keyword_reply_id`=`long_sentbox`.`keyword_reply_id`');
            $this->db->where('`user_id`', $user_id);
            $this->db->order_by('long_sentbox_id', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    // Short/Long Codes- InBox
    function getInBox($code_type = null, $user_id = 0) {
        if ($code_type == 'short') {
            $this->db->select('`short_inbox_id`, `short_inbox_sender`, `short_inbox_message`, `short_inbox_date`');
            $this->db->select('`short_keyword`, `short_number`, `short_number_type`');
            $this->db->from('`short_keywords`, `short_numbers`, `short_inbox`');
            $this->db->where('`short_numbers`.`short_number_id`=`short_keywords`.`short_number_id`');
            $this->db->where('`short_keywords`.`short_keyword_id`=`short_inbox`.`short_keyword_id`');
            $this->db->where('`user_id`', $user_id);
            $this->db->where('`short_inbox_status`', 1);
            $this->db->order_by('short_inbox_date', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_inbox_id`, `long_inbox_sender`, `long_inbox_message`, `long_inbox_operator`, `long_inbox_circle`, `long_inbox_date`');
            $this->db->select('`long_keyword`, `long_number`, `long_number_type`');
            $this->db->from('`long_keywords`, `long_numbers`, `long_inbox`');
            $this->db->where('`long_numbers`.`long_number_id`=`long_keywords`.`long_number_id`');
            $this->db->where('`long_keywords`.`long_keyword_id`=`long_inbox`.`long_keyword_id`');
            $this->db->where('`user_id`', $user_id);
            $this->db->where('`long_inbox_status`', 1);
            $this->db->order_by('long_inbox_id', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    // Save Short/Long Keyword
    function saveKeyword($user_id = 0, $type = null) {
        $date = date('d-m-Y');
        if ($type == 'short') {
            $short_number = $this->input->post('short_number');
            $short_keyword = $this->input->post('short_keyword');
            $expiry_date = $this->input->post('expiry_date');
            // Check Keyword
            $this->db->select('`short_keyword_id`');
            $this->db->from('short_keywords');
            //$this->db->where('user_id', $user_id);
            $this->db->where('short_keyword', $short_keyword);
            $this->db->where('short_number_id', $short_number);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return 101;
            } else {
                // Insert New
                $data = array(
                    'user_id' => $user_id,
                    'short_keyword' => $short_keyword,
                    'short_number_id' => $short_number,
                    'short_keyword_expiry' => $expiry_date,
                    'short_keyword_date' => $date
                );
                $res = $this->db->insert('short_keywords', $data);
                if ($res) {
                    return 1;
                } else {
                    return 102;
                }
            }
        } elseif ($type == 'long') {
            $long_number = $this->input->post('long_number');
            $long_keyword = $this->input->post('long_keyword');
            $expiry_date = $this->input->post('expiry_date');
            // Check Keyword
            $this->db->select('`long_keyword_id`');
            $this->db->from('long_keywords');
            //$this->db->where('user_id', $user_id);
            $this->db->where('long_keyword', $long_keyword);
            $this->db->where('long_number_id', $long_number);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return 101;
            } else {
                // Insert New
                $data = array(
                    'user_id' => $user_id,
                    'long_keyword' => $long_keyword,
                    'long_number_id' => $long_number,
                    'long_keyword_expiry' => $expiry_date,
                    'long_keyword_date' => $date
                );
                $res = $this->db->insert('long_keywords', $data);
                $this->db->insert('copy_long_keywords', $data);
                if ($res) {
                    return 1;
                } else {
                    return 102;
                }
            }
        }
    }

    // Save Short/Long Keyword Reply
    function saveKeywordReply($user_id = 0, $type = null) {
        $keyword_id = $this->input->post('keyword_id');
        $reply_sender_id = $this->input->post('reply_sender_id');
        $reply_content = $this->input->post('reply_content');
        if ($type == 'short') {
            // Insert New
            $data = array(
                'short_keyword_id' => $keyword_id,
                'keyword_reply_sender' => $reply_sender_id,
                'keyword_reply' => $reply_content
            );
            return $this->db->insert('short_keyword_replies', $data);
        } elseif ($type == 'long') {
            // Insert New
            $data = array(
                'long_keyword_id' => $keyword_id,
                'keyword_reply_sender' => $reply_sender_id,
                'keyword_reply' => $reply_content
            );
            return $this->db->insert('long_keyword_replies', $data);
        }
    }

    // Delete Short/Long Keyword
    function deleteKeyword($type = null) {
        $keyword_id = $this->input->post('keyword_id');
        if ($type == 'short') {
            return $this->db->delete('short_keywords', array('short_keyword_id' => $keyword_id));
        } elseif ($type == 'long') {
            return $this->db->delete('long_keywords', array('long_keyword_id' => $keyword_id));
        }
    }

    // Delete Short/Long Keyword Reply
    function deleteKeywordReply($type = null) {
        $keyword_reply_id = $this->input->post('keyword_reply_id');
        if ($type == 'short') {
            return $this->db->delete('short_keyword_replies', array('keyword_reply_id' => $keyword_reply_id));
        } elseif ($type == 'long') {
            return $this->db->delete('long_keyword_replies', array('keyword_reply_id' => $keyword_reply_id));
        }
    }

    // Save Short/Long Inbox
    function saveInbox($code_type = null, $array = array()) {
        if ($code_type == 'short') {
            // Insert New
            $data = array(
                'short_inbox_sender' => $array['from'],
                'short_inbox_message' => $array['message'],
                'short_keyword_id' => $array['short_keyword_id'],
                'short_inbox_date' => $array['date'],
                'short_inbox_status' => $array['status']
            );
            return $this->db->insert('short_inbox', $data);
        } elseif ($code_type == 'long') {
            // Insert New
            $data = array(
                'long_inbox_sender' => $array['from'],
                'long_inbox_message' => $array['message'],
                'long_inbox_operator' => $array['operator'],
                'long_inbox_circle' => $array['circle'],
                'long_keyword_id' => $array['long_keyword_id'],
                'long_inbox_date' => $array['date'],
                'long_inbox_status' => $array['status']
            );
            $this->db->insert('copy_long_inbox', $data);
            return $this->db->insert('long_inbox', $data);
        }
    }

    // Save Short/Long Sentbox
    function saveSentBox($code_type = null, $array = array()) {
        if ($code_type == 'short') {
            // Insert New
            $data = array(
                'sentbox_reciever' => $array['sentbox_reciever'],
                'sentbox_date' => $array['sentbox_date'],
                'keyword_reply_id' => $array['keyword_reply_id']
            );
            return $this->db->insert('short_sentbox', $data);
        } elseif ($code_type == 'long') {
            // Insert New
            $data = array(
                'sentbox_reciever' => $array['sentbox_reciever'],
                'sentbox_date' => $array['sentbox_date'],
                'keyword_reply_id' => $array['keyword_reply_id']
            );
            return $this->db->insert('long_sentbox', $data);
        }
    }

    // Save Short/Long Forward Options
    function saveForwardOptions($user_id = 0) {
        $pk = $this->input->post('pk');
        $value = $this->input->post('value');
        $array = explode('|', $this->input->post('name'));
        $code_type = $array[0];
        $name = $array[1];
        if ($name == 'forward_email') {
            $data = array('forward_email' => $value);
        }
        if ($name == 'forward_contact') {
            $data = array('forward_contact' => $value);
        }
        if ($name == 'forward_webhook') {
            $data = array('forward_webhook' => $value);
        }
        if ($name == 'auto_reply_route') {
            $data = array('auto_reply_route' => $value);
        }
        // Virtual Numbers
        if ($code_type == 'short') {
            $this->db->where('short_keyword_id', $pk);
            return $this->db->update('short_keywords', $data);
        }
        if ($code_type == 'long') {
            $this->db->where('long_keyword_id', $pk);
            return $this->db->update('long_keywords', $data);
        }
        // Missed Call Alerts
        if ($code_type == 'missed_call') {
            $this->db->where('mc_service_id', $pk);
            return $this->db->update('missed_call_services', $data);
        }
    }

    // Save Short/Long Keyword Reply From Dashboard
    function saveKeywordReplyD($code_type = null) {
        $reply_sender = $this->input->post('reply_sender');
        $reply_content = $this->input->post('reply_content');
        $keyword_id = $this->input->post('keyword_id');
        if ($code_type == 'short') {
            // Insert New
            $data = array(
                'short_keyword_id' => $keyword_id,
                'keyword_reply_sender' => $reply_sender,
                'keyword_reply' => $reply_content
            );
            return $this->db->insert('short_keyword_replies', $data);
        } elseif ($code_type == 'long') {
            // Insert New
            $data = array(
                'long_keyword_id' => $keyword_id,
                'keyword_reply_sender' => $reply_sender,
                'keyword_reply' => $reply_content
            );
            return $this->db->insert('long_keyword_replies', $data);
        }
    }

    // Update User Balance
    function updateUserBalance($code_type = null, $user_id = 0, $remain_balance = 0) {
        if ($code_type == 'short') {
            // Update
            $data = array(
                'short_code_balance' => $remain_balance
            );
        } elseif ($code_type == 'long') {
            // Update
            $data = array(
                'long_code_balance' => $remain_balance
            );
        } elseif ($code_type == 'missed_call') {
            // Update
            $data = array(
                'missed_call_balance' => $remain_balance
            );
        }
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }

    // Check Keyword Avaialibity
    function checkKeywordAvailability($code_type = null) {
        $number = $this->input->post('number');
        $keyword = $this->input->post('keyword');
        if ($code_type == 'short') {
            $this->db->select('`short_keyword`');
            $this->db->from('`short_keywords`');
            $this->db->where('`short_number_id`', $number);
            $this->db->where('`short_keyword`', $keyword);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return false;
            } else {
                return true;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_keyword`');
            $this->db->from('`long_keywords`');
            $this->db->where('`long_number_id`', $number);
            $this->db->where('`long_keyword`', $keyword);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return false;
            } else {
                return true;
            }
        }
    }

    // Get Short/Long Numbers
    function getNumbers($code_type = null) {
        if ($code_type == 'short') {
            $this->db->select('`short_number_id`, `short_number`, `short_number_type`, `short_number_status`');
            $this->db->from('short_numbers');
            $this->db->where('short_number_type', 1);
            $this->db->where('short_number_status', 1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        } elseif ($code_type == 'long') {
            $this->db->select('`long_number_id`, `long_number`, `long_number_type`, `long_number_status`');
            $this->db->from('long_numbers');
            $this->db->where('long_number_type', 1);
            $this->db->where('long_number_status', 1);
            $query = $this->db->get();
            if ($query->num_rows()) {
                return $query->result_array();
            } else {
                return false;
            }
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Save Long Inbox
    function saveLongCode($array = array()) {
        // Insert New
        return $this->db->insert('long_codes', $array);
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts Services
    //------------------------------------------------------------------------------------------------------------------------------------------//
    // Missed Call Alerts Services
    function getMissedCallServices($user_id = 0, $service_id = 0) {
        $this->db->select('`mc_number`, `mc_service_id`, `forward_email`, `forward_contact`, `forward_webhook`, `mc_service_expiry`');
        $this->db->select('`auto_reply_sender`, `auto_reply_message`, `auto_reply_route`, `mc_service_status`');
        $this->db->from('`missed_call_numbers`, `missed_call_services`, `users`');
        $this->db->where('`users`.`user_id`=`missed_call_services`.`user_id`');
        $this->db->where('`missed_call_numbers`.`mc_number_id`=`missed_call_services`.`mc_number_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        if ($service_id) {
            $this->db->where('`missed_call_services`.`mc_service_id`', $service_id);
        }
        $query = $this->db->get();
        if ($query->num_rows()) {
            if ($service_id) {
                return $query->row_array();
            }
            return $query->result_array();
        }
        return false;
    }

    // Missed Call Alerts Inbox
    function getMissedCallInbox($user_id = 0) {
        $this->db->select('`mc_number`, `mc_inbox_id`, `mc_inbox_sender`, `mc_inbox_operator`, `mc_inbox_circle`, `mc_inbox_date`, `mc_inbox_status`');
        $this->db->from('`missed_call_numbers`, `missed_call_services`, `users`, `missed_call_inbox`');
        $this->db->where('`users`.`user_id`=`missed_call_services`.`user_id`');
        $this->db->where('`missed_call_numbers`.`mc_number_id`=`missed_call_services`.`mc_number_id`');
        $this->db->where('`missed_call_services`.`mc_service_id`=`missed_call_inbox`.`mc_service_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $this->db->order_by("mc_inbox_date", "desc");
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        }
        return false;
    }

    // Missed Call Alerts Sentbox
    function getMissedCallSentbox($user_id = 0) {
        $this->db->select('`mc_number`, `mc_sentbox_id`, `mc_sentbox_reciever`, `mc_sentbox_datetime`');
        $this->db->select('`auto_reply_sender`, `auto_reply_message`');
        $this->db->from('`missed_call_numbers`, `missed_call_services`, `users`, `missed_call_sentbox`');
        $this->db->where('`users`.`user_id`=`missed_call_services`.`user_id`');
        $this->db->where('`missed_call_numbers`.`mc_number_id`=`missed_call_services`.`mc_number_id`');
        $this->db->where('`missed_call_services`.`mc_service_id`=`missed_call_sentbox`.`mc_service_id`');
        $this->db->where('`users`.`user_id`', $user_id);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->result_array();
        }
        return false;
    }

    // Save Auto Reply From Dashboard
    function saveAutoReply() {
        $reply_sender = $this->input->post('reply_sender');
        $reply_content = $this->input->post('reply_content');
        $service_id = $this->input->post('service_id');
        $data = array(
            'auto_reply_sender' => $reply_sender,
            'auto_reply_message' => $reply_content
        );
        $this->db->where('mc_service_id', $service_id);
        return $this->db->update('missed_call_services', $data);
    }

    // Check Actual Number In DB
    function checkMissedCallNumber($actual_number = 0) {
        $this->db->select('`mc_number`, `mc_service_id`, `forward_email`, `forward_contact`, `forward_webhook`, `mc_service_expiry`');
        $this->db->select('`auto_reply_sender`, `auto_reply_message`, `auto_reply_route`, `mc_service_status`, `users`.`user_id`, `auth_key`, `missed_call_balance`');
        $this->db->from('`missed_call_numbers`, `missed_call_services`, `users`');
        $this->db->where('`users`.`user_id`=`missed_call_services`.`user_id`');
        $this->db->where('`missed_call_numbers`.`mc_number_id`=`missed_call_services`.`mc_number_id`');
        $this->db->where('`missed_call_numbers`.`mc_number`', $actual_number);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return $query->row();
        }
        return false;
    }

    // Save Alert Into Inbox
    function saveMCInSentBox($type = null, $array = null) {
        if ($type == 'inbox') {
            return $this->db->insert('missed_call_inbox', $array);
        }
        if ($type == 'sentbox') {
            return $this->db->insert('missed_call_sentbox', $array);
        }
    }
// Export All Reports
    function getAllMissedcallReports($user_id = 0, $from = null, $to = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->db->select('`mc_number_id`');
        $this->db->from('`missed_call_services`');
        $this->db->where('`user_id`', $user_id);
        //$this->db->where("`sent_sms`.`submit_date` BETWEEN '" . $from . "' AND '" . $to . "'");
        $query = $this->db->get();
        if ($query->num_rows()) {
         $result = $query->row();
         $mc_number_id = $result->mc_number_id; 
         
         $this->db->select('`mc_inbox_sender`,`mc_inbox_date`,`mc_inbox_status`');
         $this->db->from('`missed_call_inbox`');
          $this->db->where('`mc_service_id`', $mc_number_id);
          $this->db->where("`mc_inbox_date` BETWEEN '" . $from . "' AND '" . $to . "'");
          $query_date = $this->db->get();
          $results = $query_date->result_array();
          return $results;
        } else {
            return false;
        }
    }

    //------------------------------------------------------------------------------------------------------------------------------------------//
}

?>