<style type="text/css">
    #second,#third,#fourth,#fifth,#result{
    /* #fifth,#result{ */
        display: none;
    }
</style>

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
            </ol>
        </div>
    </div>
<div class="container-fluid">
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="progress mb-5" style="height:40px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width:10%;" id="progressBar">
                            <b class="lead" id="progressText">Step - 1</b>
                        </div>
                    </div>                    
                    <!-- <form action="<?php echo base_url(); ?>profile/prosesIsiBiodata" method="post" id="form-data" name="form-data"> -->
                    <?php echo form_open_multipart($url);?>

                    <div id="first">
                        <h4 class="text-left text-light">Informasi Data Diri</h4>
                        <br>
                        (*) wajib diisi<br><br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" class="form-control" placeholder="nama lengkap *">
                                    <b class="form-text text-danger" id="namaError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" class="form-control" placeholder="Email kantor" readonly>
                                    <b class="form-text text-danger" id="emailError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="nik" id="nik" value="<?php echo $nik; ?>" class="form-control" placeholder="nik *">
                                    <b class="form-text text-danger" id="nikError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                <input type="text" name="tanggalGabung" id="tanggalGabung" value="<?php echo $tanggalGabung; ?>" class="form-control mydatepicker" placeholder="tanggal bergabung di MPM">
                                    <b class="form-text text-danger" id="tanggalGabungError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="noKtp" id="noKtp" value="<?php echo $noKtp; ?>" class="form-control" placeholder="nomor KTP *">
                                    <b class="form-text text-danger" id="noKtpError">  </b>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="telp1" id="telp1" value="<?php echo $telp1; ?>" class="form-control" placeholder="no yang bisa dihubungi *">
                                    <b class="form-text text-danger" id="telp1Error">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="telp2" id="telp2" value="<?php echo $telp2; ?>" class="form-control" placeholder="nomor whatsapp *">
                                    <b class="form-text text-danger" id="telp2Error">  </b>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="text" name="alamatSekarang" id="alamatSekarang" value="<?php echo $alamatSekarang; ?>" class="form-control" placeholder="alamat saat ini *"  >
                                    <b class="form-text text-danger" id="alamatSekarangError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="text" name="alamatKtp" id="alamatKtp" value="<?php echo $alamatKtp; ?>" class="form-control" placeholder="alamat sesuai ktp *"  >
                                    <b class="form-text text-danger" id="alamatKtpError">  </b>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="tempatLahir" id="tempatLahir" value="<?php echo $tempatLahir; ?>" class="form-control" placeholder="Tempat Lahir *"  >
                                    <b class="form-text text-danger" id="tempatLahirError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">                                    
                                    <input type="text" name="tanggalLahir" id="tanggalLahir" value="<?php echo $tanggalLahir; ?>" class="form-control mydatepicker" placeholder="Tanggal Lahir *">
                                    <b class="form-text text-danger" id="tanggalLahirError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    
                                <?php
                                    $selected = ($agama) ? $agama : $agama;   
                                    $position = array("1" => "Islam", "2" => "Kristen Protestan", "3" => "Katolik", "4" => "Hindu", "5" => "Budha", "6" => "Kong Hu Cu", "" => "-- Pilih Agama * --");
                                    echo form_dropdown('agama', $position, $selected, 'class="form-control" id="agama"');
                                ?>
                                <b class="form-text text-danger" id="agamaError">  </b>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php
                                        $selected = ($jenisKelamin) ? $jenisKelamin : $jenisKelamin;   
                                        $position = array("1" => "Laki-laki", "2" => "Perempuan", "" => "-- Jenis Kelamin * --");
                                        echo form_dropdown('jenisKelamin', $position, $selected, 'class="form-control" id="jenisKelamin"');
                                    ?>
                                    <b class="form-text text-danger" id="jenisKelaminError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php
                                        $selected = ($golonganDarah) ? $golonganDarah : $golonganDarah;   
                                        $position = array("A" => "A", "B" => "B", "O" => "O", "AB" => "AB", "" => "-- Golongan Darah * --");
                                        echo form_dropdown('golonganDarah', $position, $selected, 'class="form-control" id="golonganDarah"');
                                    ?>
                                    <b class="form-text text-danger" id="golonganDarahError">  </b>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h4 class="text-left text-light">Informasi Kontak Darurat</h4>
                        <br>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaKontakDarurat" id="namaKontakDarurat" value="<?php echo $namaKontakDarurat; ?>" class="form-control" placeholder="nama Kontak Darurat *">
                                    <b class="form-text text-danger" id="namaKontakDaruratError">  </b>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="hubungan" id="hubungan" value="<?php echo $hubungan; ?>" class="form-control" placeholder="hubungan *">
                                    <b class="form-text text-danger" id="hubunganError">  </b>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="telp3" id="telp3" value="<?php echo $telp3; ?>" class="form-control" placeholder="nomor telp darurat *">
                                    <b class="form-text text-danger" id="telp3Error">  </b>
                                </div>
                            </div>

                            

                            
                        </div>
                        <hr>
                        <h4 class="text-left text-light">Informasi NPWP dan Rekening</h4>
                        <br>
                        <div class="row">                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="noNpwp" id="noNpwp" value="<?php echo $noNpwp; ?>" class="form-control" placeholder="nomor npwp *">
                                    <b class="form-text text-danger" id="noNpwpError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="text" name="alamatNpwp" id="alamatNpwp" value="<?php echo $alamatNpwp; ?>" class="form-control" placeholder="alamat npwp">
                                    <b class="form-text text-danger" id="alamatNpwpError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="noBca" id="noBca" value="<?php echo $noBca; ?>" class="form-control" placeholder="nomor rekening bca *">
                                    <b class="form-text text-danger" id="noBcaError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaBca" id="namaBca" value="<?php echo $namaBca; ?>" class="form-control" placeholder="nama rekening bca *">
                                    <b class="form-text text-danger" id="namaBcaError">  </b>
                                </div>
                            </div>
                        </div> <br><center>
                        <a href="#" class="btn btn-danger" id="next-1">Next</a>
                        </center>
                    </div>

                    <div id="second">
                        <h4 class="text-left text-light">Pendidikan Terakhir</h4>
                        <br>
                        (*) wajib diisi<br><br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    
                                    <?php
                                        $selected = ($pendidikan2) ? $pendidikan2 : $pendidikan2;   
                                        $position = array("1" => "SMA/SMK", "2" => "D3/DIPLOMA","3" => "S1/SARJANA","4" => "S2/MAGISTER","5" => "S3/DOCTOR", "" => "-- Pendidikan Terakhir * --");
                                        echo form_dropdown('pendidikan2', $position, $selected, 'class="form-control" id="pendidikan2"');
                                    ?>

                                    <b class="form-text text-danger" id="pendidikan2Error">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaInstitusi2" id="namaInstitusi2" value="<?php echo $namaInstitusi2; ?>" class="form-control" placeholder="nama institusi *">
                                    <b class="form-text text-danger" id="namaInstitusi2Error">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="tahunKelulusan2" id="tahunKelulusan2" value="<?php echo $tahunKelulusan2; ?>" class="form-control" placeholder="tahun kelulusan *">
                                    <b class="form-text text-danger" id="tahunKelulusan2Error">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="jurusan2" id="jurusan2" value="<?php echo $jurusan2; ?>" class="form-control" placeholder="jurusan *">
                                    <b class="form-text text-danger" id="jurusan2Error">  </b>
                                </div>
                            </div>                            

                        </div><br><center>
                        <a href="#" class="btn btn-danger" id="prev-2">Previous</a>
                        <a href="#" class="btn btn-danger" id="next-2">Next</a>
                        </center>
                    </div>

                    <div id="third">
                        <h4 class="text-left text-light">Data Keluarga</h4>
                        <br>
                        (*) wajib diisi<br><br>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
     
                                    <?php
                                        $selected = ($statusPernikahan) ? $statusPernikahan : $statusPernikahan;   
                                        $position = array("1" => "belum menikah", "2" => "menikah","3" => "cerai", "" => "-- Status Pernikahan * --");
                                        echo form_dropdown('statusPernikahan', $position, $selected, 'class="form-control" id="statusPernikahan"');
                                    ?>
                                    <b class="form-text text-danger" id="statusPernikahanError">  </b>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaPasangan" value="<?php echo $namaPasangan; ?>" class="form-control" placeholder="nama pasangan">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">

                                    <?php
                                        $selected = ($pendidikanTerakhirPasangan) ? $pendidikanTerakhirPasangan : $pendidikanTerakhirPasangan;   
                                        $position = array("1" => "SMA/SMK", "2" => "D3/DIPLOMA","3" => "S1/SARJANA","4" => "S2/MAGISTER","5" => "S3/DOCTOR", "" => "-- Pendidikan Terakhir Pasangan --");
                                        echo form_dropdown('pendidikanTerakhirPasangan', $position, $selected, 'class="form-control" id="pendidikanTerakhirPasangan"');
                                    ?>
                                </div>
                            </div>   
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="jumlahAnak"  value="<?php echo $jumlahAnak; ?>" class="form-control" placeholder="jumlah anak">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaAnakPertama"  value="<?php echo $namaAnakPertama; ?>" class="form-control" placeholder="nama anak pertama">
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="tempatTanggalLahirAnakPertama" value="<?php echo $ttlAnakPertama; ?>" class="form-control" placeholder="tempat tanggal lahir anak pertama (tempatLahir-DD/MM/YY)">
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaAnakKedua" value="<?php echo $namaAnakKedua; ?>" class="form-control" placeholder="nama anak kedua">
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="tempatTanggalLahirAnakKedua" value="<?php echo $ttlAnakKedua; ?>" class="form-control" placeholder="tempat tanggal lahir anak kedua (tempatLahir-DD/MM/YY)">
                                </div>
                            </div>  
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="namaAnakKetiga" value="<?php echo $namaAnakKetiga; ?>" class="form-control" placeholder="nama anak ketiga">
                                </div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="text" name="tempatTanggalLahirAnakKetiga" value="<?php echo $ttlAnakKetiga; ?>" class="form-control" placeholder="tempat tanggal lahir anak ketiga (tempatLahir-DD/MM/YY)">
                                </div>
                            </div>   
                        </div><br><center>
                        <a href="#" class="btn btn-danger" id="prev-3">Previous</a>
                        <a href="#" class="btn btn-danger" id="next-3">Next</a>
                        </center>
                    </div>

                    <div id="fourth">
                        <h4 class="text-left text-light">Upload Foto / Scan</h4>
                        <br>
                        (*) wajib diisi<br><br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanKtp" class="col-lg-2 col-form-label">Scan KTP *</label>
                                    <div class="col-lg-5">

                                    <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanKtp )?>" height="80" width="60">
                                    <input type="hidden" name="scanKtpOld" value="<?php echo $scanKtp; ?>">
                                    <input type="file" name="scanKtp" id="scanKtp" class="form-control" value="a" placeholder="scan ktp *">
                                    <b class="form-text text-danger" id="scanKtpError">  </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanNpwp" class="col-lg-2 col-form-label">Scan NPWP</label>
                                    <div class="col-lg-5">
                                    <?php 
                                        if ($scanNpwp == '') { ?>
                                            
                                            <input type="hidden" name="scanNpwpOld" value="<?php echo $scanNpwp; ?>">                                    
                                            <input type="file" name="scanNpwp" id="scanNpwp" class="form-control" placeholder="scan npwp">
                                            <b class="form-text text-danger" id="scanNpwpError">  </b>
                                        <?php
                                        }else{ ?>
                                            <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanNpwp )?>" height="80" width="60">
                                            <input type="hidden" name="scanNpwpOld" value="<?php echo $scanNpwp; ?>">                                    
                                            <input type="file" name="scanNpwp" id="scanNpwp" class="form-control" placeholder="scan npwp">
                                            <b class="form-text text-danger" id="scanNpwpError">  </b>
                                        <?php
                                        }
                                    ?>                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanBpjsKesehatan" class="col-lg-2 col-form-label">Scan BPJS Kesehatan</label>
                                    <div class="col-lg-5">
                                    <?php 
                                        if ($scanBpjsKesehatan == '') { ?>
                                            <input type="hidden" name="scanBpjsKesehatanOld" value="<?php echo $scanBpjsKesehatan; ?>">                                    
                                            <input type="file" name="scanBpjsKesehatan" id="scanBpjsKesehatan" class="form-control" placeholder="scan bpjs kesehatan">
                                            <b class="form-text text-danger" id="scanBpjsKesehatanError">  </b>
                                        <?php
                                        }else{ ?>
                                            <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanBpjsKesehatan )?>" height="80" width="60">
                                            <input type="hidden" name="scanBpjsKesehatanOld" value="<?php echo $scanBpjsKesehatan; ?>">                                    
                                            <input type="file" name="scanBpjsKesehatan" id="scanBpjsKesehatan" class="form-control" placeholder="scan bpjs kesehatan">
                                            <b class="form-text text-danger" id="scanBpjsKesehatanError">  </b>
                                        <?php
                                        }
                                    ?>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanBpjsKetenagaKerjaan" class="col-lg-2 col-form-label">Scan BPJS Ketenagakerjaan</label>
                                    <div class="col-lg-5">
                                    <?php
                                        if ($scanBpjsKetenagaKerjaan == '') { ?>
                                            <input type="hidden" name="scanBpjsKetenagaKerjaanOld" value="<?php echo $scanBpjsKetenagaKerjaan; ?>">
                                            <input type="file" name="scanBpjsKetenagaKerjaan" id="scanBpjsKetenagaKerjaan" class="form-control" placeholder="scan bpjs ketenagakerjaan">
                                            <b class="form-text text-danger" id="scanBpjsKetenagaKerjaanError">  </b>
                                        <?php
                                        }else{ ?>
                                            <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanBpjsKetenagaKerjaan )?>" height="80" width="60">
                                            <input type="hidden" name="scanBpjsKetenagaKerjaanOld" value="<?php echo $scanBpjsKetenagaKerjaan; ?>">
                                            <input type="file" name="scanBpjsKetenagaKerjaan" id="scanBpjsKetenagaKerjaan" class="form-control" placeholder="scan bpjs ketenagakerjaan">
                                            <b class="form-text text-danger" id="scanBpjsKetenagaKerjaanError">  </b>
                                        <?php
                                        }
                                    ?>  
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanKtp" class="col-lg-2 col-form-label">Scan Foto Profil *</label>
                                    <div class="col-lg-5">
                                        <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanFotoDiri )?>" height="80" width="60">
                                        <input type="hidden" name="scanFotoDiriOld" value="<?php echo $scanFotoDiri; ?>">                                    
                                        <input type="file" name="scanFotoDiri" id="scanFotoDiri" class="form-control" placeholder="scan foto diri">
                                        <b class="form-text text-danger" id="scanFotoDiriError">  </b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanKk" class="col-lg-2 col-form-label">Scan Kartu Keluarga</label>
                                    <div class="col-lg-5">
                                    <?php
                                        if ($scanKk == '') { ?>
                                            <input type="hidden" name="scanKkOld" value="<?php echo $scanKk; ?>">                                    
                                            <input type="file" name="scanKk" id="scanKk" class="form-control" placeholder="scan foto diri">
                                            <b class="form-text text-danger" id="scanKkError">  </b>
                                        <?php
                                        }else{ ?>
                                            <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanKk )?>" height="80" width="60">
                                            <input type="hidden" name="scanKkOld" value="<?php echo $scanKk; ?>">                                    
                                            <input type="file" name="scanKk" id="scanKk" class="form-control" placeholder="scan foto diri">
                                            <b class="form-text text-danger" id="scanKkError">  </b>
                                        <?php
                                        }                                    
                                    ?>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="scanIjazahTerakhir" class="col-lg-2 col-form-label">Scan Ijazah Terakhir</label>
                                    <div class="col-lg-5">
                                    <?php
                                        if ($scanIjazahTerakhir == '') { ?>
                                            <input type="hidden" name="scanIjazahTerakhirOld" value="<?php echo $scanIjazahTerakhir; ?>">
                                            <input type="file" name="scanIjazahTerakhir" id="scanIjazahTerakhir" class="form-control" placeholder="scan foto diri">
                                            <b class="form-text text-danger" id="scanIjazahTerakhirError">  </b>
                                        <?php
                                        }else { ?>
                                            <img src=" <?php echo base_url( 'assets/uploads/'.$userId.'/'.$scanIjazahTerakhir )?>" height="80" width="60">
                                            <input type="hidden" name="scanIjazahTerakhirOld" value="<?php echo $scanIjazahTerakhir; ?>">
                                            <input type="file" name="scanIjazahTerakhir" id="scanIjazahTerakhir" class="form-control" placeholder="scan foto diri">
                                            <b class="form-text text-danger" id="scanIjazahTerakhirError">  </b>
                                        <?php    
                                        }
                                    
                                    
                                    ?>
                                        
                                    </div>
                                </div>
                            </div>      




                        </div>
                        
                        <br><center>
                        <a href="#" class="btn btn-danger" id="prev-4">Previous</a>
                        <a href="#" class="btn btn-danger" id="next-4">Next</a>
                        </center>
                    </div>

                    <div id="fifth">
                        <h4 class="text-left text-light"></h4>
                        <br>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="terimaKasih" class="col-lg-12 col-form-label">Terima kasih. Silahkan klik Submit. Data anda masuk ke database kami.</label>
                                
                            </div>      
                        </div><br><center>
                        <a href="#" class="btn btn-danger" id="prev-5">Previous</a>
                        <input type="submit" name="submit" value="Submit" id="submit" class="btn btn-success">
                        </center>
                    </div>


                    </form>
                </div>
            </div>
        </div>

    </div>
