<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Kalender Data</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>

    <?php echo form_open($url);?>
    <h2>
        <?php echo form_label($page_title);?>
    </h2><h4><font color="red"><i>(ket : masih dalam tahap pengembangan)</i></font></h4>
    <hr />

    <?php $no = 1 ; ?>

    <div class='row'>
        <div class="col-md-2">
            Silahkan Pilih Tahun
        </div>
        <div class="col-md-3">
            <div class="form-group">           
                <?php $ketdd=array(
                        2017=>'2017',                        
                    );?>
                    <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
            </div>
        </div>             
    </div>

    <div class='row'>
        <div class="col-md-2">
            Silahkan Pilih Bulan
        </div>
        <div class="col-md-3">
            <div class="form-group">           
                <?php $ketdd=array(
                        05=>'Mei',
                        
                    );?>
                    <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
            </div>
        </div>
    </div>

    <div class ="row">
        <div class="col-md-2">        
        </div>
        <div class="col-md-2">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>

    <hr>

    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th><center>No</center></th>                                          
                            <th><center>Sub Branch</center></th>
                            <th><center>Tanggal Upload Terakhir</center></th>
                            <th><center>Tanggal Data Terakhir</center></th>
                            <th><center>Zip File</center></th>
                            <th><center>Omzet Terakhir</center></th>
                            <th><center>Status</center></th>
                            <th><center>Lihat History Upload</center></th>                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($query as $querys) : ?>
                        <tr>        
                            <td width="5%">
                                <?php echo $no; ?>                                    
                            </td>
                            <td width="20%">
                                <?php echo $querys->nama_comp; ?>                                  
                            </td>
                            <td width="5%">
                                <center><?php 
                                    echo $querys->lastupload; 
                                ?></center>
                            </td>
                            <td width="10%"><center><?php echo $querys->tgl_data_terakhir." mei";  ?></center></td>
                            <td width="10%"><center><?php echo $querys->filename;  ?></center></td>
                            <td width="10%"><center>Rp.
                            <?php echo number_format($querys->omzet_all); ?></center></td>
                            <td  width="10%">
                            <?php 

                                $status_close =  $querys->status_closing; 
                                if ($status_close == 1) {
                                    echo "<font color = blue><center>Closing</center></font>";
                                }else{
                                    echo "<font color = red><center><i>Belum Closing</i></center></font>";
                                }


                            ?> 
                            </td>                            
                            <td width="5%"><center>
                                <?php
                                    echo anchor('all_kalender_data/history_upload/' . $querys->userid, 'view',"class='btn btn-primary btn-sm'");
                                ?>
                                </center>
                            </td>                                                        
                        </tr>
                        <?php $no++; ?>
                    
                    <?php endforeach; ?>
                    </tbody>
                    </table>
        </div> 
    </div>
        
       <script>
    $(document).ready(function(){
        $('#myTable').DataTable( {
            
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