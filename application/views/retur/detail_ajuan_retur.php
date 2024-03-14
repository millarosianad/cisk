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
// echo $get_history->kode;
// die;

?>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">


            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Principal</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $principal.' | '.$supp; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tanggal Pengajuan</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $tanggal_pengajuan; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Branch</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $branch_name.' | '.$nama_comp; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Last Updated</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $last_updated; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">No Pengajuan Retur</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $no_pengajuan; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $nama_status; ?>">
                        </div>
                    </div>
                </div>
            </div>



            <hr>

            <?php echo form_open($url); ?>

            <div class="dt-responsive table-responsive mt-4">
                <table id="table-cart" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1%">Kodeprod</th>
                            <th width="50%">Namaprod</th>
                            <th width="20%">Qty ajuan</th>
                            <th width="20%">Qty retur</th>
                            <th width="20%">Harga retur</th>
                            <th width="20%">Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>


                        <?php foreach ($get_detail_ajuan_retur->result() as $key) : ?>
                            <tr>
                                <td><?= $key->kodeprod; ?>
                                    <input type="hidden" name="kodeprod[]" value="<?= $key->kodeprod; ?>">
                                </td>
                                <td><?= $key->namaprod; ?>
                                    <input type="hidden" name="namaprod[]" value="<?= $key->namaprod; ?>">
                                </td>
                                <td><?= $key->jumlah; ?>
                                    <input type="hidden" name="qty_ajuan[]" value="<?= $key->jumlah; ?>">
                                </td>
                                <td><input type="number" name="qty_retur[]" class = "form-control" value="0"></td>
                                <td><input type="number" name="harga_retur[]" class = "form-control" value="0"></td>
                                <td>
                                    <?php 
                                        if ($supp == '005') {
                                            $params_dicount = 10;
                                        }else{
                                            $params_dicount = 0;
                                        }
                                    ?>
                                    <input type="text" name="discount_retur[]" class = "form-control" value="<?= $params_dicount; ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>



                    </tbody>
                </table>

            </div>

            <hr>

            <input type="hidden" class="form-control" name="id_pengajuan_retur" value="<?= $id_pengajuan_retur ?>" required>
            <input type="hidden" class="form-control" name="no_pengajuan" value="<?= $no_pengajuan ?>" required>
            
            <div class="form-group row">
                <label for="no_faktur_pajak" class="col-sm-2 col-form-label">Nomor Faktur Pajak</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id = "no_faktur_pajak" name="no_faktur_pajak" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="tanggal_faktur_pajak" class="col-sm-2 col-form-label">Tanggal Faktur Pajak</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" id = "tanggal_faktur_pajak" name="tanggal_faktur_pajak" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="tanggal_retur" class="col-sm-2 col-form-label">Tanggal Retur</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" id = "tanggal_retur" name="tanggal_retur" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 d-inline">
                    <a href="<?= base_url(); ?>retur/generate" class="btn btn-dark">Kembali</a>
                    <?php echo form_submit('submit', 'Save dan Proses Retur', 'class="btn btn-primary" required'); ?>
                </div>
            </div>



            <?php echo form_close(); ?>

        </div>
    </div>
</div>