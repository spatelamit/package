
function admin_url(command, url, passwd) {
    if (passwd == "") {
        passwd = prompt("Please enter admin password for this Kannel instance:", "(enter admin password here)");
    }
    check = confirm("Are you sure you want to '" + command + "' bearerbox from this Kannel instance?");
    if (check == true) {
        admin_window = window.open("", "newWin", "width=350,height=150,left=0,top=0");
        admin_window.location.href = url + "?password=" + passwd;
    }
    location.reload();
    self.focus();
}

function admin_smsc_url(command, url, smsc, passwd) {
    if (passwd == "") {
        passwd = prompt("Please enter admin password for this Kannel instance:", "(enter admin password here)");
    }
    check = confirm("Are you sure you want to '" + command + "' the smsc-id '" + smsc + "' on the Kannel instance?");
    if (check == true) {
        admin_window = window.open("", "newWin", "width=350,height=150,left=0,top=0");
        admin_window.location.href = url + "&password=" + passwd;
    }
    location.reload();
    self.focus();
}

$(".scroll").click(function (event) {
    event.preventDefault();
    //calculate destination place
    var dest = 0;
    if ($(this.hash).offset().top > $(document).height() - $(window).height()) {
        dest = $(document).height() - $(window).height();
    } else {
        dest = $(this.hash).offset().top;
    }
    //go to destination
    $('html,body').animate({scrollTop: dest}, 1000, 'swing');
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