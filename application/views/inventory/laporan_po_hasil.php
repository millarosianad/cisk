<div class="col-sm-12">
        - Kodeprod : <b> <?php echo $kodeprod; ?> <br>
        - periode awal : <b> <?php echo $periode_1; ?> <br>
        - periode akhir : <b> <?php echo $periode_2; ?> <br>
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">   
            <div class="col-sm-10">
                <a href="<?php echo base_url()."inventory/laporan_po/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."inventory/export_laporan_po/"; ?>"class="btn btn-warning" role="button">
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
                    <th colspan="4"><center>DP</center></th>
                    <th colspan="2"><center>Total</center></th>
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Company</th>
                    <th>Channel</th>
                    <th>Unit</th> 
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->company; ?></td>
                    <td><font size="2px"><?php echo $a->channel; ?></td>
                    <td><font size="2px"><?php echo number_format($a->total_unit); ?></td>
                    <td><font size="2px"><?php echo number_format($a->total_value); ?></td>
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                <th>Branch</th>
                <th>Sub Branch</th>
                <th>Company</th>
                <th>Channel</th>
                <th>Unit</th> 
                <th>Value</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







