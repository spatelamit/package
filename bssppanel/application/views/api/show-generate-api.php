
<div class="col-md-12 col-xs-12 mt5 padding0">
    <p class="text-success" id="generate_api_id"><?php echo $domain_name; ?>api/<?php echo $api_string; ?></p>
    <input type="hidden" name="generated_api" id="generated_api" value="<?php echo $domain_name; ?>api/<?php echo $api_string; ?>" />
    <button type="button" name="call_api" id="call_api" class="btn btn-primary" onclick="callAPI()"
            data-loading-text="Connecting..." autocomplete="off">
        Call API
    </button>
</div>
<div class="col-md-12 col-md-12 mt5 padding0" id="show_api_output"></div>