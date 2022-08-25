var controller = 'api_docs';
// Generate API
function generateAPIOld(page, tab, method)
{
    var $btn = $('#generate_api').button('loading');
    var base_url = $('#base_url').val();
    var authkey = $('#authkey').val();
    if (page === '1' && tab === '1')
    {
        var route = $('#route').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + authkey + "&route=" + route;
        dataArray[0] = api_string;
    }
    if (page === '1' && tab === '2')
    {
        var password = $('#password').val();
        var npassword = $('#npassword').val();
        var cnpassword = $('#cnpassword').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&cpassword=" + encodeURI(password) + "&npassword=" + encodeURI(npassword) + "&ncpassword=" + encodeURI(cnpassword);
        dataArray[0] = api_string;
    }
    if (page === '1' && tab === '3')
    {
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '1')
    {
        var full_name = $('#full_name').val();
        var username = $('#username').val();
        var mobile = $('#mobile').val();
        var email = $('#email').val();
        var company = $('#company').val();
        var industry = $('#industry').val();
        var expiry = $('#expiry').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&full_name=" + encodeURI(full_name) + "&username=" + encodeURI(username) + "&mobile=" + encodeURI(mobile) + "&email=" + encodeURI(email) + "&company=" + encodeURI(company) + "&industry=" + encodeURI(industry) + "&expiry=" + encodeURI(expiry);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '2')
    {
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '3')
    {
        var buser_id = $('#buser_id').val();
        var sms = $('#sms').val();
        var account_type = $('#account_type').val();
        var type = $('#type').val();
        var price = $('#price').val();
        var description = $('#description').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&user_id=" + encodeURI(buser_id) + "&sms=" + encodeURI(sms) + "&account_type=" + encodeURI(account_type) + "&type=" + encodeURI(type) + "&price=" + encodeURI(price) + "&description=" + encodeURI(description);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '4')
    {
        var muser_id = $('#muser_id').val();
        var status = $('#status').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&user_id=" + encodeURI(muser_id) + "&status=" + encodeURI(status);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '5')
    {
        var fusername = $('#fusername').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&username=" + encodeURI(fusername);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '6')
    {
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '7')
    {
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '8')
    {
        var puser_id = $('#puser_id').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&user_id=" + encodeURI(puser_id);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '9')
    {
        var pusername = $('#pusername').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&username=" + encodeURI(pusername);
        dataArray[0] = api_string;
    }
    if (page === '2' && tab === '10')
    {
        var group_name = $('#group_name').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '1')
    {
        var group_name = $('#group_name').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&group_name=" + encodeURI(group_name);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '2')
    {
        var dgroup_id = $('#dgroup_id').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&group_id=" + encodeURI(dgroup_id);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '3')
    {
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '4')
    {
        var acontact_name = $('#acontact_name').val();
        var amobile = $('#amobile').val();
        var agroup_id = $('#agroup_id').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&name=" + encodeURI(acontact_name) + "&mobile_no=" + encodeURI(amobile) + "&group_id=" + encodeURI(agroup_id);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '5')
    {
        var econtact_id = $('#econtact_id').val();
        var egroup_id = $('#egroup_id').val();
        var emobile = $('#emobile').val();
        var ename = $('#ename').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&contact_id=" + encodeURI(econtact_id) + "&egroup_id=" + encodeURI(egroup_id)
                + "&mobile_no=" + encodeURI(emobile) + "&name=" + encodeURI(ename);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '6')
    {
        var dcontact_id = $('#dcontact_id').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&contact_id=" + encodeURI(dcontact_id);
        dataArray[0] = api_string;
    }
    if (page === '3' && tab === '7')
    {
        var lgroup_id = $('#lgroup_id').val();
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&group_id=" + encodeURI(lgroup_id);
        dataArray[0] = api_string;
    }
    if (page === '4' && tab === '1')
    {
        var mobiles = $('#mobiles').val();
        var message = $('#message').val();
        var sender = $('#sender').val();
        var route = $('#route').val();       
        var dataArray = new Array(1);
        var api_string = method + ".php?authkey=" + encodeURI(authkey) + "&mobiles=" + encodeURI(mobiles) + "&message=" + encodeURI(message) + "&sender=" + encodeURI(sender) + "&route=" + encodeURI(route);
        if ($('#duration').val() != '')
        {
            api_string += "&duration=" + encodeURI($('#duration').val());
        }
        
        if ($('#flash').val() != '')
        {
            api_string += "&flash=" + encodeURI($('#flash').val());
        }
        if ($('#unicode').val() != '')
        {
            api_string += "&unicode=" + encodeURI($('#unicode').val());
        }
        if ($('#schdate').val() != '')
        {
            api_string += "&schtime=" + encodeURI($('#schdate').val());
        }
        if ($('#response').val() != '')
        {
            api_string += "&response=" + encodeURI($('#response').val());
        }
        if ($('#campaign').val() != '')
        {
            api_string += "&campaign=" + encodeURI($('#campaign').val());
        }
        dataArray[0] = api_string;
    }
    if (page === '4' && tab === '2')
    {
        var xml_data = $('#xml_data').val();
        alert(xml_data);
        var dataArray = new Array(1);
        var api_string = method + ".php?xml=" + encodeURIComponent(xml_data);
        dataArray[0] = api_string;
    }
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/show_generate_api', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#show_generate_api');
            if (data) {
                container.html(data);
            }
        }});
}

function generateAPI(page, tab, method)
{
    var $btn = $('#generate_api').button('loading');
    var base_url = $('#base_url').val();
    var formData = $('#form' + page + tab).serialize();
    $.ajax({'url': base_url + '' + controller + '/show_generate_api/' + page + '/' + tab + '/' + method,
        'type': 'POST', 'data': formData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#show_generate_api');
            if (data) {
                container.html(data);
            }
        }});
}

// Call API
function callAPI()
{
    var $btn = $('#call_api').button('loading');
    var base_url = $('#base_url').val();
    var generate_api = $('#generated_api').val();
    var dataArray = new Array(1);
    dataArray[0] = generate_api;
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/call_api', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            $btn.button('reset');
            var container = $('#show_api_output');
            if (data) {
                container.html(data);
            }
        }});
}

// Check XML Code
function checkXMLCode()
{
    var base_url = $('#base_url').val();
    var xml_data = $('#xml_data').val();
    var dataArray = new Array(1);
    dataArray[0] = encodeURI(xml_data);
    var ajaxData = {dataArray: JSON.stringify(dataArray)};
    $.ajax({'url': base_url + '' + controller + '/check_xml_code', 'type': 'POST', 'data': ajaxData, 'success': function (data) {
            var container = $('#show_generate_api');
            if (data) {
                container.html(data);
            }
        }});
}

// Regenerate Auth Key
function regenerateKey()
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/regenerate_key', 'type': 'POST', 'success': function (data) {
            var container = $('#auth_key');
            if (data) {
                container.val(data);
            }
        }});
}

// Generate PDF
function generatePDF(name)
{
    var base_url = $('#base_url').val();
    $.ajax({'url': base_url + '' + controller + '/generate_pdf/' + name, 'type': 'POST', 'success': function (data) {}});
}