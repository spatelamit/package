<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/ConfigTool.php';

Class Kannel_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
    }

    //==========================================================//
    // Save Configuration Into File
    function saveConfiguration() {
        // Create Object Of ConfigTool Class
        $conf = new ConfigTool();

        // Get Values From Form
        $group_type = $this->input->post('group_type');
        $form_array = $this->input->post('field');
        $file_name = $this->input->post('file_name');

        // File Name
        $myfile = "";
        if ($group_type == 'smsc' || $group_type == 'fake_smsc') {
            if ($file_name) {
                $myfile = $file_name;
            } else {
                $myfile = $form_array['smsc-id'] . '.conf';
            }
        } elseif ($group_type == 'smsbox_route') {
            if ($file_name) {
                $myfile = $file_name;
            } else {
                $files = glob("KannelC/SMSBox_Route/*.*", 0);
                if (isset($files) && sizeof($files)) {
                    $size = sizeof($files) + 1;
                } else {
                    $size = 1;
                }
                $myfile = "route_" . $size . '.conf';
            }
        } elseif ($group_type == 'sendsms_user') {
            if ($file_name) {
                $myfile = $file_name;
            } else {
                $files = glob("KannelC/SendSMS_User/*.*", 0);
                if (isset($files) && sizeof($files)) {
                    $size = sizeof($files) + 1;
                } else {
                    $size = 1;
                }
                $myfile = "user_" . $size . '.conf';
            }
        } else {
            if ($file_name) {
                $myfile = $file_name;
            } else {
                $myfile = $group_type . '.conf';
            }
        }

        // Add Content Into File
        foreach ($form_array as $key => $value) {
            if ($key == 'group') {
                $conf->addKeyValue($key, $value);
            } elseif ($key == 'dlr-storage') {
                $conf->addKeyValue($key, $value);
            } else {
                if (is_numeric($value)) {
                    $conf->addKeyValue($key, $value);
                } else if ($value == "") {
                    $conf->addKeyValue($key, '""');
                } else {
                    $conf->addKeyValue($key, $value);
                }
            }
        }

        // Create File
        if ($group_type == 'smsc' && $this->input->post('smsc_type') && $this->input->post('smsc_type') == 'smsc_trx') {
            $conf->setFileName("KannelC/SMSC_TRX/" . $myfile);
        } elseif ($group_type == 'smsc' && $this->input->post('smsc_type') && $this->input->post('smsc_type') == 'smsc_tx') {
            $conf->setFileName("KannelC/SMSC_TX/" . $myfile);
        } elseif ($group_type == 'smsc' && $this->input->post('smsc_type') && $this->input->post('smsc_type') == 'smsc_rx') {
            $conf->setFileName("KannelC/SMSC_RX/" . $myfile);
        } elseif ($group_type == 'fake_smsc') {
            $conf->setFileName("KannelC/Fake_SMSC/" . $myfile);
        } elseif ($group_type == 'smsbox_route') {
            $conf->setFileName("KannelC/SMSBox_Route/" . $myfile);
        } elseif ($group_type == 'sendsms_user') {
            $conf->setFileName("KannelC/SendSMS_User/" . $myfile);
        } elseif ($group_type == 'sqlbox' || $group_type == 'sql_connection') {
            $conf->setFileName("KannelC/SQLBox/" . $myfile);
        } else {
            $conf->setFileName("KannelC/Config/" . $myfile);
        }

        // Save File 
        $conf->saveToFile('w');

        return true;
    }

    // Delete Configuration Files
    function deleteConfiguration($group_type = null, $file = null) {
        $flag = 0;
        switch ($group_type) {
            case 'main':
                if (file_exists('KannelC/Config/' . $file)) {
                    if (@unlink('KannelC/Config/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'sqlbox':
                if (file_exists('KannelC/SQLBox/' . $file)) {
                    if (@unlink('KannelC/SQLBox/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'sendsms_user':
                if (file_exists('KannelC/SendSMS_User/' . $file)) {
                    if (@unlink('KannelC/SendSMS_User/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'smsbox_route':
                if (file_exists('KannelC/SMSBox_Route/' . $file)) {
                    if (@unlink('KannelC/SMSBox_Route/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'smsc_trx':
                if (file_exists('KannelC/SMSC_TRX/' . $file)) {
                    if (@unlink('KannelC/SMSC_TRX/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'smsc_tx':
                if (file_exists('KannelC/SMSC_TX/' . $file)) {
                    if (@unlink('KannelC/SMSC_TX/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'smsc_rx':
                if (file_exists('KannelC/SMSC_RX/' . $file)) {
                    if (@unlink('KannelC/SMSC_RX/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            case 'fake_smsc':
                if (file_exists('KannelC/Fake_SMSC/' . $file)) {
                    if (@unlink('KannelC/Fake_SMSC/' . $file)) {
                        $flag = 1;
                    }
                }
                break;
            default:
                $flag = 0;
                break;
        }
        return $flag;
    }

    // Generate Configuration Files
    function generateConfiguration($file_type = null) {
        // Create Object Of ConfigTool Class
        $conf = new ConfigTool();
        // Main Config File
        if (isset($file_type) && $file_type == 'main') {
            // Full Path
            $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/Config";
            // Get File Names From Form
            $files = $this->input->post('files');
            // Check Size
            if (isset($files) && sizeof($files)) {
                // Create File
                $myfile = "KannelC/Config/gw.conf";
                // Remove Element
                if (in_array('KannelC/Config/gw.conf', $files)) {
                    unset($files[array_search('KannelC/Config/gw.conf', $files)]);
                }
                if (in_array('KannelC/Config/sqlbox.conf', $files)) {
                    unset($files[array_search('KannelC/Config/sqlbox.conf', $files)]);
                }
                // Add Content Into File
                foreach ($files as $key => $value) {
                    $conf->addKeyValue("\ninclude", '"' . $path . '/' . $value . '"');
                }
                $conf->setFileName($myfile);
                $conf->saveToFile('w');
            }
        }

        // SQLBox Config File
        if (isset($file_type) && $file_type == 'sqlbox') {
            // Full Path
            $path = "/cygdrive/c/wamp/www/BulkSMSAPP/";
            // Get File Names From Directory
            $files = glob("KannelC/SQLBox/*.*", 0);
            // Check Size
            if (isset($files) && sizeof($files)) {
                $files = array_reverse($files);
                // Create File
                $myfile = "KannelC/Config/sqlbox.conf";
                // Add Content Into File
                foreach ($files as $key => $string) {
                    $conf->addKeyValue("\ninclude", '"' . $path . '/' . $string . '"');
                }
                $conf->setFileName($myfile);
                $conf->saveToFile('w');
            }
        }

        // SendSMS User Config File (Form)
        if (isset($file_type) && $file_type == 'sendsms_user') {
            // Full Path
            $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/SendSMS_User";
            // Get File Names From Form
            $files = $this->input->post('files');
            // Check Size
            if (isset($files) && sizeof($files)) {
                // Create File
                $myfile = "KannelC/Config/sendsms_user.conf";
                // Add Content Into File
                foreach ($files as $key => $string) {
                    $conf->addKeyValue("\ninclude", '"' . $path . '/' . $string . '"');
                }
                $conf->setFileName($myfile);
                $conf->saveToFile('w');
            }
        }

        // SMSBox Route Config File (Form)
        if (isset($file_type) && $file_type == 'smsbox_route') {
            // Full Path
            $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/SMSBox_Route";
            // Get File Names From Form
            $files = $this->input->post('files');
            // Check Size
            if (isset($files) && sizeof($files)) {
                // Create File
                $myfile = "KannelC/Config/smsbox_route.conf";
                // Add Content Into File
                foreach ($files as $key => $string) {
                    $conf->addKeyValue("\ninclude", '"' . $path . '/' . $string . '"');
                }
                $conf->setFileName($myfile);
                $conf->saveToFile('w');
            }
        }

        // Real SMSC Config File (Form)
        if (isset($file_type) && $file_type == 'smsc') {
            // Get File Names From Form
            $files = $this->input->post('files');
            $files = array_unique($files);
            // Check Size
            if (isset($files) && sizeof($files)) {
                // Create File
                $myfile = "KannelC/Config/smsc.conf";
                foreach ($files as $key => $value) {
                    // SMSC TRX
                    if (file_exists("KannelC/SMSC_TRX/" . $value) && filesize('KannelC/SMSC_TRX/' . $value)) {
                        $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/SMSC_TRX";
                        $conf->addKeyValue("\ninclude", '"' . $path . '/' . $value . '"');
                    }
                    // SMSC TX
                    if (file_exists("KannelC/SMSC_TX/" . $value) && filesize('KannelC/SMSC_TX/' . $value)) {
                        $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/SMSC_TX";
                        $conf->addKeyValue("\ninclude", '"' . $path . '/' . $value . '"');
                    }
                    // SMSC RX
                    if (file_exists("KannelC/SMSC_RX/" . $value) && filesize('KannelC/SMSC_RX/' . $value)) {
                        $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/SMSC_RX";
                        $conf->addKeyValue("\ninclude", '"' . $path . '/' . $value . '"');
                    }
                    $conf->setFileName($myfile);
                    $conf->saveToFile('w');
                }
            }
        }

        // Fake SMSC Config File (Form)
        if (isset($file_type) && $file_type == 'fake_smsc') {
            // Full Path
            $path = "/cygdrive/c/wamp/www/BulkSMSAPP/KannelC/Fake_SMSC";
            // Get File Names From Form
            $files = $this->input->post('files');
            // Check Size
            if (isset($files) && sizeof($files)) {
                // Create File
                $myfile = "KannelC/Config/fake_smsc.conf";
                // Add Content Into File
                foreach ($files as $key => $string) {
                    $conf->addKeyValue("\ninclude", '"' . $path . '/' . $string . '"');
                }
                $conf->setFileName($myfile);
                $conf->saveToFile('w');
            }
        }

        return true;
    }

    // Get Path
    function getPath() {
        $path = pathinfo($_SERVER['PHP_SELF']);
        $path = substr($_SERVER['DOCUMENT_ROOT'], 0, -1) . $path['dirname'] . "/";
        return $path;
    }

    //Mail alert when kannel is OFF
    public function kannelStatusMail() {
        $this->db->select('sql_id');
        $this->db->from('sqlbox_send_sms');
        $query = $this->db->get();
        $rowcount = $query->num_rows();

        if ($rowcount > 20000) {
            //source email address
            $from_email = "bulk24sms.vijendra@gmail.com";
            $from_name = "Problem In Kannel";
            //destination email address 
            $to_email = "bulk24sms.vijendra@gmail.com";
            //subject of mail
            $subject = "Some problem in Kannel";
            //message body
            $message = "<h2 style='color:#CE0000'>There is some problem in kannel</h2><h3>problems may be..</h3>  1. kannel is OFF . <br> 2. kannel is making queue.<br> 3. MYSQL is making queue.";
            // Load Email Library For Sending E-mails
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            $this->email->from($from_email, $from_name);
            $this->email->to($to_email);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        } else {

            return false;
        }
    }

    
      public function DNDCheck () {
          $route = "D";
          $dnd_check ;
          $A_check_dnd = 1;
          $C_check_dnd = 1;
          $D_check_dnd = 0;
          
          if($route == "A" && $A_check_dnd == 0){
              $dnd_check = 0;
          }else if($route == "A" && $A_check_dnd == 1){
                $dnd_check = 1;
          }else if($route == "C" && $C_check_dnd == 0){
                $dnd_check = 0;
          }else if($route == "C" && $C_check_dnd == 1){
                $dnd_check = 1;
          }else if($route == "D" && $D_check_dnd == 0){
                $dnd_check = 0;
          }else if($route == "D" && $D_check_dnd == 1){
                $dnd_check = 1;
          }
         echo  $route;echo"<br>";
         echo  $dnd_check;
     
  
        
    }
    public function dndConnection() {
      $user_dnd_array = array("9826460361", "9575636818","9977325257") ;  
      
$admin_db= $this->load->database('another', TRUE);
$admin_db->select('*');
$admin_db->where_in('Phone_Numbers',$user_dnd_array);
$query = $admin_db->get('sqlbox_send_sms1');
$result_data = $query->result_array();
     var_dump($result_data);   
$user_dnd_array = array();
    foreach ($result_data as $key => $value) {
        $user_dnd_array[]    = $value['Phone_Numbers'];
    }
    var_dump($user_dnd_array);
  
        
    }
    


    //==========================================================//
}

?>