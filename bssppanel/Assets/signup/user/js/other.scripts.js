$(document).ready(function ()
{
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    $('#expiry').datepicker({
        format: "dd-mm-yyyy",
        startDate: today
    });

    $('#expiry_date').datepicker({
        format: "dd-mm-yyyy",
        startDate: today
    });
    //todayBtn: "linked",
});
(function ($) {
    $(document).ready(function () {
        $('.navToggle_xs').click(function (e) {
            var $parent = $(this).parent('nav.dlTool_nav');
            $parent.toggleClass("open");
            var navState = $parent.hasClass('open') ? "hide" : "show";
            $(this).attr("title", navState + " navigation");
            // Set the timeout to the animation length in the CSS.
            setTimeout(function () {
                console.log("timeout set");
                $('.navToggle_xs > span').toggleClass("navClosed").toggleClass("navOpen");
            }, 200);
            e.preventDefault();
        });
    });
})(jQuery);
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
jQuery(window).load(function () {
    $('#loading').addClass('hidden');
});
$('.validate').parsley();
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