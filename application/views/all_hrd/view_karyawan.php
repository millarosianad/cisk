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
            <br><br><br><h2>Tabel Karyawan</h2><hr />
        </div>
        <div class="col-xs-16">
            
            <a href="<?php echo base_url()."all_hrd/input_karyawan"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah Karyawan</a>

            <a href="<?php echo base_url()."all_hrd/input_divisi"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah Divisi</a>

            <a href="<?php echo base_url()."all_hrd/view_cuti"; ?>  " class="btn btn-danger" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tabel Cuti</a>

            <hr />
        </div>
    </div>

    <?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px"><center>No</center></th>
                            <th><font size="2px"><center>NIK</th>
                            <th><center>Nama</center></th>
                            <th><center>Tanggal Lahir</center></th>
                            <th><center>Kontak</center></th>
                            <th><center>Divisi</center></th>
                            <th><center>- # -</center></th>              
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($query as $hrd) : ?>
                        <tr>        
                            <td width="1"><center><?php echo $no; ?></td>
                            <td><center><?php echo $hrd->nik; ?></td>
                            <td><center><?php echo $hrd->nama_kary;  ?></td>
                            <td><center><?php echo $hrd->tgl_lahir; ?></td> 
                            <td><center><?php echo $hrd->kontak; ?></td> 
                            <td><center><?php echo $hrd->nama_divisi; ?></td>                            
                            <td>
                            <center>
                                <?php
                                    echo anchor('all_hrd/detail_kary/' . $hrd->id, 'view',"class='btn btn-default btn-sm'")." ";
                                    echo anchor('all_hrd/edit_kary/' . $hrd->id, 'edit',"class='btn btn-primary btn-sm'")." ";
                                    echo anchor('all_hrd/detail_kary/' . $hrd->id, 'delete',"class='btn btn-danger btn-sm'")." ";
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