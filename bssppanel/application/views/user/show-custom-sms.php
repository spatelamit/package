<input type="hidden" name="msg_type" id="msg_type" value="<?php echo (isset($msg_type) ? $msg_type : ""); ?>" />
<input type="hidden" name="msg_data" id="msg_data" value="<?php echo (isset($msg_data) ? $msg_data : ""); ?>" />
<div class="row" id="upload_section">
    <div class="col-sm-3">
        <div class="portlet">
            <h2 class="content-header-title">Send Custom SMS</h2>
            <div class="portlet-content">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="upload_csv">Upload CSV File</label>
                        <input type="file" id="upload_csv" name="upload_csv" value="" data-parsley-required-message="Please select csv file" 
                               accept=".csv" required="" class="upload_files" />
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <div class="col-sm-8"></div>
</div>
<div class="row" id="csv_data"></div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".upload_files").filestyle();
        // Upload Contact CSV Custom SMS
        $("#upload_csv").change(function (e) {
            $('span#notification').removeClass("notification").removeClass("alert-success").removeClass("alert-danger").removeClass("alert-warning").html("");
            var ext = $("input#upload_csv").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["csv"]) === -1) {
                $('span#notification').addClass("notification alert-danger").html('Please upload only csv file');
                $("input#upload_csv").val(null);
                return false;
            }
            var file_data = $('#upload_csv').prop('files')[0];
            var form_data = new FormData();
            form_data.append('upload_csv', file_data);
            var base_url = $('#base_url').val();
            var url = base_url + '/user/upload_csv';
            $.ajax({
                url: url,
                type: 'POST',
                data: form_data,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    var container = $('#csv_data');
                    if (response) {
                        $('#upload_section').addClass('hidden');
                        container.html(response);
                        var msg_type = $('#msg_type').val();
                        var msg_data = $('#msg_data').val();
                        if (msg_type === '1') {
                            $('span#notification').addClass("notification alert-success");
                            $('span#notification').html(msg_data);
                        } else if (msg_type === '0') {
                            $('span#notification').addClass("notification alert-danger");
                            $('span#notification').html(msg_data);
                        }
                    } else {
                        $('span#notification').addClass("notification alert-danger");
                        $('span#notification').html("Error: Please upload valid csv file!");
                        return false;
                    }
                }
            });
        });
    });
</script>