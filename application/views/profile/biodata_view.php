

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">

            <div class="row page-titles mx-0">
                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
                    </ol>
                </div>
            </div>
            <!-- row -->

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanFotoDiri;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>
                                
                                <div class="row mb-5">
                                    
                                </div>

                                <h4>Kontak</h4>
                                <!-- <p class="text-muted">Hi, I'm Pikamy, has been the industry standard dummy text ever since the 1500s.</p> -->
                                <ul class="card-profile__info">
                                    <li class="mb-1"><strong class="text-dark mr-4">Nama</strong> <span><?php echo $nama; ?></span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Mobile</strong> <span><?php echo $telp1; ?></span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">WhatsApp </strong> <span><?php echo $telp2; ?></span></li>
                                    <li class="mb-1"><strong class="text-dark mr-4">Email</strong> <span><?php echo $email; ?></span></li>
                                </ul>
                            </div>
                        </div> 
                        
                        <!-- <div class="card">
                            <div class="card-body">
                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanNpwp;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>

                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanBpjsKesehatan;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>

                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanBpjsKetenagaKerjaan;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>

                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanKk;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>

                                <div class="media align-items-center mb-4">
                                    <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanIjazahTerakhir;  ?>" width="80" height="100" alt="">
                                    <div class="media-body">
                                    </div>
                                </div>
                            </div>
                        </div>  -->

                        <div class="card">
                            <div class="card-body"><strong>Scan KTP</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanKtp == '') {
                                            echo "anda belum melengkapi scan ktp";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanKtp;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>

                                <strong>Scan NPWP</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanNpwp == '') {
                                            echo "anda belum melengkapi scan npwp";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanNpwp;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>

                                <strong>Scan BPJS Kesehatan</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanBpjsKesehatan == '') {
                                            echo "anda belum melengkapi scan BPJS Kesehatan";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanBpjsKesehatan;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>

                                <strong>Scan BPJS Ketenagakerjaan</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanBpjsKetenagaKerjaan == '') {
                                            echo "anda belum melengkapi scan BPJS Ketenagakerjaan";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanBpjsKetenagaKerjaan;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>

                                <strong>Scan Kartu Keluarga</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanKk == '') {
                                            echo "anda belum melengkapi scan Kartu Keluarga";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanKk;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>

                                <strong>Scan Ijazah Terakhir</strong>
                                <div class="media align-items-center mb-4">
                                    <?php 
                                        if ($scanIjazahTerakhir == '') {
                                            echo "anda belum melengkapi scan Ijazah Terakhir";
                                        }else{ ?>
                                            <img class="mr-3" src="<?php echo base_url() ."assets/uploads/".$userId."/".$scanIjazahTerakhir;  ?>" width="80" height="100" alt="">                                    
                                        <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div> 

                        

                        
                    </div>
                    <div class="col-lg-8 col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">                    
                                    <a href="<?php echo base_url()."profile/ubahData/"; ?>" class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span><font color="white">Ubah Data</font></a>
        
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3>Informasi Data Diri</h3>
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        Nik
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $nik; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Nomor KTP
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $noKtp; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Tanggal Bergabung di MPM
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $tanggalGabung; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Tempat Tanggal Lahir
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $tempatLahir.' '.$tanggalLahir; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Agama
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $agama; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Jenis Kelamin
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $jenisKelamin; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Golongan Darah
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $golonganDarah; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Alamat Saat Ini
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $alamatSekarang; ?></strong>
                                    </div>
                                    <!-- <div class="col-lg-3">
                                        Alamat Sesuai Ktp
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $alamatKtp; ?></strong>
                                    </div> -->
                                    
                                    <div class="col-lg-3">
                                        Nomor Npwp (alamat)
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $noNpwp." ( ".$alamatNpwp. " ) "; ?></strong>
                                    </div>
                                   
                                    <div class="col-lg-3">
                                        Rekening Bca (atas nama)
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $noBca." ( ".$namaBca. " ) "; ?></strong>
                                    </div>
                                    <div class="col-lg-3">
                                        Telp Darurat (nama)
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaKontakDarurat." ( ".$telp3." - ".$hubungan." ) "; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3>Informasi Karyawan</h3>
                                <div class="row mb-1">
                                    <div class="col-lg-3">
                                        Divisi
                                    </div>
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaDivisi; ?></strong>
                                    </div>
                                    <div class="col-lg-3">
                                        Department
                                    </div>
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaDepartment; ?></strong>
                                    </div>

                                    <div class="col-lg-3">
                                        Jabatan
                                    </div>
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaJabatan; ?></strong>
                                    </div>

                                    <div class="col-lg-3">
                                        Golongan
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaGolongan; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3>Informasi Pendidikan</h3>
                                <div class="row mb-1">
                                    <div class="col-lg-3">
                                        Pendidikan
                                    </div>
                                    
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $pendidikan2; ?></strong>
                                    </div>
                                    <div class="col-lg-3">
                                        Nama Institusi
                                    </div>
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaInstitusi2; ?></strong>
                                    </div>

                                    <div class="col-lg-3">
                                        Tahun Kelulusan
                                    </div>
                                    <div class="col-lg-1">
                                        
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $tahunKelulusan2; ?></strong>
                                    </div>

                                    <div class="col-lg-3">
                                        Jurusan
                                    </div>
                                    <div class="col-lg-1"></div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $jurusan2; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h3>Informasi Keluarga</h3>
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        Status Pernikahan
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $statusPernikahan; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Nama Pasangan & pendidikan terakhir
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaPasangan. ' - '.$pendidikanTerakhirPasangan; ?></strong>
                                    </div>


                                    <div class="col-lg-12">
                                       <br>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        Data Anak Pertama
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaAnakPertama.' - '.$ttlAnakPertama; ?></strong>
                                    </div>

                                    <div class="col-lg-4">
                                        Data Anak Kedua
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaAnakKedua.' - '.$ttlAnakKedua; ?></strong>
                                    </div>
                                    <div class="col-lg-4">
                                        Data Anak Ketiga 
                                    </div>
                                    <div class="col-lg-8">
                                        <strong><?php echo $namaAnakKetiga.' - '.$ttlAnakKetiga; ?></strong>
                                    </div>                                    
                                </div>
                            </div>
                        </div>


                        


                        </div>
                    </div>
                </div>
            </div>
            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script> -->

</body>

</html>