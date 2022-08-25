    






   <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700" rel='stylesheet' />
        <link href="<?php echo base_url(); ?>assets/css/style.css" rel='stylesheet' />
    <form id="upload" method="post" action="upload.php" enctype="multipart/form-data">
        <div id="drop">
            <a>  Choose file</a>
            <input type="file" name="upl" multiple />
        </div>
        <ul>
            <!-- The file uploads will be shown here -->
        </ul> 
    </form>





    <script src="<?php echo base_url(); ?>assets/js/jquery.knob.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.ui.widget.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.fileupload.js"></script>

    <!-- Our main JS file -->
    <script src="<?php echo base_url(); ?>assets/js/script.js"></script>