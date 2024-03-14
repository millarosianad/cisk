<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    td{
        font-size: 13px;
    }
    th{
        font-size: 14px; 
    }

    .accordion {
        cursor: pointer;
        padding: 1px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.2s;
        /* border: 2px solid;
        border-radius: 25px; */
        border-top: 5px solid darkslategray;
        border-bottom: 5px solid darkslategray;
        border-left: 5px solid darkslategray;
        border-right: 5px solid darkslategray;
        border-radius: 14px;
        margin-top: 1rem;
        border-top: 1em solid darkslategray;

    }

</style>

</div>

<div class="container">

<div class="row mb-4">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>

</div>

<div class="container">
    <div class="row">
        <div class="accordion" id="accordionExample">
            <div class="card">
                <div class="card-header" style="background-color: #fff;"  id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><font color="black">Detail Pengajuan click here</font>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    No Pengajuan
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $no_pengajuan ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    Principal
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $namasupp ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    Branch - SubBranch
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $branch_name.' - '.$nama_comp ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    PIC DP
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $nama ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    Tanggal Pengajuan 
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($tanggal_pengajuan) { ?>                                            
                                            <label class="form-control"><?= $tanggal_pengajuan ?></label>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>retur belum diajukan</i></label>
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    File Lampiran Pengajuan 
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($file) { ?>
                                            <a href="<?= base_url().'assets/file/retur/'.$file ?>">
                                            <!-- <input type="text" value="<?= $file ?>" class="form-control"> -->
                                            <label class="form-control"><?= $file ?></label></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file</i></label>
                                        <?php
                                        }
                                    ?>                                    
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="container">
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    Verifikasi Principal Area at
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $principal_area_at ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nama
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $principal_area_username ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Verifikasi MPM at
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $verifikasi_at ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nama
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $verifikasi_username ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Verifikasi Principal HO at
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $principal_ho_at ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nama
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $principal_ho_username ?></label>
                                </div>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Status
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $nama_status ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Tanggal Kirim Barang
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $tanggal_kirim_barang ?></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nama Ekspedisi
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $nama_ekspedisi ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Estimasi Tiba
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $est_tanggal_tiba ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Resi Pengiriman
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($file_pengiriman) { ?>
                                            <a href="<?= base_url().'assets/file/retur/'.$file_pengiriman ?>">
                                            <!-- <input type="text" value="<?= $file_pengiriman ?>" class="form-control"> -->
                                            <label class="form-control"><?= $file_pengiriman ?></label></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_pengiriman</i></label>
                                        <?php
                                        }
                                    ?>     
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Last Update
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $proses_kirim_barang_at ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Tanggal Terima Barang
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $tanggal_terima_barang ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nama Penerima
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $nama_penerima ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Nomor Terima Barang (LPK)
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $no_terima_barang ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    File Terima Barang
                                </div>
                                <div class="col-md-4">
                                    <?php 
                                        if ($file_terima_barang) { ?>
                                            <a href="<?= base_url().'assets/file/retur/'.$file_terima_barang ?>">
                                            <!-- <input type="text" value="<?= $file_terima_barang ?>" class="form-control"> -->
                                            <label class="form-control"><?= $file_terima_barang ?></label></a>
                                        <?php
                                        }else{ ?>
                                            <label class="form-control"><i>user tidak melampirkan file_terima_barang</i></label>
                                        <?php
                                        }
                                    ?>     
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Last Update
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $terima_barang_at ?></label>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    Proses Kirim Barang By
                                </div>
                                <div class="col-md-4">
                                    <label class="form-control"><?= $username_kirim_barang ?></label>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="container">
                            <div class="row mt-2">
                                <a href="#" class="btn btn-info">export product xls</a>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <table id="example" class="display">
                                        <thead>
                                            <tr>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Kodeprod</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Namaprod</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Batch</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">ED</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Jumlah</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nama Outlet</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Alasan</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Ket</th>
                                                <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Qty Approval</th>
                                            </tr>
                                        </thead>
                                        <tbody>     
                                            <?php 
                                            foreach ($get_pengajuan_detail->result() as $a) : ?>
                                            <tr>                                          
                                                <td><?= $a->kodeprod ?></td>
                                                <td><?= $a->namaprod ?></td>
                                                <td><?= $a->batch_number ?></td>
                                                <td><?= $a->expired_date ?></td>
                                                <td><?= $a->jumlah ?></td>
                                                <td><?= $a->nama_outlet ?></td>
                                                <td><?= $a->alasan ?></td>
                                                <td><?= $a->keterangan ?></td>
                                                <td><?= $a->qty_approval ?></td>
                                            </tr>
                                            <?php endforeach; ?>   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                       

                        




                    </div>
                </div>
            </div>
        </div>
    </div>
</div>