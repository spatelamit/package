<span class="hidden" id="msg_type"><?php echo (isset($msg_type) ? $msg_type : ""); ?></span>
<span class="hidden" id="msg_data"><?php echo (isset($msg_data) ? $msg_data : ""); ?></span>
<?php
if (isset($type) && $type) {
    if ($type == 'user') {
        ?>
        <form role="form" class="tab-forms" id="addUserForm" data-parsley-validate method='post' action="javascript:saveUser();">
            <div class="row">
                <div class="col-md-12">
                    <span id="show_message"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="" name="name" id="name" placeholder="Please Enter Full Name" required=""
                               data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                               data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="" name="username" id="username" 
                               placeholder="Please Enter Username" required="" 
                               onkeyup="checkUsername(this.value, 'user')" data-parsley-minlength="5"  data-parsley-pattern="/^[A-Za-z][A-Za-z0-9]*$/"
                               data-parsley-required-message="Please Enter Username" data-parsley-pattern-message="Username must be start with a character!"
                               data-parsley-minlength-message="Username must be 5 characters long"/>
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" value="" name="contact" id="contact" data-parsley-type="integer"
                               placeholder="Please Enter Contact Number" required=""
                               data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-minlength-message="Please Enter Valid Contact Number"
                               data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-required-message="Please Enter Contact Number"
                               data-parsley-maxlength-message="Please Enter Valid Contact Number"/>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" class="form-control" value="" name="email" id="email" data-parsley-type="email" 
                               placeholder="Please Enter Email Address" required=""
                               data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address" />
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Type</label>
                        <select name="user_type" id="user_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                            <option value="">Select Type</option>
                            <option value="Reseller">Reseller</option>
                            <option value="User">User</option>
                        </select>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" value="" name="company" id="company" 
                               placeholder="Please Enter Company Name" data-parsley-required-message="Please Enter Company Name" required="" />
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Industry</label>
                        <select name="industry" class="form-control" id="industry" data-parsley-required-message="Please Select Industry" required="">
                            <option value="" selected="">Select Industry</option>
                            <option value="Agriculture ">Agriculture </option>
                            <option value="Automobile &amp; Transport">Automobile &amp; Transport</option>
                            <option value="Ecommerce">E-commerce</option>
                            <option value="Education">Education</option>
                            <option value="Financial Institution">Financial Institution</option>
                            <option value="Gym">Gym</option>
                            <option value="Hospitality">Hospitality</option>
                            <option value="IT Company">IT Company</option>
                            <option value="Lifestyle Clubs">Lifestyle Clubs</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Marriage Bureau">Marriage Bureau</option>
                            <option value="Media &amp; Advertisement">Media &amp; Advertisement</option>
                            <option value="Personal Use">Personal Use</option>
                            <option value="Political ">Political </option>
                            <option value="Public Sector">Public Sector</option>
                            <option value="Real estate">Real estate</option>
                            <option value="Reseller">Reseller</option>
                            <option value="Retail &amp; FMCG">Retail &amp; FMCG</option>
                            <option value="Stock and Commodity">Stock and Commodity</option>
                            <option value="Telecom">Telecom</option>
                            <option value="Tips And Alert">Tips And Alert</option>
                            <option value="Travel">Travel</option>
                        </select>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Choose Expiry Date</label>
                        <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                               data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="save" id="save">Save</button>
                </div>
            </div>
        </form>
        <?php
    } elseif ($type == 'admin') {
        ?>
        <form role="form" class="tab-forms" id="addAccMForm" data-parsley-validate method='post' action="javascript:saveAccountManager();">
            <div class="row">
                <div class="col-md-12 padding0">
                    <span id="show_message"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 padding0">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" class="form-control" value="" name="name" id="name" placeholder="Please Enter Full Name" required=""
                               data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                               data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="" name="username" id="username" 
                               placeholder="Please Enter Username" required="" 
                               onkeyup="checkUsername(this.value, 'admin')" data-parsley-minlength="5"  data-parsley-pattern="/^[a-zA-Z0-9._]+$/"
                               data-parsley-required-message="Please Enter Username" data-parsley-pattern-message="Please Enter Valid Username"
                               data-parsley-minlength-message="Username must be 5 characters long"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 padding0">
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" value="" name="contact" id="contact" data-parsley-type="integer"
                               placeholder="Please Enter Contact Number" required=""
                               data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-minlength-message="Please Enter Valid Contact Number"
                               data-parsley-type-message="Please Enter Valid Contact Number" data-parsley-required-message="Please Enter Contact Number"
                               data-parsley-maxlength-message="Please Enter Valid Contact Number"/>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" class="form-control" value="" name="email" id="email" data-parsley-type="email" 
                               placeholder="Please Enter Email Address" required=""
                               data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address" />
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 padding0">
                    <div class="form-group">
                        <label>User Type</label>
                        <select name="user_type" id="user_type" class="form-control" required="" data-parsley-required-message="Please Select User Type">
                            <option value="">Select Type</option>
                            <option value="1">Administrator</option>
                            <option value="2">Sub-Administrator</option>
                        </select>
                    </div>    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" value="" name="company" id="company" 
                               placeholder="Please Enter Company Name" data-parsley-required-message="Please Enter Company Name" required="" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 padding0">
                    <div class="form-group">
                        <label>Choose Expiry Date</label>
                        <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                               data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                    </div>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 padding0">
                    <button type="submit" class="btn btn-primary" name="save" id="save">Save</button>
                </div>
            </div>
        </form>
        <?php
    }
}
?>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today,
            autoclose: true,
            todayHighlight: true
        });
    });
</script>