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
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <?php 
        $interval=date('Y')-2010;
        $year=array();
        $year['2018']='2018';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

    
    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3>Data Omzet</h3><hr />
        </div>

        <div class="col-xs-16">
            <div class="form-group">
                <?php
                    $group=array();
                    $group['0']='--';                   
                ?>
            </div>
        </div>

        <div class="col-xs-2">
            Tahun (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Group Product (*)
        </div>

        <div class="col-xs-5">
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="subbranch"'); ?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-5">
            
            <?php
                $data = array(
                  'menuid'  => $getmenuid
                );
            echo form_hidden($data);
            //echo "menuid di view : ".$getmenuid;
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>
        <div class="col-xs-2">
        </div>
        <div class="col-xs-6">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

<a href="<?php echo base_url()."omzet/export_omzet_group_us/". $tahun."/".$supp."/".$note_x; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
        </div>
        
       
    </div>
    
    


       
    <hr />
    <?php 

        if ($supp == '000') {
            $supplier = '4 besar (deltomed, ultra sakti, marguna, jaya agung';
        } elseif ($supp == '001') {
            $supplier = 'deltomed';
        } elseif ($supp == '002') {
            $supplier = 'marguna';
        } elseif ($supp == '003') {
            $supplier = 'jamu jago';
        } elseif ($supp == '004') {
            $supplier = 'jaya agung';
        } elseif ($supp == '005') {
            $supplier = 'ultra sakti';
        } elseif ($supp == '009') {
            $supplier = 'Unilever';
        } elseif ($supp == 'XXX') {
            $supplier = 'all supplier';
        }
         else {
           $supplier = 'belum dipilih';
        }
        
        echo "<pre>";
        echo "Anda memilih ";
        echo "tahun : ".$tahun." | ";
        echo "supplier : ".$supplier." | ";
        echo "group : ".$note."<br>";
        echo "</pre>";
        $no = 1;
    ?>

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover"> 
                    <thead>
                        <tr>                
                            <th rowspan="1"><font size="2px"><center><br>No</font></th>
                            <th rowspan="1"><font size="2px"><center><br>NAMA DP</th>
                            <th colspan="1"><font size="2px"><center>Jan</center></th>
                            <th colspan="1"><font size="2px"><center>Feb</center></th>
                            <th colspan="1"><font size="2px"><center>Mar</center></th>
                            <th colspan="1"><font size="2px"><center>Apr</center></th>
                            <th colspan="1"><font size="2px"><center>Mei</center></th>
                            <th colspan="1"><font size="2px"><center>Jun</center></th>
                            <th colspan="1"><font size="2px"><center>Jul</center></th>
                            <th colspan="1"><font size="2px"><center>Agu</center></th>
                            <th colspan="1"><font size="2px"><center>Sep</center></th>
                            <th colspan="1"><font size="2px"><center>Okt</center></th>
                            <th colspan="1"><font size="2px"><center>Nov</center></th>
                            <th colspan="1"><font size="2px"><center>Des</center></th>
                            <th colspan="1"><font size="2px"><center>Total</th>
                            <th colspan="1"><font size="2px"><center>Rata</th>
                            <th rowspan="1"><font size="2px"><center><br>Upload Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td> 
                            <td><font size="2px"><?php echo $omzet->nama_comp; ?></font></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b1_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b2_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b3_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b4_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b5_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b6_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b7_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b8_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b9_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b10_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b11_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b12_fr); ?></font></center></td>
                            
                            <td><center><font size="2px"><?php echo number_format($omzet->total_fr); ?></font></center></td>
                           <td><center><font size="2px"><?php echo number_format($omzet->rata_fr); ?></font></center></td>
                            <td><center><font size="2px"><?php echo substr($omzet->maxupload, 0,11); ?></font></center></td>
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
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>