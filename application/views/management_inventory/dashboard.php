
<style>
    input[type=button] 
    {
        font-weight: bold;
        color: white;
        background-color: transparent;
        text-align: center;
        border: none;
    }
    /* td{
        font-size: 12px;
    }
    th{
        font-size: 13px; 
    } */

    th{
        font-weight: bold;
        background-color: #f0f0f0;
        border: 1px solid #383838;
        color: #383838;
        font-size: 13px;
        text-align: center;
        /* text-align: center; */
    }
    td{
        background-color: #ffffff;
        border: 1px solid #383838;
        font-size: 11px;
        
        /* align-items: center;
        align-content: center; */
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

    /* table{
        width: 10000px;
    } */

    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
        border-radius: 10px;
    }

    .btn-submit:hover {
        color: #f0f0f0;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #2D3250;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .override{
        display: flex;
        justify-content: space-around;
        align-items: center;
        /* padding-right: 10px; */
    }

</style>

</div>

<div class="container">

    <div class="row">
        <div class="col-md-12 az-content-label">
            <?= $title ?>
        </div>
    </div>
</div>




<div class="container mt-s">

    <div class="row mt-3 ms-5">
        <div class="col-md-12 az-content-label text-center">
            DETAIL PENGAJUAN RETUR
        </div>
    </div>

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

    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="form-inline row">
                <div class="col-sm-12">
                    <form action="<?= $url_search ?>">
                        From
                        <input class="form-control" type="date" name="from" value="<?= $this->input->get('from') ?>" required />
                        To
                        <input class="form-control" type="date" name="to" value="<?= $this->input->get('to') ?>" required />
                        <select name="status" class="form-control">
                            <option value="0" <?= $this->input->get('status') == 0 ? 'selected' : '' ?>> All Status </option>
                            <option value="1" <?= $this->input->get('status') == 1 ? 'selected' : '' ?>> Pending DP </option>
                            <option value="2" <?= $this->input->get('status') == 2 ? 'selected' : '' ?>> Pending MPM </option>
                            <option value="3" <?= $this->input->get('status') == 3 ? 'selected' : '' ?>> Pending Principal Area </option>
                            <option value="4" <?= $this->input->get('status') == 4 ? 'selected' : '' ?>> Pending Principal HO </option>
                            <option value="5" <?= $this->input->get('status') == 5 ? 'selected' : '' ?>> Pending Kirim Barang </option>
                            <option value="6" <?= $this->input->get('status') == 6 ? 'selected' : '' ?>> Pending Terima Barang  </option>
                            <option value="8" <?= $this->input->get('status') == 8 ? 'selected' : '' ?>> Barang di Terima  </option>
                            <option value="7" <?= $this->input->get('status') == 7 ? 'selected' : '' ?>> Pending Pemusnahan  </option>
                            <option value="9" <?= $this->input->get('status') == 9 ? 'selected' : '' ?>> Pemusnahan Selesai  </option>
                            <option value="10" <?= $this->input->get('status') == 10 ? 'selected' : '' ?>> Reject Principal Ho  </option>
                        </select>
                        <button type="submit" value="1" class="btn btn-outline-danger btn-sm" name="type">Search</button>
                        <?php 
                            if ($this->session->userdata('supp') == 005) { ?>
                        
                            <?php
                            }else{ ?>
                                <button type="submit" value="2" class="btn btn-outline-danger btn-sm" name="type">Export To CSV</button>
                            <?php
                            }
                        ?>
                        <a href="<?= base_url() ?>management_inventory" class="btn btn-outline-dark btn-sm">Reset</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <!-- <table id="example" class="display" style="display: inline-block; overflow-x: scroll; width:100%"> -->
            <table id="dashboard">
                <thead>
                    <tr>
                        <th>Tgl</th>
                        <th>No Retur</th>
                        <th>Principal</th>
                        <th>Company</th>
                        <th>Site</th>
                        <th>Status</th>
                        <th>Nota Retur</th>
                        <th>Override Status</th>
                    </tr>
                </thead>
                <tbody>     
                    <?php 
                    foreach ($get_pengajuan->result() as $a) : ?>
                    <tr>
                        <td><?= $a->tanggal_pengajuan ?></td>
                        <td>
                            <a href="<?= base_url().'management_inventory/generate_pdf/'.$a->signature.'/'.$a->supp ?>" class="btn btn-submit"><?= ($a->no_pengajuan) ? $a->no_pengajuan : 'NULL'; ?></a>
                        </td>
                        <td><?= $a->namasupp ?></td>
                        <td><?= $a->branch_name ?></td>
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
                                }elseif($a->status == 10){ // REJECT PRINCIPAL HO
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
                        <td>
                            <?php echo form_open($url_override) ?>
                            <div class="override">
                                <input type="hidden" name="id" value="<?= $a->id ?>">  
                                <div>
                                    <select name="status" class="form-control">
                                        <option value="" <?= $a->status == 0 ? 'selected' : '' ?>> Pilih Status </option>
                                        <option value="1" <?= $a->status == 1 ? 'selected' : '' ?>> Pending DP </option>
                                        <option value="2" <?= $a->status == 2 ? 'selected' : '' ?>> Pending MPM </option>
                                        <option value="3" <?= $a->status == 3 ? 'selected' : '' ?>> Pending Principal Area </option>
                                        <option value="4" <?= $a->status == 4 ? 'selected' : '' ?>> Pending Principal HO </option>
                                        <option value="5" <?= $a->status == 5 ? 'selected' : '' ?>> Pending Kirim Barang </option>
                                        <option value="6" <?= $a->status == 6 ? 'selected' : '' ?>> Pending Terima Barang  </option>
                                        <!-- <option value="8" <?= $a->status == 8 ? 'selected' : '' ?>> Barang di Terima  </option> -->
                                        <option value="7" <?= $a->status == 7 ? 'selected' : '' ?>> Pending Pemusnahan  </option>
                                        <!-- <option value="9" <?= $a->status == 9 ? 'selected' : '' ?>> Pemusnahan Selesai  </option> -->
                                        <!-- <option value="10" <?= $a->status == 10 ? 'selected' : '' ?>> Reject Principal Ho  </option> -->
                                        <!-- <option value="11" <?= $a->status == 11 ? 'selected' : '' ?>> Retur Sample  </option> -->
                                        <option value="12" <?= $a->status == 12 ? 'selected' : '' ?>> Delete  </option>
                                    </select>
                                </div>
                                <div>

                                    <button type="submit" class="btn-pendingmpm btn-sm" name="submit">update</button>
                                </div>  
                            </div>
                            <?php echo form_close() ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>   
                </tbody>
            </table>
        </div>
    </div>

<script>
      $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
        });
      });

      $(document).ready(function () {
        $("#status").DataTable({
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

      $(document).ready(function () {
        $("#site").DataTable({
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
    $(document).ready(function () {
        $('#dashboard').DataTable(
        {
            responsive: true,
            "pageLength": 10,
            "ordering": true,
            "order": [0, 'desc'],
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            },
        }
        );
    });
</script>

<script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url() ?>assets_new/js/checkbox_all.js"></script>
