
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 1) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/add_user">Add User</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 2) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/list_users">List Users</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 3) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/update_user_balance">Update User Balance</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 4) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/manage_users">Manage Users</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 5) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/forget_password">Forgot Password</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 6) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/view_own_profile">View Own Profile</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 7) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/view_account_expiry">View Account Expiry</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 8) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/view_user_profile">View User Profile</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 9) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/change_user_password">Change User Password</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 10) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/transaction_history">Transaction History</a>
            </li>
             <li class="<?php echo (isset($page_type) && $page_type && $page_type == 11) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/check_users_balance">Check Users Balance</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 12) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/error_codes/reseller">Error Code</a>
            </li>
        </ul>
    </div>
</div>
</div>

<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/add_user">Add User</a>
    <a href="<?php echo base_url(); ?>api_docs/list_users">List Users</a>
    <a href="<?php echo base_url(); ?>api_docs/update_user_balance">Update User Balance</a>
    <a href="<?php echo base_url(); ?>api_docs/manage_users">Manage Users</a>
    <a href="<?php echo base_url(); ?>api_docs/forget_password">Forgot Password</a>
    <a href="<?php echo base_url(); ?>api_docs/view_own_profile">View Own Profile</a>
    <a href="<?php echo base_url(); ?>api_docs/view_account_expiry">View Account Expiry</a>
    <a href="<?php echo base_url(); ?>api_docs/view_user_profile">View User Profile</a>
    <a href="<?php echo base_url(); ?>api_docs/change_user_password">Change User Password</a>
    <a href="<?php echo base_url(); ?>api_docs/transaction_history">Transaction History</a>
    <a href="<?php echo base_url(); ?>api_docs/check_user_balance">Check User Balance</a>
    <a href="<?php echo base_url(); ?>api_docs/error_codes/reseller">Error Code</a>
</nav>

