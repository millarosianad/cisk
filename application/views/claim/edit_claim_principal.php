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

 <br>
 </div></div>
 <?php foreach($query as $row):?>
 <?php $id = $row->id; ?>
 <?php echo form_open_multipart('monitor_claim/proses_edit_claim_principal/'.$id);?>
 <?php //echo form_open_multipart('/all_upload/file_upload');?>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            No. Surat Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_surat_program" placeholder="" value="<?php echo $row->no_surat_program;?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            No. AP
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_ap" placeholder="" value="<?php echo $row->no_ap;?>" readonly>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Tipe Claim
        </div>

        <div class="col-xs-3">
            <?php 
                if ($row->tipe_claim == '1') {
                    $tipe = "Barang";
                }elseif ($row->tipe_claim == '2'){
                    $tipe = "Non Barang";
                }else{
                    $tipe = "Reward";
                }
            ?>
            <input type="text" class = 'form-control' name="no_ap" placeholder="" value="<?php echo $tipe;?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            DP / Area
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="no_ap" placeholder="" value="<?php echo $row->nama_comp;?>"readonly>
        </div>
        
        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Nama Program
        </div>

        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="nama_program" placeholder="" value="<?php echo $row->nama_program;?>" readonly>
        </div>

        <div class="col-xs-1">
            &nbsp;
        </div>

        <div class="col-xs-2">
            Periode Program
        </div>

        <div class="col-xs-3">
                
        <div class="input-group input-daterange">
            <input type="text" class = 'form-control' name="from" placeholder="" autocomplete="off" value="<?php echo $row->from;?>" readonly>
            <div class="input-group-addon">to</div>
                <input type="text" class = 'form-control' name="to" placeholder="" autocomplete="off" value="<?php echo $row->to;?>" readonly> 
            </div>
        </div>
        
        <div class="col-xs-12">&nbsp; <hr></div>       

        <div class="col-xs-2">
            Upload Bukti Transfer / Dokumen pendukung lainnya
        </div>

        <div class="col-xs-3">
            <input type="file" name="files[]" multiple class="form-control"/>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-2">
            Keterangan
        </div>

        <div class="col-xs-3">                
            <textarea class="form-control" name = "keterangan" rows="4"></textarea>
        </div>


        <div class="col-xs-12">&nbsp;</div>
        <div class="col-xs-1">&nbsp;</div>
        
        <div class="col-xs-3"><center>
            <?php echo br().form_submit('proses','Kirim','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>      
        </div>        
    
    </div>
</div>

<?php endforeach;?>

<br><br>



</body>
</html>