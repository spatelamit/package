
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <h2 class="content-header-title tbl">Sender Ids SMS Tracker</h2>
                <div class="portlet-content">
                    <div class="table-responsive" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>Serial No</th>
                                    <th>Sender Ids</th>
                                    <th>No of SMS</th>
                                </tr>
                            </thead>
                            <tbody>
                                 
                                <?php
                                 if (isset($senser_ids_tracker) && $senser_ids_tracker) {
                                    $i=1;
                                    foreach($senser_ids_tracker as $sernder_responce)
                                    {?>
                                <tr  <?php if ($i % 2 == 0) { ?>
                                                style="background-color: gainsboro;"  
                                            <?php } else { ?>
                                                style="background-color: white;"  

                                            <?php } ?>>
                                     <th><?php  echo $i;?></th>
                                     <th><?php  echo $sernder_responce['sender_id'];?></th>
                                     <th><?php  echo $sernder_responce['no_of_sms'];?></th>
                                 </tr>
                                   <?php $i++; 
                                   
                                    }
                                   }else {
                                    ?>
                                        <tr>
                                        <th colspan="3">No Record Found!</th>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>