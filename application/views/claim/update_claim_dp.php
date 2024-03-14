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
   <h3><?php echo $page_title; ?></h3><hr />
</div>
<div class="col-xs-16">
        <!-- <a href="<?php echo base_url()."outlet/data_outlet/"; ?>  " class="btn btn-success" role="button"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> kembali</a>
        
        <a href="<?php echo base_url()."outlet/export_outlet/". $tahun."/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a>        -->
 </div>
 
 </div>
 <?php echo form_open_multipart($url);?>
 <?php //echo form_open_multipart('/all_upload/file_upload');?>

 <?php foreach($claim as $row):?>

<div class="row">
    <div class="col-xs-12">
        <?php 
        $principal = array(
            '1'  => 'Deltomed Herbal',
            '2'  => 'Deltomed Candy',
            '3'  => 'Deltomed Lainnya', 
            '4'  => 'Ultra Sakti',
            '5'  => 'Marguna',   
            );
        $tipe_claim = array(
            '1'  => 'Barang',
            '2'  => 'Non Barang',
            );
        $tipe_claimx = array(
            '1'  => 'All DP',
            '2'  => 'DP Tertentu',
            );
        $keterangan = array(
            '1'  => 'Lengkap',
            '2'  => 'Reject',
            );
        ?>

        <div class="col-xs-2">
            No. Surat Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_surat_program" placeholder="" value = "<?php echo $row->no_surat_program;?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>
        <div class="col-xs-2">
            No. AP
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_ap" placeholder="" value = "<?php echo $row->no_ap;?>" readonly>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Nama Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="nama_program" placeholder="" value = "<?php echo $row->nama_program;?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Periode Program
        </div>

        <div class="col-xs-3">                
            <input type="text" class = 'form-control' name="from" placeholder="" autocomplete="off" value = "<?php echo $row->from. " s.d ".$row->to;?>" readonly>
        </div>
        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-12"><hr></div>

        <div class="col-xs-2">
            Created By
        </div>

        <div class="col-xs-3">                
            <input type="hidden" class = 'form-control' name="created_dp_by" value="<?php echo $userid; ?>" readonly>
            <input type="text" class = 'form-control' name="created_dp_username" value="<?php echo $username; ?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Upload File Pendukung (Pilih 1 atau lebih file)
        </div>

        <div class="col-xs-3">
            <!-- <?php for ($i=1; $i <=4 ; $i++) :?>
                <input type="file" name="file<?php echo $i;?>" class = "form-control"><br/>
            <?php endfor;?> -->
            <!-- <input type="file" name="userfile1" class="form-control" /> -->
            <input type="file" name="files[]" multiple class="form-control"/>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        
        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Tanggal Claim DP
        </div>

        <div class="col-xs-3">                
            <input type="text" class = 'form-control' id="datepicker2" name="tgl_claim_dr_dp" placeholder="" autocomplete="off">
        </div>
        <div class="col-xs-12">&nbsp;</div>
        
        <div class="col-xs-2">
            Keterangan Claim
        </div>

        <div class="col-xs-3">
            <?php echo form_dropdown('keterangan_dr_dp', $keterangan,'','class="form-control"');?>
        </div>

        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-1">&nbsp;
        <input type="hidden" class = 'form-control' name="id" placeholder="" value = "<?php echo $this->uri->segment('3');?>" readonly>
        </div>
                
        <div class="col-xs-3"><center>
            <!-- <input type='submit' value='Upload' name='upload' /> -->
            <?php echo br().form_submit('proses','Proses','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>      
        </div>

        
    
    </div>
</div>

<?php endforeach;?>

</div>

<div class="col-xs-12"><hr></div>

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
                        <th width="4%"><font size="2px">No.Surat Program</th>
                        <th width="4%"><font size="2px">No.AP</th>
                        <th width="4%"><font size="2px">Nama Program</th>
                        <th width="2%"><font size="2px">Periode Program</th>
                        <th width="1%"><font size="2px">Tgl Claim DP</th>
                        <th width="1%"><font size="2px">Keterangan DP</th>
                        <th width="4%"><font size="2px">Created By</th>
                        <th width="4%"><font size="2px">Created Date</th>
                        
                        
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
      url: "<?php echo base_url('monitor_claim/ambil_data_dp') ?>",
      type:'POST',
    }
});

</script>


</body>
</html>