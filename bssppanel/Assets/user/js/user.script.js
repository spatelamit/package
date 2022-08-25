var controller = 'user';
function countSenderLength(sender)
{
    $('#sender_length').html(sender.length);
}
function countMessageLength(message)
{
    if ($('#check_signature').prop('checked'))
    {
        message = message + " " + $('#signature').val();
    }
    $('#message_length').html(message.length);
    var length = 0;
    var total_credits = 0;
    if ($('#message_type').val() === '1')
    {
        length = message.length / 160;
        if (message.length % 160 === 0)
        {
            total_credits = parseInt(length);
        } else
        {
            total_credits = parseInt(length) + 1;
        }
    } else if ($('#message_type').val() === '2')
    {
        length = message.length / 70;
        if (message.length % 70 === 0)
        {
            total_credits = parseInt(length);
        } else
        {
            total_credits = parseInt(length) + 1;
        }
    }
    $('#credit').html(total_credits);
    $('#total_credits').val(total_credits);
    if (message.length === 0)
    {
        $('#credit').html(0);
        $('#total_credits').val(0);
    }
}
function getPage(controller, page)
{
    var base_url = $('#base_url').val();
    var url = base_url + '' + controller + '/' + page;
    $.ajax({'url': url, 'type': 'POST', 'success': function (data) {
            var container = $('#page_container');
            if (data) {
                container.html(data);
            }
        }});
}
function getSMSPreview()
{
    if ($('#message').val() === "" || $('#message').val().length === 0) {
        return false;
    }
    var base_url = $('#base_url').val();
    var message = $('#message').val();
    var file_name = $('#temp_file_name').val();
    var total_column = $('#total_column').val();
    message = message.replace(/\s{2,}/g, ' ');
    $.ajax({'url': base_url + '' + controller + '/get_sms_preview', 'type': 'POST', 'data': {'message': message, 'file_name': file_name, 'total_column': total_column}, 'success': function (data) {
            $('#campaign_div').addClass('hidden');
            $('#sender_div').addClass('hidden');
            $('#mobile_div').addClass('hidden');
            $('#message_div').addClass('hidden');
            $('#schdeule_div').addClass('hidden');
            $('#preview_div').removeClass('hidden');
            var container = $('#preview_div');
            if (data) {
                container.html(data);
            }
        }});
}
function getNumberToWords(no_of_sms)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    if (no_of_sms === "" || no_of_sms.length === 0)
    {
        $('span#notification').addClass("notification alert-warning");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please enter number of SMS');
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_number_to_words/' + no_of_sms, 'type': 'POST', 'success': function (data) {
            var container = $('#notification');
            if (data) {
                $('span#notification').addClass("notification alert-success");
                container.html(data);
            }
        }});
}
function getScheduleDateTime(type)
{
    if (type === 1)
    {
        $('#check_sch_sms').val('1');
        $('#schdeule_div').removeClass('hidden');
    } else
    {
        $('#check_sch_sms').val('0');
        $('#schdeule_div').addClass('hidden');
    }
}
function getFieldData(field_type)
{

    if (field_type === 'campaign')
    {
        $('#campaign_div').removeClass('hidden');
        $('#sender_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#message_div').addClass('hidden');
        $('#schdeule_div').addClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').addClass('hidden');
    } else if (field_type === 'sender')
    {
        $('#campaign_div').addClass('hidden');
        $('#sender_div').removeClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#message_div').addClass('hidden');
        $('#schdeule_div').addClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').addClass('hidden');
    } else if (field_type === 'mobile')
    {
        $('#campaign_div').addClass('hidden');
        $('#sender_div').addClass('hidden');
        $('#mobile_div').removeClass('hidden');
        $('#message_div').addClass('hidden');
        $('#schdeule_div').addClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').addClass('hidden');
    } else if (field_type === 'message')
    {
        $('#campaign_div').addClass('hidden');
        $('#sender_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#message_div').removeClass('hidden');
        $('#schdeule_div').addClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').addClass('hidden');
    } else if (field_type === 'schedule')
    {
        $('#campaign_div').addClass('hidden');
        $('#sender_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#message_div').addClass('hidden');
        $('#schdeule_div').removeClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').addClass('hidden');
    } else if (field_type === 'attach')
    {
        $('#campaign_div').addClass('hidden');
        $('#sender_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#message_div').addClass('hidden');
        $('#schdeule_div').addClass('hidden');
        $('#preview_div').addClass('hidden');
        $('#file_attach').removeClass('hidden');

    }
}
function getFieldDataVoice(field_type)
{
    if (field_type === 'campaigns')
    {
        $('#campaign_div').removeClass('hidden');
        $('#caller_ids_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#files_div').addClass('hidden');
    } else if (field_type === 'caller_ids')
    {
        $('#campaign_div').addClass('hidden');
        $('#caller_ids_div').removeClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#files_div').addClass('hidden');
    } else if (field_type === 'mobile')
    {
        $('#campaign_div').addClass('hidden');
        $('#caller_ids_div').addClass('hidden');
        $('#mobile_div').removeClass('hidden');
        $('#files_div').addClass('hidden');
    } else if (field_type === 'get_files')
    {
        $('#campaign_div').addClass('hidden');
        $('#caller_ids_div').addClass('hidden');
        $('#mobile_div').addClass('hidden');
        $('#files_div').removeClass('hidden');
    }
}
function pickField(field, value)
{
    var base_url = $('#base_url').val();
    if (field === 'campaign')
        $('#campaign_name').val(value);
    if (field === 'Attechment') {
        var message = $('#message').val();
        $('#message').val(message + value);
    }
    if (field === 'sender')
        $('#sender').val(value);

    if (field === 'mobile')
    {
        var value_array = value.split("|");
        if ($('#check_group' + value_array[2]).prop('checked'))
        {
            var pre_value = $('#show_groups').html();
            if (pre_value === "")
            {
                $('#show_groups').html("<a href='#' id=selected" + value_array[2] + ">" + value_array[1] + ", </a>");
            } else
            {
                $('#show_groups').html(pre_value + "<a href='#' id=selected" + value_array[2] + ">" + value_array[1] + ", </a>");
            }
        } else
        {
            var span = document.getElementById("selected" + value_array[2]);
            span.parentNode.removeChild(span);
        }
    }
    if (field === 'message' || field === 'Attechment')
    {
        if (field === 'message') {
            $('#message').val(value);
        }

        var message = $('#message').val();
        $('#message_length').html(message.length);
        var length = 0;
        var total_credits = 0;
        if ($('#message_type').val() === '1')
        {
            length = message.length / 160;
            if (message.length % 160 === 0)
            {
                total_credits = parseInt(length);
            } else
            {
                total_credits = parseInt(length) + 1;
            }
        } else if ($('#message_type').val() === '2')
        {
            length = message.length / 70;
            if (message.length % 70 === 0)
            {
                total_credits = parseInt(length);
            } else
            {
                total_credits = parseInt(length) + 1;
            }
        }
        $('#credit').html(total_credits);
        $('#total_credits').val(total_credits);
        if (message.length === 0)
        {
            $('#credit').html(0);
            $('#total_credits').val(0);
        }
    }
}
function showSignatureField()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var check = 0;

    if ($('#check_signature').prop('checked')) {
        check = 1;
    }

    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/save_signature/1', 'type': 'POST', 'data': {'check': check}, 'success': function (data) {
            var container = $('span#notification');
            if (data.type) {
                container.addClass("notification alert-success");
                container.html(data.message);
            } else
            {
                container.addClass("notification alert-danger");
                container.html(data.message);
            }
        }});
    if ($('#check_signature').prop('checked'))
    {
        $('#signature').removeClass("hidden");
    } else
    {
        $('#signature').addClass('hidden');
    }
}
function saveSignature()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var check = 0;

    if ($('#check_signature').prop('checked')) {

        var base_url = $('#base_url').val();
        var signature = $('#signature').val();
        $.ajax({'url': base_url + '' + controller + '/save_signature/2', 'type': 'POST', 'data': {'signature': signature}, 'success': function (data) {
                var container = $('span#notification');
                if (data.type) {
                    container.addClass("notification alert-success");
                    container.html(data.message);
                } else
                {
                    container.addClass("notification alert-danger");
                    container.html(data.message);
                }
            }});
    }
}

