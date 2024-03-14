<!-- detail -->
<div class="col-12">
    <div class="row">
        <div class="col-12">
            <a href="<?= base_url().'transaction/list_order' ?>" class="btn btn-dark btn-sm">Kembali</a>
            <br><br>
            <div class="row">
                <div class="col-3">
                    Supplier
                </div>
                <div class="col-8">
                    : <?= $order_detail->namasupp;?>
                    <br>
                </div>
                <div class="col-3">
                    Branch Name / Company
                </div>
                <div class="col-8">
                    : <?= $order_detail->company;?>
                    <br>
                </div>
                <div class="col-3">
                    Alamat Kirim
                </div>
                <div class="col-8">
                    : <?= $order_detail->alamat;?>
                    <br>
                </div>
                <div class="col-3">
                    Nomor ID Order
                </div>
                <div class="col-8">
                    : <?= $order_detail->id;?>
                    <br>
                </div>
                <div class="col-3">
                    Tanggal Pesan
                    <br>
                </div>
                <div class="col-8">
                    : <?= $order_detail->tglpesan;?>
                    <br>
                </div>
                <div class="col-3">
                    Tgl - Nomor PO
                </div>
                <div class="col-8">
                    : <?= $order_detail->tglpo." - ".$order_detail->nopo;?>
                    <br>
                </div>
                
                <div class="col-3">
                    Tipe
                </div>
                <div class="col-8">
                    : <?php 
                    if($order_detail->tipe == 'S'){
                        echo "SPK";
                    }elseif($order_detail->tipe == 'A'){
                        echo "Alokasi";
                    }elseif($order_detail->tipe == 'R'){
                        echo "Replineshment";
                    }                 
                ?>
                    <br>
                </div>
                <div class="col-3">
                    Status Order
                </div>
                <div class="col-8">
                    : <?php 
                    if($order_detail->status == '1'){
                        echo "Proses (Admin)";
                    }elseif($order_detail->status == '2'){
                        echo "Proses (Finance)";
                    }else{
                        echo "Pending";
                    }                 
                ?>
                    <br>
                </div>
                <div class="col-3">
                    Status Approval
                </div>
                <div class="col-8">
                    : <?php 
                    if($order_detail->status_approval == '1'){
                        echo "Approved";
                    }else{
                        echo " Tidak ada ";
                    }                 
                ?>
                    <br>
                </div>
                <div class="col-3">
                    Alasan Approval
                </div>
                <div class="col-8">
                    : <?php 
                        if($order_detail->alasan_approval == 'null' || $order_detail->alasan_approval == ''){
                            echo "Tidak ada";
                        }else{
                            echo $order_detail->alasan_approval; 
                        }            
                    ?>
                </div>
            </div>
        </div>

        <div class="col-6 nopo">
            <?= form_open($url_po);?>
            <div class="row">
                <div class="col-4">
                    No. PO
                </div>
                <div class="col-8">
                    <?php 
                    if ($order_detail->bulan == '1') {
                        $bulan_po = 'I';
                    }elseif ($order_detail->bulan == '2') {
                        $bulan_po = 'II';
                    }elseif ($order_detail->bulan == '3') {
                        $bulan_po = 'III';
                    }elseif ($order_detail->bulan == '4') {
                        $bulan_po = 'IV';
                    }elseif ($order_detail->bulan == '5') {
                        $bulan_po = 'V';
                    }elseif ($order_detail->bulan == '6') {
                        $bulan_po = 'VI';
                    }elseif ($order_detail->bulan == '7') {
                        $bulan_po = 'VII';
                    }elseif ($order_detail->bulan == '8') {
                        $bulan_po = 'VIII';
                    }elseif ($order_detail->bulan == '9') {
                        $bulan_po = 'IX';
                    }elseif ($order_detail->bulan == '10') {
                        $bulan_po = 'X';
                    }elseif ($order_detail->bulan == '11') {
                        $bulan_po = 'XI';
                    }elseif ($order_detail->bulan == '12') {
                        $bulan_po = 'XII';
                    }
                    $isi_nopo = $this->uri->segment('5');
                    // var_dump($isi_nopo);die;
                    
                    if ($isi_nopo != '') {
                        $isi_nopo_x = $isi_nopo."/MPM/".$bulan_po."/".$order_detail->tahun; 
                    }else{
                        if ($order_detail->nopo != '') {
                            $isi_nopo_x = $order_detail->nopo;
                        }else{
                            $isi_nopo_x = '';
                        }
                        
                    }
                ?>
                    <input type="text" class="form-control" name="nopo" placeholder="nomor po"
                        value="<?= $isi_nopo_x ?>" required readonly>
                    <br>
                </div>
                <div class="col-4">
                    Alamat Kirim
                </div>
                <div class="col-8">
                    <textarea class="form-control" rows="6" name="alamat_kirim" required
                        readonly><?= $order_detail->alamat ?></textarea>
                    <br>
                </div>
                <div class="col-4">
                    Alamat Kirim (Override)
                </div>
                <div class="col-8">
                    <textarea class="form-control" rows="6" name="alamat_override" required
                        readonly><?= $order_detail->alamat_kirim ?></textarea>
                    <br>
                </div>
                <div class="col-4">
                    Note
                </div>
                <div class="col-8">
                    <textarea class="form-control" name="note"><?= $order_detail->note ?></textarea>
                    <br>
                </div>
                <div class="col-4">
                    PO Ref
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="po_ref" placeholder="po ref"
                        value="<?= $order_detail->po_ref ?>">
                    <br>
                </div>
                <div class="col-4">
                </div>
                <div class="col-8">
                    <a href="<?= base_url()."transaction/override_alamat/".$order_detail->id."/".$order_detail->supp."/".$isi_nopo  ?> "
                        class="btn btn-success btn-sm" role="button">Override alamat</a>
                    <input type="hidden" class="form-control" name="id_po" value="<?= $order_detail->id; ?>">
                    <?= form_submit('submit','Submit PO','class="btn btn-primary btn-sm"')?>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <br>

    <!-- generate-->
    <a href="<?= base_url()."transaction/generate_new/".$order_detail->id ?> " class="btn btn-primary btn-sm btn-nopo"
        role="button">Generate No PO</a>
    <hr>

    <!-- analisa doi, button button -->
    <div>
        <?php 
        if ($order_detail->lock == '1' && $order_detail->nopo != null) { ?>
        <!-- <a href="<?= base_url()."transaction/proses_doi/".$order_detail->id."/".$order_detail->tahun."/".$order_detail->bulan."/".$order_detail->tglpesan."/".$order_detail->kode."/".$order_detail->supp ?> "
            class="btn btn-warning btn-sm disabled" role="button">Analisa DOI</a> -->
        <a href="" class="btn btn-success btn-sm disabled" role="button">Kirim ke Finance</a>
        <a href="<?= base_url()."transaction/unlock_finance/".$order_detail->id."/".$order_detail->supp; ?>"
            class="btn btn-danger btn-sm disabled" role="button">Revisi</a>
        <?php
                }else{ ?>
        <!-- <a href="<?= base_url()."transaction/proses_doi/".$order_detail->id."/".$order_detail->tahun."/".$order_detail->bulan."/".$order_detail->tglpesan."/".$order_detail->kode."/".$order_detail->supp."/".$order_detail->userid ?> "
            class="btn btn-warning btn-sm" role="button">Analisa DOI</a> -->
        <a href="" class="btn btn-success btn-sm" role="button">Kirim ke Finance</a>
        <a href="<?= base_url()."transaction/unlock_finance/".$order_detail->id."/".$order_detail->supp; ?>" class="btn btn-danger btn-sm"
            role="button">Revisi</a>
        <?php
        }
    ?>
    </div>
    <br>


    <?= form_open($url_approval);?>
    <div class="row">
        <div class="col-8">
            <input type="hidden" name="userid" class="form-control" value="">
            <input type="text" name="alasan_approval" class="form-control"
                placeholder="masukkan alasan approval disini .. " required>
        </div>
        <div class="col-4">
            <input type="hidden" class="form-control" name="supp" value="<?= $order_detail->supp; ?>">
            <input type="hidden" class="form-control" name="id_po" value="<?= $order_detail->id; ?>">
            <?php
            if ($order_detail->lock == '1') { 
                echo form_submit('submit','Submit Approval dan Kirim ke Finance','class="btn btn-primary btn-sm" disabled');
            }else{
                echo form_submit('submit','Submit Approval dan Kirim ke Finance','class="btn btn-primary btn-sm"');
            }
        ?>
        </div>
    </div>
    <?= form_close(); ?>
    <hr>

    <!-- produk -->
    <br>
    <div class="row list-product">
        <div class="col-6">
            <?= form_open($url);?>
            <?php 
            $x=array();
            foreach($list_product->result() as $value)
            {
                $x[$value->kodeprod]= $value->kodeprod.' - '.$value->namaprod;
            }

            echo form_dropdown('kodeprod', $x,'','class="form-control"');
        ?>
        </div>
        <div class="col-2">
            <input type="number" min='1' class="form-control" name="jumlah" placeholder="value in karton" required>
            <input type="hidden" class="form-control" name="id_po" value="<?= $order_detail->id; ?>">
            <input type="hidden" class="form-control" name="userid" value="<?= $order_detail->userid; ?>">
        </div>
        <div class="col-4">
            <?= form_submit('submit','Simpan','class="btn btn-primary btn-sm"')?>
            <?= form_close(); ?>
        </div>
    </div>
    <br>
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Kode Prc</th>
                    <th>Nama Produk</th>
                    <th>Unit</th>
                    <th>Stock Akhir</th>
                    <th>Git</th>
                    <th>Rata - Rata</th>
                    <th>DOI</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($order_produk as $produk) : ?>
                <tr>
                    <td><?= $produk->kodeprod; ?></font>
                        </center>
                    </td>
                    <td><?= $produk->kode_prc; ?></td>
                    <td><?= $produk->namaprod; ?></td>
                    <td><?= $produk->banyak; ?></td>
                    <td><?= $produk->stock_akhir; ?></td>
                    <td><?= $produk->git; ?></td>
                    <td>
                        <center><?= $produk->rata; ?>
                    </td>
                    <td><?= $produk->doi; ?></td>
                    <td>
                        <center>
                            <?php 
                                // echo "lock : ".$lock;
                                if ($order_detail->lock == '1') { ?>
                            <button type="button" class="btn btn-primary btn-sm" disabled>Locked</button>
                            <?php
                            }else{ 
                                echo anchor('transaction/delete_product/'.$order_detail->id.'/'.$order_detail->supp.'/'. $produk->id_kodeprod, '<i class="fa fa-times-circle-o fa-2x" style="color:red"></i>',array('onclick'=>'return confirm(\'Are you sure?\')'));
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    var nopo_url = '<?= $this->uri->segment('5');?>'
    var nopo = '<?= $order_detail->nopo;?>'
    var lock = '<?= $order_detail->lock ;?>'
    if (nopo == '') {
        $('.btn-nopo').show()
    } else {
        $('.btn-nopo').hide()   
    }
    
    if (nopo_url != '') {
        $('.nopo').show()
    } else {
        $('.nopo').hide()   
    }

    if (lock != '1') {
        $('.list-product').show()
    } else {
        $('.list-product').hide()
    }
</script>