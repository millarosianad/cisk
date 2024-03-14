<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    .batas{
        border: 1px dotted grey;
        border-radius: 5px;
    }

    th{
        font-weight: bold;
        background-color: #383838;
        border: 1px solid #383838;
        color: #f0f0f0;
        align-items: center;
        align-content: center;
        font-size: 13px;
        /* text-align: center; */
    }
    td{
        background-color: #ffffff;
        border: 1px solid #383838;
        font-size: 11px;
        
        /* align-items: center;
        align-content: center; */
    }

    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #365486;
    }

    .btn-hardcopy {
        color: #f0f0f0;
        background-color: #37B5B6;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-hardcopy:hover {
        color: black;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #FE7A36;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingprincipal {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
        border: 2px solid black;
    }
    
    .btn-null {
        color: black;
        background-color: #F9EFDB;
        border-radius: 10px;
        border: 2px solid black;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
    
    .btn-custom{
        background-color: white;
        color: black;
        border-radius: 5px;
        border: 2px solid red;
        /* margin-left: 1px; */
        /* margin-top: 20px; */
        padding: 2px;
        width: 10px;
        height: 10px;
    }
</style>

</div>

<div class="container">

    <form action="<?= $url ?>">

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label">Principal</label> 
            </div>
            <div class="col-md-4">
                <select id="supp" name="supp" class="form-control" onchange="getTipe()" required>
                    <option value=""> -- pilih principal -- </option>
                    <option value="001" <?= $this->input->get('supp') == 001 ? 'selected' : '' ?>> Deltomed</option>
                    <option value="002" <?= $this->input->get('supp') == 002 ? 'selected' : '' ?>> Marguna </option>
                    <option value="005" <?= $this->input->get('supp') == 005 ? 'selected' : '' ?>> Ultra Sakti </option>
                    <option value="012" <?= $this->input->get('supp') == 012 ? 'selected' : '' ?>> Intrafood </option>
                    <option value="013" <?= $this->input->get('supp') == 013 ? 'selected' : '' ?>> Strive </option>
                    <option value="015" <?= $this->input->get('supp') == 015 ? 'selected' : '' ?>> MDJ </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="date" name="from" id="from" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label"></label> 
            </div>
            <div class="col-md-4">
                <input type="submit" value="cari program" class="btn btn-submit">
                <a href="<?= base_url().'management_claim/ajuan_claim' ?>" class="btn btn-null">Tampilkan ALL Data</a>
            </div>
        </div>

    </form>
</div>

<div class="container mt-4">

    <div class="row mt-2 ms-5">
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
        </div>
    </div>

    <div class="card-block mt-1 mb-5">
        <div class="row">
            <div class="col-md-12">

                <table id="ajuan" class="display" style="overflow-x: scroll;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Status</th>
                            <th>Status Hardcopy</th>
                            <th>No Ajuan Claim</th>
                            <th>Principal</th>
                            <th>Kategori</th>
                            <th>Nama Program</th>
                            <th>No Surat</th>
                            <th>Periode</th>
                            <th>Jatuh Tempo</th>
                            <th>Syarat</th>
                            <th>PIC</th>
                            <th>DP</th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php $no = 1;
                        foreach ($get_data->result() as $a) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <?php 
                                if ($a->status == 1) { // PROSES DP
                                    $color = "btn-pendingdp";
                                }elseif($a->status == 2){ // PROSES MPM
                                    $color = "btn-pendingmpm";
                                }elseif($a->status == 3){ // PROSES PRINCIPAL AREA
                                    $color = "btn-danger"; 
                                }elseif($a->status == 4){ // PROSES PRINCIPAL
                                    $color = "btn-pendingprincipal";
                                }elseif($a->status == 5){ // PROSES KIRIM BARANG
                                    $color = "btn-info";
                                }elseif($a->status == 6){ // PROSES TERIMA BARANG
                                    $color = "btn-danger";
                                }elseif($a->status == 7){ // PROSES PEMUSNAHAN
                                    $color = "btn-info";
                                }elseif($a->status == 8 || $a->status == 9){ // BARANG DITERIMA dan Pemusnahan
                                    $color = "btn-dark";
                                }elseif($a->status == 10){ // REJECT PRINCIPAL HO
                                    $color = "btn-dark";
                                }else{
                                    $color = "btn-null";
                                }
                                ?>

                                <?php 
                                    if ($a->status == null) { ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm">Belum ada</a>
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn <?= $color ?> btn-sm"><?= $a->nama_status ?></a>
                                    <?php
                                    }
                                ?>
                            </td>    
                            <td>
                                <?php 
                                    if ($a->status_hardcopy) { ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-pendingmpm"><?= $a->nama_status_hardcopy ?></a>                                        
                                    <?php
                                    }else{ ?>
                                        <a href="<?= base_url().'management_claim/routing_hardcopy/'.$a->signature.'/'.$a->signature_ajuan ?>" class="btn btn-hardcopy">Belum ada</a>
                                    <?php
                                    }
                                ?>
                            </td>                            
                            <td><?= $a->nomor_ajuan; ?></td>  
                            <td><?= $a->namasupp; ?></td>
                            <td><?= $a->kategori; ?></td>
                            <td><?= $a->nama_program; ?></td>
                            <td><?= $a->nomor_surat; ?></td>
                            <td><?= $a->from.' sd '.$a->to; ?></td>
                            <td><?= $a->duedate; ?></td>                            
                            <td><?= $a->syarat; ?></td>                            
                            <td><?= $a->username; ?></td>                            
                            <td>
                                <?php 
                                    if ($a->nama_comp) { ?>
                                        <label class="form-label"><?= $a->nama_comp; ?></label>
                                    <?php
                                    }else{ ?>
                                        <label class="form-label">Belum Mengajukan</label>
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
    </div>
</div>
</div>




<script>
    $(document).ready(function () {
        $('#ajuan').DataTable(
            {
                scrollX: true
            }
        );
    });
</script>