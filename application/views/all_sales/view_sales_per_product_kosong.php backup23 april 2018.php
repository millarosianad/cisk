<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/js/checkboxgroup.js"?>"></script>

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
<!--
<script>
function togglecheckboxes(master,group){
    var cbarray = document.getElementsByName(group);
    for(var i = 0; i < cbarray.length; i++){
        cbarray[i].checked = master.checked;
    }
}
</script>
-->
<script>
$(document).ready(function() {
  $("#001").change(function() {
    if (this.checked) {
      $(".001c").each(function() {
        this.checked = true;
      })
      $(".001h").each(function() {
        this.checked = true;
      })
    } else {
      $(".001c").each(function() {
        this.checked = false;
      })
      $(".001h").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001c").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".001h").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001c").prop("checked", true);
        $("#001h").prop("checked", true);
      }
    } else {
      $("#001c").prop("checked", false);
      $("#001h").prop("checked", false);
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

$(document).ready(function() {
  $("#001c").change(function() {
    if (this.checked) {
      $(".001c").each(function() {
        this.checked = true;
      })
    } else {
      $(".001c").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001c").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001c").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001c").prop("checked", true);
      }
    } else {
      $("#001c").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#001h").change(function() {
    if (this.checked) {
      $(".001h").each(function() {
        this.checked = true;
      })
    } else {
      $(".001h").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001h").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001h").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001h").prop("checked", true);
      }
    } else {
      $("#001h").prop("checked", false);
    }
  });
});

var testGroup = new CheckBoxGroup();
testGroup.addToGroup("candy");
testGroup.addToGroup("herbal");
testGroup.setControlBox("001");

</script>

<br><br><br><br>
<form action="examples.php">

  
</form>

<?php echo form_open($url);
?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />
<pre>
<b>Select Product : </b>
<input type="checkbox" id="cbgroup1_master" name="checkall" onclick="checkUncheckAll(this);"/> ALL PRINCIPAL
<input type="checkbox" name="001" id="001" onClick="testGroup.check(this);"/> Check all Deltomed
  <input TYPE="checkbox" NAME="candy" id="001c" VALUE="green" onClick="testGroup.check(this);"> Candy
  <input TYPE="checkbox" NAME="herbal" id="001h" VALUE="green2" onClick="testGroup.check(this);"> Herbal
<input type="checkbox" name="002" id="002" /> Check all Marguna 
<input type="checkbox" name="005" id="005" /> Check all Ultra Sakti
<input type="checkbox" name="004" id="004" /> Check all Jaya Agung
</pre>
<hr>
<div class='row'>

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
            $supp=$value->supp;
            $namasupp=$value->namasupp;
            echo '<tr><td colspan=4><h4><strong>'.$namasupp.'</strong></h4></td></tr><tr>';            
            $count=1;
        }

        if($count%4!=0)
        {
          ?>
          <td>
             <div class="checkbox-inline">
             <input name="options[]" type="CHECKBOX" id="" class = "<?php echo $value->suppx ?>" value="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod.' ('.$value->kodeprod.')';?>
             </div>
          </td>
          
          <?php
        }
        else
        {
            ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]" type="CHECKBOX" id="" class = "<?php echo $value->suppx ?>" value="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod.' ('.$value->kodeprod.')';?>
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