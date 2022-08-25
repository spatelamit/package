
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 1) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/add_group">Add Group</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 2) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/delete_group">Delete Group</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 3) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/list_groups">List Groups</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 4) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/add_contact">Add Contact</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 5) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/edit_contact">Edit Contact</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 6) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/delete_contact">Delete Contact</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 7) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/list_contacts">List Contacts</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 8) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/error_codes/phonebook">Error Code</a>
            </li>
        </ul>
    </div>
</div>
</div>
<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/add_group">Add Group</a>
    <a href="<?php echo base_url(); ?>api_docs/delete_group">Delete Group</a>
    <a href="<?php echo base_url(); ?>api_docs/list_groups">List Groups</a>
    <a href="<?php echo base_url(); ?>api_docs/add_contact">Add Contact</a>
    <a href="<?php echo base_url(); ?>api_docs/edit_contact">Edit Contact</a>
    <a href="<?php echo base_url(); ?>api_docs/delete_contact">Delete Contact</a>
    <a href="<?php echo base_url(); ?>api_docs/list_contacts">List Contacts</a>
    <a href="<?php echo base_url(); ?>api_docs/error_codes/phonebook">Error Code</a>
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
                            <h2 class="content-header-title">Add Group</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, group_name</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/add_group.php?<mark>authkey</mark>=YourAuthKey&<mark>group_name</mark>=GroupName</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns Group ID if success or the appropriate Error message.
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
                                                <td>group_name <span class="text-danger">*</span>                                                                                                                                
                                                </td>
                                                <td>varchar</td>
                                                <td>Group Name</td>
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
                            <form role="form" class="validate" method='post' id="form31" action="javascript:generateAPI('3', '1', 'add_group');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Group Name <span class="text-danger">*</span>                                                                                                                              
                                        </label>
                                        <input id="group_name" name="group_name" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group Name" />
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
                            <h2 class="content-header-title">Delete Group</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, group_id</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/delete_group.php?<mark>authkey</mark>=YourAuthKey&<mark>group_id</mark>=GroupID</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return appropriate message
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
                                                <td>group_id <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>int</td>
                                                <td>Group ID</td>
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
                            <form role="form" class="validate" method='post' id="form32" action="javascript:generateAPI('3', '2', 'delete_group');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                          
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Group ID <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="dgroup_id" name="dgroup_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group ID" />
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
                            <h2 class="content-header-title">List Group</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/list_groups.php?<mark>authkey</mark>=YourAuthKey</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns Group Detail if success or the appropriate Error message.
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
                            <form role="form" class="validate" method='post' id="form33" action="javascript:generateAPI('3', '3', 'list_groups');">
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
                <div class="col-md-8 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Add Contact</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, name, mobile_no, group_id</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/add_contact.php?<mark>authkey</mark>=YourAuthKey&<mark>name</mark>=ContactName&<mark>mobile_no</mark>=MobileNo&<mark>group_id</mark>=GroupID</p>
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
                                                <td>name</td>
                                                <td>varchar</td>
                                                <td>Contact Name</td>
                                            </tr>
                                            <tr>
                                                <td>mobile_no <span class="text-danger">*</span>                                                                                                                             
                                                </td>
                                                <td>int</td>
                                                <td>Mobile Number</td>
                                            </tr>
                                            <tr>
                                                <td>group_id <span class="text-danger">*</span>                                                                                                                                
                                                </td>
                                                <td>int</td>
                                                <td>Group ID</td>
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
                            <form role="form" class="validate" method='post' id="form34" action="javascript:generateAPI('3', '4', 'add_contact');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                          
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Contact Name <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="acontact_name" name="acontact_name" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Contact Name" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Mobile Number <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="amobile" name="amobile" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Mobile Number" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Group ID <span class="text-danger">*</span>                                                                                                                             
                                        </label>
                                        <input id="agroup_id" name="agroup_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group ID" />
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
                            <h2 class="content-header-title">Edit Contact</h2>
                            <h4>Parameters -</h4>
                            <label>Required :  authkey, contact_id, group, mobile_no, name</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/edit_contact.php?<mark>authkey</mark>=YourAuthKey&<mark>contact_id</mark>=ContactID&<mark>group_id</mark>=GroupID&<mark>mobile_no</mark>=MobileNo&<mark>name</mark>=Name</p>
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
                                                <td>contact_id <span class="text-danger">*</span>                                                                                                                       
                                                </td>
                                                <td>int</td>
                                                <td>Contact ID</td>
                                            </tr>
                                            <tr>
                                                <td>group_id <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>int</td>
                                                <td>Group ID</td>
                                            </tr>
                                            <tr>
                                                <td>mobile_no</td>
                                                <td>int</td>
                                                <td>Mobile Number</td>
                                            </tr>
                                            <tr>
                                                <td>name</td>
                                                <td>varchar</td>
                                                <td>Contact Name</td>
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
                            <form role="form" class="validate" method='post' id="form35" action="javascript:generateAPI('3', '5', 'edit_contact');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                           
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Contact ID <span class="text-danger">*</span>                                                                                                                                
                                        </label>
                                        <input id="econtact_id" name="econtact_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group ID" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Group ID <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="egroup_id" name="egroup_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group ID" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Mobile Number <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="emobile" name="emobile" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Mobile Number" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Contact Name</label>
                                        <input id="ename" name="ename" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Contact Name" />                                   
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
                            <h2 class="content-header-title">Delete Contact</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, contact_id</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/delete_contact.php?<mark>authkey</mark>=YourAuthKey&<mark>contact_id</mark>=ContactID</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Return appropriate message
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
                                                <td>contact_id <span class="text-danger">*</span>                                                                                                                            
                                                </td>
                                                <td>int</td>
                                                <td>Contact ID</td>
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
                            <form role="form" class="validate" method='post' id="form36" action="javascript:generateAPI('3', '6', 'delete_contact');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                            
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Contact ID <span class="text-danger">*</span>                                                                                                                               
                                        </label>
                                        <input id="dcontact_id" name="dcontact_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Contact ID" />
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
                            <h2 class="content-header-title">List Contact</h2>
                            <h4>Parameters -</h4>
                            <label>Required : authkey, group_id</label><br/>
                            <label>Optional : None</label>
                            <h4>Sample API -</h4>
                            <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/list_contacts.php?<mark>authkey</mark>=YourAuthKey&<mark>group_id</mark>=GroupID</p>
                            <h4>Note -</h4> 
                            <p class="text-danger"> 
                                Returns all contact details if success or the appropriate Error message.
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
                                                <td>group_id <span class="text-danger">*</span>                                                                                                                        
                                                </td>
                                                <td>int</td>
                                                <td>Group ID</td>
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
                            <form role="form" class="validate" method='post' id="form37" action="javascript:generateAPI('3', '7', 'list_contacts');">
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Authentication Key <span class="text-danger">*</span>                                                                                                                         
                                        </label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group padding0">
                                        <label>Group ID <span class="text-danger">*</span>                                                                                                                            
                                        </label>
                                        <input id="lgroup_id" name="lgroup_id" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Group ID" />
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