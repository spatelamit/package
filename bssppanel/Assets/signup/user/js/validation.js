
// Controller Name
var controller = 'index';

function showWebForm(type)
{
    var base_url = $('#base_url').val();
    $.ajax({
        'url': base_url + '' + controller + '/show_web_form',
        'type': 'POST',
        'data': {form: type},
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Check Username Availability
function checkUsername(username)
{
    $("#error").html('');
    $("#success").html('');
    $("#error").addClass('hidden');
    $("#success").addClass('hidden');
    if (username.length === 0 && username === "")
    {
        $("#error").removeClass('hidden');
        $("#error").fadeIn('slow').text("Please enter userename!");
        return false;
    }
    //var pattern_username = /^[a-zA-Z0-9_.]+$/;
    var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
    if (!pattern_username.test(username))
    {
        $("#error").removeClass('hidden');
        $("#error").fadeIn('slow').text("Username must be start with a character");
        return false;
    }
    if (username.length < 5)
    {
        $("#error").removeClass('hidden');
        $("#error").fadeIn('slow').text("Username must be of 5 characters long");
        return false;
    }
    var base_url = $('#base_url').val();
    if (username.length >= 5)
    {
        var dataArray = new Array(1);
        dataArray[0] = username;
        var ajaxData = {dataArray: JSON.stringify(dataArray)};
        $.ajax({
            'url': base_url + '' + controller + '/check_username_availability',
            'type': 'POST',
            'data': ajaxData,
            'success': function (data) {
                if (data) {
                    $("#error").addClass('hidden');
                    $("#success").removeClass('hidden');
                    $("#success").fadeIn('slow').text("Congratulations! Username available");
                } else
                {
                    $("#success").addClass('hidden');
                    $("#error").removeClass('hidden');
                    $("#error").fadeIn('slow').text("Sorry! Username not available");
                    $("input#signup_username").focus();
                    return false;
                }
            }
        });
    }
}

// Save User
function saveNewUser()
{
    var base_url = $('#base_url').val();
    var formData = $('#signupForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/save_user',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            if (data.status == '200')
            {
                $("#error").addClass('hidden');
                $("#success").removeClass('hidden');
                $("#success").fadeIn('slow').text(data.message);
                $('#signupForm').each(function () {
                    this.reset();
                });
            } else
            {
                $("#success").addClass('hidden');
                $("#error").removeClass('hidden');
                $("#error").fadeIn('slow').text(data.message);
                return false;
            }
        }
    });
}

// Forgot Password
function forgotPassword()
{
    var base_url = $('#base_url').val();
    var formData = $('#forgotPasswordForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/check_username',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Verify Code For Forgot Password
function verifyCode()
{
    var base_url = $('#base_url').val();
    var formData = $('#verifyCodeForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/verify_code',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Reset Password
function resetPassword()
{
    var base_url = $('#base_url').val();
    var formData = $('#resetPasswordFrom').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/reset_password',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Send Mail For Contact Form
function sendMail()
{
    var base_url = $('#base_url').val();
    var formData = $('#commentForm').serialize();
    $.ajax({
        'url': base_url + '' + controller + '/send_mail',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            if (data) {
                $("#error1").addClass('hidden');
                $("#success1").removeClass('hidden');
                $("#success1").fadeIn('slow').text("Thank you! We will contact you soon!");
                $('#contact_name').val('');
                $('#contact_contact').val('');
                $('#contact_email').val('');
                $('#contact_message').val('');
            } else
            {
                $("#success1").addClass('hidden');
                $("#error1").removeClass('hidden');
                $("#error1").fadeIn('slow').text("Sorry! Please try after sometime.");
                return false;
            }
        }
    });
}

//=====================================================
// Forgot Password
function webForgotPassword()
{
    var base_url = $('#base_url').val();
    var formData = $('#forgotPasswordForm').serialize();
    $.ajax({
        'url': base_url + '/forgot_password/check_username',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Verify Code For Forgot Password
function webVerifyCode()
{
    var base_url = $('#base_url').val();
    var formData = $('#verifyCodeForm').serialize();
    $.ajax({
        'url': base_url + '/forgot_password/verify_code',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}

// Reset Password
function webResetPassword()
{
    var base_url = $('#base_url').val();
    var formData = $('#resetPasswordFrom').serialize();
    $.ajax({
        'url': base_url + '/forgot_password/reset_password',
        'type': 'POST',
        'data': formData,
        'success': function (data) {
            var container = $('#show_web_form');
            if (data) {
                container.html(data);
            }
        }
    });
}
//=====================================================
// JQuery Form Validations
$(document).ready(function ($) {
    // Hide Messages
    $('#signinForm input').click(function (e) {
        $(".danger").fadeOut();
    });

    $('#signupForm input').click(function (e) {
        $(".danger").fadeOut();
    });

    $('#forgotPasswordForm input').click(function (e) {
        $(".danger").fadeOut();
    });

    $('#verifyCodeForm input').click(function (e) {
        $(".danger").fadeOut();
    });

    $('#resetPasswordFrom input').click(function (e) {
        $(".danger").fadeOut();
    });

    $('#commentForm input').click(function (e) {
        $(".danger").fadeOut();
    });

    // Sign In
    $("#signinForm #login").click(function () {
        $("#error").hide();
        //username required and valid
        var username = $("input#username").val();
        if (username === "" || username.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter username");
            $("input#username").focus();
            return false;
        }
        var pattern_username = /^[a-zA-Z0-9_.]+$/;
        if (!pattern_username.test(username))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid username");
            $("input#username").focus();
            return false;
        }

        //password required and valid
        var password = $("input#password").val();
        if (password === "" || password.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter password");
            $("input#password").focus();
            return false;
        }

    });

    // Sign Up
    $("#signupForm #signup").click(function () {
        $("#error").hide();
        $("#success").hide();
        //name required and valid
        var name = $("input#name").val();
        if (name === "" || name.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter full name");
            $("input#name").focus();
            return false;
        }
        var pattern_name = /^[A-Za-z]([-']?[A-Za-z]+)*( [A-Za-z]([-']?[A-Za-z]+)*)+$/;
        if (!pattern_name.test(name))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter first & last name");
            $("input#name").focus();
            return false;
        }

        //username required and valid
        var username = $("input#signup_username").val();
        if (username === "" || username.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter username");
            $("input#signup_username").focus();
            return false;
        }
        var pattern_username = /^[A-Za-z][A-Za-z0-9]*$/;
        //var pattern_username = /^[a-zA-Z0-9_.]+$/;
        if (!pattern_username.test(username))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Username must be start with a character!");
            $("input#signup_username").focus();
            return false;
        }
        if (username.length < 5)
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Username must be of 5 characters long!");
            $("input#signup_username").focus();
            return false;
        }

        //contact required and valid
        var contact_number = $("input#contact_number").val();
        if (contact_number === "" || contact_number.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter contact number");
            $("input#contact_number").focus();
            return false;
        }
        if (isNaN(contact_number))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid contact number");
            $("input#contact_number").focus();
            return false;
        }
       if (contact_number.length != 10)
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid contact number");
            $("input#contact_number").focus();
            return false;
        }

        //email required and valid
        var email_address = $("input#email_address").val();
        if (email_address === "" || email_address.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter email address");
            $("input#email_address").focus();
            return false;
        }
        var pattern_email = /^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i;
        if (!pattern_email.test(email_address))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid email address");
            $("input#email_address").focus();
            return false;
        }

        //company_name required and valid
        var company_name = $("input#company_name").val();
        if (company_name === "" || company_name.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter company name");
            $("input#company_name").focus();
            return false;
        }

        //industry required and valid
        var industry = $("select#industry").val();
        if (industry === "" || industry.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please select industry");
            $("input#industry").focus();
            return false;
        }

    });

    // Forgot Password
    $("#forgotPasswordForm #forgot_password").click(function () {
        $("#error").hide();
        //username required and valid
        var username = $("input#forgot_username").val();
        if (username === "" || username.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter username");
            $("input#forgot_username").focus();
            return false;
        }
        var pattern_username = /^[a-zA-Z0-9_.]+$/;
        if (!pattern_username.test(username))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid username");
            $("input#forgot_username").focus();
            return false;
        }
    });

    // Verify Code
    $("#verifyCodeForm #verify_code").click(function () {
        $("#error").hide();
        //verification_code required
        var verification_code = $("input#verification_code").val();
        if (verification_code === "" || verification_code.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter verification code");
            $("input#verification_code").focus();
            return false;
        }
    });

    // Reset Password
    $("#resetPasswordFrom #reset_password").click(function () {
        $("#error").hide();
        //new password required
        var new_password = $("input#new_password").val();
        if (new_password === "" || new_password.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter new password");
            $("input#new_password").focus();
            return false;
        }
        var confirm_password = $("input#confirm_password").val();
        if (confirm_password === "" || confirm_password.length === 0) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter confirm password");
            $("input#confirm_password").focus();
            return false;
        }
        if (new_password !== confirm_password) {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("New & confirm password must be same.");
            $("input#confirm_password").focus();
            return false;
        }
    });

    // contact form
    $("#commentForm #contact_form").click(function () {
        $("#error1").hide();
        $("#success1").hide();
        //name required and valid
        var name = $("input#contact_name").val();
        if (name === "" || name.length === 0) {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter name");
            $("input#contact_name").focus();
            return false;
        }

        //contact required and valid
        var contact_number = $("input#contact_contact").val();
        if (contact_number === "" || contact_number.length === 0) {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter contact number");
            $("input#contact_contact").focus();
            return false;
        }
        if (isNaN(contact_number))
        {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter valid contact number");
            $("input#contact_contact").focus();
            return false;
        }
       if (contact_number.length != 10)
        {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter valid contact number");
            $("input#contact_contact").focus();
            return false;
        }

        //email required and valid
        var email_address = $("input#contact_email").val();
        if (email_address === "" || email_address.length === 0) {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter email address");
            $("input#contact_email").focus();
            return false;
        }
        var pattern_email = /^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i;
        if (!pattern_email.test(email_address))
        {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter valid email address");
            $("input#contact_email").focus();
            return false;
        }

        //company_name required and valid
        var contact_message = $("textarea#contact_message").val();
        if (contact_message === "" || contact_message.length === 0) {
            $("#error1").removeClass('hidden');
            $("#error1").fadeIn('slow').text("Please enter message");
            $("input#contact_message").focus();
            return false;
        }
    });

    return false;
});

//=====================================================
// Send OTP
function sendOTP(type)
{
    var base_url = $('#base_url').val();
    $.ajax({
        'url': base_url + '' + controller + '/send_otp/' + type,
        'type': 'POST',
        'success': function (data) {
        }
    });
}
//=====================================================