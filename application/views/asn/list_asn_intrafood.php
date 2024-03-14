
<?php echo form_open($url_export); ?>

<div class="col-sm-12 mb-3">Export ASN</div>

<div class="col-sm-12">
    <div class="form-inline row">
        <div class="col-sm-12">
            <input class="form-control" type="date" name="from" required />
            <input class="form-control" type="date" name="to" required />
            <?php echo form_submit('submit','Export','class="btn btn-success btn-sm"');?>            
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<hr>

<div class="col-sm-12 mb-3">Import ASN</div>
<?php echo form_open_multipart($url_import.'/'.$supp); ?>
<div class="col-sm-12">
    <div class="form-inline row">
        <div class="col-sm-12">            
            <input type="file" name="file" class="form-control" required>
            <?php echo form_submit('submit','Proses','class="btn btn-primary btn-sm"');?>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>   
                            <th></th> 
                            <th><font size='2px'>Tanggal PO</font></th>                                           
                            <th><font size='2px'>No PO</th>
                            <th><font size='2px'>SubBranch</th>
                            <th><font size='2px'>Company</th>
                            <th><font size='2px'>Total Karton</th>
                            <th></th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_po as $key) : ?>
                        <tr>    
                            <td><font size='2px'>
                                <?php 
                                if ($key->total_produk_po <> null) {
                                    if ($key->total_karton_po == $key->total_karton_asn) {
                                        echo '<i class="fa fa-check-circle fa-lg" style="color:green"></i>';
                                    }else{
                                        echo '<i class="fa fa-exclamation-circle fa-lg" style="color:orange"></i>';
                                    }
                                }else{
                                    echo '<i class="fa fa-exclamation-circle fa-lg" style="color:red"></i>';
                                }
                                ?>
                            </td>
                            <td><font size='2px'><?php echo $key->tglpo; ?></td>    
                            <td>
                                <a target = "blank" href="<?php echo base_url()."trans/po/print/".$key->id ?>"><span class="glyphicon glyphicon-humburger">
                                <font size='2px'>
                                <?php echo $key->nopo; ?></a>
                            </td>
                            <td><font size='2px'><?php echo $key->nama_comp; ?></td>
                            <td><font size='2px'><?php echo $key->company; ?></td>
                            <td><font size='2px'><?php echo number_format($key->total_karton); ?></td>                                             
                            <td>
                                <a href="<?php echo base_url()."asn/input_asn/".$key->id ?>" class="btn btn-primary btn-sm" role="button">Input</a>
                                |
                                <?php
                                    if($key->status_closed == '1'){
                                        echo "closed"; 
                                    }else{ ?>
                                        <a href="<?php echo base_url()."asn/closed_asn/".$key->id ?>" class="btn btn-dark btn-sm" role="button" onclick="return confirm('Are you sure?')">Closed</a>
                                    <?php
                                    }                            
                                ?>
                            </td>       
                        </tr>
                    <?php endforeach; ?>
                    </tbody>                 
                </table>
            </div>
        </div> 
    </div>
</div>