<!-- Button trigger modal -->
<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#export">
    Export
</button>
<br><br>
<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th width="1">Tgl Pesan</th>
                <th>Tgl PO</th>
                <th>No PO</th>
                <th>Tipe</th>
                <th>Company</th>
                <th>Alamat Kirim</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($getpo as $a) :?>
            <tr>
                <td><?= $a->tglpesan?></td>
                <td><?= $a->tglpo?></td>
                <td><?= $a->nopo?></td>
                <td>
                    <?php 
                        if($a->tipe == 'S'){
                            echo "SPK";
                        }elseif($a->tipe == 'R'){
                            echo "Replineshment";
                        }elseif($a->tipe == 'A'){
                            echo "Alokasi";
                        }
                    ?>
                </td>
                <td><?= $a->company?></td>
                <td><?= $a->alamat?></td>
                <td>
                    <?php 
                                    
                        if($a->open == '1')
                        {
                            echo "<strong><font color='blue'>Success</font>";
                        }else{
                            echo "<strong><font color='red'>Pending</font>";
                        }
                    ?>
                </td>
                <td>
                    <a href="<?php echo base_url()."all_po/konfirmasi/".$a->id; ?>  " class="btn btn-primary btn-sm"
                        role="button" target="_blank"> update</a>
                    <a href="<?php echo base_url()."trans/po/print/".$a->id; ?>  " class="btn btn-warning btn-sm"
                        role="button" target="_blank"> pdf</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Export</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('transaction/export_order');?>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Bulan</label>
                    <div class="col-sm-10">
                        <input type="month" class="form-control" name="date">
                    </div>
                </div>

                <div align="center">
                    <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>