<?php
error_reporting(-1);
ini_set('display_errors', 'On');


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Process extends CI_Controller {

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
        $this->load->model('Kannel_Model', 'kannel_model');
        error_reporting(-1);
ini_set('display_errors', 'On');

    }

    // Update Voice Call DLR Through CSV File
    public function update_voice_call_dlr() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '1073741824');
        $user_id = 41;
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

        $string = "706166, 706217, 706236, 706263, 706268, 706270, 706356, 706491, 707266, 707267, 707271, 707276, 707279, 707281, 707300, "
                . "707329, 707350, 707354, 707543, 707599, 707613, 707625, 707631, 707883, 707889, 707956, 707966, 708120, 708164, 708195,"
                . " 708198, 708200, 708201, 708222, 708224, 708239, 708252, 708266, 708298, 708304, 708328, 708703, 708716, 709316, 709371, 715351, "
                . "715356, 715359, 715363, 715368, 715372";
        $campaign_array = explode(',', $string);

        // ANSWERED 75% 626808
        /*
          $this->db->query("UPDATE sent_sms SET status = 'ANSWERED', done_date='" . date('Y-m-d h:i:s') . "' "
          . " WHERE user_id=$user_id AND status='31' ORDER BY rand() LIMIT 105000");
         */

        // SUBMITTED 25%
        /*
          SELECT COUNT( * ) , `status`
          FROM `sent_sms`
          WHERE `submit_date` LIKE '2016-04-09%'
          AND campaign_id NOT
          IN ( 708703, 708716, 709316, 709371 )
          GROUP BY `status`
          LIMIT 0 , 30
         */
        $this->db->query("UPDATE sent_sms SET status=1, done_date='2016-04-08 21:17:14' "
                . " WHERE status IN (31) AND temporary_status=1 AND campaign_id IN (716236) ORDER BY rand() LIMIT 102971 ");

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
        /*
          708143, 708158, 708167, 708182, 708186, 708188, 708190, 708208, 708262, 708274, 708300, 708306, 708308, 708311, 708316,
          708317, 708327, 708330, 708340, 708346, 708349, 708352, 708355, 708358, 708359, 708364, 708370, 708371, 708375, 708376, 708381

          708221, 708232, 708235, 708248, 708287, 708301, 708388, 708390, 708391, 709060
         */
        $query = $this->db->query("SELECT user_id, GROUP_CONCAT(campaign_id SEPARATOR ', ') AS `campaigns` FROM campaigns "
                . "WHERE `service_type` = 'SMS' AND submit_date BETWEEN '2016-04-08 00:00:00' AND '2016-04-09 23:59:59' AND user_id IN (508,522) GROUP BY 'all' ");
        /*
          $query = $this->db->query("SELECT GROUP_CONCAT(campaign_id SEPARATOR ', ') AS `campaigns` FROM campaigns "
          . "WHERE submit_date LIKE '2016-04-08%' AND `service_type` = 'SMS' GROUP BY 'all' ");
         */
        if ($query->num_rows()) {
            $result = $query->row_array();
            if ($result) {
                echo $result['campaigns'];
            }
        }
        die;
    }

    public function get_total_report() {
        $id = 2658;
        $user_id = array();
        $this->db->select('user_id');
        $this->db->from('users');
        $this->db->where('most_parent_id', $id);
        $query = $this->db->get();
        $result = $query->result_array();
        $result1 = array_values($result);
        $size = sizeof($result1);
        for ($i = 0; $i < $size; $i++) {
            $user_id[] = $result1[$i]['user_id'];
        }
        echo $new_id = implode(',', $user_id);

        /*
          $this->db->select('status');
          $this->db->from('sent_sms');
          $this->db->where_in('user_id', $user_id);
          $this->db->where('status', 2);
          $this->db->where('temporary_status', 1);
          $new_query = $this->db->get();
          $new_result = $new_query->num_rows();
          echo $new_result;
         */
    }

    public function update_fake_ratio() {
        $new_data = array();
        date_default_timezone_set('Asia/Kolkata');

        $current_time = date('Y-m-d H:i:s');
        $start_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -240 minutes"));

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
            $actual_processed = 0;
            $actual_deliverd = 0;
            $fake_deliverd = 0;
            $fake_failed = 0;
            $fake_sent = 0;
            foreach ($result_sent as $result_sent_sms) {
                $campaign_id = $result_sent_sms['campaign_id'];
                $status = $result_sent_sms['status'];
                $temporary_status = $result_sent_sms['temporary_status'];

                if ($status == 1) {
                    $actual_deliverd++;
                }

                if ($temporary_status == 1) {
                    $actual_processed++;
                }
                if ($temporary_status == 2) {
                    $fake_deliverd++;
                }
                if ($temporary_status == 3) {
                    $fake_failed++;
                }
                if ($temporary_status == 4) {
                    $fake_sent++;
                }
            }
            $current_date = date('Y-m-d H:i:s');

            $calculate_sms = $actual_processed * 10 / 100;
            $calculate_sms = round($calculate_sms);
            if ($actual_deliverd > $calculate_sms) {

                $new_data[] = $data = array(
                    'campaign_id' => $campaign_id,
                    'status' => 1,
                    'done_date' => $current_date,
                    'description' => "ANSWERED",
                );
            }

            $this->db->where('temporary_status', "2");
            $this->db->update_batch('sent_sms', $new_data, 'campaign_id');
        }
    }

    public function dnd_connection() {
        $result = $this->kannel_model->dndConnection();
    }

    public function dnd_check() {
        $result = $this->kannel_model->DNDCheck();
    }

    public function log_pr() {

        /*    $data = array
          (
          array("amount"=>"3", "user_id"=>"8"),
          array("amount"=>"43", "user_id"=>"17"),
          array("amount"=>"20", "user_id"=>"7"),
          array("amount"=>"56", "user_id"=>"66"),
          );
          arsort($data);

          foreach($data as $x)
          {
          echo "Key=" . $x['user_id'] . ", Value=" . $x['amount'];
          echo "<br>";
          }
          die; */

        $today_date = date('Y-m-d');
        $this->db->distinct('user_id');
        $this->db->select('user_id');
        $this->db->from('campaigns');
        $this->db->where('campaign_id >', 4832422);
        $this->db->where('route', 'B');
        $this->db->like('submit_date', $today_date, 'after');
        //  $this->db->order_by('`total_deducted`', 'desc'); 
        $query = $this->db->get();
        if ($query->num_rows()) {
            $user_ids = $query->result_array();
            //    var_dump($user_ids);die;
            foreach ($user_ids AS $ids) {

                $this->db->select('SUM(`total_messages`) AS `total_tr_messages`, SUM(`total_deducted`) AS `tr_consumptions`,`user_id`');
                $this->db->from('`campaigns`');
                $this->db->where('campaign_id >', 4832422);
                $this->db->where('`route`', 'B');
                $this->db->where('`user_id`', $ids['user_id']);
                $this->db->like('submit_date', $today_date, 'after');
                $query2 = $this->db->get();
                if ($query2->num_rows()) {
                    $row2 = $query2->row();
                    if ($row2->tr_consumptions) {
                        $temp['tr_consumptions'] = $row2->tr_consumptions;
                    } else {
                        $temp['tr_consumptions'] = 0;
                    }
                    if ($row2->total_tr_messages) {
                        $temp['total_tr_messages'] = $row2->total_tr_messages;
                    } else {
                        $temp['total_tr_messages'] = 0;
                    }
                    $temp['user_id'] = $row2->user_id;
                } else {
                    $temp['tr_consumptions'] = 0;
                    $temp['total_tr_messages'] = 0;
                    $temp['user_id'] = $row2->user_id;
                }
                $return_array[] = $temp;
            }
            arsort($return_array);
            // return $return_array;
            var_dump($return_array);
            die;
        } else {
            return false;
        }
    }

    public function get_last_id() {
        $current_time = date('Y-m-d');
        $start_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -25 minutes"));
        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -20 minutes"));

        $this->db->select('campaign_id');
        $this->db->from('campaigns');
        $this->db->where("submit_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        echo $result->campaign_id;
    }

    public function save_smpp_daily_log() {

        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        $route_array = array(99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118);

        $size = sizeof($route_array);
        $status_actual = array();
        $final_array = array();
        $final_key = array();
        $size = sizeof($route_array);

        $delivered = 0;
        $sent = 0;
        $failed = 0;
        $rejected = 0;
        $pending = 0;
        $total = 0;
        $total_credits = 0;

        $date = date('Y-m-d', strtotime("-1 days"));
        //  $date = date('Y-m-d');
        $this->db->select('`campaign_id`');
        $this->db->from('campaigns');
        $this->db->like('submit_date', $date);
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        $campaign_id = $row->campaign_id;


        for ($i = 0; $i < $size; $i++) {
            $this->db->select('`status`');
            $this->db->from('sent_sms');
            $this->db->where('campaign_id >', $campaign_id);
            $this->db->where('user_group_id', $route_array[$i]);
            $this->db->where('temporary_status', 1);
            $this->db->like('submit_date', $date, 'after');
            $query = $this->db->get();
            $result = $query->result_array();

            foreach ($result as $result_status) {
                $status_actual[] = $result_status['status'];
            }

            $final_array = array_count_values($status_actual);
            $final_key = array_keys($final_array);
            $size_final_array = sizeof($final_array);
            $delivered = $final_array[1];
            $sent = $final_array[3];
            $failed = $final_array[2];
            $rejected = $final_array[16];
            $pending = $final_array[31];
            $total = $delivered + $sent + $failed + $rejected + $pending;

            $this->db->select('SUM(`total_deducted`) AS `consumptions`');
            $this->db->from('`campaigns`');
            $this->db->where('campaign_id >', $campaign_id);
            $this->db->where('user_group_id', $route_array[$i]);
            $this->db->like('submit_date', $date, 'after');
            $query2 = $this->db->get();
            $row2 = $query2->row();
            $total_credits = $row2->consumptions;
            $data = array(
                'route_id' => $route_array[$i],
                'delivered' => $delivered,
                'sent' => $sent,
                'failed' => $failed,
                'rejected' => $rejected,
                'pending' => $pending,
                'total' => $total,
                'total_credits' => $total_credits,
                'date' => $date
            );
            $this->db->insert('daily_smpp_log', $data);

            unset($final_array);
            unset($final_key);
            unset($status_actual);
        }
    }

    public function save_smpp_daily_log_new() {

        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        $route_array = array(101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 121);
        //$route_array = array(103, 104);

        $size = sizeof($route_array);

        $status_actual1 = array();
        $status_actual2 = array();
        $status_actual3 = array();
        $status_actual4 = array();
        $status_actual5 = array();
        $status_actual6 = array();
        $status_actual7 = array();
        $status_actual8 = array();
        $status_actual9 = array();
        $status_actual10 = array();



        $final_array1 = array();
        $final_key1 = array();

        $final_array2 = array();
        $final_key2 = array();

        $final_array3 = array();
        $final_key3 = array();

        $final_array4 = array();
        $final_key4 = array();

        $final_array5 = array();
        $final_key5 = array();

        $final_array6 = array();
        $final_key6 = array();

        $final_array7 = array();
        $final_key7 = array();


        $final_array8 = array();
        $final_key8 = array();


        $final_array9 = array();
        $final_key9 = array();



        $final_array10 = array();
        $final_key10 = array();

        $size = sizeof($route_array);

        $date = date('Y-m-d', strtotime("-1 days"));
        //$date = date('Y-m-d');
        $this->db->select('`campaign_id`');
        $this->db->from('campaigns');
        $this->db->like('submit_date', $date);
        $this->db->limit(1);
        $query = $this->db->get();
        $row = $query->row();
        $campaign_id = $row->campaign_id;


        //actual variable
        $total_delivered = 0;
        $total_sent = 0;
        $total_failed = 0;
        $total_rejected = 0;
        $total_rejected_human = 0;
        $total_landline = 0;
        $total_dnd = 0;
        $total_blocked = 0;
        $total_pending = 0;
        $total = 0;




        // variable for credits 1
        $delivered1 = 0;
        $sent1 = 0;
        $failed1 = 0;
        $rejected1 = 0;
        $rejected_human1 = 0;
        $pending1 = 0;
        $landline1 = 0;
        $dnd1 = 0;
        $blocked1 = 0;

        // variable for credits 2
        $delivered2 = 0;
        $sent2 = 0;
        $failed2 = 0;
        $rejected2 = 0;
        $rejected_human2 = 0;
        $pending2 = 0;
        $landline2 = 0;
        $dnd2 = 0;
        $blocked2 = 0;

        // variable for credits 3
        $delivered3 = 0;
        $sent3 = 0;
        $failed3 = 0;
        $rejected3 = 0;
        $rejected_human3 = 0;
        $pending3 = 0;
        $landline3 = 0;
        $dnd3 = 0;
        $blocked3 = 0;


        // variable for credits 4
        $delivered4 = 0;
        $sent4 = 0;
        $failed4 = 0;
        $rejected4 = 0;
        $rejected_human4 = 0;
        $pending4 = 0;
        $landline4 = 0;
        $dnd4 = 0;
        $blocked4 = 0;

        // variable for credits 5 
        $delivered5 = 0;
        $sent5 = 0;
        $failed5 = 0;
        $rejected5 = 0;
        $rejected_human5 = 0;
        $pending5 = 0;
        $landline5 = 0;
        $dnd5 = 0;
        $blocked5 = 0;

        // variable for credits 6
        $delivered6 = 0;
        $sent6 = 0;
        $failed6 = 0;
        $rejected6 = 0;
        $rejected_human6 = 0;
        $pending6 = 0;
        $landline6 = 0;
        $dnd6 = 0;
        $blocked6 = 0;

        // variable for credits 7
        $delivered7 = 0;
        $sent7 = 0;
        $failed7 = 0;
        $rejected7 = 0;
        $rejected_human7 = 0;
        $pending7 = 0;
        $landline7 = 0;
        $dnd7 = 0;
        $blocked7 = 0;

        // variable for credits 8
        $delivered8 = 0;
        $sent8 = 0;
        $failed8 = 0;
        $rejected8 = 0;
        $rejected_human8 = 0;
        $pending8 = 0;
        $landline8 = 0;
        $dnd8 = 0;
        $blocked8 = 0;


        // variable for credits 9
        $delivered9 = 0;
        $sent9 = 0;
        $failed9 = 0;
        $rejected9 = 0;
        $rejected_human9 = 0;
        $pending9 = 0;
        $landline9 = 0;
        $dnd9 = 0;
        $blocked9 = 0;

        // variable for credits 10
        $delivered10 = 0;
        $sent10 = 0;
        $failed10 = 0;
        $rejected10 = 0;
        $rejected_human10 = 0;
        $pending10 = 0;
        $landline10 = 0;
        $dnd10 = 0;
        $blocked10 = 0;





        for ($i = 0; $i < $size; $i++) {


            $this->db->select('`status`,`actual_credit`');
            $this->db->from('sent_sms');
            $this->db->where('campaign_id >', $campaign_id);
            $this->db->where('user_group_id', $route_array[$i]);
            $this->db->where('temporary_status', 1);
            $this->db->like('submit_date', $date, 'after');
            $query = $this->db->get();
            $result = $query->result_array();

            foreach ($result as $result_status) {


                $length = $result_status['actual_credit'];
                if ($length <= 160) {
                    $status_actual1[] = $result_status['status'];
                } elseif ($length >= 161 && $length <= 320) {
                    $status_actual2[] = $result_status['status'];
                } elseif ($length >= 321 && $length <= 480) {
                    $status_actual3[] = $result_status['status'];
                } elseif ($length >= 481 && $length <= 640) {
                    $status_actual4[] = $result_status['status'];
                } elseif ($length >= 641 && $length <= 800) {
                    $status_actual5[] = $result_status['status'];
                } elseif ($length >= 801 && $length <= 960) {
                    $status_actual6[] = $result_status['status'];
                } elseif ($length >= 961 && $length <= 1120) {
                    $status_actual7[] = $result_status['status'];
                } elseif ($length >= 1121 && $length <= 1280) {
                    $status_actual8[] = $result_status['status'];
                } elseif ($length >= 1281 && $length <= 1440) {
                    $status_actual9[] = $result_status['status'];
                } elseif ($length >= 1441 && $length <= 1600) {
                    $status_actual10[] = $result_status['status'];
                }
            }

            $final_array1 = array_count_values($status_actual1);
            $final_key1 = array_keys($final_array1);
            $size_final_array1 = sizeof($final_array1);

            $final_array2 = array_count_values($status_actual2);
            $final_key2 = array_keys($final_array2);
            $size_final_array2 = sizeof($final_array2);


            $final_array3 = array_count_values($status_actual3);
            $final_key3 = array_keys($final_array3);
            $size_final_array3 = sizeof($final_array3);

            $final_array4 = array_count_values($status_actual4);
            $final_key4 = array_keys($final_array4);
            $size_final_array4 = sizeof($final_array4);

            $final_array5 = array_count_values($status_actual5);
            $final_key5 = array_keys($final_array5);
            $size_final_array5 = sizeof($final_array5);

            $final_array6 = array_count_values($status_actual6);
            $final_key6 = array_keys($final_array6);
            $size_final_array6 = sizeof($final_array6);

            $final_array7 = array_count_values($status_actual7);
            $final_key7 = array_keys($final_array7);
            $size_final_array7 = sizeof($final_array7);

            $final_array8 = array_count_values($status_actual8);
            $final_key8 = array_keys($final_array8);
            $size_final_array8 = sizeof($final_array8);

            $final_array9 = array_count_values($status_actual9);
            $final_key9 = array_keys($final_array9);
            $size_final_array9 = sizeof($final_array9);

            $final_array10 = array_count_values($status_actual10);
            $final_key10 = array_keys($final_array10);
            $size_final_array10 = sizeof($final_array10);





            $delivered1 = $final_array1[1];
            $sent1 = $final_array1[3];
            $failed1 = $final_array1[2];
            $rejected1 = $final_array1[16];
            $pending1 = $final_array1[31];
            $rejected_human1 = $final_array1['Rejected'];
            $dnd1 = $final_array1['9'];
            $blocked1 = $final_array1['Blocked'];
            $landline1 = $final_array1[48];
            $total1 = $delivered1 + $sent1 + $failed1 + $rejected1 + $pending1 + $rejected_human1 + $dnd1 + $blocked1 + $landline1;




            $delivered2 = $final_array2[1] * 2;
            $sent2 = $final_array2[3] * 2;
            $failed2 = $final_array2[2] * 2;
            $rejected2 = $final_array2[16] * 2;
            $pending2 = $final_array2[31] * 2;
            $rejected_human2 = $final_array2['Rejected'] * 2;
            $dnd2 = $final_array2['9'] * 2;
            $blocked2 = $final_array2['Blocked'] * 2;
            $landline2 = $final_array2[48] * 2;
            $total2 = $delivered2 + $sent2 + $failed2 + $rejected2 + $pending2 + $rejected_human2 + $dnd2 + $blocked2 + $landline2;


            $delivered3 = $final_array3[1] * 3;
            $sent3 = $final_array3[3] * 3;
            $failed3 = $final_array3[2] * 3;
            $rejected3 = $final_array3[16] * 3;
            $pending3 = $final_array3[31] * 3;
            $rejected_human3 = $final_array3['Rejected'] * 3;
            $dnd3 = $final_array3['9'] * 3;
            $blocked3 = $final_array3['Blocked'] * 3;
            $landline3 = $final_array3[48] * 3;
            $total3 = $delivered3 + $sent3 + $failed3 + $rejected3 + $pending3 + $rejected_human3 + $dnd3 + $blocked3 + $landline3;


            $delivered4 = $final_array4[1] * 4;
            $sent4 = $final_array4[3] * 4;
            $failed4 = $final_array4[2] * 4;
            $rejected4 = $final_array4[16] * 4;
            $pending4 = $final_array4[31] * 4;
            $rejected_human4 = $final_array4['Rejected'] * 4;
            $dnd4 = $final_array4['9'] * 4;
            $blocked4 = $final_array4['Blocked'] * 4;
            $landline4 = $final_array4[48] * 4;
            $total4 = $delivered4 + $sent4 + $failed4 + $rejected4 + $pending4 + $rejected_human4 + $dnd4 + $blocked4 + $landline4;


            $delivered5 = $final_array5[1] * 5;
            $sent5 = $final_array5[3] * 5;
            $failed5 = $final_array5[2] * 5;
            $rejected5 = $final_array5[16] * 5;
            $pending5 = $final_array5[31] * 5;
            $rejected_human5 = $final_array5['Rejected'] * 5;
            $dnd5 = $final_array5['9'] * 5;
            $blocked5 = $final_array5['Blocked'] * 5;
            $landline5 = $final_array5[48] * 5;
            $total5 = $delivered5 + $sent5 + $failed5 + $rejected5 + $pending5 + $rejected_human5 + $dnd5 + $blocked5 + $landline5;



            $delivered6 = $final_array6[1] * 6;
            $sent6 = $final_array6[3] * 6;
            $failed6 = $final_array6[2] * 6;
            $rejected6 = $final_array6[16] * 6;
            $pending6 = $final_array6[31] * 6;
            $rejected_human6 = $final_array6['Rejected'] * 6;
            $dnd6 = $final_array6['9'] * 6;
            $blocked6 = $final_array6['Blocked'] * 6;
            $landline6 = $final_array6[48] * 6;
            $total6 = $delivered6 + $sent6 + $failed6 + $rejected6 + $pending6 + $rejected_human6 + $dnd6 + $blocked6 + $landline6;

            $delivered7 = $final_array7[1] * 7;
            $sent7 = $final_array7[3] * 7;
            $failed7 = $final_array7[2] * 7;
            $rejected7 = $final_array7[16] * 7;
            $pending7 = $final_array7[31] * 7;
            $rejected_human7 = $final_array7['Rejected'] * 7;
            $dnd7 = $final_array7['9'] * 7;
            $blocked7 = $final_array7['Blocked'] * 7;
            $landline7 = $final_array7[48] * 7;
            $total7 = $delivered7 + $sent7 + $failed7 + $rejected7 + $pending7 + $rejected_human7 + $dnd7 + $blocked7 + $landline7;


            $delivered8 = $final_array8[1] * 8;
            $sent8 = $final_array8[3] * 8;
            $failed8 = $final_array8[2] * 8;
            $rejected8 = $final_array8[16] * 8;
            $pending8 = $final_array8[31] * 8;
            $rejected_human8 = $final_array8['Rejected'] * 8;
            $dnd8 = $final_array8['9'] * 8;
            $blocked8 = $final_array8['Blocked'] * 8;
            $landline8 = $final_array8[48] * 8;
            $total8 = $delivered8 + $sent8 + $failed8 + $rejected8 + $pending8 + $rejected_human8 + $dnd8 + $blocked8 + $landline8;

            $delivered9 = $final_array9[1] * 9;
            $sent9 = $final_array9[3] * 9;
            $failed9 = $final_array9[2] * 9;
            $rejected9 = $final_array9[16] * 9;
            $pending9 = $final_array9[31] * 9;
            $rejected_human9 = $final_array9['Rejected'] * 9;
            $dnd9 = $final_array9['9'] * 9;
            $blocked9 = $final_array9['Blocked'] * 9;
            $landline9 = $final_array9[48] * 9;
            $total9 = $delivered9 + $sent9 + $failed9 + $rejected9 + $pending9 + $rejected_human9 + $dnd9 + $blocked9 + $landline9;

            $delivered10 = $final_array10[1] * 10;
            $sent10 = $final_array10[3] * 10;
            $failed10 = $final_array10[2] * 10;
            $rejected10 = $final_array10[16] * 10;
            $pending10 = $final_array10[31] * 10;
            $rejected_human10 = $final_array10['Rejected'] * 10;
            $dnd10 = $final_array10['9'] * 10;
            $blocked10 = $final_array10['Blocked'] * 10;
            $landline10 = $final_array10[48] * 10;
            $total10 = $delivered10 + $sent10 + $failed10 + $rejected10 + $pending10 + $rejected_human10 + $dnd10 + $blocked10 + $landline10;




            $total_delivered = $delivered1 + $delivered2 + $delivered3 + $delivered4 + $delivered5 + $delivered6 + $delivered7 + $delivered8 + $delivered9 + $delivered10;
            $total_sent = $sent1 + $sent2 + $sent3 + $sent4 + $sent5 + $sent6 + $sent7 + $sent8 + $sent9 + $sent10;
            $total_failed = $failed1 + $failed2 + $failed3 + $failed4 + $failed5 + $failed6 + $failed7 + $failed8 + $failed9 + $failed10;
            $total_rejected = $rejected1 + $rejected2 + $rejected3 + $rejected4 + $rejected5 + $rejected6 + $rejected7 + $rejected8 + $rejected9 + $rejected10;
            $total_pending = $pending1 + $pending2 + $pending3 + $pending4 + $pending5 + $pending6 + $pending7 + $pending8 + $pending9 + $pending10;
            $total_rejected_human = $rejected_human1 + $rejected_human2 + $rejected_human3 + $rejected_human4 + $rejected_human5 + $rejected_human6 + $rejected_human7 + $rejected_human8 + $rejected_human9 + $rejected_human10;
            $total_dnd = $dnd1 + $dnd2 + $dnd3 + $dnd4 + $dnd5 + $dnd6 + $dnd7 + $dnd8 + $dnd9 + $dnd10;
            $total_blocked = $blocked1 + $blocked2 + $blocked3 + $blocked4 + $blocked5 + $blocked6 + $blocked7 + $blocked8 + $blocked9 + $blocked10;
            $total_landline = $landline1 + $landline2 + $landline3 + $landline4 + $landline5 + $landline6 + $landline7 + $landline8 + $landline9 + $landline10;
            $total = $total1 + $total2 + $total3 + $total4 + $total5 + $total6 + $total7 + $total8 + $total9 + $total10;


            $data = array(
                'route_id' => $route_array[$i],
                'delivered' => $total_delivered,
                'sent' => $total_sent,
                'failed' => $total_failed,
                'rejected' => $total_rejected,
                'pending' => $total_pending,
                'rejected_human' => $total_rejected_human,
                'dnd' => $total_dnd,
                'blocked' => $total_blocked,
                'landline' => $total_landline,
                'total' => $total,
                'date' => $date
            );
            $this->db->insert('daily_smpp_log', $data);

            unset($data);

            unset($final_array1);
            unset($final_key1);
            unset($status_actual1);

            unset($final_array2);
            unset($final_key2);
            unset($status_actual2);

            unset($final_array3);
            unset($final_key3);
            unset($status_actual3);

            unset($final_array4);
            unset($final_key4);
            unset($status_actual4);

            unset($final_array5);
            unset($final_key5);
            unset($status_actual5);

            unset($final_array6);
            unset($final_key6);
            unset($status_actual6);


            unset($final_array7);
            unset($final_key7);
            unset($status_actual7);

            unset($final_array8);
            unset($final_key8);
            unset($status_actual8);

            unset($final_array9);
            unset($final_key9);
            unset($status_actual9);


            unset($final_array10);
            unset($final_key10);
            unset($status_actual10);
        }
    }

    public function check_join() {
        $this->db->select('txn_admin_from,admin_username,admin_name');
        // $this->db->select('admin_username,admin_name');
        $this->db->from('administrators');
        $this->db->join('transaction_logs', 'administrators.admin_id = transaction_logs.txn_admin_from','LEFT');
        // $this->db->join('administrators', 'transaction_logs.txn_admin_from = administrators.admin_id');
        $this->db->where('txn_admin_from', 6);
        $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result_array();
           var_dump($result);
           die;


        $this->db->select('`username`,`ip_address`, `history_url`, `date_time`, `date`');
        $this->db->from('admin_history');
        $this->db->join('users', 'admin_history.admin_id = users.user_id');
        $this->db->where('history_id > ', 2146412);
        $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result_array();
        //var_dump($result);
        $this->db->select('COUNT(user_id) ,user_id,username');
        $this->db->from('user_daily_backup');
        $this->db->group_by("user_id");
         $this->db->where('user_id >', 45);
        $query = $this->db->get();
        $result = $query->result_array();
        var_dump($result);
    }

    public function insert_subscription_data() {


        $data = array(
			'subscribe_name' =>  $this->input->post('subscribe_name'),
            'subscribe_email' => $this->input->post('subscribe_email'),
            'subscribe_mobile' => $this->input->post('subscribe_mobile'),
            'ip_address' => $this->input->post('ip_address'),
		    'subscribe_type' => $this->input->post('subscribe_type'),
			'subscribe_date' => $this->input->post('subscribe_date')
        );
        if ($this->db->insert('subscription', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function apexbk() {
        $mobiles = "917069436659";
        $full_data = array();
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where('mobile_no', $mobiles);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result_data) {
            $camp_ids = $result_data['campaign_id'];
            $message = urldecode($result_data['message']);
            $mobile_no = $result_data['mobile_no'];
            $status = $result_data['status'];
            $submit_date = $result_data['submit_date'];
            $done_date = $result_data['done_date'];

            $this->db->select('campaign_uid');
            $this->db->from('campaigns');
            $this->db->where('campaign_id', $camp_ids);
            $query_id = $this->db->get();
            $result_id = $query_id->row();
            $message_id = $result_id->campaign_uid;

            $full_data[] = $data = array(
                'message_id' => $message_id,
                'mobile' => $mobile_no,
                'message' => $message,
                'status' => $status,
                'submit_date' => $submit_date,
                'done_date' => $done_date,
            );
        }
        $this->db->insert_batch('apex_data', $full_data);
    }

    public function insert_blacklist() {

        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        echo $campaign = 8838858;
        $full_data = array();
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where('campaign_id', $campaign);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $result_data) {

            $mobile_no = $result_data['mobile_no'];



            $full_data[] = $data = array(
                'white_list_number' => $mobile_no,
                'white_list_status' => 1
            );
        }
        $this->db->insert_batch('white_lists', $full_data);
    }

    public function apex_show() {
        $this->db->select('*');
        $this->db->from('apex_data');
        $query = $this->db->get();
        $result['data'] = $query->result_array();
        $this->load->view('user/header', $result);
        $this->load->view('user/apexbk');
        $this->load->view('user/footer');
    }

    public function save_old_sent_sms() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        //170557215
      $start = 177557216;  
        $end = 177920979;
        $this->db->select('*');
        $this->db->from('sent_sms');
        $this->db->where("`sms_id` BETWEEN '" . $start . "' AND '" . $end . "'");
        $this->db->limit(500000);
        $query = $this->db->get();
        $result = $query->result_array();

        $this->db->insert_batch('sent_sms_old', $result);
    }

    public function save_rajkamal() {
//        ini_set('max_input_time', 24000);
//        ini_set('max_execution_time', 24000);
//        ini_set('memory_limit', '107374182400');
//        //170557215
//      
//        $this->db->select('*');
//        $this->db->from('campaigns_old');
//        $this->db->where('user_id',9267);
//        $this->db->limit(2000);
//        $query = $this->db->get();
//        $result = $query->result_array();
//       // var_dump($result);
//        $this->db->insert_batch('campaigns', $result);
    }
    
    public function check_dlr_prob() {
        $full_data = array();
        $type = "MT";
        $dlr_mask = 31;
        $smsc_id = "V_TRANS";
        $message = "HELLO USER";
        $sqlbox = "sqlbox1";
        $mobile = array(919575636818, 919893300017, 919893300050, 919893300023, 918602200100, 918602298909, 918817566554);
        $size = sizeof($mobile);
        for ($i = 0; $i < $size; $i++) {
            $full_data[] = $data = array(
                'momt' => $type,
                'sender' => "NEWSMS",
                'receiver' => $mobile[$i],
                'msgdata' => $message,
                'smsc_id' => $smsc_id,
                'dlr_mask' => 31,
                'boxc_id' => $sqlbox,
            );
        }



        $this->db->insert_batch('sqlbox_send_sms', $full_data);
    }

    public function new_ration_pr() {
        $user_ids = array();
        $this->db->select('`user_id`');
        $this->db->from('`users`');
        $this->db->where('admin_id', 1);
        $query_user = $this->db->get();
        $result_user = $query_user->result_array();

        foreach ($result_user as $result_users) {
            $user_ids[] = $result_users['user_id'];
        }

        $size = sizeof($user_ids);

        for ($i = 0; $i < $size; $i++) {
            $user_id = $user_ids[$i];

            $base_price = .13;
            $base_price_without_tax = .1534;
            $ration_price = 0;


            $this->db->select('*');
            $this->db->from('transaction_logs');
            $this->db->where('txn_user_to', $user_id);
            $this->db->where('txn_admin_from > ', 0);
            $this->db->where('txn_route', "B");
            $this->db->order_by('txn_log_id', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            $pricing_array = $query->result_array();

            $txn_id = $pricing_array[0]['txn_log_id'];
            $pricing = $pricing_array[0]['txn_price'];
            $tax_status = $pricing_array[0]['tax_status'];

            if ($tax_status == 1) {
                $ration_price = $base_price - $pricing;
                $prize_ratio = explode('.', $ration_price);
                $string_value = strlen($prize_ratio[1]);
            } else {
                $ration_price = $base_price_without_tax - $pricing;
                $prize_ratio = explode('.', $ration_price);
                $string_value = strlen($prize_ratio[1]);
                if ($string_value == 3) {
                    $set_ratio = $prize_ratio[1];
                }
                if ($string_value == 2) {
                    $set_ratio = $prize_ratio[1] * 10;
                }
                $fake_ratio = $set_ratio * 20 / 100;
                $pfake_failed_ratio = round($fake_ratio);
                $pfake_deliver_ratio = $set_ratio - $pfake_failed_ratio;

                if ($pfake_deliver_ratio > 99) {
                    $pfake_deliver_ratio = 0;
                    $pfake_failed_ratio = 0;
                }

                $data = array(
                    'user_fake_ratio' => $pfake_deliver_ratio,
                    'user_fail_ratio' => $pfake_failed_ratio,
                    'tr_ratio_discription' => "as per new pricing"
                );
            }
        }
    }

    public function balance_deduction() {
        //$user_id = 526;
        $txn_date = date('d-m-Y h:i A');
        $base_price = .13;
        $base_price_without_tax = .1534;
        $ration_price = 0;

        $user_ids = array();
        $this->db->select('`user_id`');
        $this->db->from('`users`');
        $this->db->where('admin_id', 1);
        $query_user = $this->db->get();
        $result_user = $query_user->result_array();

        foreach ($result_user as $result_users) {
            $user_ids[] = $result_users['user_id'];
        }
        $size = sizeof($user_ids);
        for ($i = 0; $i < $size; $i++) {
            $user_id = $user_ids[$i];
            $this->db->select('*');
            $this->db->from('transaction_logs');
            $this->db->where('txn_user_to', $user_id);
            $this->db->where('txn_admin_from > ', 0);
            $this->db->where('txn_route', "B");
            $this->db->order_by('txn_log_id', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get();
            $pricing_array = $query->result_array();

            $txn_id = $pricing_array[0]['txn_log_id'];
            $pricing = $pricing_array[0]['txn_price'];
            $tax_status = $pricing_array[0]['tax_status'];

            if ($pricing <= .13) {
                if ($tax_status == 1) {
                    $ration_price = $base_price - $pricing;
                    $prize_ratio = explode('.', $ration_price);
                    $string_value = strlen($prize_ratio[1]);
                    if ($string_value == 3) {
                        $set_ratio = $prize_ratio[1];
                    }
                    if ($string_value == 2) {
                        $set_ratio = $prize_ratio[1] * 10;
                    }

                    $this->db->select('*');
                    $this->db->from('users');
                    $this->db->where('user_id', $user_id);
                    $query_parant = $this->db->get();
                    $result_parant = $query_parant->result_array();
                    $parant_user_balance = $result_parant[0]['tr_sms_balance'];

                    $reducable_balancde_parant = round($parant_user_balance * $set_ratio / 100) . "     -     ";
                    $actual_balance_parant = $parant_user_balance - $reducable_balancde_parant . "     -     ";

                    $data_parant = array(
                        'tr_sms_balance' => $actual_balance_parant,
                    );

                    $this->db->where('user_id', $user_id);
                    $this->db->update('users', $data_parant);

                    $data = array(
                        'txn_route' => "B",
                        'txn_sms' => $reducable_balancde_parant,
                        'txn_price' => .13,
                        'txn_amount' => $reducable_balancde_parant * .13,
                        'txn_type' => "Reduce",
                        'txn_admin_to' => 1,
                        'txn_user_from' => $user_id,
                        'txn_date' => $txn_date,
                        'txn_description' => "As per New Pricing",
                        'new_date' => date('Y-m-d'),
                        'tax_status' => 1,
                        'txn_status' => 1
                    );


                    $this->db->insert('transaction_logs', $data);

                    echo "<br>";
                    $this->db->select('*');
                    $this->db->from('users');
                    $this->db->where('most_parent_id', $user_id);
                    $query_child = $this->db->get();
                    $result_child = $query_child->result_array();
                    $child_size = sizeof($result_child);
                    for ($j = 0; $j < $child_size; $j++) {

                        $child_user_id = $result_child[$j]['user_id'] . "     -     ";
                        $child_user_balance = $result_child[$j]['tr_sms_balance'] . "     -     ";
                        if ($child_user_balance > 9) {


                            $reducable_balancde = round($child_user_balance * $set_ratio / 100) . "     -     ";
                            $actual_balance = $child_user_balance - $reducable_balancde . "     -     ";

                            $data_child = array(
                                'tr_sms_balance' => $actual_balance,
                            );

                            $this->db->where('user_id', $child_user_id);
                            $this->db->where('most_parent_id', $user_id);
                            $this->db->update('users', $data_child);

                            $data = array(
                                'txn_route' => "B",
                                'txn_sms' => $reducable_balancde,
                                'txn_price' => .13,
                                'txn_amount' => $reducable_balancde * .13,
                                'txn_type' => "Reduce",
                                'txn_admin_to' => 1,
                                'txn_user_from' => $child_user_id,
                                'txn_date' => $txn_date,
                                'txn_description' => "As per New Pricing",
                                'new_date' => date('Y-m-d'),
                                'tax_status' => 1,
                                'txn_status' => 1
                            );


                            $this->db->insert('transaction_logs', $data);
                        }
                    }
                }
            }
        }
    }

    public function daily_user_backup() {

        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        $this->db->select('`user_id`, `name`, `username`, `auth_key`, `pr_sms_balance`, `tr_sms_balance`, `long_code_balance`, `short_code_balance`, `pr_voice_balance`, `tr_voice_balance`, `missed_call_balance`, `special_pr_balance`, `special_tr_balance`, `prtodnd_balance`, `stock_balance`, `prtodnd_credits`, `stock_credits`');
        $this->db->from('users');
        $query = $this->db->get();
        $result = $query->result_array();

        foreach ($result as $result_data) {
            $full_data[] = $all_data = array(
                'user_id' => $result_data['user_id'],
                'name' => $result_data['name'],
                'username' => $result_data['username'],
                'auth_key' => $result_data['auth_key'],
                'pr_sms_balance' => $result_data['pr_sms_balance'],
                'tr_sms_balance' => $result_data['tr_sms_balance'],
                'long_code_balance' => $result_data['long_code_balance'],
                'short_code_balance' => $result_data['short_code_balance'],
                'pr_voice_balance' => $result_data['pr_voice_balance'],
                'missed_call_balance' => $result_data['missed_call_balance'],
                'special_pr_balance' => $result_data['special_pr_balance'],
                'special_tr_balance' => $result_data['special_tr_balance'],
                'prtodnd_balance' => $result_data['prtodnd_balance'],
                'stock_balance' => $result_data['stock_balance'],
                'prtodnd_credits' => $result_data['prtodnd_credits'],
                'stock_credits' => $result_data['stock_credits'],
                'date' => date('Y-m-d'),
            );
        }


        $this->db->insert_batch('user_daily_backup', $full_data);
    }

    public function check_language() {
        $language = 1;

        $message = "hello how are you";
        $mobile = "919575636818";

        if ($language == 1) {
            $data = $this->sms_model->checkLanguage($message, $mobile);
            print_r($data);
        } else {
            
        }
    }

    public function insert_jio() {

        $data = array(60010, 60011, 60012, 60013, 60014, 60015, 60016, 60017, 60018, 60019, 60020, 60021, 60022, 60023, 60024, 60025, 60026, 60027, 60028, 60029, 60030, 60031, 60032, 60033, 60034, 60035, 60036, 60037, 60038, 60039);
        $size = sizeof($data);
        for ($i = 0; $i < $size; $i ++) {
            $inser_data = array(
                'number' => $data[$i],
                'oprator' => "JIO",
                'status' => 1,
            );
            $this->db->insert('jio_state_data', $inser_data);
        }
    }

    public function check_state_language() {

        //Your authentication key
        $authKey = "971d3401cdfa0e4427335e1cc1cd08b0";
//Multiple mobiles numbers separated by comma
        $mobileNumber = "9575636818,9644314684,8982804000,8982805000";
//Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "SMSKSK";
//Your message to send, Add URL encoding here.
        $message = "your ticket is confirm on 21-08-2018 . please come on time, thank you";
//Define route 
        $route = 1;
//Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route,
            'language' => 1
        );
//API URL
        $url = "http://sms.bulksmsserviceproviders.com/api/http_regional_sms.php";
// init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
        ));
//Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//get response
        $output = curl_exec($ch);
//Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }
        curl_close($ch);
        echo $output;
    }

    public function check_length() {
        for ($i = 5; $i < 11; $i++) {
            echo '$final_array' . $i . ' = array_count_values($status_actual' . $i . ');';
            echo "<br>";
            echo '$final_key' . $i . ' = array_keys($final_array' . $i . ');';
            echo "<br>";
            echo '$size_final_array' . $i . ' = sizeof($final_array' . $i . ');';
            echo "<br>";
            echo "<br>";
        }
    }

    public function daily_amount_analysis() {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '107374182400');
        $date = date('Y-m-d', strtotime("-1 days"));

        $this->db->select('status');
        $this->db->from('sent_sms');
        $this->db->where('temporary_status', 1);
        $this->db->where('status', 1);
        $this->db->like('submit_date', $date);
        $query = $this->db->get();
        $total_delivered_sms = $query->num_rows();
        $total_delivered_amount = $total_delivered_sms * .115;

        $this->db->select('SUM(`txn_sms`) AS total_txn_sms, AVG(`txn_price`) AS total_txn_price, SUM(`txn_amount`) AS total_txn_amount');
        $this->db->from('transaction_logs');
        $this->db->where('txn_admin_from > ', 0);
        $this->db->where('txn_type', "Add");
        $this->db->where('new_date', $date);
        $query_log = $this->db->get();
        $total_result = $query_log->row();
        $total_sms = $total_result->total_txn_sms;
        $total_price = $total_result->total_txn_price;
        $total_amount = $total_result->total_txn_amount;
        $tax_total_amount = $total_amount * 18 / 100;
        $actual_amount = $total_amount - $tax_total_amount;

        $data_array = array(
            'total_sms' => $total_sms,
            'total_amount' => $actual_amount,
            'average_pricing' => $total_price,
            'delivered_sms' => $total_delivered_sms,
            'total_delivered_amount' => $total_delivered_amount,
            'date' => $date,
            'status' => 1,
        );
        $this->db->insert('daily_amount_analysis', $data_array);
    }
    //for meeting mailer

  // public function check_meeting () {
  //  $start_date = "25-03-2019";
  //  $end_date = "26-03-2019";  
  //   $this->db->select('email');
  //   $this->db->from('user_meetings');
  //   $this->db->where("`date` BETWEEN '" . $start_date . "' AND '" .$end_date . "'");
  //   $query = $this->db->get();
  //   $result = $query->result_array();
  //   print_r($result);
  
  //     }
  
  // end of meeting 
    public function check_mail_user(){
    
      $this->utility_model->sendEmailNew_test();
     }
      public function meeting_mail(){
    
      $data = $this->utility_model->MeetingMail();

      
     }
      public function signup_mail() {
       $data = $this->utility_model->SignupMail();
      }

      public function send_otp_sms() {
 
      $data = $this->utility_model->sendOtp();



      }
       public function send_daily_sign_sms() {
 
      $data = $this->utility_model->sendDailySignSms();

      

      }
       public function send_meeting_sms() {
 
      $data = $this->utility_model->sendMeetingSms();

      

      }
      public function send_subscribe_sms() {
 
      $data = $this->utility_model->sendSubscribeSms();

      

      }
    
   // public function check_mail_user(){
  //   $start_date = "2019-03-21";
  //   $end_date = "2019-03-26";

  //    $this->db->select('subscribe_email');
  //    $this->db->from('subscription');
  //    $this->db->where("`subscribe_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
  //        $query = $this->db->get();
  //        $result = $query->result_array();
  //        foreach($result  as $val){
  //        $b =implode(',',$val);
  //        echo $b;
  //        echo "<br>";
  //   }
  //    }




 public function my_url(){
      $url = "http://sms.bulksmsserviceproviders.com/api/http_regional_sms.php";
        $attach_url = $this->sms_model->googleUrlShortner($url);
        print_r($attach_url);
               
 }

 public function balance_alert() { 
        // $value['value'] = $this->registor_model->Balance_Alert();
               $this->utility_model->Balance_Alert();
     
         // $this->load->view('balanceAlert', $value);

    
    }


   }
 ?>