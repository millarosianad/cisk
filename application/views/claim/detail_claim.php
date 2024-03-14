</div>
<!DOCTYPE html>
<html>
<head>
    <title>Monitor Claim</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 

        <script type="text/javascript">       
            
            $(document).ready(function() { 
                $("#year").click(function(){
                            /*dropdown post */
                    $.ajax({
                    url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
                    data: {id_year: $(this).val()},
                    type: "POST",
                    success: function(data){
                        $("#subbranch").html(data);
                        }
                    });
                });
            });
                    
        </script>


</head>
<body>


<div class="container">
<div class="row">
<div class="col-md-9">
<div class="col-xs-16">            
   
</div>
<div class="col-xs-16">
        <!-- <a href="<?php echo base_url()."outlet/data_outlet/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."outlet/export_outlet/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>        -->
 </div>
 <br>
 </div>
 <?php echo form_open($url);?>



</div><center>
<h3>Detail Claim DP</h3></center>
<hr />

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
                        <th width="10%"><font size="2px">No. Surat Program</th>
                        <th width="5%"><font size="2px">Area</th>
                        <th width="2%"><font size="2px">Tgl Claim dr DP</th>
                        <th width="2%"><font size="2px">Keterangan dr DP</th>
                        <th width="4%"><font size="2px">Created By</th>
                        <th width="4%"><font size="2px">Created Date</th>
                        <th width="1%"><font size="2px">F_DP</th> 
                        <th width="1%"><font size="2px">F_Princ</th> 
                        <th width="1%"><font size="2px">F_ToPrinc</th> 
                        <th width="1%"><font size="2px">EmailToPrin</th> 
                        <th width="4%"><font size="2px">Status</th> 
                        <th width="1%"><font size="2px">UbahStatus</th> 
                    </tr>
                </thead>
            </table>
        </div>
        </div></div>



<link href="<?php echo base_url() ?>assets/css/multiselect.css" rel="stylesheet"/>
<script src="<?php echo base_url() ?>assets/js/multiselect.min.js"></script>
<script>
	document.multiselect('#testSelect1');
</script>


<script src="<?php echo base_url() ?>assets/js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

<script type="text/javascript">

$(".table-outlet").DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: {
      url: "<?php echo base_url('monitor_claim/ambil_data_detail/'.$this->uri->segment('3')) ?>",
      type:'POST',
    }
});

</script>


</body>
</html>