<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Export extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('user_data_model', '', TRUE);
        $this->load->model('sms_model', '', TRUE);
        $this->load->model('code_data_model', '', TRUE);
        $this->load->model('Admin_Data_Model', 'admin_data_model');
    }

    // Export Delivery Report export_schedule_reports
    public function index($campaign_id = null) {
        $session_data = $this->session->userdata('logged_in');
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $user_id = $session_data['user_id'];
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $sent_sms = $this->user_data_model->getSentSMS($campaign_id);
        $total_credits = $this->user_data_model->getCreditsSMS($campaign_id);
        $sender_id = $total_credits->sender_id;
        $credits = $total_credits->total_credits;
        $route = $total_credits->route;
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Mobile Number,SenderId ,Message,route, Status, Submit Date, Done Date,Credits";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($sent_sms) {
            foreach ($sent_sms as $sms) {
                $length = $sms['msg_length'];
                $line = $i;
                $line .= "," . $sms['mobile_no'];
                $line .= "," . $sender_id;
                $line .= "," . urldecode($sms['message']);
                $line .= "," . $route;
                if ($sms['status'] == "1") {
                    $status = "Delivered";
                } elseif ($sms['status'] == "2") {
                    $status = "Failed";
                } elseif ($sms['status'] == "31" || $sms['status'] == "4") {
                    $status = "Pending";
                } elseif ($sms['status'] == "8") {
                    $status = "Submit";
                } elseif ($sms['status'] == "DND") {
                    $status = "DND";
                } elseif ($sms['status'] == "16") {
                    $status = "Rejected from operator";
                } elseif ($sms['status'] == "Blocked") {
                    $status = "Block By Operator";
                } elseif ($sms['status'] == "3") {
                    $status = "Sent";
                } elseif ($sms['status'] == "48") {
                    $status = "Landline";
                } else {
                    $status = $sms['status'];
                }


                $line .= "," . $status;
                $line .= "," . $sms['submit_date'];
                $line .= "," . $sms['done_date'];
                $line .= "," . $credits;

                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->row();
        $email = $result->email_address;
        $name = $result->name;
        $username = $result->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $from_email = $email;
        $from_name = "DLR export";
        $mail = "DLR export";
        $to_email = $email;
        $subject = "DLR export";
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'];
        $csvFile = $actual_link . "/Reports/$filename.csv";
        $message = "link for download delivery report.<br>" . $csvFile;
        // Load Email Library For Sending E-mails
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($mail);
        $this->email->bcc($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
////        $from_email_admin = "tiwari12mayank@gmail.com";
////        $from_name = "DLR export";
////        $mail = "DLR export";
////        $to_email = $from_email_admin;
////        $subject = "DLR export";
////        $actual_link = 'http://' . $_SERVER['HTTP_HOST'];
////        $csvFile = $actual_link . "/Reports/$filename.csv";
////        $message = "User Export Details <br><br> Name : " . $name . "<br>" . "Username : " . $username . "<br>" . "IP : " . $ip;
////
////        $this->load->library('email');
////        $config['mailtype'] = 'html';
////        $this->email->initialize($config);
////        $this->email->from($from_email, $from_name);
////        $this->email->to($mail);
////        $this->email->bcc($to_email);
////        $this->email->subject($subject);
////        $this->email->message($message);
////        $this->email->send();
////        redirect('user/text_delivery_reports', 'refresh');
        //$this->downloadExcel($filename);
        redirect('user/text_delivery_reports', 'refresh');
    }

// Export Delivery Report export_schedule_reports

    public function export_schedule_reports($campaign_id = null) {
        $session_data = $this->session->userdata('logged_in');
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        $user_id = $session_data['user_id'];
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $sent_sms = $this->user_data_model->getSentSMS($campaign_id);
        $total_credits = $this->user_data_model->getCreditsSMS($campaign_id);
        $sender_id = $total_credits->sender_id;
        $credits = $total_credits->total_credits;
        $route = $total_credits->route;
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Mobile Number,SenderId ,Message,route, Status, Submit Date, Done Date,Credits";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($sent_sms) {
            foreach ($sent_sms as $sms) {
                $length = $sms['msg_length'];
                $line = $i;
                $line .= "," . $sms['mobile_no'];
                $line .= "," . $sender_id;
                $line .= "," . urldecode($sms['message']);
                $line .= "," . $route;
                if ($sms['status'] == "1") {
                    $status = "Delivered";
                } elseif ($sms['status'] == "2") {
                    $status = "Failed";
                } elseif ($sms['status'] == "31" || $sms['status'] == "4") {
                    $status = "Pending";
                } elseif ($sms['status'] == "8") {
                    $status = "Submit";
                } elseif ($sms['status'] == "DND") {
                    $status = "DND";
                } elseif ($sms['status'] == "16") {
                    $status = "Rejected from operator";
                } elseif ($sms['status'] == "Blocked") {
                    $status = "Block By Operator";
                } elseif ($sms['status'] == "3") {
                    $status = "Sent";
                } elseif ($sms['status'] == "48") {
                    $status = "Landline";
                } else {
                    $status = $sms['status'];
                }


                $line .= "," . $status;
                $line .= "," . $sms['submit_date'];
                $line .= "," . $sms['done_date'];
                $line .= "," . $credits;

                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->row();
        $email = $result->email_address;
        $name = $result->name;
        $username = $result->username;
        $ip = $_SERVER['REMOTE_ADDR'];
        $from_email = $email;
        $from_name = "DLR export";
        $mail = "DLR export";
        $to_email = $email;
        $subject = "DLR export";
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'];
        $csvFile = $actual_link . "/Reports/$filename.csv";
        $message = "link for download delivery report.<br>" . $csvFile;
        // Load Email Library For Sending E-mails
        $this->load->library('email');
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from($from_email, $from_name);
        $this->email->to($mail);
        $this->email->bcc($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
////        $from_email_admin = "tiwari12mayank@gmail.com";
////        $from_name = "DLR export";
////        $mail = "DLR export";
////        $to_email = $from_email_admin;
////        $subject = "DLR export";
////        $actual_link = 'http://' . $_SERVER['HTTP_HOST'];
////        $csvFile = $actual_link . "/Reports/$filename.csv";
////        $message = "User Export Details <br><br> Name : " . $name . "<br>" . "Username : " . $username . "<br>" . "IP : " . $ip;
////
////        $this->load->library('email');
////        $config['mailtype'] = 'html';
////        $this->email->initialize($config);
////        $this->email->from($from_email, $from_name);
////        $this->email->to($mail);
////        $this->email->bcc($to_email);
////        $this->email->subject($subject);
////        $this->email->message($message);
////        $this->email->send();
////        redirect('user/text_delivery_reports', 'refresh');
        //$this->downloadExcel($filename);
        redirect('user/schedule_reports', 'refresh');
    }

    // Export Delivery Report
    public function deliver_reports($user_id = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $from = $this->input->post('from') . " 00:00:00";
        $to = $this->input->post('to') . " 23:59:59";
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $delivery_reports = $this->sms_model->getAllDeliveryReports($user_id, $from, $to);
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Campaign Name, Sender Id, Message, Mobile Number, Status, Submit Date, Done Date";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($delivery_reports) {
            foreach ($delivery_reports as $sms) {
                $line = $i;
                $line .= "," . $sms['campaign_name'];
                $line .= "," . $sms['sender_id'];
                $line .= "," . $sms['message'];
                $line .= "," . $sms['mobile_no'];
                if ($sms['status'] == "1") {
                    $status = "Delivered";
                } elseif ($sms['status'] == "2") {
                    $status = "Failed";
                } elseif ($sms['status'] == "31" || $sms['status'] == "4") {
                    $status = "Pending";
                } elseif ($sms['status'] == "8") {
                    $status = "Submit";
                } elseif ($sms['status'] == "DND" || $sms['status'] == "16") {
                    $status = "DND";
                } elseif ($sms['status'] == "Blocked") {
                    $status = "Block By Operator";
                } elseif ($sms['status'] == "3") {
                    $status = "Sent";
                } elseif ($sms['status'] == "48") {
                    $status = "Landline";
                } else {
                    $status = $sms['status'];
                }
                $line .= "," . $status;
                $line .= "," . $sms['submit_date'];
                $line .= "," . $sms['done_date'];
                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);
        echo $filename . ".csv";
        die;
        /*
          header("Content-Length: " . filesize($csvFile));
          header('Content-Type: application/csv');
          header('Content-Disposition: attachment; filename=' . $filename . '.csv');
          readfile($csvFile);
          die;
         */
    }

    // Export Delivery Report
    public function missed_call_reports($user_id = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $from = $this->input->post('from') . " 00:00:00";
        $to = $this->input->post('to') . " 23:59:59";
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $delivery_reports = $this->code_data_model->getAllMissedcallReports($user_id, $from, $to);
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Missed Call Number, Sender, Date Time,";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($delivery_reports) {
            foreach ($delivery_reports as $sms) {
                $line = $i;
                $line .= "," . 9811877929;
                $line .= "," . $sms['mc_inbox_sender'];
                $line .= "," . $sms['mc_inbox_date'];
                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);
        echo $filename . ".csv";
        die;
        /*
          header("Content-Length: " . filesize($csvFile));
          header('Content-Type: application/csv');
          header('Content-Disposition: attachment; filename=' . $filename . '.csv');
          readfile($csvFile);
          die;
         */
    }

    // Export Group Contacts
    public function contacts($user_id = null) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // file name
        $filename = "contacts" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //load required data from database
        $group_name_array = $this->user_data_model->getGroupName($user_id);
        $group_id_array = $this->user_data_model->getGroupId($user_id);
        $contacts = $this->sms_model->getGroupsContacts($user_id);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "Conact Number, Conact Name, Group Names";
        fputcsv($file, explode(',', $headings));
        if ($contacts) {
            foreach ($contacts as $contact) {
                $group_array = explode(',', $contact['contact_group_ids']);
                $line = $contact['mobile_number'];
                $line .= "," . $contact['contact_name'];
                $jj = 0;
                $group_name_string = "-";
                foreach ($group_name_array as $g_name) {
                    if (in_array($group_id_array[$jj]['contact_group_id'], $group_array)) {
                        if ($group_name_string == "-")
                            $group_name_string = $g_name['contact_group_name'];
                        else
                            $group_name_string.=" | " . $g_name['contact_group_name'];
                    }
                    $jj++;
                }
                $line .= "," . $group_name_string;
                fputcsv($file, explode(',', $line));
            }
        }
        fclose($file);
        $this->downloadExcel($filename);
    }

    // Export Group Contacts
    public function group_contacts($user_id = null, $group_id = null) {
        $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "/";
        $url = $this->uri->uri_string();
        $this->admin_data_model->saveAdminHistory($actual_link, $url);

        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // file name
        $filename = "contacts" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //load required data from database
        $contacts = $this->sms_model->getGroupsContacts($user_id);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "Conact Number, Conact Name";
        fputcsv($file, explode(',', $headings));
        if ($contacts) {
            foreach ($contacts as $contact) {
                $group_array = explode(',', $contact['contact_group_ids']);
                if (in_array($group_id, $group_array)) {
                    $line = $contact['mobile_number'];
                    $line .= "," . $contact['contact_name'];
                    fputcsv($file, explode(',', $line));
                }
            }
        }
        fclose($file);
        $this->downloadExcel($filename);
    }

    // Export SMPP Logs
    public function smpp_logs() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->load->model('admin_data_model', '', TRUE);
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $smpp_logs = $this->admin_data_model->searchSMPPLogs();
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Connected SMPP, Pending, DND, Rejected, Blocked, Submit, Failed, Delivered, Total, Actual Deduction";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($smpp_logs) {
            foreach ($smpp_logs as $log) {
                $line = $i;
                $line .= "," . $log['user_group_name'] . " (" . $log['smsc_id'] . ")";
                $total_pending = 0;
                $total_dnd = 0;
                $total_rejected = 0;
                $total_blocked = 0;
                $total_submit = 0;
                $total_failed = 0;
                $total_delivered = 0;
                $total = 0;
                if (isset($row['31'])) {
                    $total_pending = $total_pending + $log['31'];
                }
                if (isset($row['4'])) {
                    $total_pending = $total_pending + $log['4'];
                }
                $total = $total + $total_pending;
                if (isset($log['DND'])) {
                    $total_dnd = $total_dnd + $log['DND'];
                    $total = $total + $total_dnd;
                }
                if (isset($log['16'])) {
                    $total_rejected = $total_rejected + $log['16'];
                    $total = $total + $total_rejected;
                }
                if (isset($log['Rejected'])) {
                    $total_rejected = $total_rejected + $log['Rejected'];
                    $total = $total + $total_rejected;
                }
                if (isset($log['Blocked'])) {
                    $total_blocked = $total_blocked + $log['Blocked'];
                    $total = $total + $total_blocked;
                }
                if (isset($log['8'])) {
                    $total_submit = $total_submit + $log['8'];
                    $total = $total + $total_submit;
                }
                if (isset($log['2'])) {
                    $total_failed = $total_failed + $log['2'];
                    $total = $total + $total_failed;
                }
                if (isset($log['3'])) {
                    $total_submit = $total_submit + $log['3'];
                    $total = $total + $total_submit;
                }
                if (isset($log['1'])) {
                    $total_delivered = $total_delivered + $log['1'];
                    $total = $total + $total_delivered;
                }
                $line .= "," . $total_pending;
                $line .= "," . $total_dnd;
                $line .= "," . $total_rejected;
                $line .= "," . $total_blocked;
                $line .= "," . $total_submit;
                $line .= "," . $total_failed;
                $line .= "," . $total_delivered;
                $line .= "," . $total;
                $line .= "," . $log['total_deduction'];
                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);
        echo $filename . ".csv";
        die;
        /*
          header("Content-Length: " . filesize($csvFile));
          header('Content-Type: application/csv');
          header('Content-Disposition: attachment; filename=' . $filename . '.csv');
          readfile($csvFile);
          die;
         */
    }

    // Export Sender Ids
    public function sender_ids() {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        $this->load->model('admin_data_model', '', TRUE);
        $filename = "reports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //load required data from database
        $sender_ids = $this->admin_data_model->getSenderIds(1);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Sender Id";
        fputcsv($file, explode(',', $headings));
        $i = 1;
        if ($sender_ids) {
            foreach ($sender_ids as $sender) {
                $line = $i;
                $line .= "," . $sender['sender'];
                fputcsv($file, explode(',', $line));
                $i++;
            }
        }
        fclose($file);
        echo $filename . ".csv";
        die;
    }

    // Download Excel File
    public function downloadExcel($filename = null) {
        $csvFile = "./Reports/$filename.csv";
        header("Content-Length: " . filesize($csvFile));
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        readfile($csvFile);
    }

}

?>