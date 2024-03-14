<!doctype html>
<html>
    <head>
        <title>View Upload</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">
    </head>
    <body>
    <h3>History Upload Data</h3>
    <hr>
    <p>
    <h4><font color="red">Perhatian !!</font></h4>
    <h5>
        <i>Halaman ini akan menampilkan Data Upload ZIP Terakhir. <br><br>
        <strong>Silahkan klik button/tombol "Proses Sekarang" pada tabel di bawah ini !</strong></i>
    </h5>
    <hr>
    </p>
    
    <hr>
    <?php $no = 1; ?>
    <div class="row">        
        <div class="col-xs-10">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th>No</th>   
                        <th>UserID</th>
                        <th>Tanggal Upload terakhir</th>
                        <th>Nama File</th>
                        <th>Status</th>
                        <th><center>Status Proses</center></th>           
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($uploads as $upload) : ?>
                    <tr>                            
                        <td><?php echo $no++; ?></td>               
                        <td><?php echo $upload->userid; ?></td>
                        <td><?php echo $upload->lastupload; ?></td>
                        <td><?php echo $upload->filename; ?></td>
                        <td>
                            <?php
                                if($upload->status == '' || $upload->status == '0'){
                                    echo "<font color='red'><i>belum diproses</i></font>";
                                    
                                }else{
                                    echo "<font color='blue'><i>sudah di proses</i></font>";
                                }
                            ?>
                        </td>
                        <td>
                            <center>
                            <?php
                                if($upload->status == '' || $upload->status == '0'){
                                    echo anchor('proses_data/proses_data_omzet_all_dp/' . $upload->id, 'Proses Sekarang',"class='btn btn-primary btn-sm'");
                                    
                                }else{
                                    echo 'sudah di proses. Terima kasih';
                                }
                                
                            ?>
                            </center>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>                    
            </table>
        </div> 
    </div>

    </body>
</html>