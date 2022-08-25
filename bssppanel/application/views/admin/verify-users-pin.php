<?php
$message = "";
$message_type = "";
if ($this->session->flashdata('message_type') && $this->session->flashdata('message')) {
    $message = $this->session->flashdata('message');
    $message_type = $this->session->flashdata('message_type');
}
?>

    <div class="tab-pane fade in active">

<div class="container">
            <div role="tabpanel">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active fade in">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 horizontal-tabs tab-color padding15" style="padding: 40px;">
                                <div class="panel panel-primary">
                                    
                                    <div class="panel-body">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
                                            <form action="<?php echo base_url(); ?>admin/save_users_pin" method="post" class="control_form" id="validate-basic">
                                                <div class="form-group <?php echo (isset($message_type) && $message_type) ? $message_type : "hidden"; ?>">
                                                    <span class="<?php echo (isset($message_type) && $message_type) ? $message_type : ""; ?>">
                                                        <?php echo (isset($message) && $message) ? $message : ""; ?>
                                                    </span>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Verification Code</label>
                                                    <input type="text" name="verification_pin" id="verification_Pin" class="form-control" placeholder="Please Enter Verification Code" 
                                                           data-parsley-required-message="Please Enter Verification Pin" required="" >
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="verify" class="btn-group btn-group-justified btn btn-primary">Verify Code</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>