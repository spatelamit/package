//===============================================================================//
// Load the Google Transliterate API
/*
 google.load("elements", "1", {
 packages: "transliteration"
 });
 */
/*
 google.load("elements", "1", {packages: "transliteration", "nocss": true});
 
 var transliterationControl;
 function onLoad() {
 var options = {
 sourceLanguage: 'en',
 destinationLanguage: ['hi', 'ar', 'kn', 'ml', 'ta', 'te'],
 transliterationEnabled: false
 };
 // Create an instance on TransliterationControl with the required
 // options.
 transliterationControl = new google.elements.transliteration.TransliterationControl(options);
 
 // Enable transliteration in the textfields with the given ids.
 var ids = ["message"];
 transliterationControl.makeTransliteratable(ids);
 
 // Add the STATE_CHANGED event handler to correcly maintain the state
 // of the checkbox.
 transliterationControl.addEventListener(google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED, transliterateStateChangeHandler);
 
 // Add the SERVER_UNREACHABLE event handler to display an error message
 // if unable to reach the server.
 transliterationControl.addEventListener(google.elements.transliteration.TransliterationControl.EventType.SERVER_UNREACHABLE, serverUnreachableHandler);
 
 // Add the SERVER_REACHABLE event handler to remove the error message
 // once the server becomes reachable.
 transliterationControl.addEventListener(google.elements.transliteration.TransliterationControl.EventType.SERVER_REACHABLE, serverReachableHandler);
 
 // Set the checkbox to the correct state.
 //document.getElementById('checkboxId').checked = transliterationControl.isTransliterationEnabled();
 
 // Populate the language dropdown
 var destinationLanguage = transliterationControl.getLanguagePair().destinationLanguage;
 var languageSelect = document.getElementById('unicode_type');
 var unicode_div = document.getElementById('unicode_div');
 var ul = document.createElement('ul');
 unicode_div.appendChild(ul);
 var att1 = document.createAttribute("class");
 att1.value = "dropdown-menu";
 ul.setAttributeNode(att1);
 var att2 = document.createAttribute("role");
 att2.value = "menu";
 ul.setAttributeNode(att2);
 var supportedDestinationLanguages = google.elements.transliteration.getDestinationLanguages(google.elements.transliteration.LanguageCode.ENGLISH);
 for (var lang in supportedDestinationLanguages) {
 var li = document.createElement('li');
 var a = document.createElement('a');
 ul.appendChild(li);
 li.appendChild(a);
 var att = document.createAttribute("href");
 att.value = "javascript:languageChangeHandler('" + supportedDestinationLanguages[lang] + "')";
 if (destinationLanguage == att.value) {
 //opt.selected = true;
 }
 a.setAttributeNode(att);
 a.innerHTML = a.innerHTML + lang;
 
 /*
 var opt = document.createElement('option');
 opt.text = lang;
 opt.value = supportedDestinationLanguages[lang];
 if (destinationLanguage == opt.value) {
 opt.selected = true;
 }
 try {
 languageSelect.add(opt, null);
 } catch (ex) {
 languageSelect.add(opt);
 }
 */
/*
 }
 }
 
 // Handler for STATE_CHANGED event which makes sure checkbox status
 // reflects the transliteration enabled or disabled status.
 function transliterateStateChangeHandler(e) {
 document.getElementById('checkboxId').checked = e.transliterationEnabled;
 }
 
 // Handler for checkbox's click event.  Calls toggleTransliteration to toggle
 // the transliteration state.
 function checkboxClickHandler(type) {
 if (type === '2')
 {
 document.getElementById('unicode_type').className = "btn btn-primary btn-sm dropdown-toggle";
 transliterationControl.enableTransliteration();
 }
 else if (type === '1')
 {
 document.getElementById('unicode_type').className = "btn btn-primary btn-sm dropdown-toggle hidden";
 transliterationControl.disableTransliteration();
 }
 //transliterationControl.toggleTransliteration();
 }
 
 // Handler for dropdown option change event.  Calls setLanguagePair to
 // set the new language.
 function languageChangeHandler(value) {
 var dropdown = document.getElementById('unicode_type');
 alert(value)
 //transliterationControl.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, dropdown.options[dropdown.selectedIndex].value);
 transliterationControl.setLanguagePair(google.elements.transliteration.LanguageCode.ENGLISH, value);
 }
 
 // SERVER_UNREACHABLE event handler which displays the error message.
 function serverUnreachableHandler(e) {
 document.getElementById("errorDiv").innerHTML = "Transliteration Server unreachable";
 }
 
 // SERVER_UNREACHABLE event handler which clears the error message.
 function serverReachableHandler(e) {
 document.getElementById("errorDiv").innerHTML = "";
 }
 
 google.setOnLoadCallback(onLoad);
 */
