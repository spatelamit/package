var controller = 'admin';
// Convert Number To Words
function getNumberToWords(no_of_sms)
{
    var base_url = $('#base_url').val();
    var total = 0;

    var calculate_tax = 0;
    var total_amount = 0;
    var sms_tax = 0;
    var price = 0;
    price = $('#sms_price').val();
    sms_tax = $('#sms_tax').val();


    total = total + (no_of_sms * price);
    calculate_tax = total * sms_tax / 100;
    total_amount = calculate_tax + total;
    $('#amount').val(total_amount);

    if (no_of_sms === "" || no_of_sms.length === 0)
        return false;
    $.ajax({'url': base_url + '' + controller + '/get_number_to_words/' + no_of_sms, 'type': 'POST', 'success': function (data) {
            if (data) {
                $.notify({title: 'Success', text: data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'success'});
            }
        }});
}

function getBalanceType(type)
{

    var old_price = 0;
    var base_url = $('#base_url').val();
    var type = $('#type').val();
    var user_id = $('#user_id').val();
    var balance_type = $('#route').val();



    if (type === "Add")
        $.ajax({'url': base_url + '' + controller + '/get_old_price/' + user_id + "/" + type + "/" + balance_type, 'type': 'POST', 'success': function (data) {
                if (data) {
                    $('#sms_price').val(data);
                }
            }});
}

