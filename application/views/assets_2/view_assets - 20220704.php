<div class="col-xs-16">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#searchModal">
    Tambah Asset
    </button>
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exportModal">
    Export
    </button>
    <hr>
</div>
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">Kode</th>
                        <th><font size="2px">Nama Barang</th>
                        <th><font size="2px">Group</th>
                        <th><font size="2px">Tanggal Payroll</th>
                        <th><font size="2px">Nilai Perolehan</th>
                        <th><font size="2px">Permintaan User</th>
                        <th><font size="2px">User Mutasi</th>
                        <th><font size="2px">Status</th>
                        <th><font size="2px"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($asset as $a) : ?>
                    <tr>                      
                        <td><font size="2px"><?php echo $a->kode; ?></td>
                        <td><font size="2px"><?php echo $a->namabarang; ?></td>
                        <td><font size="2px"><?php echo $a->namagrup; ?></td>
                        <td><font size="2px"><?php echo date('d-m-Y', strtotime($a->tglperol)); ?></td>
                        <td><font size="2px">Rp.<?php echo number_format($a->np); ?></td>
                        <td><font size="2px"><?php echo ($a->untuk); ?></td>
                        <td><font size="2px"><?php echo ($a->username); ?></td>
                        <td>
                        <?php 

                            if($a->nj==0||$a->nj=='')
                            {
                                $status ='Aktif';
                            }
                            else
                            {
                                $status ='Jual';
                            }

                            echo $status; 
                        ?></td>
                        <td><center>
                            <?php
                                echo anchor('assets_2/detail_assets/' . $a->id, 'view',"class='btn btn-primary btn-sm'");
                            ?>
                            <?php if ($a->nj == 0) {
                                echo anchor('assets_2/edit_assets/' . $a->id, 'edit',"class='btn btn-success btn-sm'");
                                                       
                                echo anchor('assets_2/mutasi_assets/' . $a->id, 'mutasi',"class='btn btn-warning btn-sm'");
                           
                                echo anchor('assets_2/delete_assets/' . $a->id, 'delete',
                                    array('class' => 'btn btn-danger btn-sm',
                                            'onclick'=>'return confirm(\'Are you sure?\')'));   
                           
                             }?>
                            </center>
                        </td>   
                        
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
                <tfoot>
                    <tr>                
                        <th width="1"><font size="2px">Kode</th>
                        <th><font size="2px">Nama Barang</th>
                        <th><font size="2px">Group</th>
                        <th><font size="2px">Tanggal Payroll</th>
                        <th><font size="2px">Nilai Perolehan</th>
                        <th><font size="2px">Permintaan User</th>
                        <th><font size="2px">User Mutasi</th>
                        <th><font size="2px">Status</th>
                        <th><font size="2px"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-xs-11">&nbsp; </div>
    </div>
</div>
    <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
    </script>
</body>

<!-- ---------------------------- modal search assets ------------------------------------ -->
<?php $this->load->view('assets_2/search_assets');?>

<!-- ---------------------------- modal export assets ------------------------------- -->
<?php $this->load->view('assets_2/export_assets');?>
