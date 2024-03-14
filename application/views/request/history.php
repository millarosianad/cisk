<div class="col-xs-16">
    <!-- <a href="<?php echo base_url()."request/input_ticket"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> create ticket</a> -->
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
                        <!-- <th width="1"><font size="2px">id</th> -->
                        <th><font size="2px">Tanggal Request</th>
                        <th><font size="2px">Branch</th>
                        <th><font size="2px">Sub Branch</th>
                        <th><font size="2px">TotalCustomer</th>
                        <th><font size="2px">Status</th>
                        <th><font size="2px"><center>approval</center></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($request as $a) : ?>
                    <tr>                      
                        <!-- <td><font size="2px"><?php echo $a->id; ?></td> -->
                        <td><font size="2px"><?php echo $a->tgl_request; ?></td>
                        <td><font size="2px"><?php echo $a->branch_name; ?></td>
                        <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                        <td><font size="2px"><?php echo $a->total_customerid; ?></td>
                        <td><font color="grey">
                            <?php echo $a->total_pending; ?></font>|
                            <font color="blue">
                            <?php echo $a->total_approve; ?></font>|
                            <font color="red">
                            <?php echo $a->total_reject; ?></font>
                        </td>                  
                        <td><center>
                            <?php
                                echo anchor('request/approval_request/1/' . $a->id.'/'. $a->kode_comp, 'approve all',"class='btn btn-primary btn-sm'");
                                echo "|";
                                echo anchor('request/approval_request/0/' . $a->id.'/'. $a->kode_comp, 'reject all',"class='btn btn-danger btn-sm'");
                                echo "|";
                                echo anchor('request/detail_request/' . $a->id, 'custom',"class='btn btn-dark btn-sm'");
                            ?>
                            </center>
                        </td>                           
                    </tr>
                <?php endforeach; ?>
                
                </tbody>
               
            </table>
        </div>
        <div class="col-xs-11">&nbsp; </div>
        </div>
</div>

     
