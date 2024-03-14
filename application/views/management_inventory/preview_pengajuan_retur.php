<div class="container">
    <div class="row mt-5">
        <div class="col-md-3">
            No Pengajuan
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $no_pengajuan ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            Principal
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $namasupp ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            Branch - SubBranch
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $branch_name.' - '.$nama_comp ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            PIC
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $nama ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            Tanggal Pengajuan
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $tanggal_pengajuan ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            File Lampiran Pengajuan
        </div>
        <div class="col-md-4">
            <?php 
                if ($file) { ?>
                    <!-- <a href=""><?= $file ?></a> -->
                    <a href="#"><label class="form-control"><i><?= $file ?></i></label></a>
                <?php
                }else{ ?>
                    
                    <label class="form-control"><i>user tidak melampirkan file</i></label>
                <?php
                }
            ?>   
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            Verifikasi Principal Area
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
            Verifikasi MPM
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $verifikasi_at ?></label>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            Verifikasi Principal HO
        </div>
        <div class="col-md-4">
            <label class="form-control"><?= $principal_ho_at ?></label>
        </div>
    </div>
</div>





































<div class="container">
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="detail" class="display" style="display: inline-block; overflow-y: scroll">
                <thead>
                    <tr>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Verifikasi</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Deskripsi</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Kodeprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Namaprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Batch</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">ED</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Jumlah</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Satuan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nama Outlet</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Alasan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Ket</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan_detail->result() as $a) : ?>
                    <tr>
                        <td>
                            <?php 
                                if($a->status == 4) { ?>
                                    <p style="color: white; background-color: red;"><?= $a->nama_status ?></p>
                                <?php
                                }elseif($a->status == null){ ?>
                                    <p><i>pending verifikasi</i></p>
                                <?php
                                }elseif($a->status == 3){ ?>
                                    <p style="color: white; background-color: green;"><?= $a->nama_status ?></p>
                                <?php
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if ($a->status == 4) { ?>
                                    <p style="color: white; background-color: red;">
                                        <?= $a->deskripsi ?>
                                </p>
                                <?php
                                }elseif($a->status == null){ ?>
                                    <p><i>pending verifikasi</i></p>
                                <?php
                                }elseif($a->status == 3){ ?>
                                    <p style="color: white; background-color: green;"><?= $a->deskripsi ?></p>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->kodeprod ?></td>
                        <td><?= $a->namaprod ?></td>
                        <td><?= $a->batch_number ?></td>
                        <td><?= $a->expired_date ?></td>
                        <td><?= $a->jumlah ?></td>
                        <td><?= $a->satuan ?></td>
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

    <hr>
    

<script>
      $(document).ready(function () {
        $("#detail").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });

        $("#example").DataTable({
            "pageLength": 100,
            // "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
      });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/kodeprod') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_kodeprod) {
            $("select[name = kodeprod]").html(hasil_kodeprod);
        }
    });

</script>