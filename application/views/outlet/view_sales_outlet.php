<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->

        <!--
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        -->
        
        <script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/jquery-1.10.2.min.js') ?>"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <!--
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        -->
        <script type="text/javascript" language="javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
        

        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
    </head>
    <body>
    <?php $no = 1; ?>
    <div class="col-xs-16">            
            <h3>Data Outlet</h3><hr />
    </div>
    <div class="col-xs-16">

        <a href="<?php echo base_url()."outlet/data_outlet/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."outlet/export_outlet/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>

        
    </div>
    <hr>
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>                
                        <th>Kode</th>   
                        <th>Outlet</th>
                        <th>Address</th>
                        <th>Tipe</th>
                        <th>Class</th>
                        <th>Rayon</th>
                        <th>kota</th>
                        <th>Jan</th>
                        <th>Feb</th> 
                        <th>Mar</th> 
                        <th>Apr</th> 
                        <th>Mei</th> 
                        <th>Jun</th> 
                        <th>Jul</th> 
                        <th>Agus</th> 
                        <th>Sep</th> 
                        <th>Okt</th> 
                        <th>Nov</th> 
                        <th>Des</th>               
                    </tr>
                </thead>
                <tbody>
                    <?php if(is_array($outlets)): ?>
                    <?php foreach($outlets as $outlet) : ?>
                    <tr>                            
                        <td><?php echo $outlet->kode; ?></td>
                        <td><?php echo $outlet->outlet; ?></td>
                        <td><?php echo $outlet->alamat; ?></td>
                        <td><?php echo $outlet->kode_type; ?></td>
                        <td><?php echo $outlet->kodesalur; ?></td>
                        <td><?php echo $outlet->rayon; ?></td>
                        <td><?php echo $outlet->kota; ?></td>
                        <td><?php echo $outlet->b1; ?></td>
                        <td><?php echo $outlet->b2; ?></td>
                        <td><?php echo $outlet->b3; ?></td>
                        <td><?php echo $outlet->b4; ?></td>
                        <td><?php echo $outlet->b5; ?></td>
                        <td><?php echo $outlet->b6; ?></td>
                        <td><?php echo $outlet->b7; ?></td>
                        <td><?php echo $outlet->b8; ?></td>
                        <td><?php echo $outlet->b9; ?></td>
                        <td><?php echo $outlet->b10; ?></td>
                        <td><?php echo $outlet->b11; ?></td>
                        <td><?php echo $outlet->b12; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
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