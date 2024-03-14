<div class="container">
    <div class="row">
        <div class="accordion" id="accordionTwo">
            <div class="card">
                <div class="card-header" style="background-color: #fff;"  id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><font color="black">Tambah Product metode Direct</font>
                        </button>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionTwo" style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <?= form_open($url); ?>
                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    Product 
                                </div>
                                <div class="col-md-4">
                                    <select name="kodeprod" id="id_kodeprod" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="batch_number" class="form-label">batch number</label> 
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="batch_number" name="batch_number" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="satuan" class="form-label">Satuan</label> 
                                </div>
                                <div class="col-md-4">
                                    <select name="satuan" id="satuan" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="ed" class="form-label">Expired Date</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" id="ed" name="ed" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="nama_outlet" class="form-label">Nama Outlet</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="nama_outlet" name="nama_outlet" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="alasan_retur" class="form-label">alasan retur</label>
                                </div>
                                <div class="col-md-4">
                                    <select name="alasan_retur" id="alasan_retur" class="form-control" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                                </div>
                                <div class="col-md-4">
                                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="Gunakan kolom ini agar pengajuan retur anda CLEAR dan mudah dipahami"></textarea>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <input type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>">
                                    <input type="hidden" name="signature" value="<?= $signature ?>">
                                    <input type="hidden" name="supp" value="<?= $supp ?>">
                                </div>
                                <div class="col-md-4">
                                    <?php
                                        if (!$tanggal_pengajuan) { ?>                    
                                            <button type="submit" class="btn btn-info">Add Product</button>
                                        <?php 
                                        }else{ ?>
                                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>                
                                        <?php
                                        }
                                    ?>
                                    <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-dark">back</a>
                                </div>
                            </div>
                        </div>
                        <?= form_close();?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="accordion" id="accordionTwo">
            <div class="card">
                <div class="card-header" style="background-color: #fff;"  id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><font color="black">Tambah Product metode Import XLS</font> <a href="<?= base_url().'management_inventory/export_template_pengajuan_retur' ?>" class="btn btn-warning btn-sm rounded-pill">download template terlebih dahulu</a> 
                        </button>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionTwo" style="width:100%; overflow:hidden;">
                    <div class="card-body">

                        <?= form_open_multipart($url_import); ?>
                        <div class="container">

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label for="file_import" class="form-label">File Import</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control" name="file">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <input type="hidden" name="signature" value="<?= $signature ?>">
                                    <input type="hidden" name="supp" value="<?= $supp ?>">
                                </div>
                                <div class="col-md-4">
                                    <?php
                                        if (!$tanggal_pengajuan) { ?>  
                                            <button type="submit" class="btn btn-info">Import</button>
                                                             
                                        <?php 
                                        }else{ ?>
                                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                                        <?php
                                        }
                                    ?>
                                    <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-dark">back</a>
                                </div>
                            </div>

                        </div>
                        <?= form_close();?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="container">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            <?php 
                if($this->session->flashdata('pesan')){ ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php
                }elseif($this->session->flashdata('pesan_success')){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= $this->session->flashdata('pesan_success'); ?>
                    </div>
                <?php
                }
            ?>
            Detail Product
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <!-- <table id="example" class="display" style="display: inline-block; overflow-y: scroll; height:400px;"> -->
            <table id="detail" class="display" style="display: inline-block; overflow-x: scroll; width:100%">
                <thead>
                    <tr>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Verifikasi</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Deskripsi</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Kodeprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Namaprod</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Batch</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">ED</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Qty Ajuan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Qty Approval</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Satuan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nama Outlet</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Alasan</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Ket</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">
                            <?php 
                            if (!$tanggal_pengajuan) { ?>
                                <a href="<?= base_url().'management_inventory/delete_detail/'.$signature.'/'.$supp ?>" class="btn btn-danger btn-sm rounded" onclick="return confirm('Anda yakin menghapus semua produk ?')">Delete All</a>
                            <?php
                            }else{ ?>
                                <label class="btn btn-secondary btn-sm btn-outline rounded">Delete All</label>
                            <?php
                            }?>
                        </th>
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
                        <td><?= $a->qty_approval ?></td>
                        <td>
                            <?php 
                                if ($a->satuan) {
                                    echo $a->satuan; 
                                }else{ ?>
                                    <label style="background-color: red;"><font color="white"><i>&nbsp; blank &nbsp;</i> </font></label>
                                <?php
                                }
                            ?>
                        </td>
                        <td><?= $a->nama_outlet ?></td>
                        <td><?= $a->alasan ?></td>
                        <td><?= $a->keterangan ?></td>
                        <td>
                            <?php 
                                if (!$tanggal_pengajuan) { ?>
                                    <a href="<?= base_url().'management_inventory/delete_product/'.$a->signature.'/'.$supp.'/'.$signature ?>" class="btn btn-danger btn-sm btn-outline" onclick="return confirm('Are you sure?')">X</a>
                                <?php
                                }else{ ?>
                                    <label class="btn btn-secondary btn-sm btn-outline">X</label>
                                <?php
                                }
                            ?>
                            
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <h4>Preview Data Pengajuan</h4>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3">
            <p>Count Produk</p>
        </div>
        <div class="col-md-4">
            <p>: <?= $count_kodeprod ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <p>Sum QTY Pengajuan</p>
        </div>
        <div class="col-md-4">
            <p>: <?= $sum_qty_pengajuan ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <p>Value RBP</p>
        </div>
        <div class="col-md-4">
            <p>: <?= number_format($value_rbp) ?></p>
        </div>
    </div>
    <hr>

    <div class="row mb-5">
        <div class="col-md-12">
            <p><strong><font size="4px">Jangan lupa untuk RE-CHECK data anda sebelum diajukan. Termasuk di kolom ED. Mungkin saja ada kesalahan data yang tidak disadari saat copy paste data dari SDS ke web MPM </font></strong><br><br>Jika sudah ok, klik Button "Preview Pengajuan Retur" :</p>
        </div>

        <div class="col-md-12">
            <div class="">
                <div class="">

                    <?php
                        if (!$tanggal_pengajuan) { ?>
                            <?php echo form_open($url_pengajuan); ?>
                                <input type="hidden" name="signature" value="<?= $signature ?>">
                                <input type="hidden" name="supp" value="<?= $supp ?>">
                                <input type="hidden" name="tipe" value="<?= $tipe ?>">
                                <input type="submit" value="Proses Pengajuan Retur" class="btn btn-info" id="btnKirim" onclick="return button()">
                                <button class="btn btn-info" id="btnLoading" type="button" disabled>
                                ... mohon tunggu, jangan tutup halaman ini ...
                                </button>
                                <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-dark">back</a>       
                            <?= form_close();?>
                        <?php 
                        }else{ ?>
                            <button type="submit" class="btn btn-dark" disabled>permintaan sudah diajukan</button>
                            <a href="<?= base_url().'management_inventory/' ?>" class="btn btn-dark">back</a>  
                        <?php
                        }
                    ?>             
                </div>
            </div>
        </div>
    </div>
    
    <hr>
    <br>
    <br>

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

    $("select[name = kodeprod]").on("change", function() {
        var kodeprod_terpilih = $("option:selected", this).attr("id_kodeprod");
        console.log(kodeprod_terpilih);
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/satuan') ?>',
            data: 'kodeprod=' + kodeprod_terpilih,
            success: function(hasil_satuan) {
                $("select[name = satuan]").html(hasil_satuan);
            }
        });
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/alasan_retur_new') ?>',
        data: 'supp=<?= $this->uri->segment('4') ?>',
        success: function(hasil_alasan) {
            $("select[name = alasan_retur]").html(hasil_alasan);
        }
    });
</script>

<script>
    function button()
    {
        $("#btnKirim").hide();
        $("#btnBack").hide();
        $("#btnLoading").show();
    }

    $(document).ready(function() {       
        $("#btnLoading").hide();
    });
</script>