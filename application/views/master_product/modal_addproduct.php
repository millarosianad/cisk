<?php $id = $this->session->userdata('id'); ?>
<div class="modal fade " id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Tambah Product</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php echo form_open('master_product/tambah_product/'); ?>
            <div class="modal-body">
                <input type="hidden" id="hdnSession" data-value=<?= $id; ?> />
                <div class="form-group row">
                    <label class="col-sm-4">Kodeprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kodeprod" minlength='6' maxlength='6' required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Namaprod</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namaprod" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Nama Invoice</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="namainvoice" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Kode PRC</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prc" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Supplier</label>
                    <div class="col-sm-6">
                        <?php
                        foreach ($suppq->result() as $value) {
                            $supp[$value->supp] = $value->namasupp;
                        }
                        echo form_dropdown('supp', $supp, '', 'id="kode_supp" class="form-control" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group</label>
                    <div class="col-sm-6">
                        <select name="group" class="form-control" id="group">
                            <option value="0"></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sub Group</label>
                    <div class="col-sm-6">
                        <select name="s_group" class="form-control" id="sub_group">
                            <option value="0"></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row kodedelto">
                    <label class="col-sm-4">Kodeprod Deltomed</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="kd_delto" id="kode_deltomed" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Unit</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="unit" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Order Unit</label>
                    <div class="col-sm-6">
                        <?php
                        foreach ($jenis->result() as $o_unit) {
                            $odr_unit[$o_unit->odrunit] = $o_unit->odrunit;
                        }
                        echo form_dropdown('odr_unit', $odr_unit, '', 'class="form-control" required');
                        ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Berat</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="berat" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Volume</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="volume" required />
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-4">Divisi Id</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="divisi" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_besar" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Besar</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_besar" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Sedang</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_sedang" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Sedang</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_sedang" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Sat. Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sat_kecil" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Qty Kecil</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="qty_kecil" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Beli</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_beli" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Grosir</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_grosir" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Motoris Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_motoris_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Karoseri</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="karoseri" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Ppnbm</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="ppnbm" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Locker</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="locker" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Dimensi 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="dimensi_1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Dimensi 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="dimensi_2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Pallete 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pallete_1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Pallete 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pallete_2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Barcode</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="barcode" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Id Supplier</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prodid_supplier" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Supplier</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prod_supplier" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group 3</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_3" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Buffer Stock</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="buffer_stock" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Status</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="status" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Status PO</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="status_po" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Free Tax</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="free_tax" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 1</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_1" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 2</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_2" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Harga Toko 3</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="hrg_toko_3" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Group Id Target</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="group_id_target" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Product Focus</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="prod_focus" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Grosir</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_grosir" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Discount Motoris Ritel</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="disc_motoris_ritel" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Image</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="image" required />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4">Deskripsi</label>
                    <div class="col-sm-6">
                        <textarea name="deskripsi" rows="7" cols="50" class="form-control" required></textarea>
                    </div>
                </div>
                <div id="apps">
                    <hr>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Namaprod</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apps_namaprod" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Deskripsi</label>
                        <div class="col-sm-6">
                            <!-- <input class="form-control" type="text" name="berat"/> -->
                            <textarea name="apps_deskripsi" rows="7" cols="50" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Image</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apps_image" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Konversi Sedang ke Kecil</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apps_konversi" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Min Pembelian</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" name="apps_min_pembelian" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Satuan Online</label>
                        <div class="col-sm-6">
                            <select name="apps_satuan" class="form-control">
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
                            <select name="apps_kategori" class="form-control">
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
                            <input class="form-control" type="number" name="apps_urutan" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Status Aktif</label>
                        <div class="col-sm-6">
                            <input id="checkbox1" type="checkbox" name="apps_aktif" value="1">
                            <label>Aktif</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Status Promosi Coret</label>
                        <div class="col-sm-6">
                            <input id="checkbox2" type="checkbox" name="apps_promo_coret" value="1">
                            <label>Aktif</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Status Terbaru</label>
                        <div class="col-sm-6">
                            <input id="checkbox3" type="checkbox" name="apps_terbaru" value="1">
                            <label>Aktif</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Status Terlaris</label>
                        <div class="col-sm-6">
                            <input id="checkbox4" type="checkbox" name="apps_terlaris" value="1">
                            <label>Aktif</label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Apps Status Herbana</label>
                        <div class="col-sm-6">
                            <input id="checkbox5" type="checkbox" name="apps_status_herbana" value="1">
                            <label>Aktif</label>
                        </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        $("#kode_supp").click(function() {
            $.ajax({
                url: "<?php echo base_url(); ?>master_product/build_group",
                data: {
                    kode_supp: $(this).val()
                },
                type: "POST",
                success: function(response) {
                    console.log(response.grup);
                    $("#group").html(response);
                    $('#sub_group').html('');
                }
            });
        });

        $("#group").click(function() {
            $.ajax({
                url: "<?php echo base_url(); ?>master_product/build_subgroup",
                data: {
                    grup: $(this).val()
                },
                type: "POST",
                success: function(data) {
                    $("#sub_group").html(data);
                }
            });
        });
    });

    $(document).ready(function() {
        $("select#kode_supp").change(function() {
            var selectedLocation = $(this).children("option:selected").val();
            if (selectedLocation == '001') {
                $('.kodedelto').show()
            } else {
                $('.kodedelto').hide()
                $("input#kode_deltomed").removeAttr("required")
            }
        });

        // var session=  $("#hdnSession").data('value');
        // // alert(session);
        // if (session != 547){
        //     $("#apps").hide();
        // }else{
        //     $("#apps").show();
        // }
    });
</script>