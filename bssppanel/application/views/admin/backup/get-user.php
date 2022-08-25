<?php
if ($user) {
    ?>
    <div class="col-md-4" style="border-right: 1px solid rgb(221, 221, 221);">
        <div class="col-md-12 padding0">
            <h4>
                <?php echo $user['username']; ?> 
                (<?php echo ($user['parent_username'] == "") ? $user['admin_username'] : $user['parent_username']; ?>)
                (<?php echo ($user['ref_username'] == "") ? $user['admin_username'] : $user['ref_username']; ?>)
            </h4> 
            <input type="hidden" id="user_id" value="<?php echo $user['user_id']; ?>" />
            <input type="hidden" id="user_type" value="<?php echo $user['utype']; ?>" />
            <input type="hidden" id="username" value="<?php echo $user['username']; ?>" />
        </div>
        <table class="table table-bordered bgf9">
            <thead>
                <tr>
                    <th colspan="2">
                        Profile Information 
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Name</td>
                    <td><?php echo $user['name']; ?></td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td><?php echo $user['email_address']; ?></td>
                </tr>
                <tr>
                    <td>Contact Number</td>
                    <td><?php echo $user['contact_number']; ?></td>
                </tr>
                <tr>
                    <td>User Type</td>
                    <td><?php echo $user['utype']; ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Individual Balance</strong>
                    </td>
                </tr>
                <tr>
                    <td>Promotional SMS</td>
                    <td><?php echo $user['pr_sms_balance']; ?> SMS</td>
                </tr>
                <tr>
                    <td>Transactional SMS</td>
                    <td><?php echo $user['tr_sms_balance']; ?> SMS</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <strong>Overall Balance</strong>
                    </td>
                </tr>
                <tr>
                    <td>Promotional SMS</td>
                    <td><?php echo $user['total_pr_balance']; ?> SMS</td>
                </tr>
                <tr>
                    <td>Transactional SMS</td>
                    <td><?php echo $user['total_tr_balance']; ?> SMS</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><?php echo ($user['user_status'] == 1) ? "Activated" : "Deactivated"; ?></td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12 padding0">
            <button class="btn btn-primary" data-toggle="modal" data-target="#update_user">Update</button>

            <div class="modal" id="update_user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                                <span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil-square-o"></i> Update User Info</h4>
                        </div>
                        <form id="validate-basic" data-parsley-validate onsubmit="/*saveUserInfo();*/" method='post' action="javascript:updateUserInfo('user');">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Enter Full Name" value="<?php echo $user['name']; ?>"
                                                   required="" data-parsley-error-message="Please Enter Full Name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email_address">Email Address</label>
                                            <input type="email" class="form-control" id="email_address" placeholder="Enter Email Address" value="<?php echo $user['email_address']; ?>"
                                                   data-parsley-type="email" required="" data-parsley-error-message="Please Enter Valid Email Address" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="contact_number">Contact Number</label>
                                            <input type="text" disabled="" class="form-control" id="contact_number" placeholder="Enter Contact Number" value="<?php echo $user['contact_number']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="dob">Date Of Birth</label>
                                            <input type="text" class="form-control" id="dob" placeholder="Enter Date Of Birth" value="<?php echo $user['date_of_birth']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" placeholder="Enter City" value="<?php echo $user['city']; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <select name="country" id="country" class="form-control">
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
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="zipcode">Zipcode</label>
                                            <input type="text" class="form-control" id="zipcode" placeholder="Enter Zipcode" value="<?php echo $user['zipcode']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="company">Company</label>
                                            <input type="text" class="form-control" id="company" placeholder="Enter Company" value="<?php echo $user['company_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="industry">Industry</label>
                                            <select id="industry" name="industry" class="form-control">
                                                <option value="" <?php echo ($user['industry'] == "") ? 'selected="selected"' : '' ?>>Select Industry</option>
                                                <option value="Agriculture " <?php echo ($user['industry'] == "Agriculture") ? 'selected="selected"' : '' ?>>Agriculture </option>
                                                <option value="Automobile & Transport" <?php echo ($user['industry'] == "Automobile & Transport") ? 'selected="selected"' : '' ?>>Automobile & Transport</option>
                                                <option value="Ecommerce" <?php echo ($user['industry'] == "Ecommerce") ? 'selected="selected"' : '' ?>>E-Commerce</option>
                                                <option value="Education" <?php echo ($user['industry'] == "Education") ? 'selected="selected"' : '' ?>>Education</option>
                                                <option value="Financial Institution" <?php echo ($user['industry'] == "Financial Institution") ? 'selected="selected"' : '' ?>>Financial Institution</option>
                                                <option value="Gym" <?php echo ($user['industry'] == "Gym") ? 'selected="selected"' : '' ?>>Gym</option>
                                                <option value="Hospitality" <?php echo ($user['industry'] == "Hospitality") ? 'selected="selected"' : '' ?>>Hospitality</option>
                                                <option value="IT Company" <?php echo ($user['industry'] == "IT Company") ? 'selected="selected"' : '' ?>>IT Company</option>
                                                <option value="Lifestyle Clubs" <?php echo ($user['industry'] == "Lifestyle Clubs") ? 'selected="selected"' : '' ?>>Lifestyle Clubs</option>
                                                <option value="Logistics" <?php echo ($user['industry'] == "Logistics") ? 'selected="selected"' : '' ?>>Logistics</option>
                                                <option value="Marriage Bureau" <?php echo ($user['industry'] == "Marriage Bureau") ? 'selected="selected"' : '' ?>>Marriage Bureau</option>
                                                <option value="Media & Advertisement" <?php echo ($user['industry'] == "Media & Advertisement") ? 'selected="selected"' : '' ?>>Media & Advertisement</option>
                                                <option value="Personal Use" <?php echo ($user['industry'] == "Personal Use") ? 'selected="selected"' : '' ?>>Personal Use</option>
                                                <option value="Political" <?php echo ($user['industry'] == "Political") ? 'selected="selected"' : '' ?>>Political </option>
                                                <option value="Public Sector" <?php echo ($user['industry'] == "Public Sector") ? 'selected="selected"' : '' ?>>Public Sector</option>
                                                <option value="Real estate" <?php echo ($user['industry'] == "Real estate") ? 'selected="selected"' : '' ?>>Real estate</option>
                                                <option value="Reseller" <?php echo ($user['industry'] == "Reseller") ? 'selected="selected"' : '' ?>>Reseller</option>
                                                <option value="Retail & FMCG" <?php echo ($user['industry'] == "Retail & FMCG") ? 'selected="selected"' : '' ?>>Retail & FMCG</option>
                                                <option value="Stock and Commodity" <?php echo ($user['industry'] == "Stock and Commodity") ? 'selected="selected"' : '' ?>>Stock and Commodity</option>
                                                <option value="Telecom" <?php echo ($user['industry'] == "Telecom") ? 'selected="selected"' : '' ?>>Telecom</option>
                                                <option value="Tips And Alert" <?php echo ($user['industry'] == "Tips And Alert") ? 'selected="selected"' : '' ?>>Tips And Alert</option>
                                                <option value="Travel" <?php echo ($user['industry'] == "Travel") ? 'selected="selected"' : '' ?>>Travel</option>
                                                <option value="Wholesalers Distributors or Manufacturers" <?php echo ($user['industry'] == "Wholesalers Distributors or Manufacturers") ? 'selected="selected"' : '' ?>>Wholesalers Distributors or Manufacturers</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" id="address" placeholder="Enter Address"><?php echo $user['address']; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <button class="btn btn-danger" type="button" onclick="deleteItem('user');">Delete</button>
            <button class="btn btn-<?php echo ($user['user_status'] == 0) ? "success" : "warning"; ?>" type="button" 
                    onclick="enableDisableItem('user', <?php echo ($user['user_status'] == 0) ? "1" : "0"; ?>);">
                        <?php echo ($user['user_status'] == 0) ? "Active" : "Deactive"; ?>
            </button>
            <button class="btn btn-success" type="button" onclick="deleteItem('user');">Login As</button>
        </div>
    </div>

    <div class="col-md-8 padding0" id="user_tab">
        <!-- <a href="#" class="btn btn-warning" id="user-set-btn">Go to User Settings</a> -->

        <?php if ($user_tab == 1) { ?>

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed radius0" data-toggle="collapse" data-target="#user-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="user-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="<?php echo (isset($user_tab) && $user_tab == 1) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '1');">SMPP Routing</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 2) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '2');">Rule Setting</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 3) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '3');">User Set Ratio</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 4) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '4');">Set Expiry</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 5) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '5');">Funds</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 6) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '6');">Transaction Logs</a>
                            </li>
                            <li class="<?php echo (isset($user_tab) && $user_tab == 7) ? "active" : ""; ?>">
                                <a href="javascript:void(0)" onclick="getUserTab('user', '7');">Reset Password</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- User SMPP Routing -->
            <div class="col-md-7">
                <form role="form" class="tab-forms" id="validate-basic" data-parsley-validate method='post' action="javascript:saveUserInfo('user', <?php echo $user_tab; ?>);">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <label>Promotional Route</label>
                                </td>
                                <td>
                                    <select class="form-control" name="pr_route" id="pr_route" required="" data-parsley-error-message="Please Select Promotional User Group">
                                        <option value="">Select User Group</option>
                                        <?php
                                        if ($pr_user_groups) {
                                            foreach ($pr_user_groups as $pr_ugroup) {
                                                if ($user['pro_user_group_id'] == $pr_ugroup['user_group_id']) {
                                                    ?>
                                                    <option value="<?php echo $pr_ugroup['user_group_id']; ?>" selected=""><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>    
                                                    <?php
                                                } else {
                                                    ?>  
                                                    <option value="<?php echo $pr_ugroup['user_group_id']; ?>"><?php echo $pr_ugroup['user_group_name'] . " [" . $pr_ugroup['smsc_id'] . "]"; ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Transactional Route</label>
                                </td>
                                <td>
                                    <select class="form-control" name="tr_route" id="tr_route" required="" data-parsley-error-message="Please Select Transactional User Group">
                                        <option value="">Select User Group</option>
                                        <?php
                                        if ($tr_user_groups) {
                                            foreach ($tr_user_groups as $tr_ugroup) {
                                                if ($user['tr_user_group_id'] == $tr_ugroup['user_group_id']) {
                                                    ?>
                                                    <option value="<?php echo $tr_ugroup['user_group_id']; ?>" selected=""><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>    
                                                    <?php
                                                } else {
                                                    ?>  
                                                    <option value="<?php echo $tr_ugroup['user_group_id']; ?>"><?php echo $tr_ugroup['user_group_name'] . " [" . $tr_ugroup['smsc_id'] . "]"; ?></option>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" class="btn btn-primary">Save Routing</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

        <?php } ?>
    </div>
    <?php
} else {
    ?>
    <div class="col-md-12">
        <h4>User not found!</h4>
    </div>
    <?php
}
?>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/admin/js/validator.js"></script>
<script type="text/javascript">
                            $(document).ready(function () {
                                var nowDate = new Date();
                                var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
                                $('#dob').datepicker({
                                    format: "dd-mm-yyyy",
                                    endDate: today
                                });
                            });

                            $('#validate-basic').parsley();
</script>