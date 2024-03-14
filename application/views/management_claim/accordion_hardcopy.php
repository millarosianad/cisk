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
    <div class="row">
        <div class="accordion" id="accordionExampleHardcopy">
            <div class="card">
                <div class="card-header" style="background-color: #fff;"  id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOneHardcopy" aria-expanded="true" aria-controls="collapseOneHardcopy"><font color="black">Detail Status Hardcopy</font>
                        </button>
                    </h5>
                </div>

                <div id="collapseOneHardcopy" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExampleHardcopy" style="width:100%; overflow:hidden;">
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Status Hardcopy</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><font color="black"><?= ($nama_status_hardcopy) ? $nama_status_hardcopy : 'null -> belum mengirimkan hardcopy' ?></font></label>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">File Hardcopy</label>
                                </div>
                                <div class="col-md-7">
                                    <?php 
                                        if ($file_hardcopy) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$file_hardcopy ?>">
                                                <label for="kategori" class="form-control"><?= $file_hardcopy ?></label>
                                            </a>
                                        <?php
                                        }else{ ?>
                                            <label for="kategori" class="form-control"><?= $file_hardcopy ?></label>
                                        <?php
                                    } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nomor Resi</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nomor_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Tanggal Kirim Hardcopy</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $tanggal_kirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Nama Pengirim Hardcopy</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $nama_pengirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Email Pengirim Hardcopy</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $email_pengirim_hardcopy ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Last Update at</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $update_kirim_hardcopy_at ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Tanggal Terima di MPM</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $tanggal_terima_hardcopy ?></label>
                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Terima Hardcopy by</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $terima_hardcopy_by ?></label>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Last Updated Terima Hardcopy at</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $update_terima_hardcopy_at ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">File Tanda Terima ke Principal</label>
                                </div>
                                <div class="col-md-7">

                                    <?php 
                                        if ($file_tanda_terima_hardcopy_ke_principal) { ?>
                                            <a href="<?= base_url().'assets/uploads/management_claim/'.$file_tanda_terima_hardcopy_ke_principal ?>">
                                                <label for="kategori" class="form-control"><?= $file_tanda_terima_hardcopy_ke_principal ?></label>
                                            </a>
                                        <?php
                                        }else{ ?>
                                            <label for="kategori" class="form-control"><?= $file_tanda_terima_hardcopy_ke_principal ?></label>
                                        <?php
                                    } ?>

                                </div>
                            </div>

                            <!-- <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Tanda Terima ke Principal by</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $tanda_terima_hardcopy_ke_principal_by ?></label>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Penerima Hardopy (Principal)</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $tanda_terima_hardcopy_ke_principal_nama ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Tanggal Terima Hardcopy (Principal)</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $tanggal_tanda_terima_hardcopy_ke_principal ?></label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <label for="kategori" class="form-label">Last Update Hardcopy di Principal</label>
                                </div>
                                <div class="col-md-7">
                                    <label for="kategori" class="form-control"><?= $update_tanda_terima_hardcopy_ke_principal ?></label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>