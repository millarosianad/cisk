<div class="col-sm-10">
    - Tahun : <?php echo $tahun; ?> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
    - Breakdown : Kodeprod
    <hr>
    Informasi !! : <br>
    - Halaman ini hanya menampilkan nilai Unit. Untuk melihat Value. Silahkan klik Export.
    - Class dan Tipe Outlet berdasarkan data terbaru yang diupload (current)
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sales_outlet"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_outlet/$tahun/2/kodeprod"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    <a href="<?php echo base_url()."sales_omzet/export_excel_outlet/$tahun/2/"; ?>"class="btn btn-success" role="button">
    export(.excel)</a>
    </div>
    <br>