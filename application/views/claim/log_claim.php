</div>
<!DOCTYPE html>
<html>
<head>
    <title>Log Claim</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="col-xs-16">   <br><br>         
                    <h3><?php echo $page_title; ?></h3><hr />
                </div>
                <br>
            </div>
        </div>
        <?php echo form_open_multipart($url);?>
</div>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-1">
                &nbsp;
            </div>
        <div class="col-xs-12">
                <table id="myTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="1%"><font size="2px">No</th>
                        <th width="10%"><font size="2px">Branch</th>
                        <th width="10%"><font size="2px">SubBranch</th>
                        <th width="10%"><font size="2px">Filename</th> 
                        <th width="10%"><font size="2px">CreateDate</th>                      
                        <th width="1%"><font size="2px">T_Master</th>
                        <th width="1%"><font size="2px">T_Detail</th>
                        <th width="1%"><font size="2px">T_DetailProduct</th>
                        <th width="1%"><font size="2px">T_DetailTransaksi</th>
                        
                        <th width="10%"><font size="2px">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $no = 1;
                        foreach($query as $a) : ?>
                    <tr>
                        <td><font size="2px"><?php echo $no++ ?></td>
                        <td><font size="2px"><?php echo $a['branch_name'] ?></td>
                        <td><font size="2px"><?php echo $a['nama_comp'] ?></td>
                        <td><font size="2px"><?php echo $a['filename'] ?></td>
                        <td><font size="2px"><?php echo $a['createddate'] ?></td>
                        <td><center><font size="2px"><?php  
                            if ($a['t_master'] == 1) {
                                ?><font color="blue"><?php echo "true"; ?></font><?php
                            }else{
                                ?><font color="red"><?php echo "false"; ?></font><?php
                            }
                            ?>
                        </td>
                        <td><center><font size="2px"><?php
                            if ($a['t_detail'] == 1) {
                                    ?><font color="blue"><?php echo "true"; ?></font><?php
                                }else{
                                    ?><font color="red"><?php echo "false"; ?></font><?php
                                }
                                ?>
                        </td>
                        <td><center><font size="2px"><?php
                            if ($a['t_detail_product'] == 1) {
                                ?><font color="blue"><?php echo "true"; ?></font><?php
                            }else{
                                ?><font color="red"><?php echo "false"; ?></font><?php
                            }
                            ?>
                        </td>
                        <td><center><font size="2px"><?php
                            if ($a['t_detail_transaksi'] == 1) {
                                ?><font color="blue"><?php echo "true"; ?></font><?php
                            }else{
                                ?><font color="red"><?php echo "false"; ?></font><?php
                            }
                            ?>
                        </td>
                        
                        <td><font size="2px"><?php 
                            if($a['status'] == null){
                                echo anchor("monitor_claim/proses_log/" . $a['id'], "proses","class='btn btn-primary btn-sm'");
                            }else{
                                echo "di proses oleh ".$a['username'];
                            }
                            
                        ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>




</body>
</html>