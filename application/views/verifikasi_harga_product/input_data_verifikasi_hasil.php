<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | Verifikasi Harga Product</title>

        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
    
    </head>
    <body>
    <?php echo form_open($url);?>    

    <?php 
        $interval=date('Y')-2013;
        $year=array();
        $year['2020']='2020';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2013]=''.$i+2013;
        }
    ?>

    <div class="row">        
        <div class="col-xs-16">
            <?php echo br(3); ?>
            <h3><?php echo $page_title; ?></h3><hr />
        </div>

        <div class="col-md-3">
            <div class="form-group">
                
                
            </div>
        </div>
    </div>

        <div class="col-xs-3">
            Tahun (*)
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,'','class="form-control"');?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">
            Bulan 
        </div>
        <?php 
            $bulan = array(
                '1'  => 'Januari',
                '2'  => 'Februari',
                '3'  => 'Maret', 
                 '4'  => 'April',
                '5'  => 'Mei',
                '6'  => 'Juni',
                '7'  => 'Juli',
                '8'  => 'Agustus',
                '9'  => 'September',
                '10'  => 'Oktober',
                '11'  => 'November',
                '12'  => 'Desember 2019'               
              );
        ?>
        <div class="col-xs-5">
            <?php echo form_dropdown('bulan', $bulan,'','class="form-control"');?>
        </div>

        <div class="col-xs-11"><br></div>

        <div class="col-xs-3">        
            
        </div>
        <div class="col-xs-5">
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
            <a href="<?php echo base_url()."verifikasi_harga_product/export_verifikasi_harga/"; ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>
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
                            <th ><font size="2px">Kode</th>
                            <th ><font size="2px">Branch</th>
                            <th ><font size="2px">Sub Branch</th>
                            <th ><font size="2px">Kodeprod</th>
                            <th ><font size="2px">Nama Produk</th>
                            <th ><font size="2px">Harga Lama</th>
                            <th ><font size="2px">Harga Terbaru</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($proses as $a) : ?>
                        <tr>
                            <td><font size="2px"><?php echo $a->kode; ?></td>                      
                            <td><font size="2px"><?php echo $a->branch_name; ?></td>
                            <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                            <td><font size="2px"><?php echo $a->namaprod; ?></td>
                            <td><font size="2px"><?php echo $a->harga; ?></td>
                            <td><font size="2px"><?php echo $a->h_dp; ?></td>                         
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>