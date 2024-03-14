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

    .batas{
        border: 2px solid darkslategray;
        border-radius: 5px;
    }

    .batas2{
        border: 1px dotted darkslategray;
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
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><font color="black">Detail Program dan History Pengajuan Claim</font>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Status</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><font color="black"><?= ($nama_status) ? $nama_status : 'null -> ajukan claim terlebih dahulu' ?></font></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">No Ajuan Claim</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><font color="black"><?= ($nomor_ajuan) ? $nomor_ajuan : 'null -> anda belum mengajukan claim' ?></font></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr class="batas">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $kategori ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Principal</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $namasupp ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Periode</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $from.' s/d '.$to ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nama Program</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nama_program ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nomor Surat</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nomor_surat ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Detail Persyaratan</label>
                                </div>
                                <div class="col-md-7">
                                    <textarea name="" id="" cols="30" rows="5"  class="form-control"><?= $syarat ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Download File</label>
                                </div>
                                <div class="col-md-7">
                                    <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_jpg ?>" class="btn btn-sm btn-success" target="_blank" style="background-color: darkslategray">jpg</a>
                                    <a href="<?= base_url().'assets/uploads/management_claim/'.$upload_pdf ?>" class="btn btn-sm btn-info" target="_blank" style="background-color: darkslategray">pdf</a>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Deadline Pengiriman Data</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $duedate ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Created By</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $username ?></label>
                                </div>
                            </div>

                            <div class="row mt-4 mb-4">
                                <div class="col-md-12">
                                    <hr class = "batas">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-7 text-center">
                                    <label for="kategori" class="form-label"><i>Data Pengajuan</i></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Branch | Subbranch | Site Code</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nama_comp ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Nama Pengirim</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nama_pengirim ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Email Pengirim</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $email_pengirim ?></label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Upload File</label>
                                </div>
                                <div class="col-md-7">
                                    <?php 
                                    if ($kategori == 'bonus_barang') { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_excel ?>">
                                            <label for="kategori" class="form-control"><?= $ajuan_excel ?></label>
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?= base_url().'assets/uploads/management_claim/'.$ajuan_excel ?>">
                                            <label for="kategori" class="form-control"><?= $ajuan_excel ?></label>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mt-1">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Upload Zip</label>
                                </div>
                                <div class="col-md-7">
                                    <?php 

                                    if ($ajuan_zip) {
                                        if ($kategori == 'bonus_barang') { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/import/'.$ajuan_zip ?>">
                                                <label for="kategori" class="form-control"><?= $ajuan_zip ?></label>
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$ajuan_zip ?>">
                                                <label for="kategori" class="form-control"><?= $ajuan_zip ?></label>
                                            </a>
                                        <?php }

                                    }else{ ?>
                                        <label for="site_code" class="form-control">DP tidak melampirkan file zip</label>
                                    <?php
                                    } ?>
                                </div>
                            </div>

                            <?php 
                                if ($kategori == 'bonus_barang') { ?>
                                    <div class="row mt-4">
                                        <div class="col-md-3">
                                            <label for="site_code" class="form-label">Download Data Raw</label>
                                        </div>
                                        <div class="col-md-7">
                                            <a href="<?= base_url().'management_claim/download_raw/'.$signature_ajuan ?>" class="btn btn-dark btn-sm rounded" target="_blank">Download Raw</a>
                                        </div>
                                    </div>
                                <?php } 
                            ?>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Created At</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $created_at ?></label>
                                </div>
                            </div>

                            <div class="row mt-4 mb-4">
                                <div class="col-md-12">
                                    <hr class = "batas">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-7 text-center">
                                    <label for="kategori" class="form-label"><i>Data Verifikasi MPM</i></label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Keterangan</label>
                                </div>
                                <div class="col-md-7">
                                    <textarea name="" id="" cols="30" rows="3" class="form-control"><?= $verifikasi_keterangan ?></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">File</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $verifikasi_file ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Verifikasi By</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $verifikasi_username ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="site_code" class="form-label">Verifikasi At</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $verifikasi_created_at ?></label>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>