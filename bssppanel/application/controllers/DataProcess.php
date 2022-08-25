<?php
class DataProcess extends CI_Controller {
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        //$this->load->model('data_process_model', 'dataprocessmodel');
        //  $this->load->model('dataprocessmodel', '', TRUE);
        $this->load->model('sms_model', '', TRUE);


    }

   
    public function save_old_sent_sms() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        //164618486
      $start = 165118486;
        $end = 165618485;
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where("`sms_id` BETWEEN '" . $start . "' AND '" . $end . "'");
        $this->db->limit(500000);
        $query = $this->db->get();
        $result = $query->result_array();

      $this->db->insert_batch('sent_sms_old', $result);
    }


    
     
    
    // Export Delivery Report
    public function deliver_reports($user_id = null) {
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 240000);
        ini_set('max_execution_time', 240000);
        $from = "2019-01-01 00:00:00";
        $to = "2019-02-25 23:59:59";
        $filename = "prDECTillFeBreports" . date('Ymdhis');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $delivery_reports = $this->sms_model->getAllDeliveryReports($user_id, $from, $to);
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Campaign Name, Sender Id, Message, Mobile Number, Status, Submit Date,route";
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
                    $status = "Failed";
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
              // $line .= "," . $sms['done_date'];
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
    public function deliver_reportsold($user_id = null) {
        ini_set('memory_limit', '-1');
        ini_set('max_input_time', 240000);
        ini_set('max_execution_time', 240000);
        $from = "2018-12-01 00:00:00";
        $to = "2018-12-31 23:59:59";
        $filename = "prDecreportsIndiabulls" . date('Y-m-d-his');
        $csvFile = "./Reports/$filename.csv";
        //$this->load->library('parser');
        //load required data from database
        $delivery_reports = $this->sms_model->getAllDeliveryReportsOld($user_id, $from, $to);
        //pass retrieved data into template and return as a string
        //$stringData = $this->parser->parse('export-sent-sms', $data, true);
        //open excel and write string into excel
        $file = fopen($csvFile, 'w') or die("can't open file");
        //fwrite($fh, $stringData);
        $headings = "S.No., Campaign Name, Sender Id, Message, Mobile Number, Status, Submit Date, route";
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
                    $status = "Failed";
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
                $line .= "," . $sms['route'];
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



}
 