// Get User Groups
function getUserGroups(smpp_type)
{
    var base_url = $('#base_url').val();
    if (smpp_type == -1)
    {
        $.notify({title: 'Error', text: 'Please Select SMPP Type!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#smpp_type').focus();
        return false;
    }
    $.ajax({'url': base_url + '' + controller + '/get_user_groups/' + smpp_type, 'type': 'POST', 'success': function (data) {
            var container = $('#user_group');
            if (data) {
                container.html(data);
            }
        }});
}
// Get SMPP Port
function getSMPPPort(user_group_id, smpp_type)
{
    var base_url = $('#base_url').val();
    if (user_group_id === 0)
    {
        $.notify({title: 'Error', text: 'Please Select User Type!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    }
    $.ajax({'url': base_url + '' + controller + '/get_smpp_ports/' + smpp_type + "/" + user_group_id, 'type': 'POST', 'success': function (data) {
            if (smpp_type == 'PR')
                var container = $('#pr_port');
            else if (smpp_type == 'TR')
                var container = $('#tr_port');
            else if (smpp_type == 'OPEN')
                var container = $('#open_port');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Delivery SMS
function getDeliverySMS(campaign_id, user_id, route)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/sent_sms/' + campaign_id + "/" + user_id + "/" + route, 'type': 'POST', 'success': function (data) {
            var container = $('#sent_sms');
            if (data) {
                container.html(data);
            }
        }});
}
// Delete Sent SMS
function deleteSentSMS(sms_id, campaign_id)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/delete_sent_sms/' + sms_id + '/' + campaign_id, 'type': 'POST', 'success': function (data) {
            var container = $('#sent_sms');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get User's Tab
function getUserTab(type, tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    if (type === 'user')
    {
        $.ajax({'url': base_url + '' + controller + '/get_user_tab/' + user_id + "/" + tab, 'type': 'POST', 'success': function (data) {
                $('#loading_text').addClass('hidden');
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                }
            }});
    } else if (type === 'smpp')
    {
        $.ajax({'url': base_url + '' + controller + '/get_smpp_user_tab/' + user_id + "/" + tab, 'type': 'POST', 'success': function (data) {
                $('#loading_text').addClass('hidden');
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                }
            }});
    }
}
// Get User's Paging
function pagingUser(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    $.ajax({'url': base_url + '' + controller + '/paging_user/' + page + '/' + record_per_page + '/' + subtab + '/' + user_id, 'type': 'POST', 'success': function (data) {
            var container = $('#user_funds');
            if (data) {
                container.html(data);
            }
        }});
}
// Enable/Disable Items
function enableDisableItem(type, status)
{
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var username = $('#username').val();
    if (type === 'user')
    {
        $.ajax({'url': base_url + '' + controller + '/enable_disable_user/' + user_id + "/" + username + "/" + status, 'type': 'POST', 'success': function (data) {
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    } else if (type === 'smpp')
    {
        $.ajax({'url': base_url + '' + controller + '/enable_disable_smpp_user/' + user_id + "/" + username + "/" + status, 'type': 'POST', 'success': function (data) {
                var container = $('#get_user');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Delete Items
function deleteItem(type)
{
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    if (confirm("Are you sure want to delete!"))
    {
        if (type === 'user')
        {
            $.ajax({'url': base_url + '' + controller + '/delete_user/' + user_id, 'type': 'POST', 'success': function (data) {
                    var container = $('#get_user');
                    if (data) {
                        container.html(data);
                        var msg_type = $('#msg_type').html();
                        var msg_data = $('#msg_data').html();
                        if (msg_type === '1') {
                            $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                        } else if (msg_type === '0') {
                            $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                        }
                    }
                }});
        } else if (type === 'smpp')
        {
            $.ajax({'url': base_url + '' + controller + '/delete_smpp_user/' + user_id, 'type': 'POST', 'success': function (data) {
                    var container = $('#get_user');
                    if (data) {
                        container.html(data);
                        var msg_type = $('#msg_type').html();
                        var msg_data = $('#msg_data').html();
                        if (msg_type === '1') {
                            $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                        } else if (msg_type === '0') {
                            $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                        }
                    }
                }});
        }
    }
}
// Update User's Info
function updateUserInfo(type, tab, subtab)
{
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var username = $('#username').val();
    var formData = $('#userUpdateForm').serialize();
    if (type === 'user')
    {
        $.ajax({'url': base_url + '' + controller + '/save_update_user/' + user_id + '/' + username, 'type': 'POST', 'data': formData, 'success': function (data) {
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    } else if (type === 'smpp')
    {
        $.ajax({'url': base_url + '' + controller + '/save_update_smpp_user/' + user_id + '/' + username, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                var container = $('#get_user');
                if (data) {
                    container.html(data);
                }
            }});
    }
}
// Save User's Info
function saveUserInfo(type, tab, subtab)
{
    var $btn = $('#btn' + subtab + tab).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var dis = $('#dis').val();
    var user_type = $('#user_type').val();
    var username = $('#username').val();

    if (type === 'user')
    {

        var formData = $('#userSettingForm' + tab + subtab).serialize();
        $.ajax({'url': base_url + '' + controller + '/save_user_info/' + user_id + '/' + username + '/' + tab + '/' + subtab, 'type': 'POST', 'data': formData, 'success': function (data) {
                $btn.button('reset');
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }
        });
    }
}

function saveAdminComment(type, counting, tab, subtab)
{
    var $btn = $('#btn' + counting + subtab + tab).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var dis = $('#dis').val();
    var user_type = $('#user_type').val();
    var username = $('#username').val();

    if (type === 'user')
    {

        var formData = $('#userSettingForm' + counting + tab + subtab).serialize();
        $.ajax({'url': base_url + '' + controller + '/save_user_info/' + user_id + '/' + username + '/' + tab + '/' + subtab, 'type': 'POST', 'data': formData, 'success': function (data) {
                $btn.button('reset');
                var container = $('#searchnew');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }
        });
    }
}
// Block Reseller/User
function blockReseller(status)
{
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var username = $('#username').val();
    $.ajax({'url': base_url + '' + controller + '/block_reseller/' + user_id + "/" + username + "/" + status, 'type': 'POST', 'success': function (data) {
            var container = $('#searchnew');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
//Calculate Admin Amount
function calculateAdminAmount(price)
{
    var total = 0;
    var sms_balance = 0;
    var calculate_tax = 0;
    var total_amount = 0;
    var sms_tax = 0;
    sms_balance = $('#sms_balance').val();
    sms_tax = $('#sms_tax').val();

    if (sms_balance === "" && sms_balance.length === 0)
    {
        $('#notification').addClass("show alert-danger");
        $('#notification').html('<h4><i class="fa fa-exclamation-circle"></i> Error</h4><p> Please enter sms!</p>');
        setTimeout(function () {
            $("#notification").removeClass("show alert-danger");
        }, 3000);
        return false;
    }
    if (price === "" && price.length === 0)
    {
        $('#notification').addClass("show alert-danger");
        $('#notification').html('<h4><i class="fa fa-exclamation-circle"></i> Error</h4><p> Please enter price!</p>');
        setTimeout(function () {
            $("#notification").removeClass("show alert-danger");
        }, 3000);
        return false;
    }
    sms_balance = parseInt(sms_balance);

    total = total + (sms_balance * price);
    calculate_tax = total * sms_tax / 100;
    total_amount = calculate_tax + total;
    $('#amount').val(total_amount);
}

//Calculate special balance
function calculateSpecialAmount(price)
{

    var sms_tax = 0;
    var total_credits = 0;
    var sms_price = 0;
    var total_amount = 0;
    //  var total_amount = 0;
    sms_tax = $('#tax_apply').val();
    sms_price = $('#sms_price').val();

    if (price === "" && price.length === 0) {
        $.notify({title: 'Error', text: 'Please enter sms!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }


    var sms_balance = parseInt(price);
    total_amount = sms_balance * sms_price;
    total_credits = total_amount + ((total_amount * sms_tax) / 100);

    $('#special_amount').val(total_credits);
}
// Get Setting's Tab
function getSettingTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_setting_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#show_settings_tab');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Setting's Tab Paging
function pagingSettings(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_settings/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#show_settings_tab');
            if (data) {
                container.html(data);
            }
        }});
}
// Save Setting
function saveSetting(tab, subtab)
{
    var $btn = $('#btn' + tab + subtab).button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#settingForm' + tab + subtab).serialize();
    $.ajax({'url': base_url + '' + controller + '/save_setting/' + tab + '/' + subtab, 'type': 'POST', 'data': formData, 'success': function (data) {
            // alert(data);
            $btn.button('reset');
            var container = $('#show_settings_tab');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Delete Setting 
function deleteSettings(id, tab, pk)
{
    bootbox.confirm("Are you sure?", function (result) {
        if (result) {
            var base_url = $('#base_url').val();
            $.ajax({'url': base_url + '' + controller + '/delete_settings/' + id + '/' + tab + '/' + pk, 'type': 'POST', 'success': function (data) {
                    var container = $('#show_settings_tab');
                    if (data) {
                        container.html(data);
                        var msg_type = $('#msg_type').html();
                        var msg_data = $('#msg_data').html();
                        if (msg_type === '1') {
                            $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                        } else if (msg_type === '0') {
                            $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                        }
                    }
                }});
        }
    });
}
// Change Status
function changeStatus(id, tab, pk, status)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_status/' + id + '/' + tab + '/' + pk, 'type': 'POST', data: {}, 'success': function (data) {
            var container = $('#show_settings_tab');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Show All SMS
function showAllSMS(type, user_id, total)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/show_user_sms/' + user_id + "/" + type + "/" + total, 'type': 'POST', 'success': function (data) {
            if (type === 'tr')
                var container = $('#show_user_tr_sms');
            else if (type === 'pr')
                var container = $('#show_user_pr_sms');
            if (data) {
                container.html(data);
            }
        }});
}
// Show All Requests
function showAllRequests(type, user_id)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/show_all_requests/' + user_id + "/" + type, 'type': 'POST', 'success': function (data) {
            if (type === 'tr')
                var container = $('#show_all_transactionals');
            else if (type === 'pr')
                var container = $('#show_all_promotionals');
            if (data) {
                container.html(data);
            }
        }});
}
// Send PR Messages
function sendPrMessage(number, action)
{
    var $btn = $('#btn' + action + number).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id' + number).val();
    var campaign_id = $('#campaign_id' + number).val();
    var position = $('#position' + number).val();
    var total_sms = $('#total_sms').val();
    if (position === 'outer')
    {
        var fake_delivered_ratio = "";
        var fake_failed_ratio = "";
        var smsc_route_id = "";
        var reject_sms = $('#reject_sms' + number).val();
    }
    if (position === 'inner')
    {
        var fake_delivered_ratio = $('#fake_delivered_ratio' + number).val();
        var fake_failed_ratio = $('#fake_failed_ratio' + number).val();
        var smsc_route_id = $('#smsc_route_id' + number).val();
        var reject_sms = $('#reject_sms' + number).val();
    }
    var dataArray = new Array(7);
    dataArray[0] = action;
    dataArray[1] = user_id;
    dataArray[2] = campaign_id;
    dataArray[3] = fake_delivered_ratio;
    dataArray[4] = fake_failed_ratio;
    dataArray[5] = smsc_route_id;
    dataArray[6] = reject_sms;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/send_pr_message' + "/" + position + "/" + user_id + "/" + total_sms, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            if (data) {
                if (position === 'outer') {
                    $('#spam_promotional').html(data);
                } else if (position === 'inner') {
                    var $response = $(data);
                    var condition = $response.filter('#condition').val();
                    if (condition === '1') {
                        $('#spam_promotional').html(data);
                    } else if (condition === '0') {
                        $('#show_user_pr_sms').html(data);
                    }
                }
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Send All PR Messages
function sendAllPrMessage(action)
{
    var $btn = $('#btn' + action).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#all_user_id').val();
    var total_sms = $('#total_sms').val();
    var fake_delivered_ratio = $('#all_fake_delivered_ratio').val();
    var fake_failed_ratio = $('#all_fake_failed_ratio').val();
    var smsc_route_id = $('#all_smsc_route_id').val();
    var reject_sms = $('#all_reject_sms').val();
    var dataArray = new Array(6);
    dataArray[0] = action;
    dataArray[1] = user_id;
    dataArray[2] = fake_delivered_ratio;
    dataArray[3] = fake_failed_ratio;
    dataArray[4] = smsc_route_id;
    dataArray[5] = reject_sms;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/send_all_pr_message' + "/" + user_id + "/" + total_sms, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            if (data) {
                var $response = $(data);
                var condition = $response.filter('#condition').val();
                if (condition === '1')
                    $('#spam_promotional').html(data);
                else if (condition === '0')
                    $('#show_user_pr_sms').html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Send TR Messages
function sendTrMessage(number, action)
{
    var $btn = $('#btn' + action + number).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id' + number).val();
    var campaign_id = $('#campaign_id' + number).val();
    var position = $('#position' + number).val();
    var total_sms = $('#total_sms').val();
    if (position === 'outer')
    {
        var keyword_ratio = "";
        var fake_delivered_ratio = "";
        var fake_failed_ratio = "";
        var smsc_route_id = "";
        var reject_sms = $('#reject_sms' + number).val();
    }
    if (position === 'inner')
    {
        var keyword_ratio = $('#keyword_ratio' + number).val();
        var fake_delivered_ratio = $('#fake_delivered_ratio' + number).val();
        var fake_failed_ratio = $('#fake_failed_ratio' + number).val();
        var smsc_route_id = $('#smsc_route_id' + number).val();
        var reject_sms = $('#reject_sms' + number).val();
    }
    var dataArray = new Array(8);
    dataArray[0] = action;
    dataArray[1] = user_id;
    dataArray[2] = campaign_id;
    dataArray[3] = keyword_ratio;
    dataArray[4] = fake_delivered_ratio;
    dataArray[5] = fake_failed_ratio;
    dataArray[6] = smsc_route_id;
    dataArray[7] = reject_sms;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/send_tr_message' + "/" + position + "/" + user_id + "/" + total_sms, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            if (data) {
                if (position === 'outer') {
                    $('#spam_transactional').html(data);
                } else if (position === 'inner') {
                    var $response = $(data);
                    var condition = $response.filter('#condition').val();
                    if (condition === '1')
                        $('#spam_transactional').html(data);
                    else if (condition === '0')
                        $('#show_user_tr_sms').html(data);
                }
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Send All TR Messages
function sendAllTrMessage(action)
{
    var $btn = $('#btn' + action).button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#all_user_id').val();
    var total_sms = $('#total_sms').val();
    var keyword_ratio = $('#all_keyword_ratio').val();
    var fake_delivered_ratio = $('#all_fake_delivered_ratio').val();
    var fake_failed_ratio = $('#all_fake_failed_ratio').val();
    var smsc_route_id = $('#all_smsc_route_id').val();
    var reject_sms = $('#all_reject_sms').val();
    var dataArray = new Array(8);
    dataArray[0] = action;
    dataArray[1] = user_id;
    dataArray[2] = keyword_ratio;
    dataArray[3] = fake_delivered_ratio;
    dataArray[4] = fake_failed_ratio;
    dataArray[5] = smsc_route_id;
    dataArray[6] = reject_sms;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/send_all_tr_message' + "/" + user_id + "/" + total_sms, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            if (data) {
                var $response = $(data);
                var condition = $response.filter('#condition').val();
                if (condition === '1')
                    $('#spam_transactional').html(data);
                else if (condition === '0')
                    $('#show_user_tr_sms').html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Approve Sender Id
function approveSender(sender, user_id, total)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/approve_sender/' + user_id + '/' + sender + '/' + total, 'type': 'POST', 'success': function (data) {
            var container = $('#show_user_tr_sms');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Update User's Number DB
function updateUserDB(user_id)
{
    var base_url = $('#base_url').val();
    var database_limit = $('#database_limit').val();
    $.ajax({'url': base_url + '' + controller + '/update_user_db/' + user_id + '/' + database_limit, 'type': 'POST', 'success': function (data) {
            if (data === '1') {
                $.notify({title: 'Success', text: 'User database limit updated successfully!', image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
            } else if (data === '0') {
                $.notify({title: 'Error', text: 'User database limit updation failed!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
            }
        }});
}
// Get keyword's Tab
function getKeywordTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_keyword_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#keywords');
            if (data) {
                container.html(data);
            }
        }});
}
// Get keyword's Tab Paging
function pagingKeywords(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_keywords/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {

            var container = $('#keywords');
            if (data) {
                container.html(data);
            }
        }});
}
// Delete Keyword
function deleteKeyword(id, tab, pk)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_keyword/' + id + '/' + tab + '/' + pk, 'type': 'POST', 'success': function (data) {
                var container = $('#keywords');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Save Keyword
function saveKeyword(tab)
{
    var base_url = $('#base_url').val();
    var formData = $('#keywordForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_black_keyword/' + tab, 'type': 'POST', 'data': formData, 'success': function (data) {
            var container = $('#keywords');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Approve Keyword
function approveKeyword(action, pk, number)
{
    var base_url = $('#base_url').val();
    var matching_ratio = $('#keyword_ratio' + number).val();
    var dataArray = new Array(3);
    dataArray[0] = matching_ratio;
    dataArray[1] = action;
    dataArray[2] = pk;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/approve_keyword', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#keywords');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Change Keyword's Status
function changeKeywordStatus(id, status, subtab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_keyword_status/' + id + "/" + status + "/" + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#keywords');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get Sender Id Tab
function getSenderTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_sender_tab/' + tab, 'type': 'POST', 'success': function (data) {
            //   alert(data);
            $('#loading_text').addClass('hidden');
            var container = $('#sender_ids');
            if (data) {
                container.html(data);
            }
        }});

}
// for get unique sender ids with username
function getSenderTabUnique(tab)
{
    // alert(tab);
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_sender_tab_uniq/' + tab, 'type': 'POST', 'success': function (data) {
            //       alert(data);
            $('#loading_text').addClass('hidden');
            var container = $('#sender_ids');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Sender Id Tab Paging
function pagingSenders(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_sender_id/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#sender_ids');
            if (data) {
                container.html(data);
            }
        }});
}
//paging of payment logs
function pagingPayment(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_payment_logs/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#payment_approve');
            if (data) {
                container.html(data);
            }
        }});

}


function pagingPaymentSubadmin(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_payment_subadmin_logs/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#show-subadmin-approve');
            if (data) {
                container.html(data);
            }
        }});

}
// Delete Sender Id
function deleteSenderId(id, key, subtab)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_sender_id/' + id + "/" + key + "/" + subtab, 'type': 'POST', 'success': function (data) {
                var container = $('#sender_ids');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}

//insert vodafone sender id
function insertNewSenderID() {
    var base_url = $('#base_url').val();
    var $btn = $('#save_id').button('loading');
    var formData = $('#insertSenderID').serialize();
    // alert(formData);
    $.ajax({'url': base_url + '' + controller + '/insert_new_sender_id', 'type': 'POST', 'data': formData, 'success': function (data) {
            var container = $('#sender_ids');
            $btn.button('reset');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

//insert promotional sender id
function insertPrSenderID() {
    var base_url = $('#base_url').val();
    var $btn = $('#save_pr_id').button('loading');
    var formData = $('#insertPrSenderID').serialize();
    // alert(formData);
    $.ajax({'url': base_url + '' + controller + '/insert_pr_sender_id', 'type': 'POST', 'data': formData, 'success': function (data) {
            var container = $('#sender_ids');
            $btn.button('reset');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}




// Delete vodafone Sender Id
function deleteApproveSenderId(id, key, subtab)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_approve_sender_id/' + id + "/" + key + "/" + subtab, 'type': 'POST', 'success': function (data) {

                var container = $('#sender_ids');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();

                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}


// Delete promotional approve Sender Id
function deletePrApproveSenderId(id, key, subtab)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_pr_approve_sender_id/' + id + "/" + key + "/" + subtab, 'type': 'POST', 'success': function (data) {

                var container = $('#sender_ids');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();

                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}




//click to approve 
function clickToApprove(id, subtab) {
    var base_url = $('#base_url').val();

    if (confirm("Are you sure want to Approve!"))
    {
        $.ajax({'url': base_url + '' + controller + '/click_to_approve/' + id + "/" + subtab, 'type': 'POST', 'success': function (data) {

                var container = $('#sender_ids');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}

// Change Sender Id's Status
function changeSIdStatus(id, key, status, subtab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/sender_id_status/' + id + "/" + key + "/" + status + "/" + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#sender_ids');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

//import new sender id
function ImportNewSenderID()
{
    //var $btn = $('#save_ugroup').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#sender_id_form').serialize();

    $.ajax({'url': base_url + '' + controller + '/ImportNewID', 'type': 'POST', 'data': formData, 'success': function (data) {

            var container = $('#sender_ids');
            $btn.button('reset');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

//update vodafone routing
function updateSenderidRouting()
{

    var $btn = $('#save_routing').button('loading');
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to change Route !"))
    {
        var formData = $('#sender_id_routing').serialize();
        $.ajax({'url': base_url + '' + controller + '/update_sender_id_routing', 'type': 'POST', 'data': formData, 'success': function (data) {

                var container = $('#sender_ids');
                $btn.button('reset');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}

// Get User Group's Tab
function getUserGroupTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_ugroups_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#user_groups');
            if (data) {
                container.html(data);
            }
        }});
}
// Save User Group
function saveUserGroup()
{
    var $btn = $('#save_ugroup').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#userGroupForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_user_group', 'type': 'POST', 'data': formData, 'success': function (data) {
            var container = $('#user_groups');
            $btn.button('reset');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Set Default
function setDefault(id, tab, status, type)
{
    var base_url = $('#base_url').val();
    var dataArray = new Array(3);
    dataArray[0] = id;
    dataArray[1] = status;
    dataArray[2] = type;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/set_default/' + tab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Set Backup Route
function setBackupRoute(id, tab, status, type)
{
    var base_url = $('#base_url').val();
    var dataArray = new Array(3);
    dataArray[0] = id;
    dataArray[1] = status;
    dataArray[2] = type;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/set_backup_route/' + tab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Change User Group's Status
function changeUGStatus(id, tab, status, type)
{
    var base_url = $('#base_url').val();
    var dataArray = new Array(3);
    dataArray[0] = id;
    dataArray[1] = status;
    dataArray[2] = type;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/change_ugroup_status/' + tab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Delete User Group
function deleteUserGroup(id, tab)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_user_group/' + tab + '/' + id, 'type': 'POST', 'success': function (data) {
                var container = $('#user_groups');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Update User Group
function updateUserGroup(id, type)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/update_user_group/' + id + '/' + type, 'type': 'POST', 'success': function (data) {
            var container = $('#user_groups');
            if (data) {
                container.html(data);
            }
        }});
}
// Set For Resend
function setForResend(id, tab, status, type)
{
    var base_url = $('#base_url').val();
    var dataArray = new Array(3);
    dataArray[0] = id;
    dataArray[1] = status;
    dataArray[2] = type;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/change_resend_status/' + tab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get User's Tab
function getUTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_users_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#users');
            if (data) {
                container.html(data);
            }
        }});
}
// Save SMPP Routing
function saveSMPPRouting(type)
{
    var base_url = $('#base_url').val();
    if (type === 'pr') {
        var current_smpp = $('#current_pr_route').val();
        var new_smpp = $('#new_pr_route').val();
    } else if (type === 'tr') {
        var current_smpp = $('#current_tr_route').val();
        var new_smpp = $('#new_tr_route').val();
    } else if (type === 'otp') {
        var current_smpp = $('#new_otp_route').val();
        var new_smpp = $('#new_otp_route').val();
    } else if (type === 'pr_bal') {
        var current_smpp = $('#new_pr_bal_route').val();
        var new_smpp = $('#new_pr_bal_route').val();
        
    }else if (type === 'tr_bal') {
        var current_smpp = $('#new_tr_bal_route').val();
        var new_smpp = $('#new_tr_bal_route').val();
        
    }

    $.ajax({'url': base_url + '' + controller + '/change_smpp_routing/' + type, 'type': 'POST', data: {current_smpp: current_smpp, new_smpp: new_smpp}, 'success': function (data) {
            var container = $('#users');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

//save retry users

function saveRetryUser()
{
    var base_url = $('#base_url').val();

    var id = $('#multiple-checkboxes').val();
    var type = 1;

    var ajaxData = {dataArray: JSON.stringify(id)};
    $.ajax({'url': base_url + '' + controller + '/save_retry_user/' + type, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#users');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}


function makeMeSpecialReseller(id, status)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure!")) {
        $.ajax({'url': base_url + '' + controller + '/make_me_special_reseller/' + id + '/' + status, 'type': 'POST', 'success': function (data) {

                var container = $('#users');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
//save user groups for retry 
function saveRetryRoute()
{
    var base_url = $('#base_url').val();

    var route = $('#retry_route').val();



    $.ajax({'url': base_url + '' + controller + '/save_retry_route/' + route, 'route': 'POST', 'success': function (data) {
            var container = $('#users');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

// Search Users
function searchUsers(search)
{
    if (search === "") {
        $.notify({title: 'Error', text: 'Please Enter Text!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});

        $('#pr_route').focus();
        // window.location.reload();

        return false;
    } else {

        var base_url = $('#base_url').val();
        var dataArray = new Array(1);
        dataArray[0] = search;
        var ajaxData = {dataArray: JSON.stringify(dataArray)};
        $.ajax({'url': base_url + '' + controller + '/search_users', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                var container = $('#show_search_result');
                if (data) {
                    container.html(data);
                }
            }});
    }
}

// Search special Reseller
function searchSpecialReseller(special_reseller)
{
    if (special_reseller === "") {
        $.notify({title: 'Error', text: 'Please Enter Text!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});

        //$('#pr_route').focus();
        // window.location.reload();

        return false;
    } else {

        var base_url = $('#base_url').val();
        var dataArray = new Array(1);
        dataArray[0] = special_reseller;
        var ajaxData = {dataArray: JSON.stringify(dataArray)};
        $.ajax({'url': base_url + '' + controller + '/search_special_reseller', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                var container = $('#search_result');
                if (data) {
                    container.html(data);
                }
            }});
    }
}

// Search special Reseller
function searchSenderidByUsername(username)
{
    if (username === "") {
        $.notify({title: 'Error', text: 'Please Enter Text!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    } else {

        var base_url = $('#base_url').val();
        var dataArray = new Array(1);
        dataArray[0] = username;
        var ajaxData = {dataArray: JSON.stringify(dataArray)};
        $.ajax({'url': base_url + '' + controller + '/search_senderid_by_username', 'type': 'POST', 'data': ajaxData, 'success': function (data) {

                var container = $('#search_response');
                if (data) {
                    container.html(data);
                }
            }});
    }
}






// Get User
function getUser(username)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_user/' + username, 'type': 'POST', 'success': function (data) {
            var container = $('#show_search_result');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Log's Tab
function geLogsTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_logs_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#show_all_logs');
            if (data) {
                container.html(data);
            }
        }});
}

// Get pr consumption by date 
function getReportPrSms()
{
    var $btn = $('#get_pr_report').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#getReportPrSms').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_pr_consumption',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#show_pr_report');
            if (data) {
                container.html(data);
            }
        }});
}

// Get tr consumption by date 
function getReportTrSms()
{
    var $btn = $('#get_tr_report').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#getReportTrSms').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_tr_consumption',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#show_tr_report');
            if (data) {
                container.html(data);
            }
        }});

}

// Get overall log by date
function getOverallLogs()
{
    var $btn = $('#search_report').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#getOverallLogs').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_overall_log',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#show_overall_report');
            if (data) {
                container.html(data);
            }
        }});

}




// Get Log's Tab Paging
function pagingLogs(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_logs/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#show_all_logs');
            if (data) {
                container.html(data);
            }
        }});
}
// Filter SMS Consumption
function filterSMSConsumption(username)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/filter_sms_consumptions', 'type': 'POST', 'data': {username: username}, 'success': function (data) {
            var container = $('#sms_consumptions_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Get SMS Tracking Tab
function getSMSTrackingTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_sms_tracking_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#sms_tracking');
            if (data) {
                container.html(data);
            }
        }});
}
// Get SMS Tracking Tab Paging
function pagingDlrReport(page, record_per_page)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_delivery_reports/' + page + '/' + record_per_page, 'type': 'POST', 'success': function (data) {
            var container = $('#sms_tracking');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Set SMS Paging
function pagingSentSMS(page, record_per_page, campaign_id, total_pages)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_sent_sms/' + page + '/' + record_per_page + '/' + campaign_id, 'type': 'POST', 'success': function (data) {
            var container = $('#sent_sms_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Search Delivery Report
function searchDlrReport(search)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_delivery_reports/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#search_dlr_report_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Search Sent SMS
function searchSentSMS(search, campaign)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_sent_sms/' + campaign + '/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#sent_sms_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Track Mobile Number
function trackMobileNumber(mobile)
{
    var $btn = $('#track_number').button('loading');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/track_mobile/' + mobile, 'type': 'POST', 'success': function (data) {
            $btn.button('reset')
            var container = $('#show_track_mobile');
            if (data) {
                container.html(data);
            }
        }});
}
// Search Detailed Report
function searchDetailedReport()
{
    var $btn = $('#search_button').button('loading')
    var base_url = $('#base_url').val();
    var number = $('#search_by_number').val();
    var fdate = $('#search_by_fdate').val();
    var tdate = $('#search_by_tdate').val();
    var status = $('#search_by_status').val();
    $.ajax({'url': base_url + '' + controller + '/get_detailed_report', 'type': 'POST', data: {number: number, fdate: fdate, tdate: tdate, status: status}, 'success': function (data) {
            $btn.button('reset')
            var container = $('#show_reports');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Delivery Report
function getDeliveryReport()
{
    var $btn = $('#get_report_btn').button('loading');
    var base_url = $('#base_url').val();
    //var by_from_date = $('#by_from_date').val();
    //var by_to_date = $('#by_to_date').val();
    //var by_route = $('#by_route').val();
    var formData = $('#dlrReportForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_delivery_reports',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#get_delivery_reports');
            if (data) {
                container.html(data);
            }
        }});
}

function getOverAllReport()
{
    var $btn = $('#get_report_btn').button('loading');
    var base_url = $('#base_url').val();
    //var by_from_date = $('#by_from_date').val();
    //var by_to_date = $('#by_to_date').val();
    //var by_route = $('#by_route').val();
    var formData = $('#dlrReportForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_overall_reports',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#get-overall-report');
            if (data) {
                container.html(data);
            }
        }});
}
function getAllNumberSmsReport()
{
    var $btn = $('#get_sms_btn').button('loading');
    var base_url = $('#base_url').val();
    //var by_from_date = $('#by_from_date').val();
    //var by_to_date = $('#by_to_date').val();
    //var by_route = $('#by_route').val();
    var formData = $('#dlrReportSmsForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_all_numbersms_report',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#search_dlr_report_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Get seller Report
function getSellerReport()
{
    //alert('hello');
    // var $btn = $('#click_report').button('loading');
    var base_url = $('#base_url').val();

    var formData = $('#sellerReportForm').serialize();
    //  alert(formData); 
    $.ajax({
        'url': base_url + '' + controller + '/get_seller_reports',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            //  $btn.button('reset');
            var container = $('#get_seller_reports');
            if (data) {
                container.html(data);
            }
        }});
}

function getSignUpReport()
{

    var $btn = $('#get_report_btn').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#signUpReportForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_signup_reports',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#users');
            if (data) {
                container.html(data);
            }
        }});
}

//sms consumption report
function getSmsReport()
{

    var $btn = $('#get_sms_report').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#getSmsReportData').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/get_sms_report',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#show_report');
            if (data) {
                container.html(data);
            }
        }});
}


// Resend Delivery Reports
function resendDeliveryReports()
{
    var $btn = $('#resend').button('loading');
    var base_url = $('#base_url').val();
    var by_from_date = $('#by_from_date').val();
    var by_to_date = $('#by_to_date').val();
    var by_route = $('#by_route').val();
    var by_users = $('#by_users_data').val();
    var ajaxData = $('#resend_sms_form').serialize() + "&fdate=" + by_from_date + "&tdate=" + by_to_date + "&route=" + by_route + "&users=" + by_users;
    $.ajax({'url': base_url + '' + controller + '/resend_delivery_reports', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            if (data.type) {
                $.notify({title: 'Success', text: data.message, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
            } else {
                $.notify({title: 'Error', text: data.message, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
            }
        }});
}
// Check DNS Setting
function checkDNSSettings()
{
    var base_url = $('#base_url').val();
    var domain_name = $('#domain').val();
    if (domain_name === "" || domain_name.length === 0)
    {
        $.notify({title: 'Error', text: 'Please Enter Valid Domain Name!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    }
    var dataArray = new Array(1);
    dataArray[0] = domain_name;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/check_dns_settings', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $('#show_message').css("display", "block");
            var container = $('#show_message');
            if (data) {
                container.html(data);
            }
        }});
}
// Check Username
function checkUsername(username, type)
{
    var base_url = $('#base_url').val();
    var username = $('#username').val();
    if (username.length === 0 && username === "")
    {
        $.notify({title: 'Error', text: 'Please enter username!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    }
    var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
    if (!pattern_username.test(username))
    {
        $.notify({title: 'Error', text: 'Username must be start with a character!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    }
    if (username.length < 5)
    {
        $.notify({title: 'Error', text: 'Username must be 5 characters long!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    }
    var dataArray = new Array(1);
    dataArray[0] = username;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/check_username/' + type, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $('#show_message').css("display", "block");
            var container = $('#show_message');
            if (data) {
                container.html(data);
            }
        }});
}
// Save User
function saveUser()
{
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#addUserForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_user', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#users');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Paging Users
function pagingUsers(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_users/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#show_users');
            if (data) {
                container.html(data);
            }
        }});
}
// Search User For Notify
function searchUserNotify(search, type)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_user_notify/' + type, 'type': 'POST', 'data': {search: search}, 'success': function (data) {
            var container = $('#search_user_notify');
            if (data) {
                container.html(data);
            }
        }});
}
// Get SMPP Tab
function getSMPPTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_smpp_logs/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#smpp_logs');
            if (data) {
                container.html(data);
            }
        }});
}
// Filter SMPP Logs
function filterSMPPLogs()
{
    var $btn = $('#search_logs').button('loading');
    var date = $('#search_by_date').val();
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/filter_smpp_logs/' + date, 'type': 'POST', 'success': function (data) {
            $btn.button('reset');
            var container = $('#smpp_logs_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Search SMPP Logs
function searchSMPPLogs()
{
    var $btn = $('#search_logs').button('loading');
    var fdate = $('#export_from_date').val();
    var tdate = $('#export_to_date').val();
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_smpp_logs', 'type': 'POST', 'data': {fdate: fdate, tdate: tdate}, 'success': function (data) {
            $btn.button('reset');
            var container = $('#smpp_logs_table');
            if (data) {
                container.html(data);
                $('#export_logs').removeClass('hidden');
            }
        }});
}
// Export SMPP Logs
function exportSMPPLogs()
{
    var $btn = $('#export_logs').button('loading');
    var base_url = $('#base_url').val();
    var export_from_date = $('#export_from_date').val();
    var export_to_date = $('#export_to_date').val();
    if (export_from_date == "")
    {
        return false;
    }
    if (export_to_date == "")
    {
        return false;
    }
    $.ajax({'url': base_url + '/export/smpp_logs', 'type': 'POST', 'data': {'fdate': export_from_date, 'tdate': export_to_date}, 'success': function (data) {
            $btn.button('reset');
            var url = base_url + "/Reports/" + data;
            window.open(url);
        }});
}

// AJAX TR Spam
function ajaxTrSpam()
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + 'admin/show_tr_spam', type: "POST", dataType: "html", success: function (data) {
            if (data != '') {
                $('#spam_transactional').html(data);
                var number = parseInt($('#total_tr_count').val());
                if (number) {
                    $(document).prop('title', 'Pending: Transactional Request');
                    var obj = document.createElement("audio");
                    obj.src = base_url + "Assets/alerts.mp3";
                    obj.volume = 0.80;
                    obj.autoPlay = true;
                    obj.preLoad = true;
                    obj.play();
                } else {
                    $(document).prop('title', 'Bulk SMS: Admin Control Panel');
                }
            }
        }});
}
// AJAX PR Spam
function ajaxPrSpam()
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + 'admin/show_pr_spam', type: "GET", dataType: "html", success: function (data) {
            if (data != '') {
                $('#spam_promotional').html(data);
                var number = parseInt($('#total_pr_count').val());
                if (number) {
                    $(document).prop('title', 'Pending: Promotional Request');
                    var obj = document.createElement("audio");
                    obj.src = base_url + "Assets/alerts.mp3";
                    obj.volume = 0.20;
                    obj.autoPlay = true;
                    obj.preLoad = true;
                    obj.play();
                } else {
                    $(document).prop('title', 'Bulk SMS: Admin Control Panel');
                }
            }
        }});
}
// Set Interval For PR/TR Span SMS
setInterval(function () {
    if ($('#show_user_pr_sms').html() === "") {
        ajaxPrSpam();
    }
    if ($('#show_user_tr_sms').html() === "") {
        ajaxTrSpam();
    }
}, 5000);
// Change Short/Long Status
function changeSLStatus(id, status, subtab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/sl_status/' + id + "/" + status + "/" + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#virtual_numbers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get Virtual Number's Tab
function geVirtualTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_virtual_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#virtual_numbers');
            if (data) {
                container.html(data);
            }
        }});
}
// Get Virtual Number's Tab Paging
function pagingVirtualNumbers(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_virtual/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#virtual_numbers');
            if (data) {
                container.html(data);
            }
        }});
}
// Save Number
function saveNumber(type, number, subtab)
{
    var base_url = $('#base_url').val();
    var $btn = $('#btn_' + type).button('loading');
    var formData = $('#virtual_data' + number).serialize();
    $.ajax({'url': base_url + '' + controller + '/save_sl_number/' + type + '/' + subtab, type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#virtual_numbers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Delete Short/Long Data
function deleteSLData(id, subtab)
{
    if (confirm("Are you sure want to delete!")) {
        var base_url = $('#base_url').val();
        $.ajax({'url': base_url + '' + controller + '/delete_sl_data/' + id + "/" + subtab, 'type': 'POST', 'success': function (data) {
                var container = $('#virtual_numbers');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Get Account Manager's Tab
function getAccountTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_account_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#account_managers');
            if (data) {
                container.html(data);
            }
        }});
}
// Save Account Manager
function saveAccountManager(admin_id)
{
    var base_url = $('#base_url').val();
    var $btn = $('#save').button('loading');
    var formData = $('#addAccMForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_account_manager/' + admin_id, type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#account_managers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get Account Manager's Tab Paging
function pagingAccount(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_accounts/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#show_account_managers');
            if (data) {
                container.html(data);
            }
        }});
}
// Change Account Manager Status
function changeAMStatus(id, status)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_amanager_status/' + id + '/' + status, 'type': 'POST', 'success': function (data) {
            var container = $('#show_account_managers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type1').html();
                var msg_data = $('#msg_data1').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Delete Account Manager
function deleteAManager(id)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_account_manager/' + id, 'type': 'POST', 'success': function (data) {
                var container = $('#show_account_managers');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Update Account Manager
function updateAManager(id)
{
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/update_account_manager/' + id, 'type': 'POST', 'success': function (data) {
            $btn.button('reset')
            var container = $('#account_managers');
            if (data) {
                container.html(data);
            }
        }});
}
// Transfer Balance To Account Manager
function transferBalance()
{
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#transferBalForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/transfer_balance', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#account_managers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Reset Password For Account Manager
function resetAccPassword()
{
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#resetPwdForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/reset_am_password', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#account_managers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Notify User's By E-Mail/SMS
function notifyUsers(type)
{
    var $btn = $('#notify' + type).button('loading');
    var base_url = $('#base_url').val();
    if (type === 'sms')
    {
        var formData = $('#notifySMSForm').serialize();
    }
    if (type === 'email')
    {
        var formData = $('#notifyEmailForm').serialize();
    }
    $.ajax({'url': base_url + '' + controller + '/send_notify_users/' + type, type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#users');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Get User Balance Tab
function getUBalanceTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_users_balance_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#users_balance');
            if (data) {
                container.html(data);
            }
        }});
}
// Get User Balance Tab Paging
function pagingUsersBalance(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    var filter_by_date = $('#filter_by_date').val();
    $.ajax({'url': base_url + '' + controller + '/paging_users_balance/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'data': {date: filter_by_date}, 'success': function (data) {
            var container = $('#users_balance_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Filter User Balance Logs
function filterBalanceLogs(search, type, route, subtab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/filter_users_balance/' + subtab, 'type': 'POST', 'data': {search: search, type: type, route: route}, 'success': function (data) {
            var container = $('#users_balance_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Filter User Balance
function filterUsersBalance(date, user_id, subtab)
{
    var $btn = $('#filter_balance').button('loading');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/filter_users_balance/' + subtab, 'type': 'POST', 'data': {date: date, user_id: user_id}, 'success': function (data) {
            $btn.button('reset');
            var container = $('#users_balance_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Analyze User's Balance
function analyzeUsersBalance(subtab)
{
    var $btn = $('#analyzeBtn').removeClass('hidden').button('loading');
    var base_url = $('#base_url').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var filter_by_username = $('#filter_by_username').val();
    $.ajax({'url': base_url + '' + controller + '/filter_users_balance/' + subtab, 'type': 'POST', 'data': {from_date: from_date, to_date: to_date, user_id: filter_by_username}, 'success': function (data) {
            $btn.button('reset').addClass('hidden');
            var container = $('#users_balance_table');
            if (data) {
                container.html(data);
            }
        }});
}
// Export Sender Ids
function exportSenderIds()
{
    var $btn = $('#export_senders').button('loading');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '/export/sender_ids', 'type': 'POST', 'success': function (data) {
            $btn.button('reset');
            var url = base_url + "/Reports/" + data;
            window.open(url);
        }});
}
// Resend SMS
function reSendSMS()
{
    var $btn = $('#resend').button('loading');
    var base_url = $('#base_url').val();
    var user_id = $('#user_id').val();
    var ajaxData = $('#resend_sms_form').serialize();
    $.ajax({'url': base_url + '' + controller + '/resend_sms/' + user_id, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            $('.resend_campaign').popover('hide');
            if (data.type) {
                $.notify({title: 'Success', text: data.message, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: data.message_type});
            } else {
                $.notify({title: 'Error', text: data.message, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: data.message_type});
            }
        }});
}
// Save XML Routing Setting
function saveXMLRouteSetting()
{
    var $btn = $('#save_xml').button('loading');
    var base_url = $('#base_url').val();
    var ajaxData = $('#xmlRouteForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/xml_route_setting',
        'type': 'POST',
        'data': ajaxData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }
    });
}
// Save Backup Route Setting
function saveBackupRouteSetting()
{
    var $btn = $('#save_backup_route').button('loading');
    var base_url = $('#base_url').val();
    var ajaxData = $('#backupRouteForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/backup_route_setting',
        'type': 'POST',
        'data': ajaxData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#user_groups');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }
    });
}
// Active Users (Day-Wise)
function filterActiveUsers(date)
{
    var $btn = $('#filter_balance').button('loading');
    var base_url = $('#base_url').val();
    var route = $('#filter_route').val();
    $.ajax({
        'url': base_url + '' + controller + '/filter_active_users',
        'type': 'POST',
        'data': {date: date, route: route},
        'success': function (data) {
            $btn.button('reset');
            var container = $('#active_users_table');
            if (data) {
                container.html(data);
            }
        }
    });
}
// Get SMS Rates Tab
function getSMSRateTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_sms_rates_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#sms_rate_plans');
            if (data) {
                container.html(data);
            }
        }});
}
// Save SMS Rate
function saveSMSRate(rate_id)
{
    var min = $('#rate_plan_min').val();
    var max = $('#rate_plan_max').val();
    if (parseInt(max) <= parseInt(min)) {
        $.notify({title: 'Error', text: 'Max SMS must be greater than Min SMS', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return;
    }
    var base_url = $('#base_url').val();
    var $btn = $('#save').button('loading');
    var formData = $('#addSMSRateForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_sms_rate/' + rate_id, type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#sms_rate_plans');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }
    });
}
// Paging SMS Rates
function pagingSMSRate(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify('You entered wrong page number!', "error");
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_sms_rates/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#sms_rate_plans');
            if (data) {
                container.html(data);
            }
        }});
}
// Change SMS Rate Status
function changeSMSRateStatus(id, status)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_sms_rate_status/' + id + '/' + status, 'type': 'POST', 'success': function (data) {
            var container = $('#sms_rate_plans');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type1').html();
                var msg_data = $('#msg_data1').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}
