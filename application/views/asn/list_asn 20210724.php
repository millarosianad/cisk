<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>    
                            <th>Tanggal PO</th>                                           
                            <th>No PO</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Company</th>
                            <th>Tipe</th>
                            <th>Total Unit</th>
                            <th>Total Value</th>
                            <th></th>                                      
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_po as $key) : ?>
                        <tr>                           
                            <td><?php echo $key->tglpo; ?></td>                     
                            <td><?php echo $key->nopo; ?></td>
                            <td><?php echo $key->branch_name; ?></td>
                            <td><?php echo $key->nama_comp; ?></td>
                            <td><?php echo $key->company; ?></td>
                            <td><?php echo $key->tipe; ?></td>
                            <td><?php echo number_format($key->u); ?></td>
                            <td><?php echo number_format($key->v); ?></td>                                                
                            <td>
                                <a href="<?php echo base_url()."asn/input_asn/".$key->id ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-humburger">Input</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot><tr>    
                            <th>Tanggal PO</th>                                           
                            <th>No PO</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Company</th>
                            <th>Tipe</th>
                            <th>Total Unit</th>
                            <th>Total Value</th>
                            <th></th>                                       
                        </tr>
                    </tfoot>                    
                </table>
            </div>
        </div> 
    </div>
</div>