<div class="col-sm-12">
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."inventory/export_monitoring_stock_deltomed/"; ?>"class="btn btn-warning" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>     
                <a href="<?php echo base_url()."inventory/export_monitoring_stock_deltomed_po_outstanding/"; ?>"class="btn btn-primary" role="button">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export po outstanding (csv)</a> 
                <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed_update_next/"; ?>"class="btn btn-success" role="button">
                Proses 2</a>     
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
                </tr>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Kodeprod</th> 
                    <th>Namaprod</th> 
                    <th>Avg</th> 
                    <th>Stock</th> 
                    <th>PoOutstanding</th>
                    <th>SalesBerjalan</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($monitoring_stock_deltomed_update as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <td><font size="2px"><?php echo $a->rata; ?></td>
                    <td><font size="2px"><?php echo $a->stock_akhir_unit; ?></td>
                    <td><font size="2px"><?php echo $a->po_outstanding_unit; ?></td>
                    <td><font size="2px"><?php echo $a->sales_berjalan; ?></td>
                    
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Kodeprod</th> 
                    <th>Namaprod</th> 
                    <th>Avg</th> 
                    <th>Stock</th> 
                    <th>PoOutstanding</th>
                    <th>SalesBerjalan</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







