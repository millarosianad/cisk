<!-- Load Datatables dan Bootstrap dari CDN -->
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">


<script type="text/javascript">       
    $(document).ready(function() { 
        $("#subbranch").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>outlet/buildSalesName",    
            data: {id_subbranch: $(this).val()},
            type: "POST",
            success: function(data){
                $("#salesman").html(data);
                }
            });
        });
    });
      
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>


<h2>
<?php echo form_label($page_title);?>
</h2><hr />


<div class='row'>
    <div class="col-md-10">
        <?php 
            echo "<pre>";
                echo "Nocab : ".$nocab."<br>";
                echo "Cycle : ".$cycle." bulan<br>";
                echo "Created date : ".$tglrepl."<br>";
                echo "Hari Kerja : ".$hari_kerja."<br>";
                $new_id_header = $last_id_header + 1 ;
                echo "<hr><br>- AVG : rata2 penjualan(unit) -> sum(unit)/cycle -> bulan sebelumnya<br>";
                echo "- SL : stock level -> mengambil dari tabel mpm.tbl_stok_level<br>";
                echo "- hr : hari kerja -> mengambil dari tabel db_po.t_bulan<br>";
                echo "- Stock_akhir (*) : stock akhir -> mengambil dari tabel ST. Ini digunakan utk nilai repl<br>";
                echo "- Index Stock : hanya untuk informasi -> ((stock_akhir / AVG) * hr)<br>";
                echo "- STDSL : standar stock level -> (AVG / hari kerja) * SL <br>";
                echo "- Sales_lalu : sales(unit) bulan berjalan<br>";
                echo "- Harga : mengambil harga terbaru dari tabel mpm.tabprod inner join prod_detail<br>";
                echo "- Auto_repl1 : stock_akhir - STDSL<br>";
                echo "- Auto_repl2 : stock_akhir - STDSL - Sales Lalu<br>";
                echo "- pesan1 : auto_repl1 / karton<br>";
                echo "- pesan2 : auto_repl2 / karton<br>";
                echo "- unit1 : pesan1 * karton<br>";
                echo "- unit2 : pesan2 * karton<br>";
            echo "</pre>";
        ?>
    </div>
    <div class="col-md-10">
    <a href="<?php echo base_url()."c_repl/export/".$new_id_header; ?>  " class="btn btn-default" role="button"> Export - CSV</a>
    <a href="<?php echo base_url()."c_repl/to_po/".$new_id_header."/001"; ?>  " class="btn btn-warning" role="button" target="_blank"> Deltomed</a>
    <a href="<?php echo base_url()."c_repl/to_po/".$new_id_header."/005"; ?> " class="btn btn-primary" role="button" target="_blank"> Ultra Sakti</a>
    <a href="<?php echo base_url()."c_repl/to_po/".$new_id_header."/002"; ?> " class="btn btn-success" role="button" target="_blank"> Marguna</a>
    <a href="<?php echo base_url()."c_repl/to_po/".$new_id_header."/004"; ?> " class="btn btn-danger" role="button" target="_blank"> Jaya Agung</a>
    </div>
</div>

</div>

<hr>
<?php $no = 1; ?>
<div class="row">        
        <div class="col-xs-12">
            <div class="col-xs-1">
                &nbsp;
            </div>
            
            <div class="col-xs-12">
                <table id="myTable" class="table table-bordered">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</th>
                            <th width="1"><font size="2px">supp</th>
                            <th width="1"><font size="2px">kodeprod</th>
                            <th width="1"><font size="2px">namaprod</th>
                            <th width="1"><font size="2px">avg</th>
                            <th width="1"><font size="2px">sl</th>
                            <th width="1"><font size="2px">stdsl</th>
                            <th width="1"><font size="2px">stok</th>
                            <th width="1"><font size="2px">git</th>
                            <th width="1"><font size="2px">stock_akhir</th>
                            <th width="1"><font size="2px">index_stock</th>
                            <th width="1"><font size="2px">sales_lalu</th>
                            <th width="1"><font size="2px">harga</th>
                            <th width="1"><font size="2px">karton</th>
                            <th width="1"><font size="2px">adjustment</th>
                            <th width="1"><font size="2px">auto_repl1</th>
                            <th width="1"><font size="2px">auto_repl2</th>
                            <th width="1"><font size="2px">pesan1</th>
                            <th width="1"><font size="2px">pesan2</th>
                            <th width="1"><font size="2px">unit1</th>
                            <th width="1"><font size="2px">unit2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($query as $query) : ?>
                        <tr>        
                            <?php

                                if ($query->pesan1 <> '0') {
                                    ?>
                                                               
                                    <td width="1"><font size="2px"><strong>  <center><?php echo $no++; ?></center></td>         
                                    <td width="1"><font size="2px"><strong><?php echo $query->supp; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->kodeprod; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->namaprod; ?></td>               
                                    <td width="1"><font size="2px"><strong><?php echo $query->avg; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->sl; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->stdsl; ?></td>     
                                    <td width="1"><font size="2px"><strong><?php echo $query->stok; ?></td>         
                                    <td width="1"><font size="2px"><strong><?php echo $query->git; ?></td>         
                                    <td width="1"><font size="2px"><strong><?php echo $query->stock_akhir; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->index_stock; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->sales_lalu; ?></td>               
                                    <td width="1"><font size="2px"><strong><?php echo $query->harga; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->karton; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->adjustments; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->auto_repl1; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->auto_repl2; ?></td>               
                                    <td width="1"><font size="2px"><strong><?php echo $query->pesan1; ?> </td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->pesan2; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->unit1; ?></td>
                                    <td width="1"><font size="2px"><strong><?php echo $query->unit2; ?></td>
                                    
                                    <?php
                                } else {
                                    ?>                          
                                    <td width="1"><font size="2px"><center><?php echo $no++; ?></center></td>         
                                    <td width="1"><font size="2px"><?php echo $query->supp; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->kodeprod; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->namaprod; ?></td>               
                                    <td width="1"><font size="2px"><?php echo $query->avg; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->sl; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->stdsl; ?></td>     
                                    <td width="1"><font size="2px"><?php echo $query->stok; ?></td>         
                                    <td width="1"><font size="2px"><?php echo $query->git; ?></td>         
                                    <td width="1"><font size="2px"><?php echo $query->stock_akhir; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->index_stock; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->sales_lalu; ?></td>               
                                    <td width="1"><font size="2px"><?php echo $query->harga; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->karton; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->adjustments; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->auto_repl1; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->auto_repl2; ?></td>               
                                    <td width="1"><font size="2px"><?php echo $query->pesan1; ?> </td>
                                    <td width="1"><font size="2px"><?php echo $query->pesan2; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->unit1; ?></td>
                                    <td width="1"><font size="2px"><?php echo $query->unit2; ?></td>
                                    <?php
                                }?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                'iDisplayLength': 200
            });
        });
        </script>

        <!--jquery dan select2-->
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    placeholder: "Please Select"
                });
            });
        </script>
    </body>
</html>