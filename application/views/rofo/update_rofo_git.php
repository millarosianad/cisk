<?php //echo br(3); 
        $no = 1;
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-11">
                <h3><?php echo $page_title; ?></h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <a href="<?php echo base_url()."all_po/form_rofo/"; ?> " class="btn btn-default" role="button"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> kembali</a>
                <a href="<?php echo base_url()."all_po/export_rofo_update_git/"; ?> " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export tabel rofo (.xls)</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <pre>
Mohon diperhatikan !
1. Export tabel rofo dengan cara klik button 'export tabel rofo'. Proses download akan berjalan
2. Buka excel tabel rofo tsb. Masukkan data-data di kolom GIT. Dan simpan file tsb. (jangan ubah format penyimpanan selain .xls)
3. Upload kembali excel yang sudah ditambahkan GIT. Dan klik button 'Proses upload'.
                </pre>
            </div>
        </div>
        <?php echo form_open_multipart($url);?>
        <div class="row">   
            <div class="col-md-4">
                <input type="file" name="file" id="file" class="form-control" placeholder="file">
            </div>
            <div class="col-md-4">
            <?php echo form_submit('submit','Proses Upload','class="btn btn-primary"')?>
                <?php echo form_close(); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-11">
                <hr>
            </div>
        </div>
    </div>
    <br>
</div>

    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px"><center>No</font></th>
                            <th><font size="2px"><center>Supp</th>
                            <th><font size="2px"><center>Branch</th>
                            <th><font size="2px"><center>SubBranch</th>
                            <th><font size="2px"><center>Kodeprod</th>
                            <th><font size="2px"><center>Namaprod</th>
                            <th><font size="2px"><center>AVG</th>
                            <th><font size="2px"><center>Stock Mei</th>
                            <th><font size="2px"><center>PO_blm_DO</th>
                            <th><font size="2px"><center>GIT</th>
                            <th><font size="2px"><center>PO Juni</th>
                            <th><font size="2px"><center>Total Stock</th>
                            <th><font size="2px"><center>Target Juni</th>
                            <th><font size="2px"><center>Est Stock Akhir Juni</th>
                            <th><font size="2px"><center>Target Juli</th>
                            <th><font size="2px"><center>Est Saldo Berjalan</th>
                            <th><font size="2px"><center>SL (hari)</th>
                            <th><font size="2px"><center>SL (Unit) </th>
                            <th><font size="2px"><center>Purchase Plan (unit)</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td>
                                <center>
                                <font size="2px">
                                    <?php
                                        if ($x->supp == '001') {
                                            $principal = "deltomed";
                                        }elseif ($x->supp == '002') {
                                            $principal = "marguna";
                                        }elseif ($x->supp == '005') {
                                            $principal = "ultra sakti";
                                        }else{
                                            $principal = "all";
                                        }
                                        echo $principal;
                                    ?>
                                </font>
                                
                                </center>
                            </td>
                            <td><font size="2px"><?php echo $x->branch_name; ?></font></td>
                            <td><font size="2px"><?php echo $x->nama_comp; ?></font></td>
                            <td><font size="2px"><?php echo $x->kodeprod; ?></font></td>
                            <td><font size="2px"><?php echo $x->namaprod; ?></font></td>
                            <td><font size="2px"><?php echo $x->avg; ?></font></td>
                            <td><font size="2px"><?php echo $x->stock; ?></font></td>
                            <td><font size="2px"><?php echo $x->po_belum_do; ?></font></td>
                            <td><font size="2px"><?php echo $x->git; ?></font></td>
                            <td><font size="2px"><?php echo $x->po_berjalan; ?></font></td>
                            <td><font size="2px"><?php echo $x->total_stock; ?></font></td>
                            <td><font size="2px"><?php echo $x->target_1; ?></font></td>
                            <td><font size="2px"><?php echo $x->est_1; ?></font></td>
                            <td><font size="2px"><?php echo $x->target_2; ?></font></td>
                            <td><font size="2px"><?php echo $x->est_2; ?></font></td>
                            <td><font size="2px"><?php echo $x->stock_level; ?></font></td>
                            <td><font size="2px"><?php echo $x->stock_level_unit; ?></font></td>
                            <td><font size="2px"><?php echo $x->purchase_plan; ?></font></td>
                           
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>