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
                        <th width="1%"><font size="2px">Bankid</th>
                        <th width="1%"><font size="2px">nama_bank</th>
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
      url: "<?php echo base_url('sell/get_bank') ?>",
      type:'POST',
    }
});

</script>


</body>
</html>