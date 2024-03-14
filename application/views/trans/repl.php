<script type="text/javascript">
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

	if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
         return true;

}
</script>
<script type="text/javascript">
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
</script>

    <?php

    switch($state)
    {
        case 'show':
        {
            echo br(2);
            echo form_fieldset();
            echo form_open($uri);
            foreach($query2->result() as $value)
            {
                $client[$value->nocab]= $value->nama_comp." ---- ".$value->company;
            }
            echo '<table class="table">';
            echo '<tr>';
            $nocab=true;
            $tgl=false;
            if($pilih=='nocab')
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
            echo '<td>'.form_dropdown('userid', $client,'',"onclick='radioEvent(1)'").'</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>'.form_radio('pilih','1',$tgl,'id="radTanggal"').'</td>';
            echo '<td>'.form_label('DATE').'</td>';
            echo '<td>'.form_input('tanggal',$tanggal,'id="datepicker" onclick="radioEvent(0)"').'</td>';
            echo '</tr>';
            echo '<tr><td colspan=2>'.form_submit('submit',' PROCESS').'</td></tr>';
            echo '</table>';

            if(isset($query))
            {
                echo '<div class="col-md-5">';
                echo $pagination;
                echo $query;
                echo $pagination;
                echo '</div>';
            }
            echo form_close();

           
            echo form_fieldset_close();
            echo br(2);
        }break;
        case 'show_detail':
        {
            echo br(2);
            echo '<form><INPUT TYPE="button" VALUE="Back" onClick="history.go(-1);return true;"></form>';
            echo $query;
            echo form_open($uri2);
            echo form_label('SUPPLIER : ');
            foreach($supp->result() as $value)
            {
                $dd[$value->supp]= $value->namasupp;
            }
            echo form_dropdown('supp',$dd);
            echo form_submit('submit','SAVE');
            echo form_close();
        }break;
        case 'list_upload':
        {
            echo form_open($uri);
            echo br(2);
            foreach($query2->result() as $value)
            {
                 $client[$value->id]= $value->nama_comp.'-----'.$value->company;
            }
            echo form_label('Customer :','drop_userid');
            echo form_dropdown('userid', $client,"","id='drop_userid' class='form-control'");
            echo br();
            echo form_submit('submit','LOOK','class="btn btn-info"');
            echo form_close();

            echo br(2);
            echo $pagination;
            echo "<div class='table-responsive'>".$query."</div>";
            echo $pagination;
        }break;
        case 'detail':
        {
            echo br(2);
            echo form_fieldset();
            echo form_open($uri);
            ?>
            <table class="table">
            <?php
            echo '<tr><td>'.form_label('CUSTOMER').'</td>';
            echo '<td>'.form_input('customerid',$id,'readonly size=2').'-';
            echo form_input('customer','DP '.$customer,'readonly size="30" ').'-';
            echo form_input('company',$company,'readonly size="50" ').'</td></tr>';
            $var=array('3'=>'3 BULAN','6'=>'6 BULAN');
            echo '<tr><td>'.form_label('DATE').'</td>';
            echo '<td>'.form_input('tanggal',$tanggal,'readonly id="datepicker"').'</td></tr>';
            /*echo '<tr><td>'.form_label('SUPPLIER').'</td>';
            foreach($supp->result() as $value)
            {
                $dd[$value->supp]= $value->namasupp;
            }
            echo '<td>'.form_dropdown('supp',$dd).'</td></tr>';*/

            echo '<tr><td>'.form_label('CYCLE').'</td>';
            echo '<td>'.form_dropdown('cycle',$var,$this->input->post('cycle')).'</td></tr>';
            echo '<tr><td>'.form_submit('proses','PROCESS','class="btn btn-info"').'</td></tr>';
            ?>
            </table>
            <?php
            echo form_close();
            echo form_fieldset_close();
            echo br();
            if(isset($query))
            {
                echo form_fieldset();
                echo form_open($uri2);
                
                echo "<div class='table-responsive'>".$query."</div>";
                if($query!='')
                {
                    echo form_label('SUPPLIER : ');
                    foreach($supp->result() as $value)
                    {
                        $dd[$value->supp]= $value->namasupp;
                    }
                    echo form_dropdown('supp',$dd);
                    echo form_submit('submit','SAVE');
                }
                else {
                    echo '<h1>NO RESULT!</h1>';
                }
                echo br(2).$link;
                echo form_close();
                echo form_fieldset_close();
            }
        }break;
    }
?>