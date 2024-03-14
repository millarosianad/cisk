<div class="col-xl-12 col-md-12">
<?php echo form_open($url2);?>
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">NAMA BARANG</th>
                            <th><font size="2px">TIPE</th>
                            <th><font size="2px">JUMLAH</th>
                            <th><font size="2px">HARGA</th>
                            <th><font size="2px">TAX</th>
                            <th><font size="2px">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($proses as $a) : ?>
                        <tr>                      
                            <td><font size="2px"><?php echo $a->nama_barang; ?></td>
                            <td><font size="2px"><?php echo $a->tipe; ?></td>
                            <td><font size="2px"><?php echo number_format($a->jumlah); ?></td>
                            <td><font size="2px"><?php echo number_format($a->harga); ?></td>
                            <td><font size="2px"><?php echo number_format($a->tax); ?></td>
                            <td><font size="2px"><?php echo number_format($a->jumlah*$a->harga+$a->tax); ?></td>
                            <td><center>
                                <?php
                                    echo anchor('assets_2/delete_barang_pengajuan/' . $a->id, ' ',
                                        array('class' => 'ti-trash btn btn-danger btn-xs',
                                              'onclick'=>'return confirm(\'Are you sure?\')'));   
                                ?>
                                </center>
                            </td>   
                        </tr>
                    <?php endforeach; ?>                    
                    </tbody>
                    <tfoot>
                            <tr>                
                                <th width="1"><font size="2px">NAMA BARANG</th>
                                <th><font size="2px">TIPE</th>
                                <th><font size="2px">JUMLAH</th>
                                <th><font size="2px">HARGA</th>
                                <th><font size="2px">TAX</th>
                                <th><font size="2px">Total</th>
                                <th></th>
                            </tr>
                    </tfoot>
                    </table>
                    </div>
        </div>
    </div>
    <?php echo form_submit('submit','Confirm', 'class="btn btn-success"');?>
    <?php echo form_close();?>