<style>
    th{
        font-weight: bold;
        background-color: #f0f0f0;
        border: 1px solid #383838;
        color: #383838;
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
    }

    .btn-submit:hover {
        color: #f0f0f0;
        background-color: #647D87;
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

    .btn-ok{
        background-color: #525CEB;
        color: #f0f0f0;
        padding: 5px 5px 5px 5px;
        border-radius: 5px;
    }

    .btn-no{
        background-color: #647D87;
        color: #f0f0f0;
        padding: 5px 5px 5px 5px;
        border-radius: 5px;
    }

    .btn-ok:hover{
        /* background-color: #f0f0f0; */
        cursor: pointer;   
    }

    .btn-export {
        color: #f0f0f0;
        background-color: #D04848;
        border-radius: 10px;
    }

    .btn-export:hover {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .btn-all {
        color: #f0f0f0;
        background-color: #6895D2;
        border-radius: 10px;
    }

    .btn-all:hover {
        color: #f0f0f0;
        background-color: #7077A1;
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

    <div class="row mt-3">
        <div class="col-md-12">
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

    <form action="<?= $url ?>">

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label">Principal</label> 
            </div>
            <div class="col-md-5">
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
                <label for="kategori" class="form-label">Kategori</label>
            </div>
            <div class="col-md-5">
                <select id="kategori" name="kategori" class="form-control" required>
                    <option value=""> -- pilih kategori -- </option>
                    <option value="all" <?= $this->input->get('kategori') == 'all' ? 'selected' : '' ?>> All </option>
                    <option value="loyalty" <?= $this->input->get('kategori') == 'loyalty' ? 'selected' : '' ?>> Loyalty </option>
                    <option value="bonus_barang" <?= $this->input->get('kategori') == 'bonus_barang' ? 'selected' : '' ?>> Bonus Barang</option>
                    <option value="diskon_herbal" <?= $this->input->get('kategori') == 'diskon_herbal' ? 'selected' : '' ?>> Diskon Herbal</option>
                    <option value="diskon_candy" <?= $this->input->get('kategori') == 'diskon_candy' ? 'selected' : '' ?>> Diskon Candy</option>
                    <option value="diskon" <?= $this->input->get('kategori') == 'diskon' ? 'selected' : '' ?>> Diskon</option>
                    <option value="insentif" <?= $this->input->get('kategori') == 'insentif' ? 'selected' : '' ?>> Insentif </option>
                    <option value="listing_fee" <?= $this->input->get('kategori') == 'listing_fee' ? 'selected' : '' ?>> Listing Fee </option>
                    <option value="rafaksi" <?= $this->input->get('kategori') == 'rafaksi' ? 'selected' : '' ?>> Rafaksi </option>
                    <option value="program MT" <?= $this->input->get('kategori') == 'program MT' ? 'selected' : '' ?>> Program MT </option>
                    <option value="sewa display" <?= $this->input->get('kategori') == 'sewa display' ? 'selected' : '' ?>> Sewa Display </option>
                    <option value="salesman herbana" <?= $this->input->get('kategori') == 'salesman herbana' ? 'selected' : '' ?>> Salesman Herbana </option>
                    <option value="sample promosi" <?= $this->input->get('kategori') == 'sample promosi' ? 'selected' : '' ?>> Sample Promosi </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="from" class="form-label">Periode</label> 
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="date" name="from" id="from" class="form-control" value="<?= $this->input->get('from') ?>" required>
                    <input type="date" name="to" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="pic" class="form-label">Nama PIC</label>
            </div>
            <div class="col-md-5">
                <select id="pic" name="pic" class="form-control" required>
                    <option value=""> -- Pilih PIC -- </option>
                    <option value="all" <?= $this->input->get('pic') == 'all' ? 'selected' : '' ?>> All </option>
                    <option value="18" <?= $this->input->get('pic') == '18' ? 'selected' : '' ?>> Ismi </option>
                    <option value="444" <?= $this->input->get('pic') == '444' ? 'selected' : '' ?>> Ambar </option>
                    <option value="561" <?= $this->input->get('pic') == '561' ? 'selected' : '' ?>> Adi </option>
                    <option value="557" <?= $this->input->get('pic') == '557' ? 'selected' : '' ?>> Rani </option>
                    <option value="99" <?= $this->input->get('pic') == '99' ? 'selected' : '' ?>> Yuli </option>
                    <option value="812" <?= $this->input->get('pic') == '812' ? 'selected' : '' ?>> Dea </option>
                    <option value="297" <?= $this->input->get('pic') == '297' ? 'selected' : '' ?>> Suffy </option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-2">
                <label for="supp" class="form-label"></label> 
            </div>
            <div class="col-md-6">
                <input type="submit" value="Tampilkan Data Sesuai Filter" class="btn btn-submit">
                <a href="<?= base_url('management_claim/dashboard') ?>" class="btn btn-all">Tampilkan All Data</a>
                <!-- <a href="<?= base_url('management_claim/export_dashboard') ?>" class="btn btn-export">Export</a> -->
                <input type="submit" value="Export Sesuai Filter" class="btn btn-export" name="export">
            </div>
        </div>

    </form>

</div>
</div>

<div class="container mt-1">

    <div class="card-block mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <hr class="batas">
            </div>

            <?php 
                if ($get_registrasi_program_by_supp_kategori_periode->num_rows() > 0){ ?>

                    <div class="col-md-12 mt-4">

                        <table id="dashboard">
                            <thead>
                                <tr>                                    
                                    <th class="text-center col-1">No</th>
                                    <?php
                                    $no = 1;
                                    foreach ($get_registrasi_program_by_supp_kategori_periode->result() as $program) { ?>
                                    <th>
                                        <div class="mb-2">
                                            <?= $program->namasupp.' - ' ?><a href="<?= base_url().'assets/uploads/management_claim/'.$program->upload_pdf ?>" class="btn-custom"><?= $program->nomor_surat ?></a>
                                        </div>
                                        <div>
                                            <?= $program->kategori.' | '.$program->from.' sd '.$program->to.' | PIC : '.$program->username ?>
                                        </div>
                                        <div class="mt-2">
                                            <?php 
                                                $summary = $this->model_management_claim->summary_ajuan_claim_group_status_by_idprogram($program->id);
                                                if ($summary->num_rows() > 0) { ?>
                                                <table>                                                    
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Count</th>
                                                    </tr>
                                                    <?php
                                                    foreach ($summary->result() as $a) { ?>
                                                        <tr>
                                                            <td><?= $a->nama_status ?></td>
                                                            <td><?= $a->count_status ?></td>
                                                        </tr>
                                                    <?php
                                                    } ?>
                                                </table>
                                                <?php
                                                }    
                                            ?>
                                        </div>
                                    </th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody> 
                            
                            <?php 
                                foreach ($get_ajuan_claim_group_subbranch_by_idprogram->result() as $a) { ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <?php 
                                        foreach ($get_registrasi_program_by_supp_kategori_periode->result() as $b) { ?>
                                            <td>
                                                <?php

                                                $get_data = $this->model_management_claim->get_ajuan_claim_by_idprogram_sitecode($b->id, $a->site_code);
                                                if ($get_data->num_rows() > 0) { ?>
                                                    
                                                    <div>
                                                        DP : <?= $get_data->row()->branch_name.' | '.$get_data->row()->nama_comp. ' | '.$get_data->row()->site_code ?>
                                                    </div>
                                                    <div class="mt-2">
                                                        <?php 
                                                            if ($get_data->row()->status == '6') { ?>
                                                                <a href="#" class="btn-ok">status : <?= $get_data->row()->nama_status ?></a>
                                                            <?php
                                                            }else{ ?>
                                                                <a href="#" class="btn-no">status : <?= $get_data->row()->nama_status ?></a>
                                                            <?php
                                                            }
                                                        ?>
                                                    </div>
                                                    

                                                <?php
                                                } ?>

                                                
                                                
                                            </td>
                                        <?php
                                        }
                                    ?>
                                </tr>
                                    <?php
                                    }
                                    ?>                    
                            </tbody>
                        </table>
                    </div>
                <?php
                }else{ ?>

                    <div class="col-md-12 text-center">
                        <h4>data tidak ditemukan</h4>
                    </div>
                <?php
                }
            ?>
        </div>
    </div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/nama_comp_claim') ?>',
        data: '',
        success: function(hasil_branch) {
            $("select[name = site_code]").html(hasil_branch);
        }
    });
</script>
<!-- 
<script>
    $(document).ready(function () {
        $('#dashboard').DataTable();
    });
</script> -->

<script>
    $(document).ready(function () {
        $('#dashboard').DataTable(
        {
            // scrollX: true,
            // sScrollX: '100%',
            // scrollY: 1000,
            responsive: true
        }
        );
    });
</script>