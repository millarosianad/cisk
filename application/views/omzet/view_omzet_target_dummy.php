<!DOCTYPE html>
<html>
<head>
    <title>Sell Out DP</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">

</head>
<body>

<hr>
<div class='row'>
    <div class="col-md-3">
</div>
<?php $no = 1; ?>
<hr>
<div class="row">        
        <div class="col-xs-12">
        <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-bank">    
                    <thead>
                        <tr>                
                            <th>No</th>
                            <th>bankid</th>
                            <th>Namabank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($target as $sell) : ?>
                        <tr>        
                            <td><?php echo $no++; ?></td>               
                            <td><?php echo $sell->bankid; ?></td>
                            <td><?php echo $sell->nama_bank; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

    <script type="text/javascript">
    $(".table-bank").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('omzet/get_bank'); ?>",
            type:'POST',
        }
    });

    </script>
    </body>
</html>


