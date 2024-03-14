<!doctype html>
<html>
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

        <script type="text/javascript">       
            $(document).ready(function() { 
                $("#supp").click(function(){
                    $.ajax({
                    url:"<?php echo base_url(); ?>all_bsp/buildgroup",    
                    data: {kode_supp: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#group").html(data);
                        }
                    });
                });
            });            
        </script>

        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>
    <?php //echo br(3); ?>
    <?php echo form_open('all_bsp/data_omzet_bsp_hasil/');?>
    <?php //echo form_label($page_title);?>
    <?php 
        //echo form_label(" Year : ");
        //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
        $interval=date('Y')-2010;
        $year=array();
        $year['2010']='2010';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
        //echo br(5);
        //echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");

        $no = 1;
    ?>

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {   
            $supplier['x'] = ' - Pilih Supplier - ';
            $supplier[$value->supp]= $value->namasupp;
        }
        //echo form_dropdown('supp', $options, 'All',"class='form-control'");
    ?>

    <div class="row">        
        <div class="col-xs-16">
            
            <h3>Omzet BSP</h3><hr />
        </div>
        <div class="col-xs-2">
            Tahun (*)
        </div>
        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,date('Y'),'class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>   

        <div class="col-xs-2">
            Group Product
        </div>
        
        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <a href="<?php echo base_url()."all_bsp/export_group/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
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
        echo "supplier : ".$supplier."<br>";
        echo "</pre>";
        echo "</pre>";
    ?>
    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th rowspan="2"><font size="2px"><center><br>No</font></th>
                            <th rowspan="2"><font size="2px"><center><br>NAMA DP</th>
                            <th colspan="2"><font size="2px"><center>Jan</center></th>
                            <th colspan="2"><font size="2px"><center>Feb</center></th>
                            <th colspan="2"><font size="2px"><center>Mar</center></th>
                            <th colspan="2"><font size="2px"><center>Apr</center></th>
                            <th colspan="2"><font size="2px"><center>Mei</center></th>
                            <th colspan="2"><font size="2px"><center>Jun</center></th>
                            <th colspan="2"><font size="2px"><center>Jul</center></th>
                            <th colspan="2"><font size="2px"><center>Agu</center></th>
                            <th colspan="2"><font size="2px"><center>Sep</center></th>
                            <th colspan="2"><font size="2px"><center>Okt</center></th>
                            <th colspan="2"><font size="2px"><center>Nov</center></th>
                            <th colspan="2"><font size="2px"><center>Des</center></th>
                            <th colspan="2"><font size="2px"><center>Total</th>
                            <th colspan="2"><font size="2px"><center>Rata</th>
                        </tr>
                    
                        <tr>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>
                            <td><center>candy</td>
                            <td><center>herbal</td>                            
                        </tr>
                    </thead>

                    <tbody>
                    
                        <?php foreach($omzets as $omzet) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td> 
                            <td><font size="2px"><?php echo $omzet->namacomp; ?></font></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b1_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b1_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b2_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b2_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b3_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b3_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b4_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b4_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b5_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b5_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b6_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b6_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b7_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b7_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b8_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b8_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b9_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b9_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b10_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b10_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b11_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b11_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b12_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->b12_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->total_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->total_herbal); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->rata_candy); ?></font></center></td>
                            <td><center><font size="2px"><?php echo number_format($omzet->rata_herbal); ?></font></center></td>
                            
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