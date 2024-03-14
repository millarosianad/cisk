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

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample" style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-2">
                                    No Pengajuan
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $no_pengajuan ?>" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Principal
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $namasupp ?>" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Branch - SubBranch
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $branch_name.' - '.$nama_comp ?>" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    PIC 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $nama ?>" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Tanggal Pengajuan 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $tanggal_pengajuan ?>" class="form-control">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    File Lampiran Pengajuan 
                                </div>
                                <div class="col-md-3">
                                    <?php 
                                        if ($file) { ?>
                                            <a href=""><?= $file ?></a>
                                        <?php
                                        }else{ ?>
                                            <i>user tidak melampirkan file</i>
                                        <?php
                                        }
                                    ?>                                    
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-5">
                                <div class="col-md-2">
                                    Verifikasi Principal Area 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $principal_area_at ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Nama
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $principal_area_username ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Verifikasi MPM 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $verifikasi_at ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Nama
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $verifikasi_username ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Verifikasi Principal HO 
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $principal_ho_at ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Nama
                                </div>
                                <div class="col-md-3">
                                    <input type="text" value="<?= $principal_ho_username ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        

                        <hr>

                        <div class="container">
                            <div class="row mt-5">
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