<div class="container">
    <div class="row">
        <?php
        if (isset($page_type) && $page_type && $page_type == 1) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Add User</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, full_name, username, mobile, email, company, industry, expiry</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/add_user.php?<mark>authkey</mark>=YourAuthKey&<mark>full_name</mark>=FullName&<mark>username</mark>=Username&<mark>mobile</mark>=MobileNo&<mark>email</mark>=Email&<mark>company</mark>=Company&<mark>industry</mark>=Industry&<mark>expiry</mark>=ExpiryDate</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return The User ID On Successful Registration.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>full_name <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>varchar</td>
                                                <td>Full Name of User</td>
                                            </tr>
                                            <tr>
                                                <td>username <span class="text-danger">*</span>                                                                                                                              
                                                </td>
                                                <td>varchar</td>
                                                <td>Username</td>
                                            </tr>
                                            <tr>
                                                <td>mobile<span class="text-danger">*</span>                                                                                                                                
                                                </td>
                                                <td>int</td>
                                                <td>User's Mobile</td>
                                            </tr>
                                            <tr>
                                                <td>email <span class="text-danger">*</span>                                                                                                                                
                                                </td>
                                                <td>varchar</td>
                                                <td>User's Email</td>
                                            </tr>
                                            <tr>
                                                <td>company <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>varchar</td>
                                                <td>User's company name</td>
                                            </tr>
                                            <tr>
                                                <td>industry <span class="text-danger">*</span>                                                                                                                                 
                                                </td>
                                                <td>varchar</td>
                                                <td>User's industry</td>
                                            </tr>
                                            <tr>
                                                <td>expiry <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>date</td>
                                                <td>Expiry date of User's account</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form21" action="javascript:generateAPI('2', '1', 'add_user');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Full Name <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="full_name" name="full_name" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Full Name" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Username <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="username" name="username" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Username" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Mobile Number <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="mobile" name="mobile" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Mobile Number" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Email Address <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="email" name="email" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Email Address" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Company Name <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="company" name="company" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Company Name" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Industry <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="industry" name="industry" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Industry" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Expiry Date <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="expiry" name="expiry" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Expiry Date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 2) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">List Users</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/users.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Note -</h4>
                            <p class="text-danger"> 
                                Returns all client Id and details of clients, if all parameters is correct or the appropriate Error message.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form22" action="javascript:generateAPI('2', '2', 'users');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                          
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 3) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Update User Balance</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey,user_id, sms, account_type, type, price, description</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/transfer_balance.php?<mark>authkey</mark>=YourAuthKey&<mark>user_id</mark>=UserID&<mark>sms</mark>=NoOfSMS&<mark>account_type</mark>=AccountType&<mark>type</mark>=Type&<mark>price</mark>=Price&<mark>description</mark>=Description</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return Appropriate message.
                            </p>
                            <h4>Transaction Type -</h4>
                            <ol>
                                <li>Adding Balance to Client</li>
                                <li>Reducing Balance From Client</li>
                            </ol>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>user_id  <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>int</td>
                                                <td>Client ID</td>
                                            </tr>
                                            <tr>
                                                <td>sms <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>int</td>
                                                <td>SMS Balance</td>
                                            </tr>
                                            <tr>
                                                <td>account_type <span class="text-danger">*</span>                                                                                                                           
                                                </td>
                                                <td>string</td>
                                                <td>Account Type (A, B, C or D)</td>
                                            </tr>
                                            <tr>
                                                <td>type <span class="text-danger">*</span>                                                                                                                              
                                                </td>
                                                <td>string</td>
                                                <td>Transaction Type (Add or Reduce)</td>
                                            </tr>
                                            <tr>
                                                <td>price <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>int</td>
                                                <td>Price</td>
                                            </tr>
                                            <tr>
                                                <td>description <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>varchar</td>
                                                <td>Description</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form23" action="javascript:generateAPI('2', '3', 'transfer_balance');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                         
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>User ID <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="buser_id" name="buser_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter User ID" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>SMS Balance <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="sms" name="sms" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Number of SMS" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Account Type <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="account_type" name="account_type" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Account Type" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Transaction Type <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="type" name="type" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Transaction Type" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Price <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input id="price" name="price" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Price" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Description <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="description" name="description" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Description" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 4) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Manage Users</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey,user_id, status</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/manage_user.php?<mark>authkey</mark>=YourAuthKey&<mark>user_id</mark>=UserID&<mark>status</mark>=Status</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return Appropriate message.
                            </p>
                            <h4>Various Values for status -</h4>
                            <ol>
                                <li>Active</li>
                                <li>Ban</li>
                                <li>Delete</li>
                            </ol>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>user_id <span class="text-danger">*</span>                                                                                                                           
                                                </td>
                                                <td>varchar</td>
                                                <td>Client's ID or Client Username</td>
                                            </tr>
                                            <tr>
                                                <td>status <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>int</td>
                                                <td>status Ex. 1 or 2</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form24" action="javascript:generateAPI('2', '4', 'manage_user');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                      
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client ID <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input id="muser_id" name="muser_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter USer ID" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Status <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input id="status" name="status" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Status" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 5) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Forgot Password</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, username</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/forget_password.php?<mark>authkey</mark>=YourAuthKey&<mark>username</mark>=Username</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return appropriate message.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>username <span class="text-danger">*</span>                                                                                                                         
                                                </td>
                                                <td>varchar</td>
                                                <td>Client Username</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form25" action="javascript:generateAPI('2', '5', 'forget_password');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                          
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client Username <span class="text-danger">*</span>                                                                                                                        
                                        </label>
                                        <input id="fusername" name="fusername" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Username" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 6) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">View Own Profile</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/profile.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns complete details of reseller account, if auth key is correct or the appropriate Error Message.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form26" action="javascript:generateAPI('2', '6', 'profile');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                     
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 7) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">View Account Expiry</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/get_expiry.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns Expiry Date of user, if it set and auth key is correct or if user don't have Expiry Date then returns "Expiry Not Set".
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form27" action="javascript:generateAPI('2', '7', 'get_expiry');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                     
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 8) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">View User Profile</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, user_id</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/user_profile.php?<mark>authkey</mark>=YourAuthKey&<mark>user_id</mark>=UserID</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns complete details of client account, if auth key is correct or the appropriate Error Message.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                           
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>user_id <span class="text-danger">*</span>                                                                                                                           
                                                </td>
                                                <td>varchar</td>
                                                <td>User's ID or Client Username</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form28" action="javascript:generateAPI('2', '8', 'user_profile');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                      
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client ID <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="puser_id" name="puser_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter User ID" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 9) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Change User Password</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, client user_id, user_password</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/change_password.php?<mark>authkey</mark>=YourAuthKey&<mark>user_id</mark>=UserID&<mark>user_password</mark>=UserPassword</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns success, if all parameters is correct or the appropriate Error message.
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>user_id <span class="text-danger">*</span>                                                                                                                               
                                                </td>
                                                <td>varchar</td>
                                                <td>User ID or Client's Username</td>
                                            </tr>
                                            <tr>
                                                <td>user_password <span class="text-danger">*</span>                                                                                                                          
                                                </td>
                                                <td>varchar</td>
                                                <td>Client's New Password</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form29" action="javascript:generateAPI('2', '9', 'change_password');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                            
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client Username <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="pusername" name="pusername" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Username" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client's New Password <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="new_password" name="new_password" type="password" class="form-control" required="" data-parsley-error-message="Please Enter Password" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 10) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Transaction History</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/transactions.php?<mark>authkey</mark>=YourAuthKey&<mark>user_id</mark>=UserID<mark></p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return credit history if auth key is correct or the appropriate Error Message
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            <tr>
                                                <td>Client ID <span class="text-danger"></span>                                                                                                                             
                                                </td>
                                                <td>user_id</td>
                                                <td>user unique id</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form210" action="javascript:generateAPI('2', '10', 'transactions');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client ID  (optional) <span class="text-danger"></span>                                                                                                                               
                                        </label>
                                        <input id="user_id" name="user_id" type="text" class="form-control"  >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        if (isset($page_type) && $page_type && $page_type == 11) {
            ?>
            <div class="row">
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Check Users Balance</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/all_user_balance.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return credit history if auth key is correct or the appropriate Error Message
                            </p>
                            <h4>Description -</h4>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>authkey <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>Alphanumeric</td>
                                                <td>Login Authentication Key (This key is unique for every user)</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form211" action="javascript:generateAPI('2', '11', 'all_user_balance');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                    <div class="col-md-8 form-group padding0">
                                        <label>Client ID  (optional) <span class="text-danger"></span>                                                                                                                               
                                        </label>
                                        <input id="bal_user_id" name="bal_user_id" type="text" class="form-control"  >
                                    </div>
                                   
                                </div> 
                                <div class="row">
                                    <div class="form-group col-md-8 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                    </div>
                                </div>
                                <div class="row mw-parameter" id="show_generate_api"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        
        if (isset($page_type) && $page_type && $page_type == 12) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Error Codes</h2>
                            <div class="col-md-6">
                                <h4>Missing Parameters -</h4>
                                <div class="portlet-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered bgf">
                                            <thead>
                                                <tr>
                                                    <th>Error Code</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>101</td>
                                                    <td>Missing Authkey</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <h4>Invalid Parameters -</h4>
                                <div class="portlet-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered bgf">
                                            <thead>
                                                <tr>
                                                    <th>Error Code</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>201</td>
                                                    <td>Invalid Authkey</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Error Codes -</h4>
                                <div class="portlet-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered bgf">
                                            <thead>
                                                <tr>
                                                    <th>Error Code</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>301</td>
                                                    <td>Authentication Failed</td>
                                                </tr>
                                                <tr>
                                                    <td>302</td>
                                                    <td>Expired User Account</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <h4>System Errors -</h4>
                                <div class="portlet-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered bgf">
                                            <thead>
                                                <tr>
                                                    <th>Error Code</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>001</td>
                                                    <td>Unable To Connect Database</td>
                                                </tr>
                                                <tr>
                                                    <td>002</td>
                                                    <td>Unable To Select Database</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>