
// Controller Name
var controller = 'user';

// Color Picker Buttton
//$('.theme-colours').hide();
$('.picker_close').click(function (e) {
    e.stopPropagation();
    $("#choose_color").toggleClass("position");
});
$('.theme-colours').click(function (e) {
    e.stopPropagation();
});
$(document).click(function () {
    $("#choose_color").removeClass("position");
});

/*
 $('.theme-colours').hide();
 $('.picker_close').click(function (e) { 
 e.stopPropagation();
 $('.theme-colours').slideToggle('slow');
 });
 $('.theme-colours').click(function (e) {
 e.stopPropagation();
 });
 $(document).click(function () {
 $('.theme-colours').slideUp();
 });
 */

$("#default").on('click', function ()
{
    var theme = 'default';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/default.css");
            return false;
        }
    });
});

$("#green").on('click', function ()
{
    var theme = 'green';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/green.css");
            return false;
        }
    });
});

$("#red").on('click', function ()
{
    var theme = 'red';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/red.css");
            return false;
        }
    });
});

$("#light-blue").on('click', function ()
{
    var theme = 'light-blue';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/light-blue.css");
            return false;
        }
    });
});

$("#light-green").on('click', function ()
{
    var theme = 'light-green';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/light-green.css");
            return false;
        }
    });
});

$("#orange").on('click', function ()
{
    var theme = 'orange';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/orange.css");
            return false;
        }
    });
});

$("#pink").on('click', function ()
{
    var theme = 'pink';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/pink.css");
            return false;
        }
    });
});

$("#black").on('click', function ()
{
    var theme = 'black';
    var style_url = $('#base_url1').val();
    var base_url = $('#base_url').val();
    var url = base_url + '/' + controller + '/change_theme/' + theme;
    $.ajax({
        'url': url,
        'type': 'POST',
        'success': function () {
            $("#color").attr("href", style_url + "Assets/user/css/color-schemes/black.css");
            return false;
        }
    });
});