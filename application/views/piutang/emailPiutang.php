<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<?php echo form_open($url);?>

<h2>
<?php echo form_label($page_title);?>
</h2>
<hr />
<div class='row'>


    <div class="form-group row">
        <label for="from" class="col-sm-2 col-form-label">From</label>
        <div class="col-sm-5">
        <input type="text" name="from" class="form-control" id="from" value="<?php echo $from; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="to" class="col-sm-2 col-form-label">To</label>
        <div class="col-sm-5">
        <input type="text" name = "to" class="form-control" id="to" value="<?php echo $emailTo; ?>">
        </div>
    </div>

    <div class="form-group row">
        <label for="cc" class="col-sm-2 col-form-label">CC</label>
        <div class="col-sm-5">
        <input type="text" name = "cc" class="form-control" id="cc" value="<?php echo $cc; ?>">
        </div>
    </div>
    
    <div class="form-group row">
        <label for="subject" class="col-sm-2 col-form-label">subject</label>
        <div class="col-sm-5">
        <input type="text" name = "subject" class="form-control" id="subject" value="<?php echo $subject; ?>">
        </div>
    </div>

    

    <?php $no = 1;?>

    <div class="form-group row">
        <label for="message" class="col-sm-2 col-form-label">Message</label>
        <div class="col-sm-5">
        <textarea name="message" id="message" cols="70" rows="10">
        Dear <?php echo $company; ?><br><br>
        Sehubungan dengan tagihan <?php echo $company; ?> yang <?php echo $waktu; ?>, dengan rincian sbb : <br><br>
        
        <table border='1'>
            <thead>
            <tr>
                <th width='1%'><center>No</center></th>
                <th width='10%'><center>No faktur</center></th>
                <th width='20%'><center>No DO</center></th>
                <th width='20%'><center>Tanggal</center></th>
                <th width='20%'><center>Tanggal J Tempo</center></th>
                <th width='20%'><center>Nilai</center></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($getMessage as $x) : ?>
            
            <tr>
                <td><center><?php echo $no++; ?></center></td>
                <td><center><?php echo $x->nodokjdi; ?></center></td>
                <td><center><?php echo $x->nodokacu; ?></center></td>
                <td><center><?php echo $x->tgldokjdi; ?></center></td>
                <td><center><?php echo $x->tgl_jtempo; ?></center></td>
                <td><center><?php echo number_format($x->nilai); ?></center></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <br><hr><br>
        Kami meminta bantuannya untuk dapat melunasi tagihan tersebut, sehingga jika ada PO yang belum dijalankan dapat segera diproses. 
        <br>Apabila dalam kolom di atas ada nilai yang Minus (-), merupakan Retur atau Faktur Pembatalan. 
        <br>Jika ada pertanyaan dapat menghubungi PT MPM.<br>
        Terima Kasih<br><br>

        Rgds<br><br>

        Suriana<br><br>

        NB:Mohon konfirmasikan Kami, jika sudah melakukan pembayaran atas faktur di atas.
        
        </textarea>
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
<script src="<?php echo base_url() ?>assets/js/script.js"></script>