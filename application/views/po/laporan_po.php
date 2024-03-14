<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Site MPM | Report PO</title>

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
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
    ?>

        <div class="row">        
        <div class="col-xs-16">            
            <h3><?php echo br(3).' '.$page_title; ?></h3><hr />
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
            Bulan (2)
        </div>

        <div class="col-xs-5">
            <?php        
                $bulan=array();
                $bulan['0']='- pilih bulan -';
                $bulan['1']='Januari';
                $bulan['2']='Februari';
                $bulan['3']='Maret';
                $bulan['4']='April';
                $bulan['5']='Mei';
                $bulan['6']='Juni';
                $bulan['7']='Juli';
                $bulan['8']='Agustus';
                $bulan['9']='September';
                $bulan['10']='Oktober';
                $bulan['11']='November';
                $bulan['12']='Desember';
                echo form_dropdown('bulan', $bulan, '0','class="form-control"');
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
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <a href="<?php echo base_url()."all_po/export_laporan_po/" ?> " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export</a>
        
            <?php echo form_close();?>

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
                            <th width="1"><font size="2px">Company</th>
                            <th width="1"><font size="2px">Branch</th>
                            <th width="1"><font size="2px">SubBranch</th>
                            <th><font size="2px">Tipe</th>
                            <th><font size="2px">Tgl PO</th>
                            <th><font size="2px">Kodeprod</th>
                            <th><font size="2px">NamaProd</th>
                            <th><font size="2px">Group</th>
                            <th><font size="2px">Qty</th>
                            <th><font size="2px">Harga</th>
                            <th><font size="2px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($hasil as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>   
                            <td><font size="2px"><?php echo $x->company; ?></td>            
                            <td><font size="2px"><?php echo $x->branch_name; ?></td>
                            <td><font size="2px"><?php echo $x->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $x->tipe; ?></td>
                            <td><font size="2px"><?php echo $x->tglpo; ?></td>
                            <td><font size="2px"><?php echo $x->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $x->namaprod; ?></td>
                            <td><font size="2px"><?php echo $x->nama_group; ?></td>
                            <td><font size="2px"><?php echo number_format($x->banyak); ?></td>
                            <td><font size="2px"><?php echo $x->harga; ?></td>
                            <td><font size="2px"><?php echo number_format($x->total); ?></td>
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