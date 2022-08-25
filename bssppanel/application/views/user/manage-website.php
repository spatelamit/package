</div>
<div class="container">
    <div class="row" id="website_subtab">
        <div class="col-md-3 col-sm-4 borderR">
            <div class="row ptb15">
                <div class="col-sm-6 padding0">
                    <select name="website_domain" id="website_domain" class="form-control" onchange="selectWebsite(this.value);">
                        <option value="0">Add New Website</option>
                        <?php
                        if (isset($websites) && $websites) {
                            foreach ($websites as $row) {
                                ?>
                                <option value="<?php echo $row['website_id']; ?>"><?php echo $row['website_domain']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-1 text-center"><h4>OR</h4></div>
                <div class="col-sm-5">
                    <input type="button" name="add_new_website" id="add_new_website" value="Add New Website" class="btn btn-success" 
                           onclick="selectWebsite('0')" />
                </div>
            </div>
            <div class="portlet">
                <div class="col-md-12 padding0">
                    <h2 class="content-header-title">Manage Website</h2>
                </div>
                <div class="portlet-content">
                    <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveWebsite();">
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="company_name">Company Name</label>
                                <input type="text" id="company_name" name="company_name" required="" placeholder="Enter Company Name"
                                       data-parsley-error-message="Please Enter Company Name" class="form-control" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="domain_name">Domain Name</label>
                                <input type="text" id="domain_name" name="domain_name" required="" placeholder="Enter Domain Name"
                                       data-parsley-error-message="Please Enter Valid Domain Name" class="form-control" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <button type="button" class="btn btn-default btn-xs" onclick="checkDNSSetting();">Check DNS</button>
                            </div>
                            <div class="form-group col-md-12 padding0" id="check_dns_msg"></div>
                            <div class="form-group col-md-12 padding0">
                                <h5>
                                    Correct IP to be pointed is : 166.62.55.94
                                </h5>
                            </div>
                            <div class="form-group col-md-12 padding0 mt5">
                                <button type="submit" class="btn btn-primary" id="btnwebsite" data-loading-text="Processing..." autocomplete="off">Save Website</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
        <div class="col-md-9 col-sm-8"></div>
    </div>
</div>