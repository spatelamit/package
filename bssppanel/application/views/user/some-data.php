

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <title>Bulksmsserviceproviders</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
        <link href="<?php echo base_url(); ?>Assets/user/css/bootstrap.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>Assets/user/css/fonts.css" rel="stylesheet" />

        <style type="text/css">

            .show {
                color: blue;
                margin: 5px 0;
                padding: 3px 5px;
                cursor: pointer;
                font: 15px/19px Arial,Helvetica,sans-serif;
            }
            .show a {
                text-decoration: none;
            }
            .show:hover {
                text-decoration: underline;
            }


            ul.setPaginate li.setPage{
                padding:15px 10px;
                font-size:14px;
            }

            ul.setPaginate{
                margin:0px;
                padding:0px;
                height:100%;
                overflow:hidden;
                font:12px 'Tahoma';
                list-style-type:none;	
            }  

            ul.setPaginate li.dot{padding: 3px 0;}

            ul.setPaginate li{
                float:left;
                margin:0px;
                padding:0px;
                margin-left:5px;
            }



            ul.setPaginate li a
            {
                background: none repeat scroll 0 0 #ffffff;
                border: 1px solid #cccccc;
                color: #999999;
                display: inline-block;
                font: 15px/25px Arial,Helvetica,sans-serif;
                margin: 5px 3px 0 0;
                padding: 0 5px;
                text-align: center;
                text-decoration: none;
            }	

            ul.setPaginate li a:hover,
            ul.setPaginate li a.current_page
            {
                background: none repeat scroll 0 0 #0d92e1;
                border: 1px solid #000000;
                color: #ffffff;
                text-decoration: none;
            }

            ul.setPaginate li a{
                color:black;
                display:block;
                text-decoration:none;
                padding:5px 8px;
                text-decoration: none;
            }




        </style>    
    </head>

    <body>

        <h3 align="center"  style="color: #900;">Failed & Rejected Data &nbsp; (<?php echo $start_date = date('d-m-Y', strtotime("-1 days")); ?>)</h3>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
      <div class="table-responsive">
                <ul class='setPaginate'><li class='setPage'>Page 1 of 353</li><li><a class='current_page'>1</a></li><li><a href='?page=2'>2</a></li><li><a href='?page=3'>3</a></li><li><a href='?page=4'>4</a></li><li><a href='?page=5'>5</a></li><li><a href='?page=6'>6</a></li><li><a href='?page=7'>7</a></li><li class='dot'>...</li><li><a href='?page=352'>352</a></li><li><a href='?page=353'>353</a></li><li><a href='?page=2'>Next</a></li><li><a href='?page=353'>Last</a></li></ul>

                <div class="navi" id="demo">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>SR.NO</th>
                                <th>USERNAME</th>
                                <th>MESSAGE</th>
                                <th>ACTUAL ROUTE</th>
                                <th>TYPE</th>
                                <th>DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($data) && $data) {
                                $i = 1;
                                foreach ($data as $data_records) {
                                    ?>

                                    <tr>
                                        <th scope="row"><?php echo $i; ?></th>
                                        <td><?php echo $data_records['username']; ?></td>
                                        <td class="msg"><?php echo $data_records['message']; ?></td>
                                        <td><?php echo $data_records['actual_route']; ?></td>
                                        <td><?php echo $data_records['status']; ?></td>
                                        <td><?php echo $data_records['submit_date']; ?></td>
                                    </tr>




                                    <?php
                                    $i++;
                                }
                            } else {
                                ?>

                                <tr>

                                    <td colspan="7" align="center" style="padding-top:20px;">No Records Found!</td>
                                </tr>   
                            <?php } ?>         
                        </tbody>
                        <tfoot>

                        </tfoot>

                    </table>
                </div>
      </div>
                </div>
            </div>
        </div>



        <div style="float:left;  margin-left: 30px; margin-top: 12px; ">
            <form method="GET" action="demo_prtodnd.php" >
                <input type="text" name="page" style="height:20px; width:45px">
                    <input type="submit" value="GO TO PAGE" style="margin-bottom: 10px; margin-left: -5px; margin-right: 0px; color:white; background-color:#2780E3;; height:30px; width:120px">
                        </form></div>
                        </body>
                        </html>


