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


<?php foreach($query as $querys) : ?>

    <p><h2>Data Upload</h2></p><hr>
    <div class="row">
        <div class="col-lg-9"><center><font color="red"><?php echo isset($error) ? $error : ''; ?></font></center></div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <h4>Di bawah ini adalah data upload terakhir Sub Branch anda yang sudah masuk ke dalam website</h4>
        </div><br><br><br>

        <div class="col-lg-11">
          <div class="col-lg-3">
            1. Nama File :
          </div>
          <div class="col-lg-6">
            <font size="4px"><strong><?php echo $querys->filename; ?></strong></font>
          </div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-3">
            2. Dikirim secara :
          </div>
          <div class="col-lg-6">
            <?php 
              if ($querys->flag == '3') {
                $cara = "otomatis dikirim dan diproses oleh SDS";
              }elseif($querys->flag == '2'){
                $cara = "konversi excel di Tim IT";
              }else{
                $cara = "manual melalui upload di web ini";
              }
            ?>
              <font size="4px"><strong><?php echo $cara; ?></strong></font>
          </div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-3">
            3. Tanggal Kirim Data :
          </div>
          <div class="col-lg-6">
            <font size="4px"><strong><?php echo $querys->lastupload; ?></strong></font>
          </div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-3">
            4. Status Submit :
          </div>
          <div class="col-lg-8">
          <?php 
              if ($querys->status == '0') {
                $status = "Belum (data harus di submit agar belum masuk ke database website)";
              }else{
                $status = "Berhasil (data sudah masuk ke database website)";
              }
            ?>
              <font size="4px"><strong><?php echo $status; ?></strong></font>
          </div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-3">
            5. Tanggal Transaksi :
          </div>
          <div class="col-lg-8">
            <font size="4px"><strong><?php echo $querys->tanggal. '-' .$querys->bulan. '-' .$querys->tahun; ?></strong></font>
          </div>
        </div>

        <div class="col-lg-11">
          <div class="col-lg-3">
            6. Total Omzet :
          </div>
          <div class="col-lg-8">
            <font size="4px"><strong><?php echo "Rp. ".$querys->omzet; ?></strong></font>
          </div>
        </div>
    </div><br /><hr>

    <div class="row">

    
        <div class="col-lg-13">
          <div class="col-lg-9">
            Apakah anda ingin melanjutkan untuk meng-Upload Data Penjualan ?
            <a href="#" class="btn btn-primary" role="button" onclick="aktif()">Ya</a>
            <a href="#" class="btn btn-danger" role="button" onclick="nonaktif()">Tidak</a>
          </div>
    </div>

              <br><br>

    <div id="label_upload">
    <hr>
      <div class="row">
        <div class="col-sm-4"><strong>Pilih Tahun :</strong></div>
        <div class="col-md-4">
              <?php
                  //echo form_label(" Year : ");
                  echo form_label("<b>PILIH TAHUN FILE YANG DIUPLOAD : </b>");
                  $interval=date('Y')-2018;
                  $options=array();
                  //$options['2018']='2018';
                  for($i=1;$i<=$interval;$i++)
                  {
                      $options[''.$i+2018]=''.$i+2018;
                  }
                  echo form_dropdown('year', $options, date('Y'),'class="form-control"');
                  echo br();
              ?>
          </div>
      </div>
      
        <div class="row">
          <div class="col-sm-4"><strong>Apakah ini data closing di bulan ini ?</strong></div>
          <div class="col-md-4">
              <?php
                $options = array(
                    '0' => 'Bukan Closing Bulan Ini',
                    '1'  => 'Ya, Closing Bulan Ini'
                  );
                echo form_dropdown('status_closing',$options,'','class="form-control"');
              ?>

            </div>
        </div>
        <br>
        <div class="row">
        <div class="col-md-4"><strong>Upload data (DTXXXXXX.ZIP) :</strong></div>
        <div class="col-md-4"><input type="file" name="userfile" id="file" class="filestyle"/></div>
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

<?php endforeach; ?>
</div>


</form>

<script type="text/javascript" src="<?php echo base_url()."assets/js/readonly.js"?>"></script>

</body>
</html>
