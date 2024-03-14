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

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>


</head>
<body>





<?php

$status = array(
    // NULL  => 'Terkirim ke DP',
    // '1'  => 'Terkirim ke MPM',
    // '2'  => 'Cross cek di MPM',
    '3'  => 'Terkirim ke Principal',
    '4'  => 'Selesai',
    // '5'  => 'Barang dikirim ke DP',
    );


?>

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
</div><center>
<h4>Monitor Claim (Principal)</h4></center>
<hr />
<?php 
                        $url = base_url()."monitor_claim/update_principal/";
                        echo form_open_multipart($url);
                    ?>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-1">
        &nbsp;
        </div>
        <div class="col-xs-12">
            <table class="table table table-bordered table-hover table-outlet">
                <thead>
                    <tr>
                        <th><font size="1px">No</th>
                        <th><font size="1px">Status</th>  
                        <th><font size="1px">No. Surat Program</th>
                        <th><font size="1px">No. AP</th>
                        <th><font size="1px">Nama Program</th>
                        <th><font size="1px">Area</th>
                        <th><font size="1px">File MPM</th>
                        <th><font size="1px">File Principal</th> 
                        <th><font size="1px">Upload Bukti</th>                
                        <th><center><font size="1px"><input type="checkbox" id="cbgroup1_master" name="checkall" onclick="checkUncheckAll(this);"/></th>                         
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $id = 1;
                    echo br().form_submit('submit','Proses All Checklist','onclick="return ValidateCompare();" class="btn btn-primary btn-md"').br(2);
                    foreach($query as $row){ ?>
                                      
                    <tr>
                        <td><font size="1px"><?php echo $id; ?></td>
                        <td><font size="1px">
                        <?php 
                                if($row->status == null || $row->status == '0'){
                                    $status = "Terkirim ke DP";
                                }elseif($row->status == '1'){
                                    $status = "Terkirim ke MPM";
                                }elseif($row->status == '2'){
                                    $status = "Cross Cek di MPM";
                                }elseif($row->status == '3'){
                                    $status = "Terkirim ke Principal";
                                }elseif($row->status == '4'){
                                    $status = "Selesai";
                                }elseif($row->status == '5'){
                                    $status = "Selesai dari Principal";
                                }else{
                                    $status = "-";
                                } 
                            ?>
                            <?php echo $status;?> 
                        </td>
                        <td><font size="1px"><?php echo $row->no_surat_program; ?> </td>
                        <td><font size="1px"><?php echo $row->no_ap; ?> </td>
                        <td><font size="1px"><?php echo $row->nama_program; ?> </td>
                        <td><font size="1px"><?php echo $row->nama_comp; ?> </td>
                        <td>
                            <a href="<?php echo base_url()."monitor_claim/download_file_to_principal/".$row->id; ?>" role="button" target="_blank"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a> 
                        </td>
                        <td><font size="1px">
                            <?php 
                                if ($row->filename_principal == NULL) {
                                    echo "belum ada";
                                }else{ ?>
                                    <a href="<?php echo base_url()."monitor_claim/download_file_principal/".$row->id; ?>" role="button" target="_blank"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a> 
                                <?php }
                            ?>                           
                        </td> 
                        <td>
                            <a href="<?php echo base_url()."monitor_claim/detail_claim_principal/".$row->id; ?>" role="button" target="_blank"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a> 
                        </td>                       
                        <td><center><input type="checkbox" class="form-control-input" name="options[]" value="<?php echo $row->id; ?>"></td>
                    </tr>
                    <?php 
                    $id = $id + 1 ;
                } 
                
                ?>
                </tbody>

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
</body>
</html>