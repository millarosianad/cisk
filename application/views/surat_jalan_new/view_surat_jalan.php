<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open('surat_jalan/print_range'); ?>
<div class="row">
    <div class="col">
        <input type="date" class="form-control" id="datepicker3" name="start" placeholder="start date" autocomplete="off">
    </div>
    <div class="col">
        <input type="date" class="form-control" id="datepicker2" name="end" placeholder="end date" autocomplete="off"> 
    </div>
    <div class="col">
        <?php $ketdd=array(
                            1=>'Faktur Lunas',
                            0=>'Copy Faktur',
                            2=>'Faktur Lunas (Grup DP)'
                            );?>
        <?php echo form_dropdown('keterangan',$ketdd,'','class="form-control"');?>
    </div>
    <div class="col">
        <div class="form-group">
            <?php
                $select_values = 'all';
                foreach($query->result() as $value)
                {
                    $x[$value->grup_lang]= $value->grup_nama; 

                }
                echo form_dropdown('grup_lang',$x,'','class="form-control" id="grup_lang"');
                
            ?>
        </div>
    </div>
</div>
<div class="col-xs-2">        
    <?php echo form_submit('submit','Print Range','class = "btn btn-danger btn-sm"'); ?>
    <?php echo form_close();?>
</div>

<hr>

<div class="row">
    <div class="col-4">
        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#tambah_suratjalan">
        Tambah Surat Jalan
        </button>
    </div>
</div>
</br>

<div class="card table-card">
    <div class="card-header">
        <div class="card-block">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</th>
                            <th><font size="2px">Client</th>
                            <th><font size="2px">Keterangan</th>
                            <th><font size="2px">Tanggal</th>
                            <th><font size="2px">Print</th>
                            <th><font size="2px">Amplop_K</th>
                            <th><font size="2px">Amplop_B</th>
                            <th><font size="2px">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1 ; ?> 
                    <?php foreach($surat_jalan as $surat_jalans) : ?>
                        <tr>        
                            <td width="5%"><?php echo $no; ?></td>
                            <td width="25%">                                
                                <?php 
                                echo anchor('surat_jalan/edit/' . $surat_jalans->kode_lang, $surat_jalans->nama_lang);
                                ?>                                    
                            </td>
                            <td width="10%"><?php echo $surat_jalans->keterangan; ?></td>
                            <td width="10%"><?php echo $surat_jalans->tanggal; ?></td>
                            <td><center>
                                <?php
                                    //menambah variabel 'faktur lunas atau copy faktur'
                                    if ($surat_jalans->keterangan == "Copy Faktur") {
                                        $x = '0';
                                    } else {
                                        $x = '1';
                                    }                                    

                                    //echo anchor('surat_jalan/print_surat/' . $surat_jalans->id, ' ',"class='glyphicon glyphicon-print'");

                                    echo anchor('surat_jalan/print_surat/' . $surat_jalans->id .'/'.$x, ' ',array('class' => 'ti-printer ti-xl', 'target' => 'blank'));

                                ?>
                                </center>                           
                            </td>
                            <td><center>
                                <?php
                                    echo anchor('surat_jalan/amplop/' . $surat_jalans->kode_lang, ' ',array('class' => 'ti-printer ti-xl', 'target' => 'blank'));
                                ?>
                                </center>                           
                            </td>
                            <td><center>
                                <?php
                                    echo anchor('surat_jalan/amplop_coklat/' . $surat_jalans->kode_lang, ' ',array('class' => 'ti-printer ti-xl', 'target' => 'blank'));
                                ?>
                                </center>                           
                            </td>
                            
                            <td><center>
                                <?php
                                    echo anchor('surat_jalan/delete/' . $surat_jalans->id . '/' . $surat_jalans->kode_lang, ' ',array('class' => 'fa fa-times','style' => 'color:red;','onclick'=>'return confirm(\'Are you sure?\')'));
                                ?>
                                
                                </center>                           
                            </td>
                                   
                        </tr>
                        <?php $no++; ?>
                    
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                            <tr>      
                                <th width="1"><font size="2px">No</th>
                                <th><font size="2px">Client</th>
                                <th><font size="2px">Keterangan</th>
                                <th><font size="2px">Tanggal</th>
                                <th><font size="2px">Print</th>
                                <th><font size="2px">Amplop_K</th>
                                <th><font size="2px">Amplop_B</th>
                                <th><font size="2px">Delete</th>
                            </tr>
                    </tfoot>
                </table>
            <div>
        </div>
    </div>
</div>

<!---------------------------------------- Modal -------------------------------------------->
<?php $this->load->view('surat_jalan_new/modal_input_surat'); ?>

<script src="<?php echo base_url() ?>assets/js/script.js"></script>