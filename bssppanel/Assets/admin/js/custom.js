// <!-- Menu Toggle Script -->
$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
});
// <!-- nice scroll -->
$(document).ready(
        function () {
            $("html").niceScroll();
        }
);
var nice = false;
$(document).ready(
        function () {
            nice = $("html").niceScroll();
        }
);
// <!-- popover and tooltip -->
$(function () {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();
});

$('#validate-basic').parsley();
$('.tab-forms').parsley();
$(document).ready(function () {
    $('#notify_users').multiselect();
    $('#main-sidebar').simplerSidebar({
        opener: '#toggle-sidebar',
        top: 42,
        animation: {
            easing: "easeOutQuint"
        },
        sidebar: {
            closingLinks: '.close-sb'
        }
    });
});

$(document).ready(function () {
    setTimeout(function () {
        $("#notification").removeClass("show alert-success");
    }, 3000);
});

$("[data-slider]").each(function () {
    var input = $(this);
    $("<span>").addClass("output").insertAfter($(this));
}).bind("slider:ready slider:changed", function (event, data) {
    $(this).nextAll(".output:first").html(data.value.toFixed(2));
});
/*
 $(document).click(function () {
 if ($(".popover").hasClass('in'))
 $(".popover").removeClass("in");
 });
 */
$('.reject_sms_outer1').popover({
    placement: 'top',
    animation: true,
    container: '#spam_transactional',
    html: true,
    selector: false
})
$('.reject_sms_outer1').click(function () {
    $('.reject_sms_outer1').not(this).popover('hide'); //all but this
});

$('.reject_sms_outer2').popover({
    placement: 'top',
    animation: true,
    container: '#spam_promotional',
    html: true,
    selector: false
})
$('.reject_sms_outer2').click(function () {
    $('.reject_sms_outer2').not(this).popover('hide'); //all but this
});

$('body').on('click', function (e) {
    //only buttons
    if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) {
        $('[data-toggle="popover"]').popover('hide');
    }
});