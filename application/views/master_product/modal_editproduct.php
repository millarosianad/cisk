<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Edit Product</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('master_product/edit_product/'); ?>
            <div class="modal-body">
                <!-- <p id="loadingImage" style="font-size: 60px; display: none ">Loading ...</p> -->

                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" minlength='6' maxlength='6' id="kodeprod" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="namaprod" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Nama Invoice</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namainvoice" id="namainvoice" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Kode PRC</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prc" id="prc" required readonly />
                    </div>
                </div>
                <div class="form-group row kodedelto">
                    <label class="col-sm-4">Kodeprod Deltomed</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kd_delto" id="kode_deltomed" required />
                    </div>
                </div>
                <!-- <div class="form-group row">
                <label class="col-sm-4">Supplier</label>
                <div class="col-sm-6">
                <?php
                foreach ($suppq->result() as $value) {
                    $supp[$value->supp] = $value->namasupp;
                }
                echo form_dropdown('supp', $supp, '', 'id="kode_supp2" class="form-control"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Group</label>
                <div class="col-sm-6">
                <?php
                foreach ($group->result() as $value) {
                    $grup[$value->kode_group] = $value->nama_group;
                }
                echo form_dropdown('group', $grup, '', ' id="group2" class="form-control"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub Group</label>
                <div class="col-sm-6">
                <?php
                foreach ($subgroup->result() as $value) {
                    $s_group[$value->sub_group] = $value->nama_sub_group;
                }
                echo form_dropdown('s_group', $s_group, '', 'id="sub_group2" class="form-control"');
                ?>
                </div>
            </div> -->
                <div class="form-group row">
                    <label class="col-sm-4">Unit</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="unit" id="unit" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Order Unit</label>
                    <div class="col-sm-6">
                        <?php
                        foreach ($jenis->result() as $o_unit) {
                            $odr_unit[$o_unit->odrunit] = $o_unit->odrunit;
                        }
                        echo form_dropdown('odr_unit', $odr_unit, '', 'class="form-control" id="odr_unit" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Berat</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="berat" id="berat" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Volume</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="volume" id="volume" />
                    </div>
                </div>
                <input type="hidden" name="supp" id="kode_supp2" value="">
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_besar" id="besar" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_besar" id="qty1" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Sedang</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_sedang" id="sedang" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Sedang</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_sedang" id="qty2" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_kecil" id="kecil" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_kecil" id="qty3" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success"'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function getEditIDProduct(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            beforeSend: function() {
                // setting a timeout
                $('#loadingImage').show();

            },
            success: function(response) {
                console.log(response.edit);
                var kodesupp = response.edit.supp
                if (kodesupp == '001') {
                    $('.kodedelto').show()
                } else {
                    $('.kodedelto').hide()
                    $("input#kode_deltomed").removeAttr("required")
                }
                // $('#loadingImage').hide();
                $("#edit").modal() // Buka Modal
                $('#kodeprod').val(params) // parameter
                $('#namaprod').val(response.edit.namaprod)
                $('#namainvoice').val(response.edit.namainvoice)
                $('#prc').val(response.edit.kode_prc)
                $('#kode_deltomed').val(response.edit.kodeprod_deltomed)
                $('#unit').val(response.edit.isisatuan)
                $('#odr_unit').val(response.edit.odrunit)
                $('#group2').val(response.edit.grup)
                $('#sub_group2').val(response.edit.subgroup)
                $("#kode_supp2").val(response.edit.supp)
                $("#b_kecil").val(response.edit.berat_gr)
                $("#berat").val(response.edit.berat)
                $("#volume").val(response.edit.volume).change();
            }
        });

        $(document).ready(function() {
            $("#kode_supp2").click(function() {
                $.ajax({
                    url: "<?php echo base_url(); ?>master_product/build_group",
                    data: {
                        kode_supp: $(this).val()
                    },
                    type: "POST",
                    success: function(data) {
                        $("#group2").html(data);
                        $('#sub_group2').html('');
                    }
                });
            });

            $("#group2").click(function() {
                $.ajax({
                    url: "<?php echo base_url(); ?>master_product/build_subgroup",
                    data: {
                        grup: $(this).val()
                    },
                    type: "POST",
                    success: function(datas) {
                        $("#sub_group2").html(datas);
                    }
                });
            });
        });
    }
</script>