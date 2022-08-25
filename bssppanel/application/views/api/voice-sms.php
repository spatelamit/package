
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 1) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/voice_sms_api">Send Voice SMS</a>
            </li>
            
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 3) ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/voice_delivery_report_api">Delivery Report</a>
            </li>
          
        </ul>
    </div>
</div>
</div>

<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/voice_sms_api">Send Voice SMS</a>
    <a href="<?php echo base_url(); ?>api_docs/xml_send_voice_sms_api">XML Send Voice SMS</a>
    <a href="<?php echo base_url(); ?>api_docs/voice_delivery_report_api">Delivery Report</a>
    <a href="<?php echo base_url(); ?>api_docs/error_codes/text_sms">Error Code</a>
</nav>

<div class="container">
    <div class="row">
        <?php
        if (isset($page_type) && $page_type && $page_type == 1) {
            ?>
            <div class="row">
                <div class="col-md-9 borderR">
                    <div class="row">
                        <div class="bhoechie-tab-container">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
                                <div class="list-group">
                                    <a href="#" class="list-group-item active text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Send Voice SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Encoding API
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Send SMS on multiple numbers
                                    </a>
                                    
                                      <!--
                                      <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Send Schedule SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Send Unicode SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Schedule Unicode SMS
                                    </a>
                                  
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>Send Group SMS
                                    </a>
                                    -->
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9 bhoechie-tab">
                                <div class="portlet">

                                    <div class="bhoechie-tab-content active">
                                        <h2 class="content-header-title">Send SMS</h2>
                                       
                                        <h4>Sample API -</h4>
                                        <h4>Modern API</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php?<mark>authkey</mark>=YourAuthKey&<mark>mobiles</mark>=9999999991&<mark>message</mark>=Url Of Voice File&<mark>sender</mark>=9999999999&<mark>route</mark>=B</p>
                                        <h5>OR</h5>
                                        <h4>Traditional API</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php?<mark>username</mark>=username&<mark>password</mark>=password&<mark>mobiles</mark>=9999999991&<mark>message</mark>=Url Of Voice File&<mark>sender</mark>=9999999999&<mark>route</mark>=B</p>
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
                                                            <td>authkey <span class="text-danger">*</span></td>
                                                            <td>Alphanumeric</td>
                                                            <td>Login Authentication Key (This key is unique for every user)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>username<span class="text-danger">*</span></td>
                                                            <td>varchar</td>
                                                            <td>Login username (This username is unique for every user)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>password <span class="text-danger">*</span></td>
                                                            <td>varchar</td>
                                                            <td>Login password</td>
                                                        </tr>
                                                        <tr>
                                                            <td>mobiles <span class="text-danger">*</span></td>
                                                            <td>Integer</td>
                                                            <td>Mobile number can be entered with country code or without country code Multiple mobile no. should be separated by comma</td>
                                                        </tr>
                                                        <tr>
                                                            <td>message <span class="text-danger">*</span></td>
                                                            <td>varchar</td>
                                                            <td>Url Of Voice File</td>
                                                        </tr>
                                                        <tr>
                                                            <td>sender <span class="text-danger">*</span></td>
                                                            <td>varchar</td>
                                                            <td>Receiver will see this as sender 's</td>
                                                        </tr>
                                                        <tr>
                                                            <td>route <span class="text-danger">*</span></td>
                                                            <td>Varchar</td>
                                                            <td>If your operator supports multiple routes then give one route name.<br/>
                                                                For Promotional route=<strong>A</strong> | <strong>1</strong> | <strong>default</strong><br/>
                                                                For Dynamic route=<strong>B</strong> | <strong>4</strong> | <strong>template</strong><br/>
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td>duration <span class="text-danger">*</span></td>
                                                            <td>Integer</td>
                                                            <td>Duration of your voice file in second. </td>
                                                  
                                                        </tr>
                                                      
                                                        <tr>
                                                            <td>response</td>
                                                            <td>varchar</td>
                                                            <td>By default you will get response in string format but you want to receive in other format (json,xml) then set this parameter. for example: &response=json or &response=xml</td>
                                                        </tr>
                                                        <tr>
                                                            <td>campaign</td>
                                                            <td>varchar</td>
                                                            <td>campaign name you wish to create.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <h4>Sample Output - <span class="text-primary">"1"</span></h4>
                                        <h4>Note -</h4>
                                        <p class="text-danger">Output will be request Id which is "1". With this request Id, delivery Report can be Viewed. If Request not Sent Successfully, you will get the appropriate Error Message. </p>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">Encode Your API</h2>
                                        <h4>What is Url Encoding?</h4>
                                        <p>URL encoding converts characters into a format that can be send through internet We should use urlencode for all GET parameters because POST parameters are automatically encoded.</p>
                                        <h4>Why Url Encoding?</h4>
                                        <p>URLs often contain characters outside the ASCII set, the URL has to be converted into a valid ASCII format.URLs use some characters for special use in defining their syntax,when these characters are not used in their special role inside a URL, they need to be encoded.Url encoding is done to encode user input</p>
                                        <h4>Example -</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php?<mark>authkey</mark>=YourAuthKey&<mark>mobiles</mark>=9999999991&<mark>message</mark>=Url Of Voice File&<mark>sender</mark>=999999999&<mark>route</mark>=B</p>
                                      
                                        <h4>Encoded API -</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php?<mark>authkey</mark>=YourAuthKey&<mark>mobiles</mark>=<?php echo urlencode('9999999991'); ?>&<mark>message</mark>=<?php echo urlencode('Url Of Voice File'); ?>/<mark>sender</mark>=999999999&<mark>route</mark>=B</p>
                                       
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">Send message on multiple numbers</h2>
                                        <h4>Example -</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_voice_http.php?<mark>authkey</mark>=YourAuthKey&<mark>mobiles</mark>=9999999991,9999999992&<mark>message</mark>=<?php echo urlencode('Url Of Voice File'); ?>&<mark>sender</mark>=999999999&<mark>route</mark>=B</p>
                                        <h5>To send message on multiple numbers numbers should be comma separated</h5>
                                        
                                    </div>

                                   
                                    <!--
                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">Send Message on Group</h2>
                                        <h4>Example -</h4>
                                        <p class="text-success"><?php //echo (isset($domain_name) && $domain_name) ? $domain_name : "";                       ?>api/send_http.php?<mark>authkey</mark>=YourAuthKey&<mark>mobiles</mark>=9999999991&<mark>message</mark>=Hello Worlds&<mark>sender</mark>=SenderID&<mark>route</mark>=A&<mark>unicode/</mark>/1/<mark>schtime</mark>/2015-03-19 15:33:48</p>
                                        <p class="text-success"><?php //echo (isset($domain_name) && $domain_name) ? $domain_name : "";                       ?>/api/sendhttp.php?authkey=YourAuthKey&mobiles=9999999999,919999999999&message=test+%26+new&sender=123456&<mark>group_id</mark>=groupid</p>
                                        <h5>While sending message on group that contains numbers saved by user,parameter to be set is group_id and it's value should be equal to the existing group id 
                                        To View all groups and it's id's <a href="#">Get Group Id</a></h5>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form41" action="javascript:generateAPI('4', '1', 'send_voice_http');">
                                <div class="row">
                                    <!--<label>Authentication Key <span class="text-danger">*</span></label>-->
                                    <div class="col-md-12 col-sm-12 form-group padding0 padding0">
                                        <label>Authentication Key <span class="text-danger">*</span></label>
                                        <input id="authkey" name="authkey" type="text" class="form-control" value="<?php echo (isset($user_info) && $user_info && $user_info['auth_key']) ? $user_info['auth_key'] : ""; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                        <label>Mobiles <span class="text-danger">*</span></label>
                                        <input id="mobiles" name="mobiles" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Mobiles">
                                    </div>     
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                        <label>Voice File Url  <span class="text-danger">*</span></label>
                                        <textarea id="message" name="message" class="form-control" required="" data-parsley-error-message="Please Enter Message"></textarea>
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                        <label>Sender <span class="text-danger">*</span></label>
                                        <input id="sender" name="sender" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Sender">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                        <label>Route <span class="text-danger">*</span></label>
                                        <input id="route" name="route" type="text" class="form-control" required="" data-parsley-error-message="Please Enter Route">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                           <label>Duration <span class="text-danger">*</span></label>
                                           <input id="duration" name="duration" type="text" class="form-control" required="required">
                                    </div>
                                </div>
                              
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0">
                                        <label>Response</label>
                                        <input id="response" name="response" type="text" class="form-control">
                                    </div>    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 form-group padding0"> 
                                        <label>Campaign</label>
                                        <input id="campaign" name="campaign" type="text" class="form-control">
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
                    <div class="row">
                        <div class="bhoechie-tab-container">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 bhoechie-tab-menu">
                                <div class="list-group">
                                    <a href="#" class="list-group-item active text-center">
                                        <h4 class="fa fa-gears"></h4><br/>XML Send SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>XML SMS Description
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>XML Schedule SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>XML Flash SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>XML Unicode SMS
                                    </a>
                                    <a href="#" class="list-group-item text-center">
                                        <h4 class="fa fa-gears"></h4><br/>WHAT IS XML
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9 bhoechie-tab">
                                <div class="portlet">

                                    <div class="bhoechie-tab-content active">
                                        <h2 class="content-header-title">XML Send SMS</h2>
                                        <h4>Why XML API</h4>
                                        <ol>
                                            <li>It is not possible for a URL to take too many data in GET method and have to use POST method.</li>
                                            <li>It enabled few extra features like sending custom SMS.</li>
                                            <li>Sending 500 SMS will take 5 loop in HTTP API but NONE in XML thus saving your resources and ours too.</li>
                                            <li>If you face any issue, take help of our engineers</li>
                                        </ol>
                                        <h4>Sample XML format -</h4>
                                        <pre class="language"><code class=" language-xml">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;Template<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;XML API<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"message1"</span> &gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"number1"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"number2"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hi test message"</span> &gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"number3"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <h4>Post your request with above format in data variable.</h4>
                                        <p class="text-success"><?php echo (isset($domain_name) && $domain_name) ? $domain_name : ""; ?>api/send_xml.php</p>
                                        <h4>Sample Output - <span class="text-primary">"1"</span></h4>
                                        <h4>Note -</h4>
                                        <p class="text-danger">Output will be request Id which is "1". With this request Id, delivery Report can be Viewed. If Request not Sent Sucessfully, you will get the appropriate Error Message</p>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">Description</h2>
                                        <div class="portlet-content">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered bgf">
                                                    <thead>
                                                        <tr>
                                                            <th>Parameter Name</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>AUTHKEY  <span class="text-danger">*</span></td>
                                                            <td>Login Authentication Key (This key is unique for every user)</td>
                                                        </tr>
                                                        <tr>
                                                            <td>TEXT <span class="text-danger">*</span></td>
                                                            <td>It contains the URL encoded message content to send</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SENDER <span class="text-danger">*</span></td>
                                                            <td>It contains senderid</td>
                                                        </tr>
                                                        <tr>
                                                            <td>TO <span class="text-danger">*</span></td>
                                                            <td>It contain mobile numbers</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SCHEDULEDATETIME</td>
                                                            <td>It contains scheduled Date and Time</td>
                                                        </tr>
                                                        <tr>
                                                            <td>FLASH</td>
                                                            <td>1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>UNICODE</td>
                                                            <td>1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>ROUTE</td>
                                                            <td>Route name if you have more than one route available in your account.
                                                                <br/>
                                                                For Promotional route=<strong>A</strong> | <strong>1</strong> | <strong>default</strong><br/>
                                                                For Transactional route=<strong>B</strong> | <strong>4</strong> | <strong>template</strong><br/>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>CAMPAIGN</td>
                                                            <td>It contains campaign name</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <h4>Send message on single number -</h4>
                                            <pre class="language"><code class=" language-xml">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                        <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                        <span class="mw-code operator">&lt;</span>ROUTE&gt;Template<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                        <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;TRIAL<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                        <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                        <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"Hi this is a test message"</span>&gt;
                                <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                        <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                            <p>To send message on multiple numbers, use 'ADDRESS' tag multiple times with different mobile numbers. Above API example can be modified to send single message on two mobile numbers.</p>
                                        </div>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">XML Schedule Message</h2>
                                        <pre class="language"><code class=" language-xml">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;BULKSMS<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>SCHEDULEDATETIME<span class="mw-code number">&gt;2015</span><span class="mw-code number">-03</span><span class="mw-code number">-19</span><span class="mw-code number"> 17</span><span class="mw-code number">:01</span><span class="mw-code number">:30</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SCHEDULEDATETIME&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is test message"</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>To send Schedule message one more tag <mark>SCHEDULEDATETIME</mark> will be added and it will contain scheduled Date and Time Sending Customize Message</p>
                                        <h2 class="content-header-title tbl">Sending Customize Message</h2>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is test message"</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is second test message"</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999991"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>This Api can be used to send different messages on different numbers,simply by adding another <mark>SMS</mark> tag with different message content in TEXT</p>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">XML Flash Message</h2>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;FLASH SMS<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>FLASH<span class="mw-code number">&gt;1</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>FLASH&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is test message"</span>&gt;
                            <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>To send Flash message one more tag <mark>FLASH</mark> will be added and it will contain 1</p>

                                        <h2 class="content-header-title tbl">Sending Message Using route</h2>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;CUSTOM SMS<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is test message"</span>&gt;
                            <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>Above Api can be used to send sms using route and it can be done easily by adding one more tag i.e <mark>ROUTE</mark> tag and it's value should be either default or template other wise message will be processed from default route.</p>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h2 class="content-header-title">XML Unicode Message</h2>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>CAMPAIGN&gt;UNICODE SMS<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>CAMPAIGN&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>UNICODE<span class="mw-code number">&gt;1</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>UNICODE&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"hii this is test message"</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>To send Unicode message one more tag <mark>UNICODE</mark> will be added and it will contain 2</p>
                                    </div>

                                    <div class="bhoechie-tab-content">
                                        <h4>What iS XML?</h4>
                                        <p>XML (Extensible Markup Language) is a flexible way to create common information formats and share both the format and the data on Internet.As in HTML,it has it's own predefined tags while what makes XML flexible is that custom tags can be defined and the tags are invented by the author of the XML document.</p>
                                        <h2 class="content-header-title">Encode Your Message</h2>
                                        <h4>What is HTML Encoding?</h4>
                                        <p>The HTML character encoder converts all applicable characters to their corresponding HTML entities. Certain characters have special significance in HTML and should be converted to their correct HTML entities to preserve their meanings. For example, it is not possible to use the < character as it is used in the HTML syntax to create and close tags. It must be converted to its corresponding <mark>&lt;</mark> HTML entity to be displayed in the content of an HTML page. HTML entity names are case sensitive.</p>
                                        <h4>Example -</h4>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"your password is: "</span>0989898<span class="mw-code string">" "</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>In this api the message content "your password is: "0989898" " includes " (double quotes) operator,due to which HTML encoding is necessary, otherwise it will break your XML and an error will be occur.</p>
                                        <h4>Encoded Api -</h4>
                                        <pre class="language"><code class=" language-java">
                <span class="mw-code operator">&lt;</span>MESSAGE&gt;
                    <span class="mw-code operator">&lt;</span>AUTHKEY&gt;Authentication Key<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>AUTHKEY&gt;
                    <span class="mw-code operator">&lt;</span>ROUTE&gt;<span class="mw-code keyword">default</span><span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ROUTE&gt;
                    <span class="mw-code operator">&lt;</span>SENDER&gt;SenderID<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SENDER&gt;
                    <span class="mw-code operator">&lt;</span>SMS TEXT<span class="mw-code operator">=</span><span class="mw-code string">"your password is: "</span>0989898<span class="mw-code string">" "</span>&gt;
                        <span class="mw-code operator">&lt;</span>ADDRESS TO<span class="mw-code operator">=</span><span class="mw-code string">"9999999990"</span>&gt;<span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>ADDRESS&gt;
                    <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>SMS&gt;
                <span class="mw-code operator">&lt;</span><span class="mw-code operator">/</span>MESSAGE&gt;
                </code></pre>
                                        <p>By Encoding HTML special characters in api, will not break your XML.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="portlet">
                        <h2 class="content-header-title">Generate API</h2>
                        <div class="portlet-content">
                            <form role="form" class="validate" method='post' id="form42" action="javascript:generateAPI('4', '2', 'send_xml');">
                                <div class="row">
                                    <div class="col-md-12 form-group padding0">
                                        <label>Data</label>
                                        <textarea  class="form-control" id="xml_data" name="xml_data" rows="10" placeholder="Write your XML code here">
<MESSAGE>
    <AUTHKEY>Your auth key</AUTHKEY>
    <SENDER>SenderID</SENDER>
    <ROUTE>Template</ROUTE>
    <CAMPAIGN>campaign name</CAMPAIGN>
    <SMS TEXT="message1">
        <ADDRESS TO="number1"></ADDRESS>
    </SMS>
</MESSAGE>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 mt5 padding0">
                                        <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary"
                                                data-loading-text="Generating..." autocomplete="off">Generate API</button>
                                        <button type="button" name="check_xml" id="check_xml" class="btn btn-success" onclick="checkXMLCode();">Test your XML code</button>
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
                            <h2 class="content-header-title">Push Delivery Report</h2>
                            <h3>Get instant delivery report as soon as we receive</h3>
                            <h4>What is DLR Push?</h4>
                            <label>We post data (with all details of delivery report) on your web URL</label><br/>
                            <h4>Benefits over old API</h4>
                            <label>DLR Push improves speed & efficiency of receiving Delivery reports.</label><br/>
                            <label>It reduces Server Load, and latency in fetching the reports.</label><br/>
                            <h4>DLR push works as follows :</h4>
                            <label>At first, Add your URL to webhooks in your account, it should be an address of the webpage of your application on which you need to receive delivery reports.</label><br/>
                            <label>Now we start posting the data on this URL as soon as we receive new DLR.</label>
                            <h4> What if your server is down for any reason while we posting data?</h4>
                            <label>Don’t worry! We will keep trying to post data in the interval of 1 hour.</label><br/>
                            <h4>Sample API -</h4>
                            <pre class="language">
                <code class="language-php">
                    <span class="mw-code php"><span class="mw-code delimiter">&lt;?php</span>
                    <span class="mw-code comment" spellcheck="true">
                    </span><span class="mw-code variable">$request = $_REQUEST["data"];</span></span>
                    <span class="mw-code comment" spellcheck="true">
                    </span><span class="mw-code variable">$jsonData = json_decode($request);</span></span>
                    <span class="mw-code comment" spellcheck="true">
                    // your request id 
                    </span><span class="mw-code variable">$request_id = $jsonData->requestId;</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    /* details of each number */
                    </span><span class="mw-code variable">$details = $jsonData->numbers;</span></span>
                    </span><span class="mw-code variable">$array = json_decode(json_encode($details), true);</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    </span><span class="mw-code variable">$link = mysqli_connect("myhost", "myuser", "mypassw", "mybd") or die("Error " . mysqli_error($link));</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    </span><span class="mw-code variable">foreach ($array as $number => $value) {</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    // status of each number
                    </span><span class="mw-code variable">$status = $value['status'];</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    // destination number
                    </span><span class="mw-code variable">$reciever_number = $number;</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    //detail description of report
                    </span><span class="mw-code variable">$desc = $value['desc'];</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    //delivery report time
                    </span><span class="mw-code variable">$recieving_date = $value['date'];</span></span>
                    </span><span class="mw-code variable">$query = "INSERT INTO mytable (request_id,date,receiver,status,description) VALUES ('" . $request_id . "','" . $recieving_date . "','" . $reciever_number . "','" . $status . "','" . $desc . "')";</span></span>
                    <span class="mw-code comment" spellcheck="true"></span>
                    //execute the query.
                </span><span class="mw-code variable">$result = $link->query($query) ;</span></span>
</span><span class="mw-code variable"> }</span></span>
 
                    <span class="mw-code comment" spellcheck="true"></span>
                    <span class="mw-code delimiter">?&gt;</span></span>
                </code>
    </pre>
    <h4>We Will hit the URL in following format:</h4>
    <p class="text-success">http://yourdomain.com/dlr/pushUrl.php?data=%7B%22requestId%22%3A%22546b384ce51f469a2e8b4567%22%2C%22numbers%22%3A%7B%22911234567890%22%3A%7B%22date%22%3A%222014-11-18+17%3A45%3A59%22%2C%22status%22%3A1%2C%22desc%22%3A%22DELIVERED%22%7D%7D%7D </p>
    <h4>Sample data for single number:</h4>
    <p class="text-success">
        data={
        "requestId":"546b384ce51f469a2e8b4567",
        "numbers":{
        "911234567890":{
        "date":"2014-11-18 17:45:59",
        "status":1,
        "desc":"DELIVERED"
        }}}
    </p>
    <h4>Sample data for multiple number:</h4>
    <p class="text-success">    
        data={
        "requestId":"546b384ce51f469a2e8b4567",
        "numbers":{
        "911234567890":{
        "date":"2014-11-18 17:45:59",
        "status":1,
        "desc":"DELIVERED"
        }
        "919876543210":{
        "date":"2014-11-18 17:23:59",
        "status":2,
        "desc":"FAILED"
        }
        }}
    </p>
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
                        <td>reqid</td>
                        <td>varchar</td>
                        <td>Request id (a unique 24 character alphanumeric value for identification of a particular SMS)</td>
                    </tr>
                    <tr>
                        <td>status</td>
                        <td>numeric</td>
                        <td>Status of SMS Delivery 1=delivered 2=failed 16=rejected</td>
                    </tr>
                    <tr>
                        <td>desc</td>
                        <td>Text</td>
                        <td>Delivered/Failed/Rejected</td>
                    </tr>
                    <tr>
                        <td>number</td>
                        <td>numeric (with country code)</td>
                        <td>Receiver’s Contact Number</td>
                    </tr>
                    <tr>
                        <td>date (YYMMDDhhmm)</td>
                        <td>string</td>
                        <td>Displays the Delivery time and Date of SMS YY: Year MM: Month DD: Date hh: Hours mm: Minutes</td>
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
                <form role="form" class="validate" method='post' id="form43" action="javascript:generateAPI('4', '3', 'balance');">
                    <div class="row">
                        <div class="col-md-8 form-group padding0">
                            <label>Fill the URL below to test your PushDlr code.
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 form-group padding0">
                            <label>URL 
                                <span class="text-danger">*</span>
                            </label>
                            <input id="pushdlr_url" name="pushdlr_url" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group padding0">
                            <label>(Ex. http://yourdomain.com/dlr/pushUrl.php)
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8 mt5 padding0">
                            <button type="submit" name="generate_api" id="generate_api" class="btn btn-primary">Generate PushDlr URL</button>
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
                                            <td>105</td>
                                            <td>Missing Mobile Number(s)</td>
                                        </tr>
                                        <tr>
                                            <td>106</td>
                                            <td>Missing Message</td>
                                        </tr>
                                        <tr>
                                            <td>107</td>
                                            <td>Missing Sender Id</td>
                                        </tr>
                                        <tr>
                                            <td>108</td>
                                            <td>Missing username</td>
                                        </tr>
                                        <tr>
                                            <td>109</td>
                                            <td>Missing password</td>
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
                                        <tr>
                                            <td>203</td>
                                            <td>Invalid Sender Id</td>
                                        </tr>
                                        <tr>
                                            <td>204</td>
                                            <td>Invalid Mobile Number(s)</td>
                                        </tr>
                                        <tr>
                                            <td>205</td>
                                            <td>Invalid XML Request</td>
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
                                            <td>305</td>
                                            <td>This route is currently unavailable</td>
                                        </tr>
                                        <tr>
                                            <td>306</td>
                                            <td>Schedule time is Incorrect</td>
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