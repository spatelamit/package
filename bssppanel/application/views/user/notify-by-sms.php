</div>
<div class="container">
    <div class="row">
        <?php
        $data = array('id' => "validate-basic", 'class' => "form parsley-form");
        echo form_open('user/notify_users', $data);
        ?>
        <div class="col-sm-3">
            <div class="portlet">
                <div class="col-md-12">
                    <h2 class="content-header-title">Notify Users By SMS</h2>
                </div>
                <div class="portlet-content">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <input type="text" name="search_user" id="search_user" placeholder="Search User" class="form-control" 
                                   onkeyup="searchUserNotify(this.value)" />
                        </div>
                    </div>
                    <hr/>
                    <div class="row" id="search_user_notify"></div>
                </div>
            </div> 
        </div>
        <div class="col-sm-5">
            <div class="portlet">
                <div class="portlet-content">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="sender">Sender Id</label>
                            <input type="text" id="sender" name="sender" class="form-control" required="" data-parsley-minlength="6"  
                                   data-parsley-maxlength="6" placeholder="Sender Id" value="<?php echo $default_sender_id; ?>"
                                   data-parsley-required-message="Please enter sender id" data-parsley-maxlength-message="Sender id must be of 6 character long" 
                                   data-parsley-minlength-message="Sender id must be of 6 character long" />
                        </div>
                        <div class="form-group col-md-12">
                            <label for="message">Message</label>
                            <textarea id="message" name="message"  cols="10" rows="3" required="" class="form-control" placeholder="Message" 
                                      data-parsley-error-message="Please enter message!"></textarea>
                        </div>
                        <div class="form-group col-md-12 mt5">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
        </form>
    </div>
</div>