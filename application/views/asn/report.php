<a href="<?php echo base_url()."asn/export_report/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
<br>
<br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>     
                            <th>No DO</th>
                            <th>No ASN</th>
                            <th>Tanggal PO</th>                                          
                            <th>No PO</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Company</th>
                            <th>Tipe</th>
                            <th>Tanggal Kirim</th>
                            <th>Expedisi</th>
                            <th>Total Unit</th>
                            <th>Total Unit DO</th>
                            <th>Total Value</th>
                            <th>Total Value DO</th>
                            <th>Unit Terpenuhi</th>                                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_report as $key) : ?>
                        <tr> 
                            <td><?php echo $key->no_do; ?></td>
                            <td><?php echo $key->no_asn; ?></td>                          
                            <td><?php echo $key->tglpo; ?></td>                     
                            <td><?php echo $key->nopo; ?></td>
                            <td><?php echo $key->branch_name; ?></td>
                            <td><?php echo $key->nama_comp; ?></td>
                            <td><?php echo $key->company; ?></td>
                            <td><?php echo $key->tipe; ?></td>
                            <td><?php echo $key->do_tanggal_kirim; ?></td>
                            <td><?php echo $key->do_nama_expedisi; ?></td>
                            <td><?php echo number_format($key->u); ?></td>
                            <td><?php echo number_format($key->u_do); ?></td>
                            <td><?php echo number_format($key->v); ?></td>
                            <td><?php echo number_format($key->v_do); ?></td>
                            <td><?php echo number_format($key->persen,1); ?>%</td>                                                
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>    
                            <th>No DO</th>
                            <th>No ASN</th>
                            <th>Tanggal PO</th>                                          
                            <th>No PO</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Company</th>
                            <th>Tipe</th>
                            <th>Tanggal Kirim</th>
                            <th>Expedisi</th>
                            <th>Total Unit</th>
                            <th>Total Unit DO</th>
                            <th>Total Value</th>
                            <th>Total Value DO</th>
                            <th>Unit Terpenuhi</th>                                  
                        </tr>
                    </tfoot>                    
                </table>
            </div>
        </div> 
    </div>
</div>