<?php
echo br(2);
switch($state)
{
    case 'show':
    {
        
    }break;
    case 'add':
    {?>
    <div class="row">
    <div class="col-md-4">
    <?php
        echo form_open($uri);
        foreach($dp->result() as $row)
        {
            $dd[$row->kode_lang.' | '.$row->nama_lang.' | '.$row->alamat] = $row->kode_lang.' | '.$row->nama_lang.' | '.$row->alamat;
        }
        echo form_label('Pelanggan');
        echo form_dropdown('pelanggan',$dd,'','id="x" class="form-control"');
        $minggu=array('1'=>'Minggu 1','2'=>'Minggu 2','3'=>'Minggu 3','4'=>'Minggu 4');
        ?>
        </div>
        <div class="col-md-2">
        <?php
        echo form_label('Minggu');
        echo form_dropdown('minggu',$minggu,'','id="x" class="form-control"');
        $hari=array('1'=>'Senin','2'=>'Selasa','3'=>'Rabu','4'=>'Kamis','5'=>'Jumat','6'=>'Sabtu','7'=>'Minggu');
        ?>
        </div>
        <div class="col-md-2">
        <?php
        echo form_label('Hari');
        echo form_dropdown('hari',$hari,'','id="x" class="form-control"');
        ?>
        </div>
        <div class="col-md-1">
        <?php
        echo form_label('SL');?>
        <input type="number" value="1" name="sl" size="2" min="1" max="30"  style="width: 70px;" class="form-control">
        
        </div>
        <div class="col-md-1">
        <?php
        echo form_label('&nbsp;');
        echo form_submit('add','TAMBAH','class="btn btn-info"');
        ?>
        </div>
        </div>
    <?php
        echo form_close();
        if($table!='')
        {
        ?>
        <div class='row'>
        <div class='col-md-8'>
        <?php
        echo br(1).$table;
        ?>
        </div></div>
        <?php
        echo br(1);
        echo 'Click "NEXT" to show PIVOT RKS';
        echo form_open($uri2);
        echo br(1);
        echo form_submit('submit','NEXT','class="btn btn-info"');
        echo form_close();
        echo br(2);
        }
    }break;
}
?>
