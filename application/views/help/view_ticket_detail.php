<div class="col-xs-16">
    <a href="<?php echo base_url()."help/view_ticket"; ?>  " class="btn btn-dark" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> view ticket</a>
    <a href="<?php echo base_url()."help/input_ticket"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> create ticket</a>
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah Data Karyawan</button> -->
    <hr />
</div>
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">id</th>
                        <th><font size="2px">Nopo</th>
                        <th><font size="2px">Company</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($asset as $a) : ?>
                    <tr>                      
                        <td><font size="2px"><?php echo $a->id; ?></td>
                        <td><font size="2px"><?php echo $a->nopo; ?></td>
                        <td><font size="2px"><?php echo $a->company; ?></td>
                        
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
               
            </table>
        </div>
        <div class="col-xs-11">&nbsp; </div>
        </div>
</div>

     

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="card table-card">
            <div class="card-header">
            <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
            <thead>
                        <tr>                
                            <th width="1"><font size="2px">Kode</th>
                            <th><font size="2px">Nama Barang</th>
                            <th><font size="2px">Group</th>
                            <th><font size="2px">Tanggal Payroll</th>
                            <th><font size="2px">Nilai Perolehan</th>
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
                                    echo anchor('assets/detail_assets/' . $a->id, 'view',"class='btn btn-primary btn-sm'");
                                ?>
                                <?php if ($a->nj == 0) {
                                    echo anchor('assets/edit_assets/' . $a->id, 'edit',"class='btn btn-success btn-sm'");
                                }?>
                                <?php
                                    echo anchor('assets/mutasi_assets/' . $a->id, 'mutasi',"class='btn btn-warning btn-sm'");
                                ?>
                                <!-- <?php
                                    echo anchor('assets/delete_assets/' . $a->id, 'delete',
                                        array('class' => 'btn btn-danger btn-sm',
                                                'onclick'=>'return confirm(\'Are you sure?\')'));   
                                ?> -->
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
                            <th><font size="2px">Status</th>
                                <th><font size="2px"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-xs-11">&nbsp; </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
