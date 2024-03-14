<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/js/checkboxgroup.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#subbranch").click(function(){
          var id_subbranch=$(this).val();
          console.log(id_subbranch)
            $.ajax({
            url:"<?php echo base_url(); ?>outlet/buildSalesName",    
            // data: {id_subbranch: $(this).val()},
            data: {id_subbranch: $(this).val().toString()},
            type: "POST",
            success: function(data){
                $("#salesman").html(data);
                }
            });
        });
    });
      
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>c_dp/build_namacomp",    
            data: {id_year: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>
<script type="text/javascript">
    function ValidateCompare()
    {
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

  $(document).ready(function() {
    $("#001").change(function() {
      if (this.checked) {
        $(".001G0102").each(function() {
          this.checked = true;
        })
        $(".001G0101").each(function() {
          this.checked = true;
        })
        $(".001G0103").each(function() {
          this.checked = true;
        })
      } else {
        $(".001G0102").each(function() {
          this.checked = false;
        })
        $(".001G0101").each(function() {
          this.checked = false;
        })
        $(".001G0103").each(function() {
          this.checked = false;
        })
      }
    });

  $(".001").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001G0102").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".001G0101").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".001G0103").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001G0102").prop("checked", true);
        $("#001G0101").prop("checked", true);
        $("#001G0103").prop("checked", true);
      }
    } else {
      $("#001G0102").prop("checked", false);
      $("#001G0101").prop("checked", false);
      $("#001G0103").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#002").change(function() {
    if (this.checked) {
      $(".002G0201").each(function() {
        this.checked = true;
      })
      $(".002G0202").each(function() {
        this.checked = true;
      })
    } else {
      $(".002G0201").each(function() {
        this.checked = false;
      })
      $(".002G0202").each(function() {
        this.checked = false;
      })
    }
  });

  $(".002").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".002G0201").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".002G0202").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#002G0201").prop("checked", true);
      }
      if (isAllChecked2 == 0) {
        $("#002G0202").prop("checked", true);
      }
    } else {
      $("#002G0201").prop("checked", false);
      $("#002G0202").prop("checked", false);
    }
  });
});

$(document).ready(function() {
    $("#005").change(function() {
      if (this.checked) {
        $(".005G0501").each(function() {
          this.checked = true;
        })
        $(".005G0502").each(function() {
          this.checked = true;
        })
        $(".005G0503").each(function() {
          this.checked = true;
        })
        $(".005G0504").each(function() {
          this.checked = true;
        })
        $(".005G0505").each(function() {
          this.checked = true;
        })
        $(".005G0506").each(function() {
          this.checked = true;
        })
      } else {
        $(".005G0501").each(function() {
          this.checked = false;
        })
        $(".005G0502").each(function() {
          this.checked = false;
        })
        $(".005G0503").each(function() {
          this.checked = false;
        })
        $(".005G0504").each(function() {
          this.checked = false;
        })
        $(".005G0505").each(function() {
          this.checked = false;
        })
        $(".005G0506").each(function() {
          this.checked = false;
        })
      }
    });

  $(".005").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0501").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".005G0502").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".005G0503").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".005G0504").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".005G0505").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      $(".005G0506").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0501").prop("checked", true);
        $("#005G0502").prop("checked", true);
        $("#005G0503").prop("checked", true);
        $("#005G0504").prop("checked", true);
        $("#005G0505").prop("checked", true);
        $("#005G0506").prop("checked", true);
      }
    } 
    else {
      $("#005G0501").prop("checked", false);
      $("#005G0502").prop("checked", false);
      $("#005G0503").prop("checked", false);
      $("#005G0504").prop("checked", false);
      $("#005G0505").prop("checked", false);
      $("#005G0506").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#004").change(function() {
    if (this.checked) {
      $(".004G0401").each(function() {
        this.checked = true;
      })
    } else {
      $(".004G0401").each(function() {
        this.checked = false;
      })
    }
  });

  $(".004").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".004G0401").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#004G0401").prop("checked", true);
      }
    } else {
      $("#004G0401").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#001G0102").change(function() {
    if (this.checked) {
      $(".001G0102").each(function() {
        this.checked = true;
      })
    } else {
      $(".001G0102").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001G0102").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001G0102").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001G0102").prop("checked", true);
      }
    } else {
      $("#001G0102").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#001G0101").change(function() {
    if (this.checked) {
      $(".001G0101").each(function() {
        this.checked = true;
      })
    } else {
      $(".001G0101").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001G0101").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001G0101").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001G0101").prop("checked", true);
      }
    } else {
      $("#001G0101").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#001G0103").change(function() {
    if (this.checked) {
      $(".001G0103").each(function() {
        this.checked = true;
      })
    } else {
      $(".001G0103").each(function() {
        this.checked = false;
      })
    }
  });

  $(".001G0103").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".001G0103").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#001G0103").prop("checked", true);
      }
    } else {
      $("#001G0103").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#002G0201").change(function() {
    if (this.checked) {
      $(".002G0201").each(function() {
        this.checked = true;
      })
    } else {
      $(".002G0201").each(function() {
        this.checked = false;
      })
    }
  });

  $(".002G0201").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".002G0201").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#002G0201").prop("checked", true);
      }
    } else {
      $("#002G0201").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#002G0202").change(function() {
    if (this.checked) {
      $(".002G0202").each(function() {
        this.checked = true;
      })
    } else {
      $(".002G0202").each(function() {
        this.checked = false;
      })
    }
  });

  $(".002G0202").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".002G0202").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#002G0202").prop("checked", true);
      }
    } else {
      $("#002G0202").prop("checked", false);
    }
  });
});