//===============================================================================//

// Translator Tool
var control;
google.load("elements", "1", {packages: "transliteration"});
function getUnicodeLang(type)
{
    if (type === '1')
    {
        $("#translControl").empty();
        control.toggleTransliteration();
        //$("#translControl").remove();
    } else if (type === '2')
    {
        $("#translControl").removeClass('hidden')
        //document.getElementById('translControl').style.display = "block";
        //onLoads();
        var options = {
            sourceLanguage: 'en',
            destinationLanguage: ['hi', 'bn', 'fa', 'gu', 'kn', 'ml', 'mr', 'ne', 'pa', 'ta', 'te', 'ur'],
            shortcutKey: 'ctrl+g',
            transliterationEnabled: true
        };
        // Create an instance on TransliterationControl with the required options.
        control = new google.elements.transliteration.TransliterationControl(options);
        // Enable transliteration in the textfields with the given ids.
        var ids = ["message"];
        control.makeTransliteratable(ids);
        // Show the transliteration control which can be used to toggle between
        // English and Hindi and also choose other destination language.
        control.showControl('translControl');
    }
}

// Useful Settings
$(document).ready(function ()
{
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), nowDate.getSeconds(), 0);
    $("#schedule_date").datetimepicker({
        format: "yyyy-mm-dd hh:ii:00",
        autoclose: true,
        todayBtn: true,
        startDate: today,
        minuteStep: 15
    });
    $('#expiry_date').datepicker({
        format: "dd-mm-yyyy",
        startDate: today
    });
    $('#date_of_birth').datepicker({
        format: "dd-mm-yyyy",
        endDate: today
    });
    $('#sender_counter').simplyCountable({
        counter: '#count_sender',
        countType: 'characters',
        maxCount: 6,
        countDirection: 'up'
    });
    $('#message').simplyCountable({
        counter: '#message_counter',
        countType: 'characters',
        countDirection: 'up'
    });
});

// Validate Forms
$('#validate-basic').parsley();

// Validate Forms
$('#sendVoiceSMSForms').parsley();

// Validate Forms
$('.tab-forms').parsley();

// Validate Send SMS Form
$('form#sendSMSForm').parsley();

// Empty Notification
/*
 $(document).ready(function () {
 setTimeout(function () {
 alert('hello')
 $("span#notification").addClass("notification alert-success");
 $("#span#notification").slideDown();
 //$("span#notification").html('');
 //$("span#notification").removeClass("notification alert-success alert-danger alert-warning");
 //$("span#notification").reset();
 }, 5000);
 });
 */

// MultiSelect
$(document).ready(function () {
    $('#groups').multiselect({
        maxHeight: 200,
        //includeSelectAllOption: true,
        //selectAllText: 'Select all',
        enableCaseInsensitiveFiltering: true,
        onChange: function (option, checked, select) {
            var selected = $("#groups option:selected");
            var selected_options = '';
            selected.each(function () {
                if (selected_options === '')
                    selected_options += $(this).val();
                else
                    selected_options += "," + $(this).val();
            });
            if (selected_options !== '')
            {
                $('#show_extra_fields').html('');
                var base_url = $('#base_url').val();
                var group_id = $('#group_id').val();
                var contact_id = $('#contact_id').val();
                $.ajax({
                    'url': base_url + '/user/get_extra_fields',
                    'type': 'POST',
                    'data': {'group_ids': selected_options, 'group_id': group_id, 'contact_id': contact_id},
                    'success': function (data) {
                        if (data) {
                            $('#show_extra_fields').html(data);
                        }
                    }
                });
            } else
            {
                $('#show_extra_fields').html('');
            }
        }
    });
});

