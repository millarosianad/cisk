<style>
    th {
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }
</style>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            


            <div class="title">
                <div class="row">
                    <div class="col">
                        <h3>Konfirmasi Surat Jalan</h3>
                    </div>
                    <div class="col text-right">
                        <a href="<?= base_url() ?>dc/export_keluar" target="_blank" class="btn btn-warning">export</a>
                    </div>
                </div>
            </div>
            <div class="dt-responsive table-responsive mt-4">
                <!-- <table id="table-dc" class="table table-striped table-bordered nowrap"> -->
                <table id="table-dc" class="table table-hover m-b-0">
                    <thead>
                        <tr>
                            <th>Kode</th>                            
                            <th>Pengiriman (SJ1)</th>
                            <th>TanggalKirim</th>
                            <th>File_1</th>
                            <th>File_2</th>
                            <th>File_3</th>
                            <th>Penerimaan (SJ2)</th>
                            <th>TanggalTiba</th>
                            <th>File_4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_konfirmasi as $key) : ?>
                            <tr>
                                <td>
                                    <?php echo anchor(base_url().'dc/generate_pdf_keluar/'.$key->signature, '<i><font size=2px>'.$key->kode.'</font></i>', 'target=_blank'); ?>
                                </td>
                                <td>
                                    <a href="<?= base_url() ?>dc/konfirmasi_pengiriman_dc/<?= $key->signature; ?>" class=" btn btn-primary btn-sm" target="blank">click here</a>
                                    <!-- <input type="file" name="file" class="form-control"> -->
                                </td>
                                <td>
                                    <?php 
                                        if ($key->tanggal_kirim) {
                                            echo $key->tanggal_kirim;
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($key->file_1) {
                                            echo anchor(base_url().'assets/file/dc/'.$key->file_1,'<i>view</i>', 'target=_blank');
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($key->file_2) {
                                            echo anchor(base_url().'assets/file/dc/'.$key->file_2,'<i>view</i>', 'target=_blank');
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($key->file_3) {
                                            echo anchor(base_url().'assets/file/dc/'.$key->file_3,'<i>view</i>', 'target=_blank');
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?= base_url() ?>dc/konfirmasi_penerimaan_dc/<?= $key->signature; ?>" class=" btn btn-warning btn-sm" target="blank">click here</a>
                                    <!-- <input type="file" name="file" class="form-control"> -->
                                </td>
                                <td>
                                    <?php 
                                        if ($key->tanggal_tiba) {
                                            echo $key->tanggal_tiba;
                                        }else{
                                            echo "-";
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($key->file_4) {
                                            echo anchor(base_url().'assets/file/dc/'.$key->file_4,'<i>view</i>', 'target=_blank');
                                        }else{
                                            echo "-";
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

<script>
    $(document).ready(function() {
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('dc/nodo_barang_keluar') ?>',
            success: function(hasil_kode) {
                $("select[name = kode_masuk]").html(hasil_kode);
            }
        });
    })
</script>