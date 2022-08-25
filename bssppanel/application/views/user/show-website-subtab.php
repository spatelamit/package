<input type="hidden" name="msg_type" id="msg_type1" value="<?php echo (isset($msg_type)) ? $msg_type : ""; ?>" />
<input type="hidden" name="msg_data" id="msg_data1" value="<?php echo (isset($msg_data)) ? $msg_data : ""; ?>" />
<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'banner') ? "active" : ""; ?>">
    <div id="web-banner">
        <h2 class="content-header-title">Banner</h2>
        <form id="bannerForm" action="javascript:saveWebsiteData('banner');" method="post" enctype="multipart/form-data" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Logo (Formats: .jpg, .png, .gif; Max size: 50KB)</label>
                </div>
                <?php
                if (isset($website) && $website->company_logo) {
                    ?>
                    <div class="form-group col-md-12">
                        <a href="javascript:void(0)" onclick="deleteWebData('logo', <?php echo $website_id; ?>, '0', '<?php echo $website->company_logo; ?>');" title="Delete Company Logo">
                            <img style="max-height: 65px;min-height: 24px;" src="<?php echo base_url(); ?>Website_Data/Logos/<?php echo $website->company_logo; ?>" />
                        </a>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group col-md-8">
                    <input type="hidden" name="company_logo_name" id="company_logo_name"  value="<?php echo (isset($website) && $website->company_logo) ? $website->company_logo : ""; ?>" />
                    <input type="file"  class="upload_files" name="company_logo" id="company_logo" accept="image/gif, image/jpeg, image/png" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Banner Text (Max 100 characters)</label>
                    <textarea name="banner_text" maxlength="100" id="banner_text" class="form-control" placeholder="Enter Banner Text"><?php echo (isset($website) && $website->banner_text) ? $website->banner_text : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="banner1">
                        <input type="radio" name="banner" id="banner1" value="1" <?php echo (isset($website) && $website->website_banner == 1) ? "checked" : ""; ?> /> Banner 1
                    </label>
                </div>
                <div class="form-group col-md-3">
                    <label for="banner2">
                        <input type="radio" name="banner" id="banner2" value="2" <?php echo (isset($website) && $website->website_banner == 2) ? "checked" : ""; ?> /> Banner 2
                    </label>
                </div>
                <div class="form-group col-md-3">
                    <label for="banner3">
                        <input type="radio" name="banner" id="banner3" value="3" <?php echo (isset($website) && $website->website_banner == 3) ? "checked" : ""; ?> /> Banner 3
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-7 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="banner" />
                    <button type="submit" name="save_banner" class="btn btn-primary"
                            id="btnbanner" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'services') ? "active" : ""; ?>">
    <div id="web-feature">
        <h2 class="content-header-title">Services</h2>
        <form id="serviceForm" action="javascript:saveWebsiteData('services');" method="post" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-12">
                    <label><strong>Reseller</strong> (Max 600 characters)</label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <textarea name="service_text1" maxlength="600" id="service_text1" class="form-control" 
                              placeholder="Enter Service" rows="3"><?php echo (isset($website) && $website->website_service1) ? $website->website_service1 : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label><strong>White Label</strong> (Max 600 characters)</label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <textarea name="service_text2" maxlength="600" id="service_text2" class="form-control" 
                              placeholder="Enter Service" rows="3"><?php echo (isset($website) && $website->website_service2) ? $website->website_service2 : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label><strong>HTTP API</strong> (Max 600 characters)</label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <textarea name="service_text3" maxlength="600" id="service_text3" class="form-control"
                              placeholder="Enter Service" rows="3"><?php echo (isset($website) && $website->website_service3) ? $website->website_service3 : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="services" />
                    <button type="submit" name="save_features" class="btn btn-primary"
                            id="btnservices" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'payment') ? "active" : ""; ?>">
    <div id="web-banner">
        <h2 class="content-header-title">Payment Gateway</h2>
        hello
    </div>
</div>


