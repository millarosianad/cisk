<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/js/checkboxgroup.js"?>"></script>

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
<pre>
<b>Select Product : </b>
<input type="checkbox" id="cbgroup1_master" name="checkall" onclick="checkUncheckAll(this);"/> ALL PRINCIPAL
<input type="checkbox" name="001" id="001" onClick="testGroup.check(this);"/> Check all Deltomed
  <input TYPE="checkbox" NAME="candy" id="001G0102" VALUE="green" onClick="testGroup.check(this);"> Candy
  <input TYPE="checkbox" NAME="herbal" id="001G0101" VALUE="green2" onClick="testGroup.check(this);"> Herbal
  <input TYPE="checkbox" NAME="beverage" id="001G0103" VALUE="green2" onClick="testGroup.check(this);"> Beverage
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
<hr>
<div class='row'>

  <div class="col-md-12">

    <div class="col-md-2">
      <?php
        echo form_label(" Dari Tahun  : ");
      ?>
      <select name="start_year" class = "form-control">
          <option value=""> -- Dari Tahun -- </option>          
          <option value="2018"> 2018 </option>
          <option value="2019"> 2019 </option>
          <option value="2020"> 2020 </option>
      </select>
    </div>

    <div class="col-md-3">
      <?php
        echo form_label(" Dari Bulan  : ");
      ?>
      <select name="start_month" class = "form-control">
          <option value=""> -- Dari Bulan -- </option>
          <option value="1"> Januari </option>
          <option value="2"> Februari </option>
          <option value="3"> Maret </option>
          <option value="4"> April </option>
          <option value="5"> Mei </option>
          <option value="6"> Juni </option>
          <option value="7"> Juli </option>
          <option value="8"> Agustus </option>
          <option value="9"> September </option>
          <option value="10"> Oktober </option>
          <option value="11"> November </option>
          <option value="12"> Desember </option>
      </select>
    </div>

    <div class="col-md-1">
      &nbsp;
    </div>   

    <div class="col-md-2">
      <?php
        echo form_label(" Sampai Tahun  : ");
      ?>
      <select name="end_year" class = "form-control">
          <option value=""> -- Sampai Tahun -- </option>
          <option value="2018"> 2018 </option>
          <option value="2019"> 2019 </option>
          <option value="2020"> 2020 </option>
      </select>
    </div>

    <div class="col-md-3">
      <?php
        echo form_label(" Sampai Bulan  : ");
      ?>
      <select name="end_month" class = "form-control">
          <option value=""> -- Sampai Bulan -- </option>
          <option value="1"> Januari </option>
          <option value="2"> Februari </option>
          <option value="3"> Maret </option>
          <option value="4"> April </option>
          <option value="5"> Mei </option>
          <option value="6"> Juni </option>
          <option value="7"> Juli </option>
          <option value="8"> Agustus </option>
          <option value="9"> September </option>
          <option value="10"> Oktober </option>
          <option value="11"> November </option>
          <option value="12"> Desember </option>
      </select>
    </div>
  </div>

  <br><br><br><br>

  <div class="col-md-12">
    <div class="col-md-3">
      <?php
        echo form_label("Group By  : ");
      ?>
      <select name="group" class = "form-control">
          <option value="0"> -- Tidak Perlu -- </option>
          <option value="1"> Class Outlet</option>
          <option value="2"> Type Outlet </option>
      </select>
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