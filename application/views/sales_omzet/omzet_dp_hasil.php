<div class="col-sm-10">
    - Tahun : <?php echo $tahun; ?> <br>
    - Supplier : <b> <?php echo $namasupp; ?> <br>
    - Group: <?php echo $namagroup; ?><br>
    - Closing Bulan : <?php echo $bulan; ?><br>
    - Tipe Outlet : <?php echo $tipex_1.'-'.$tipex_2.'-'.$tipex_3; ?><hr>
    Informasi !! : Halaman ini hanya menampilkan Value. Untuk melihat nilai OT per bulan silahkan klik Export
    <br>
    <br>
    <a href="<?php echo base_url()."sales_omzet/omzet_dp"; ?>"class="btn btn-dark" role="button">kembali</a>
    <a href="<?php echo base_url()."sales_omzet/export_omzet/"; ?>" class="btn btn-warning" role="button">export (.csv)</a>
</div>
    <br>
    </div>

    <div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">NAMA DP</th>
                            <th><font size="2px">JAN</th>
                            <th><font size="2px">FEB</th>
                            <th><font size="2px">MAR</th>
                            <th><font size="2px">APR</th>
                            <th><font size="2px">MEI</th>
                            <th><font size="2px">JUN</th>
                            <th><font size="2px">JUL</th>
                            <th><font size="2px">AGS</th>
                            <th><font size="2px">SEP</th>
                            <th><font size="2px">OKT</th>
                            <th><font size="2px">NOV</th>
                            <th><font size="2px">DES</th>
                            <th><font size="2px">Total</th>
                            <th><font size="2px">Rata</th>
                            <th width="1"><font size="2px">Upload Terakhir</th>
                            <th width="1"><font size="2px">Closing</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo number_format($a->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($a->b12); ?></td>
                            <td><font size="2px"><?php echo number_format($a->total); ?></td>
                            <td><font size="2px"><?php echo number_format($a->rata); ?></td>
                            <td><font size="2px"><?php echo $a->lastupload; ?></td>
                            <td><font size="2px">
                            <?php 
                                if ($a->status_closing == '0') {
                                    echo "belum";
                                }elseif ($a->status_closing == '1'){                               
                                    echo "closing";
                                }else{
                                    echo " - ";
                                }
                                 ?>
                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    <tfoot>
                            <tr>                
                                    <th width="1"><font size="2px">NAMA DP</th>
                                    <th><font size="2px">JAN</th>
                                    <th><font size="2px">FEB</th>
                                    <th><font size="2px">MAR</th>
                                    <th><font size="2px">APR</th>
                                    <th><font size="2px">MEI</th>
                                    <th><font size="2px">JUN</th>
                                    <th><font size="2px">JUL</th>
                                    <th><font size="2px">AGS</th>
                                    <th><font size="2px">SEP</th>
                                    <th><font size="2px">OKT</th>
                                    <th><font size="2px">NOV</th>
                                    <th><font size="2px">DES</th>
                                    <th><font size="2px">Total</th>
                                    <th><font size="2px">Rata</th>
                                    <th width="1"><font size="2px">Upload Terakhir</th>
                                    <th width="1"><font size="2px">Closing</th>
                                </tr>
                    </tfoot>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>