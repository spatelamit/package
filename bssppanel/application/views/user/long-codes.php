<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($tab) && $tab == 'dashboard') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/long_codes/dashboard">Dashboard</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'inbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/long_codes/inbox">Inbox</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'sentbox') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/long_codes/sentbox">Sentbox</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'keywords') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/long_codes/keywords">Keywords</a>
            </li>
            <li class="<?php echo (isset($tab) && $tab == 'keyword_reply') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>user/long_codes/keyword_reply">Keyword Replies</a>
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
                                Long Code Balance: <?php echo (isset($user_info) && $user_info['long_code_balance']) ? $user_info['long_code_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Long Number</th>
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
                                    if (isset($long_keywords) && $long_keywords) {
                                        $i = 1;
                                        foreach ($long_keywords as $key => $long_keywords) {
                                            ?>
                                            <tr>
                                                <td><?php echo $long_keywords['long_number']; ?></td>
                                                <td><?php echo $long_keywords['long_keyword']; ?></td>
                                                <td id="keyword_reply<?php echo $i; ?>">
                                                    <?php
                                                    if ($long_keywords['replies']) {
                                                        if (sizeof($long_keywords['replies'])) {
                                                            ?>
                                                            <ul class="padding0">
                                                                <?php
                                                                $j = 1;
                                                                foreach ($long_keywords['replies'] as $key => $reply) {
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
                                                    <button type="button" class="btn btn-primary btn-xs keyword_reply" data-toggle="popover"
                                                            id="popover<?php echo $i; ?>"
                                                            data-content="<form class='forms' action='javascript:void(0);'>
                                                            <div class='form-group'>
                                                            <input type='hidden' name='keyword_type' id='keyword_type<?php echo $i; ?>' value='long' />
                                                            <input type='text' name='reply_sender' id='reply_sender<?php echo $i; ?>' class='form-control' placeholder='Enter Sender Id'                                                             
                                                            data-parsley-minlength='6' data-parsley-minlength-message='Sender Id must be 6 characters'
                                                            data-parsley-maxlength='6' data-parsley-maxlength-message='Sender Id must be 6 characters' />
                                                            </div>
                                                            <div class='form-group'>
                                                            <textarea class='form-control' name='reply_content' id='reply_content<?php echo $i; ?>'
                                                            rows='2' placeholder='Enter Reply Content'></textarea></div>
                                                            <div class='form-group'>
                                                            <button class='btn btn-default btn-xs' type='submit' 
                                                            onclick='saveKeywordReply(<?php echo $i; ?>, <?php echo $long_keywords['long_keyword_id']; ?>);'
                                                            id='btn'>Save</button></div></form>">
                                                        Add New
                                                    </button>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="email" data-pk="<?php echo $long_keywords['long_keyword_id']; ?>"
                                                               data-name='long|forward_email'>
                                                                   <?php echo ($long_keywords['forward_email']) ? $long_keywords['forward_email'] : "Your Email"; ?>
                                                            </a>
                                                        </li>
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="contact" data-pk="<?php echo $long_keywords['long_keyword_id']; ?>"
                                                               data-name='long|forward_contact'>
                                                                   <?php echo ($long_keywords['forward_contact']) ? $long_keywords['forward_contact'] : "Your Contact"; ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="padding0">
                                                        <li class="padding0">
                                                            <a href="javascript:void(0);" class="webhook" data-pk="<?php echo $long_keywords['long_keyword_id']; ?>"
                                                               data-name='long|forward_webhook'>
                                                                   <?php echo ($long_keywords['forward_webhook']) ? $long_keywords['forward_webhook'] : "Your Web Hook URL"; ?>
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
                                Long Code Balance: <?php echo (isset($user_info) && $user_info['long_code_balance']) ? $user_info['long_code_balance'] : 0; ?>
                            </div>
                        </div>
                        <div class="table-responsive mt5" id="data_table">
                            <table class="table table-hover bgf">
                                <thead>
                                    <tr>
                                        <th>Long Number</th>
                                        <th>Keyword</th>
                                        <th>Sender</th>
                                        <th>Content</th>
                                        <th>Operator</th>
                                        <th>Circle</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($long_inbox) && $long_inbox) {
                                        foreach ($long_inbox as $key => $inbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $inbox['long_number']; ?></td>
                                                <td><?php echo $inbox['long_keyword']; ?></td>
                                                <td><?php echo $inbox['long_inbox_sender']; ?></td>
                                                <td><?php echo $inbox['long_inbox_message']; ?></td>
                                                <td><?php echo $inbox['long_inbox_operator']; ?></td>
                                                <td><?php echo $inbox['long_inbox_circle']; ?></td>
                                                <td><?php echo $inbox['long_inbox_date']; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">No Record Found!</td>
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
                                Long Code Balance: <?php echo (isset($user_info) && $user_info['long_code_balance']) ? $user_info['long_code_balance'] : 0; ?>
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
                                    if (isset($long_sentbox) && $long_sentbox) {
                                        foreach ($long_sentbox as $key => $sentbox) {
                                            ?>
                                            <tr>
                                                <td><?php echo $sentbox['long_keyword']; ?></td>
                                                <td><?php echo $sentbox['long_number']; ?></td>
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

    // Keyword
    if (isset($tab) && $tab == 'keywords') {
        ?>
        <div class="row">
            <div class="col-md-2 borderR">
                <div class="row content-header-title fhead">
                    <div class="col-md-12 padding0">Add New Keyword</div>
                </div>
                <div class="row">
                    <form role="form" class="tab-forms" id="longKeywordForm" data-parsley-validate method='post' action="javascript:saveSLKeyword('long');">
                        <div class="form-group col-md-12 padding0">
                            <label for="long_number">Long Number</label>
                            <select name="long_number" id="long_number" class="form-control" required data-parsley-required-message="Please Select Long Number">
                                <option value="">Select Number</option>
                                <?php
                                if (isset($long_numbers) && $long_numbers) {
                                    foreach ($long_numbers as $key => $number) {
                                        ?>
                                        <option value="<?php echo $number['long_number_id']; ?>"><?php echo $number['long_number']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-12 padding0">
                            <label for="long_keyword">Keyword</label>
                            <input type="text" name="long_keyword" id="short_keyword" placeholder="Please Enter Keyword" value=""
                                   class="form-control" required="" data-parsley-required-message="Please Enter Keyword"
                                   data-parsley-minlength="1" data-parsley-minlength-message="Keyword must be of 1 charater long"
                                   onkeyup="checkKeywordAvailability('long', this.value);" />
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
                                    if (isset($long_keywords) && $long_keywords) {
                                        foreach ($long_keywords as $key => $keyword) {
                                            ?>
                                            <tr>
                                                <td><?php echo $keyword['long_number']; ?></td>
                                                <td><?php echo $keyword['long_keyword']; ?></td>
                                                <td><?php echo $keyword['long_keyword_date']; ?></td>
                                                <td><?php echo $keyword['long_keyword_expiry']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($keyword['long_keyword_status'])
                                                        echo "<span class='label label-success'>Approved</span>";
                                                    else
                                                        echo "<span class='label label-danger'>Disapproved</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" onclick="deleteSLKeyword('long', <?php echo $keyword['long_keyword_id']; ?>)"
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
                    <form role="form" class="tab-forms" id="longKeywordReplyForm" data-parsley-validate method='post' action="javascript:saveSLKReply('long');">
                        <div class="form-group col-md-12 padding0">
                            <label for="keyword_id">Keyword</label>
                            <select name="keyword_id" id="keyword_id" required="" data-parsley-required-message="Please Enter Keyword" class="form-control">
                                <option value="">Select Keyword</option>
                                <?php
                                if (isset($long_keywords) && $long_keywords) {
                                    foreach ($long_keywords as $key => $keyword) {
                                        ?>
                                        <option value="<?php echo $keyword['long_keyword_id']; ?>"><?php echo $keyword['long_keyword'] . " (" . $keyword['long_number'] . ")"; ?></option>
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
                            <select name="filter_keyword_id" id="filter_keyword_id" class="form-control" onchange="filterKeywordReplies('long', this.value);">
                                <option value="-1">Select Keyword</option>
                                <?php
                                if (isset($long_keywords) && $long_keywords) {
                                    foreach ($long_keywords as $key => $keyword) {
                                        ?>
                                        <option value="<?php echo $keyword['long_keyword_id']; ?>"><?php echo $keyword['long_keyword'] . " (" . $keyword['long_number'] . ")"; ?></option>
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
                                if (isset($long_keyword_reply) && $long_keyword_reply) {
                                    foreach ($long_keyword_reply as $key => $keyword_reply) {
                                        ?>
                                        <tr>
                                            <td><?php echo $keyword_reply['long_number']; ?></td>
                                            <td><?php echo $keyword_reply['long_keyword']; ?></td>
                                            <td>
                                                <?php echo $keyword_reply['keyword_reply']; ?> [<?php echo $keyword_reply['keyword_reply_sender']; ?>]
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="deleteSLKReply('long', <?php echo $keyword_reply['keyword_reply_id']; ?>)"
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