
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 1) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/check_balance">Check Balance</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 2) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/change_password">Change Password</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 3) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/validation">Validation</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 4) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/error_codes/basic">Error Code</a>
            </li>
        </ul>
    </div>
</div>
</div>

<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/check_balance" title="Item 1">Check Balance</a>
    <a href="<?php echo base_url(); ?>api_docs/change_password" title="Item 2">Change Password</a>
    <a href="<?php echo base_url(); ?>api_docs/validation" title="Item 3">Validation</a>
    <a href="<?php echo base_url(); ?>api_docs/error_codes/basic" title="Item 4">Error Code</a>
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
                            <h2 class="content-header-title">Check Balance</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, route</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/balance.php?<mark>authkey</mark>=YourAuthKey&<mark>route</mark>=A</p>
                            <h4>Error Responses -</h4>
                            <p class="text-danger">
                                Returns Text sms balance detail if all parameters is correct or appropriate Error Message
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
                                                <td>route <span class="text-danger">*</span>                                                                                                                                 
                                                </td>
                                                <td>string</td>
                                                <td>Route name. 
                                                    <br/>
                                                    For Promotional route=<strong>A</strong> | <strong>1</strong> | <strong>default</strong><br/>
                                                    For Transactional route=<strong>B</strong> | <strong>4</strong> | <strong>template</strong><br/>
                                                    For Transactional route=<strong>C</strong> | <strong>2</strong> | <strong>Stock</strong><br/>
                                                    For Transactional route=<strong>D</strong> | <strong>3</strong> | <strong>Premium</strong><br/>
                                                    
                                                 
                                                </td>
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
                            <form role="form" class="validate" method='post' id="form11" action="javascript:generateAPI('1', '1', 'balance');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Route <span class="text-danger">*</span>                                                                                                                                 
                                        </label>
                                        <input id="route" name="route" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Route" />
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
                            <h2 class="content-header-title">Change Password</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, cpassword, npassword, ncpassword</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/password.php?<mark>authkey</mark>=YourAuthKey&<mark>cpassword
                                </mark>=password&<mark>npassword</mark>=newpassword&<mark>ncpassword</mark>=confirmpassword</p>
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
                                                <td>cpassword <span class="text-danger">*</span>                                                                                                                          
                                                </td>
                                                <td>varchar</td>
                                                <td>User's Current Password</td>
                                            </tr>
                                            <tr>
                                                <td>npassword <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>varchar</td>
                                                <td>New Password</td>
                                            </tr>
                                            <tr>
                                                <td>ncpassword <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>varchar</td>
                                                <td>Confirm password</td>
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
                            <form role="form" class="validate" method='post' id="form12" action="javascript:generateAPI('1', '2', 'password');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Password <span class="text-danger">*</span>                                                                                                                          
                                        </label>
                                        <input id="password" name="password" id="password" type="password" class="form-control" required="" data-parsley-error-message="Please Enter Current Password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>New Password <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input name="npassword" id="npassword" type="password" class="form-control" required="" data-parsley-error-message="Please Enter New Password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Confirm Password <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input name="cnpassword" id="cnpassword" type="password" class="form-control" required="" data-parsley-error-message="Please Enter Confirm Password">
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
                            <h2 class="content-header-title">Validation</h2>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/validate.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Error Responses -</h4>
                            <p class="text-danger">
                                Returns a response "Valid", if all parameters is correct or appropriate Error Message
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
                            <form role="form" class="validate" method='post' id="form13" action="javascript:generateAPI('1', '3', 'validate');">
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
        if (isset($page_type) && $page_type && $page_type == 4) {
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
                                                <tr>
                                                    <td>102</td>
                                                    <td>Missing Route</td>
                                                </tr>
                                                <tr>
                                                    <td>103</td>
                                                    <td>Missing Current Password</td>
                                                </tr>
                                                <tr>
                                                    <td>104</td>
                                                    <td>Missing New Password</td>
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
                                                <tr>
                                                    <td>202</td>
                                                    <td>Invalid Route</td>
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
                                                <tr>
                                                    <td>303</td>
                                                    <td>Banned User Account</td>
                                                </tr>
                                                <tr>
                                                    <td>304</td>
                                                    <td>Wrong Current Password</td>
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