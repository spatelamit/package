</div>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <div class="portlet">
                <div class="col-md-12 padding0">
                    <h2 class="content-header-title">Web Hooks (For DLR Push)</h2>
                </div>
                <div class="portlet-content">
                    <?php
                    $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                    echo form_open('user/save_webhooks', $data);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-12 padding0">
                            <label for="push_dlr_url">
                                We post data (delivery report) in below URL.
                            </label>
                            <input type="text" name="push_dlr_url" id="push_dlr_url" placeholder="Enter Push DLR URL" 
                                   class="form-control" required="" data-parsley-required-message="Please enter your push DLR URL"
                                   data-parsley-type="url" data-parsley-type-message="Please enter valid push DLR URL"
                                   value="<?php echo (isset($user_info) && $user_info['push_dlr_url']) ? $user_info['push_dlr_url'] : ""; ?>" />
                        </div>
                        <div class="form-group col-md-12 mt5 padding0">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    </form>
                </div> 
            </div> 
        </div>

        <div class="col-sm-3">
            <div class="portlet">
                <div class="col-md-12">
                    <h2 class="content-header-title">Data Format</h2>
                </div>
                <div class="portlet-content">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="push_dlr_url">
                                We post data in below format:
                            </label>
                            <textarea name="data_format" id="data_format" class="form-control" rows="16" readonly="">
data={
    "requestId":"546b384ce51f469a2e8b4567",
    "numbers":{
        "911234567890":{
            "date":"2014-11-18 17:45:59",
            "status":1,
            "desc":"DELIVERED"
        },
        "911234567890":{
            "date":"2014-11-18 17:23:59",
            "status":2,
            "desc":"FAILED"
        }
    }
}
                            </textarea>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>