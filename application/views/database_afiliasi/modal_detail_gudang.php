<?php
$required = "";

?>
<!-- modal detail gudang -->
<div class="modal fade" id="tambah_detail_gudang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Detail Gudang</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open($url_detail_gudang); ?>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4">Sub Branch</label>
                    <div class="col-sm-8">
                        <select name="site_code" id="site_code_dtl" class="form-control" <?= $required; ?>>
                        </select>
                        <input class="form-control" type="text" name="id_gudang" id="id_dtl" hidden>
                    </div>
                </div>
                <hr><br>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang (m2)
                                <input class="form-control" type="number" name="panjang_gudang" id="panjang_gudang_dtl"
                                    <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar (m2)
                                <input class="form-control" type="number" name="lebar_gudang" id="lebar_gudang_dtl"
                                    <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang" id="pallet_gudang_dtl"
                                    <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking (m2)
                                <input class="form-control" type="number" name="racking_gudang" id="racking_gudang_dtl"
                                    <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Baik - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_gudang_baik"
                                    id="panjang_gudang_baik_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_gudang_baik"
                                    id="lebar_gudang_baik_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Baik - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang_baik"
                                    id="pallet_gudang_baik_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking
                                <input class="form-control" type="number" name="racking_gudang_baik"
                                    id="racking_gudang_baik_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Retur - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_gudang_retur"
                                    id="panjang_gudang_retur_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_gudang_retur"
                                    id="lebar_gudang_retur_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Retur - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang_retur"
                                    id="pallet_gudang_retur_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking
                                <input class="form-control" type="number" name="racking_gudang_retur"
                                    id="racking_gudang_retur_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Karantina - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_gudang_karantina"
                                    id="panjang_gudang_karantina_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_gudang_karantina"
                                    id="lebar_gudang_karantina_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Karantina - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang_karantina"
                                    id="pallet_gudang_karantina_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking
                                <input class="form-control" type="number" name="racking_gudang_karantina"
                                    id="racking_gudang_karantina_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang AC - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_gudang_ac"
                                    id="panjang_gudang_ac_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_gudang_ac"
                                    id="lebar_gudang_ac_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang AC - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang_ac"
                                    id="pallet_gudang_ac_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking
                                <input class="form-control" type="number" name="racking_gudang_ac"
                                    id="racking_gudang_ac_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Loading Dock - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_loading_dock"
                                    id="panjang_loading_dock_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_loading_dock"
                                    id="lebar_loading_dock_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Loading Dock - in pallet</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">total pallet
                                <input class="form-control" type="number" name="pallet_gudang_loading"
                                    id="pallet_gudang_loading_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">racking
                                <input class="form-control" type="number" name="racking_gudang_loading"
                                    id="racking_gudang_loading_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-4">Jumlah Alat Kerja</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">pallet
                                <input class="form-control" type="number" name="jumlah_pallet" id="jumlah_pallet_dtl"
                                    <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">hand pallet
                                <input class="form-control" type="number" name="jumlah_hand_pallet"
                                    id="jumlah_hand_pallet_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">trolley
                                <input class="form-control" type="number" name="jumlah_trolley" id="jumlah_trolley_dtl"
                                    <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">sealer
                                <input class="form-control" type="number" name="jumlah_sealer" id="jumlah_sealer_dtl"
                                    <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Sirkulasi Udara Gudang Baik</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">ac
                                <input class="form-control" type="number" name="jumlah_ac" id="jumlah_ac_dtl"
                                    <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">exhaust fan
                                <input class="form-control" type="number" name="jumlah_exhaust_fan"
                                    id="jumlah_exhaust_fan_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">kipas angin
                                <input class="form-control" type="number" name="jumlah_kipas_angin"
                                    id="jumlah_kipas_angin_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Kantor Div Logistik</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang (m2)
                                <input class="form-control" type="number" name="panjang_kantor_div_logistik"
                                    id="panjang_kantor_div_logistik_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar (m2)
                                <input class="form-control" type="number" name="lebar_kantor_div_logistik"
                                    id="lebar_kantor_div_logistik_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-3"><i><b>Jumlah Armada Logistik</b></i></div>

                <div class="form-group row">
                    <label class="col-sm-4">Mobil Penumpang</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">sewa
                                <input class="form-control" type="number" name="jumlah_mobil_penumpang_sewa"
                                    id="jumlah_mobil_penumpang_sewa_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">milik sendiri
                                <input class="form-control" type="number" name="jumlah_mobil_penumpang_milik_sendiri"
                                    id="jumlah_mobil_penumpang_milik_sendiri_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Mobil Pengiriman</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">blind van
                                <input class="form-control" type="number" name="jumlah_mobil_pengiriman_blind_van"
                                    id="jumlah_mobil_pengiriman_blind_van_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">engkel
                                <input class="form-control" type="number" name="jumlah_mobil_pengiriman_engkel"
                                    id="jumlah_mobil_pengiriman_engkel_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">double
                                <input class="form-control" type="number" name="jumlah_mobil_pengiriman_double"
                                    id="jumlah_mobil_pengiriman_double_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Blind Van</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">sewa
                                <input class="form-control" type="number" name="jumlah_blind_van_sewa"
                                    id="jumlah_blind_van_sewa_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">milik sendiri
                                <input class="form-control" type="number" name="jumlah_blind_van_milik_sendiri"
                                    id="jumlah_blind_van_milik_sendiri_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Engkel</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">sewa
                                <input class="form-control" type="number" name="jumlah_engkel_sewa"
                                    id="jumlah_engkel_sewa_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">milik sendiri
                                <input class="form-control" type="number" name="jumlah_engkel_milik_sendiri"
                                    id="jumlah_engkel_milik_sendiri_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Double</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">sewa
                                <input class="form-control" type="number" name="jumlah_double_sewa"
                                    id="jumlah_double_sewa_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">milik sendiri
                                <input class="form-control" type="number" name="jumlah_double_milik_sendiri"
                                    id="jumlah_double_milik_sendiri_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Motor Pengiriman</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">sewa
                                <input class="form-control" type="number" name="jumlah_motor_pengiriman_sewa"
                                    id="jumlah_motor_pengiriman_sewa_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">milik sendiri
                                <input class="form-control" type="number" name="jumlah_motor_pengiriman_milik_sendiri"
                                    id="jumlah_motor_pengiriman_milik_sendiri_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Saddle Bag</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">dipakai
                                <input class="form-control" type="number" name="jumlah_saddle_bag_dipakai"
                                    id="jumlah_saddle_bag_dipakai_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">cadangan
                                <input class="form-control" type="number" name="jumlah_saddle_bag_cadangan"
                                    id="jumlah_saddle_bag_cadangan_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Kantor Total - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_kantor_total"
                                    id="panjang_kantor_total_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_kantor_total"
                                    id="lebar_kantor_total_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Ruang Sales - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_ruang_sales"
                                    id="panjang_ruang_sales_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_ruang_sales"
                                    id="lebar_ruang_sales_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Ruang Finance (m2)</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_ruang_finance"
                                    id="panjang_ruang_finance_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_ruang_finance"
                                    id="lebar_ruang_finance_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Ruang Logistic - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_ruang_logistik"
                                    id="panjang_ruang_logistik_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_ruang_logistik"
                                    id="lebar_ruang_logistik_dtl" <?= $required; ?> />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4">Luas Gudang Arsip - in meter2</label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-4">panjang
                                <input class="form-control" type="number" name="panjang_gudang_arsip"
                                    id="panjang_gudang_arsip_dtl" <?= $required; ?> />
                            </div>
                            <div class="col-sm-4">lebar
                                <input class="form-control" type="number" name="lebar_gudang_arsip"
                                    id="lebar_gudang_arsip_dtl" <?= $required; ?> />
                            </div>
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

