<?php

// $required = "";
$required = "required";

?>
<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">

                <ul class="nav nav-tabs md-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile"
                            role="tab">Profile</a>
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
                        <a class="nav-link" id="niaga-tab" data-toggle="tab" href="#niaga" role="tab"
                            aria-controls="niaga" aria-selected="false">Niaga</a>
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
                        <a class="nav-link" id="asset-tab" data-toggle="tab" href="#asset" role="tab"
                            aria-controls="asset" aria-selected="false">Asset Kantor</a>
                        <div class="slide"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="strukturorganisasi-tab" data-toggle="tab" href="#strukturorganisasi"
                            role="tab" aria-controls="strukturorganisasi" aria-selected="false">Struktur Organisasi</a>
                        <div class="slide"></div>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <?php echo form_open_multipart(); ?>
                        <div class="col-12">
                            <div class="form-group row">
                                <label class="col-sm-4">Sub Branch</label>
                                <div class="col-sm-6">
                                    <!-- <input class="form-control" type="text" name="kodeprod" id="kodeprod" required > -->
                                    <input name="site_code" value="<?= $get_preview->site_code;?>" class="form-control"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Nama DP Afiliasi</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="nama" value="<?= $get_preview->nama;?>"
                                        placeholder="contoh : PT Javas Karya Tripta" <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Status Afiliasi</label>
                                <div class="col-sm-6">
                                    <input class="form-control" name="status_afiliasi" value="<?= $get_preview->status_afiliasi;?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4">Alamat</label>
                                <div class="col-sm-6">
                                    <textarea name="alamat" class="form-control" cols="30" rows="5"
                                        <?= $required; ?>><?= $get_preview->alamat?></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Provinsi</label>
                                <div class="col-sm-6">
                                    <input name="propinsi" value="<?= $get_preview->propinsi?>" class="form-control"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kota / Kabupaten</label>
                                <div class="col-sm-6">
                                    <input name="kabupaten" value="<?= $get_preview->kota?>" class="form-control"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kecamatan</label>
                                <div class="col-sm-6">
                                    <input name="kecamatan" value="<?= $get_preview->kecamatan?>" class="form-control"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kelurahan</label>
                                <div class="col-sm-6">
                                    <input name="kelurahan" value="<?= $get_preview->kelurahan?>" class="form-control"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Kode Pos</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="kodepos" value="<?= $get_preview->kodepos?>"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">No. Telp</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="text" name="telp" <?= $get_preview->telp?>
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Status Properti</label>
                                <div class="col-sm-6">
                                    <input class="form-control" value="<?= $get_preview->status_properti?>" name="status_properti" <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Periode Sewa</label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-5"><input class="form-control" type="date" name="sewa_from"
                                                id="sewa_from_profile" <?= $required; ?>></div> s/d
                                        <div class="col-sm-5"><input class="form-control" type="date" name="sewa_to"
                                                id="sewa_to_profile" <?= $required; ?>></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Harga Sewa (per tahun)</label>
                                <div class="col-sm-6">
                                    <input class="form-control" type="number" name="harga_sewa" id="harga_sewa_profile"
                                        <?= $required; ?>>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Bentuk Bangunan</label>
                                <div class="col-sm-6">
                                    <select class="form-control" value="0" name="bentuk_bangunan"
                                        id="bentuk_bangunan_profile" <?= $required; ?>>
                                        <option value=""> - Pilih Salah Satu - </option>
                                        <option value="gudang">Gudang</option>
                                        <option value="ruko">Ruko</option>
                                        <option value="rumah">Rumah</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4">Foto Lokasi</label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <img alt="" id="img_tampak_depan_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Tampak Depan
                                            <input class="form-control" type="file" name="foto_tampak_depan"
                                                id="foto_tampak_depan_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            <img alt="" id="img_gudang_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Gudang
                                            <input class="form-control" type="file" name="foto_gudang"
                                                id="foto_gudang_profile" <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <img alt="" id="img_kantor_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Kantor
                                            <input class="form-control" type="file" name="foto_kantor"
                                                id="foto_kantor_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            <img alt="" id="img_area_loading_gudang_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Area Loading Gudang
                                            <input class="form-control" type="file" name="foto_area_loading_gudang"
                                                id="foto_area_loading_gudang_profile" <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4"></label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <img alt="" id="img_gudang_baik_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Gudang Baik
                                            <input class="form-control" type="file" name="foto_gudang_baik"
                                                id="foto_gudang_baik_profile" <?= $required; ?>>
                                        </div>
                                        <div class="col-sm-6">
                                            <img alt="" id="img_gudang_retur_profile" style='max-width: 60%;'>
                                            <br>
                                            Foto Gudang Retur
                                            <input class="form-control" type="file" name="foto_gudang_retur"
                                                id="foto_gudang_retur_profile" <?= $required; ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success" required'); ?>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <!-- <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addDetail()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-dc" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>Luas Gudang</th>
                                    <th>Total Mobil Penumpang</th>
                                    <th>Total Mobil Pengiriman</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_detail as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->luas_gudang; ?></td>
                                    <td><?= $a->total_mobil_penumpang; ?></td>
                                    <td><?= $a->total_mobil_pengiriman; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editDetail('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("gudang")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("gudang")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="karyawan" role="tabpanel" aria-labelledby="karyawan-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addKaryawan()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-karyawan" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Pendidikan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_karyawan as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->nama; ?></td>
                                    <td><?= $a->jenis_kelamin; ?></td>
                                    <td><?= $a->tempat.', '.$a->tanggal_lahir; ?></td>
                                    <td><?= $a->tingkat_pendidikan; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editKaryawan('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("karyawan")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("karyawan")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="niaga" role="tabpanel" aria-labelledby="niaga-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addNiaga()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-niaga" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Kepemilikan</th>
                                    <th>Bahan Bakar</th>
                                    <th>No Polisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_niaga as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->jenis_kendaraan; ?></td>
                                    <td><?= $a->bahan_bakar; ?></td>
                                    <td><?= $a->no_polisi; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editNiaga('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("niaga")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("niaga")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="nonniaga" role="tabpanel" aria-labelledby="nonniaga-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addNon_niaga()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-nonniaga" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>Jenis Kendaraan</th>
                                    <th>Kepemilikan</th>
                                    <th>Nama Pemakai</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_non_niaga as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->jenis_kendaraan; ?></td>
                                    <td><?= $a->kepemilikan; ?></td>
                                    <td><?= $a->nama_pemakai; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editNon_niaga('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("non_niaga")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("non_niaga")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="itasset" role="tabpanel" aria-labelledby="itasset-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addIt_asset()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-itasset" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>NamaAsset</th>
                                    <th>Merk</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_it_asset as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->nama_asset; ?></td>
                                    <td><?= $a->merk; ?></td>
                                    <td><?= $a->type; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editIt_asset('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("it_asset")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("it_asset")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="asset" role="tabpanel" aria-labelledby="itasset-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" onclick="addAsset()"><i
                                    class="fa fa-plus"></i>Tambah
                                Data</button>
                        </div>

                        <table id="table-asset" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>JenisAsset</th>
                                    <th>Merk</th>
                                    <th>Type</th>
                                    <th>Tanggal Pembelian</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_asset as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->jenis_asset; ?></td>
                                    <td><?= $a->merk; ?></td>
                                    <td><?= $a->type; ?></td>
                                    <td><?= $a->tanggal_pembelian; ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            onclick="editAsset('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("asset")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("asset")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="strukturorganisasi" role="tabpanel"
                        aria-labelledby="strukturorganisasi-tab">
                        <div class="mt-5">
                            <button class="btn btn-primary btn-primary mb-4" data-toggle="modal"
                                data-target="#tambah_struktur"><i class="fa fa-plus"></i>Tambah Data</button>
                        </div>

                        <?= $this->load->view('database_afiliasi/struktur_organisasi'); ?>

                    </div> -->

                </div>

            </div>
        </div>
    </div>
</div>