</div>



<script src="http://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

<!-- picker -->
<script src="<?php echo base_url(); ?>assets/plugins/moment/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins-init/form-pickers-init.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        // alert("Hello");
        $("#next-1").click(function(e){

            e.preventDefault();
            $('#namaError').html('');
            $('#emailError').html('');
            $('#nikError').html('');
            $('#tanggalGabungError').html('');
            $('#noKtpError').html('');
            $('#tempatLahirError').html('');
            $('#tanggalLahirError').html('');
            $('#agamaError').html('');
            $('#jenisKelaminError').html('');
            $('#golonganDarahError').html('');

            $('#telp1Error').html('');
            $('#telp2Error').html('');
            $('#telp3Error').html('');
            $('#namaKontakDaruratError').html('');
            $('#hubunganError').html('');
            $('#alamatSekarangError').html('');
            $('#alamatKtpError').html('');
            
            $('#noNpwpError').html('');
            $('#alamatNpwpError').html('');
            $('#noBcaError').html('');
            $('#namaBcaError').html('');

            if($("#nama").val() == '') {
                $("#namaError").html(' * isi nama lengkap anda. ');
                return false;
            }
            else if($("#email").val() == ''){
                $("#emailError").html(' * isi email @muliaputramandiri.com. ');
                return false;
            }else if($("#nik").val() == ''){
                $("#nikError").html(' * isi nik anda. ');
                return false;
            }else if($("#tanggalGabung").val() == ''){
                $("#tanggalGabungError").html(' * isi tanggal bergabung di MPM. ');
                return false;
            }else if($("#noKtp").val() == ''){
                $("#noKtpError").html(' * isi nomor Ktp anda. ');
                return false;
            }else if($("#tempatLahir").val() == ''){
                $("#tempatLahirError").html(' * isi tempat Lahir anda. ');
                return false;
            }else if($("#tanggalLahir").val() == ''){
                $("#tanggalLahirError").html(' * isi tanggal Lahir anda. ');
                return false;
            }else if($("#agama").val() == ''){
                $("#agamaError").html(' * pilih agama anda. ');
                return false;
            }else if($("#jenisKelamin").val() == ''){
                $("#jenisKelaminError").html(' * pilih jenis Kelamin anda. ');
                return false;
            }else if($("#golonganDarah").val() == ''){
                $("#golonganDarahError").html(' * pilih golongan darah anda. ');
                return false;
            }else if($("#telp1").val() == ''){
                $("#telp1Error").html(' * isi dengan nomor telp yang bisa dihubungi. ');
                return false;
            }
            else if($("#telp2").val() == ''){
                $("#telp2Error").html(' * isi no whatsapp anda. ');
                return false;
            }else if($("#telp3").val() == ''){
                $("#telp3Error").html(' * isi no kontak emergency anda. ');
                return false;
            }else if($("#namaKontakDarurat").val() == ''){
                $("#namaKontakDaruratError").html(' * isi nama kontak emergency anda. ');
                return false;
            }else if($("#hubungan").val() == ''){
                $("#hubunganError").html(' * isi hubungan emergency kontak dengan anda. ');
                return false;
            }else if($("#alamatSekarang").val() == ''){
                $("#alamatSekarangError").html(' * isi alamat anda saat ini. ');
                return false;
            }else if($("#alamatKtp").val() == ''){
                $("#alamatKtpError").html(' * isi alamat di ktp anda. ');
                return false;
            }
            // else if($("#noNpwp").val() == ''){
            //     $("#noNpwpError").html(' * isi noNpwp anda. ');
            //     return false;
            // }
            // else if($("#alamatNpwp").val() == ''){
            //     $("#alamatNpwpError").html(' * isi alamat Npwp anda. ');
            //     return false;
            // }
            else if($("#noBca").val() == ''){
                $("#noBcaError").html(' * isi no Bca anda. ');
                return false;
            }else if($("#namaBca").val() == ''){
                $("#namaBcaError").html(' * isi nama Bca anda. ');
                return false;
            }
            else {
                $("#second").show();
                $("#first").hide();
                $("#progressBar").css("width","30%");
                $("#progressText").html("Step - 2");
            }
        });

        $("#next-2").click(function(e){

            e.preventDefault();
            $('#pendidikan2Error').html('');
            $('#namaInstitusi2Error').html('');
            $('#tahunKelulusan2Error').html('');
            $('#jurusan2Error').html('');
            // $('#pendidikan1Error').html('');
            // $('#namaInstitusi1Error').html('');
            // $('#tahunKelulusan1Error').html('');
            // $('#jurusan1Error').html('');

            if($("#pendidikan2").val() == '') {
                $("#pendidikan2Error").html(' * isi pendidikan Terakhir anda. ');
                return false;
            }
            else if($("#namaInstitusi2").val() == ''){
                $("#namaInstitusi2Error").html(' * isi nama Institusi anda. ');
                return false;
            }else if($("#tahunKelulusan2").val() == ''){
                $("#tahunKelulusan2Error").html(' * isi nama tahun kelulusan anda. ');
                return false;
            }else if($("#jurusan2").val() == ''){
                $("#jurusan2Error").html(' * isi nama Jurusan anda. ');
                return false;
            }
            // else if($("#pendidikan1").val() == '') {
            //     $("#pendidikan1Error").html(' * isi pendidikan anda. ');
            //     return false;
            // }
            // else if($("#namaInstitusi1").val() == ''){
            //     $("#namaInstitusi1Error").html(' * isi nama Institusi anda. ');
            //     return false;
            // }else if($("#tahunKelulusan1").val() == ''){
            //     $("#tahunKelulusan1Error").html(' * isi nama tahun kelulusan anda. ');
            //     return false;
            // }else if($("#jurusan1").val() == ''){
            //     $("#jurusan1Error").html(' * isi nama Jurusan anda. ');
            //     return false;
            // }
            else{
                $("#third").show();
                $("#second").hide();
                $("#progressBar").css("width","60%");
                $("#progressText").html("Step - 3");
            }            
        });

        $("#next-3").click(function(e){
            e.preventDefault();
            $('#statusPernikahanError').html('');

            if($("#statusPernikahan").val() == '') {
                $("#statusPernikahanError").html(' * isi status pernikahan anda. ');
                return false;
            }else{
                $("#fourth").show();
                $("#third").hide();
                $("#progressBar").css("width","90%");
                $("#progressText").html("Step - 4");
            }            
        });

        $("#next-4").click(function(){
            
            $("#fifth").show();
            $("#fourth").hide();
            $("#progressBar").css("width","100%");
            $("#progressText").html("Selesai");            
        });       

        $("#prev-2").click(function(){
            $("#second").hide();
            $("#first").show()
            // $("#second").hide();
            $("#progressBar").css("width","10%");
            $("#progressText").html("Step - 1");
        });

        $("#prev-3").click(function(){
            $("#second").show();
            $("#third").hide()
            // $("#second").hide();
            $("#progressBar").css("width","30%");
            $("#progressText").html("Step - 2");
        });

        $("#prev-4").click(function(){
            $("#third").show();
            $("#fourth").hide()
            // $("#second").hide();
            $("#progressBar").css("width","60%");
            $("#progressText").html("Step - 3");
        });

        $("#prev-5").click(function(){
            $("#fourth").show();
            $("#fifth").hide()
            // $("#second").hide();
            $("#progressBar").css("width","90%");
            $("#progressText").html("Step - 4");
        });

    });
</script>