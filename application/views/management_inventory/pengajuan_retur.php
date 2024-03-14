<?php
foreach ($site_code->result() as $a) {
    $site_dp = $a->site_code;
    $subbranch_dp = $a->nama_comp;
    $company_dp = $a->company;
    $site[$a->site_code] = $a->branch_name.' - '.$a->nama_comp.' ('.$a->site_code.')';
}
?>
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
        /* text-align: center; */
    }
    th{
        font-size: 14px; 
    }
</style>

</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>

    <?= form_open_multipart($url); ?>
    
    <div class="row mt-4">
        <div class="col-md-2">
            <label for="batch_number" class="form-label">Subbranch</label> 
        </div>
        <div class="col-md-4">
            <?php
                echo form_dropdown('site_code', $site, '', 'class="form-control"  id="site_code" required');
            ?>
        </div>
    </div>

    <?php
    if ($status_mpi == 1) { ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA  </option>
                <option value="001-NKA"> Deltomed - NKA  </option>
            </select>
        </div>
    </div>

    <?php 
    }else{ ?>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="supp" class="form-label">Principal</label> 
        </div>
        <div class="col-md-4">
            <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                <option value=""> -- pilih principal -- </option>
                <option value="001-GT"> Deltomed - GT  </option>
                <!-- <option value="001-GT-PHARMA"> Deltomed - GT - PHARMA  </option> -->
                <option value="001-MTI"> Deltomed - MTI  </option>
                <!-- <option value="001-NKA"> Deltomed - NKA  </option> -->
                <option value="001-herbana"> Deltomed - Herbana Herbamojo </option>
                <option value="002"> Marguna </option>
                <option value="004"> Jaya Agung Makmur </option>
                <option value="005"> Ultra Sakti </option>
                <option value="012"> Intrafood </option>
                <option value="013"> Strive </option>
                <option value="015"> MDJ </option>
            </select>
        </div>
    </div>

    <?php
    } ?>

    

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="tipe" class="form-label">Tipe</label> 
        </div>
        <div class="col-md-4">
            <select id="tipe" name="tipe" class="form-control" required>
                <option value="reguler"> reguler </option>
                <option value="khusus"> khusus  </option>
            </select>
            <!-- <div id="p1">aaaa</div> -->
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="nama" class="form-label">Nama Yang Mengajukan</label> 
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="file" id="label_attachment" class="form-label">Upload File Pendukung (opsional)</label> 
        </div>
        <div class="col-md-4">
            <input type="file" class="form-control" id="file" name="file">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-2">
            <label for="file" class="form-label">Manage Signature Digital</label> 
        </div>
        <div class="col-md-4">
        
            <div class="col-md-10 d-flex flex-row">
                    
                <?php 
                    $file = './assets/uploads/signature/'.$this->session->userdata('username').'-signature.png'; // 'images/'.$file (physical path)
                    if (file_exists($file)) { ?>
                        <a href="<?= base_url() ?>management_inventory/signature_digital" class="btn btn-outline-dark btn-sm" target="_blank">
                            <img src="<?= base_url().'assets/uploads/signature/'.$this->session->userdata('username').'-signature.png' ?>" alt="<?= $this->session->userdata('username').'-signature' ?>" width="150px">
                        </a>  
                    <?php
                    } else { ?>
                        <a href="<?= base_url() ?>management_inventory/signature_digital" class="btn btn-outline-dark btn-sm" target="_blank">
                            click here
                        </a>  
                    <?php 
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-2">
            
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-info">Save dan Lanjutkan Pengisian Produk</button>
        </div>
    </div>
    
    <?= form_close();?>
    
    <hr>

</div>

<div class="container">
    <div class="row mt-5 ms-5">
        <div class="col-md-12 az-content-label text-center">
            History Pengajuan Retur
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <table id="example" class="display" style="display: inline-block; overflow-x: scroll; width:100%">
                <thead>
                    <tr>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Tgl</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">No Retur</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Principal</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Site</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Status</th>
                        <th style="background-color: darkslategray;" class="text-center col-1"><font color="white">Nota Retur</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td><?= $a->tanggal_pengajuan ?></td>
                        <td>
                            <a href="<?= base_url().'management_inventory/generate_pdf/'.$a->signature.'/'.$a->supp ?>" class="btn btn-warning btn-sm rounded"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a>
                        </td>
                        <td><?= $a->namasupp ?></td>
                        <td><?= $a->nama_comp ?></td>
                        <td>
                            <?php 
                                if ($a->status == 1) { // PROSES DP
                                    $color = "btn-info btn-sm rounded";
                                }elseif($a->status == 2){ // PROSES MPM
                                    $color = "btn-warning btn-sm rounded";
                                }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                                    $color = "btn-danger btn-sm rounded"; 
                                }elseif($a->status == 4){ // PROSES PRINCIPAL HO
                                    $color = "btn-danger btn-sm rounded";
                                }elseif($a->status == 5){ // PROSES KIRIM BARANG
                                    $color = "btn-info btn-sm rounded";
                                }elseif($a->status == 6){ // PROSES TERIMA BARANG
                                    $color = "btn-danger btn-sm rounded";
                                }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                                    $color = "btn-info btn-sm rounded";
                                }elseif($a->status == 8 || $a->status == 9){ // BARANG DITERIMA dan Pemusnahan
                                    $color = "btn-dark btn-sm rounded";
                                }else{
                                    $color = "btn-info btn-sm rounded";
                                }
                                
                            ?>
                            <a href="<?= base_url().'management_inventory/routing/'.$a->signature ?>" class="btn <?= $color ?> btn-sm"><?= $a->nama_status ?></a>
                        </td>
                        <td>
                            <?php 
                                if ($a->noseri) { ?>
                                    <a href="#" class="btn btn-primary">DONE</a>
                                <?php }else{ ?>
                                    <i>belum tersedia</i>
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
    <br>
    <br>

<script>
      $(document).ready(function () {
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

<script>
    $("select[name = tipe]").on("change", function() {
        var tipe_terpilih = document.getElementById('tipe').value;
        console.log(tipe_terpilih);
        if (tipe_terpilih == 'khusus') {
            document.getElementById("label_attachment").innerHTML = "<label for='file' id='label_attachment' class='form-label'>Upload File Pendukung (wajib)</label> ";
            document.getElementById("file").required = true;
        }else{
            document.getElementById("label_attachment").innerHTML = "<label for='file' id='label_attachment' class='form-label'>Upload File Pendukung (opsional)</label> ";
            document.getElementById('file').removeAttribute('required');
        }
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
