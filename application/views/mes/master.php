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
                        <div class="col-md-6">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title_store; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url_store); ?>

                                    <div class="form-group">
                                        <label for="storeID">StoreID</label>
                                        <input type="text" class="form-control" id="storeID"  placeholder="Enter StoreID">
                                        <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Nama Store</label>
                                        <input type="text" class="form-control" id="nama_store"  placeholder="Enter nama store">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Status</label>
                                        <select name="status" class="form-control" id="status" required>
                                            <option value="1"> Aktif (default) </option>
                                            <option value="0"> Non Aktif </option>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <?php echo form_submit('submit', 'Simpan Store', 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>    
                                </div>  
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title_user; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url_user); ?>

                                    <div class="form-group">
                                        <label for="userID">UserID</label>
                                        <input type="text" class="form-control" id="userID"  placeholder="Enter UserID">
                                        <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Nama Store</label>
                                        <input type="text" class="form-control" id="nama_store"  placeholder="Enter nama store">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Status</label>
                                        <select name="status" class="form-control" id="status" required>
                                            <option value="1"> Aktif (default) </option>
                                            <option value="0"> Non Aktif </option>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <?php echo form_submit('submit', 'Simpan User', 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>    
                                </div>  
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Tabel Store</h5>
                                </div>
                                
                                <div class="card-block">
                                    <table width="100%" id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                        <thead>
                                            <tr>
                                                <th width="10%"><font size="1px">StoreID</th>
                                                <th width="10%"><font size="1px">Nama Store</th>
                                                <th width="10%"><font size="1px">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                              
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Tabel User</h5>
                                </div>
                                
                                <div class="card-block">
                                    <table width="100%" id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                        <thead>
                                            <tr>
                                                <th width="10%"><font size="1px">StoreID</th>
                                                <th width="10%"><font size="1px">Nama Store</th>
                                                <th width="10%"><font size="1px">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                              
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title_user; ?></h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart($url_user); ?>

                                    <div class="form-group">
                                        <label for="userID">UserID</label>
                                        <input type="text" class="form-control" id="userID"  placeholder="Enter UserID">
                                        <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Nama Store</label>
                                        <input type="text" class="form-control" id="nama_store"  placeholder="Enter nama store">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_store">Status</label>
                                        <select name="status" class="form-control" id="status" required>
                                            <option value="1"> Aktif (default) </option>
                                            <option value="0"> Non Aktif </option>
                                        </select>
                                    </div>                                    
                                    <div class="form-group">
                                        <?php echo form_submit('submit', 'Simpan User', 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>    
                                </div>  
                            </div>
                        </div>



                    </div>
                </div>

                <div class="page-body">
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>History Relokasi</h5> (menampilkan riwayat ajuan relokasi)
                                </div>

                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-auto">
                                        List status = draft -> need supplychain approval -> need finance approval -> approved
                                        <hr>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <table width="100%" id="multi-colum-dt" class="table table-striped table-bordered nowrap" style="display: inline-block; overflow-y: scroll">
                                        <thead>
                                            <tr>
                                                <th width="10%"><font size="1px">NoRelokasi</th>
                                                <th width="10%"><font size="1px">Status</th>
                                                <th class="col-auto"><font size="1px">Nama</th>
                                                <th class="col-auto"><font size="1px">From -> To</th>
                                                <th class="col-auto"><font size="1px">TanggalRelokasi</th>
                                                <th class="col-auto"><font size="1px">Principal</th>
                                                <!-- <th class="col-auto"><font size="1px">Created</th> -->
                                                <th class="col-auto"><font size="1px">Faktur Retur</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                        
                                              
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('database_afiliasi/subbranch') ?>',
        data: {},
        success: function(hasil) {
            $("select[name = from_site]").html(hasil);
            $("select[name = to_site]").html(hasil);
        }
    });
</script>

                        
                        