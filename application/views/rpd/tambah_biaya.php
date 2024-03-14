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

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Kode RPD</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Status Approval</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tanggal Berangkat</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tempat Berangkat</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tanggal Tiba</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Tempat Tujuan</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Maksud Perjalanan Dinas</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Pelaksana</label>
                        <div class="col-sm-7">
                            <input type="text" name="nopo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Keterangan</label>
                        <div class="col-sm-7">
                            <textarea name="" id="" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>


            <hr>

            <button class="btn btn-primary btn-primary" onclick="addRpd()"><i class="fa fa-plus"></i>Tambah
                Biaya</button>


            <div class="dt-responsive table-responsive mt-4">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Biaya</th>
                            <th>Nominal RP</th>
                            <th>Keterangan</th>
                            <th>
                                <center>#
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($get_biaya as $key) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?php echo $key->jenis_biaya; ?></td>
                            <td><?php echo $key->nominal_rp; ?></td>
                            <td><?php echo $key->keterangan; ?></td>
                            <td>

                                <?php
                                    echo anchor(
                                        'biaya_operasional/delete_biaya/' . md5($key->id),
                                        'delete',
                                        array(
                                            'class' => 'btn btn-danger btn-sm',
                                            'onclick' => 'return confirm(\'Are you sure?\')'
                                        )
                                    );
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