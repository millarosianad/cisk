<!-- multipart/form-data -->
<!-- <form method='post' action='/upload_file/file_upload' enctype='multipart/form-data'> -->
<?php echo form_open_multipart('/upload_file/file_upload'); ?>
<div class="card-block">

  <?php foreach ($query as $querys) : ?>
    <div class="row">
      <div class="col-lg-9">
        <center>
          <font color="red"><?php echo isset($error) ? $error : ''; ?></font>
        </center>
      </div>
    </div>

    <div class="row">
      <h4>Di bawah ini adalah data upload terakhir Sub Branch anda yang sudah masuk ke dalam website</h4>
    </div>
    <br>

    <div class="row">
      <div class="col-lg-3">
        1. Nama File :
      </div>
      <div class="col-lg-6">
        <font size="4px"><strong><?php echo $querys->filename; ?></strong></font>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        2. Dikirim secara :
      </div>
      <div class="col-lg-6">
        <?php
        if ($querys->flag == '3') {
          $cara = "otomatis dikirim dan diproses oleh SDS";
        } elseif ($querys->flag == '2') {
          $cara = "konversi excel di Tim IT";
        } else {
          $cara = "manual melalui upload di web ini";
        }
        ?>
        <font size="4px"><strong><?php echo $cara; ?></strong></font>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        3. Tanggal Kirim Data :
      </div>
      <div class="col-lg-6">
        <font size="4px"><strong><?php echo $querys->lastupload; ?></strong></font>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        4. Status Submit :
      </div>
      <div class="col-lg-8">
        <?php
        if ($querys->status == '0') {
          $status = "Belum (data harus di submit agar belum masuk ke database website)";
        } else {
          $status = "Berhasil (data sudah masuk ke database website)";
        }
        ?>
        <font size="4px"><strong><?php echo $status; ?></strong></font>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        5. Tanggal Transaksi :
      </div>
      <div class="col-lg-8">
        <font size="4px"><strong><?php echo $querys->tanggal . '-' . $querys->bulan . '-' . $querys->tahun; ?></strong></font>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-3">
        6. Total Omzet :
      </div>
      <div class="col-lg-8">
        <font size="4px"><strong><?php echo "Rp. " . $querys->omzet; ?></strong></font>
      </div>
    </div>
    <hr>

    <div class="row">
      <div class="col-lg-12">
        Apakah anda ingin melanjutkan untuk meng-Upload Data Penjualan ?
        <a href="#" class="btn btn-round btn-primary btn-sm" role="button" data-toggle="modal" data-target="#uploadModal">Upload</a>
      </div>
    </div>

  <?php endforeach; ?>
</div>

<!-- =============================================== modal ======================================================== -->

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6"><strong>Pilih Tahun File Yang Diupload :</strong></div>
          <div class="col-md-6">
            <?php
            //echo form_label(" Year : ");
            $interval = date('Y') - 2018;
            $options = array();
            //$options['2018']='2018';
            for ($i = 1; $i <= $interval; $i++) {
              $options['' . $i + 2018] = '' . $i + 2018;
            }
            echo form_dropdown('year', $options, date('Y'), 'class="form-control"');
            ?>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-6"><strong>Upload data (DTXXXXXX.ZIP) :</strong></div>
          <div class="col-md-6"><input type="file" name="userfile" class="form-control" id="userfile" required multiple="" /></div>

          <div class="col-md-12 loading" align="center">
            <img src="<?= base_url() . 'assets/gif/loading.gif' ?>" alt="">
          </div>
        </div>
      </div>
      <div class="modal-footer" style="justify-content: center;">
        <button class="btn btn-round btn-sm btn-success submit" value="upload">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $(".loading").hide();
    $(".submit").click(function() {
      $(".loading").show();
      $(".submit").hide();
    });
  });
</script>