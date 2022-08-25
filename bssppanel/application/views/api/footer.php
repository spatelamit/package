<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>Assets/user/js/color-theme.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/validator.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/api.scripts.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo base_url(); ?>Assets/user/js/other.scripts.js"></script>
<script type="text/javascript">
    $('#popover1').popover();
    $('#popover2').popover();
    $('body').on('click', function (e) {
        //only buttons
        if ($(e.target).data('toggle') !== 'popover'
                && $(e.target).parents('.popover.in').length === 0) {
            $('[data-toggle="popover"]').popover('hide');
        }
    });
</script>


</body>
</html>