<div class="col-sm-10">
    - Tahun : <?php echo $year; ?> <br>
    - Kodeprod : <?php echo $kodeprod; ?> <br>
    - Class Outlet : <?php echo $kdc; ?> <br>
    - Breakdown : SubBranch, Kodeprod, Tipe, Group Product<br>

    <hr>
    Informasi !! : Untuk sementara web belum bisa menampilkan data ke layar monitor anda, namun sudah bisa ditarik dengan cara <b>"Klik Export"</b>
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/sell_out_product"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_sales_per_product_kode_kodeprod_kode_type_grup"; ?>"class="btn btn-warning" role="button">
    export(.csv)</a>
    <a href="<?php echo base_url()."sales_omzet/export_excel_sales_per_product/28"; ?>"class="btn btn-success" role="button">
    export(.excel)</a>
    </div>
    <br>
    
    
 
