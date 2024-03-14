<div class="col-sm-10">
    - periode outlet transaksi (single count)  : <b> <?php echo $from_otsc.' s/d '.$to_otsc; ?> </b> <br>
    - periode exception  : <b> <?php echo $from_outlet.' s/d '.$to_outlet; ?> </b> <br>
    - Kodeprod : <b> <?php echo $kodeprod; ?><hr>
    Informasi !! : menu ini menampilkan nilai ot baru yang belum pernah bertransaksi selama periode yang ditentukan 
    <br>
    <br>
    <a href="<?php echo base_url()."outlet_transaksi/otsc"; ?>"class="btn btn-dark" role="button">
                kembali</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_export"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_export_exception"; ?>" class="btn btn-danger" role="button">outlet exception (.csv)</a>
    <!-- <a href="<?php echo base_url()."outlet_transaksi/otsc_detail_export"; ?>" class="btn btn-success" role="button">export detail otsc (.csv)</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_outlet_export"; ?>" class="btn btn-info" role="button">export otsc outlet(.csv)</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_master_export"; ?>" class="btn btn-danger" role="button">export master outlet (.csv)</a>
    <a href="<?php echo base_url()."outlet_transaksi/otsc_master_otsc_export"; ?>" class="btn btn-primary" role="button">export master otsc (.csv)</a> -->
    </div>
    <br>
    
<?php $no = 1; ?>
<div class="card table-card">
    <div class="card-header">
     <div class="card-block">
         <div class="dt-responsive table-responsive">
           <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
            <thead>
                <tr>
                    <th width="1"><font size="2px">Branch</font></th>                
                    <th width="1"><font size="2px">SubBranch</font></th>   
                    <th width="1"><font size="2px">OT</font></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($proses as $x) : ?>
                <tr>
                    <td><?php echo $x->branch_name; ?></td>                       
                    <td><?php echo $x->nama_comp; ?></td>                    
                    <td><font size="2px"><?php echo $x->ot; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                    <tr>
                        <th width="1"><font size="2px">Branch</font></th>                
                        <th width="1"><font size="2px">SubBranch</font></th>   
                        <th width="1"><font size="2px">OT</font></th>
                    </tr>
            </tfoot>
            </table>
            </div>
        </div>
    </div>
</div>
