<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<style type="text/css">

a {
	color:#898989;
	font-size:14px;
	font-weight:bold;
	text-decoration:none;
}
a:hover {
	color:#CC0033;
}

h1 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #CC0033;
}
h2 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #898989;
}


</style>


<?php
//Upload File
if (isset($_POST['submit'])) {
	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
            
            ?>
            <div class="container">
<h2>Displaying contents:</h2> 
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Firstname</th>
      </tr>
    </thead>
    <tbody>
      <tr class="active">
        <td>1</td>
        <td>Anna</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Debbie</td>
      </tr>
      <tr>
        <td>3</td>
        <td>John</td>
      </tr>
    </tbody>
  </table>
</div>

            <?php
		echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
		echo "<h2>Displaying contents:</h2>";
            
                        echo  readfile($_FILES['filename']['tmp_name']) ;
           
	}  
	$handle = fopen($_FILES['filename']['tmp_name'], "r");

	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            
                $contact_no = $data[0];
                $Groups = $data[2];
                $name = $data[1];
                $tr_date = str_replace('/', '-', $data[3]);
                $date = date("Y-m-d", strtotime($tr_date));
                $email=$data[4];
                
      	//$import="INSERT into bulk_data(contact_no,name,Groups,date,email) values('$contact_no','$name','$Groups','$date','$email')";

		//mysql_query($import) or die(mysql_error());
	}

	fclose($handle);
     
	print "Import done";
        
        ?>

    <?php
	//view upload form
}else {
   
        ?>
    <div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="portlet">
                <h2 class="content-header-title">Import Contacts via CSV</h2>
                <div class="portlet-content">
                    <form role="form" class="" id="" method='post' action='http://sms.bulksmsserviceproviders.com/user/import_contacts1'  enctype='multipart/form-data' >
                        <div class="row" id="">
                            <div class="form-group col-md-6">
                                <label for="">Upload CSV</label>
                                <input type="file" id="" name="filename" value="" data-parsley-required-message="Please select csv file" 
                                       accept=".csv" required=""  class="upload_files" />
                                <input type='submit' name='submit' value='Upload' class="btn btn primary" >
                            </div>
                        </div>
                      
                    </form>
                </div> 
            </div> 
        </div>
    </div>
</div>
    <?php

}

?>



