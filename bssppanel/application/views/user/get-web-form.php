<!-- Reseller Website -->

<!-- Reseller Signin -->
<?php if (isset($form) && $form == "signin") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2" >
        <div class="wrap">
            <p class="form-title">Sign In</p>
            <?php
            $data = array('id' => "signinForm", 'class' => "login");

            echo form_open('sav4h5gs5xf5wat85kgv58474r5d/domain_auth_details', $data);
            ?>
            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username" />
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" />
            <button type="submit" name="login" id="login" class="btn btn-primary">Sign In</button>
            <div class="f-pass">
                <a href="javascript:void(0);" onclick="showWebForm('forgot');"><i class="ion ion-help-circled"></i> Forgot Password</a>
                <p>Not have an account yet? <a href="javascript:void(0);" onclick="showWebForm('signup');">Sign Up</a></p>
            </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Signup -->
<?php if (isset($form) && $form == "signup") { ?>
    <div class="col-md-6 col-md-offset-3" data-id="2">
        <div class="wrap">
            <p class="form-title">Sign Up</p>
            <form role="form" class="login" id="signupForm" method='post' action="javascript:saveNewUser();">
                <div class="row">
                    <div class="col-md-12">
                        <div id="error" class="alert alert-danger danger hidden"></div>
                        <div id="success" class="alert alert-success success hidden"></div>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="signup_username" name="signup_username" class="form-control" placeholder="Enter Username"
                               onkeyup="checkUsername(this.value);" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Enter Contact Number" />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="email_address" name="email_address" class="form-control" placeholder="Enter Email Address"  />
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="company_name" name="company_name" class="form-control" placeholder="Enter Company Name" />
                    </div>
                    <div class="col-md-6">
                        <select name="industry" class="form-control" id="industry" style="background-color: black; color: white ;opacity: 0.4;">
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
                            <option value="security and housekeeping">security and housekeeping</option>

                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="country"  class="form-control" style="background-color: black; color: white ;opacity: 0.4;">
                            <option value="INDIA">INDIA</option>
                            <option value="United States">United States</option> 
                            <option value="United Kingdom">United Kingdom</option> 
                            <option value="Afghanistan">Afghanistan</option> 
                            <option value="Albania">Albania</option> 
                            <option value="Algeria">Algeria</option> 
                            <option value="American Samoa">American Samoa</option> 
                            <option value="Andorra">Andorra</option> 
                            <option value="Angola">Angola</option> 
                            <option value="Anguilla">Anguilla</option> 
                            <option value="Antarctica">Antarctica</option> 
                            <option value="Antigua and Barbuda">Antigua and Barbuda</option> 
                            <option value="Argentina">Argentina</option> 
                            <option value="Armenia">Armenia</option> 
                            <option value="Aruba">Aruba</option> 
                            <option value="Australia">Australia</option> 
                            <option value="Austria">Austria</option> 
                            <option value="Azerbaijan">Azerbaijan</option> 
                            <option value="Bahamas">Bahamas</option> 
                            <option value="Bahrain">Bahrain</option> 
                            <option value="Bangladesh">Bangladesh</option> 
                            <option value="Barbados">Barbados</option> 
                            <option value="Belarus">Belarus</option> 
                            <option value="Belgium">Belgium</option> 
                            <option value="Belize">Belize</option> 
                            <option value="Benin">Benin</option> 
                            <option value="Bermuda">Bermuda</option> 
                            <option value="Bhutan">Bhutan</option> 
                            <option value="Bolivia">Bolivia</option> 
                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
                            <option value="Botswana">Botswana</option> 
                            <option value="Bouvet Island">Bouvet Island</option> 
                            <option value="Brazil">Brazil</option> 
                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
                            <option value="Brunei Darussalam">Brunei Darussalam</option> 
                            <option value="Bulgaria">Bulgaria</option> 
                            <option value="Burkina Faso">Burkina Faso</option> 
                            <option value="Burundi">Burundi</option> 
                            <option value="Cambodia">Cambodia</option> 
                            <option value="Cameroon">Cameroon</option> 
                            <option value="Canada">Canada</option> 
                            <option value="Cape Verde">Cape Verde</option> 
                            <option value="Cayman Islands">Cayman Islands</option> 
                            <option value="Central African Republic">Central African Republic</option> 
                            <option value="Chad">Chad</option> 
                            <option value="Chile">Chile</option> 
                            <option value="China">China</option> 
                            <option value="Christmas Island">Christmas Island</option> 
                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
                            <option value="Colombia">Colombia</option> 
                            <option value="Comoros">Comoros</option> 
                            <option value="Congo">Congo</option> 
                            <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
                            <option value="Cook Islands">Cook Islands</option> 
                            <option value="Costa Rica">Costa Rica</option> 
                            <option value="Cote D'ivoire">Cote D'ivoire</option> 
                            <option value="Croatia">Croatia</option> 
                            <option value="Cuba">Cuba</option> 
                            <option value="Cyprus">Cyprus</option> 
                            <option value="Czech Republic">Czech Republic</option> 
                            <option value="Denmark">Denmark</option> 
                            <option value="Djibouti">Djibouti</option> 
                            <option value="Dominica">Dominica</option> 
                            <option value="Dominican Republic">Dominican Republic</option> 
                            <option value="Ecuador">Ecuador</option> 
                            <option value="Egypt">Egypt</option> 
                            <option value="El Salvador">El Salvador</option> 
                            <option value="Equatorial Guinea">Equatorial Guinea</option> 
                            <option value="Eritrea">Eritrea</option> 
                            <option value="Estonia">Estonia</option> 
                            <option value="Ethiopia">Ethiopia</option> 
                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
                            <option value="Faroe Islands">Faroe Islands</option> 
                            <option value="Fiji">Fiji</option> 
                            <option value="Finland">Finland</option> 
                            <option value="France">France</option> 
                            <option value="French Guiana">French Guiana</option> 
                            <option value="French Polynesia">French Polynesia</option> 
                            <option value="French Southern Territories">French Southern Territories</option> 
                            <option value="Gabon">Gabon</option> 
                            <option value="Gambia">Gambia</option> 
                            <option value="Georgia">Georgia</option> 
                            <option value="Germany">Germany</option> 
                            <option value="Ghana">Ghana</option> 
                            <option value="Gibraltar">Gibraltar</option> 
                            <option value="Greece">Greece</option> 
                            <option value="Greenland">Greenland</option> 
                            <option value="Grenada">Grenada</option> 
                            <option value="Guadeloupe">Guadeloupe</option> 
                            <option value="Guam">Guam</option> 
                            <option value="Guatemala">Guatemala</option> 
                            <option value="Guinea">Guinea</option> 
                            <option value="Guinea-bissau">Guinea-bissau</option> 
                            <option value="Guyana">Guyana</option> 
                            <option value="Haiti">Haiti</option> 
                            <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
                            <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
                            <option value="Honduras">Honduras</option> 
                            <option value="Hong Kong">Hong Kong</option> 
                            <option value="Hungary">Hungary</option> 
                            <option value="Iceland">Iceland</option> 
                            <option value="INDIA" selected="selected">INDIA</option> 
                            <option value="Indonesia">Indonesia</option> 
                            <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
                            <option value="Iraq">Iraq</option> 
                            <option value="Ireland">Ireland</option> 
                            <option value="Israel">Israel</option> 
                            <option value="Italy">Italy</option> 
                            <option value="Jamaica">Jamaica</option> 
                            <option value="Japan">Japan</option> 
                            <option value="Jordan">Jordan</option> 
                            <option value="Kazakhstan">Kazakhstan</option> 
                            <option value="Kenya">Kenya</option> 
                            <option value="Kiribati">Kiribati</option> 
                            <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
                            <option value="Korea, Republic of">Korea, Republic of</option> 
                            <option value="Kuwait">Kuwait</option> 
                            <option value="Kyrgyzstan">Kyrgyzstan</option> 
                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
                            <option value="Latvia">Latvia</option> 
                            <option value="Lebanon">Lebanon</option> 
                            <option value="Lesotho">Lesotho</option> 
                            <option value="Liberia">Liberia</option> 
                            <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
                            <option value="Liechtenstein">Liechtenstein</option> 
                            <option value="Lithuania">Lithuania</option> 
                            <option value="Luxembourg">Luxembourg</option> 
                            <option value="Macao">Macao</option> 
                            <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
                            <option value="Madagascar">Madagascar</option> 
                            <option value="Malawi">Malawi</option> 
                            <option value="Malaysia">Malaysia</option> 
                            <option value="Maldives">Maldives</option> 
                            <option value="Mali">Mali</option> 
                            <option value="Malta">Malta</option> 
                            <option value="Marshall Islands">Marshall Islands</option> 
                            <option value="Martinique">Martinique</option> 
                            <option value="Mauritania">Mauritania</option> 
                            <option value="Mauritius">Mauritius</option> 
                            <option value="Mayotte">Mayotte</option> 
                            <option value="Mexico">Mexico</option> 
                            <option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
                            <option value="Moldova, Republic of">Moldova, Republic of</option> 
                            <option value="Monaco">Monaco</option> 
                            <option value="Mongolia">Mongolia</option> 
                            <option value="Montserrat">Montserrat</option> 
                            <option value="Morocco">Morocco</option> 
                            <option value="Mozambique">Mozambique</option> 
                            <option value="Myanmar">Myanmar</option> 
                            <option value="Namibia">Namibia</option> 
                            <option value="Nauru">Nauru</option> 
                            <option value="Nepal">Nepal</option> 
                            <option value="Netherlands">Netherlands</option> 
                            <option value="Netherlands Antilles">Netherlands Antilles</option> 
                            <option value="New Caledonia">New Caledonia</option> 
                            <option value="New Zealand">New Zealand</option> 
                            <option value="Nicaragua">Nicaragua</option> 
                            <option value="Niger">Niger</option> 
                            <option value="Nigeria">Nigeria</option> 
                            <option value="Niue">Niue</option> 
                            <option value="Norfolk Island">Norfolk Island</option> 
                            <option value="Northern Mariana Islands">Northern Mariana Islands</option> 
                            <option value="Norway">Norway</option> 
                            <option value="Oman">Oman</option> 
                            <option value="Pakistan">Pakistan</option> 
                            <option value="Palau">Palau</option> 
                            <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
                            <option value="Panama">Panama</option> 
                            <option value="Papua New Guinea">Papua New Guinea</option> 
                            <option value="Paraguay">Paraguay</option> 
                            <option value="Peru">Peru</option> 
                            <option value="Philippines">Philippines</option> 
                            <option value="Pitcairn">Pitcairn</option> 
                            <option value="Poland">Poland</option> 
                            <option value="Portugal">Portugal</option> 
                            <option value="Puerto Rico">Puerto Rico</option> 
                            <option value="Qatar">Qatar</option> 
                            <option value="Reunion">Reunion</option> 
                            <option value="Romania">Romania</option> 
                            <option value="Russian Federation">Russian Federation</option> 
                            <option value="Rwanda">Rwanda</option> 
                            <option value="Saint Helena">Saint Helena</option> 
                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
                            <option value="Saint Lucia">Saint Lucia</option> 
                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
                            <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
                            <option value="Samoa">Samoa</option> 
                            <option value="San Marino">San Marino</option> 
                            <option value="Sao Tome and Principe">Sao Tome and Principe</option> 
                            <option value="Saudi Arabia">Saudi Arabia</option> 
                            <option value="Senegal">Senegal</option> 
                            <option value="Serbia and Montenegro">Serbia and Montenegro</option> 
                            <option value="Seychelles">Seychelles</option> 
                            <option value="Sierra Leone">Sierra Leone</option> 
                            <option value="Singapore">Singapore</option> 
                            <option value="Slovakia">Slovakia</option> 
                            <option value="Slovenia">Slovenia</option> 
                            <option value="Solomon Islands">Solomon Islands</option> 
                            <option value="Somalia">Somalia</option> 
                            <option value="South Africa">South Africa</option> 
                            <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
                            <option value="Spain">Spain</option> 
                            <option value="Sri Lanka">Sri Lanka</option> 
                            <option value="Sudan">Sudan</option> 
                            <option value="Suriname">Suriname</option> 
                            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
                            <option value="Swaziland">Swaziland</option> 
                            <option value="Sweden">Sweden</option> 
                            <option value="Switzerland">Switzerland</option> 
                            <option value="Syrian Arab Republic">Syrian Arab Republic</option> 
                            <option value="Taiwan, Province of China">Taiwan, Province of China</option> 
                            <option value="Tajikistan">Tajikistan</option> 
                            <option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
                            <option value="Thailand">Thailand</option> 
                            <option value="Timor-leste">Timor-leste</option> 
                            <option value="Togo">Togo</option> 
                            <option value="Tokelau">Tokelau</option> 
                            <option value="Tonga">Tonga</option> 
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option> 
                            <option value="Tunisia">Tunisia</option> 
                            <option value="Turkey">Turkey</option> 
                            <option value="Turkmenistan">Turkmenistan</option> 
                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
                            <option value="Tuvalu">Tuvalu</option> 
                            <option value="Uganda">Uganda</option> 
                            <option value="Ukraine">Ukraine</option> 
                            <option value="UAE">United Arab Emirates</option> 
                            <option value="UK">United Kingdom</option> 
                            <option value="US">United States</option> 
                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
                            <option value="Uruguay">Uruguay</option> 
                            <option value="Uzbekistan">Uzbekistan</option> 
                            <option value="Vanuatu">Vanuatu</option> 
                            <option value="Venezuela">Venezuela</option> 
                            <option value="Viet Nam">Viet Nam</option> 
                            <option value="Virgin Islands, British">Virgin Islands, British</option> 
                            <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
                            <option value="Wallis and Futuna">Wallis and Futuna</option> 
                            <option value="Western Sahara">Western Sahara</option> 
                            <option value="Yemen">Yemen</option> 
                            <option value="Zambia">Zambia</option> 
                            <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                       
                    </div>
                    <div class="col-md-8">
                        <label for="terms">
                            <input type="checkbox" name="terms" id="terms" value="1" checked="" />
                            I agree with <a href="<?php echo base_url(); ?>terms_conditions" target="_blank">terms of services</a>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="signup" id="signup" class="btn btn-primary">Sign Up</button>
                    </div>
                </div>
                <div class="f-pass">
                    <a href="<?php echo base_url();?>" style="font-size:20px;"><i class="ion ion-reply"></i> Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Forgot Password -->
<?php if (isset($form) && $form == "forgot") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Forgot Password?</p>
            <form role="form" class="login" id="forgotPasswordForm" method='post' action="javascript:forgotPassword();">
                <div id="error" class="alert alert-danger danger hidden"></div>
                <input type="text" id="forgot_username" name="forgot_username" class="form-control" placeholder="Enter Username" />
                <button type="submit" name="forgot_password" id="forgot_password" class="btn btn-primary">Submit</button>
                <div class="f-pass">
                    <a href="javascript:void(0);" onclick="showWebForm('signin');"><i class="ion ion-reply"></i> Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Verify Code -->
<?php if (isset($form) && $form == "verify") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Verify Code</p>
            <form role="form" class="login" id="verifyCodeForm" method='post' action="javascript:verifyCode();">
                <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                <input type="text" id="verification_code" name="verification_code" class="form-control" placeholder="Enter Verification Code" />
                <button type="submit" name="verify_code" id="verify_code" class="btn btn-primary">Submit</button>
                <div class="f-pass">
                    Didn't get the code yet? <a href="javascript:void(0);" onclick="sendOTP('reseller');">Request</a> another one.
                </div>
                <div class="f-pass">
                    <a href="javascript:void(0);" onclick="showWebForm('forgot');"><i class="ion ion-help-circled"></i> Forgot Password</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<!-- Reseller Reset Password -->
<?php if (isset($form) && $form == "reset") { ?>
    <div class="col-md-4 col-md-offset-4" data-id="2">
        <div class="wrap">
            <p class="form-title">Reset Password</p>
            <form role="form" class="login" id="resetPasswordFrom" method='post' action="javascript:resetPassword();">
                <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                    <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter New Password" />
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" />
                <button type="submit" name="reset_password" id="reset_password" class="btn btn-primary">Reset Password</button>
                <div class="f-pass">
                    <a href="javascript:void(0);" onclick="showWebForm('verify');"><i class="ion ion-help-circled"></i> Forgot Password</a>
                </div>
            </form>
        </div>
    </div>
<?php } ?>


<!-- Bulk SMS Service Providers Website -->

<!-- Bulk SMS Service Providers Signin -->
<?php if (isset($form) && $form == "web_login") { ?>
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title">Sign In</h3>
                <!-- <?php echo (isset($company_name)) ? $company_name : "Company Name"; ?>- -->
            </div>
            <div class="panel-body">
                <?php
                $data = array('id' => "signinForm", 'class' => "form-horizontal");
                echo form_open('signin/validate_user', $data);
                ?>
                <div class="row">
                    <div class="col-md-12" id="show_message">
                        <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                            <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                        <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                            <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-person"></i></span>
                            <input class="form-control" type="text" placeholder="Enter Username" name="username" id="username" />
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-locked"></i></span>
                            <input class="form-control" type="password" placeholder="Enter Password" id="password" name="password" />
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <button type="submit" name="login" id="login" class="btn btn-lg btn-primary">Sign In</button>
                    </div>
                </div>
                </form>
                <div class="row form-group">
                    <div class="col-md-12">
                        <a href="<?php echo base_url(); ?>forgot_password">Forgot Password?</a>
                    </div>
                </div>
            </div>
            <div class="panel-footer"><span>Want an account?</span>
                <a href="<?php echo base_url(); ?>signup" class="btn btn-success btn-sm pull-right">Sign Up</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Forgot Password -->
<?php if (isset($form) && $form == "web_forgot") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title" id="actionHeading">Forgot Password?</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>We will help you to reset it!</h4>
                    </div>
                </div>
                <form role="form" class="form-horizontal" id="forgotPasswordForm" method='post' action="javascript:webForgotPassword();">
                    <div class="row">
                        <div class="col-md-12" id="show_message">
                            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-person"></i></span>
                                <input class="form-control" type="text" placeholder="Enter Username" name="forgot_username" id="forgot_username" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="forgot_password" id="forgot_password" class="btn btn-lg btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Sign In?</span>
                <a href="<?php echo base_url(); ?>signin" class="btn btn-success btn-sm pull-right">Sign In</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Verify Code -->
<?php if (isset($form) && $form == "web_verify") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title" id="actionHeading">
                    Does XXXXXXX<?php echo substr($contact_number, -3); ?> really belong to you?
                </h3>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" id="verifyCodeForm" method='post' action="javascript:webVerifyCode();">
                    <div class="row">
                        <div class="col-md-12" id="show_message">
                            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-person"></i></span>
                                <input class="form-control" type="text" placeholder="Enter Verification Code" name="verification_code" id="verification_code" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="verify_code" id="verify_code" class="btn btn-lg btn-primary">Submit</button>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            Didn't get the code yet? <a href="javascript:void();" onclick="sendOTP('web');">Request</a> another one.
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Forgot Password?</span>
                <a href="<?php echo base_url(); ?>forgot_password" class="btn btn-success btn-sm pull-right">Forgot Password</a>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Bulk SMS Service Providers Reset Password -->
<?php if (isset($form) && $form == "web_reset") { ?>
    <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="http://bulksmsserviceproviders.com/" class="btn-out pull-right"><i class="ion-ios-arrow-thin-left"></i> Go to Website</a>
                <h3 class="panel-title" id="actionHeading">Reset Password</h3>
            </div>
            <div class="panel-body">
                <form role="form" class="form-horizontal" id="resetPasswordFrom" method='post' action="javascript:webResetPassword();">
                    <div class="row">
                        <div class="col-md-12" id="show_message">
                            <div id="error" class="alert alert-danger danger <?php echo (isset($type) && $type == '2') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '2') ? $message : ""; ?></div>
                            <div id="success" class="alert alert-success success <?php echo (isset($type) && $type == '1') ? "" : "hidden"; ?>">
                                <?php echo (isset($type) && $type == '1') ? $message : ""; ?></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-locked"></i></span>
                                <input class="form-control" type="password" placeholder="Enter New Password" name="new_password" id="new_password" />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-locked"></i></span>
                                <input class="form-control" type="password" placeholder="Enter Confirm Password" name="confirm_password" id="confirm_password" />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <button type="submit" name="reset_password" id="reset_password" class="btn btn-lg btn-primary">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer"><span>Back to Forgot Password?</span>
                <a href="<?php echo base_url(); ?>forgot_password" class="btn btn-success btn-sm pull-right">Forgot Password</a>
            </div>
        </div>
    <?php } ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/validation.js"></script>