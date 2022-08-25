<?php
$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
?>
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'general') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/account_settings/general">General Setting</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'personal') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/account_settings/personal">Personal Setting</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'other') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/account_settings/other">Other Setting</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'panel') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/account_settings/panel">Panel Setting</a>
            </li>
        </ul>
    </div>
</div>
</div>
<div class="container">
    <?php
    // General Settings
    if (isset($tab) && $tab == 'general') {
        ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">Account Setting</h2>
                    <div class="portlet-content">
                        <?php
                        $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                        echo form_open('user/save_account_settings', $data);
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" disabled="disabled" 
                                       value="<?php echo (isset($username) && $username) ? $username : ""; ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Please Enter Full Name" required=""
                                       data-parsley-pattern="^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$" 
                                       value="<?php echo (isset($user_info) && $user_info['name']) ? $user_info['name'] : ""; ?>"
                                       data-parsley-pattern-message="Please Enter First And Last Name" data-parsley-required-message="Please Enter Full Name" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="contact_number">Mobile Number</label>
                                <input name="contact_number" id="contact_number" placeholder="Enter Contact Number" 
                                       value="<?php echo (isset($user_info) && $user_info['contact_number']) ? $user_info['contact_number'] : ""; ?>"
                                       class="form-control" type="text" data-parsley-required-message="Please Enter Contact Number" data-parsley-type-message="Please Enter Valid Contact Number"
                                       data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="10" required=""
                                       data-parsley-minlength-message="Please Enter Valid Contact Number" data-parsley-maxlength-message="Please Enter Valid Contact Number">
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="email_id">Email Address</label>
                                <input type="text" class="form-control" name="email_id" id="email_id" data-parsley-type="email" 
                                       placeholder="Please Enter Email Address" required="" 
                                       value="<?php echo (isset($user_info) && $user_info['email_address']) ? $user_info['email_address'] : ""; ?>"
                                       data-parsley-type-message="Please Enter Valid Email Address" data-parsley-required-message="Please Enter Email Address" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="company">Company</label>
                                <input type="text" class="form-control" name="company" id="company" placeholder="Please Enter Company" required=""
                                       value="<?php echo (isset($user_info) && $user_info['company_name']) ? $user_info['company_name'] : ""; ?>"
                                       data-parsley-required-message="Please Enter Full Name" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="gst">GST NO</label>
                                <input type="text" class="form-control" name="gst" id="company" placeholder="Please Enter GST no" 
                                       value="<?php echo (isset($user_info) && $user_info['gst_no']) ? $user_info['gst_no'] : ""; ?>"
                                       data-parsley-required-message="Please Enter GST No" />
                            </div>
                            
                        </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }
    // Personal Settings
    if (isset($tab) && $tab == 'personal') {
        ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">Personal Setting</h2>
                    <div class="portlet-content">
                        <?php
                        $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                        echo form_open('user/save_personal_settings', $data);
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="date_of_birth">Date Of Birth</label>
                                <input type="text" name="date_of_birth" id="date_of_birth" placeholder="Enter Date of Birth" 
                                       value="<?php echo (isset($user_info) && $user_info['date_of_birth']) ? $user_info['date_of_birth'] : ""; ?>"
                                       data-parsley-error-message="Please Enter Your Date of Birth" class="form-control" required="" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" rows="3" cols="23" placeholder="Enter Address" required="" 
                                          data-parsley-error-message="Please Enter Your Address" class="form-control"><?php echo (isset($user_info) && $user_info['address']) ? $user_info['address'] : ""; ?></textarea>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" placeholder="Enter City" 
                                       value="<?php echo (isset($user_info) && $user_info['city']) ? $user_info['city'] : ""; ?>"
                                       data-parsley-error-message="Please Enter Your City" class="form-control" required="" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="zipcode">Zipcode</label>
                                <input type="text" name="zipcode" id="zipcode" placeholder="Enter Zipcode" 
                                       value="<?php echo (isset($user_info) && $user_info['zipcode']) ? $user_info['zipcode'] : ""; ?>"
                                       data-parsley-error-message="Please Enter Your Zipcode" class="form-control" required="" />
                            </div>
                          
                            <div class="form-group col-md-12 padding0">
                                <label for="state">State</label>

                                <select name="state" id="state" class="form-control" required="" data-parsley-error-message="Please Select Your State">

                                    <?php
                                    if ($user_info['state'] != NULL) {
                                        ?>
                                        <option selected="" value="<?php echo $user_info['state'] ?>" selected=""><?php echo $user_info['state'] ?></option>
                                        <?php
                                    }
                                    ?>


                                    <option>Select State</option>
                                    <option value="Madhya Pradesh" >Madhya Pradesh</option>
                                    <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                    <option value="Andhra Pradesh">Andhra Pradesh</option>
                                    <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Chandigarh">Chandigarh</option>
                                    <option value="Chhattisgarh">Chhattisgarh</option>
                                    <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                    <option value="Daman and Diu">Daman and Diu</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Goa">Goa</option>
                                    <option value="Gujarat">Gujarat</option>
                                    <option value="Haryana">Haryana</option>
                                    <option value="Himachal Pradesh">Himachal Pradesh</option>
                                    <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                    <option value="Jharkhand">Jharkhand</option>
                                    <option value="Karnataka">Karnataka</option>
                                    <option value="Kerala">Kerala</option>
                                    <option value="Lakshadweep">Lakshadweep</option>
                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Manipur">Manipur</option>
                                    <option value="Meghalaya">Meghalaya</option>
                                    <option value="Mizoram">Mizoram</option>
                                    <option value="Nagaland">Nagaland</option>
                                    <option value="Orissa">Orissa</option>
                                    <option value="Pondicherry">Pondicherry</option>
                                    <option value="Punjab">Punjab</option>
                                    <option value="Rajasthan">Rajasthan</option>
                                    <option value="Sikkim">Sikkim</option>
                                    <option value="Tamil Nadu">Tamil Nadu</option>
                                    <option value="Tripura">Tripura</option>
                                    <option value="Uttaranchal">Uttaranchal</option>
                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="West Bengal">West Bengal</option>

                                </select>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control" required="" data-parsley-error-message="Please Select Your Country">
                                    <option value="">Select Country</option>
                                    <?php
                                    if (isset($countries) && $countries) {
                                        foreach ($countries as $key => $country_name) {
                                            if (isset($user_info) && $country_name == $user_info['country']) {
                                                ?>
                                                <option value="<?php echo $country_name; ?>" selected=""><?php echo $country_name; ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value="<?php echo $country_name; ?>"><?php echo $country_name; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>                                    
                                </select>
                            </div>
                            
                        </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }
    // Other Settings
    if (isset($tab) && $tab == 'other') {
        ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">Other Setting</h2>
                    <div class="portlet-content">
                        <?php
                        $data = array('onsubmit' => "return validateOtherSetting()", 'id' => "validate-basic", 'class' => "form parsley-form");
                        echo form_open('user/save_other_settings', $data);
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="default_sender_id">Default Sender Id</label>
                                <input type="text" name="default_sender_id" id="default_sender_id" placeholder="Enter Sender Id" 
                                       value="<?php echo (isset($user_info) && $user_info['default_sender_id']) ? $user_info['default_sender_id'] : ""; ?>"
                                       class="form-control" required="" data-parsley-error-message="Please Enter Your Sender Id" data-parsley-minlength="6"  
                                       data-parsley-maxlength="6" placeholder="Sender Id" parsley-minlength-message="Sender Id Must be of 6 Character Long!" 
                                       parsley-maxlength-message="Sender Id Should be of 6 Character Long!" />
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="user_industry">Industry</label>
                                <select id="user_industry" name="user_industry" data-parsley-error-message="Please Select Your Industry" class="form-control" required="">
                                    <option value="" <?php echo (isset($user_info) && $user_info['industry'] == "") ? 'selected="selected"' : '' ?>>Select Industry</option>
                                    <option value="Agriculture " <?php echo (isset($user_info) && $user_info['industry'] == "Agriculture") ? 'selected="selected"' : '' ?>>Agriculture </option>
                                    <option value="Automobile & Transport" <?php echo (isset($user_info) && $user_info['industry'] == "Automobile & Transport") ? 'selected="selected"' : '' ?>>Automobile & Transport</option>
                                    <option value="Ecommerce" <?php echo (isset($user_info) && $user_info['industry'] == "Ecommerce") ? 'selected="selected"' : '' ?>>E-Commerce</option>
                                    <option value="Education" <?php echo (isset($user_info) && $user_info['industry'] == "Education") ? 'selected="selected"' : '' ?>>Education</option>
                                    <option value="Financial Institution" <?php echo (isset($user_info) && $user_info['industry'] == "Financial Institution") ? 'selected="selected"' : '' ?>>Financial Institution</option>
                                    <option value="Gym" <?php echo (isset($user_info) && $user_info['industry'] == "Gym") ? 'selected="selected"' : '' ?>>Gym</option>
                                    <option value="Hospitality" <?php echo (isset($user_info) && $user_info['industry'] == "Hospitality") ? 'selected="selected"' : '' ?>>Hospitality</option>
                                    <option value="IT Company" <?php echo (isset($user_info) && $user_info['industry'] == "IT Company") ? 'selected="selected"' : '' ?>>IT Company</option>
                                    <option value="Lifestyle Clubs" <?php echo (isset($user_info) && $user_info['industry'] == "Lifestyle Clubs") ? 'selected="selected"' : '' ?>>Lifestyle Clubs</option>
                                    <option value="Logistics" <?php echo (isset($user_info) && $user_info['industry'] == "Logistics") ? 'selected="selected"' : '' ?>>Logistics</option>
                                    <option value="Marriage Bureau" <?php echo (isset($user_info) && $user_info['industry'] == "Marriage Bureau") ? 'selected="selected"' : '' ?>>Marriage Bureau</option>
                                    <option value="Media & Advertisement" <?php echo (isset($user_info) && $user_info['industry'] == "Media & Advertisement") ? 'selected="selected"' : '' ?>>Media & Advertisement</option>
                                    <option value="Personal Use" <?php echo (isset($user_info) && $user_info['industry'] == "Personal Use") ? 'selected="selected"' : '' ?>>Personal Use</option>
                                    <option value="Political" <?php echo (isset($user_info) && $user_info['industry'] == "Political") ? 'selected="selected"' : '' ?>>Political </option>
                                    <option value="Public Sector" <?php echo (isset($user_info) && $user_info['industry'] == "Public Sector") ? 'selected="selected"' : '' ?>>Public Sector</option>
                                    <option value="Real estate" <?php echo (isset($user_info) && $user_info['industry'] == "Real estate") ? 'selected="selected"' : '' ?>>Real estate</option>
                                    <option value="Reseller" <?php echo (isset($user_info) && $user_info['industry'] == "Reseller") ? 'selected="selected"' : '' ?>>Reseller</option>
                                    <option value="Retail & FMCG" <?php echo (isset($user_info) && $user_info['industry'] == "Retail & FMCG") ? 'selected="selected"' : '' ?>>Retail & FMCG</option>
                                    <option value="Stock and Commodity" <?php echo (isset($user_info) && $user_info['industry'] == "Stock and Commodity") ? 'selected="selected"' : '' ?>>Stock and Commodity</option>
                                    <option value="Telecom" <?php echo (isset($user_info) && $user_info['industry'] == "Telecom") ? 'selected="selected"' : '' ?>>Telecom</option>
                                    <option value="Tips And Alert" <?php echo (isset($user_info) && $user_info['industry'] == "Tips And Alert") ? 'selected="selected"' : '' ?>>Tips And Alert</option>
                                    <option value="Travel" <?php echo (isset($user_info) && $user_info['industry'] == "Travel") ? 'selected="selected"' : '' ?>>Travel</option>
                                    <option value="Wholesalers Distributors or Manufacturers" <?php echo (isset($user_info) && $user_info['industry'] == "Wholesalers Distributors or Manufacturers") ? 'selected="selected"' : '' ?>>Wholesalers Distributors or Manufacturers</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 padding0">
                                <label for="timezone">Default Time Zone</label>
                                <select id="timezone" name="timezone" data-parsley-error-message="Please Select Your Time Zone" class="form-control" required="">
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "") ? 'selected="selected"' : '' ?> value="">Select Time Zone</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-11:00") ? 'selected="selected"' : '' ?> value="-11:00">(GMT-11:00) Midway Island, Samoa</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-10:00") ? 'selected="selected"' : '' ?> value="-10:00">(GMT-10:00) Hawaii-Aleutian</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-10:00") ? 'selected="selected"' : '' ?> value="-10:00">(GMT-10:00) Hawaii</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-09:30") ? 'selected="selected"' : '' ?> value="-09:30">(GMT-09:30) Marquesas Islands</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-09:00") ? 'selected="selected"' : '' ?> value="-09:00">(GMT-09:00) Gambier Islands</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-09:00") ? 'selected="selected"' : '' ?> value="-09:00">(GMT-09:00) Alaska</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-08:00") ? 'selected="selected"' : '' ?> value="-08:00">(GMT-08:00) Tijuana, Baja California</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-08:00") ? 'selected="selected"' : '' ?> value="-08:00">(GMT-08:00) Pitcairn Islands</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-08:00") ? 'selected="selected"' : '' ?> value="-08:00">(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-07:00") ? 'selected="selected"' : '' ?> value="-07:00">(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-07:00") ? 'selected="selected"' : '' ?> value="-07:00">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-07:00") ? 'selected="selected"' : '' ?> value="-07:00">(GMT-07:00) Arizona</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-06:00") ? 'selected="selected"' : '' ?> value="-06:00">(GMT-06:00) Saskatchewan, Central America</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-06:00") ? 'selected="selected"' : '' ?> value="-06:00">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-06:00") ? 'selected="selected"' : '' ?> value="-06:00">(GMT-06:00) Easter Island</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-06:00") ? 'selected="selected"' : '' ?> value="-06:00">(GMT-06:00) Central Time (US & Canada)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-05:00") ? 'selected="selected"' : '' ?> value="-05:00">(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-05:00") ? 'selected="selected"' : '' ?> value="-05:00">(GMT-05:00) Cuba</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-05:00") ? 'selected="selected"' : '' ?> value="-05:00">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:30") ? 'selected="selected"' : '' ?> value="-04:30">(GMT-04:30) Caracas</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) Santiago</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) La Paz</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) Faukland Islands</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) Brazil</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) Atlantic Time (Goose Bay)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-04:00") ? 'selected="selected"' : '' ?> value="-04:00">(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:30") ? 'selected="selected"' : '' ?> value="-03:30">(GMT-03:30) Newfoundland</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) UTC-3</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) Montevideo</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) Miquelon, St. Pierre</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) Greenland</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) Buenos Aires</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-03:00") ? 'selected="selected"' : '' ?> value="-03:00">(GMT-03:00) Brasilia</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-02:00") ? 'selected="selected"' : '' ?> value="-02:00">(GMT-02:00) Mid-Atlantic</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-01:00") ? 'selected="selected"' : '' ?> value="-01:00">(GMT-01:00) Cape Verde Is.</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-01:00") ? 'selected="selected"' : '' ?> value="-01:00">(GMT-01:00) Azores</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "-0:00") ? 'selected="selected"' : '' ?> value="+0:00">(GMT) Greenwich Mean Time : Belfast</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+0:00") ? 'selected="selected"' : '' ?> value="+0:00">(GMT) Greenwich Mean Time : Dublin</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+0:00") ? 'selected="selected"' : '' ?> value="+0:00">(GMT) Greenwich Mean Time : Lisbon</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+0:00") ? 'selected="selected"' : '' ?> value="+0:00">(GMT) Greenwich Mean Time : London</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+0:00") ? 'selected="selected"' : '' ?> value="+0:00">(GMT) Monrovia, Reykjavik</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+01:00") ? 'selected="selected"' : '' ?> value="+01:00">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+01:00") ? 'selected="selected"' : '' ?> value="+01:00">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+01:00") ? 'selected="selected"' : '' ?> value="+01:00">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+01:00") ? 'selected="selected"' : '' ?> value="+01:00">(GMT+01:00) West Central Africa</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+01:00") ? 'selected="selected"' : '' ?> value="+01:00">(GMT+01:00) Windhoek</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Beirut</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Cairo</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Gaza</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Harare, Pretoria</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Jerusalem</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Minsk</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+02:00") ? 'selected="selected"' : '' ?> value="+02:00">(GMT+02:00) Syria</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+03:00") ? 'selected="selected"' : '' ?> value="+03:00">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+03:00") ? 'selected="selected"' : '' ?> value="+03:00">(GMT+03:00) Nairobi</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+03:30") ? 'selected="selected"' : '' ?> value="+03:30">(GMT+03:30) Tehran</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+04:00") ? 'selected="selected"' : '' ?> value="+04:00">(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+04:00") ? 'selected="selected"' : '' ?> value="+04:00">(GMT+04:00) Yerevan</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+04:30") ? 'selected="selected"' : '' ?> value="+04:30">(GMT+04:30) Kabul</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+05:00") ? 'selected="selected"' : '' ?> value="+05:00">(GMT+05:00) Ekaterinburg</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+05:00") ? 'selected="selected"' : '' ?> value="+05:00">(GMT+05:00) Tashkent</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+05:30") ? 'selected="selected"' : '' ?> value="+05:30">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+05:45") ? 'selected="selected"' : '' ?> value="+05:45">(GMT+05:45) Kathmandu</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+06:00") ? 'selected="selected"' : '' ?> value="+06:00">(GMT+06:00) Astana, Dhaka</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+06:00") ? 'selected="selected"' : '' ?> value="+06:00">(GMT+06:00) Novosibirsk</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+06:30") ? 'selected="selected"' : '' ?> value="+06:30">(GMT+06:30) Yangon (Rangoon)</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+07:00") ? 'selected="selected"' : '' ?> value="+07:00">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+07:00") ? 'selected="selected"' : '' ?> value="+07:00">(GMT+07:00) Krasnoyarsk</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+08:00") ? 'selected="selected"' : '' ?> value="+08:00">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+08:00") ? 'selected="selected"' : '' ?> value="+08:00">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+08:00") ? 'selected="selected"' : '' ?> value="+08:00">(GMT+08:00) Perth</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+08:45") ? 'selected="selected"' : '' ?> value="+08:45">(GMT+08:45) Eucla</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+09:00") ? 'selected="selected"' : '' ?> value="+09:00">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+09:00") ? 'selected="selected"' : '' ?> value="+09:00">(GMT+09:00) Seoul</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+09:00") ? 'selected="selected"' : '' ?> value="+09:00">(GMT+09:00) Yakutsk</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+09:30") ? 'selected="selected"' : '' ?> value="+09:30">(GMT+09:30) Adelaide</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+09:30") ? 'selected="selected"' : '' ?> value="+09:30">(GMT+09:30) Darwin</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+10:00") ? 'selected="selected"' : '' ?> value="+10:00">(GMT+10:00) Brisbane</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+10:00") ? 'selected="selected"' : '' ?> value="+10:00">(GMT+10:00) Hobart</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+10:00") ? 'selected="selected"' : '' ?> value="+10:00">(GMT+10:00) Vladivostok</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+10:30") ? 'selected="selected"' : '' ?> value="+10:30">(GMT+10:30) Lord Howe Island</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+11:00") ? 'selected="selected"' : '' ?> value="+11:00">(GMT+11:00) Solomon Is., New Caledonia</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+11:00") ? 'selected="selected"' : '' ?> value="+11:00">(GMT+11:00) Magadan</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+11:30") ? 'selected="selected"' : '' ?> value="+11:30">(GMT+11:30) Norfolk Island</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+12:00") ? 'selected="selected"' : '' ?> value="+12:00">(GMT+12:00) Anadyr, Kamchatka</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+12:00") ? 'selected="selected"' : '' ?> value="+12:00">(GMT+12:00) Auckland, Wellington</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+12:00") ? 'selected="selected"' : '' ?> value="+12:00">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+12:45") ? 'selected="selected"' : '' ?> value="+12:45">(GMT+12:45) Chatham Islands</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+13:00") ? 'selected="selected"' : '' ?> value="+13:00">(GMT+13:00) Nuku'alofa</option>
                                    <option <?php echo (isset($user_info) && $user_info['default_timezone'] == "+14:00") ? 'selected="selected"' : '' ?> value="+14:00">(GMT+14:00) Kiritimati</option>
                                </select>
                            </div>
                         
                        </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }
    // Panel Settings
    if ($tab == 'panel') {
        ?>
        <div class="row">
            <div class="col-sm-3">
                <div class="portlet">
                    <h2 class="content-header-title">User Setting</h2>
                    <div class="portlet-content">
                        <?php
                        $data = array('id' => "validate-basic", 'class' => "form parsley-form");
                        echo form_open('user/save_setting', $data);
                        ?>
                        <div class="row">
                            <div class="form-group col-md-12 padding0">
                                <label for="setting" class="fancy-check">
                                    <input type="checkbox" name="setting" id="setting" value="1"
                                           <?php echo (isset($user_info['user_settings_col']) && $user_info['user_settings_col']) ? "checked" : ""; ?> />
                                    <span>
                                        Show Manage Users To Resellers (When Login As)
                                    </span>
                                </label>
                            </div>
                           
                        </div>
                        </form>
                    </div> 
                </div> 
            </div>
        </div>
        <?php
    }
    ?>
</div>
<!-- <div class="form-group col-md-12 mt5 padding0">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>-->