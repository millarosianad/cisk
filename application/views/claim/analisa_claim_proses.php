<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Web | Claim Master</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <?php 
            $interval=date('Y')-2018;
            $year=array();
            $year['2020']='2020';
            for($i=1;$i<=$interval;$i++)
            {
                $year[''.$i+2018]=''.$i+2018;
            }
        ?>
    
    </head>
    <body>
    <?php echo form_open($url);?>

    <div class="row">
            <div class="col-md-9">
                <div class="col-xs-16">            
                    <h3><br><br><?php echo $page_title; ?></h3><hr />
                </div>
                <br>
            </div>
        </div>
        <?php echo form_open_multipart($url);?>
        <div class="row">
            <div class="col-md-11">
                <div class="col-xs-3">            
                    Prinsipal
                </div>
                <div class="col-xs-5">   

                <!-- <?php 
                    foreach($query['data'] as $a) : ?>
                    <?php
                        echo $a['NAMASUPP'];
                    ?>
                <?php endforeach; ?> -->
 
                <?php $jenis_data=array(
                        '001'=>'Deltomed',
                        '002'=>'Marguna',
                        '005'=>'Ultra Sakti'                        
                    );?>
                    <?php echo form_dropdown('supp',$jenis_data,'','class="form-control"');?>

                </div>
                <div class="col-xs-3">            
                    &nbsp;
                </div>                
            </div>
        </div><br>


        <div class="row">
            <div class="col-md-11">
                <div class="col-xs-3">            
                    Periode
                </div>
                <div class="col-xs-5">   

                    <div class="input-group input-daterange">
                        <input type="text" class = 'form-control' id="datepicker2" name="periode1" placeholder="" autocomplete="off">
                        <div class="input-group-addon">to</div>
                            <input type="text" class = 'form-control' id="datepicker" name="periode2" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>         
        </div><br>
        
        <div class="row">
            <div class="col-xs-11">
                <div class="col-xs-3">     
                </div>
                <div class="col-xs-5">  
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                    <a href="<?php echo base_url()."monitor_claim/export_claim/" ?>   " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export</a>
        
                </div>
            </div>
        </div><br>
    
    
       
    <hr />
    <?php 

        echo "<pre>";
        echo "Anda memilih ";
        echo "Periode : ".$periode1." - ".$periode2;
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
                            <th width="1"><font size="2px"><center> No</font></th>
                            <th><font size="2px"><center> Branch</th>
                            <th><font size="2px"><center> SubBranch</th>
                            <th><font size="2px"><center> NoClaim</th>
                            <th><font size="2px"><center> TglClaim</th>
                            <th><font size="2px"><center> Deskripsi</th>
                            <th><font size="2px"><center> Tipe</th>
                            <th><font size="2px"><center> StatusLunas</th>
                            <th><font size="2px"><center> NoPembyKlaim</th>
                            <th><font size="2px"><center> Produk - transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                                        
                        <?php foreach($query as $x) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $x->branch_name; ?></td>
                            <td><font size="2px"><?php echo $x->nama_comp; ?></td>
                            <td><font size="2px"><?php echo $x->no_claim; ?></td>
                            <td><font size="2px"><?php echo $x->tanggal_claim; ?></td>
                            <td><font size="2px"><?php echo $x->claim_descripiton; ?></td>
                            <td><font size="2px"><?php echo $x->tipe_trans; ?></td>
                            <td><font size="2px"><?php echo $x->status_lunas; ?></td>
                            <td><font size="2px"><?php echo $x->no_pembayaran_klaim; ?></td>
                            <td><font size="2px"><center>                                
                            <?php
                                echo anchor("monitor_claim/detail_claim_produk/" . $x->no_claim2, "produk","class='btn btn-primary btn-sm'");
                            ?> - 
                            <?php
                                echo anchor("monitor_claim/detail_claim_transaksi/" . $x->no_claim2, "transaksi","class='btn btn-success btn-sm'");
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
    
    <script src="<?php echo base_url() ?>assets/js/script.js"></script>                       
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