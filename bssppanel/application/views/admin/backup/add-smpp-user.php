<div class="page-content-title txt-center">
    <h3><i class="fa fa-user-plus"></i> Add SMPP User </h3> 
</div>

<div class="col-md-12">
    <div class="col-md-6 padding0">
        <?php
        $data = array('onsubmit' => "return validateSMPPUser()");
        echo form_open('admin/save_smpp_user', $data);
        ?>
            <div class="table-responsive">
                <table class="table bgf9">
                   <thead>
                        <tr>
                            <th colspan="2">
                                Add New SMPP User
                            </th>
                        </tr>
                    </thead> 
                    <tbody>
                        <tr>
                            <td>
                                <label>Name</label>
                            </td>
                            <td>
                                <input type="text" name="smpp_name" id="smpp_name" class="form-control" placeholder="Name"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email Address</label>
                            </td>
                            <td>
                                <input type="text" name="smpp_email" id="smpp_email" class="form-control" placeholder="Email Addess"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Username</label>
                            </td>
                            <td>
                                <input type="text" name="smpp_username" id="smpp_username" class="form-control" placeholder="Username" onkeyup="getSMPPUsername(this.value);"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Contact Number</label>
                            </td>
                            <td>
                                <input type="text" name="smpp_contact" id="smpp_contact" class="form-control" placeholder="Contact Number"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" value="Save SMPP User" class="btn btn-primary" name="save" id="save" />
                                <input type="reset" value="Reset" class="btn btn-default"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>




