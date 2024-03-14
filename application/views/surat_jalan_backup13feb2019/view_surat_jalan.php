<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Surat Jalan</title>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

        <!-- Load SCRIPT.JS which will create datepicker for input field  -->
        
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>

    </head>
    <body>

   
 
     <div class="row">        
        <div class="col-xs-13">
            <h2>Tabel Surat Jalan</h2><hr />
        </div>
        <?php echo form_open('all_surat_jalan/print_range');?>
        
        <div class="col-xs-13">
            
            <div class="col-xs-2">
                <input type="text" class="form-control" id="datepicker3" name="start" placeholder="start date">                
            </div>
            <div class="col-xs-2">
                <input type="text" class="form-control" id="datepicker2" name="end" placeholder="end date">                       
            </div>
            <div class="col-xs-3">
                <?php $ketdd=array(
                                    1=>'Faktur Lunas',
                                    0=>'Copy Faktur',
                                    2=>'Faktur Lunas (Grup DP)'
                                    );?>
                <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <?php
                        $select_values = 'all';
                        foreach($query->result() as $value)
                        {
                            $x[$value->grup_lang]= $value->grup_nama; 

                        }
                        echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
                        
                    ?>
                </div>
             </div>

            <div class="col-xs-2">        
                <?php echo form_submit('submit',' Print Range','class = "btn btn-danger"'); ?>
        
                <?php echo form_close();?>
            </div>
        
        </div>

     

          


        
        <div class="col-xs-13">&nbsp;<hr></div>
        
        <?php echo form_open('all_surat_jalan/view_surat_jalan_by_tgl/');?>
        <div class="col-xs-11">
            <font color="red">
                <?php 
                    echo validation_errors(); 
                    echo br(1);
                ?>
            </font>
        </div> 
        <div class="col-xs-13">  
            <div class="col-xs-3">            
                <input type="text" class="form-control" id="datepicker" name="tgl" placeholder="masukkan tanggal">
            </div>

            <div class="col-xs-6"> 
                <?php echo form_submit('submit','Cari Berdasarkan Tanggal','class="btn btn-success"');?>
                <?php echo form_close();?>
                <a href="<?php echo base_url()."all_surat_jalan/input_surat_jalan"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah Surat Jalan</a>
            </div>
        </div>
    </div>

    <br>

    <?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>No</th>
                            <th>Client</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                            <th><center>Print</center></th> 
                            <th><center>Amplop_K</center></th>
                            <th><center>Amplop_B</center></th>
                            <th><center>Delete</center></th>               
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($surat_jalan as $surat_jalans) : ?>
                        <tr>        
                            <td width="5%"><?php echo $no; ?></td>
                            <td width="25%">                                
                                <?php 
                                echo anchor('all_surat_jalan/edit/' . $surat_jalans->kode_lang, $surat_jalans->nama_lang);
                                    //echo $surat_jalans->nama_lang; 
                                ?>                                    
                            </td>
                            <td width="10%"><?php echo $surat_jalans->keterangan; ?></td>
                            <td width="10%"><?php echo $surat_jalans->tanggal; ?></td>
                            <td><center>
                                <?php
                                    //menambah variabel 'faktur lunas atau copy faktur'
                                    if ($surat_jalans->keterangan == "Copy Faktur") {
                                        $x = '0';
                                    } else {
                                        $x = '1';
                                    }                                    

                                    //echo anchor('all_surat_jalan/print_surat/' . $surat_jalans->id, ' ',"class='glyphicon glyphicon-print'");

                                    echo anchor('all_surat_jalan/print_surat/' . $surat_jalans->id .'/'.$x, ' ',array('class' => 'glyphicon glyphicon-print', 'target' => 'blank'));

                                ?>
                                </center>                           
                            </td>
                            <td><center>
                                <?php
                                    echo anchor('all_surat_jalan/amplop/' . $surat_jalans->kode_lang, ' ',array('class' => 'glyphicon glyphicon-print', 'target' => 'blank'));
                                ?>
                                </center>                           
                            </td>
                            <td><center>
                                <?php
                                    echo anchor('all_surat_jalan/amplop_coklat/' . $surat_jalans->kode_lang, ' ',array('class' => 'glyphicon glyphicon-print', 'target' => 'blank'));
                                ?>
                                </center>                           
                            </td>
                            
                            <td><center>
                                <?php
                                    echo anchor('all_surat_jalan/delete/' . $surat_jalans->id . '/' . $surat_jalans->kode_lang, ' ',array('class' => 'glyphicon glyphicon-remove','onclick'=>'return confirm(\'Are you sure?\')'));
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