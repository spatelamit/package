<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Voice_Sms_Model extends CI_Model {

    // Class Constructor
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        $this->load->library('csv_reader');
        $this->load->library('csvreader');
        $this->load->model('Utility_Model', 'utility_model');
        $this->load->model('Sms_Model', 'sms_model');
    }
 
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Voice SMS
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Get File From Server
    function getFileFromURL($url = null) {
        /*
          $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
          $headers[] = 'Connection: Keep-Alive';
          $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
         */
        //$user_agent = 'php';
        $process = curl_init($url);
        //curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        //curl_setopt($process, CURLOPT_USERAGENT, $useragent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // VOICE SMS API INTERGRATION
    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Send Voice SMS (Using Infobip Voice SMS API)
    function sendVoiceMessage($user_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // Get User Details
        $this->benchmark->mark('Start_Time');
        $result_user = $this->sms_model->getUserSettings($user_id);
        if ($result_user) {
            // Load Library For File Uploading
            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('Myaudiolib', 'myaudiolib');
            //$this->load->helper('db_helper');
            $pr_voice_balance = $result_user->pr_voice_balance;
            $tr_voice_balance = $result_user->tr_voice_balance;
            $vtr_fake_ratio = $result_user->vtr_fake_ratio;
            $vtr_fail_ratio = $result_user->vtr_fail_ratio;
            $vpr_fake_ratio = $result_user->vpr_fake_ratio;
            $vpr_fail_ratio = $result_user->vpr_fail_ratio;
            // Form Values
            $route = $this->input->post('route');
            $campaign_name = $this->input->post('campaign_name');
            // Check Call Id
            $caller_id = $this->input->post('caller_id');
            /*
              if (strlen($caller_id) == 10) {
              $caller_id = "91" . $caller_id;
              } elseif (strlen($caller_id) == 12) {
              $caller_id = $caller_id;
              } else {
              return 112;
              }
             */
            $duration = $this->input->post('duration');
            // Schedule Voice SMS
            $current_date = date('Y-m-d H:i:s');
            $start_date_time = date('Y-m-d H:i:s');
            $end_date_time = date('Y-m-d H:i:s');

            /*
              // Check Dates
              $current_date = date('Y-m-d H:i:00');
              $start_date_time = $this->input->post('start_date_time');
              $end_date_time = $this->input->post('end_date_time');
              // If Start Time Less Than Now
              if ($current_date >= $start_date_time) {
              return 108;
              }
              // If End Time Less Than Now
              if ($current_date >= $end_date_time) {
              return 109;
              }
              // If End Time Less Than Start Time
              if ($start_date_time >= $end_date_time) {
              return 110;
              }
             */

            // Get Contacts From Form Field/Upload CSV File
            $mobile_array = array();
            $result_array = array();
            $mobile_numbers = $this->input->post('mobile_numbers');
            $temp_file_name = date('dmYhis') . "-" . $user_id;
            $config['file_name'] = $temp_file_name;
            $config['upload_path'] = './Temp_CSV_Files/';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            if ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $output = preg_replace('!\s+!', ' ', $mobile_numbers);
                $output = preg_replace('!\s+!', ',', $output);
                $result_array = explode(',', $output);
                $result_array = array_diff($result_array, array(''));
            } elseif ($mobile_numbers == "" && $this->upload->do_upload('mobiles')) {
                $uploaded_file = explode('.', $_FILES['mobiles']['name']);
                $extension = $uploaded_file[1];
                if ($extension == 'csv' || $extension == 'xls' || $extension == 'xlsx') {
                    $myfile = "./Temp_CSV_Files/" . $temp_file_name . ".csv";
                    $mobile_array = $this->csv_reader->parse_file($myfile, false);
                    $result_array = array_unique($mobile_array);
                } else {
                    return 107;
                }
            } elseif ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $result_array = explode(',', $mobile_numbers);
            }
            // Check Mobile Numbers Validation
            $flag = 0;
            $new_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (strlen($mobile) == 10) {
                        $new_array[] = "91" . $mobile;
                    } elseif (strlen($mobile) == 12) {
                        $new_array[] = $mobile;
                    } else {
                        $flag++;
                        break;
                    }
                }
            }




            // Some Mobile Numbers are invalid!
            if ($flag > 0) {
                return "104";
            }
            // Get Contacts From Groups
            $select_contact_array = array();
            if ($this->input->post('check_group')) {
                $selected_groups_array = $this->input->post('check_group');
                if (sizeof($selected_groups_array)) {
                    $result_contacts = $this->sms_model->getGroupsContacts($user_id);
                    if ($result_contacts) {
                        foreach ($result_contacts as $row_contact) {
                            $contact_groups_array = explode(',', $row_contact['contact_group_ids']);
                            $array_intersect = array_intersect($selected_groups_array, $contact_groups_array);
                            if (sizeof($array_intersect)) {
                                if (strlen($row_contact['mobile_number']) == 10) {
                                    $select_contact_array[] = "91" . $row_contact['mobile_number'];
                                } elseif (strlen($row_contact['mobile_number']) == 12) {
                                    $select_contact_array[] = $row_contact['mobile_number'];
                                }
                            }
                        }
                    }
                }
            }
            // Final Result Array For Mobile Numbers
            $result_array = array_unique(array_merge($new_array, $select_contact_array));
            // Get Voice File
            $voice_file = "";
            $temp_voice_file = "";
            $voice_file_url = $this->input->post('voice_file_url');
            $temp_name = "voice" . date('dmYhis') . $user_id;
            $config1['file_name'] = $temp_name;
            $config1['upload_path'] = './Uploads/';
            $config1['allowed_types'] = '*';
            $this->upload->initialize($config1);
            if ($voice_file_url != "" && !$this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', basename($voice_file_url));
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                    // Get the file From URL
                    $file = $this->getFileFromURL($voice_file_url);
                    file_put_contents($temp_voice_file, $file);
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && $this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', $_FILES['upload_voice_file']['name']);
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && !$this->upload->do_upload('upload_voice_file') && $this->input->post('voice_draft_file')) {
                $uploaded_file = explode('|', $this->input->post('voice_draft_file'));
                $voice_file = $uploaded_file[0];
                $temp_voice_file = "Voice/" . $uploaded_file[1];
            }


            // Add Scheme On Url
            /*
              $parsed = parse_url($voice_file);
              if (empty($parsed['scheme'])) {
              $voice_file = 'http://' . ltrim($voice_file, '/');
              }
             */

            // Rejected Numbers
            $reject_list_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (substr($mobile, 0, 2) != 91) {
                        $reject_list_array[] = $mobile;
                    }
                }
            }

            // Get Black Lists & Remove Black List Numbers
            $bresult_array = array();
            $black_list_array = array();
            $result_black_list = $this->sms_model->getBlackLists();
            if ($result_black_list) {
                $black_list_array = $result_black_list;
                $bresult_array = array_intersect($result_array, $black_list_array);
                $result_array = array_diff($result_array, $black_list_array);
            }

            // Total Messages To Be Submit
            $total_messages = sizeof($result_array);
            $mp3file = $this->myaudiolib->getFile($temp_voice_file);
            $duration1 = $this->myaudiolib->getDurationEstimate(); //(faster) for CBR only
            //$duration2 = $this->mp3lib->getDuration(); //(slower) for VBR (or CBR)
            /*
              echo "File: $voice_file" . "<br>";
              echo "File: $temp_voice_file" . "<br>";
              echo "Duration: $duration1 Seconds" . "<br>";
              echo "Estimate: $duration2 Seconds" . "<br>";
              echo "Format:" . mp3lib::formatTime($duration2) . "<br>";
              die;
             */


            // Calculate Total Credits
            $total_credits = 0;
            if ($duration1 % 30 == 0) {
                $total_credits = intval($duration1 / 30);
            } else {
                $total_credits = intval($duration1 / 30) + 1;
            }
            $actual_balance_required = $total_messages * $total_credits;

            // Check Result Array
            if ($result_array && sizeof($result_array)) {
                // Promotional Route
                if ($route == "A") {
                    // Check Available Balance And Send Number of Message
                    if ($pr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Promotional Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;



                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;

                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vpr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vpr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vpr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vpr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // Infobip Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $curl_post_data = array(
                                          "destinationAddress" => $result_array[0],
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $update_sms = json_decode($curl_response_data_json);
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $update_sms);
                                          }
                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $curl_post_data = array(
                                          "destinationAddresses" => $result_array,
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $array = json_decode($curl_response_data_json);
                                          $status_array = $array->ttsCallStatuses;
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value->ttsCallRequestId,
                                          'status' => $value->status,
                                          'description' => $value->description
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VPR";
                        $updated_sms_balance = $pr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }

                // Dynamic Route
                if ($route == "B") {
                    // Check Available Balance And Send Number of Message
                    if ($tr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Dynamic Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;



                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }

                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vtr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vtr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);

                                    // Get Fake Delivered Number
                                    if ($vtr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }

                                    // Get Fake Failed Number
                                    if ($vtr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        //--------------------------------------------------------//
                                        // Infobip Voice SMS API
                                        // Single Destination Address


                                        if (sizeof($result_array) == 1) {

                                            $curl_post_data = array(
                                                "destinationAddress" => $result_array[0],
                                                "voiceFileUrl" => $voice_file,
                                                "language" => "en",
                                                "sourceAddress" => $caller_id,
                                                "record" => true,
                                                "waitForDtmf" => true,
                                                "dtmfTimeout" => 5,
                                                "retry" => false,
                                                "ringTimeout" => 45
                                            );

                                            //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                            $curl_post_data_json = json_encode($curl_post_data);
                                            //Calling TTS API using php curl
                                            $service_url = "http://api.infobip.com/tts/3/single";
                                            $username = 'Balajigroup';
                                            $password = 'Mayank@1234';
                                            $curl = curl_init($service_url);
                                            //Authentication:
                                            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                            curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                                "accept: application/json",
                                                "authorization: Basic QmFsYWppZ3JvdXA6QmFsQDIwMTc=",
                                                "content-type: application/json"
                                            ));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($curl, CURLOPT_POST, true);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                            $curl_response_data_json = curl_exec($curl);
                                            curl_close($curl);


                                            // Get Status of Each Number & Update into table
                                            $update_sms = json_decode($curl_response_data_json);
                                            $this->db->where('mobile_no', $result_array[0]);
                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update('sent_sms', $update_sms);
                                        }


                                        // Multiple Destination Address
                                        if (sizeof($result_array) > 1) {

                                            $curl_post_data = array(
                                                "destinationAddresses" => $result_array,
                                                "voiceFileUrl" => $voice_file,
                                                "language" => "en",
                                                "sourceAddress" => $caller_id,
                                                "record" => true,
                                                "waitForDtmf" => true,
                                                "dtmfTimeout" => 5,
                                                "retry" => false,
                                                "ringTimeout" => 45
                                            );

                                            //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                            $curl_post_data_json = json_encode($curl_post_data);
                                            //Calling TTS API using php curl
                                            $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                            $username = 'Balajigroup';
                                            $password = 'Mayank@1234';
                                            $curl = curl_init($service_url);
                                            //Authentication:
                                            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                            curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($curl, CURLOPT_POST, true);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                            $curl_response_data_json = curl_exec($curl);
                                            curl_close($curl);


                                            // Get Status of Each Number & Update into table
                                            $array = json_decode($curl_response_data_json);
                                            $status_array = $array->ttsCallStatuses;
                                            // Generate Update Array
                                            foreach ($status_array as $key => $value) {
                                                $update_smsS[] = array(
                                                    'mobile_no' => $result_array[$key],
                                                    'ttsCallRequestId' => $value->ttsCallRequestId,
                                                    'status' => $value->status,
                                                    'description' => $value->description
                                                );
                                            }

                                            $this->db->where('campaign_id', $campaign_id);
                                            $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                        }
                                        //--------------------------------------------------------//
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VTR";
                        $updated_sms_balance = $tr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }
            } else {
                return 105;
            }
        }
    }

    // Send Voice SMS (Using Bulk24SMS Voice SMS API)
    function sendVoiceMessageB24SMS($user_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // Get User Details
        $this->benchmark->mark('Start_Time');
        $result_user = $this->sms_model->getUserSettings($user_id);
        if ($result_user) {
            // Load Library For File Uploading
            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('myaudiolib');

            $pr_voice_balance = $result_user->pr_voice_balance;
            $tr_voice_balance = $result_user->tr_voice_balance;
            $vtr_fake_ratio = $result_user->vtr_fake_ratio;
            $vtr_fail_ratio = $result_user->vtr_fail_ratio;
            $vpr_fake_ratio = $result_user->vpr_fake_ratio;
            $vpr_fail_ratio = $result_user->vpr_fail_ratio;
            // Form Values
            $route = $this->input->post('route');
            $campaign_name = $this->input->post('campaign_name');
            // Check Call Id
            $caller_id = $this->input->post('caller_id');
            /*
              if (strlen($caller_id) == 10) {
              $caller_id = "91" . $caller_id;
              } elseif (strlen($caller_id) == 12) {
              $caller_id = $caller_id;
              } else {
              return 112;
              }
             */
            $duration = $this->input->post('duration');
            // Schedule Voice SMS
            $current_date = date('Y-m-d H:i:s');
            $start_date_time = date('Y-m-d H:i:s');
            $end_date_time = date('Y-m-d H:i:s');
            /*
              // Check Dates
              $current_date = date('Y-m-d H:i:00');
              $start_date_time = $this->input->post('start_date_time');
              $end_date_time = $this->input->post('end_date_time');
              // If Start Time Less Than Now
              if ($current_date >= $start_date_time) {
              return 108;
              }
              // If End Time Less Than Now
              if ($current_date >= $end_date_time) {
              return 109;
              }
              // If End Time Less Than Start Time
              if ($start_date_time >= $end_date_time) {
              return 110;
              }
             */
            // Get Contacts From Form Field/Upload CSV File
            $mobile_array = array();
            $result_array = array();
            $mobile_numbers = $this->input->post('mobile_numbers');
            $temp_file_name = date('dmYhis') . "-" . $user_id;
            $config['file_name'] = $temp_file_name;
            $config['upload_path'] = './Temp_CSV_Files/';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            if ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $output = preg_replace('!\s+!', ' ', $mobile_numbers);
                $output = preg_replace('!\s+!', ',', $output);
                $result_array = explode(',', $output);
                $result_array = array_diff($result_array, array(''));
            } elseif ($mobile_numbers == "" && $this->upload->do_upload('mobiles')) {
                $uploaded_file = explode('.', $_FILES['mobiles']['name']);
                $extension = $uploaded_file[1];
                if ($extension == 'csv' || $extension == 'xls' || $extension == 'xlsx') {
                    $myfile = "./Temp_CSV_Files/" . $temp_file_name . ".csv";
                    $mobile_array = $this->csv_reader->parse_file($myfile, false);
                    $result_array = array_unique($mobile_array);
                } else {
                    return 107;
                }
            } elseif ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $result_array = explode(',', $mobile_numbers);
            }
            // Check Mobile Numbers Validation
            $flag = 0;
            $new_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (strlen($mobile) == 10) {
                        $new_array[] = "91" . $mobile;
                    } elseif (strlen($mobile) == 12) {
                        $new_array[] = $mobile;
                    } else {
                        $flag++;
                        break;
                    }
                }
            }



            // Some Mobile Numbers are invalid!
            if ($flag > 0) {
                return "104";
            }
            // Get Contacts From Groups
            $select_contact_array = array();
            if ($this->input->post('check_group')) {
                $selected_groups_array = $this->input->post('check_group');
                if (sizeof($selected_groups_array)) {
                    $result_contacts = $this->sms_model->getGroupsContacts($user_id);
                    if ($result_contacts) {
                        foreach ($result_contacts as $row_contact) {
                            $contact_groups_array = explode(',', $row_contact['contact_group_ids']);
                            $array_intersect = array_intersect($selected_groups_array, $contact_groups_array);
                            if (sizeof($array_intersect)) {
                                if (strlen($row_contact['mobile_number']) == 10) {
                                    $select_contact_array[] = "91" . $row_contact['mobile_number'];
                                } elseif (strlen($row_contact['mobile_number']) == 12) {
                                    $select_contact_array[] = $row_contact['mobile_number'];
                                }
                            }
                        }
                    }
                }
            }
            // Final Result Array For Mobile Numbers
            $result_array = array_unique(array_merge($new_array, $select_contact_array));

            // Get Voice File
            $voice_file = "";
            $temp_voice_file = "";
            $voice_file_url = $this->input->post('voice_file_url');
            $temp_name = "voice" . date('dmYhis') . $user_id;
            $config1['file_name'] = $temp_name;
            $config1['upload_path'] = './Uploads/';
            $config1['allowed_types'] = '*';
            $this->upload->initialize($config1);
            if ($voice_file_url != "" && !$this->upload->do_upload()) {
                $uploaded_file = explode('.', basename($voice_file_url));
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                    // Get the file From URL
                    $file = $this->getFileFromURL($voice_file_url);
                    file_put_contents($temp_voice_file, $file);
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && $this->upload->do_upload('upload_voice_file')) {
                $uploaded_file = explode('.', $_FILES['upload_voice_file']['name']);
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            }

            // Rejected Numbers
            $reject_list_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (substr($mobile, 0, 2) != 91) {
                        $reject_list_array[] = $mobile;
                    }
                }
            }

            // Get Black Lists & Remove Black List Numbers
            $bresult_array = array();
            $black_list_array = array();
            $result_black_list = $this->sms_model->getBlackLists();
            if ($result_black_list) {
                $black_list_array = $result_black_list;
                $bresult_array = array_intersect($result_array, $black_list_array);
                $result_array = array_diff($result_array, $black_list_array);
            }

            // Total Messages To Be Submit
            $total_messages = sizeof($result_array);
            $mp3file = $this->myaudiolib->getFile($temp_voice_file);
            $duration1 = $this->myaudiolib->getDurationEstimate(); //(faster) for CBR only
            // Calculate Total Credits
            $total_credits = 0;
            if ($duration1 % 30 == 0) {
                $total_credits = intval($duration1 / 30);
            } else {
                $total_credits = intval($duration1 / 30) + 1;
            }
            $actual_balance_required = $total_messages * $total_credits;
            // Check Result Array
            if ($result_array && sizeof($result_array)) {
                // Promotional Route
                if ($route == "A") {
                    // Check Available Balance And Send Number of Message
                    if ($pr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Promotional Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;


                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }
                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;

                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }
                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vpr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vpr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vpr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vpr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }
                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        //--------------------------------------------------------//
                                        // Bulk24SMS Networks Voice SMS API
                                        $api_url = "http://sms.bulk24sms.com/send_voice_mail.php";
                                        $api_authkey = "1752APJjoFwLC55642493";
                                        $api_route = "2";




                                        $voice_sms_array = array(
                                            'authkey' => $api_authkey,
                                            'campaign' => $campaign_name,
                                            'sender' => $caller_id,
                                            'mobiles' => implode(',', $result_array),
                                            'duration' => $duration,
                                            'url_file_name' => $voice_file,
                                            'route' => $api_route,
                                            'schtimestart' => $start_date_time,
                                            'schtimeend' => $end_date_time
                                        );
                                        $api_res = $this->utility_model->sendSMS($api_url, $voice_sms_array);
                                        //--------------------------------------------------------//
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VPR";
                        $updated_sms_balance = $pr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }
                // Dynamic Route
                if ($route == "B") {
                    // Check Available Balance And Send Number of Message
                    if ($tr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Dynamic Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;



                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }
                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }
                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vtr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vtr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vtr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vtr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }
                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        //--------------------------------------------------------//
                                        // Bulk24SMS Networks Voice SMS API

                                        $api_url = "http://sms.bulk24sms.com/send_voice_mail.php";
                                        $api_authkey = "1752APJjoFwLC55642493";
                                        $api_route = "2";
                                        $voice_sms_array = array(
                                            'authkey' => $api_authkey,
                                            'campaign' => $campaign_name,
                                            'sender' => $caller_id,
                                            'mobiles' => implode(',', $result_array),
                                            'duration' => $duration,
                                            'url_file_name' => $voice_file,
                                            'route' => $api_route,
                                            'schtimestart' => $start_date_time,
                                            'schtimeend' => $end_date_time
                                        );
                                        $api_res = $this->utility_model->sendSMS($api_url, $voice_sms_array);
                                        //--------------------------------------------------------//
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VTR";
                        $updated_sms_balance = $tr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }
            } else {
                return 105;
            }
        }
    }

    // Send Voice SMS (Using SmartAlertBox Voice SMS API)
    function sendVoiceMessageNew($user_id = 0) {
        ini_set('max_input_time', 24000);
        ini_set('max_execution_time', 24000);
        ini_set('memory_limit', '1073741824');
        // Get User Details
        $this->benchmark->mark('Start_Time');
        $result_user = $this->sms_model->getUserSettings($user_id);
        if ($result_user) {
            // Load Library For File Uploading
            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('myaudiolib');
            //$this->load->helper('db_helper');

            $pr_voice_balance = $result_user->pr_voice_balance;
            $tr_voice_balance = $result_user->tr_voice_balance;
            $vtr_fake_ratio = $result_user->vtr_fake_ratio;
            $vtr_fail_ratio = $result_user->vtr_fail_ratio;
            $vpr_fake_ratio = $result_user->vpr_fake_ratio;
            $vpr_fail_ratio = $result_user->vpr_fail_ratio;
            // Form Values
            $route = $this->input->post('route');
            $campaign_name = $this->input->post('campaign_name');
            // Check Call Id
            $caller_id = $this->input->post('caller_id');

            /*
              if (strlen($caller_id) == 10) {
              $caller_id = "91" . $caller_id;
              } elseif (strlen($caller_id) == 12) {
              $caller_id = $caller_id;
              } else {
              return 112;
              }
             */
            $duration = $this->input->post('duration');
            // Schedule Voice SMS
            $current_date = date('Y-m-d H:i:s');
            $start_date_time = date('Y-m-d H:i:s');
            $end_date_time = date('Y-m-d H:i:s');
            /*
              // Check Dates
              $current_date = date('Y-m-d H:i:00');
              $start_date_time = $this->input->post('start_date_time');
              $end_date_time = $this->input->post('end_date_time');
              // If Start Time Less Than Now
              if ($current_date >= $start_date_time) {
              return 108;
              }
              // If End Time Less Than Now
              if ($current_date >= $end_date_time) {
              return 109;
              }
              // If End Time Less Than Start Time
              if ($start_date_time >= $end_date_time) {
              return 110;
              }
             */
            // Get Contacts From Form Field/Upload CSV File
            $mobile_array = array();
            $result_array = array();
            $mobile_numbers = $this->input->post('mobile_numbers');
            $temp_file_name = date('dmYhis') . "-" . $user_id;
            $config['file_name'] = $temp_file_name;
            $config['upload_path'] = './Temp_CSV_Files/';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            if ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $output = preg_replace('!\s+!', ' ', $mobile_numbers);
                $output = preg_replace('!\s+!', ',', $output);
                $result_array = explode(',', $output);
                $result_array = array_diff($result_array, array(''));
            } elseif ($mobile_numbers == "" && $this->upload->do_upload('mobiles')) {
                $uploaded_file = explode('.', $_FILES['mobiles']['name']);
                $extension = $uploaded_file[1];
                if ($extension == 'csv' || $extension == 'xls' || $extension == 'xlsx') {
                    $myfile = "./Temp_CSV_Files/" . $temp_file_name . ".csv";
                    $mobile_array = $this->csv_reader->parse_file($myfile, false);
                    $result_array = array_unique($mobile_array);
                } else {
                    return 107;
                }
            } elseif ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $result_array = explode(',', $mobile_numbers);
            }
            // Check Mobile Numbers Validation
            $flag = 0;
            $new_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (strlen($mobile) == 10) {
                        $new_array[] = $mobile;
                    } elseif (strlen($mobile) == 12) {
                        $new_array[] = $mobile;
                    } else {
                        $flag++;
                        break;
                    }
                }
            }




            // Some Mobile Numbers are invalid!
            if ($flag > 0) {
                return "104";
            }
            // Get Contacts From Groups
            $select_contact_array = array();
            if ($this->input->post('check_group')) {
                $selected_groups_array = $this->input->post('check_group');
                if (sizeof($selected_groups_array)) {
                    $result_contacts = $this->sms_model->getGroupsContacts($user_id);
                    if ($result_contacts) {
                        foreach ($result_contacts as $row_contact) {
                            $contact_groups_array = explode(',', $row_contact['contact_group_ids']);
                            $array_intersect = array_intersect($selected_groups_array, $contact_groups_array);
                            if (sizeof($array_intersect)) {
                                if (strlen($row_contact['mobile_number']) == 10) {
                                    $select_contact_array[] = "91" . $row_contact['mobile_number'];
                                } elseif (strlen($row_contact['mobile_number']) == 12) {
                                    $select_contact_array[] = $row_contact['mobile_number'];
                                }
                            }
                        }
                    }
                }
            }
            // Final Result Array For Mobile Numbers
            $result_array = array_unique(array_merge($new_array, $select_contact_array));

            // Get Voice File
            $voice_file = "";
            $temp_voice_file = "";
            $voice_file_url = $this->input->post('voice_file_url');
            $temp_name = "voice" . date('dmYhis') . $user_id;
            $config1['file_name'] = $temp_name;
            $config1['upload_path'] = './Uploads/';
            $config1['allowed_types'] = '*';
            $this->upload->initialize($config1);
            if ($voice_file_url != "" && !$this->upload->do_upload() && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', basename($voice_file_url));
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                    // Get the file From URL
                    $file = $this->getFileFromURL($voice_file_url);
                    file_put_contents($temp_voice_file, $file);
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && $this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', $_FILES['upload_voice_file']['name']);
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && !$this->upload->do_upload() && $this->input->post('voice_draft_file')) {
                $uploaded_file = explode('|', $this->input->post('voice_draft_file'));
                $voice_file = $uploaded_file[0];
                $temp_voice_file = "Voice/" . $uploaded_file[1];
            }

            // Add Scheme On Url
            /*
              $parsed = parse_url($voice_file);
              if (empty($parsed['scheme'])) {
              $voice_file = 'http://' . ltrim($voice_file, '/');
              }
             */

            // Rejected Numbers
            $reject_list_array = array();
            /* /**
              if (sizeof($result_array)) {
              foreach ($result_array as $mobile) {
              if (substr($mobile, 0, 2) != 91) {
              $reject_list_array[] = $mobile;
              }
              }
              }
             */

            // Get Black Lists & Remove Black List Numbers
            $bresult_array = array();
            $black_list_array = array();
            $result_black_list = $this->sms_model->getBlackLists();
            if ($result_black_list) {
                $black_list_array = $result_black_list;
                $bresult_array = array_intersect($result_array, $black_list_array);
                $result_array = array_diff($result_array, $black_list_array);
            }

            // Total Messages To Be Submit
            $total_messages = sizeof($result_array);
            $mp3file = $this->myaudiolib->getFile($temp_voice_file);
            $duration1 = 28;
            //$duration1 = $this->myaudiolib->getDurationEstimate(); //(faster) for CBR only
            //$duration2 = $this->mp3lib->getDuration(); //(slower) for VBR (or CBR)
            /*
              echo "File: $voice_file" . "<br>";
              echo "File: $temp_voice_file" . "<br>";
              echo "Duration: $duration1 Seconds" . "<br>";
              echo "Estimate: $duration2 Seconds" . "<br>";
              echo "Format:" . mp3lib::formatTime($duration2) . "<br>";
              die;
             */

            // Calculate Total Credits
            $total_credits = 0;
            if ($duration1 % 30 == 0) {
                $total_credits = intval($duration1 / 30);
            } else {
                $total_credits = intval($duration1 / 30) + 1;
            }
            $actual_balance_required = $total_messages * $total_credits;
            // Check Result Array
            if ($result_array && sizeof($result_array)) {
                $api_url = "http://smsalertbox.com/api/voice.php?";
                $uid = "6d6179616e6b303030";
                $pin = "4022ff71b1c45f2183e5952b84481d3f";
                $file_id = "8067";
                // Promotional Route
                if ($route == "A") {
                    // Check Available Balance And Send Number of Message
                    if ($pr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Promotional Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;



                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;

                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vpr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vpr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vpr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vpr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }
                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // New Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $service_url = $api_url;
                                          $parameters = "uid=$uid&";
                                          $parameters.="pin=$pin&";
                                          $parameters.="voice=$file_id&";
                                          $parameters.="route=voicecall&";
                                          $parameters.="number=" . $result_array[0];
                                          $url = $service_url . "" . $parameters;
                                          // API URL
                                          $ch = curl_init($url);
                                          curl_setopt_array($ch, array(
                                          CURLOPT_URL => $url,
                                          CURLOPT_RETURNTRANSFER => true
                                          ));
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                          if ($curl_response_data_json = curl_exec($ch)) {
                                          curl_close($ch);
                                          // Get Status of Each Number & Update into table
                                          $data = array(
                                          'ttsCallRequestId' => $curl_response_data_json
                                          );
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $data);
                                          }
                                          }

                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $service_url = $api_url;
                                          $parameters = "uid=$uid&";
                                          $parameters.="pin=$pin&";
                                          $parameters.="voice=$file_id&";
                                          $parameters.="route=voicecall&";
                                          $parameters.="number=" . implode(',', $result_array);
                                          $url = $service_url . "" . $parameters;

                                          $myfile = fopen("./voice_api/voice_api_hits.txt", "a");
                                          if ($myfile) {
                                          $txt = "$url";
                                          fwrite($myfile, "\n" . $txt);
                                          fclose($myfile);
                                          }

                                          // API URL
                                          $ch = curl_init($url);
                                          curl_setopt_array($ch, array(
                                          CURLOPT_URL => $url,
                                          CURLOPT_RETURNTRANSFER => true
                                          ));
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                          if ($curl_response_data_json = curl_exec($ch)) {
                                          curl_close($ch);
                                          // Get Status of Each Number & Update into table
                                          $status_array = explode(',', $curl_response_data_json);
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VPR";
                        $updated_sms_balance = $pr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }

                // Dynamic Route
                if ($route == "B") {
                    // Check Available Balance And Send Number of Message
                    if ($tr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Dynamic Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;



                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }
                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }
                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vtr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vtr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vtr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vtr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }
                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // New Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $service_url = $api_url;
                                          $parameters = "uid=$uid&";
                                          $parameters.="pin=$pin&";
                                          $parameters.="voice=$file_id&";
                                          $parameters.="route=voicecall&";
                                          $parameters.="number=" . $result_array[0];
                                          $url = $service_url . "" . $parameters;
                                          // API URL
                                          $ch = curl_init($url);
                                          curl_setopt_array($ch, array(
                                          CURLOPT_URL => $url,
                                          CURLOPT_RETURNTRANSFER => true
                                          ));
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                          if ($curl_response_data_json = curl_exec($ch)) {
                                          curl_close($ch);
                                          // Get Status of Each Number & Update into table
                                          $data = array(
                                          'ttsCallRequestId' => $curl_response_data_json
                                          );
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $data);
                                          }
                                          }

                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $service_url = $api_url;
                                          $parameters = "uid=$uid&";
                                          $parameters.="pin=$pin&";
                                          $parameters.="voice=$file_id&";
                                          $parameters.="route=voicecall&";
                                          $parameters.="number=" . implode(',', $result_array);
                                          $url = $service_url . "" . $parameters;
                                          // API URL
                                          $ch = curl_init($url);
                                          curl_setopt_array($ch, array(
                                          CURLOPT_URL => $url,
                                          CURLOPT_RETURNTRANSFER => true
                                          ));
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                                          if ($curl_response_data_json = curl_exec($ch)) {
                                          curl_close($ch);
                                          // Get Status of Each Number & Update into table
                                          $status_array = explode(',', $curl_response_data_json);
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VTR";
                        $updated_sms_balance = $tr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }
            } else {
                return 105;
            }
        }
    }

    public function updateVoiceStatusVideocon() {
        $current_time = date('Y-m-d H:i:s');
        $start_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -60 minutes")); 

        $end_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . " -1 minutes"));
        $current_date = date('Y-m-d H:i:s');

        $this->db->select('`campaign_id`');
        $this->db->from('campaigns');
        $this->db->where("`submit_date` BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $this->db->where('service_type', "VOICE");
        $query = $this->db->get();
        $result = $query->result_array();

        foreach ($result as $result_new) {
            $campaign_id = $result_new['campaign_id'];
            $this->db->select('`campaign_id`,`msg_id`');
            $this->db->from('sent_sms');
            $this->db->where('campaign_id', $campaign_id);
            $query_sent_sms = $this->db->get();
            $result_sent = $query_sent_sms->row();
            $msg_id = $result_sent->msg_id;
            $email = "tiwari12mayank@gmail.com";   // Enter User Email
            $password = "Connect@123";    // Enter User Password
            $VoiceID = $msg_id;          // Enter Voice File ID
# URL Of Campaign Creation 
            $url = 'http://202.164.57.172/CampaignData';

# Post Data Array
            $data = array('username' => $email, 'password' => $password, 'campaignid' => $VoiceID);

            $options = array(
                CURLOPT_POST => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true, // return web page
                CURLOPT_HEADER => false, // don't return headers
                CURLOPT_FOLLOWLOCATION => true, // follow redirects
                CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                CURLOPT_ENCODING => "", // handle compressed
                CURLOPT_USERAGENT => "test", // name of client
                CURLOPT_AUTOREFERER => true, // set referrer on redirect
                CURLOPT_CONNECTTIMEOUT => 120, // time-out on connect
                CURLOPT_TIMEOUT => 120, // time-out on response
            );

            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);
            $upadte_sms = json_decode($content, true);
            $new_update = $upadte_sms['message'];


            foreach ($new_update as $dlr_update) {


                $new_msg_id = $dlr_update['CampaignID'];
                if ($new_msg_id == $msg_id) {

                    $done_date = $dlr_update['EndTIME'];
                    $disctiption = $dlr_update['Call_Status'];
                    if ($disctiption == "ANSWERED") {
                        $status = 1;
                    } else {
                        $status = 2;
                    }
                    $mobile = $dlr_update['MSISDN'];
                    $end = substr($mobile, 1, 10);
                    $full = "91" . $end;

                    $update_data = array(
                        'msg_id' => $msg_id,
                        'status' => $status,
                        'description' => $disctiption,
                        'done_date' => $done_date,
                    );


                    $this->db->where('msg_id', $msg_id);
                    $this->db->where('mobile_no', $full);
                    $this->db->where('campaign_id', $campaign_id);
                    $this->db->update('sent_sms', $update_data);
                }
            }
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
    // Update Voice SMS Status
    function updateVoiceSMSStatus() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.infobip.com/tts/3/reports",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Basic QmFsYWppZ3JvdXA6QmFsMTIz",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);


        curl_close($curl);
        $upadte_sms = json_decode($response, true);


        $value = sizeof($upadte_sms);
        $result_sms = $this->sms_model->getVoiceSMS();
        if ($value) {
            $devide_array = array();
            $size_results = sizeof($upadte_sms['results']);

            for ($k = 0; $k < $size_results; $k++) {
                $devide_array[$k] = $upadte_sms['results'][$k];
            }
            $size = sizeof($devide_array);
            for ($i = 0; $i < $size; $i++) {
                $bulkid = $devide_array[$i]['bulkId'];
                $to = $devide_array[$i]['to'];

                $messageid = $devide_array[$i]['messageId'];

                $status = $devide_array[$i]['status']['groupName'];

                $discription = $devide_array[$i]['error']['description'];
                if ($status == "DELIVERED") {
                    $status = 1;
                }
                if ($status == "EXPIRED") {
                    $status = 2;
                }
                if ($status == "REJECTED") {
                    $status = 3;
                }
                $done_date = date('Y-m-d H:i:s');
                $data = array(
                    'status' => $status,
                    'done_date' => $done_date,
                    'description' => $discription
                );

                $this->db->where('msg_id', $messageid);
                $this->db->where('mobile_no', $to);
                $this->db->where('ttsCallRequestId', $bulkid);
                $this->db->update('sent_sms', $data);
            }
        }
    }

    // Send Voice SMS (Using Infobip Voice SMS API)
    function sendVoiceMessage1($user_id = 0) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // Get User Details
        $this->benchmark->mark('Start_Time');
        $result_user = $this->sms_model->getUserSettings($user_id);
        if ($result_user) {
            // Load Library For File Uploading
            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('Myaudiolib', 'myaudiolib');
            //$this->load->helper('db_helper');
            $pr_voice_balance = $result_user->pr_voice_balance;
            $voice_tr_route = $result_user->voice_tr_route;
            $voice_pr_route = $result_user->voice_pr_route;
            $tr_voice_balance = $result_user->tr_voice_balance;
            $vtr_fake_ratio = $result_user->vtr_fake_ratio;
            $vtr_fail_ratio = $result_user->vtr_fail_ratio;
            $vpr_fake_ratio = $result_user->vpr_fake_ratio;
            $vpr_fail_ratio = $result_user->vpr_fail_ratio;
            // Form Values
            $route = $this->input->post('route');
            $campaign_name = $this->input->post('campaign_name');
            // Check Call Id
            $caller_id = $this->input->post('caller_id');
            /*
              if (strlen($caller_id) == 10) {
              $caller_id = "91" . $caller_id;
              } elseif (strlen($caller_id) == 12) {
              $caller_id = $caller_id;
              } else {
              return 112;
              }
             */
            $duration = $this->input->post('duration');
            // Schedule Voice SMS
            //$current_date = '2016-04-08 18:47:07';
            // $start_date_time = '2016-04-08 18:47:07';
            //$end_date_time = '2016-04-08 18:47:07';
            // Check Dates
            $current_date = date('Y-m-d H:i:00');
            $start_date_time = date('Y-m-d H:i:00');
            // $start_date_time = $this->input->post('start_date_time');
            $end_date_time = date('Y-m-d H:i:00');
            //  $end_date_time = $this->input->post('end_date_time');
            /*
              // If Start Time Less Than Now
              if ($current_date >= $start_date_time) {
              return 108;
              }
              // If End Time Less Than Now
              if ($current_date >= $end_date_time) {
              return 109;
              }
              // If End Time Less Than Start Time
              if ($start_date_time >= $end_date_time) {
              return 110;
              }
             */

            // Get Contacts From Form Field/Upload CSV File
            $mobile_array = array();
            $result_array = array();
            $mobile_numbers = $this->input->post('mobile_numbers');
            $temp_file_name = date('dmYhis') . "-" . $user_id;
            $config['file_name'] = $temp_file_name;
            $config['upload_path'] = './Temp_CSV_Files/';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);
            if ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $output = preg_replace('!\s+!', ' ', $mobile_numbers);
                $output = preg_replace('!\s+!', ',', $output);
                $result_array = explode(',', $output);
                $result_array = array_diff($result_array, array(''));
            } elseif ($mobile_numbers == "" && $this->upload->do_upload('mobiles')) {
                $uploaded_file = explode('.', $_FILES['mobiles']['name']);
                $extension = $uploaded_file[1];
                if ($extension == 'csv' || $extension == 'xls' || $extension == 'xlsx') {
                    $myfile = "./Temp_CSV_Files/" . $temp_file_name . ".csv";
                    $mobile_array = $this->csv_reader->parse_file($myfile, false);
                    $result_array = array_unique($mobile_array);
                } else {
                    return 107;
                }
            } elseif ($mobile_numbers != "" && !$this->upload->do_upload()) {
                $result_array = explode(',', $mobile_numbers);
            }
            // Check Mobile Numbers Validation
            $flag = 0;
            $new_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (strlen($mobile) == 10) {
                        $new_array[] = "91" . $mobile;
                    } elseif (strlen($mobile) == 12) {
                        $new_array[] = $mobile;
                    } else {
                        //  $flag++;
                        // break;
                    }
                }
            }


            // Some Mobile Numbers are invalid!
            /*
              if ($flag > 0) {
              return "104";
              }
             */

            // Get Contacts From Groups
            $select_contact_array = array();
            if ($this->input->post('check_group')) {
                $selected_groups_array = $this->input->post('check_group');
                if (sizeof($selected_groups_array)) {
                    $result_contacts = $this->sms_model->getGroupsContacts($user_id);
                    if ($result_contacts) {
                        foreach ($result_contacts as $row_contact) {
                            $contact_groups_array = explode(',', $row_contact['contact_group_ids']);
                            $array_intersect = array_intersect($selected_groups_array, $contact_groups_array);
                            if (sizeof($array_intersect)) {
                                if (strlen($row_contact['mobile_number']) == 10) {
                                    $select_contact_array[] = "91" . $row_contact['mobile_number'];
                                } elseif (strlen($row_contact['mobile_number']) == 12) {
                                    $select_contact_array[] = $row_contact['mobile_number'];
                                }
                            }
                        }
                    }
                }
            }
            // Final Result Array For Mobile Numbers
            $result_array = array_unique(array_merge($new_array, $select_contact_array));
            // Get Voice File
            $voice_file = "";
            $temp_voice_file = "";
            $voice_file_url = $this->input->post('voice_file_url');
            $temp_name = "voice" . date('dmYhis') . $user_id;
            $config1['file_name'] = $temp_name;
            $config1['upload_path'] = './Uploads/';
            $config1['allowed_types'] = '*';
            $this->upload->initialize($config1);
            if ($voice_file_url != "" && !$this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', basename($voice_file_url));
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                    // Get the file From URL
                    $file = $this->getFileFromURL($voice_file_url);
                    file_put_contents($temp_voice_file, $file);
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && $this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
                $uploaded_file = explode('.', $_FILES['upload_voice_file']['name']);
                $extension = end($uploaded_file);
                if ($extension == 'mp3' || $extension == 'wav') {
                    $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
                    $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
                } else {
                    return 111;
                }
            } elseif ($voice_file_url == "" && !$this->upload->do_upload('upload_voice_file') && $this->input->post('voice_draft_file')) {
                $uploaded_file = explode('|', $this->input->post('voice_draft_file'));
                $voice_id = $uploaded_file[1];
                $manual_duration = 0;
                if (is_numeric($voice_id)) {
                    $voice_file = $uploaded_file[1];
                    $this->db->select('*');
                    $this->db->from('draft_messages');
                    $this->db->where('user_id', $user_id);
                    $this->db->where('draft_message', $voice_file);
                    $this->db->where('draft_message_type', "VOICE");
                    $duration_query = $this->db->get();
                    $duration_result = $duration_query->row();
                    $manual_duration = $duration_result->duration;
                } else {
                    $voice_file = $uploaded_file[0];
                }

                $temp_voice_file = "Voice/" . $uploaded_file[1];
            }


            // Add Scheme On Url
            /*
              $parsed = parse_url($voice_file);
              if (empty($parsed['scheme'])) {
              $voice_file = 'http://' . ltrim($voice_file, '/');
              }
             */

            // Rejected Numbers
            $reject_list_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (substr($mobile, 0, 2) != 91) {
                        $reject_list_array[] = $mobile;
                    }
                }
            }

            // Get Black Lists & Remove Black List Numbers
            $bresult_array = array();
            $black_list_array = array();
            $result_black_list = $this->sms_model->getBlackLists();
            if ($result_black_list) {
                $black_list_array = $result_black_list;
                $bresult_array = array_intersect($result_array, $black_list_array);
                $result_array = array_diff($result_array, $black_list_array);
            }
            // Total Messages To Be Submit
            $total_messages = sizeof($result_array);
            $mp3file = $this->myaudiolib->getFile($temp_voice_file);
            $duration1 = $this->myaudiolib->getDurationEstimate(); //(faster) for CBR only
            //$duration2 = $this->mp3lib->getDuration(); //(slower) for VBR (or CBR)
            /*
              echo "File: $voice_file" . "<br>";
              echo "File: $temp_voice_file" . "<br>";
              echo "Duration: $duration1 Seconds" . "<br>";
              echo "Estimate: $duration2 Seconds" . "<br>";
              echo "Format:" . mp3lib::formatTime($duration2) . "<br>";
              die;
             */

            // Calculate Total Credits
            if ($manual_duration > 0) {
                $total_credits = 0;
                if ($manual_duration % 30 == 0) {
                    $total_credits = $manual_duration / 30;
                } else {
                    $demo_credits = ($manual_duration / 30);
                    $credit_array = explode(".", $demo_credits);
                    $total_credits = $credit_array[0] + 1;
                }
                $actual_balance_required = $total_messages * $total_credits;
            } else {
                $total_credits = 0;
                if ($duration1 % 30 == 0) {
                    $total_credits = intval($duration1 / 30);
                } else {
                    $demo_credits = intval($duration1 / 30);
                    $credit_array = explode(".", $demo_credits);
                    $total_credits = $credit_array[0] + 1;
                }
                $actual_balance_required = $total_messages * $total_credits;
            }


            // Check Result Array
            if ($result_array && sizeof($result_array)) {
                // Promotional Route
                if ($route == "A") {
                    // Check Available Balance And Send Number of Message
                    if ($pr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Promotional Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date('Y-m-d H:i:00');
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;


                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE', 
                            'voice_route_id' => $voice_pr_route,
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date('Y-m-d H:i:00');
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $temp_black_array['voice_process_status'] = 1;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date('Y-m-d H:i:00');
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $temp_reject_array['voice_process_status'] = 1;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vpr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vpr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vpr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date('Y-m-d H:i:00');
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $temp_fake_array['voice_process_status'] = 1;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vpr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date('Y-m-d H:i:00');
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $temp_failed_array['voice_process_status'] = 1;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date('Y-m-d H:i:00');
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_temp_array['voice_process_status'] = $temporary_status;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // Infobip Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $curl_post_data = array(
                                          "destinationAddress" => $result_array[0],
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $update_sms = json_decode($curl_response_data_json);
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $update_sms);
                                          }
                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $curl_post_data = array(
                                          "destinationAddresses" => $result_array,
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $array = json_decode($curl_response_data_json);
                                          $status_array = $array->ttsCallStatuses;
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value->ttsCallRequestId,
                                          'status' => $value->status,
                                          'description' => $value->description
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VPR";
                        $updated_sms_balance = $pr_voice_balance - ( $deduct_balance * $total_credits );
                        // $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        $mostParentID = 0;
                        $spacial_reseller_status = 0;
                        $most_parent_id_reseller_status = 0;
                        $updated_key_balance = 0;


                        $response_bal = $this->sms_model->updateBalance($user_id, $mostParentID, $updated_sms_balance, $balance_type, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance);
                        // $response_bal = $this->sms_model->updateBalance($user_id, $mostParentID, $updated_sms_balance, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance, $balance_type);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }

                // Dynamic Route
                if ($route == "B") {
                    // Check Available Balance And Send Number of Message
                    if ($tr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Dynamic Balance";
                        $log_by = "By Panel";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By Panel";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date('Y-m-d H:i:00');
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;

                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $caller_id,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE',
                            'voice_route_id' => $voice_tr_route,
                        );
                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);
                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date('Y-m-d H:i:00');
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $temp_black_array['voice_process_status'] = 1;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date('Y-m-d H:i:00');
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $temp_reject_array['voice_process_status'] = 1;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }

                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vtr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vtr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);

                                    // Get Fake Delivered Number
                                    if ($vtr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date('Y-m-d H:i:00');
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $temp_fake_array['voice_process_status'] = 1;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }

                                    // Get Fake Failed Number
                                    if ($vtr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date('Y-m-d H:i:00');
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $temp_failed_array['voice_process_status'] = 1;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date('Y-m-d H:i:00');
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_temp_array['voice_process_status'] = 1;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // Infobip Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $curl_post_data = array(
                                          "destinationAddress" => $result_array[0],
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $update_sms = json_decode($curl_response_data_json);
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $update_sms);
                                          }

                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $curl_post_data = array(
                                          "destinationAddresses" => $result_array,
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $array = json_decode($curl_response_data_json);
                                          $status_array = $array->ttsCallStatuses;
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value->ttsCallRequestId,
                                          'status' => $value->status,
                                          'description' => $value->description
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VTR";
                        $updated_sms_balance = $tr_voice_balance - ( $deduct_balance * $total_credits );
                        //   $response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        $mostParentID = 0;
                        $spacial_reseller_status = 0;
                        $most_parent_id_reseller_status = 0;
                        $updated_key_balance = 0;


                        $response_bal = $this->sms_model->updateBalance($user_id, $mostParentID, $updated_sms_balance, $balance_type, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }
            } else {
                return 105;
            }
        }
    }

     function sendHttpVoiceMessage($array_users = array()) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // Get User Details
        $user_id = $array_users['user_id'];
        $mobiles = $array_users['mobiles'];
        $message = $array_users['message'];
        $from = $array_users['sender'];
        $route = $array_users['route'];
        $response = $array_users['response'];
        $duration = $array_users['duration'];
        $campaign_name = $array_users['campaign'];
        $schtime = $array_users['schtime'];
        $client_ip_address = $array_users['client_ip_address'];

        $this->benchmark->mark('Start_Time');
        $result_user = $this->sms_model->getUserSettings($user_id);

        if ($result_user) {
            // Load Library For File Uploading
            //$this->load->library('upload');
            // Load Library For File Audio
            //$this->load->library('Myaudiolib', 'myaudiolib');
            //$this->load->helper('db_helper');
            $pr_voice_balance = $result_user->pr_voice_balance;
            $tr_voice_balance = $result_user->tr_voice_balance;
            $vtr_fake_ratio = $result_user->vtr_fake_ratio;
            $vtr_fail_ratio = $result_user->vtr_fail_ratio;
            $vpr_fake_ratio = $result_user->vpr_fake_ratio;
            $vpr_fail_ratio = $result_user->vpr_fail_ratio;
            // Form Values
            // $route = $this->input->post('route');
            //$campaign_name = $this->input->post('campaign_name');
            // Check Call Id
            /*
              $caller_id = $from;
              if (strlen($caller_id) == 10) {
              $caller_id = "91" . $caller_id;
              } elseif (strlen($caller_id) == 12) {
              $caller_id = $caller_id;
              } else {
              return 112;
              }
             */
            // $duration = $this->input->post('duration');
            // Schedule Voice SMS
            $current_date = date('Y-m-d H:i:s');
            $start_date_time = date('Y-m-d H:i:s');
            $end_date_time = date('Y-m-d H:i:s');

            /*
              // Check Dates
              $current_date = date('Y-m-d H:i:00');
              $start_date_time = $this->input->post('start_date_time');
              $end_date_time = $this->input->post('end_date_time');
              // If Start Time Less Than Now
              if ($current_date >= $start_date_time) {
              return 108;
              }
              // If End Time Less Than Now
              if ($current_date >= $end_date_time) {
              return 109;
              }
              // If End Time Less Than Start Time
              if ($start_date_time >= $end_date_time) {
              return 110;
              }
             */

            // Get Contacts From Form Field/Upload CSV File
            $mobile_array = array();
            $result_array = array();
            $mobile_numbers = $mobiles;
            $result_array = explode(',', $mobile_numbers);

            /*     $temp_file_name = date('dmYhis') . "-" . $user_id;
              $config['file_name'] = $temp_file_name;
              $config['upload_path'] = './Temp_CSV_Files/';
              $config['allowed_types'] = '*';
              $this->upload->initialize($config);
              if ($mobile_numbers != "" && !$this->upload->do_upload()) {
              $output = preg_replace('!\s+!', ' ', $mobile_numbers);
              $output = preg_replace('!\s+!', ',', $output);
              $result_array = explode(',', $output);
              $result_array = array_diff($result_array, array(''));
              } elseif ($mobile_numbers == "" && $this->upload->do_upload('mobiles')) {
              $uploaded_file = explode('.', $_FILES['mobiles']['name']);
              $extension = $uploaded_file[1];
              if ($extension == 'csv' || $extension == 'xls' || $extension == 'xlsx') {
              $myfile = "./Temp_CSV_Files/" . $temp_file_name . ".csv";
              $mobile_array = $this->csv_reader->parse_file($myfile, false);
              $result_array = array_unique($mobile_array);
              } else {
              return 107;
              }
              } elseif ($mobile_numbers != "" && !$this->upload->do_upload()) {
              $result_array = explode(',', $mobile_numbers);
              }

             */
            // Check Mobile Numbers Validation
            $flag = 0;
            $new_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (strlen($mobile) == 10) {
                        $new_array[] = "91" . $mobile;
                    } elseif (strlen($mobile) == 12) {
                        $new_array[] = $mobile;
                    } else {
                        $flag++;
                        break;
                    }
                }
            }
            // Some Mobile Numbers are invalid!
            if ($flag > 0) {
                return "104";
            }
            // Get Contacts From Groups
            $select_contact_array = array();
            if ($this->input->post('check_group')) {
                $selected_groups_array = $this->input->post('check_group');
                if (sizeof($selected_groups_array)) {
                    $result_contacts = $this->sms_model->getGroupsContacts($user_id);
                    if ($result_contacts) {
                        foreach ($result_contacts as $row_contact) {
                            $contact_groups_array = explode(',', $row_contact['contact_group_ids']);
                            $array_intersect = array_intersect($selected_groups_array, $contact_groups_array);
                            if (sizeof($array_intersect)) {
                                if (strlen($row_contact['mobile_number']) == 10) {
                                    $select_contact_array[] = "91" . $row_contact['mobile_number'];
                                } elseif (strlen($row_contact['mobile_number']) == 12) {
                                    $select_contact_array[] = $row_contact['mobile_number'];
                                }
                            }
                        }
                    }
                }
            }
            // Final Result Array For Mobile Numbers
            $result_array = array_unique(array_merge($new_array, $select_contact_array));

            // Get Voice File
            $voice_file = "";
            $temp_voice_file = "";
            $voice_file_url = $message;
            /* $temp_name = "voice" . date('dmYhis') . $user_id;
              $config1['file_name'] = $temp_name;
              $config1['upload_path'] = './Uploads/';
              $config1['allowed_types'] = '*';
              $this->upload->initialize($config1);
              if ($voice_file_url != "" && !$this->upload->do_upload() && !$this->input->post('voice_draft_file')) {
              $uploaded_file = explode('.', basename($voice_file_url));
              $extension = end($uploaded_file);
              if ($extension == 'mp3' || $extension == 'wav') {
              $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
              // Get the file From URL
              $file = $this->getFileFromURL($voice_file_url);
              file_put_contents($temp_voice_file, $file);
              $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
              } else {
              return 111;
              }
              } elseif ($voice_file_url == "" && $this->upload->do_upload('upload_voice_file') && !$this->input->post('voice_draft_file')) {
              $uploaded_file = explode('.', $_FILES['upload_voice_file']['name']);
              $extension = end($uploaded_file);
              if ($extension == 'mp3' || $extension == 'wav') {
              $voice_file = base_url() . "Uploads/" . $temp_name . "." . $extension;
              $temp_voice_file = "Uploads/" . $temp_name . "." . $extension;
              } else {
              return 111;
              }
              } elseif ($voice_file_url == "" && !$this->upload->do_upload() && $this->input->post('voice_draft_file')) {
              $uploaded_file = explode('|', $this->input->post('voice_draft_file'));
              $voice_file = $uploaded_file[0];
              $temp_voice_file = "Voice/" . $uploaded_file[1];
              }
             */

            // Add Scheme On Url
            /*
              $parsed = parse_url($voice_file);
              if (empty($parsed['scheme'])) {
              $voice_file = 'http://' . ltrim($voice_file, '/');
              }
             */

            // Rejected Numbers
            $reject_list_array = array();
            if (sizeof($result_array)) {
                foreach ($result_array as $mobile) {
                    if (substr($mobile, 0, 2) != 91) {
                        $reject_list_array[] = $mobile;
                    }
                }
            }

            // Get Black Lists & Remove Black List Numbers
            $bresult_array = array();
            $black_list_array = array();
            $result_black_list = $this->sms_model->getBlackLists();
            if ($result_black_list) {
                $black_list_array = $result_black_list;
                $bresult_array = array_intersect($result_array, $black_list_array);
                $result_array = array_diff($result_array, $black_list_array);
            }

            // Total Messages To Be Submit
            $total_messages = sizeof($result_array);

            //  $mp3file = $this->myaudiolib->getFile($temp_voice_file);
            // $duration1 = $this->myaudiolib->getDurationEstimate(); //(faster) for CBR only
            //$duration2 = $this->mp3lib->getDuration(); //(slower) for VBR (or CBR)
            /*
              echo "File: $voice_file" . "<br>";
              echo "File: $temp_voice_file" . "<br>";
              echo "Duration: $duration1 Seconds" . "<br>";
              echo "Estimate: $duration2 Seconds" . "<br>";
              echo "Format:" . mp3lib::formatTime($duration2) . "<br>";
              die;
             */

            // Calculate Total Credits
            $total_credits = 0;
            if ($duration % 30 == 0) {
                $total_credits = intval($duration / 30);
            } else {
                $total_credits = intval($duration / 30) + 1;
            }
            $actual_balance_required = $total_messages * $total_credits;

            // Check Result Array
            if ($result_array && sizeof($result_array)) {
                // Promotional Route

                if ($route == "A") {
                    // Check Available Balance And Send Number of Message
                    if ($pr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Promotional Balance";
                        $log_by = "By API";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By API";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;
                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $from,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file_url,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );

                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);

                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file_url;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $temp_black_array['voice_process_status'] = 1;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file_url;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $temp_reject_array['voice_process_status'] = 1;

                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }
                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vpr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vpr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);
                                    // Get Fake Delivered Number
                                    if ($vpr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file_url;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $temp_fake_array['voice_process_status'] = 1;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }
                                    // Get Fake Failed Number
                                    if ($vpr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file_url;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $temp_failed_array['voice_process_status'] = 1;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file_url;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_temp_array['voice_process_status'] = 1;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                    if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        /*
                                          //--------------------------------------------------------//
                                          // Infobip Voice SMS API
                                          // Single Destination Address
                                          if (sizeof($result_array) == 1) {
                                          $curl_post_data = array(
                                          "destinationAddress" => $result_array[0],
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $update_sms = json_decode($curl_response_data_json);
                                          $this->db->where('mobile_no', $result_array[0]);
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update('sent_sms', $update_sms);
                                          }
                                          // Multiple Destination Address
                                          if (sizeof($result_array) > 1) {
                                          $curl_post_data = array(
                                          "destinationAddresses" => $result_array,
                                          "voiceFileUrl" => $voice_file,
                                          "language" => "en",
                                          "sourceAddress" => $caller_id,
                                          "record" => true,
                                          "waitForDtmf" => true,
                                          "dtmfTimeout" => 5,
                                          "retry" => false,
                                          "ringTimeout" => 45
                                          );
                                          //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                          $curl_post_data_json = json_encode($curl_post_data);
                                          //Calling TTS API using php curl
                                          $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                          $username = 'Balajigroup';
                                          $password = 'Mayank@1234';
                                          $curl = curl_init($service_url);
                                          //Authentication:
                                          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                          curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                          curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                          curl_setopt($curl, CURLOPT_POST, true);
                                          curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                          $curl_response_data_json = curl_exec($curl);
                                          curl_close($curl);
                                          // Get Status of Each Number & Update into table
                                          $array = json_decode($curl_response_data_json);
                                          $status_array = $array->ttsCallStatuses;
                                          // Generate Update Array
                                          foreach ($status_array as $key => $value) {
                                          $update_sms[] = array(
                                          'mobile_no' => $result_array[$key],
                                          'ttsCallRequestId' => $value->ttsCallRequestId,
                                          'status' => $value->status,
                                          'description' => $value->description
                                          );
                                          }
                                          $this->db->where('campaign_id', $campaign_id);
                                          $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                          }
                                          //--------------------------------------------------------//
                                         */
                                    }
                                }
                            }
                        }
                        // Update User Balance
                        $balance_type = "VPR";
                        $updated_sms_balance = $pr_voice_balance - ( $deduct_balance * $total_credits );
                        //$response_bal = $this->sms_model->updateBalance($user_id, $updated_sms_balance, $balance_type);
                        $mostParentID = 0;
                        $spacial_reseller_status = 0;
                        $most_parent_id_reseller_status = 0;
                        $updated_key_balance = 0;


                        $response_bal = $this->sms_model->updateBalance($user_id, $mostParentID, $updated_sms_balance, $balance_type, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance);
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return "1";
                    }
                }

                // Dynamic Route
                if ($route == "B") {
                    // Check Available Balance And Send Number of Message
                    if ($tr_voice_balance < $actual_balance_required) {
                        $reason = "Insufficient Dynamic Balance";
                        $log_by = "By API";
                        $response_log = $this->sms_model->insertSMSLog($user_id, $reason, $log_by);
                        return "101";
                    } else {
                        // Insert Campaign
                        $request_by = "By API";
                        $campaign_uid = strtolower(random_string('alnum', 24));
                        $submit_date = date("Y-m-d H:i:s");
                        // Deduct SMS Balance
                        $deduct_balance = 0;
                        $campaign_status = 2;
                        $data_campaign = array(
                            'campaign_uid' => $campaign_uid,
                            'campaign_name' => $campaign_name,
                            'user_id' => $user_id,
                            'total_messages' => $total_messages,
                            'total_credits' => $total_credits,
                            'caller_id' => $from,
                            'request_by' => $request_by,
                            'submit_date' => $submit_date,
                            'message' => $voice_file_url,
                            'message_length' => $duration,
                            'route' => $route,
                            'start_date_time' => $start_date_time,
                            'end_date_time' => $end_date_time,
                            'service_type' => 'VOICE'
                        );

                        $response_cm = $this->sms_model->insertVoiceCampaign($data_campaign);

                        if ($response_cm) {
                            // Get Last Campaign Id
                            $campaign_id = $this->db->insert_id();
                            // Insert Black List Numbers
                            if (sizeof($bresult_array)) {
                                $data_black = array();
                                foreach ($bresult_array as $black_number) {
                                    $temp_black_array = array();
                                    $status = "Blocked";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_black_array['campaign_id'] = $campaign_id;
                                    $temp_black_array['user_id'] = $user_id;
                                    $temp_black_array['msg_id'] = $msg_id;
                                    $temp_black_array['message'] = $voice_file_url;
                                    $temp_black_array['msg_length'] = $duration;
                                    $temp_black_array['mobile_no'] = $black_number;
                                    $temp_black_array['status'] = $status;
                                    $temp_black_array['submit_date'] = $subdate;
                                    $temp_black_array['temporary_status'] = $temporary_status;
                                    $temp_black_array['voice_process_status'] = 1;
                                    $data_black[] = $temp_black_array;
                                    unset($temp_black_array);
                                }
                                if (sizeof($data_black)) {
                                    $res_black = $this->db->insert_batch('sent_sms', $data_black);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_black);
                                }
                            }

                            // Insert Rejected List Numbers
                            if (sizeof($reject_list_array)) {
                                $data_rejected = array();
                                foreach ($reject_list_array as $reject_number) {
                                    $temp_reject_array = array();
                                    $deduct_balance++;
                                    $status = "Rejected";
                                    $msg_id = strtolower(random_string('alnum', 24));
                                    $subdate = date("Y-m-d H:i:s");
                                    $temporary_status = 1;
                                    $temp_reject_array['campaign_id'] = $campaign_id;
                                    $temp_reject_array['user_id'] = $user_id;
                                    $temp_reject_array['msg_id'] = $msg_id;
                                    $temp_reject_array['message'] = $voice_file_url;
                                    $temp_reject_array['msg_length'] = $duration;
                                    $temp_reject_array['mobile_no'] = $reject_number;
                                    $temp_reject_array['status'] = $status;
                                    $temp_reject_array['submit_date'] = $subdate;
                                    $temp_reject_array['temporary_status'] = $temporary_status;
                                    $temp_reject_array['voice_process_status'] = 1;
                                    $data_rejected[] = $temp_reject_array;
                                    unset($temp_reject_array);
                                }
                                if (sizeof($data_rejected)) {
                                    $res_rejected = $this->db->insert_batch('sent_sms', $data_rejected);
                                    // $this->db->insert_batch('voice_msg_dlr', $data_rejected);
                                }
                            }

                            // Insert Remaining Numbers
                            if (sizeof($result_array)) {
                                // Get White Lists
                                $white_list_array = array();
                                $result_white_list = $this->sms_model->getWhiteLists();
                                if ($result_white_list) {
                                    $white_list_array = $result_white_list;
                                }

                                // Apply Ratio (Fake Delivered And Failed)
                                if (sizeof($result_array) > 100) {
                                    $result_array1 = array_diff($result_array, $white_list_array);
                                    $user_fake = (sizeof($result_array1) * $vtr_fake_ratio ) / 100;
                                    $u_fake = ROUND($user_fake);
                                    $user_fail = (sizeof($result_array1) * $vtr_fail_ratio ) / 100;
                                    $u_fail = ROUND($user_fail);

                                    // Get Fake Delivered Number
                                    if ($vtr_fake_ratio) {
                                        if ($u_fake) {
                                            $data_fake = array();
                                            $fake_d_array = $this->sms_model->getRandomArray($result_array1, $u_fake);
                                            $result_array1 = array_diff($result_array1, $fake_d_array);
                                            foreach ($fake_d_array as $fake_number) {
                                                $temp_fake_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 2;
                                                $temp_fake_array['campaign_id'] = $campaign_id;
                                                $temp_fake_array['user_id'] = $user_id;
                                                $temp_fake_array['msg_id'] = $msg_id;
                                                $temp_fake_array['message'] = $voice_file_url;
                                                $temp_fake_array['msg_length'] = $duration;
                                                $temp_fake_array['mobile_no'] = $fake_number;
                                                $temp_fake_array['status'] = $status;
                                                $temp_fake_array['submit_date'] = $subdate;
                                                $temp_fake_array['temporary_status'] = $temporary_status;
                                                $temp_fake_array['voice_process_status'] = 1;
                                                $data_fake[] = $temp_fake_array;
                                                unset($temp_fake_array);
                                            }
                                            if (sizeof($data_fake)) {
                                                $res_fake = $this->db->insert_batch('sent_sms', $data_fake);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_fake);
                                            }
                                        }
                                    }

                                    // Get Fake Failed Number
                                    if ($vtr_fail_ratio) {
                                        if ($u_fail) {
                                            $data_failed = array();
                                            $fake_f_array = $this->sms_model->getRandomArray($result_array1, $u_fail);
                                            $result_array1 = array_diff($result_array1, $fake_f_array);
                                            foreach ($fake_f_array as $failed_number) {
                                                $temp_failed_array = array();
                                                $deduct_balance++;
                                                $msg_id = strtolower(random_string('alnum', 24));
                                                $status = "31";
                                                $subdate = date("Y-m-d H:i:s");
                                                $temporary_status = 3;
                                                $temp_failed_array['campaign_id'] = $campaign_id;
                                                $temp_failed_array['user_id'] = $user_id;
                                                $temp_failed_array['msg_id'] = $msg_id;
                                                $temp_failed_array['message'] = $voice_file_url;
                                                $temp_failed_array['msg_length'] = $duration;
                                                $temp_failed_array['mobile_no'] = $failed_number;
                                                $temp_failed_array['status'] = $status;
                                                $temp_failed_array['submit_date'] = $subdate;
                                                $temp_failed_array['temporary_status'] = $temporary_status;
                                                $temp_failed_array['voice_process_status'] = 1;
                                                $data_failed[] = $temp_failed_array;
                                                unset($temp_failed_array);
                                            }
                                            if (sizeof($data_failed)) {
                                                $res_failed = $this->db->insert_batch('sent_sms', $data_failed);
                                                // $this->db->insert_batch('voice_msg_dlr', $data_failed);
                                            }
                                        }
                                    }
                                    $result = array_intersect($result_array, $white_list_array);
                                    $result_array = array_merge($result_array1, $result);
                                }

                                // Actual Remaining Numbers
                                if (sizeof($result_array)) {
                                    $ssms_data = array();
                                    foreach ($result_array as $ndnd_number) {
                                        $ssms_temp_array = array();
                                        $deduct_balance++;
                                        $msg_id = strtolower(random_string('alnum', 24));
                                        $status = "31";
                                        $subdate = date("Y-m-d H:i:s");
                                        $temporary_status = 1;
                                        // Sent SMS
                                        $ssms_temp_array['campaign_id'] = $campaign_id;
                                        $ssms_temp_array['user_id'] = $user_id;
                                        $ssms_temp_array['msg_id'] = $msg_id;
                                        $ssms_temp_array['message'] = $voice_file_url;
                                        $ssms_temp_array['msg_length'] = $duration;
                                        $ssms_temp_array['mobile_no'] = $ndnd_number;
                                        $ssms_temp_array['status'] = $status;
                                        $ssms_temp_array['submit_date'] = $subdate;
                                        $ssms_temp_array['temporary_status'] = $temporary_status;
                                        $ssms_temp_array['voice_process_status'] = 1;
                                        $ssms_data[] = $ssms_temp_array;
                                        unset($ssms_temp_array);
                                    }

                                      if (sizeof($ssms_data)) {
                                        $res1 = $this->db->insert_batch('sent_sms', $ssms_data);
                                        // $this->db->insert_batch('voice_msg_dlr', $ssms_data);
                                        //--------------------------------------------------------//
                                        // Infobip Voice SMS API
                                        // Single Destination Address


                                        if (sizeof($result_array) == 1) {

                                            $curl_post_data = array(
                                                "destinationAddress" => $result_array[0],
                                                "voiceFileUrl" => $voice_file,
                                                "language" => "en",
                                                "sourceAddress" => $caller_id,
                                                "record" => true,
                                                "waitForDtmf" => true,
                                                "dtmfTimeout" => 5,
                                                "retry" => false,
                                                "ringTimeout" => 45
                                            );

                                            //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                            $curl_post_data_json = json_encode($curl_post_data);
                                            //Calling TTS API using php curl
                                            $service_url = "http://api.infobip.com/tts/3/single";
                                            $username = 'Balajigroup';
                                            $password = 'Mayank@1234';
                                            $curl = curl_init($service_url);
                                            //Authentication:
                                            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                            curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                                "accept: application/json",
                                                "authorization: Basic QmFsYWppZ3JvdXA6QmFsQDIwMTc=",
                                                "content-type: application/json"
                                            ));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($curl, CURLOPT_POST, true);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                            $curl_response_data_json = curl_exec($curl);
                                            curl_close($curl);


                                            // Get Status of Each Number & Update into table
                                            $update_sms = json_decode($curl_response_data_json);
                                            //$this->db->where('mobile_no', $result_array[0]);
                                            //$this->db->where('campaign_id', $campaign_id);
                                            //$this->db->update('sent_sms', $update_sms);
                                        }


                                        // Multiple Destination Address
                                        if (sizeof($result_array) > 1) {

                                            $curl_post_data = array(
                                                "destinationAddresses" => $result_array,
                                                "voiceFileUrl" => $voice_file,
                                                "language" => "en",
                                                "sourceAddress" => $caller_id,
                                                "record" => true,
                                                "waitForDtmf" => true,
                                                "dtmfTimeout" => 5,
                                                "retry" => false,
                                                "ringTimeout" => 45
                                            );

                                            //$curl_post_data_json = json_encode($curl_post_data, JSON_UNESCAPED_UNICODE);
                                            $curl_post_data_json = json_encode($curl_post_data);
                                            //Calling TTS API using php curl
                                            $service_url = 'http://oneapi.infobip.com/tts/1/bulk/requests';
                                            $username = 'Balajigroup';
                                            $password = 'Mayank@1234';
                                            $curl = curl_init($service_url);
                                            //Authentication:
                                            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                                            curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
                                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($curl, CURLOPT_POST, true);
                                            curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data_json);
                                            $curl_response_data_json = curl_exec($curl);
                                            curl_close($curl);

                                              $update_sms = array();
                                            // Get Status of Each Number & Update into table
                                            $array = json_decode($curl_response_data_json);
                                            $status_array = $array->ttsCallStatuses;
                                            // Generate Update Array
                                            foreach ($status_array as $key => $value) {
                                                $update_sms = array(
                                                    'mobile_no' => $result_array[$key],
                                                    'ttsCallRequestId' => $value->ttsCallRequestId,
                                                    'status' => $value->status,
                                                    'description' => $value->description
                                                );
                                            }
                                            
                                         
                                          //  $this->db->where('campaign_id', $campaign_id);
                                           // $this->db->update_batch('sent_sms', $update_sms, 'mobile_no');
                                        }
                                        //--------------------------------------------------------//
                                    }
                                }
                            }
                        }
                        // Update User Balance
                       $balance_type = "VTR";
                        $updated_sms_balance = $tr_voice_balance - ( $deduct_balance * $total_credits );
                        $response_bal = $this->sms_model->updateBalance($user_id, $mostParentID, $updated_sms_balance, $balance_type, $spacial_reseller_status, $most_parent_id_reseller_status, $updated_key_balance, $route) ;
                        // Total Deduction
                        $this->benchmark->mark('End_Time');
                        $total_time = $this->benchmark->elapsed_time('Start_Time', 'End_Time');
                        $total_deduction = $deduct_balance * $total_credits;
                        $data = array(
                            'total_deducted' => $total_deduction,
                            'actual_message' => $deduct_balance,
                            'campaign_status' => $campaign_status,
                            'total_time' => $total_time
                        );
                        $this->db->where('campaign_id', $campaign_id);
                        $this->db->update('campaigns', $data);
                        return $campaign_uid;
                    }
                }
            } else {
                return 105;
            }
        }
    }

    function reSendVoiceCallAdmin($user_id = 0, $action_from = null) {
        ini_set('max_input_time', 2400);
        ini_set('max_execution_time', 2400);
        ini_set('memory_limit', '1073741824');
        // Get User Rules
        $this->benchmark->mark('Start_Time');
        var_dump($result_user);
        die;
        return $result_user = $this->sms_model->getUserSettings($user_id);

        if ($result_user) {

            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('Myaudiolib', 'myaudiolib');

            $session_data = $this->session->userdata('admin_logged_in');
            echo $resend_admin_id = $session_data['admin_id'];
            // Form Values
            // Campaign Id
            echo $campaign_id = $this->input->post('resend_campaign_id');
            // Routing
            echo $route = $this->input->post('resend_route');
            //action
            echo $action_type = $this->input->post('resend_action_type');
            //resend campaning name
            echo $campaign_name = $this->input->post('resend_campaign_name');
            var_dump($result_user);


            $this->load->library('upload');
            // Load Library For File Audio
            $this->load->library('Myaudiolib', 'myaudiolib');
            $this->load->helper('db_helper');


            return $temporary_status = 1;
        }
    }

    public
            function reSendVoiceCallAdminTest($user_id) {
        $result_user = $this->sms_model->getUserSettingsTest($user_id);

        // Campaign Id
        $campaign_id = $this->input->post('resend_campaign_id');
        //action
        $action_type = $this->input->post('resend_action_type');
        // Routing
        $route = $this->input->post('resend_route');

        $campaign_name = "Resend";

        $campaign_uid = strtolower(random_string('alnum', 24));
        $status;
        $temporary_status;
        if ($action_type == 4) {
            $status = 1;
        }
        if ($action_type == 8) {
            $status = 31;
        }
        if ($action_type == 6) {
            $status = 1;
            $temporary_status = 2;
        }


        if ($result_user) {
            $this->db->select("*");
            $this->db->from('sent_sms');
            $this->db->where('campaign_id', $campaign_id);
            if ($action_type == 1) {
                $this->db->where('status >', 0);
            } else {
                $this->db->where('status', $status);
                if ($temporary_status == 2) {
                    $this->db->where('temporary_status', $temporary_status);
                }
            }


            $query = $this->db->get();
            $sizeofsms = $query->num_rows();
            if ($query->num_rows()) {
                $this->db->select("*");
                $this->db->from('campaigns');
                $this->db->where('campaign_id', $campaign_id);
                $camp_query = $this->db->get();
                $camp_result = $camp_query->row();
                $total_messages = $sizeofsms;
                $total_credits = $camp_result->total_credits;
                $caller_id = $camp_result->caller_id;
                $message_length = $camp_result->message_length;
                $submit_date = date('Y-m-d H:i:s');
                $voice_file = $camp_result->message;

                $data_campaign = array(
                    'user_group_id' => 0,
                    'campaign_uid' => $campaign_uid,
                    'campaign_name' => $campaign_name,
                    'user_id' => $user_id,
                    'admin_id' => 1,
                    'total_messages' => $total_messages,
                    'total_credits' => $total_credits,
                    'caller_id' => $caller_id,
                    'submit_date' => $submit_date,
                    'message' => $voice_file,
                    'message_length' => $message_length,
                    'route' => $route,
                    'end_date_time' => $submit_date,
                    'service_type' => 'VOICE',
                    'request_by' => "By Panel"
                );


                $response_cm = $this->db->insert('campaigns', $data_campaign);
                if ($response_cm) {
                    $result_array = $query->result_array();
                    $campaign_id = $this->db->insert_id();
                    foreach ($result_array as $result_insert_array) {
                        $data = array(
                            'campaign_id' => $campaign_id,
                            'user_id' => $user_id,
                            'message' => $voice_file,
                            'msg_length' => $message_length,
                            'mobile_no' => $result_insert_array['mobile_no'],
                            'status' => 31,
                            'submit_date' => $submit_date,
                            'temporary_status' => 1
                        );

                        $sent_responce = $this->db->insert('sent_sms', $data);
                    }


                    if ($sent_responce) {
                        return TRUE;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------------------------//
}

?>