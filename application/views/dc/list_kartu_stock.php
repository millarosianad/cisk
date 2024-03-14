<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
        <a href="<?php echo base_url() . "dc/list_po/"; ?>" class="btn btn-md btn-dark" role="button">kembali</a>
        <hr>
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>     
                        <tr>   
                            <th><font size='2px'>Principal</font></th>                                           
                            <th><font size='2px'>Barang Masuk</font></th>                                           
                            <th><font size='2px'>Barang Keluar</font></th>            
                            <th><font size='2px'>Stock On Hand</font></th>            
                            <th><font size='2px'><center>#</font></th>            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($get_kartu_stock as $key) : ?>
                        <tr>    
                            <td><font size='2px'><?php echo $key->namasupp; ?></td>    
                            <td><font size='2px'><?php echo $key->kode_kartu_masuk; ?></td>    
                            <td><font size='2px'><?php echo $key->kode_kartu_keluar; ?></td>    
                            <td><font size='2px'><?php echo $key->total_unit; ?></td>  
                            <td>

                                <a href="<?php echo base_url()."dc/generate_pdf_picklist/".$key->signature."/".$key->supp."/".$key->nodo ?>" class="btn btn-primary btn-sm" role="button" target="blank">picklist</a>

                                <a href="<?php echo base_url()."dc/confirm/".$key->signature."/".$key->supp."/".$key->nodo ?>" class="btn btn-success btn-sm" role="button" target="blank" data-toggle="modal" data-target="#setting_product">confirm picklist</a>

                            </td>
                               
                        </tr>
                    <?php endforeach; ?>
                    </tbody>                 
                </table>
            </div>
        </div> 
    </div>
</div>

<?php 
    // $this->load->view('mt/modal_import_order');
    $this->load->view('dc/modal_confirm_picklist');
    // $this->load->view('mt/modal_mapping_outlet');
?>