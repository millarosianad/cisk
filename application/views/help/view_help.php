<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Help</title>
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
    

    <div class="row">        
        <div class="col-xs-16">
            <h2>Complaine System</h2><hr />
        </div>
        <div class="col-xs-16">
            
            <a href="<?php echo base_url()."all_help/input_complain"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah</a>
            <hr />
        </div>
    </div>

    <div class="row">        
        <div class="col-xs-13">

            <?php 
                echo "<pre>";
                echo "Keterangan ".'<b>"'."Kolom Status".'"</b>'." dalam Complaine System, yaitu :<br><br>";
                echo " - Pending : berarti pengaduan anda belum di proses oleh IT.<br>";
                echo " - On Process : berarti pengaduan anda sedang di tangani oleh IT, mohon tunggu kabar selanjutnya.<br>";
                echo " - Finish : berarti pengaduan anda sudah selesai, silahkan cek kembali permasalahan anda.";
                echo "</pre>";
            ?>
        </div>
    </div>

    <?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th><center>No</center></th>                                          
                            <th><center>Tgl Ajuan</center></th>
                            <th><center>Pelapor</center></th>
                            <th><center>Kategori</center></th>
                            <th><center>Permasalahan</center></th>
                            <th><center>File/Image</center></th>   
                            <th><center>IT</center></th>                  
                            <th><center>Status</center></th>
                            <th><center>#</center></th>                
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($helps as $help) : ?>
                        <tr>        
                            <td width="5%"><?php echo $no; ?></td>
                            <td width="10%"><?php echo $help->tgl_ajuan; ?></td>
                            <td width="10%"><?php echo $help->nama_pelapor." - ".$help->username;  ?></td>
                            <td width="10%"><?php echo $help->nama_kategori; ?></td>
                            <td width="20%"><?php echo $help->masalah; ?></td>
                            <td>
                                 <?php  
                                 $images = [
                                             'src'   => 'uploads/' . $help->file,
                                             'width' => '100'
                                           ];
                                 echo img($images);
                                  ?>
                            </td>                    
                            <td width="10%"><?php echo $help->nama_it; ?></td>
                            <td>
                                <?php 
                                    $var_status = $help->nama_status;
                                    if ($var_status == "PENDING") {
                                        $status = "<font color=red><strong><i>$help->nama_status</font>";
                                    } elseif ($var_status == "ON PROCESS") {
                                        $status = "<font color=green><strong><i>$help->nama_status</font>";
                                    } else {
                                        $status = "<font color=blue><strong><i>$help->nama_status</font>";
                                    }
                                    
                                 ?>
                                <?php //echo $help->nama_status; 
                                    echo $status;
                                ?>
                            </td>
                            <td width="15%"><center>
                                <?php
                                    echo anchor('all_help/detail_complain/' . $help->id, 'view',"class='btn btn-primary btn-sm'");
                                ?>
                                <?php
                                    echo anchor('all_help/edit_complain/' . $help->id, 'edit',"class='btn btn-success btn-sm'");
                                ?>
                                <?php 
                                    echo anchor('all_help/delete_complain/' . $help->id, 'delete',
                                        array('class' => 'btn btn-danger btn-sm',
                                              'onclick'=>'return confirm(\'Are you sure?\')'));
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