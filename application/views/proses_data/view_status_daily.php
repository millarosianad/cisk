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

    <?php echo form_open('all_surat_jalan/view_surat_jalan_by_tgl/');?>
    
    

     <div class="row">        
        <div class="col-xs-16">
            <h2>Status Proses Data</h2><hr />
        </div>
        <div>
            <font color="red">
                <?php 
                    echo validation_errors(); 
                    echo br(1);
                ?>
            </font>
        </div>
        <div class="col-xs-16">
            
            <a href="<?php echo base_url()."all_status_proses_data/input_afiliasi"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Tambah Afiliasi</a>
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
                            <th>Afiliasi</th>
                            <th>Tgl Update</th>
                            <th>Tgl Data</th>
                            <th><center>Status</center></th> 
                            <th><center>Keterangan</center></th>
                            <th><center>Action</center></th>        
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($status as $statuss) : ?>
                        <tr>        
                            <td width="5%"><?php echo $no; ?></td>
                            <td width="25%"><?php echo $statuss->nama_afiliasi; ?></td>
                            <td width="15%"><?php echo $statuss->tgl_update; ?></td>
                            <td width="15%"><?php echo $statuss->tgl_data; ?></td>
                            <td width="10%"><?php echo $statuss->ket_status; ?></td>
                            <td width="10%"><?php echo $statuss->keterangan; ?></td>
                            <td width="15%"><center>
                                <?php
                                    echo anchor('all_status_proses_data/detail_status/' . $statuss->id, 'update', ['class' => 'btn btn-success btn-sm']);
                                    echo " | ";
                                    echo anchor('all_status_proses_data/delete_status/' . $statuss->id, 'delete',
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