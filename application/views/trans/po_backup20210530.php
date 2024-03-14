<!--script type="text/javascript">
  $(document).ready(function() {
    $("#datepicker").datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
  });
</script-->
<script type="text/javascript">
$(document).ready(function() { 
        $("#drop_supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>trans/buildgroup_target",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#group").html(data);
                }
            });
        });
    });
</script>
 <script>
$(function() {
$( "#datepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
        changeYear:true,
        changeMonth:true
    });
});


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

</script>
<?php
    echo br(2);
    switch($state)
    {
        case 'rekap_dialog':
        {
        ?><div class="con">
                <?php
                echo form_open($uri);
                echo form_label('EXPORT RECAP PO').br(2);
                echo form_label("Supplier : ");
                foreach($supp->result() as $value)
                {
                    $dd[$value->supp]= $value->namasupp;
                }
                echo form_dropdown('supp',$dd).br(2);
                echo form_label(" MONTH : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $months = array(1=>'JAN',2=>'FEB',3=>'MAR',4=>'APR',5=>'MAY',6=>'JUN',
                                7=>'JUL',8=>'AUG',9=>'SEP',10=>'OCT',11=>'NOV',12=>'DEC');
                echo form_dropdown('month', $months,date('m')).br(2);
                echo form_label(" YEAR : ");
                $interval=date('Y')-2010;
                $options=array();
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
            
                echo form_dropdown('year', $options,date('Y'),"class='form-inline'");
                echo br(2);
                echo form_submit('print','PROCESS');
                echo form_close();
                ?>
            </div><?php
        }break;
        case 'download':
        {
             echo br(2);
             echo form_open($uri);
             echo form_input('download',$keyword,'id="keyword"');
             echo form_submit('search','DOWNLOAD');
             echo form_close();
        }break;
        case 'manual':
        {
            ?>
            <?php echo form_fieldset($page_title);?>
            <?php echo form_open($url);

            /*$tipe = array('A'=>'ALOKASI','S'=>'SPK');
            echo br();
            echo form_label('PO TYPE :');
            echo form_dropdown('tipe', $tipe);
            echo br(2);*/
            ?>
            <div class='row'>
            <div class='col-md-8'>
            <table class="table">
            <tr>
                <th>Product</th>
                <th align="center">Amount (KARTON)</th>
                <th align="center">Tambah</th>
            </tr>
            <tr bgcolor="#DEF3CC"><td>
            <?php
            foreach($query->result() as $value)
            {
                $product[$value->kodeprod]= $value->kodeprod.'-'.$value->namaprod.' ( @'.$value->isisatuan.' ) - '.$value->kodeprod;
            }
            echo form_dropdown('product', $product,'',"class='form-control'");
            $query->free_result();
            ?>
            </td>
            <td>
            <div class="form-inline">
            <input type="text" Style="text-align:right;" value="1" class="form-control" id="amount" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
            <?php echo form_button('plus','+','id="plus" onclick="add()" class="btn btn-info"')?>
            <?php echo form_button('min','-','id="min" onclick="sub()" class="btn"')?>
            </div>
            </td>
            <td>
            <?php echo form_submit('submit','Tambah','class="btn"')?>
            </td>
            </tr>
            </table>
            </div>
            </div>
            <?php

            echo form_close();
            if($table!='')
            {

                  echo $table;
                  echo br(1);
                  echo 'Click "NEXT" to continue transaction';
                  echo form_open($url2);
                  echo br(1);
                  
                  echo form_submit('submit','NEXT');
                  echo form_close();
            }
            ?>
            <?php echo form_fieldset_close();

        }break;
        case 'manual_confirm':
        {
            echo form_fieldset($page_title);?>
            <form><INPUT TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
            <?php
            echo '<table>';
            echo '<tr>';
            echo '<td>'.form_label('PELANGGAN *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('pelanggan',$client->company,'id="pelanggan" size="70" readonly').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NPWP *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('npwp',$client->npwp,'id="npwp" size="70" readonly').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('EMAIL *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('email',$client->email,'id="email" size="70" readonly').'</td>';
            echo '</tr>';
            echo '</table>';

            echo '<h2>'.form_label('Pilih alamat lain jika pengiriman tidak ditujukan pada alamat tersimpan!').'</h2>';
            echo form_open($url);

            $tipe = array('A'=>'ALOKASI','S'=>'SPK');

            echo '<table>';
            echo '<tr>';
            echo '<td>'.form_radio('alamat','saved',true).'</td>';
            echo '<td>'.form_label('ALAMAT TERSIMPAN *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_textarea('alamatsaved',$client->address,'id="alamat" readonly').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_radio('alamat','new').'</td>';
            echo '<td>'.form_label('ALAMAT LAIN').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_textarea('alamatlain').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="2">'.form_dropdown('tipe', $tipe).'</td>';
            echo '<td colspan="2">'.form_submit('submit','PURCHASE','onclick="return check()"').'</td>';
            echo '</tr>';
            echo '</table>';
            //echo form_hidden('tipe',$tipe);

            echo form_close();
            echo '<h3>'.form_label('(*)Tidak boleh kosong. Klik '.  anchor('user/account_edit','account').' untuk memperbaharui data pelanggan').'</h3>';
            echo form_fieldset_close();

        }break;
        case 'show_supp':
        {
            ?>
            <?php echo form_open($url);?>
          
            <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -170px;">
           
            <?php
                echo form_label("Supplier : ");
                foreach($query->result() as $value)
                {
                    $dd[$value->supp]= $value->namasupp;
                }
                echo form_dropdown('supp',$dd,'','id="drop_supp" class="form-control"');
               
                foreach($query2->result() as $value)
                {
                     $client[$value->id]= $value->company.' ( '.$value->kode_dp.' )';
                }

                echo form_label('Customer :');
                echo form_dropdown('userid', $client,'','class="form-control"');
                //echo form_label(" Year : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                //echo form_dropdown('year', $options, date('Y'));
                echo form_label("Group Product : ",'drop_group');
                    $group=array();
                echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"');
                echo br();
            ?>
           
            <?php echo '<br/>'.form_submit('submit','Proses','class="btn btn-info"'); ?>
            </div>
            <?php echo form_close();
        }break;
        case 'open':
        {
            echo br();
            //echo isset($add)? $add.$printer:'';
            //echo br(2);
            if(isset($sum))
            {
                echo '<h1>Total Outlet : '.$sum.'</h1>'.br(2);
            }
            /*if(isset($keyword))
            {
                echo form_open($uri);
                echo form_input('keyword',$keyword,'id="keyword"');
                echo form_submit('search','LOOK');
                echo form_close();
                echo br(2);
            }*/
            echo form_open($uri);
            foreach($query2->result() as $value)
            {
                $client[$value->id]= $value->company." ".($value->kode_dp==''?'':' ( '.$value->kode_dp.' )');
            }
            ?>
            <div class='table-responsive'>
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
            echo '<td>'.form_input('tanggal',isset($tanggal)?$tanggal:'','readonly id="datepicker"  onclick="return radioEvent(0)"').'</td>';
            echo '</tr>';
            echo '<tr><td colspan=3>'.form_submit('submit',' SUBMIT','class="btn btn-default"').'</td></tr>';
            echo '</table>';
            echo '</div>';
            echo form_close();

            echo isset($pagination)?'<div>'.$pagination.'</div>':'';
            echo '<div id="center">'.$query.'</div>';
            echo isset($pagination)?'<div>'.$pagination.'</div>':'';
        }break;
        case 'show':
        {
            echo br(2);
            echo isset($add)? $add:'';
            echo isset($printer)? $printer:'';
            echo br(2);
            if(isset($sum))
            {
                echo '<h1>Total Outlet : '.$sum.'</h1>'.br(2);
            }
            /*if(isset($keyword))
            {
                echo form_open($uri);
                echo form_input('keyword',$keyword,'id="keyword"');
                echo form_submit('search','LOOK');
                echo form_close();
                echo br(2);
            }*/
            echo form_open($uri);
            foreach($query2->result() as $value)
            {
                $client[$value->id]= $value->company."-".$value->username;
                //$client[$value->id]= $value->company." ".($value->kode_dp==''?'':' ( '.$value->kode_dp.' )');
            }
            ?>
            <div class='row'>
                <div class='col-md-6'>
            <?php
            echo '<div class="table-responsive">';
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
            echo '<td>'.form_input('tanggal',isset($tanggal)?$tanggal:'','readonly id="datepicker"  onclick="return radioEvent(0)"').'</td>';
            echo '</tr>';
            echo '<tr><td colspan=3>'.form_submit('submit','Submit','class="btn btn-default"').'</td></tr>';
            echo '</table>';
            echo '</div></div></div>';
            echo form_close();

            echo isset($pagination)?$pagination:'';
            echo '<div id="table-responsive">'.$query.'</div>';
            echo isset($pagination)?$pagination:'';
        }break;
        case 'show_detail':
        {

            echo br();
            //echo $half;
            //echo br(3);
            ?>
            <div class='row'>
            <div class="col-md-8">
            <div class='form-inline'>
            
            <?php 
                $id_po = $this->uri->segment('4');
                $supp_po = $this->uri->segment('5');
            ?>
            
            
            
            <?php

            $mpo=array(1=>'I',2=>'II',3=>'III',4=>'IV',
                       5=>'V',6=>'VI',7=>'VII',8=>'VIII',
                       9=>'IX',10=>'X',11=>'XI',12=>'XII'
                );
            $interval=date('Y')-2010;
            $options=array();
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            echo br(1);

            $kodemax = $this->uri->segment('6');
            //echo $kodemax;

            $nopo = $query3->nopo;
            //echo "<br>nopo : ".$nopo."<br>";
            $nopo_x = substr($nopo, 0,4);
            //echo "<br>nopo_x : ".$nopo_x."<br>";
            

            echo form_open($uri2);

            if ($nopo != '' && $nopo_x != '/MPM') {
                ?>
                <INPUT TYPE="button" VALUE="Kembali" class="btn btn-danger" onClick="history.go(-1);return true;">
                <br><br>
                <?php
                echo form_label('NO PURCHASE ORDER : ');
                echo form_input('nopo',substr($query3->nopo,0,6),'class="form-control"').' / MPM / '.  form_dropdown('mpo',$mpo,date('n'),'class="form-control"').' / '.form_dropdown('ypo',$options,date('Y'),'class="form-control"');
            }else{
                ?>
                <INPUT TYPE="button" VALUE="Kembali" class="btn btn-danger" onClick="history.go(-1);return true;">
                <a href="<?php echo base_url()."all_po/generate/".$id_po."/".$supp_po; ?>  " class="btn btn-success" role="button">Generate No PO</a>
                <br><br>
                <?php

                echo form_label('NO PURCHASE ORDER : ');
                echo form_input('nopo',$kodemax,'class="form-control"').' / MPM / '.  form_dropdown('mpo',$mpo,date('n'),'class="form-control"').' / '.form_dropdown('ypo',$options,date('Y'),'class="form-control"');
            }

            echo br(2);
            echo form_label('NOTE : ');
            echo br();
            echo form_textarea('note',$query3->note,'class="form-control"');
            echo br(2);
            echo form_label('Alamat : ');
            echo br();
            echo form_textarea('alamat',$query3->alamat,'class="form-control"');
            echo br();
            
            echo form_submit('submit','SUBMIT','class="btn btn-info"');
             
                //ambil uri segment
                $id_po = $this->uri->segment('4');
                $supp_po = $this->uri->segment('5');
                //echo $id_po;
            ?>           


            <?php
            echo form_close();
            echo form_open($uri);
            echo br(1);?>
            </div></div></div>
            <div class='row'>
            <div class="col-md-9">
            <table class='table'>
                <tr>
                    <th>Product</th><th align="center">Amount (KARTON)</th><th align="center">Tambah</th>
                    <th align="center">Cek DOI</th>
                </tr>
                <tr>
                    <td>
                        <?php
                            foreach($query2->result() as $value)
                            {
                                $product[$value->kodeprod]= $value->kodeprod.' - '.$value->namaprod;
                            }
                            echo form_dropdown('product', $product,'','class="form-control"');
                            $query2->free_result();
                        ?>
                    </td>
                    <td>
                        <div class="form-inline">
                        <input type="text" Style="text-align:right;" value="1"  class='form-control' id="amount" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
                        <?php echo form_button('plus','+','class="btn btn-info" id="plus" onclick="add()"')?>
                        <?php echo form_button('min','-','class="btn btn" id="min" onclick="sub()"')?>
                        </div>
                    </td>            
                    <td>
                        <?php echo form_submit('submit','Tambah','class="btn btn-info"')?>
                    </td>
                    <td>
                        <?php 
                            //ambil uri segment
                            $id_po = $this->uri->segment('4');
                            $supp_po = $this->uri->segment('5');
                            //echo $id_po;
                         ?>            
                        <a href="<?php echo base_url()."all_po/update_doi/".$id_po."/".$supp_po; ?>  " class="btn btn-warning" role="button">Proses Cek
                    </td>
            
                </tr>
            </table>
            </div></div>

            <?php
            echo form_close();
            /* isi tabel */
            echo isset($pagination)?$pagination:'';
            echo '<div class="col-md-8">';
            echo '<div id="center">'.$query.'</div>';
            echo '</div>';
            echo isset($pagination)?$pagination:'';


        }break;
        case 'add':
        {
            ?>
            <?php echo form_fieldset($page_title);?>
            <?php echo form_open($url);?>

            <table>
            <tr>
                <th bgcolor="#666666">Product</th><th bgcolor="#666666" align="center">Amount (KARTON)</th><th bgcolor="#666666" align="center">Tambah</th>
            </tr>
            <tr bgcolor="#DEF3CC"><td>
            <?php
            foreach($query->result() as $value)
            {
                $product[$value->kodeprod]= $value->namaprod;
            }
            echo form_dropdown('product', $product);
            $query->free_result();
            ?>
            </td>
            <td>
            <input type="text" Style="text-align:right;" value="1"  id="amount" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
            <?php echo form_button('plus','+','id="plus" onclick="add()"')?>
            <?php echo form_button('min','-','id="min" onclick="sub()"')?>
            </td>
            <td>
            <?php echo form_submit('submit','Tambah')?>
            </td>
            </tr>
            </table>
            <?php echo form_close();
            if($table!='')
            {
                  echo $table;
                  echo br(1);
                  echo 'Klik "PURCHASE" to finish the transaction';
                  echo form_open($url2);
                  echo br(1);
                  echo form_submit('submit','PURCHASE');
                  echo form_close();
            }
            echo form_fieldset_close();

            }break;?>
        <?php

        case 'edit':
        {
            ?>
            <?php echo form_fieldset('Edit Dokter');?>

            <?php echo form_open('apotek/dokter/update');?>
            <table>
            <tr>
                <td><?php echo form_label('kode');?></td>
                <td>:</td>
                <td><?php echo form_input('id',$edit->id,'readonly');?></td>
            </tr>
            <tr>
                <td><?php echo form_label('NAMA');?></td>
                <td>:</td>
                <td><?php echo form_input('nama',$edit->nama);?></td>
            </tr>
            <tr>
                <td><?php echo form_label('TELEPON');?></td>
                <td>:</td>
                 <td><?php echo form_input('telepon',$edit->telepon);?></td>
            </tr>
            <tr>
                <td><?php echo form_label('ALAMAT');?></td>
                <td>:</td>
                <td><?php echo form_textarea('alamat',$edit->alamat);?></td>
            </tr>
            </table>
            <?php
            echo br(2);
            echo form_submit('submit','UPDATE');
            echo form_reset('reset','RESET');

            echo form_close();
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