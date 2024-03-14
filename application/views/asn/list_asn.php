
<?php echo form_open_multipart($url); ?>
<div class="col-sm-12">
    <div class="form-group row">
        <div class="col-sm-6">
            <a href ="<?php echo base_url()."asn/export_asn/"; ?>" class='btn btn-success' role='button'>Download</a>
        </div>
        <label class="col-form-label">Upload ASN</label>
        <div class="col-sm-4">
            <input type="file" name="file" class="form-control" required>
        </div>
            
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close(); ?>
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>   
                            <th></th> 
                            <th>Tanggal PO</th>                                           
                            <th>No PO</th>
                            <!-- <th>Branch</th> -->
                            <th>SubBranch</th>
                            <!-- <th>Company</th> -->
                            <!-- <th>Tipe</th> -->
                            <!-- <th>Total Unit</th> -->
                            <th>Total Karton</th>
                            <!-- <th>Total Value</th> -->
                            <th></th>                                      
                            <th></th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_po as $key) : ?>
                        <tr>    
                            <td>
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
                            <td><?php echo $key->tglpo; ?></td>    
                            <td>
                                <a href="<?php echo base_url()."assets/po/".$key->nopox ?>.pdf"><span class="glyphicon glyphicon-humburger"><?php echo $key->nopo; ?></a>
                            </td>
                            <!-- <td><?php echo $key->branch_name; ?></td> -->
                            <td><?php echo $key->nama_comp; ?></td>
                            <!-- <td><?php echo $key->company; ?></td> -->
                            <!-- <td><?php echo $key->tipe; ?></td> -->
                            <!-- <td><?php echo number_format($key->total_unit); ?></td>                                                -->
                            <td><?php echo number_format($key->total_karton); ?></td>                                               
                            <td>
                                <a href="<?php echo base_url()."asn/input_asn/".$key->id ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-humburger">Input</a>
                            </td>                            
                            <td><center>
                                <?php
                                    if($key->status_closed == '1'){
                                        echo "closed"; 
                                    }else{ ?>
                                        <a href="<?php echo base_url()."asn/closed_asn/".$key->id ?>" class="btn btn-dark" role="button" onclick="return confirm('Are you sure?')">Closed</a>
                                    <?php
                                    }                            
                                ?>
                                </center>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot><tr>
                            <th></th> 
                            <th>Tanggal PO</th>                                           
                            <th>No PO</th>
                            <!-- <th>Branch</th> -->
                            <th>SubBranch</th>
                            <!-- <th>Company</th> -->
                            <!-- <th>Tipe</th> -->
                            <!-- <th>Total Unit</th> -->
                            <th>Total Karton</th>
                            <!-- <th>Total Value</th> -->
                            <th></th>                                  
                            <th>closed</th>                                  
                        </tr>
                    </tfoot>                    
                </table>
            </div>
        </div> 
    </div>
</div>