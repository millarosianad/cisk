<style>
    th{
        font-size: 12px;
    }
    td{
        font-size: 12px;
    }
</style>

<?php
$required = "required";
?>



<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">

                                    <div class="mb-4">
                                        <a href="<?= base_url(); ?>mt/import_order" class="btn btn-dark btn-sm">Kembali</a>
                                    </div>     

                                    <hr>

                                    <?php 
                                    // var_dump($get_log_header);
                                    ?>
                                    
                                    <div class="row">
                                        <div class="col-md-3">Branch | SubBranch | SiteCode</div>
                                        <div class="col-md-5">: <?= $get_log_header->branch_name." | ".$get_log_header->nama_comp." | ".$get_log_header->site_code; ?></div>
                                        <div class="col-md-4"><i>Signature : <?= $get_log_header->signature; ?></i></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">Partner</div>
                                        <div class="col-md-9">: <?= $get_log_header->partner; ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">File Original</div>
                                        <div class="col-md-9">: 
                                            <?= anchor(base_url().'assets/uploads/import_mt/'.$get_log_header->filename_csv,$get_log_header->filename_csv); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">File Pdf</div>
                                        <div class="col-md-9">: 
                                            <?= anchor(base_url().'assets/uploads/import_mt/'.$get_log_header->filename_pdf,$get_log_header->filename_pdf); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">Created by</div>
                                        <div class="col-md-9">: <?= $get_log_header->username." at ".$get_log_header->created_at;  ?></div>
                                    </div>

                                    
                                    <hr>

<pre>
tabel status
status | nama_status | deskripsi  | data_yang_dikirim oleh SDS ke WEB
1   | transfered  | sds sukses get data dari api dan insert ke temporary    | SPOxxx / kode_dokumen
2   | posting | sds posting  | SNRxxx
3   | printed | sds print   | NULL
4   | do | sds rekap do | SPBxxx
5   | delivery | sds cetak sj  | SJLxxx
6   | terkirim  | ekspedisi update status 'terkirim' di sds | tgl_kirim dari t_ekspedisi
7   | batal | toko menolak | alasan      
8   | pending | pending pengiriman | alasan
</pre>
<hr>

<table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
    <thead>
        <tr>
            <!-- <th width="1">Branch</th> -->
            <!-- <th width="1%">Sub Branch</th> -->
            <!-- <th width="1%">Signature</th> -->
            <th width="1%">Created At</th>
            <th width="1%">Status Number</th>
            <th>Status</th>
            <th>Dokumen</th>
            <th>Alasan</th>
            <th>TanggalTerima</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($get_log as $a) : ?>
        <tr>
            <!-- <td><?= $a->branch_name; ?></td> -->
            <!-- <td><?= $a->nama_comp; ?></td> -->
            <!-- <td><?= $a->signature; ?></td> -->
            <td><?= $a->created_at; ?></td>
            <td><?= $a->status; ?></td>
            <td>
                <?php
                    if ($a->status == 1) {
                        echo "transfered";
                    }elseif($a->status == 2){
                        echo "posting";
                    }elseif($a->status == 3){
                        echo "printed";
                    }elseif($a->status == 4){
                        echo "DO";
                    }elseif($a->status == 5){
                        echo "delivery";
                    }elseif($a->status == 6){
                        echo "terkirim";
                    }elseif($a->status == 7){
                        echo "batal";
                    }elseif($a->status == 8){
                        echo "pending";
                    }else{
                        echo $a->status;
                    }       
                ?>
            </td>
            <td><?= $a->dokumen; ?></td>
            <td><?= $a->alasan; ?></td>
            <td><?= $a->tanggal_terima; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

                                </div>

                                <br>
                            </div>
                        </div>