function showTrackerField()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var check = 0;

    if ($('#check_tracker').prop('checked')) {
        check = 1;
    }

    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/save_campaign_tracker/1', 'type': 'POST', 'data': {'check': check}, 'success': function (data) {
            var container = $('span#notification');
            if (data.type) {
                container.addClass("notification alert-success");
                container.html(data.message);
            } else
            {
                container.addClass("notification alert-danger");
                container.html(data.message);
            }
        }});
    if ($('#check_tracker').prop('checked'))
    {
        $('#tracker_link').removeClass("hidden");
    } else
    {
        $('#tracker_link').addClass('hidden');
    }
}

function saveTracker()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var check = 0;

    if ($('#check_tracker').prop('checked')) {

        var base_url = $('#base_url').val();
        var tracker_link = $('#tracker_link').val();
        $.ajax({'url': base_url + '' + controller + '/save_campaign_tracker/2', 'type': 'POST', 'data': {'tracker_link': tracker_link}, 'success': function (data) {
                var container = $('span#notification');
                if (data.type) {
                    container.addClass("notification alert-success");
                    container.html(data.message);
                } else
                {
                    container.addClass("notification alert-danger");
                    container.html(data.message);
                }
            }});
    }
}

function saveAsDraft()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var message = $('#message').val();
    if (message === "" || message.length === 0)
    {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please enter message');
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/save_as_draft', 'type': 'POST', 'data': {'message': message}, 'success': function (response) {
            var container = $('#show_drafts');
            container.html(response);
            var msg_type = $('#msg_type').val();
            var msg_data = $('#msg_data').val();
            if (msg_type === '1') {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(msg_data);
            } else if (msg_type === '0') {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html(msg_data);
            }
        }});
}
function deleteItems(type, id)
{
    
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete draft")) {
        $.ajax({'url': base_url + '' + controller + '/delete_items/' + type + '/' + id, 'type': 'POST', 'success': function (data) {
                
                if (type === "drafts") {
                    var container = $('#show_drafts');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                } else if (type === "senders") {
                    var container = $('#show_senders');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                } else if (type === "ATTACH") {
                    var container = $('#show-attach-drafts');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}

//Delete Draft Msg

function deleteDraftMsg(type, id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete draft")) {
        $.ajax({'url': base_url + '' + controller + '/delete_draft_msg/' + type + '/' + id, 'type': 'POST', 'success': function (data) {
                if (type === "drafts") {

                    var container = $('#show_drafts');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}

//Delete Voice Draft


function deleteDraftVoice(type, id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete draft")) {
        $.ajax({'url': base_url + '' + controller + '/delete_draft_voice/' + type + '/' + id, 'type': 'POST', 'success': function (data) {
                if (type === "drafts") {

                    var container = $('#show_voice_drafts');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}

function deleteDraftAttach(type, id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete draft")) {
        $.ajax({'url': base_url + '' + controller + '/delete_draft_attachment/' + type + '/' + id, 'type': 'POST', 'success': function (data) {
                if (type === "drafts") {

                    var container = $('#show_attach_drafts');
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}

function saveGroup()
{
    var $btn = $('#btngroup').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    var formData = $('#groupForm').serialize();
    $.ajax({url: base_url + '' + controller + '/save_group', type: 'POST', data: formData, success: function (response) {
            $btn.button('reset');
            var container = $('#show_contact_groups');
            if (response) {
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('#group_name').val('');
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function getGroupContacts(group_id, group_name, page, record_per_page)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var total_pages = $('#total_pages' + group_id).val();
    if (parseInt(page) > parseInt(total_pages))
    {
        $('span#notification').addClass("notification alert-warning");
        $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: You don't have more contacts!");
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_group_contacts/' + page + '/' + record_per_page + '/0', 'type': 'POST', 'data': {'group_name': group_name, 'group_id': group_id}, 'success': function (data) {
            var container = $('#show_group_contacts');
            if (data) {
                container.html(data);
            }
        }});
}
function pagingGroupContacts(page, record_per_page, total_pages)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    if (parseInt(page) > parseInt(total_pages))
    {
        $('span#notification').addClass("notification alert-warning");
        $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: You don't have more contacts!");
        return false;
    }
    var base_url = $('#base_url').val();
    var group_id = $('#group_id').val();
    var group_name = $('#update_group_name').val();
    $.ajax({'url': base_url + '' + controller + '/get_group_contacts/' + page + '/' + record_per_page + '/' + total_pages, 'type': 'POST', 'data': {'group_name': group_name, 'group_id': group_id}, 'success': function (data) {
            var container = $('#show_group_contacts');
            if (data) {
                container.html(data);
            }
        }});
}
function updateGroupName(group_id)
{
    var $btn = $('#btnupgroup').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    var group_name = $('#update_group_name').val();
    $.ajax({'url': base_url + '' + controller + '/update_group_name/' + group_id, 'type': 'POST', 'data': {'group_name': group_name}, 'success': function (data) {
            $btn.button('reset');
            var container = $('#show_group_contacts');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function deleteGroup(group_id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    if (confirm("Are you sure you want to delete this group!")) {
        var base_url = $('#base_url').val();
        $.ajax({'url': base_url + '' + controller + '/delete_group/' + group_id, 'type': 'POST', 'success': function (data) {
                var container = $('#show_phonebook');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}
function deleteContacts(group_id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var total_contacts = $('#total_contacts').val();
    if (total_contacts === '0')
    {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: No contact selected!');
        return false;
    } else {
        if (confirm("Are you sure you want to delete this selected contact(s)!")) {
            var base_url = $('#base_url').val();
            var group_name = $('#update_group_name').val();
            var selected_contacts = $('#selected_contacts').val();
            $.ajax({'url': base_url + '' + controller + '/delete_contacts/' + group_id, 'type': 'POST', 'data': {'selected_contacts': selected_contacts, 'group_name': group_name}, 'success': function (data) {
                    var container = $('#show_group_contacts');
                    if (data) {
                        container.html(data);
                        var msg_type = $('#msg_type').val();
                        var msg_data = $('#msg_data').val();
                        if (msg_type === '1') {
                            $('span#notification').addClass("notification alert-success");
                            $('span#notification').html(msg_data);
                        } else if (msg_type === '0') {
                            $('span#notification').addClass("notification alert-danger");
                            $('span#notification').html(msg_data);
                        }
                    }
                }});
        }
    }
}
function selectAllContacts()
{
    var selected_contacts = "";
    if ($('#select_contacts').prop('checked'))
    {
        var total_contacts = 0;
        $('.check_contact').each(function (index, value) {
            this.checked = true;
            total_contacts++;
            if (selected_contacts === "")
                selected_contacts = $(value).val();
            else
                selected_contacts += "," + $(value).val();
        });
        $('#delete_option').removeClass('hidden');
        $('#delete_button').val('Delete ' + total_contacts + ' Contacts');
        $('#selected_contacts').val(selected_contacts);
    } else
    {
        $('.check_contact').each(function () {
            this.checked = false;
        });
        $('#delete_option').addClass('hidden');
        $('#delete_button').val('Delete 0 Contacts');
        $('#selected_contacts').val(selected_contacts);
    }
}
function showDeleteOption()
{
    var selected_contacts = "";
    var number = 1;
    var count = 0;
    $('.check_contact').each(function (index, value) {
        if ($(value).prop('checked'))
        {
            if (selected_contacts === "")
                selected_contacts = $(value).val();
            else
                selected_contacts += "," + $(value).val();
            count++;
        }
        number++;
    });
    if (selected_contacts === "")
    {
        $('#delete_option').addClass('hidden');
        $('#delete_button').val('Delete 0 Contacts');
        $('#selected_contacts').val(selected_contacts);
    } else
    {
        $('#delete_option').removeClass('hidden');
        $('#delete_button').val('Delete ' + count + ' Contacts');
        $('#selected_contacts').val(selected_contacts);
    }
}
function selectColumn(column)
{
    var message = $('#message').val();
    if (message === "")
    {
        $('#message').val(message + " ##C" + column + "##");
        $('#message').focus();
    } else
    {
        $('#message').val(message + " ##C" + column + "##");
        $('#message').focus();
    }
    var selected_column = $('#selected_column').val();
    if (selected_column === "")
    {
        $('#selected_column').val(selected_column + "" + column);
    } else
    {
        $('#selected_column').val(selected_column + "," + column);
    }
}
function calculateAmount(price)
{
    var total = 0;
    var sms_balance = $('#sms_balance').val();
    total = total + (sms_balance * price);
    $('#amount').val(total)
}
function checkDNSSetting()
{
    var base_url = $('#base_url').val();
    var domain_name = $('#domain_name').val();
    if (domain_name === "" || domain_name.length === 0)
    {
        $('#check_dns_msg').html("<span style=color:red>Please Enter Valid Domain Name</span>");
        return false;
    }
    var dataArray = new Array(1);
    dataArray[0] = domain_name;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/check_dns_settings', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#check_dns_msg');
            if (data) {
                container.html(data);
            }
        }});
}
function getWebsite(company, domain, website_id)
{
    $('#company_name').val(company);
    $('#domain_name').val(domain);
    $('#website_id').val(website_id);
}
function selectWebsite(website)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_website_subtab/' + website, 'type': 'POST', 'success': function (data) {
            var container = $('#website_subtab');
            if (data) {
                container.html(data);
            }
        }});
}
function deleteWebData(type, website_id, key, img_name)
{
    var dataArray = new Array(1);
    dataArray[0] = img_name;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete this image")) {
        $.ajax({'url': base_url + '' + controller + '/delete_web_data/' + type + '/' + website_id + '/' + key, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
                var container = $('#website_subtab');
                if (data) {
                    container.html(data);
                }
            }});
    }
}
function deleteWebsite(website_id)
{
    var $btn = $('#delete_website').button('loading');
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete")) {
        $.ajax({'url': base_url + '' + controller + '/delete_website/' + website_id, 'type': 'POST', 'success': function (data) {
                $btn.button('reset');
                var container = $('#website_subtab');
                if (data) {
                    container.html(data);
                }
            }});
    }
}
function saveWebsite()
{
    var $btn = $('#btnwebsite').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    var company_name = $('#company_name').val();
    var domain_name = $('#domain_name').val();
    var website_id = $('#website_domain').val();
    var dataArray = new Array(2);
    dataArray[0] = company_name;
    dataArray[1] = domain_name;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/save_website/' + website_id, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#website_subtab');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function saveWebsiteData(tab)
{
    var $btn = $('#btn' + tab).button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    var website_id = $('#website_domain').val();
    var url = base_url + '' + controller + '/save_website_data1/' + website_id + '/' + tab;
    if (tab === 'banner' || tab === 'about_us' || tab === 'clients')
    {
        if (tab === 'banner') {
            var banner_text = $('#banner_text').val();
            var banner = 0;
            if ($('#banner1').prop('checked'))
            {
                banner = 1;
            } else if ($('#banner2').prop('checked'))
            {
                banner = 2;
            } else if ($('#banner3').prop('checked'))
            {
                banner = 3;
            }
            var company_logo_name = $('#company_logo_name').val();
            var file_data = $('#company_logo').prop('files')[0];
            var form_data = new FormData();
            form_data.append('banner_text', banner_text);
            form_data.append('banner', banner);
            form_data.append('image_name', company_logo_name);
            form_data.append('company_logo', file_data);
        } else if (tab === 'about_us') {
            var about_us_text = $('#about_us_text').val();
            var about_us_image_name = $('#about_us_image_name').val();
            var file_data = $('#about_us_image').prop('files')[0];
            var form_data = new FormData();
            form_data.append('about_us_text', about_us_text);
            form_data.append('image_name', about_us_image_name);
            form_data.append('about_us_image', file_data);
        } else if (tab === 'clients') {
            var file_data = $('#about_us_image').prop('files')[0];
            var form_data = new FormData();
            form_data.append('about_us_image', file_data);
        }
        $.ajax({url: url, type: 'POST', data: form_data, dataType: 'text', cache: false, contentType: false, processData: false, success: function (response) {
                if (response) {
                    $btn.button('reset');
                    $('#show_website_subtab').html(response);
                    var msg_type = $('#msg_type1').val();
                    var msg_data = $('#msg_data1').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    } else
    {
        if (tab === 'services') {
            var formData = $('#serviceForm').serialize();
        } else if (tab === 'contact_us') {
            var formData = $('#contactForm1').serialize();
        } else if (tab === 'social') {
            var formData = $('#socialForm').serialize();
        } else if (tab === 'seo') {
            var formData = $('#seoForm').serialize();
        } else if (tab === 'other') {
            var formData = $('#otherForm').serialize();
        }
        $.ajax({url: url, type: 'POST', data: formData, success: function (response) {
                if (response) {
                    $btn.button('reset');
                    $('#show_website_subtab').html(response);
                    var msg_type = $('#msg_type1').val();
                    var msg_data = $('#msg_data1').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}
function changeRoute(route)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_route/' + route, 'type': 'POST', 'success': function (data) {
            var container = $('#show_change_route');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function getUserTabs(user, tab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_user_tabs/' + tab + '/' + user, 'type': 'POST', 'success': function (data) {
            var container = $('#show_users_tab');
            if (data) {
                container.html(data);
            }
        }});
}

function getDlrGraph(user, tab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_dlr_graph/' + tab + '/' + user, 'type': 'POST', 'success': function (data) {
            var container = $('#show_users_tab');
            if (data) {
                container.html(data);
            }
        }});
}

function changeUserStatus(user_id, status, tab)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_user_status/' + user_id + '/' + status + '/' + tab, 'type': 'POST', 'success': function (data) {
            var container = $('#show_users_tab');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
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
function saveUserInfo(ref_user_id, tab, subtab)
{

    var $btn = $('#btn' + tab + subtab).button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    if (tab === 1 && subtab === 1)
    {
        var ajaxData = $('#fundForm').serialize();
    } else if (tab === 1 && subtab === 2)
    {
        var ajaxData = $('#expiryForm').serialize();
    } else if (tab === 1 && subtab === 3)
    {
        var ajaxData = "";
    } else if (tab === 1 && subtab === 4)
    {
        var ajaxData = $('#accountForm').serialize();
    } else if (tab === 1 && subtab === 5)
    {
        var ajaxData = $('#bKeywordForm').serialize();
    } else if (tab === 2 && subtab === 1)
    {
        var ajaxData = $('#profileForm').serialize();
    } else if (tab === 2 && subtab === 2)
    {
        var ajaxData = $('#passwordForm').serialize();
    } else if (tab === 2 && subtab === 3)
    {
        var ajaxData = $('#alertForm').serialize();
    } else if (tab === 1 && subtab === 6)
    {
        var ajaxData = $('#spacialRatioTR').serialize();
    } else if (tab === 1 && subtab === 7)
    {
        var ajaxData = $('#spacialRatioPR').serialize();
    }
    $.ajax({'url': base_url + '' + controller + '/save_user_info/' + ref_user_id + '/' + tab + '/' + subtab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {

            var container = $('#show_users_tab');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();

                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function getNumberToWordsU(no_of_sms)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    if (no_of_sms === "" || no_of_sms.length === 0)
        return false;
    $.ajax({'url': base_url + '' + controller + '/get_number_to_words/' + no_of_sms, 'type': 'POST', 'success': function (data) {
            var container = $('#notification');
            if (data) {
                container.addClass("notification alert-success");
                container.html(data);
            }
        }});
}
function saveUser()
{
    var $btn = $('#save_btn').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#addNewForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_user', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#show_users_tab');
            if (data.status === '200')
            {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(data.message);
                $('#addNewForm').each(function () {
                    this.reset();
                });
                $btn.button('reset');
            } else
            {
                if (data.status === '101')
                {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(data.message);
                    $btn.button('reset');
                } else if (data.status === '102')
                {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(data.message);
                    $btn.button('reset');
                }
            }
        }});
}
function getNewUsername(username)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    if (username === "" || username.length === 0)
    {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please enter username!');
        return false;
    }
    var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
    if (!pattern_username.test(username))
    {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Username must be start with a character!');
        return false;
    }
    if (username.length <= 5)
    {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Username must be 5 Characters long!');
        return false;
    }
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/get_username/' + username, 'type': 'POST', 'success': function (data) {
            if (data) {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html("<i class='fa fa-check-circle'></i> Success: Username available!");
            } else
            {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Username not available! Please try another");
            }
        }});
}
function searchUser(user)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_user/' + user, 'type': 'POST', 'success': function (data) {
            var container = $('#show_users');
            if (data) {
                container.html(data);
            }
        }});
}
function searchDReports(search)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_delivery_reports/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#data_table');
            if (data) {
                container.html(data);
            }
        }});
}

function searchDscheduleReports(search)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_delivery_schedule_reports/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#data_table');
            if (data) {
                container.html(data);
            }
        }});
}

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
function searchGroupContact(search, group_id)
{
    var base_url = $('#base_url').val();
    var dataArray = new Array(2);
    dataArray[0] = search;
    dataArray[1] = group_id;
    $.ajax({'url': base_url + '' + controller + '/search_group_contact/' + group_id + '/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#show_contacts');
            if (data) {
                container.html(data);
            }
        }});
}
function exportInExcel(campaign_id)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '/export/index/' + campaign_id, 'type': 'POST', 'success': function (data) {
        }});
}
function saveResellerSetting(form_type, tab)
{
    var $btn = $('#btnreseller' + form_type).button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    if (form_type == 1)
    {
        var ajaxData = $('#resellerSSMSForm').serialize();
    } else if (form_type == 2)
    {
        var ajaxData = $('#resellerSMailForm').serialize();
    } else if (form_type == 3)
    {
        var ajaxData = $('#resellerOtherForm').serialize();
    } else if (form_type == 4)
    {
        var ajaxData = $('#resellerDemoForm').serialize();
    } else if (form_type == 5)
    {
        var ajaxData = $('#notificationForm').serialize();
    }
    $.ajax({'url': base_url + '' + controller + '/save_reseller_setting/' + form_type + '/' + tab, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            $('#reseller_settings').html(data);
            var msg_type = $('#msg_type').val();
            var msg_data = $('#msg_data').val();
            if (msg_type === '1') {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(msg_data);
            } else if (msg_type === '0') {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html(msg_data);
            }
        }});
}
function saveSenderId()
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#senderIdForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_sender_id', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $('#data_table').html(data);
            var msg_type = $('#msg_type').val();
            var msg_data = $('#msg_data').val();
            if (msg_type === '1') {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(msg_data);
            } else if (msg_type === '0') {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html(msg_data);
            }
        }});
}
function saveKeyword(type)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#keywordForm').serialize();
    $.ajax({'url': base_url + '' + controller + '/save_keyword/' + type, 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $('#data_table').html(data);
            var msg_type = $('#msg_type').val();
            var msg_data = $('#msg_data').val();
            if (msg_type === '1') {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(msg_data);
            } else if (msg_type === '0') {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html(msg_data);
            }
        }});
}
function deleteUserData(type, index)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    if (type === 'sender')
    {
        var url = base_url + '' + controller + '/delete_sender_id/' + index;
    } else if (type === 'keyword' || type === 'black_keyword')
    {
        var url = base_url + '' + controller + '/delete_keyword/' + index + '/' + type;
    }
    if (confirm("Are you sure want to delete " + type)) {
        $.ajax({'url': url, 'type': 'POST', 'success': function (data) {
                $('#data_table').html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }});
    }
}
function exportAllReports(user_id)
{
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
    $.ajax({'url': base_url + '/export/deliver_reports/' + user_id, 'type': 'POST', 'data': {'from': export_from_date, 'to': export_to_date}, 'success': function (data) {
            var url = base_url + "/Reports/" + data;
            window.open(url);
        }});
}




