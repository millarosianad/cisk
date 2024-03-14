<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open($url);?>

<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />
<div class='row'>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <?php
                echo form_input('tanggal','','id="datepicker" class="form-control" autocomplete="off"');
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo form_label("Range"); ?>
            <?php $range=array(
                    0=>'Minggu',
                    1=>'Bulan'
                );?>
                <?php echo form_dropdown('range',$range,'','class="form-control"');?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
        </div>
    </div>         
</div>
<?php echo form_close();?>
<!-- Load SCRIPT.JS which will create datepicker for input field  -->
<hr>
</div>
<?php $no = 1; ?>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12">
    <!-- <div class="col-sm-11"> -->
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                <thead>
                    <tr>                
                        <th width="1"><font size="2px">No</font></th>
                        <th><font size="2px">Customer</th>
                        <th><font size="2px">Kode Pelanggan</th>
                        <th><font size="2px">Saldo Awal</th>
                        <th><font size="2px">Debit</th>
                        <th><font size="2px">Kredit</th>
                        <th><font size="2px">Saldo Akhir</th>
                        <th><font size="2px">Current</th>
                        <th><font size="2px">Due Date</th>
                        <th><font size="2px">1-30</th>
                        <th><font size="2px">31-60</th>
                        <th><font size="2px">61-90</th>
                        <th><font size="2px">>90</th>
                        <th><font size="2px">Total Over Due</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($query as $q) : ?>
                        <tr>        
                            <td><center><font size="1px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="1px"><?php echo $q->grup_nama; ?></td>
                            <td><font size="1px"><?php echo $q->grup_lang; ?></td>
                            <td><font size="2px">
                            <?php echo number_format( $q->saldoawal); ?></td>
                            <td><font size="2px"><?php 
                            echo anchor('piutang/detailPiutang/1/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->debit));
                            ?></td>
                            <td><font size="2px"><?php
                            echo anchor('piutang/detailPiutang/2/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->kredit));
                            ?>
                            </td>
                            <td><font size="2px"><?php echo number_format( $q->saldoakhir); ?></td>
                            <td><font size="2px"><?php                             
                            echo anchor('piutang/detailPiutang/3/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->current));
                            ?></td>
                            <td><font size="2px"><?php                             
                            echo anchor('piutang/detailPiutang/4/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->duedate)); ?></td>
                            <td><font size="2px"><?php
                            echo anchor('piutang/detailPiutang/5/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->r1)); 
                            ?></td>
                            <td><font size="2px"><?php  
                            echo anchor('piutang/detailPiutang/6/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->r2));
                            ?></td>
                            <td><font size="2px"><?php 
                            echo anchor('piutang/detailPiutang/7/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->r3));
                            ?></td>
                            <td><font size="2px"><?php
                            echo anchor('piutang/detailPiutang/8/'.$q->grup_lang.'/'.$q->tanggal, number_format($q->r4));
                            ?></td>
                            <td><font size="2px"><?php echo number_format($q->total);
                            ?></td>
                        </tr>
                    <?php endforeach; ?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>








<script src="<?php echo base_url() ?>assets/js/script.js"></script>