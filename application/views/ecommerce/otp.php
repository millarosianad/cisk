<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">


<div class="col-12">
    <a href="<?php echo base_url()."ecommerce/export_csv/otp"; ?>" type="button" class="btn btn-md btn-success">Export (.csv)</a>
    <a href="<?php echo base_url()."ecommerce/log_otp"; ?>" type="button" class="btn btn-md btn-dark">Log OTP</a>
    <!-- <a href="<?php echo base_url()."ecommerce/export_xls/kontak"; ?>" type="button" class="btn btn-sm btn-round btn-success">Export (.xls)</a> -->
    <br><hr>
    <div class="dt-responsive table-responsive">
        <!-- <table id="multi-colum-dt" class="table table-striped table-bordered nowrap"> -->
        <table class="table table table-striped table-bordered nowrap table-hover table-outlet">
            <thead>
                <tr>
                    <th width="1%"><font size="2px">branch</th>
                    <th width="1%"><font size="2px">sub branch</th>
                    <th width="1%"><font size="2px">customer code mpm</th>
                    <th width="1%"><font size="2px">nama customer</th>
                    <th width="1%"><font size="2px">alamat</th>
                    <th width="1%"><font size="2px">code_approval</th>
                    <th width="1%"><font size="2px">history otp whatsapp</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>
    <script type="text/javascript">
    $(".table-outlet").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
        url: "<?php echo base_url('ecommerce/ambil_data') ?>",
        type:'POST',
        }
    });

    </script>


    </body>
</html>