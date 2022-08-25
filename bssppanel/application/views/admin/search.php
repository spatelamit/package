    <script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/jquery.min.js"></script>
           <link href="<?php echo base_url(); ?>Assets/admin/css/bootstrap.min.css" rel="stylesheet">
<script>
    $(function () {
    $( '#mytable' ).searchable({
        striped: true,
      //  oddRow: { 'background-color': '#f5f5f5' },
        //evenRow: { 'background-color': '#fff' },
       // searchType: 'fuzzy'
    });
    
  
});
</script>

<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <h3>Table / Fuzzy search example</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <input type="search" id="search" value="" class="form-control" placeholder="Search ">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-hover fade in" id="mytable">
                 <thead>
                    <tr>
                        <th>Username</th>
                        <th>Designation</th>
                        <th>User Type</th>
                        
                  
                    </tr>
                </thead>
                <tbody>
             <?php
                    if (isset($employees) && $employees) {
                    //    $i = 1;
                        foreach ($employees as $key => $employee) {
                            ?>
                            <tr>
                                <td><?php echo $employee['sms_type']; ?></td>
                                <td><?php echo $employee['dlr_url']; ?></td>
                            
                                <td><?php echo $employee['momt']; ?></td>
                              
                              
                            
                            </tr>
                            <?php
                          //  $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="9">No Record Exists!</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <hr>
        </div>
    </div>
   
<script src="//rawgithub.com/stidges/jquery-searchable/master/dist/jquery.searchable-1.0.0.min.js"></script>