// Delete SMS Rate
function deleteSMSRate(id)
{
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete!"))
    {
        $.ajax({'url': base_url + '' + controller + '/delete_sms_rate/' + id, 'type': 'POST', 'success': function (data) {
                var container = $('#sms_rate_plans');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}
// Update SMS Rate
function updateSMSRate(id)
{
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/update_sms_rates/' + id, 'type': 'POST', 'success': function (data) {
            $btn.button('reset')
            var container = $('#sms_rate_plans');
            if (data) {
                container.html(data);
            }
        }});
}

// Get Account Manager Permissions
function getAManagerPermissions(id) {
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_am_permissions/' + id, 'type': 'POST', 'success': function (data) {
            var container = $('#show_permissions');
            if (data) {
                container.html(data);
            }
        }});
}

// Set Account Manager Permissions
function setAMPermissions() {
    var $btn = $('#save').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#setPermissionForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/set_am_permissions', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#account_managers');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

//============================================================//
// Missed Call Alerts Services
// Save Service Number
function saveMissedCall(subtab)
{
    var base_url = $('#base_url').val();
    var $btn = $('#btn_missed_call' + subtab).button('loading');
    var formData = $('#missed_call' + subtab).serialize();
    $.ajax({'url': base_url + '' + controller + '/save_missed_call/' + subtab, type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#missed_call');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

// Change Missed Call Alerts Status
function statusMissedCall(id, status, subtab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/status_missed_call/' + id + "/" + status + "/" + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#missed_call');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').html();
                var msg_data = $('#msg_data').html();
                if (msg_type === '1') {
                    $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                } else if (msg_type === '0') {
                    $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                }
            }
        }});
}

