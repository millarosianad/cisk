<?php
$required = "";

?>
<button type="button" class="btn btn-primary btn-mat" data-toggle="modal" data-target="#import">
  Import
</button>
<!-- <a href='<?= base_url()."database_afiliasi/export_csv/"?>' class="btn btn-warning btn-mat">Export (.csv)</a> -->
<br><br>
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
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="strukturorganisasi-tab" data-toggle="tab" href="#strukturorganisasi"
                            role="tab" aria-controls="strukturorganisasi" aria-selected="false">Struktur Organisasi</a>
                        <div class="slide"></div>
                    </li> -->
                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-mat mb-4" onclick="addProfile()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
                        </div>

                        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>SubBranch</th>
                                    <th>Alamat</th>
                                    <th>Propinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($get_profile as $a) : ?>
                                <tr>
                                    <td><?= $a->branch_name; ?></td>
                                    <td><?= $a->nama_comp; ?></td>
                                    <td><?= $a->alamat; ?></td>
                                    <td><?= $a->propinsi; ?></td>
                                    <td><?= $a->kota; ?></td>
                                    <td>
                                        <!-- <a href=" /" class="btn btn-primary">Edit</a> -->
                                        <!-- <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#tambah_profile">Edit</button> -->
                                        <!-- <button class="fa fa-edit fa-xl btn-info" id="testOnclick" onclick="getProfile('1')"></button> -->


                                        <button class="btn btn-info btn-sm"
                                            onclick="editProfile('<?= $a->id; ?>')">Edit</button>
                                        <a href="<?= base_url()."database_afiliasi/download_pdf/".md5("profile")."/".$a->id;?>"
                                            class="btn btn-warning btn-sm">PDF</a>
                                        <a href="<?= base_url()."database_afiliasi/delete/".md5("profile")."/".md5($a->id);?>"
                                            class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">

                        <div class="mt-5">
                            <button class="btn btn-primary btn-mat mb-4" onclick="addDetail()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
                        </div>

                        <table id="table-detail" class="table table-striped table-bordered nowrap">
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
                            <button class="btn btn-primary btn-mat mb-4" onclick="addKaryawan()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
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
                            <button class="btn btn-primary btn-mat mb-4" onclick="addNiaga()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
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
                            <button class="btn btn-primary btn-mat mb-4" onclick="addNon_niaga()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
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
                            <button class="btn btn-primary btn-mat mb-4" onclick="addIt_asset()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
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
                            <button class="btn btn-primary btn-mat mb-4" onclick="addAsset()"><i
                                    class="fa fa-plus"></i>Tambah Data</button>
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
                            <button class="btn btn-primary btn-mat mb-4" data-toggle="modal"
                                data-target="#tambah_struktur"><i class="fa fa-plus"></i>Tambah Data</button>
                        </div>

                        <?= $this->load->view('database_afiliasi/struktur_organisasi'); ?>

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php 
    // $this->load->view('database_afiliasi/download');
    $this->load->view('database_afiliasi/modal_import');
    $this->load->view('database_afiliasi/modal_tambah_profile');
    $this->load->view('database_afiliasi/modal_detail_gudang');
    $this->load->view('database_afiliasi/modal_karyawan');
    $this->load->view('database_afiliasi/modal_niaga');
    $this->load->view('database_afiliasi/modal_non_niaga');
    $this->load->view('database_afiliasi/modal_it_asset');
    $this->load->view('database_afiliasi/modal_asset');
    $this->load->view('database_afiliasi/modal_struktur');
    // $this->load->view('master_product/modal_editproduct_apps');
?>

<script>
    $(document).ready(function() {
    $.ajax({
            type: 'POST',
            url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
            success: function(hasil_subbranch) {
                $("select[name = site_code]").html(hasil_subbranch);
            }
        });
    })
</script>