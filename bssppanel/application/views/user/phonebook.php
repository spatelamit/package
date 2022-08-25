</div>
<?php
$country_status = $user_info['country_status'];
$country = $user_info['country'];
?>
<div class="container">
    <div class="row" id="show_phonebook">
        <div class="col-md-2 borderR">
            <div class="row content-header-title fhead">
                <div class="col-xs-4 col-md-3 padding0">Groups</div>
            </div>
            <div class="row">
                <form role="form" class="tab-forms" id="groupForm" data-parsley-validate method='post' action="javascript:saveGroup();">
                    <div class="col-md-12 col-xs-12 padding0">
                        <div class="input-group">
                            <input type="text" id="group_name" name="group_name" data-parsley-required-message=""
                                   value="" required="" class="form-control input-sm" placeholder="Enter Group Name" onkeypress="return blockSpecialChar(event)"/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary btn-sm" id="btngroup" 
                                        data-loading-text="<i class='fa fa-spinner'></i>" autocomplete="off">
                                    <i class="fa fa-check"></i>                                
                                </button>
                            </span>
                        </div>
                        <hr/>
                    </div>
                </form>
                <div class="col-md-12 table-responsive ptb15 data_table padding0" id="show_contact_groups" style="height: 550px;overflow-y: auto">
                    <table class="table table-hover bgf">
                        <thead>
                            <tr>
                                <th>Group Name</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($contact_groups) && $contact_groups) {
                                foreach ($contact_groups as $group) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);" 
                                               onclick="getGroupContacts(<?php echo $group['contact_group_id']; ?>, '<?php echo $group['contact_group_name']; ?>', 1, 30);">
                                                   <?php echo $group['contact_group_name']; ?>
                                            </a>
                                        </td>
                                        <td class="text-right"><?php echo $group['total_contacts']; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td align=center colspan=2>No Group Exists!</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row content-header-title fhead">
                <div class="col-xs-6 col-md-3 padding0">Contacts</div>
                <div class="col-xs-6 col-md-9 btns-route text-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a href="<?php echo base_url(); ?>user/add_contact"><i class="fa fa-plus"></i> Add Contact</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>user/import_contacts"><i class="fa fa-upload"></i> Import Contacts</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>export/contacts/<?php echo $user_id; ?>"><i class="fa fa-download"></i> Export Contacts</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row" id="show_group_contacts"></div>
        </div>            
    </div>
</div>

<script type="text/javascript">

    function blockSpecialChar(e) {
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
    }
</script>