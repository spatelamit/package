<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'dashboard') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/short_codes/dashboard">Dashboard</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'inbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/short_codes/inbox">Inbox</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'sentbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/short_codes/sentbox">Sentbox</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'keywords') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/short_codes/keywords">Keywords</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'keyword_reply') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/short_codes/keyword_reply">Keyword Replies</a>
            </li>
        </ul>
    </div>
</div>
</div>
<div class="container">
    <?php
    // Dashboard
    if (isset($tab) && $tab == 'dashboard') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Dashboard 
                            </div>
                            <div class="col-md-6 content-header-title tbl text-right">
                                Short Code Balance: <?php echo (isset($user_info) && $user_info['short_code_balance']) ? $user_info['short_code_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Short Number</th>
                                        <th>Keyword</th>
                                        <th>
                                            Auto Reply 
                                            <a type="button" data-toggle="tooltip" data-placement="top" 
                                               title="Deduct from your transactional balance on each keyword reply">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Forwarding
                                            <a type="button" data-toggle="tooltip" data-placement="top" 
                                               title="Forward to your email and mobile number and Deduct from your transactional balance on each forwarding on mobile number">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                        <th>
                                            Web Hook
                                            <a type="button" data-toggle="tooltip" data-placement="top" 
                                               title="Forward to your domain with following information: sender, message, keyword and datetime. For more info: Developer Tools->Virtual Numbers">
                                                <i class="fa fa-question-circle"></i>
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($short_keywords) && $short_keywords) {
                                        $i = 1;
                                        foreach ($short_keywords as $key => $short_keyword) {
                                            ?>                                   
                                            <tr>
                                                <td>
                                                    <?php echo $short_keyword['short_number']; ?>
                                                </td>
                                                <td><?php echo $short_keyword['short_keyword']; ?></td>
                                                <td id="keyword_reply<?php echo $i; ?>">
                                                    <?php
                                                    if ($short_keyword['replies']) {
                                                        if (sizeof($short_keyword['replies'])) {
                                                            ?>
                                                            <ul class="padding0">
                                                                <?php
                                                                $j = 1;
                                                                foreach ($short_keyword['replies'] as $key => $reply) {
                                                                    $reply_content = substr($reply['keyword_reply'], 0, 40);
                                                                    ?>
                                                                    <li class="padding0">
                                                                        <?php echo $j . ". " . $reply_content; ?> [<strong><?php echo $reply['keyword_reply_sender']; ?></strong>]
                                                                    </li>
                                                                    <?php
                                                                    $j++;
                                                                }
                                                                ?>
                                                            </ul>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <!--required data-parsley-required-message='Entrer Sender Id'-->
                                                    <!--required data-parsley-required-message='Entrer Message'-->
                                                    <button type="button" class="btn btn-primary btn-xs keyword_reply" data-toggle="popover"
                                                            id="popover<?php echo $i; ?>"
                                                            data-content="<form class='forms' action='javascript:void(0);'>
                                                            <div class='form-group'>
                                                            <input type='hidden' name='keyword_type' id='keyword_type<?php echo $i; ?>' value='short' />
                                                            <input type='text' name='reply_sender' id='reply_sender<?php echo $i; ?>' class='form-control' placeholder='Enter Sender Id'
                                                            data-parsley-minlength='6' data-parsley-minlength-message='Sender Id must be 6 characters'
                                                            data-parsley-maxlength='6' data-parsley-maxlength-message='Sender Id must be 6 characters' />
                                                            </div>
                                                            <div class='form-group'>
                                                            <textarea class='form-control' name='reply_content' id='reply_content<?php echo $i; ?>'
                                                            rows='2' placeholder='Enter Reply Content'></textarea></div>
                                                            <div class='form-group'>
                                                            <button class='btn btn-default btn-xs' type='button' 
                                                            onclick='saveKeywordReply(<?php echo $i; ?>, <?php echo $short_keyword['short_keyword_id']; ?>);'
                                                            id='btn'>Save</button></div></form>">
                                                        Add New
                                                    </button>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="email" data-pk="<?php echo $short_keyword['short_keyword_id']; ?>"
                                                               data-name='short|forward_email'>
                                                                   <?php echo ($short_keyword['forward_email']) ? $short_keyword['forward_email'] : "Your Email"; ?>
                                                            </a>
                                                        </li>
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="contact" data-pk="<?php echo $short_keyword['short_keyword_id']; ?>"
                                                               data-name='short|forward_contact'>
                                                                   <?php echo ($short_keyword['forward_contact']) ? $short_keyword['forward_contact'] : "Your Contact"; ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="webhook" data-pk="<?php echo $short_keyword['short_keyword_id']; ?>"
                                                               data-name='short|forward_webhook'>
                                                                   <?php echo ($short_keyword['forward_webhook']) ? $short_keyword['forward_webhook'] : "Your Web Hook URL"; ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">
                                                No Record Found!
                                            </td>
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
        <?php
    }

    // Inbox
    if (isset($tab) && $tab == 'inbox') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Inbox
                            </div>
                            <div class="col-md-6 content-header-title tbl text-right">
                                Short Code Balance: <?php echo (isset($user_info) && $user_info['short_code_balance']) ? $user_info['short_code_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Short Number</th>
                                        <th>Keyword</th>
                                        <th>Sender</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($short_inbox) && $short_inbox) {
                                        foreach ($short_inbox as $key => $inbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $inbox['short_number']; ?></td>
                                                <td><?php echo $inbox['short_keyword']; ?></td>
                                                <td><?php echo $inbox['short_inbox_sender']; ?></td>
                                                <td><?php echo $inbox['short_inbox_message']; ?></td>
                                                <td><?php echo $inbox['short_inbox_date']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Record Found!</td>
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
        <?php
    }

    // Sentbox
    if (isset($tab) && $tab == 'sentbox') {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet">
                    <div class="portlet-content">
                        <div class="row">
                            <div class="col-md-6 content-header-title tbl">
                                Sentbox
                            </div>
                            <div class="col-md-6 content-header-title tbl text-right">
                                Short Code Balance: <?php echo (isset($user_info) && $user_info['short_code_balance']) ? $user_info['short_code_balance'] : 0; ?>
                            </div>
                        </div>

                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Keyword</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($short_sentbox) && $short_sentbox) {
                                        foreach ($short_sentbox as $key => $sentbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $sentbox['short_keyword']; ?></td>
                                                <td><?php echo $sentbox['short_number']; ?></td>
                                                <td><?php echo $sentbox['sentbox_reciever']; ?></td>
                                                <td>
                                                    <h5><?php echo $sentbox['keyword_reply_sender']; ?></h5>
                                                    <p><?php echo $sentbox['keyword_reply']; ?></p>
                                                </td>
                                                <td><?php echo $sentbox['sentbox_date']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Record Found!</td>
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
        <?php
    }

    // Keywords
    if (isset($tab) && $tab == 'keywords') {
        ?>
        <div class="row">
            <div class="col-md-2 borderR">
                <div class="row content-header-title fhead">
                    <div class="col-md-12 padding0">Add New Keyword</div>
                </div>
                <div class="row">
                    <form role="form" class="tab-forms" id="shortKeywordForm" data-parsley-validate method='post' action="javascript:saveSLKeyword('short');">
                        <div class="form-group col-md-12 padding0">
                            <label for="short_number">Short Number</label>
                            <select name="short_number" id="short_number" class="form-control" required data-parsley-required-message="Please Select Short Number">
                                <option value="">Select Number</option>
                                <?php
                                if (isset($short_numbers) && $short_numbers) {
                                    foreach ($short_numbers as $key => $number) {
                                        ?>
                                        <option value="<?php echo $number['short_number_id']; ?>"><?php echo $number['short_number']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="short_keyword">Keyword</label>
                            <input type="text" name="short_keyword" id="short_keyword" placeholder="Please Enter Keyword" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Keyword"
                                   onkeyup="checkKeywordAvailability('short', this.value);" />
                            <!--data-parsley-minlength="6" data-parsley-minlength-message="Keyword must be of 6 charater long"-->
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="expiry_date">Validity</label>
                            <input type="text" name="expiry_date" id="expiry_date" placeholder="Please Enter Validity" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Validity" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <button type="submit" id="save_keyword" data-loading-text="Processing..." class="btn btn-primary" autocomplete="off">
                                Save Keyword
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-10">
                <div class="portlet">
                    <h2 class="content-header-title tbl">Keywords</h2>
                    <div class="portlet-content">
                        <div class="table-responsive" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Keyword</th>
                                        <th>Date</th>
                                        <th>Validity</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($short_keywords) && $short_keywords) {
                                        foreach ($short_keywords as $key => $keyword) {
                                            ?>
                                            <tr>
                                                <td><?php echo $keyword['short_number']; ?></td>
                                                <td><?php echo $keyword['short_keyword']; ?></td>
                                                <td><?php echo $keyword['short_keyword_date']; ?></td>
                                                <td><?php echo $keyword['short_keyword_expiry']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($keyword['short_keyword_status'])
                                                        echo "<span class='label label-success'>Approved</span>";
                                                    else
                                                        echo "<span class='label label-danger'>Disapproved</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="deleteSLKeyword('short', <?php echo $keyword['short_keyword_id']; ?>)"
                                                       class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Delete Keyword">
                                                        <i class="fa fa-trash-o"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }else {
                                        ?>
                                        <tr>
                                            <td colspan="6">No Record Found!</td>
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
        <?php
    }

    // Keyword Reply
    if (isset($tab) && $tab == 'keyword_reply') {
        ?>
        <div class="row">
            <div class="col-md-2 borderR">
                <div class="row content-header-title fhead">
                    <div class="col-md-12 padding0">Add New Keyword Reply</div>
                </div>
                <div class="row">
                    <form role="form" class="tab-forms" id="shortKeywordReplyForm" data-parsley-validate method='post' action="javascript:saveSLKReply('short');">
                        <div class="form-group col-md-12 padding0">
                            <label for="keyword_id">Keyword</label>
                            <select name="keyword_id" id="keyword_id" required="" data-parsley-required-message="Please Enter Keyword" class="form-control">
                                <option value="">Select Keyword</option>
                                <?php
                                if (isset($short_keywords) && $short_keywords) {
                                    foreach ($short_keywords as $key => $keyword) {
                                        ?>
                                        <option value="<?php echo $keyword['short_keyword_id']; ?>"><?php echo $keyword['short_keyword'] . " (" . $keyword['short_number'] . ")"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="reply_sender_id">Sender Id</label>
                            <input type="text" name="reply_sender_id" id="reply_sender_id" placeholder="Please Enter Sender Id" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Sender Id"
                                   data-parsley-minlength="6" data-parsley-minlength-message="Sender Id must be 6 character long" 
                                   data-parsley-maxlength="6" data-parsley-maxlength-message="Sender Id must be 6 character long" />
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="reply_content">Reply Content</label>
                            <textarea name="reply_content" id="reply_content" placeholder="Please Enter Reply Content" class="form-control" 
                                      required="" data-parsley-required-message="Please Enter Content" rows="4"></textarea>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <button type="submit" id="save_keyword_reply" data-loading-text="Processing..." class="btn btn-primary" autocomplete="off">
                                Save Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-10">
                <div class="portlet">
                    <div class="col-md-12 content-header-title tbl">
                        <div class="col-md-9 col-sm-4 col-xs-12">
                            <h4>Keyword Reply</h4>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12 pull-right">
                            <select name="filter_keyword_id" id="filter_keyword_id" class="form-control" onchange="filterKeywordReplies('short', this.value);">
                                <option value="-1">Select Keyword</option>
                                <?php
                                if (isset($short_keywords) && $short_keywords) {
                                    foreach ($short_keywords as $key => $keyword) {
                                        ?>
                                        <option value="<?php echo $keyword['short_keyword_id']; ?>"><?php echo $keyword['short_keyword'] . " (" . $keyword['short_number'] . ")"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive" id="data_table">
                        <table class="table table-hover bgf">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Keyword</th>
                                    <th>Reply Content</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($short_keyword_reply) && $short_keyword_reply) {
                                    foreach ($short_keyword_reply as $key => $keyword_reply) {
                                        ?>
                                        <tr>
                                            <td><?php echo $keyword_reply['short_number']; ?></td>
                                            <td><?php echo $keyword_reply['short_keyword']; ?></td>
                                            <td>
                                                <?php echo $keyword_reply['keyword_reply']; ?> [<?php echo $keyword_reply['keyword_reply_sender']; ?>]
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="deleteSLKReply('short', <?php echo $keyword_reply['keyword_reply_id']; ?>)"
                                                   class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Keyword Reply">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="4">No Record Found!</td>
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
        <?php
    }
    ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.forms').parsley();
    });
</script>