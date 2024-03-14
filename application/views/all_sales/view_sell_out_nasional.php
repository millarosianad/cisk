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
                    url:"<?php echo base_url(); ?>omzet/buildgroup_target",    
                    data: {kode_supp: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#group").html(data);
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
        $interval=date('Y')-2015;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2015]=''.$i+2015;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <h3>Sell Out Nasional</h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
            </div>
        </div>
    </div>

        <div class="col-xs-2">
            Tahun (1)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Periode Bulan (2)
        </div>

        <div class="col-xs-5">
            <?php        
                $periode=array();
                $periode['1']='Januari';
                $periode['2']='Februari';
                $periode['3']='Maret';
                $periode['4']='April';
                $periode['5']='Mei';
                $periode['6']='Juni';
                $periode['7']='Juli';
                $periode['8']='Agustus';
                $periode['9']='September';
                $periode['10']='Oktober';
                $periode['11']='November';
                $periode['12']='Desember';
                echo form_dropdown('periode', $periode, '0','class="form-control"');
            ?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Supplier (3)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Group Product (4)
        </div>

        <div class="col-xs-5">
                <?php
                    $group=array();
                    $group['0']='--';
                   
                ?>
            <?php  echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"'); ?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <a href="<?php echo base_url()."all_sales/sell_out_nasional_export"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
            <?php echo form_close();?>
        </div>
    <?php $no = 1; ?>

    
    </div>
    <div class="row"> 
    <br><br>
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th width="1"><font size="2px">Kode Produk</th>
                            <th><font size="2px">Nama Produk</th>
                            <th><font size="2px">Unit</th>
                            <th><font size="2px">Value</th>
                            <th><font size="2px">Bulan</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($data as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $x->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $x->namaprod; ?></td>
                            <td><font size="2px"><?php echo number_format($x->unit); ?></td>
                            <td><font size="2px"><?php echo number_format($x->value); ?></td>
                            <td><font size="2px"><?php echo number_format($x->bulan); ?></td>
                            
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