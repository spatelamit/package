<?php
$user_data = $this->session->userdata('logged_in');
$school_id = $user_data['school_id'];

 $size  = sizeof($all_employee);

?>

<?php
$mysql_hostname = "localhost";  //your mysql host name
$mysql_user = "educoord_system";   //your mysql user name
$mysql_password = "Srtsbssp2018";   //your mysql password
$mysql_database = "educoord_system"; //your mysql database

$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $bd) or die("Error on database connection");
?>

<div class="main">

    <div class="container-fluid">

        <div class="wrap">

            <div class="button-pos" style="display: inline-block; width: 100%; ">





                <div class="row">
                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <ul class="employee-ul">
                            <li><a href="<?php echo base_url(); ?>index.php/Dashboard/index" role="button" style="background-color: #00173d;color: #fff;padding: 13px;border-radius: 3px;"><span class="glyphicon glyphicon-backward"></span> Go To Dashboard</a></li>
                            
                            
                        </ul>
                    </div>
                </div>

            </div>

            <div id="panel123"  style="padding: 30px;" > 

                <div id="carouselexample" class="carousel slide"  data-ride="carousel" style="border: 1px solid #f1efef;box-shadow: 3px 3px 10px grey;padding-bottom: 95px;" >

                    <ol class="carousel-indicators">

                        <li data-target="#carouselexample" data-slide-to="0" class="active"></li>

                        <li data-target="#carouselexample" data-slide-to="1"></li>



                    </ol>

                    <div class="carousel-inner" style="padding: 30px;">

                        <div class="item active" >

                            <div class="text-center"><h1 style="margin-bottom: 40px;">Assign Employee Leave</h1></div>
                            <form method="post" action="<?php echo base_url(); ?>index.php/Calender_Controller/save_employee_leave">
                                <input type="hidden" name="size" value="<?php echo $size; ?>">
                                <div class="col-md-12">
                                <div class="table-responsive">


                                    <table id="mytable" class="table table-bordred table-striped">

                                        <thead>
                                            <tr>
                                                <th style="text-align: center;">Sr.No.</th>
                                                <th style="text-align: center;">Name</th>
                                                <th style="text-align: center;">Username</th>
                                                <th style="text-align: center;">Designation</th>
                                                <th style="text-align: center;">Total Leave</th>
                                                <th style="text-align: center;">Monthly</th>
                                                <th style="text-align: center;">Yearly</th>
                                           </tr>
                                            <?php
                                            
                                            if (isset($all_employee)) {
                                                  $i = 0;
                                                  $y = 1;
                                                ?>
                                              <?php  foreach ($all_employee as $row) {
                                                  ?>
                                                <tr>
                                              <input type="hidden" name="employee_id<?php echo $i; ?>" value="<?php echo $employee_id = $row['employee_id']; ?>">
                                              <input type="hidden" name="school_id<?php echo $i; ?>" value="<?php echo $school_id = $row['school_id']; ?>">
                                              <input type="hidden" name="employee_name<?php echo $i; ?>" value="<?php echo $row['employee_name']; ?>">
                                         
                                              
                                                <th style="text-align: center;"><?php echo $y; ?></th>
                                                <th style="text-align: center;"><?php echo $row['employee_name']; ?></th>
                                                <th style="text-align: center;"><?php echo $row['employee_username']; ?></th>
                                                <th style="text-align: center;"><?php echo $row['employee_type']; ?></th>
                                                
                                                        <?php
                                                $query = "SELECT * FROM `employee_assign_leave` WHERE `employee_id` = '" . $employee_id . "' and `school_id` = '" . $school_id . "'";
                                                $result = mysql_query($query);
                                                $row1 = mysql_fetch_array($result);
                                                          $row1['total_leave'];
                                                                ?>
                                                
                                                
                                                <th style="text-align: center;"><input type="text" name="total_leave<?php echo $i; ?>" value="<?php echo $row1['total_leave']; ?>"></th>
                                                <th style="text-align: center;"><input type="radio" name="leave_type<?php echo $i; ?>" value="1" <?php if($row1['leave_type']==1){ ?> checked<?php }else{ ?> <?php } ?>></th>
                                                <th style="text-align: center;"><input type="radio" name="leave_type<?php echo $i; ?>" value="2" <?php if($row1['leave_type']==2){ ?> checked<?php }else{ ?> <?php } ?>></th>
                                                  </tr>
                                          <?php   
                                          $i++;
                                          $y++;
                                       
                                              }
                                       
                                                }else{
                                            ?>
                                        </thead>
                                        <tbody>
                                        <tr>
                                        <th style="text-align: center;" colspan="10">No Record Found</th>
                                        </tr>
                                        </tbody>
                                                <?php } ?>
                                    </table>
                                </div>
                            </div>
                                <button type="submit" name="submit" style="float: right; ">Assign</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
