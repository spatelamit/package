
<div id="nav_sub">
    <div class="row">
        <ul class="nav nav-pills">
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 'long_code') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/long_code">Long Codes</a>
            </li>
            <li class="<?php echo (isset($page_type) && $page_type && $page_type == 'short_code') ? "active" : ""; ?>">
                <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/short_code">Short Codes</a>
            </li>
        </ul>
    </div>
</div>
</div>

<nav class="dlTool_nav visible-xs">
    <a href="#" class="navToggle_xs" title="show menu">
        <span class="navClosed"><i>show menu</i></span>
    </a>
    <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/long_code" title="Item 1">Long Codes</a>
    <a href="<?php echo base_url(); ?>api_docs/virtual_numbers/short_code" title="Item 2">Short Codes</a>
</nav>

<div class="container">
    <div class="row">
        <?php
        if (isset($page_type) && $page_type && $page_type == 'long_code') {
            ?>
            <div class="row">
                <div class="col-md-5 borderR">
                    <div class="portlet">
                        <div class="mw-parameter">
                            <h2 class="content-header-title">Web Hook URL</h2>
                            <h4>We Will hit the URL in following format:</h4>
                            <!--<p class="text-success">http://yourdomain.com/get_long_code.php?sender%22%3A%22546b384ce51f469a2e8b4567%22%2C%22number%22%3A%7B%22911234567890%22%3A%22message%22%3A%7B%22911234567890%22%3A%7B%22keyword%22%3A%222014-11-18+17%3A45%3A59%22%2C%22datetime%22%3A1%2C%22 </p>-->
                            <p class="text-success">http://yourdomain.com/get_long_code.php</p>
                            <h4>Sample data:</h4>
                            <p class="text-success">
                                {
                                sender:911234567890,
                                message:Thanks,
                                number:9999911111,
                                keyword:KEYWORD,
                                datetime:2015-01-01 10:00:00
                                }
                            </p>
                            <div class="portlet-content">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered bgf">
                                        <thead>
                                            <tr>
                                                <th>Parameter Name</th>
                                                <th>Value</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>sender</td>
                                                <td>varchar</td>
                                                <td>Sender Number</td>
                                            </tr>
                                            <tr>
                                                <td>message</td>
                                                <td>Text</td>
                                                <td>Text Message</td>
                                            </tr>
                                            <tr>
                                                <td>number</td>
                                                <td>varchar</td>
                                                <td>Receiver Number</td>
                                            </tr>
                                            <tr>
                                                <td>keyword</td>
                                                <td>Text</td>
                                                <td>keyword</td>
                                            </tr>
                                            <tr>
                                                <td>datetime (YYYY-MM--DD hh:mm:ss)</td>
                                                <td>string</td>
                                                <td>Displays the time and Date of SMS YYYY: Year MM: Month DD: Date hh: Hours mm: Minutes ss: Seconds</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="col-md-7">
                    <h2 class="content-header-title">Sample Code</h2>
                    <pre class="language">
            <code class="language-php">
            <span class="mw-code php"><span class="mw-code delimiter">&lt;?php</span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your sender
            </span><span class="mw-code variable">$sender = $_REQUEST["sender"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your message
            </span><span class="mw-code variable">$message = $_REQUEST["message"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your number
            </span><span class="mw-code variable">$number = $_REQUEST["number"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your keyword
            </span><span class="mw-code variable">$keyword = $_REQUEST["keyword"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your datetime
            </span><span class="mw-code variable">$datetime = $_REQUEST["datetime"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            </span><span class="mw-code variable">$link = mysqli_connect("myhost", "myuser", "mypassw", "mybd") or die("Error " . mysqli_error($link));</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            </span><span class="mw-code variable">$query = "INSERT INTO mytable (sender, message, number, keyword, datetime) VALUES ('" . $sender . "', '" . $number . "','" . $message . "','" . $keyword . "','" . $datetime . "')";</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            //execute the query.
            </span><span class="mw-code variable">$result = $link->query($query);</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            <span class="mw-code delimiter">?&gt;</span></span>
            </code>
    </pre>
    </div>
    </div>
    <?php
}
if (isset($page_type) && $page_type && $page_type == 'short_code') {
    ?>
    <div class="row">
        <div class="col-md-5 borderR">
            <div class="portlet">
                <div class="mw-parameter">
                    <h2 class="content-header-title">Web Hook URL</h2>
                    <h4>We Will hit the URL in following format:</h4>
                    <!--<p class="text-success">http://yourdomain.com/get_short_code.php?sender%22%3A%22546b384ce51f469a2e8b4567%22%2C%22message%22%3A%7B%22911234567890%22%3A%7B%22keyword%22%3A%222014-11-18+17%3A45%3A59%22%2C%22datetime%22%3A1%2C%22 </p>-->
                    <p class="text-success">http://yourdomain.com/get_short_code.php</p>
                    <h4>Sample data:</h4>
                    <p class="text-success">
                        {
                        sender:343434,
                        message:Thanks,
                        receiver:9999911111,
                        keyword:KEYWORD,
                        datetime:2015-01-01 10:00:00
                        }
                    </p>
                    <div class="portlet-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered bgf">
                                <thead>
                                    <tr>
                                        <th>Parameter Name</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>sender</td>
                                        <td>varchar</td>
                                        <td>Sender Number</td>
                                    </tr>
                                    <tr>
                                        <td>message</td>
                                        <td>Text</td>
                                        <td>Text Message</td>
                                    </tr>
                                    <tr>
                                        <td>receiver</td>
                                        <td>varchar</td>
                                        <td>Receiver Number</td>
                                    </tr>
                                    <tr>
                                        <td>keyword</td>
                                        <td>Text</td>
                                        <td>keyword</td>
                                    </tr>
                                    <tr>
                                        <td>datetime (YYYY-MM--DD hh:mm:ss)</td>
                                        <td>string</td>
                                        <td>Displays the time and Date of SMS YYYY: Year MM: Month DD: Date hh: Hours mm: Minutes ss: Seconds</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class="col-md-7">
            <h2 class="content-header-title">Sample Code</h2>
            <pre class="language">
            <code class="language-php">
            <span class="mw-code php"><span class="mw-code delimiter">&lt;?php</span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your sender
            </span><span class="mw-code variable">$sender = $_REQUEST["sender"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your message
            </span><span class="mw-code variable">$message = $_REQUEST["message"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your receiver
            </span><span class="mw-code variable">$receiver = $_REQUEST["receiver"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your keyword
            </span><span class="mw-code variable">$keyword = $_REQUEST["keyword"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            // your datetime
            </span><span class="mw-code variable">$datetime = $_REQUEST["datetime"];</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            </span><span class="mw-code variable">$link = mysqli_connect("myhost", "myuser", "mypassw", "mybd") or die("Error " . mysqli_error($link));</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            </span><span class="mw-code variable">$query = "INSERT INTO mytable (sender, message, receiver, keyword, datetime) VALUES ('" . $sender . "', '" . $receiver . "','" . $message . "','" . $keyword . "','" . $datetime . "')";</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            //execute the query.
            </span><span class="mw-code variable">$result = $link->query($query);</span></span>
            <span class="mw-code comment" spellcheck="true"></span>
            <span class="mw-code delimiter">?&gt;</span></span>
            </code>
    </pre>
    </div>
    </div>
    <?php
}
?>
</div>
</div>