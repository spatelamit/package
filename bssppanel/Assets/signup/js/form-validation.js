
// Sign Up Form Validations
$(document).ready(function ($) {
    // hide messages 
    $(".danger").hide();
    $(".success").hide();

    $('#signup-form input').click(function (e) {
        $(".danger").fadeOut();
        $(".success").fadeOut();
    });

    $("#signup-form #submit").click(function () {
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
        var pattern_username = /^[a-zA-Z0-9_.]+$/;
        if (!pattern_username.test(username))
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Please enter valid username");
            $("input#signup_username").focus();
            return false;
        }
        if (username.length < 5)
        {
            $("#error").removeClass('hidden');
            $("#error").fadeIn('slow').text("Username must be of 5 characters long");
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

    return false;
});

// Sign In Form Validations
$(document).ready(function ($) {
    // hide messages 
    $(".danger").hide();
    $('#validate-signin input').click(function (e) {
        $(".danger").fadeOut();
    });

    $("#validate-signin #submit").click(function () {
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

    return false;
});
