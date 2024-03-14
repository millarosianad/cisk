<div class="card">

    <div class="col-sm-5">
        <a href="<?php echo base_url()."report_sales/export_ob_herbal/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
        <a href="<?php echo base_url()."assets/file/loyalty/ketentuan_program_ob_herbal.jpg"; ?>" class="btn btn-success" target="blank" role="button">ketentuan produk</a>
        <a href="<?php echo base_url()."assets/file/loyalty/Rekap Monitoring Program Loyalty 2020 Update Jun2020.xls"; ?>" class="btn btn-primary" target="blank" role="button">source file</a>
    </div>

    <hr>
    
    <div class="card-block">
    

        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                        <th colspan="6"><center>Data Outlet</th>
                        <th colspan="10">2019</th>
                        <th colspan="8"><center>2020</th>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <th>SB</th>
                        <th>Kodelang DP</th>
                        <th>Kodelang Alternatif</th>
                        <th>Kodelang Versi Web</th>
                        <th>Outlet</th>
                        <th>Total Sales</th>
                        <th>AVG Sales</th>
                        <th>Class</th>
                        <th>Ajuan Class Baru</th>
                        <th>Rata2 Target Baru</th>
                        <th>Growth</th>
                        <th>Total Target Sales All 3 Bulan</th>
                        <th>Bonus Cash Back</th>
                        <th>Value Cash Back</th>
                        <th>Note</th>
                        <th>Class</th>
                        <th>Target</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total</th>
                        <th>Achievement</th>
                        <th>GAP</th>                        
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($get_data as $key) { ?>
                    <tr>
                        <td><?php echo $key->branch_name; ?></td>
                        <td><?php echo $key->nama_comp; ?></td>
                        <td><?php echo $key->kode_lang; ?></td>
                        <td><?php echo $key->kode_lang_alternatif; ?></td>
                        <td><?php echo $key->kode_lang_web; ?></td>
                        <td><?php echo $key->nama_lang; ?></td>
                        <td><?php 
                        if($key->total_sales_2019==''){
                            $total_sales_2019 = '0';
                        }else{
                            $total_sales_2019 = $key->total_sales_2019;
                        }
                        echo number_format($total_sales_2019); ?></td>
                        <td><?php 
                        if($key->avg==''){
                            $avg = '0';
                        }else{
                            $avg = $key->avg;
                        }
                        echo number_format($avg); ?></td>
                        <td><?php echo $key->kelas; ?></td>
                        <td><?php echo $key->ajuan_kelas_baru; ?></td>
                        <td><?php 
                        if($key->rata_target_baru==''){
                            $rata_target_baru = '0';
                        }else{
                            $rata_target_baru = $key->rata_target_baru;
                        }
                        echo number_format($rata_target_baru); ?></td>
                        <td><?php echo $key->growth; ?></td>
                        <td><?php echo number_format($key->total_target_3_bulan); ?></td>
                        <td><?php echo $key->bonus_cash_back. ' %'; ?></td>
                        <td><?php echo number_format($key->value_cash_back); ?></td>
                        <td><?php echo $key->note; ?></td>
                        <td><?php echo $key->class; ?></td>
                        <td><?php 
                        if($key->target==''){
                            $target = '0';
                        }else{
                            $target = $key->target;
                        }
                        echo number_format($target); ?></td>
                        <td><?php echo number_format($key->b4); ?></td>
                        <td><?php echo number_format($key->b5); ?></td>
                        <td><?php echo number_format($key->b6); ?></td>
                        <td><?php echo number_format($key->total); ?></td>
                        <td><?php echo $key->ach. ' %'; ?></td>
                        <td><?php echo number_format($key->gap); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Branch</th>
                        <th>SB</th>
                        <th>Kodelang DP</th>
                        <th>Kodelang Alternatif</th>
                        <th>Kodelang Versi Web</th>
                        <th>Outlet</th>
                        <th>Total Sales</th>
                        <th>AVG Sales</th>
                        <th>Class</th>
                        <th>Ajuan Class Baru</th>
                        <th>Rata2 Target Baru</th>
                        <th>Growth</th>
                        <th>Total Target Sales All 3 Bulan</th>
                        <th>Bonus Cash Back</th>
                        <th>Value Cash Back</th>
                        <th>Note</th>
                        <th>Class</th>
                        <th>Target</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total</th>
                        <th>Achievement</th>
                        <th>GAP</th>      
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>