<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'about_us') ? "active" : ""; ?>">
    <div id="web-about">
        <h2 class="content-header-title">About Us</h2>
        <form id="aboutForm" action="javascript:saveWebsiteData('about_us');" enctype="multipart/form-data" method="post" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-12">
                    <label>About Us Contents (Max 1200 characters)</label>
                    <textarea rows="8" maxlength="1200" name="about_us_text" id="about_us_text" class="form-control" 
                              placeholder="Enter About Us Text"><?php echo (isset($website) && $website->website_about_contents) ? $website->website_about_contents : ""; ?></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>About Us Image (Formats: .jpg, .png, .gif; Max size: 100KB)</label>
                </div>
                <?php
                if (isset($website) && $website->website_about_image) {
                    ?>
                    <div class="form-group col-md-12">
                        <a href="javascript:void(0)" onclick="deleteWebData('about', <?php echo $website_id; ?>, '0', '<?php echo $website->website_about_image; ?>');" title="Delete About Us Image">
                            <img style="max-height: 65px;min-height: 24px;" src="<?php echo base_url(); ?>Website_Data/About/<?php echo $website->website_about_image; ?>" />
                        </a>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group col-md-8">
                    <input type="hidden" name="about_us_image_name" id="about_us_image_name" value="<?php echo (isset($website) && $website->website_about_image) ? $website->website_about_image : ""; ?>" />
                    <input type="file"  class="upload_files" name="about_us_image" id="about_us_image" accept="image/gif, image/jpeg, image/png" />
                </div>
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="about_us" />
                    <button type="submit" name="save_about_us" class="btn btn-primary"
                            id="btnabout_us" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div> 
</div>

<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'contact_us') ? "active" : ""; ?>">
    <div id="web-contact">
        <h2 class="content-header-title">Contact Us</h2>
        <form id="contactForm" action="javascript:saveWebsiteData('contact_us');" method="post" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="reciever_email">Receiver Email</label>
                    <input type="text" name="reciever_email" id="reciever_email" class="form-control" placeholder="Enter Receiver Email"
                           value="<?php echo (isset($website) && $website->reciever_email) ? $website->reciever_email : ""; ?>" 
                           data-parsley-type="email" data-parsley-error-message="Please Enter Valid Email Address" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Contact Number</label>
                    <input type="text" name="website_contact" id="website_contact" class="form-control" placeholder="Enter Contact Number"
                           value="<?php echo (isset($website) && $website->website_contacts) ? $website->website_contacts : ""; ?>"
                           data-parsley-type="integer" data-parsley-error-message="Please Enter Valid Contact Number"
                           data-parsley-minlength="10" data-parsley-maxlength="10" />
                </div>
                <div class="form-group col-md-6">
                    <label>Email Address</label>
                    <input type="text" name="website_email" id="website_email" class="form-control" placeholder="Enter Email Address"
                           value="<?php echo (isset($website) && $website->website_emails) ? $website->website_emails : ""; ?>"
                           data-parsley-type="email" data-parsley-error-message="Please Enter Valid Email Address" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Address</label>
                    <textarea name="website_address" id="website_address" class="form-control" rows="2" placeholder="Enter Address"><?php echo (isset($website) && $website->website_addresses) ? $website->website_addresses : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>City</label>
                    <input type="text" name="website_city" id="website_city" class="form-control" placeholder="Enter City"
                           value="<?php echo (isset($website) && $website->website_cities) ? $website->website_cities : ""; ?>" />
                </div>
                <div class="form-group col-md-6">
                    <label>Zipcode</label>
                    <input type="text" name="website_zipcode" id="website_zipcode" class="form-control" placeholder="Enter Zipcode"
                           value="<?php echo (isset($website) && $website->website_zipcodes) ? $website->website_zipcodes : ""; ?>"
                           data-parsley-type="integer" data-parsley-error-message="Please Enter Valid Zipcode"
                           data-parsley-minlength="6" data-parsley-maxlength="6"  />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Country</label>
                    <select name="website_country" id="website_country" class="form-control">
                        <option value="">Select Country</option>
                        <option value="Afganistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
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
                        <option value="Bonaire">Bonaire</option>
                        <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Brazil">Brazil</option>
                        <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                        <option value="Brunei">Brunei</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Canary Islands">Canary Islands</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Channel Islands">Channel Islands</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cocos Island">Cocos Island</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Cote DIvoire">Cote D'Ivoire</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Curaco">Curacao</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="East Timor">East Timor</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Falkland Islands">Falkland Islands</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="French Southern Ter">French Southern Ter</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Great Britain">Great Britain</option>
                        <option value="Greece">Greece</option>
                        <option value="Greenland">Greenland</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Hawaii">Hawaii</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hong Kong">Hong Kong</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Isle of Man">Isle of Man</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Korea North">Korea North</option>
                        <option value="Korea Sout">Korea South</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Laos">Laos</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Macau">Macau</option>
                        <option value="Macedonia">Macedonia</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Midway Islands">Midway Islands</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Nambia">Nambia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherland Antilles">Netherland Antilles</option>
                        <option value="Netherlands">Netherlands (Holland, Europe)</option>
                        <option value="Nevis">Nevis</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Niue">Niue</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau Island">Palau Island</option>
                        <option value="Palestine">Palestine</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Phillipines">Philippines</option>
                        <option value="Pitcairn Island">Pitcairn Island</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Republic of Montenegro">Republic of Montenegro</option>
                        <option value="Republic of Serbia">Republic of Serbia</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="St Barthelemy">St Barthelemy</option>
                        <option value="St Eustatius">St Eustatius</option>
                        <option value="St Helena">St Helena</option>
                        <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                        <option value="St Lucia">St Lucia</option>
                        <option value="St Maarten">St Maarten</option>
                        <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                        <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
                        <option value="Saipan">Saipan</option>
                        <option value="Samoa">Samoa</option>
                        <option value="Samoa American">Samoa American</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syria</option>
                        <option value="Tahiti">Tahiti</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Erimates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States of America">United States of America</option>
                        <option value="Uraguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City State">Vatican City State</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                        <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                        <option value="Wake Island">Wake Island</option>
                        <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Zaire">Zaire</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="contact_us" />
                    <button type="submit" name="save_contact_us" class="btn btn-primary"
                            id="btncontact_us" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div> 
