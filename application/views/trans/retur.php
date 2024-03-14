<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View All Omzet</title>

        <!-- <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script> -->
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker2").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker3").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script>
<script type="text/javascript">
     $(function() {
        $( "#namaprod_add" ).autocomplete({
        source:'<?php echo site_url('trans/retur/add')?>'
    });
    });
    
    
    
    
    /*$().ready( function() {
    $("#namaprod_add").autocomplete({
      minLength: 1,
      select:function(event,ui)
      {
         $('#namaprod_add').val(ui.item.namaprod);
      },
      source:function(req, add){
          $.ajax({
            url: "<?php echo site_url('trans/retur/add')?>",
            dataType: 'json',
            type: 'POST',
            data: req,
            success:
            function(data){
              if(data.response =='true'){
                add(data.message);
              }
            }
          });
        }
    });
    });*/

function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if ((charCode < 48 || charCode > 57) && (charCode < 37 || charCode>40) && (charCode < 8 || charCode >8) && (charCode < 46 || charCode > 46) )
            return false;
         return true;

}
function radioEvent(a)
{
    //alert(a);
    switch(a)
    {
        case 1:
          document.getElementById("radCustomer").checked=true;
         // document.getElementById("radTanggal").check=false;
          break;
        case 0:
          document.getElementById("radTanggal").checked=true;
         // document.getElementById("radCustomer").check=false;
          break;
    }
}
function add()
{
    //alert('PLUS');
    var nilai=parseInt(document.getElementById("amount").value);
    nilai+=1;
    document.getElementById("amount").value=nilai
}
function sub()
{
    //alert('MINUS');
    var nilai=parseInt(document.getElementById("amount").value);
    if(nilai>1){
        nilai-=1
        document.getElementById("amount").value=nilai;
    }
}
function check()
{
    var alamat=document.getElementById('alamat').value;
    var pelanggan=document.getElementById('pelanggan').value;
    var email=document.getElementById('email').value;
    var npwp=document.getElementById('npwp').value;

    if(alamat!='' && email!='' && npwp!='' && pelanggan!='')
    {
        return true;
    }
    else
    {
        alert('Harap perbaharui data anda !');
        return false;
    }
}
</script>

<hr>
<br>

