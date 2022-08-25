
   <form role="form" class="" id="" method='post' action='http://sms.bulksmsserviceproviders.com/user/import'  enctype='multipart/form-data' >
<div class="form-group col-md-12">
    <label for="groups">Choose Group (you can choose multiple group)</label>
</div>
<div class="form-group col-md-8">
    <select name="groups[]" id="groups" multiple required="" class="form-control" data-parsley-required-message="Please choose at least one group">
        <?php
        if (isset($contact_groups) && $contact_groups) {
            foreach ($contact_groups as $group) {
                ?>
                <option value = "<?php echo $group['contact_group_id']; ?>"><?php echo $group['contact_group_name']; ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>
<div class="form-group col-md-4 text-center">
    <input type="hidden" name="temp_file_name" id="temp_file_name" value="<?php echo $temp_file_name; ?>" />
  
    <button type="submit" class="btn btn-primary" name="import_contacts">Import Contacts</button>
</div>
<?php

//Upload File
if (isset($_POST['submit'])) {
    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
        echo "<h1>" . "File " . $_FILES['filename']['name'] . " uploaded successfully." . "</h1>";

         readfile($_FILES['filename']['tmp_name']) ;
      //  $handle = fopen($_FILES['filename']['tmp_name'], 'r');       
        $handle = fopen($_FILES['filename']['tmp_name'], "r");
        $flag = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $contact_no = $data[0];
            $Groups = $data[2];
            $name = $data[1];
            $tr_date = str_replace('/', '-', $data[3]);
            $date = date("Y-m-d", strtotime($tr_date));
            $email = $data[4];
             $import = "INSERT into bulk_data(contact_no,name,Groups,date,email) values('$contact_no','$name','$Groups','$date','$email')";
         mysql_query($import) or die(mysql_error());
            ?>
       
<?php
        }
    }
    fclose($handle);
    print "Import done";  
//view upload form
} else {
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="portlet">
                    <h2 class="content-header-title">Import Contacts via CSV</h2>
                    <div class="portlet-content">
       
                            <div class="row" id="">
                                <div class="form-group col-md-6">
                                    <label for="">Upload CSV</label>
                                    <input type="file" id="" name="filename" value="" data-parsley-required-message="Please select csv file" 
                                           accept=".csv" required=""  class="upload_files" />
                                    <input type='submit' name='submit' value='Upload' class="btn btn primary" >
                                </div>
                            </div>

                    
                    </div> 
                </div> 
            </div>
        </div>
    </div>

    <?php
}
?>

</form>

<script type="text/javascript">
 

    // arguments: reference to select list, callback function (optional)
function getSelectedOptions(sel, fn) {
    var opts = [], opt;
    
    // loop through options in select list
    for (var i=0, len=sel.options.length; i<len; i++) {
        opt = sel.options[i];
        
        // check if selected
        if ( opt.selected ) {
            // add to array of option elements to return from this function
            opts.push(opt);
            
            // invoke optional callback function if provided
            if (fn) {
                fn(opt);
            }
        }
    }
    
    // return array containing references to selected option elements
    return opts;
}
// example callback function (selected options passed one by one)
function callback(opt) {
    // display in textarea for this example
    var display = document.getElementById('display');
    display.innerHTML += opt.value + ', ';

    // can access properties of opt, such as...
    //alert( opt.value )
    //alert( opt.text )
    //alert( opt.form )
}
// anonymous function onchange for select list with id demoSel
document.getElementById('demoSel').onchange = function(e) {
    // get reference to display textarea
    var display = document.getElementById('display');
    display.innerHTML = ''; // reset
    
    // callback fn handles selected options
    getSelectedOptions(this, callback);
    
    // remove ', ' at end of string
    var str = display.innerHTML.slice(0, -2);
    display.innerHTML = str;
};



    </script>

    

  
    
