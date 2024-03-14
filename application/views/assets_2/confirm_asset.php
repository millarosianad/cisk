<div class="card table-card">
        <div class="card-header">
        <h5>Data Asset</h5>
        <br><br>
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</th>
                            <th><font size="2px">Kode</th>
                            <th><font size="2px">Nama Barang</th>
                            <th><font size="2px">Jumlah</th>
                            <th><font size="2px">Tanggal</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($asset as $a) :
                        $no=1;?>
                        <?php if($a->status==2){?>
                        <tr>                      
                            <td><font size="2px"><?php echo $no++; ?></td>
                            <td><font size="2px"><?php echo ($a->kode); ?></td>
                            <td><font size="2px"><?php echo ($a->namabarang); ?></td>
                            <td><font size="2px"><?php echo ($a->jumlah); ?></td>
                            <td><font size="2px"><?php echo ($a->tgl_mutasi); ?></td>
                            <td><a href="<?php echo base_url()."assets_2/file/bukti_mutasi/".$a->bukti_upload; ?>" target="_blank"><img src="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload; ?>" width='70' height='90'></a></td>
                            <td><a href="<?php echo base_url()."assets_2/file/bukti_mutasi/".$a->bukti_upload2; ?>" target="_blank"><img src="<?php echo base_url()."assets/file/bukti_mutasi/".$a->bukti_upload2; ?>" width='70' height='90'></a></td>
                        </tr>
                        <?php } ?>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>                
                            <th width="1"><font size="2px">No</th>
                            <th><font size="2px">Kode</th>
                            <th><font size="2px">Nama Barang</th>
                            <th><font size="2px">Jumlah</th>
                            <th><font size="2px">Tanggal</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

