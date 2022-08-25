<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>DATE OF SMSC</title>


    </head>
    <body bgcolor="#C9C9C9">
        <div align="center">
            <h2 style="color: #900;">Please Insert Date</h2>
             <form method="post" action="<?php echo base_url(); ?>admin/full_smsc_details">
        
                 DATE : <input type="date" name="date" placeholder="Date"/><br><br>
                 <input type="hidden" name="smsc"  value="<?php echo $smsc; ?>"/>
        <input type="submit" name="submit" value="Submit" /><br><br>
    
        </form>
           
</div>
   
    </body>
    
</html>