// Upload Contact CSV
$("#contact_csv").change(function (e) {
    $('span#notification').html("");
    $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
    var ext = $("input#contact_csv").val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["csv"]) == -1) {
        $('span#notification').addClass("notification alert-danger");
        $('span#notification').html('<i class="fa fa-exclamation-circle"></i> Error: Please upload only csv file');
        return false;
    }
    var file_data = $('#contact_csv').prop('files')[0];
    var form_data = new FormData();
    form_data.append('contact_csv', file_data);
    var base_url = $('#base_url').val();
    var url = base_url + '/user/upload_contact_csv';
    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var container = $('#csv_data');
            if (response) {
                $('#upload_section').addClass('hidden');
                container.html(response);
            } else {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please upload valid csv file!");
                return false;
            }
        }
    });
});

// Upload Contact CSV Custom SMS
$("#upload_csv").change(function (e) {
    $('span#notification').removeClass("notification").removeClass("alert-success").removeClass("alert-danger").removeClass("alert-warning").html("");
    var ext = $("input#upload_csv").val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["csv"]) === -1) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please upload only csv file');
        $("input#upload_csv").val(null);
        return false;
    }
    var file_data = $('#upload_csv').prop('files')[0];
    var form_data = new FormData();
    form_data.append('upload_csv', file_data);
    var base_url = $('#base_url').val();
    var url = base_url + '/user/upload_csv';
    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var container = $('#csv_data');
            if (response) {
                $('#upload_section').addClass('hidden');
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
            } else {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please upload valid csv file!");
                return false;
            }
        }
    });
});

// Add More Fields
$(function () {
    var scntDiv = $('#show_new_fields');
    var i = $('#show_new_fields div').size() + 1;
    $(document).on('click', 'a#add_new_field', function () {
        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth() + 1;
        var year = currentDate.getFullYear();
        var hrs = currentDate.getHours();
        var min = currentDate.getMinutes();
        var sec = currentDate.getMilliseconds();
        var date = day + "" + month + "" + year + "" + hrs + "" + min + "" + sec;
        var str = "<div id='div'><div class='form-group col-md-4'>";
        str += "<input type='hidden' name='new_field_ids[]' id='new_field_id" + i + "' value='" + date + "' />";
        str += "<input type='text' class='form-control names' name='new_field_names[]' id='new_field_name" + i + "' value='' placeholder='Field Name'";
        str += "onkeyup='getSuggestions(" + i + ", this.value)' />";
        //str += "data-parsley-pattern='/^[a-zA-Z0-9_]+$/' data-parsley-pattern-message='Field name allows underscore or alphanumeric characters.' />";
        str += "<div class='suggesstion' id='suggesstion" + i + "'></div></div><div class='form-group col-md-4'>";
        str += "<input type='text' class='form-control values' name='new_field_values[]' id='new_field_value" + i + "' value='' placeholder='Field Value' />";
        str += "</div><div class='form-group col-md-3' id='div_type" + i + "'>";
        str += "<select name='new_field_types[]' id='new_field_type" + i + "' class='form-control'>";
        str += "<option value='number'>Number</option><option value='text'>Text</option>";
        str += "</select></div><div class='form-group col-md-1'>";
        str += "<a href='javascript:void(0)' class='btn btn-danger btn-sm' id='remove_btn'><i class='fa fa-times'></i></a>";
        str += "</div><div class='form-group col-md-12' id='custom_msg" + i + "' style='color:red'></div>";
        str += "</div>";
        $(str).appendTo(scntDiv);
        i++;
        return false;
    });
    $(document).on('click', 'a#remove_btn', function () {
        if (i > 1) {
            $(this).parents('div#div').remove();
            i--;
        }
        return false;
    });
});

// Active/Inactive User Tabs
$(document).ready(function () {
    $("#show_users>a").click(function (e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.um-widget").removeClass("active");
        $("div.um-widget").eq(index).addClass("active");
    });
});

