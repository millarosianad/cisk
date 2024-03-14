
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Belum Closing Bulan <?php echo $get_bulan_sekarang; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                            <li><i class="feather icon-minus minimize-card"></i>
                                            </li>
                                            <li><i class="feather icon-refresh-cw reload-card"></i>
                                            </li>
                                            <li><i class="feather icon-trash close-card"></i></li>
                                            <li><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                   <ul>
                                       <?php 
                                        foreach ($get_dp_belum_closing as $a) { ?>
                                            <li><?php echo $a->nama_comp; ?></li>    
                                             
                                        <?php
                                        }
                                       ?><li>..</li>
                                   </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Kalender Data Bulan <?php echo $get_bulan_sekarang; ?></h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                            <li><i class="feather icon-minus minimize-card"></i>
                                            </li>
                                            <li><i class="feather icon-refresh-cw reload-card"></i>
                                            </li>
                                            <li><i class="feather icon-trash close-card"></i></li>
                                            <li><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                
                                <div class="dt-responsive table-responsive">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                                            <thead>
                                                <tr>                
                                                    <th width = "1%"><font size="2px">Branch</th>
                                                    <th><font size="2px">SubBranch</th>
                                                    <th><font size="2px">Tanggal Data <?php echo $get_bulan_sekarang; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($get_tanggal_data as $a) : ?>
                                                <tr>                      
                                                    <td><font size="2px"><?php echo $a->branch_name; ?></td>
                                                    <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                                                    <td><font size="2px"><?php echo $a->tanggal_data; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            
                                            </tbody>
                                            <tfoot>
                                                    <tr>                
                                                        <th><font size="2px">Branch</th>
                                                        <th><font size="2px">SubBranch</th>
                                                        <th><font size="2px">Tanggal Data</th>    
                                                    </tr>
                                            </tfoot>
                                            </table>
                                        </div>


                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-xl-12 col-md-12">
                            <div class="card latest-update-card">
                                <div class="card-header">
                                    <h5>Informasi Tambahan</h5>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li class="first-opt"><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                            <li><i class="feather icon-maximize full-card"></i></li>
                                            <li><i class="feather icon-minus minimize-card"></i>
                                            </li>
                                            <li><i class="feather icon-refresh-cw reload-card"></i>
                                            </li>
                                            <li><i class="feather icon-trash close-card"></i></li>
                                            <li><i class="feather icon-chevron-left open-card-option"></i>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                
                                <ul>
                                    <li>- Dashboard sekarang menampilkan summary kalender data bulan berjalan. Agar dapat lebih cepat mengetahui kecepatan pengiriman data oleh DP</li>
                                    <li>- Raw data 2019 - September 2020 kini sudah bisa diakses di menu raw data versi baru</li>
                                    <li>- Penambahan breakdown class dan type outlet di menu Outlet Transaksi 1x 2x 3x</li>
                                    <li>- Monitoring Stock Deltomed <span class="pcoded-badge label label-warning">menu baru</span></li>
                                    <li>- Actual VS Target <span class="pcoded-badge label label-warning">menu baru</span></li>
                                    <li>- Stock Akhir dan DOI <span class="pcoded-badge label label-warning">menu baru</span></li>
                                    <li>- Line Sold <span class="pcoded-badge label label-warning">menu baru</span></li>
                                    <li>- Sales dan OT Harian <span class="pcoded-badge label label-warning">menu baru</span></li>
                                    <li>- Informasi Harga Jual DP <span class="pcoded-badge label label-warning">menu baru</span></li>
                                </ul>

                                <br><br>
                                <h5>Untuk request data, ide, saran, kritik atau apapun mengenai website ini, bisa menghubungi IT</h5><br>
                                
                            </div>
                        </div>

                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
