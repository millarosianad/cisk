<?php echo form_open($url); ?>

<div class="card">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Awal</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_1" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Periode Akhir</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <input class="form-control" type="date" name="periode_2" required />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit', 'Proses', '" class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <?php echo form_open_multipart($url_update); ?>

    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-12">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th width="1"><font size="1px"><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>
                            <th width="100%"><font size="2px">noseri_beli</th>
                            <th width="100%"><font size="2px">noseri</th>
                            <th width="100%"><font size="2px">company</th>
                            <th width="100%"><font size="2px">nopo</th>
                            <th width="100%"><font size="2px">nodo</th>
                            <th width="100%"><font size="2px">nodo_beli</th>
                            <th width="100%"><font size="2px">alasan</th>
                            <th width="100%"><font size="2px">no_relokasi</th>
                        </tr>
                    </thead>
                    <tbody>                                        
                        <?php foreach ($get_header_retur->result() as $a) : ?>
                        <tr>
                            <td>
                                <center>
                                    <input type="checkbox" id="<?php echo $a->id; ?>" name="options[]" class = "<?php echo $a->id; ?>" value="<?php echo $a->id; ?>">
                                </center>
                            </td>
                            <td><font size="1px"><?= $a->noseri_beli; ?></td>
                            <td><font size="1px"><?= $a->noseri; ?></td>
                            <td><font size="1px"><?= $a->company; ?></td>
                            <td><font size="1px"><?= $a->nopo; ?></td>
                            <td><font size="1px"><?= $a->nodo; ?></td>
                            <td><font size="1px"><?= $a->nodo_beli; ?></td>
                            <td><font size="1px"><?= $a->alasan; ?></td>
                            <td><font size="1px"><?= $a->no_ajuan_relokasi; ?></td>
                        </tr>
                        <?php endforeach; ?>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">Alasan</label>
            <div class="col-sm-5">
                <select name="alasan" class="form-control" required>
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">Relokasi</label>
            <div class="col-sm-5">
                <select name="relokasi" class="form-control" >
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">From to</label>
            <div class="col-sm-5">
                <select name="from_to" class="form-control" >
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">Tanggal Pengajuan</label>
            <div class="col-sm-5">
                <select name="tanggal_pengajuan" class="form-control" >
                </select>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">PIC</label>
            <div class="col-sm-5">
                <select name="pic" class="form-control" >
                </select>
            </div>
        </div>
    </div>

    <input type="hidden" name="periode_1" value="<?= $periode_1; ?>">
    <input type="hidden" name="periode_2" value="<?= $periode_2; ?>">

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2">&nbsp;</label>
            <div class="col-sm-5">
                <?php echo form_submit('submit', 'Update Alasan Retur', 'class="btn btn-success" required'); ?>
                <?php echo form_close(); ?>
                <a href="<?= base_url().'retur/manage_alasan' ?>" target="_blank" class="btn btn-dark">manage alasan</a>
            </div>
        </div>
    </div>

</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/alasan_retur') ?>',
        data: '',
        success: function(hasil_alasan) {
            $("select[name = alasan]").html(hasil_alasan);
        }
    });
</script>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/relokasi') ?>',
        data: '',
        success: function(hasil_relokasi) {
            $("select[name = relokasi]").html(hasil_relokasi);
        }
    });

    $("select[name = relokasi]").on("change", function() {
        var relokasi_terpilih = $("option:selected", this).attr("signature_relokasi");
        console.log(relokasi_terpilih)
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/relokasi_from_to') ?>',
            data: 'signature_relokasi=' + relokasi_terpilih,
            success: function(hasil_from_to) {
                console.log(hasil_from_to);
                $("select[name = from_to]").html(hasil_from_to);
            }
        });

    });

    $("select[name = relokasi]").on("change", function() {
        var relokasi_terpilih = $("option:selected", this).attr("signature_relokasi");
        console.log(relokasi_terpilih)
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/relokasi_tanggal_pengajuan') ?>',
            data: 'signature_relokasi=' + relokasi_terpilih,
            success: function(hasil_from_to) {
                console.log(hasil_from_to);
                $("select[name = tanggal_pengajuan]").html(hasil_from_to);
            }
        });

    });

    $("select[name = relokasi]").on("change", function() {
        var relokasi_terpilih = $("option:selected", this).attr("signature_relokasi");
        console.log(relokasi_terpilih)
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/relokasi_pic') ?>',
            data: 'signature_relokasi=' + relokasi_terpilih,
            success: function(hasil_from_to) {
                console.log(hasil_from_to);
                $("select[name = pic]").html(hasil_from_to);
            }
        });

    });

</script>

