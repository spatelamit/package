</div>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <div class="col-md-12 padding0">
                    <h2 class="content-header-title">Change Password</h2>
                </div>
                <div class="portlet-content">
                    <?php
                    $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                    echo form_open('user/save_password', $data);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-12 padding0">
                            <label for="current_password">Current Password</label>
                            <input type="password" name="current_password" id="current_password" placeholder="Enter Current Password" 
                                   class="form-control" required="" data-parsley-required-message="Please enter your current password" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="new_password">New Password</label>
                            <input type="password" name="new_password" id="new_password" placeholder="Enter New Password" 
                                   class="form-control" required="" data-parsley-required-message="Please enter your new password" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password" 
                                   class="form-control" required="" data-parsley-equalto="#new_password" data-parsley-equalto-message="New password and confirm password are not same"
                                   data-parsley-required-message="Please enter your confirm password" />
                        </div>
                        <div class="form-group col-md-12 padding0 mt5">
                            <button type="submit" class="btn btn-primary">Save Password</button>
                        </div>
                    </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
</div>