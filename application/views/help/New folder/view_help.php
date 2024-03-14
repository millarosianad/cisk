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

    <?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>No</th>                                          
                            <th>Tgl Ajuan</th>
                            <th>User/Nama Pelapor</th>
                            <th>Kategori</th>
                            <th>Permasalahan</th>
                            <th>File/Image</th>                     
                            <th>Status</th>
                            <th><center>View|Edit|Delete</center></th>                
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($helps as $help) : ?>
                        <tr>        
                            <td width="5%"><?php echo $no; ?></td>
                            <td width="10%"><?php echo $help->tgl_ajuan; ?></td>
                            <td width="10%"><?php echo $help->nama_pelapor; ?></td>
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
                            <td><?php echo $help->nama_status; ?></td>
                            <td><center>
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