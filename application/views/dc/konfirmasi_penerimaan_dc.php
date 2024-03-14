<?php 
    foreach ($konfirmasi_pengiriman as $key) {
        $signature = $key->signature_dc;
    }
?>

<?php echo form_open_multipart($url); ?>

<div class="row">
    <div class="col-sm-3">Tanggal Sampai DP</div>
    <div class="col-sm-8">
        <input type="date" name="tanggal_tiba" class="form-control" required>
    </div>
</div>

<div class="row mt-3">
    <div class="col-sm-3">Foto Surat Jalan Mpm</div>
    <div class="col-sm-8">
        <input type="file" name="file_4" class="form-control" required>
        <p style="color: rgb(0 0 0 / 30%);">file harus ditandatangani oleh DP</p>
    </div>
</div>

<?php 
    $signature = $this->uri->segment('3');
?>
<input type="hidden" name="signature" value="<?= $signature; ?>">

<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-8">
        <?php echo form_submit('submit', 'submit data penerimaan', 'class="btn btn-warning"'); ?>
        <?php echo form_close(); ?>
    </div>
</div>

