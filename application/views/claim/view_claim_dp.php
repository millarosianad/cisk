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
 </div>
 <br>
 </div>
</div><center>
<h3>Monitor Claim (DP)</h3></center>
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
                        <th width="3%"><font size="2px">Status Claim</th>
                        <th width="10%"><font size="2px">No. Surat Program</th>
                        <th width="10%"><font size="2px">Nama Program</th>
                        <th width="1%"><font size="2px">FIle MPM</th> 
                        <th width="1%"><font size="2px">FIle DP</th> 
                        <th width="7%"><font size="2px">Tgl Kirim Claim</th>
                        <th width="10%"><font size="2px">Keterangan</th>                        
                        <th width="15%"><font size="2px">Upload FIle Claim</th> 
                        <th width="3%"><font size="2px">Simpan</th> 
                    </tr>
                </thead>
                <tbody>
                <?php foreach($query as $row){ ?>
                    <?php 
                        $url = base_url()."monitor_claim/update_dp/".$row->id;
                        echo form_open_multipart($url);
                    ?>
                    <tr>
                        <td>1</td>
                        <td>
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
                                    $status = "Cross cek di Principal";
                                }elseif($row->status == '5'){
                                    $status = "Selesai dari Principal";
                                }else{
                                    $status = "-";
                                } 
                            ?>
                            <?php echo $status;?>                                
                        </td>
                        <td><?php echo $row->no_surat_program; ?> </td>
                        <td><?php echo $row->nama_program; ?> </td>
                        <td>
                            <a href="<?php echo base_url()."monitor_claim/download_file/".$row->id_ref; ?>  " class="btn btn-default" role="button" target="_blank"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a>
                        </td>  
                        <td><?php
                                if ($row->file_dp == NULL) {
                                    echo "<font color=red><i>belum ada</i></font>";
                                }else{?>
                                    <a href="<?php echo base_url()."monitor_claim/download_file_dp/".$row->id; ?>  " class="btn btn-default" role="button" target="_blank"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a> 
                                <?php }
                            ?>
                        </td>               
                        <td>
                            <?php 
                                if ($row->tgl_claim_dp == NULL) {
                                    echo "<font color=red><i>belum ada</i></font>";
                                }else{
                                    echo $row->tgl_claim_dp;
                                }
                             ?>
                        </td>
                        <td>
                            <?php 
                                if ($row->ket == NULL) { ?>
                                    <textarea class="form-control" name = "ket" cols = "5" rows = "4"><?php echo $row->ket; ?></textarea>
                                <?php }else{ ?>
                                    <textarea class="form-control" name = "ket" cols = "5" rows = "4"><?php echo $row->ket; ?></textarea>
                                <?php }
                            ?>                            
                        </td>                        
                        <td>                        
                            <input type="file" name="files[]" multiple class="form-control"/>                       
                        </td>                        
                        <td>
                            <?php echo form_submit('proses','Kirim','onclick="return ValidateCompare();" class="btn btn-primary btn-sm"');?>   
                        </td>                        
                    </tr>
                <?php } ?>
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