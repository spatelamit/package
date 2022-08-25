</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>Assets/user/css/jquerysctipttop.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/rotator.js"></script>

<style>
    #my_div {
        display:none;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="portlet">
                <h2 class="content-header-title">Import Contacts via CSV</h2>
                <div class="portlet-content">
                    <form role="form" class="login" id="contactCSVForm" method='post' >
                        <div class="row" id="upload_section">
                            <div class="form-group col-md-6">
                                <label for="contact_csv">Upload CSV</label>
                                <input type="file" id="contact_csv" name="contact_csv" value="" data-parsley-required-message="Please select csv file" 
                                       accept=".csv" required=""  class="upload_files" />     

                            </div>

                            <div id="my_div">
                                <div id="rotator" style="height:300px;width:300px"></div>
                                <h3>Please Wait While Processing...</h3>
                            </div>

                        </div>


                        <div class="row">
                            <div class="form-group col-md-12" id="csv_data">
                                    <a href="<?php echo base_url(); ?>/user/phonebook" role="button" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>

                            </div>
                        </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>


</div>


<script>
    $('#contact_csv').change(function () {
        if (this.value !== '')
        {
            $('#my_div').fadeIn();
            $("#rotator").rotator();
        } else
        {
            $('#my_div').fadeOut();
        }
    });
</script>
