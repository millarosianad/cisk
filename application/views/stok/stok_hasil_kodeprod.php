<div class="col-sm-10">
        - Tahun : <b> <?php echo $tahun; ?> </b> <br>
        - Kodeprod :  <?php echo $kodeprod; ?><hr>
        - Breakdown : Kodeprod
        Informasi !! : Halaman ini hanya menampilkan nilai Unit. Untuk melihat Value, silahkan klik Export
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."stok/stok_product/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."stok/export_stok_product/1"; ?>"class="btn btn-warning" role="button">
                <!-- <a href="<?php echo base_url()."stok/export_stok_product/$tahun/$created_date->created_date/1/kodeprod"; ?>"class="btn btn-warning" role="button"> -->
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>        
            </div>
            <div class="col-sm-5"></div>
        </div>
    </div>
</div>

<div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>Sub Branch</th>
                    <th>Kode Product</th>
                    <th>Nama Product</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th> 
                    <th>Apr</th> 
                    <th>Mei</th> 
                    <th>Jun</th> 
                    <th>Jul</th> 
                    <th>Ags</th> 
                    <th>Sep</th> 
                    <th>Okt</th> 
                    <th>Nov</th> 
                    <th>Des</th>            
                </tr>
            </thead>
            <tbody>

            <?php foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php echo $a->unit_1; ?></td>
                    <td><font size="2px"><?php echo $a->unit_2; ?></td>
                    <td><font size="2px"><?php echo $a->unit_3; ?></td>
                    <td><font size="2px"><?php echo $a->unit_4; ?></td>
                    <td><font size="2px"><?php echo $a->unit_5; ?></td>
                    <td><font size="2px"><?php echo $a->unit_6; ?></td>
                    <td><font size="2px"><?php echo $a->unit_7; ?></td>
                    <td><font size="2px"><?php echo $a->unit_8; ?></td>
                    <td><font size="2px"><?php echo $a->unit_9; ?></td>
                    <td><font size="2px"><?php echo $a->unit_10; ?></td>
                    <td><font size="2px"><?php echo $a->unit_11; ?></td>
                    <td><font size="2px"><?php echo $a->unit_12; ?></td>
                </tr>
            <?php endforeach; ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Sub Branch</th>
                    <th>Kode Product</th>
                    <th>Nama Product</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>Mar</th> 
                    <th>Apr</th> 
                    <th>Mei</th> 
                    <th>Jun</th> 
                    <th>Jul</th> 
                    <th>Ags</th> 
                    <th>Sep</th> 
                    <th>Okt</th> 
                    <th>Nov</th> 
                    <th>Des</th>   
                </tr>
            </tfoot>
        </table>
    </div>
</div>