// Get Missed Call Alert's Tab
function getMissedCallTab(tab)
{
    $('#loading_text').removeClass('hidden');
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_missed_call_tab/' + tab, 'type': 'POST', 'success': function (data) {
            $('#loading_text').addClass('hidden');
            var container = $('#missed_call');
            if (data) {
                container.html(data);
            }
        }});
}

// Get Missed Call Alert's Tab Paging
function pagingMissedCall(page, record_per_page, total_pages, subtab)
{
    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_missed_call/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#missed_call');
            if (data) {
                container.html(data);
            }
        }});
}

// Delete Missed Call Alerts Data
function deleteMissedCall(id, subtab)
{
    if (confirm("Are you sure want to delete!")) {
        var base_url = $('#base_url').val();
        $.ajax({'url': base_url + '' + controller + '/delete_missed_call/' + id + "/" + subtab, 'type': 'POST', 'success': function (data) {
                var container = $('#missed_call');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').html();
                    var msg_data = $('#msg_data').html();
                    if (msg_type === '1') {
                        $.notify({title: 'Success', text: msg_data, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: 'success'});
                    } else if (msg_type === '0') {
                        $.notify({title: 'Error', text: msg_data, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
                    }
                }
            }});
    }
}

// View All Message (Multiple Messages)
$(document).ready(function () {
    $(document).on("click", ".detail-message", function () {
        $('#show_all_messages').html('');
        var dataString = {
            campaign_id: $(this).attr('id')
        };
        var base_url = $('#base_url').val();
        $.ajax({
            type: "POST",
            'url': base_url + 'admin/get_all_messages',
            data: dataString,
            success: function (data) {
                if (data) {
                    $('#show_all_messages').html(data);
                }
            }
        });
    });
});
//delete seceted keywords
function delete_confirm() {
    var result = confirm("Are you sure to delete Keywords?");
    if (result) {
        return true;
    } else {
        return false;
    }
}

$(document).ready(function () {
    $('#select_all').on('click', function () {
        if (this.checked) {
            $('.checkbox').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkbox').each(function () {
                this.checked = false;
            });
        }
    });

    $('.checkbox').on('click', function () {
        if ($('.checkbox:checked').length == $('.checkbox').length) {
            $('#select_all').prop('checked', true);
        } else {
            $('#select_all').prop('checked', false);
        }
    });
});
//end script delete seceted keywords
// Search Users
function searchUsersKeywords(search)
{
    if (search === "") {
        $.notify({title: 'Error', text: 'Please Enter Text!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        $('#pr_route').focus();
        return false;
    } else {
        var base_url = $('#base_url').val();
        var dataArray = new Array(1);
        dataArray[0] = search;
        var ajaxData = {dataArray: JSON.stringify(dataArray)};
        $.ajax({'url': base_url + '' + controller + '/search_users_keyword', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                var container = $('#show_search_result1');
                if (data) {
                    container.html(data);
                }
            }});
    }
}
//update push dlr from admin
function update_push_dlr()
{
    var $btn = $('#update_push_dlr').button('loading');
    var base_url = $('#base_url').val();
    var ajaxData = $('#update_dlr_form').serialize();
    $.ajax({'url': base_url + '' + controller + '/fake_update_push_dlr/', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');

            if (data.type) {
                $.notify({title: 'Success', text: data.message, image: '<i class="fa fa-check-circle"></i>'}, {style: 'metro', className: data.message_type});
            } else {
                $.notify({title: 'Error', text: data.message, image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: data.message_type});
            }
        }});

}

