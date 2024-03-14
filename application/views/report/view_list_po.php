<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View List PO</title>
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
            
            <h3>List PO</h3><hr />
            <p><i>Tampilan pada halaman ini akan disesuaikan berdasarkan Principal User Login yang digunakan</i></p>
            <hr>
        </div>
    </div>

    <?php $no = 1 ; ?> 
    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>No</th>
                            <th>Order</th>
                            <th>Tgl Order</th>
                            <th>Tgl PO</th>
                            <th>Perusahaan</th>
                            <th>Principal</th>
                            <th>No. PO</th>                          
                            <th>Detail</th>                     
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reports as $report) : ?>
                        <tr>        
                            <td><?php echo $no; ?></td>
                            <td><?php echo "ODR".$report->id; ?></td>
                            <td><?php echo $report->created; ?></td>
                            <td><?php echo $report->tglpo; ?></td>
                            <td><?php echo $report->company; ?></td>
                            <th><?php echo $report->namasupp; ?></th>
                            <td><?php echo $report->nopo; ?></td>
                            <td><center>
                                <?php
                                    echo anchor('all_report/detail_po/' . $report->id, 'view', ['class' => 'btn btn-primary btn-sm']);  
                                    //echo anchor('all_report/detail_po/' . $report->id, 'view',"class='btn btn-primary btn-sm'");
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