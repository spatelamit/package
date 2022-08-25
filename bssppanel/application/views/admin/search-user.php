<div style="overflow-y: scroll; " class="page-content-title txt-center">
    <h3><i class="fa fa-search"></i> Search User: <?php echo $username; ?></h3> 
</div>
<div style="overflow-y: scroll; " id="user-mngmnt">
    <div class="container-fluid padding15" style="overflow-y: scroll;">
        <div  style="overflow-y: scroll;" class="row" id="searchnew">
            <?php echo (isset($user_info) && $user_info) ? $user_info : ''; ?>
        </div>
    </div>
</div>