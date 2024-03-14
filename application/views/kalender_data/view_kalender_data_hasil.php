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
    </h2>
    <hr />

    <?php $no = 1 ; ?>

    <?php 
        echo "<pre>";
        echo "<h4>Informasi !!</h4>";
        echo "Halaman ini menampilkan data <strong>TANGGAL TERAKHIR TRANSAKSI</strong> setiap bulannya<br>";
        echo "</pre>";
    ?>
    <hr>

    <div class='row'>
        <div class="col-md-2">
            Silahkan Pilih Jenis Data
        </div>
        <div class="col-md-3">
            <div class="form-group">           
                <?php $jenis_data=array(
                        1=>'Data Text MPM',
                        2=>'Data Upload Website'                        
                    );?>
                    <?php echo form_dropdown('jenis_data',$jenis_data,'','class="form-control"');?>
                    
            </div>
        </div> 
           
    </div>

    <div class='row'>
        <div class="col-md-2">
            Silahkan Pilih Tahun
        </div>
        <div class="col-md-2">
            <div class="form-group">           
                <?php $tahun=array(
                        2020=>'2020',                       
                    );?>
                    <?php echo form_dropdown('tahun',$tahun,'','class="form-control"');?>
                    <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
            </div>
        </div> 
           
    </div>
    <hr>
    <?php  
        echo "<pre>";
            echo "Anda memilih ";
            echo "tahun : ".$tahun_pilih." | ";
            echo $jenis_data_pilih_x."";
        echo "</pre>"; 
    ?>
    <hr>

    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th><center>No</center></th>                                          
                            <th><center>Sub Branch</center></th>
                            <th><center>Jan</center></th>
                            <th><center>Feb</center></th>   
                            <th><center>Mar</center></th>   
                            <th><center>Apr</center></th>   
                            <th><center>Mei</center></th>   
                            <th><center>Jun</center></th>                
                            <th><center>Jul</center></th>   
                            <th><center>Agus</center></th>   
                            <th><center>Sep</center></th>   
                            <th><center>Okt</center></th> 
                            <th><center>Nov</center></th>   
                            <th><center>Des</center></th>
                            <?php 

                                if ($jenis_data_pilih_x == "Data Text MPM")
                                {

                                } else{
                                    echo '<th><center>History</center></th>';
                                }

                            ?>
                            
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
                            <center>
                                <?php
                                    if ($querys->januari == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->januari." jan"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->februari == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->februari." feb"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->maret == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->maret." mar"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->april == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->april." apr"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->mei == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->mei." mei"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->juni == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->juni." jun"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->juli == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->juli." jul"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->agustus == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->agustus." agus"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->september == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->september." sep"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->oktober == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->oktober." okt"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->november == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->november." nov"; 
                                    }                                    
                                ?>                                
                            </center></td>
                            <td width="5%">
                            <center>
                                <?php
                                    if ($querys->desember == 0) {
                                         echo "-";
                                     } else{
                                        echo $querys->desember." des"; 
                                    }                                    
                                ?>                                
                            </center>

                            </td>
                            <?php
                                if ($jenis_data_pilih_x == "Data Text MPM")
                                {
                                    ?>

                                    <?php

                                }else{
                                    
                                    ?>
                                        <td width="5%">
                                            <center>                                
                                                <?php
                                                    echo anchor('all_kalender_data/history_upload/' . $querys->kode_comp, 'view',"class='btn btn-primary btn-sm'");
                                                ?>                              
                                            </center>
                                        </td>
                                    <?php
                                }
                            ?>
                            
                                
                                                             
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