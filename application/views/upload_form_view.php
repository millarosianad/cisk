<html>
<head>
<title>Upload Form</title>
</head>
<body>
<?php echo isset($error) ? $error : ''; ?>
<?php echo form_open_multipart('/uploadfile/file_upload');?>
<input type="file" name="userfile" size="20" />
<br /><br />
<input type="submit" value="upload" />
</form>
</body>
</html>