// Slim Scroll
$(function () {
    $('#show_users').slimScroll({
        height: '550px',
        size: '2px',
        position: 'right',
        color: '#ddd',
        alwaysVisible: false,
        railVisible: true,
        railColor: '#222',
        railOpacity: 0.3,
        wheelStep: 10,
        allowPageScroll: false,
        disableFadeOut: false
    });
});

// File Style
$(".upload_files").filestyle();

// Get Column Suggestions
function getSuggestions(index, value)
{
    var base_url = $('#base_url').val();
    var url = base_url + 'user/get_suggestions';
    $.ajax({
        'url': url,
        'type': 'POST',
        'data': {'index': index, 'value': value},
        'success': function (data) {
            var container = $('#suggesstion' + index);
            if (data) {
                container.show();
                container.html(data);
            }
        }
    });
}

// To Select Column Name
function selectColumnName(index, val, type) {
    $("#new_field_name" + index).val(val);
    $("#div_type" + index).html('<strong>' + type + '</strong><input type="hidden" id="new_field_type' + index + '" value="' + type + '" />');
    $("#suggesstion" + index).hide();
}

/*
 $('#pagination-demo').twbsPagination({
 totalPages: 10,
 visiblePages: 5,
 onPageClick: function (event, page) {
 var base_url = $('#base_url').val();
 var tab = $('#page_id').val();
 $.ajax({
 'url': base_url + '' + controller + '/get_page/' + tab + '/' + page,
 'type': 'POST',
 'success': function (data) {
 var container = $('#data_table');
 if (data) {
 container.html(data);
 }
 }
 });
 }
 });
 */

/*
 $("#contact_csv").change(function (e) {
 $('span#notification').html("");
 $('span#notification').removeClass("notification alert-success alert-danger alert-warning");
 var ext = $("input#contact_csv").val().split(".").pop().toLowerCase();
 if ($.inArray(ext, ["csv"]) == -1) {
 $('span#notification').addClass("notification alert-danger");
 $('span#notification').html('Please upload only csv file');
 return false;
 }
 if (e.target.files != undefined) {
 var reader = new FileReader();
 reader.onload = function (e) {
 var csvval = e.target.result.split("\n");
 var inputrad = "";
 var csvvalue = csvval[0].split(",");
 for (var i = 0; i < csvvalue.length; i++)
 {
 var temp = csvvalue[i];
 var inputrad = inputrad + " " + temp;
 }
 $("#csvimporthint").html(inputrad);
 //$("#csvimporthinttitle").show();
 };
 reader.readAsText(e.target.files.item(0));
 }
 return false;
 });
 */

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

