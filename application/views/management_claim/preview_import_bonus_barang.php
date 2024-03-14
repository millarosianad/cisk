<style>
    td{
        font-size: 11px;
    }
    th{
        font-size: 14px; 
    }
    .btn-submit {
        color: #f0f0f0;
        background-color: #383838;
    }

    .btn-pendingmpm {
        color: #f0f0f0;
        background-color: #2D3250;
    }

    .btn-pendingdp {
        color: #f0f0f0;
        background-color: #7077A1;
    }

    .label-error{
        color: white;
        background-color: red;
        font-size: 15px;
    }
</style>
</div>

<div class="container">

<div class="row">
    <div class="col-md-12 az-content-label">
        <?= $title ?>
    </div>
</div>
    
<?php echo form_open_multipart($url); ?>

<div class="row mt-1 ms-5">
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
    <div class="col-md-3">
        Total Row
    </div>
    <div class="col-md-6">
        : <?= $total_row; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        <?= $total_failed > 0 ? '<p class="label-error">Total Row Failed</p>' : 'Total Row Failed'; ?>
        <!-- <p class="label-error">Total Row Failed</p> -->
    </div>
    <div class="col-md-6">
        : <strong><?= $total_failed; ?></strong>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Total Row Success
    </div>
    <div class="col-md-6">
        : <?= $total_success; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Nama Pengirim
    </div>
    <div class="col-md-6">
        : <?= $nama_pengirim; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Email Pengirim
    </div>
    <div class="col-md-6">
        : <?= $email_pengirim; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Total QTY Jual
    </div>
    <div class="col-md-6">
        : <?= $total_qty_jual; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Total QTY Bonus
    </div>
    <div class="col-md-6">
        : <?= $total_qty_bonus; ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Total Value Jual
    </div>
    <div class="col-md-6">
        : <?= number_format($total_value_jual); ?>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-3">
        Total Value Bonus
    </div>
    <div class="col-md-6">
        : <?= number_format($total_value_bonus); ?>
    </div>
</div>

<!-- <div class="row mt-2">
    <div class="col-md-3">
        Status Data Final
    </div>
    <div class="col-md-6">
        : <?php 
            if ($status_data_final == 0) {
                echo "Data Belum Final";
            }elseif($status_data_final == 1){
                echo "Data Sudah Final";
            }
        ?>
    </div>
</div> -->

<?php 
    if ($total_success != $total_row) { ?>
        <div class="row mt-2">
            <div class="col-md-3">
                Result
            </div>
            <div class="col-md-6"><p style="background-color: red; color:white"><strong>
                : Data anda ditolak. Perbaiki data anda dan import kembali</strong></p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-3">
                
            </div>
            <div class="col-md-6">
                <a href="<?= base_url().'management_claim/form_ajuan_claim/'.$signature_program; ?>" class="btn btn-dark">Back</a>
            </div>
        </div>

    <?php
    }else{ ?>

    <div class="row mt-3">
        <div class="col-md-3">
            Jika sudah sesuai, silahkan klik tombol disamping
        </div>
        <div class="col-md-6">
            <a href="<?= base_url().'management_claim/proses_pengajuan/'.$signature_program.'/'.$signature; ?>" class="btn btn-submit btn-sm rounded-1" id="btnKirim" onclick="return button()">Data Anda Aman. Silahkan Lanjut Pelaporan ke MPM</a>
            <button class="btn btn-info" id="btnLoading" type="button" disabled>
            ... mohon tunggu, jangan tutup halaman ini ...
            </button>
        </div>
    </div>

    

    <?php
    }
?>


<div class="row">
    <div class="col-md-12">
        <hr>
    </div>
</div>


<?php 
    if($total_failed > 0){ ?>

<div class="row mt-3">
    <div class="col-md-12">
        <h5 class="text-center"><i>Preview Data Failed</i></h5>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12 mt-1">
        <table id="example" class="cell-border" style="display: inline-block; overflow-y: scroll">
            <thead>
                <tr>
                    <th style="background-color: darkslategray; height: 40px;" class="text-center col-1"><font color="white">No</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">validasi</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">nomor_surat_program</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">nama_program</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">site_code</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">branch_name</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">nama_comp</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">no_sales</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">tgl_sales</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">kode_class</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">nama_class</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">kode_customer</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">nama_customer</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">kodeprod</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">namaprod</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">qty_jual</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">qty_bonus</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">value_jual</th>
                    <th style="background-color: darkslategray;" class="text-center col-2"><font color="white">value_bonus</th>
                </tr>
            </thead>
            <tbody>     
                <?php $no = 1;
                foreach ($get_preview_import->result() as $a) : ?>
                <tr>                    
                    <td class="text-center"><?= $no++; ?></td>
                    <td class="text-center">
                        <?php 
                            if ($a->validasi_row == 0) {
                                echo "success";
                            }else{ ?>
                                <label class="text-danger">Failed</label>
                            <?php
                            }
                        ?>
                    </td>
                    <td>
                        <?= ($a->id_program != 0) ? "$a->nomor_surat_program" : "<label class='text-danger'>$a->nomor_surat_program</label>"  ?>
                    </td>
                    <td>
                        <?= ($a->id_program != 0) ? "$a->nama_program" : "<label class='text-danger'>$a->nama_program</label>"  ?>
                    </td>
                    <td><?= $a->site_code; ?></td>
                    <td>
                        <?= ($a->branch_name != null) ? "$a->branch_name" : "<label class='text-danger'>blank</label>"  ?>
                    </td>
                    <td>
                        <?= ($a->nama_comp != null) ? "$a->nama_comp" : "<label class='text-danger'>blank</label>"  ?>
                    </td>
                    <td><?= $a->no_sales; ?></td>
                    <td><?= $a->tgl_sales; ?></td>
                    <td><?= $a->kode_class; ?></td>
                    <td>
                        <?= ($a->nama_class != null) ? "$a->nama_class" : "<label class='text-danger'>blank</label>"  ?>
                    </td>
                    <td><?= $a->kode_customer; ?></td>
                    <td><?= $a->nama_customer; ?></td>
                    <td>
                        <?= ($a->namaprod != null) ? "$a->kodeprod" : "<label class='text-danger'>$a->kodeprod</label>"  ?>
                    </td>
                    <td>
                        <?= ($a->namaprod != null) ? "$a->namaprod" : "<label class='text-danger'>blank</label>"  ?>
                    </td>
                    <td><?= $a->qty_jual; ?></td>
                    <td><?= $a->qty_bonus; ?></td>
                    <td><?= $a->value_jual; ?></td>
                    <td><?= $a->value_bonus; ?></td>
                </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>

    </div>
</div>

<?php
    }
?>
    

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
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

</form>


</div>
</div>
