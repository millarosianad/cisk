<?php
foreach ($get_status_pengajuan as $key) {
    $status = $key->status;
    $nama_status = $key->nama_status;
    $nama_comp = $key->nama_comp;
    $no_pengajuan = $key->no_pengajuan;
}
?>
<pre>
No. Pengajuan : <?= $no_pengajuan; ?><br>
Sub Branch : <?= $nama_comp; ?><br>
Status Pengajuan Saat Ini : <?= $nama_status."(".$status.")"; ?>
</pre><hr>

<a href="<?php echo base_url() . "retur/pengajuan"; ?>" class="btn btn-dark" role="button">kembali</a>
<a href="<?php echo base_url() . "retur/export_pengajuan/" . $this->uri->segment('3'); ?>" class="btn btn-warning" role="button">export(.csv)</a>

<?php
if ($status == '1' || $status == '3') 
{ ?>
    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#import">Import</button>
    <button class="btn btn-default btn-default" data-toggle="modal" data-target="#pricelist"><i class="fa fa-plus"></i>Tambah Produk</button>

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
<?php }else{?>
    <?php
    if ($status == '2') 
    {
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
    ?>

<?php } ?>

<br><br>
<?php
$this->load->view('retur/modal_tambah_produk');
$this->load->view('retur/modal_edit_produk_pengajuan');
// $this->load->view('retur/modal_edit_produk_pengajuan_ceklist');
$this->load->view('master_product/modal_pricelist_by_id');
$this->load->view('retur/import_product');
?>

<?php echo form_open($url); ?>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <br><br><br><br><br><br><br>
                <table id="multi-colum-dt" class="table">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <th width="2"><font size="2px">Status</th>
                            <th width="2"><font size="2px">Kodeprod</th>
                            <th width="2"><font size="2px">Namaprod</th>
                            <th width="2"><font size="2px">BatchNumber</th>
                            <th width="2"><font size="2px">ExpiredDate</th>
                            <th width="2"><font size="2px">Jumlah</th>
                            <th width="2"><font size="2px">Alasan</th>
                            <th width="2">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // var_dump($get_produk_pengajuan);
                        // foreach ($get_produk_pengajuan as $a) :
                            foreach ($get_produk_pengajuan as $key) { ?>
                                # code...
                            <?php } ?>
                            
                        
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>



