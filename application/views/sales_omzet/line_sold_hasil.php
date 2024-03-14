<div class="col-sm-12">
        - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
        - Kodeprod : <b> <?php echo $kodeprod; ?><br>
        - Breakdown = - <hr>
        Informasi !! :<br> 
        - total faktur antara web dan bra mungkin akan beda karena di web faktur batal juga dihitung. Gap web vs bra kurang dari 0.09. Jika ditemukan lebih dari ini dapat menghubungi IT.<br>
        - Pemilihan periode dengan gap 2 tahun ke atas tidak bisa diproses karena akan mengambil resource yang besar.
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."sales_omzet/line_sold/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."sales_omzet/export_csv_linesold/99"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>        
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>

    <hr>
    
    <div class="card-block">

    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th colspan="2"><center>DP</center></th>
                    <th colspan="3"><center>Line Sold</th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Total Faktur</th> 
                    <th>Total Produk</th> 
                    <th>Total Produk / Total Faktur</th>                     
                </tr>
            </thead>
            <tbody>

            <?php foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->faktur; ?></td>
                    <td><font size="2px"><?php echo $a->produk; ?></td>
                    <td><font size="2px"><?php echo $a->line_sold; ?></td>
                </tr>
            <?php endforeach; ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Total Faktur</th> 
                    <th>Total Produk</th> 
                    <th>Total Produk / Total Faktur</th> 
                </tr>
            </tfoot>
        </table>
    </div>





    </div>
</div>







