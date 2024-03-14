<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload Form</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
</head>
<body>



<?php echo form_open_multipart('/all_upload/file_upload');?>
<div class="container">
<br /><br /><br />
    <p><h1>UPLOAD FILE</h1><hr></p>
    <div class="row">
        <div class="col-lg-9"><center><font color="red"><?php echo isset($error) ? $error : ''; ?></font></center></div>
      </div><br />
      <div class="row">
        <div class="col-md-3"><strong>Upload data (DTXXXXXX.ZIP) :</strong></div>
        <div class="col-md-4"><input type="file" name="userfile" class="filestyle" /></div>
      </div><br />
      <div class="row">
        <div class="col-sm-3"><strong>Pilih Tahun :</strong></div>
        <div class="col-md-4">
            <?php
                 //echo form_label(" Year : ");
                echo form_label("<b>PILIH TAHUN FILE YANG DIUPLOAD : </b>");
                $interval=date('Y')-2016;
                $options=array();
                $options['2016']='2016';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, date('Y'),'class="form-control"');
                echo br();
            ?>
        </div>
      </div>
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
