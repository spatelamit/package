</div>
<div class="container">
    <div class="row">        
        <div class="col-sm-6 col-md-4 col-xs-12">
            <div class="portlet">
                <h2 class="content-header-title tbl">HTTP API Hits From IP Address</h2>
                <div class="portlet-content">
                    <div class="table-responsive" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($api_hits) && $api_hits) {
                                    foreach ($api_hits as $api_hit) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $api_hit->client_ip_address; ?>
                                            </td>
                                            <td>
                                                <?php echo $api_hit->api_hit_date; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="2">No Record Found!</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>