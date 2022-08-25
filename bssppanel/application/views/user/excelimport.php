



<?php if ($this->session->flashdata('message')) { ?>
    <div align="center" class="alert alert-success">      
        <?php echo $this->session->flashdata('message') ?>
    </div>
<?php } ?>
<div align="center"  class="form-group col-md-12">
    <form action="<?php echo base_url(); ?>user/insertPhone" method="post" name="upload_excel" enctype="multipart/form-data">
        <input type="file" name="file" id="file">
        <button type="submit" id="submit" name="import" class="btn btn-primary button-loading">Import</button>
    </form>
    <br>
    <br>
    <div style="width:80%; margin:0 auto;" align="center">
        <table class="table table-bordered table-striped">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
            </tr>
            <?php
            if (isset($view_data) && is_array($view_data) && count($view_data)): $i = 1;
                foreach ($view_data as $key => $data) {
                    ?>
                    <tr>
                        <td><?php echo $data['name'] ?></td>
                        <td><?php echo $data['no'] ?></td>
                        <td><?php echo $data['email'] ?></td>
                        <td><?php echo $data['address'] ?></td>
                    </tr>
                <?php } endif; ?>
        </table>
    </div>

</div>