</div>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="portlet">
                <h2 class="content-header-title">Generate Key</h2>
                <div class="portlet-content">
                    <?php
                    $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                    echo form_open('user/regenerate_auth_key', $data);
                    ?>
                    <div class="row">
                        <div class="form-group col-md-12 padding0">
                            <label for="auth_key">Generate Authentication Key</label>
                            <input type="text" name="auth_key" id="auth_key" placeholder="Generate Authentication Key" 
                                   value="<?php echo (isset($user_info) && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>"
                                   class="form-control" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <span style="color: #E42929;font-size: 12px;">
                                <strong>Remember:</strong> Don't change this key until you have some kind of security issue. 
                                Changing this key will stop the entire APIs using old authorization key.
                            </span>
                        </div>
                        <div class="form-group col-md-12 mt5 padding0">
                            <button type="submit" class="btn btn-primary">Regenerate Key</button>
                        </div>
                    </div>
                    </form>
                </div> 
            </div> 
        </div>
    </div>
</div>