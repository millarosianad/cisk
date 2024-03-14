<?php 
    if ($get_discount) {
        foreach ($get_discount as $key) {
            $diskon = $key->diskon;
            $ppn = $key->ppn;
        }
    }else{
        $diskon = 0;
        $ppn = 0;
    }   
?>

<a href="<?php echo base_url()."retur/pengajuan"; ?>"class="btn btn-dark" role="button">kembali</a>
<a href="<?php echo base_url()."retur/export_pengajuan/".$this->uri->segment('3')."/".$this->uri->segment('4'); ?>"class="btn btn-warning" role="button">export(.csv)</a>

<?php 
    foreach ($get_status_pengajuan as $key) {
        $status = $key->status;
        // echo "status : ".$status;
    }
    if ($status == '2' || $status == '4' || $status == '5') { ?>
        <button class="btn btn-default btn-default" data-toggle="modal" data-target="#pricelist" disabled><i class="fa fa-plus"></i>Tambah Produk</button>
    <?php
    }else{ 
        ?>
        <button class="btn btn-primary btn-primary" data-toggle="modal" data-target="#pricelist"><i class="fa fa-plus"></i>Tambah Produk</button>
    <?php
    }
?>


<?php

    echo anchor(base_url()."retur/email_pengajuan/".$this->uri->segment('3')."/".$this->uri->segment('4'),
    'infokan ke mpm',
    array(
        'class' => 'btn btn-success',
        'onclick' => 'return confirm(\'Anda yakin sudah menginput produk dengan benar ? kami akan mengirim data anda ke tim terkait. \')'
    )
    );

    echo ' ';

    echo anchor(base_url()."retur/generate_pdf/".$this->uri->segment('4')."/".$this->uri->segment('3'),
    'generate pdf',array(
        'class' => 'btn btn-dark',
        
    )
    );
?>

<?php

    if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
        echo anchor(base_url()."retur/revisi/".$this->uri->segment('3')."/".$this->uri->segment('4'),
        'infokan ke dp',
        array(
            'class' => 'btn btn-primary',
        )
        );

        echo ' ';

        echo anchor(base_url()."retur/email_principal/".$this->uri->segment('3')."/".$this->uri->segment('4'),
        'infokan ke principal',
        array(
            'class' => 'btn btn-danger',
            'onclick' => 'return confirm(\'Anda yakin akan mengirim data ini ke principal ? \')'
        )
        );

    }

    
?>

<?php 
    $this->load->view('retur/modal_tambah_produk_intrafood');   
?>

<?php $this->load->view('retur/modal_edit_produk_pengajuan_intrafood'); ?>

<?php $this->load->view('master_product/modal_pricelist_by_id'); ?>



<br>
<br>
<div class="card table-card">
    <div class="card-header">
        <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="2"><font size="2px">Status</th>
                            <th width="2"><font size="2px">Kodeprod</th>
                            <th width="2"><font size="2px">Namaprod</th>
                            <th width="2"><font size="2px">Karton</th>
                            <th width="2"><font size="2px">Dos/Pack</th>
                            <th width="2"><font size="2px">Pcs</th>
                            <th width="2"><font size="2px">Karton (harga)</th>
                            <th width="2"><font size="2px">Dos/Pack (harga)</th>
                            <th width="2"><font size="2px">Pcs (harga)</th>
                            <th width="2"><font size="2px">Value</th>
                            <th width="2"><font size="2px">KodeProduksi</th>
                            <th width="2"><font size="2px">Keterangan</th>
                            <th width="2">hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_produk_pengajuan as $a) : 
                            $status = $a->status;?>
                            <tr>
                            <td><font size="2px"><i>
                                <?php
                                if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
                                ?>
                                    <a href="#" onclick="get_produk_retur_by_id('<?= $a->id ?>')"><?php echo $a->nama_status;?></a>
                                <?php }else{
                                    echo $a->nama_status;
                                 } ?> 
                                </td>
                                <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                                <td><font size="2px"><?php echo $a->namaprod; ?></td>
                                <td><font size="2px"><?php echo $a->total_karton; ?></td>
                                <td><font size="2px"><?php echo $a->total_dus; ?></td>
                                <td><font size="2px"><?php echo $a->total_pcs; ?></td>
                                <td><font size="2px"><?php echo $a->harga_karton; ?></td>
                                <td><font size="2px"><?php echo $a->harga_dus; ?></td>
                                <td><font size="2px"><?php echo $a->harga_pcs; ?></td>
                                <td><font size="2px"><?php echo $a->value; ?></td>
                                <td><font size="2px"><?php echo $a->kode_produksi; ?></td>
                                <td><font size="2px"><?php echo $a->keterangan; ?></td>
                                
                                <td>
                                <font size="1px">
                                    <?php

                                    if($a->status == 4 || $a->status == 1){
                                        echo anchor(
                                            'retur/hapus_produk_pengajuan_retur/'.$a->id.'/'.$a->signature.'/'.$this->uri->segment('4'),
                                            ' ',
                                            array(
                                                'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                                'onclick' => 'return confirm(\'Hapus produk ini ?\')'
                                            )
                                            );
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
<?php echo form_open_multipart($url); ?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <div class="form-group row">
                <label class="col-sm-3">Diskon</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="text" name="diskon" value="<?= $diskon ?>" required />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">PPN (% contoh = 10 artinya sistem menganggap 10%)</label>
                <div class="col-sm-4">
                    <input id="" class="form-control" type="text" name="ppn" value="<?= $ppn ?>" required />
                    <input id="" class="form-control" type="hidden" name="signature" value="<?php echo $this->uri->segment('3'); ?>"  required />
                    <input id="" class="form-control" type="hidden" name="supp" value="<?php echo $this->uri->segment('4'); ?>"  required />
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-4">

                <?php
                if($status == 4 || $status == 1){ 
                    echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required');
                    echo form_close(); 
                }?>

                </div>
            </div>

        </div>
    </div>
</div>

