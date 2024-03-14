</div>
<!DOCTYPE html>
<html>
<head>
    <title>Outlet</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">

</head>
<body>


<div class="container">
<div class="row">
<div class="col-md-9">
<div class="col-xs-16">            
   <h3>Data Outlet</h3><hr />
</div>
<div class="col-xs-16">
        <a href="<?php echo base_url()."outlet/data_outlet/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."outlet/export_outlet_bd_csv/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>       
 </div>
 <br>
 </div></div></div><hr>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-1">
        &nbsp;
        </div>
        <div class="col-xs-12">
            <table class="table table table-striped table-bordered table-hover table-outlet">
                <thead>
                    <tr>
                        <th width="1%"><font size="2px">No</th>
                        <th width="1%"><font size="2px">Kode</th>
                        <th width="1%"><font size="2px">Outlet</th>
                        <th width="1%"><font size="2px">Class</th>
                        <th width="1%"><font size="2px">Kodeprod</th>
                        <th width="1%"><font size="2px">Jan</th>
                        <th width="1%"><font size="2px">Feb</th> 
                        <th width="1%"><font size="2px">Mar</th> 
                        <th width="1%"><font size="2px">Apr</th> 
                        <th width="1%"><font size="2px">Mei</th> 
                        <th width="1%"><font size="2px">Jun</th> 
                        <th width="1%"><font size="2px">Jul</th> 
                        <th width="1%"><font size="2px">Agus</th> 
                        <th width="1%"><font size="2px">Sep</th> 
                        <th width="1%"><font size="2px">Okt</th> 
                        <th width="1%"><font size="2px">Nov</th> 
                        <th width="1%"><font size="2px">Des</th>
                    </tr>
                </thead>
            </table>
        </div>
        </div></div>




<script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

<script type="text/javascript">

$(".table-outlet").DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: {
      url: "<?php echo base_url('outlet/ambil_data_bd') ?>",
      type:'POST',
    }
});

</script>


</body>
</html>