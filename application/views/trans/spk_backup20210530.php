<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
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

<script type="text/javascript">


function onlyNumbers(event)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;

}


function radioEvent(a)
{
    //alert(a);
    switch(a)
    {
        case 1:
          document.getElementById("saved").checked=true;
         // document.getElementById("radTanggal").check=false;
          break;
        case 0:
          document.getElementById("new").checked=true;
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
<?php

    switch(strtolower($state))
    {
        case 'check_order':
        {
            echo isset($print_pdf)   ? $print_pdf:'';
            echo isset($print_excel) ? $print_excel:'';
            echo br(2);
            echo isset($add)? $add:'';
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


            echo isset($pagination)?$pagination:'';
            echo '<div id="center">'.$query.'</div>';
            echo isset($pagination)?$pagination:'';
        }break;
        case 'show_supp':
        {
            ?>
            <?php echo form_open($url);?>
            <div style="position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -50px;
            margin-left: -100px;">
            <?php
                echo form_label("Supplier : ",'drop_supp');
                foreach($query->result() as $value)
                {
                    $dd[$value->supp]= $value->namasupp;
                }
                echo form_dropdown('supp',$dd,'','id="drop_supp" class="form-control"');
                echo br();

                echo form_label("Group Product: ",'drop_group');
                    $group=array();
                echo form_dropdown('group', $group, 'ALL','class="form-control" id="group"');
                echo br();
                //echo form_label(" Year : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                //echo form_dropdown('year', $options, date('Y'));
            ?>
            <?php echo form_submit('submit','Proses','class="btn btn-info"'); ?>
            </div>
            <?php echo form_close();
        }break;
        case 'show':
        {
            echo isset($print_pdf)   ? $print_pdf:'';
            echo isset($print_excel) ? $print_excel:'';
            echo br(2);
            echo isset($add)? $add:'';
            echo br(2);
            if(isset($sum))
            {
                echo '<h1>Total Outlet : '.$sum.'</h1>'.br(2);
            }
            if(isset($keyword))
            {
                echo form_open($uri);
                echo form_input('keyword',$keyword);
                echo form_submit('search','SEARCH');
                echo form_close();
                echo br(2);
            }


            echo isset($pagination)?$pagination:'';
            echo '<div id="center">'.$query.'</div>';
            echo isset($pagination)?$pagination:'';
        }break;
        case 'confirm':
        {
            echo br().form_fieldset($page_title);?>
            <form><INPUT class='btn btn-info'TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>
            <?php
            echo br();
            echo "<div class='table-responsive'>";
            echo '<table class="table">';
            echo '<tr>';
            echo '<td>'.form_label('PELANGGAN *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('pelanggan',$client->company,'id="pelanggan" size="70" readonly class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('NPWP *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('npwp',$client->npwp,'id="npwp" size="70" readonly class="form-control"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_label('EMAIL *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_input('email',$client->email,'id="email" size="70" readonly class="form-control"').'</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';

            echo '<h3>'.form_label('Pilih alamat lain jika pengiriman tidak ditujukan pada alamat tersimpan!').'</h3>';
            echo form_open($url);
            echo "<div class='table-responsive'>";
            echo '<table class="table">';
            echo '<tr>';
            echo '<td>'.form_radio('alamat','saved',true,'id="saved"').'</td>';
            echo '<td>'.form_label('ALAMAT TERSIMPAN *').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_textarea('alamatsaved',$client->address,'id="alamat" readonly class="form-control" onclick="return radioEvent(1)"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_radio('alamat','new',false,'id="new"').'</td>';
            echo '<td>'.form_label('ALAMAT LAIN').'</td>';
            echo '<td>'.form_label(':').'</td>';
            echo '<td>'.form_textarea('alamatlain','', ' class="form-control" onclick="return radioEvent(0)"').'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="4">'.form_submit('submit','PURCHASE','class="btn btn-info" onclick="return check()"').'</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            echo form_close();
            echo '<h3>'.form_label('(*)Tidak boleh kosong. Klik '.  anchor('user/account_edit','account').' untuk memperbaharui data pelanggan').'</h3>';
            echo form_fieldset_close();
            
        }break;
        case 'add':
        {
            ?>
            <?php echo br().form_fieldset($page_title);?>
            <?php echo form_open($url);?>
            <div class="row">
            <div class="col-md-9">
            <div class='table-responsive'>
            <table class="table">
            <tr>
                <th>Product</th><th align="center">Amount (KARTON/DUS)</th><th align="center">Tambah</th>
            </tr>
            <tr bgcolor="#DEF3CC"><td>
            <?php
            foreach($query->result() as $value)
            {
                $product[$value->kodeprod]= $value->namaprod." ( @".$value->odrunit." ) - ".$value->kodeprod;
            }
            echo form_dropdown('product', $product,'','class="form-control"');
            $query->free_result();
            ?>
            </td>
            <td>
            <div class="form-inline">
            <input type="text" class="form-control" Style="text-align:right;" value="1"  id="amount" name="amount" size="10" onkeypress="return onlyNumbers(event);" />
            <?php echo form_button('plus','+','id="plus" class="btn btn-info" onclick="add()"')?>
            <?php echo form_button('min','-','id="min" class="btn" onclick="sub()"')?>
            </div>
            </td>
            <td>
            <?php echo form_submit('submit','Tambah','class="btn btn-info"')?>
            </td>
            </tr>
            </table>
            </div></div></div>
            <?php echo form_close();         
          
            if($table!='')
            {
                  ?>
                  <div class="row">
                      <div class='col-md-5'>
                  <?php echo $table;?>
                  </div></div>    
                  <?php
                  echo br(1);
                  echo 'Click "NEXT" to continue the transaction';
                  echo form_open($url2);
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