<script>
    function addDetail() {
        $.ajax({
            success: function (response) {
                console.log(response.get_detail);
                $("#tambah_detail_gudang").modal() // Buka Modal
                $('#id_dtl').val('') // parameter
                $('#site_code_dtl').val('')
                $('#panjang_gudang_dtl').val('')
                $('#lebar_gudang_dtl').val('')
                $('#pallet_gudang_dtl').val('')
                $('#racking_gudang_dtl').val('')
                $('#panjang_gudang_baik_dtl').val('')
                $('#lebar_gudang_baik_dtl').val('')
                $('#pallet_gudang_baik_dtl').val('')
                $('#racking_gudang_baik_dtl').val('')
                $('#panjang_gudang_retur_dtl').val('')
                $('#lebar_gudang_retur_dtl').val('')
                $('#pallet_gudang_retur_dtl').val('')
                $('#racking_gudang_retur_dtl').val('')
                $('#panjang_gudang_karantina_dtl').val('')
                $('#lebar_gudang_karantina_dtl').val('')
                $('#pallet_gudang_karantina_dtl').val('')
                $('#racking_gudang_karantina_dtl').val('')
                $('#panjang_gudang_ac_dtl').val('')
                $('#lebar_gudang_ac_dtl').val('')
                $('#pallet_gudang_ac_dtl').val('')
                $('#racking_gudang_ac_dtl').val('')
                $('#panjang_loading_dock_dtl').val('')
                $('#lebar_loading_dock_dtl').val('')
                $('#pallet_gudang_loading_dtl').val('')
                $('#racking_gudang_loading_dtl').val('')
                $('#jumlah_pallet_dtl').val('')
                $('#jumlah_hand_pallet_dtl').val('')
                $('#jumlah_trolley_dtl').val('')
                $('#jumlah_sealer_dtl').val('')
                $('#jumlah_ac_dtl').val('')
                $('#jumlah_exhaust_fan_dtl').val('')
                $('#jumlah_kipas_angin_dtl').val('')
                $('#panjang_kantor_div_logistik_dtl').val('')
                $('#lebar_kantor_div_logistik_dtl').val('')
                $('#jumlah_mobil_penumpang_sewa_dtl').val('')
                $('#jumlah_mobil_penumpang_milik_sendiri_dtl').val('')
                $('#jumlah_mobil_pengiriman_blind_van_dtl').val('')
                $('#jumlah_mobil_pengiriman_engkel_dtl').val('')
                $('#jumlah_mobil_pengiriman_double_dtl').val('')
                $('#jumlah_blind_van_sewa_dtl').val('')
                $('#jumlah_blind_van_milik_sendiri_dtl').val('')
                $('#jumlah_engkel_sewa_dtl').val('')
                $('#jumlah_engkel_milik_sendiri_dtl').val('')
                $('#jumlah_double_sewa_dtl').val('')
                $('#jumlah_double_milik_sendiri_dtl').val('')
                $('#jumlah_motor_pengiriman_sewa_dtl').val('')
                $('#jumlah_motor_pengiriman_milik_sendiri_dtl').val('')
                $('#jumlah_saddle_bag_dipakai_dtl').val('')
                $('#jumlah_saddle_bag_cadangan_dtl').val('')
                $('#panjang_kantor_total_dtl').val('')
                $('#lebar_kantor_total_dtl').val('')
                $('#panjang_ruang_sales_dtl').val('')
                $('#lebar_ruang_sales_dtl').val('')
                $('#panjang_ruang_finance_dtl').val('')
                $('#lebar_ruang_finance_dtl').val('')
                $('#panjang_ruang_logistik_dtl').val('')
                $('#lebar_ruang_logistik_dtl').val('')
                $('#panjang_gudang_arsip_dtl').val('')
                $('#lebar_gudang_arsip_dtl').val('')
                .change();
            }
        });
    }

    function editDetail(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('database_afiliasi/get_dbafiliasi') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_detail);
                $("#tambah_detail_gudang").modal() // Buka Modal
                $('#id_dtl').val(params) // parameter
                $('#site_code_dtl').val(response.get_detail.site_code)
                $('#panjang_gudang_dtl').val(response.get_detail.panjang_gudang)
                $('#lebar_gudang_dtl').val(response.get_detail.lebar_gudang)
                $('#pallet_gudang_dtl').val(response.get_detail.pallet_gudang)
                $('#racking_gudang_dtl').val(response.get_detail.racking_gudang)
                $('#panjang_gudang_baik_dtl').val(response.get_detail.panjang_gudang_baik)
                $('#lebar_gudang_baik_dtl').val(response.get_detail.lebar_gudang_baik)
                $('#pallet_gudang_baik_dtl').val(response.get_detail.pallet_gudang_baik)
                $('#racking_gudang_baik_dtl').val(response.get_detail.racking_gudang_baik)
                $('#panjang_gudang_retur_dtl').val(response.get_detail.panjang_gudang_retur)
                $('#lebar_gudang_retur_dtl').val(response.get_detail.lebar_gudang_retur)
                $('#pallet_gudang_retur_dtl').val(response.get_detail.pallet_gudang_retur)
                $('#racking_gudang_retur_dtl').val(response.get_detail.racking_gudang_retur)
                $('#panjang_gudang_karantina_dtl').val(response.get_detail.panjang_gudang_karantina)
                $('#lebar_gudang_karantina_dtl').val(response.get_detail.lebar_gudang_karantina)
                $('#pallet_gudang_karantina_dtl').val(response.get_detail.pallet_gudang_karantina)
                $('#racking_gudang_karantina_dtl').val(response.get_detail.racking_gudang_karantina)
                $('#panjang_gudang_ac_dtl').val(response.get_detail.panjang_gudang_ac)
                $('#lebar_gudang_ac_dtl').val(response.get_detail.lebar_gudang_ac)
                $('#pallet_gudang_ac_dtl').val(response.get_detail.pallet_gudang_ac)
                $('#racking_gudang_ac_dtl').val(response.get_detail.racking_gudang_ac)
                $('#panjang_loading_dock_dtl').val(response.get_detail.panjang_loading_dock)
                $('#lebar_loading_dock_dtl').val(response.get_detail.lebar_loading_dock)
                $('#pallet_gudang_loading_dtl').val(response.get_detail.pallet_gudang_loading)
                $('#racking_gudang_loading_dtl').val(response.get_detail.racking_gudang_loading)
                $('#jumlah_pallet_dtl').val(response.get_detail.jumlah_pallet)
                $('#jumlah_hand_pallet_dtl').val(response.get_detail.jumlah_hand_pallet)
                $('#jumlah_trolley_dtl').val(response.get_detail.jumlah_trolley)
                $('#jumlah_sealer_dtl').val(response.get_detail.jumlah_sealer)
                $('#jumlah_ac_dtl').val(response.get_detail.jumlah_ac)
                $('#jumlah_exhaust_fan_dtl').val(response.get_detail.jumlah_exhaust_fan)
                $('#jumlah_kipas_angin_dtl').val(response.get_detail.jumlah_kipas_angin)
                $('#panjang_kantor_div_logistik_dtl').val(response.get_detail.panjang_kantor_div_logistik)
                $('#lebar_kantor_div_logistik_dtl').val(response.get_detail.lebar_kantor_div_logistik)
                $('#jumlah_mobil_penumpang_sewa_dtl').val(response.get_detail.jumlah_mobil_penumpang_sewa)
                $('#jumlah_mobil_penumpang_milik_sendiri_dtl').val(response.get_detail
                    .jumlah_mobil_penumpang_milik_sendiri)
                $('#jumlah_mobil_pengiriman_blind_van_dtl').val(response.get_detail
                    .jumlah_mobil_pengiriman_blind_van)
                $('#jumlah_mobil_pengiriman_engkel_dtl').val(response.get_detail
                    .jumlah_mobil_pengiriman_engkel)
                $('#jumlah_mobil_pengiriman_double_dtl').val(response.get_detail
                    .jumlah_mobil_pengiriman_double)
                $('#jumlah_blind_van_sewa_dtl').val(response.get_detail.jumlah_blind_van_sewa)
                $('#jumlah_blind_van_milik_sendiri_dtl').val(response.get_detail
                    .jumlah_blind_van_milik_sendiri)
                $('#jumlah_engkel_sewa_dtl').val(response.get_detail.jumlah_engkel_sewa)
                $('#jumlah_engkel_milik_sendiri_dtl').val(response.get_detail.jumlah_engkel_milik_sendiri)
                $('#jumlah_double_sewa_dtl').val(response.get_detail.jumlah_double_sewa)
                $('#jumlah_double_milik_sendiri_dtl').val(response.get_detail.jumlah_double_milik_sendiri)
                $('#jumlah_motor_pengiriman_sewa_dtl').val(response.get_detail.jumlah_motor_pengiriman_sewa)
                $('#jumlah_motor_pengiriman_milik_sendiri_dtl').val(response.get_detail
                    .jumlah_motor_pengiriman_milik_sendiri)
                $('#jumlah_saddle_bag_dipakai_dtl').val(response.get_detail.jumlah_saddle_bag_dipakai)
                $('#jumlah_saddle_bag_cadangan_dtl').val(response.get_detail.jumlah_saddle_bag_cadangan)
                $('#panjang_kantor_total_dtl').val(response.get_detail.panjang_kantor_total)
                $('#lebar_kantor_total_dtl').val(response.get_detail.lebar_kantor_total)
                $('#panjang_ruang_sales_dtl').val(response.get_detail.panjang_ruang_sales)
                $('#lebar_ruang_sales_dtl').val(response.get_detail.lebar_ruang_sales)
                $('#panjang_ruang_finance_dtl').val(response.get_detail.panjang_ruang_finance)
                $('#lebar_ruang_finance_dtl').val(response.get_detail.lebar_ruang_finance)
                $('#panjang_ruang_logistik_dtl').val(response.get_detail.panjang_ruang_logistik)
                $('#lebar_ruang_logistik_dtl').val(response.get_detail.lebar_ruang_logistik)
                $('#panjang_gudang_arsip_dtl').val(response.get_detail.panjang_gudang_arsip)
                $('#lebar_gudang_arsip_dtl').val(response.get_detail.lebar_gudang_arsip)
                .change();
            }
        });
    }
</script>