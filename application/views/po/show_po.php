<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    
    </head>
    <body>

    <?php 
        $no = 1;
        echo form_open($url);
    ?>

   
    
    <h2>Konfirmasi Penerimaan Barang (PO)</h2>
    <h3>Maaf menu ini sedang dikembangkan. </h3>
    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th><font size="2px">Tanggal PO</th>
                            <th><font size="2px">No PO</th>
                            <th><font size="2px">No Surat Jalan/DO</th>
                            <th><font size="2px">Supplier</th>
                            <th><font size="2px">Tipe</th>
                            <th><font size="2px"><center>Status Barang</center></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($getpo as $po) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>  
                            <td><font size="2px"><?php echo $po->tglpo; ?></td>
                            <td><font size="2px"><?php echo anchor('all_po/detail_po/'.$po->id_po,$po->nopo); ?></td>
                            <td><?php
                                if($po->no_do==''){
                                    echo "<i>belum ada</i>";
                                }else{?>
                                    <font size="2px"><?php echo anchor('all_po/detail_do/'.$po->no_sales, $po->no_do); ?>
                                    <?php
                                }?>
                            </td>
                            <td><font size="2px"><?php echo $po->namasupp; ?></td>
                            <td><font size="2px">
                                <?php 
                                    if($po->tipe == 'S'){
                                        echo "SPK";
                                    }elseif($po->tipe == 'R'){
                                        echo "Replineshment";
                                    }elseif($po->tipe == 'A'){
                                        echo "Alokasi";
                                    }
                                ?>
                            </td>
                            <td>
                            <center>                                
                                <?php
                                    if($po->status == '1' && $po->no_do != ''){
                                        echo "<font color='blue'><b>barang telah diterima </b></font>";
                                    }elseif($po->status == null && $po->no_do == null){
                                        echo "<font color='grey'><i>barang belum dikirim</i></font>";
                                    }
                                    
                                    else{
                                    echo "maaf, belum bisa digunakan.";
                                }
                                ?>         


                            </center>

                            
                            </td>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>