$(document).ready(function() {
  $("#005G0501").change(function() {
    if (this.checked) {
      $(".005G0501").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0501").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0501").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0501").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0501").prop("checked", true);
      }
    } else {
      $("#005G0501").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#005G0502").change(function() {
    if (this.checked) {
      $(".005G0502").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0502").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0502").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0502").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0502").prop("checked", true);
      }
    } else {
      $("#005G0502").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#005G0503").change(function() {
    if (this.checked) {
      $(".005G0503").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0503").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0503").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0503").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0503").prop("checked", true);
      }
    } else {
      $("#005G0503").prop("checked", false);
    }
  });
});


$(document).ready(function() {
  $("#005G0504").change(function() {
    if (this.checked) {
      $(".005G0504").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0504").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0504").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0504").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0504").prop("checked", true);
      }
    } else {
      $("#005G0504").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#005G0505").change(function() {
    if (this.checked) {
      $(".005G0505").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0505").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0505").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0505").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0505").prop("checked", true);
      }
    } else {
      $("#005G0505").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#005G0506").change(function() {
    if (this.checked) {
      $(".005G0506").each(function() {
        this.checked = true;
      })
    } else {
      $(".005G0506").each(function() {
        this.checked = false;
      })
    }
  });

  $(".005G0506").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".005G0506").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked == 0) {
        $("#005G0506").prop("checked", true);
      }
    } else {
      $("#005G0506").prop("checked", false);
    }
  });
});

$(document).ready(function() {
  $("#012").change(function() {
    if (this.checked) {
      $(".012").each(function() {
        this.checked = true;
      })
    } else {
      $(".012").each(function() {
        this.checked = false;
      })
    }
  });

  $(".012").click(function() {
    if ($(this).is(":checked")) {
      var isAllChecked = 0;
      $(".012").each(function() {
        if (!this.checked)
          isAllChecked = 1;
      })
      if (isAllChecked2 == 0) {
        $("#012").prop("checked", true);
      }
    } else {
      $("#012").prop("checked", false);
    }
  });
});

var testGroup = new CheckBoxGroup();
testGroup.addToGroup("beverage");
testGroup.addToGroup("candy");
testGroup.addToGroup("herbal");
testGroup.setControlBox("001");

var testGroupM = new CheckBoxGroup();
testGroupM.addToGroup("pilkita");
testGroupM.addToGroup("otherm");
testGroupM.setControlBox("002");

var testGroupUs = new CheckBoxGroup();
testGroupUs.addToGroup("freshcare");
testGroupUs.addToGroup("hotin");
testGroupUs.addToGroup("madu");
testGroupUs.addToGroup("mywell");
testGroupUs.addToGroup("tresnojoyo");
testGroupUs.addToGroup("other");
testGroupUs.setControlBox("005");

var testGroupJ = new CheckBoxGroup();
testGroupJ.setControlBox("004");

var testGroupI = new CheckBoxGroup();
testGroupI.setControlBox("012");

</script>