function sechduleCancel(campaign_id)
{

    var $btn = $('#save_routing').button('loading');
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to  cancel this Campaign !"))
    {

        $.ajax({url: base_url + 'user/cancel_sch_sms/' + campaign_id, type: 'POST', success: function (data) {
                window.location = "http://sms.bulksmsserviceproviders.com/user/schedule_reports";

            }});
    }
}


function exportAllMissedCallReports(user_id)
{
    var $btn = $('#export_all').button('loading');
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
    $.ajax({'url': base_url + '/export/missed_call_reports/' + user_id, 'type': 'POST', 'data': {'from': export_from_date, 'to': export_to_date}, 'success': function (data) {
            $btn.button('reset');
            var url = base_url + "/Reports/" + data;
            window.open(url);
        }});
}
function deleteColumn(key, group_id, col_id, group_name)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var url = base_url + '' + controller + '/delete_column';
    if (confirm("Are you sure want to delete this column")) {
        $.ajax({'url': url, 'type': 'POST', 'data': {'key': key, 'group_id': group_id, 'col_id': col_id, 'group_name': group_name}, 'success': function (data) {
                var container = $('#show_group_contacts');
                if (data) {
                    container.html(data);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}
function saveSLKeyword(type)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var $btn = $('#save_keyword').button('loading');
    var base_url = $('#base_url').val();
    if (type === 'short') {
        var form = $('#shortKeywordForm');
        var formData = $('#shortKeywordForm').serialize();
    } else if (type === 'long') {
        var form = $('#longKeywordForm');
        var formData = $('#longKeywordForm').serialize();
    }
    $.ajax({url: base_url + '' + controller + '/save_sl_keyword/' + type, type: 'POST', data: formData, success: function (response) {
            var container = $('#data_table');
            if (response) {
                $btn.button('reset');
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    form.find('input:text, input:password, input:file, select, textarea').val('');
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function saveSLKReply(type)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var $btn = $('#save_keyword_reply').button('loading');
    var base_url = $('#base_url').val();
    if (type === 'short') {
        var form = $('#shortKeywordReplyForm');
        var formData = $('#shortKeywordReplyForm').serialize();
    } else if (type === 'long') {
        var form = $('#longKeywordReplyForm');
        var formData = $('#longKeywordReplyForm').serialize();
    }
    $.ajax({url: base_url + '' + controller + '/save_sl_keyword_reply/' + type, type: 'POST', data: formData, success: function (response) {
            var container = $('#data_table');
            if (response) {
                $btn.button('reset');
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    form.find('input:text, input:password, input:file, select, textarea').val('');
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function saveKeywordReply(number, keyword_id)
{
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning").html("");
    var base_url = $('#base_url').val();
    var reply_sender = $('#reply_sender' + number).val();
    if (reply_sender === '' && reply_sender.length === 0) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please enter sender id!');
        return false;
    }
    if (reply_sender.length != 6) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Sender id must be of 6 character long!');
        return false;
    }
    var reply_content = $('#reply_content' + number).val();
    if (reply_content === '' && reply_content.length === 0) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please enter reply content!');
        return false;
    }
    var type = $('#keyword_type' + number).val();
    $.ajax({url: base_url + '' + controller + '/save_keyword_reply/' + type, type: 'POST', data: {reply_sender: reply_sender, reply_content: reply_content, keyword_id: keyword_id, number: number}, success: function (response) {
            var container = $('#keyword_reply' + number);
            if (response) {
                $('.keyword_reply').popover('hide');
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success").html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger").html(msg_data);
                }
            }
        }});
}
function deleteSLKeyword(type, keyword_id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete " + type + ' keyword')) {
        $.ajax({url: base_url + '' + controller + '/delete_sl_keyword/' + type, type: 'POST', data: {keyword_id: keyword_id}, success: function (response) {
                var container = $('#data_table');
                if (response) {
                    container.html(response);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}
function deleteSLKReply(type, keyword_reply_id)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (confirm("Are you sure want to delete " + type + ' keyword reply')) {
        $.ajax({url: base_url + '' + controller + '/delete_sl_keyword_reply/' + type, type: 'POST', data: {keyword_reply_id: keyword_reply_id}, success: function (response) {
                var container = $('#data_table');
                if (response) {
                    container.html(response);
                    var msg_type = $('#msg_type').val();
                    var msg_data = $('#msg_data').val();
                    if (msg_type === '1') {
                        $('span#notification').addClass("notification alert-success");
                        $('span#notification').html(msg_data);
                    } else if (msg_type === '0') {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html(msg_data);
                    }
                }
            }});
    }
}
function filterKeywordReplies(type, keyword_id)
{
    var base_url = $('#base_url').val();
    $.ajax({url: base_url + '' + controller + '/filter_keyword_replies/' + keyword_id + '/' + type, type: 'POST', success: function (response) {
            var container = $('#data_table');
            if (response) {
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
function checkKeywordAvailability(type, keyword)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    if (type === 'short')
    {
        var number = $('#short_number').val();
    } else if (type === 'long')
    {
        var number = $('#long_number').val();
        if (number === "") {
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please enter long number');
            return false;
        }
        /*   if (number.length < 10) {
         $('span#notification').addClass("notification alert-danger");
         $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Long number must be of 10 digits');
         return false;
         }*/
        if (keyword === "") {
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please enter keyword');
            return false;
        }
        if (keyword.length < 2) {
            $('span#notification').addClass("notification alert-danger");
            $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Keyword must be of 3 characters');
            return false;
        }
    }
    $.ajax({url: base_url + '' + controller + '/check_keyword_availability/' + type, type: 'POST', data: {number: number, keyword: keyword}, success: function (data) {
            if (data) {
                var container = $('span#notification');
                if (data.type) {
                    container.addClass("notification alert-success");
                    container.html(data.message);
                } else
                {
                    container.addClass("notification alert-danger");
                    container.html(data.message);
                }
            }
        }});
}
var global_button = "";
$(document).ready(function () {
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), nowDate.getSeconds(), 0);
    var m = nowDate.getMonth();
    if (m < 10) {
        m = parseInt(m) + 1;
        if (m < 10) {
            m = "0" + m;
        }
    }
    var d = nowDate.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var cur_date = String(nowDate.getFullYear() + "-" + m + "-" + d + " " + nowDate.getHours() + ":" + nowDate.getMinutes() + ":" + nowDate.getSeconds());
    $("button[type='submit']").click(function (event) {
        global_button = this.id;
        $('span#notification').html("");
        $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    });
    $("form#sendSMSForms").validate({rules: {"campaign_name": {required: true}, "sender": {required: true, minlength: 6, maxlength: 6}, "message": {required: true}}, messages: {"campaign_name": {required: "Please enter campaign name"}, "sender": {required: 'Please enter sender id', minlength: 'Sender id must be 6 char. long', maxlength: 'Sender id must be 6 char. long'}, "message": {required: 'Please enter message'}}, submitHandler: function () {
            $('span#notification').html("");
            $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
            var mobile = $('#mobile_numbers').val();
            var csv = $('#mobiles').val();
            var checked_groups = 0;
            $('.checkboxes').each(function (index, value) {
                if (this.checked === true) {
                    checked_groups++;
                }
            });
            if ((mobile === "" && mobile.length === 0) && (csv === "" && csv.length === 0) && (checked_groups === 0)) {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please provide mobile number(s) or upload csv or select contact group");
                return false;
            }
            if (global_button === 'schedule_sms') {
                var schedule_date = $('#schedule_date').val();
                if (schedule_date === "" && schedule_date.length === 0) {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter schedule date");
                    return false;
                }
                if (schedule_date <= cur_date) {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid schedule date. It must be greater than now");
                    return false;
                }
            }
            var $btn = $('#' + global_button).button('loading');
            var base_url = $('#base_url').val();
            var formData = new FormData($('form#sendSMSForms')[0]);
            $.ajax({url: base_url + '' + controller + '/send_sms1/' + global_button, type: 'POST', data: formData, async: true, cache: false, contentType: false, processData: false, success: function (response) {
                    $btn.button('reset');
                    if (response) {
                        if (response.type == '100') {
                            var container = $('span#notification');
                            container.addClass("notification alert-danger");
                            container.html(response.message);
                        } else
                        {
                            var container = $('#send_sms_form');
                            container.html(response);
                            var msg_type = $('#msg_type').val();
                            var msg_data = $('#msg_data').val();
                            if (msg_type === '1') {
                                $('span#notification').addClass("notification alert-success");
                                $('span#notification').html(msg_data);
                            } else if (msg_type === '0') {
                                $('span#notification').addClass("notification alert-danger");
                                $('span#notification').html(msg_data);
                            }
                            $('form#sendSMSForms')[0].reset();
                            global_button = "";
                        }
                    }
                }});
        }});
});
function reSendSMS() {
    var $btn = $('#resend').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#resend_sms_form').serialize();
    $.ajax({'url': base_url + '' + controller + '/resend_sms', 'type': 'POST', 'data': ajaxData, 'success': function (data) {

            $btn.button('reset');
            var container = $('span#notification');
            if (data.type) {
                container.addClass("notification alert-success");
                container.html(data.message);
            } else {
                container.addClass("notification alert-danger");
                container.html(data.message);
            }
        }});
}

//resend schedule
function reSendSchedule() {
    var $btn = $('#resend_sch').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#resend_schedule_form').serialize();
    $.ajax({'url': base_url + '' + controller + '/resend_sms', 'type': 'POST', 'data': ajaxData, 'success': function (data) {

            $btn.button('reset');
            var container = $('span#notification');
            if (data.type) {
                container.addClass("notification alert-success");
                container.html(data.message);
            } else {
                container.addClass("notification alert-danger");
                container.html(data.message);
            }
        }});
}

function sendVoiceSMS() {
    var $btn = $('#send_sms').button('loading');
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger");
    var base_url = $('#base_url').val();
    var ajaxData = $('#sendVoiceSMSForms').serialize();
    alert(ajaxData);
    $.ajax({'url': base_url + '' + controller + '/send_voice_sms', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            alert(data)
            $btn.button('reset');
            $('#send_voice_sms_form').html(data);
            var msg_type = $('#msg_type').val();
            var msg_data = $('#msg_data').val();
            if (msg_type === '1') {
                $('span#notification').addClass("notification alert-success");
                $('span#notification').html(msg_data);
            } else if (msg_type === '0') {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html(msg_data);
            }
        }});
}
function showFileDiv(divname)
{

    if (divname === 'FileDiv2') {
        $('#FileDiv2').removeClass('hidden');
        $('#FileDiv3').addClass('hidden');
        $('#FileDiv4').addClass('hidden');
        $('#FileDiv6').addClass('hidden');

    }
    if (divname === 'FileDiv3') {
        $('#FileDiv2').addClass('hidden');
        $('#FileDiv3').removeClass('hidden');
        $('#FileDiv4').addClass('hidden');
        $('#FileDiv6').addClass('hidden');

    }
    if (divname === 'FileDiv4') {
        $('#FileDiv2').addClass('hidden');
        $('#FileDiv3').addClass('hidden');
        $('#FileDiv4').removeClass('hidden');
        $('#FileDiv6').addClass('hidden');

    }
    if (divname === 'FileDiv6') {

        $('#FileDiv2').addClass('hidden');
        $('#FileDiv3').addClass('hidden');
        $('#FileDiv4').addClass('hidden');
        $('#FileDiv6').removeClass('hidden');

    }
}

function changeVoiceRoute(route)
{
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/change_voice_route/' + route, 'type': 'POST', 'success': function (data) {
            var container = $('#show_change_route');
            if (data) {
                container.html(data);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}
$(document).ready(function ()
{
    $('[data-toggle="tooltip"]').tooltip()
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), nowDate.getSeconds(), 0);
    var m = nowDate.getMonth();
    if (m < 10) {
        m = parseInt(m) + 1;
        if (m < 10) {
            m = "0" + m;
        }
    }
    var d = nowDate.getDate();
    if (d < 10) {
        d = "0" + d;
    }
    var cur_date = String(nowDate.getFullYear() + "-" + m + "-" + d + " " + nowDate.getHours() + ":" + nowDate.getMinutes() + ":" + nowDate.getSeconds());
    $("#start_date_time").datetimepicker({format: "yyyy-mm-dd hh:ii:00", autoclose: true, todayBtn: true, startDate: today, minuteStep: 5, orientation: 'left'});
    $("#end_date_time").datetimepicker({format: "yyyy-mm-dd hh:ii:00", autoclose: true, todayBtn: true, startDate: today, minuteStep: 5, orientation: 'left'});
    $(".upload_files").filestyle();
    $("form#sendVoiceSMSForms").validate({rules: {"campaign_name": {required: true}, "caller_id": {required: true, minlength: 10, maxlength: 12}, "duration": {required: true}}, messages: {"campaign_name": {required: "Please enter campaign name"}, "caller_id": {required: 'Please enter caller id', minlength: 'Caller id must be 10 digits long'}, "duration": {required: 'Please enter duration'}}, submitHandler: function () {
            $('span#notification').html("");
            $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
            var mobile = $('#mobile_numbers').val();
            var csv = $('#mobiles').val();
            var checked_groups = 0;
            $('.checkboxes').each(function (index, value) {
                if (this.checked === true) {
                    checked_groups++;
                }
            });
            if ((mobile === "" && mobile.length === 0) && (csv === "" && csv.length === 0) && (checked_groups === 0)) {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please provide mobile number(s) or upload csv");
                return false;
            }
            var upload_voice_file = $('#upload_voice_file').val();
            var voice_file_url = $('#voice_file_url').val();
            if ((upload_voice_file === "" && upload_voice_file.length === 0) && (voice_file_url === "" && voice_file_url.length === 0) && $("input[name=voice_draft_file]").val() === "") {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please provide voice file or file url");
                return false;
            }
            /*
             var start_date_time = $('#start_date_time').val();
             var end_date_time = $('#end_date_time').val();
             if (start_date_time === "" && start_date_time.length === 0) {
             $('span#notification').addClass("notification alert-danger");
             $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter start date-time");
             return false;
             }
             if (end_date_time === "" && end_date_time.length === 0) {
             $('span#notification').addClass("notification alert-danger");
             $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter end date-time");
             return false;
             }
             if (start_date_time <= cur_date) {
             $('span#notification').addClass("notification alert-danger");
             $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid start date-time. It must be greater than now");
             return false;
             }
             if (end_date_time <= cur_date) {
             $('span#notification').addClass("notification alert-danger");
             $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time. It must be greater than now");
             return false;
             }
             if (end_date_time <= start_date_time) {
             $('span#notification').addClass("notification alert-danger");
             $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please enter valid end date-time. It must be greater than start date-time date");
             return false;
             }
             */
            var $btn = $('#send_voice_sms').button('loading');
            var base_url = $('#base_url').val();
            var formData = new FormData($('form#sendVoiceSMSForms')[0]);
            $.ajax({url: base_url + '' + controller + '/send_voice_sms', type: 'POST', data: formData, async: true, cache: false, contentType: false, processData: false, success: function (response) {
                    $btn.button('reset');
                    var container = $('#send_voice_sms_form');
                    if (response) {
                        container.html(response);
                        var msg_type = $('#msg_type').val();
                        var msg_data = $('#msg_data').val();
                        if (msg_type === '1') {
                            $('span#notification').addClass("notification alert-success");
                            $('span#notification').html(msg_data);
                        } else if (msg_type === '0') {
                            $('span#notification').addClass("notification alert-danger");
                            $('span#notification').html(msg_data);
                        }
                        $('#form#sendVoiceSMSForms')[0].reset();
                    }
                }});
        }});
});

// Missed Call Alerts Services
function saveAutoReply(number, service_id)
{
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning").html("");
    var base_url = $('#base_url').val();
    var reply_sender = $('#reply_sender' + number).val();
    if (reply_sender === '' && reply_sender.length === 0) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please enter sender id!');
        return false;
    }
    if (reply_sender.length != 6) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Sender id must be of 6 character long!');
        return false;
    }
    var reply_content = $('#reply_content' + number).val();
    if (reply_content === '' && reply_content.length === 0) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please enter reply content!');
        return false;
    }
    $.ajax({url: base_url + '' + controller + '/save_auto_reply', type: 'POST',
        data: {reply_sender: reply_sender, reply_content: reply_content, service_id: service_id, number: number}, success: function (response) {
            var container = $('#auto_reply' + number);
            if (response) {
                $('.mc_auto_reply').popover('hide');
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success").html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger").html(msg_data);
                }
            }
        }});
}
function saveUserWhite() {

    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/save_user_white/', 'type': 'POST', 'success': function (data) {
            if (data) {
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                }
            }
        }});
}

//search user balance
function searchExistBalance()
{
    var $btn = $('#search').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#searchExistBalance').serialize();
    $.ajax({'url': base_url + '' + controller + '/search_exist_balance', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#exist_balance');

            if (data) {
                container.html(data);
            }
        }});
}

// search user balance by username
function searchAllBalance(search)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/search_all_balance/' + search, 'type': 'POST', 'success': function (data) {
            var container = $('#exist_balance');
            if (data) {
                container.html(data);
            }
        }});
}
// Get total consumption by date 


function getReportAccountUserSms()
{
    var $btn = $('#search').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#getReportAccountUserSms').serialize();
    $.ajax({'url': base_url + '' + controller + '/search_account_cunsumtion', type: 'POST', data: formData, 'success': function (data) {
            $btn.button('reset')
            var container = $('#all_user_cunsumption');

            if (data) {
                container.html(data);
            }
        }});
}


