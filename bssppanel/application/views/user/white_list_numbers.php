
<div class="col-md-3 padding15">
    <form role="form" class="setting_forms" method='post' action="<?php echo base_url(); ?>user/save_user_white">
        <div class="form-group" style="float: left">
            <h3><b>Add White List Number</b></h3> <br>
            <input type="text" name="contact_number" id="contact_number" value="" placeholder="Enter Conact Number" class="form-control"
                   required="" data-parsley-error-message="Please Enter Valid Number" data-parsley-type="integer"
                   data-parsley-minlength="10" data-parsley-maxlength="10">

        </div>
        <input type="hidden" name="number_type" id="number_type" value="white" />
        <button style="margin-top: 78px; margin-left: 10px;" type="submit" class="btn btn-primary btn-sm"  data-loading-text="Processing..." autocomplete="off">Save</button>

    </form>
</div>
<div class="table-responsive padding15">
    <table class="table table-bordered table-striped">
     
            <tr>
                <th>S.No.</th>
                <th>Mobile Number</th>
                <th>Status</th>
              
            </tr>
              <?php
                $i = 1;
                if (isset($numbers) && $numbers) {
                    foreach ($numbers as $white_list) {
                        ?>
            <tr>
                <td><?php echo $i; ?></td>  
                <td><?php echo $white_list['white_list_number'] ?></td>
               <td><?php echo $white_list['white_list_status'] ?></td>
            
            <?php 
            $i++;   
            }
               
                    }else{
                        
                  
            
            ?>
               </tr> 
            <tr>
                <td colspan="5"><?php echo "No Record Found" ;?></td> 
            </tr>
            <?php 
                    }
                    ?>
       
    </table>
</div>