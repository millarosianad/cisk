<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script type="text/javascript">
         
            function ValidateCompare(){

                var c=document.getElementsByName("options[]");
                var count=0;
                for(var i=0;i<c.length;i++)
                {
                    if(c[i].checked)
                    {
                        count++;
                    }
                }
                if (count<1) {
                    alert("PILIH PRODUK YANG AKAN DIAMATI.");
                    return false; 
                }
                return true;
                }
</script>

<script>
function togglecheckboxes(master,group){
    var cbarray = document.getElementsByName(group);
    for(var i = 0; i < cbarray.length; i++){
        cbarray[i].checked = master.checked;
    }
}
</script>

<script>
$(document).ready(function() {
  $("#001").change(function() {
    if (this.checked) {
      $(".001").each(function() {
        this.checked = true;
      })
    } else {
      $(".001").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001").prop("checked", true);
      }
    } else {
      $("#001").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#002").change(function() {
    if (this.checked) {
      $(".002").each(function() {
        this.checked = true;
      })
    } else {
      $(".002").each(function() {
        this.checked = false;
      })
    }
  });

  $(".002").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".002").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#002").prop("checked", true);
      }
    } else {
      $("#002").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#005").change(function() {
    if (this.checked) {
      $(".005").each(function() {
        this.checked = true;
      })
    } else {
      $(".005").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#005").prop("checked", true);
      }
    } else {
      $("#005").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#004").change(function() {
    if (this.checked) {
      $(".004").each(function() {
        this.checked = true;
      })
    } else {
      $(".004").each(function() {
        this.checked = false;
      })
    }
  });

  $(".004").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".004").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#004").prop("checked", true);
      }
    } else {
      $("#004").prop("checked", false);
    }
  });
});

</script>


<?php echo form_open($url);
?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />
<pre>
<b>Select Product : </b>
<input type="checkbox" id="cbgroup1_master" name="checkall" onclick="checkUncheckAll(this);"/> ALL PRINCIPAL
<input type="checkbox" name="001" id="001" /> Check all Deltomed
<input type="checkbox" name="002" id="002" /> Check all Marguna
<input type="checkbox" name="004" id="004" /> Check all Jaya Agung
</pre>
<hr>
<div class='row'>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label("DP : ");
                foreach($query2->result() as $value)
                {
                    $dd[$value->kode_comp]= $value->nama_comp;
                }
                echo form_dropdown('nocab',$dd,'','class="form-control"');
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php
                echo form_label(" Year : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, date('Y'),'class="form-control"');
            ?>
        </div>
    </div>
   
     <div class="col-md-2">
        <div class="form-group">
            <?php        
                echo form_label(" UNIT/VALUE : ");
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">

        <!-- cek ke fungsi validatecompare, untuk cek kalau belum pilih satu produk pun -->
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>

        </div>
    </div>    
</div>
<br />

    <?php
    echo form_label("Product : ");
    $supp='';
    echo '<table class="table table-hover">';
    $count=1;
    
    foreach($query->result() as $value)
    {
        if($supp!=$value->supp)
        {
            //echo $col=4-($count%4);
            $supp=$value->supp;
            $namasupp=$value->namasupp;

            //menampilkan judul / nama principal (ex. Delto, ultra, marguna)
            echo '<tr><td colspan=4><h4><strong>'.$namasupp.'</strong></h4></td></tr><tr>';
            
            $count=1;
        }

        if($count%4!=0)
        {
        ?>
        <td>
           <div class="checkbox-inline">
           <input name="options[]" type="CHECKBOX" id="" class = "<?php echo $value->supp ?>" value="<?php echo $value->namaprod ?>"><?php echo $value->namaprod.' <i>('.$value->kode_bsp.')</i>';?>
           </div>
        </td>
        <!-- <?php //echo $value->supp."[]" ?> -->
        
        <?php
        }
        else
        {
            ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]"" type="CHECKBOX" id="" class = "<?php echo $value->supp ?>" value="<?php echo $value->namaprod ?>"><?php echo $value->namaprod.' <i>('.$value->kode_bsp.')</i>';?>
                </div>
            </td>
        <?php
            echo '</tr><tr>';
        }
        $count++;
    }
    echo '</tr></table>';

?>
<?php echo form_close();?>