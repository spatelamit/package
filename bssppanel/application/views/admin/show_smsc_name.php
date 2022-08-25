<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>NAME OF SMSCs</title>


    </head>
    <body bgcolor="#C9C9C9">
        <div align="center">
            <h1 style="color: #900;">NAME OF SMSCs</h1>
          
            <?php
            foreach ($smsc as $smsc1) {
                ?>
             <div align="center" style="background-color: #3737FF; width: 200px; height: 22px; ">
                 <a style="text-decoration: none ; color: white; font-family: inherit;" href="<?php echo base_url() . "admin/get_date/" . $smsc1['smsc_id']; ?>" style="font-size: 20px;"><?php echo $smsc1['user_group_name']; ?></a>
                 </div><br><br>


                <?php
            }
            ?>
</div>
   
    </body>
    
</html>