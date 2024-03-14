<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">
                        <!-- form mpi -->
                        <div class="col-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-12">
                                            <?php echo form_open($url);?>
                                            <div>
                                                Tahun
                                            </div>
                                            <div class="col-6">
                                                <?php 
                                                $year = date("Y");
                                                    $tahun = array(
                                                        "$year"  => "$year",
                                                        // "2021"  => "2021"          
                                                        );
                                                ?>
                                                <?php echo form_dropdown('tahun', $tahun,'','class="form-control"');?>
                                            </div>
                                            <div>
                                                Unit / Value
                                            </div>
                                            <div class="col-6">
                                                <?php 
                                                $uv = array(
                                                    '1'  => 'Unit',
                                                    '2'  => 'Value',          
                                                    );
                                            ?>
                                                <?php echo form_dropdown('uv', $uv,'','class="form-control"');?>
                                            </div>

                                            <div>
                                                Group By
                                            </div>
                                            <div class="col-6">

                                                <?php 
                                                $group_by = array(
                                                    '1'  => 'Kode Cabang',
                                                    '2'  => 'Kode Produk',
                                                    '3'  => 'Kode Cabang dan Kode Produk ',          
                                                    );
                                            ?>
                                                <?php echo form_dropdown('group_by', $group_by,'','class="form-control"');?>
                                            </div>

                                            <div>
                                                Tipe Outlet
                                            </div>
                                            <div class="col-6">
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" name="apotik" value="1"><span> Apotik
                                                        (tanpa
                                                        kimia farma)</span> &nbsp;</span>
                                                </label>
                                                <label class="fancy-checkbox">
                                                    <input type="checkbox" name="kimia_farma" value="1"><span>
                                                        Apotik
                                                        Kimia Farma</span> &nbsp;</span>
                                                </label>
                                            </div>
                                            <br>
                                            <div>
                                                <?php echo form_submit('submit','Proses','class="btn btn-primary btn-round btn-sm"');?>
                                                <?php echo form_close();?>
                                            </div>
                                            <hr>
                                            <pre>
<h5>Catatan</h5>
- klsout (kelas outlet) MPI terdiri dari : APOTIK, MINIMARKET, P&D, PBF, RS PEMERINTAH, RS SWASTA, SUPERMARKET, TOKO OBAT

- Centang Apotik (tanpa kimia farma) artinya : menampilkan semua sales dengan kondisi "klsout = apotik" dan "namalang <b><u>tidak</u></b> mengandung kata kimia farma"  
- Centang Apotik Kimia Farma artinya : menampilkan semua sales dengan kondisi "klsout = apotik" dan "namalang yang didalamnya mengandung kata kimia farma"  
- Centang keduanya artinya : menampilkan semua sales dengan kondisi "klsout = apotik"
- Tidak Centang keduanya artinya : menampilkan semua sales dengan tidak memperhatikan kondisi apapun
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>