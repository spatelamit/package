<div role="tabpanel" class="mt5">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#um_fund" aria-controls="home" role="tab" data-toggle="tab">Add New User</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="um_fund">
            <div class="row ptb15">
                <div class="col-md-6">
                    <h2 class="content-header-title fhead">Add New User</h2>
                    <form role="form" class="tab-forms" id="addNewForm" data-parsley-validate method='post' 
                          action="javascript:saveUser();">
                        <div class="form-group col-md-6">
                            <label for="name">Name</label>
                            <input name="name" id="name" placeholder="Enter Name" value="" class="form-control" required="" 
                                   data-parsley-pattern-message="Please Enter First & Last Name" data-parsley-required-message="Please Enter Full Name" 
                                   data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" type="text">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="name">Username</label>
                            <input name="username" id="username" placeholder="Enter Username" value="" class="form-control" required="" 
                                   data-parsley-pattern-message="Username must be start with a character" data-parsley-required-message="Please Enter Username" 
                                   data-parsley-pattern="/^[A-Za-z][A-Za-z0-9]*$/" type="text" onkeyup="getNewUsername(this.value);"
                                   data-parsley-minlength="5" data-parsley-maxlength="32"
                                   data-parsley-minlength-message="Username Must Be 6 Characters Long" data-parsley-maxlength-message="Please Enter Valid Username">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact">Contact Number</label>
                            <input name="contact" id="contct" placeholder="Enter Contact Number" value="" 
                                   class="form-control" required="" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-type="integer"
                                   data-parsley-required-message="Please Enter Contact Number" type="text"
                                   data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number"
                                   data-parsley-type-message="Please Enter Valid Contact Number">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email_address">Email Address</label>
                            <input name="email_address" id="email_address" placeholder="Enter Email Address" value="" 
                                   class="form-control" required="" data-parsley-type="email" type="text"
                                   data-parsley-required-message="Please Enter Email Address" data-parsley-type-message="Please Enter Valid Email Address">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact">Company Name</label>
                            <input name="company_name" id="company_name" placeholder="Enter Company Name" value="" 
                                   class="form-control" required="" data-parsley-required-message="Please Enter Company Name" type="text">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="industry">Industry</label>
                            <select id="industry" name="industry" required="" data-parsley-required-message="Please Select Industry Name" class="form-control">
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
                        <div class="form-group col-md-6">
                            <label for="expiry_date">Choose Expiry Date</label>
                            <input name="expiry_date" id="expiry_date" placeholder="Enter Expiry Date" class="form-control" required="" 
                                   data-parsley-error-message="Please Enter Expiry Date" type="text" value="">
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary" id="save_btn" data-loading-text="Processing..." autocomplete="off">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        $('#expiry_date').datepicker({
            format: "dd-mm-yyyy",
            startDate: today
        });
    });
    $('.tab-forms').parsley();
</script>