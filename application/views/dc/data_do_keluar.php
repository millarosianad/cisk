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
                        <label class="col-sm-4 col-form-label">Company</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $get_data_header_by_nodo_keluar->company; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">TanggalDO</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $get_data_header_by_nodo_keluar->tgldo; ?>">
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nomor PO</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $get_data_header_by_nodo_keluar->nopo; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Nodo</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control" value="<?= $get_data_header_by_nodo_keluar->nodo; ?>">
                        </div>
                    </div>
                </div>
            </div>



            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Alamat DP</label>
                        <div class="col-sm-7">
                            <textarea class="form-control" rows="5"><?= $get_data_header_by_nodo_keluar->alamat; ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <?php echo form_open($url); ?>

            <input type="hidden" name="nodo" value="<?= $get_data_header_by_nodo_keluar->nodo; ?>">
            <input type="hidden" name="nopo" value="<?= $get_data_header_by_nodo_keluar->nopo; ?>">
            <input type="hidden" name="site_code" value="<?= $get_data_header_by_nodo_keluar->kode_alamat; ?>">
            <input type="hidden" name="branch_name" value="<?= $get_data_header_by_nodo_keluar->branch_name; ?>">
            <input type="hidden" name="nama_comp" value="<?= $get_data_header_by_nodo_keluar->nama_comp; ?>">


            <input type="hidden" name="kode" value="<?= $get_data_header_by_nodo_keluar->kode; ?>">
            <input type="hidden" name="tgldo" value="<?= $get_data_header_by_nodo_keluar->tgldo; ?>">
            <input type="hidden" name="tglpo" value="<?= $get_data_header_by_nodo_keluar->tglpo; ?>">
            <input type="hidden" name="tipe" value="<?= $get_data_header_by_nodo_keluar->tipe; ?>">
            <input type="hidden" name="company" value="<?= $get_data_header_by_nodo_keluar->company; ?>">
            <input type="hidden" name="kode_alamat" value="<?= $get_data_header_by_nodo_keluar->kode_alamat; ?>">
            <input type="hidden" name="alamat" value="<?= $get_data_header_by_nodo_keluar->alamat; ?>">
            <input type="hidden" name="signature" value="<?= $get_data_header_by_nodo_keluar->signature; ?>">



            <div class="dt-responsive table-responsive mt-4">
                <table id="table-cart" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th width="1%">Kodeprod</th>
                            <th width="50%">Namaprod</th>
                            <th width="1%">Qty Kirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>


                        <?php foreach ($get_data_detail_by_nodo_keluar as $key) : ?>
                            <tr>
                                <td><?= $key->kodeprod; ?>
                                    <input type="hidden" name="kodeprod[]" value="<?= $key->kodeprod; ?>">
                                </td>
                                <td><?= $key->namaprod; ?>
                                    <input type="hidden" name="namaprod[]" value="<?= $key->namaprod; ?>">
                                </td>
                                <td><?= $key->banyak; ?>
                                    <input type="hidden" class="form-control" name="qty_diterima[]" value="<?= $key->banyak; ?>">
                                </td>
                                <input type="hidden" name="id[]" value="<?= $key->id; ?>">
                            </tr>
                        <?php endforeach; ?>



                    </tbody>
                </table>

            </div>

            <hr>

            <a href="<?= base_url(); ?>dc/dashboard" class="btn btn-dark">Kembali</a>
            <?php echo form_submit('submit', 'Save Barang Keluar', 'class="btn btn-primary" required'); ?>
            <?php echo form_close(); ?>

        </div>
    </div>
</div>