function paginghistory(page, record_per_page, total_pages, subtab)
{

    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/paging_history/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#controller_history');
            if (data) {
                container.html(data);
            }
        }});
}

function pagingotp(page, record_per_page, total_pages, subtab)
{

    if (page > total_pages || page < 1)
    {
        $.notify({title: 'Error', text: 'You entered wrong page number!', image: '<i class="fa fa-exclamation-circle"></i>'}, {style: 'metro', className: 'error'});
        return false;
    }
    var base_url = $('#base_url').val();

    $.ajax({'url': base_url + '' + controller + '/paging_otp/' + page + '/' + record_per_page + '/' + subtab, 'type': 'POST', 'success': function (data) {
            var container = $('#otp_test');
            if (data) {
                container.html(data);
            }
        }});
}

function getAllDailyReport()
{
    var $btn = $('#get_report_btn').button('loading');
    var base_url = $('#base_url').val();
    
    var formData = $('#dailyReportForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/search_daily_report',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#search_daily_report_table');
            if (data) {
                container.html(data);
            }
        }});
}
function getDailyAnalysisAmount()
{
    var $btn = $('#get_report_btn').button('loading');
    var base_url = $('#base_url').val();
    
    var formData = $('#dailyReportForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/search_daily_analysis_amount',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            $btn.button('reset');
            var container = $('#search_system_analysis_table');
            if (data) {
                container.html(data);
            }
        }});
}