</div>

<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'social') ? "active" : ""; ?>">
    <div id="web-social">
        <h2 class="content-header-title">Social Links</h2> 
        <form id="socialForm" action="javascript:saveWebsiteData('social');" method="post" class="tab-forms">
            <?php
            $social_link_array = array('', '', '', '');
            if (isset($website) && $website->website_social_links) {
                $social_link_array = explode('|', $website->website_social_links);
            }
            ?>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Facebook</label>
                    <input type="text" name="facebook_link" id="facebook_link" class="form-control" placeholder="Enter Facebook Page Link"
                           value="<?php echo ($social_link_array[0] != "") ? $social_link_array[0] : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Twitter</label>
                    <input type="text" name="twitter_link" id="twitter_link" class="form-control" placeholder="Enter Twitter Page Link"
                           value="<?php echo ($social_link_array[1] != "") ? $social_link_array[1] : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>LinkedIn</label>
                    <input type="text" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="Enter LinkedIn Page Link"
                           value="<?php echo ($social_link_array[2] != "") ? $social_link_array[2] : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Google Plus</label>
                    <input type="text" name="google_plus_link" id="google_plus_link" class="form-control" placeholder="Enter Google Plus Page Link"
                           value="<?php echo ($social_link_array[3] != "") ? $social_link_array[3] : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="social" />
                    <button type="submit" name="save_social_links" class="btn btn-primary"
                            id="btnsocial" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'seo') ? "active" : ""; ?>">
    <div id="web-seo">
        <h2 class="content-header-title">SEO</h2>
        <form id="seoForm" action="javascript:saveWebsiteData('seo');" method="post" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Google Analytic ID</label>
                    <input type="text" name="google_analytic_id" id="google_analytic_id" class="form-control" placeholder="Enter Google Analytic ID"
                           value="<?php echo (isset($website) && $website->google_analytic_id) ? $website->google_analytic_id : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Enter Meta Title"
                           value="<?php echo (isset($website) && $website->meta_title) ? $website->meta_title : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Meta Description</label>
                    <textarea name="meta_desc" id="meta_desc" class="form-control" placeholder="Enter Meta Description"><?php echo (isset($website) && $website->meta_desc) ? $website->meta_desc : ""; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <label>Meta Keywords</label>
                </div>
            </div>
            <?php
            $keyword_array = array('', '', '', '');
            if (isset($website) && $website->meta_keywords) {
                $keyword_array = explode('|', $website->meta_keywords);
            }
            $sno = 0;
            for ($j = 1; $j <= 4; $j++) {
                if ($j == 1 || $j == 3) {
                    ?>
                    <div class="row">
                        <?php
                    }
                    ?>
                    <div class="form-group col-md-4">
                        <input type="text" name="keyword<?php echo $j; ?>" id="keyword<?php echo $j; ?>" class="form-control" placeholder="Enter Keyword" 
                               value="<?php echo $keyword_array[$sno]; ?>" />
                    </div>
                    <?php
                    if ($j == 2 || $j == 4) {
                        ?>
                    </div>
                    <?php
                }
                ?>
                <?php
                $sno++;
            }
            ?>
            <div class="row">
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="seo" />
                    <button type="submit" name="save_seo" class="btn btn-primary"
                            id="btnseo" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bhoechie-tab-content <?php echo (isset($website_tab) && $website_tab == 'other') ? "active" : ""; ?>">
    <div id="web-other">
        <h2 class="content-header-title">Other</h2>
        <form id="otherForm" action="javascript:saveWebsiteData('other');" method="post" class="tab-forms">
            <div class="row">
                <div class="form-group col-md-7">
                    <label>External URL Text (Home, Back or Go to google, max 24 characters)</label>
                    <input type="text" name="external_url_text" id="external_url_text" class="form-control" placeholder="Enter External URL Text"
                           value="<?php echo (isset($website) && $website->external_url_text) ? $website->external_url_text : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-7">
                    <label>External URL (External web url or home url)</label>
                    <input type="text" name="external_url_link" id="external_url_link" class="form-control" placeholder="Enter External URL Link"
                           value="<?php echo (isset($website) && $website->external_url_link) ? $website->external_url_link : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Notification e.g. offer, notification, alerts etc.</label>
                    <input type="text" name="notification" id="notification" class="form-control" placeholder="Enter Notification"
                           value="<?php echo (isset($website) && $website->website_notification) ? $website->website_notification : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="theme_color">Theme Color</label>
                    <input type="text" name="theme_color" id="theme_color" class="form-control" style="background-color: <?php echo (isset($website) && $website->website_theme_color) ? $website->website_theme_color : ""; ?>"
                           value="<?php echo (isset($website) && $website->website_theme_color) ? $website->website_theme_color : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Author Name</label>
                    <input type="text" name="author_name" id="author_name" class="form-control" placeholder="Enter Author Name"
                           value="<?php echo (isset($website) && $website->website_author) ? $website->website_author : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-8">
                    <label>Author Email Address</label>
                    <input type="text" name="author_email" id="author_email" class="form-control" placeholder="Enter Author Email Address"
                           value="<?php echo (isset($website) && $website->website_email) ? $website->website_email : ""; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12 mt5">
                    <input type="hidden" name="website_id" id="website_id" value="<?php echo $website_id; ?>" />
                    <input type="hidden" name="tab" id="tab" value="other" />
                    <button type="submit" name="save_other" class="btn btn-primary"
                            id="btnother" data-loading-text="Processing..." autocomplete="off">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.portlet>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.portlet>div.bhoechie-tab-content").eq(index).addClass("active");
        });
    });

    $(":file").filestyle();

    $('#theme_color').colorpicker({
        color: false,
        format: 'hex',
        input: 'input'
    });
</script>