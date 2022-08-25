<div class="row">
    <div class="form-group col-md-12 padding0">
        <label for="schedule_date">SMS Preview</label>
    </div>
    <div class="form-group col-md-6 padding0">
        <div class="table-responsive" id="data_table">
            <table class="table table-hover bgf">
                <tbody>
                    <?php
                    if (isset($sms) && $sms) {
                        foreach ($sms as $value) {
                            ?>
                            <tr>
                                <td><?php echo $value; ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>            
        </div>
    </div>
</div>