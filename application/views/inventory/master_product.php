<div class="col-sm-12">
        <!-- - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
        - periode awal : <b> <?php echo $periode_1; ?> <br>
        - periode akhir : <b> <?php echo $periode_2; ?> <br> -->
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">   
            <div class="col-sm-10">
                <a href="<?php echo base_url()."inventory/export_master_produk/"; ?>"class="btn btn-warning" role="button">
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
                    <th>Principal</th>
                    <th>KodeProduk</th>
                    <th>NamaProduk</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->namasupp; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php
                        if ($a->active == '1') {
                            echo "aktif";
                        }else{
                            echo "non aktif";
                        }
                    ?></td>
                    <td>
                        <?php
                        echo anchor(base_url()."inventory/master_product_detail/".$a->kodeprod, 'detail', "class='btn btn-primary btn-sm'");
                        ?>            
                    </td>
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Principal</th>
                    <th>KodeProduk</th>
                    <th>NamaProduk</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







