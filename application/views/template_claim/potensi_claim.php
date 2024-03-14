<div class="card">

    <div class="col-sm-5">
        <a href="<?php echo base_url()."claim/export_ant_group/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
        <a href="<?php echo base_url()."assets/file/loyalty/ketentuan_program_ant_group.jpg"; ?>" class="btn btn-success" target="blank" role="button">ketentuan produk</a>
        <a href="<?php echo base_url()."assets/file/loyalty/Rekap Monitoring Program Loyalty 2020 Update Jun2020.xls"; ?>" class="btn btn-primary" target="blank" role="button">source file</a>
    </div>

    <hr>
    
    <div class="card-block">
    

        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                        <th colspan="6"><center>Data Outlet</th>
                        <th colspan="11"><center>Actual 2020</th>
                        <th colspan="8"><center>Actual Produk Fokus 2020</th>
                        <th colspan="5"><center>Paket</th>
                        <th colspan="6"><center>Total Hadiah LM</th>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <th>SB</th>
                        <th>Kodelang DP</th>
                        <th>Kodelang Alternatif</th>
                        <th>Kodelang Versi Web</th>
                        <th>Outlet</th>
                        <th>Target Semester 1</th>
                        <th>Target Fokus Semester 1</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total Actual</th>
                        <th>Achievement</th>
                        <th>Gap Actual vs Target</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total Actual Produk Fokus</th>
                        <th>Achievement</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($get_program as $key) { ?>
                    <tr>
                        <td><?php echo $key->branch_name; ?></td>
                        <td><?php echo $key->nama_comp; ?></td>
                        <td><?php echo $key->kode_lang_dp; ?></td>
                        <td><?php echo $key->kode_lang_alternatif; ?></td>
                        <td><?php echo $key->kode_lang_versi_web; ?></td>
                        <td><?php echo $key->nama_lang; ?></td>
                        <td><?php echo number_format($key->target_sm1_2020); ?></td>
                        <td><?php echo number_format($key->target_produk_fokus_sm1_2020); ?></td>
                        <td><?php echo number_format($key->b1_2020); ?></td>
                        <td><?php echo number_format($key->b2_2020); ?></td>
                        <td><?php echo number_format($key->b3_2020); ?></td>
                        <td><?php echo number_format($key->b4_2020); ?></td>
                        <td><?php echo number_format($key->b5_2020); ?></td>
                        <td><?php echo number_format($key->b6_2020); ?></td>
                        <td><?php echo number_format($key->actual_sm1_2020); ?></td>
                        <td><?php echo number_format($key->ach). ' %'; ?></td>
                        <td><?php echo number_format($key->gap_actual_vs_target); ?></td>
                        <td><?php echo number_format($key->b1_fokus_2020); ?></td>
                        <td><?php echo number_format($key->b2_fokus_2020); ?></td>
                        <td><?php echo number_format($key->b3_fokus_2020); ?></td>
                        <td><?php echo number_format($key->b4_fokus_2020); ?></td>
                        <td><?php echo number_format($key->b5_fokus_2020); ?></td>
                        <td><?php echo number_format($key->b6_fokus_2020); ?></td>
                        <td><?php echo number_format($key->actual_fokus_sm1_2020); ?></td>
                        <td><?php echo number_format($key->ach_fokus). ' %'; ?></td>
                        <td><?php echo $key->paket_a; ?></td>
                        <td><?php echo $key->paket_b; ?></td>
                        <td><?php echo $key->paket_c; ?></td>
                        <td><?php echo $key->paket_d; ?></td>
                        <td><?php echo $key->paket_e; ?></td>
                        <td><?php echo number_format($key->hadiah_a); ?></td>
                        <td><?php echo number_format($key->hadiah_b); ?></td>
                        <td><?php echo number_format($key->hadiah_c); ?></td>
                        <td><?php echo number_format($key->hadiah_d); ?></td>
                        <td><?php echo number_format($key->hadiah_e); ?></td>
                        <td><?php echo number_format($key->total_hadiah); ?></td>
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
                        <th>Target Semester 1</th>
                        <th>Target Fokus Semester 1</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total Actual</th>
                        <th>Achievement</th>
                        <th>Gap Actual vs Target</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Total Actual Produk Fokus</th>
                        <th>Achievement</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>Total</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>