<?php

    switch(strtolower($state))
    {
        case 'edit_detail':
        {
            echo form_open($uri);
            echo '<div class="table-responsive">';
            echo '<table class="table">';
                echo '<tr>';
                    echo '<td>'.form_label('Jumlah Unit').'</td>';
                    echo '<td>'.':'.'</td>';
                    echo '<td>'.form_input('unit',$query->banyak,'class="form-control"').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>'.form_label('Harga Beli').'</td>';
                    echo '<td>'.':'.'</td>';
                    echo '<td>'.form_input('hbeli',$query->harga_beli,'class="form-control"').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>'.form_label('Diskon Beli').'</td>';
                    echo '<td>'.':'.'</td>';
                    echo '<td>'.form_input('dbeli',$query->diskon_beli,'class="form-control"').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>'.form_label('Harga Jual').'</td>';
                    echo '<td>'.':'.'</td>';
                    echo '<td>'.form_input('hjual',$query->harga,'class="form-control"').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>'.form_label('Diskon Jual').'</td>';
                    echo '<td>'.':'.'</td>';
                    echo '<td>'.form_input('djual',$query->diskon,'class="form-control"').'</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td colspan="3" align="left">'.form_submit('submit','UPDATE','class="btn btn-info"').'</td>';
                echo '</tr>';
           echo '</table>';
           echo '</div>';
           echo form_close();
        }break;
        case 'show_supp':
        {
            ?>
            <?php echo form_open($uri);?>
           
            <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -150px;">
            <div class='col-md-8'>
            <div class='form-group'>
            <?php
                echo form_label("Supplier / Supp : ","supp");
                foreach($query->result() as $value)
                {
                    $dd[$value->supp]= $value->namasupp.' / '.$value->supp;
                }
                echo form_dropdown('supp',$dd,'','id="supp" class="form-control"');
                echo form_label("Date / Tanggal Penjualan :",'datepicker');
                echo form_input('tgl','','id="datepicker" class="form-control" autocomplete="off"');
                foreach($query2->result() as $value)
                {
                    $ee[$value->id]= $value->company.' - '.$value->username.' - '.$value->id;
                }
                echo form_label("Client / Userid:");
                echo form_dropdown('client',$ee,'','class="form-control"');
                echo br();
                //echo form_label(" Year : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                //echo form_dropdown('year', $options, date('Y'));
            ?>
          
            <?php echo form_submit('submit','Proses',"class='btn btn-info'"); ?>
            <a href="<?php echo base_url()."retur/import/"; ?>  " class="btn btn-warning" target = "blank" role="button"><span class="glyphicon glyphicon-floppy-open" aria-hidden="true"></span> Import Excel (.xls) <span class="glyphicon glyphicon-floppy-open" aria-hidden="true"></span></a>
            </div></div></div>
            <?php echo form_close();
        }break;
        case 'show':
        {
            echo $add;
            echo $print_dialog;
            echo br(2);
            echo form_open($uri);
            foreach($query2->result() as $value)
            {
                $client[$value->id]= $value->company." - ".($value->username);
            }
            ?>
            <div class='row'>
                <div class='col-md-5'>
            <?php
            echo '<table class="table">';
            echo '<tr>';
            $nocab=true;
            $tgl=false;
            if($pilih==0)
            {
                $nocab=true;
                $tgl=false;
            }
            else {
                $nocab=false;
                $tgl=true;
            }

            echo '<td>'.form_radio('pilih','0',$nocab,'id="radCustomer"').'</td>';
            echo '<td>'.form_label('CUSTOMER').'</td>';
            echo '<td>'.form_dropdown('userid', $client,isset($userid)?$userid:'',"class='form-control' onclick='return radioEvent(1)'").'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_radio('pilih','1',$tgl,'id="radTanggal"').'</td>';
            echo '<td>'.form_label('DATE').'</td>';
            echo '<td>'.form_input('tanggal',isset($tanggal)?$tanggal:'','class="form-control"  id="datepicker"  onclick="return radioEvent(0)"').'</td>';
            echo '</tr>';
            echo '<tr><td colspan=3>'.form_submit('submit',' PROCESS','class="btn btn-info"').'</td></tr>';
            echo '</table></div></div>';
            
            echo form_close();

            echo isset($pagination)?$pagination:'';
            echo '<div class="row"><div class="col-md-12"><div id="table-responsive">'.$query.'</div></div></div>';
            echo isset($pagination)?$pagination:'';
        }break;
        case 'print_dialog':
        {
            echo '<div class="con">';
            echo br(2);
            echo form_open($uri);
            echo form_label(" Tanggal : ");
            $options=array();
            echo form_input('tanggal',isset($tanggal)?$tanggal:'','readonly id="datepicker"');
            echo br(2);
            
            echo form_label(" Report : ");
            $options=array();
            $options['1']='CLIENT';
            $options['2']='SUPPLIER';
            $options['3']='ALL';
            $options['4']='SUPPLIER (EXCEL)';
            echo form_dropdown('report', $options, 'MONITOR');
            echo br(2);

            echo form_label(" Format : ");
            $options=array();
            $options['1']='EXCEL';
            echo form_dropdown('format', $options, 'MONITOR');
            echo br(2);
           
            echo form_submit('submit',' PROCESS');
            echo form_close();
            echo '</div>';
        }break;
        case 'confirm':
        {
            ?>
            <!--form><INPUT TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form-->
            <?php
            echo form_open($uri);
            echo '</br><div class="row"><div class="col-md-6"><table class=table>';
            echo '<tr>';
            echo '<td>'.form_label('NO RETUR / nodo').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nodo','','class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO RETUR PEMBELIAN / nodo_beli').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nodo_beli','','class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO SERI ACUAN PENJUALAN / noseri ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('noseri','','class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO SERI ACUAN PEMBELIAN noseri_beli ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('noseri_beli','','class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO TANDA TERIMA / nopo ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nopo','','class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            /*echo '<tr>';
            echo '<td>'.form_label('TANGGAL PEMBELIAN ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tgldo_beli','','id="datepicker"').'</td>';
            echo '</tr>';*/
            echo '<tr>';
            echo '<td>'.form_label('TANGGAL PROSES / tglbuat').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tglbuat','','id="datepicker2" class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('TANGGAL PROSES PEMBELIAN / tglbeli').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tgl_beli','','id="datepicker" class="form-control" autocomplete="off"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="4">'.form_submit('submit','PROCESS','class="btn btn-info"').'</td>';
            echo '</tr>';
            echo '</table></div></div>';
            echo form_close();          

        }break;
        case 'show_detail':
        {

            echo br(3);
            ?>
            <form><INPUT TYPE="button" VALUE="Back" class="btn btn-danger" onClick="history.go(-1);return true;"></form>
            <br/>
            <?php
            echo form_open($uri2);
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<table class="table">';
            echo '<tr>';
            echo '<td>'.form_label('NO RETUR').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nodo',$info->nodo,'class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO RETUR PEMBELIAN').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nodo_beli',$info->nodo_beli,'class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO SERI ACUAN PENJUALAN ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('noseri',$info->noseri,'class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO SERI ACUAN PEMBELIAN ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('noseri_beli',$info->noseri_beli,'class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NO TANDA TERIMA ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('nopo',$info->nopo,'class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('TANGGAL PEMBELIAN ').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tgldo_beli',$info->tgldo_beli,'id="datepicker" class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('TANGGAL PROSES').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tglbuat',$info->tglbuat,'id="datepicker2" class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('TANGGAL BELI').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('tgl_beli',$info->tgl_beli,'id="datepicker3" class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="4">'.form_submit('submit','PROCESS','class="btn btn-info"').'</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
            echo form_close();
           
            echo form_open($uri);
            echo br(1);?>
            <div class="table-responsive">
            <table class="table">
            <tr>
                <th>Product</th><th align="center">Amount (Satuan Terkecil)</th><th align="center">Tambah</th>
            </tr>
            <tr><td>
            <?php
            foreach($query2->result() as $value)
            {
                $product[$value->kodeprod]= $value->namaprod;
            }
            echo form_dropdown('product', $product,'','class="form-control"');
            $query2->free_result();
            ?>
            </td>
            <td>
            <div class="form-inline">
            <input type="text" class='form-control' Style="text-align:right;" value="1"  id="amount" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
            <?php echo form_button('plus','+','id="plus" onclick="add()" class="form-control"')?>
            <?php echo form_button('min','-','id="min" onclick="sub()" class="form-control"')?>
            </div>
            </td>
            <td>
            <?php echo form_submit('submit','Tambah','class="btn btn-info"')?>
            </td>
            </tr>
            </table>
            </div>
            <?php
            echo form_close();

            echo isset($pagination)?$pagination:'';
            echo '<div class="table-responsive">'.$query.'</div>';
            echo isset($pagination)?$pagination:'';


        }break;
        case 'add':
        {
            ?>
            
            <?php echo '<br/>'.form_open($uri_detail);?>
            <div class="form-inline">
            <?php
                foreach($query->result() as $value)
                {
                    $product[$value->kodeprod] = $value->old.' / '.$value->namaprod.' / '.$value->kodeprod;
                }
                echo form_dropdown('product', $product,"","class='form-control'");
                $query->free_result();
                echo form_submit('submit','PILIH',"class='form-control'");
                if(isset($detail))
                {
                    if(!$detail)
                    {
                        echo form_input("warning"," NOT FOUND!","class='form-control' disabled size=20");
                    }
                }
            ?>
            </div>
            <?php echo form_close()?>
            <?php echo form_open($uri);?>
            <br/>
            <?php 
             $kodeprod='';
             $namaprod='';
             $hbeli='';
             $dbeli='';
             $hjual='';
             $djual='';
            if(isset($detail))
            {
                if($detail)
                {
                    if($detail->num_rows()>0)
                    {
                        $row=$detail->row();
                        $kodeprod=$row->kodeprod;
                        $namaprod=$row->namaprod;
                        $hbeli=$row->harga_beli;
                        $dbeli=$row->diskon_beli;
                        $hjual=$row->harga;
                        $djual=$row->diskon;                
                    }
                }
            }
            ?>
            <div class='row'>
            <div class='col-md-12'>
            <table class="table">
            <tr>
                <th>Nama Produk</th>
                <th align="center">Amount (Unit Terkecil)</th>
                <th align="right">Harga Beli</th>
                <th align="right">Diskon Beli</th>
                <th align="right">Harga Jual</th>
                <th align="right">Diskon Jual</th>
                <th align="center">Tambah</th>
            </tr>
            <tr>
                <?php echo form_hidden('kode',$kodeprod,'size="8"');?>
                <div class="form-inline">
                <td>
                    <?php
                    /*foreach($query->result() as $value)
                    {
                        $product[$value->kodeprod]= $value->namaprod;
                    }
                    echo form_dropdown('product', $product);
                    $query->free_result();*/
                    
                    echo form_input('namaprod',$namaprod,'class="form-control" id="namaprod_add" size="40" disabled');
                    ?>
                </td>
                <td>
                    <div class="form-inline">
                    <input type="text" Style="text-align:right;" value="1"  id="amount" class="form-control" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
                    <?php echo form_button('plus','+','id="plus" onclick="add()" class="btn btn-info"')?>
                    <?php echo form_button('min','-','id="min" onclick="sub()" class="btn"')?>
                    </div>
                </td>
                <td>
                    <?php echo form_input('hbeli',$hbeli,'class="form-control" id="hbeli_add" size="10" Style="text-align:right;"');?>
                </td>
                 <td>
                    <?php echo form_input('dbeli',$dbeli,'class="form-control" id="dbeli_add" size="10" Style="text-align:right;"');?>
                </td>
                 <td>
                    <?php echo form_input('hjual',$hjual,'class="form-control" id="hjual_add" size="10" Style="text-align:right;"');?>
                </td>
                 <td>
                    <?php echo form_input('djual',$djual,'class="form-control" id="djual_add" size="10" Style="text-align:right;"');?>
                </td>
                <td>
                    <?php echo form_submit('submit','Tambah','class="btn btn-info"')?>
                </td>
                </div>
            </tr>
            </table></div></div>
            <?php echo form_close();

            if($table!='')
            {
                  ?>
                  <div class='row'>
                  <div class='col-md-8'>
                  <?php
                  echo $table;
                  ?>
                  </div></div>
                  <?php
                  echo br(1);
                  echo 'Click "NEXT" to continue the transaction';
                  echo form_open($uri2);
                  echo br(1);
                  echo form_submit('submit','NEXT','class="btn btn-info"');
                  echo form_close();
            }
            echo form_fieldset_close();

            }break;

    }
?>

<script>
/*<![CDATA[*/

jQuery(document).ready(function($)
{
	$('#thetable').tableScroll({height:200});
});

/*]]>*/
</script>