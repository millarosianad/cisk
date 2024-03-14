<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload Form</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
</head>
<body>

			<div>
				<?php echo validation_errors(); ?>
			</div>

<?php echo form_open_multipart('/download/create');?>


<div class="container">

    <p><h3>Halaman Upload Data Report (Delto)</h3><hr></p>
    <div class="row">
        <div class="col-lg-9"><center><font color="red"><?php echo isset($error) ? $error : ''; ?></font></center></div>
      </div><br />

      <div class="row">
        <div class="col-md-3"><strong>Title (Judul) :</strong></div>
        <div class="col-md-4"><input type="text" value="<?php echo set_value('title'); ?>" name="title" class="form-control" /></div>
      </div><br />

      <div class="row">
        <div class="col-md-3"><strong>File  :</strong><br>(ZIP, TXT, PDF, DOC, XLS, JPG, GIF, PNG )</div>
        <div class="col-md-4"><input type="file" name="userfile" class="filestyle" /></div>
      </div><br />

      <div class="row">
          <div class="col-md-8">
            <center>          
             <button class="btn btn-lg btn-primary" value="upload">Submit</button>
             <button class="btn btn-lg btn-default" value="upload">Reset</button>
            </center>  
          </div>
      </div>
    
</div>


</form>
</body>
</html>
