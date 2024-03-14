<?php 
    echo form_open($url);  
?>

<div class="col-sm-12">
        - periode : <b> <?php echo $periode_1.' - '.$periode_2; ?> </b> <br>
    </div>
</div>
        
<div class="card-block">

    <div class="col-sm-10">
        <div class="form-group row">            
            <div class="col-sm-10">
                <a href="<?php echo base_url()."inventory/po_outstanding/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
                <a href="<?php echo base_url()."inventory/po_outstanding_strive_export/"; ?>"class="btn btn-warning" role="button">
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
                    <th width='15%'><center>Branch</th>
                    <th width='10%'><center>SubBranch</th>
                    <th width='10%'><center>TglPo</th>
                    <th width='10%'><center>NoPo</th>
                    <th width='10%'><center>Kodeprod</th>
                    <th width='10%'><center>Namaprod</th>
                    <th width='10%'><center>QtyPO</th>
                </tr>
            </thead>
            <tbody>

            <?php 
            // var_dump($proses);
            foreach($proses as $x) : ?>
                <tr>                 
                    <td><font size="2"><?php echo $x->branch_name; ?></td>
                    <td><font size="2"><?php echo $x->nama_comp; ?></td>
                    <td><font size="2"><?php echo $x->tglpo; ?></td>
                    <td><font size="2"><?php echo $x->nopo; ?></td>
                    <td><font size="2"><?php echo $x->kodeprod; ?></td>
                    <td><font size="2"><?php echo $x->namaprod; ?></td>
                    <td><font size="2"><?php echo number_format($x->qty_po); ?></td>
                </tr>
            <?php 
            endforeach; 
            ?>
            
            </tbody>
           
        </table>
    </div>
    </div>
</div>







