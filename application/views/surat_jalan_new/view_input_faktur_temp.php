<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#tambah_suratjalan">
Tambah Surat Jalan
</button>
<br>
<br>
<?php $no = 1 ; ?> 
<div class="card table-card">
        <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
            <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>           
                        <th>No</th>
                        <th>No Faktur</th>
                        <th>Rp</th>
                        <th>No Faktur Pajak</th>
                        <th><center>Keterangan</center></th>
                        <th><center>No Sales</center></th>
                        <th><center>Delete</center></th>  
                    </tr>
                </thead>
                <tbody>
                <?php foreach($tampil_temp as $tampil_temps) : ?>
                    <tr>        
                        <td width="5%"><?php echo $no; ?></td>
                        <td width="25%"><?php echo $tampil_temps->faktur; ?></td>
                        <td width="10%"><?php echo number_format($tampil_temps->nildok); ?></td>
                        <td width="10%"><?php echo $tampil_temps->pajak; ?></td>
                        <td width="10%"><?php echo $tampil_temps->keterangan; ?></td>
                        <td width="10%"><?php echo $tampil_temps->no_sales; ?></td>
                        <td><center>
                            <?php
                                echo anchor('surat_jalan/delete_temp/'.$grup_lang.'/'.$jenis_faktur.'/'.$tampil_temps->id, ' ',array('class' => 'fa fa-times','style' => 'color:red;','onclick'=>'return confirm(\'Are you sure?\')'));
                            ?>
                            </center>                           
                        </td>        
                    </tr>
                    <?php $no++; ?>
                
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>           
                        <th>No</th>
                        <th>No Faktur</th>
                        <th>Rp</th>
                        <th>No Faktur Pajak</th>
                        <th><center>Keterangan</center></th>
                        <th><center>No Sales</center></th>
                        <th><center>Delete</center></th>  
                    </tr>
                </tfoot>
            </table>
            <div>
                <label>Tanggal</label>
                    <?php echo form_open($url);?>
                    <div class='form-inline'>
                        <input type="date" name="tanggal" id="datepicker" class="form-control" autocomplete="off" required>
                        <?php
                        echo form_submit('submit','SAVE','class="btn btn-success btn-sm"');
                        echo form_close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!---------------------------------------- Modal -------------------------------------------->
<?php $this->load->view('surat_jalan_new/modal_input_surat'); ?>