var base_url = "";
$(document).ready(function ()
{
    base_url = $('#base_url').val();
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), nowDate.getHours(), nowDate.getMinutes(), nowDate.getSeconds(), 0);
    $('#exportDLR .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        endDate: today
    });

    $('#exportSMPPLogs .input-daterange').datepicker({
        format: "yyyy-mm-dd",
        endDate: today
    });

    // For Expiry Date
    $('#expiry_date').datepicker({
        format: "dd-mm-yyyy",
        startDate: today
    });

    // For Email
    $('#data_table ul li a.email').editable({
        type: 'text',
        showbuttons: false,
        placement: 'bottom',
        url: base_url + 'user/save_options',
        ajaxOptions: {
            dataType: 'json'
        },
        validate: function (value) {
            if (value != "") {
                var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
                if (!pattern.test(value)) {
                    return 'Invalid Email Address';
                }
            }
        }
    });

    // For Contact
    $('#data_table ul li a.contact').editable({
        type: 'text',
        showbuttons: false,
        placement: 'bottom',
        url: base_url + 'user/save_options',
        ajaxOptions: {
            dataType: 'json'
        },
        validate: function (value) {
            if (value != "") {
                var pattern = new RegExp(/^\d*[0-9](|.\d*[0-9]|,\d*[0-9])?$/);
                if (!pattern.test(value)) {
                    return 'Invalid Contact Number';
                }
                if (value.length < 10) {
                    return 'Invalid Contact Number';
                }
            }
        }
    });

    // For Web Hook
    $('#data_table ul li a.webhook').editable({
        type: 'text',
        showbuttons: false,
        placement: 'bottom',
        url: base_url + 'user/save_options',
        ajaxOptions: {
            dataType: 'json'
        },
        validate: function (value) {
            if (value != "") {
                //var pattern = new RegExp(/^(http(s)?:\/\/)?(www\.)?[A-Za-z0-9]+([\-\.]{1}[A-Za-z0-9]+)*\.[A-Za-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/);
                //var pattern=new RegExp(/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/);
                var pattern = new RegExp(/^(https?|ftp):\/\/(((([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([A-Za-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([A-Za-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([A-Za-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([A-Za-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([A-Za-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([A-Za-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([A-Za-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/);
                if (!pattern.test(value)) {
                    return 'Invalid URL';
                }
            }
        }
    });

    // For Default Route (Missed Call Alerts)
    $('#data_table ul li a.default_route').editable({
        type: 'select',
        value: 2,
        source: [
            {value: 'A', text: 'Promotional'},
            {value: 'B', text: 'Transactional'},
        ],
        showbuttons: false,
        placement: 'bottom',
        url: base_url + 'user/save_options',
        ajaxOptions: {
            dataType: 'json'
        },
        validate: function (value) {
            if (value == "") {
                return 'Invalid Route';
            }
        }
    });

    // For Keyword Reply
    $('.keyword_reply').popover({
        placement: 'right',
        animation: true,
        container: '#data_table',
        html: true,
        selector: false
    })

    $('.keyword_reply').click(function () {
        $('.keyword_reply').not(this).popover('hide');
    });

    // For Auto Reply (Missed Call)
    $('.mc_auto_reply').popover({
        placement: 'right',
        animation: true,
        container: '#data_table',
        html: true,
        selector: false
    })

    $('.mc_auto_reply').click(function () {
        $('.mc_auto_reply').not(this).popover('hide');
    });

    $('body').on('click', function (e) {
        //only buttons
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
});

$('ul.dropdown-menu.custom-dropdown-menu').on('click', function (event) {
    event.stopPropagation();
});

// auto adjust the height of
/*
 $('#send_sms_form').on('keyup', 'textarea', function (e) {
 $(this).css('height', 'auto');
 $(this).height(this.scrollHeight);
 });
 $('#send_sms_form').find('textarea').keyup();
 */
$(function () {
    $('#send_sms_form textarea').autosize({append: "\n"});
});

$(document).ready(function () {
    $.clock.locale = {
        "en": {
            "weekdays": ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            "months": ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
        }
    };
    $("#clock1").clock();
});


// Upload Contact CSV Custom SMS
$("#upload_audio_file").change(function (e) {
    $('span#notification').removeClass("notification").removeClass("alert-success").removeClass("alert-danger").removeClass("alert-warning").html("");
    var ext = $("input#upload_audio_file").val().split(".").pop().toLowerCase();
    if ($.inArray(ext, ["mp3", "wav"]) === -1) {
        $('span#notification').addClass("notification alert-danger").html('<i class="fa fa-exclamation-circle"></i> Error: Please upload only mp3 or wav file');
        $("input#upload_audio_file").val(null);
        return false;
    }
    var file_data = $('#upload_audio_file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('upload_audio_file', file_data);
    var base_url = $('#base_url').val();
    var url = base_url + '/user/upload_audio_file';
    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            var container = $('#show_voice_drafts');
            if (response) {
                container.html(response);
                var msg_type = $('#msg_type').val();
                var msg_data = $('#msg_data').val();
                if (msg_type === '1') {
                    $('span#notification').addClass("notification alert-success");
                    $('span#notification').html(msg_data);
                    $("input#upload_audio_file").val(null);
                } else if (msg_type === '0') {
                    $('span#notification').addClass("notification alert-danger");
                    $('span#notification').html(msg_data);
                    $("input#upload_audio_file").val(null);
                }
            } else {
                $('span#notification').addClass("notification alert-danger");
                $('span#notification').html("<i class='fa fa-exclamation-circle'></i> Error: Please upload valid mp3 or wav file!");
                $("input#upload_audio_file").val(null);
                return false;
            }
        }
    });
});

//===============================================================================//