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

    <?php echo form_open($url);?>



    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['2018']='2018';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }

        $no = 1;
    ?>

    
    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            
        </div>


        <div class="col-xs-6">
            <a href="<?php echo base_url()."all_sales/sell_in/"; ?>  " class="btn btn-default" role="button"> kembali</a>

             <a href="<?php echo base_url()."all_sales/export_sell_in/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
    </div>
    
    
       
    

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th rowspan="2" width="1"><font size="2px">No</font></th>
                            <th rowspan="2" width="1"><font size="2px">NAMA DP</th>
                            <th rowspan="2" width="1"><font size="2px">kodeprod</th>
                            <th rowspan="2" width="1"><font size="2px">namaprod</th>
                            <th colspan="4"><font size="2px"><center>Jan</center></th>
                            <th colspan="4"><font size="2px"><center>Feb</center></th>
                            <th colspan="4"><font size="2px"><center>Mar</center></th>
                            <th colspan="4"><font size="2px"><center>Apr</center></th>
                            <th colspan="4"><font size="2px"><center>Mei</center></th>
                            <th colspan="4"><font size="2px"><center>Jun</center></th>
                            <th colspan="4"><font size="2px"><center>Jul</center></th>

                            <th colspan="4"><font size="2px"><center>Agu</center></th>
                            <th colspan="4"><font size="2px"><center>Sep</center></th>
                            <th colspan="4"><font size="2px"><center>Okt</center></th>
                            <th colspan="4"><font size="2px"><center>Nov</center></th>
                            <th colspan="4"><font size="2px"><center>Des</center></th>
                            
                        </tr>

                        <tr>
                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>

                            <td><center>I</td>
                            <td><center>II</td>
                            <td><center>III</td>
                            <td><center>IV</td>


                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $omzet) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $omzet->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $omzet->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $omzet->namaprod; ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b1_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b2_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b2_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b2_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b2_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b3_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b3_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b3_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b3_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b4_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b4_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b4_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b4_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b5_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b5_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b5_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b5_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b6_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b6_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b6_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b6_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b7_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b7_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b7_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b7_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b8_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b8_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b8_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b8_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b9_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b9_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b9_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b9_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b10_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b10_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b10_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b10_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b11_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b11_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b11_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b11_d); ?></td>

                            <td><font size="2px"><?php echo number_format($omzet->b12_a); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b12_b); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b12_c); ?></td>
                            <td><font size="2px"><?php echo number_format($omzet->b12_d); ?></td>
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