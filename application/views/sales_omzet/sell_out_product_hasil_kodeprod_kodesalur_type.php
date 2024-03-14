<div class="col-sm-10">
    - Tahun : <?php echo $year; ?> <br>
    - Kodeprod : <?php echo $kodeprod; ?> <br>
    - Class Outlet : <?php echo $kdc; ?> <br>
    - Breakdown : Kodeprod, Class, Tipe<br>

    <hr>
    Informasi !! : Untuk sementara web belum bisa menampilkan data ke layar monitor anda, namun sudah bisa ditarik dengan cara <b>"Klik Export"</b>
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sell_out_product"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_sales_per_product/$year/7/kodeprod_kodesalur_type"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    </div>
    <br>
    
    
 
