<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ($this->session->flashdata('abc')) {
    echo $this->session->flashdata('abc');
}

?>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login For Smsc Details </title>

	
</head>
<body bgcolor="">

    <div align="center" >
        <form method="post" action="<?php echo base_url(); ?>admin/check_admin_smsc_details">
        <h2>TO CHECK THE SMSC DETAILS PLESE RETYPE USERNAME & PASSWORD </h2>
        Username : <input type="text" name="username" placeholder="username" required="required"/><br><br>
        Password : <input type="password" name="password" placeholder="password" required="required"/><br><br>
        <input type="submit" name="submit" value="Log in" /><br><br>
    
        </form>
    </div>
    
</body>
</html>
