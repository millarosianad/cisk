<div class="modal fade" id="edit_apps" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Edit Product</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('master_product/edit_product_apps/'); ?>
            <div class="modal-body">
                <!-- <p id="loadingImage" style="font-size: 60px; display: none ">Loading ...</p> -->

                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" minlength='6' maxlength='6' id="kodeprod_apps" required readonly />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" id="namaprod" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Nama Invoice</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namainvoice" id="namainvoice" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Kode PRC</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prc" id="prc" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Supplier</label>
                    <div class="col-sm-6">
                        <?php
                        foreach ($suppq->result() as $value) {
                            $supp[$value->supp] = $value->namasupp;
                        }
                        echo form_dropdown('supp', $supp, '', 'id="kode_supp2" class="form-control" required');
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
                        echo form_dropdown('group', $grup, '', ' id="group_2" class="form-control"');
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
                </div>
                <div class="form-group row kodedelto">
                    <label class="col-sm-4">Kodeprod Deltomed</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kd_delto" id="kode_deltomed2" required />
                    </div>
                </div>
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
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4">Divisi Id</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="divisi" id="divisiid" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_besar" id="besar" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_besar" id="qty1" required />
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
                        <input class="form-control" type="text" name="sat_kecil" id="kecil" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_kecil" id="qty3" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_beli" id="beli" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Grosir</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_grosir" id="h_grosir" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_ritel" id="h_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Motoris Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_motoris_ritel" id="h_motoris_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Karoseri</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="karoseri" id="karoseri" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Ppnbm</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ppnbm" id="ppnbm" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Locker</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="locker" id="locker" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Dimensi 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="dimensi_1" id="dimensi1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Dimensi 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="dimensi_2" id="dimensi2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Pallete 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pallete_1" id="pallete1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Pallete 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pallete_2" id="pallete2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Barcode</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="barcode" id="barcode" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Id Supplier</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prodid_supplier" id="product_id_supplier" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Supplier</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prod_supplier" id="product_supplier" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_1" id="group1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_2" id="group2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 3</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_3" id="group3" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Buffer Stock</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="buffer_stock" id="buffer_stock" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Status</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="status" id="status" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Status PO</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="status_po" id="status_po" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Free Tax</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="free_tax" id="free_tax" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_1" id="harga_toko1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_2" id="harga_toko2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 3</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_3" id="harga_toko3" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group Id Target</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_id_target" id="group_id_target" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Focus</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prod_focus" id="product_focus" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Grosir</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_grosir" id="discount_grosir" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_ritel" id="discount_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Motoris Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_motoris_ritel" id="discount_motoris_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Image</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="image" id="image" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Deskripsi</label>
                    <div class="col-sm-6">
                        <textarea name="deskripsi" rows="7" cols="50" id="deskripsi" required></textarea>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="apps_namaprod" id="apps_namaprod" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Deskripsi</label>
                    <div class="col-sm-6">
                        <!-- <input class="form-control" type="text" name="berat"/> -->
                        <textarea name="apps_deskripsi" id="apps_deskripsi" rows="7" cols="50"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Images</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="apps_image" id="apps_images" />
                        <br>
                        <img alt="" id="img_view" style='max-width: 60%;'>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Konversi Sedang ke Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="apps_konversi" id="apps_konversi" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Min Pembelian</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="apps_min_pembelian" id="apps_min_pembelian" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Satuan Online</label>
                    <div class="col-sm-6">
                        <select name="apps_satuan" class="form-control" id="apps_satuan">
                            <option value=""></option>
                            <option value="AMPLOP">AMPLOP</option>
                            <option value="BAG">BAG</option>
                            <option value="BLISTER">BLISTER</option>
                            <option value="BOTOL">BOTOL</option>
                            <option value="BOX">BOX</option>
                            <option value="BUNDLING">BUNDLING</option>
                            <option value="DUS">DUS</option>
                            <option value="PACK">PACK</option>
                            <option value="PAK">PAK</option>
                            <option value="PCS">PCS</option>
                            <option value="POT">POT</option>
                            <option value="RENCENG">RENCENG</option>
                            <option value="RENTENG">RENTENG</option>
                            <option value="SACHET">SACHET</option>
                            <option value="STRIP">STRIP</option>
                            <option value="TOPLES">TOPLES</option>
                            <option value="TUBE">TUBE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Kategori</label>
                    <div class="col-sm-6">
                        <select name="apps_kategori" class="form-control" id="apps_kategori" required>
                            <option value=""></option>
                            <option value="FOOD & BEV">FOOD & BEV</option>
                            <option value="HERBAL">HERBAL</option>
                            <option value="MINYAK ANGIN & BALSAM">MINYAK ANGIN & BALSAM</option>
                            <option value="SUPPLEMEN & MULTIVITAMIN">SUPPLEMEN & MULTIVITAMIN</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Urutan</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="apps_urutan" id="apps_urutan" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Status Aktif</label>
                    <div class="col-sm-6">
                        <!-- <input id="checkbox1" type="checkbox" name="apps_aktif" value="1"> -->

                        <input id="apps_aktif" type="checkbox" name="apps_aktif" value="1">

                        <label>Aktif</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Status Promosi Coret</label>
                    <div class="col-sm-6">
                        <input id="apps_promosi_coret" type="checkbox" name="apps_promo_coret" value="1">
                        <label>Aktif</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Status Terbaru</label>
                    <div class="col-sm-6">
                        <input id="apps_terbaru" type="checkbox" name="apps_terbaru" value="1">
                        <label>Aktif</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Status Terlaris</label>
                    <div class="col-sm-6">
                        <input id="apps_terlaris" type="checkbox" name="apps_terlaris" value="1">
                        <label>Aktif</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Apps Status Herbana</label>
                    <div class="col-sm-6">
                        <input id="apps_status_herbana" type="checkbox" name="apps_status_herbana" value="1">
                        <label>Aktif</label>
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
    function getEditIDProduct_Apps(params) {
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
                var aktif = response.edit.apps_status_aktif
                var promosi = response.edit.apps_status_promosi_coret
                var terbaru = response.edit.apps_status_terbaru
                var terlaris = response.edit.apps_status_terlaris
                var herbana = response.edit.apps_status_herbana

                if (aktif == 1) {
                    $('#apps_aktif').prop('checked', true);
                } else {
                    $('#apps_aktif').prop('checked', false);
                }
                if (promosi == 1) {
                    $('#apps_promosi_coret').prop('checked', true);
                } else {
                    $('#apps_promosi_coret').prop('checked', false);
                }
                if (terbaru == 1) {
                    $('#apps_terbaru').prop('checked', true);
                } else {
                    $('#apps_terbaru').prop('checked', false);
                }
                if (terlaris == 1) {
                    $('#apps_terlaris').prop('checked', true);
                } else {
                    $('#apps_terlaris').prop('checked', false);
                }
                if (herbana == 1) {
                    $('#apps_status_herbana').prop('checked', true);
                } else {
                    $('#apps_status_herbana').prop('checked', false);
                }
                var kodesupp = response.edit.supp
                if (kodesupp == '001') {
                    $('.kodedelto').show()
                } else {
                    $('.kodedelto').hide()
                    $("input#kode_deltomed2").removeAttr("required")
                }
                // $('#loadingImage').hide();
                $("#edit_apps").modal() // Buka Modal
                $('#kodeprod_apps').val(params) // parameter
                $('#namaprod').val(response.edit.namaprod)
                $('#namainvoice').val(response.edit.namainvoice)
                $('#prc').val(response.edit.kode_prc)
                $('#kode_deltomed2').val(response.edit.kodeprod_deltomed)
                $('#unit').val(response.edit.isisatuan)
                $('#odr_unit').val(response.edit.odrunit)
                $('#group_2').val(response.edit.grup)
                $('#sub_group2').val(response.edit.subgroup)
                $("#kode_supp2").val(response.edit.supp)
                $("#b_kecil").val(response.edit.berat_gr)
                $("#berat").val(response.edit.berat)
                $("#volume").val(response.edit.volume).change();
                $("#apps_namaprod").val(response.edit.apps_namaprod)
                $("#apps_deskripsi").val(response.edit.apps_deskripsi)
                $("#apps_images").val(response.edit.apps_images)
                $("#img_view").attr('src', response.edit.apps_images)
                $("#apps_konversi").val(response.edit.apps_konversi_sedang_ke_kecil)
                $("#apps_min_pembelian").val(response.edit.apps_min_pembelian)
                $("#apps_satuan").val(response.edit.apps_satuan_online)
                $("#apps_kategori").val(response.edit.apps_kategori_online)
                $("#apps_urutan").val(response.edit.apps_urutan)
                $("#divisiid").val(response.edit.divisiid)
                $("#beli").val(response.edit.beli)
                $("#h_grosir").val(response.edit.h_grosir)
                $("#h_ritel").val(response.edit.h_ritel)
                $("#h_motoris_ritel").val(response.edit.h_motoris_ritel)
                $("#karoseri").val(response.edit.karoseri)
                $("#ppnbm").val(response.edit.ppnbm)
                $("#locker").val(response.edit.locker)
                $("#qty1").val(response.edit.qty1)
                $("#qty2").val(response.edit.qty2)
                $("#qty3").val(response.edit.qty3)
                $("#besar").val(response.edit.besar)
                $("#sedang").val(response.edit.dimensi1)
                $("#kecil").val(response.edit.kecil)
                $("#dimensi1").val(response.edit.dimensi1)
                $("#dimensi2").val(response.edit.dimensi2)
                $("#pallete1").val(response.edit.pallete1)
                $("#pallete2").val(response.edit.pallete2)
                $("#barcode").val(response.edit.barcode)
                $("#product_supplier").val(response.edit.product_supplier)
                $("#product_id_supplier").val(response.edit.product_id_supplier)
                $("#group1").val(response.edit.group1)
                $("#group2").val(response.edit.group2)
                $("#group3").val(response.edit.group3)
                $("#buffer_stock").val(response.edit.beli)
                $("#status_po").val(response.edit.status_po)
                $("#status").val(response.edit.status)
                $("#image").val(response.edit.image)
                $("#deskripsi").val(response.edit.deskripsi)
                $("#free_tax").val(response.edit.free_tax)
                $("#harga_toko1").val(response.edit.harga_toko1)
                $("#harga_toko2").val(response.edit.harga_toko2)
                $("#harga_toko3").val(response.edit.harga_toko3)
                $("#group_id_target").val(response.edit.group_id_target)
                $("#product_focus").val(response.edit.product_focus)
                $("#discount_grosir").val(response.edit.discount_grosir)
                $("#discount_ritel").val(response.edit.discount_ritel)
                $("#discount_motoris_ritel").val(response.edit.discount_motoris_ritel)
                .change();
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

            $("select#kode_supp2").change(function() {
                var selectedLocation = $(this).children("option:selected").val();
                if (selectedLocation == '001') {
                    $('.kodedelto').show()
                } else {
                    $('.kodedelto').hide()
                }
            });
        });
    }
</script>