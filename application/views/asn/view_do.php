<div>
    <a href="<?php echo base_url()."asn/list_asn/"; ?>"class="btn btn-dark" role="button">
                kembali</a>
</div>
<br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>    
                            <th>Tanggal PO</th>                                           
                            <th>No PO</th>
                            <th>No ASN</th>
                            <th>Branch</th>
                            <th>SubBranch</th>
                            <th>Company</th>
                            <th>Tipe</th>
                            <th>Kodeprod</th>
                            <th>Namaprod</th>
                            <th>Total Unit</th>
                            <th></th>                                       
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_po as $key) : ?>
                        <tr>                           
                            <td><?php echo $key->tglpo; ?></td>                                        
                            <td><?php echo $key->nopo; ?></td>                                        
                            <td><?php echo $key->no_asn; ?></td>
                            <td><?php echo $key->branch_name; ?></td>
                            <td><?php echo $key->nama_comp; ?></td>
                            <td><?php echo $key->company; ?></td>
                            <td><?php echo $key->tipe; ?></td>
                            <td><?php echo $key->kodeprod; ?></td>
                            <td><?php echo $key->namaprod; ?></td>
                            <td><?php echo $key->banyak; ?></td>
                            <td>
                            <a href="<?php echo base_url()."asn/tambah_do/$key->id/$key->kodeprod" ?>" class="btn btn-primary" role="button"><span class="glyphicon glyphicon-humburger">Input</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot><tr>    
                                <th>Tanggal PO</th>                                           
                                <th>No PO</th>                                           
                                <th>No ASN</th>
                                <th>Branch</th>
                                <th>SubBranch</th>
                                <th>Company</th>
                                <th>Tipe</th>
                                <th>Kodeprod</th>
                                <th>Namaprod</th>
                                <th>Total Unit</th>
                                <th></th>                                   
                        </tr>
                    </tfoot>                    
                </table>
            </div>
        </div> 
    </div>
</div>