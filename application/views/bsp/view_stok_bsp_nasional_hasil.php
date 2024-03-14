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


    <?php //echo br(3); ?>
    <?php echo form_open($url);?>
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
            <h3><?php  echo $page_title; ?></h3><hr />
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



        <div class="col-xs-6">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>

            <a href="<?php echo base_url()."all_bsp/export_stok/". $tahun."/".$supp."/".$note_x; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>

        </div>
    </div>

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
        echo "<br>";
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
                            <th width="1"><font size="2px">No</font></th>
                            <th><font size="2px">Kode Produk</font></th>
                            <th><font size="2px">Nama Produk</th>
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
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php 
                            $no = 1;
                            foreach($stoks as $st) : 
                        ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>
                            <td><font size="2px"><?php echo $st->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $st->deskripsi; ?></td>
                            <td><font size="2px"><?php echo number_format($st->b1); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b2); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b3); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b4); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b5); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b6); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b7); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b8); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b9); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b10); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b11); ?></td>
                            <td><font size="2px"><?php echo number_format($st->b12); ?></td>
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