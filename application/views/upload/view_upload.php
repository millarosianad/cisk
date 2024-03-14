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
        <strong>Jika ada ketidaksesuaian jumlah omzet, silahkan hubungi IT MPM</strong></i>
    </h5>
    <hr>
    </p>
    <?php $no = 1; ?>
    <div class="row">        
        <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th>No</th>   
                        <th>UserID</th>
                        <th>Tanggal Upload terakhir</th>
                        <th>Nama File</th>
                        <th><center>Status Submit</center></th>
                        <th><center>Omzet</center></th> 
                        <th><center>Status Closing</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($uploads as $upload) : ?>
                    <tr>                            
                        <td><?php echo $no++; ?></td>               
                        <td><?php echo $upload->userid; ?></td>
                        <td><?php echo $upload->lastupload; ?></td>
                        <td><?php echo $upload->filename; ?></td>
                        
                        <td><center>
                            <?php
                                if($upload->status == '' || $upload->status == '0'){  
                                    ?>
                                    <a href="<?php echo base_url()."proses_data/submit/".$upload->id ?>" class="btn btn-default" role="button" target="blank">submit sekarang</a>
                                    <?php 
                                }else{                                    
                                    if (strlen($upload->filename)== '12') {
                                        echo "<font color='black'><i>success by web</i></font>";
                                    }else{
                                        echo "<font color='black'><i>success by SDS</i></font>";
                                    }
                                }
                            ?></center>
                        </td>
                        <td><center><?php echo $upload->omzet; ?></center></td>
                        <td><center>
                            <?php
                                if($upload->status_closing == 0){  
                                    echo "<font color='black'><i>Bukan Closing</i></font>";
                                }else{                                    
                                    echo "<font color='blue'><i>Closing</i></font>";
                                }
                            ?></center>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>                    
            </table>
        </div> 
    </div>

    </body>
</html>