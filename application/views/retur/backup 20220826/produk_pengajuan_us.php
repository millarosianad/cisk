<a href="<?php echo base_url() . "retur/pengajuan"; ?>" class="btn btn-dark" role="button">kembali</a>
<a href="<?php echo base_url() . "retur/export_pengajuan/" . $this->uri->segment('3'). "/" .$this->uri->segment('4'); ?>" class="btn btn-warning" role="button">export(.csv)</a>

<?php
foreach ($get_status_pengajuan as $key) {
    $status = $key->status;
    // echo "status : ".$status;
}
// proses mpm, pending principal, proses kirim barang
if ($status == '2' || $status == '4' || $status == '5') { ?> 
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#import" disabled>
        Import
    </button>
    <button class="btn btn-default btn-default" data-toggle="modal" data-target="#pricelist" disabled><i class="fa fa-plus"></i>Tambah Produk</button>
<?php
} else {
?>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#import">
        Import
    </button>
    <button class="btn btn-primary btn-primary" data-toggle="modal" data-target="#pricelist"><i class="fa fa-plus"></i>Tambah Produk</button>
<?php
}
?>


<?php
echo anchor(
    base_url() . "retur/email_pengajuan/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
    'infokan ke mpm',
    array(
        'class' => 'btn btn-success',
        'onclick' => 'return confirm(\'Anda yakin sudah menginput produk dengan benar ? kami akan mengirim data anda ke tim terkait. \')'
    )
);
?>

<?php

if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
    echo anchor(
        base_url() . "retur/revisi/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
        'infokan ke dp',
        array(
            'class' => 'btn btn-primary',
        )
    );

    echo anchor(
        base_url() . "retur/email_principal/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
        'infokan ke principal',
        array(
            'class' => 'btn btn-danger',
            'onclick' => 'return confirm(\'Anda yakin akan mengirim data ini ke principal ? \')'
        )
    );
}


?>

<?php
// $this->load->view('master_product/modal_pricelist'); 
$this->load->view('retur/modal_tambah_produk_us');
$this->load->view('retur/modal_edit_produk_pengajuan');
$this->load->view('master_product/modal_pricelist_by_id');
$this->load->view('retur/import_product');
?>

<br><br>
<?php echo form_open($url); ?>

<div class="card table-card">
    <div class="card-header">
        <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <th width="2"><font size="2px">Status</th>
                            <th width="2"><font size="2px">Kodeprod</th>
                            <th width="2"><font size="2px">Namaprod</th>
                            <th width="2"><font size="2px">BatchNumber</th>
                            <th width="2"><font size="2px">ExpiredDate</th>
                            <th width="2"><font size="2px">Jumlah</th>
                            <th width="2"><font size="2px">Satuan</th>
                            <th width="2"><font size="2px">Alasan Retur</th>
                            <th width="2"><font size="2px">Nama Outlet</th>
                            <th width="2"><font size="2px">Keterangan</th>
                            <th width="2">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_produk_pengajuan as $a) : ?>
                            <tr>
                                <td>
                                    <center>
                                        <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                    </center>
                                </td> 
                                <td>
                                    <font size="2px"><i>
                                            <?php
                                            if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
                                            ?>
                                                <a href="#" onclick="get_produk_retur_by_id('<?= $a->id ?>')"><?php echo $a->nama_status; ?></a>
                                            <?php } else {
                                                echo $a->nama_status;
                                            } ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->kodeprod; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->namaprod; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->batch_number; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->expired_date; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->jumlah; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->satuan; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->alasan; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->nama_outlet; ?>
                                </td>
                                <td>
                                    <font size="2px"><?php echo $a->keterangan; ?>
                                </td>

                                <td>
                                    <font size="1px">
                                        <?php

                                        if ($a->status == 4 || $a->status == 1) {
                                            echo anchor(
                                                'retur/hapus_produk_pengajuan_retur/' . $a->id . '/' . $a->signature . '/' . $this->uri->segment('4'),
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


<?php
    if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
?>


<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <div class="form-group row">
                <label class="col-sm-3">Status Approval</label>
                <div class="col-sm-4">
                    <select name="status_approval" class="form-control" id="" required>
                        <option value="">-- Pilih --</option>
                        <option value="3">Data sesuai (verified)</option>
                        <option value="4">Data tidak sesuai (not verified)</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3">Deskripsi</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="deskripsi" id="deskripsi" required />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-4">
                    <!-- <button type="submit" class="btn btn-primary" name="status_approve" value="1" onclick="return confirm('Are you sure you want approve?');">Approve</button> -->
                    <?php
                        if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
                        ?>

                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                    
                    <?php echo form_close(); ?>

                    <?php } else {
                                                // echo $a->nama_status;
                                            } ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php }else{ } ?>

