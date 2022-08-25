

<?php 

$request = $_REQUEST["data"]; 

$file = fopen("contacts.csv","w");

 fputcsv($file,explode(',',$request));
 
 fclose($file);

 
$jsonData = json_decode($request,true); 

$connection = mysql_connect('localhost', 'bulksms_user', 'BALAJI@sr#ts7828');
if (!$connection) {
    die('database not connect due to:' . mysql_error());
}else{
    mysql_select_db('bulksms_system', $connection);
   echo  "database connected";
}






foreach($jsonData as $key => $value) { 
    
 $requestID = $value['requestId']; 
  $userId = $value['userId']; $senderId = $value['senderId']; 
foreach($value['report'] as $key1 => $value1) { 
    
 $desc = $value1['desc']; 
  $status = $value1['status'];  
$receiver = $value1['number'];
 $date = $value1['date']; 
$query = "INSERT INTO xml_dlr (request_id,user_id,sender_id,date,receiver,status,description) VALUES ('" . $requestID . "','" . $userId . "','" . $senderId . "','" . $date . "','" . $receiver . "','" . $status . "','" . $desc . "')";
 
 mysql_query($query); 
 }
  }
 

?>
