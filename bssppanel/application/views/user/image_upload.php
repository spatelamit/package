<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Bulk SMS- User Control Panel</title>

</head>
<body>
<?php //echo $error; ?>
<form method="POST" action="<?php echo base_url(); ?>index.php/purchase" enctype="multipart/form-data">
<input type="file" name="file_upload"><br>
<input type="submit" name="submit" value="Upload">
	
</form>

</body>
</html>