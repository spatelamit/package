<?php
$this->load->helper('cookie');
$my_cookie1 = $this->input->cookie('extra_session', false);
$my_cookie2 = $this->input->cookie('extra_form', false);
$my_cookie3 = $this->input->cookie('extra_charges', false);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cookie Data</title>
        <script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
        <script type="text/javascript">
            function saveCookie() {
                var base_url = $('#base_url').val();
                var url = base_url + '/index/cookie';
                $.ajax({
                    'url': url,
                    'type': 'POST',
                    'success': function (data) {
                        if (data) {
                            var url = base_url + '/index/get_cookie';
                            $.ajax({
                                'url': url,
                                'type': 'POST',
                                'success': function (response) {
                                    var container = $('#show');
                                    if (response) {
                                        container.html(response);
                                    }
                                }
                            });
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <input type="hidden" name="base_url" id="base_url" value="<?php echo site_url(); ?>" />
        <button type="button" onclick="saveCookie()">Save Cookie</button>
        <div id="show"></div>
    </body>
</html>