<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>

<?php

// var_dump($get_history);
// die;

?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">

            <!-- <button class="btn btn-primary btn-primary" onclick="addRpd()"><i class="fa fa-plus"></i>Tambah Proforma</button> -->
                
            <button class="btn btn-primary" onclick="addProfile()"><i class="fa fa-plus"></i>Tambah Data</button>
            
            <button class="btn btn-info" data-toggle="modal" data-target="#report">Report</button>

            <a class="btn btn-success" href="<?= base_url() ?>rtd/surat_jalan">Surat Jalan</a>

            <hr>
            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <!-- <tr>
                            <th colspan="4">
                                <center></center>
                            </th>
                            <th colspan="3">
                                <center>KM</center>
                            </th>
                            <th colspan="3">
                                <center>Konsumsi</center>
                            </th>
                            <th colspan="1"></th>
                        </tr> -->
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Supp</th>
                            <th>Kodeprod</th>
                            <th>Unit</th>
                            <th>ED</th>
                            <th>BatchNumber</th>
                            <th>
                                <center>#
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <!-- <? var_dump($get_history);   ?> -->
                        <?php foreach ($get_data as $key) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?php echo $key->kode; ?></td>
                                <td><?php echo $key->namasupp; ?></td>
                                <td><?php echo $key->kodeprod; ?></td>
                                <td><?php echo $key->unit; ?></td>
                                <td><?php echo $key->ed; ?></td>
                                <td><?php echo $key->batch_number; ?></td>
                                <td>
                                    
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
$this->load->view('rtd/modal_proforma');
$this->load->view('biaya_operasional/modal_report');
?>

<script>
    $(document).ready(function() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url('rtd/supp') ?>',
            success: function(hasil_supp) {
                $("select[name = supp]").html(hasil_supp);
            }
        });
    })
</script>
<script>
    $(document).ready(function() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url('rtd/kodeprod') ?>',
            success: function(hasil_kodeprod) {
                $("select[name = kodeprod]").html(hasil_kodeprod);
            }
        });
    })
</script>