<?php

// $required = "";
$required = "";
$readonly = "readonly";

?>
<a href='<?= base_url()."database_afiliasi"?>' class="btn btn-dark btn-mat">Kembali</a>
<br><br>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <ul class="nav nav-tabs md-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab">Profile</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab">Detail
                        Gudang</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="karyawan-tab" data-toggle="tab" href="#karyawan" role="tab"
                        aria-controls="karyawan" aria-selected="false">Karyawan</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="niaga-tab" data-toggle="tab" href="#niaga" role="tab" aria-controls="niaga"
                        aria-selected="false">Niaga</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nonniaga-tab" data-toggle="tab" href="#nonniaga" role="tab"
                        aria-controls="nonniaga" aria-selected="false">Non Niaga</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="itasset-tab" data-toggle="tab" href="#itasset" role="tab"
                        aria-controls="itasset" aria-selected="false">IT Asset</a>
                    <div class="slide"></div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="asset-tab" data-toggle="tab" href="#asset" role="tab" aria-controls="asset"
                        aria-selected="false">Asset Kantor</a>
                    <div class="slide"></div>
                </li>
                <!-- <li class="nav-item">
                        <a class="nav-link" id="strukturorganisasi-tab" data-toggle="tab" href="#strukturorganisasi"
                            role="tab" aria-controls="strukturorganisasi" aria-selected="false">Struktur Organisasi</a>
                        <div class="slide"></div>
                    </li> -->
            </ul>

            <div class="tab-content" id="myTabContent">
                <br>
                <!-- profile -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form name="formProfile" action="<?= base_url().'database_afiliasi/preview_simpan'?>" method="post" onsubmit="return validateForm()"
                        enctype="multipart/form-data">
                        <div class="col-12">
                            <div class="form-group row">
                                <input name="id" value="<?= $get_preview_profile->id;?>" class="form-control input"
                                    hidden>
                                <input name="created_at" value="<?= $get_preview_profile->created_at;?>"
                                    class="form-control input" hidden>
                                <label class="col-sm-4">Sub Branch</label>
                                <div class="col-sm-8">
                                    <!-- <input class="form-control input" type="text" name="kodeprod" id="kodeprod" required > -->
                                    <input name="site_code" value="<?= $get_preview_profile->site_code;?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?> hidden>
                                    <input name="nama_comp" value="<?= $get_preview_profile->nama_comp;?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Nama DP Afiliasi</label>
                                <div class="col-sm-8">
                                    <input class="form-control input" type="text" name="nama"
                                        value="<?= $get_preview_profile->nama;?>"
                                        placeholder="contoh : PT Javas Karya Tripta" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Status Afiliasi</label>
                                <div class="col-sm-8">
                                    <input class="form-control input" name="status_afiliasi"
                                        value="<?= $get_preview_profile->status_afiliasi;?>"
                                        <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4">Alamat</label>
                                <div class="col-sm-8">
                                    <textarea name="alamat" class="form-control input" cols="30" rows="5"
                                        <?= $readonly.' '.$required; ?>><?= $get_preview_profile->alamat?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Provinsi</label>
                                <div class="col-sm-8">
                                    <input name="propinsi" value="<?= $get_preview_profile->propinsi?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kota / Kabupaten</label>
                                <div class="col-sm-8">
                                    <input name="kabupaten" value="<?= $get_preview_profile->kota?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kecamatan</label>
                                <div class="col-sm-8">
                                    <input name="kecamatan" value="<?= $get_preview_profile->kecamatan?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kelurahan</label>
                                <div class="col-sm-8">
                                    <input name="kelurahan" value="<?= $get_preview_profile->kelurahan?>"
                                        class="form-control input" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kode Pos</label>
                                <div class="col-sm-8">
                                    <input class="form-control input" type="text" name="kodepos"
                                        value="<?= $get_preview_profile->kodepos?>" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">No. Telp</label>
                                <div class="col-sm-8">
                                    <input class="form-control input" type="text" name="telp"
                                        value="<?= $get_preview_profile->telp?>" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Status Properti</label>
                                <div class="col-sm-8">
                                    <input class="form-control input"
                                        value="<?= $get_preview_profile->status_properti?>" name="status_properti"
                                        <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Periode Sewa</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-5"><input class="form-control input" type="date"
                                                name="sewa_from" value="<?= $get_preview_profile->sewa_from?>"
                                                <?= $readonly.' '.$required; ?>>
                                        </div> s/d
                                        <div class="col-sm-5"><input class="form-control input" type="date"
                                                name="sewa_to" value="<?= $get_preview_profile->sewa_to?>"
                                                <?= $readonly.' '.$required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Harga Sewa (per tahun)</label>
                                <div class="col-sm-8">
                                    <input class="form-control input" type="number" name="harga_sewa"
                                        value="<?= $get_preview_profile->harga_sewa?>" <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Bentuk Bangunan</label>
                                <div class="col-sm-8">
                                    <input class="form-control input"
                                        value="<?= $get_preview_profile->bentuk_bangunan?>" name="bentuk_bangunan"
                                        <?= $readonly.' '.$required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Foto Lokasi</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Foto Tampak Depan
                                            <input class="form-control input" type="file" name="foto_tampak_depan"
                                                id="foto_tampak_depan_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            Foto Gudang
                                            <input class="form-control input" type="file" name="foto_gudang"
                                                id="foto_gudang_profile" <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Foto Kantor
                                            <input class="form-control input" type="file" name="foto_kantor"
                                                id="foto_kantor_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            Foto Area Loading Gudang
                                            <input class="form-control input" type="file"
                                                name="foto_area_loading_gudang" id="foto_area_loading_gudang_profile"
                                                <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            Foto Gudang Baik
                                            <input class="form-control input" type="file" name="foto_gudang_baik"
                                                id="foto_gudang_baik_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            Foto Gudang Retur
                                            <input class="form-control input" type="file" name="foto_gudang_retur"
                                                id="foto_gudang_retur_profile" <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <!-- detail gudang -->
                <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    <div class="col-12">
                        <div class="form-group row">
                            <label class="col-sm-4">Sub Branch</label>
                            <div class="col-sm-8">
                                <input value="<?= $get_preview_detail->site_code;?>" class="form-control input"
                                    <?= $readonly.' '.$required; ?> hidden>
                                <input value="<?= $get_preview_detail->nama_comp;?>" name="nama_comp"
                                    class="form-control input" <?= $readonly.' '.$required; ?>>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang (m2)
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar (m2)
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking (m2)
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Baik - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang_baik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang_baik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Baik - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang_baik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang_baik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Retur - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang_retur;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang_retur;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Retur - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang_retur;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang_retur;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Karantina - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang_karantina;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang_karantina;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Karantina - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang_karantina;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang_karantina;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang AC - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang_ac;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang_ac;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang AC - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang_ac;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang_ac;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Loading Dock - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_loading_dock;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_loading_dock;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Loading Dock - in pallet</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">total pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->pallet_gudang_loading;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">racking
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->racking_gudang_loading;?>"
                                            <?= $readonly.' '.$required; ?>>
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
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_pallet;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">hand pallet
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_hand_pallet;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">trolley
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_trolley;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">sealer
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_sealer;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Sirkulasi Udara Gudang Baik</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">ac
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_ac;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">exhaust fan
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_exhaust_fan;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">kipas angin
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_kipas_angin;?>"
                                            <?= $readonly.' '.$required; ?>>
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
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_kantor_div_logistik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar (m2)
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_kantor_div_logistik;?>"
                                            <?= $readonly.' '.$required; ?>>
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
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_mobil_penumpang_sewa;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">milik sendiri
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_mobil_penumpang_milik_sendiri;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Mobil Pengiriman</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">blind van
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_mobil_pengiriman_blind_van;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">engkel
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_mobil_pengiriman_engkel;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">double
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_mobil_pengiriman_double;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Blind Van</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">sewa
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_blind_van_sewa;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">milik sendiri
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_blind_van_milik_sendiri;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Engkel</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">sewa
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_engkel_sewa;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">milik sendiri
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_engkel_milik_sendiri;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Double</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">sewa
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_double_sewa;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">milik sendiri
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_double_milik_sendiri;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Motor Pengiriman</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">sewa
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_motor_pengiriman_sewa;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">milik sendiri
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_motor_pengiriman_milik_sendiri;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Saddle Bag</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">dipakai
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_saddle_bag_dipakai;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">cadangan
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->jumlah_saddle_bag_cadangan;?>"
                                            <?= $readonly.' '.$required; ?>>
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
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_kantor_total;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_kantor_total;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Ruang Sales - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_ruang_sales;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_ruang_sales;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Ruang Finance (m2)</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_ruang_finance;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_ruang_finance;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Ruang Logistic - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_ruang_logistik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_ruang_logistik;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4">Luas Gudang Arsip - in meter2</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">panjang
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->panjang_gudang_arsip;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                    <div class="col-sm-4">lebar
                                        <input class="form-control input" type="number"
                                            value="<?= $get_preview_detail->lebar_gudang_arsip;?>"
                                            <?= $readonly.' '.$required; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- karyawan -->
                <div class="tab-pane fade" id="karyawan" role="tabpanel" aria-labelledby="karyawan-tab">
                    <div class="dt-responsive table-responsive">
                        <table id="table-karyawan" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Nama
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kelamin
                                    </th>
                                    <th>
                                        <font size="2px">Tempat
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Lahir
                                    </th>
                                    <th>
                                        <font size="2px">Tingkat Pendidikan
                                    </th>
                                    <th>
                                        <font size="2px">Status Pernikahan
                                    </th>
                                    <th>
                                        <font size="2px">Department
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan
                                    </th>
                                    <th>
                                        <font size="2px">Status Karyawan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Masuk Kerja
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_preview_karyawan as $karyawan):?>
                                <tr>
                                    <td><?=$karyawan->site_code;?></td>
                                    <td><?=$karyawan->nama;?></td>
                                    <td><?=$karyawan->jenis_kelamin;?></td>
                                    <td><?=$karyawan->tempat;?></td>
                                    <td><?=$karyawan->tanggal_lahir;?></td>
                                    <td><?=$karyawan->tingkat_pendidikan;?></td>
                                    <td><?=$karyawan->status_pernikahan;?></td>
                                    <td><?=$karyawan->department;?></td>
                                    <td><?=$karyawan->jabatan;?></td>
                                    <td><?=$karyawan->status_karyawan;?></td>
                                    <td><?=$karyawan->tanggal_masuk_kerja;?></td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Nama
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kelamin
                                    </th>
                                    <th>
                                        <font size="2px">Tempat
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Lahir
                                    </th>
                                    <th>
                                        <font size="2px">Tingkat Pendidikan
                                    </th>
                                    <th>
                                        <font size="2px">Status Pernikahan
                                    </th>
                                    <th>
                                        <font size="2px">Department
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan
                                    </th>
                                    <th>
                                        <font size="2px">Status Karyawan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Masuk Kerja
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- niaga -->
                <div class="tab-pane fade" id="niaga" role="tabpanel" aria-labelledby="niaga-tab">
                    <div class="dt-responsive table-responsive">
                        <table id="table-niaga" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kendaraan
                                    </th>
                                    <th>
                                        <font size="2px">Kepemilikan
                                    </th>
                                    <th>
                                        <font size="2px">Bahan Bakar
                                    </th>
                                    <th>
                                        <font size="2px">No Polisi
                                    </th>
                                    <th>
                                        <font size="2px">Tahun Pembuatan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Berakhir
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Kir
                                    </th>
                                    <th>
                                        <font size="2px">Vendor
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Awal Sewa
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Akhir Sewa
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_preview_niaga as $niaga):?>
                                <tr>
                                    <td><?=$niaga->site_code;?></td>
                                    <td><?=$niaga->jenis_kendaraan;?></td>
                                    <td><?=$niaga->kepemilikan;?></td>
                                    <td><?=$niaga->bahan_bakar;?></td>
                                    <td><?=$niaga->no_polisi;?></td>
                                    <td><?=$niaga->tahun_pembuatan;?></td>
                                    <td><?=$niaga->tanggal_pajak_berakhir;?></td>
                                    <td><?=$niaga->tanggal_pajak_kir;?></td>
                                    <td><?=$niaga->vendor;?></td>
                                    <td><?=$niaga->tanggal_awal_sewa;?></td>
                                    <td><?=$niaga->tanggal_akhir_sewa;?></td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kendaraan
                                    </th>
                                    <th>
                                        <font size="2px">Kepemilikan
                                    </th>
                                    <th>
                                        <font size="2px">Bahan Bakar
                                    </th>
                                    <th>
                                        <font size="2px">No Polisi
                                    </th>
                                    <th>
                                        <font size="2px">Tahun Pembuatan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Berakhir
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Kir
                                    </th>
                                    <th>
                                        <font size="2px">Vendor
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Awal Sewa
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Akhir Sewa
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- non niaga -->
                <div class="tab-pane fade" id="nonniaga" role="tabpanel" aria-labelledby="nonniaga-tab">
                    <div class="dt-responsive table-responsive">
                        <table id="table-nonniaga" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kendaraan
                                    </th>
                                    <th>
                                        <font size="2px">Kepemilikan
                                    </th>
                                    <th>
                                        <font size="2px">Nama Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan
                                    </th>
                                    <th>
                                        <font size="2px">Bahan Bakar
                                    </th>
                                    <th>
                                        <font size="2px">No Polisi
                                    </th>
                                    <th>
                                        <font size="2px">Tahun Pembuatan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Berakhir
                                    </th>
                                    <th>
                                        <font size="2px">Vendor
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Awal Sewa
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Akhir Sewa
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_preview_non_niaga as $niaga):?>
                                <tr>
                                    <td><?=$niaga->site_code;?></td>
                                    <td><?=$niaga->jenis_kendaraan;?></td>
                                    <td><?=$niaga->kepemilikan;?></td>
                                    <td><?=$niaga->nama_pemakai;?></td>
                                    <td><?=$niaga->jabatan;?></td>
                                    <td><?=$niaga->bahan_bakar;?></td>
                                    <td><?=$niaga->no_polisi;?></td>
                                    <td><?=$niaga->tahun_pembuatan;?></td>
                                    <td><?=$niaga->tanggal_pajak_berakhir;?></td>
                                    <td><?=$niaga->vendor;?></td>
                                    <td><?=$niaga->tanggal_awal_sewa;?></td>
                                    <td><?=$niaga->tanggal_akhir_sewa;?></td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Kendaraan
                                    </th>
                                    <th>
                                        <font size="2px">Kepemilikan
                                    </th>
                                    <th>
                                        <font size="2px">Nama Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan
                                    </th>
                                    <th>
                                        <font size="2px">Bahan Bakar
                                    </th>
                                    <th>
                                        <font size="2px">No Polisi
                                    </th>
                                    <th>
                                        <font size="2px">Tahun Pembuatan
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pajak Berakhir
                                    </th>
                                    <th>
                                        <font size="2px">Vendor
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Awal Sewa
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Akhir Sewa
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- it asset -->
                <div class="tab-pane fade" id="itasset" role="tabpanel" aria-labelledby="itasset-tab">
                    <div class="dt-responsive table-responsive">
                        <table id="table-itasset" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Nama Asset
                                    </th>
                                    <th>
                                        <font size="2px">Merk
                                    </th>
                                    <th>
                                        <font size="2px">Type
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pembelian
                                    </th>
                                    <th>
                                        <font size="2px">Operating System
                                    </th>
                                    <th>
                                        <font size="2px">Processor
                                    </th>
                                    <th>
                                        <font size="2px">Ram
                                    </th>
                                    <th>
                                        <font size="2px">Storage
                                    </th>
                                    <th>
                                        <font size="2px">Kapasitas Baterai
                                    </th>
                                    <th>
                                        <font size="2px">Divisi Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan Pemakai
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_preview_it_asset as $niaga):?>
                                <tr>
                                    <td><?=$niaga->site_code;?></td>
                                    <td><?=$niaga->nama_asset;?></td>
                                    <td><?=$niaga->merk;?></td>
                                    <td><?=$niaga->type;?></td>
                                    <td><?=$niaga->tanggal_pembelian;?></td>
                                    <td><?=$niaga->operating_system;?></td>
                                    <td><?=$niaga->processor;?></td>
                                    <td><?=$niaga->ram;?></td>
                                    <td><?=$niaga->storage;?></td>
                                    <td><?=$niaga->kapasitas_baterai;?></td>
                                    <td><?=$niaga->divisi_pemakai;?></td>
                                    <td><?=$niaga->jabatan_pemakai;?></td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Nama Asset
                                    </th>
                                    <th>
                                        <font size="2px">Merk
                                    </th>
                                    <th>
                                        <font size="2px">Type
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pembelian
                                    </th>
                                    <th>
                                        <font size="2px">Operating System
                                    </th>
                                    <th>
                                        <font size="2px">Processor
                                    </th>
                                    <th>
                                        <font size="2px">Ram
                                    </th>
                                    <th>
                                        <font size="2px">Storage
                                    </th>
                                    <th>
                                        <font size="2px">Kapasitas Baterai
                                    </th>
                                    <th>
                                        <font size="2px">Divisi Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan Pemakai
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- asset -->
                <div class="tab-pane fade" id="asset" role="tabpanel" aria-labelledby="asset-tab">
                    <div class="dt-responsive table-responsive">
                        <table id="table-itasset" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Asset
                                    </th>
                                    <th>
                                        <font size="2px">Merk
                                    </th>
                                    <th>
                                        <font size="2px">Type
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pembelian
                                    </th>
                                    <th>
                                        <font size="2px">Divisi Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan Pemakai
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($get_preview_asset as $niaga):?>
                                <tr>
                                    <td><?=$niaga->site_code;?></td>
                                    <td><?=$niaga->jenis_asset;?></td>
                                    <td><?=$niaga->merk;?></td>
                                    <td><?=$niaga->type;?></td>
                                    <td><?=$niaga->tanggal_pembelian;?></td>
                                    <td><?=$niaga->divisi_pemakai;?></td>
                                    <td><?=$niaga->jabatan_pemakai;?></td>
                                </tr>
                                <?php endforeach?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        <font size="2px">Site Code
                                    </th>
                                    <th>
                                        <font size="2px">Jenis Asset
                                    </th>
                                    <th>
                                        <font size="2px">Merk
                                    </th>
                                    <th>
                                        <font size="2px">Type
                                    </th>
                                    <th>
                                        <font size="2px">Tanggal Pembelian
                                    </th>
                                    <th>
                                        <font size="2px">Divisi Pemakai
                                    </th>
                                    <th>
                                        <font size="2px">Jabatan Pemakai
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div align="center">
    <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    function validateForm() {
        if (document.forms["formProfile"]["foto_tampak_depan"].value == "") {
            alert("Foto Tampak Depan Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_tampak_depan"].focus();
            return false;
        }
        if (document.forms["formProfile"]["foto_gudang"].value == "") {
            alert("Foto Gudang Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_gudang"].focus();
            return false;
        }
        if (document.forms["formProfile"]["foto_kantor"].value == "") {
            alert("Foto Kantor Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_kantor"].focus();
            return false;
        }
        if (document.forms["formProfile"]["foto_area_loading_gudang"].value == "") {
            alert("Foto Area Loading Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_area_loading_gudang"].focus();
            return false;
        }
        if (document.forms["formProfile"]["foto_gudang_baik"].value == "") {
            alert("Foto Gudang Baik Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_gudang_baik"].focus();
            return false;
        }
        if (document.forms["formProfile"]["foto_gudang_retur"].value == "") {
            alert("Foto Gudang Retur Tidak Boleh Kosong");
            document.forms["formProfile"]["foto_gudang_retur"].focus();
            return false;
        }
    }
</script>