<?php echo form_open($url);
?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />
<hr>
<div class='row'>
   

  <div class="col-xs-3">
    Tahun (*)
  </div>

  <div class="col-xs-5">
    <?php 
    
    $interval=date('Y')-2015;
    $options=array();
    $options['0']='- Pilih Tahun -';
    // $options['2015']='2015';
    for($i=1;$i<=$interval;$i++)
    {
        $options[''.$i+2015]=''.$i+2015;
    }
    echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
      
    ?>
  </div>
  
  <div class="col-xs-11">&nbsp;</div>

  <div class="col-xs-3">
    Sub Branch <br> <i>(*) Untuk menarik beberapa Sub Branch Sekaligus. Caranya : tekan tombol <strong>ctrl / shift</strong> di keyboard lalu klik Sub Branch - Sub Branch yang diinginkan</i>)
  </div>

  <div class="col-xs-5">
      <div class="form-group">
          <?php        
              $options=array();
              // $options['0']='- Pilih Sub Branch -';
              // echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
          ?>
          <select multiple name = "nocab[]" id = "subbranch" class = "form-control" size = "7">
        </select>
      </div>
  </div> 

  <div class="col-xs-11">&nbsp;</div>
  
  <div class="col-xs-3">
    Unit / Value (*)
  </div>

  <div class="col-xs-5">
      <div class="form-group">
          <?php        
            $options=array();
            $options['0']='UNIT';
            $options['1']='VALUE';
            echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
          ?>
      </div>
  </div>

  <div class="col-xs-11">&nbsp;</div>

  <div class="col-xs-3">
    Salesman (*)
  </div>

  <div class="col-xs-5">
      <div class="form-group">
          <?php        
              $options=array();
              $options['0']='ALL';
              $options['1']='PILIH DP';
              echo form_dropdown('sm', $options, 'ALL','class="form-control" id="salesman"');
          ?>
      </div>
  </div>

  <div class="col-xs-11">&nbsp;</div>

  <div class="col-xs-3">
    Breakdown (*)
  </div>

  <div class="col-xs-5">
      <div class="form-group">
          <?php        
              $options=array();
              $options['0']='-';
              $options['1']='Kode Produk';
              echo form_dropdown('bd', $options, 'ALL','class="form-control"');
          ?>
      </div>
  </div>

  <div class="col-xs-11">&nbsp;</div>

  <div class="col-xs-3">
    </div>

  <div class="col-xs-8">
    <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>        
  </div>
        
</div>

<br />

<pre>
<b>Select Product : </b>
<input type="checkbox" id="cbgroup1_master" name="checkall" onclick="checkUncheckAll(this);"/> ALL PRINCIPAL
<input type="checkbox" name="001" id="001" onClick="testGroup.check(this);"/> Check all Deltomed
  <input TYPE="checkbox" NAME="herbal" id="001G0101" VALUE="green2" onClick="testGroup.check(this);"> Herbal (Divisi 1)
  <input TYPE="checkbox" NAME="candy" id="001G0102" VALUE="green" onClick="testGroup.check(this);"> Candy (Divisi 2)
  <input TYPE="checkbox" NAME="beverage" id="001G0103" VALUE="green2" onClick="testGroup.check(this);"> Herbana (Divisi 3)
<input type="checkbox" name="002" id="002" onClick="testGroupM.check(this);"/> Check all Marguna 
  <input TYPE="checkbox" NAME="pilkita" id="002G0201" VALUE="green" onClick="testGroupM.check(this);"> Pilkita
  <input TYPE="checkbox" NAME="otherm" id="002G0202" VALUE="green2" onClick="testGroupM.check(this);"> Others Marguna
<input type="checkbox" name="005" id="005" onClick="testGroupUs.check(this);"/> Check all Ultra Sakti
  <input TYPE="checkbox" NAME="freshcare" id="005G0501" VALUE="green" onClick="testGroupUs.check(this);"> Freshcare
  <input TYPE="checkbox" NAME="hotin" id="005G0502" VALUE="green2" onClick="testGroupUs.check(this);"> Hotin
  <input TYPE="checkbox" NAME="madu" id="005G0503" VALUE="green" onClick="testGroupUs.check(this);"> Madu
  <input TYPE="checkbox" NAME="mywell" id="005G0504" VALUE="green2" onClick="testGroupUs.check(this);"> Mywell
  <input TYPE="checkbox" NAME="tresnojoyo" id="005G0505" VALUE="green" onClick="testGroupUs.check(this);"> Tresnojoyo
  <input TYPE="checkbox" NAME="other" id="005G0506" VALUE="green2" onClick="testGroupUs.check(this);"> Others Ultra Sakti
<input type="checkbox" name="004" id="004" onClick="testGroupJ.check(this);" /> Check all Jaya Agung
<input type="checkbox" name="012" id="012" onClick="testGroupI.check(this);" /> Check all Intra Food
</pre>

<br>

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

            echo '<tr><td colspan=4><h4><strong>'.$namasupp.'</strong></h4></td></tr><tr>';
            
            $count=1;
        }
        


        if($count%4!=0)
        {
        ?>
        <td>
           <div class="checkbox-inline">
           <input name="options[]"  type="CHECKBOX" id="options" class = "<?php echo $value->suppx ?>" value="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod.' ('.$value->kodeprod.')';?>
           </div>
        </td>

        <?php
        }
        else
        {
            ?>
            <td>
                <div class="checkbox-inline">
                    <input name="options[]" class="<?php echo $value->suppx ?>" type="CHECKBOX" id="options" class = "<?php echo $value->supp ?>" value="<?php echo $value->kodeprod ?>"><?php echo $value->namaprod.' ('.$value->kodeprod.')';?>
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