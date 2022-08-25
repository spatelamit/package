

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

// Smooth scrolling anchor links
$('a[href*=#]:not([href=#])').click(function () {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
            || location.hostname == this.hostname) {

        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({
                scrollTop: target.offset().top
            }, 1000);
            return false;
        }
    }
});

// This adds an offset in case the header is fixed
$('a[href*=#]:not([href=#])').click(function () {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            var top_offset = 0;
            if ($('.site-header').css('position') == 'fixed') {
                top_offset = $('.site-header').height();
            }
            $('html,body').animate({
                scrollTop: target.offset().top - top_offset
            }, 1000);
            return false;
        }
    }
});

$('body').scrollspy({target: '.nav-spy'})