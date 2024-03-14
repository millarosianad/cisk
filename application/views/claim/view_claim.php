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
 <br>
 </div></div>
 <?php echo form_open_multipart($url);?>
 <?php //echo form_open_multipart('/all_upload/file_upload');?>

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
            '3'  => 'Reward',
            );

        ?>
        
        <div class="col-xs-2">
            Principal
        </div>

        <div class="col-xs-3">
            <input type="hidden" class = 'form-control' name="username" placeholder="" value="<?php echo $username; ?> " readonly>
            <?php echo form_dropdown('principal', $principal,'','class="form-control"');?>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            No. AP
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_ap" placeholder="">
        </div>


        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            No. Surat Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_surat_program" placeholder="">
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Tipe Claim
        </div>

        <div class="col-xs-3">
            <?php echo form_dropdown('tipe_claim', $tipe_claim,'','class="form-control"');?>
        </div>
        

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Nama Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="nama_program" placeholder="">
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Periode Program
        </div>

        <div class="col-xs-3">
                
        <div class="input-group input-daterange">
            <input type="text" class = 'form-control' id="datepicker2" name="from" placeholder="" autocomplete="off">
            <div class="input-group-addon">to</div>
                <input type="text" class = 'form-control' id="datepicker" name="to" placeholder="" autocomplete="off">
            </div>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Tahun DP
        </div>

        <div class="col-xs-3">
            <?php 
            
            $interval=date('Y')-2010;
            $options=array();
            $options['0']='- Pilih Tahun -';
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            
            ?>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Upload File Pendukung (Pilih 1 atau lebih file)
        </div>

        <div class="col-xs-3">
            <input type="file" name="files[]" multiple class="form-control"/>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Area / DP
        </div>

        <div class="col-xs-3">
            <?php        
                $options=array();
            ?>
            <select multiple name = "nocab[]" id = "subbranch" class = "form-control" size = "7">
            </select>
        </div>
        <div class="col-xs-12">&nbsp;</div>

        
        <div class="col-xs-12"><center>
            <!-- <?php echo br().form_submit('proses','Proses','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>       -->
            <?php echo br().form_submit('proses','Simpan','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>
        </div>        
    
    </div>
</div>

<hr>
</div>
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
                        <th width="2%"><font size="2px">Principal</th>
                        <th width="10%"><font size="2px">No Program</th>
                        <!-- <th width="4%"><font size="2px">No.AP</th> -->
                        <th width="10%"><font size="2px">Nama Program</th>
                        <!-- <th width="2%"><font size="2px">Tipe Claim</th> -->
                        <!-- <th width="2%"><font size="2px">Periode Program</th> -->
                        <!-- <th width="10%"><font size="2px">Area/DP</th> -->
                        <!-- <th width="1%"><font size="2px">Created By</th>
                        <th width="4%"><font size="2px">Created Date</th>                         -->
                        <th width="1%"><font size="2px">Program</th>
                        <th width="1%"><font size="2px">F_MPM</th>
                        <th width="1%"><font size="2px">SendEmail</th>
                        <th width="1%"><font size="2px">Gen</th>
                        <th width="1%"><font size="2px">Detail</th>
                        <th width="1%"><font size="2px">Edit</th>
                        <th width="1%"><font size="2px">Del</th>
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
      url: "<?php echo base_url('monitor_claim/ambil_data') ?>",
      type:'POST',
    }
});

</script>


</body>
</html>