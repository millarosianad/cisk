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

<?php
foreach ($get_status_pengajuan as $key) {
    $status = $key->status;
    $nama_status = $key->nama_status;
    $nama_comp = $key->nama_comp;
    $no_pengajuan = $key->no_pengajuan;
    $note = $key->note;
}
?>
<pre>
No. Pengajuan : <?= $no_pengajuan; ?><br>
Sub Branch : <?= $nama_comp; ?><br>
Status Pengajuan Saat Ini : <?= $nama_status."(".$status.")"; ?><br>
Note : <?= $note; ?>
</pre>
<hr>

<a href="<?= base_url() . "retur/pengajuan"; ?>" class="btn btn-dark" role="button">kembali</a>
<a href="<?= base_url() . "retur/export_pengajuan/" . $this->uri->segment('3'); ?>" class="btn btn-warning"
    role="button">export(.csv)</a>


<?php
if ($status == '1' || $status == '3') 
{ ?>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#import">Import</button>
<button class="btn btn-default btn-default" data-toggle="modal" data-target="#pricelist"><i
        class="fa fa-plus"></i>Tambah Produk</button>

<?php 
        echo anchor(
            base_url() . "retur/email_pengajuan/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
            'infokan ke mpm untuk verifikasi data',
            array(
                'class' => 'btn btn-success',
                'onclick' => 'return confirm(\'Anda yakin sudah menginput produk dengan benar ? kami akan mengirim data anda ke tim terkait. \')'
            )
        );
    ?>

<?php }else{
    if ($status == '2') {
            if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 515 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
                echo anchor(
                    base_url() . "retur/revisi/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
                    'infokan ke dp',
                    array(
                        'class' => 'btn btn-primary',
                    )
                );
                echo ' ';
                echo anchor(
                    base_url() . "retur/email_principal/" . $this->uri->segment('3') . "/" . $this->uri->segment('4'),
                    'infokan ke principal',
                    array(
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm(\'Anda yakin akan mengirim data ini ke principal ? \')'
                    )
                );
                echo ' ';
                echo anchor(
                    base_url() . "retur/generate_pdf/" . $this->uri->segment('4') . "/" . $this->uri->segment('3'),
                    'generate pdf',
                    array(
                        'class' => 'btn btn-dark',
                    )
                );
            }
        }
    }
?>

<?php if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 442 || $this->session->userdata('id') == 547 || $this->session->userdata('id') == 588) {
    echo '
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#note">
    Note
</button>
<br><br>
' ; } ?>

<?php $this->load->view('retur/modal_tambah_produk_intrafood'); ?>

<?= form_open_multipart('retur/approval_produk_pengajuan/'); ?>

<div class="card table-card">
    <div class="card-header">
        <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px">
                                <input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()">
                            </th>
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
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td>
                            <td><font size="2px"><?= $a->nama_status; ?></td>
                            <td><font size="2px"><?= $a->kodeprod; ?></td>
                            <td><font size="2px"><?= $a->namaprod; ?></td>
                            <td><font size="2px"><?= $a->total_karton; ?></td>
                            <td><font size="2px"><?= $a->total_dus; ?></td>
                            <td><font size="2px"><?= $a->total_pcs; ?></td>
                            <td><font size="2px"><?= $a->harga_karton; ?></td>
                            <td><font size="2px"><?= $a->harga_dus; ?></td>
                            <td><font size="2px"><?= $a->harga_pcs; ?></td>
                            <td><font size="2px"><?= $a->value; ?></td>
                            <td><font size="2px"><?= $a->kode_produksi; ?></td>
                            <td><font size="2px"><?= $a->keterangan; ?></td>
                            <td><font size="1px">
                                <?php
                                if($a->status == 4 || $a->status == 1){
                                echo anchor('retur/hapus_produk_pengajuan_retur/'.$a->id.'/'.$a->signature.'/'.$this->uri->segment('4'),' ', array(
                                        'class' => 'fa fa-times fa-2x', 'style' => 'color:red',
                                        'onclick' => 'return confirm(\'Hapus produk ini ?\')'
                                    ));
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

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <div class="form-group row">
                <label class="col-sm-3">Status Approval</label>                
                <div class="col-sm-4">
                    <select name="status_approval" class="form-control" id="" >
                        <option value="">-- Pilih --</option>
                        <option value="3">Data sesuai (verified)</option>
                        <option value="4">Data tidak sesuai (not verified)</option>
                    </select>
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-3">Deskripsi</label>
                <div class="col-sm-4">
                    <input class="form-control" type="text" name="deskripsi" id="deskripsi"  />
                </div>
                <input class="form-control" type="hidden" name="supp" value="<?php echo $this->uri->segment('4'); ?>"  />
                <input class="form-control" type="hidden" name="signature" value="<?php echo $signature; ?>"  />
            </div>

            <div class="form-group row">
                <label class="col-sm-3"></label>
                <div class="col-sm-4">

                <?php
                if ($this->session->userdata('id') == 297 || $this->session->userdata('id') == 588 || $this->session->userdata('id') == 442) {
                    echo form_submit('submit', 'Simpan', 'class="btn btn-success" ');
                    echo form_close(); 
                }
                
                ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php 
    
    $this->load->view('retur/modal_edit_produk_pengajuan_intrafood');
    $this->load->view('master_product/modal_pricelist_by_id');
    $this->load->view('retur/modal_note');
?>