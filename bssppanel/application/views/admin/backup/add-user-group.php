<div class="page-content-title txt-center">
    <h3><i class="fa fa-user-plus"></i> <?php echo (isset($user_group_id)) ? 'Update' : 'Add'; ?> User Group </h3> 
</div>

<div class="col-md-12">
    <div class="col-md-6 padding15">
        <?php
        $data = array('role' => 'form', 'class' => 'tab-forms', 'id' => 'validate-basic', 'data-parsley-validate' => true);
        if (isset($user_group_id))
            echo form_open('admin/save_up_user_group', $data);
        else
            echo form_open('admin/save_user_group', $data);
        ?>
        <div class="table-responsive">
            <table class="table bgf9">
                <thead>
                    <tr>
                        <th colspan="2">
                            <?php echo (isset($user_group_id)) ? 'Update' : 'Add'; ?> New Group
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <label>User Group Name</label>
                        </td>
                        <td>
                            <input type="text" name="user_group_name" id="user_group_name" placeholder="Enter User Group Name" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Group Name" value="<?php echo (isset($user_group)) ? $user_group->user_group_name : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Connected SMSC</label>
                        </td>
                        <td>
                            <input type="text" name="smsc_name" id="smsc_name" placeholder="Enter SMSC Name" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Connected SMSC" value="<?php echo (isset($user_group)) ? $user_group->smsc_id : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>User Group Username</label>
                        </td>
                        <td>
                            <input type="text" name="user_group_username" id="user_group_username" placeholder="Enter User Group Username" class="form-control" 
                                   data-parsley-error-message="Please Enter User Group Username" required="" value="<?php echo (isset($user_group)) ? $user_group->user_group_username : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>User Group Password</label>
                        </td>
                        <td>
                            <input type="password" name="user_group_password" id="user_group_password" placeholder="Enter User Group Password" class="form-control" 
                                   data-parsley-error-message="Please Enter User Group Password" required="" value="<?php echo (isset($user_group)) ? $user_group->user_group_password : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Purpose</label>
                        </td>
                        <td>
                            <select name="purpose" id="purpose" class="form-control" required="" data-parsley-error-message="Please Select Purpose">
                                <option value="" <?php echo (isset($user_group) && $user_group->purpose == '') ? 'selected' : ''; ?>>Select Route</option>
                                <option value="Promotional" <?php echo (isset($user_group) && $user_group->purpose == 'Promotional') ? 'selected' : ''; ?>>Promotional SMS</option>
                                <option value="Transactional" <?php echo (isset($user_group) && $user_group->purpose == 'Transactional') ? 'selected' : ''; ?>>Transactional SMS</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Sender Id Type</label>
                        </td>
                        <td>
                            <select name="sender_id_type" id="sender_id_type" class="form-control" required="" data-parsley-error-message="Please Select Sender Id Type">
                                <option value="" <?php echo (isset($user_group) && $user_group->sender_id_type == '') ? 'selected' : ''; ?>>Select Type</option>
                                <option value="Numeric" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Numeric') ? 'selected' : ''; ?>>Numeric Type</option>
                                <option value="Alphabetic" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Alphabetic') ? 'selected' : ''; ?>>Alphabetic Type</option>
                                <option value="Alphanumeric" <?php echo (isset($user_group) && $user_group->sender_id_type == 'Alphanumeric') ? 'selected' : ''; ?>>Alphanumeric Type</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Sender Id Length</label>
                        </td>
                        <td>
                            <input type="text" name="sender_length" id="sender_length" placeholder="Enter Enter Sender Id Length" class="form-control" required=""
                                   data-parsley-error-message="Please Enter Sender Id Length" value="<?php echo (isset($user_group)) ? $user_group->sender_id_length : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="user_group_id" value="<?php echo (isset($user_group)) ? $user_group->user_group_id : ''; ?>" />
                            <button type="submit" class="btn btn-primary" name="save" id="save"><?php echo (isset($user_group_id)) ? 'Update' : 'Save'; ?></button> 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </form>
    </div>
</div>