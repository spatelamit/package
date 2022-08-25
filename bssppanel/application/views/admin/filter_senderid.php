<h2 style="color: #d14">Upload only .CSV File</h2>
<form action="<?php echo base_url(); ?>admin/upload_single_row/" method="post"  enctype="multipart/form-data">
<input type="file" name="file" id="file">
<button type="submit" id="submit" name="import" class="btn btn-primary button-loading">Import</button>
</form>
<br>