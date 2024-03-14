<div class="card">

    <div class="col-sm-7">
        <a href="<?php echo base_url()."claim/export_ant_group_q3/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
        <a href="<?php echo base_url()."assets/file/loyalty/ketentuan_program_ant_group_q3.jpg"; ?>" class="btn btn-success" target="blank" role="button">ketentuan produk</a>
        <a href="<?php echo base_url()."assets/file/loyalty/Rekap Loyalty Ant Q3 Jul-Sep 20.xls"; ?>" class="btn btn-primary" target="blank" role="button">source file</a>
        <a href="<?php echo base_url()."claim/ant_group_sm1"; ?>" class="btn btn-dark" target="blank" role="button"> Loyalty Sebelumnya >></a>
    </div>

    <hr>
    
    <div class="card-block">
    

        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                <thead>
                    <tr>
                        <th colspan="3"><center>DP</th>
                        <th colspan="3"><center>Outlet Versi DP</th>
                        <th colspan="2"><center>Outlet Versi Web</th>
                        <th colspan="10"><center>Jan - Juni 2020</th>
                        <th colspan="6"><center>Paket</th>
                        <th colspan="20"><center>Antangin Group Q3</th>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <th>SubBranch</th>
                        <th>DP</th>
                        <th>Kode Outlet DP</th>
                        <th>Kode Outlet Alternatif</th>
                        <th>Nama Outlet</th>
                        <th>Kode Outlet</th>
                        <th>Nama Outlet</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Actual Semester 1</th>
                        <th>Average</th>
                        <th>Growth 20 Persen</th>
                        <th>Estimasi Target Q3</th>
                        <th>S</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>Total Target Q3</th>
                        <th>Total Target Fokus</th>
                        <th>Growth</th>
                        <th>Juli</th>
                        <th>Agustus</th>
                        <th>September</th>
                        <th>Total Actual Q3</th>
                        <th>Ach</th>
                        <th>Gap Actual vs Target</th>
                        <th>Juli Fokus</th>
                        <th>Agustus Fokus</th>
                        <th>September Fokus</th>
                        <th>Total Actual Fokus Q3</th>
                        <th>Ach Fokus</th>
                        <th>Gap Actual Fokus vs Target</th>
                        <th>LM S</th>
                        <th>LM A</th>
                        <th>LM B</th>
                        <th>LM C</th>
                        <th>LM D</th>
                        <th>LM E</th>
                        <th>Total LM</th>

                    </tr>
                </thead>
                <tbody>
                <?php foreach ($get_sales as $key) { ?>
                    <tr>
                        <td><?php echo $key->branch_name; ?></td>
                        <td><?php echo $key->nama_comp; ?></td>
                        <td><?php echo $key->dp; ?></td>
                        <td><?php echo $key->kode_outlet; ?></td>
                        <td><?php echo $key->kode_outlet_alternatif; ?></td>
                        <td><?php echo $key->nama_outlet; ?></td>
                        <td><?php echo $key->kode_lang_web; ?></td>
                        <td><?php echo $key->nama_lang_web; ?></td>
                        <td><?php echo $key->jan; ?></td>
                        <td><?php echo $key->feb; ?></td>
                        <td><?php echo $key->mar; ?></td>
                        <td><?php echo $key->apr; ?></td>
                        <td><?php echo $key->mei; ?></td>
                        <td><?php echo $key->jun; ?></td>
                        <td><?php echo number_format($key->actual_semester_1); ?></td>
                        <td><?php echo number_format($key->average); ?></td>
                        <td><?php echo number_format($key->growth_20persen); ?></td>
                        <td><?php echo number_format($key->est_target_q3); ?></td>
                        <td><?php echo $key->paket_s; ?></td>
                        <td><?php echo $key->paket_a; ?></td>
                        <td><?php echo $key->paket_b; ?></td>
                        <td><?php echo $key->paket_c; ?></td>
                        <td><?php echo $key->paket_d; ?></td>
                        <td><?php echo $key->paket_e; ?></td>
                        <td><?php echo number_format($key->total_target_q3); ?></td>
                        <td><?php echo number_format($key->target_produk_fokus); ?></td>
                        <td><?php echo $key->growth; ?></td>
                        <td><?php echo number_format($key->b7); ?></td>
                        <td><?php echo number_format($key->b8); ?></td>
                        <td><?php echo number_format($key->b9); ?></td>
                        <td><?php echo number_format($key->total_actual_q3); ?></td>
                        <td><?php echo number_format($key->ach). ' %'; ?></td>
                        <td><?php echo number_format($key->gap_actual_vs_target); ?></td>
                        <td><?php echo number_format($key->b7_fokus); ?></td>
                        <td><?php echo number_format($key->b8_fokus); ?></td>
                        <td><?php echo number_format($key->b9_fokus); ?></td>
                        <td><?php echo number_format($key->total_fokus_actual_q3); ?></td>
                        <td><?php echo number_format($key->ach_fokus). ' %'; ?></td>
                        <td><?php echo number_format($key->gap_actual_fokus_vs_target); ?></td>
                        <td><?php echo number_format($key->lm_s); ?></td>
                        <td><?php echo number_format($key->lm_a); ?></td>
                        <td><?php echo number_format($key->lm_b); ?></td>
                        <td><?php echo number_format($key->lm_c); ?></td>
                        <td><?php echo number_format($key->lm_d); ?></td>
                        <td><?php echo number_format($key->lm_e); ?></td>
                        <td><?php echo number_format($key->total_lm); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Branch</th>
                        <th>SubBranch</th>
                        <th>DP</th>
                        <th>Kode Outlet DP</th>
                        <th>Kode Outlet Alternatif</th>
                        <th>Nama Outlet</th>
                        <th>Kode Outlet</th>
                        <th>Nama Outlet</th>
                        <th>jan</th>
                        <th>feb</th>
                        <th>mar</th>
                        <th>apr</th>
                        <th>mei</th>
                        <th>jun</th>
                        <th>Actual Semester 1</th>
                        <th>Average</th>
                        <th>Growth 20 Persen</th>
                        <th>Estimasi Target Q3</th>
                        <th>S</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>Total Target Q3</th>
                        <th>Total Target Fokus</th>
                        <th>Growth</th>
                        <th>Juli</th>
                        <th>Agustus</th>
                        <th>September</th>
                        <th>Total Actual Q3</th>
                        <th>Ach</th>
                        <th>Gap Actual vs Target</th>
                        <th>Juli Fokus</th>
                        <th>Agustus Fokus</th>
                        <th>September Fokus</th>
                        <th>Total Actual Fokus Q3</th>
                        <th>Ach Fokus</th>
                        <th>Gap Actual Fokus vs Target</th>
                        <th>LM S</th>
                        <th>LM A</th>
                        <th>LM B</th>
                        <th>LM C</th>
                        <th>LM D</th>
                        <th>LM E</th>
                        <th>Total LM</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>







