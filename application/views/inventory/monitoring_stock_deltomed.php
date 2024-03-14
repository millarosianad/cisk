<div class="col-sm-12">
        - Proses 1 : generate => avg|stock_akhir|sales_berjalan <br>
        - Proses 2 : generate => po_outstanding|total_stock|target|potensi_sales|stock_berjalan|doi|stock_ideal|suggest_po
    </div>

        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">   
        <?php 
            $id = $this->session->userdata('id');
            if ($id == '297' || $id == '515') { ?>
                <div class="col-sm-10">
                    <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed/"; ?>"class="btn btn-dark" role="button">
                    kembali</a>
                    <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed_clear/"; ?>"class="btn btn-danger" role="button">
                    clear cache</a>
                    <a href="<?php echo base_url()."inventory/export_monitoring_stock_deltomed/"; ?>"class="btn btn-warning" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>     
                    <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed_update/"; ?>"class="btn btn-primary" role="button">
                    proses 1</a>   
                    <a href="<?php echo base_url()."inventory/monitoring_stock_deltomed_update_next/"; ?>"class="btn btn-success" role="button">
                    proses 2</a>   
                </div>
            <?php    
            }else{ ?>
                <div class="col-sm-10">
                    <a href="<?php echo base_url()."inventory/export_monitoring_stock_deltomed/"; ?>"class="btn btn-warning" role="button">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> export (csv)</a>     
                       
                </div>

            <?php
            }
        ?>         
            
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
                    <th>Branch</th>
                    <th>Sub Branch</th>
                    <th>Kodeprod</th> 
                    <th>Namaprod</th>  
                    <th>TotalStock</th>
                    <th>Target</th>
                    <th>SuggestPO</th>
                    <th>LastUpload</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($get_monitoring_stock_deltomed_last as $a) : ?>
                <tr>                 
                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                    <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                    <td><font size="2px"><?php echo $a->namaprod; ?></td>
                    <!-- <td><font size="2px"><?php echo $a->rata; ?></td>
                    <td><font size="2px"><?php echo $a->stock_akhir_unit; ?></td>
                    <td><font size="2px"><?php echo $a->po_outstanding_unit; ?></td> -->
                    <td><font size="2px"><?php echo $a->total_stock_unit; ?></td>
                    <!-- <td><font size="2px"><?php echo $a->sales_berjalan_unit; ?></td> -->
                    <td><font size="2px"><?php echo $a->target_unit; ?></td>
                    <!-- <td><font size="2px"><?php echo $a->potensi_sales_unit; ?></td>
                    <td><font size="2px"><?php echo $a->stock_berjalan_unit; ?></td>
                    <td><font size="2px"><?php echo $a->doi_unit; ?></td>
                    <td><font size="2px"><?php echo $a->sl_hari; ?></td>
                    <td><font size="2px"><?php echo $a->sl_unit; ?></td> -->
                    <td><font size="2px"><?php echo $a->suggest_po; ?></td>
                    <td><font size="2px"><?php echo $a->lastupload; ?></td>
                    
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
                    <th>TotalStock</th>
                    <th>Target</th>
                    <th>SuggestPO</th>
                    <th>LastUpload</th>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>
</div>







