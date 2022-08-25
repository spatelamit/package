<?php

if (isset($_POST['submit'])) {
    $count2 =$_POST["hidden"];
    for ($i = 1; $i <= $count2; $i++) {
        $save = mysql_query("INSERT into multiple_action (name, age, phno, qualification) VALUES ('" . $_POST["name$i"] . "', '" . $_POST["age$i"] . "', '" . $_POST["phone$i"] . "', '" . $_POST["qualification$i"] . "')");
    }
    if ($save) {
        echo 'success';
    } else {
        echo 'fail';
    }
}
if (isset($_POST['save'])) {
    $count3 = $_POST["hidden1"];

    for ($j = 1; $j <= $count3; $j++) {
        $save1 = mysql_query("INSERT into bulk_data (contact_no,name,Groups,email) VALUES ('" . $_POST["contact_no$j"] . "', '" . $_POST["name$j"] . "', '" . $_POST["Groups$j"] . "', '" . $_POST["date$j"] . "')");
    }
    if ($save1) {
        echo 'success1';
    } else {
        echo 'fail1';
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>My Multiple Save </title>
    </head>

    <body>
        <center>
            <form action="http://sms.bulksmsserviceproviders.com/user/phonebook2" method="get"> <!-- Form for generate no of times -->
                <table align="center">
                    <tr>
                        <th colspan="3" align="center">Order Num Of Rows</th>
                    </tr>
                    <tr>
                        <td>No Of</td>
                        <td><input type="text" name="no" /></td>
                        <td><input type="submit" name="order" value="Generate" /></td>
                    </tr>
                </table>
            </form><!-- End generate Form -->


            <!---- Create multiple Save Form,-->
            <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                <table align="center">
                    <tr>
                        <th colspan="3" align="left">Dynamic multiple Save To MySql</th>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>Age</td>
                        <td>Phone</td>
                        <td>Qualification</td>
                    </tr>
                    <?php
                    if (isset($_GET['order'])) {
                        $count = $_GET['no'] - 1; //get the num of times as numeric
                        while ($i <= $count) {// loop by using while(),NOTE the looping dynamic textbox must be under the for loop othet must be outside 																																																																 																													of while()
                            $i++;
                            ?>
                            <tr>
                                <td><input type="text" name="name<?php echo $i; ?>" /></td>
                                <td><input type="text" name="age<?php echo $i; ?>" /></td>
                                <td><input type="text" name="phone<?php echo $i; ?>" /></td>
                                <td><input type="text" name="qualification<?php echo $i; ?>" /></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="4" align="center">
                            <input type="hidden" name="hidden" value="<?php echo $i; ?>" /><!-- Get max count of loop -->
                            <input type="submit" name="submit" value="Save Multiple" /></td>
                    </tr>
                </table>
            </form>

            <?php
            if (isset($_POST['save_csv'])) {
                if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
                    echo "<h1>" . "File " . $_FILES['filename']['name'] . " uploaded successfully." . "</h1>";

                    //      echo  readfile($_FILES['filename']['tmp_name']) ;
                    $handle = fopen($_FILES['filename']['tmp_name'], 'r');
                    $handle1 = fopen($_FILES['filename']['tmp_name'], 'r');
                    ?>
                    <form role="form" class="" id="" method='post' action='http://sms.bulksmsserviceproviders.com/user/phonebook2'  enctype='multipart/form-data' >
                        <table class="table" border="1">

                            <?php //while ($csv_line = fgetcsv($handle, 1024)) { ?>
                            <tbody>

                                <?php
                                $counter = 0;
                                while ($csv_line = fgetcsv($handle, 1024)) {
                                    ?>
                                    <tr>                            

                                        <td><?php echo $csv_line[0]; ?></td>
                                        <td><?php echo $csv_line[1]; ?></td>
                                        <td><?php echo $csv_line[2]; ?></td>
                                        <td><?php echo $csv_line[3]; ?></td>

                                    </tr> 

                                    <?php
                                    $counter++;
                                }
                                ?>   <?php
                              
                             while($j <= $counter) {// loop by using while(),NOTE the looping dynamic textbox must be under the for loop othet must be outside 																																																																 																													of while()
                                  $j++;
                                      while ($line = fgetcsv($handle1, 1024)) {
                                            
                                        ?>
                                <tr>                  

                                            <td><input type="text" name="contact_no<?php echo $j; ?>" value="<?php echo $line[0]; ?><?php echo $j; ?>" /><?php //echo $line[0]; ?></td>
                                            <td><input type="text" name="name<?php echo $j; ?>" value="<?php echo $line[1]; ?><?php echo $j; ?>" /></td>
                                            <td><input type="text" name="Groups<?php echo $j; ?>" value="<?php echo $line[2]; ?><?php echo $j; ?>" /></td>
                                            <td><input type="text" name="email<?php echo $j; ?>" value="<?php echo $line[3]; ?><?php echo $j; ?>" /></td>

                                        </tr> 

                                        <?php
                                }
                                
                            }
                                    ?>

                                    <tr>
                                      
                                        <td colspan="3" align="center">
                                            <input type="hidden" name="hidden1" value="<?php echo $j-1; ?>" /><!-- Get max count of loop -->
                                            <input type="submit" name="save" value="Save Multiple" /></td>
                                    </tr>
                                    <?php ?>
                                </tbody>
                            </table>
 <?php
                    }
                
                    fclose($handle);

                    print "Import done";
                    ?>
                        </form>
                        <?php
//view upload form
                } else {
                    ?>

                    <form role="form" class="" id="" method='post' action='http://sms.bulksmsserviceproviders.com/user/phonebook2'  enctype='multipart/form-data' >

                        <label for="">Upload CSV</label>
                        <input type="file" id="" name="filename" value="" data-parsley-required-message="Please select csv file" 
                               accept=".csv" required=""  class="upload_files" />
                        <input type='submit' name='save_csv' value='Upload' class="btn btn primary" >
                            </div>
                            </div>

                    </form>

                    <?php
                }
                ?>
        </center>
    </body>
</html>
